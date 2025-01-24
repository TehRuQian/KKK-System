-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2025 at 06:10 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kkk`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `a_adminID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`a_adminID`) VALUES
(12345678),
(12345679);

-- --------------------------------------------------------

--
-- Table structure for table `tb_banner`
--

CREATE TABLE `tb_banner` (
  `b_bannerID` int(11) NOT NULL,
  `b_name` varchar(255) NOT NULL,
  `b_banner` varchar(50) NOT NULL,
  `b_status` tinyint(1) NOT NULL COMMENT 'True if active',
  `b_dateUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `b_adminID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_banner`
--

INSERT INTO `tb_banner` (`b_bannerID`, `b_name`, `b_banner`, `b_status`, `b_dateUpdated`, `b_adminID`) VALUES
(1, 'Default Advertisement', 'advertisement.jpg', 1, '2025-01-21 09:04:58', 12345678),
(2, 'Boat', 'boat.png', 1, '2025-01-21 13:29:12', 12345678),
(3, 'MAHA 2024', '454968363_1071110884641382_8049550342449933270_n.j', 0, '2025-01-21 13:33:55', 12345678),
(4, 'Carta Organisasi KADA', '20241201-CARTA-LEMBAGA-new-1.jpg', 1, '2025-01-21 13:33:16', 12345678);

-- --------------------------------------------------------

--
-- Table structure for table `tb_feedback`
--

CREATE TABLE `tb_feedback` (
  `fb_feedbackID` int(11) NOT NULL,
  `fb_content` varchar(255) NOT NULL,
  `fb_submitDate` datetime DEFAULT NULL,
  `fb_comment` varchar(255) DEFAULT NULL,
  `fb_memberNo` int(11) NOT NULL,
  `fb_status` int(11) NOT NULL,
  `fb_type` int(11) NOT NULL,
  `fb_adminID` int(11) DEFAULT NULL,
  `fb_editStatusDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_feedback`
--

INSERT INTO `tb_feedback` (`fb_feedbackID`, `fb_content`, `fb_submitDate`, `fb_comment`, `fb_memberNo`, `fb_status`, `fb_type`, `fb_adminID`, `fb_editStatusDate`) VALUES
(1, 'a', '2025-01-24 12:37:30', NULL, 1001, 7, 1, NULL, NULL),
(2, 'b', '2025-01-24 12:37:38', '', 1001, 1, 2, 12345678, '2025-01-24 12:40:45');

-- --------------------------------------------------------

--
-- Table structure for table `tb_financial`
--

CREATE TABLE `tb_financial` (
  `f_memberNo` int(11) NOT NULL,
  `f_shareCapital` double NOT NULL COMMENT 'Modah Syer',
  `f_feeCapital` double NOT NULL COMMENT 'Modal Yuran',
  `f_fixedSaving` double NOT NULL COMMENT 'Simpanan Tetap',
  `f_memberFund` double NOT NULL COMMENT 'Tabung Anggota (Al-Abrar)',
  `f_memberSaving` double NOT NULL COMMENT 'Simpanan Anggota',
  `f_dateUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_financial`
--

INSERT INTO `tb_financial` (`f_memberNo`, `f_shareCapital`, `f_feeCapital`, `f_fixedSaving`, `f_memberFund`, `f_memberSaving`, `f_dateUpdated`) VALUES
(1000, 400, 0, 50, 20, 0, '2025-01-21 14:54:58'),
(1001, 200, 0, 50, 20, 0, '2025-01-21 14:52:08'),
(1003, 300, 0, 150, 20, 50, '2025-01-21 14:52:08'),
(1004, 150, 0, 0, 15, 0, '2025-01-21 14:52:08'),
(1005, 150, 0, 0, 15, 0, '2025-01-21 14:52:08'),
(1006, 150, 0, 0, 15, 0, '2025-01-21 14:52:08'),
(1007, 300, 0, 50, 15, 0, '2025-01-21 14:52:08'),
(1008, 550, 0, 50, 10, 0, '2025-01-21 14:52:08'),
(1009, 100, 0, 0, 10, 0, '2025-01-21 14:52:08'),
(1010, 100, 0, 0, 10, 0, '2025-01-21 14:52:08'),
(1011, 50, 0, 0, 5, 0, '2025-01-21 14:52:08'),
(1012, 50, 0, 0, 5, 0, '2025-01-21 14:52:08');

-- --------------------------------------------------------

--
-- Table structure for table `tb_ftype`
--

CREATE TABLE `tb_ftype` (
  `fb_id` int(11) NOT NULL,
  `fb_desc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_ftype`
--

INSERT INTO `tb_ftype` (`fb_id`, `fb_desc`) VALUES
(1, 'Cadangan'),
(2, 'Masalah');

-- --------------------------------------------------------

--
-- Table structure for table `tb_guarantor`
--

CREATE TABLE `tb_guarantor` (
  `g_guarantorID` int(11) NOT NULL,
  `g_loanApplicationID` int(11) NOT NULL,
  `g_memberNo` int(11) NOT NULL,
  `g_signature` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_guarantor`
--

INSERT INTO `tb_guarantor` (`g_guarantorID`, `g_loanApplicationID`, `g_memberNo`, `g_signature`) VALUES
(3, 4, 1001, 'gambar_penjamin1_4.png'),
(4, 4, 1000, 'gambar_penjamin2_4.jpg'),
(5, 5, 1010, 'gambar_penjamin1_5.jpg'),
(6, 5, 1009, 'gambar_penjamin2_5.jpg'),
(7, 5, 1004, 'gambar_penjamin1_5.jpg'),
(8, 5, 1005, 'gambar_penjamin2_5.jpg'),
(9, 6, 1005, 'gambar_penjamin1_6.jpg'),
(10, 6, 1006, 'gambar_penjamin2_6.jpg'),
(11, 7, 1010, 'gambar_penjamin1_7.jpg'),
(12, 7, 1000, 'gambar_penjamin2_7.jpg'),
(13, 8, 1001, 'gambar_penjamin1_8.png'),
(14, 8, 1005, 'gambar_penjamin2_8.jpg'),
(15, 9, 1003, 'gambar_penjamin1_9.png'),
(16, 9, 1011, 'gambar_penjamin2_9.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_heir`
--

CREATE TABLE `tb_heir` (
  `h_heirID` int(11) NOT NULL,
  `h_memberApplicationID` int(11) NOT NULL,
  `h_name` varchar(50) NOT NULL,
  `h_relationWithMember` int(10) NOT NULL,
  `h_ic` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_heir`
--

INSERT INTO `tb_heir` (`h_heirID`, `h_memberApplicationID`, `h_name`, `h_relationWithMember`, `h_ic`) VALUES
(1, 1, 'Tan Mei Mei', 5, '010203-01-0102'),
(2, 1, 'Tan Ah Bang', 5, '000102-01-1234'),
(3, 1, 'Tan Ah Ba', 4, '740202-02-0202'),
(4, 2, 'Roslan bin Ayah', 4, '780202-08-0131'),
(5, 2, 'Siti bin Vali', 4, '760101-09-1234'),
(6, 2, 'Faris bin Roslan', 5, '040404-09-1231'),
(7, 3, 'Ali A/L Habib', 4, '451123-01-0123'),
(8, 3, 'Seeri A/P Wani', 4, '481124-01-0124'),
(9, 3, 'Theena A/P Pravin', 2, '040102-01-0124'),
(10, 4, 'Lam Ji Dan', 2, '040103-08-0123'),
(11, 4, 'Lam Fan Shu', 2, '010101-08-1234'),
(12, 4, 'Wong Mei Ling', 1, '802020-08-0123'),
(13, 4, 'LamYoye', 5, '760202-08-1234'),
(14, 5, 'Razak bin Rahman', 4, '670808-03-1231'),
(15, 5, 'Izyan binti Abdul', 4, '690504-03-1234'),
(16, 5, 'Putra bin Razak', 5, '970909-03-1231'),
(17, 5, 'Izzati binti Razak', 5, '991010-03-1234'),
(18, 6, 'Aminah binti Faizul', 2, '990505-02-1234'),
(19, 6, 'Hassan bin Faizul', 2, '980808-02-5677'),
(20, 6, 'Zainab binti Faizul', 2, '950303-02-9012'),
(21, 7, 'Zarina binti Razif', 2, '910202-05-5678'),
(22, 7, 'Ismail bin Razif', 2, '920303-05-1234'),
(23, 7, 'Hidayah binti Razif', 2, '950404-05-5678'),
(24, 8, 'Razif bin Abdullah', 4, '690101-05-2345'),
(25, 8, 'Zarina binti Razif', 5, '910202-05-5678'),
(26, 8, 'Ismail bin Razif', 5, '920303-05-1234'),
(27, 8, 'HidayahbintiRazif', 5, '950404-05-5678'),
(28, 9, 'Zainal bin Ibrahim', 1, '690101-11-3456'),
(29, 9, 'Rohani binti Ismail', 4, '430505-08-2345'),
(30, 9, 'Norazlin binti Zainal', 2, '980505-08-9876'),
(31, 9, 'KhalidbinZainal', 2, '950404-08-6789'),
(32, 10, 'Ahmad Faisal bin Mohd Yusof', 5, '871112-12-3457'),
(33, 10, 'Mohd Yusof bin Ahmad', 4, '550101-10-8765'),
(34, 10, 'Siti Nurhaliza binti Rosli', 4, '560215-08-5678'),
(35, 11, 'Mohamed bin Abdul Latif', 4, '480104-01-2345'),
(36, 11, 'Zainab binti Ibrahim', 1, '920604-02-6789'),
(37, 11, 'Mohd Faizal bin Mohamed', 5, '880815-12-5678'),
(38, 12, 'Sekari bin Fazli', 4, '420303-01-5675'),
(39, 12, 'Ahmad Shamsul bin Sekari', 5, '830701-05-6789'),
(40, 12, 'Roslina binti Ismail', 1, '870101-08-2345'),
(41, 13, 'Chen Zhi Hao', 4, '880123-14-6789'),
(42, 13, 'Tan Siew Lan', 1, '890101-02-3453'),
(43, 13, 'Chen Yu Ling', 2, '100305-12-1234'),
(44, 14, 'Rohan Kumar', 1, '920301-04-0333'),
(45, 14, 'Lakshmi Kumar', 5, '850301-08-0777'),
(46, 14, 'Theena Khan', 5, '900101-09-1234'),
(47, 15, 'Haris bin Chik', 5, '850716-12-3456'),
(48, 15, 'Chick bin Che Ya', 4, '470514-01-2345'),
(49, 15, 'Farhana binti Chik', 5, '920202-03-2345'),
(50, 16, 'Siti Aisyah binti Fauzi', 4, '510212-07-5678'),
(51, 16, 'Ishak bin Abdul Rahman', 4, '460215-01-2345'),
(52, 16, 'Mohamed bin Ishak', 5, '850621-10-6789'),
(53, 17, 'Hasnah binti Mahmud', 1, '840101-02-3456'),
(54, 17, 'Kamarul bin Kamaluddin', 2, '060302-14-2345'),
(55, 17, 'Siti Mariam binti Ismail', 4, '550711-06-6789'),
(56, 18, 'Abdul Rahman bin Abdul Malik', 4, '510203-08-2345'),
(57, 18, 'Abdul Malik bin Zainal', 1, '850201-12-3456'),
(58, 18, 'Mohd Nizam bin Abdul Rahman', 5, '890904-06-2345');

-- --------------------------------------------------------

--
-- Table structure for table `tb_homestate`
--

CREATE TABLE `tb_homestate` (
  `st_id` int(11) NOT NULL,
  `st_desc` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_homestate`
--

INSERT INTO `tb_homestate` (`st_id`, `st_desc`) VALUES
(1, 'Johor'),
(2, 'Kedah'),
(3, 'Kelantan'),
(4, 'Melaka'),
(5, 'Negeri Sembilan'),
(6, 'Pahang'),
(7, 'Pulau Pinang'),
(8, 'Sabah'),
(9, 'Sarawak'),
(10, 'Selangor'),
(11, 'Terengganu'),
(12, 'WP Kuala Lumpur'),
(13, 'WP Labuan'),
(14, 'WP Putrajaya'),
(15, 'Perak'),
(16, 'Perlis');

-- --------------------------------------------------------

--
-- Table structure for table `tb_hrelation`
--

CREATE TABLE `tb_hrelation` (
  `hr_rid` int(11) NOT NULL,
  `hr_desc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_hrelation`
--

INSERT INTO `tb_hrelation` (`hr_rid`, `hr_desc`) VALUES
(1, 'Suami Isteri'),
(2, 'Anak'),
(3, 'Keturunan'),
(4, 'Orang Tua'),
(5, 'Saudara kandung'),
(6, 'Lain-lain');

-- --------------------------------------------------------

--
-- Table structure for table `tb_lbank`
--

CREATE TABLE `tb_lbank` (
  `lb_id` int(11) NOT NULL,
  `lb_desc` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_lbank`
--

INSERT INTO `tb_lbank` (`lb_id`, `lb_desc`) VALUES
(1, 'Affin Bank'),
(2, 'Agrobank'),
(3, 'Al Rajhi Bank Malaysia'),
(4, 'Alliance Bank'),
(5, 'AmBank'),
(6, 'Bank Islam'),
(7, 'Bank Muamalat'),
(8, 'Bank Rakyat'),
(9, 'BSN'),
(10, 'CIMB'),
(11, 'Citibank Malaysia'),
(12, 'Co-op Bank Pertama'),
(13, 'Hong Leong Bank'),
(14, 'HSBC Malaysia'),
(15, 'Maybank'),
(16, 'MBSB Bank'),
(17, 'OCBC Malaysia'),
(18, 'Public Bank'),
(19, 'RHB'),
(20, 'Standard Chartered Malaysia'),
(21, 'UOB Malaysia');

-- --------------------------------------------------------

--
-- Table structure for table `tb_loan`
--

CREATE TABLE `tb_loan` (
  `l_loanApplicationID` int(11) NOT NULL,
  `l_memberNo` int(11) NOT NULL,
  `l_loanType` int(11) NOT NULL,
  `l_appliedLoan` double NOT NULL,
  `l_loanPeriod` int(11) NOT NULL COMMENT '(Years)',
  `l_monthlyInstalment` double NOT NULL COMMENT '\r\n',
  `l_loanPayable` double NOT NULL,
  `l_bankAccountNo` int(11) NOT NULL,
  `l_bankName` int(11) NOT NULL,
  `l_monthlyGrossSalary` double NOT NULL,
  `l_monthlyNetSalary` double NOT NULL,
  `l_signature` varchar(50) NOT NULL,
  `l_file` varchar(50) NOT NULL COMMENT '(Pengesahan Majikan)',
  `l_status` int(11) NOT NULL,
  `l_applicationDate` datetime DEFAULT NULL,
  `l_approvalDate` timestamp NULL DEFAULT NULL,
  `l_adminID` int(11) DEFAULT NULL COMMENT 'Admin who approved the application'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_loan`
--

INSERT INTO `tb_loan` (`l_loanApplicationID`, `l_memberNo`, `l_loanType`, `l_appliedLoan`, `l_loanPeriod`, `l_monthlyInstalment`, `l_loanPayable`, `l_bankAccountNo`, `l_bankName`, `l_monthlyGrossSalary`, `l_monthlyNetSalary`, `l_signature`, `l_file`, `l_status`, `l_applicationDate`, `l_approvalDate`, `l_adminID`) VALUES
(4, 1003, 3, 2000, 2, 91.67, 1924.99, 1234567890, 10, 2000, 1800, 'gambar_pemohon_4.png', 'pengesahan_majikan_4_1737462659.pdf', 3, '2024-11-15 20:29:55', '2024-11-25 05:33:49', 12345678),
(5, 1003, 3, 10000, 4, 243.33, 11436.67, 1234567890, 10, 2000, 1800, 'gambar_pemohon_5.png', 'pengesahan_majikan_5_1737467251.pdf', 3, '2024-12-04 21:44:54', '2024-12-30 14:49:05', NULL),
(6, 1007, 5, 10000, 5, 201.67, 11898.33, 1234567890, 10, 3500, 3200, 'gambar_pemohon_6.jpg', 'pengesahan_majikan_6_1737467585.pdf', 3, '2024-12-13 21:51:56', '2024-12-30 07:22:12', 12345679),
(7, 1007, 6, 1000, 2, 45.17, 1084, 1234567890, 6, 3500, 3200, 'gambar_pemohon_7.jpg', 'pengesahan_majikan_7_1737467687.pdf', 2, '2024-12-13 21:53:49', '2024-12-30 07:22:22', 12345679),
(8, 1008, 4, 1000, 3, 31.28, 1094.72, 1234567890, 7, 3000, 2800, 'gambar_pemohon_8.jpg', 'pengesahan_majikan_8_1737467945.pdf', 3, '2024-12-17 21:58:11', '2024-12-30 07:22:30', 12345679),
(9, 1000, 4, 5000, 5, 100.83, 6050, 1234567890, 1, 3000, 2800, 'gambar_pemohon_9.jpg', 'pengesahan_majikan_9_1737471586.pdf', 1, '2025-01-21 22:56:57', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_ltype`
--

CREATE TABLE `tb_ltype` (
  `lt_lid` int(11) NOT NULL,
  `lt_desc` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_ltype`
--

INSERT INTO `tb_ltype` (`lt_lid`, `lt_desc`) VALUES
(1, 'Al-Bai'),
(2, 'Al-Innah'),
(3, 'Baik Pulih Kenderaan'),
(4, 'Road Tax dan Insurans'),
(5, 'Khas'),
(6, 'Karnival Musim Istimewa'),
(7, 'Al-Qadrul Hassan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_member`
--

CREATE TABLE `tb_member` (
  `m_memberApplicationID` int(11) NOT NULL,
  `m_name` varchar(50) NOT NULL,
  `m_ic` varchar(14) NOT NULL,
  `m_email` varchar(255) NOT NULL,
  `m_gender` int(11) NOT NULL,
  `m_religion` int(11) NOT NULL,
  `m_race` int(11) NOT NULL,
  `m_maritalStatus` int(11) NOT NULL,
  `m_homeAddress` varchar(50) NOT NULL,
  `m_homePostcode` int(11) NOT NULL,
  `m_homeCity` varchar(50) NOT NULL,
  `m_homeState` int(11) NOT NULL,
  `m_memberNo` int(11) DEFAULT NULL,
  `m_pfNo` int(11) DEFAULT NULL,
  `m_position` varchar(50) NOT NULL,
  `m_positionGrade` varchar(11) NOT NULL,
  `m_officeAddress` varchar(50) NOT NULL,
  `m_officePostcode` int(11) NOT NULL,
  `m_officeCity` varchar(50) NOT NULL,
  `m_officeState` int(11) NOT NULL,
  `m_faxNumber` int(11) DEFAULT NULL,
  `m_phoneNumber` varchar(13) NOT NULL,
  `m_homeNumber` varchar(13) DEFAULT NULL,
  `m_monthlySalary` int(11) NOT NULL,
  `m_feeMasuk` double NOT NULL,
  `m_modalSyer` double NOT NULL,
  `m_modalYuran` double NOT NULL,
  `m_deposit` double NOT NULL,
  `m_alAbrar` double NOT NULL COMMENT 'Sumbangan Tabung Tabung Kebajikan ',
  `m_simpananTetap` double NOT NULL,
  `m_feeLain` double NOT NULL,
  `m_status` int(11) NOT NULL,
  `m_applicationDate` datetime DEFAULT NULL,
  `m_approvalDate` timestamp NULL DEFAULT NULL,
  `m_adminID` int(11) DEFAULT NULL COMMENT 'Admin who approve the application'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_member`
--

INSERT INTO `tb_member` (`m_memberApplicationID`, `m_name`, `m_ic`, `m_email`, `m_gender`, `m_religion`, `m_race`, `m_maritalStatus`, `m_homeAddress`, `m_homePostcode`, `m_homeCity`, `m_homeState`, `m_memberNo`, `m_pfNo`, `m_position`, `m_positionGrade`, `m_officeAddress`, `m_officePostcode`, `m_officeCity`, `m_officeState`, `m_faxNumber`, `m_phoneNumber`, `m_homeNumber`, `m_monthlySalary`, `m_feeMasuk`, `m_modalSyer`, `m_modalYuran`, `m_deposit`, `m_alAbrar`, `m_simpananTetap`, `m_feeLain`, `m_status`, `m_applicationDate`, `m_approvalDate`, `m_adminID`) VALUES
(1, 'Tan Mei Ling', '040201-08-0666', 'tanmeiling@gmail.com', 2, 2, 2, 1, '21, Jalan Rumah', 12345, 'Bukit Mertajam', 7, NULL, 1004, 'Kerani', 'N12', '21, Jalan Pejabat', 12345, 'Kota Bahru', 3, NULL, '0123456789', '', 2000, 35, 300, 0, 0, 5, 50, 0, 2, '2024-09-02 08:54:56', '2024-09-30 01:26:55', 12345678),
(2, 'Roslan bin Roslan', '010101-01-0101', 'roslan@gmail.com', 1, 1, 1, 2, '67, Jalan Duta', 10000, 'Duta', 8, 1000, 1005, 'Pengurus', '100', '12, Jalan Bahagia', 51000, 'Kota Bahru', 3, NULL, '0123456789', '', 3000, 35, 300, 0, 0, 5, 50, 0, 3, '2024-09-14 08:59:31', '2024-09-30 01:27:35', 12345678),
(3, 'Pravin A/L Ali', '891212-01-1231', 'pravin@gmail.com', 1, 3, 3, 1, '123, Jalan Bendera', 80000, 'Bendera', 1, 1001, 1006, 'Petani', 'P12', '123, Jalan Kelantan', 12345, 'Kota Bahru', 3, NULL, '0123456789', '071234567', 2000, 35, 300, 0, 0, 5, 50, 0, 3, '2024-09-15 09:08:12', '2024-09-30 01:33:16', 12345678),
(4, 'Lam Kin Xin', '781213-09-0123', 'lamkinxin@gmail.com', 1, 2, 2, 1, '78, Jalan Ah Fook', 12345, 'Ipoh', 6, 1003, 1007, 'Jurutera', 'N12', '78, Jalan Koperasi', 51021, 'Kota Bahru', 3, NULL, '0123456789', '', 2000, 35, 300, 0, 0, 5, 50, 0, 3, '2024-09-23 09:19:44', '2024-09-30 01:34:21', 12345678),
(5, 'Farra bin Razak', '960304-03-1234', 'farra@gmail.com', 2, 1, 1, 2, '98, Jalan Kelantan', 50000, 'Kota Bahru', 3, 1004, 1008, 'Pengurus', 'N12', '98, Jalan Koperasi', 50010, 'Kota Bahru', 3, NULL, '0123456789', '', 4000, 35, 300, 0, 0, 5, 50, 0, 3, '2024-10-10 09:45:43', '2024-10-28 04:38:54', 12345678),
(6, 'Faizul bin Mustafa', '770101-02-5678', 'faizul@gmail.com', 1, 1, 1, 2, '45, Jalan Taman', 5100, 'Bakar Arang', 2, 1005, 1009, 'Pengurus Besar', 'A1', '127, Jalan Dato&rsquo;Lundang', 15710, 'Kota Bharu', 3, NULL, '0123456789', '', 5000, 35, 300, 0, 0, 5, 50, 0, 3, '2024-10-10 11:57:23', '2024-10-28 04:40:40', 12345678),
(7, 'Razif bin Abdullah', '690130-05-2345', 'razif@gmail.com', 1, 1, 1, 2, '12, Jalan Seri Perdana, Taman Kota Bahagia', 15300, 'Kota Bharu', 3, 1006, 1010, 'Timbalan Pengurus Besar', 'A2', '127, Jalan Dato&rsquo;Lundang', 15710, 'Kota Bharu', 3, NULL, '0123456789', '', 4000, 35, 300, 0, 0, 5, 50, 0, 3, '2024-10-14 12:02:40', '2024-10-28 04:40:50', 12345678),
(8, 'Ali bin Razif', '980505-05-8765', 'ali@gmail.com', 1, 1, 1, 1, '32, Jalan Perdana, Taman Kota Bahagia', 15300, 'Kota Bharu', 3, NULL, 1011, 'Pengarah', 'B1', '127, Jalan Dato&rsquo;Lundang', 15710, 'Kota Bharu', 3, NULL, '0123456789', '', 3500, 35, 300, 0, 0, 5, 50, 0, 2, '2024-10-23 12:06:31', '2024-10-28 04:41:04', 12345678),
(9, 'Hayati binti Omar', '690101-11-2345', 'hayati@gmail.com', 2, 1, 1, 2, '10, Jalan Seri Cahaya, Taman Aman', 20000, 'Kuala Terengganu', 11, 1007, 1012, 'Pengarah', 'B1', '127, Jalan Dato Lundang', 15710, 'Kota Bharu', 3, NULL, '01112345678', '', 3500, 35, 300, 0, 0, 5, 50, 0, 3, '2024-10-24 12:15:06', '2024-10-28 04:41:13', 12345678),
(10, 'Ahmad Zaki bin Mohd Yusof', '820815-12-5678', 'ahmadzaki@gmail.com', 1, 1, 1, 2, '123, Jalan Kemubu 3, Kampung Kemubu', 16450, 'Tumpat', 3, 1008, 1013, 'Pegawai Pembangunan Pertanian', 'C1', 'Pejabat KADA Jajahan Kota Bharu Utara, Mulong', 16010, 'Kota Bharu', 3, NULL, '0123456789', '099876543', 3000, 35, 300, 0, 0, 5, 50, 0, 3, '2024-10-29 12:28:34', '2024-11-25 06:20:35', 12345678),
(11, 'Ikhmal bin Mohamed', '750910-14-2345', 'ikhmal@gmail.com', 1, 1, 1, 2, '56, Jalan Seri Indah 2, Kampung Indah', 16010, 'Kota Bharu', 3, NULL, 1014, 'Pegawai Penyeliaan Projek', 'C2', 'Pejabat KADA Jajahan Kota Bharu Selatan, Melor', 16400, 'Kota Bharu', 3, NULL, '0136789012', '', 3000, 35, 300, 0, 0, 5, 50, 0, 2, '2024-11-12 12:33:05', '2024-11-25 06:21:02', 12345678),
(12, 'Shazlan bin Sekari @ Shokri', '630505-10-2345', 'shazlan@gmail.com', 1, 1, 1, 2, '78, Jalan Sekari, Taman Kemajuan', 15300, 'Kota Bharu', 3, 1009, 1015, 'Ketua Pegawai Pejabat KADA Jajahan Kota Bharu Utar', 'A3', 'Pejabat KADA Jajahan Kota Bharu Utara, Mulong', 16010, 'Kota Bharu', 3, NULL, '0174567890', '098765431', 4000, 35, 300, 0, 10, 5, 50, 0, 3, '2024-11-14 12:55:12', '2024-11-25 06:21:18', 12345678),
(13, 'Chen Wei Liang', '880702-11-2345', 'chenweiliang@gmail.com', 1, 2, 2, 2, '24, Jalan Taman Mutiara', 15000, 'Kota Bharu', 3, 1010, 1016, 'Penyelia Pertanian', 'C2', 'Pejabat KADA Jajahan Pasir Puteh, Selising', 16810, 'Pasir Puteh', 3, NULL, '0198765432', '', 2500, 35, 300, 0, 0, 5, 50, 0, 3, '2024-11-21 12:59:46', '2024-11-25 06:21:28', 12345678),
(14, 'Shalini Devi', '890301-12-0111', 'shalinidevi@gmail.com', 2, 3, 3, 1, '70, Taman Universiti', 81300, 'Skudai', 1, 1011, 1016, 'Pegawai ICT', 'C2', '127, Jalan Dato&rsquo;Lundang', 15710, 'Kota Bharu', 3, NULL, '0118899002', '', 4600, 35, 300, 0, 0, 5, 50, 0, 3, '2024-11-29 15:05:08', '2024-12-30 07:19:01', 12345679),
(15, 'Safrudin binti Chik', '920501-05-2345', 'safrudin@gmail.com', 2, 1, 1, 1, '21, Jalan Tok Gajah, Kampung Gajah', 16300, 'Kota Bharu', 3, NULL, 1017, 'Pegawai Eksekutif', 'A2', 'Pejabat KADA Jajahan Kota Bharu Selatan, Melor', 16400, 'Kota Bharu', 3, NULL, '0123456789', '', 4000, 35, 300, 0, 0, 5, 50, 0, 2, '2024-12-03 15:12:02', '2024-12-30 07:19:12', 12345679),
(16, 'Hasidah binti Ishak', '830309-02-1234', 'hasidah@gmail.com', 2, 1, 1, 2, '45, Jalan Dato&#039; Muda, Kampung Besar', 18000, 'Kota Bharu', 3, 1012, 1018, 'Ketua Pegawai', 'A2', 'Pejabat KADA Jajahan Tumpat, Kg. Paloh', 16040, 'Palekbang, Tumpat', 3, NULL, '0145678901', '098765432', 4000, 35, 300, 0, 0, 5, 50, 0, 3, '2024-12-19 15:17:51', '2024-12-30 07:19:23', 12345679),
(17, 'Kamaluddin bin Mahmud', '790308-07-1234', 'kamaluddin@gmail.com', 1, 1, 1, 2, '88, Jalan Kampung Jaya', 15350, 'Kota Bharu', 3, NULL, 1017, 'Pengurus Program Pembangunan', 'B2', '19, Jalan Pembangunan', 16000, 'Kota Bharu', 3, NULL, '0123456789', '', 3000, 35, 300, 0, 0, 5, 50, 0, 1, '2025-01-21 16:06:34', NULL, NULL),
(18, 'Zainab binti Abdul Rahman', '930420-12-5678', 'zainab@gmail.com', 2, 1, 1, 2, '12, Jalan Permai, Taman Indah', 16000, 'Kota Bharu', 3, NULL, 1018, 'Pegawai ICT', 'B4', 'Pejabat KADA Jajahan Bachok, KM 25 Bukit Bator', 16070, 'Jelawat, Bachok', 3, NULL, '0123456789', '', 3000, 35, 300, 0, 0, 5, 50, 0, 1, '2025-01-21 16:10:12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_officestate`
--

CREATE TABLE `tb_officestate` (
  `st_id` int(11) NOT NULL,
  `st_desc` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_officestate`
--

INSERT INTO `tb_officestate` (`st_id`, `st_desc`) VALUES
(1, 'Johor'),
(2, 'Kedah'),
(3, 'Kelantan'),
(4, 'Melaka'),
(5, 'Negeri Sembilan'),
(6, 'Pahang'),
(7, 'Pulau Pinang'),
(8, 'Sabah'),
(9, 'Sarawak'),
(10, 'Selangor'),
(11, 'Terengganu'),
(12, 'WP Kuala Lumpur'),
(13, 'WP Labuan'),
(14, 'WP Putrajaya'),
(15, 'Perak'),
(16, 'Perlis');

-- --------------------------------------------------------

--
-- Table structure for table `tb_policies`
--

CREATE TABLE `tb_policies` (
  `p_policyID` int(10) NOT NULL,
  `p_memberRegFee` double NOT NULL COMMENT 'Fee Masuk',
  `p_returningMemberRegFee` double NOT NULL,
  `p_minShareCapital` double NOT NULL COMMENT 'Modah Syer Minimum',
  `p_minFeeCapital` double NOT NULL COMMENT 'Modal Yuran Minimum',
  `p_minFixedSaving` double NOT NULL COMMENT 'Wang deposit Anggota Minimum',
  `p_minMemberFund` double NOT NULL COMMENT 'Sumbangan Tabung Kebajikan Minimum',
  `p_minMemberSaving` double NOT NULL COMMENT 'Simpanan Tetap Minimum',
  `p_minOtherFees` double NOT NULL COMMENT 'Lain-lain',
  `p_minShareCapitalForLoan` double NOT NULL COMMENT 'Modah Syer Minimum',
  `p_maxInstallmentPeriod` int(11) NOT NULL COMMENT 'Tempoh Ansuran Maksima',
  `p_maxAlBai` double NOT NULL,
  `p_rateAlBai` double NOT NULL,
  `p_maxAlInnah` double NOT NULL,
  `p_rateAlInnah` double NOT NULL,
  `p_maxBPulihKenderaan` double NOT NULL,
  `p_rateBPulihKenderaan` double NOT NULL,
  `p_maxCukaiJalanInsurans` double NOT NULL,
  `p_rateCukaiJalanInsurans` double NOT NULL,
  `p_maxKhas` double NOT NULL,
  `p_rateKhas` double NOT NULL,
  `p_maxKarnivalMusim` double NOT NULL,
  `p_rateKarnivalMusim` double NOT NULL,
  `p_maxAlQadrulHassan` double NOT NULL,
  `p_rateAlQadrulHassan` double NOT NULL,
  `p_salaryDeductionForSaving` double NOT NULL COMMENT 'Potongan Gaji Bulanan untuk Simpanan Tetap',
  `p_salaryDeductionForMemberFund` double NOT NULL COMMENT 'Potongan Gaji Bulanan untuk Tabung Kebajikan',
  `p_dateUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `p_adminID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_policies`
--

INSERT INTO `tb_policies` (`p_policyID`, `p_memberRegFee`, `p_returningMemberRegFee`, `p_minShareCapital`, `p_minFeeCapital`, `p_minFixedSaving`, `p_minMemberFund`, `p_minMemberSaving`, `p_minOtherFees`, `p_minShareCapitalForLoan`, `p_maxInstallmentPeriod`, `p_maxAlBai`, `p_rateAlBai`, `p_maxAlInnah`, `p_rateAlInnah`, `p_maxBPulihKenderaan`, `p_rateBPulihKenderaan`, `p_maxCukaiJalanInsurans`, `p_rateCukaiJalanInsurans`, `p_maxKhas`, `p_rateKhas`, `p_maxKarnivalMusim`, `p_rateKarnivalMusim`, `p_maxAlQadrulHassan`, `p_rateAlQadrulHassan`, `p_salaryDeductionForSaving`, `p_salaryDeductionForMemberFund`, `p_dateUpdated`, `p_adminID`) VALUES
(1, 35, 0, 300, 0, 0, 5, 50, 0, 300, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 5, '2023-05-10 16:00:00', 12345678),
(2, 50, 100, 300, 35, 0, 5, 0, 0, 300, 6, 20000, 4.2, 20000, 4.2, 4500, 4.2, 4500, 4.2, 10000, 4.2, 10000, 4.2, 20000, 4.2, 50, 5, '2025-01-23 13:07:35', 12345678);

-- --------------------------------------------------------

--
-- Table structure for table `tb_reportretrievallog`
--

CREATE TABLE `tb_reportretrievallog` (
  `r_retrievalID` int(11) NOT NULL,
  `r_retrievalDate` date NOT NULL,
  `r_month` int(11) NOT NULL,
  `r_year` int(11) NOT NULL,
  `r_adminID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_rmonth`
--

CREATE TABLE `tb_rmonth` (
  `rm_id` int(11) NOT NULL,
  `rm_desc` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_rmonth`
--

INSERT INTO `tb_rmonth` (`rm_id`, `rm_desc`) VALUES
(1, 'Januari'),
(2, 'Februari'),
(3, 'Mac'),
(4, 'April'),
(5, 'Mei'),
(6, 'Jun'),
(7, 'Julai'),
(8, 'Ogos'),
(9, 'September'),
(10, 'Oktober'),
(11, 'November'),
(12, 'Disember');

-- --------------------------------------------------------

--
-- Table structure for table `tb_status`
--

CREATE TABLE `tb_status` (
  `s_sid` int(11) NOT NULL,
  `s_desc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_status`
--

INSERT INTO `tb_status` (`s_sid`, `s_desc`) VALUES
(0, 'Draf'),
(1, 'Sedang Diproses'),
(2, 'Ditolak'),
(3, 'Dilulus'),
(4, 'Dijelaskan'),
(5, 'Berhenti'),
(6, 'Pencen'),
(7, 'Diterima'),
(8, 'Selesai');

-- --------------------------------------------------------

--
-- Table structure for table `tb_tarikdiri`
--

CREATE TABLE `tb_tarikdiri` (
  `td_tarikdiriID` int(11) NOT NULL,
  `td_memberNo` int(11) NOT NULL,
  `td_alasan` varchar(255) NOT NULL,
  `td_submitDate` datetime DEFAULT NULL,
  `td_status` int(11) NOT NULL,
  `td_approvalDate` datetime DEFAULT NULL,
  `td_adminID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaction`
--

CREATE TABLE `tb_transaction` (
  `t_transactionID` int(11) NOT NULL,
  `t_transactionType` int(11) NOT NULL,
  `t_method` varchar(255) NOT NULL,
  `t_transactionAmt` double NOT NULL,
  `t_month` int(11) NOT NULL,
  `t_year` int(11) NOT NULL,
  `t_desc` varchar(255) NOT NULL,
  `t_transactionDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `t_memberNo` int(11) NOT NULL,
  `t_adminID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_transaction`
--

INSERT INTO `tb_transaction` (`t_transactionID`, `t_transactionType`, `t_method`, `t_transactionAmt`, `t_month`, `t_year`, `t_desc`, `t_transactionDate`, `t_memberNo`, `t_adminID`) VALUES
(1, 1, 'Potongan Gaji', 50, 9, 2024, 'Potongan Gaji', '2024-10-04 14:33:50', 1000, 12345679),
(2, 4, 'Potongan Gaji', 5, 9, 2024, 'Potongan Gaji', '2024-10-04 14:33:54', 1000, 12345679),
(3, 1, 'Potongan Gaji', 50, 9, 2024, 'Potongan Gaji', '2024-10-04 14:33:58', 1001, 12345679),
(4, 4, 'Potongan Gaji', 5, 9, 2024, 'Potongan Gaji', '2024-10-04 14:34:11', 1001, 12345679),
(5, 1, 'Potongan Gaji', 50, 9, 2024, 'Potongan Gaji', '2024-10-04 14:34:16', 1003, 12345679),
(6, 4, 'Potongan Gaji', 5, 9, 2024, 'Potongan Gaji', '2024-10-04 14:34:21', 1003, 12345679),
(7, 3, 'Transaksi Tambahan', 50, 10, 2024, 'Bayaran Simpanan Tetap', '2024-10-11 14:34:33', 1000, 12345679),
(8, 3, 'Transaksi Tambahan', 50, 10, 2024, 'Bayaran Simpanan Tetap', '2024-10-11 14:34:38', 1001, 12345679),
(9, 1, 'Transaksi Tambahan', 250, 10, 2024, 'Pembelian Saham dan Bayaran Simpanan Anggota', '2024-10-11 12:36:22', 1003, 12345679),
(10, 4, 'Transaksi Tambahan', 50, 10, 2024, 'Pembelian Saham dan Bayaran Simpanan Anggota', '2024-10-11 12:36:28', 1003, 12345679),
(11, 1, 'Potongan Gaji', 50, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1000, 12345678),
(12, 4, 'Potongan Gaji', 5, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1000, 12345678),
(13, 1, 'Potongan Gaji', 50, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1001, 12345678),
(14, 4, 'Potongan Gaji', 5, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1001, 12345678),
(15, 3, 'Potongan Gaji', 50, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1003, 12345678),
(16, 4, 'Potongan Gaji', 5, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1003, 12345678),
(17, 8, 'Potongan Gaji', 91.67, 10, 2024, 'Potongan Gaji Bayaran Balik 4', '2024-11-08 12:38:14', 1003, 12345678),
(18, 1, 'Potongan Gaji', 50, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1004, 12345678),
(19, 4, 'Potongan Gaji', 5, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1004, 12345678),
(20, 1, 'Potongan Gaji', 50, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1005, 12345678),
(21, 4, 'Potongan Gaji', 5, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1005, 12345678),
(22, 1, 'Potongan Gaji', 50, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1006, 12345678),
(23, 4, 'Potongan Gaji', 5, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1006, 12345678),
(24, 1, 'Potongan Gaji', 50, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1007, 12345678),
(25, 4, 'Potongan Gaji', 5, 10, 2024, 'Potongan Gaji', '2024-11-08 12:38:14', 1007, 12345678),
(26, 1, 'Potongan Gaji', 50, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1000, 12345678),
(27, 4, 'Potongan Gaji', 5, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1000, 12345678),
(28, 1, 'Potongan Gaji', 50, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1001, 12345678),
(29, 4, 'Potongan Gaji', 5, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1001, 12345678),
(30, 3, 'Potongan Gaji', 50, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1003, 12345678),
(31, 4, 'Potongan Gaji', 5, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1003, 12345678),
(32, 8, 'Potongan Gaji', 91.67, 11, 2024, 'Potongan Gaji Bayaran Balik 4', '2024-12-06 13:23:37', 1003, 12345678),
(33, 1, 'Potongan Gaji', 50, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1004, 12345678),
(34, 4, 'Potongan Gaji', 5, 11, 2024, 'Potongan Gaji', '2025-01-21 14:43:54', 1004, 12345678),
(35, 1, 'Potongan Gaji', 50, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1005, 12345678),
(36, 4, 'Potongan Gaji', 5, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1005, 12345678),
(37, 1, 'Potongan Gaji', 50, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1006, 12345678),
(38, 4, 'Potongan Gaji', 5, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1006, 12345678),
(39, 1, 'Potongan Gaji', 50, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1007, 12345678),
(40, 4, 'Potongan Gaji', 5, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1007, 12345678),
(41, 1, 'Potongan Gaji', 50, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1008, 12345678),
(42, 4, 'Potongan Gaji', 5, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1008, 12345678),
(43, 1, 'Potongan Gaji', 50, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1009, 12345678),
(44, 4, 'Potongan Gaji', 5, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1009, 12345678),
(45, 1, 'Potongan Gaji', 50, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1010, 12345678),
(46, 4, 'Potongan Gaji', 5, 11, 2024, 'Potongan Gaji', '2024-12-06 13:23:37', 1010, 12345678),
(47, 1, 'Transaksi Tambahan', 500, 12, 2024, 'Pembelian Saham', '2024-12-10 14:27:07', 1008, 12345678),
(48, 1, 'Transaksi Tambahan', 200, 12, 2024, 'Pembelian Saham', '2024-12-10 14:27:13', 1007, 12345678),
(49, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1000, 12345679),
(50, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1000, 12345679),
(51, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1001, 12345679),
(52, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1001, 12345679),
(53, 3, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1003, 12345679),
(54, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1003, 12345679),
(55, 8, 'Potongan Gaji', 91.67, 12, 2024, 'Potongan Gaji Bayaran Balik 4', '2025-01-03 14:52:08', 1003, 12345679),
(56, 8, 'Potongan Gaji', 243.33, 12, 2024, 'Potongan Gaji Bayaran Balik 5', '2025-01-03 14:52:08', 1003, 12345679),
(57, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1004, 12345679),
(58, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1004, 12345679),
(59, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1005, 12345679),
(60, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1005, 12345679),
(61, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1006, 12345679),
(62, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1006, 12345679),
(63, 3, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1007, 12345679),
(64, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1007, 12345679),
(65, 10, 'Potongan Gaji', 201.67, 12, 2024, 'Potongan Gaji Bayaran Balik 6', '2025-01-03 14:52:08', 1007, 12345679),
(66, 3, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1008, 12345679),
(67, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1008, 12345679),
(68, 9, 'Potongan Gaji', 31.28, 12, 2024, 'Potongan Gaji Bayaran Balik 8', '2025-01-03 14:52:08', 1008, 12345679),
(69, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1009, 12345679),
(70, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1009, 12345679),
(71, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1010, 12345679),
(72, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1010, 12345679),
(73, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1011, 12345679),
(74, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1011, 12345679),
(75, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1012, 12345679),
(76, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-03 14:52:08', 1012, 12345679),
(77, 1, 'Transaksi Tambahan', 200, 1, 2025, 'Pembelian Saham', '2025-01-21 14:54:58', 1000, 12345679);

-- --------------------------------------------------------

--
-- Table structure for table `tb_ttype`
--

CREATE TABLE `tb_ttype` (
  `tt_id` int(11) NOT NULL,
  `tt_desc` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_ttype`
--

INSERT INTO `tb_ttype` (`tt_id`, `tt_desc`) VALUES
(1, 'Modah Syer'),
(2, 'Modal Yuran'),
(3, 'Simpanan Tetap'),
(4, 'Tabung Anggota'),
(5, 'Simpanan Anggota'),
(6, 'Al-Bai'),
(7, 'Al-Innah'),
(8, 'Baik Pulih Kenderaan'),
(9, 'Road Tax and Insurance'),
(10, 'Khas'),
(11, 'Karnival Musim Istimewa'),
(12, 'Al-Qadrul Hassan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_ugender`
--

CREATE TABLE `tb_ugender` (
  `ug_gid` int(11) NOT NULL,
  `ug_desc` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_ugender`
--

INSERT INTO `tb_ugender` (`ug_gid`, `ug_desc`) VALUES
(1, 'Lelaki'),
(2, 'Perempuan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_umaritalstatus`
--

CREATE TABLE `tb_umaritalstatus` (
  `um_mid` int(11) NOT NULL,
  `um_desc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_umaritalstatus`
--

INSERT INTO `tb_umaritalstatus` (`um_mid`, `um_desc`) VALUES
(1, 'Bujang'),
(2, 'Kahwin'),
(3, 'Cerai'),
(4, 'Kematian Pasangan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_urace`
--

CREATE TABLE `tb_urace` (
  `ur_rid` int(11) NOT NULL,
  `ur_desc` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_urace`
--

INSERT INTO `tb_urace` (`ur_rid`, `ur_desc`) VALUES
(1, 'Melayu'),
(2, 'Cina'),
(3, 'India'),
(4, 'Lain-lain');

-- --------------------------------------------------------

--
-- Table structure for table `tb_ureligion`
--

CREATE TABLE `tb_ureligion` (
  `ua_rid` int(11) NOT NULL,
  `ua_desc` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_ureligion`
--

INSERT INTO `tb_ureligion` (`ua_rid`, `ua_desc`) VALUES
(1, 'Islam'),
(2, 'Buddha'),
(3, 'Hindu'),
(4, 'Kristian'),
(5, 'Lain-lain');

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `u_id` int(11) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `token_expiry` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `u_pwd` varchar(255) DEFAULT NULL,
  `u_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`u_id`, `reset_token`, `token_expiry`, `u_pwd`, `u_type`) VALUES
(1000, 'fbedd7beb60fab5063a7da5bae7b450b', '2025-01-21 08:48:00', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(1001, '', '2025-01-21 08:35:25', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(1003, '', '2025-01-21 08:35:32', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(1004, '', '2025-01-21 13:48:44', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(1005, '', '2025-01-21 13:48:48', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(1006, '', '2025-01-21 13:48:52', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(1007, '', '2025-01-21 13:48:56', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(1008, '', '2025-01-21 13:48:59', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(1009, '', '2025-01-21 13:49:02', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(1010, '', '2025-01-21 13:49:04', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(1011, '', '2025-01-21 14:19:01', '$2y$10$ZlXf7EN/CN53U4PWRFHIHez1Isyj/4J.8OmBqDDeV0Ajy/rUNogYe', 2),
(1012, '', '2025-01-21 14:19:23', '$2y$10$RHYsPeOpkHJOzlJv4/41..YE.2WERrzHhATrZgbp7FyYLCmikavye', 2),
(12345678, '077047f56df149ae3bb1970c9df623b2', '2025-01-21 06:31:28', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 1),
(12345679, '4b85d76d82c5bcee243b959c7c10fb44', '2025-01-21 07:29:23', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_utype`
--

CREATE TABLE `tb_utype` (
  `ut_tid` int(11) NOT NULL,
  `ut_desc` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_utype`
--

INSERT INTO `tb_utype` (`ut_tid`, `ut_desc`) VALUES
(1, 'Admin'),
(2, 'Member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`a_adminID`);

--
-- Indexes for table `tb_banner`
--
ALTER TABLE `tb_banner`
  ADD PRIMARY KEY (`b_bannerID`),
  ADD KEY `b_adminID` (`b_adminID`);

--
-- Indexes for table `tb_feedback`
--
ALTER TABLE `tb_feedback`
  ADD PRIMARY KEY (`fb_feedbackID`),
  ADD KEY `fb_adminID` (`fb_adminID`),
  ADD KEY `fb_type` (`fb_type`),
  ADD KEY `fb_status` (`fb_status`),
  ADD KEY `fb_memberNo` (`fb_memberNo`);

--
-- Indexes for table `tb_financial`
--
ALTER TABLE `tb_financial`
  ADD UNIQUE KEY `f_memberNo_2` (`f_memberNo`),
  ADD KEY `f_memberNo` (`f_memberNo`);

--
-- Indexes for table `tb_ftype`
--
ALTER TABLE `tb_ftype`
  ADD PRIMARY KEY (`fb_id`);

--
-- Indexes for table `tb_guarantor`
--
ALTER TABLE `tb_guarantor`
  ADD PRIMARY KEY (`g_guarantorID`),
  ADD KEY `g_loanApplicationID` (`g_loanApplicationID`),
  ADD KEY `g_memberNo` (`g_memberNo`);

--
-- Indexes for table `tb_heir`
--
ALTER TABLE `tb_heir`
  ADD PRIMARY KEY (`h_heirID`),
  ADD KEY `h_memberApplicationID` (`h_memberApplicationID`),
  ADD KEY `h_relationWithMember` (`h_relationWithMember`);

--
-- Indexes for table `tb_homestate`
--
ALTER TABLE `tb_homestate`
  ADD PRIMARY KEY (`st_id`);

--
-- Indexes for table `tb_hrelation`
--
ALTER TABLE `tb_hrelation`
  ADD PRIMARY KEY (`hr_rid`);

--
-- Indexes for table `tb_lbank`
--
ALTER TABLE `tb_lbank`
  ADD PRIMARY KEY (`lb_id`);

--
-- Indexes for table `tb_loan`
--
ALTER TABLE `tb_loan`
  ADD PRIMARY KEY (`l_loanApplicationID`),
  ADD KEY `l_loanType` (`l_loanType`),
  ADD KEY `l_adminID` (`l_adminID`),
  ADD KEY `l_status` (`l_status`),
  ADD KEY `l_memberNo` (`l_memberNo`),
  ADD KEY `l_bankName` (`l_bankName`);

--
-- Indexes for table `tb_ltype`
--
ALTER TABLE `tb_ltype`
  ADD PRIMARY KEY (`lt_lid`);

--
-- Indexes for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`m_memberApplicationID`),
  ADD UNIQUE KEY `m_memberApplicationID` (`m_memberApplicationID`),
  ADD UNIQUE KEY `m_memberNo` (`m_memberNo`),
  ADD UNIQUE KEY `m_memberNo_2` (`m_memberNo`),
  ADD KEY `m_gender` (`m_gender`,`m_religion`,`m_race`,`m_maritalStatus`,`m_status`),
  ADD KEY `m_religion` (`m_religion`),
  ADD KEY `m_race` (`m_race`),
  ADD KEY `m_maritalStatus` (`m_maritalStatus`),
  ADD KEY `m_status` (`m_status`),
  ADD KEY `m_adminID` (`m_adminID`),
  ADD KEY `m_homeState` (`m_homeState`,`m_officeState`),
  ADD KEY `tb_member_ibfk_7` (`m_officeState`);

--
-- Indexes for table `tb_officestate`
--
ALTER TABLE `tb_officestate`
  ADD PRIMARY KEY (`st_id`);

--
-- Indexes for table `tb_policies`
--
ALTER TABLE `tb_policies`
  ADD PRIMARY KEY (`p_policyID`),
  ADD KEY `p_adminID` (`p_adminID`);

--
-- Indexes for table `tb_reportretrievallog`
--
ALTER TABLE `tb_reportretrievallog`
  ADD PRIMARY KEY (`r_retrievalID`),
  ADD KEY `r_adminID` (`r_adminID`),
  ADD KEY `tb_reportretrievallog_ibfk_2` (`r_month`);

--
-- Indexes for table `tb_rmonth`
--
ALTER TABLE `tb_rmonth`
  ADD PRIMARY KEY (`rm_id`);

--
-- Indexes for table `tb_status`
--
ALTER TABLE `tb_status`
  ADD PRIMARY KEY (`s_sid`);

--
-- Indexes for table `tb_tarikdiri`
--
ALTER TABLE `tb_tarikdiri`
  ADD PRIMARY KEY (`td_tarikdiriID`),
  ADD KEY `td_status` (`td_status`),
  ADD KEY `td_memberNo` (`td_memberNo`);

--
-- Indexes for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  ADD PRIMARY KEY (`t_transactionID`),
  ADD KEY `t_transactionType` (`t_transactionType`),
  ADD KEY `t_memberNo` (`t_memberNo`,`t_adminID`),
  ADD KEY `t_adminID` (`t_adminID`),
  ADD KEY `t_month` (`t_month`),
  ADD KEY `t_year` (`t_year`);

--
-- Indexes for table `tb_ttype`
--
ALTER TABLE `tb_ttype`
  ADD PRIMARY KEY (`tt_id`);

--
-- Indexes for table `tb_ugender`
--
ALTER TABLE `tb_ugender`
  ADD PRIMARY KEY (`ug_gid`);

--
-- Indexes for table `tb_umaritalstatus`
--
ALTER TABLE `tb_umaritalstatus`
  ADD PRIMARY KEY (`um_mid`);

--
-- Indexes for table `tb_urace`
--
ALTER TABLE `tb_urace`
  ADD PRIMARY KEY (`ur_rid`);

--
-- Indexes for table `tb_ureligion`
--
ALTER TABLE `tb_ureligion`
  ADD PRIMARY KEY (`ua_rid`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`u_id`),
  ADD KEY `u_type` (`u_type`);

--
-- Indexes for table `tb_utype`
--
ALTER TABLE `tb_utype`
  ADD PRIMARY KEY (`ut_tid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_banner`
--
ALTER TABLE `tb_banner`
  MODIFY `b_bannerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_feedback`
--
ALTER TABLE `tb_feedback`
  MODIFY `fb_feedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_guarantor`
--
ALTER TABLE `tb_guarantor`
  MODIFY `g_guarantorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_heir`
--
ALTER TABLE `tb_heir`
  MODIFY `h_heirID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `tb_loan`
--
ALTER TABLE `tb_loan`
  MODIFY `l_loanApplicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `m_memberApplicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tb_policies`
--
ALTER TABLE `tb_policies`
  MODIFY `p_policyID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_reportretrievallog`
--
ALTER TABLE `tb_reportretrievallog`
  MODIFY `r_retrievalID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_tarikdiri`
--
ALTER TABLE `tb_tarikdiri`
  MODIFY `td_tarikdiriID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  MODIFY `t_transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_banner`
--
ALTER TABLE `tb_banner`
  ADD CONSTRAINT `tb_banner_ibfk_1` FOREIGN KEY (`b_adminID`) REFERENCES `tb_admin` (`a_adminID`);

--
-- Constraints for table `tb_feedback`
--
ALTER TABLE `tb_feedback`
  ADD CONSTRAINT `tb_feedback_ibfk_1` FOREIGN KEY (`fb_adminID`) REFERENCES `tb_admin` (`a_adminID`),
  ADD CONSTRAINT `tb_feedback_ibfk_2` FOREIGN KEY (`fb_type`) REFERENCES `tb_ftype` (`fb_id`),
  ADD CONSTRAINT `tb_feedback_ibfk_3` FOREIGN KEY (`fb_memberNo`) REFERENCES `tb_member` (`m_memberNo`),
  ADD CONSTRAINT `tb_feedback_ibfk_4` FOREIGN KEY (`fb_status`) REFERENCES `tb_status` (`s_sid`);

--
-- Constraints for table `tb_financial`
--
ALTER TABLE `tb_financial`
  ADD CONSTRAINT `tb_financial_ibfk_1` FOREIGN KEY (`f_memberNo`) REFERENCES `tb_member` (`m_memberNo`);

--
-- Constraints for table `tb_guarantor`
--
ALTER TABLE `tb_guarantor`
  ADD CONSTRAINT `tb_guarantor_ibfk_1` FOREIGN KEY (`g_loanApplicationID`) REFERENCES `tb_loan` (`l_loanApplicationID`),
  ADD CONSTRAINT `tb_guarantor_ibfk_2` FOREIGN KEY (`g_memberNo`) REFERENCES `tb_member` (`m_memberNo`);

--
-- Constraints for table `tb_heir`
--
ALTER TABLE `tb_heir`
  ADD CONSTRAINT `tb_heir_ibfk_1` FOREIGN KEY (`h_memberApplicationID`) REFERENCES `tb_member` (`m_memberApplicationID`),
  ADD CONSTRAINT `tb_heir_ibfk_2` FOREIGN KEY (`h_relationWithMember`) REFERENCES `tb_hrelation` (`hr_rid`);

--
-- Constraints for table `tb_loan`
--
ALTER TABLE `tb_loan`
  ADD CONSTRAINT `tb_loan_ibfk_1` FOREIGN KEY (`l_loanType`) REFERENCES `tb_ltype` (`lt_lid`),
  ADD CONSTRAINT `tb_loan_ibfk_2` FOREIGN KEY (`l_status`) REFERENCES `tb_status` (`s_sid`),
  ADD CONSTRAINT `tb_loan_ibfk_3` FOREIGN KEY (`l_memberNo`) REFERENCES `tb_member` (`m_memberNo`),
  ADD CONSTRAINT `tb_loan_ibfk_4` FOREIGN KEY (`l_bankName`) REFERENCES `tb_lbank` (`lb_id`);

--
-- Constraints for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD CONSTRAINT `tb_member_ibfk_1` FOREIGN KEY (`m_gender`) REFERENCES `tb_ugender` (`ug_gid`),
  ADD CONSTRAINT `tb_member_ibfk_2` FOREIGN KEY (`m_religion`) REFERENCES `tb_ureligion` (`ua_rid`),
  ADD CONSTRAINT `tb_member_ibfk_3` FOREIGN KEY (`m_race`) REFERENCES `tb_urace` (`ur_rid`),
  ADD CONSTRAINT `tb_member_ibfk_4` FOREIGN KEY (`m_maritalStatus`) REFERENCES `tb_umaritalstatus` (`um_mid`),
  ADD CONSTRAINT `tb_member_ibfk_5` FOREIGN KEY (`m_status`) REFERENCES `tb_status` (`s_sid`),
  ADD CONSTRAINT `tb_member_ibfk_6` FOREIGN KEY (`m_homeState`) REFERENCES `tb_homestate` (`st_id`),
  ADD CONSTRAINT `tb_member_ibfk_7` FOREIGN KEY (`m_officeState`) REFERENCES `tb_officestate` (`st_id`);

--
-- Constraints for table `tb_policies`
--
ALTER TABLE `tb_policies`
  ADD CONSTRAINT `tb_policies_ibfk_1` FOREIGN KEY (`p_adminID`) REFERENCES `tb_admin` (`a_adminID`);

--
-- Constraints for table `tb_reportretrievallog`
--
ALTER TABLE `tb_reportretrievallog`
  ADD CONSTRAINT `tb_reportretrievallog_ibfk_1` FOREIGN KEY (`r_adminID`) REFERENCES `tb_admin` (`a_adminID`),
  ADD CONSTRAINT `tb_reportretrievallog_ibfk_2` FOREIGN KEY (`r_month`) REFERENCES `tb_rmonth` (`rm_id`);

--
-- Constraints for table `tb_tarikdiri`
--
ALTER TABLE `tb_tarikdiri`
  ADD CONSTRAINT `tb_tarikdiri_ibfk_1` FOREIGN KEY (`td_memberNo`) REFERENCES `tb_member` (`m_memberNo`),
  ADD CONSTRAINT `tb_tarikdiri_ibfk_2` FOREIGN KEY (`td_status`) REFERENCES `tb_status` (`s_sid`);

--
-- Constraints for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  ADD CONSTRAINT `tb_transaction_ibfk_1` FOREIGN KEY (`t_memberNo`) REFERENCES `tb_member` (`m_memberNo`),
  ADD CONSTRAINT `tb_transaction_ibfk_2` FOREIGN KEY (`t_adminID`) REFERENCES `tb_admin` (`a_adminID`),
  ADD CONSTRAINT `tb_transaction_ibfk_3` FOREIGN KEY (`t_month`) REFERENCES `tb_rmonth` (`rm_id`),
  ADD CONSTRAINT `tb_transaction_ibfk_4` FOREIGN KEY (`t_transactionType`) REFERENCES `tb_ttype` (`tt_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
