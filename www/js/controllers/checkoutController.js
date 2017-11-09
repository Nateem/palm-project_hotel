angular.module('app')
    .controller('checkoutController', function($rootScope, $scope, $filter, $compile, DTOptionsBuilder, DTColumnBuilder, $http, $q, notificationService) {
        $scope.controllerName = 'checkoutController';
        $scope.CURDATE = new Date();
        var vm = this;
        $scope.checkout = {};
        $scope.detail = "เลือกรายการขวามือ";
        vm.message = '';
        vm.checkout = checkout;
        vm.DelCheckin = DelCheckin;
        vm.SELECT_checkout_detail = SELECT_checkout_detail;

        vm.persons = {};
        vm.dtOptions = DTOptionsBuilder.fromFnPromise(function() {
                var defer = $q.defer();
                $http({
                        method: "POST",
                        url: 'models/checkout.model.php',
                        data: {
                            TYPES: 'SELECT_checkout',
                            CURRENT_DATA: $rootScope.globals.currentDATA
                        },
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function(data) {
                        //console.log(data);
                        if (data.ERROR == false) {
                            defer.resolve(data.DATA);
                        }
                    });
                return defer.promise;
            })
            .withPaginationType('full_numbers')
            .withOption('responsive', true)
            .withOption('createdRow', createdRow);


        vm.dtColumns = [
            DTColumnBuilder.newColumn(null).withTitle('Actions').notSortable()
            .renderWith(actionsHtml),
            DTColumnBuilder.newColumn('E_START').withTitle('เข้า').withClass("").renderWith(function(data,type,full){
                return $filter('amDateFormat')(data, 'YYYY/MM/DD , ddd');    //could use currency/date or any angular filter
            }),
            DTColumnBuilder.newColumn('E_END').withTitle('ออก').withClass("").renderWith(function(data,type,full){
                return $filter('amDateFormat')(data, 'YYYY/MM/DD , ddd');    //could use currency/date or any angular filter
            }),
            DTColumnBuilder.newColumn('BILLCODE').withTitle('เลขที่ใบเสร็จ'),
            DTColumnBuilder.newColumn('FULLNAME').withTitle('ผู้จอง'),
            DTColumnBuilder.newColumn('CREATED').withTitle('เมื่อ').withClass("").renderWith(function(data,type,full){
                return $filter('amDateFormat')(data, 'YYYY/MM/DD HH:mm:ss น.');    //could use currency/date or any angular filter
            }),
            DTColumnBuilder.newColumn('UPDATED').withTitle('แก้ไข').withClass("").renderWith(function(data,type,full){
                return $filter('amDateFormat')(data, 'YYYY/MM/DD HH:mm:ss น.');    //could use currency/date or any angular filter
            }),

        ];
        vm.dtInstance = {};
        vm.reloadData = reloadData;

        function reloadData() {
            vm.dtInstance.reloadData();
        };

        function createdRow(row, data, dataIndex) {
            // Recompiling so we can bind Angular directive to the DT
            $compile(angular.element(row).contents())($scope);
        }

        function actionsHtml(data, type, full, meta) {
            return '<button class="btn btn-info"  ng-click="showCase.SELECT_checkout_detail(' + full.ID + ')">' +
                '   <i class="fa fa-info-circle"></i>' +
                '</button>'+
                '<button class="btn btn-danger" ng-if="globals.currentDATA.USERLEVEL==\'admin\'"  ng-click="showCase.DelCheckin(' + full.ID + ')">' +
                '   <i class="fa fa-times"></i>' +
                '</button>';
        }

        function SELECT_checkout_detail(ID) {
            $http({
                    method: "POST",
                    url: 'models/checkout.model.php',
                    data: {
                        TYPES: 'SELECT_checkout_detail',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        ID: ID,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    $scope.checkout_detailRepeat = data.DATA;
                    $scope.FULLNAME = data.CHECKOUT.FNAME + " " + data.CHECKOUT.LNAME;
                    $scope.BILLCODE = data.CHECKOUT.BILLCODE;
                    $scope.E_START = data.CHECKOUT.E_START;
                    $scope.E_END = data.CHECKOUT.E_END;
                    $scope.E_BETWEEN = data.CHECKOUT.E_BETWEEN;
                    $scope.PRICE_BEDPLUS = data.CHECKOUT.PRICE_BEDPLUS;
                    $scope.OTHER_PRICE = data.CHECKOUT.OTHER_PRICE;
                    $scope.PAY = data.CHECKOUT.PAY;
                    $scope.CASH_CHANGE = data.CHECKOUT.CASH_CHANGE;
                    $scope.PRICE_TOTAL = data.CHECKOUT.PRICE_TOTAL;
                    $scope.DEPOSIT = data.CHECKOUT.DEPOSIT;                    
                    $scope.DISCOUNT = data.CHECKOUT.DISCOUNT;                    
                    $scope.CREATED = data.CHECKOUT.CREATED;                    
                    $scope.NOTE = data.CHECKOUT.NOTE;
                    $scope.PRICE_SUM_TOTAL = (Number($scope.PRICE_TOTAL) + Number($scope.OTHER_PRICE))-Number($scope.DEPOSIT);
                    $scope.OWE = Number($scope.PRICE_SUM_TOTAL) - Number($scope.PAY);
                    $scope.CHANGE = Number($scope.PAY) - Number($scope.PRICE_SUM_TOTAL);
                    
                    $(function(){
                        $("#ShowCheckinDetail").modal("show");
                    })
                    
                });
        }

        function DelCheckin(ID) {
            $scope.checkout.ID = ID;
            notificationService.notify({
                title: 'ยืนยัน',
                text: 'คุณต้องการยกเลิกรายการนี้ใช่หรือไม่?',
                hide: false,
                styling: "bootstrap3",
                animate: {
                    animate: true,
                    in_class: 'bounceInLeft',
                    out_class: 'bounceOutRight'
                },
                confirm: {
                    confirm: true,
                },
                buttons: {
                    closer: false,
                    sticker: false
                },
                history: {
                    history: false
                }
            }).get().on('pnotify.confirm', function() {
                $http({
                        method: "POST",
                        url: 'models/checkout.model.php',
                        data: {
                            TYPES: 'DELETE_checkout',
                            CURRENT_DATA: $rootScope.globals.currentDATA,
                            ID: ID,
                        },
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function(data) {
                        //console.log(data);
                        vm.dtInstance.reloadData();
                        notificationService.notify({
                            title: 'ระบบตอบรับ',
                            text: data.MSG,
                            styling: "bootstrap3",
                            type: data.TYPE,
                            icon: true
                        });
                    });
            }).on('pnotify.cancel', function() {
                //event
            });
        }

        function checkout(id) {
            //console.log(id);
            $scope.checkout.ID = id;
            $http({
                    method: "POST",
                    url: 'models/checkout.model.php',
                    data: {
                        TYPES: 'SELECT_orders_where',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        ORDER_ID: id
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.checkout.CUS_CODE = data.DATA.GENCODE;
                        $scope.checkout.PRICE = Number(data.DATA.PRICE);
                        $scope.chkCode(data.DATA.GENCODE);
                        $(function() {
                            $("#iCODE").focus(function() {
                                $(this).select();
                            });
                        })
                    }
                });
        }
    })
