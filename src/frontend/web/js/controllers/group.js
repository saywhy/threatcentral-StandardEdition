var rootScope;
var myApp = angular.module('myApp', []);
myApp.controller('group', function($scope, $http,$filter) {
  rootScope = $scope;

  $scope.del = function(){
    var node = $scope.nowGroup;
    hasNodes = '';
    if(node.nodes){
      hasNodes = '删除这个组将会同时删除下级组，<br>';
    }
    zeroModal.confirm({
      content: '操作提示',
      contentDetail: hasNodes+'是否确认删除这个组？',
      okFn: function() {
        var idList = getIdList(node);
        $scope.remove(idList);
      },
      cancelFn: function() {
      }
    });
  }
  $scope.add = function(type){
    var pid = 0;
    if(type == 'children'){
      pid = $scope.nowGroup.id;
    }
    $scope.nowGroup = {};
    $scope.nowGroup['id'] = 'new';
    $scope.nowGroup['pid'] = pid;
    $scope.nowGroup['type'] = '0';
    $scope.nowGroup['text'] = '';
    $scope.nowGroup['FilterList'] = [];
    addGroupTree($scope.nowGroup);
    updateTree();
    selectNode($scope.nowGroup.id);
  }

  $scope.save = function(){
    var loading = zeroModal.loading(4);

    var rqs_data = $scope.nowGroup;
    $http.post("/group/update",rqs_data).then(function success(rsp){
      zeroModal.close(loading);
      if(rsp.data.status == 'success')
      {
        removeGroupTree([$scope.nowGroup.id]);
        $scope.nowGroup = rsp.data.group;
        addGroupTree($scope.nowGroup);
        updateGroupTree($scope.nowGroup);
        updateTree();
        selectNode($scope.nowGroup.id);
        zeroModal.success('保存成功!');
      }else{
        zeroModal.error('保存失败!');
      }
    },function err(rsp){
      zeroModal.close(loading);
      zeroModal.error('保存失败!');
    });
  }

  $scope.remove = function(idList){
    var loading = zeroModal.loading(4);
    var rqs_data = {idList:idList};
    $http.post("/group/remove",rqs_data).then(function success(rsp){
      zeroModal.close(loading);
      if(rsp.data.status == 'success')
      {
        removeGroupTree(idList);
        updateTree();
        $scope.nowGroup = null;
        zeroModal.success('删除成功!');
      }else{
        zeroModal.error('删除失败!');
      }
    },function err(rsp){
      zeroModal.close(loading);
      zeroModal.error('删除失败!');
    });
  }
  $scope.selectNode = function(node){
    $scope.nowGroup = node;
    $scope.nowUsers = null;
    $http.post("/group/users",node).then(function success(rsp){
      if(rsp.data.status == 'success')
      {
        updataTable(rsp.data.list,'userList');
      }
    },function err(rsp){
    });
  }
  $scope.removeUserForGroup = function(uid){
    var rqs_data = {
      uid:uid,
      gid:$scope.nowGroup.id
    };
    $http.post("/group/remove-user",rqs_data).then(function success(rsp){
      if(rsp.data.status == 'success')
      {
        updataTable(rsp.data.list,'userList');
      }
    },function err(rsp){
    });
  }
});

var Groups = {}

var GroupTree = [];


for (var i = 0; i < GroupList.length; i++) {
  var group = GroupList[i];
  Groups[group.id] = group;
  group.type = ''+group.type;
  if(group.pid == 0)
  {
    GroupTree.push(group);
  }else{
    if(!Groups[group.pid].nodes){
      Groups[group.pid].nodes = [];
    }
    Groups[group.pid].nodes.push(group);
  }
}

var treeDom;
window.onload = function(){
  updateTree()
};
var Nodes;
function updateTree(){
  if(treeDom){
    treeDom.treeview('remove');
    treeDom = null;
  }
  treeDom = $('#groupTree').treeview({
    showBorder: false,
    color: "#428bca",
    data: GroupTree,
    onNodeSelected: function(event, node) {
      rootScope.selectNode(node);
    },
    onNodeUnselected: function (event, node) {
      rootScope.nowGroup = null;
      rootScope.$apply();
    }
  });
  var nodes = treeDom.treeview('getUnselected');
  Nodes = {};
  for (var i = nodes.length - 1; i >= 0; i--) {
    var node = nodes[i];
    Nodes[node.id] = node;
  }
  // console.log(Nodes)
}

function selectNode(id){
  expandNode(id);
  var nodeId = Nodes[id].nodeId;
  treeDom.treeview('selectNode', [ nodeId, { silent: true } ]);
}

function expandNode(id){
  var parentId = Nodes[id].parentId
  if(parentId){
    var parent = treeDom.treeview('getNode', parentId);
    expandNode(parent.id);
    treeDom.treeview('expandNode', [ parentId, { levels: 1, silent: true } ]);
  }
  
}

var search = function(e) {
  var pattern = $('#input-search').val();
  var options = {
    ignoreCase: true,
    exactMatch: true,
    revealResults: true
  };
  var results = $searchableTree.treeview('search', [ pattern, options ]);

  // var output = '<p>' + results.length + ' matches found</p>';
  // $.each(results, function (index, result) {
  //   output += '<p>- ' + result.text + '</p>';
  // });
  // $('#search-output').html(output);
}

function getIdList(node){
  var idList = [];
  idList.push(node.id);
  if(node.nodes){
    for (var i = node.nodes.length - 1; i >= 0; i--) {
      idList = idList.concat(getIdList(node.nodes[i]));
    }
  }
  return idList;
}

function addGroupTree(newNode,node){
  if(!node){
    node = {nodes:GroupTree,id:0};
  }
  if(newNode.pid == node.id){
    if(!node.nodes){
      node.nodes = [];
    }
    node.nodes.push(newNode);
    return;
  }else{
    if(node.nodes){
      for (var i = node.nodes.length - 1; i >= 0; i--) {
        addGroupTree(newNode,node.nodes[i]);
      }
    }
    
  }
}

function removeGroupTree(idList,node){
  if(!node){
    node = {nodes:GroupTree};
  }
  if(node.nodes){
    for (var i = node.nodes.length - 1; i >= 0; i--) {
      if(idList.indexOf(node.nodes[i].id) != -1){
        node.nodes.splice(i,1);
      }else{
        removeGroupTree(idList,node.nodes[i]);
      }
    }
    if(node.nodes.length == 0){
      delete node.nodes;
    }
  }
}

function updateGroupTree(newNode,node){
  if(!node){
    node = {nodes:GroupTree};
  }
  if(node.nodes){
    for (var i = node.nodes.length - 1; i >= 0; i--) {
      if(node.nodes[i].id == newNode.id){
        node.nodes[i].text = newNode.text;
        node.nodes[i].id = newNode.id;
        node.nodes[i].pid = newNode.pid;
        node.nodes[i].FilterList = newNode.FilterList;
        return;
      }
      updateGroupTree(newNode,node.nodes[i]);
    }
  }
}


var Tables = {};
var ColumnsTemplate = {
  userList:[
      { data: 'username' },
      { data: 'role' },
      {
        data: function(item){
            return moment(item.created_at,'X').format('YYYY-MM-DD HH:mm:ss');
        }
      },
      { 
          data: function(item){
              return '<button class="btn btn-danger btn-xs" onclick="removeUserForGroup(\''+item.id+'\') ">移除</button>';
          }
      }
  ]
};
function removeUserForGroup(uid){
  rootScope.removeUserForGroup(uid);
}
function updataTable(data,domId){
    if(Tables[domId]){
      Tables[domId].clear();
      Tables[domId].rows.add(data);
      Tables[domId].draw();
    }else{
      Tables[domId] = $('#'+domId).DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "language": {
              "paginate": {
                  "next": "下一页",
                  "sPrevious": "上一页"
              },
              "sInfoEmpty": "",
              "sEmptyTable": "未查询到相关信息",
              "sInfo": ""
          },
          data: data,
          columns: ColumnsTemplate[domId]
      });
    }
}
