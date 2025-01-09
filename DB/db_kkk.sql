-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 05:11 PM
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
  `a_adminID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`a_adminID`) VALUES
('A001'),
('A002');

-- --------------------------------------------------------

--
-- Table structure for table `tb_banner`
--

CREATE TABLE `tb_banner` (
  `b_bannerID` varchar(10) NOT NULL,
  `b_banner` varchar(50) NOT NULL,
  `b_dateUpdated` date NOT NULL,
  `b_adminID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_banner`
--

INSERT INTO `tb_banner` (`b_bannerID`, `b_banner`, `b_dateUpdated`, `b_adminID`) VALUES
('B001', 'Welcome Banner', '2024-12-04', 'A001'),
('B002', 'Event Promotion', '2024-12-04', 'A002');

-- --------------------------------------------------------

--
-- Table structure for table `tb_financial`
--

CREATE TABLE `tb_financial` (
  `f_memberNo` int(11) NOT NULL,
  `f_shareCapital` double NOT NULL COMMENT 'Modah Syer',
  `f_feeCapital` double NOT NULL COMMENT 'Modal Yuran',
  `f_fixedSaving` double NOT NULL COMMENT 'Simpanan Tetap',
  `f_memberFund` double NOT NULL COMMENT 'Tabung Anggota',
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
(1, 10, 100, 200, 50, 200, 100, 100, 100, 50, 0, 0, 0, '2024-12-04'),
(2, 30, 300, 500, 50, 550, 200, 0, 150, 50, 0, 0, 0, '2024-12-01'),
(3, 5, 50, 350, 50, 400, 100, 0, 200, 50, 200, 0, 0, '2024-11-12'),
(4, 20, 200, 600, 50, 650, 0, 100, 300, 50, 0, 50, 100, '2024-11-29'),
(5, 10, 100, 400, 50, 450, 100, 200, 200, 50, 0, 0, 150, '2024-11-26'),
(6, 15, 150, 350, 50, 400, 150, 50, 100, 50, 50, 0, 100, '2024-11-30'),
(7, 5, 50, 500, 50, 550, 100, 100, 0, 0, 100, 0, 0, '2024-12-02'),
(8, 10, 100, 200, 50, 250, 50, 100, 100, 50, 0, 0, 100, '2024-12-03'),
(9, 10, 100, 300, 50, 350, 150, 100, 150, 50, 0, 0, 0, '2024-11-06'),
(10, 10, 100, 500, 50, 550, 50, 50, 50, 50, 100, 100, 100, '2024-11-03');

-- --------------------------------------------------------

--
-- Table structure for table `tb_guarantor`
--

CREATE TABLE `tb_guarantor` (
  `g_guarantorID` varchar(10) NOT NULL,
  `g_loanApplicationID` varchar(10) NOT NULL,
  `g_memberNo` int(11) NOT NULL,
  `g_signature` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_guarantor`
--

INSERT INTO `tb_guarantor` (`g_guarantorID`, `g_loanApplicationID`, `g_memberNo`, `g_signature`) VALUES
('G001', 'L001', 3, 'SignatureG001'),
('G002', 'L001', 3, 'SignatureG002'),
('G003', 'L002', 4, 'SignatureG003'),
('G004', 'L002', 4, 'SignatureG004'),
('G005', 'L003', 5, 'SignatureG005'),
('G006', 'L003', 5, 'SignatureG006'),
('G007', 'L004', 6, 'SignatureG007'),
('G008', 'L004', 6, 'SignatureG008'),
('G009', 'L005', 3, 'SignatureG009'),
('G010', 'L005', 3, 'SignatureG010');

-- --------------------------------------------------------

--
-- Table structure for table `tb_heir`
--

CREATE TABLE `tb_heir` (
  `h_heirID` varchar(10) NOT NULL,
  `h_memberNo` int(11) NOT NULL,
  `h_name` varchar(50) NOT NULL,
  `h_relationWithMember` int(10) NOT NULL,
  `h_ic` varchar(14) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_heir`
--

INSERT INTO `tb_heir` (`h_heirID`, `h_memberNo`, `h_name`, `h_relationWithMember`, `h_ic`) VALUES
('H001', 1, 'Aminah Ahmad', 1, '880501-07-0666'),
('H002', 1, 'Hakim Ahmad', 2, '910201-03-0222'),
('H003', 1, 'Farah Ahmad', 2, '920201-09-0888'),
('H004', 1, 'Ibrahim Ahmad', 4, '770701-05-0444'),
('H005', 2, 'Irfan Ismail', 1, '870401-06-0555'),
('H006', 2, 'Amira Ismail', 2, '930101-10-0999'),
('H007', 2, 'Zara Ismail', 6, '850301-08-0777'),
('H008', 3, 'Benjamin Tan', 2, '950101-11-0000'),
('H009', 3, 'Chloe Tan', 3, '890301-12-0111'),
('H010', 3, 'Ella Tan', 5, '860201-13-0222'),
('H011', 4, 'Hana Hafiz', 2, '920201-09-0888'),
('H012', 4, 'Yusuf Hafiz', 4, '880701-05-0444'),
('H013', 4, 'Ali Hafiz', 2, '910201-03-0222'),
('H014', 4, 'Aminah Hafiz', 1, '900101-02-0111'),
('H015', 5, 'Sita Kumar', 1, '940201-11-0000'),
('H016', 5, 'Rohan Kumar', 2, '920301-04-0333'),
('H017', 5, 'Lakshmi Kumar', 5, '850301-08-0777'),
('H018', 6, 'Imran Karim', 2, '930101-10-0999'),
('H019', 6, 'Halim Karim', 3, '860201-13-0222'),
('H020', 7, 'Grace Lim', 1, '910201-03-0222'),
('H021', 7, 'David Lim', 2, '920201-09-0888'),
('H022', 7, 'Sophia Lim', 5, '850301-08-0777'),
('H023', 8, 'Ali Saad', 2, '930101-10-0999'),
('H024', 8, 'Hana Saad', 6, '940201-11-0000'),
('H025', 9, 'Aisyah Hakim', 1, '870401-06-0555'),
('H026', 9, 'Farah Hakim', 2, '900501-07-0666'),
('H027', 9, 'Hassan Hakim', 5, '850301-08-0777'),
('H028', 10, 'Halim Mohd', 2, '940201-11-0000'),
('H029', 10, 'Izzah Mohd', 3, '890301-12-0111'),
('H030', 10, 'Farah Mohd', 4, '880701-05-0444');

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
  `l_loanApplicationID` varchar(10) NOT NULL,
  `l_memberNo` int(11) NOT NULL,
  `l_loanType` int(11) NOT NULL,
  `l_appliedLoan` double NOT NULL,
  `l_loanPeriod` int(11) NOT NULL,
  `l_monthlyInstalment` double NOT NULL,
  `l_bankAccountNo` int(11) NOT NULL,
  `l_bankName` int(11) NOT NULL,
  `l_monthlyGrossSalary` double NOT NULL,
  `l_monthlyNetSalary` double NOT NULL,
  `l_signature` varchar(50) NOT NULL,
  `l_status` int(11) NOT NULL,
  `l_applicationDate` date NOT NULL,
  `l_approvalDate` date DEFAULT NULL,
  `l_adminID` varchar(10) DEFAULT NULL COMMENT 'Admin who approved the application'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_loan`
--

INSERT INTO `tb_loan` (`l_loanApplicationID`, `l_memberNo`, `l_loanType`, `l_appliedLoan`, `l_loanPeriod`, `l_monthlyInstalment`, `l_bankAccountNo`, `l_bankName`, `l_monthlyGrossSalary`, `l_monthlyNetSalary`, `l_signature`, `l_status`, `l_applicationDate`, `l_approvalDate`, `l_adminID`) VALUES
('L001', 3, 1, 50000, 24, 2083.33, 1234567891, 1, 10000, 8000, 'tanmeiling', 3, '2024-12-01', '2024-12-27', 'A001'),
('L002', 4, 2, 75000, 36, 2083.33, 1234567892, 2, 12000, 9000, 'mohdhafiz', 3, '2024-12-02', '2024-12-27', 'A002'),
('L003', 5, 3, 30000, 12, 2500, 1234567893, 3, 8000, 6000, 'ravikumar', 1, '2024-12-03', NULL, NULL),
('L004', 6, 4, 100000, 48, 2083.33, 1234567894, 4, 15000, 10000, 'anisakarim', 2, '2024-12-04', '2024-12-27', 'A001'),
('L005', 3, 5, 2000, 6, 3333.33, 1234567891, 1, 9000, 7000, 'tanmeiling', 2, '2024-12-05', '2024-12-27', 'A002');

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
  `m_memberApplicationID` varchar(11) NOT NULL,
  `m_name` varchar(50) NOT NULL,
  `m_ic` varchar(14) NOT NULL,
  `m_gender` int(11) NOT NULL,
  `m_religion` int(11) NOT NULL,
  `m_race` int(11) NOT NULL,
  `m_maritalStatus` int(11) NOT NULL,
  `m_homeAddress` varchar(50) NOT NULL,
  `m_memberNo` int(11) DEFAULT NULL,
  `m_pfNo` int(11) NOT NULL,
  `m_position` varchar(11) NOT NULL,
  `m_positionGrade` varchar(11) NOT NULL,
  `m_officeAddress` varchar(50) NOT NULL,
  `m_phoneNumber` varchar(13) NOT NULL,
  `m_homeNumber` varchar(13) DEFAULT NULL,
  `m_monthlySalary` int(11) NOT NULL,
  `m_status` int(11) NOT NULL,
  `m_applicationDate` date NOT NULL,
  `m_approvalDate` date DEFAULT NULL,
  `m_adminID` varchar(10) DEFAULT NULL COMMENT 'Admin who approve the application'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_member`
--

INSERT INTO `tb_member` (`m_memberApplicationID`, `m_name`, `m_ic`, `m_gender`, `m_religion`, `m_race`, `m_maritalStatus`, `m_homeAddress`, `m_memberNo`, `m_pfNo`, `m_position`, `m_positionGrade`, `m_officeAddress`, `m_phoneNumber`, `m_homeNumber`, `m_monthlySalary`, `m_status`, `m_applicationDate`, `m_approvalDate`, `m_adminID`) VALUES
('1001', 'Ali Ahmad', '900101-02-0111', 1, 1, 1, 1, 'No.1, Kampung Peringat, Kota Bharu, Kelantan', 1, 1001, 'Pengurus', 'G41', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0123456789', NULL, 3500, 2, '2024-11-01', NULL, 'A001'),
('1002', 'Nur Aisyah', '910201-03-0222', 2, 1, 1, 2, 'No.12, Kampung Tunjong, Kota Bharu, Kelantan', 2, 1002, 'Penolong Pe', 'G29', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0129876543', NULL, 3200, 2, '2024-11-02', NULL, 'A002'),
('1003', 'Tan Mei Ling', '920301-04-0333', 2, 2, 2, 1, 'No.15, Taman Mount Austin, Johor Bahru', 3, 1003, 'Kerani', 'N19', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0112233445', '075223344', 2500, 3, '2024-11-03', '2024-11-15', 'A001'),
('1004', 'Mohd Hafiz', '880701-05-0444', 1, 1, 1, 2, 'No.20, Kampung Kubang Kerian, Kota Bharu, Kelantan', 4, 1004, 'Ketua Pegaw', 'G52', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0142233445', '073334455', 6000, 3, '2024-11-04', '2024-11-15', 'A002'),
('1005', 'Ravi Kumar', '870401-06-0555', 1, 3, 3, 2, 'No.25, Taman Desa Aman, Kuala Lumpur', 5, 1005, 'Pegawai Tek', 'J41', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0163344556', NULL, 4500, 3, '2024-11-05', '2024-11-15', 'A001'),
('1006', 'Anisa Karim', '900501-07-0666', 2, 1, 1, 1, 'No.30, Kampung Kota, Kota Bharu, Kelantan', 6, 1006, 'Setiausaha', 'N29', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0174455667', NULL, 3200, 3, '2024-11-06', '2024-11-15', 'A002'),
('1007', 'Lim Wei Sheng', '850301-08-0777', 1, 4, 2, 1, 'No.35, Taman Pelangi, Johor Bahru', 7, 1007, 'Penganalisi', 'G43', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0185566778', '075223355', 5000, 3, '2024-11-07', '2024-11-15', 'A001'),
('1008', 'Aminah Saad', '920201-09-0888', 2, 1, 1, 1, 'No.40, Kampung Sireh, Kota Bharu, Kelantan', 8, 1008, 'Pegawai Kew', 'W41', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0196677889', '073456789', 4700, 3, '2024-11-08', '2024-11-15', 'A002'),
('1009', 'Farid Hakim', '930101-10-0999', 1, 1, 1, 1, 'No.50, Taman Bukit Mewah, Kajang, Selangor', 9, 1009, 'Pegawai Kom', 'S41', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0197788990', NULL, 3500, 1, '2024-11-09', NULL, 'A001'),
('1010', 'Zarina Mohd', '940201-11-0000', 2, 1, 1, 2, 'No.60, Kampung Pasir Tumboh, Kota Bharu, Kelantan', 10, 1010, 'Penyelaras ', 'G41', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0198899001', '073456123', 3800, 1, '2024-11-10', NULL, 'A002'),
('1011', 'Shalini Devi', '890301-12-0111', 2, 3, 3, 1, 'No.70, Taman Universiti, Skudai, Johor', 11, 1011, 'Pegawai ICT', 'F41', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0118899002', NULL, 4600, 1, '2024-11-11', NULL, 'A001'),
('1012', 'Roslan Mansor', '860201-13-0222', 1, 1, 1, 1, 'No.80, Kampung Panji, Kota Bharu, Kelantan', 12, 1012, 'Pegawai Aud', 'W43', 'Jalan Dato Lundang, Kota Bharu, Kelantan, Malaysia', '0137788002', '073345678', 5500, 1, '2024-11-12', NULL, 'A002'),
('1234', 'Ali bin Abu', '010203-04-0506', 1, 1, 1, 2, 'Add 1', 1234, 1234, 'Pos', 'A', 'Add 2', '123455', NULL, 1500, 1, '2024-12-02', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_policies`
--

CREATE TABLE `tb_policies` (
  `p_policyID` varchar(10) NOT NULL,
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
  `p_dateUpdated` date NOT NULL,
  `p_adminID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_policies`
--

INSERT INTO `tb_policies` (`p_policyID`, `p_memberRegFee`, `p_minShareCapital`, `p_minFeeCapital`, `p_minFixedSaving`, `p_minMemberFund`, `p_minMemberSaving`, `p_minOtherFees`, `p_minShareCapitalForLoan`, `p_profitRate`, `p_maxInstallmentPeriod`, `p_maxFinancingAmt`, `p_salaryDeductionForSaving`, `p_salaryDeductionForMemberFund`, `p_dateUpdated`, `p_adminID`) VALUES
('POLICY001', 35, 300, 0, 0, 5, 50, 0, 300, 5, 6, 40000, 50, 5, '2023-05-12', 'A001'),
('POLICY002', 35, 300, 0, 50, 5, 50, 0, 300, 5, 6, 40000, 50, 5, '2024-12-04', 'A002');

-- --------------------------------------------------------

--
-- Table structure for table `tb_reportretrievallog`
--

CREATE TABLE `tb_reportretrievallog` (
  `r_retrievalID` varchar(10) NOT NULL,
  `r_retrievalDate` date NOT NULL,
  `r_month` int(11) NOT NULL,
  `r_year` int(11) NOT NULL,
  `r_adminID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_reportretrievallog`
--

INSERT INTO `tb_reportretrievallog` (`r_retrievalID`, `r_retrievalDate`, `r_month`, `r_year`, `r_adminID`) VALUES
('R001', '2024-12-04', 12, 2024, 'A001'),
('R002', '2024-12-05', 12, 2024, 'A001');

-- --------------------------------------------------------

--
-- Table structure for table `tb_rmonth`
--

CREATE TABLE `tb_rmonth` (
  `rm_desc` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_rmonth`
--

INSERT INTO `tb_rmonth` (`rm_desc`) VALUES
('April'),
('Disember'),
('Februari'),
('Januari'),
('Julai'),
('Jun'),
('Mac'),
('Mei'),
('November'),
('Ogos'),
('Oktober'),
('September');

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
  `t_transactionID` varchar(10) NOT NULL,
  `t_transactionType` int(11) NOT NULL,
  `t_transactionAmt` double NOT NULL,
  `t_transactionDate` date NOT NULL,
  `t_memberNo` int(11) NOT NULL,
  `t_adminID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_transaction`
--

INSERT INTO `tb_transaction` (`t_transactionID`, `t_transactionType`, `t_transactionAmt`, `t_transactionDate`, `t_memberNo`, `t_adminID`) VALUES
('T001', 0, 100, '2024-12-04', 1, 'A001'),
('T002', 0, 200, '2024-12-01', 2, 'A002'),
('T003', 0, 350, '2024-11-26', 3, 'A001'),
('T004', 0, 50, '2024-11-16', 4, 'A002'),
('T005', 0, 200, '2024-11-07', 5, 'A001'),
('T006', 0, 100, '2024-11-28', 6, 'A002'),
('T007', 0, 250, '2024-11-25', 7, 'A001'),
('T008', 0, 100, '2024-12-03', 8, 'A002'),
('T009', 0, 150, '2024-12-01', 9, 'A001'),
('T010', 0, 50, '2024-12-02', 10, 'A002');

-- --------------------------------------------------------

--
-- Table structure for table `tb_ttype`
--

CREATE TABLE `tb_ttype` (
  `tt_desc` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_ttype`
--

INSERT INTO `tb_ttype` (`tt_desc`) VALUES
('Al-Bai'),
('Al-Innah'),
('Al-Qadrul Hassan'),
('Baik Pulih Kenderaan'),
('Karnival Musim Istimewa'),
('Khas'),
('Modah Syer'),
('Modal Fee'),
('Road Tax and Insurance'),
('Simpanan Anggota'),
('Simpanan Tetap'),
('Tabung Anggota');

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
  `um_desc` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_umaritalstatus`
--

INSERT INTO `tb_umaritalstatus` (`um_mid`, `um_desc`) VALUES
(1, 'Bujang'),
(2, 'Kahwin'),
(3, 'Cerai'),
(4, 'Kematian P');

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
  `u_id` varchar(20) NOT NULL,
  `u_pwd` varchar(16) NOT NULL,
  `u_type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`u_id`, `u_pwd`, `u_type`) VALUES
('A001', '123456', 1),
('A002', '123456', 1),
('M001', '123456', 2),
('M002', '123456', 2),
('M003', '123456', 2),
('M004', '123456', 2),
('M005', '123456', 2);

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
  ADD KEY `f_memberNo` (`f_memberNo`);

--
-- Indexes for table `tb_guarantor`
--
ALTER TABLE `tb_guarantor`
  ADD PRIMARY KEY (`g_guarantorID`),
  ADD KEY `g_memberNo` (`g_memberNo`),
  ADD KEY `g_loanApplicationID` (`g_loanApplicationID`);

--
-- Indexes for table `tb_heir`
--
ALTER TABLE `tb_heir`
  ADD PRIMARY KEY (`h_heirID`),
  ADD KEY `h_memberNo` (`h_memberNo`),
  ADD KEY `tb_heir_ibfk_2` (`h_relationWithMember`);

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
  ADD KEY `l_loanType` (`l_loanType`,`l_bankName`,`l_status`,`l_memberNo`),
  ADD KEY `l_adminID` (`l_adminID`),
  ADD KEY `tb_loan_ibfk_3` (`l_status`),
  ADD KEY `l_memberNo` (`l_memberNo`),
  ADD KEY `tb_loan_ibfk_5` (`l_bankName`);

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
  ADD KEY `m_adminID` (`m_adminID`);

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
  ADD KEY `r_adminID` (`r_adminID`);

--
-- Indexes for table `tb_rmonth`
--
ALTER TABLE `tb_rmonth`
  ADD PRIMARY KEY (`rm_desc`);

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
  ADD KEY `t_adminID` (`t_adminID`);

--
-- Indexes for table `tb_ttype`
--
ALTER TABLE `tb_ttype`
  ADD PRIMARY KEY (`tt_desc`);

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
  ADD CONSTRAINT `tb_heir_ibfk_1` FOREIGN KEY (`h_memberNo`) REFERENCES `tb_member` (`m_memberNo`),
  ADD CONSTRAINT `tb_heir_ibfk_2` FOREIGN KEY (`h_relationWithMember`) REFERENCES `tb_hrelation` (`hr_rid`);

--
-- Constraints for table `tb_loan`
--
ALTER TABLE `tb_loan`
  ADD CONSTRAINT `tb_loan_ibfk_1` FOREIGN KEY (`l_adminID`) REFERENCES `tb_admin` (`a_adminID`),
  ADD CONSTRAINT `tb_loan_ibfk_2` FOREIGN KEY (`l_loanType`) REFERENCES `tb_ltype` (`lt_lid`),
  ADD CONSTRAINT `tb_loan_ibfk_3` FOREIGN KEY (`l_status`) REFERENCES `tb_status` (`s_sid`),
  ADD CONSTRAINT `tb_loan_ibfk_4` FOREIGN KEY (`l_memberNo`) REFERENCES `tb_member` (`m_memberNo`),
  ADD CONSTRAINT `tb_loan_ibfk_5` FOREIGN KEY (`l_bankName`) REFERENCES `tb_lbank` (`lb_id`);

--
-- Constraints for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD CONSTRAINT `tb_member_ibfk_1` FOREIGN KEY (`m_gender`) REFERENCES `tb_ugender` (`ug_gid`),
  ADD CONSTRAINT `tb_member_ibfk_2` FOREIGN KEY (`m_religion`) REFERENCES `tb_ureligion` (`ua_rid`),
  ADD CONSTRAINT `tb_member_ibfk_3` FOREIGN KEY (`m_race`) REFERENCES `tb_urace` (`ur_rid`),
  ADD CONSTRAINT `tb_member_ibfk_4` FOREIGN KEY (`m_maritalStatus`) REFERENCES `tb_umaritalstatus` (`um_mid`),
  ADD CONSTRAINT `tb_member_ibfk_5` FOREIGN KEY (`m_status`) REFERENCES `tb_status` (`s_sid`);

--
-- Constraints for table `tb_policies`
--
ALTER TABLE `tb_policies`
  ADD CONSTRAINT `tb_policies_ibfk_1` FOREIGN KEY (`p_adminID`) REFERENCES `tb_admin` (`a_adminID`);

--
-- Constraints for table `tb_reportretrievallog`
--
ALTER TABLE `tb_reportretrievallog`
  ADD CONSTRAINT `tb_reportretrievallog_ibfk_1` FOREIGN KEY (`r_adminID`) REFERENCES `tb_admin` (`a_adminID`);

--
-- Constraints for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  ADD CONSTRAINT `tb_transaction_ibfk_1` FOREIGN KEY (`t_memberNo`) REFERENCES `tb_member` (`m_memberNo`),
  ADD CONSTRAINT `tb_transaction_ibfk_2` FOREIGN KEY (`t_adminID`) REFERENCES `tb_admin` (`a_adminID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
