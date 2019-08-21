var myApp = angular.module("myApp", []);
myApp.controller("myCtrl", function($scope, $http, $filter) {
  $scope.init = function() {
    $scope.loading = null;
    $scope.tab = "default";
    $scope.output_nodes = {};
    $scope.nodeAll = {};
    $scope.input_nodes = [];
    $scope.aggregator_nodes = [];
    $scope.default_nodes = [];
    $scope.newGroup = {
      name: "",
      nodes: []
    };
    $scope.newNode = {
      init: function() {
        this.name = "";
        this.type = "md5";
        this.prototype = "stdlib.aggregatorMD5";
        this.inputs = [];
      },
      push2inputs: function(node) {
        var index = this.inputs.indexOf(node.name);
        if (index == -1) {
          this.inputs.push(node.name);
        } else {
          this.inputs.splice(index, 1);
        }
      },
      setType: function(type) {
        var prototypeArr = {
          md5: "stdlib.aggregatorMD5",
          IPv4: "stdlib.aggregatorIPv4Generic",
          domain: "stdlib.aggregatorDomain",
          URL: "stdlib.aggregatorURL"
        };
        this.type = type;
        this.prototype = prototypeArr[type];
        this.inputs = [];
      }
    };
    $scope.newNode.init();
    $scope.nowNode = {
      init: function() {
        this.name = "";
        this.type = "md5";
        this.prototype = "stdlib.aggregatorMD5";
        this.inputs = [];
        this.aggregator_node = null;
      },
      push2inputs: function(node) {
        var index = this.inputs.indexOf(node.name);
        if (index == -1) {
          this.inputs.push(node.name);
        } else {
          this.inputs.splice(index, 1);
        }
      },
      setType: function(type) {
        var prototypeArr = {
          md5: "stdlib.aggregatorMD5",
          IPv4: "stdlib.aggregatorIPv4Generic",
          domain: "stdlib.aggregatorDomain",
          URL: "stdlib.aggregatorURL"
        };
        this.type = type;
        this.prototype = prototypeArr[type];
        this.inputs = [];
      },
      setNode: function(node) {
        var aggregator_node = $scope.nodeAll[node.inputs[0]];
        var prototypeName = $scope.nodeAll[
          aggregator_node.name
        ].properties.prototype.split(".");
        var prototype =
          $scope.prototypes[prototypeName[0]].prototypes[prototypeName[1]];
        console.log($scope.prototypes);

        this.setType(prototype.indicator_types[0]);
        this.name = node.name;
        this.inputs = angular.copy(aggregator_node.properties.inputs);
        this.aggregator_node = aggregator_node;
      }
    };
    $scope.nowNode.init();
    $scope.selectGroupID = [];
    $scope.rootUrl = location.protocol + "//" + location.host;
    $scope.NodeList = [];
    $scope.getPrototypes(function() {
      $scope.getFull(function() {
        $scope.getNodes(function() {
          //   $("#chartAll").height(($("#chartAll").height() * 2) / 5);
          var defaultNodes = angular.copy(
            $scope.input_nodes.concat(
              $scope.aggregator_nodes,
              $scope.default_nodes
            )
          );
          //   console.log(defaultNodes);
          angular.forEach(defaultNodes, function(item) {
            if (item != null) {
              if (item.name.indexOf("hoohoolab") != -1) {
                item.name = item.name.replace(/hoohoolab/g, "Saic");
              }
              if (item.class.indexOf("hoohoolab") != -1) {
                item.class = item.class.replace(/hoohoolab/g, "Saic");
              }
            }
          });

          createSankey("chartAll", defaultNodes);
        });
      });
    });
    $scope.getGroups();
  };
  function setNodeList(nodes) {
    console.log(nodes);

    $scope.NodeList = nodes;
    $scope.default_nodes = [];
    $scope.input_nodes = [];
    $scope.aggregator_nodes = [];
    $scope.output_nodes = {};
    for (var i = $scope.NodeList.length - 1; i >= 0; i--) {
      var node = $scope.NodeList[i];
      //   console.log($scope.nodeAll);
      if (!$scope.nodeAll[node.name]) {
        continue;
      }
      var prototypeName = $scope.nodeAll[node.name].properties.prototype.split(
        "."
      );
      var prototype =
        $scope.prototypes[prototypeName[0]].prototypes[prototypeName[1]];
      node.indicator_types = prototype.indicator_types;
      console.log(node);
      //   console.log(i);
      if (node.output == false) {
        // console.log(node);
        if (node.name.substr(0, 7) == "Default") {
          //   console.log(node);
          node.isDefault = true;
          $scope.default_nodes.push(node);
          //   console.log($scope.default_nodes);
        }
        $scope.output_nodes[node.name] = node;
      }
      if (node.output == true && node.inputs.length == 0) {
        $scope.input_nodes.push(node);
      }
      if (node.output == true && node.inputs.length > 0) {
        $scope.aggregator_nodes.push(node);
      }
    }
    zeroModal.close($scope.loading);
    $scope.loading = null;
    // console.log($scope.default_nodes);
  }
  $scope.showSankey = function(node) {
    var W = $(window).width() * 0.9;
    var H = W / 2;
    zeroModal.show({
      title: "聚合详情：" + node.name,
      content: chart,
      width: W + "px",
      height: H + "px",
      ok: false,
      cancel: false,
      onCleanup: function() {
        hide_box.appendChild(chart);
      }
    });
    console.log(angular.copy($scope.NodeList), node.name);

    createSankey("chart", angular.copy($scope.NodeList), node.name);
  };
  $scope.getPrototypes = function(callback) {
    if ($scope.loading == null) {
      $scope.loading = zeroModal.loading(4);
    }
    $http.get("/proxy/prototype?f=local").then(
      function success(rsp) {
        if (rsp.data.result) {
          for (var k in rsp.data.result) {
            if (k.indexOf("hoohoolab") != -1) {
              //   k = k.replace(/hoohoolab/g, "Saic");
              console.log(rsp.data.result[k].prototypes);
              for (var i in rsp.data.result[k].prototypes) {
                if (
                  rsp.data.result[k].prototypes[i].class.indexOf("hoohoolab") !=
                  -1
                ) {
                  rsp.data.result[k].prototypes[i].class = rsp.data.result[
                    k
                  ].prototypes[i].class.replace(/hoohoolab/g, "Saic");
                }
              }
            }
          }
          rsp.data.result["Saic"] = rsp.data.result["hoohoolab"];
          $scope.prototypes = rsp.data.result;
          console.log($scope.prototypes);
          //   替换
          //   angular.forEach(defaultNodes, function(item) {
          //     if (item.name.indexOf("hoohoolab") != -1) {
          //       item.name = item.name.replace(/hoohoolab/g, "Saic");
          //     }
          //     if (item.class.indexOf("hoohoolab") != -1) {
          //       item.class = item.class.replace(/hoohoolab/g, "Saic");
          //     }
          //   });

          if (callback) {
            callback();
          }
        } else {
          $scope.apiErr(rsp);
        }
      },
      function err(rsp) {
        $scope.apiErr(rsp);
      }
    );
  };
  $scope.setFull = function(data) {
    $scope.info = data;
    for (var i = data.nodes.length - 1; i >= 0; i--) {
      var node = data.nodes[i];
      if (!node) {
        continue;
      }
      node.id = i;
      $scope.nodeAll[node.name] = node;
    }
  };
  $scope.getFull = function(callback) {
    if ($scope.loading == null) {
      $scope.loading = zeroModal.loading(4);
    }
    $http.get("/proxy/config/full").then(
      function success(rsp) {
        if (rsp.data.result) {
          angular.forEach(rsp.data.result.nodes, function(item) {
            if (item != null) {
              if (item.name.indexOf("hoohoolab") != -1) {
                item.name = item.name.replace(/hoohoolab/g, "Saic");
              }
              if (item.properties.prototype.indexOf("hoohoolab") != -1) {
                item.properties.prototype = item.properties.prototype.replace(
                  /hoohoolab/g,
                  "Saic"
                );
              }
              if (item.properties.inputs.length != 0) {
                angular.forEach(item.properties.inputs, function(
                  item_child,
                  index
                ) {
                  if (item_child.indexOf("hoohoolab") != -1) {
                    item.properties.inputs[index] = item_child.replace(
                      /hoohoolab/g,
                      "Saic"
                    );
                  }
                });
              }
            }
          });
          $scope.setFull(rsp.data.result);
          if (callback) {
            callback();
          }
        } else {
          console.log(rsp);
          $scope.apiErr(rsp);
        }
      },
      function err(rsp) {
        $scope.apiErr(rsp);
      }
    );
  };
  $scope.getNodes = function(callback) {
    if ($scope.loading == null) {
      $scope.loading = zeroModal.loading(4);
    }
    $http.get("/proxy/status/cyberhunt").then(
      function success(rsp) {
        if (rsp.data.result) {
          angular.forEach(rsp.data.result, function(item) {
            if (item != null) {
              if (item.name.indexOf("hoohoolab") != -1) {
                item.name = item.name.replace(/hoohoolab/g, "Saic");
              }
              if (item.class.indexOf("hoohoolab") != -1) {
                item.class = item.class.replace(/hoohoolab/g, "Saic");
              }
              if (item.inputs.length != 0) {
                angular.forEach(item.inputs, function(item_child, index) {
                  if (item_child.indexOf("hoohoolab") != -1) {
                    item.inputs[index] = item_child.replace(
                      /hoohoolab/g,
                      "Saic"
                    );
                  }
                });
              }
            }
          });
          console.log(rsp.data.result);
          setNodeList(rsp.data.result);
          if (callback) {
            callback();
          }
        } else {
          $scope.apiErr(rsp);
        }
      },
      function err(rsp) {
        $scope.apiErr(rsp);
      }
    );
  };
  $scope.getGroups = function() {
    if ($scope.loading == null) {
      $scope.loading = zeroModal.loading(4);
    }
    $http.get("/agent/group-list").then(
      function success(rsp) {
        if (rsp.data.status == "success") {
          $scope.groups = rsp.data.data;
          console.log(rsp.data.data);
        }
      },
      function err(rsp) {
        $scope.apiErr(rsp);
      }
    );
  };
  $scope.delGroup = function(index) {
    zeroModal.confirm({
      content: "确定删除这个分组吗？",
      okFn: function() {
        if ($scope.loading == null) {
          $scope.loading = zeroModal.loading(4);
        }
        $http.delete("/agent/group-del?id=" + $scope.groups[index].id).then(
          function success(rsp) {
            if (rsp.data.status == "success") {
              $scope.groups.splice(index, 1);
            }
            zeroModal.close($scope.loading);
            $scope.loading = null;
          },
          function err(rsp) {
            $scope.apiErr(rsp);
          }
        );
      },
      cancelFn: function() {}
    });
  };
  $scope.showSelectTable = function(group) {
    if ($scope.selectGroupID == group.id) {
      $scope.selectGroupID = null;
      $scope.selectNodeList = [];
    } else {
      $scope.selectGroupID = group.id;
      $scope.selectNodeList = angular.copy(group.nodes);
    }
  };
  $scope.selectNode = function(name) {
    var index = $scope.selectNodeList.indexOf(name);
    if (index > -1) {
      $scope.selectNodeList.splice(index, 1);
    } else {
      $scope.selectNodeList.push(name);
    }
  };
  $scope.equals = function(arr1, arr2) {
    return arr1.sort().join() == arr2.sort().join();
  };

  $scope.updateGroup = function(index) {
    var group = $scope.groups[index];
    var post_data = angular.copy(group);
    post_data.nodes = angular.copy($scope.selectNodeList);
    $scope.saveGroup(post_data, function success(rsp) {
      if (rsp.data.status == "success") {
        $scope.groups[index] = angular.copy(post_data);
        $scope.selectGroupID = null;
        $scope.selectNodeList = [];
      }
      zeroModal.close($scope.loading);
      $scope.loading = null;
    });
  };

  $scope.addGroup = function() {
    var post_data = angular.copy($scope.newGroup);
    post_data.nodes = angular.copy($scope.selectNodeList);
    $scope.saveGroup(post_data, function success(rsp) {
      if (rsp.data.status == "success") {
        group = angular.copy(post_data);
        group.id = rsp.data.id;
        group.nodes = [];
        $scope.groups.push(group);
        $scope.newGroup.name = "";
      }
      zeroModal.close($scope.loading);
      $scope.loading = null;
    });
  };

  $scope.delNode = function(node) {
    var item = $scope.nodeAll[node.name];
    var url = "/proxy/config/node/" + item.id + "?r=1&version=" + item.version;
    zeroModal.confirm({
      content: "确定删除这个节点吗？",
      okFn: function() {
        if ($scope.loading == null) {
          $scope.loading = zeroModal.loading(4);
        }
        $http.delete(url).then(
          function success(rsp) {
            if (rsp.data.result) {
              $scope.setFull(rsp.data.result);
              $scope.getNodes();
            } else {
              $scope.apiErr(rsp);
            }
          },
          function err(rsp) {
            $scope.apiErr(rsp);
          }
        );
      },
      cancelFn: function() {}
    });
  };

  $scope.saveGroup = function(post_data, success) {
    if ($scope.loading == null) {
      $scope.loading = zeroModal.loading(4);
    }
    $http.post("/agent/group-save", post_data).then(success, function err(rsp) {
      $scope.apiErr(rsp);
    });
  };

  $scope.showAddNodeBox = function() {
    var W = $(window).width() * 0.9;
    var H = W / 2;
    zeroModal.show({
      title: "添加节点",
      content: addNodeBox,
      width: W + "px",
      height: H + "px",
      ok: true,
      cancel: true,
      okFn: function() {
        return $scope.addNewNode();
      },
      onCleanup: function() {
        hide_box.appendChild(addNodeBox);
      }
    });
  };

  $scope.addNewNode = function() {
    if ($scope.newNode.inputs.length == 0) {
      zeroModal.error("请选择输入节点！");
      return false;
    }
    var aggregator_node = {
      name: "aggregator_" + $scope.newNode.name,
      properties: {
        inputs: angular.copy($scope.newNode.inputs),
        output: true,
        prototype: $scope.newNode.prototype
      },
      version: $scope.info.version
    };
    $scope.addNode(aggregator_node, function() {
      var output_node = {
        name: $scope.newNode.name,
        properties: {
          inputs: [aggregator_node.name],
          output: false,
          prototype: "stdlib.feedHCWithValue"
        },
        version: $scope.info.version
      };
      $scope.addNode(
        output_node,
        function(result) {
          $scope.setFull(result);
          $scope.newNode.init();
          $scope.getNodes();
        },
        true
      );
    });
  };

  $scope.showUpdateNodeBox = function(node) {
    $scope.nowNode.setNode(node);
    var W = $(window).width() * 0.9;
    var H = W / 2;
    zeroModal.show({
      title: $scope.nowNode.name,
      content: updateNodeBox,
      width: W + "px",
      height: H + "px",
      ok: true,
      cancel: true,
      okFn: function() {
        $scope.updateNode();
        return false;
      },
      onCleanup: function() {
        hide_box.appendChild(updateNodeBox);
      }
    });
  };

  $scope.updateNode = function() {
    var aggregator_node = angular.copy($scope.nowNode.aggregator_node);
    aggregator_node.properties.inputs = angular.copy($scope.nowNode.inputs);
    aggregator_node.version = $scope.info.version;
    $scope.addNode(
      aggregator_node,
      function(result) {
        $scope.setFull(result);
        $scope.nowNode.init();
        $scope.getNodes();
      },
      true
    );
  };

  $scope.addNode = function(node, callback, reload) {
    var url = "/proxy/config/node";
    if (reload) {
      url += "?r=1";
    }
    if ($scope.loading == null) {
      $scope.loading = zeroModal.loading(4);
    }
    $http.post(url, node).then(
      function success(rsp) {
        if (rsp.data.result) {
          callback(rsp.data.result);
        } else {
          $scope.apiErr(rsp);
        }
      },
      function err(rsp) {
        $scope.apiErr(rsp);
      }
    );
  };

  $scope.apiErr = function(rsp) {
    console.log(rsp);
    zeroModal.close($scope.loading);
    $scope.loading = null;
  };

  $scope.init();
});
