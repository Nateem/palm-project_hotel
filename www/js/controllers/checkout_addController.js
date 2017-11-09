angular.module('app')
    .controller('checkout_addController', function($rootScope, $scope, $interval, $http, notificationService) {
        $scope.orders_type_ID = '1';

        $scope.SELECT_prefix = function() {

            $http({
                    method: "POST",
                    url: 'models/events.model.php',
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
        $scope.onChangeDateRang = function() {

            $http({
                    method: "POST",
                    url: 'models/events.model.php',
                    data: {
                        TYPES: 'SELECT_events',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                        FORM_DATA: $scope.form
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
        $scope.LoadBedPlus = function() {

            $http({
                    method: "POST",
                    url: 'models/events.model.php',
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
        $scope.check_customer = function(ID13) {
            $http({
                    method: "POST",
                    url: 'models/events.model.php',
                    data: {
                        TYPES: 'CHECK_CUSTOMER',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                        ID13: ID13
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.modal.PREFIX_ID = data.DATA.PREFIX_ID;
                        $scope.modal.FNAME = data.DATA.FNAME;
                        $scope.modal.LNAME = data.DATA.LNAME;
                        $scope.modal.ADDRESS = data.DATA.ADDRESS;
                        $scope.modal.PHONE = data.DATA.PHONE;
                    } else {
                        $scope.modal.PREFIX_ID = '';
                        $scope.modal.FNAME = '';
                        $scope.modal.LNAME = '';
                        $scope.modal.ADDRESS = '';
                        $scope.modal.PHONE = '';
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
        var loadInit = function() {
            $scope.controllerName = 'check_outController';
            var DATE = moment(new Date());
            $scope.form = {};
            $scope.filterShow = '';
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
             $scope.SELECT_status();
            $scope.CURDATE = moment(new Date());
            $scope.OTHER_PRICE = 0;
            $scope.PAY_PLUS = 0;
            $scope.PAY_TOTAL = 0;
            $scope.CASH_CHANGE = 0;
            $scope.OWE = 0;
            $scope.form.DATECHKIN = 1;
            $scope.form.DATERANG = { startDate: DATE.format(), endDate: DATE.add(1, 'days').format() };
            $scope.onChangeDateRang();
        }
        loadInit();

        $scope.$watch("form.DATERANG.startDate", function(newValue, oldValue) {
            $scope.form.DATERANG.endDate = moment($scope.form.DATERANG.startDate).add(1, 'days').format();
            $scope.onChangeDateRang();
        });
        $scope.currentDateTime = new Date();
        $interval(function() {
            $scope.currentDateTime = new Date();
        }, 1000);

        $scope.ngChangeNumber = function() {
            $scope.PAY_TOTAL = Number($scope.PAY) + Number($scope.PAY_PLUS);
            $scope.SUMTOTAL = (Number($scope.PRICE_TOTAL) + Number($scope.OTHER_PRICE))-Number($scope.DEPOSIT);
            $scope.OWE = Number($scope.SUMTOTAL) - Number($scope.PAY_TOTAL);
            $scope.CASH_CHANGE = Number($scope.PAY_TOTAL) - Number($scope.SUMTOTAL);
        }
        $scope.UpdateBeforCheckout = function() {
            return $http({
                    method: "POST",
                    url: 'models/checkout_add.model.php',
                    data: {
                        TYPES: 'UpdateBeforCheckout',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                        BILLCODE: $scope.InfoRoomRepeat.BILLCODE,
                        PAY_TOTAL: $scope.PAY_TOTAL,
                        SUMTOTAL: $scope.SUMTOTAL,
                        OWE: $scope.OWE,
                        CASH_CHANGE: $scope.CASH_CHANGE,
                        OTHER_PRICE: $scope.OTHER_PRICE,
                        CURDATE: moment(new Date()).format(),
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {

                    }
                });
        }
        $scope.INSERT_TEMP = function(ROOM_CODE, STATUS_CODE) {
            if (STATUS_CODE == 1) {
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: "ห้องนี้เป็นห้องว่าง",
                    styling: "bootstrap3",
                    type: 'error',
                    icon: true
                });
            } else if (STATUS_CODE == 2) {
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: 'ทำการเช็คเอาท์ก่อนกำหนด?',
                    hide: false,
                    styling: "bootstrap3",
                    type: 'success',
                    icon: true,
                    animate: {
                        animate: true,
                        in_class: 'bounceInLeft',
                        out_class: 'bounceOutRight'
                    },
                    confirm: {
                        confirm: true,
                        buttons: [{
                            text: 'Info Check-Out',
                            addClass: 'btn-info',
                            click: function(notice) {
                                notice.remove();
                                $scope.InfoRoomCheckIn(ROOM_CODE).then(function success(response) {
                                    $(function() {
                                        $("#infoModal").modal("show");
                                    });
                                }, function fail(response) {

                                });

                            }
                        }, {
                            text: 'Close',
                            click: function(notice) {
                                notice.remove();
                            }
                        }]
                    },
                    buttons: {
                        closer: false,
                        sticker: false
                    },
                    history: {
                        history: false
                    }
                }).get().on('pnotify.confirm', function() {

                }).on('pnotify.cancel', function() {
                    //event
                });
            } else if (STATUS_CODE == 3) {
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: "ห้องนี้ได้มีการจองไว้แต่ยังไม่เข้าพัก",
                    styling: "bootstrap3",
                    type: 'warning',
                    icon: true
                });






            } else {
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: "ห้องนี้ไม่พร้อมสำหรับทำรายการ",
                    styling: "bootstrap3",
                    type: 'error',
                    icon: true
                });
            }

        }
        $scope.InfoRoomCheckIn = function(ROOM_CODE) {
            return $http({
                    method: "POST",
                    url: 'models/events.model.php',
                    data: {
                        TYPES: 'InfoRoomBooking',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        orders_type_ID: $scope.orders_type_ID,
                        ROOM_CODE: ROOM_CODE,
                        CURDATE: moment(new Date()).format(),
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.InfoRoomRepeat = data.DATA;
                        $scope.InfoDetailRoomRepeat = data.DATA1;
                        $scope.BEDPLUS1 = data.DATA.BEDPLUS1;
                        $scope.BEDPLUS2 = data.DATA.BEDPLUS2;
                        $scope.PRICE_BEDPLUS = data.DATA.PRICE_BEDPLUS;
                        $scope.DISCOUNT = data.DATA.DISCOUNT;
                        $scope.OTHER_PRICE = data.DATA.OTHER_PRICE;
                        $scope.PRICE_TOTAL = data.DATA.PRICE_TOTAL;
                        $scope.PAY = data.DATA.PAY;
                        $scope.DEPOSIT = data.DATA.DEPOSIT;
                        $scope.PAY_TOTAL = $scope.PAY;
                        $scope.SUMTOTAL = Number($scope.PRICE_TOTAL) + Number($scope.OTHER_PRICE)-Number($scope.DEPOSIT);
                        $scope.OWE = Number($scope.SUMTOTAL) - Number($scope.PAY_TOTAL);
                        $scope.CASH_CHANGE = Number($scope.PAY_TOTAL) - Number($scope.SUMTOTAL);

                    }
                });
        }
        $scope.checkOutAfterCheckIn = function(BILLCODE) {
            $scope.UpdateBeforCheckout().then(function success(response) {
                notificationService.notify({
                    title: 'ยืนยัน',
                    text: 'คุณต้องการเช็คเอาท์รายการนี้ใช่หรือไม่?',
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
                            url: 'models/checkout_add.model.php',
                            data: {
                                TYPES: 'checkOutAfterCheckIn',
                                CURRENT_DATA: $rootScope.globals.currentDATA,
                                orders_type_ID: $scope.orders_type_ID,
                                BILLCODE: BILLCODE,
                            },
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        })
                        .success(function(data) {
                            //console.log(data);
                            if (data.ERROR == false) {
                                loadInit();
                                $(function() {
                                    $("#infoModal").modal("hide");
                                });
                            }
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
            }, function fail(response) {

            });

        }
        $scope.SELECT_TEMP = function() {

            $http({
                    method: "POST",
                    url: 'models/events.model.php',
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
                        $scope.PRICE_SUM_TOTAL = data.PRICE_SUM_TOTAL;
                        $scope.ngChangeNumber();
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
                    url: 'models/events.model.php',
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
                        $scope.PRICE_TOTAL = data.PRICE_TOTAL;
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
                        url: 'models/events.model.php',
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
                    url: 'models/events.model.php',
                    data: {
                        TYPES: 'CHANGE_TO_UPDATE',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        FORM_DATA: $scope.modal,
                        orders_type_ID: $scope.orders_type_ID,
                        UPDATE_CUS: true_false
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
        var popupwindow = function(url, title, w, h) {
            var left = (screen.width / 2) - (w / 2);
            var top = (screen.height / 2) - (h / 1.5);
            var win = window.open(url, title, 'toolbar=no, location=0, directories=0, status=0, menubar=0, scrollbars=0, resizable=0, copyhistory=0, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
            win.focus();
            return win;
        }
        $scope.PRINT_BILL = function(EleID) {
            $scope.UpdateBeforCheckout().then(function success(response) {
                $rootScope.PrintElem(EleID);
            }, function fail(response) {

            });
        }
        $scope.FORM_MODAL_SUBMIT = function(formName) {
            if (confirm("คุณต้องการบันทึกรายการใช่หรือไม่?")) {
                $scope.CHANGE_TO_UPDATE(true);
                $http({
                        method: "POST",
                        url: 'models/events.model.php',
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

                        }
                        notificationService.notify({
                            title: 'ระบบตอบรับ',
                            text: data.MSG,
                            styling: "bootstrap3",
                            type: data.TYPE,
                            icon: true
                        });
                    });
            } else {
                return false;
            }

        }
        $scope.ChkNumBadge = function() {
            $http({
                    method: "POST",
                    url: 'models/events.model.php',
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
        $(function() {
            $("#infoModal").on('shown.bs.modal', function() {
                $scope.OTHER_PRICE = 0;
                $scope.PAY_PLUS = 0;
            });
        })
    })
