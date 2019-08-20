var rootScope;
var myApp = angular.module("myApp", []);
myApp.controller("aptDetailCtrl", function($scope, $rootScope, $http, $filter) {
  rootScope = $scope;
  $scope.init = function() {
    $scope.card_detail_data = {};
    $scope.initial_access = [];
    $scope.execution = [];
    $scope.persistence = [];
    $scope.privilege_escalation = [];
    $scope.defense_evasion = [];
    $scope.credential_access = [];
    $scope.discovery = [];
    $scope.lateral_movement = [];
    $scope.collection = [];
    $scope.execution = [];
    $scope.command_control = [];

    var card_detail_data = localStorage.getItem("card_detail_data");
    $scope.card_detail_data = JSON.parse(card_detail_data);

    console.log($scope.card_detail_data.persistence);
    console.log($scope.card_detail_data);

    // initial_access;
    if ($scope.card_detail_data.initial_access != "") {
      for (k in JSON.parse($scope.card_detail_data.initial_access)) {
        var obj = {};
        obj.name = k;
        obj.value = JSON.parse($scope.card_detail_data.initial_access)[k];
        $scope.initial_access.push(obj);
      }
    }
    // execution;
    if ($scope.card_detail_data.execution != "") {
      for (k in JSON.parse($scope.card_detail_data.execution)) {
        var obj = {};
        obj.name = k;
        obj.value = JSON.parse($scope.card_detail_data.execution)[k];
        $scope.execution.push(obj);
      }
    }

    // Persistence;
    if ($scope.card_detail_data.persistence != "") {
    }
    for (k in JSON.parse($scope.card_detail_data.persistence)) {
      var obj = {};
      obj.name = k;
      obj.value = JSON.parse($scope.card_detail_data.persistence)[k];
      $scope.persistence.push(obj);
    }

    //Privilege Escalation:
    if ($scope.card_detail_data.privilege_escalation != "") {
      for (k in JSON.parse($scope.card_detail_data.privilege_escalation)) {
        var obj = {};
        obj.name = k;
        obj.value = JSON.parse($scope.card_detail_data.privilege_escalation)[k];
        $scope.privilege_escalation.push(obj);
      }
    }

    //Defense Evasion
    if ($scope.card_detail_data.defense_evasion != "") {
      for (k in JSON.parse($scope.card_detail_data.defense_evasion)) {
        var obj = {};
        obj.name = k;
        obj.value = JSON.parse($scope.card_detail_data.defense_evasion)[k];
        $scope.defense_evasion.push(obj);
      }
    }

    //Credential Access
    if ($scope.card_detail_data.credential_access != "") {
      for (k in JSON.parse($scope.card_detail_data.credential_access)) {
        var obj = {};
        obj.name = k;
        obj.value = JSON.parse($scope.card_detail_data.credential_access)[k];
        $scope.credential_access.push(obj);
      }
    }
    // Discovery:
    if ($scope.card_detail_data.discovery != "") {
      for (k in JSON.parse($scope.card_detail_data.discovery)) {
        var obj = {};
        obj.name = k;
        obj.value = JSON.parse($scope.card_detail_data.discovery)[k];
        $scope.discovery.push(obj);
      }
    }

    //  Lateral Movement:
    if ($scope.card_detail_data.lateral_movement != "") {
      for (k in JSON.parse($scope.card_detail_data.lateral_movement)) {
        var obj = {};
        obj.name = k;
        obj.value = JSON.parse($scope.card_detail_data.lateral_movement)[k];
        $scope.lateral_movement.push(obj);
      }
    }

    // Collection
    if ($scope.card_detail_data.collection != "") {
      for (k in JSON.parse($scope.card_detail_data.collection)) {
        var obj = {};
        obj.name = k;
        obj.value = JSON.parse($scope.card_detail_data.collection)[k];
        $scope.collection.push(obj);
      }
    }

    // Exfiltration;
    if ($scope.card_detail_data.execution != "") {
      for (k in JSON.parse($scope.card_detail_data.execution)) {
        var obj = {};
        obj.name = k;
        obj.value = JSON.parse($scope.card_detail_data.execution)[k];
        $scope.execution.push(obj);
      }
    }

    // Command and Control
    if ($scope.card_detail_data.command_control != "") {
      for (k in JSON.parse($scope.card_detail_data.command_control)) {
        var obj = {};
        obj.name = k;
        obj.value = JSON.parse($scope.card_detail_data.command_control)[k];
        $scope.command_control.push(obj);
      }
    }

    $scope.all_array = [
      {
        title: "Initial Access",
        card: $scope.initial_access,
      },
      {
        title: "Execution",
        card: $scope.execution,
      },
      {
        title: "Persistence",
        card: $scope.persistence,
      },
      {
        title: "Privilege Escalation",
        card: $scope.privilege_escalation,
      },
      {
        title: "Defense Evasion",
        card: $scope.defense_evasion,
      },
      {
        title: "Credential Access",
        card: $scope.credential_access,
      },
      {
        title: "Discovery",
        card: $scope.discovery,
      },
      {
        title: "Lateral Movement",
        card: $scope.lateral_movement,
      },
      {
        title: "Collection",
        card: $scope.collection,
      },
      {
        title: "Exfiltration",
        card: $scope.execution,
      },
      {
        title: "Command and Control",
        card: $scope.command_control,
      },
    ];
    console.log($scope.all_array);
  };

  $scope.init();
});
