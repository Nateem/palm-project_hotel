angular.module('app')
    .controller('checkin_addController', function($rootScope, $scope, $http, notificationService) {
        $scope.orders_type_ID = '2';

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
            $scope.controllerName = 'check_inController';
            var DATE = moment(new Date());
            $scope.form = {};
            $scope.modal = {};
            $scope.filterShow = '';
            $scope.rangOptions = {
                opens: 'left',
                buttonClasses: ['btn btn-sm btn-default'],
                applyClass: 'btn-sm btn-primary',
                cancelClass: 'btn-sm btn-default',
                format: 'DD/MM/YYYY',
                separator: ' To ',
                locale: $rootScope.daterangepickerLocale
            };
            $scope.SELECT_prefix();
            $scope.SELECT_status();
            $scope.LoadBedPlus();
            $scope.NUM_BADGE = 0;
            $scope.CURDATE = moment(new Date());
            $scope.modal.ID13 = "0000000000000";
            $scope.modal.deposit = 0;
            $scope.modal.PRICE_BEDPLUS = 0;
            $scope.modal.DISCOUNT = 0;
            $scope.modal.PAY = 0;
            $scope.modal.OWE = 0;
            $scope.modal.CASH_CHANGE = 0;
            $scope.form.DATECHKIN = 1;
            $scope.PRICE_TOTAL = 0;
            $scope.PRICE_SUM_TOTAL = 0;
            $scope.form.DATERANG = { startDate: DATE.format(), endDate: DATE.add(1, 'days').format() };
            $scope.onChangeDateRang();
        }
        loadInit();

        $scope.$watch("form.DATECHKIN", function(newValue, oldValue) {
            $scope.form.DATERANG.endDate = moment(new Date()).add(newValue, 'days').format();
            $scope.onChangeDateRang();
            $scope.NUM_BADGE = 0;
        });

        $scope.ngChangeNumber = function() {

            $scope.PRICE_TOTAL2 = (Number($scope.PRICE_TOTAL) + Number($scope.modal.PRICE_BEDPLUS)) - Number($scope.modal.DISCOUNT);
            $scope.PRICE_SUM_TOTAL = Number($scope.PRICE_TOTAL2) - Number($scope.modal.deposit);
            $scope.modal.OWE = Number($scope.PRICE_SUM_TOTAL) - Number($scope.modal.PAY);
            $scope.modal.CASH_CHANGE = Number($scope.modal.PAY) - Number($scope.PRICE_SUM_TOTAL);
        }
        $scope.ChangeRoomShow = function(ROOM_CODE) {
            return $http({
                    method: "POST",
                    url: 'models/checkin_add.model.php',
                    data: {
                        TYPES: 'CHANGE_ROOM_SHOW',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        ROOM_CODE: ROOM_CODE,
                        FORM_DATA: $scope.form
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.changeRoomType = data.ROOM_TYPE;
                    }

                });
        }
        $scope.changeRoomEvent = function(E_START, E_END) {
            return $http({
                    method: "POST",
                    url: 'models/checkin_add.model.php',
                    data: {
                        TYPES: 'SELECT_events',
                        CURRENT_DATA: $rootScope.globals.currentDATA,
                        E_START: E_START,
                        E_END: E_END
                    },
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
                .success(function(data) {
                    //console.log(data);
                    if (data.ERROR == false) {
                        $scope.changeRoomRepeat = data.DATA;
                        $scope.changeRoomHeadRepeat = data.DATA_HEAD;
                    }
                });
        }
        $scope.CHANGE_ROOM_CODE = function(ROOM_CODE, STATUS_CODE, events_detail_ID) {
            if (STATUS_CODE == 1) {
                notificationService.notify({
                    title: 'ยืนยัน',
                    text: 'คุณต้องการย้ายไปห้องนี้ใช่หรือไม่?',
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
                            url: 'models/checkin_add.model.php',
                            data: {
                                TYPES: 'CHANGE_ROOM_CODE',
                                CURRENT_DATA: $rootScope.globals.currentDATA,
                                ROOM_CODE: ROOM_CODE,
                                events_detail_ID: events_detail_ID
                            },
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                        })
                        .success(function(data) {
                            //console.log(data);
                            if (data.ERROR == false) {
                                $(function() {
                                    $("#roomChangeModal").modal("hide");
                                });
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
                }).on('pnotify.cancel', function() {
                    //event
                });

            } else if (STATUS_CODE == 2) {
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: 'ห้องนี้มีผู้เข้าพักอยู่?',
                    styling: "bootstrap3",
                    type: 'info',
                    icon: true
                });

            } else if (STATUS_CODE == 3) {
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: "ห้องนี้ได้มีการจองไว้",
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
        $scope.INSERT_TEMP = function(ROOM_CODE, STATUS_CODE) {
            if (STATUS_CODE == 1) {
                $http({
                        method: "POST",
                        url: 'models/events.model.php',
                        data: {
                            TYPES: 'INSERT_TEMP',
                            CURRENT_DATA: $rootScope.globals.currentDATA,
                            FORM_DATA: $scope.form,
                            ROOM_CODE: ROOM_CODE,
                            orders_type_ID: $scope.orders_type_ID,
                            STATUS_CODE: STATUS_CODE
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
                            icon: true
                        });
                    });
            } else if (STATUS_CODE == 2) {
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: 'ห้องนี้มีผู้เข้าพักอยู่?',
                    hide: false,
                    styling: "bootstrap3",
                    type: 'info',
                    icon: true,
                    animate: {
                        animate: true,
                        in_class: 'bounceInLeft',
                        out_class: 'bounceOutRight'
                    },
                    confirm: {
                        confirm: true,
                        buttons: [{
                            text: 'ย้ายห้อง',
                            addClass: 'btn-warning',
                            click: function(notice) {
                                notice.remove();
                                $scope.ChangeRoomShow(ROOM_CODE).then(function() {
                                    $scope.changeRoomEvent($scope.changeRoomType.E_START, $scope.changeRoomType.E_END).then(function() {
                                        $(function() {
                                            $("#roomChangeModal").modal("show");
                                        });
                                    });
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
                /*notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: "ห้องนี้ได้มีการจองไว้ก่อนหน้าแล้ว",
                    styling: "bootstrap3",
                    type: 'warning',
                    icon: true
                });
                */
                notificationService.notify({
                    title: 'ระบบตอบรับ',
                    text: 'ห้องนี้ได้มีการจองไว้ก่อนหน้าแล้ว?',
                    hide: false,
                    styling: "bootstrap3",
                    type: 'warning',
                    icon: true,
                    animate: {
                        animate: true,
                        in_class: 'bounceInLeft',
                        out_class: 'bounceOutRight'
                    },
                    confirm: {
                        confirm: true,
                        buttons: [{
                            text: 'Info',
                            addClass: 'btn-info',
                            click: function(notice) {
                                notice.remove();
                                $scope.InfoRoomBooking(ROOM_CODE).then(function() {
                                    $(function() {
                                        $("#infoModal").modal("show");
                                    });
                                });

                            }
                        }, {
                            text: 'Check-In',
                            addClass: 'btn-primary',
                            click: function(notice) {
                                notice.remove();
                                $scope.checkInAfterBooking(ROOM_CODE, $scope.InfoRoomRepeat.BILLCODE);
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
        $scope.InfoRoomBooking = function(ROOM_CODE) {
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
                    }
                });
        }
        $scope.checkInAfterBooking = function(ROOM_CODE, BILLCODE) {
            notificationService.notify({
                title: 'ยืนยัน',
                text: 'คุณต้องการเชคอินรายการนี้ใช่หรือไม่?',
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
                        url: 'models/checkin_add.model.php',
                        data: {
                            TYPES: 'checkInAfterBooking',
                            CURRENT_DATA: $rootScope.globals.currentDATA,
                            orders_type_ID: $scope.orders_type_ID,
                            ROOM_CODE: ROOM_CODE,
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
                        $scope.PRICE_TOTAL = data.PRICE_TOTAL;
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
            return $http({
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
        $scope.PRINT_BILL = function() {
            $scope.CHANGE_TO_UPDATE(false).then(function success(response) {
                $rootScope.PrintElem('printBody');
            }, function fail(response) {

            });
        }
        $scope.FORM_MODAL_SUBMIT = function(formName) {
            $scope.CHANGE_TO_UPDATE(true).then(function success(response) {
                if (confirm("คุณต้องการบันทึกรายการใช่หรือไม่?")) {
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
                                $(function() {
                                    $("#ShowTempOrders").modal("hide");
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
                } else {
                    return false;
                }
            }, function fail(response) {

            });


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
        $scope.ShowTempOrders = function() {
            $scope.SELECT_TEMP();
            $(function() {
                $("#ShowTempOrders").modal("show");
            })
        }
    })
