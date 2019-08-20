<style>
    .dropdown-menu {
        min-width: 100px;
    }
    #navApp{
        height: 64px;
    }
    .dropdown{
        height:64px;
    }
    .nav_li_a{
    color: #FFF;
    height: 64px;
    border: 0;
    line-height: 64px;
    padding: 0 20px !important;
}
#navApp a{
    font-size:16px;
}
.dropdown-menu{
    background: #FFFFFF;
box-shadow: 0 6px 6px 0 rgba(0,0,0,0.16);
border-radius: 4px;
width:200px;
/* height:210px; */
    left: 50%;
    transform: translateX(-50%);
    margin:0;
    font-size: 16px;
color: #333333;
padding-top:21px;
}
.skin-blue .main-header .navbar .nav > li > a:hover, .skin-blue .main-header .navbar .nav > li > a:active, .skin-blue .main-header .navbar .nav > li > a:focus, .skin-blue .main-header .navbar .nav .open > a, .skin-blue .main-header .navbar .nav .open > a:hover, .skin-blue .main-header .navbar .nav .open > a:focus, .skin-blue .main-header .navbar .nav > .active > a {
    background: transparent;
    color: #fff;
}
.nav .open>a, .nav .open>a:focus, .nav .open>a:hover {
background: transparent;
border-color:transparent;
color:#fff;
}
.dropdown-menu>li{
height:38px;
padding:0px;
}
.dropdown-menu>li>a{
height:38px;
line-height:38px;
padding:0 24px;
}
.dropdown-menu>li>a:hover{
background: #0070FF;
color:#fff;
padding:0 24px;
}
.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
background: #0070FF;
color: #fff;
}
.hover_li_title:hover{
    background: #456196;
}
.nav-pills>li.active>a {
border-top-color:transparent;
}
.nav-pills>li>a {
    border-top:none;
}
 .skin-blue .main-header .navbar .nav > .active > a{
     background: #456196;
 }
.nav-pills>li.active>a {
    font-weight: normal;
}
.nav>li>a{
   display:inherit;

}
.hover_li_title.active{
background:#456196 !important;
}

</style>
<div ng-controller="mainNavCtrl" id="navApp" ng-cloak>
    <ul class="nav nav-pills">
        <!-- 首页 -->
        <li role="presentation" class="dropdown  <?=isActive(['/site/index'])?> <?=isActive(['/map.html'])?> hover_li_title"  ng-if="menu_list.index">
            <a class="dropdown-toggle nav_li_a"  data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-home"></i> 首页<span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li class="treeview <?=isActive(['/site/index'])?>"
                    ng-if="menu_list.index_overview">
                    <a href="<?=getPath('/site/index')?>">
                        <span>概览</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/map.html'])?>"
                    ng-if="menu_list.index_BigScreen">
                    <a href="<?=getPath('/map.html')?>">
                        <span>可视化大屏</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- 情报 -->
        <li role="presentation" class="dropdown hover_li_title <?=isActive(['/search/index', '/agent/index', '/share/index', '/intelligence/source-management', '/search/apt-lib'])?> " ng-if="menu_list.intelligence">
            <a class="dropdown-toggle nav_li_a"  data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-podcast"></i> 情报<span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li class="treeview <?=isActive(['/search/index'])?>"
                    ng-if="menu_list.intelligence_query">
                    <a href="<?=getPath('/search/index')?>" >
                        <span>情报查询</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/agent/index'])?>"
                    ng-if="menu_list.intelligence_extract">
                    <a href="<?=getPath('/agent/index')?>" >
                        <span>情报聚合</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/share/index'])?>"
                    ng-if="menu_list.intelligence_share">
                    <a href="<?=getPath('/share/index')?>">
                        <span>情报共享</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/intelligence/source-management'])?>"
                    ng-if="menu_list.intelligence_sourceAdmin">
                    <a href="<?=getPath('/intelligence/source-management')?>" >
                        <span>情报源管理</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/search/apt-lib'])?>"
                    ng-if="menu_list.intelligence_apt">
                    <a href="<?=getPath('/search/apt-lib')?>" >
                        <span>APT武器库</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- 资产 -->
        <li role="presentation" class="dropdown hover_li_title <?=isActive(['/assets/asset-management', '/assets/asset-risky', '/assets/details'])?>" ng-if="menu_list.assets">
            <a class="dropdown-toggle nav_li_a" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-database"></i> 资产<span class="caret"></span>
            </a>
            <ul class="dropdown-menu" >
                <li class="treeview <?=isActive(['/assets/asset-management'])?>"
                    ng-if="menu_list.assets_admin">
                    <a href="<?=getPath('/assets/asset-management')?>" >
                        <span>资产管理</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/assets/asset-risky'])?>"
                    ng-if="menu_list.assets_risk">
                    <a href="<?=getPath('/assets/asset-risky')?>" >
                        <span>受影响资产</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- 预警 -->
        <li role="presentation" class="dropdown hover_li_title <?=isActive(['/alert/index', '/alert/loophole', '/alert/darknet', '/alert/loophole-detail'])?>" ng-if="menu_list.warning">
            <a class="dropdown-toggle nav_li_a"  data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-heartbeat"></i> 预警<span class="caret"></span>
            </a>
            <ul class="dropdown-menu" >
                <li class="treeview <?=isActive(['/alert/index'])?>"
                    ng-if="menu_list.warning_threat">
                    <a href="<?=getPath('/alert/index')?>" >
                        <span>威胁预警</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/alert/loophole'])?>"
                    ng-if="menu_list.warning_loophole">
                    <a href="<?=getPath('/alert/loophole')?>" >
                        <span>漏洞预警</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/alert/darknet'])?>"
                    ng-if="menu_list.warning_drakNet">
                    <a href="<?=getPath('/alert/darknet')?>">
                        <span>暗网预警</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- 报表 -->
        <li role="presentation" class="dropdown hover_li_title <?=isActive(['/report/index', '/report/send'])?>" ng-if="menu_list.report">
            <a class="dropdown-toggle nav_li_a"  data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-area-chart"></i>报表<span class="caret"></span>
            </a>
            <ul class="dropdown-menu" >
                <li class="treeview <?=isActive(['/report/index'])?>"
                    ng-if="menu_list.report_creat">
                    <a href="<?=getPath('/report/index')?>">
                        <span>报表生成</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/report/send'])?>" ng-if="menu_list.report_send">
                    <a href="<?=getPath('/report/send')?>" >
                        <span>报表发送</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- 设置 -->
        <li role="presentation" class="dropdown hover_li_title <?=isActive(['/seting/network', '/seting/systemnotice', '/seting/custom-information-search', '/seting/centralmanager', '/seting/user', '/seting/log', '/api/index'])?> " ng-if="menu_list.set">
            <a class="dropdown-toggle nav_li_a"  data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                aria-expanded="false">
                <i class="fa fa-cog"></i>设置<span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li class="treeview <?=isActive(['/seting/network'])?>" ng-if="menu_list.set_sys">
                    <a href="<?=getPath('/seting/network')?>" >
                        <span>网络配置</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/seting/systemnotice'])?>"
                    ng-if="menu_list.set_notice">
                    <a href="<?=getPath('/seting/systemnotice')?>" >
                        <span>威胁通知</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/seting/custom-information-search'])?>"
                    ng-if="menu_list.set_loopholeRelation">
                    <a href="<?=getPath('/seting/custom-information-search')?>">
                        <span>漏洞关联</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/seting/centralmanager'])?>"
                    ng-if="menu_list.set_admin">
                    <a href="<?=getPath('/seting/centralmanager')?>">
                        <span>集中管理</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/seting/user'])?>" ng-if="menu_list.set_user">
                    <a href="<?=getPath('/seting/user')?>" >
                        <span>账号管理</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/seting/log'])?>" ng-if="menu_list.set_log">
                    <a href="<?=getPath('/seting/log')?>" >
                        <span>审计日志</span>
                    </a>
                </li>
                <li class="treeview <?=isActive(['/api/index'])?>" ng-if="menu_list.api">
                    <a href="<?=getPath('/api/index')?>" >
                        <span>情报API</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>
<script type="text/javascript" src="/js/nav.js"></script>