'use strict';
var menu_json = [{
        "name": "home",
        "url": "/",
        "templateUrl": "templates/home.view.html",
        'controller': 'homeController',
        "TH_name": "หน้าแรก",
        "EN_name": "Home",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Home.png'
    }, {
        "name": "booking",
        "url": "/booking",
        "templateUrl": "templates/booking.view.html",
        'controller': 'bookingController',
        "TH_name": "การจอง",
        "EN_name": "Booking",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "booking_add",
        "url": "/booking_add",
        "templateUrl": "templates/booking_add.view.html",
        'controller': 'booking_addController',
        "TH_name": "ทำรายการจอง",
        "EN_name": "Add Booking",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "not_available",
        "url": "/not_available",
        "templateUrl": "templates/not_available.view.html",
        'controller': 'not_availableController',
        "TH_name": "ไม่พร้อมใช้งาน",
        "EN_name": "Not available",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "not_available_add",
        "url": "/not_available_add",
        "templateUrl": "templates/not_available_add.view.html",
        'controller': 'not_available_addController',
        "TH_name": "เพิ่มห้องไม่พร้อมใช้งาน",
        "EN_name": "Add Not available",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "checkin",
        "url": "/checkin",
        "templateUrl": "templates/checkin.view.html",
        'controller': 'checkinController',
        "TH_name": "เชคอิน",
        "EN_name": "Check IN",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "checkin_add",
        "url": "/checkin_add",
        "templateUrl": "templates/checkin_add.view.html",
        'controller': 'checkin_addController',
        "TH_name": "ทำรายการเชคอิน",
        "EN_name": "Check IN",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "checkout",
        "url": "/checkout",
        "templateUrl": "templates/checkout.view.html",
        'controller': 'checkoutController',
        "TH_name": "เช็คเอาท์",
        "EN_name": "Check OUT",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "checkout_add",
        "url": "/checkout_add",
        "templateUrl": "templates/checkout_add.view.html",
        'controller': 'checkout_addController',
        "TH_name": "ทำรายการเช็คเอาท์",
        "EN_name": "Check OUT",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "summary",
        "url": "/summary",
        "templateUrl": "templates/summary.view.html",
        'controller': 'summaryController',
        "TH_name": "สรุปยอด",
        "EN_name": "Summary",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "summary_total",
        "url": "/summary_total",
        "templateUrl": "templates/summary_total.view.html",
        'controller': 'summary_totalController',
        "TH_name": "สรุปยอดรวม",
        "EN_name": "Summary",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 1,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "manage",
        "url": "/manage",
        "templateUrl": "templates/manage.view.html",
        'controller': 'manageController',
        "TH_name": "จัดการทั้งหมด",
        "EN_name": "Manage",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 9,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "manage_room_type",
        "url": "/manage_room_type",
        "templateUrl": "templates/manage_room_type.view.html",
        'controller': 'manage_room_typeController',
        "TH_name": "จัดการประเภทห้อง",
        "EN_name": "Manage Room Type",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 9,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "manage_room_data",
        "url": "/manage_room_data",
        "templateUrl": "templates/manage_room_data.view.html",
        'controller': 'manage_room_dataController',
        "TH_name": "จัดการข้อมูลห้องพัก",
        "EN_name": "Manage Room Data",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 9,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }, {
        "name": "manage_customer",
        "url": "/manage_customer",
        "templateUrl": "templates/manage_customer.view.html",
        'controller': 'manage_customerController',
        "TH_name": "จัดการข้อมูลลูกค้า",
        "EN_name": "Manage Customer",
        "menu_hide": false,
        "PAGE_VIEW_LEVEL": 9,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }
    /*, {
        "name": "booking_auto",
        "url": "/booking_auto",
        "templateUrl": "templates/booking_auto.view.html",
        'controller': 'booking_autoController',
        "TH_name": "ทำรายการจองอัตโนมัติ",
        "EN_name": "Auto Booking",
        "menu_hide": false,
        "ICO_CLASS": './img/icon/Speed-96.png'
    }*/

];
$(function() {
    var shrinkHeader = 100;
    $(window).scroll(function() {
        var scroll = getCurrentScroll();
        if (scroll >= shrinkHeader) {
            $('.top_scroll').addClass('fix_scroll');
        } else {
            $('.top_scroll').removeClass('fix_scroll');
        }
    });

    function getCurrentScroll() {
        return window.pageYOffset || document.documentElement.scrollTop;
    }
});
angular.module('app', [
    'ui.router',
    'ngCookies',
    'chart.js',
    'ui.notify',
    'ngLoadingSpinner',
    'ckeditor',
    'daterangepicker',
    'angularMoment',
    'datatables',
    'datatables.buttons',
    'checklist-model'
])

.config(function($stateProvider, $urlRouterProvider, notificationServiceProvider) {
    notificationServiceProvider.setDefaults({
        history: false,
        delay: 4000,
        styling: 'bootstrap3',
        closer: false,
        closer_hover: false
    });


    var spd = $stateProvider;
    var funcController = function($rootScope, $scope, $state) {
        $scope.stateName = $state.current.data.stateName;
        $rootScope.globals.stateName = $state.current.data.stateName;
        $rootScope.globals.stateNameEN = $state.current.data.stateNameEN;
        $rootScope.globals.stateICO_CLASS = $state.current.data.stateICO_CLASS;
        $rootScope.globals.statePAGE_VIEW_LEVEL = $state.current.data.statePAGE_VIEW_LEVEL;
    }

    //$rootScope.menu_json = menu_json;
    angular.forEach(menu_json, function(value1, key) {

        if (!value1.name) {

            angular.forEach(value1.dropdown, function(value2, key2) {
                if (!value2.name) {
                    angular.forEach(value2.dropdown, function(value3, key3) {
                        spd.state({
                            name: value3.name,
                            url: value3.url,
                            templateUrl: value3.templateUrl,
                            data: {
                                stateName: value3.TH_name,
                                stateNameEN: value3.EN_name,
                                stateICO_CLASS: value3.ICO_CLASS,
                                statePAGE_VIEW_LEVEL: value3.PAGE_VIEW_LEVEL
                            },
                            controller: funcController
                        });
                    });
                } else {
                    spd.state({
                        name: value2.name,
                        url: value2.url,
                        templateUrl: value2.templateUrl,
                        data: {
                            stateName: value2.TH_name,
                            stateNameEN: value2.EN_name,
                            stateICO_CLASS: value2.ICO_CLASS,
                            statePAGE_VIEW_LEVEL: value2.PAGE_VIEW_LEVEL
                        },
                        controller: funcController
                    });
                }

            });

        } else {

            spd.state({
                name: value1.name,
                url: value1.url,
                templateUrl: value1.templateUrl,
                data: {
                    stateName: value1.TH_name,
                    stateNameEN: value1.EN_name,
                    stateICO_CLASS: value1.ICO_CLASS,
                    statePAGE_VIEW_LEVEL: value1.PAGE_VIEW_LEVEL
                },
                controller: funcController
            });

        }

    });
    spd.state({
        name: 'register',
        url: '/register',
        templateUrl: 'templates/register.view.html',
        data: {
            stateName: 'ลงทะเบียน',
            stateNameEN: 'Register',
            stateICO_CLASS: 'fa fa-registered',
            statePAGE_VIEW_LEVEL: 1
        },
        controller: funcController
    });
    spd.state({
        name: 'login',
        url: '/login',
        templateUrl: 'templates/login.view.html',
        data: {
            stateName: 'เข้าสู่ระบบ',
            stateNameEN: 'Login',
            stateICO_CLASS: 'fa fa-sign-in',
            statePAGE_VIEW_LEVEL: 1
        },
        controller: funcController
    });
    spd.state({
        name: 'error404',
        url: '/error404',
        templateUrl: 'templates/error404.view.html',
        data: {
            stateName: 'ไม่พบหน้าที่ร้องขอ',
            stateNameEN: 'Error404',
            stateICO_CLASS: 'fa fa-exclamation-triangle',
            statePAGE_VIEW_LEVEL: 1
        },
        controller: funcController
    });
    $urlRouterProvider.otherwise('/error404');
})

.directive('myEnter', function() {
    return function(scope, element, attrs) {
        element.bind("keydown keypress", function(event) {
            if (event.which === 13) {
                scope.$apply(function() {
                    scope.$eval(attrs.myEnter);
                });

                event.preventDefault();
            }
        });
    };
})

.run(function($rootScope, $http, $state, notificationService, $cookieStore, amMoment) {

        $rootScope.WebAppData = {
            "NAME": "โรงแรมเรือนระเบียง",
            "ADDRESS": "140/19 ม.1 ต.ทรายขาว อ.สอยดาว จ.จันทบุรี 22180",
            "TAX": "3220400323268",
            "LOGO_URL": "img/logo/ruenrabeng_logo.jpg",
            "PHONE": "039-421311 , 039-421312",
            "CREDIT": "Na TeeM",
        };
        $rootScope.CURDATE = new Date();
        $rootScope.PrintElem = function(elem, tax) {
            if (!tax) {
                tax = false;
            }
            var mywindow = window.open('', 'PRINT');
            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write('<style>*{margin:0;padding:0;font-family:"Browallia New",tahoma,Arial, "times New Roman";font-size:22px;}.head_page{ text-align:center;}html {    font-family:"Browallia New",tahoma,Arial, "times New Roman";    font-size:22px;    color:#000000;}body {    font-family:"Browallia New",tahoma,Arial, "times New Roman";    font-size:22px;    padding:0;    margin:0;    color:#000000;}@media all{.page-break { display:none; }.page-break-no{display:none;}}@media print{.page-break { display:block;height:1px; page-break-before:always; }    .page-break-no{ display:block;height:1px; page-break-after:avoid; }}</style>');
            mywindow.document.write('</head><body><div class="page-break-no">&nbsp;</div><div class="">');

            mywindow.document.write('<div class="head_page"><img src="' + $rootScope.WebAppData.LOGO_URL + '" height="40px" ></div>');
            mywindow.document.write('<h2 class="head_page">' + $rootScope.WebAppData.NAME + '</h2>');
            if (tax) {
                mywindow.document.write('<h2 class="head_page">หมายเลขประจำตัวผู้เสียภาษี ' + $rootScope.WebAppData.TAX + '</h2>');
            }
            mywindow.document.write('<h2 class="head_page">' + $rootScope.WebAppData.ADDRESS + '</h2>');
            mywindow.document.write('<h2 class="head_page"> โทร. ' + $rootScope.WebAppData.PHONE + '</h2>');
            if (tax) {
                mywindow.document.write('<h2 class="head_page">ใบกำกับภาษี</h2>');
            }
            mywindow.document.write('<h3>' + document.title + '<small>(ส่วนของลูกค้า)</small></h3>');
            mywindow.document.write(document.getElementById(elem).innerHTML);

            mywindow.document.write('</div><div class="page-break">&nbsp;</div><div class="\">');

            mywindow.document.write('<div class="head_page"><img src="' + $rootScope.WebAppData.LOGO_URL + '" height="40px" ></div>');
            mywindow.document.write('<h2 class="head_page">' + $rootScope.WebAppData.NAME + '</h2>');
            if (tax) {
                mywindow.document.write('<h2 class="head_page">หมายเลขประจำตัวผู้เสียภาษี ' + $rootScope.WebAppData.TAX + '</h2>');
            }
            mywindow.document.write('<h2 class="head_page">' + $rootScope.WebAppData.ADDRESS + '</h2>');
            mywindow.document.write('<h2 class="head_page"> โทร. ' + $rootScope.WebAppData.PHONE + '</h2>');
            if (tax) {
                mywindow.document.write('<h2 class="head_page">ใบกำกับภาษี</h2>');
            }
            mywindow.document.write('<h3>' + document.title + '<small>(ส่วนของโรงแรม)</small></h3>');
            mywindow.document.write(document.getElementById(elem).innerHTML);
            mywindow.document.write('</div></body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            setTimeout(function() {
                mywindow.print();
                mywindow.close();
            }, 1000);


            return true;
        }
        $rootScope.PrintElemOne = function(elem) {
            var mywindow = window.open('', 'PRINT');

            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write('<style>*{font-family:"Browallia New",tahoma,Arial, "times New Roman";font-size:22px;}.head_page{ text-align:center;margin:0px;padding:0px;}.text-right{text-align:right;}.text-center{text-align:center;}html {    font-family:"Browallia New",tahoma,Arial, "times New Roman";    font-size:22px;    color:#000000;}body {    font-family:"Browallia New",tahoma,Arial, "times New Roman";    font-size:22px;    padding:0;    margin:0;    color:#000000;}@media all{.page-break { display:none; }.page-break-no{display:none;}}@media print{.page-break { display:block;height:1px; page-break-before:always; }    .page-break-no{ display:block;height:1px; page-break-after:avoid; }}</style>');
            mywindow.document.write('</head><body><div class="page-break-no">&nbsp;</div><div class="">');
            mywindow.document.write('<div class="head_page"><img src="' + $rootScope.WebAppData.LOGO_URL + '" height="40px" ></div>');
            mywindow.document.write('<h2 class="head_page">' + $rootScope.WebAppData.NAME + '</h2>');
mywindow.document.write('<h2 class="head_page">' + $rootScope.WebAppData.ADDRESS + '</h2>');
            mywindow.document.write('<h2 class="head_page"> โทร. ' + $rootScope.WebAppData.PHONE + '</h2>');
            mywindow.document.write('<h2>' + document.title + '</h2>');
            mywindow.document.write(document.getElementById(elem).innerHTML);

            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/

            mywindow.print();
            mywindow.close();

            return true;
        }
        amMoment.changeLocale('th');
        $rootScope.daterangepickerLocale = {
            applyLabel: '<i class="fa fa-check"></i> ยืนยัน',
            cancelLabel: 'ปิด',
            fromLabel: 'เริ่ม',
            toLabel: 'ถึง',
            format: 'DD/MM/YYYY',
            customRangeLabel: 'กำหนดเอง',
            daysOfWeek: ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'],
            monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
            firstDay: 1
        };
        var globals = $cookieStore.get('globals') || {};
        $rootScope.globals = globals;
        if (globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + globals.currentUser.authdata; // jshint ignore:line
        }
        $rootScope.$on('$locationChangeStart', function(event, next, current) {
            // redirect to login page if not logged in

            if ($state.current.name !== 'login' && !$rootScope.globals.currentUser) {
                event.preventDefault();
                $state.go('login');
                notificationService.notify({
                    title: 'คำเตือน',
                    text: 'Please login! | กรุณาเข้าสู่ระบบ',
                    styling: "bootstrap3",
                    type: "warning",
                    icon: true
                });
            } else {
                if ($rootScope.globals.currentDATA.LEVEL < $rootScope.globals.statePAGE_VIEW_LEVEL) {
                    event.preventDefault();
                    $state.go('home');
                    notificationService.notify({
                        title: 'คำเตือน',
                        text: 'Please login! | ระดับไม่เพียงพอสำหรับเข้าใช้เมนูนี้',
                        styling: "bootstrap3",
                        type: "warning",
                        icon: true
                    });

                }
            }

            // console.log($state.current.name);
        });
    })
    .constant('angularMomentConfig', {
        timezone: 'Asia/Bangkok' // e.g. 'Europe/London'
    });
