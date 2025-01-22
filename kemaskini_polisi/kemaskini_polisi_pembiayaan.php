<?php 
  include('../kkksession.php');
  if (!session_id()) {
      session_start();
  }

  if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
  }
  
  include '../header_admin.php';
  include '../db_connect.php';
  $admin_id = $_SESSION['u_id'];

  // Retrieve latest policy with newest ID
  $sql = "
    SELECT * FROM tb_policies
    ORDER BY p_policyID DESC
    LIMIT 1;";
  $result = mysqli_query($con, $sql);
  $policy = mysqli_fetch_assoc($result);
?>

<!-- Main Content -->
<div class="container">
  <h2>Kemaskini Polisi Permohonan Pembiayaan</h2>
  <form method="POST" action="kemaskini_polisi_pembiayaan_process.php" id="policyForm">
  
    <!-- Modal Syer Minimum Peminjam -->
    <div>
      <label class="form-label mt-4">Modal Syer Minimum Peminjam</label>
      <div class="input-group mb-3">
        <span class="input-group-text">RM</span>
        <input type="text" class="form-control" name="f_minShareCapitalForLoan" value="<?php echo htmlspecialchars($policy['p_minShareCapitalForLoan']); ?>" id="f_minShareCapitalForLoan">
        <span class="input-group-text">.00</span>
      </div>
    </div>

    <!-- Kadar Keuntungan -->
    <div>
      <label class="form-label mt-4">Kadar Keuntungan</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="f_profitRate" value="<?php echo htmlspecialchars($policy['p_profitRate']); ?>" id="f_profitRate">
        <span class="input-group-text">%</span>
      </div>
    </div>

    <!-- Tempoh Ansuran Maksima -->
    <div>
      <label class="form-label mt-4">Tempoh Ansuran Maksima</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="f_maxInstallmentPeriod" value="<?php echo htmlspecialchars($policy['p_maxInstallmentPeriod']); ?>" id="f_maxInstallmentPeriod">
        <span class="input-group-text">tahun</span>
      </div>
    </div>

    <!-- Jumlah Pembiayaan Maksima -->
    <div>
      <label class="form-label mt-4">Jumlah Pembiayaan Maksima</label>
      <div class="input-group mb-3">
        <span class="input-group-text">RM</span>
        <input type="text" class="form-control" name="f_maxFinancingAmt" value="<?php echo htmlspecialchars($policy['p_maxFinancingAmt']); ?>" id="f_maxFinancingAmt">
        <span class="input-group-text">.00</span>
      </div>
    </div>

    <!-- Jadual Pembayaran Balik Skim Al-Bai / Al-Innah -->
    <table class="table table-hover" style="margin: 0 auto; text-align: center;" id="paymentScheduleTable">
      <tr>
        <td scope="col">Kadar Keuntungan</td>
        <td scope="col" colspan="<?php echo $policy['p_maxInstallmentPeriod'] ?>" id="profitRateTable">
          <?php echo number_format($policy['p_profitRate'], 2) ?> %
        </td>
      </tr>
      <tr id="yearsRow"></tr>
      <tr id="monthsRow"></tr>
      <tr>
        <td scope="row">Jumlah Pembiayaan</td>
        <td colspan="<?php echo $policy['p_maxInstallmentPeriod'] ?>" id="financingRow">
          Ansuran Bulanan
        </td>
      </tr>
      <tbody id="paymentRows"></tbody>
    </table>
    <br>

    <div style="display: flex; gap: 10px; justify-content: center;">
      <button type="button" class="btn btn-primary" onclick="window.location.href='kemaskini_polisi.php'">Kembali</button>
      <button type="submit" class="btn btn-primary">Kemaskini</button>
    </div>
  </form>
  <br>
</div>

<script>
  // Function to update the payment schedule table dynamically
  function updateTable() {
    // Get values from form
    let profitRate = parseFloat(document.getElementById('f_profitRate').value) || <?php echo $policy['p_profitRate']; ?>;
    let maxInstallmentPeriod = parseInt(document.getElementById('f_maxInstallmentPeriod').value) || <?php echo $policy['p_maxInstallmentPeriod']; ?>;
    let maxFinancingAmt = parseFloat(document.getElementById('f_maxFinancingAmt').value) || <?php echo $policy['p_maxFinancingAmt']; ?>;

    // Update the Profit Rate
    document.getElementById('profitRateTable').innerText = profitRate.toFixed(2) + ' %';

    // Clear previous rows
    document.getElementById('yearsRow').innerHTML = '';
    document.getElementById('monthsRow').innerHTML = '';
    document.getElementById('paymentRows').innerHTML = '';

    // Create year and month rows dynamically
    let yearsHtml = '<td scope="row">Tempoh (Tahun)</td>';
    let monthsHtml = '<td scope="row">Tempoh (Bulan)</td>';
    for (let x = 1; x <= maxInstallmentPeriod; x++) {
      yearsHtml += `<td>${x}</td>`; 
      monthsHtml += `<td>${x * 12}</td>`; 
    }

    // Set the years and months rows in the table
    document.getElementById('yearsRow').innerHTML = yearsHtml;
    document.getElementById('monthsRow').innerHTML = monthsHtml;

    // Create financing rows
    let paymentRowsHtml = '';
    for (let x = 1000; x <= maxFinancingAmt; x += 1000) {
      let row = `<tr><td>${x.toFixed(2)}</td>`; // Financing amount row
      for (let y = 1; y <= maxInstallmentPeriod; y++) {
        let installment = (x * (1 + profitRate * y / 100)) / (y * 12);
        row += `<td>${installment.toFixed(2)}</td>`; // Installment calculation for each period
      }
      row += '</tr>';
      paymentRowsHtml += row;
    }
    // Set the financing rows in the table
    document.getElementById('paymentRows').innerHTML = paymentRowsHtml;
  }

  // Add event listeners to update the table whenever the input values change
  document.getElementById('f_profitRate').addEventListener('input', updateTable);
  document.getElementById('f_maxInstallmentPeriod').addEventListener('input', updateTable);
  document.getElementById('f_maxFinancingAmt').addEventListener('input', updateTable);

  // Initial table update
  updateTable();
</script>

