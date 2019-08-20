/*Copyright (c) 2013-2016, Rob Schmuecker
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice,
  this list of conditions and the following disclaimer in the documentation
  and/or other materials provided with the distribution.

* The name Rob Schmuecker may not be used to endorse or promote products
  derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL MICHAEL BOSTOCK BE LIABLE FOR ANY DIRECT,
INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY
OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.*/

var alert_data = alertData;

// var EXAll = alert_data.AlertFileList.concat(
//         alert_data.AlertIPList,
//         alert_data.AlertURLList,
//         alert_data.ExceptionAlertList,
//         alert_data.HitRegulationList
//     );
// var EXAll = alert_data.HitRegulationList;

// var errorEventIDList = [];
// for (var i = EXAll.length - 1; i >= 0; i--) {
//     if(EXAll[i].HitEventList)
//     {
//         errorEventIDList = errorEventIDList.concat(EXAll[i].HitEventList);
//     }else if(EXAll[i].EventList){
//         errorEventIDList = errorEventIDList.concat(EXAll[i].EventList);
//     }
// }
var OBJAll = alert_data.AlertFileList.concat(
        alert_data.AlertIPList,
        alert_data.AlertURLList
    );
var errorFilePath = [];
var errorIP = [];
var errorURL = [];
for (var i = OBJAll.length - 1; i >= 0; i--) {
    var item = OBJAll[i];
    if(item.FilePath){
        errorFilePath.push(item.FilePath);
    }
    if(item.IP){
        errorIP.push(item.IP);
    }
    if(item.URL){
        errorURL.push(item.URL);
    }
}


console.log("AlertID:"+alert_data.AlertID);
console.log(alert_data.HitRegulationList);
console.log(alert_data.EventList);


var objList = {};

var rootObj = alert_data.EventList[0].SrcObj;

var actionList = [];
var tIDList = [];
var rIDList = [];

function isWhite(obj){
    var FilePath = obj.FilePath ? obj.FilePath : (obj.ImagePath ? obj.ImagePath : '');
    var Signer = obj.Signer ? obj.Signer : '';
    FilePathList = ['c:\\windows\\explorer.exe'];

    if(Signer == 'Microsoft Windows'){
        return true;
    }
    if(FilePathList.indexOf(FilePath) != -1){
        return true;
    }
    return false;
}

function readEvent(Event){
    EventID = Event.EventID;

    for (var i = 0; i < alert_data.HitRegulationList.length; i++) {
        var reg = alert_data.HitRegulationList[i];
        if(reg.HitEventList.indexOf(EventID) != -1){
            if(!Event.SrcObj.HitRegulationList){
                Event.SrcObj.HitRegulationList = {};
            }
            if(!Event.SrcObj.HitRegulationList[reg.RegID]){
                Event.SrcObj.HitRegulationList[reg.RegID] = reg;
                Event.SrcObj.error = true;
                if(!isWhite(Event.SrcObj)){
                    Event.SrcObj.kill = true;
                }
            }
            if(!isWhite(Event.TarObj)){
                Event.TarObj.kill = true;
            }
        }
    }
    hasError(Event.TarObj);
    if((Event.TarObj.ipError||Event.TarObj.urlError) && !isWhite(Event.SrcObj)){
        Event.SrcObj.kill = true;
    }
    hasError(Event.SrcObj);
}
function hasError(obj){
    if(errorFilePath.indexOf(obj.FilePath) != -1){
        obj.error = true;
        obj.fileError = true;
    }
    if(errorFilePath.indexOf(obj.ImagePath) != -1){
        obj.error = true;
        obj.fileError = true;
        if(!isWhite(obj)){
            obj.kill = true;
        }
    }
    if(errorIP.indexOf(obj.RemoteIP) != -1){
        obj.error = true;
        obj.ipError = true;
    }
    if(errorURL.indexOf(obj.DomainName) != -1){
        obj.error = true;
        obj.urlError = true;
    }
    return false;
}

var childrenKey = '_children';//不展开
//var childrenKey = 'children'//全部展开

for (var i in alert_data.EventList) {

    var Event = alert_data.EventList[i];
    var sID = Event.SrcObj.id;
    var tID = Event.TarObj.id;

    var action = sID+'_'+tID;
    
    if(actionList.indexOf(action) != -1){
        continue;
    }
    actionList.push(action);

    if(!objList[sID])
    {
        objList[sID] =  Event.SrcObj;
    }
    if(!objList[tID])
    {
        objList[tID] =  Event.TarObj;
    }

    readEvent(Event);

    if(!objList[sID][childrenKey]){
        objList[sID][childrenKey] = [];
    }
    rIDList.push(Event.SrcObj.id);
    if(tIDList.indexOf(tID) != -1 || rIDList.indexOf(tID) != -1){
        var obj = {};
        for (var key in objList[tID]) {
            var value = objList[tID][key];
            if(key != childrenKey && key != 'id'){
                obj[key] = value;
            }

        }
        obj.id = uuid();
        hasError(obj);
        objList[sID][childrenKey].push(obj);
        tIDList.push(obj.id);
    }else{
        objList[sID][childrenKey].push(objList[tID]);
        tIDList.push(tID);
    }
}



var sysTree = {
    name:"连接节点"
};
sysTree[childrenKey] = [];
var objTree = {};
for (var key in objList) {
    var obj = objList[key];
    if(tIDList.indexOf(obj.id) == -1 )
    {
        sysTree[childrenKey].push(obj);
    }
}


if(sysTree[childrenKey].length == 0)
{
    objTree = rootObj;
}else if(sysTree[childrenKey].length == 1){
    objTree = sysTree[childrenKey][0];
}
else{
    objTree = sysTree;
}


function openTree(node,level,childrenMax) {
    if(level > 0){
        if(node._children){
            if(childrenMax && childrenMax < node._children.length){
                return;
            }
            node.children = node._children;
            node._children = null;
        }
        if(node.children){
            for (var i = node.children.length - 1; i >= 0; i--) {
                var cnode = node.children[i];
                openTree(cnode,level-1,childrenMax);
            }
        }
    }
}

openTree(objTree,3,15);

function uuid() {
    var s = [];
    var hexDigits = "0123456789abcdef";
    for (var i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = "4";  // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";
 
    var uuid = s.join("");
    return uuid;
}


var rootScope = null;

// Get JSON data
// treeJSON = d3.json("/plugins/dndTree/flare.json", function(error, treeData) {
function createTree(treeData) {

    // Calculate total nodes, max label length
    var totalNodes = 0;
    var maxLabelLength = 0;
    // variables for drag/drop
    var selectedNode = null;
    var draggingNode = null;
    // panning variables
    var panSpeed = 200;
    var panBoundary = 20; // Within 20px from edges will pan when dragging.
    // Misc. variables
    var i = 0;
    var duration = 750;
    var root;

    // size of the diagram
    // var viewerWidth = $(document).width();
    // var viewerHeight = $(document).height();

    var viewerWidth = document.getElementById("tree-container").offsetWidth;
    var viewerHeight =  viewerWidth*5/12;

    var tree = d3.layout.tree()
        .size([viewerHeight, viewerWidth]);

    // define a d3 diagonal projection for use by the node paths later on.
    var diagonal = d3.svg.diagonal()
        .projection(function(d) {
            return [d.y, d.x];
        });

    // A recursive helper function for performing some setup by walking through all nodes

    function visit(parent, visitFn, childrenFn) {
        if (!parent) return;

        visitFn(parent);

        var children = childrenFn(parent);
        if (children) {
            var count = children.length;
            for (var i = 0; i < count; i++) {
                visit(children[i], visitFn, childrenFn);
            }
        }
    }

    // Call visit function to establish maxLabelLength
    visit(treeData, function(d) {
        totalNodes++;
        maxLabelLength = Math.max(d.name.length, maxLabelLength);

    }, function(d) {

        var children = d.children && d.children.length > 0 ? d.children : null;
        var _children = d._children && d._children.length > 0 ? d._children : null;

        return children ? children : _children;
    });


    // sort the tree according to the node names

    function sortTree() {
        tree.sort(function(a, b) {
            return b.name.toLowerCase() < a.name.toLowerCase() ? 1 : -1;
        });
    }
    // Sort the tree initially incase the JSON isn't in a sorted order.
    // sortTree();

    // TODO: Pan function, can be better implemented.

    function pan(domNode, direction) {
        var speed = panSpeed;
        if (panTimer) {
            clearTimeout(panTimer);
            translateCoords = d3.transform(svgGroup.attr("transform"));
            if (direction == 'left' || direction == 'right') {
                translateX = direction == 'left' ? translateCoords.translate[0] + speed : translateCoords.translate[0] - speed;
                translateY = translateCoords.translate[1];
            } else if (direction == 'up' || direction == 'down') {
                translateX = translateCoords.translate[0];
                translateY = direction == 'up' ? translateCoords.translate[1] + speed : translateCoords.translate[1] - speed;
            }
            scaleX = translateCoords.scale[0];
            scaleY = translateCoords.scale[1];
            scale = zoomListener.scale();
            svgGroup.transition().attr("transform", "translate(" + translateX + "," + translateY + ")scale(" + scale + ")");
            d3.select(domNode).select('g.node').attr("transform", "translate(" + translateX + "," + translateY + ")");
            zoomListener.scale(zoomListener.scale());
            zoomListener.translate([translateX, translateY]);
            panTimer = setTimeout(function() {
                pan(domNode, speed, direction);
            }, 50);
        }
    }

    // Define the zoom function for the zoomable tree

    function zoom() {
        svgGroup.attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")");
    }


    // define the zoomListener which calls the zoom function on the "zoom" event constrained within the scaleExtents
    var zoomListener = d3.behavior.zoom().scaleExtent([0.1, 3]).on("zoom", zoom);

    function initiateDrag(d, domNode) {
        draggingNode = d;
        d3.select(domNode).select('.ghostCircle').attr('pointer-events', 'none');
        d3.selectAll('.ghostCircle').attr('class', 'ghostCircle show');
        d3.select(domNode).attr('class', 'node activeDrag');

        svgGroup.selectAll("g.node").sort(function(a, b) { // select the parent and sort the path's
            if (a.id != draggingNode.id) return 1; // a is not the hovered element, send "a" to the back
            else return -1; // a is the hovered element, bring "a" to the front
        });
        // if nodes has children, remove the links and nodes
        if (nodes.length > 1) {
            // remove link paths
            links = tree.links(nodes);
            nodePaths = svgGroup.selectAll("path.link")
                .data(links, function(d) {
                    return d.target.id;
                }).remove();
            // remove child nodes
            nodesExit = svgGroup.selectAll("g.node")
                .data(nodes, function(d) {
                    return d.id;
                }).filter(function(d, i) {
                    if (d.id == draggingNode.id) {
                        return false;
                    }
                    return true;
                }).remove();
        }

        // remove parent link
        parentLink = tree.links(tree.nodes(draggingNode.parent));
        svgGroup.selectAll('path.link').filter(function(d, i) {
            if (d.target.id == draggingNode.id) {
                return true;
            }
            return false;
        }).remove();

        dragStarted = null;
    }

    function initiateDragOne(d, domNode) {

        // tar_FileSize.innerHTML = d.FileSize;
        // tar_ImagePath.innerHTML = (d.ImagePath ? d.ImagePath : d.FilePath);
        // tar_MD5.innerHTML = d.MD5;
        // tar_ObjType.innerHTML = d.ObjType;
        // tar_PID.innerHTML = d.PID;
        // tar_ProcessName.innerHTML = d.ProcessName;
        // tar_UserName.innerHTML = d.UserName;
        // tar_CommandLine.innerHTML = d.CommandLine;
        // tar_Signer.innerHTML = d.Signer;
        console.log(d);
        if(rootScope){
            rootScope.detail = d;
            rootScope.$apply();
        }
        if($("#event_detail").is(":hidden")){
            $("#event_detail").slideToggle();
            $("#event_detail .box-body").height(viewerHeight-30);
            $("#event_detail .box-body").css("overflow-y","auto");
        }
        
        // if(d!=root){
        //     src_FileSize.innerHTML = d.parent.FileSize;
        //     src_ImagePath.innerHTML = d.parent.ImagePath;
        //     src_MD5.innerHTML = d.parent.MD5;
        //     src_ObjType.innerHTML = d.parent.ObjType;
        //     src_PID.innerHTML = d.parent.PID;
        //     src_ProcessName.innerHTML = d.parent.ProcessName;
        //     src_UserName.innerHTML = d.parent.UserName;
        // }else{
        //     var tds = src_obj.getElementsByTagName("td");
        //     for (var i = tds.length - 1; i >= 0; i--) {
        //         tds[i].innerHTML = "无";
        //     }
        // }
        
        d3.selectAll('.ghostCircle').attr('class', 'ghostCircle');
        d3.select(domNode).selectAll('.ghostCircle').attr('class', 'ghostCircle show');
    }

    // define the baseSvg, attaching a class for styling and the zoomListener
    var baseSvg = d3.select("#tree-container").append("svg")
        .attr("width", viewerWidth)
        .attr("height", viewerHeight)
        .attr("class", "overlay")
        .call(zoomListener);

        // baseSvg.on("mousedown.zoom", null);
        // baseSvg.on("mousemove.zoom", null);
        baseSvg.on("dblclick.zoom", null);
        // baseSvg.on("touchstart.zoom", null);
        baseSvg.on("wheel.zoom", null);
        baseSvg.on("mousewheel.zoom", null);
        baseSvg.on("MozMousePixelScroll.zoom", null);

    
    // Define the drag listeners for drag/drop behaviour of nodes.
    dragListener = d3.behavior.drag()
    //     .on("dragstart", function(d) {
    //         if (d == root) {
    //             return;
    //         }
    //         dragStarted = true;
    //         nodes = tree.nodes(d);
    //         d3.event.sourceEvent.stopPropagation();
    //         // it's important that we suppress the mouseover event on the node being dragged. Otherwise it will absorb the mouseover event and the underlying node will not detect it d3.select(this).attr('pointer-events', 'none');
    //     })
    //     .on("drag", function(d) {

    //         if (d == root) {
    //             return;
    //         }
    //         if (dragStarted) {
    //             domNode = this;
    //             initiateDrag(d, domNode);
    //         }

    //         // get coords of mouseEvent relative to svg container to allow for panning
    //         relCoords = d3.mouse($('svg').get(0));
    //         if (relCoords[0] < panBoundary) {
    //             panTimer = true;
    //             pan(this, 'left');
    //         } else if (relCoords[0] > ($('svg').width() - panBoundary)) {

    //             panTimer = true;
    //             pan(this, 'right');
    //         } else if (relCoords[1] < panBoundary) {
    //             panTimer = true;
    //             pan(this, 'up');
    //         } else if (relCoords[1] > ($('svg').height() - panBoundary)) {
    //             panTimer = true;
    //             pan(this, 'down');
    //         } else {
    //             try {
    //                 clearTimeout(panTimer);
    //             } catch (e) {

    //             }
    //         }

    //         d.x0 += d3.event.dy;
    //         d.y0 += d3.event.dx;
    //         var node = d3.select(this);
    //         node.attr("transform", "translate(" + d.y0 + "," + d.x0 + ")");
    //         updateTempConnector();
    //     }).on("dragend", function(d) {
    //         if (d == root) {
    //             return;
    //         }
    //         domNode = this;
    //         if (selectedNode) {
    //             // now remove the element from the parent, and insert it into the new elements children
    //             var index = draggingNode.parent.children.indexOf(draggingNode);
    //             if (index > -1) {
    //                 draggingNode.parent.children.splice(index, 1);
    //             }
    //             if (typeof selectedNode.children !== 'undefined' || typeof selectedNode._children !== 'undefined') {
    //                 if (typeof selectedNode.children !== 'undefined') {
    //                     selectedNode.children.push(draggingNode);
    //                 } else {
    //                     selectedNode._children.push(draggingNode);
    //                 }
    //             } else {
    //                 selectedNode.children = [];
    //                 selectedNode.children.push(draggingNode);
    //             }
    //             // Make sure that the node being added to is expanded so user can see added node is correctly moved
    //             expand(selectedNode);
    //             sortTree();
    //             endDrag();
    //         } else {
    //             endDrag();
    //         }
    //     });

    // function endDrag() {
    //     selectedNode = null;
    //     d3.selectAll('.ghostCircle').attr('class', 'ghostCircle');
    //     d3.select(domNode).attr('class', 'node');
    //     // now restore the mouseover event or we won't be able to drag a 2nd time
    //     d3.select(domNode).select('.ghostCircle').attr('pointer-events', '');
    //     updateTempConnector();
    //     if (draggingNode !== null) {
    //         update(root);
    //         centerNode(draggingNode);
    //         draggingNode = null;
    //     }
    // }

    // Helper functions for collapsing and expanding nodes.

    function collapse(d) {
        if (d.children) {
            d._children = d.children;
            d._children.forEach(collapse);
            d.children = null;
        }
    }

    function expand(d) {
        if (d._children) {
            d.children = d._children;
            d.children.forEach(expand);
            d._children = null;
        }
    }

    var overCircle = function(d) {
        selectedNode = d;
        updateTempConnector();
    };
    var outCircle = function(d) {
        selectedNode = null;
        updateTempConnector();
    };

    // Function to update the temporary connector indicating dragging affiliation
    var updateTempConnector = function() {
        var data = [];
        if (draggingNode !== null && selectedNode !== null) {
            // have to flip the source coordinates since we did this for the existing connectors on the original tree
            data = [{
                source: {
                    x: selectedNode.y0,
                    y: selectedNode.x0
                },
                target: {
                    x: draggingNode.y0,
                    y: draggingNode.x0
                }
            }];
        }
        var link = svgGroup.selectAll(".templink").data(data);

        link.enter().append("path")
            .attr("class", "templink")
            .attr("d", d3.svg.diagonal())
            .attr('pointer-events', 'none');

        link.attr("d", d3.svg.diagonal());

        link.exit().remove();
    };

    // Function to center node when clicked/dropped so node doesn't get lost when collapsing/moving with large amount of children.

    function plus(){
        zoomListener.scale(zoomListener.scale()*1.5);
        centerNode();
    }
    function minus(){
        zoomListener.scale(zoomListener.scale()/1.5);
        centerNode();
    }
    function reset(){
        zoomListener.scale(1);
        centerNode();
    }

    var center_node = {
        x0:100,
        y0:0
    };

    function centerNode(source) {
        if(!source)
        {
            source = center_node
        }else
        {
            center_node = source;
        }
        scale = zoomListener.scale();
        x = -source.y0;
        y = -source.x0;
        x = x * scale + viewerWidth / 2;
        y = y * scale + viewerHeight / 2;
        d3.select('g').transition()
            .duration(duration)
            .attr("transform", "translate(" + x + "," + y + ")scale(" + scale + ")");
        zoomListener.scale(scale);
        zoomListener.translate([x, y]);
    }

    // Toggle children function

    function toggleChildren(d) {
        if (d.children) {
            d._children = d.children;
            d.children = null;
        } else if (d._children) {
            d.children = d._children;
            d._children = null;
        }
        return d;
    }

    // Toggle children on click.

    function click(d) {
        domNode = this;
        initiateDragOne(d, domNode);

        if (d3.event.defaultPrevented) return; // click suppressed
        d = toggleChildren(d);
        update(d);
        center_node = d;
        // centerNode(d);
    }

    function update(source) {
        // Compute the new height, function counts total children of root node and sets tree height accordingly.
        // This prevents the layout looking squashed when new nodes are made visible or looking sparse when nodes are removed
        // This makes the layout more consistent.
        var levelWidth = [1];
        var childCount = function(level, n) {

            if (n.children && n.children.length > 0) {
                if (levelWidth.length <= level + 1) levelWidth.push(0);

                levelWidth[level + 1] += n.children.length;
                n.children.forEach(function(d) {
                    childCount(level + 1, d);
                });
            }
        };
        childCount(0, root);
        var newHeight = d3.max(levelWidth) * 25; // 25 pixels per line  
        tree = tree.size([newHeight, viewerWidth]);

        // Compute the new tree layout.
        var nodes = tree.nodes(root).reverse(),
            links = tree.links(nodes);

        // Set widths between levels based on maxLabelLength.
        nodes.forEach(function(d) {
            d.y = (d.depth * (maxLabelLength * 10)); //maxLabelLength * 10px
            // alternatively to keep a fixed scale one can set a fixed depth per level
            // Normalize for fixed-depth by commenting out below line
            // d.y = (d.depth * 500); //500px per level.
        });

        // Update the nodes…
        node = svgGroup.selectAll("g.node")
            .data(nodes, function(d) {
                return d.id || (d.id = ++i);
            });

        // Enter any new nodes at the parent's previous position.
        var nodeEnter = node.enter().append("g")
            .call(dragListener)
            .attr("class", "node")
            .attr("transform", function(d) {
                return "translate(" + source.y0 + "," + source.x0 + ")";
            })
            .on('click', click);

        nodeEnter.append("circle")
            .attr('class', 'nodeCircle')
            .attr("r", 0)
            .style("fill", function(d) {
                return d._children ? "lightsteelblue" : "#fff";
            });

        nodeEnter.append("text")
            .attr("x", function(d) {
                return d.children || d._children ? -10 : 10;
            })
            .attr("dy", ".35em")
            .attr('class', 'nodeText')
            .attr("text-anchor", function(d) {
                return d.children || d._children ? "end" : "start";
            })
            .text(function(d) {
                return d.name;
            })
            .style("fill-opacity", 0);

        // phantom node to give us mouseover in a radius around it
        nodeEnter.append("circle")
            .attr('class', 'ghostCircle')
            .attr("r", 15)
            .attr("opacity", 0.2) // change this to zero to hide the target area
            .style("fill", "#0085ff")
            .attr('pointer-events', 'mouseover')
            .on("mouseover", function(node) {
                overCircle(node);
            })
            .on("mouseout", function(node) {
                outCircle(node);
            });

        // Update the text to reflect whether node has children or not.
        node.select('text')
            .attr("x", function(d) {
                return d.children || d._children ? -10 : 10;
            })
            .attr("text-anchor", function(d) {
                return d.children || d._children ? "end" : "start";
            })
            .text(function(d) {
                return d.name;
            });

        // Change the circle fill depending on whether it has children and is collapsed
        node.select("circle.nodeCircle")
            .attr("r", 4.5)
            .style("fill", function(d) {
                if(d.error)
                {
                    return "rgb(255, 104, 104)";
                }
                return d._children ? "lightsteelblue" : "#fff";
            });

        // Transition nodes to their new position.
        var nodeUpdate = node.transition()
            .duration(duration)
            .attr("transform", function(d) {
                return "translate(" + d.y + "," + d.x + ")";
            });

        // Fade the text in
        nodeUpdate.select("text")
            .style("fill-opacity", 1);

        // Transition exiting nodes to the parent's new position.
        var nodeExit = node.exit().transition()
            .duration(duration)
            .attr("transform", function(d) {
                return "translate(" + source.y + "," + source.x + ")";
            })
            .remove();

        nodeExit.select("circle")
            .attr("r", 0);

        nodeExit.select("text")
            .style("fill-opacity", 0);

        // Update the links…
        var link = svgGroup.selectAll("path.link")
            .data(links, function(d) {
                return d.target.id;
            });

        // Enter any new links at the parent's previous position.
        link.enter().insert("path", "g")
            .attr("class", "link")
            .attr("d", function(d) {
                var o = {
                    x: source.x0,
                    y: source.y0
                };
                return diagonal({
                    source: o,
                    target: o
                });
            });

        // Transition links to their new position.
        link.transition()
            .duration(duration)
            .attr("d", diagonal);

        // Transition exiting nodes to the parent's new position.
        link.exit().transition()
            .duration(duration)
            .attr("d", function(d) {
                var o = {
                    x: source.x,
                    y: source.y
                };
                return diagonal({
                    source: o,
                    target: o
                });
            })
            .remove();

        // Stash the old positions for transition.
        nodes.forEach(function(d) {
            d.x0 = d.x;
            d.y0 = d.y;
        });
    }

    // Append a group which holds all nodes and which the zoom Listener can act upon.
    var svgGroup = baseSvg.append("g");

    // Define the root
    root = treeData;
    root.x0 = viewerHeight / 2;
    root.y0 = 0;

    // Layout the tree initially and center on the root node.
    update(root);
    // centerNode(root);


    x = viewerWidth / 2;
    y = viewerHeight / 2;
    d3.select('g').transition()
            .duration(duration)
            .attr("transform", "translate(" + 100 + "," + 0 + ")scale(" + 1 + ")");
        zoomListener.scale(1);
        zoomListener.translate([100, 0]);
    

    
    // centerNode(root.children[0]);
    // centerNode(root.children[0].children[0]);



    var size_int = 1;
    function updateTree(){
        var node = root.children[0].children[0]



        if(!node.children)
        {
            node.children = [];
        }

        var new_node = {
            name:"gaolei_"+size_int,
            size:size_int*100
        };
        node.children.push(new_node);
        size_int++;
        update(node);
        centerNode(new_node);
    }

    $("#proTree button").click(function(){
        switch (this.id) {
            case "plus":
                plus();
                break;
            case "minus":
                minus();
                break;
            case "reset":
                reset();
                break;
        }

    });
    $("#event_detail .fa-close").click(function(){
        if(!$("#event_detail").is(":hidden")){
            $("#event_detail").slideToggle();
        }
    });

}

window.onload = function()
{
    createTree(objTree);
}

var myApp = angular.module('myApp', []);
myApp.controller('myCtrl', function($scope, $http,$filter) {
  rootScope = $scope;
  $scope.EX = EX;
  $scope.kill = function(obj){
    zeroModal.confirm({
        content: '您确定要结束次进程吗？',
        contentDetail: '',
        okFn: function() {
            var rqs_data = {
                ImagePath:obj.ImagePath,
                PID:obj.PID,
                SensorID:alert_data.SensorID
            };
            $http.post('/alert/kill',rqs_data).then(function  (rsp){
                if(rsp.data.status == 'success'){
                   zeroModal.success('命令发送成功!');
                }else{
                    zeroModal.error('命令发送失败!');
                }
                zeroModal.close(loading);
            },function err(rsp){
                zeroModal.error('命令发送失败!');
                zeroModal.close(loading);
            });
        },
        cancelFn: function() {
        }
    });
        
  }
  $scope.updata = function(type){
    var rqs_data = {
      type:type,
      page:1,
      List:[EX]
    };
    var statusList={
      'delWhiteBeh':2,
      'setWhiteBeh':4,
      'delWhite':2,
      'setWhite':3
    }
    var loading = zeroModal.loading(4);
    $http.post('/alert/update',rqs_data).then(function  (rsp){
        if(rsp.data.status == 'success'){
          $scope.EX.status = statusList[type];
        }
        zeroModal.close(loading);
    },function err(rsp){
        zeroModal.close(loading);
    });
  }
});










