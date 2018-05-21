-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 21, 2018 at 01:02 PM
-- Server version: 5.5.31
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_hotel-easy`
--

-- --------------------------------------------------------

--
-- Table structure for table `bed_plus`
--

CREATE TABLE `bed_plus` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `PRICE` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bed_plus`
--

INSERT INTO `bed_plus` (`ID`, `NAME`, `PRICE`) VALUES
(1, 'ราคา150', 150),
(2, 'ราคา350', 350);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `ID` int(12) NOT NULL,
  `BILLCODE` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `orders_type_ID` int(11) NOT NULL,
  `customer_ID` int(8) UNSIGNED ZEROFILL NOT NULL,
  `PHONE` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `E_START` datetime NOT NULL COMMENT 'เริ่ม',
  `E_END` datetime NOT NULL COMMENT 'สิ้นสุด',
  `E_BETWEEN` int(2) NOT NULL COMMENT 'จำนวนวัน',
  `BEDPLUS1` double NOT NULL COMMENT 'ราคาเตียงเสริม1',
  `BEDPLUS2` double NOT NULL COMMENT 'ราคาเตียงเสริม2',
  `PRICE_BEDPLUS` double NOT NULL COMMENT 'ราคาเตียงเสริม',
  `PRICE_TOTAL` decimal(11,2) NOT NULL COMMENT 'รวมราคา',
  `DISCOUNT` double NOT NULL COMMENT 'ส่วนลด',
  `DEPOSIT` double NOT NULL COMMENT 'เงินมัดจำ',
  `PAY` double NOT NULL COMMENT 'จ่าย',
  `CASH_CHANGE` double NOT NULL COMMENT 'เงินทอน',
  `NOTE` text NOT NULL COMMENT 'หมายเหตุ',
  `FILE_UPLOAD` varchar(200) NOT NULL COMMENT 'ที่อยู่ไฟล์แจ้งการโอน',
  `TRANSFER_BANK_ID` int(2) NOT NULL,
  `TRANSFER_DATETIME` datetime NOT NULL COMMENT 'วันเวลา',
  `TRANSFER_PRICE` decimal(10,2) NOT NULL COMMENT 'โอนจำนวน',
  `CHECKOUT` enum('NO','YES') NOT NULL COMMENT 'สถานะการจ่ายเงิน',
  `BOOKING_PASS` varchar(100) NOT NULL,
  `STATUS` int(1) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว',
  `FRONT_BACK` enum('FRONT','BACK') NOT NULL COMMENT 'จองจากหน้าบ้าน,หลังบ้าน',
  `CREATED` datetime NOT NULL COMMENT 'สร้างเมื่อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`ID`, `BILLCODE`, `orders_type_ID`, `customer_ID`, `PHONE`, `E_START`, `E_END`, `E_BETWEEN`, `BEDPLUS1`, `BEDPLUS2`, `PRICE_BEDPLUS`, `PRICE_TOTAL`, `DISCOUNT`, `DEPOSIT`, `PAY`, `CASH_CHANGE`, `NOTE`, `FILE_UPLOAD`, `TRANSFER_BANK_ID`, `TRANSFER_DATETIME`, `TRANSFER_PRICE`, `CHECKOUT`, `BOOKING_PASS`, `STATUS`, `FRONT_BACK`, `CREATED`) VALUES
(23, 'BK0000001', 1, 00000001, '', '2017-03-07 12:00:00', '2017-03-08 11:59:00', 1, 0, 350, 350, '4000.00', 0, 0, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-07 23:30:43'),
(24, 'BK0000002', 1, 00000023, '', '2017-03-10 12:00:00', '2017-03-12 11:59:00', 2, 0, 0, 0, '1600.00', 0, 200, 0, 0, 'ไม่มีเตียงเสริม', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-08 00:51:41'),
(25, 'BK0000003', 1, 00000023, '', '2017-03-08 12:00:00', '2017-03-09 11:59:00', 1, 300, 1050, 1350, '1600.00', 50, 200, 0, 0, 'ทดสอบรายการ', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-08 01:42:14'),
(26, 'BK0000004', 1, 00000023, '', '2017-03-08 12:00:00', '2017-03-09 11:59:00', 1, 0, 0, 0, '800.00', 0, 0, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-08 13:40:03'),
(27, 'BK0000005', 1, 00000001, '', '2017-03-17 12:00:00', '2017-03-22 11:59:00', 1, 0, 0, 0, '800.00', 0, 0, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-16 12:36:22'),
(28, 'CI0000001', 2, 00000001, '', '2017-03-17 12:00:00', '2017-03-18 11:59:00', 1, 0, 0, 0, '800.00', 0, 0, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-16 12:36:22'),
(29, 'RP0000001', 3, 00000001, '', '2017-03-17 12:00:00', '2017-03-18 11:59:00', 1, 0, 0, 0, '800.00', 0, 0, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-16 12:36:22'),
(32, 'BK0000008', 1, 00000001, '', '2017-03-17 12:00:00', '2017-03-18 11:59:00', 1, 0, 0, 0, '800.00', 0, 0, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-17 16:55:19'),
(34, 'BK0000010', 1, 00000001, '', '2017-03-17 12:00:00', '2017-03-18 11:59:00', 1, 0, 0, 0, '1000.00', 0, 0, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-17 16:59:54'),
(35, 'BK0000011', 1, 00000001, '', '2017-03-17 12:00:00', '2017-03-18 11:59:00', 1, 0, 0, 0, '2000.00', 0, 0, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-17 17:08:48'),
(36, 'NA0000003', 3, 00000001, '', '2017-04-11 12:00:00', '2017-04-12 11:59:00', 1, 0, 0, 0, '0.00', 0, 0, 0, 0, 'rrrrreeee', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-04-11 16:25:41');

-- --------------------------------------------------------

--
-- Table structure for table `booking_detail`
--

CREATE TABLE `booking_detail` (
  `ID` int(11) NOT NULL,
  `BILLCODE` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `room_data_CODE` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสห้อง',
  `PRICE` double NOT NULL COMMENT 'ราคาห้องพัก',
  `STATUS` int(2) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `booking_detail`
--

INSERT INTO `booking_detail` (`ID`, `BILLCODE`, `room_data_CODE`, `PRICE`, `STATUS`) VALUES
(28, 'BK0000001', 'S020601001', 800, 0),
(29, 'BK0000001', 'S020601002', 800, 0),
(30, 'BK0000001', 'S020601003', 800, 0),
(31, 'BK0000001', 'S020601004', 800, 0),
(32, 'BK0000001', 'S020601005', 800, 0),
(35, 'BK0000002', 'S020601001', 1600, 0),
(36, 'BK0000003', 'S020601001', 800, 0),
(37, 'BK0000003', 'S020601003', 800, 0),
(39, 'BK0000004', 'S020601002', 800, 0),
(40, 'BK0000005', 'S020601004', 800, 0),
(41, 'CI0000001', 'S020601005', 800, 0),
(42, 'RP0000001', 'S020601001', 800, 0),
(43, 'BK0000002', 'S020601003', 800, 0),
(44, 'BK0000002', 'S020601003', 800, 0),
(45, 'BK0000008', 'S020601003', 800, 0),
(47, 'BK0000010', 'D020601001', 1000, 0),
(48, 'BK0000011', 'D020601002', 1000, 0),
(49, 'BK0000011', 'D020601003', 1000, 0),
(50, 'NA0000001', 'D020601001', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `checkin`
--

CREATE TABLE `checkin` (
  `ID` int(12) NOT NULL,
  `BILLCODE` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customer_ID` int(8) UNSIGNED ZEROFILL NOT NULL,
  `PHONE` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `E_START` datetime NOT NULL COMMENT 'เริ่ม',
  `E_END` datetime NOT NULL COMMENT 'สิ้นสุด',
  `E_BETWEEN` int(2) NOT NULL COMMENT 'จำนวนวัน',
  `BEDPLUS1` double NOT NULL COMMENT 'ราคาเตียงเสริม1',
  `BEDPLUS2` double NOT NULL COMMENT 'ราคาเตียงเสริม2',
  `PRICE_BEDPLUS` double NOT NULL COMMENT 'ราคาเตียงเสริม',
  `PRICE_TOTAL` decimal(11,2) NOT NULL COMMENT 'รวมราคา',
  `DISCOUNT` double NOT NULL COMMENT 'ส่วนลด',
  `DEPOSIT` double NOT NULL COMMENT 'เงินมัดจำ',
  `PAY` double NOT NULL COMMENT 'จ่าย',
  `CASH_CHANGE` double NOT NULL COMMENT 'เงินทอน',
  `NOTE` text NOT NULL COMMENT 'หมายเหตุ',
  `FILE_UPLOAD` varchar(200) NOT NULL COMMENT 'ที่อยู่ไฟล์แจ้งการโอน',
  `TRANSFER_BANK_ID` int(2) NOT NULL,
  `TRANSFER_DATETIME` datetime NOT NULL COMMENT 'วันเวลา',
  `TRANSFER_PRICE` decimal(10,2) NOT NULL COMMENT 'โอนจำนวน',
  `CHECKOUT` enum('NO','YES') NOT NULL COMMENT 'สถานะการจ่ายเงิน',
  `BOOKING_PASS` varchar(100) NOT NULL,
  `STATUS` int(1) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว',
  `FRONT_BACK` enum('FRONT','BACK') NOT NULL COMMENT 'จองจากหน้าบ้าน,หลังบ้าน',
  `CREATED` datetime NOT NULL COMMENT 'สร้างเมื่อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `checkin`
--

INSERT INTO `checkin` (`ID`, `BILLCODE`, `customer_ID`, `PHONE`, `E_START`, `E_END`, `E_BETWEEN`, `BEDPLUS1`, `BEDPLUS2`, `PRICE_BEDPLUS`, `PRICE_TOTAL`, `DISCOUNT`, `DEPOSIT`, `PAY`, `CASH_CHANGE`, `NOTE`, `FILE_UPLOAD`, `TRANSFER_BANK_ID`, `TRANSFER_DATETIME`, `TRANSFER_PRICE`, `CHECKOUT`, `BOOKING_PASS`, `STATUS`, `FRONT_BACK`, `CREATED`) VALUES
(12, 'EV000001', 00000001, '', '2017-03-06 12:00:00', '2017-04-28 11:59:00', 2, 0, 0, 0, '2000.00', 0, 0, 0, 0, '', '', 0, '2016-05-02 00:00:00', '0.00', 'YES', '', 0, 'FRONT', '2016-04-26 18:15:21');

-- --------------------------------------------------------

--
-- Table structure for table `checkin_detail`
--

CREATE TABLE `checkin_detail` (
  `ID` int(11) NOT NULL,
  `BILLCODE` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `room_data_CODE` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสห้อง',
  `PRICE` double NOT NULL COMMENT 'ราคาห้องพัก',
  `STATUS` int(2) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `checkin_detail`
--

INSERT INTO `checkin_detail` (`ID`, `BILLCODE`, `room_data_CODE`, `PRICE`, `STATUS`) VALUES
(1, 'EV000001', 'HMS020601001', 1500, 0),
(2, 'EV000001', 'VIP063506001', 5000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `checkin_trash`
--

CREATE TABLE `checkin_trash` (
  `ID` int(12) NOT NULL,
  `customer_ID` int(8) UNSIGNED ZEROFILL NOT NULL,
  `PHONE` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `room_data_CODE` varchar(20) NOT NULL,
  `E_START` datetime NOT NULL COMMENT 'เริ่ม',
  `E_END` datetime NOT NULL COMMENT 'สิ้นสุด',
  `E_BETWEEN` int(2) NOT NULL COMMENT 'จำนวนวัน',
  `PRICE_TOTAL` decimal(11,2) NOT NULL COMMENT 'รวมราคา',
  `FILE_UPLOAD` varchar(200) NOT NULL COMMENT 'ที่อยู่ไฟล์แจ้งการโอน',
  `TRANSFER_BANK_ID` int(2) NOT NULL,
  `TRANSFER_DATETIME` datetime NOT NULL COMMENT 'วันเวลา',
  `TRANSFER_PRICE` decimal(10,2) NOT NULL COMMENT 'โอนจำนวน',
  `CHECKOUT` enum('NO','YES') NOT NULL COMMENT 'สถานะการจ่ายเงิน',
  `BOOKING_PASS` varchar(100) NOT NULL,
  `STATUS` int(1) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว',
  `FRONT_BACK` enum('FRONT','BACK') NOT NULL COMMENT 'จองจากหน้าบ้าน,หลังบ้าน',
  `CREATED` datetime NOT NULL COMMENT 'สร้างเมื่อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `checkin_trash`
--

INSERT INTO `checkin_trash` (`ID`, `customer_ID`, `PHONE`, `room_data_CODE`, `E_START`, `E_END`, `E_BETWEEN`, `PRICE_TOTAL`, `FILE_UPLOAD`, `TRANSFER_BANK_ID`, `TRANSFER_DATETIME`, `TRANSFER_PRICE`, `CHECKOUT`, `BOOKING_PASS`, `STATUS`, `FRONT_BACK`, `CREATED`) VALUES
(5, 00000004, '20000', 'D020601001', '2016-05-17 12:00:00', '2016-05-20 11:59:00', 3, '0.00', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'BACK', '2016-05-13 01:49:20'),
(6, 00000009, '', 'D020601001', '2016-05-18 12:00:00', '2016-05-18 11:59:00', 0, '0.00', 'attached_file/cus00000009_20160518_13.07.35.pdf', 0, '0000-00-00 00:00:00', '0.00', 'NO', '7495', 0, 'FRONT', '2016-05-18 13:04:26'),
(7, 00000009, '', 'D020601001', '2016-05-18 12:00:00', '2016-05-18 11:59:00', 0, '0.00', 'attached_file/cus00000009_20160518_13.07.35.pdf', 0, '0000-00-00 00:00:00', '0.00', 'NO', '7495', 0, 'FRONT', '2016-05-18 13:05:26');

-- --------------------------------------------------------

--
-- Table structure for table `color_primary`
--

CREATE TABLE `color_primary` (
  `ID` int(4) NOT NULL,
  `BG_COLOR` varchar(10) NOT NULL COMMENT 'สีพื้น',
  `TXT_COLOR` varchar(10) NOT NULL COMMENT 'สีอักษร',
  `NAME` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `color_primary`
--

INSERT INTO `color_primary` (`ID`, `BG_COLOR`, `TXT_COLOR`, `NAME`) VALUES
(1, '#F44336', '#FFF', 'Red'),
(2, '#E91E63', '#FFF', 'Pink'),
(3, '#9C27B0', '#FFF', 'Purple'),
(4, '#673AB7', '#FFF', 'Deep Purple\r\n'),
(5, '#3F51B5', '#FFF', 'Indigo'),
(6, '#2196F3', '#FFF', 'Blue'),
(7, '#03A9F4', '#000', 'Light Blue'),
(8, '#00BCD4', '#000', 'Cyan'),
(9, '#009688', '#FFF', 'Teal'),
(10, '#4CAF50', '#000', 'Green\r\n'),
(11, '#8BC34A', '#000', 'Light Green'),
(12, '#CDDC39', '#000', 'Lime'),
(13, '#FFEB3B', '#000', 'Yellow'),
(14, '#FFC107', '#000', 'Amber'),
(15, '#FF9800', '#000', 'Orange'),
(16, '#FF5722', '#FFF', 'Deep Orange'),
(17, '#795548', '#FFF', 'Brown'),
(18, '#9E9E9E', '#000', 'Grey'),
(19, '#607D8B', '#FFF', 'Blue Grey');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `ID` int(8) UNSIGNED ZEROFILL NOT NULL,
  `ID13` varchar(100) NOT NULL,
  `PREFIX_ID` int(3) NOT NULL COMMENT 'คำนำหน้า',
  `FNAME` varchar(100) NOT NULL COMMENT 'ชื่อ',
  `LNAME` varchar(100) NOT NULL COMMENT 'สกุล',
  `ADDRESS` text NOT NULL COMMENT 'ที่อยู่',
  `PHONE` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `EMAIL` varchar(100) NOT NULL COMMENT 'อีเมลล์',
  `USERNAME` varchar(100) NOT NULL COMMENT 'รหัสผู้ใช้งาน',
  `PASSWORD` varchar(100) NOT NULL COMMENT 'รหัสผ่าน',
  `STATUS` int(2) NOT NULL DEFAULT '9' COMMENT 'สถานะ',
  `CREATED` datetime NOT NULL COMMENT 'สร้างเมื่อ',
  `LOGIN_LASTTIME` datetime NOT NULL COMMENT 'เข้าระบบล่าสุด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`ID`, `ID13`, `PREFIX_ID`, `FNAME`, `LNAME`, `ADDRESS`, `PHONE`, `EMAIL`, `USERNAME`, `PASSWORD`, `STATUS`, `CREATED`, `LOGIN_LASTTIME`) VALUES
(00000039, '0000000000000', 1, 'a', 'b', '', '081-3333333', '', '', '', 9, '2017-06-04 12:05:36', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `ID` int(12) NOT NULL,
  `BILLCODE` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `orders_type_ID` int(11) NOT NULL,
  `customer_ID` int(8) UNSIGNED ZEROFILL NOT NULL,
  `PHONE` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `E_START` datetime NOT NULL COMMENT 'เริ่ม',
  `E_END` datetime NOT NULL COMMENT 'สิ้นสุด',
  `E_BETWEEN` int(2) NOT NULL COMMENT 'จำนวนวัน',
  `BEDPLUS1` double NOT NULL COMMENT 'ราคาเตียงเสริม1',
  `BEDPLUS2` double NOT NULL COMMENT 'ราคาเตียงเสริม2',
  `PRICE_BEDPLUS` double NOT NULL COMMENT 'ราคาเตียงเสริม',
  `DISCOUNT` double NOT NULL COMMENT 'ส่วนลด',
  `OTHER_PRICE` double NOT NULL COMMENT 'อื่นๆ(อาหารและเครื่องดื่ม)',
  `PRICE_TOTAL` decimal(11,2) NOT NULL COMMENT 'รวมราคา',
  `DEPOSIT` double NOT NULL COMMENT 'เงินมัดจำ',
  `PAY` double NOT NULL COMMENT 'จ่าย',
  `OWE` double NOT NULL COMMENT 'ค้างชำระ',
  `CASH_CHANGE` double NOT NULL COMMENT 'เงินทอน',
  `PRICE_SUM_TOTAL` double NOT NULL COMMENT 'ยอดรวมทั้งหมด',
  `NOTE` text NOT NULL COMMENT 'หมายเหตุ',
  `FILE_UPLOAD` varchar(200) NOT NULL COMMENT 'ที่อยู่ไฟล์แจ้งการโอน',
  `TRANSFER_BANK_ID` int(2) NOT NULL,
  `TRANSFER_DATETIME` datetime NOT NULL COMMENT 'วันเวลา',
  `TRANSFER_PRICE` decimal(10,2) NOT NULL COMMENT 'โอนจำนวน',
  `CHECKOUT` enum('NO','YES') NOT NULL COMMENT 'สถานะการจ่ายเงิน',
  `BOOKING_PASS` varchar(100) NOT NULL,
  `STATUS` int(1) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว',
  `FRONT_BACK` enum('FRONT','BACK') NOT NULL COMMENT 'จองจากหน้าบ้าน,หลังบ้าน',
  `CREATED` datetime NOT NULL COMMENT 'สร้างเมื่อ',
  `UPDATED` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`ID`, `BILLCODE`, `orders_type_ID`, `customer_ID`, `PHONE`, `E_START`, `E_END`, `E_BETWEEN`, `BEDPLUS1`, `BEDPLUS2`, `PRICE_BEDPLUS`, `DISCOUNT`, `OTHER_PRICE`, `PRICE_TOTAL`, `DEPOSIT`, `PAY`, `OWE`, `CASH_CHANGE`, `PRICE_SUM_TOTAL`, `NOTE`, `FILE_UPLOAD`, `TRANSFER_BANK_ID`, `TRANSFER_DATETIME`, `TRANSFER_PRICE`, `CHECKOUT`, `BOOKING_PASS`, `STATUS`, `FRONT_BACK`, `CREATED`, `UPDATED`) VALUES
(55, 'EV0000003', 2, 00000039, '', '2017-06-04 12:00:00', '2017-06-05 11:59:00', 1, 0, 0, 0, 0, 0, '600.00', 200, 800, -400, 400, 400, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-06-04 12:04:49', '2017-06-04 12:07:12'),
(56, 'EV0000004', 2, 00000039, '', '2017-06-04 12:00:00', '2017-06-05 11:59:00', 1, 0, 0, 0, 0, 0, '600.00', 200, 600, -200, 200, 400, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-06-04 12:07:24', '2017-06-04 12:08:45'),
(57, 'EV0000005', 2, 00000039, '', '2017-12-26 12:00:00', '2017-12-29 11:59:00', 3, 0, 0, 2, 300, 0, '2702.00', 0, 2000, 0, -702, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-12-26 18:08:46', '0000-00-00 00:00:00'),
(58, 'EV0000006', 2, 00000039, '', '2018-03-19 12:00:00', '2018-03-20 11:59:00', 1, 0, 0, 0, 0, 0, '600.00', 0, 0, 0, -600, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2018-03-19 11:39:06', '0000-00-00 00:00:00'),
(59, 'BK0000001', 1, 00000039, '', '2018-03-19 12:00:00', '2018-03-20 11:59:00', 1, 0, 0, 0, 0, 0, '1000.00', 0, 0, 0, -1000, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2018-03-19 11:40:05', '0000-00-00 00:00:00'),
(60, 'EV0000007', 2, 00000039, '', '2018-05-09 12:00:00', '2018-05-14 11:59:00', 5, 0, 0, 0, 0, 0, '8000.00', 0, 0, 8000, -8000, 8000, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2018-05-09 16:49:38', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `events_detail`
--

CREATE TABLE `events_detail` (
  `ID` int(11) NOT NULL,
  `BILLCODE` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `room_data_CODE` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสห้อง',
  `PRICE` double NOT NULL COMMENT 'ราคาห้องพัก',
  `STATUS` int(2) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว',
  `CHK_IN` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `CHK_IN_DATETIME` datetime NOT NULL,
  `CHK_OUT` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `CHK_OUT_DATETIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `events_detail`
--

INSERT INTO `events_detail` (`ID`, `BILLCODE`, `room_data_CODE`, `PRICE`, `STATUS`, `CHK_IN`, `CHK_IN_DATETIME`, `CHK_OUT`, `CHK_OUT_DATETIME`) VALUES
(63, 'EV0000003', 'B101', 600, 2, '1', '2017-06-04 12:05:39', '1', '2017-06-04 12:07:12'),
(64, 'EV0000004', 'B102', 600, 2, '1', '2017-06-04 12:08:05', '1', '2017-06-04 12:08:45'),
(65, 'EV0000005', 'A3', 3000, 1, '1', '2017-12-26 18:09:18', '0', '0000-00-00 00:00:00'),
(66, 'EV0000006', 'B108', 600, 1, '1', '2018-03-19 11:39:40', '0', '0000-00-00 00:00:00'),
(67, 'BK0000001', 'A1', 1000, 1, '1', '2018-03-19 11:40:46', '0', '0000-00-00 00:00:00'),
(68, 'EV0000007', 'A2', 5000, 1, '1', '2018-05-09 16:52:02', '0', '0000-00-00 00:00:00'),
(69, 'EV0000007', 'B105', 3000, 1, '1', '2018-05-09 16:52:02', '0', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders_type`
--

CREATE TABLE `orders_type` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ประเภท',
  `color_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orders_type`
--

INSERT INTO `orders_type` (`ID`, `NAME`, `color_ID`) VALUES
(1, 'การจอง', 0),
(2, 'เชคอิน', 0),
(3, 'กำลังปรับปรุง\r\n', 0);

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `ID` int(2) NOT NULL,
  `NAME` varchar(250) NOT NULL,
  `SHORTNAME` varchar(200) NOT NULL,
  `ADDRESS` text NOT NULL,
  `VAT_ID` varchar(50) NOT NULL COMMENT 'เลขประจำตัวผู้เสียภาษี',
  `PHONE` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`ID`, `NAME`, `SHORTNAME`, `ADDRESS`, `VAT_ID`, `PHONE`) VALUES
(1, 'บูมบูมรีสอร์ท จันทบุรี', 'บูมบูมรีสอร์ท', 'ที่อยู่ 19/9 ม. 6 ต.คลองขุด อ.ท่าใหม่ จ.จันทบุรี\r\nChantaburi', '3102300044017', '039-433-355');

-- --------------------------------------------------------

--
-- Table structure for table `prefix`
--

CREATE TABLE `prefix` (
  `PREFIX_ID` int(4) NOT NULL,
  `PREFIX` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `prefix`
--

INSERT INTO `prefix` (`PREFIX_ID`, `PREFIX`) VALUES
(1, 'นาย'),
(2, 'นาง'),
(3, 'นางสาว'),
(4, ' ');

-- --------------------------------------------------------

--
-- Table structure for table `repair`
--

CREATE TABLE `repair` (
  `ID` int(12) NOT NULL,
  `BILLCODE` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customer_ID` int(8) UNSIGNED ZEROFILL NOT NULL,
  `PHONE` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `E_START` datetime NOT NULL COMMENT 'เริ่ม',
  `E_END` datetime NOT NULL COMMENT 'สิ้นสุด',
  `E_BETWEEN` int(2) NOT NULL COMMENT 'จำนวนวัน',
  `BEDPLUS1` double NOT NULL COMMENT 'ราคาเตียงเสริม1',
  `BEDPLUS2` double NOT NULL COMMENT 'ราคาเตียงเสริม2',
  `PRICE_BEDPLUS` double NOT NULL COMMENT 'ราคาเตียงเสริม',
  `PRICE_TOTAL` decimal(11,2) NOT NULL COMMENT 'รวมราคา',
  `DISCOUNT` double NOT NULL COMMENT 'ส่วนลด',
  `DEPOSIT` double NOT NULL COMMENT 'เงินมัดจำ',
  `PAY` double NOT NULL COMMENT 'จ่าย',
  `CASH_CHANGE` double NOT NULL COMMENT 'เงินทอน',
  `NOTE` text NOT NULL COMMENT 'หมายเหตุ',
  `FILE_UPLOAD` varchar(200) NOT NULL COMMENT 'ที่อยู่ไฟล์แจ้งการโอน',
  `TRANSFER_BANK_ID` int(2) NOT NULL,
  `TRANSFER_DATETIME` datetime NOT NULL COMMENT 'วันเวลา',
  `TRANSFER_PRICE` decimal(10,2) NOT NULL COMMENT 'โอนจำนวน',
  `CHECKOUT` enum('NO','YES') NOT NULL COMMENT 'สถานะการจ่ายเงิน',
  `BOOKING_PASS` varchar(100) NOT NULL,
  `STATUS` int(1) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว',
  `FRONT_BACK` enum('FRONT','BACK') NOT NULL COMMENT 'จองจากหน้าบ้าน,หลังบ้าน',
  `CREATED` datetime NOT NULL COMMENT 'สร้างเมื่อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `repair`
--

INSERT INTO `repair` (`ID`, `BILLCODE`, `customer_ID`, `PHONE`, `E_START`, `E_END`, `E_BETWEEN`, `BEDPLUS1`, `BEDPLUS2`, `PRICE_BEDPLUS`, `PRICE_TOTAL`, `DISCOUNT`, `DEPOSIT`, `PAY`, `CASH_CHANGE`, `NOTE`, `FILE_UPLOAD`, `TRANSFER_BANK_ID`, `TRANSFER_DATETIME`, `TRANSFER_PRICE`, `CHECKOUT`, `BOOKING_PASS`, `STATUS`, `FRONT_BACK`, `CREATED`) VALUES
(12, 'RP000001', 00000009, '', '2017-03-06 12:00:00', '2017-04-28 11:59:00', 2, 0, 0, 0, '2000.00', 0, 0, 0, 0, '', '', 0, '2016-05-02 00:00:00', '0.00', 'YES', '', 0, 'FRONT', '2016-04-26 18:15:21');

-- --------------------------------------------------------

--
-- Table structure for table `repair_detail`
--

CREATE TABLE `repair_detail` (
  `ID` int(11) NOT NULL,
  `BILLCODE` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `room_data_CODE` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสห้อง',
  `PRICE` double NOT NULL COMMENT 'ราคาห้องพัก',
  `STATUS` int(2) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `repair_detail`
--

INSERT INTO `repair_detail` (`ID`, `BILLCODE`, `room_data_CODE`, `PRICE`, `STATUS`) VALUES
(1, 'RP000001', 'D020601003', 1000, 0),
(2, 'RP000001', 'D020601004', 1000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `ID` int(5) NOT NULL,
  `TYPE_ID` int(3) NOT NULL,
  `NAME` varchar(100) NOT NULL,
  `CODE` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `room_data`
--

CREATE TABLE `room_data` (
  `ID` int(5) NOT NULL,
  `CODE` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสห้องพัก',
  `NAME` varchar(200) NOT NULL COMMENT 'ชื่อห้อง',
  `room_type_ID` int(3) NOT NULL COMMENT 'ชนิดห้อง',
  `status_ID` int(2) NOT NULL COMMENT 'สถานะ',
  `LAST_BOOKING` datetime NOT NULL COMMENT 'จองครั้งล่าสุด'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `room_data`
--

INSERT INTO `room_data` (`ID`, `CODE`, `NAME`, `room_type_ID`, `status_ID`, `LAST_BOOKING`) VALUES
(2, 'B101', '101', 8, 1, '0000-00-00 00:00:00'),
(3, 'B102', '102', 9, 1, '0000-00-00 00:00:00'),
(4, 'B103', '103', 9, 1, '0000-00-00 00:00:00'),
(5, 'B104', '104', 9, 1, '0000-00-00 00:00:00'),
(6, 'B105', '105', 9, 1, '0000-00-00 00:00:00'),
(7, 'B106', '106', 9, 1, '0000-00-00 00:00:00'),
(8, 'B107', '107', 9, 1, '0000-00-00 00:00:00'),
(9, 'A1', 'A1', 6, 1, '0000-00-00 00:00:00'),
(10, 'A2', 'A2', 6, 1, '0000-00-00 00:00:00'),
(11, 'A3', 'A3', 6, 1, '0000-00-00 00:00:00'),
(12, 'C201', '201', 13, 1, '0000-00-00 00:00:00'),
(13, 'C202', '202', 10, 1, '0000-00-00 00:00:00'),
(14, 'C203', '203', 13, 1, '0000-00-00 00:00:00'),
(15, 'C204', '204', 10, 1, '0000-00-00 00:00:00'),
(16, 'C206', '206', 10, 1, '0000-00-00 00:00:00'),
(17, 'C207', '207', 10, 1, '0000-00-00 00:00:00'),
(18, 'D301', '301', 14, 1, '0000-00-00 00:00:00'),
(19, 'D302', '302', 14, 1, '0000-00-00 00:00:00'),
(20, 'D303', '303', 14, 1, '0000-00-00 00:00:00'),
(21, 'D304', '304', 14, 1, '0000-00-00 00:00:00'),
(22, 'D305', '305', 14, 1, '0000-00-00 00:00:00'),
(23, 'C205', '205', 13, 0, '0000-00-00 00:00:00'),
(24, 'C208', '208', 10, 0, '0000-00-00 00:00:00'),
(25, 'A4', 'A4', 6, 0, '0000-00-00 00:00:00'),
(26, 'D306', '306', 14, 0, '0000-00-00 00:00:00'),
(27, 'D307', '307', 15, 0, '0000-00-00 00:00:00'),
(28, 'D308', '308', 15, 0, '0000-00-00 00:00:00'),
(29, 'D309', '309', 15, 0, '0000-00-00 00:00:00'),
(30, 'D310', '310', 15, 0, '0000-00-00 00:00:00'),
(31, 'D311', '311', 14, 0, '0000-00-00 00:00:00'),
(32, 'D312', '312', 14, 0, '0000-00-00 00:00:00'),
(33, 'D313', '313', 15, 0, '0000-00-00 00:00:00'),
(34, 'D314', '314', 15, 0, '0000-00-00 00:00:00'),
(35, 'D315', '315', 15, 0, '0000-00-00 00:00:00'),
(36, 'D316', '316', 15, 0, '0000-00-00 00:00:00'),
(37, 'D317', '317', 14, 0, '0000-00-00 00:00:00'),
(38, 'D318', '318', 14, 0, '0000-00-00 00:00:00'),
(41, 'C209', '209', 10, 0, '0000-00-00 00:00:00'),
(42, 'C210', '210', 10, 0, '0000-00-00 00:00:00'),
(43, 'C211', '211', 10, 0, '0000-00-00 00:00:00'),
(44, 'C212', '212', 10, 0, '0000-00-00 00:00:00'),
(45, 'C213', '213', 13, 0, '0000-00-00 00:00:00'),
(46, 'C214', '214', 13, 0, '0000-00-00 00:00:00'),
(47, 'C215', '215', 13, 0, '0000-00-00 00:00:00'),
(48, 'C216', '216', 13, 0, '0000-00-00 00:00:00'),
(49, 'A5', 'A5', 6, 0, '0000-00-00 00:00:00'),
(50, 'A6', 'A6', 7, 0, '0000-00-00 00:00:00'),
(51, 'B108', '108', 8, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `room_img`
--

CREATE TABLE `room_img` (
  `ID` int(10) NOT NULL,
  `room_type_ID` int(3) NOT NULL,
  `IMG_NAME` varchar(300) NOT NULL COMMENT 'ชื่อรูป',
  `CREATED` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `room_img`
--

INSERT INTO `room_img` (`ID`, `room_type_ID`, `IMG_NAME`, `CREATED`) VALUES
(10, 6, 'room/6(0)20160218114523.JPG', '2016-02-18 11:45:23'),
(11, 6, 'room/6(1)20160218114523.JPG', '2016-02-18 11:45:23'),
(12, 6, 'room/6(2)20160218114523.JPG', '2016-02-18 11:45:23'),
(13, 6, 'room/6(3)20160218114523.JPG', '2016-02-18 11:45:23'),
(14, 6, 'room/6(4)20160218114523.JPG', '2016-02-18 11:45:23'),
(15, 6, 'room/6(5)20160218114523.JPG', '2016-02-18 11:45:23'),
(16, 6, 'room/6(6)20160218114523.JPG', '2016-02-18 11:45:23'),
(17, 6, 'room/6(7)20160218114523.jpg', '2016-02-18 11:45:23'),
(18, 7, 'room/7(0)20160218114624.JPG', '2016-02-18 11:46:24'),
(19, 7, 'room/7(1)20160218114624.JPG', '2016-02-18 11:46:24'),
(20, 7, 'room/7(2)20160218114624.JPG', '2016-02-18 11:46:24'),
(21, 7, 'room/7(3)20160218114624.JPG', '2016-02-18 11:46:24'),
(22, 7, 'room/7(4)20160218114624.JPG', '2016-02-18 11:46:24'),
(23, 8, 'room/8(0)20160218114847.JPG', '2016-02-18 11:48:47'),
(24, 8, 'room/8(1)20160218114847.JPG', '2016-02-18 11:48:47'),
(25, 8, 'room/8(2)20160218114847.JPG', '2016-02-18 11:48:47'),
(26, 8, 'room/8(3)20160218114847.JPG', '2016-02-18 11:48:47'),
(27, 8, 'room/8(4)20160218114847.JPG', '2016-02-18 11:48:47'),
(28, 8, 'room/8(5)20160218114847.JPG', '2016-02-18 11:48:47'),
(29, 8, 'room/8(6)20160218114847.JPG', '2016-02-18 11:48:47'),
(30, 9, 'room/9(0)20160218115001.JPG', '2016-02-18 11:50:01');

-- --------------------------------------------------------

--
-- Table structure for table `room_type`
--

CREATE TABLE `room_type` (
  `ID` int(3) NOT NULL,
  `NAME_TYPE` varchar(100) NOT NULL COMMENT 'ชื่อประเภท',
  `SUPPORT` int(2) NOT NULL COMMENT 'จำนวนผู้เข้าพัก',
  `BED_SIZE` varchar(100) NOT NULL COMMENT 'ขนาดเตียง',
  `PRICE` double NOT NULL COMMENT 'ราคาเต็ม'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `room_type`
--

INSERT INTO `room_type` (`ID`, `NAME_TYPE`, `SUPPORT`, `BED_SIZE`, `PRICE`) VALUES
(6, '1000 - เตียงเดี่ยว', 2, '6 ฟุต 1 เตียง', 1000),
(7, '1000 - เตียงคู่', 2, '3.5 ฟุต 2 เตียง', 1000),
(8, '600 - เตียงเดี่ยว', 2, '6 ฟุต 1 เตียง', 600),
(9, '600 - เตียงคู่', 2, '3.5 ฟุต 2 เตียง', 600),
(10, '800 - เตียงเดี่ยว', 2, '6 ฟุต 1 เตียง', 800),
(13, '800 - เตียงคู่', 2, '3.5 ฟุต 2 เตียง', 800),
(14, '700 - เตียงเดี่ยว', 2, '6 ฟุต 1 เตียง', 700),
(15, '700 - เตียงคู่', 2, '3.5 ฟุต 2 เตียง', 700);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `ID` int(2) NOT NULL,
  `STATUS` varchar(200) NOT NULL COMMENT 'สถานะ',
  `STATUS_EN` varchar(200) NOT NULL COMMENT 'สถานะ EN',
  `color_ID` int(4) NOT NULL,
  `COLOR_CODE` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`ID`, `STATUS`, `STATUS_EN`, `color_ID`, `COLOR_CODE`) VALUES
(1, 'ห้องว่าง', '', 9, 'green'),
(2, 'มีผู้เข้าพัก', '', 1, 'blue'),
(3, 'จองแล้ว', '', 13, 'orange'),
(4, 'ไม่พร้อมให้บริการ', '', 5, 'red');

-- --------------------------------------------------------

--
-- Table structure for table `temp01`
--

CREATE TABLE `temp01` (
  `ID` int(12) NOT NULL,
  `BILLCODE` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `orders_type_ID` int(11) NOT NULL,
  `customer_ID` int(8) UNSIGNED ZEROFILL NOT NULL,
  `PHONE` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `E_START` datetime NOT NULL COMMENT 'เริ่ม',
  `E_END` datetime NOT NULL COMMENT 'สิ้นสุด',
  `E_BETWEEN` int(2) NOT NULL COMMENT 'จำนวนวัน',
  `BEDPLUS1` double NOT NULL COMMENT 'ราคาเตียงเสริม1',
  `BEDPLUS2` double NOT NULL COMMENT 'ราคาเตียงเสริม2',
  `PRICE_BEDPLUS` double NOT NULL COMMENT 'ราคาเตียงเสริม',
  `DISCOUNT` double NOT NULL COMMENT 'ส่วนลด',
  `OTHER_PRICE` double NOT NULL COMMENT 'อื่นๆ(อาหารและเครื่องดื่ม)',
  `PRICE_TOTAL` decimal(11,2) NOT NULL COMMENT 'รวมราคา',
  `DEPOSIT` double NOT NULL COMMENT 'เงินมัดจำ',
  `PAY` double NOT NULL COMMENT 'จ่าย',
  `OWE` double NOT NULL COMMENT 'ค้างชำระ',
  `CASH_CHANGE` double NOT NULL COMMENT 'เงินทอน',
  `PRICE_SUM_TOTAL` double NOT NULL COMMENT 'ยอดรวมทั้งหมด',
  `NOTE` text NOT NULL COMMENT 'หมายเหตุ',
  `FILE_UPLOAD` varchar(200) NOT NULL COMMENT 'ที่อยู่ไฟล์แจ้งการโอน',
  `TRANSFER_BANK_ID` int(2) NOT NULL,
  `TRANSFER_DATETIME` datetime NOT NULL COMMENT 'วันเวลา',
  `TRANSFER_PRICE` decimal(10,2) NOT NULL COMMENT 'โอนจำนวน',
  `CHECKOUT` enum('NO','YES') NOT NULL COMMENT 'สถานะการจ่ายเงิน',
  `BOOKING_PASS` varchar(100) NOT NULL,
  `STATUS` int(1) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว',
  `FRONT_BACK` enum('FRONT','BACK') NOT NULL COMMENT 'จองจากหน้าบ้าน,หลังบ้าน',
  `CREATED` datetime NOT NULL COMMENT 'สร้างเมื่อ',
  `UPDATED` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `temp01`
--

INSERT INTO `temp01` (`ID`, `BILLCODE`, `orders_type_ID`, `customer_ID`, `PHONE`, `E_START`, `E_END`, `E_BETWEEN`, `BEDPLUS1`, `BEDPLUS2`, `PRICE_BEDPLUS`, `DISCOUNT`, `OTHER_PRICE`, `PRICE_TOTAL`, `DEPOSIT`, `PAY`, `OWE`, `CASH_CHANGE`, `PRICE_SUM_TOTAL`, `NOTE`, `FILE_UPLOAD`, `TRANSFER_BANK_ID`, `TRANSFER_DATETIME`, `TRANSFER_PRICE`, `CHECKOUT`, `BOOKING_PASS`, `STATUS`, `FRONT_BACK`, `CREATED`, `UPDATED`) VALUES
(1, 'EV0000008', 2, 00000001, '', '2018-05-10 12:00:00', '2018-05-11 11:59:00', 1, 0, 0, 0, 0, 0, '0.00', 0, 0, 0, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2018-05-10 16:11:19', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `temp01h69ok72f9ehdgkhr3erc9paf05`
--

CREATE TABLE `temp01h69ok72f9ehdgkhr3erc9paf05` (
  `ID` int(12) NOT NULL,
  `BILLCODE` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `customer_ID` int(8) UNSIGNED ZEROFILL NOT NULL,
  `PHONE` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `E_START` datetime NOT NULL COMMENT 'เริ่ม',
  `E_END` datetime NOT NULL COMMENT 'สิ้นสุด',
  `E_BETWEEN` int(2) NOT NULL COMMENT 'จำนวนวัน',
  `BEDPLUS1` double NOT NULL COMMENT 'ราคาเตียงเสริม1',
  `BEDPLUS2` double NOT NULL COMMENT 'ราคาเตียงเสริม2',
  `PRICE_BEDPLUS` double NOT NULL COMMENT 'ราคาเตียงเสริม',
  `PRICE_TOTAL` decimal(11,2) NOT NULL COMMENT 'รวมราคา',
  `DISCOUNT` double NOT NULL COMMENT 'ส่วนลด',
  `DEPOSIT` double NOT NULL COMMENT 'เงินมัดจำ',
  `PAY` double NOT NULL COMMENT 'จ่าย',
  `CASH_CHANGE` double NOT NULL COMMENT 'เงินทอน',
  `NOTE` text NOT NULL COMMENT 'หมายเหตุ',
  `FILE_UPLOAD` varchar(200) NOT NULL COMMENT 'ที่อยู่ไฟล์แจ้งการโอน',
  `TRANSFER_BANK_ID` int(2) NOT NULL,
  `TRANSFER_DATETIME` datetime NOT NULL COMMENT 'วันเวลา',
  `TRANSFER_PRICE` decimal(10,2) NOT NULL COMMENT 'โอนจำนวน',
  `CHECKOUT` enum('NO','YES') NOT NULL COMMENT 'สถานะการจ่ายเงิน',
  `BOOKING_PASS` varchar(100) NOT NULL,
  `STATUS` int(1) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว',
  `FRONT_BACK` enum('FRONT','BACK') NOT NULL COMMENT 'จองจากหน้าบ้าน,หลังบ้าน',
  `CREATED` datetime NOT NULL COMMENT 'สร้างเมื่อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `temp01h69ok72f9ehdgkhr3erc9paf05`
--

INSERT INTO `temp01h69ok72f9ehdgkhr3erc9paf05` (`ID`, `BILLCODE`, `customer_ID`, `PHONE`, `E_START`, `E_END`, `E_BETWEEN`, `BEDPLUS1`, `BEDPLUS2`, `PRICE_BEDPLUS`, `PRICE_TOTAL`, `DISCOUNT`, `DEPOSIT`, `PAY`, `CASH_CHANGE`, `NOTE`, `FILE_UPLOAD`, `TRANSFER_BANK_ID`, `TRANSFER_DATETIME`, `TRANSFER_PRICE`, `CHECKOUT`, `BOOKING_PASS`, `STATUS`, `FRONT_BACK`, `CREATED`) VALUES
(1, 'BK0000003', 00000023, '', '2017-03-08 12:00:00', '2017-03-09 11:59:00', 1, 0, 0, 0, '1600.00', 0, 0, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-08 01:04:50');

-- --------------------------------------------------------

--
-- Table structure for table `temp010k0nemrufak5622jtjtldvdok7`
--

CREATE TABLE `temp010k0nemrufak5622jtjtldvdok7` (
  `ID` int(12) NOT NULL,
  `BILLCODE` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `customer_ID` int(8) UNSIGNED ZEROFILL NOT NULL,
  `PHONE` varchar(50) NOT NULL COMMENT 'เบอร์โทรศัพท์',
  `E_START` datetime NOT NULL COMMENT 'เริ่ม',
  `E_END` datetime NOT NULL COMMENT 'สิ้นสุด',
  `E_BETWEEN` int(2) NOT NULL COMMENT 'จำนวนวัน',
  `BEDPLUS1` double NOT NULL COMMENT 'ราคาเตียงเสริม1',
  `BEDPLUS2` double NOT NULL COMMENT 'ราคาเตียงเสริม2',
  `PRICE_BEDPLUS` double NOT NULL COMMENT 'ราคาเตียงเสริม',
  `PRICE_TOTAL` decimal(11,2) NOT NULL COMMENT 'รวมราคา',
  `DISCOUNT` double NOT NULL COMMENT 'ส่วนลด',
  `DEPOSIT` double NOT NULL COMMENT 'เงินมัดจำ',
  `PAY` double NOT NULL COMMENT 'จ่าย',
  `CASH_CHANGE` double NOT NULL COMMENT 'เงินทอน',
  `NOTE` text NOT NULL COMMENT 'หมายเหตุ',
  `FILE_UPLOAD` varchar(200) NOT NULL COMMENT 'ที่อยู่ไฟล์แจ้งการโอน',
  `TRANSFER_BANK_ID` int(2) NOT NULL,
  `TRANSFER_DATETIME` datetime NOT NULL COMMENT 'วันเวลา',
  `TRANSFER_PRICE` decimal(10,2) NOT NULL COMMENT 'โอนจำนวน',
  `CHECKOUT` enum('NO','YES') NOT NULL COMMENT 'สถานะการจ่ายเงิน',
  `BOOKING_PASS` varchar(100) NOT NULL,
  `STATUS` int(1) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว',
  `FRONT_BACK` enum('FRONT','BACK') NOT NULL COMMENT 'จองจากหน้าบ้าน,หลังบ้าน',
  `CREATED` datetime NOT NULL COMMENT 'สร้างเมื่อ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `temp010k0nemrufak5622jtjtldvdok7`
--

INSERT INTO `temp010k0nemrufak5622jtjtldvdok7` (`ID`, `BILLCODE`, `customer_ID`, `PHONE`, `E_START`, `E_END`, `E_BETWEEN`, `BEDPLUS1`, `BEDPLUS2`, `PRICE_BEDPLUS`, `PRICE_TOTAL`, `DISCOUNT`, `DEPOSIT`, `PAY`, `CASH_CHANGE`, `NOTE`, `FILE_UPLOAD`, `TRANSFER_BANK_ID`, `TRANSFER_DATETIME`, `TRANSFER_PRICE`, `CHECKOUT`, `BOOKING_PASS`, `STATUS`, `FRONT_BACK`, `CREATED`) VALUES
(1, 'BK0000002', 00000023, '', '2017-03-07 12:00:00', '2017-03-08 11:59:00', 1, 300, 700, 1000, '1000.00', 0, 100, 0, 0, '', '', 0, '0000-00-00 00:00:00', '0.00', 'NO', '', 0, 'FRONT', '2017-03-07 17:15:48');

-- --------------------------------------------------------

--
-- Table structure for table `temp02`
--

CREATE TABLE `temp02` (
  `ID` int(11) NOT NULL,
  `BILLCODE` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `room_data_CODE` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสห้อง',
  `PRICE` double NOT NULL COMMENT 'ราคาห้องพัก',
  `STATUS` int(2) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว',
  `CHK_IN` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `CHK_IN_DATETIME` datetime NOT NULL,
  `CHK_OUT` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `CHK_OUT_DATETIME` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp02h69ok72f9ehdgkhr3erc9paf05`
--

CREATE TABLE `temp02h69ok72f9ehdgkhr3erc9paf05` (
  `ID` int(11) NOT NULL,
  `BILLCODE` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `room_data_CODE` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสห้อง',
  `PRICE` double NOT NULL COMMENT 'ราคาห้องพัก',
  `STATUS` int(2) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `temp02h69ok72f9ehdgkhr3erc9paf05`
--

INSERT INTO `temp02h69ok72f9ehdgkhr3erc9paf05` (`ID`, `BILLCODE`, `room_data_CODE`, `PRICE`, `STATUS`) VALUES
(1, 'BK0000003', 'S020601001', 800, 0),
(2, 'BK0000003', 'S020601005', 800, 0);

-- --------------------------------------------------------

--
-- Table structure for table `temp020k0nemrufak5622jtjtldvdok7`
--

CREATE TABLE `temp020k0nemrufak5622jtjtldvdok7` (
  `ID` int(11) NOT NULL,
  `BILLCODE` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'เลขที่ใบเสร็จ',
  `room_data_CODE` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสห้อง',
  `PRICE` double NOT NULL COMMENT 'ราคาห้องพัก',
  `STATUS` int(2) NOT NULL COMMENT '0=ยังไม่เข้าพัก,1=เข้าพักแล้ว,2=ออกแล้ว'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `temp020k0nemrufak5622jtjtldvdok7`
--

INSERT INTO `temp020k0nemrufak5622jtjtldvdok7` (`ID`, `BILLCODE`, `room_data_CODE`, `PRICE`, `STATUS`) VALUES
(1, 'BK0000002', 'D020601007', 1000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(8) NOT NULL,
  `PIC` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default.png',
  `USERNAME` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `PASSWORD` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `PREFIX_ID` int(3) DEFAULT '4',
  `FNAME` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `LNAME` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `OFFICE` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ตำแหน่ง',
  `TEL` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `LEVEL` int(11) NOT NULL DEFAULT '1',
  `EDITED` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CREATED` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `PIC`, `USERNAME`, `PASSWORD`, `PREFIX_ID`, `FNAME`, `LNAME`, `OFFICE`, `TEL`, `LEVEL`, `EDITED`, `CREATED`) VALUES
(1, 'default.png', 'admin', '21232f297a57a5a743894a0e4a801fc3', 4, 'Root', 'admin', 'นักเขียนโปรแกรม', NULL, 99, '2017-04-01 04:51:13', '2016-11-03 00:00:00'),
(2, 'default.png', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 4, 'User', 'User', 'ผู้ใช้งานโปรแกรม', NULL, 1, '2016-11-03 11:52:34', '2016-11-03 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bed_plus`
--
ALTER TABLE `bed_plus`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `booking_detail`
--
ALTER TABLE `booking_detail`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `checkin`
--
ALTER TABLE `checkin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `checkin_detail`
--
ALTER TABLE `checkin_detail`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `checkin_trash`
--
ALTER TABLE `checkin_trash`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `events_detail`
--
ALTER TABLE `events_detail`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orders_type`
--
ALTER TABLE `orders_type`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `prefix`
--
ALTER TABLE `prefix`
  ADD PRIMARY KEY (`PREFIX_ID`);

--
-- Indexes for table `repair`
--
ALTER TABLE `repair`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `repair_detail`
--
ALTER TABLE `repair_detail`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `room_data`
--
ALTER TABLE `room_data`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `room_img`
--
ALTER TABLE `room_img`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `room_type`
--
ALTER TABLE `room_type`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `temp01`
--
ALTER TABLE `temp01`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `temp01h69ok72f9ehdgkhr3erc9paf05`
--
ALTER TABLE `temp01h69ok72f9ehdgkhr3erc9paf05`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `temp010k0nemrufak5622jtjtldvdok7`
--
ALTER TABLE `temp010k0nemrufak5622jtjtldvdok7`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `temp02`
--
ALTER TABLE `temp02`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `temp02h69ok72f9ehdgkhr3erc9paf05`
--
ALTER TABLE `temp02h69ok72f9ehdgkhr3erc9paf05`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `temp020k0nemrufak5622jtjtldvdok7`
--
ALTER TABLE `temp020k0nemrufak5622jtjtldvdok7`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_customer_prefix` (`PREFIX_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bed_plus`
--
ALTER TABLE `bed_plus`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `ID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `booking_detail`
--
ALTER TABLE `booking_detail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `checkin`
--
ALTER TABLE `checkin`
  MODIFY `ID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `checkin_detail`
--
ALTER TABLE `checkin_detail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `checkin_trash`
--
ALTER TABLE `checkin_trash`
  MODIFY `ID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `ID` int(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `ID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `events_detail`
--
ALTER TABLE `events_detail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `orders_type`
--
ALTER TABLE `orders_type`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `prefix`
--
ALTER TABLE `prefix`
  MODIFY `PREFIX_ID` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `repair`
--
ALTER TABLE `repair`
  MODIFY `ID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `repair_detail`
--
ALTER TABLE `repair_detail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `room_data`
--
ALTER TABLE `room_data`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `room_img`
--
ALTER TABLE `room_img`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `room_type`
--
ALTER TABLE `room_type`
  MODIFY `ID` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `ID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `temp01`
--
ALTER TABLE `temp01`
  MODIFY `ID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `temp01h69ok72f9ehdgkhr3erc9paf05`
--
ALTER TABLE `temp01h69ok72f9ehdgkhr3erc9paf05`
  MODIFY `ID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `temp010k0nemrufak5622jtjtldvdok7`
--
ALTER TABLE `temp010k0nemrufak5622jtjtldvdok7`
  MODIFY `ID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `temp02`
--
ALTER TABLE `temp02`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `temp02h69ok72f9ehdgkhr3erc9paf05`
--
ALTER TABLE `temp02h69ok72f9ehdgkhr3erc9paf05`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `temp020k0nemrufak5622jtjtldvdok7`
--
ALTER TABLE `temp020k0nemrufak5622jtjtldvdok7`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
