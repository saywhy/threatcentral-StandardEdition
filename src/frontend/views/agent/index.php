<?php
/* @var $this yii\web\View */

$this->title = '情报聚合';
// $this->params['chartVersion'] = '1.1.1';
?>
<link rel="stylesheet" href="/css/common.css">
<style>
    /*.node circle{
    fill: #F1C40F;
  }*/
    /* #chartAll{
    min-height: 600px;
  }
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


  .radio-group{
    white-space: nowrap;
    display: inline-block;
    margin-bottom: 5px;
    text-overflow: ellipsis;
    overflow-x: hidden;
    cursor: pointer;
  }
  .zeromodal-body{
    overflow-x: hidden;
  }
  .box-row{
    clear: both;
  } */
    /* 情报提取 */
    .agent_container {
        padding: 36px 48px;
    }

    .agent_top {
        /* height: 304px; */
        background: #FFFFFF;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.08);
        border-radius: 6px;
    }

    .agent_top_title {
        height: 64px;
        font-size: 20px;
        color: #333333;
        line-height: 64px;
        padding: 0 36px;
    }

    .domain_table tr:nth-child(odd) {
        background: #fff;
    }

    .domain_table tr:nth-child(even) {
        background: #eef6ff;

    }

    .domain_table {
        width: 100%;
        table-layout: fixed;
    }

    .domain_table tr {
        height: 48px;
        line-height: 48px;
        padding-left: 26px;
    }

    .domain_table td,
    .domain_table th {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        text-align: center;
    }

    .domain_p {
        height: 48px;
        line-height: 48px;
        padding-left: 26px;
        font-size: 16px;
        color: #333333;
    }

    .btn_look {
        background: #64A8FF;
        border-radius: 4px;
        height: 28px;
        width: 82px;
        color: #fff;
        font-size: 12px;
    }

    #chart {
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    #chartAll {
        min-height: 500px;
    }

    .radio-group {
        white-space: nowrap;
        display: inline-block;
        margin-bottom: 5px;
        text-overflow: ellipsis;
        overflow-x: hidden;
        cursor: pointer;
    }

    .link {
        fill: none;
        stroke: #0078FF;
        stroke-opacity: .1;
    }

    .link:hover {
        stroke-opacity: .5;
    }

    .agent_bom {
        margin-top: 36px;
        height: 593px;
        background: #FFFFFF;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.08);
        border-radius: 6px;
    }

    .agent_bom_title {
        height: 64px;
        font-size: 20px;
        color: #333333;
        line-height: 64px;
        padding: 0 36px;
        border-bottom: 1px solid #ececec;
    }
    .agent_bom_chart{
        padding:20px;
    }
</style>
<!-- Main content -->
<section ng-app="myApp" ng-controller="myCtrl">
    <div class="agent_container">
        <div class="agent_top">
            <div class="agent_top_title">
                默认聚合
            </div>
            <div class="agent_top_table">
                <table class="table ng-cloak domain_table">
                    <tr>
                        <th>节点名</th>
                        <th>指标</th>
                        <th>URL</th>
                        <th>操作</th>
                    </tr>
                    <tr style="cursor: pointer;" ng-repeat="item in default_nodes"
                        ng-click="go_loophole_detail(item.html)">
                        <td ng-bind="item.name">节点名</td>
                        <td ng-bind="item.length"></td>
                        <td>
                            <a href="{{'/feeds/'+ item.name }}" target="_blank"
                                ng-bind="rootUrl+'/feeds/'+item.name"></a>
                        </td>
                        <td>
                            <button class="btn_look" ng-click="showSankey(item)">查看详细</button>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
        <div class="agent_bom">
            <div class="agent_bom_title">
               <img src="/images/agent/agent_all.png" style="vertical-align: middle;" alt="">
                <span style="vertical-align: middle;" >聚合总览</span>
            </div>
            <div class="agent_bom_chart">
                <div id="chartAll"></div>
            </div>
        </div>
    </div>
    <div id="hide_box" style="display: none;">
        <div id="chart"></div>
        <div id="addNodeBox">
            <div class="box-row">
                <div class="form-group col-md-12">
                    <label>节点名称</label>
                    <input class="form-control" style="width: 30%;min-width: 30em" ng-model="newNode.name"
                        placeholder="请输入节点名称...">
                </div>
            </div>
            <div class="box-row">
                <div class="form-group col-md-12">
                    <label>节点类型</label>
                    <div class="radio">
                        <span class="radio-group col-lg-3 col-md-4 col-sm-6 col-xs-12"
                            ng-click="newNode.setType('md5')">
                            <span class="iradio_minimal-blue" ng-class="newNode.type == 'md5' ? 'checked' : ''"></span>
                            <span>MD5</span>
                        </span>
                        <span class="radio-group col-lg-3 col-md-4 col-sm-6 col-xs-12"
                            ng-click="newNode.setType('IPv4')">
                            <span class="iradio_minimal-blue" ng-class="newNode.type == 'IPv4' ? 'checked' : ''"></span>
                            <span>IPv4</span>
                        </span>
                        <span class="radio-group col-lg-3 col-md-4 col-sm-6 col-xs-12"
                            ng-click="newNode.setType('domain')">
                            <span class="iradio_minimal-blue"
                                ng-class="newNode.type == 'domain' ? 'checked' : ''"></span>
                            <span>Domain</span>
                        </span>
                        <span class="radio-group col-lg-3 col-md-4 col-sm-6 col-xs-12"
                            ng-click="newNode.setType('URL')">
                            <span class="iradio_minimal-blue" ng-class="newNode.type == 'URL' ? 'checked' : ''"></span>
                            <span>URL</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="box-row">
                <div class="form-group col-md-12">
                    <label>请选择输入节点</label>
                    <div class="radio">
                        <div class="radio-group col-lg-3 col-md-4 col-sm-6 col-xs-12"
                            ng-click="newNode.push2inputs(item)" ng-repeat="item in input_nodes"
                            ng-if="item.indicator_types.indexOf(newNode.type) > -1">
                            <span class="icheckbox_minimal-blue"
                                ng-class="newNode.inputs.indexOf(item.name) > -1 ? 'checked' : ''"></span>
                            <span ng-bind="item.name"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<script src="/plugins/dndTree/d3.v3.min.js"></script>
<script src="/js/agent/sankey.js"></script>
<script src="/js/controllers/agent.js"></script>
