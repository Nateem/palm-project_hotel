angular.module('app')
	.controller('homeController', function($rootScope,$interval,$scope,$http){
		$scope.controllerName = 'homeController';
		$scope.menu = menu_json;
		$scope.THdateConvert =  moment(new Date()).format("LLLL");
		$scope.ENdateConvert =  new Date();
        $interval(function() {           
            $scope.THdateConvert = moment(new Date()).format("LLLL");
            $scope.ENdateConvert =  new Date();
        }, 1000);
        var LoadCountRoom = function() {

            $http({
                    method: "POST",
                    url: 'models/home.model.php',
                    data: {
                        TYPES: 'SELECT_events_count',
                        CURRENT_DATA: $rootScope.globals.currentDATA,                       
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.room_checkin = data.room_checkin;
                        $scope.room_repair = data.room_repair;
                        $scope.room_booking = data.room_booking;
                        $scope.room_available = data.room_available;    

                        $scope.room_checkin_percent = (Number(data.room_checkin)*Number(100))/Number(data.room_all);
                        $scope.room_repair_percent = (Number(data.room_repair)*Number(100))/Number(data.room_all);
                        $scope.room_booking_percent = (Number(data.room_booking)*Number(100))/Number(data.room_all);        
                        $scope.room_available_percent = (Number(data.room_available)*Number(100))/Number(data.room_all);                
                    }
                });
        }
        LoadCountRoom();
	})