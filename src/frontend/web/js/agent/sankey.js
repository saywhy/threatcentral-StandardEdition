function createSankey(chartDomID, data, nowNodeName) {
  updateSankey(chartDomID, data, nowNodeName);
  window.onresize = function() {
    if (this.width != document.documentElement.clientWidth) {
      updateSankey(chartDomID, data, nowNodeName);
      this.width = document.documentElement.clientWidth;
    }
  };
}
function updateSankey(chartDomID, data, nowNodeName) {
  $("#" + chartDomID).html("");
  var nodes = data;
  if (nowNodeName) {
    var nowNode = getNode(nowNodeName);
    var nowProxyNode = getNode(nowNode.inputs[0]);
    var inputs = nowProxyNode.inputs;
  }
  nodes = [];
  function delInvalidNode() {
    for (var index = 0; index < data.length; index++) {
      var node = data[index];
      if (node.output == false || node.inputs.length == 0) {
        if (nowNodeName) {
          if (
            node.name == nowNodeName ||
            inputs.indexOf(node.name) != -1 ||
            node.inputs.indexOf(nowProxyNode.name) != -1
          ) {
            nodes.push(node);
            continue;
          } else {
            continue;
          }
        } else {
          nodes.push(node);
          continue;
        }
      }
      var hasOut = false;
      for (var i = data.length - 1; i >= 0; i--) {
        if (data[i].inputs.indexOf(node.name) != -1) {
          hasOut = true;
          break;
        }
      }
      if (hasOut) {
        if (nowNodeName) {
          if (nowProxyNode.name == node.name) {
            nodes.push(node);
          }
        } else {
          nodes.push(node);
        }
      }
    }
  }
  delInvalidNode();

  var energy = {
    nodes: nodes,
    links: []
  };
  var maxValue = 1;
  var maxLength = 1;

  for (var i = nodes.length - 1; i >= 0; i--) {
    var inputs = nodes[i].inputs;
    maxLength = Math.max(maxLength, nodes[i].length);
    for (var y = 0; y < inputs.length; y++) {
      var nodeName = inputs[y];
      var source = getNode(nodeName);
      if (source) {
        var update_tx = source.statistics["update.tx"];
        if (!update_tx) {
          update_tx = 0;
        }

        var link = {
          source: source.index,
          target: i,
          update_tx: update_tx
        };
        maxValue = Math.max(maxValue, update_tx);
        energy.links.push(link);
      }
    }
  }
  for (var i = energy.links.length - 1; i >= 0; i--) {
    var link = energy.links[i];
    var miniValue = maxValue / 20;
    if (link.update_tx < miniValue) {
      link.value = miniValue;
    } else {
      link.value = link.update_tx;
    }
  }

  function getNode(name) {
    for (var i = nodes.length - 1; i >= 0; i--) {
      node = nodes[i];
      if (node.name == name) {
        node.index = i;
        return node;
      }
    }
    return null;
  }

  function getLengthPercent(length) {
    if (length == 0) {
      length = 1;
    }
    return length / maxLength;
  }

  function getValuePercent(value) {
    if (value == 0) {
      value = 1;
    }
    return value / maxValue;
  }

  d3.sankey = function() {
    var sankey = {},
      nodeWidth = 0,
      nodePadding = 0,
      nodeRadius = 0,
      nodeMiniRadius = 0,
      size = [1, 1],
      nodes = [],
      links = [];

    sankey.nodeWidth = function(_) {
      if (!arguments.length) return nodeWidth;
      nodeWidth = +_;
      return sankey;
    };

    sankey.nodeRadius = function(_) {
      if (!arguments.length) return nodeRadius;
      nodeRadius = +_;
      return sankey;
    };

    sankey.nodeMiniRadius = function(_) {
      if (!arguments.length) return nodeMiniRadius;
      nodeMiniRadius = +_;
      return sankey;
    };

    sankey.nodePadding = function(_) {
      if (!arguments.length) return nodePadding;
      nodePadding = +_;
      return sankey;
    };

    sankey.nodes = function(_) {
      if (!arguments.length) return nodes;
      nodes = _;
      return sankey;
    };

    sankey.links = function(_) {
      if (!arguments.length) return links;
      links = _;
      return sankey;
    };

    sankey.size = function(_) {
      if (!arguments.length) return size;
      size = _;
      return sankey;
    };

    sankey.layout = function(iterations) {
      computeNodeLinks();
      computeNodeValues();
      computeNodeBreadths();
      computeNodeDepths(iterations);
      computeLinkDepths();
      return sankey;
    };

    sankey.relayout = function() {
      computeLinkDepths();
      return sankey;
    };

    sankey.link = function() {
      var curvature = 0.5;

      function link(d) {
        var x0 = d.source.x + d.source.dx,
          x1 = d.target.x,
          xi = d3.interpolateNumber(x0, x1),
          x2 = xi(curvature),
          x3 = xi(1 - curvature),
          y0 = d.source.y + d.source.dy / 2,
          y1 = d.target.y + d.target.dy / 2;
        return (
          "M" +
          x0 +
          "," +
          y0 +
          "C" +
          x2 +
          "," +
          y0 +
          " " +
          x3 +
          "," +
          y1 +
          " " +
          x1 +
          "," +
          y1
        );
      }

      link.curvature = function(_) {
        if (!arguments.length) return curvature;
        curvature = +_;
        return link;
      };

      return link;
    };

    // Populate the sourceLinks and targetLinks for each node.
    // Also, if the source and target are not objects, assume they are indices.
    function computeNodeLinks() {
      nodes.forEach(function(node) {
        node.sourceLinks = [];
        node.targetLinks = [];
      });
      links.forEach(function(link) {
        var source = link.source,
          target = link.target;
        if (typeof source === "number")
          source = link.source = nodes[link.source];
        if (typeof target === "number")
          target = link.target = nodes[link.target];
        source.sourceLinks.push(link);
        target.targetLinks.push(link);
      });
    }

    // Compute the value (size) of each node by summing the associated links.
    function computeNodeValues() {
      nodes.forEach(function(node) {
        node.value = Math.max(
          d3.sum(node.sourceLinks, value),
          d3.sum(node.targetLinks, value)
        );
      });
    }

    // Iteratively assign the breadth (x-position) for each node.
    // Nodes are assigned the maximum breadth of incoming neighbors plus one;
    // nodes with no incoming links are assigned breadth zero, while
    // nodes with no outgoing links are assigned the maximum breadth.
    function computeNodeBreadths() {
      var remainingNodes = nodes,
        nextNodes,
        x = 0;

      while (remainingNodes.length) {
        nextNodes = [];
        remainingNodes.forEach(function(node) {
          node.x = x;
          node.dx = nodeWidth;
          node.sourceLinks.forEach(function(link) {
            nextNodes.push(link.target);
          });
        });
        remainingNodes = nextNodes;
        ++x;
      }

      //
      moveSinksRight(x);
      scaleNodeBreadths((width - nodeWidth) / (x - 1));
    }

    function moveSourcesRight() {
      nodes.forEach(function(node) {
        if (!node.targetLinks.length) {
          node.x =
            d3.min(node.sourceLinks, function(d) {
              return d.target.x;
            }) - 1;
        }
      });
    }

    function moveSinksRight(x) {
      nodes.forEach(function(node) {
        if (!node.sourceLinks.length) {
          node.x = x - 1;
        }
      });
    }

    function scaleNodeBreadths(kx) {
      nodes.forEach(function(node) {
        node.x *= kx;
      });
    }

    function computeNodeDepths(iterations) {
      var nodesByBreadth = d3
        .nest()
        .key(function(d) {
          return d.x;
        })
        .sortKeys(d3.ascending)
        .entries(nodes)
        .map(function(d) {
          return d.values;
        });

      //
      initializeNodeDepth();
      resolveCollisions();
      for (var alpha = 1; iterations > 0; --iterations) {
        relaxRightToLeft((alpha *= 0.99));
        resolveCollisions();
        relaxLeftToRight(alpha);
        resolveCollisions();
      }

      function initializeNodeDepth() {
        var ky = d3.min(nodesByBreadth, function(nodes) {
          return (
            (size[1] - (nodes.length - 1) * nodePadding) / d3.sum(nodes, value)
          );
        });

        nodesByBreadth.forEach(function(nodes) {
          nodes.forEach(function(node, i) {
            node.y = i;
            node.dy = sankey.nodeRadius() * 2;
            // node.y = i;
            // node.dy = node.value * ky;
          });
        });

        links.forEach(function(link) {
          link.dy = link.value * ky;
        });
      }

      function relaxLeftToRight(alpha) {
        nodesByBreadth.forEach(function(nodes, breadth) {
          nodes.forEach(function(node) {
            if (node.targetLinks.length) {
              var y =
                d3.sum(node.targetLinks, weightedSource) /
                d3.sum(node.targetLinks, value);
              node.y += (y - center(node)) * alpha;
            }
          });
        });

        function weightedSource(link) {
          return center(link.source) * link.value;
        }
      }

      function relaxRightToLeft(alpha) {
        nodesByBreadth
          .slice()
          .reverse()
          .forEach(function(nodes) {
            nodes.forEach(function(node) {
              if (node.sourceLinks.length) {
                var y =
                  d3.sum(node.sourceLinks, weightedTarget) /
                  d3.sum(node.sourceLinks, value);
                node.y += (y - center(node)) * alpha;
              }
            });
          });

        function weightedTarget(link) {
          return center(link.target) * link.value;
        }
      }

      function resolveCollisions() {
        nodesByBreadth.forEach(function(nodes) {
          var y0 = 0,
            n = nodes.length;
          if (n > 1) {
            var padding = (size[1] - n * nodeRadius * 2) / (n - 1);
          } else {
            var padding = 0;
            y0 = size[1] / 2 - nodeRadius;
          }
          for (i = 0; i < n; ++i) {
            node = nodes[i];
            node.y = y0 + i * (padding + nodeRadius * 2);
          }
        });
      }

      function ascendingDepth(a, b) {
        return a.y - b.y;
      }
    }

    function computeLinkDepths() {
      nodes.forEach(function(node) {
        node.sourceLinks.sort(ascendingTargetDepth);
        node.targetLinks.sort(ascendingSourceDepth);
      });
      nodes.forEach(function(node) {
        var sy = 0,
          ty = 0;
        node.sourceLinks.forEach(function(link) {
          link.sy = sy;
          sy += link.dy;
        });
        node.targetLinks.forEach(function(link) {
          link.ty = ty;
          ty += link.dy;
        });
      });

      function ascendingSourceDepth(a, b) {
        return a.source.y - b.source.y;
      }

      function ascendingTargetDepth(a, b) {
        return a.target.y - b.target.y;
      }
    }

    function center(node) {
      return node.y + node.dy / 2;
    }

    function value(link) {
      return link.value;
    }

    return sankey;
  };

  function getNodeColor(node) {
    if (node.output == false) {
      //   return '#F1C40F';
      return "#EDC87C";
    } else {
      if (node.inputs.length > 0) {
        return "#A3E87E";
      } else {
        return "#ED8E8C";
      }
    }
  }

  var margin = { top: 30, right: 30, bottom: 30, left: 30 },
    width = $("#" + chartDomID).width() - 5 - margin.left - margin.right,
    height = $("#" + chartDomID).height() - 5 - margin.top - margin.bottom;

  var formatNumber = d3.format(",.0f"),
    format = function(d) {
      return "指标:" + formatNumber(d);
    },
    color = d3.scale.category20();

  var svg = d3
    .select("#" + chartDomID)
    .append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  var sankey = d3
    .sankey()
    .nodeWidth(0)
    .nodePadding(200)
    .nodeMiniRadius(15)
    .nodeRadius(30)
    .size([width, height]);

  var path = sankey.link();

  sankey
    .nodes(energy.nodes)
    .links(energy.links)
    .layout(32);

  var link = svg
    .append("g")
    .selectAll(".link")
    .data(energy.links)
    .enter()
    .append("path")
    .attr("class", "link")
    .attr("d", path)
    .style("stroke-width", function(d) {
      return getValuePercent(d.value) * sankey.nodeMiniRadius() * 2;
    })
    .sort(function(a, b) {
      return b.dy - a.dy;
    });
  link.append("title").text(function(d) {
    return d.source.name + " → " + d.target.name + "\n" + format(d.update_tx);
  });
  var node = svg
    .append("g")
    .selectAll(".node")
    .data(energy.nodes)
    .enter()
    .append("g")
    .attr("class", "node")
    .attr("transform", function(d) {
      return "translate(" + d.x + "," + d.y + ")";
    })
    .call(
      d3.behavior
        .drag()
        .origin(function(d) {
          return d;
        })
        .on("dragstart", function() {
          this.parentNode.appendChild(this);
        })
        .on("drag", dragmove)
    );

  node
    .append("circle")
    .attr("r", function(d) {
      return (
        (sankey.nodeRadius() - sankey.nodeMiniRadius()) *
          getLengthPercent(d.length) +
        sankey.nodeMiniRadius()
      );
    })
    .attr("cy", function(d) {
      return d.dy / 2;
    })
    .attr("cx", sankey.nodeWidth() / 2)
    .attr("fill", "#F1C40F")
    .style("fill", getNodeColor);

  node
    .append("circle")
    .attr("r", function(d) {
      return (
        ((sankey.nodeRadius() - sankey.nodeMiniRadius()) *
          getLengthPercent(d.length) +
          sankey.nodeMiniRadius()) /
        2
      );
    })
    .attr("cy", function(d) {
      return d.dy / 2;
    })
    .attr("cx", sankey.nodeWidth() / 2)
    .attr("fill", "#fff");

  node.append("title").text(function(d) {
    return d.name + "\n" + format(d.length);
  });

  node
    .append("text")
    .attr("x", function(d) {
      return -(
        (sankey.nodeRadius() - sankey.nodeMiniRadius()) *
          getLengthPercent(d.length) +
        sankey.nodeMiniRadius()
      );
    })
    .attr("y", function(d) {
      return (
        d.dy / 2 +
        (sankey.nodeRadius() - sankey.nodeMiniRadius()) *
          getLengthPercent(d.length) +
        sankey.nodeMiniRadius()
      );
    })
    .attr("dy", ".35em")
    .attr("text-anchor", "end")
    .attr("transform", null)
    .text(function(d) {
      return d.name;
    })
    .filter(function(d) {
      return d.x < width / 2;
    })
    .attr("x", function(d) {
      return (
        (sankey.nodeRadius() - sankey.nodeMiniRadius()) *
          getLengthPercent(d.length) +
        sankey.nodeMiniRadius()
      );
    })
    .attr("text-anchor", "start");
  function dragmove(d) {
    d3.select(this).attr(
      "transform",
      "translate(" +
        d.x +
        "," +
        (d.y = Math.max(0, Math.min(height - d.dy, d3.event.y))) +
        ")"
    );
    sankey.relayout();
    link.attr("d", path);
  }
}
