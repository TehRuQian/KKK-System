<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../header_admin.php';
include '../db_connect.php';

$uid = $_SESSION['u_id'];

$bulan = isset($_POST['Bulan']) ? $_POST['Bulan'] : '';
$tahun = isset($_POST['Tahun']) ? $_POST['Tahun'] : '';

//report data
$sql = "SELECT r.*, m.rm_desc FROM tb_reportretrievallog r 
        JOIN tb_rmonth m ON r.r_month = m.rm_id";

if ($bulan && $tahun) {
    $sql .= " WHERE r.r_month = '$bulan' AND r.r_year = '$tahun'";
}

$result = mysqli_query($con, $sql);


$report = null; 
if (isset($_POST['r_month']) && isset($_POST['r_year'])) {
    $month = $_POST['r_month'];
    $year = $_POST['r_year'];

   
    $report_sql = "SELECT r.*, m.rm_desc FROM tb_reportretrievallog r 
                   JOIN tb_rmonth m ON r.r_month = m.rm_id 
                   WHERE r.r_month = '$month' AND r.r_year = '$year'";
    $report_result = mysqli_query($con, $report_sql);
    $report = mysqli_fetch_assoc($report_result);
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


if ($bulan && $tahun) {
    //member data
    $member_sql = "SELECT COUNT(*) AS member_count FROM tb_member WHERE MONTH(m_applicationDate) = '$bulan' AND YEAR(m_applicationDate) = '$tahun'";
    $member_result = mysqli_query($con, $member_sql);
    if ($member_result) {
        $member_data = mysqli_fetch_assoc($member_result);
        $member_count = $member_data['member_count'];
    }

    //member_status
    $status_sql = "SELECT m_status, COUNT(*) AS status_count FROM tb_member 
    WHERE MONTH(m_applicationDate) = '$bulan' AND YEAR(m_applicationDate) = '$tahun' 
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

    //Laon data
    $loan_sql = "SELECT COUNT(*) AS loan_count FROM tb_loan WHERE MONTH(l_applicationDate) = '$bulan' AND YEAR(l_applicationDate) = '$tahun'";
    $loan_result = mysqli_query($con, $loan_sql);
    if ($loan_result) {
        $loan_data = mysqli_fetch_assoc($loan_result);
        $loan_count = $loan_data['loan_count'];
    }

    //loan status
    $loan_status_sql = "SELECT l_status, COUNT(*) AS loan_status_count,
                               SUM(CASE WHEN l_status = 3 THEN l_appliedLoan ELSE 0 END) AS total_approved_loans  
                        FROM tb_loan 
                        WHERE MONTH(l_applicationDate) = '$bulan' AND YEAR(l_applicationDate) = '$tahun' 
                        GROUP BY l_status";
    $loan_status_result = mysqli_query($con, $loan_status_sql);

    if ($loan_status_result) {
    while ($loan_status_row = mysqli_fetch_assoc($loan_status_result)) {
    switch ($loan_status_row['l_status']) {
      case 1: $loan_pending = $loan_status_row['loan_status_count']; break;
      case 2: $loan_rejected = $loan_status_row['loan_status_count']; break;
      case 3: $loan_approved = $loan_status_row['loan_status_count']; 
              $total_approved_loans = $loan_status_row['total_approved_loans'];
              break;
        } 
      }
    }

    //loan type
    $loan_type_sql = "SELECT l_loanType, COUNT(*) AS loan_type_count FROM tb_loan 
    WHERE MONTH(l_applicationDate) = '$bulan' AND YEAR(l_applicationDate) = '$tahun' 
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
    $transactions_sql = "SELECT COUNT(*) AS transaction_count, SUM(t_transactionAmt) AS transaction_total
                         FROM tb_transaction 
                         WHERE MONTH(t_transactionDate) = '$bulan' AND YEAR(t_transactionDate) = '$tahun'";
    $transactions_result = mysqli_query($con, $transactions_sql);

    if ($transactions_result && mysqli_num_rows($transactions_result) > 0) {
    $transaction_data = mysqli_fetch_assoc($transactions_result);
    $transaction_count = $transaction_data['transaction_count'] ?? 0;
    $transaction_total = $transaction_data['transaction_total'] ?? 0;
    } else {
      $transaction_total = 0;
    }

    //Polisi
    $policies_sql = "SELECT * FROM tb_policies WHERE MONTH(p_dateUpdated) = '$bulan' AND YEAR(p_dateUpdated) = '$tahun'";
    $policies_result = mysqli_query($con, $policies_sql);

  }


?>

<form method="post" action="">
  <fieldset>
    <div class="container">
      <div class="row g-2 align-items-center">
        <div class="col-auto">
          <label class="form-label mt-4">Bulan</label>
          <select name="Bulan" class="form-select" id="Bulan" style="width: 20ch;" required>
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
          <input type="text" name="Tahun" class="form-control" maxlength="4" placeholder="Contoh: 2024" required style="width: 20ch;" value="<?= $tahun ?>">    
        </div>
        <div class="col-auto" style="padding-top: 60px;">
          <button type="submit" class="btn btn-primary" >Tapis</button>
        </div>
      </div>
    
      <br><br>
      <div class="jumbotron">
        <div class="card mb-3">
          <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
            Papan Pemuka Laporan
          </div>
          <div class="card-body">
            <table class="table table-hover align-middle">
              <thead class="table-hover">
                <tr>
                  <th>No.</th>
                  <th>Laporan</th>
                  <th>Disahkan oleh</th>
                  <th>Tarikh</th>
                  <th>Butiran</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $count = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($report_row = mysqli_fetch_assoc($result)) {
                        $laporanID = "Laporan " . $report_row['rm_desc'] . " " . $report_row['r_year'];
                        echo "<tr>";
                        echo "<td>{$count}</td>";
                        echo "<td>{$laporanID}</td>";
                        echo "<td>{$report_row['r_adminID']}</td>";
                        echo "<td>{$report_row['r_retrievalDate']}</td>";
                        echo "<td>
                                <form action='' method='post'>
                                    <input type='hidden' name='r_month' value='{$report_row['r_month']}'>
                                    <input type='hidden' name='r_year' value='{$report_row['r_year']}'>
                                    <button type='submit' class='btn btn-info'>Lihat</button>
                                </form>
                             </td>";
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Tiada laporan untuk dipaparkan untuk bulan yang dipilih.</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <?php 
      
      if ($report) {
          echo "<div class='report-details'>";
                //title
          echo "<h3><strong>Laporan Kewangan untuk " . $report['rm_desc'] . " " . $report['r_year'] . "</strong></h3>";
          echo "<h4>Disediakan oleh: Admin [" . $report['r_adminID'] . "]</h4>";
          echo "<h4>Tarikh Penjanaan Laporan:  " . $report['r_retrievalDate'] . "</h4>";

                //Ringkasan
          echo "<p><strong>1. Ringkasan Eksekutif</strong></p>";
          echo "<p>Laporan ini memberikan analisis terperinci mengenai prestasi kewangan syarikat untuk tempoh  " . $report['rm_desc'] . " " . $report['r_year'] . " 
                   , termasuk metrik utama seperti permohonan ahli, permohonan pinjaman, rekod transaksi dan kesihatan kewangan keseluruhan.</p>";

                //Pemohonan ahli
          echo "<p><strong>2. Gambaran Keseluruhan Permohonan Ahli</strong></p>";
          echo "<p>Jumlah Permohonan Ahli Baru: $member_count</p>";
          echo "<p>Permohonan Mengikut Status:</p>";
          echo "<ul>";
          echo "<li>Diluluskan: $member_approved</li>";
          echo "<li>Sedang Diproses: $member_pending</li>";
          echo "<li>Ditolak: $member_rejected</li>";
          echo "</ul>";

                //Loan data
          echo "<p><strong>3. Gambaran Keseluruhan Permohonan Pinjaman</strong></p>";
          echo "<p>Jumlah Permohonan Pinjaman Baru: $loan_count </p>";
          echo "<p>Permohonan Mengikut Status:</p>";
          echo "<ul>";
          echo "<li>Diluluskan: $loan_approved</li>";
          echo "<li>Sedang Diproses: $loan_pending</li>";
          echo "<li>Ditolak: $loan_rejected</li>";
          echo "</ul>";
              // Jenis
          echo "<p>Permohonan Pinjaman Mengikut Jenis:</p>";
          echo "<ul>";
          echo "<li>Al-Bai: $alBai</li>";
          echo "<li>Al-Innah: $alInnah</li>";
          echo "<li>Baik Pulih Kenderaan: $baikPulihKenderaan</li>";
          echo "<li>Road Tax dan Insurans: $roadTax</li>";
          echo "<li>Khas: $khas</li>";
          echo "<li>Karnival Musim Istimewa: $karnival</li>";
          echo "<li>Al-Qadrul Hassan: $alQadrul</li>";
          echo "</ul>";

          //Transaksi
          echo "<p><strong>4. Prestasi Transaksi</strong></p>";
          echo "<p>Jumlah Transaksi: $transaction_count</p>";
          echo "<p>Jumlah Amaun Transaksi: RM " . number_format($transaction_total, 2) . "</p>";

          //polisi
          echo "<p><strong>5. Maklumat Polisi</strong></p>";
          if ($policies_result) {
            while ($policy = mysqli_fetch_assoc($policies_result)) {
                echo "<ul>";
                echo "<li>Yuran Pendaftaran Anggota: " . htmlspecialchars($policy['p_memberRegFee']) . "</li>";  
                echo "<li>Modal Syer Minimum: " . htmlspecialchars($policy['p_minShareCapital']) . "</li>";  
                echo "<li>Yuran Modal Minimum: " . htmlspecialchars($policy['p_minFeeCapital']) . "</li>";  
                echo "<li>Yuran Tetap Minimum: " . htmlspecialchars($policy['p_minFixedSaving']) . "</li>";  
                echo "<li>Simpanan Tetap Minimum: " . htmlspecialchars($policy['p_minMemberFund']) . "</li>";  
                echo "<li>Tabung Kebajikan Minimum: " . htmlspecialchars($policy['p_minMemberSaving']) . "</li>";  
                echo "<li>Jumlah Pembiayaan Maksimum: " . htmlspecialchars($policy['p_maxFinancingAmt']) . "</li>";  
                echo "<li>Kadar Keuntungan: " . htmlspecialchars($policy['p_profitRate']) . "</li>";  
                echo "</ul>";
            }
          } else {
              echo "<p>Tiada polisi dikemas kini untuk tempoh yang dipilih.</p>";
          }

          //jumlah keseluruhan
          $net_profit = $transaction_total - $total_approved_loans;

          echo "<p><strong>6. Prestasi Kewangan</strong></p>";
          echo "<p>Jumlah Pendapatan untuk Tempoh: RM " . number_format($transaction_total, 2) . "</p>";
          echo "<p>Jumlah Perbelanjaan untuk Tempoh: RM " . number_format($total_approved_loans, 2) . "</p>";
          echo "<p>Keuntungan Bersih untuk Tempoh: RM " . number_format($net_profit, 2) . "</p>";


          echo "</div><hr>";
      }
      ?>

    </div>
  </fieldset>
</form>

</div>
</body>
</html>  
