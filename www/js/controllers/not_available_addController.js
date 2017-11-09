angular.module('app')
    .controller('not_available_addController', function($rootScope, $scope, $http, notificationService) {
        $scope.orders_type_ID = '3';
        $scope.SELECT_prefix = function() {

            $http({
                    method: "POST",
                    url: 'models/not_available_add.model.php',
                    data: {
                        TYPES: 'SELECT_prefix',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.prefixRepeat = data.DATA;
                    }

                });
        }
        $scope.SELECT_status = function() {

            $http({
                    method: "POST",
                    url: 'models/events.model.php',
                    data: {
                        TYPES: 'SELECT_status',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.statusRepeat = data.DATA;
                    }

                });
        }
        $scope.ChkNumBadge = function() {
            $http({
                    method: "POST",
                    url: 'models/not_available_add.model.php',
                    data: {
                        TYPES: 'NUM_BADGE',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    $scope.NUM_BADGE = data.DATA;

                });
        }
        $scope.onChangeDateRang = function() {

            $http({
                    method: "POST",
                    url: 'models/events.model.php',
                    data: {
                        TYPES: 'SELECT_events',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                        FORM_DATA: $scope.form ,
                        
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.DataRoomRepeat = data.DATA;
                        $scope.DataRoomHeadRepeat = data.DATA_HEAD;
                        $scope.BILLCODE = data.BILLCODE;
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
        }
        $scope.LoadBedPlus = function() {

            $http({
                    method: "POST",
                    url: 'models/not_available_add.model.php',
                    data: {
                        TYPES: 'SELECT_bed_plus',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.DataBedplusRepeat = data.DATA;
                    }
                });
        }
       
        var loadInit = function() {
            $scope.controllerName = 'not_available_addController';
            var DATE = moment(new Date());            
            $scope.form = {};
            $scope.modal = {};
            $scope.rangOptions = {
                opens: 'left',
                buttonClasses: ['btn btn-sm btn-default'],
                applyClass: 'btn-sm btn-primary',
                cancelClass: 'btn-sm btn-default',
                format: 'DD/MM/YYYY',
                separator: ' To ',
                locale: $rootScope.daterangepickerLocale
            };           
            $scope.SELECT_status();
            $scope.NUM_BADGE = 0;
            $scope.CURDATE = DATE;            
            $scope.form.DATERANG = { startDate: DATE.format(), endDate: DATE.add(1, 'days').format() };
        }
        loadInit();

        $scope.$watch("form.DATERANG", function(newValue, oldValue) {
            $scope.onChangeDateRang();
            $scope.NUM_BADGE = 0;
        });        
        
        $scope.INSERT_TEMP = function(ROOM_CODE,STATUS_CODE) {
            if(STATUS_CODE==1){
                $http({
                    method: "POST",
                    url: 'models/not_available_add.model.php',
                    data: {
                        TYPES: 'INSERT_TEMP',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        FORM_DATA: $scope.form,
                        ROOM_CODE: ROOM_CODE,
                        orders_type_ID: $scope.orders_type_ID,
                        STATUS_CODE:STATUS_CODE
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    $scope.ChkNumBadge();
                    notificationService.notify({
                        title: 'ระบบตอบรับ',
                        text: data.MSG,
                        styling: "bootstrap3",
                        type: data.TYPE,
                        delay: 1000,
                        icon: true,
                    });
                });
            }
            else if(STATUS_CODE==2){
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: "ห้องนี้มีผู้เข้าพักอยู่",
                    styling: "bootstrap3",
                    type: 'info',
                    delay: 2000,
                    icon: true
                });
            }
            else if(STATUS_CODE==3){
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: "ห้องนี้ได้มีการจองไว้ก่อนหน้าแล้ว",
                    styling: "bootstrap3",
                    type: 'warning',
                    delay: 2000,
                    icon: true
                });
            }
            else{
                 notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: "ห้องนี้ไม่พร้อมสำหรับทำรายการ",
                    styling: "bootstrap3",
                    type: 'error',
                    delay: 2000,
                    icon: true
                });
            }
            
        }
        $scope.SELECT_TEMP = function() {

            $http({
                    method: "POST",
                    url: 'models/not_available_add.model.php',
                    data: {
                        TYPES: 'SELECT_TEMP',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.tempDataRepeat = data.DATA;                        
                    }
                    /*
                                        notificationService.notify({
                                            title: 'ระบบตอบรับ',
                                            text: data.MSG,
                                            styling: "bootstrap3",
                                            type: data.TYPE,
                                            icon: true
                                        });*/
                });
        }
        $scope.UPDATE_TEMP = function() {

            $http({
                    method: "POST",
                    url: 'models/not_available_add.model.php',
                    data: {
                        TYPES: 'UPDATE_TEMP',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.tempDataRepeat = data.DATA;
                    }
                    /*
                                        notificationService.notify({
                                            title: 'ระบบตอบรับ',
                                            text: data.MSG,
                                            styling: "bootstrap3",
                                            type: data.TYPE,
                                            icon: true
                                        });*/
                });
        }
        $scope.DELETE_TEMP = function(ID) {

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
                        url: 'models/not_available_add.model.php',
                        data: {
                            TYPES: 'DELETE_TEMP',
                            CURRENT_DATA: $rootScope.globals.currentDATA,
                            orders_type_ID: $scope.orders_type_ID,
                            ID: ID,
                        },
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function(data) {
                        //console.log(data);
                        $scope.ChkNumBadge();
                        $scope.SELECT_TEMP();
                        notificationService.notify({
                            title: 'ระบบตอบรับ',
                            text: data.MSG,
                            styling: "bootstrap3",
                            type: data.TYPE,
                            delay: 2000,
                            icon: true
                        });
                    });
            }).on('pnotify.cancel', function() {
                //event
            });

        }
        $scope.CHANGE_TO_UPDATE = function(true_false) {
            $http({
                    method: "POST",
                    url: 'models/not_available_add.model.php',
                    data: {
                        TYPES: 'CHANGE_TO_UPDATE',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        FORM_DATA: $scope.modal,
                        orders_type_ID: $scope.orders_type_ID,
                        UPDATE_CUS:true_false
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    /*
                    notificationService.notify({
                        title: 'ระบบตอบรับ',
                        text: data.MSG,
                        styling: "bootstrap3",
                        type: data.TYPE,
                        icon: true
                    });*/
                });
        }
        var popupwindow =  function (url, title, w, h) {
          var left = (screen.width/2)-(w/2);
          var top = (screen.height/2)-(h/1.5);
         var win = window.open(url, title, 'toolbar=no, location=0, directories=0, status=0, menubar=0, scrollbars=0, resizable=0, copyhistory=0, width='+w+', height='+h+', top='+top+', left='+left);
         win.focus();
          return  win;
        } 
        $scope.FORM_MODAL_SUBMIT = function(formName) {
            $scope.CHANGE_TO_UPDATE(true);
            if (confirm("คุณต้องการบันทึกรายการใช่หรือไม่?")) {                
                $http({
                        method: "POST",
                        url: 'models/not_available_add.model.php',
                        data: {
                            TYPES: 'FORM_MODAL_SUBMIT',
                            CURRENT_DATA: $rootScope.globals.currentDATA,
                            orders_type_ID: $scope.orders_type_ID,
                        },
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    })
                    .success(function(data) {
                        //console.log(data);
                        if (data.ERROR == false) {
                            formName.$setPristine();
                            loadInit();
                            $(function() {
                                $("#ShowTempOrders").modal("hide");
                            });
                        }
                        notificationService.notify({
                            title: 'ระบบตอบรับ',
                            text: data.MSG,
                            styling: "bootstrap3",
                            type: data.TYPE,
                            delay: 2000,
                            icon: true
                        });
                    });
            } else {
                return false;
            }

        }
        
        $scope.ShowTempOrders = function() {
            $scope.SELECT_TEMP();
            $(function() {
                $("#ShowTempOrders").modal("show");

            })
           
        }
        $(function(){
            $('#ShowTempOrders').on('shown.bs.modal', function () {
                  $('#inpID13').focus();
                  $('#inpID13').select();                  
            });
        });
         
       
    })
