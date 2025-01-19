<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../header_admin.php';
include '../db_connect.php';

$adminID = $_SESSION['u_id'] ?? null;


error_log('Current Session: ' . print_r($_SESSION, true));
error_log('Admin ID from session: ' . $adminID);


$bulan = isset($_POST['Bulan']) ? $_POST['Bulan'] : '';  
$tahun = isset($_POST['Tahun']) ? $_POST['Tahun'] : ''; 

$period = "";
if ($bulan && $tahun) {
    $month_query = mysqli_query($con, "SELECT rm_desc FROM tb_rmonth WHERE rm_id = '$bulan'");
    if ($month_query && mysqli_num_rows($month_query) > 0) {
        $month_name = mysqli_fetch_assoc($month_query)['rm_desc'];
        $period = $month_name . " " . $tahun;
    }
} elseif ($bulan) {
    $month_query = mysqli_query($con, "SELECT rm_desc FROM tb_rmonth WHERE rm_id = '$bulan'");
    if ($month_query && mysqli_num_rows($month_query) > 0) {
        $month_name = mysqli_fetch_assoc($month_query)['rm_desc'];
        $period = $month_name;
    }
} elseif ($tahun) {
    $period = "Tahun " . $tahun;
}



//member data
$member_count = 0;
$member_approved = $member_pending = $member_rejected = 0;

//loan data
$loan_count = 0;
$loan_approved = $loan_pending = $loan_rejected = $total_approved_loans = 0;
$alBai = $alInnah = $baikPulihKenderaan = $roadTax = $khas = $karnival = $alQadrul = 0;

//Transaction data
$transaction_count = 0;
$transaction_sum = 0;

// Build where clause
$where_clause = "";
if ($bulan && $tahun) {
    $where_clause = "WHERE MONTH(m_applicationDate) = '$bulan' AND YEAR(m_applicationDate) = '$tahun'";
} elseif ($bulan) {
    $where_clause = "WHERE MONTH(m_applicationDate) = '$bulan'";
} elseif ($tahun) {
    $where_clause = "WHERE YEAR(m_applicationDate) = '$tahun'";
}

//member data
$member_sql = "SELECT COUNT(*) AS member_count FROM tb_member $where_clause";
$member_result = mysqli_query($con, $member_sql);
if ($member_result) {
    $member_data = mysqli_fetch_assoc($member_result);
    $member_count = $member_data['member_count'];
}

//member_status
$status_sql = "SELECT m_status, COUNT(*) AS status_count FROM tb_member 
               $where_clause 
               GROUP BY m_status";
$status_result = mysqli_query($con, $status_sql);

if ($status_result) {
    while ($status_row = mysqli_fetch_assoc($status_result)) {
        switch ($status_row['m_status']) {
            case 1: $member_pending = $status_row['status_count']; break;
            case 2: $member_rejected = $status_row['status_count']; break;
            case 3: $member_approved = $status_row['status_count']; break;
        }
    }
}

//Loan data
$loan_where = str_replace('m_applicationDate', 'l_applicationDate', $where_clause);
$loan_sql = "SELECT COUNT(*) AS loan_count FROM tb_loan $loan_where";
$loan_result = mysqli_query($con, $loan_sql);
if ($loan_result) {
    $loan_data = mysqli_fetch_assoc($loan_result);
    $loan_count = $loan_data['loan_count'];
}

//loan status
$loan_status_sql = "SELECT l_status, COUNT(*) AS loan_status_count,
                           SUM(CASE WHEN l_status = 3 THEN l_appliedLoan ELSE 0 END) AS total_approved_loans  
                    FROM tb_loan $loan_where 
                    GROUP BY l_status";
$loan_status_result = mysqli_query($con, $loan_status_sql);

if ($loan_status_result) {
    while ($loan_status_row = mysqli_fetch_assoc($loan_status_result)) {
        switch ($loan_status_row['l_status']) {
            case 1: $loan_pending = $loan_status_row['loan_status_count']; break;
            case 2: $loan_rejected = $loan_status_row['loan_status_count']; break;
            case 3: 
                $loan_approved = $loan_status_row['loan_status_count']; 
                $total_approved_loans = $loan_status_row['total_approved_loans'];
                break;
        }
    }
}

//loan type
$loan_type_sql = "SELECT l_loanType, COUNT(*) AS loan_type_count FROM tb_loan 
                  $loan_where 
                  GROUP BY l_loanType";
$loan_type_result = mysqli_query($con, $loan_type_sql);

if ($loan_type_result) {
    while ($loan_type_row = mysqli_fetch_assoc($loan_type_result)) {
        switch ($loan_type_row['l_loanType']) {
            case 1: $alBai = $loan_type_row['loan_type_count']; break;
            case 2: $alInnah = $loan_type_row['loan_type_count']; break;
            case 3: $baikPulihKenderaan = $loan_type_row['loan_type_count']; break;
            case 4: $roadTax = $loan_type_row['loan_type_count']; break;
            case 5: $khas = $loan_type_row['loan_type_count']; break;
            case 6: $karnival = $loan_type_row['loan_type_count']; break;
            case 7: $alQadrul = $loan_type_row['loan_type_count']; break;
        }
    }
}

//Transaction data
$transaction_where = str_replace('m_applicationDate', 't_transactionDate', $where_clause);
$transactions_sql = "SELECT COUNT(*) AS transaction_count, SUM(t_transactionAmt) AS transaction_total
                    FROM tb_transaction $transaction_where";
$transactions_result = mysqli_query($con, $transactions_sql);

if ($transactions_result && mysqli_num_rows($transactions_result) > 0) {
    $transaction_data = mysqli_fetch_assoc($transactions_result);
    $transaction_count = $transaction_data['transaction_count'] ?? 0;
    $transaction_total = $transaction_data['transaction_total'] ?? 0;
} else {
    $transaction_total = 0;
}

//Polisi
$policies_where = str_replace('m_applicationDate', 'p_dateUpdated', $where_clause);
$policies_sql = "SELECT * FROM tb_policies $policies_where";
$policies_result = mysqli_query($con, $policies_sql);
?>

<form method="post" action="">
    <fieldset>
        <div class="container">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="form-label mt-4">Bulan</label>
                    <select name="Bulan" class="form-select" id="Bulan" style="width: 20ch;">
                        <option value="">-- Pilih Bulan --</option>
                        <?php
                        $month_result = mysqli_query($con, "SELECT * FROM tb_rmonth");
                        while ($row = mysqli_fetch_array($month_result)) {
                            echo '<option value="' . $row['rm_id'] . '"' . ($row['rm_id'] == $bulan ? ' selected' : '') . '>' . htmlspecialchars($row['rm_desc']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-auto" style="padding-top: 24px;">
                    <label for="Tahun" class="form-label">Tahun</label>
                    <input type="text" name="Tahun" class="form-control" maxlength="4" placeholder="Contoh: 2024" style="width: 20ch;" value="<?= $tahun ?>">    
                </div>
                <div class="col-auto" style="padding-top: 60px;">
                    <button type="submit" class="btn btn-primary">Tapis</button>
                </div>
            </div>

            <div class="report-details mt-4">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><strong>Laporan Kewangan <?= htmlspecialchars($period) ?></strong></h3>

                    <?php if ($bulan || $tahun): ?>
                      <div style="float: right;">
                        <!-- Simpan Log --> 
                        <button type="button" class="btn btn-success" onclick="saveRetrievalLog(<?= $bulan ?? 'null' ?>, <?= $tahun ?? 'null' ?>, <?= $adminID ?? 'null' ?>)">
                            <i class="fas fa-save"></i> Simpan Log
                        </button>

                      <!-- PDF -->
                        <a href="generate_pdf.php?bulan=<?= urlencode($bulan) ?>&tahun=<?= urlencode($tahun) ?>" 
                           class="btn btn-primary" 
                           target="_blank">
                            <i class="fas fa-download"></i> Lihat dalam PDF
                        </a>
                      </div>
                    <?php endif; ?>
              </div>
            </div>

             <div id="loading" style="display: none;">
                    <p>Memuat data...</p>
              </div>

              <?php if ($bulan || $tahun): ?>
                <div class="report-content">
                <p><strong>1. Ringkasan Eksekutif</strong></p>
                <p>Laporan ini memberikan analisis terperinci mengenai prestasi kewangan syarikat untuk tempoh <?= $period ?>, 
                   termasuk metrik utama seperti permohonan ahli, permohonan pinjaman, rekod transaksi dan kesihatan kewangan keseluruhan.</p>

                <p><strong>2. Gambaran Keseluruhan Permohonan Ahli</strong></p>
                <p>Jumlah Permohonan Ahli Baru: <?= $member_count ?></p>
                <p>Permohonan Mengikut Status:</p>
                <ul>
                    <li>Diluluskan: <?= $member_approved ?></li>
                    <li>Sedang Diproses: <?= $member_pending ?></li>
                    <li>Ditolak: <?= $member_rejected ?></li>
                </ul>

                <p><strong>3. Gambaran Keseluruhan Permohonan Pinjaman</strong></p>
                <p>Jumlah Permohonan Pinjaman Baru: <?= $loan_count ?></p>
                <p>Permohonan Mengikut Status:</p>
                <ul>
                    <li>Diluluskan: <?= $loan_approved ?></li>
                    <li>Sedang Diproses: <?= $loan_pending ?></li>
                    <li>Ditolak: <?= $loan_rejected ?></li>
                </ul>

                <p>Permohonan Pinjaman Mengikut Jenis:</p>
                <ul>
                    <li>Al-Bai: <?= $alBai ?></li>
                    <li>Al-Innah: <?= $alInnah ?></li>
                    <li>Baik Pulih Kenderaan: <?= $baikPulihKenderaan ?></li>
                    <li>Road Tax dan Insurans: <?= $roadTax ?></li>
                    <li>Khas: <?= $khas ?></li>
                    <li>Karnival Musim Istimewa: <?= $karnival ?></li>
                    <li>Al-Qadrul Hassan: <?= $alQadrul ?></li>
                </ul>

                <p><strong>4. Prestasi Transaksi</strong></p>
                <p>Jumlah Transaksi: <?= $transaction_count ?></p>
                <p>Jumlah Amaun Transaksi: RM <?= number_format($transaction_total, 2) ?></p>

                <p><strong>5. Maklumat Polisi</strong></p>
                <?php if ($policies_result && mysqli_num_rows($policies_result) > 0): ?>
                    <?php while ($policy = mysqli_fetch_assoc($policies_result)): ?>
                        <ul>
                            <li>Yuran Pendaftaran Anggota: <?= htmlspecialchars($policy['p_memberRegFee']) ?></li>
                            <li>Modal Syer Minimum: <?= htmlspecialchars($policy['p_minShareCapital']) ?></li>
                            <li>Yuran Modal Minimum: <?= htmlspecialchars($policy['p_minFeeCapital']) ?></li>
                            <li>Yuran Tetap Minimum: <?= htmlspecialchars($policy['p_minFixedSaving']) ?></li>
                            <li>Simpanan Tetap Minimum: <?= htmlspecialchars($policy['p_minMemberFund']) ?></li>
                            <li>Tabung Kebajikan Minimum: <?= htmlspecialchars($policy['p_minMemberSaving']) ?></li>
                            <li>Jumlah Pembiayaan Maksimum: <?= htmlspecialchars($policy['p_maxFinancingAmt']) ?></li>
                            <li>Kadar Keuntungan: <?= htmlspecialchars($policy['p_profitRate']) ?></li>
                        </ul>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tiada polisi dikemas kini untuk tempoh yang dipilih.</p>
                <?php endif; ?>

                <?php
                $net_profit = $transaction_total - $total_approved_loans;
                ?>
                <p><strong>6. Prestasi Kewangan</strong></p>
                <p>Jumlah Pendapatan untuk Tempoh: RM <?= number_format($transaction_total, 2) ?></p>
                <p>Jumlah Perbelanjaan untuk Tempoh: RM <?= number_format($total_approved_loans, 2) ?></p>
                <p>Keuntungan Bersih untuk Tempoh: RM <?= number_format($net_profit, 2) ?></p>
                </div>
              </div>
              <?php else: ?>
                <p>Sila pilih bulan dan/atau tahun untuk melihat laporan.</p>
              <?php endif; ?>
        </div>
    </fieldset>
</form>

<script>
function saveRetrievalLog(month, year, adminID) {

    var formData = new FormData();
    formData.append('month', month);
    formData.append('year', year);

    
    fetch('save_report_log.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            alert('Log berjaya disimpan');
            window.location.reload();
        } else {
            alert('Error menyimpan log');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error menyimpan log');
    });
}

document.querySelector('form').addEventListener('submit', function(e) {
    document.getElementById('loading').style.display = 'block';
});

</script>

</div>
</body>
</html>