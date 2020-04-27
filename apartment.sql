-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 27, 2020 at 03:34 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apartment`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `admin_usr` varchar(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL COMMENT 'ชื่อผู้ใช้',
  `admin_pwd` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'รหัสผ่าน',
  `admin_level` int(11) NOT NULL COMMENT 'ระดับสิทธิ์การใช้งาน (0,1)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`admin_usr`, `admin_pwd`, `admin_level`) VALUES
('admin', '$2y$10$D2GaK8Dc3i3xtoGafHZ2Z.BgHS4dNkN11sQEFUYXIRZrK.SNiiPf.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_bill`
--

CREATE TABLE `tb_bill` (
  `bill_date` varchar(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL COMMENT 'วันที่บิลออก',
  `bill_room` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ห้องที่ต้องจ่ายบิล',
  `bill_nrg` double NOT NULL DEFAULT 0 COMMENT 'ค่าไฟ',
  `bill_nrg_price` double NOT NULL DEFAULT 3.5 COMMENT 'หน่วยที่ใช้',
  `bill_water` double NOT NULL DEFAULT 0 COMMENT 'ค่าน้ำ',
  `bill_water_price` double NOT NULL DEFAULT 7 COMMENT 'หน่วยที่ใช้',
  `bill_price` double NOT NULL DEFAULT 0 COMMENT 'ค่าห้อง',
  `bill_invoice` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ที่อยู่ใบแจ้งหนี้',
  `bill_paydate` varchar(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT '' COMMENT 'วันที่จ่ายบิล',
  `bill_receipt` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ตำแหน่งภาพสลิปในระบบ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tb_report`
--

CREATE TABLE `tb_report` (
  `report_date` varchar(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL COMMENT 'วันที่รายงาน',
  `report_room` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ห้องที่รายงาน',
  `report_detail` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'รายละเอียดรายงาน',
  `report_photo` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ภาพประกอบการรายงาน',
  `report_fix` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'แก้ไขปัญหาแล้วหรือยัง'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tb_reserve`
--

CREATE TABLE `tb_reserve` (
  `date` char(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT '//' COMMENT 'วันที่จอง',
  `name` varchar(32) COLLATE utf8mb4_bin NOT NULL DEFAULT 'คุณ' COMMENT 'ชื่อผู้จอง',
  `phone` char(10) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT '--' COMMENT 'เบอร์โทรศัพท์',
  `room` char(7) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT '' COMMENT 'ห้องที่ขอจอง',
  `type` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ประเภทห้อง',
  `receipt` text CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT 'ตำแหน่งไฟล์สลิป'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `tb_room`
--

CREATE TABLE `tb_room` (
  `room_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'หมายเลขห้อง',
  `room_pwd` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'password' COMMENT 'รหัสผ่านเข้าถึงระบบ',
  `room_type` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '''normal''' COMMENT 'ชนิดห้อง',
  `room_owner` varchar(256) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT 'owner' COMMENT 'ชื่อผู้เช่า',
  `room_owner_addr` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT 'ที่อยู่เจ้าของห้อง',
  `room_owner_phone` text CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT '' COMMENT 'เบอร์โทรเจ้าของห้อง'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='ตารางจัดเก็บห้อง';

--
-- Dumping data for table `tb_room`
--

INSERT INTO `tb_room` (`room_id`, `room_pwd`, `room_type`, `room_owner`, `room_owner_addr`, `room_owner_phone`) VALUES
('A1', '$2y$10$yG.cp.5IZv1SYC4VD8MVu.7QIEghjzc9D.nMvsrWSx/uCPIkR4NGu', 'รายเดือน', '', '', ''),
('A2', '', 'รายเดือน', '', '', ''),
('A3', '', 'รายเดือน', '', '', ''),
('A4', '', 'รายเดือน', '', '', ''),
('A5', '', 'รายเดือน', '', '', ''),
('A6', '', 'รายเดือน', '', '', ''),
('B1', '', 'รายเดือน', '', '', ''),
('B2', '', 'รายเดือน', '', '', ''),
('B3', '', 'รายเดือน', '', '', ''),
('B4', '', 'รายเดือน', '', '', ''),
('B5', '', 'รายเดือน', '', '', ''),
('B6', '', 'รายเดือน', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`admin_usr`);

--
-- Indexes for table `tb_bill`
--
ALTER TABLE `tb_bill`
  ADD PRIMARY KEY (`bill_date`);

--
-- Indexes for table `tb_report`
--
ALTER TABLE `tb_report`
  ADD PRIMARY KEY (`report_date`);

--
-- Indexes for table `tb_reserve`
--
ALTER TABLE `tb_reserve`
  ADD PRIMARY KEY (`date`);

--
-- Indexes for table `tb_room`
--
ALTER TABLE `tb_room`
  ADD PRIMARY KEY (`room_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
