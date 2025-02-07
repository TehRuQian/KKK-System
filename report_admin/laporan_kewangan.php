<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../fpdf/fpdf.php');
require_once('../db_connect.php');

$bulan = isset($_GET['bulan']) ? mysqli_real_escape_string($con, $_GET['bulan']) : '';
$tahun = isset($_GET['tahun']) ? mysqli_real_escape_string($con, $_GET['tahun']) : '';

    
if (!empty($tahun)) {
    
    if (!preg_match('/^\d{4}$/', $tahun)) {
        die("Format tahun tidak sah. Sila masukkan tahun dalam format 4 digit (contoh: 2024)");
    }
    error_log("Processing year: " . $tahun);
}

if (!empty($bulan)) {
    
    if (!preg_match('/^([1-9]|1[0-2])$/', $bulan)) {
        die("Format bulan tidak sah. Sila masukkan bulan dari 1 hingga 12");
    }
    error_log("Processing month: " . $bulan);
}


function createWhereClause($bulan, $tahun, $dateField) {
    $conditions = array();
    
    if (!empty($bulan)) {
        $conditions[] = "MONTH($dateField) = '$bulan'";
    }
    
    if (!empty($tahun)) {
        $conditions[] = "YEAR($dateField) = '$tahun'";
    }

    error_log("Generated WHERE clause for $dateField: " . (!empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : ""));
    
    return !empty($conditions) ? " WHERE " . implode(" AND ", $conditions) : "";
}


$period = "";
if ($bulan && $tahun) {
    $month_query = mysqli_query($con, "SELECT rm_desc FROM tb_rmonth WHERE rm_id = '$bulan'");
    if ($month_query && mysqli_num_rows($month_query) > 0) {
        $month_data = mysqli_fetch_assoc($month_query);
        $period = $month_data['rm_desc'] . " " . $tahun;
    }
} elseif ($bulan) {
    $month_query = mysqli_query($con, "SELECT rm_desc FROM tb_rmonth WHERE rm_id = '$bulan'");
    if ($month_query && mysqli_num_rows($month_query) > 0) {
        $month_data = mysqli_fetch_assoc($month_query);
        $period = $month_data['rm_desc'];
    }
} elseif ($tahun) {
    $period = "Tahun " . $tahun;
} else {
    $period = "Semua Tempoh";
}

class PDF extends FPDF {
    function Header() {
        if(file_exists('../img/kkk_logo.png')) {
            $this->Image('../img/kkk_logo.png', 10, 6, 30);
        }
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'LAPORAN KEWANGAN', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Muka ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    
    function CreateTable($header, $data, $totalRow = false) {
        
        $w = array(120, 70);
    
        
        $this->SetFont('Arial', 'B', 11);
        $this->SetFillColor(200, 200, 200); 
        for($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'L', true);
        }
        $this->Ln();
        
        
        $this->SetFont('Arial', '', 11);
        $this->SetFillColor(240, 240, 240); 
        foreach($data as $index => $row) {
            $fill = ($totalRow && $index == count($data) - 1); 
            $this->Cell($w[0], 6, $row[0], 1, 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 1, 0, 'L', $fill);
            $this->Ln();
        }
    }
}

try {
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 11);

    
    $pdf->Cell(0, 10, 'Tempoh: ' . $period, 0, 1);
    $pdf->Ln(5);

    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '1. Ringkasan Eksekutif', 0, 1);
    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(0, 10, 'Laporan ini memberikan analisis terperinci mengenai prestasi kewangan syarikat untuk tempoh ' . 
                          (!empty($period) ? $period : 'semua tempoh') . 
                          ', termasuk metrik utama seperti permohonan ahli, permohonan pinjaman, rekod transaksi ' .
                          'dan kesihatan kewangan keseluruhan.', 0, 'L');
    $pdf->Ln(5);

    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '2. Gambaran Keseluruhan Permohonan Ahli', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    $member_sql = "SELECT 
        COUNT(*) as total_count,
        SUM(CASE WHEN m_status = 1 THEN 1 ELSE 0 END) as pending_count,
        SUM(CASE WHEN m_status = 2 THEN 1 ELSE 0 END) as rejected_count,
        SUM(CASE WHEN m_status = 3 THEN 1 ELSE 0 END) as approved_count
        FROM tb_member" . createWhereClause($bulan, $tahun, 'm_applicationDate');

        error_log("Member SQL: " . $member_sql);

    $member_result = mysqli_query($con, $member_sql);
    $member_data = mysqli_fetch_assoc($member_result);

    $header = array('Status', 'Jumlah');
    $data = array(
        array('Diluluskan', $member_data['approved_count'] ?? 0),
        array('Sedang Diproses', $member_data['pending_count'] ?? 0),
        array('Ditolak', $member_data['rejected_count'] ?? 0),
        array('JUMLAH PERMOHONAN', 
            ($member_data['approved_count'] ?? 0) + 
            ($member_data['pending_count'] ?? 0) + 
            ($member_data['rejected_count'] ?? 0))
    );
    $pdf->CreateTable($header, $data, true);
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '3. Gambaran Keseluruhan Status Ahli', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    $member_sql = "SELECT 
        COUNT(*) as total_count,
        SUM(CASE WHEN m_status = 3 THEN 1 ELSE 0 END) as active_count,
        SUM(CASE WHEN m_status = 5 THEN 1 ELSE 0 END) as stop_count,
        SUM(CASE WHEN m_status = 6 THEN 1 ELSE 0 END) as retire_count
        FROM tb_member" . createWhereClause($bulan, $tahun, 'm_applicationDate');

        error_log("Member SQL: " . $member_sql);

    $member_result = mysqli_query($con, $member_sql);
    $member_data = mysqli_fetch_assoc($member_result);

    $header = array('Status', 'Jumlah');
    $data = array(
        array('Aktif', $member_data['active_count'] ?? 0),
        array('Berhenti', $member_data['stop_count'] ?? 0),
        array('Pencen', $member_data['retire_count'] ?? 0),
        array('JUMLAH STATUS', 
            ($member_data['active_count'] ?? 0) + 
            ($member_data['stop_count'] ?? 0) + 
            ($member_data['retire_count'] ?? 0))
    );
    $pdf->CreateTable($header, $data, true);
    $pdf->Ln(10);

    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '4. Gambaran Keseluruhan Permohonan Pinjaman', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    $loan_sql = "SELECT 
        COUNT(*) as total_count,
        SUM(CASE WHEN l_status = 1 THEN 1 ELSE 0 END) as pending_count,
        SUM(CASE WHEN l_status = 2 THEN 1 ELSE 0 END) as rejected_count,
        SUM(CASE WHEN l_status = 3 THEN 1 ELSE 0 END) as approved_count,
        SUM(CASE WHEN l_status = 4 THEN 1 ELSE 0 END) as completed_count,
        SUM(l_appliedLoan) as total_amount
        FROM tb_loan" . createWhereClause($bulan, $tahun, 'l_applicationDate');

    $loan_result = mysqli_query($con, $loan_sql);
    $loan_data = mysqli_fetch_assoc($loan_result);

    $header = array('Status', 'Jumlah');
    $data = array(
        array('Diluluskan', $loan_data['approved_count'] ?? 0),
        array('Sedang Diproses', $loan_data['pending_count'] ?? 0),
        array('Ditolak', $loan_data['rejected_count'] ?? 0),
        array('Dijelaskan', $loan_data['completed_count'] ?? 0),
        array('JUMLAH PERMOHONAN', $loan_data['total_count'] ?? 0)
    );
    $pdf->CreateTable($header, $data, true);
    $pdf->Ln(5);

    
    $loan_type_sql = "SELECT 
        SUM(CASE WHEN l_loanType = 1 THEN 1 ELSE 0 END) as alBai,
        SUM(CASE WHEN l_loanType = 2 THEN 1 ELSE 0 END) as alInnah,
        SUM(CASE WHEN l_loanType = 3 THEN 1 ELSE 0 END) as baikPulihKenderaan,
        SUM(CASE WHEN l_loanType = 4 THEN 1 ELSE 0 END) as roadTax,
        SUM(CASE WHEN l_loanType = 5 THEN 1 ELSE 0 END) as khas,
        SUM(CASE WHEN l_loanType = 6 THEN 1 ELSE 0 END) as karnival,
        SUM(CASE WHEN l_loanType = 7 THEN 1 ELSE 0 END) as alQadrul
        FROM tb_loan" . createWhereClause($bulan, $tahun, 'l_applicationDate');

    $loan_type_result = mysqli_query($con, $loan_type_sql);
    $loan_type_data = mysqli_fetch_assoc($loan_type_result);

    $header = array('Jenis Pinjaman', 'Jumlah');
    $data = array(
        array('Al-Bai', $loan_type_data['alBai'] ?? 0),
        array('Al-Innah', $loan_type_data['alInnah'] ?? 0),
        array('Baik Pulih Kenderaan', $loan_type_data['baikPulihKenderaan'] ?? 0),
        array('Road Tax dan Insurans', $loan_type_data['roadTax'] ?? 0),
        array('Khas', $loan_type_data['khas'] ?? 0),
        array('Karnival Musim Istimewa', $loan_type_data['karnival'] ?? 0),
        array('Al-Qadrul Hassan', $loan_type_data['alQadrul'] ?? 0),
        array('JUMLAH', array_sum($loan_type_data))
    );
    $pdf->CreateTable($header, $data, true);
    $pdf->Ln(10);

    $pdf->Cell(0, 10, 'Jumlah Amaun Pinjaman: RM ' . number_format($loan_data['total_amount'] ?? 0, 2), 0, 1);
    $pdf->Ln(5);


    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '5. Prestasi Transaksi', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    $transaction_sql = "SELECT 
        COUNT(*) as transaction_count,
        COALESCE(SUM(t_transactionAmt), 0) as transaction_total
        FROM tb_transaction" . createWhereClause($bulan, $tahun, 't_transactionDate');

    $transaction_result = mysqli_query($con, $transaction_sql);
    $transaction_data = mysqli_fetch_assoc($transaction_result);

    $header = array('Perkara', 'Jumlah');
    $data = array(
        array('Bilangan Transaksi', $transaction_data['transaction_count'] ?? 0),
        array('JUMLAH AMAUN', 'RM ' . number_format($transaction_data['transaction_total'] ?? 0, 2))
    );
    $pdf->CreateTable($header, $data, true);
    $pdf->Ln(10);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '6. Maklumat Polisi', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    $policies_sql = "SELECT * FROM tb_policies";
    if ($bulan && $tahun) {
        $policies_sql .= " WHERE MONTH(p_dateUpdated) = '$bulan' AND YEAR(p_dateUpdated) = '$tahun'";
    }
    $policies_sql .= " ORDER BY p_dateUpdated DESC LIMIT 1";

    $policies_result = mysqli_query($con, $policies_sql);
    $policy_data = mysqli_fetch_assoc($policies_result);

    $header = array('Perkara', 'Nilai');
    $data = array(
        array('Yuran Pendaftaran Anggota', 'RM ' . number_format($policy_data['p_memberRegFee'] ?? 0, 2)),
        array('Modal Syer Minimum', 'RM ' . number_format($policy_data['p_minShareCapital'] ?? 0, 2)),
        array('Yuran Modal Minimum', 'RM ' . number_format($policy_data['p_minFeeCapital'] ?? 0, 2)),
        array('Yuran Tetap Minimum', 'RM ' . number_format($policy_data['p_minFixedSaving'] ?? 0, 2)),
        array('Simpanan Tetap Minimum', 'RM ' . number_format($policy_data['p_minMemberFund'] ?? 0, 2)),
        array('Tabung Kebajikan Minimum', 'RM ' . number_format($policy_data['p_minMemberSaving'] ?? 0, 2)),
        array('Jumlah Pembiayaan Maksimum Bagi Al-Bai', 'RM ' . number_format($policy_data['p_maxAlBai'] ?? 0, 2)),
        array('Kadar Keuntungan Bagi Al-Bai', ($policy_data['p_rateAlBai'] ?? 0) . '%'),
        array('Jumlah Pembiayaan Maksimum Bagi Al-Innah', 'RM ' . number_format($policy_data['p_maxAlInnah'] ?? 0, 2)),
        array('Kadar Keuntungan Bagi Al-Innah', ($policy_data['p_rateAlInnah'] ?? 0) . '%'),
        array('Jumlah Pembiayaan Maksimum Bagi Baik Pulih Kenderaan', 'RM ' . number_format($policy_data['p_maxBPulihKenderaan'] ?? 0, 2)),
        array('Kadar Keuntungan Bagi  Baik Pulih Kenderaan', ($policy_data['p_rateBPulihKenderaan'] ?? 0) . '%'),
        array('Jumlah Pembiayaan Maksimum Bagi Road Tax dan Insurans', 'RM ' . number_format($policy_data['p_maxCukaiJalanInsurans'] ?? 0, 2)),
        array('Kadar Keuntungan Bagi Road Tax dan Insurans', ($policy_data['p_rateCukaiJalanInsurans'] ?? 0) . '%'),
        array('Jumlah Pembiayaan Maksimum Bagi Khas', 'RM ' . number_format($policy_data['p_maxKhas'] ?? 0, 2)),
        array('Kadar Keuntungan Bagi Khas', ($policy_data['p_rateKhas'] ?? 0) . '%'),
        array('Jumlah Pembiayaan Maksimum Bagi Karnival Musim Istimewa', 'RM ' . number_format($policy_data['p_maxKarnivalMusim'] ?? 0, 2)),
        array('Kadar Keuntungan Bagi Karnival Musim Istimewa', ($policy_data['p_rateKarnivalMusim'] ?? 0) . '%'),
        array('Jumlah Pembiayaan Maksimum Bagi Al-Qadrul Hassan', 'RM ' . number_format($policy_data['p_maxAlQadrulHassan'] ?? 0, 2)),
        array('Kadar Keuntungan Bagi Al-Qadrul Hassan', ($policy_data['p_rateAlQadrulHassan'] ?? 0) . '%')
    );
    $pdf->CreateTable($header, $data, true);
    $pdf->Ln(10);

    // 7. Kesimpulan
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, '7. Kesimpulan', 0, 1);
    $pdf->SetFont('Arial', '', 11);

    
    $member_approval_rate = $member_data['total_count'] > 0 ? 
        ($member_data['approved_count'] / $member_data['total_count']) * 100 : 0;
    
    $loan_approval_rate = $loan_data['total_count'] > 0 ? 
        ($loan_data['approved_count'] / $loan_data['total_count']) * 100 : 0;

    
    $conclusion = "Berdasarkan analisis untuk tempoh " . $period . ", laporan ini merumuskan bahawa:\n\n";
    
    
    $conclusion .= "1. Permohonan Keahlian:\n";
    $conclusion .= "   - Kadar kelulusan keahlian adalah " . number_format($member_approval_rate, 1) . "%\n";
    $conclusion .= "   - Jumlah " . $member_data['total_count'] . " permohonan baharu telah diterima\n\n";

    
    $conclusion .= "2. Permohonan Pinjaman:\n";
    $conclusion .= "   - Kadar kelulusan pinjaman adalah " . number_format($loan_approval_rate, 1) . "%\n";
    $conclusion .= "   - Jumlah nilai pinjaman yang diluluskan: RM " . number_format($loan_data['total_amount'], 2) . "\n\n";

    
    
    $conclusion .= "3. Cadangan:\n";
    if ($member_approval_rate < 70) {
        $conclusion .= "   - Meningkatkan proses kelulusan keahlian\n";
    }
    if ($loan_approval_rate < 70) {
        $conclusion .= "   - Mengkaji semula kriteria kelayakan pinjaman\n";
    }
    if ($net_profit <= 0) {
        $conclusion .= "   - Mengkaji semula strategi pengurusan kewangan\n";
    }
    $conclusion .= "   - Memantau prestasi kewangan secara berterusan\n";
    $conclusion .= "   - Mengekalkan polisi yang sedia ada untuk kestabilan operasi\n";

    
    $pdf->MultiCell(0, 6, $conclusion, 0, 'L');

        ob_end_clean();
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="Laporan_Kewangan_' . $period . '.pdf"');
        $pdf->Output('I');
        exit;

} catch (Exception $e) {
    error_log("PDF Generation Error: " . $e->getMessage());
    echo "Error generating PDF: " . $e->getMessage();
}
?>