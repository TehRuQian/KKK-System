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

  $sameRate = (
    $policy['p_rateAlBai'] == $policy['p_rateAlInnah'] 
    && $policy['p_rateAlBai'] == $policy['p_rateBPulihKenderaan'] 
    && $policy['p_rateAlBai'] == $policy['p_rateCukaiJalanInsurans']
    && $policy['p_rateAlBai'] == $policy['p_rateKhas']
    && $policy['p_rateAlBai'] == $policy['p_rateKarnivalMusim']
    && $policy['p_rateAlBai'] == $policy['p_rateAlQadrulHassan']);
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

    <!-- Tempoh Ansuran Maksima -->
    <div>
      <label class="form-label mt-4">Tempoh Ansuran Maksima</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="f_maxInstallmentPeriod" value="<?php echo htmlspecialchars($policy['p_maxInstallmentPeriod']); ?>" id="f_maxInstallmentPeriod">
        <span class="input-group-text">tahun</span>
      </div>
    </div>

    <h5>Kadar Keuntungan dan Pembiayaan Maksima</h5>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="f_sameRate" name="f_sameProfitRate" value="1" 
        <?php echo $sameRate ? 'checked' : ''; ?> 
        onclick="check()">
      <label class="form-check-label" for="f_sameRate">
        Kadar keuntungan sama untuk semua jenis pinjaman
      </label>
    </div>

    <table class="table table-hover">
      <thead>
        <tr>
          <th>Jenis Pinjaman</th>
          <th>Pembiayaan Maksima</th>
          <th>Kadar Keuntungan</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Al-Bai</td>
          <td>
            <div class="input-group mb-3">
              <span class="input-group-text">RM</span>
              <input type="text" class="form-control" name="f_maxAlBai" value="<?php echo htmlspecialchars($policy['p_maxAlBai']); ?>" id="f_maxAlBai">
              <span class="input-group-text">.00</span>
            </div>
          </td>
          <td>
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="f_rateAlBai" value="<?php echo htmlspecialchars($policy['p_rateAlBai']); ?>" id="f_rateAlBai">
              <span class="input-group-text">%</span>
            </div>
          </td>
        </tr>
        <!-- Repeat for other financing types (Al-Innah, Baik Pulih, etc.) -->
      </tbody>
    </table>

    <!-- Jadual Pembayaran Balik Skim Al-Bai / Al-Innah -->
    <table class="table table-hover" style="margin: 0 auto; text-align: center;" id="paymentScheduleTable">
      <tr>
        <td scope="col">Kadar Keuntungan</td>
        <td scope="col" colspan="<?php echo $policy['p_maxInstallmentPeriod'] ?>" id="profitRateTable">
          <?php echo number_format($policy['p_rateAlBai'], 2) ?> %
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
  function updateTable() {
    let profitRate = parseFloat(document.getElementById('f_rateAlBai').value) || <?php echo $policy['p_rateAlBai']; ?>;
    let maxInstallmentPeriod = parseInt(document.getElementById('f_maxInstallmentPeriod').value) || <?php echo $policy['p_maxInstallmentPeriod']; ?>;

    let maxAlBai = parseFloat(document.getElementById('f_maxAlBai').value) || <?php echo $policy['p_maxAlBai']; ?>;
    let maxAlInnah = parseFloat(document.getElementById('f_maxAlInnah').value) || <?php echo $policy['p_maxAlInnah']; ?>;
    let maxBPulihKenderaan = parseFloat(document.getElementById('f_maxBPulihKenderaan').value) || <?php echo $policy['p_maxBPulihKenderaan']; ?>;
    let maxCukaiJalanInsurans = parseFloat(document.getElementById('f_maxCukaiJalanInsurans').value) || <?php echo $policy['p_maxCukaiJalanInsurans']; ?>;
    let maxKhas = parseFloat(document.getElementById('f_maxKhas').value) || <?php echo $policy['p_maxKhas']; ?>;
    let maxKarnivalMusim = parseFloat(document.getElementById('f_maxKarnivalMusim').value) || <?php echo $policy['p_maxKarnivalMusim']; ?>;
    let maxAlQadrulHassan = parseFloat(document.getElementById('f_maxAlQadrulHassan').value) || <?php echo $policy['p_maxAlQadrulHassan']; ?>;

    let maxFinancingAmt = Math.max(
      maxAlBai,
      maxAlInnah,
      maxBPulihKenderaan,
      maxCukaiJalanInsurans,
      maxKhas,
      maxKarnivalMusim,
      maxAlQadrulHassan
    )

    document.getElementById('profitRateTable').innerText = profitRate.toFixed(2) + ' %';

    document.getElementById('yearsRow').innerHTML = '';
    document.getElementById('monthsRow').innerHTML = '';
    document.getElementById('paymentRows').innerHTML = '';

    let yearsHtml = '<td scope="row">Tempoh (Tahun)</td>';
    let monthsHtml = '<td scope="row">Tempoh (Bulan)</td>';
    for (let x = 1; x <= maxInstallmentPeriod; x++) {
      yearsHtml += `<td>${x}</td>`; 
      monthsHtml += `<td>${x * 12}</td>`; 
    }

    document.getElementById('yearsRow').innerHTML = yearsHtml;
    document.getElementById('monthsRow').innerHTML = monthsHtml;

    let paymentRowsHtml = '';
    for (let x = 1000; x <= maxFinancingAmt; x += 1000) {
      let row = `<tr><td>${x.toFixed(2)}</td>`;
      for (let y = 1; y <= maxInstallmentPeriod; y++) {
        let installment = (x * (1 + profitRate * y / 100)) / (y * 12);
        row += `<td>${installment.toFixed(2)}</td>`;
      }
      row += '</tr>';
      paymentRowsHtml += row;
    }
    document.getElementById('paymentRows').innerHTML = paymentRowsHtml;
  }

  document.getElementById('f_sameRate').addEventListener('input', updateTable);
  document.getElementById('f_maxInstallmentPeriod').addEventListener('input', updateTable);

  document.getElementById('f_maxAlBai').addEventListener('input', updateTable);
  document.getElementById('f_maxAlInnah').addEventListener('input', updateTable);
  document.getElementById('f_maxBPulihKenderaan').addEventListener('input', updateTable);
  document.getElementById('f_maxCukaiJalanInsurans').addEventListener('input', updateTable);
  document.getElementById('f_maxKhas').addEventListener('input', updateTable);
  document.getElementById('f_maxKarnivalMusim').addEventListener('input', updateTable);
  document.getElementById('f_maxAlQadrulHassan').addEventListener('input', updateTable);

  updateTable();

  function check() {
    var checkBox = document.getElementById("f_sameRate");
    var allInputs = [
        'f_maxAlBai', 'f_maxAlInnah', 'f_maxBPulihKenderaan', 
        'f_maxCukaiJalanInsurans', 'f_maxKhas', 'f_maxKarnivalMusim', 
        'f_maxAlQadrulHassan'
    ];

    if (checkBox.checked) {
        allInputs.forEach(id => {
            document.getElementById(id).style.display = "none";
        });
    } else {
        allInputs.forEach(id => {
            document.getElementById(id).style.display = "block";
        });
    }
  }
</script>
