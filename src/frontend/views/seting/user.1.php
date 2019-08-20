<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */

$this->title = '用户管理';
?>
<!-- Main content -->
<section class="content" ng-app="myApp" >


  <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom">

        <?php include 'nav.php';?>
        
        <div class="tab-content">

          <!-- user-->
          <div class="tab-pane active" id="user">
            <section ng-controller="UserCtrl">
              <h4 class="seting-header" style="margin-bottom: -1px;">
                <i class="fa fa-user"></i>
                用户
                <div class="btn-group pull-right" style="margin: -5px 5px">
                    <button type="button" ng-click="add()" class="btn btn-sm btn-default" >添加用户</button>
                    <button type="button" ng-click="addGroups()" class="btn btn-sm btn-default" ng-class="UserIDList.length ? '' : 'disabled'">加入用户组</button>
                </div>
                <style type="text/css">
                    .list-group-item:first-child,.list-group-item:last-child{
                        border-radius:0px;
                    }
                </style>
                <div id="hide_box" style="display: none;">
                    <div id="groupTree"></div>
                </div>
              </h4>

              <div class="row">

                <div class="col-sm-12">
                    <table class="table table-hover" ng-show="pages.data.length>0" style="border-bottom: 1px solid #f4f4f4;">
                        <tr>
                          <th><input type="checkbox" ng-checked="UserIDList.length == pages.data.length" ng-click="selectAll()"></th>
                          <th style="text-align:center;">序号</th>
                          <th>用户名</th>
                          <th>角色</th>
                          <th>创建人</th>
                          <th>创建时间</th>
                          <th>操作</th>
                        </tr>

                        <tr style="cursor: pointer;" ng-repeat="item in pages.data">
                            <td><input type="checkbox" ng-checked="UserIDList.indexOf(item.id) != -1" ng-click="selectOne(item.id,$event)" ></td>
                            <td style="text-align: center;" ng-bind="$index+1"></td>
                            <td ng-bind="item.username"></td>
                            <td ng-bind="item.role"></td>
                            <td ng-bind="item.creatorname"></td>
                            <td ng-bind="item.created_at*1000 | date:'yyyy-MM-dd HH:mm'"></td>
                            <td>
                            <button class="btn btn-xs btn-default" ng-click="del(item);$event.stopPropagation();" data-toggle="tooltip" title="删除用户"><i class="fa fa-trash-o"></i></button>
                            <button class="btn btn-xs btn-default" ng-click="resetPassword(item);$event.stopPropagation();" data-toggle="tooltip" title="重置密码"><i class="fa fa-edit"></i></button>
                            </td>
                        </tr>
                      </table>
                </div>
              </div>


              <div class="row" >

                <div class="col-sm-12" style="min-height: 20px;">
                    <em>共有<span ng-bind="pages.count"></span>个用户</em>
                        <!-- angularjs分页 -->
                        <ul class="pagination pagination-sm no-margin pull-right" ng-if="pages.count>0">
                            <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)" ng-if="pages.pageNow>1">上一页</a></li>
                            <li><a href="javascript:void(0);" ng-click="getPage(1)" ng-if="pages.pageNow>1">1</a></li>
                            <li><a href="javascript:void(0);" ng-if="pages.pageNow>4">...</a></li>

                            <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow-2)" ng-bind="pages.pageNow-2" ng-if="pages.pageNow>3"></a></li>
                            <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)" ng-bind="pages.pageNow-1" ng-if="pages.pageNow>2"></a></li>
                            
                            <li class="active"><a href="javascript:void(0);" ng-bind="pages.pageNow"></a></li>

                            <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)" ng-bind="pages.pageNow+1" ng-if="pages.pageNow<pages.maxPage-1"></a></li>
                            <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow+2)" ng-bind="pages.pageNow+2" ng-if="pages.pageNow<pages.maxPage-2"></a></li>


                            <li><a href="javascript:void(0);" ng-if="pages.pageNow<pages.maxPage-3">...</a></li>

                            <li><a href="javascript:void(0);" ng-click="getPage(pages.maxPage)" ng-bind="pages.maxPage" ng-if="pages.pageNow<pages.maxPage"></a></li>
                            <li><a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)" ng-if="pages.pageNow<pages.maxPage">下一页</a></li>
                        </ul>
                        <!-- /.angularjs分页 -->
                </div>

              </div>


              <div style="display: none;" id="hideenBox">
                <div id="newUser" >
                  <form>
                    <div class="box-body">
                      <div class="form-group {{nameerror ? 'has-error':''}}">
                        <label for="InputVersion">用户名：</label>
                        <input class="form-control" ng-model="newUser.username">
                        <p class="help-block">请填写用户名</p>
                      </div>

                      <div class="form-group {{passworderror ? 'has-error':''}}">
                        <label for="InputVersion">设置密码：</label>
                        <input type="password" class="form-control" ng-model="newUser.password">
                        <p class="help-block">请填写8-30位密码,包含大写字母、小写字母、数字、特称字符</p>
                      </div>

                      <div class="form-group {{repassworderror ? 'has-error':''}}">
                        <label for="InputVersion">确认密码：</label>
                        <input type="password" class="form-control" ng-model="newUser.repassword">
                        <p class="help-block" ng-bind="repassworderror ? '密码不一致' : '　'"></p>
                      </div>

                      <div class="form-group">
                        <label for="InputVersion">角色：</label>
                        <input name='UserRole' type='radio' id="isshare" ng-checked="newUser.role == 'share'" ng-click="newUser.role = 'share';" style="margin: 4px 4px 4px 15px" />
                        <label for="isshare">共享用户</label>
                        <input name='UserRole' type='radio' id="isuser" ng-checked="newUser.role == 'user'" ng-click="newUser.role = 'user';" style="margin: 4px 4px 4px 15px" />
                        <label for="isuser">普通用户</label>
                        <input name='UserRole' type='radio' id="isadmin" ng-checked="newUser.role == 'admin'" ng-click="newUser.role = 'admin';" style="margin: 4px 4px 4px 15px" />
                        <label for="isadmin">管理员</label>
                      </div>
                    </div>
                  </form>
                </div>
                <div id="resetPassword" >
                  <form>
                    <div class="box-body">
                      <div class="form-group {{resetUser_passworderror ? 'has-error':''}}">
                        <label for="InputVersion">设置密码：</label>
                        <input type="password" class="form-control" ng-model="resetUser.password">
                        <p class="help-block">请填写8-30位密码,包含大写字母、小写字母、数字、特称字符</p>
                      </div>

                      <div class="form-group {{resetUser_repassworderror ? 'has-error':''}}">
                        <label for="InputVersion">确认密码：</label>
                        <input type="password" class="form-control" ng-model="resetUser.repassword">
                        <p class="help-block" ng-bind="resetUser_repassworderror ? '密码不一致' : '　'"></p>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </section>
          </div>
          <!-- ./user -->

        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>

</section>

           
<!-- /.content -->

<script type="text/javascript">
    var GroupList = <?= json_encode($GroupList) ?>;
</script>
<script type="text/javascript" src="/js/controllers/UserList.js"></script>













































