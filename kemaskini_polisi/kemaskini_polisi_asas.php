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
  <h2>Kemaskini Polisi Asas Pemohonan Anggota</h2>
  <form method="POST" action="kemaskini_polisi_asas_process.php">
  <!-- Fee Masuk -->
    <div>
      <label class="form-label mt-4">Fee Masuk</label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_memberRegFee" value="<?php echo htmlspecialchars($policy['p_memberRegFee']); ?>">
          <span class="input-group-text">.00</span>
        </div>
    </div>

    <!-- Modah Syer Minimum -->
    <div>
      <label class="form-label mt-4">Modah Syer Minimum</label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_minShareCapital" value="<?php echo htmlspecialchars($policy['p_minShareCapital']); ?>">
          <span class="input-group-text">.00</span>
        </div>
    </div>

    <!-- Modal Yuran Minimum -->
    <div>
      <label class="form-label mt-4">Modal Yuran Minimum</label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_minFeeCapital" value="<?php echo htmlspecialchars($policy['p_minFeeCapital']); ?>">
          <span class="input-group-text">.00</span>
        </div>
    </div>

    <!-- Wang Deposit Anggota Minimum -->
    <div>
      <label class="form-label mt-4">Wang Deposit Anggota Minimum</label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_minFixedSaving" value="<?php echo htmlspecialchars($policy['p_minFixedSaving']); ?>">
          <span class="input-group-text">.00</span>
        </div>
    </div>

    <!-- Sumbangan Tabung Kebajikan Minimum -->
    <div>
      <label class="form-label mt-4">Sumbangan Tabung Kebajikan Minimum</label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_minMemberFund" value="<?php echo htmlspecialchars($policy['p_minMemberFund']); ?>">
          <span class="input-group-text">.00</span>
        </div>
    </div>

    <!-- Simpanan Tetap Minimum -->
    <div>
      <label class="form-label mt-4">Simpanan Tetap Minimum</label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_minMemberSaving" value="<?php echo htmlspecialchars($policy['p_minMemberFund']); ?>">
          <span class="input-group-text">.00</span>
        </div>
    </div>

    <!-- Lain-lain -->
    <div>
      <label class="form-label mt-4">Lain-lain</label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input type="text" class="form-control" name="f_minOtherFees" value="<?php echo htmlspecialchars($policy['p_minOtherFees']); ?>">
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
