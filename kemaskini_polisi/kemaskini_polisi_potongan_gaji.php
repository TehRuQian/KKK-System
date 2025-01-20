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
  <h2>Kemaskini Polisi Potongan Gaji</h2>
  <form method="POST" action="kemaskini_polisi_potongan_gaji_process.php">
  <!-- Simpanan Tetap -->
    <div>
      <label class="form-label mt-4">Simpanan Tetap</label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_salaryDeductionForSaving" value="<?php echo htmlspecialchars($policy['p_salaryDeductionForSaving']); ?>">
          <span class="input-group-text">.00</span>
        </div>
    </div>

    <!-- Sumbangan Tabung Kebajikan -->
    <div>
      <label class="form-label mt-4">Sumbangan Tabung Kebajikan</label>
        <div class="input-group mb-3">
        <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_salaryDeductionForMemberFund" value="<?php echo htmlspecialchars($policy['p_salaryDeductionForMemberFund']); ?>">
          <span class="input-group-text">.00</span>
        </div>
    </div>

    <div style="display: flex; gap: 10px; justify-content: center;">
        <button type="button" class="btn btn-primary" onclick="window.location.href='kemaskini_polisi.php'">Kembali</button>
        <button type="submit" class="btn btn-primary">Kemaskini</button>
    </div>
  </form>
  <br>
</div>
