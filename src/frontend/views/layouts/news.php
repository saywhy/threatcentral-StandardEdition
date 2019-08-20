<li class="dropdown tasks-menu" id="newsApp"   ng-controller="newsCtrl">
            <div id="news_hide_box" style="display: none;">
              <div id="newsDetail">
                <div ng-bind-html="contentHtml"></div>
                <div class="text-muted pull-right">
                  <small>
                    <i class="fa fa-clock-o"></i>
                    <sapn ng-bind="nowNews.created_at*1000 | date:'yyyy-MM-dd HH:mm:ss'"></sapn>
                  </small>
                </div>
              </div>
            </div>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger" ng-if="countNews()" ng-bind="countNews()"></span>
              <span class="label label-danger"></span>
            </a>
            <ul class="dropdown-menu" id="news-menu">
              <li class="header">你有<span ng-bind="countNews()"></span>条未读消息</li>
              <li>
                <ul class="menu">
                  <li  ng-click="showNews(item)" ng-repeat="item in newsList">
                    <a href="javascript:void(0);">
                      <div class="news-lable-unread" ng-if="item.status == 1">
                        <i class="fa fa-circle-o text-aqua"></i>
                      </div>
                      <div class="text-black news-title">
                        <span ng-bind="item.title"></span>
                      </div>

                      <div class="text-muted pull-right">
                        <small>
                          <i class="fa fa-clock-o"></i>
                          <sapn ng-bind="getMoment(item)"></sapn>
                        </small>
                      </div>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
