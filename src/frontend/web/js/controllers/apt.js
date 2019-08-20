var rootScope;
var myApp = angular.module("myApp", []);
myApp.controller("aptCtrl", function($scope, $rootScope, $http, $filter) {
  rootScope = $scope;
  $scope.init = function() {
    $scope.attack_source_more = true;
    $scope.industry_more = true;
    $scope.attack_target_more = true;
    $scope.motive_more = true;
    $rootScope.card_detail_data = {};
    $scope.get_card_list();
    $scope.get_data();
    $scope.more_if_source = true;
    $scope.more_if_source_length = 4;
    $scope.more_if_industry = true;
    $scope.more_if_industry_length = 4;

    $scope.more_if_target = true;
    $scope.more_if_target_length = 4;
    $scope.more_if_motivation = true;
    $scope.more_if_motivation_length = 4;

    $scope.closed_search_if = false;
    $scope.search_length = 0;

    $scope.more_source_if = true;
    $scope.more_industry_if = true;
    $scope.more_target_if = true;
    $scope.more_motive_if = true;
  };
  //   点击item
  $scope.item_p_click = function(item, name) {
    $scope.closed_search_if = true;
    if (item.class == false) {
      item.class = true;
      if (name == "source") {
        $scope.attack_source_search.push(item);
      }
      if (name == "industry") {
        $scope.industry_search.push(item);
      }
      if (name == "target") {
        $scope.attack_target_search.push(item);
      }
      if (name == "motivation") {
        $scope.motive_search.push(item);
      }
    }
    $scope.get_card_list(item, name);
  };
  //   删除搜索项
  $scope.del_name = function(item, name) {
    if (name == "source") {
      angular.forEach($scope.attack_source_search, function(k, index) {
        if (item.name == k.name) {
          $scope.attack_source_search.splice(index, 1);
        }
      });
      angular.forEach($scope.attack_source, function(k) {
        if (item.name == k.name) {
          k.class = false;
        }
      });
    }
    if (name == "industry") {
      angular.forEach($scope.industry_search, function(k, index) {
        if (item.name == k.name) {
          $scope.industry_search.splice(index, 1);
        }
      });
      angular.forEach($scope.industry, function(k) {
        if (item.name == k.name) {
          k.class = false;
        }
      });
    }
    if (name == "target") {
      angular.forEach($scope.attack_target_search, function(k, index) {
        if (item.name == k.name) {
          $scope.attack_target_search.splice(index, 1);
        }
      });
      angular.forEach($scope.attack_target, function(k) {
        if (item.name == k.name) {
          k.class = false;
        }
      });
    }
    if (name == "motivation") {
      angular.forEach($scope.motive_search, function(k, index) {
        if (item.name == k.name) {
          $scope.motive_search.splice(index, 1);
        }
      });
      angular.forEach($scope.motive, function(k) {
        if (item.name == k.name) {
          k.class = false;
        }
      });
    }
    if (
      $scope.attack_source_search.length == 0 &&
      $scope.industry_search.length == 0 &&
      $scope.attack_target_search.length == 0 &&
      $scope.motive_search.length == 0
    ) {
      $scope.card_list = [];
    }
    $scope.get_card_list(item, name);
  };
  $scope.get_data = function() {
    $http.get(" /search/apt-filter").then(
      function success(rsp) {
        $scope.attack_source = [];
        $scope.attack_source_search = [];
        $scope.industry = [];
        $scope.industry_search = [];
        $scope.attack_target = [];
        $scope.attack_target_search = [];
        $scope.motive = [];
        $scope.motive_search = [];
        console.log(JSON.parse(rsp.data));
        // 攻击来源
        for (k in JSON.parse(rsp.data).data.attack_source) {
          var obj = {};
          if (k != "") {
            obj.name = k;
            obj.num = JSON.parse(rsp.data).data.attack_source[k];
            obj.class = false;
            $scope.attack_source.push(obj);
          }
        }
        for (k in JSON.parse(rsp.data).data.attack_source) {
          var obj = {};
          if (k == "") {
            obj.name = "其他";
            obj.num = JSON.parse(rsp.data).data.attack_source[k];
            obj.class = false;
            $scope.attack_source.push(obj);
          }
        }
        console.log($scope.attack_source);
        // angular.copy($scope.attack_source, $scope.attack_source_search);
        // 针对行业
        for (k in JSON.parse(rsp.data).data.target_industry) {
          var obj = {};
          if (k != "") {
            obj.name = k;
            obj.num = JSON.parse(rsp.data).data.target_industry[k];
            obj.class = false;
            $scope.industry.push(obj);
          }
        }
        for (k in JSON.parse(rsp.data).data.target_industry) {
          var obj = {};
          if (k == "") {
            obj.name = "其他";
            obj.num = JSON.parse(rsp.data).data.target_industry[k];
            obj.class = false;
            $scope.industry.push(obj);
          }
        }
        // angular.copy($scope.industry, $scope.industry_search);
        // 攻击目标
        for (k in JSON.parse(rsp.data).data.attack_target) {
          var obj = {};
          if (k != "") {
            obj.name = k;
            obj.num = JSON.parse(rsp.data).data.attack_target[k];
            obj.class = false;
            $scope.attack_target.push(obj);
          }
        }
        for (k in JSON.parse(rsp.data).data.attack_target) {
          var obj = {};
          if (k == "") {
            obj.name = "其他";
            obj.num = JSON.parse(rsp.data).data.attack_target[k];
            obj.class = false;
            $scope.attack_target.push(obj);
          }
        }
        // angular.copy($scope.attack_target, $scope.attack_target_search);
        // 动机
        if (JSON.parse(rsp.data).data.motive) {
          for (k in JSON.parse(rsp.data).data.motive) {
            var obj = {};
            if (k != "") {
              obj.name = k;
              obj.num = JSON.parse(rsp.data).data.motive[k];
              obj.class = false;
              $scope.motive.push(obj);
            }
          }
          for (k in JSON.parse(rsp.data).data.motive) {
            var obj = {};
            if (k == "") {
              obj.name = "其他";
              obj.num = JSON.parse(rsp.data).data.motive[k];
              obj.class = false;
              $scope.motive.push(obj);
            }
          }
        }
      },
      function err(rsp) {}
    );
  };
  $scope.closed_search = function() {
    $scope.attack_source_search = [];
    $scope.industry_search = [];
    $scope.attack_target_search = [];
    $scope.motive_search = [];
    $scope.closed_search_if = false;
    angular.forEach($scope.attack_source, function(k) {
      k.class = false;
    });
    angular.forEach($scope.industry, function(k) {
      k.class = false;
    });
    angular.forEach($scope.attack_target, function(k) {
      k.class = false;
    });
    angular.forEach($scope.motive, function(k) {
      k.class = false;
    });
    $scope.get_card_list();
  };
  //   获取卡片信息
  $scope.get_card_list = function(item, name) {
    $scope.loading = zeroModal.loading(4);
    $scope.attack_source_params = [];
    $scope.attack_target_params = [];
    $scope.target_industry_params = [];
    $scope.motive_params = [];
    if (item && name) {
      if ($scope.attack_source_search.length > 0) {
        angular.forEach($scope.attack_source_search, function(item) {
          $scope.attack_source_params.push(item.name);
        });
      }
      if ($scope.attack_target_search.length > 0) {
        angular.forEach($scope.attack_target_search, function(item) {
          $scope.attack_target_params.push(item.name);
        });
      }
      if ($scope.industry_search.length > 0) {
        angular.forEach($scope.industry_search, function(item) {
          $scope.target_industry_params.push(item.name);
        });
      }
      if ($scope.motive_search.length > 0) {
        angular.forEach($scope.motive_search, function(item) {
          $scope.motive_params.push(item.name);
        });
      }
      var obj = {
        params: {
          attack_source: $scope.attack_source_params.join(","),
          attack_target: $scope.attack_target_params.join(","),
          target_industry: $scope.target_industry_params.join(","),
          motive: $scope.motive_params.join(",")
        }
      };
      $http.get("/search/apt-list", obj).then(
        function success(rsp) {
          console.log(rsp);
          $scope.card_list = rsp.data.data;
          zeroModal.close($scope.loading);
        },
        function err(rsp) {}
      );
    } else {
      $http.get("/search/apt-list").then(
        function success(rsp) {
          console.log(rsp);
          $scope.card_list = rsp.data.data;
          zeroModal.close($scope.loading);
        },
        function err(rsp) {}
      );
    }
  };
  $scope.card_detail = function(item) {
    $rootScope.card_detail_data = item;
    localStorage.setItem("card_detail_data", JSON.stringify(item));
    window.location.href = "/search/apt-detail?id=" + item.id;
  };

  $scope.more_source = function(blean) {
    $scope.more_source_if = !blean;
  };
  $scope.more_industry = function(blean) {
    $scope.more_industry_if = !blean;
  };
  $scope.more_target = function(blean) {
    $scope.more_target_if = !blean;
  };
  $scope.more_motive = function(blean) {
    $scope.more_motive_if = !blean;
  };
  $scope.init();
});
