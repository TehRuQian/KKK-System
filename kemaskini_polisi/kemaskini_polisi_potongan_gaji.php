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
  <h2>Kemaskini Polisi Potongan Gaji</h2>
  <form method="POST" action="kemaskini_polisi_potongan_gaji_process.php">
  <!-- Simpanan Tetap -->
  <div class="d-flex">
    <div class="me-3">
      <label class="form-label mt-4">Simpanan Tetap</label>
      <div class="input-group mb-3">
        <span class="input-group-text">RM</span>
        <input type="number" min="0" step="1" class="form-control" name="f_salaryDeductionForSaving" value="<?php echo htmlspecialchars($policy['p_salaryDeductionForSaving']); ?>">
        <span class="input-group-text">.00</span>
      </div>
      <small>Potongan Gaji untuk anggota yang tidak membuat permintaan untuk perubahan potongan gaji.</small>
    </div>

    <div>
      <label class="form-label mt-4">Minimum Potongan Gaji Simpanan Tetap</label>
      <div class="input-group mb-3">
        <span class="input-group-text">RM</span>
        <input type="number" min="0" step="1" class="form-control" name="f_minSalaryDeductionForSaving" value="<?php echo htmlspecialchars($policy['p_minSalaryDeductionForSaving']); ?>">
        <span class="input-group-text">.00</span>
      </div>
      <small>Potongan Gaji Minimum untuk anggota yang ingin membuat perubahan untuk potongan gaji.</small>
    </div>
  </div>

    <!-- Sumbangan Tabung Kebajikan -->
    <div class="d-flex">
      <div class="me-3">
        <label class="form-label mt-4">Sumbangan Tabung Kebajikan</label>
          <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
            <input type="number" min="0" step="1" class="form-control" name="f_salaryDeductionForMemberFund" value="<?php echo htmlspecialchars($policy['p_salaryDeductionForMemberFund']); ?>">
            <span class="input-group-text">.00</span>
          </div>
          <small>Potongan Gaji untuk anggota yang tidak membuat permintaan untuk perubahan potongan gaji.</small>
      </div>
      <div>
        <label class="form-label mt-4">Minimum Potongan Gaji Sumbangan Tabung Kebajikan</label>
          <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
            <input type="number" min="0" step="1" class="form-control" name="f_minSalaryDeductionForMemberFund" value="<?php echo htmlspecialchars($policy['p_minSalaryDeductionForMemberFund']); ?>">
            <span class="input-group-text">.00</span>
          </div>
          <small>Potongan Gaji Minimum untuk anggota yang ingin membuat perubahan untuk potongan gaji.</small>
      </div>
    </div>

    <div>
        <label class="form-label mt-4">Hari Cutoff</label>
          <div class="input-group mb-3">
            <input type="number" min="1" max="31" step="1" class="form-control" name="f_cutOff" value="<?php echo htmlspecialchars($policy['p_cutOffDay']); ?>">
          </div>
          <small>Permohonan yang diluluskan sebelum hari ini akan dipotong gaji.</small>
      </div>

    <div style="display: flex; gap: 10px; justify-content: center;">
        <button type="button" class="btn btn-primary" onclick="window.location.href='kemaskini_polisi.php'">Kembali</button>
        <button type="submit" class="btn btn-primary">Kemaskini</button>
    </div>
  </form>
  <br>
</div>
