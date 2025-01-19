<?php 
  include('../kkksession.php');
  if (!session_id()) {
      session_start();
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
  <form method="POST" action="kemaskini_polisi_pembiayaan_process.php">
  <!-- Modal Syer Minimum Peminjam -->
    <div>
      <label class="form-label mt-4">Modal Syer Minimum Peminjam</label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_minShareCapitalForLoan" value="<?php echo htmlspecialchars($policy['p_minShareCapitalForLoan']); ?>">
          <span class="input-group-text">.00</span>
        </div>
    </div>

    <!-- Kadar Keuntungan -->
    <div>
      <label class="form-label mt-4">Kadar Keuntungan</label>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="f_profitRate" value="<?php echo htmlspecialchars($policy['p_profitRate']); ?>">
          <span class="input-group-text">%</span>
        </div>
    </div>

    <!-- Tempoh Ansuran Maksima -->
    <div>
      <label class="form-label mt-4">Tempoh Ansuran Maksima</label>
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="f_maxInstallmentPeriod" value="<?php echo htmlspecialchars($policy['p_maxInstallmentPeriod']); ?>">
          <span class="input-group-text">tahun</span>
        </div>
    </div>

    <!-- Jumlah Pembiayaan Maksima -->
    <div>
      <label class="form-label mt-4">Jumlah Pembiayaan Maksima</label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_maxFinancingAmt" value="<?php echo htmlspecialchars($policy['p_maxFinancingAmt']); ?>">
          <span class="input-group-text">.00</span>
        </div>
    </div>

    <!-- Jadual Pembayaran Balik Skim Al-Bai / Al-Innah -->
    <table class="table table-hover" style="margin: 0 auto; text-align: center;">
      <tr>
        <td scope="col">Kadar Keuntungan</td>
        <td scope="col" colspan="<?php echo $policy['p_maxInstallmentPeriod'] ?>">
          <?php echo number_format($policy['p_profitRate'], 2)?> %
        </td>
      </tr>
      <tr>
        <td scope="row">Tempoh (Tahun)</td>
        <?php
          for($x = 1; $x <= $policy['p_maxInstallmentPeriod']; $x++){
            echo "<td>$x</td>";
          }
        ?>
      </tr>
      <tr>
        <td scope="row">Tempoh (Bulan)</td>
        <?php
          for($x = 1; $x <= $policy['p_maxInstallmentPeriod']; $x++){
            echo "<td>" . ($x * 12) . "</td>";
          }
        ?>
      </tr>
      <tr>
        <td scope="row">Jumlah Pembiayaan</td>
        <td scope="col" colspan="<?php echo $policy['p_maxInstallmentPeriod'] ?>">
          Ansuran Bulanan
        </td>
      </tr>
      <?php
        for($x = 1000; $x <= $policy['p_maxFinancingAmt']; $x += 1000){
          echo "<tr>";
            echo "<td>". number_format($x, 2) ."</td>";
            for($y = 1; $y <= $policy['p_maxInstallmentPeriod']; $y++){
              echo "<td>";
                $installment = ($x * (1+ $policy['p_profitRate']*$y / 100)) / ($y * 12);
                echo number_format($installment, 2);
              echo "</td>";
            }
          echo "</tr>";
        }
      ?>
    </table>

    <div style="display: flex; gap: 10px; justify-content: center;">
        <button type="button" class="btn btn-primary" onclick="window.location.href='kemaskini_polisi.php'">Kembali</button>
        <button type="submit" class="btn btn-primary">Kemaskini</button>
    </div>
  </form>
</div>


