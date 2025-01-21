-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2025 at 10:31 AM
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
(200),
(201);

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
(38, 'Default', 'advertisement.jpg', 1, '2025-01-21 02:14:50', 200),
(39, 'Sky', 'WhatsApp Image 2025-01-15 at 11.52.01 PM.jpeg', 0, '2025-01-21 02:16:29', 200),
(40, 'Keluar', 'IMG_0730.JPG', 0, '2025-01-21 02:16:33', 200),
(41, 'Awas', 'IMG_0573.JPG', 0, '2025-01-21 02:16:36', 200);

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
(1, 400, 35, 65, 200, 300, '2025-01-20 07:34:15'),
(2, 400, 20, 185, 105, 600, '2025-01-21 01:26:43'),
(3, 355, 50, 200, 95, 500, '2025-01-21 01:26:43'),
(4, 325, 200, 200, 90, 650, '2025-01-21 06:44:41'),
(5, 255, 100, 100, 90, 450, '2025-01-21 06:44:41'),
(6, 165, 150, 20, 90, 400, '2025-01-21 06:44:41');

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
(1, 1, 3, 'SignatureG001'),
(2, 1, 2, 'SignatureG002'),
(3, 2, 3, 'SignatureG003'),
(4, 2, 4, 'SignatureG004'),
(5, 3, 1, 'SignatureG005'),
(6, 3, 2, 'SignatureG006'),
(7, 4, 3, 'SignatureG007'),
(8, 4, 2, 'SignatureG008'),
(9, 5, 2, 'SignatureG009'),
(10, 5, 3, 'SignatureG010'),
(11, 11, 1, 'gambar_penjamin1_11.jpg'),
(12, 11, 2, 'gambar_penjamin2_11.jpg'),
(13, 12, 1, 'gambar_penjamin1_12.jpg'),
(14, 12, 2, 'gambar_penjamin2_12.jpg'),
(15, 13, 1, 'gambar_penjamin1_13.jpg'),
(16, 13, 4, 'gambar_penjamin2_13.jpg'),
(17, 15, 2, 'gambar_penjamin1_15.jpg'),
(18, 15, 3, 'gambar_penjamin2_15.jpg');

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
(1, 1001, '1', 1, '1'),
(2, 1001, '2', 2, '2'),
(3, 1001, '3', 3, '3'),
(4, 1001, 'Ibrahim Ahmad', 4, '770701-05-0444'),
(5, 1002, '5', 5, '5'),
(6, 1002, 'Amira Ismail', 2, '930101-10-0999'),
(7, 1002, 'Zara Ismail', 6, '850301-08-0777'),
(8, 1003, 'Benjamin Tan', 1, '950101-11-0000'),
(9, 1003, 'Chloe Tan', 3, '890301-12-0111'),
(10, 1003, 'Ella Tan', 5, '860201-13-0222'),
(12, 1004, 'Yusuf Hafiz', 4, '880701-05-0444'),
(14, 1004, 'Aminah Hafiz @Mina', 1, '900101-02-0111'),
(16, 1005, 'Rohan Kumar', 2, '920301-04-0333'),
(17, 1005, 'Lakshmi Kumar', 5, '850301-08-0777'),
(18, 1006, 'Imran Karim', 2, '930101-10-0999'),
(19, 1006, 'Halim Karim', 3, '860201-13-0222'),
(20, 1007, 'Grace Lim', 1, '910201-03-0222'),
(21, 1007, 'David Lim', 2, '920201-09-0888'),
(22, 1007, 'Sophia Lim', 5, '850301-08-0777'),
(23, 1008, 'Ali Saad', 2, '930101-10-0999'),
(24, 1008, 'Hana Saad', 6, '940201-11-0000'),
(25, 1009, 'Aisyah Hakim', 1, '870401-06-0555'),
(26, 1009, 'Farah Hakim', 2, '900501-07-0666'),
(27, 1009, 'Hassan Hakim', 5, '850301-08-0777'),
(28, 1010, 'Halim Mohd', 2, '940201-11-0000'),
(29, 1010, 'Izzah Mohd', 3, '890301-12-0111'),
(30, 1010, 'Farah Mohd', 4, '880701-05-0444'),
(35, 1005, 'Ravi Kumar', 2, '800201-08-0444'),
(49, 1005, 'aaa', 6, '123456789'),
(57, 1005, 'cc', 2, '999888777'),
(96, 1005, 's', 2, '126597738'),
(97, 1013, 'dfghj', 2, '123456-12-1234'),
(98, 1013, 'jbjvhguyu', 3, '123456-12-1234'),
(99, 1013, 'drtyjfy', 4, '123456-12-1234'),
(101, 1004, 'Lam Yoke Yu', 2, '123456-12-1234');

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
(7, 'Penang'),
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
(6, 'Lain-lain'),
(7, 'Tiada Hubungan');

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
(21, 'UOB Malaysia'),
(22, 'Tiada');

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
(1, 1, 1, 2000, 2, 90.33, 1154.7, 214748364, 3, 10000, 8000, 'tanmeiling', '', 3, '2025-12-01 00:00:00', '2025-12-26 08:00:00', 200),
(2, 2, 2, 10000, 6, 173.89, 0, 1234567892, 2, 12000, 9000, 'mohdhafiz', '', 4, '2025-12-02 00:00:00', '2025-12-26 08:00:00', 201),
(3, 3, 3, 15000, 2, 677.5, 16260, 1234567893, 3, 8000, 6000, 'ravikumar', '', 1, '2025-12-03 00:00:00', NULL, NULL),
(4, 4, 4, 10000, 4, 243.33, 11680, 1234567894, 4, 15000, 10000, 'anisakarim', '', 2, '2025-12-04 00:00:00', '2025-12-26 08:00:00', 200),
(5, 1, 5, 2000, 6, 34.78, 2504, 214748364, 3, 9000, 7000, 'tanmeiling', '', 2, '2025-12-05 00:00:00', '2025-12-26 08:00:00', 201),
(6, 6, 1, 10000, 3, 312.18, 8762.56, 12345678, 2, 2500, 2000, 'abc', '', 3, '2024-01-01 00:00:00', '2024-01-01 08:00:00', 200),
(8, 1, 1, 10000, 2, 451.67, 6113.3, 12345678, 2, 2500, 2000, 'abc', '', 3, '2023-01-01 00:00:00', '2023-01-01 08:00:00', 200),
(9, 3, 1, 50, 2, 5, 0, 1234567893, 3, 2500, 2000, 'abc', '', 4, '2025-01-01 00:00:00', '2025-01-02 08:00:00', 201),
(10, 3, 1, 50, 2, 5, 10, 1234567893, 3, 2500, 2000, 'abc', '', 3, '2025-01-02 00:00:00', '2025-01-03 08:00:00', 201),
(11, 2, 3, 1000, 2, 45.17, 0, 1234567892, 2, 1200, 1000, 'gambar_pemohon_11.jpg', 'pengesahan_majikan_11_1737357893.pdf', 4, '2025-01-20 15:23:49', '2025-01-20 00:26:47', 200),
(12, 2, 5, 1000, 2, 45.17, 1084, 1234567892, 2, 1200, 1000, 'gambar_pemohon_12.jpg', 'pengesahan_majikan_12_1737360909.pdf', 1, '2025-01-20 16:14:25', NULL, NULL),
(13, 2, 4, 2000, 2, 90.33, 2168, 1234567892, 2, 1200, 1000, 'gambar_pemohon_13.jpg', 'pengesahan_majikan_13_1737361660.pdf', 1, '2025-01-20 16:25:37', NULL, NULL),
(14, 2, 4, 2000, 2, 90.33, 2168, 1234567, 7, 1200, 1000, 'gambar_pemohon_14.jpg', '', 1, '2025-01-21 14:51:40', NULL, NULL),
(15, 2, 4, 2000, 2, 90.33, 2168, 1234567, 7, 1200, 1000, 'gambar_pemohon_15.jpg', '', 1, '2025-01-21 15:14:58', NULL, NULL);

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
  `m_monthlySalary` double NOT NULL,
  `m_feeMasuk` double NOT NULL,
  `m_modalSyer` double NOT NULL,
  `m_modalYuran` double NOT NULL,
  `m_deposit` double NOT NULL,
  `m_alAbrar` double NOT NULL,
  `m_simpananTetap` double NOT NULL COMMENT 'Sumbangan Tabung Tabung Kebajikan ',
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
(1001, 'Ali Ahmad', '900101-02-0111', 'aliahmad@gmail.com', 1, 1, 1, 1, 'No.1, Kampung Peringat', 15000, 'Kota Bharu', 3, NULL, 1001, 'Pengurus', 'G41', 'Jalan Dato Lundang', 15000, 'Kota Bharu', 3, NULL, '0123456789', NULL, 3500, 0, 0, 0, 0, 0, 0, 0, 2, '2025-11-01 00:00:00', '2025-11-11 00:00:00', 200),
(1002, 'Nur Aisyah', '910201-03-0222', 'nuraisyah@gmail.com', 2, 1, 1, 2, 'No.12, Kampung Tunjong', 15010, 'Kota Bharu', 3, NULL, 1002, 'Penolong Pegurus', 'G29', 'Jalan Dato Lundang', 15010, 'Kota Bharu', 3, NULL, '0129876543', NULL, 3200, 0, 0, 0, 0, 0, 0, 0, 2, '2025-11-02 00:00:00', '2025-11-14 00:00:00', 201),
(1003, 'Tan Mei Ling', '050111-04-1234', 'tanmeiling@gmail.com', 2, 1, 1, 1, 'No.15, Taman Mount Austi', 8110, 'Johor Bahr', 2, 1, 1, 'Keran', 'N1', 'Jalan Dato Lundan', 150, 'Kota Bhar', 4, NULL, '01122334456', '07522334', 250, 0, 0, 0, 0, 0, 0, 0, 3, '2025-11-03 00:00:00', '2025-11-14 00:00:00', 200),
(1004, 'Mohd Hafiz', '880701-05-0444', 'mohdhafiz@gmail.com', 1, 1, 1, 2, 'No.20, Kampung Kubang Kerian', 15020, 'Kota Bharu', 3, 2, 1004, 'Ketua Pegawai', 'G52', 'Jalan Dato Lundang ', 15020, 'Kota Bharu', 3, NULL, '0142233445', '073334455', 6000, 0, 0, 0, 0, 0, 0, 0, 3, '2025-11-04 00:00:00', '2025-11-14 00:00:00', 201),
(1005, 'Ravi Kumar', '870401-06-0555', 'ravikumar@gmail.com', 1, 3, 3, 2, 'No.25, Taman Desa Aman', 54000, 'Kuala Lumpur', 12, 3, 1005, 'Pegawai Teknikal', 'J41', 'Jalan Dato Lundang', 15000, 'Kota Bharu', 12, NULL, '0163344556', '0182221234', 4500, 0, 0, 0, 0, 0, 0, 0, 3, '2025-11-05 00:00:00', '2025-11-14 00:00:00', 200),
(1006, 'Anisa Karim', '900501-07-0666', 'anisakarim@gmail.com', 2, 1, 1, 1, 'No.30, Kampung Kota, Kota Bharu, Kelantan', 15030, 'Kota Bharu', 3, 4, 1006, 'Setiausaha', 'N29', 'Jalan Dato Lundang', 15030, 'Kota Bharu', 3, NULL, '0174455667', '-', 3200, 0, 0, 0, 0, 0, 0, 0, 3, '2025-11-06 00:00:00', '2025-11-14 00:00:00', 201),
(1007, 'Lim Wei Sheng', '850301-08-0777', 'limweisheng@gmail.com', 1, 4, 2, 1, 'No.35, Taman Pelangi, Johor Bahru', 81200, 'Johor Bahru', 1, 5, 1007, 'Penganalisis', 'G43', 'Jalan Dato Lundang', 15000, 'Kota Bharu', 3, NULL, '0185566778', '075223355', 5000, 0, 0, 0, 0, 0, 0, 0, 3, '2025-11-07 00:00:00', '2025-11-14 00:00:00', 200),
(1008, 'Aminah Saad', '920201-09-0888', 'aminahsaad@gmail.com', 2, 1, 1, 1, 'No.40, Kampung Sireh, Kota Bharu, Kelantan', 0, 'Kota Bharu', 3, 6, 1008, 'Pegawai Kew', 'W41', 'Jalan Dato Lundang', 15040, 'Kota Bharu', 3, NULL, '0196677889', '073456789', 4700, 0, 0, 0, 0, 0, 0, 0, 3, '2024-11-08 00:00:00', '2024-11-14 00:00:00', 201),
(1009, 'Farid Hakim', '930101-10-0999', 'faridhakim@gmail.com', 1, 1, 1, 1, 'No.50, Taman Bukit Mewah, Kajang, Selangor', 43000, 'Kajang', 10, NULL, 1009, 'Pegawai Komputer', 'S41', 'Jalan Dato Lundang ', 15000, 'Kota Bharu', 3, NULL, '0197788990', NULL, 3500, 0, 0, 0, 0, 0, 0, 0, 1, '2024-11-09 00:00:00', NULL, NULL),
(1010, 'Zarina Mohd', '940201-11-0000', 'zarinamohd@gmail.com', 2, 1, 1, 2, 'No.60, Kampung Pasir Tumboh, Kota Bharu, Kelantan', 15060, 'Kota Bharu', 3, NULL, 1010, 'Penyelaras ', 'G41', 'Jalan Dato Lundang ', 15060, 'Kota Bharu', 3, NULL, '0198899001', '073456123', 3800, 0, 0, 0, 0, 0, 0, 0, 1, '2024-11-10 00:00:00', NULL, NULL),
(1011, 'Shalini Devi', '890301-12-0111', 'shalinidevi@gmail.com', 2, 3, 3, 1, 'No.70, Taman Universiti, Skudai, Johor', 81300, 'Skudai', 1, NULL, 1011, 'Pegawai ICT', 'F41', 'Jalan Dato Lundang ', 15000, 'Kota Bharu', 3, NULL, '0118899002', NULL, 4600, 0, 0, 0, 0, 0, 0, 0, 2, '2024-11-11 00:00:00', '2025-01-20 00:28:08', 200),
(1012, 'Roslan Mansor', '860201-13-0222', 'roslanmansor@gmail.com', 1, 1, 1, 1, 'No.80, Kampung Panji, Kota Bharu, Kelantan', 15080, 'Kota Bharu', 3, 7, 1012, 'Pegawai Audit', 'W43', 'Jalan Dato Lundang ', 15080, 'Kota Bharu', 3, NULL, '0137788002', '073345678', 5500, 0, 0, 0, 0, 0, 0, 0, 3, '2024-11-12 00:00:00', '2025-01-20 00:27:55', 200),
(1013, 'DFREW', '123456-10-1234', 'bhjnbh@gmail.com', 2, 2, 2, 2, '67, kjgyujnb', 45678, 'ghjk', 10, NULL, 567, 'vtyh', 'ghb', 'vgyjhvcfyu', 45677, 'fghjk', 7, NULL, '0134567890', '034567867', 156, 35, 300, 35, 50, 5, 5, 0, 1, '2025-01-20 07:46:43', NULL, NULL);

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
(7, 'Penang'),
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
  `p_minShareCapital` double NOT NULL COMMENT 'Modah Syer Minimum',
  `p_minFeeCapital` double NOT NULL COMMENT 'Modal Yuran Minimum',
  `p_minFixedSaving` double NOT NULL COMMENT 'Wang deposit Anggota Minimum',
  `p_minMemberFund` double NOT NULL COMMENT 'Sumbangan Tabung Kebajikan Minimum',
  `p_minMemberSaving` double NOT NULL COMMENT 'Simpanan Tetap Minimum',
  `p_minOtherFees` double NOT NULL COMMENT 'Lain-lain',
  `p_minShareCapitalForLoan` double NOT NULL COMMENT 'Modah Syer Minimum',
  `p_profitRate` double NOT NULL COMMENT 'Kadar Keuntungan',
  `p_maxInstallmentPeriod` int(11) NOT NULL COMMENT 'Tempoh Ansuran Maksima',
  `p_maxFinancingAmt` double NOT NULL COMMENT 'Jumlah Pembiayaan Maksima',
  `p_salaryDeductionForSaving` double NOT NULL COMMENT 'Potongan Gaji Bulanan untuk Simpanan Tetap',
  `p_salaryDeductionForMemberFund` double NOT NULL COMMENT 'Potongan Gaji Bulanan untuk Tabung Kebajikan',
  `p_dateUpdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `p_adminID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_policies`
--

INSERT INTO `tb_policies` (`p_policyID`, `p_memberRegFee`, `p_minShareCapital`, `p_minFeeCapital`, `p_minFixedSaving`, `p_minMemberFund`, `p_minMemberSaving`, `p_minOtherFees`, `p_minShareCapitalForLoan`, `p_profitRate`, `p_maxInstallmentPeriod`, `p_maxFinancingAmt`, `p_salaryDeductionForSaving`, `p_salaryDeductionForMemberFund`, `p_dateUpdated`, `p_adminID`) VALUES
(1, 35, 300, 0, 0, 5, 50, 0, 300, 5, 6, 40000, 50, 5, '2023-05-11 00:00:00', 200),
(2, 35, 300, 0, 50, 5, 50, 0, 300, 5, 6, 40000, 50, 5, '2024-12-03 00:00:00', 201),
(3, 40, 300, 0, 50, 5, 0, 0, 300, 5, 6, 40000, 50, 5, '2025-01-18 19:30:49', 200),
(4, 40, 300, 0, 50, 5, 0, 0, 300, 5, 6, 20000, 50, 5, '2025-01-18 22:49:28', 201),
(5, 35, 300, 0, 50, 5, 0, 0, 300, 5, 6, 20000, 50, 5, '2025-01-18 23:46:42', 201),
(6, 40, 300, 0, 50, 5, 0, 0, 300, 5, 6, 20000, 50, 5, '2025-01-18 23:46:55', 201),
(7, 40, 300, 35, 50, 5, 5, 0, 300, 5, 6, 20000, 50, 5, '2025-01-18 23:50:40', 201),
(8, 40, 300, 35, 50, 5, 5, 0, 300, 4.2, 6, 20000, 50, 5, '2025-01-18 23:52:25', 201),
(9, 40, 300, 35, 50, 5, 0, 0, 300, 4.2, 6, 20000, 55, 5, '2025-01-19 00:11:07', 201),
(10, 40, 300, 35, 50, 5, 0, 0, 300, 4.2, 6, 20000, 50, 5, '2025-01-19 00:11:36', 201),
(11, 35, 300, 35, 50, 5, 5, 0, 300, 4.2, 6, 20000, 50, 5, '2025-01-19 00:11:46', 201),
(12, 35, 300, 35, 50, 5, 5, 0, 300, 4.2, 6, 25000, 50, 5, '2025-01-19 00:12:00', 201),
(13, 35, 300, 35, 50, 5, 5, 0, 300, 4.2, 6, 25000, 50, 10, '2025-01-19 00:12:09', 201),
(14, 35, 300, 35, 50, 5, 5, 0, 300, 4.2, 6, 25000, 50, 5, '2025-01-19 00:12:16', 201),
(15, 35, 400, 35, 50, 5, 5, 0, 300, 4.2, 6, 25000, 50, 5, '2025-01-20 07:32:21', 200),
(16, 35, 400, 35, 50, 5, 5, 0, 300, 4.2, 8, 400000, 50, 5, '2025-01-20 07:33:08', 200),
(17, 35, 400, 35, 50, 5, 5, 0, 300, 4.2, 8, 400000, 55, 5, '2025-01-20 07:33:39', 200),
(20, 35, 400, 35, 50, 5, 5, 0, 300, 4.2, 8, 20000, 55, 5, '2025-01-20 08:58:23', 201),
(21, 35, 400, 40, 50, 5, 5, 0, 300, 4.2, 8, 20000, 55, 5, '2025-01-21 06:19:47', 200),
(22, 40, 400, 40, 50, 5, 5, 0, 300, 4.2, 8, 20000, 55, 5, '2025-01-21 06:19:59', 200);

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

--
-- Dumping data for table `tb_reportretrievallog`
--

INSERT INTO `tb_reportretrievallog` (`r_retrievalID`, `r_retrievalDate`, `r_month`, `r_year`, `r_adminID`) VALUES
(1, '2024-11-04', 11, 2024, 200),
(2, '2024-12-05', 12, 2024, 200);

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
(1, 'Sedang Diproses'),
(2, 'Ditolak'),
(3, 'Dilulus'),
(4, 'Dijelaskan');

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
(153, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 1, 200),
(154, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 1, 200),
(155, 6, 'Potongan Gaji', 90.33, 12, 2024, 'Potongan Gaji Bayaran Balik 1', '2025-01-19 14:36:58', 1, 200),
(156, 6, 'Potongan Gaji', 451.67, 12, 2024, 'Potongan Gaji Bayaran Balik 8', '2025-01-19 14:36:58', 1, 200),
(157, 3, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 2, 200),
(158, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 2, 200),
(159, 7, 'Potongan Gaji', 173.89, 12, 2024, 'Potongan Gaji Bayaran Balik 2', '2025-01-19 14:36:58', 2, 200),
(160, 3, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 3, 200),
(161, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 3, 200),
(162, 6, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji Bayaran Balik 10', '2025-01-19 14:36:58', 3, 200),
(163, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 4, 200),
(164, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 4, 200),
(165, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 5, 200),
(166, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 5, 200),
(167, 1, 'Potongan Gaji', 50, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 6, 200),
(168, 4, 'Potongan Gaji', 5, 12, 2024, 'Potongan Gaji', '2025-01-19 14:36:58', 6, 200),
(169, 6, 'Potongan Gaji', 312.18, 12, 2024, 'Potongan Gaji Bayaran Balik 6', '2025-01-19 14:36:58', 6, 200),
(170, 3, 'Potongan Gaji', 50, 1, 2025, 'Potongan Gaji', '2025-01-19 14:37:10', 2, 200),
(171, 4, 'Potongan Gaji', 5, 1, 2025, 'Potongan Gaji', '2025-01-19 14:37:10', 2, 200),
(172, 7, 'Potongan Gaji', 173.89, 1, 2025, 'Potongan Gaji Bayaran Balik 2', '2025-01-19 14:37:10', 2, 200),
(173, 3, 'Potongan Gaji', 50, 1, 2025, 'Potongan Gaji', '2025-01-19 14:37:10', 3, 200),
(174, 4, 'Potongan Gaji', 5, 1, 2025, 'Potongan Gaji', '2025-01-19 14:37:10', 3, 200),
(175, 6, 'Potongan Gaji', 5, 1, 2025, 'Potongan Gaji Bayaran Balik 10', '2025-01-19 14:37:10', 3, 200),
(176, 1, 'Transaksi Tambahan', 100, 1, 2025, 'Cubaan', '2025-01-19 14:37:27', 1, 200),
(177, 6, 'Transaksi Tambahan', 100, 1, 2025, 'Cubaan: Bayaran Balik 1', '2025-01-19 14:37:28', 1, 200),
(178, 6, 'Transaksi Tambahan', 200, 1, 2025, 'Cubaan: Bayaran Balik 1: Bayaran Balik 8', '2025-01-19 14:37:28', 1, 200),
(179, 3, 'Potongan Gaji', 50, 1, 2025, 'Potongan Gaji', '2025-01-20 07:30:03', 1, 200),
(180, 4, 'Potongan Gaji', 5, 1, 2025, 'Potongan Gaji', '2025-01-20 07:30:03', 1, 200),
(181, 6, 'Potongan Gaji', 90.33, 1, 2025, 'Potongan Gaji Bayaran Balik 1', '2025-01-20 07:30:03', 1, 200),
(182, 6, 'Potongan Gaji', 451.67, 1, 2025, 'Potongan Gaji Bayaran Balik 8', '2025-01-20 07:30:03', 1, 200),
(183, 1, 'Potongan Gaji', 50, 1, 2025, 'Potongan Gaji', '2025-01-20 07:30:03', 4, 200),
(184, 4, 'Potongan Gaji', 5, 1, 2025, 'Potongan Gaji', '2025-01-20 07:30:03', 4, 200),
(185, 1, 'Potongan Gaji', 50, 1, 2025, 'Potongan Gaji', '2025-01-20 07:30:03', 5, 200),
(186, 4, 'Potongan Gaji', 5, 1, 2025, 'Potongan Gaji', '2025-01-20 07:30:03', 5, 200),
(187, 1, 'Potongan Gaji', 50, 1, 2025, 'Potongan Gaji', '2025-01-20 07:30:03', 6, 200),
(188, 4, 'Potongan Gaji', 5, 1, 2025, 'Potongan Gaji', '2025-01-20 07:30:03', 6, 200),
(189, 6, 'Potongan Gaji', 312.18, 1, 2025, 'Potongan Gaji Bayaran Balik 6', '2025-01-20 07:30:03', 6, 200),
(190, 1, 'Transaksi Tambahan', 100, 1, 2025, 'Testing', '2025-01-20 07:31:10', 2, 200),
(191, 4, 'Transaksi Tambahan', -50, 1, 2025, 'Testing', '2025-01-20 07:31:10', 2, 200),
(192, 8, 'Transaksi Tambahan', 1084, 1, 2025, 'Testing: Bayaran Balik 11', '2025-01-20 07:31:10', 2, 200),
(193, 1, 'Potongan Gaji', 50, 2, 2025, 'Potongan Gaji', '2025-01-20 07:34:15', 1, 200),
(194, 3, 'Potongan Gaji', 5, 2, 2025, 'Potongan Gaji', '2025-01-20 07:34:15', 1, 200),
(195, 4, 'Potongan Gaji', 5, 2, 2025, 'Potongan Gaji', '2025-01-20 07:34:15', 1, 200),
(196, 6, 'Potongan Gaji', 90.33, 2, 2025, 'Potongan Gaji Bayaran Balik 1', '2025-01-20 07:34:15', 1, 200),
(197, 6, 'Potongan Gaji', 451.67, 2, 2025, 'Potongan Gaji Bayaran Balik 8', '2025-01-20 07:34:15', 1, 200),
(198, 3, 'Potongan Gaji', 55, 2, 2025, 'Potongan Gaji', '2025-01-21 01:26:43', 2, 200),
(199, 4, 'Potongan Gaji', 5, 2, 2025, 'Potongan Gaji', '2025-01-21 01:26:43', 2, 200),
(200, 7, 'Potongan Gaji', 173.89, 2, 2025, 'Potongan Gaji Bayaran Balik 2', '2025-01-21 01:26:43', 2, 200),
(201, 1, 'Potongan Gaji', 55, 2, 2025, 'Potongan Gaji', '2025-01-21 01:26:43', 3, 200),
(202, 4, 'Potongan Gaji', 5, 2, 2025, 'Potongan Gaji', '2025-01-21 01:26:43', 3, 200),
(203, 6, 'Potongan Gaji', 5, 2, 2025, 'Potongan Gaji Bayaran Balik 10', '2025-01-21 01:26:43', 3, 200),
(204, 1, 'Potongan Gaji', 55, 2, 2025, 'Potongan Gaji', '2025-01-21 06:44:41', 4, 200),
(205, 4, 'Potongan Gaji', 5, 2, 2025, 'Potongan Gaji', '2025-01-21 06:44:41', 4, 200),
(206, 1, 'Potongan Gaji', 55, 2, 2025, 'Potongan Gaji', '2025-01-21 06:44:41', 5, 200),
(207, 4, 'Potongan Gaji', 5, 2, 2025, 'Potongan Gaji', '2025-01-21 06:44:41', 5, 200),
(208, 1, 'Potongan Gaji', 55, 2, 2025, 'Potongan Gaji', '2025-01-21 06:44:41', 6, 200),
(209, 4, 'Potongan Gaji', 5, 2, 2025, 'Potongan Gaji', '2025-01-21 06:44:41', 6, 200),
(210, 6, 'Potongan Gaji', 312.18, 2, 2025, 'Potongan Gaji Bayaran Balik 6', '2025-01-21 06:44:41', 6, 200),
(211, 7, 'Transaksi Tambahan', 10607.21, 1, 2025, 'Bayaran balik: Bayaran Balik 2', '2025-01-21 06:45:21', 2, 200);

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
(1, '', '2025-01-16 21:41:24', '$2y$10$Ibwhc.2bcA/dqwoCKxbuBePzHMt28iGYqrT8nlFstyrPDLfajRur6', 2),
(2, '1440f2ccd0064f390b491ca7caec30b5', '2025-01-20 08:00:12', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(3, '', '2025-01-16 19:54:10', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(4, '', '2025-01-16 19:54:10', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(5, '', '2025-01-16 19:54:10', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 2),
(200, '', '2025-01-16 19:54:10', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 1),
(201, '', '2025-01-16 19:54:10', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 1);

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
-- Indexes for table `tb_financial`
--
ALTER TABLE `tb_financial`
  ADD UNIQUE KEY `f_memberNo_2` (`f_memberNo`),
  ADD KEY `f_memberNo` (`f_memberNo`);

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
  MODIFY `b_bannerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tb_guarantor`
--
ALTER TABLE `tb_guarantor`
  MODIFY `g_guarantorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tb_heir`
--
ALTER TABLE `tb_heir`
  MODIFY `h_heirID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `tb_loan`
--
ALTER TABLE `tb_loan`
  MODIFY `l_loanApplicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `m_memberApplicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1014;

--
-- AUTO_INCREMENT for table `tb_policies`
--
ALTER TABLE `tb_policies`
  MODIFY `p_policyID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tb_reportretrievallog`
--
ALTER TABLE `tb_reportretrievallog`
  MODIFY `r_retrievalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  MODIFY `t_transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_banner`
--
ALTER TABLE `tb_banner`
  ADD CONSTRAINT `tb_banner_ibfk_1` FOREIGN KEY (`b_adminID`) REFERENCES `tb_admin` (`a_adminID`);

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
