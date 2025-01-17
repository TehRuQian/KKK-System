-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2025 at 01:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+08:00";


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
  `b_banner` varchar(50) NOT NULL,
  `b_dateUpdated` date NOT NULL,
  `b_adminID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_banner`
--

INSERT INTO `tb_banner` (`b_bannerID`, `b_banner`, `b_dateUpdated`, `b_adminID`) VALUES
(1, 'Welcome Banner', '2024-12-04', 200),
(2, 'Event Promotion', '2024-12-04', 201);

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
  `f_alBai` double NOT NULL,
  `f_alInnah` double NOT NULL,
  `f_bPulihKenderaan` double NOT NULL COMMENT 'Baik Pulih Kenderaan',
  `f_roadTaxInsurance` double NOT NULL COMMENT 'Road Tax & Insuran',
  `f_specialScheme` double NOT NULL COMMENT 'Khas',
  `f_specialSeasonCarnival` double NOT NULL COMMENT 'Karnival Musim Istimewa',
  `f_alQadrulHassan` double NOT NULL,
  `f_dateUpdated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_financial`
--

INSERT INTO `tb_financial` (`f_memberNo`, `f_shareCapital`, `f_feeCapital`, `f_fixedSaving`, `f_memberFund`, `f_memberSaving`, `f_alBai`, `f_alInnah`, `f_bPulihKenderaan`, `f_roadTaxInsurance`, `f_specialScheme`, `f_specialSeasonCarnival`, `f_alQadrulHassan`, `f_dateUpdated`) VALUES
(1, 10, 100, 200, 50, 200, 13008, 0, 0, 0, 0, 0, 0, '2024-12-04'),
(2, 30, 300, 500, 50, 550, 0, 12520, 0, 0, 0, 0, 0, '2024-12-01'),
(3, 5, 50, 350, 50, 400, 0, 0, 0, 0, 0, 0, 0, '2024-11-12'),
(4, 20, 200, 600, 50, 650, 0, 0, 0, 0, 0, 0, 0, '2024-11-29'),
(5, 10, 100, 400, 50, 450, 0, 0, 0, 0, 0, 0, 0, '2024-11-26'),
(6, 15, 150, 350, 50, 400, 11260, 0, 0, 0, 0, 0, 0, '2024-11-30');

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
(11, 9, 2, './uploads/154ee6596cd5a3fc6b21faf2c24f0fda.jpeg'),
(12, 9, 5, './uploads/3007c6fe3fce090005a7ebd68dcc8706.jpeg'),
(13, 15, 3, './uploads/8e2ff105c9050900a2ff7fc72d159bae.jpeg'),
(14, 15, 4, './uploads/e782801b4f3fa24f55c2906500fb57ff.jpeg'),
(15, 17, 2, './uploads/678a0c4aa0897.jpg'),
(16, 17, 1, './uploads/678a0c4aa0e19.jpg'),
(17, 18, 2, './uploads/678a243f03ab9.jpeg'),
(18, 18, 3, './uploads/678a243f0419a.jpg'),
(19, 19, 6, './uploads/678a24a16e72b.png'),
(20, 19, 2, './uploads/678a24a16eee9.jpeg'),
(21, 21, 2, './uploads/91e43be0aa908bb4db5e116e07714282.jpeg'),
(22, 21, 5, './uploads/91e43be0aa908bb4db5e116e07714282.jpeg'),
(23, 22, 2, './uploads/389e344fd978cc02a4f1f004614ea389.jpeg'),
(24, 22, 3, './uploads/389e344fd978cc02a4f1f004614ea389.jpeg'),
(25, 23, 2, './uploads/75b28e607c66474be14e887f2ed9a3f1.png'),
(26, 23, 3, './uploads/b0268bad9ddb80f55d024450e07fc58e.jpg');

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
(11, 1004, 'Hana Hafiz', 2, '920201-09-0888'),
(12, 1004, 'Yusuf Hafiz', 4, '880701-05-0444'),
(13, 1004, 'Ali Hafiz', 2, '910201-03-0222'),
(14, 1004, 'Aminah Hafiz', 1, '900101-02-0111'),
(15, 1005, 'Sita Kumar', 1, '940201-11-0000'),
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
(30, 1010, 'Farah Mohd', 4, '880701-05-0444');

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
  `l_status` int(11) NOT NULL,
  `l_applicationDate` timestamp NULL DEFAULT NULL,
  `l_approvalDate` timestamp NULL DEFAULT current_timestamp(),
  `l_adminID` int(11) DEFAULT NULL COMMENT 'Admin who approved the application'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_loan`
--

INSERT INTO `tb_loan` (`l_loanApplicationID`, `l_memberNo`, `l_loanType`, `l_appliedLoan`, `l_loanPeriod`, `l_monthlyInstalment`, `l_loanPayable`, `l_bankAccountNo`, `l_bankName`, `l_monthlyGrossSalary`, `l_monthlyNetSalary`, `l_signature`, `l_file`, `l_status`, `l_applicationDate`, `l_approvalDate`, `l_adminID`) VALUES
(1, 1, 1, 2000, 2, 90.33, 2168, 214748364, 3, 10000, 8000, 'tanmeiling', '', 3, '2024-12-01', '2024-12-27', 200),
(2, 2, 2, 10000, 6, 173.89, 12520, 1234567892, 2, 12000, 9000, 'mohdhafiz', '', 3, '2024-12-02', '2024-12-27', 201),
(3, 3, 3, 15000, 2, 677.5, 16260, 1234567893, 3, 8000, 6000, 'ravikumar', '', 1, '2024-12-03', NULL, NULL),
(4, 4, 4, 10000, 4, 243.33, 11680, 1234567894, 4, 15000, 10000, 'anisakarim', '', 2, '2024-12-04', '2024-12-27', 200),
(5, 1, 5, 2000, 6, 34.78, 2504, 214748364, 3, 9000, 7000, 'tanmeiling', '', 2, '2024-12-05', '2024-12-27', 201),
(6, 6, 1, 10000, 3, 312.18, 11260, 12345678, 2, 2500, 2000, 'abc', '', 3, '2024-01-01', '2024-01-02', 200),
(8, 1, 1, 10000, 2, 451.67, 10840, 12345678, 2, 2500, 2000, 'abc', '', 3, '2023-01-01', '2023-01-02', 200),
(9, 1, 6, 213, 2, 9.76, 0, 1234, 20, 456, 456, './uploads/8d969f4e9e3586a1226cc389a6a29b1b.jpeg', '', 1, '2025-01-14', NULL, NULL),
(10, 1, 6, 213, 2, 9.76, 0, 1234, 20, 456, 456555, './uploads/5f75d98b16da6313773ec91aa7791c2e.jpeg', '', 1, '2025-01-14', NULL, NULL),
(14, 1, 6, 213, 2, 9.76, 0, 1234, 20, 456, 456555, './uploads/38781eba16fb36924b91f8acf55624c3.jpeg', '', 1, '2025-01-16', NULL, NULL),
(15, 3, 6, 213, 2, 9.76, 0, 1234, 20, 456, 456555, './uploads/c21c8bb494addaa196a6b04b73c3d433.jpeg', '', 1, '2025-01-16', NULL, NULL),
(16, 3, 4, 2888, 4, 72.2, 0, 12345678, 1, 12345, 12345, './uploads/8c23a911d8bfc370889dced3ab007ee1.jpg', '', 1, '2025-01-17', NULL, NULL),
(17, 3, 4, 2888, 4, 72.2, 0, 12345678, 1, 12345, 12345, './uploads/c85707a41f5afa7fef4440266664f202.png', '', 1, '2025-01-17', NULL, NULL),
(18, 3, 4, 28845, 3, 921.44, 0, 12345678, 16, 12345, 12345, './uploads/7afcfb4fe06432536eee8bb2d8d3dceb.jpeg', '', 1, '2025-01-17', NULL, NULL),
(19, 3, 3, 5555, 5, 115.73, 6943.75, 12345678, 10, 12345, 12345, './uploads/43d780267eff044655f3bc893fb84d15.jpeg', '', 1, '2025-01-17', NULL, NULL),
(20, 3, 3, 5555, 5, 115.73, 6943.75, 12345678, 10, 12345, 12345, './uploads/ef4549d90c827e51e3b6514ad5ab0d84.jpg', '', 1, '2025-01-17', NULL, NULL),
(21, 1, 5, 555, 5, 11.56, 693.75, 12345678, 3, 12345, 12345, './uploads/aa8d8dc9b307dc01de2b947959845c89.jpeg', '', 1, '2025-01-17', NULL, NULL),
(22, 1, 2, 555, 5, 11.56, 693.75, 12345678, 3, 12345, 12345, './uploads/f7846d9740bfb5e82a9690e0fb5847c7.jpeg', '', 1, '2025-01-17', NULL, NULL),
(23, 1, 3, 555, 5, 11.56, 693.75, 12345678, 3, 12345, 12345, './uploads/26ad487315b802676dd3668b6e534155.png', 'employer_confirmation_23_1737116043.pdf', 1, '2025-01-17', NULL, NULL);

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
  `m_phoneNumber` varchar(13) NOT NULL,
  `m_homeNumber` varchar(13) DEFAULT NULL,
  `m_monthlySalary` int(11) NOT NULL,
  `m_feeMasuk` double NOT NULL,
  `m_modalSyer` double NOT NULL,
  `m_modalYuran` double NOT NULL,
  `m_deposit` double NOT NULL,
  `m_alAbrar` double NOT NULL,
  `m_simpananTetap` double NOT NULL COMMENT 'Sumbangan Tabung Tabung Kebajikan ',
  `m_feeLain` double NOT NULL,
  `m_status` int(11) NOT NULL,
  `m_applicationDate` datetime DEFAULT NULL,
  `m_approvalDate` timestamp NULL DEFAULT current_timestamp(),
  `m_adminID` int(11) DEFAULT NULL COMMENT 'Admin who approve the application'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Dumping data for table `tb_member`
--

INSERT INTO `tb_member` (`m_memberApplicationID`, `m_name`, `m_ic`, `m_email`, `m_gender`, `m_religion`, `m_race`, `m_maritalStatus`, `m_homeAddress`, `m_homePostcode`, `m_homeCity`, `m_homeState`, `m_memberNo`, `m_pfNo`, `m_position`, `m_positionGrade`, `m_officeAddress`, `m_officePostcode`, `m_officeCity`, `m_officeState`, `m_phoneNumber`, `m_homeNumber`, `m_monthlySalary`, `m_feeMasuk`, `m_modalSyer`, `m_modalYuran`, `m_deposit`, `m_alAbrar`, `m_simpananTetap`, `m_feeLain`, `m_status`, `m_applicationDate`, `m_approvalDate`, `m_adminID`) VALUES
(1001, 'Ali Ahmad', '900101-02-0111', 'aliahmad@gmail.com', 1, 1, 1, 1, 'No.1, Kampung Peringat', 15000, 'Kota Bharu', 3, NULL, 1001, 'Pengurus', 'G41', 'Jalan Dato Lundang', 15000, 'Kota Bharu', 3, '0123456789', NULL, 3500, 0, 0, 0, 0, 0, 0, 0, 2, '2025-11-01 00:00:00', '2025-11-11 16:00:00', 200),
(1002, 'Nur Aisyah', '910201-03-0222', 'nuraisyah@gmail.com', 2, 1, 1, 2, 'No.12, Kampung Tunjong', 15010, 'Kota Bharu', 3, NULL, 1002, 'Penolong Pegurus', 'G29', 'Jalan Dato Lundang', 15010, 'Kota Bharu', 3, '0129876543', NULL, 3200, 0, 0, 0, 0, 0, 0, 0, 2, '2025-11-02 00:00:00', '2025-11-14 16:00:00', 201),
(1003, 'Tan Mei Ling', '050111-04-1234', 'tanmeiling@gmail.com', 2, 1, 1, 1, 'No.15, Taman Mount Austi', 8110, 'Johor Bahru', 2, 1, 1, 'Keran', 'N1', 'Jalan Dato Lundan', 150, 'Kota Bhar', 4, '01122334456', '07522334', 250, 0, 0, 0, 0, 0, 0, 0, 3, '2025-11-03 00:00:00', '2025-11-14 16:00:00', 200),
(1004, 'Mohd Hafiz', '880701-05-0444', 'mohdhafiz@gmail.com', 1, 1, 1, 2, 'No.20, Kampung Kubang Kerian', 15020, 'Kota Bharu', 3, 2, 1004, 'Ketua Pegawai', 'G52', 'Jalan Dato Lundang ', 15020, 'Kota Bharu', 3, '0142233445', '073334455', 6000, 0, 0, 0, 0, 0, 0, 0, 3, '2025-11-04 00:00:00', '2025-11-14 16:00:00', 201),
(1005, 'Ravi Kumar', '870401-06-0555', 'ravikumar@gmail.com', 1, 3, 3, 2, 'No.25, Taman Desa Aman', 54000, 'Kuala Lumpur', 12, 3, 1005, 'Pegawai Teknikal', 'J41', 'Jalan Dato Lundang', 15000, 'Kota Bharu', 12, '0163344556', '0182221234', 4500, 0, 0, 0, 0, 0, 0, 0, 3, '2025-11-05 00:00:00', '2025-11-14 16:00:00', 200),
(1006, 'Anisa Karim', '900501-07-0666', 'anisakarim@gmail.com', 2, 1, 1, 1, 'No.30, Kampung Kota, Kota Bharu, Kelantan', 15030, 'Kota Bharu', 3, 4, 1006, 'Setiausaha', 'N29', 'Jalan Dato Lundang', 15030, 'Kota Bharu', 3, '0174455667', '-', 3200, 0, 0, 0, 0, 0, 0, 0, 3, '2025-11-06 00:00:00', '2025-11-14 16:00:00', 201),
(1007, 'Lim Wei Sheng', '850301-08-0777', 'limweisheng@gmail.com', 1, 4, 2, 1, 'No.35, Taman Pelangi, Johor Bahru', 81200, 'Johor Bahru', 1, 5, 1007, 'Penganalisis', 'G43', 'Jalan Dato Lundang', 15000, 'Kota Bharu', 3, '0185566778', '075223355', 5000, 0, 0, 0, 0, 0, 0, 0, 3, '2025-11-07 00:00:00', '2025-11-14 16:00:00', 200),
(1008, 'Aminah Saad', '920201-09-0888', 'aminahsaad@gmail.com', 2, 1, 1, 1, 'No.40, Kampung Sireh, Kota Bharu, Kelantan', 0, 'Kota Bharu', 3, 6, 1008, 'Pegawai Kew', 'W41', 'Jalan Dato Lundang', 15040, 'Kota Bharu', 3, '0196677889', '073456789', 4700, 0, 0, 0, 0, 0, 0, 0, 3, '2024-11-08 00:00:00', '2024-11-14 16:00:00', 201),
(1009, 'Farid Hakim', '930101-10-0999', 'faridhakim@gmail.com', 1, 1, 1, 1, 'No.50, Taman Bukit Mewah, Kajang, Selangor', 43000, 'Kajang', 10, NULL, 1009, 'Pegawai Komputer', 'S41', 'Jalan Dato Lundang ', 15000, 'Kota Bharu', 3, '0197788990', NULL, 3500, 0, 0, 0, 0, 0, 0, 0, 2, '2024-11-09 00:00:00', '2025-01-16 07:18:46', 0),
(1010, 'Zarina Mohd', '940201-11-0000', 'zarinamohd@gmail.com', 2, 1, 1, 2, 'No.60, Kampung Pasir Tumboh, Kota Bharu, Kelantan', 15060, 'Kota Bharu', 3, 8, 1010, 'Penyelaras ', 'G41', 'Jalan Dato Lundang ', 15060, 'Kota Bharu', 3, '0198899001', '073456123', 3800, 0, 0, 0, 0, 0, 0, 0, 3, '2024-11-10 00:00:00', '2025-01-16 09:30:48', 0),
(1011, 'Shalini Devi', '890301-12-0111', 'shalinidevi@gmail.com', 2, 3, 3, 1, 'No.70, Taman Universiti, Skudai, Johor', 81300, 'Skudai', 1, 9, 1011, 'Pegawai ICT', 'F41', 'Jalan Dato Lundang ', 15000, 'Kota Bharu', 3, '0118899002', NULL, 4600, 0, 0, 0, 0, 0, 0, 0, 3, '2024-11-11 00:00:00', '2025-01-16 09:44:50', 0),
(1012, 'Roslan Mansor', '860201-13-0222', 'roslanmansor@gmail.com', 1, 1, 1, 1, 'No.80, Kampung Panji, Kota Bharu, Kelantan', 15080, 'Kota Bharu', 3, 12, 1012, 'Pegawai Audit', 'W43', 'Jalan Dato Lundang ', 15080, 'Kota Bharu', 3, '0137788002', '073345678', 5500, 0, 0, 0, 0, 0, 0, 0, 3, '2024-11-12 00:00:00', '2025-01-17 08:07:34', 0),
(1013, 'Ali yang bernama Ali', '040622-01-0101', 'hello@graduate.utm.my', 1, 2, 2, 1, '10, Jalan 1', 78456, 'Hello', 11, 7, 1013, 'Manager', 'N17', '1, Jalan Ali', 78945, 'hr', 11, '0145669966', '077760237', 2500, 40, 300, 56, 6656, 5965, 595, 595, 3, '2025-01-16 15:26:23', '2025-01-16 09:30:00', 0),
(1014, 'Teh Ru Qian', '040404-04-0404', 'tehruqian@graduate.utm.my', 1, 4, 3, 3, '10, Jalan Ali', 78945, 'JB', 1, 10, 1014, 'Pegawai', 'M14', '11', 78456, 'Home', 2, '0123456789', NULL, 10000, 0, 0, 0, 0, 0, 0, 0, 3, '2025-01-17 22:57:47', '2025-01-17 07:57:47', 0),
(1015, 'Goe Jie Ying', '040404-04-0404', 'jygoe63@gmail.com', 1, 5, 4, 3, '10, Jalan Ali', 78945, 'JB', 1, NULL, 1015, 'Pegawai', 'M14', '11', 78456, 'Home', 4, '0123456789', NULL, 10000, 35, 35, 0, 0, 0, 0, 0, 1, '2025-01-16 17:32:34', NULL, 0),
(1016, 'Chua Jia Lin', '010101-01-1010', 'chuajialin@graduate.utm.my', 2, 1, 2, 2, '10, Jalan Ali', 78945, 'JB', 3, NULL, 1016, 'Pegawai', 'M14', '11', 78456, 'Home', 14, '0123456789', NULL, 10000, 35, 0, 0, 0, 0, 0, 0, 1, '2025-01-17 21:47:37', NULL, NULL),
(1017, 'Tan Yi Ya', '040404-01-0404', 'tanyiya@graduate.utm.my', 2, 3, 3, 1, '10, Jalan Ali', 78945, 'JB', 1, NULL, 1017, 'Pegawai', 'M14', '11', 78456, 'Home', 14, '0123456789', NULL, 10000, 35, 0, 0, 0, 0, 0, 0, 1, '2025-01-17 21:48:47', NULL, 0),
(1018, 'Lam Yoke Yu', '010101-01-1010', 'lam.yu@graduate.utm.my', 2, 1, 2, 2, '10, Jalan Ali', 78945, 'JB', 1, 11, 1018, 'Pegawai', 'M14', '11', 78456, 'Home', 3, '0123456789', NULL, 10000, 35, 0, 0, 0, 0, 0, 0, 3, '2025-01-17 23:02:40', '2025-01-17 08:02:40', 0),
(1019, 'Lim Chong Chong', '045689-01-5698', 'bello@graduate.utm.my', 1, 2, 2, 3, '10, Jalan Ali', 78456, 'JB', 14, 13, 1019, 'Manager', 'N17', '10, Jalan Ali', 78945, 'hr', 11, '0145669966', '075896478', 15000, 35, 300, 35, 50, 35, 50, 35, 3, '2025-01-17 16:37:05', '2025-01-17 09:02:10', 0);

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
(1, 35, 300, 0, 0, 5, 50, 0, 300, 5, 6, 40000, 50, 5, '2023-05-11 16:00:00', 200),
(2, 35, 300, 0, 50, 5, 50, 0, 300, 5, 6, 40000, 50, 5, '2024-12-03 16:00:00', 201);

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
(3, 'Dilulus');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaction`
--

CREATE TABLE `tb_transaction` (
  `t_transactionID` int(11) NOT NULL,
  `t_transactionType` int(11) NOT NULL,
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

INSERT INTO `tb_transaction` (`t_transactionID`, `t_transactionType`, `t_transactionAmt`, `t_month`, `t_year`, `t_desc`, `t_transactionDate`, `t_memberNo`, `t_adminID`) VALUES
(1, 2, 100, 1, 2024, '', '2024-01-14 03:39:35', 1, 200),
(2, 3, 200, 1, 2024, '', '2024-01-14 03:39:48', 2, 201),
(3, 4, 350, 1, 2024, '', '2024-01-14 03:38:19', 3, 200),
(4, 5, 50, 1, 2024, '', '2024-01-14 03:47:40', 4, 201),
(5, 6, 200, 11, 2024, '', '2024-11-06 16:00:00', 5, 200),
(6, 7, 100, 11, 2024, '', '2024-11-27 16:00:00', 6, 201),
(7, 8, 250, 11, 2024, '', '2024-11-24 16:00:00', 1, 200),
(8, 9, 100, 12, 2024, '', '2024-12-02 16:00:00', 2, 201),
(9, 10, 150, 12, 2024, '', '2024-11-30 16:00:00', 3, 200),
(10, 1, 50, 12, 2024, '', '2024-12-01 16:00:00', 4, 201),
(11, 1, 50, 1, 2025, '', '2025-01-03 16:00:00', 3, 201),
(12, 2, 50, 1, 2025, '', '2025-01-04 16:00:00', 3, 201),
(13, 3, 50, 1, 2025, '', '2025-01-05 16:00:00', 3, 201),
(14, 4, 50, 1, 2025, '', '2025-01-05 16:00:00', 3, 201),
(15, 5, 50, 1, 2025, '', '2025-01-06 16:00:00', 3, 201),
(16, 6, 50, 1, 2025, '', '2025-01-06 16:00:00', 3, 201),
(17, 7, 50, 1, 2025, '', '2025-01-06 16:00:00', 3, 201),
(18, 8, 50, 1, 2025, '', '2025-01-07 16:00:00', 3, 201),
(19, 9, 50, 1, 2025, '', '2025-01-07 16:00:00', 3, 201),
(20, 10, 50, 1, 2025, '', '2025-01-08 16:00:00', 3, 201),
(21, 11, 50, 1, 2025, '', '2025-01-08 16:00:00', 3, 201),
(22, 12, 50, 1, 2025, '', '2025-01-08 16:00:00', 3, 201),
(23, 1, 50, 1, 2025, '', '2025-01-09 16:00:00', 3, 201),
(24, 2, 50, 1, 2025, '', '2025-01-09 16:00:00', 3, 201),
(25, 3, 50, 1, 2025, '', '2025-01-09 16:00:00', 3, 201),
(26, 4, 50, 1, 2025, '', '2025-01-05 16:00:00', 3, 201),
(27, 5, 50, 1, 2025, '', '2025-01-04 16:00:00', 3, 201);

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
  `u_pwd` varchar(16) NOT NULL,
  `u_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`u_id`, `u_pwd`, `u_type`) VALUES
(1, '123456', 2),
(2, '123456', 2),
(3, '123456', 2),
(4, '123456', 2),
(5, '123456', 2),
(200, '123456', 1),
(201, '123456', 1);

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
  MODIFY `b_bannerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_guarantor`
--
ALTER TABLE `tb_guarantor`
  MODIFY `g_guarantorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tb_heir`
--
ALTER TABLE `tb_heir`
  MODIFY `h_heirID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tb_loan`
--
ALTER TABLE `tb_loan`
  MODIFY `l_loanApplicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `m_memberApplicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1013;

--
-- AUTO_INCREMENT for table `tb_policies`
--
ALTER TABLE `tb_policies`
  MODIFY `p_policyID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_reportretrievallog`
--
ALTER TABLE `tb_reportretrievallog`
  MODIFY `r_retrievalID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  MODIFY `t_transactionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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

--
-- Constraints for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `tb_user_ibfk_1` FOREIGN KEY (`u_type`) REFERENCES `tb_utype` (`ut_tid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
