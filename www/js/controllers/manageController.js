angular.module('app')
	.controller('manageController', function($rootScope,$state,$interval,$scope,notificationService){
		$scope.controllerName = 'manageController';		
		if ($rootScope.globals.currentDATA.LEVEL < $rootScope.globals.statePAGE_VIEW_LEVEL) {
            $state.go('home');
            notificationService.notify({
                title: 'คำเตือน',
                text: 'Please login! | ระดับไม่เพียงพอสำหรับเข้าใช้เมนูนี้',
                styling: "bootstrap3",
                type: "warning",
                icon: true
            });
        }
	})