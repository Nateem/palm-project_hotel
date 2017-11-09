angular.module('app')
    .controller('not_availableController', function($rootScope, $scope, $filter, $compile, DTOptionsBuilder, DTColumnBuilder, $http, $q, notificationService) {
        $scope.controllerName = 'not_availableController';
        $scope.CURDATE = new Date();
        var vm = this;
        $scope.not_available = {};
        $scope.detail = "เลือกรายการขวามือ";
        vm.message = '';
        vm.not_available = not_available;
        vm.DelNotA = DelNotA;
        vm.SELECT_not_available_detail = SELECT_not_available_detail;

        vm.persons = {};
        vm.dtOptions = DTOptionsBuilder.fromFnPromise(function() {
                var defer = $q.defer();
                $http({
                        method: "POST",
                        url: 'models/not_available.model.php',
                        data: {
                            TYPES: 'SELECT_not_available',
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
            DTColumnBuilder.newColumn('E_START').withTitle('เริ่ม').withClass("").renderWith(function(data,type,full){
                return $filter('amDateFormat')(data, 'YYYY/MM/DD , ddd');    //could use currency/date or any angular filter
            }),
            DTColumnBuilder.newColumn('E_END').withTitle('ถึง').withClass("").renderWith(function(data,type,full){
                return $filter('amDateFormat')(data, 'YYYY/MM/DD , ddd');    //could use currency/date or any angular filter
            }),
            DTColumnBuilder.newColumn('BILLCODE').withTitle('เลขที่ใบเสร็จ'),
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
            return '<button class="btn btn-info"  ng-click="showCase.SELECT_not_available_detail(' + full.ID + ')">' +
                '   <i class="fa fa-info-circle"></i>' +
                '</button>'+
                '<button class="btn btn-danger" ng-if="globals.currentDATA.USERLEVEL==\'admin\'"  ng-click="showCase.DelNotA(' + full.ID + ')">' +
                '   <i class="fa fa-times"></i>' +
                '</button>';
        }

        function SELECT_not_available_detail(ID) {
            $http({
                    method: "POST",
                    url: 'models/not_available.model.php',
                    data: {
                        TYPES: 'SELECT_not_available_detail',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        ID: ID,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    $scope.not_available_detailRepeat = data.DATA;
                    $scope.BILLCODE = data.NA.BILLCODE;
                    $scope.E_START = data.NA.E_START;
                    $scope.E_END = data.NA.E_END;
                    $scope.E_BETWEEN = data.NA.E_BETWEEN;
                    $scope.NOTE = data.NA.NOTE;
                    
                    $(function(){
                        $("#ShowNotADetail").modal("show");
                    })
                    
                });
        }

        function DelNotA(ID) {
            $scope.not_available.ID = ID;
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
                        url: 'models/not_available.model.php',
                        data: {
                            TYPES: 'DELETE_not_available',
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

        function not_available(id) {
            //console.log(id);
            $scope.not_available.ID = id;
            $http({
                    method: "POST",
                    url: 'models/not_available.model.php',
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
                        $scope.not_available.CUS_CODE = data.DATA.GENCODE;
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
