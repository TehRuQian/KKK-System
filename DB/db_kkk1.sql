-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2025 at 09:11 AM
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
-- Database: `db_kkk1`
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
(9, 3, 'Theena A/P Pravin', 2, '040102-01-0124');

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
(1, 'Tan Mei Ling', '040201-08-0666', 'tanmeiling@hotmail.com', 2, 2, 2, 1, '21, Jalan Rumah', 12345, 'Bukit Mertajam', 7, NULL, 12345, 'Kerani', 'N12', '21, Jalan Pejabat', 12345, 'Kota Bahru', 3, NULL, '0123456789', '', 2000, 35, 300, 0, 0, 5, 50, 0, 1, '2025-01-21 08:54:56', NULL, NULL),
(2, 'Roslan bin Roslan', '010101-01-0101', 'tanmeiling@gmail.com', 1, 1, 1, 2, '67, Jalan Duta', 10000, 'Duta', 8, NULL, 1235, 'Pengurus', '100', '12, Jalan Bahagia', 51000, 'Kota Bahru', 3, NULL, '0123456789', '', 3000, 35, 300, 0, 0, 5, 50, 0, 1, '2025-01-21 08:59:31', NULL, NULL),
(3, 'Pravin A/L Ali', '891212-01-1231', 'pravin@gmail.com', 1, 3, 3, 1, '123, Jalan Bendera', 80000, 'Bendera', 1, NULL, 1236, 'Petani', 'P12', '123, Jalan Kelantan', 12345, 'Kota Bahru', 3, NULL, '0123456789', '071234567', 2000, 35, 300, 0, 0, 5, 50, 0, 1, '2025-01-21 09:08:12', NULL, NULL);

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
(1, 35, 300, 0, 0, 5, 50, 0, 300, 5, 6, 40000, 50, 5, '2023-05-10 16:00:00', 12345678);

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
(12345678, '', '2025-01-16 11:54:10', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 1),
(12345679, '', '2025-01-16 11:54:10', '$argon2id$v=19$m=16,t=2,p=1$TTI5eHU4TWpCWXd1Tllsdg$ZRj93tHbkJmyBn69e8BgDQ', 1);

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
  MODIFY `b_bannerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_guarantor`
--
ALTER TABLE `tb_guarantor`
  MODIFY `g_guarantorID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_heir`
--
ALTER TABLE `tb_heir`
  MODIFY `h_heirID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_loan`
--
ALTER TABLE `tb_loan`
  MODIFY `l_loanApplicationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `m_memberApplicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_policies`
--
ALTER TABLE `tb_policies`
  MODIFY `p_policyID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_reportretrievallog`
--
ALTER TABLE `tb_reportretrievallog`
  MODIFY `r_retrievalID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_transaction`
--
ALTER TABLE `tb_transaction`
  MODIFY `t_transactionID` int(11) NOT NULL AUTO_INCREMENT;

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
