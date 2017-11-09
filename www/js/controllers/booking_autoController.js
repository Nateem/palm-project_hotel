angular.module('app')
    .controller('booking_autoController', function($rootScope, $scope, $http, notificationService) {

        $scope.OnSubmit = function(formName) {

            $http({
                    method: "POST",
                    url: 'models/booking_auto.model.php',
                    data: {
                        TYPES: 'AUTO_BOOKING',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        FORM_DATA: $scope.form
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.AutoBookingRepeat = data.DATA;
                    }
                    notificationService.notify({
                        title: 'ระบบตอบรับ',
                        text: data.MSG,
                        styling: "bootstrap3",
                        type: data.TYPE,
                        icon: true
                    });
                });
        } 
        $scope.SELECT_TEMP = function() {

            $http({
                    method: "POST",
                    url: 'models/booking_auto.model.php',
                    data: {
                        TYPES: 'SELECT_TEMP',
                        CURRENT_DATA: $rootScope.globals.currentDATA
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.tempRepeat = data.DATA;
                    }                    
                });
        }  
         $scope.SELECT_room_type = function() {

            $http({
                    method: "POST",
                    url: 'models/booking_auto.model.php',
                    data: {
                        TYPES: 'SELECT_room_type',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.room_typeRepeat = data.DATA;
                    }

                });
        }
        $scope.SELECT_room_type_WHERE = function(ID) {

            $http({
                    method: "POST",
                    url: 'models/booking_auto.model.php',
                    data: {
                        TYPES: 'SELECT_room_type_WHERE',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        ID:ID
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    //console.log(ID);
                    if (data.ERROR == false) {
                        $scope.room_typeWhere = data.DATA;
                    }

                });
        }
        var loadInit = function() {
            var DATE = moment(new Date());
            $scope.form = {};        
            $scope.SELECT_room_type();
            $scope.rangOptions = {
                opens: 'left',
                buttonClasses: ['btn btn-sm btn-default'],
                applyClass: 'btn-sm btn-primary',
                cancelClass: 'btn-sm btn-default',
                format: 'DD/MM/YYYY',
                separator: ' To ',
                locale: $rootScope.daterangepickerLocale
            };
           
            $scope.CURDATE = DATE;           
            $scope.form.DATERANG = { startDate: DATE.format(), endDate: DATE.add(1, 'days').format() };
            //$scope.DATE_BETWEEN = DATE.range($scope.form.DATERANG.startDate,$scope.form.DATERANG.endDate);
            $scope.form.ROOM_TYPE = "";
            $scope.form.NUM_ROOM = 1;
        }
        loadInit(); 

    })
