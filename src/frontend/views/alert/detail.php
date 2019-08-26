<?php
/* @var $this yii\web\View */

$this->title = '预警详情';
?>
<style>
    .detail_content {
        padding: 36px 48px;
    }

    .row,
    .col-md-3,
    .col-md-4,
    .col-md-6,
    p {
        padding: 0;
        margin: 0;
    }

    .detail_top {
        height: 184px;
        width: 100%;
        background: #FFFFFF;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.08);
        border-radius: 6px;
    }

    .detail_top_top {
        height: 67px;
        border-bottom: 1px solid #ECECEC;
        padding: 0 24px;
        position: relative;
    }

    .detail_top_top_left {
        float: left;
        height: 67px;
        line-height: 67px;
    }

    .detail_top_top_left_title {
        font-size: 20px;
        color: #333333;
        vertical-align: middle;
    }

    .detail_top_top_right {
        height: 67px;
    }

    .detail_top_top_right_btn {
        background: #0070FF;
        border-radius: 4px;
        width: 124px;
        height: 42px;
        font-size: 14px;
        color: #fff;
        position: absolute;
        right: 26px;
        top: 50%;
        transform: translateY(-50%);
    }

    .detail_top_bom {
        padding: 0 26px;
        height: 116px;
    }

    .detail_top_bom_item {
        height: 116px;
    }

    .detail_top_bom_p {
        height: 58px;
        line-height: 58px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .detail_top_bom_title {
        font-size: 16px;
        color: #649EE9;
        line-height: 20px;
        vertical-align: middle;
    }

    .detail_top_bom_content {
        font-size: 16px;
        color: #333333;
        vertical-align: middle;
    }

    .detail_bom {
        margin-top: 36px;
        min-height: 200px;
        background: #FFFFFF;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.08);
        border-radius: 6px;
    }

    .detail_bom_nav {
        height: 56px;
        background: #EEF6FF;
        border: none;
    }

    .detail_bom_nav li {
        height: 56px;

    }

    .detail_bom_nav>li>a {
        height: 56px;
        line-height: 56px !important;
        font-size: 16px !important;
        color: #333333;
        padding: 0 30px !important;
        border: 0;
    }

    .detail_bom_nav a:hover {
        color: #409eff !important;
    }

    .detail_bom_nav>li>a:hover {
        border: none;
        background: transparent;
    }

    .detail_bom_nav>li.active>a:hover {}

    .detail_bom_nav>li.active>a {
        color: #409eff !important;
        border: none !important;
    }

    .detail_bom_ul,
    .detail_bom_ul>li {
        list-style: none;
        padding: 0px;
        margin: 0px;
    }

    .detail_bom_ul>li:nth-child(odd) {
        background: #fff;
        height: 48px;
        padding: 0 26px;
        line-height: 48px;
    }

    .detail_bom_ul>li:nth-child(even) {
        background: #EEF6FF;
        height: 48px;
        line-height: 48px;
        padding: 0 26px;
    }

    .detail_bom_li_title {
        font-size: 16px;
        color: #333333;
        width: 150px;
        display: inline-block;
    }

    .detail_bom_li_content {
        font-size: 14px;
        color: #666666;
        line-height: 20px;
    }

    .detail_bom_nav>li.active {
        border-top: 3px solid #0070FF;
        border-radius: 4px;
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

    .title_info_box {
        line-height: 50px;
    }

    .title_info_box p {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .title_info_title {
        font-size: 16px;
        color: #333333;
    }

    .title_info_content {
        font-size: 16px;
        color: #666666;
    }

    .zeromodal-container {
        border-radius: 6px;
    }

    .modal-title {
        border-left: 3px solid #0070FF;
        padding-left: 6px;
    }
</style>
<section class="detail_content" ng-app="myApp" ng-controller="myCtrl" ng-cloak>
    <div class="detail_top">
        <!-- 上 -->
        <div class="detail_top_top">
            <div class="detail_top_top_left">
                <span class="detail_top_top_left_title">{{detail.indicator}}</span>
                <img src="/images/alert/top_detail.png" style="cursor:pointer;" ng-click="detail_title()" alt="">
            </div>
            <div class="detail_top_top_right">
                <button class="detail_top_top_right_btn" ng-if="set_true&&lookup_license"
                    ng-click="search_extend(detail.indicator)">
                    扩展查询
                </button>
            </div>
        </div>
        <!-- 下 -->
        <div class="detail_top_bom row">
            <div class="col-md-4 detail_top_bom_item">
                <p class="detail_top_bom_p">
                    <img src="/images/alert/top_1.png" alt="">
                    <span class="detail_top_bom_title">失陷资产:</span>
                    <span class="detail_top_bom_content">{{alert.client_ip}}</span>
                </p>
                <p class="detail_top_bom_p">
                    <img src="/images/alert/top_2.png" alt="">
                    <span class="detail_top_bom_title">威胁指标:</span>
                    <span class="detail_top_bom_content" title="{{detail.indicator}}">{{detail.indicator}}</span>
                </p>
            </div>
            <div class="col-md-4">
                <p class="detail_top_bom_p">
                    <img src="/images/alert/top_3.png" alt="">
                    <span class="detail_top_bom_title">情报来源:</span>
                    <span class="detail_top_bom_content"
                        title="{{detail.attr.sources[0]}}">{{detail.attr.sources[0]}}</span>
                </p>
                <p class="detail_top_bom_p">
                    <img src="/images/alert/top_4.png" alt="">
                    <span class="detail_top_bom_title">预警时间:</span>
                    <span class="detail_top_bom_content">{{detail.time*1000 | date : 'yyyy-MM-dd HH:mm'}}</span>
                </p>
            </div>
            <div class="col-md-4">
                <p class="detail_top_bom_p">
                    <img src="/images/alert/top_5.png" alt="">
                    <span class="detail_top_bom_title">预警类型:</span>
                    <span class="detail_top_bom_content">{{alert.category}}</span>
                </p>
            </div>
        </div>
    </div>
    <!-- tab栏 -->
    <div class="detail_bom">
        <ul class="nav nav-tabs detail_bom_nav">
            <li role="presentation" class="active">
                <a href="#info" data-toggle="tab">情报详情</a>
            </li>
            <li role="presentation"><a href="#whois" data-toggle="tab">whois信息</a></li>
            <li role="presentation"><a href="#domain" data-toggle="tab">关联域名</a></li>
            <li role="presentation"><a href="#file" data-toggle="tab">关联文件</a></li>
        </ul>
        <div class="tab-content">
            <div id="info" class="tab-pane active">
                <ul class="detail_bom_ul">
                    <li class="detail_bom_li">
                        <img src="/images/alert/bom_1.png" alt="">
                        <span class="detail_bom_li_title">
                            地理位置
                        </span>
                        <span class="detail_bom_li_content">{{detail.attr.geo}}</span>
                    </li>
                    <li class="detail_bom_li" ng-if="alert.category=='钓鱼仿冒'">
                        <img src="/images/alert/bom_4.png" alt="">
                        <span class="detail_bom_li_title">
                            相似度
                        </span>
                        <span class="detail_bom_li_content">{{detail.attr.confidence}}</span>
                    </li>
                    <li class="detail_bom_li" ng-if="alert.category!='钓鱼仿冒'">
                        <img src="/images/alert/top_3.png" alt="">
                        <span class="detail_bom_li_title">
                            情报来源
                        </span>
                        <span class="detail_bom_li_content">{{detail.attr.sources[0]}}</span>
                    </li>
                    <li class="detail_bom_li" ng-if="alert.category!='钓鱼仿冒'">
                        <img src="/images/alert/bom_3.png" alt="">
                        <span class="detail_bom_li_title">
                            威胁类型
                        </span>
                        <span class="detail_bom_li_content">{{alert.category}}</span>
                    </li>
                    <li class="detail_bom_li" ng-if="alert.category!='钓鱼仿冒'">
                        <img src="/images/alert/bom_4.png" alt="">
                        <span class="detail_bom_li_title">
                            置信度
                        </span>
                        <span class="detail_bom_li_content">{{detail.attr.confidence}}</span>
                    </li>
                    <li class="detail_bom_li" ng-if="alert.category!='钓鱼仿冒'">
                        <img src="/images/alert/top_4.png" alt="">
                        <span class="detail_bom_li_title">
                            首次发现时间
                        </span>
                        <span class="detail_bom_li_content">{{detail.attr.hoohoolab_first_seen}}</span>
                    </li>
                    <li class="detail_bom_li" ng-if="alert.category!='钓鱼仿冒'">
                        <img src="/images/alert/bom_6.png" alt="">
                        <span class="detail_bom_li_title">
                            最近发现时间
                        </span>
                        <span class="detail_bom_li_content">{{detail.attr.hoohoolab_last_seen}}</span>
                    </li>
                </ul>
            </div>
            <div id="whois" class="tab-pane">
                <ul class="detail_bom_ul">
                    <li ng-repeat="item in whois_info">
                        <span class="detail_bom_li_title">{{item.name}}</span>
                        <span class="detail_bom_li_content">{{item.value}}</span>
                    </li>
                </ul>
            </div>
            <div id="domain" class="tab-pane">
                <p class="domain_p">{{detail.attr.hoohoolab_domains}} </p>
            </div>
            <div id="file" class="tab-pane">
                <table class="table ng-cloak domain_table">
                    <tr style="font-size: 16px;color: #333;">
                        <th style="font-weight: normal;">THREAT</th>
                        <th style="font-weight: normal;">MD5</th>
                        <th style="font-weight: normal;">SHA1</th>
                        <th style="font-weight: normal;">SHA256</th>
                    </tr>
                    <tr ng-repeat="item in detail.attr.hoohoolab_files" style="font-size: 14px;color: #666666;">
                        <td title="{{item.threat}}">{{item.threat}}</td>
                        <td title="{{item.MD5}}">{{item.MD5}}</td>
                        <td title="{{item.SHA1}}">{{item.SHA1}}</td>
                        <td title="{{item.SHA256}}">{{item.SHA256}}</td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
    <!-- 弹窗 -->
    <div style="display: none;" id="title_hideenBox">
        <div id="title_info">
            <div class="row">
                <div class="col-md-6 title_info_box">
                    <p>
                        <span class="title_info_title">资产分组：</span>
                        <span class="title_info_content">{{alert.company}}</span>
                    </p>
                    <p>
                        <span class="title_info_title">关联域名：</span>
                        <span class="title_info_content"
                            title="{{detail.attr.hoohoolab_domains}}">{{detail.attr.hoohoolab_domains}}</span>
                    </p>
                </div>
                <div class="col-md-6 title_info_box">
                    <p>
                        <span class="title_info_title">资产状态：</span>
                        <span class="title_info_content">{{alert.asset_status}}</span>
                    </p>
                    <p>
                        <span class="title_info_title">地理位置：</span>
                        <span class="title_info_content">{{alert.position}}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="/plugins/angular/angular-sanitize.min.js"></script>
<script>
    var alert = <?=json_encode($alert)?> ;
    console.log(alert);
    if (typeof alert.data.geo == 'string') {
        try {
            alert.data.geo = JSON.parse(alert.data.geo);
        } catch (e) {}
    }
    var json = JSON.stringify(alert.data, 1, '\t');
    if (alert.data.attr && alert.data.attr.threat > -1) {
        alert.data.attr.threat_arr = [];
        for (var i = 0; i < 5; i++) {
            if (alert.data.attr.threat > i) {
                if (alert.data.attr.threat < (i + 1)) {
                    alert.data.attr.threat_arr.push('fa-star-half-o');
                } else {
                    alert.data.attr.threat_arr.push('fa-star');
                }
            } else {
                alert.data.attr.threat_arr.push('fa-star-o');
            }
        }
    }

    function json_highLight(json) {
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(
            /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
            function (match) {
                var cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
    }

    var app = angular.module('myApp', ['ngSanitize']);
    app.controller('myCtrl', function ($scope, $http, $filter) {
        $scope.set_true = false
        $scope.lookup_license = false
        //   获取状态设备角色
        $scope.get_centralmanage_self = function () {
            var loading = zeroModal.loading(4);
            $http({
                method: "get",
                url: "/site/dev-self"
            }).then(
                function successCallback(data) {
                    zeroModal.close(loading);
                    $scope.centralmanage_self = data.data.data;
                    if ($scope.centralmanage_self.role_type == "manage") {
                        $scope.set_true = true;
                    }
                    if ($scope.centralmanage_self.role_type == "branch") {
                        $scope.set_true = false;
                    }
                },
                function errorCallback(data) {
                    zeroModal.close(loading);
                    zeroModal.error(data.data.message);
                }
            );
        };

        //   获取license
        $scope.get_lookup_license = function () {
            $http({
                method: "get",
                url: "/intelligence/license"
            }).then(
                function successCallback(data) {
                    console.log(data);
                    $scope.lookup_license = data.data.data.result;
                },
                function errorCallback(data) {}
            );
        };

        $scope.get_centralmanage_self();
        $scope.get_lookup_license();
        $scope.alert = alert;
        $scope.detail = alert.data;
        console.log($scope.detail);
        $scope.whois_info = [];
        for (var k in $scope.detail.attr.hoohoolab_ip_whois) {
            var obj = {};
            if ($scope.detail.attr.sources[0].indexOf("IPReputation") != -1) {
                switch (k) {
                    //   IP Reputation
                    case "net_range":
                        obj.name = "网络地址范围";
                        break;
                    case "net_name":
                        obj.name = "网络名称";
                        break;
                    case "descr":
                        obj.name = "网络描述";
                        break;
                    case "created":
                        obj.name = "网络注册时间";
                        break;
                    case "updated":
                        obj.name = "网络更新时间";
                        break;
                    case "country":
                        obj.name = "网络注册的国家";
                        break;
                    case "contact_owner_name":
                        obj.name = "网络所有者名称";
                        break;
                    case "contact_owner_code":
                        obj.name = "网络所有者编码";
                        break;
                    case "contact_owner_country":
                        obj.name = "网络所有者国家";
                        break;
                    case "contact_owner_country":
                        obj.name = "网络所有者国家";
                        break;
                    case "contact_owner_city":
                        obj.name = "网络所有者城市";
                        break;
                    case "contact_owner_email":
                        obj.name = "网络所有者邮箱";
                        break;
                    case "contact_abuse_name":
                        obj.name = "滥用报告接口人";
                        break;
                    case "contact_abuse_email":
                        obj.name = "滥用报告邮箱";
                        break;
                    case "asn":
                        obj.name = "自主系统号（ASN）";
                        break;
                    default:
                        obj.name = k;
                        break;
                }
            } else {
                switch (k) {
                    // Malicious URL whois
                    // Phishing URL
                    case "domain":
                        obj.name = "域名";
                        break;
                    case "created":
                        obj.name = "域名注册时间";
                        break;
                    case "updated":
                        obj.name = "域名更新时间";
                        break;
                    case "expires":
                        obj.name = "域名过期时间";
                        break;
                    case "name":
                        obj.name = "注册人名称";
                        break;
                    case "org":
                        obj.name = "注册人组织";
                        break;
                    case "country":
                        obj.name = "注册人国家";
                        break;
                    case "city":
                        obj.name = "注册人城市";
                        break;
                    case "email":
                        obj.name = "注册人邮箱";
                        break;
                    case "email":
                        obj.name = "注册人邮箱";
                        break;
                    case "registrar_name":
                        obj.name = "注册机构名称";
                        break;
                    case "registrar_email":
                        obj.name = "注册机构邮箱";
                        break;
                    case "NS":
                        obj.name = "域名服务器";
                        break;
                    case "NS_ips":
                        obj.name = "域名服务器IP";
                        break;
                    case "MX":
                        obj.name = "邮件服务器";
                        break;
                    case "MX_ips":
                        obj.name = "邮件服务器IP";
                        break;
                    default:
                        obj.name = k;
                        break;
                }
            }
            obj.value = $scope.detail.attr.hoohoolab_ip_whois[k];
            $scope.whois_info.push(obj)
        }

        $scope.json = json_highLight(json);
        alert.data.session = JSON.stringify(alert.data.session);
        $scope.sessionList = [];
        if (alert.data.matched) {

            var list = alert.data.session.split(alert.data.matched);
            $scope.sessionList.push({
                className: '',
                text: list[0]
            });
            for (var i = 1; i < list.length; i++) {
                $scope.sessionList.push({
                    className: 'highlight',
                    text: alert.data.matched
                });
                $scope.sessionList.push({
                    className: '',
                    text: list[i]
                });
            }
        } else {
            $scope.sessionList.push({
                className: '',
                text: alert.data.session
            });
        }
        //   弹窗
        $scope.detail_title = function () {
            var W = 552;
            var H = 185;
            var box = null;
            box = zeroModal.show({
                title: "资产信息",
                content: title_info,
                width: W + "px",
                height: H + "px",
                ok: false,
                cancel: false,
                okFn: function () {},
                onCleanup: function () {
                    title_hideenBox.appendChild(title_info);
                }
            });
        };
        //   扩展查询
        $scope.search_extend = function (obj) {
            console.log(obj);
            var loading = zeroModal.loading(4);
            $http({
                method: "get",
                url: "/intelligence/extension",
                params: {
                    indicator: obj
                }
            }).then(
                function (data) {
                    console.log(data.data.data.result);
                    zeroModal.close(loading);
                    for (var k in data.data.data.result) {
                        switch (k) {
                            case "DomainGeneralInfo":
                                //   window.location.href = "/ExtendedQuery.html#/domain?name=" + obj;
                                window.open("/ExtendedQuery.html#/domain?name=" + obj)
                                break;
                            case "FileGeneralInfo":
                                //   window.location.href = "/ExtendedQuery.html#/hash?name=" + obj;
                                window.open("/ExtendedQuery.html#/hash?name=" + obj)
                                break;
                            case "IpGeneralInfo":
                                //   window.location.href = "/ExtendedQuery.html#/ip?name=" + obj;
                                window.open("/ExtendedQuery.html#/ip?name=" + obj)
                                break;
                            case "UrlGeneralInfo":
                                //   window.location.href = "/ExtendedQuery.html#/url?name=" + obj;
                                window.open("/ExtendedQuery.html#/url?name=" + obj)
                                break;
                            default:
                                break;
                        }
                    }
                },
                function () {}
            );
        };
    });
</script>
