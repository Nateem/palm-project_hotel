angular.module('app')
    .controller('checkinController', function($rootScope, $scope, $filter, $compile, DTOptionsBuilder, DTColumnBuilder, $http, $q, notificationService) {
        $scope.controllerName = 'checkinController';
        var vm = this;
        $scope.checkin = {};
        $scope.detail = "เลือกรายการขวามือ";
        vm.message = '';
        vm.checkin = checkin;
        vm.DelCheckin = DelCheckin;
        vm.SELECT_checkin_detail = SELECT_checkin_detail;

        vm.persons = {};
        vm.dtOptions = DTOptionsBuilder.fromFnPromise(function() {
                var defer = $q.defer();
                $http({
                        method: "POST",
                        url: 'models/checkin.model.php',
                        data: {
                            TYPES: 'SELECT_checkin',
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
                return $filter('amDateFormat')(data, 'ddd , DD/MM/YYYY');    //could use currency/date or any angular filter
            }),
            DTColumnBuilder.newColumn('E_END').withTitle('ออก').withClass("").renderWith(function(data,type,full){
                return $filter('amDateFormat')(data, 'ddd , DD/MM/YYYY');    //could use currency/date or any angular filter
            }),
            
            DTColumnBuilder.newColumn('FULLNAME').withTitle('ลูกค้า'),
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
            return '<button class="btn btn-info"  ng-click="showCase.SELECT_checkin_detail(' + full.ID + ')">' +
                '   <i class="fa fa-info-circle"></i>' +
                '</button>'+
                '<button class="btn btn-danger" ng-if="globals.currentDATA.USERLEVEL==\'admin\'"  ng-click="showCase.DelCheckin(' + full.ID + ')">' +
                '   <i class="fa fa-times"></i>' +
                '</button>';
        }

        function SELECT_checkin_detail(ID) {
            $http({
                    method: "POST",
                    url: 'models/checkin.model.php',
                    data: {
                        TYPES: 'SELECT_checkin_detail',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        ID: ID,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    $scope.checkin_detailRepeat = data.DATA;
                    $scope.FULLNAME = data.CHECKIN.FNAME + " " + data.CHECKIN.LNAME;
                    $scope.BILLCODE = data.CHECKIN.BILLCODE;
                    $scope.E_START = data.CHECKIN.E_START;
                    $scope.E_END = data.CHECKIN.E_END;
                    $scope.E_BETWEEN = data.CHECKIN.E_BETWEEN;
                    $scope.PRICE_BEDPLUS = data.CHECKIN.PRICE_BEDPLUS;
                    $scope.PRICE_TOTAL = data.CHECKIN.PRICE_TOTAL;
                    $scope.DISCOUNT = data.CHECKIN.DISCOUNT;
                    $scope.PAY = data.CHECKIN.PAY;                    
                    $scope.DEPOSIT = data.CHECKIN.DEPOSIT;
                    $scope.PRICE_SUM_TOTAL = Number($scope.PRICE_TOTAL) - Number($scope.DEPOSIT);
                    $scope.NOTE = data.CHECKIN.NOTE;
                    $scope.CREATED = data.CHECKIN.CREATED; 
                    $scope.OWE = Number($scope.PRICE_SUM_TOTAL) - Number($scope.PAY);
                    $scope.CHANGE = Number($scope.PAY) - Number($scope.PRICE_SUM_TOTAL);
                    $(function(){
                        $("#ShowCheckinDetail").modal("show");
                    })
                    
                });
        }

        function DelCheckin(ID) {
            $scope.checkin.ID = ID;
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
                        url: 'models/checkin.model.php',
                        data: {
                            TYPES: 'DELETE_checkin',
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

        function checkin(id) {
            //console.log(id);
            $scope.checkin.ID = id;
            $http({
                    method: "POST",
                    url: 'models/checkin.model.php',
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
                        $scope.checkin.CUS_CODE = data.DATA.GENCODE;
                        $scope.checkin.PRICE = Number(data.DATA.PRICE);
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
