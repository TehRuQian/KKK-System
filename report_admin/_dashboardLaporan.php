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
$member_active = 0;
$member_stop = 0;
$member_retire = 0;
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
        $new_member_status = $member_approved + $member_pending + $member_rejected;
    }
}

$status_member_sql = "SELECT m_status, COUNT(*) AS status_count FROM tb_member 
               $where_clause 
               GROUP BY m_status";
$status_member_result = mysqli_query($con, $status_member_sql);

if ($status_member_result) {
    while ($status_row = mysqli_fetch_assoc($status_member_result)) {
        switch ($status_row['m_status']) {
            case 3: $member_active = $status_row['status_count']; break;
            case 5: $member_stop = $status_row['status_count']; break;
            case 6: $member_retire = $status_row['status_count']; break;
        }
        $member_status = $member_active + $member_stop + $member_retire;
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
            case 4:
                $loan_completed = $loan_status_row['loan_status_count'];
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
$policies_sql = "SELECT * FROM tb_policies $policies_where ORDER BY p_dateUpdated DESC LIMIT 1";
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
                        <a href="laporan_kewangan.php?bulan=<?= urlencode($bulan) ?>&tahun=<?= urlencode($tahun) ?>" 
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
                <p>Jumlah Permohonan Ahli Baru: <?= $new_member_status ?></p>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Status</th>
                      <th>Bilangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Diluluskan</td>
                      <td><?= $member_approved ?></td>
                    </tr>
                    <tr>
                      <td>Sedang Diproses</td>
                      <td><?= $member_pending ?></td>
                    </tr>
                    <tr>
                      <td>Ditolak</td>
                      <td><?= $member_rejected ?></td>
                    </tr>
                  </tbody>
                </table>

                <p><strong>3. Gambaran Keseluruhan Status Ahli</strong></p>
                <p>Jumlah Status Ahli: <?= $member_status ?></p>
                <table class="table table-hover">
                <thead>
                    <tr>
                    <th>Status</th>
                    <th>Bilangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>Aktif</td>
                    <td><?= $member_active ?></td>
                    </tr>
                    <tr>
                    <td>Berhenti</td>
                    <td><?= $member_stop ?></td>
                    </tr>
                    <tr>
                    <td>Pencen</td>
                    <td><?= $member_retire ?></td>
                    </tr>
                </tbody>
                </table>

                <p><strong>4. Gambaran Keseluruhan Permohonan Pinjaman</strong></p>
                <p>Jumlah Permohonan Pinjaman Baru: <?= $loan_count ?></p>
                <p>Permohonan Mengikut Status:</p>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Status</th>
                      <th>Bilangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Diluluskan</td>
                      <td><?= $loan_approved ?></td>
                    </tr>
                    <tr>
                      <td>Sedang Diproses</td>
                      <td><?= $loan_pending ?></td>
                    </tr>
                    <tr>
                      <td>Ditolak</td>
                      <td><?= $loan_rejected ?></td>
                    </tr>
                    <tr>
                      <td>Dijelaskan</td>
                      <td><?= $loan_completed ?? 0 ?></td>
                    </tr>
                  </tbody>
                </table>

                <p>Permohonan Pinjaman Mengikut Jenis:</p>
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Status</th>
                      <th>Bilangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Al-Bai</td>
                      <td><?= $alBai ?></td>
                    </tr>
                    <tr>
                      <td>Al-Innah</td>
                      <td><?= $alInnah ?></td>
                    </tr>
                    <tr>
                      <td>Baik Pulih Kenderaan</td>
                      <td><?= $baikPulihKenderaan ?></td>
                    </tr>
                    <tr>
                      <td>Road Tax dan Insurans</td>
                      <td><?= $roadTax ?></td>
                    </tr>
                    <tr>
                      <td>Khas</td>
                      <td><?= $khas ?></td>
                    </tr>
                    <tr>
                      <td>Karnival Musim Istimewa</td>
                      <td><?= $karnival ?></td>
                    </tr>
                    <tr>
                      <td>Al-Qadrul Hassan</td>
                      <td><?= $alQadrul ?></td>
                    </tr>
                  </tbody>
                </table>

                <p><strong>5. Prestasi Transaksi</strong></p>
                <table class="table table-hover">
                  <tbody>
                    <tr>
                      <td>Jumlah Transaksi</td>
                      <td><?= $transaction_count ?></td>
                    </tr>
                    <tr>
                      <td>Jumlah Amaun Transaksi</td>
                      <td>RM <?= number_format($transaction_total, 2) ?></td>
                    </tr>
                  </tbody>
                </table>

                <p><strong>6. Maklumat Polisi</strong></p>
                <?php if ($policies_result && mysqli_num_rows($policies_result) > 0): ?>
                    <?php $policy = mysqli_fetch_assoc($policies_result); ?>
                    <p>Polisi Asas Permohonan Anggota</p>
                    <table class="table table-hover">
                      <tbody>
                        <tr>
                          <td scope="row">Fee Masuk</td>
                          <td><?php echo "RM" . number_format($policy['p_memberRegFee'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Fee Masuk Anggota yang Pernah Menjadi Anggota</td>
                          <td><?php echo "RM" . number_format($policy['p_returningMemberRegFee'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Modah Syer Minimum</td>
                          <td><?php echo "RM" . number_format($policy['p_minShareCapital'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Modal Yuran Minimum</td>
                          <td><?php echo "RM" . number_format($policy['p_minFeeCapital'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Wang Deposit Anggota Minimum</td>
                          <td><?php echo "RM" . number_format($policy['p_minMemberSaving'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Sumbangan Tabung Kebajikan Minimum</td>
                          <td><?php echo "RM" . number_format($policy['p_minMemberFund'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Simpanan Tetap Minimum</td>
                          <td><?php echo "RM" . number_format($policy['p_minFixedSaving'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Lain-lain</td>
                          <td><?php echo "RM" . number_format($policy['p_minOtherFees'], 2); ?></td>
                        </tr>
                      </tbody>
                    </table>
                  
                    <p>Polisi Permohonan Pembiayaan</p>
                    <table class="table table-hover">
                      <tbody>
                        <tr>
                          <td scope="row">Modal Syer Minimum Peminjam</td>
                          <td>RM <?php echo number_format($policy['p_minShareCapitalForLoan'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Tempoh Ansuran Maksima</td>
                          <td><?php echo $policy['p_maxInstallmentPeriod'] . " tahun"; ?></td>
                        </tr>
                      </tbody>
                    </table>

                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Jenis Pembiayaan</th>
                          <th>Kadar Keuntungan</th>
                          <th>Pembiayaan Maksima</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Al-Bai</td>
                          <td><?php echo number_format($policy['p_rateAlBai'],2) ?>%</td>
                          <td>RM <?php echo number_format($policy['p_maxAlBai'], 2); ?></td>
                        <tr>
                        <tr>
                          <td scope="row">Al-Innah</td>
                          <td><?php echo number_format($policy['p_rateAlInnah'], 2) ?>%</td>
                          <td>RM <?php echo number_format($policy['p_maxAlInnah'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Baik Pulih Kenderaan</td>
                          <td><?php echo number_format($policy['p_rateBPulihKenderaan'], 2) ?>%</td>
                          <td>RM <?php echo number_format($policy['p_maxBPulihKenderaan'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Cukai Jalan dan Insurans</td>
                          <td><?php echo number_format($policy['p_rateCukaiJalanInsurans'], 2) ?>%</td>
                          <td>RM <?php echo number_format($policy['p_maxCukaiJalanInsurans'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Skim Khas</td>
                          <td><?php echo number_format($policy['p_rateKhas'], 2) ?>%</td>
                          <td>RM <?php echo number_format($policy['p_maxKhas'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Karnival Musim Istimewa</td>
                          <td><?php echo number_format($policy['p_rateKarnivalMusim'], 2) ?>%</td>
                          <td>RM <?php echo number_format($policy['p_maxKarnivalMusim'], 2); ?></td>
                        </tr>
                        <tr>
                          <td scope="row">Al-Qadrul Hassan</td>
                          <td><?php echo number_format($policy['p_rateAlQadrulHassan'], 2) ?>%</td>
                          <td>RM <?php echo number_format($policy['p_maxAlQadrulHassan'], 2); ?></td>
                        </tr>
                    </tbody>
                  </table>
                <?php else: ?>
                    <p>Tiada polisi terkini tersedia.</p>
                <?php endif; ?>

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