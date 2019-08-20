<?php
/* @var $this yii\web\View */

$this->title = '情报';
// $this->params['chartVersion'] = '1.1.1';
?>
<style>
.titleNum{
    display: inline-block;
    height: 100px;
}
.titleNum_title{

}
.titleNum_num{
    text-align: center;
    font-size: 30px;
   font-weight: 700;
}
</style>
<!-- Main content -->
<section class="content">
  <!--proxyNodeList -->
  <div class="row">
    <div class="col-md-12" ng-controller="behCtrl">
      <div class="box">
    <div style="padding:5px;margin-bottom:5px;height:100px;">
        <div class="titleNum col-md-4" style="border-right:2px solid #999;border-bottom:2px solid #999;">
            <p class="titleNum_title">总情报数</p>
            <p class="titleNum_num">1,928,617</p>
        </div>
        <div class="titleNum col-md-4" style="border-right:2px solid #999;border-bottom:2px solid #999;">
            <p class="titleNum_title">24小时内更新数</p>
            <p class="titleNum_num">28,617</p>
        </div>
        <div class="titleNum col-md-4"style="border-bottom:2px solid #999;" >
            <p class="titleNum_title">一周内更新数</p>
            <p class="titleNum_num">128,617</p>
        </div>
    </div>

        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-dot-circle-o"></i> 情报状态表</h3>
          <div class="box-tools">

          </div>
        </div>
        <div class="box-body table-responsive no-padding">

          <div class="nav-tabs-custom" style="margin-bottom: 0px">

            <div class="tab-content" style="padding-top:0px;border-bottom:0px; ">

              <table class="table table-hover" id="inputNodeTable">
                <thead>
                  <tr>
                    <th>节点名</th>
                    <th>指标</th>
                    <th>状态</th>
                    <th>最后一次运行时间</th>
                    <th>最后一次运行成功时间</th>
                    <th>最后一次状态</th>
                  </tr>
                </thead>
              </table>

            </div>
          </div>
          <style>
            #chart {
              /*height: 500px;*/
              overflow:hidden;
            }

            /*.node circle{
              fill: #F1C40F;
            }*/

            .node rect {
              cursor: move;
              fill-opacity: .9;
              shape-rendering: crispEdges;
            }

            .node text {
              pointer-events: none;
              text-shadow: 0 1px 0 #fff;
              font-size: 12px;
            }

            .link {
              fill: none;
              stroke: #000;
              stroke-opacity: .2;
            }

            .link:hover {
              stroke-opacity: .5;
            }

          </style>
          <div id="hide_box" style="display: none;">
            <div id="chart"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->

<script src="/plugins/dndTree/d3.v3.min.js"></script>
<script src="/js/agent/sankey.js"></script>
<script type="text/javascript">
  var rootUrl = location.protocol+"//"+location.host;
  var NodeList = [];
  var inputNodeList = [];
  function setNodeList(nodes){
    //     {
    //   "class": "cyberhunt.ft.redis.RedisSet",
    //   "clock": 3830,
    //   "inputs": [
    //     "aggregatorIPv4"
    //   ],
    //   "length": 123528,
    //   "name": "DefaultIPv4feedHC",
    //   "output": false,
    //   "state": 5,
    //   "statistics": {
    //     "added": 123528,
    //     "update.processed": 123528,
    //     "update.queued": 123528,
    //     "update.rx": 123528
    //   },
    //   "trace": true
    // },

    NodeList = nodes;
    inputNodeList = [];
    for (var i = NodeList.length - 1; i >= 0; i--) {
      var node = NodeList[i];
      if(node.output == true && node.inputs.length == 0){
        inputNodeList.push(node);
      }
    }
    updateTable(inputNodeList,'inputNodeTable');
    console.log(inputNodeList);
  }
  var Tables = {};
  var ColumnsTemplate = {
    inputNodeTable:[
        { data: 'name' },
        { data: 'length' },
        {
            data: function(item){
                var State_str = ['READY','CONNECTED','REBUILDING','RESET','INIT','STARTED','CHECKPOINT','IDLE','STOPPED'];
                return State_str[item.state];
            }
        },
        {
            data: function(item){
                var dateStr = moment(item.last_run,'x').format("YYYY-MM-DD HH:mm:ss");
                dateStr = dateStr == 'Invalid date' ? '' : dateStr;
                return dateStr;
            }
        },
        {
            data: function(item){
                var dateStr = moment(item.last_successful_run,'x').format("YYYY-MM-DD HH:mm:ss");
                dateStr = dateStr == 'Invalid date' ? '' : dateStr;
                return dateStr;
            }
        },
        { data: 'sub_state' }
    ]
  };
  function updateTable(data,domId){
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
              "sInfoEmpty": "共 0 条记录",
              "sEmptyTable": "未查询到相关信息",
              "sInfo": ""
          },
          data: data,
          columns: ColumnsTemplate[domId]
      });
  }
  window.onload = function() {
    $.ajax({
        dataType: 'json',
        url: '/proxy/status/cyberhunt',
        success:function(rsp)
        {
          setNodeList(rsp.result);
        },complete:function(rsp)
        {
        }
    });
  }



</script>
