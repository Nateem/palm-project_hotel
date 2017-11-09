angular.module('app')
    .controller('bookingController', function($rootScope, $scope, $filter, $compile, DTOptionsBuilder, DTColumnBuilder, $http, $q, notificationService) {
        $scope.controllerName = 'bookingController';
        var vm = this;
        $scope.booking = {};
        $scope.detail = "เลือกรายการขวามือ";
        vm.message = '';
        vm.booking = booking;
        vm.DelBooking = DelBooking;
        vm.SELECT_booking_detail = SELECT_booking_detail;

        vm.persons = {};
        vm.dtOptions = DTOptionsBuilder.fromFnPromise(function() {
                var defer = $q.defer();
                $http({
                        method: "POST",
                        url: 'models/booking.model.php',
                        data: {
                            TYPES: 'SELECT_booking',
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
            DTColumnBuilder.newColumn('BILLCODE').withTitle('เลขที่ใบเสร็จ'),
            DTColumnBuilder.newColumn('E_START').withTitle('เข้า').withClass("").renderWith(function(data,type,full){
                return $filter('amDateFormat')(data, 'ddd, DD/MM/YYYY');    //could use currency/date or any angular filter
            }),
            DTColumnBuilder.newColumn('E_END').withTitle('ออก').withClass("").renderWith(function(data,type,full){
                return $filter('amDateFormat')(data, 'ddd, DD/MM/YYYY');    //could use currency/date or any angular filter
            }),            
            DTColumnBuilder.newColumn('FULLNAME').withTitle('ผู้จอง'),
            DTColumnBuilder.newColumn('CREATED').withTitle('เมื่อ').withClass("").renderWith(function(data,type,full){
                return $filter('amDateFormat')(data, 'ddd, DD/MM/YYYY');    //could use currency/date or any angular filter
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
            return '<button class="btn btn-info"  ng-click="showCase.SELECT_booking_detail(' + full.ID + ')">' +
                '   <i class="fa fa-info-circle"></i>' +
                '</button>'+
                '<button class="btn btn-danger" ng-if="globals.currentDATA.USERLEVEL==\'admin\'"  ng-click="showCase.DelBooking(' + full.ID + ')">' +
                '   <i class="fa fa-times"></i>' +
                '</button>';
        }

        function SELECT_booking_detail(ID) {
            $http({
                    method: "POST",
                    url: 'models/booking.model.php',
                    data: {
                        TYPES: 'SELECT_booking_detail',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        ID: ID,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    $scope.booking_detailRepeat = data.DATA;
                    $scope.FULLNAME = data.BOOKING.FNAME + " " + data.BOOKING.LNAME;
                    $scope.BILLCODE = data.BOOKING.BILLCODE;
                    $scope.E_START = data.BOOKING.E_START;
                    $scope.E_END = data.BOOKING.E_END;
                    $scope.CREATED = data.BOOKING.CREATED;
                    $scope.E_BETWEEN = data.BOOKING.E_BETWEEN;                   
                    $scope.PRICE_BEDPLUS = data.BOOKING.PRICE_BEDPLUS;
                    $scope.PRICE_TOTAL = data.BOOKING.PRICE_TOTAL;
                    $scope.DISCOUNT = data.BOOKING.DISCOUNT;
                    $scope.DEPOSIT = data.BOOKING.DEPOSIT;
                    $scope.PRICE_SUM_TOTAL = Number($scope.PRICE_TOTAL) - Number($scope.DEPOSIT);
                    $scope.PAY = data.BOOKING.PAY;                   
                    $scope.NOTE = data.BOOKING.NOTE;
                    $scope.OWE = Number($scope.PRICE_SUM_TOTAL) - Number($scope.PAY);
                    $scope.CHANGE = Number($scope.PAY) - Number($scope.PRICE_SUM_TOTAL);
                    $(function(){
                        $("#ShowBookingDetail").modal("show");
                    })
                    
                });
        }

        function DelBooking(ID) {
            $scope.booking.ID = ID;
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
                        url: 'models/booking.model.php',
                        data: {
                            TYPES: 'DELETE_booking',
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

        function booking(id) {
            //console.log(id);
            $scope.booking.ID = id;
            $http({
                    method: "POST",
                    url: 'models/booking.model.php',
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
                        $scope.booking.CUS_CODE = data.DATA.GENCODE;
                        $scope.booking.PRICE = Number(data.DATA.PRICE);
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
