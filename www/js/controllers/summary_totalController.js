angular.module('app')
    .controller('summary_totalController', function($rootScope, $scope, $http) {
        $scope.controllerName = 'summary_totalController';

        var loadInit = function() {
            $scope.form = {};
            $scope.rangOptions = {
                singleDatePicker: true,
                opens: 'left',
                buttonClasses: ['btn btn-sm btn-default'],
                applyClass: 'btn-sm btn-primary',
                cancelClass: 'btn-sm btn-default',
                format: 'DD/MM/YYYY',
                separator: ' To ',
                locale: $rootScope.daterangepickerLocale
            };
            $scope.form.E_START = new Date();
            $scope.form.E_END = new Date();
            $scope.roles_orders_type = [
                { id: 1, text: "การจอง" },
                { id: 2, text: "เช็คอิน" },
                { id: 3, text: "กำลังปรับปรุง" }
            ];
            $scope.orders_type = {
                roles: [1, 2, 3]
            };
        }
        loadInit();

        $scope.dataSummary = function() {
            return $http({
                    method: "POST",
                    url: 'models/summary_total.model.php',
                    data: {
                        TYPES: 'DATA_SUMMARY',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        FORM_DATA: $scope.form,
                        ORDERS_TYPE:$scope.orders_type.roles
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.dataSumRepeat = data.DATA;
                    }

                });

        }

        $scope.ShowModal = function() {
            $scope.dataSummary().then(function success(response) {
                $scope.getTotal = function() {
                    var total = {};
                    total.PRICE_BEDPLUS = 0;
                    total.DISCOUNT = 0;
                    total.E_BETWEEN = 0;
                    total.PRICE = 0;
                    total.OTHER_PRICE = 0;
                    total.SumTotal = 0;
                    var sumPrice = [];
                    for (var i = 0; i < $scope.dataSumRepeat.length; i++) {
                        var rooms = $scope.dataSumRepeat[i];
                        total.PRICE_BEDPLUS += Number(rooms.PRICE_BEDPLUS);
                        total.DISCOUNT += Number(rooms.DISCOUNT);
                        total.E_BETWEEN += Number(rooms.E_BETWEEN);
                        sumPrice[i] = 0;
                        for (var n = 0; n < rooms.events_detail.length; n++) {
                            var p = rooms.events_detail[n];
                            sumPrice[i] += Number(p.PRICE);
                        }
                        total.PRICE += Number(sumPrice[i]);
                        total.OTHER_PRICE += Number(rooms.OTHER_PRICE);
                        total.SumTotal += Number(rooms.PRICE_TOTAL);
                    }
                    return total;
                }
                $(function() {
                    $("#ModalPrint").modal("show");
                })
            }, function fail(response) {

            });

        }
    })
