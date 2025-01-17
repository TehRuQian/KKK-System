<?php
require('../fpdf/fpdf.php');

class PDF extends FPDF {
    // 页眉
    function Header() {
        // Logo
        if(file_exists('../img/kkk_logo.png')) {
            $this->Image('../img/kkk_logo.png', 10, 6, 30);
        }
        // 标题
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'LAPORAN KEWANGAN', 0, 1, 'C');
        $this->Ln(10);
    }

    // 页脚
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Muka ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

try {
    // 数据库连接
    require_once('../db_connect.php');

    // 获取 POST 数据
    $bulan = isset($_POST['bulan']) ? mysqli_real_escape_string($con, $_POST['bulan']) : '';
    $tahun = isset($_POST['tahun']) ? mysqli_real_escape_string($con, $_POST['tahun']) : '';

    // 获取期间文本
    $period = "";
    if ($bulan && $tahun) {
        $month_query = mysqli_query($con, "SELECT rm_desc FROM tb_rmonth WHERE rm_id = '$bulan'");
        $month_name = mysqli_fetch_assoc($month_query)['rm_desc'];
        $period = $month_name . " " . $tahun;
    } elseif ($bulan) {
        $month_query = mysqli_query($con, "SELECT rm_desc FROM tb_rmonth WHERE rm_id = '$bulan'");
        $month_name = mysqli_fetch_assoc($month_query)['rm_desc'];
        $period = $month_name;
    } elseif ($tahun) {
        $period = "Tahun " . $tahun;
    }

    // 创建 PDF
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 11);

    // 添加期间
    $pdf->Cell(0, 10, 'Tempoh: ' . $period, 0, 1);
    $pdf->Ln(5);

    // 1. 执行摘要
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '1. Ringkasan Eksekutif', 0, 1);
    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(0, 10, 'Laporan ini memberikan analisis terperinci mengenai prestasi kewangan syarikat untuk tempoh ' . 
                          $period . ', termasuk metrik utama seperti permohonan ahli, permohonan pinjaman, rekod transaksi ' .
                          'dan kesihatan kewangan keseluruhan.');
    $pdf->Ln(5);

    // 2. 会员申请概览
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '2. Gambaran Keseluruhan Permohonan Ahli', 0, 1);
    $pdf->SetFont('Arial', '', 11);
    
    // 获取会员数据
    $member_sql = "SELECT 
        COUNT(*) as total_count,
        SUM(CASE WHEN m_status = 1 THEN 1 ELSE 0 END) as pending_count,
        SUM(CASE WHEN m_status = 2 THEN 1 ELSE 0 END) as rejected_count,
        SUM(CASE WHEN m_status = 3 THEN 1 ELSE 0 END) as approved_count
        FROM tb_member WHERE MONTH(m_applicationDate) = '$bulan' AND YEAR(m_applicationDate) = '$tahun'";
    
    $member_result = mysqli_query($con, $member_sql);
    $member_data = mysqli_fetch_assoc($member_result);

    $pdf->Cell(0, 10, 'Jumlah Permohonan Ahli Baru: ' . $member_data['total_count'], 0, 1);
    $pdf->Cell(0, 10, 'Permohonan Mengikut Status:', 0, 1);
    $pdf->Cell(20, 10, '', 0, 0);
    $pdf->Cell(0, 10, 'Diluluskan: ' . $member_data['approved_count'], 0, 1);
    $pdf->Cell(20, 10, '', 0, 0);
    $pdf->Cell(0, 10, 'Sedang Diproses: ' . $member_data['pending_count'], 0, 1);
    $pdf->Cell(20, 10, '', 0, 0);
    $pdf->Cell(0, 10, 'Ditolak: ' . $member_data['rejected_count'], 0, 1);
    $pdf->Ln(5);

    // 3. 贷款申请概览
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '3. Gambaran Keseluruhan Permohonan Pinjaman', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    // 获取贷款数据
    $loan_sql = "SELECT 
        COUNT(*) as total_count,
        SUM(CASE WHEN l_status = 1 THEN 1 ELSE 0 END) as pending_count,
        SUM(CASE WHEN l_status = 2 THEN 1 ELSE 0 END) as rejected_count,
        SUM(CASE WHEN l_status = 3 THEN 1 ELSE 0 END) as approved_count,
        SUM(l_appliedLoan) as total_amount
        FROM tb_loan WHERE MONTH(l_applicationDate) = '$bulan' AND YEAR(l_applicationDate) = '$tahun'";

    $loan_result = mysqli_query($con, $loan_sql);
    $loan_data = mysqli_fetch_assoc($loan_result);

    $pdf->Cell(0, 10, 'Jumlah Permohonan Pinjaman: ' . $loan_data['total_count'], 0, 1);
    $pdf->Cell(0, 10, 'Jumlah Amaun: RM ' . number_format($loan_data['total_amount'], 2), 0, 1);
    $pdf->Cell(0, 10, 'Status Permohonan:', 0, 1);
    $pdf->Cell(20, 10, '', 0, 0);
    $pdf->Cell(0, 10, 'Diluluskan: ' . $loan_data['approved_count'], 0, 1);
    $pdf->Cell(20, 10, '', 0, 0);
    $pdf->Cell(0, 10, 'Sedang Diproses: ' . $loan_data['pending_count'], 0, 1);
    $pdf->Cell(20, 10, '', 0, 0);
    $pdf->Cell(0, 10, 'Ditolak: ' . $loan_data['rejected_count'], 0, 1);

    // 4. 交易表现
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '4. Prestasi Transaksi', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    // 获取交易数据
    $transaction_sql = "SELECT 
        COUNT(*) as transaction_count,
        SUM(t_transactionAmt) as transaction_total
        FROM tb_transaction 
        WHERE MONTH(t_transactionDate) = '$bulan' AND YEAR(t_transactionDate) = '$tahun'";

    $transaction_result = mysqli_query($con, $transaction_sql);
    $transaction_data = mysqli_fetch_assoc($transaction_result);

    $pdf->Cell(0, 10, 'Jumlah Transaksi: ' . $transaction_data['transaction_count'], 0, 1);
    $pdf->Cell(0, 10, 'Jumlah Amaun Transaksi: RM ' . number_format($transaction_data['transaction_total'], 2), 0, 1);
    $pdf->Ln(5);

    // 5. 财务表现
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '5. Prestasi Kewangan', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    $net_profit = $transaction_data['transaction_total'] - $loan_data['total_amount'];

    $pdf->Cell(0, 10, 'Jumlah Pendapatan untuk Tempoh: RM ' . number_format($transaction_data['transaction_total'], 2), 0, 1);
    $pdf->Cell(0, 10, 'Jumlah Perbelanjaan untuk Tempoh: RM ' . number_format($loan_data['total_amount'], 2), 0, 1);
    $pdf->Cell(0, 10, 'Keuntungan Bersih untuk Tempoh: RM ' . number_format($net_profit, 2), 0, 1);

    // 输出 PDF
    $pdf->Output('D', 'Laporan_Kewangan_' . $period . '.pdf');

} catch (Exception $e) {
    die('Error: ' . $e->getMessage());
}
?>