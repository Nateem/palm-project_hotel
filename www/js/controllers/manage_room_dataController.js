angular.module("app")
    .controller("manage_room_dataController", function($rootScope, $scope,$state, $filter, $compile, DTOptionsBuilder, DTColumnBuilder, $http, $q, notificationService) {
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

        var vm = this;
        $scope.room_data = {};
        $scope.detail = "";
        vm.message = '';
        vm.actionEdit = actionEdit;
        vm.deleteType = deleteType;
        vm.persons = {};
        vm.dtOptions = DTOptionsBuilder.fromFnPromise(function() {
                var defer = $q.defer();
                $http({
                        method: "POST",
                        url: 'models/manage_room_data.model.php',
                        data: {
                            TYPES: 'SELECT_room_data',
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
            .withOption('createdRow', createdRow)
            .withDOM('frtip')
            .withButtons([{
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i> Excel',
                className: "btn-default",
            }, {
                extend: 'print',
                text: '<i class="fa fa-print"></i> พิมพ์',
                className: "btn-default"
            }]);


        vm.dtColumns = [
            DTColumnBuilder.newColumn(null).withTitle('').notSortable()
            .renderWith(actionsLeftHtml),
            DTColumnBuilder.newColumn('NAME_TYPE').withTitle('ประเภทห้องพัก'),
            DTColumnBuilder.newColumn('CODE').withTitle('รหัสห้องพัก'),
            DTColumnBuilder.newColumn('NAME').withTitle('ชื่อห้องพัก'),           
            DTColumnBuilder.newColumn(null).withTitle('').notSortable()
            .renderWith(actionsRightHtml),


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

        function actionsLeftHtml(data, type, full, meta) {
            return '<button class="btn btn-warning" ng-click="showCase.actionEdit(' + full.ID + ')">' +
                '   <i class="fa fa-edit"></i>' +
                '</button>';
        }

        function actionsRightHtml(data, type, full, meta) {
            return '<button class="btn btn-danger btn-sm" ng-click="showCase.deleteType(' + full.ID + ')">' +
                '   <i class="fa fa-trash-o"></i>' +
                '</button>';
        }

        function actionEdit(id) {
            //console.log(id);
            $scope.room_data.ID = id;
            $http({
                    method: "POST",
                    url: 'models/manage_room_data.model.php',
                    data: {
                        TYPES: 'SELECT_room_data_where',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        ID: id
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.room_data.CODE = data.DATA.CODE;
                        $scope.room_data.NAME = data.DATA.NAME;
                        $scope.room_data.room_type_ID = data.DATA.room_type_ID;
                    }
                });
        }
        var loadRoomType = function() {
            $http({
                    method: "POST",
                    url: 'models/manage_room_data.model.php',
                    data: {
                        TYPES: 'SELECT_room_type',
                        FORM_DATA: $scope.room_data,
                        CURRENT_DATA: $rootScope.globals.currentDATA
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if(data.ERROR==false){
                    	$scope.room_typeRepeat = data.DATA;
                    }
                });
        }
        loadRoomType();
        $scope.formSave = function(formName, Type) {
            if (formName.$invalid == false) {
                if (Type == "UPDATE") {
                    $http({
                            method: "POST",
                            url: 'models/manage_room_data.model.php',
                            data: {
                                TYPES: 'UPDATE_room_data',
                                FORM_DATA: $scope.room_data,
                                CURRENT_DATA: $rootScope.globals.currentDATA
                            },
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        })
                        .success(function(data) {
                            //console.log(data);
                            notificationService.notify({
                                title: 'ระบบตอบรับ',
                                text: data.MSG,
                                styling: "bootstrap3",
                                type: data.TYPE,
                                delay: 1000,
                                icon: true
                            });
                            reloadData();
                            $scope.resetForm(formName);
                        });
                } else if (Type == "INSERT") {
                    $http({
                            method: "POST",
                            url: 'models/manage_room_data.model.php',
                            data: {
                                TYPES: 'INSERT_room_data',
                                FORM_DATA: $scope.room_data,
                                CURRENT_DATA: $rootScope.globals.currentDATA
                            },
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        })
                        .success(function(data) {
                            //console.log(data);
                            notificationService.notify({
                                title: 'ระบบตอบรับ',
                                text: data.MSG,
                                styling: "bootstrap3",
                                type: data.TYPE,
                                delay: 1000,
                                icon: true
                            });
                            reloadData();
                            $scope.resetForm(formName);
                        });
                }
            } else {
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: "กรุณาทำตามเงื่อนไขให้ครบถ้วน",
                    styling: "bootstrap3",
                    type: "warning",
                    delay: 1000,
                    icon: true
                });
            }
        }

        function deleteType(ID) {
            notificationService.notify({
                title: 'ยืนยัน',
                text: 'คุณต้องการลบรายการนี้ใช่หรือไม่?',
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
                        url: 'models/manage_room_data.model.php',
                        data: {
                            TYPES: 'DELETE_room_data',
                            CURRENT_DATA: $rootScope.globals.currentDATA,
                            ID: ID
                        },
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function(data) {
                        //console.log(data);
                        if (data.ERROR == false) {
                            $scope.detail = "";
                            $scope.room_data = {};
                            vm.dtInstance.reloadData();
                        }
                        notificationService.notify({
                            title: 'ระบบตอบรับ',
                            text: data.MSG,
                            styling: "bootstrap3",
                            type: data.TYPE,
                            delay: 1000,
                            icon: true
                        });
                    });
            }).on('pnotify.cancel', function() {
                //event
            });

        }
        $scope.resetForm = function(formName) {
            $scope.room_data = {};
            formName.$setPristine();
        }




    })
