<!DOCTYPE html>
<!--  -->
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>KKK Online System</title>
      <link href="../bootstrap.css" rel="stylesheet">
      <link href="../img/kkk_logo.png" rel="icon" type="/image/x-icon">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>

  <style>
  ul li:hover{
    background-color: #5AB0FF;
    border-radius: 10px;
  }
  </style>

  <body>

    <!-- First Row Nav Bar -->
    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="../admin_main/admin.php">
            <img src="../img/kkk_logo_cropped.png" alt="kkk" style="height: 30px;">
        </a>
        <a href="../logout.php" class="btn btn-dark d-flex">Log Keluar</a>
      </div>
    </nav>

<!-- Second Row Nav Bar -->
<nav class="navbar navbar-expand-lg" data-bs-theme="light" style="background-color:#82C8FF; position: sticky; top: 0; z-index: 1030;">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor02">
      <ul class="navbar-nav me-auto">
        <!-- Permohonan Dropdown -->
        <li class="nav-item dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'member_approval.php' || basename($_SERVER['PHP_SELF']) == 'loan_approval.php' || basename($_SERVER['PHP_SELF']) == 'approval_berhenti.php') ? 'active' : ''; ?>">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Permohonan</a>
          <div class="dropdown-menu">
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'member_approval.php') ? 'active' : ''; ?>" href="../member_approval/member_approval.php">Permohonan Anggota</a>
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'loan_approval.php') ? 'active' : ''; ?>" href="../loan_approval/loan_approval.php">Permohonan Pinjaman</a>
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'berhenti_approval.php') ? 'active' : ''; ?>" href="../berhenti_approval/berhenti_approval.php">Permohonan Berhenti Menjadi Anggota</a>
          </div>
        </li>
        
        <!-- Senarai Anggota Dropdown -->
        <li class="nav-item dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'view_member_list.php' || basename($_SERVER['PHP_SELF']) == 'view_loan_list.php') ? 'active' : ''; ?>">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Senarai Anggota</a>
          <div class="dropdown-menu">
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'view_member_list.php') ? 'active' : ''; ?>" href="../view_list/view_member_list.php">Senarai Anggota Semasa</a>
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'view_past_member_list.php') ? 'active' : ''; ?>" href="../view_list/view_past_member_list.php">Senarai Permohonan Lepas</a>
          </div>
        </li>

        <!-- Senarai Dropdown -->
        <li class="nav-item dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'view_member_list.php' || basename($_SERVER['PHP_SELF']) == 'view_loan_list.php') ? 'active' : ''; ?>">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Senarai Peminjam</a>
          <div class="dropdown-menu">
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'view_loan_list.php') ? 'active' : ''; ?>" href="../view_list/view_loan_list.php">Senarai Peminjam</a>
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'view_pass_loan_list.php') ? 'active' : ''; ?>" href="../view_list/view_pass_loan_list.php">Senarai Peminjam Lepas</a>
          </div>
        </li>        
        
        <!-- Transaksi Dropdown -->
        <li class="nav-item dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'potongan_gaji.php' || basename($_SERVER['PHP_SELF']) == 'transaksi_lain.php' || basename($_SERVER['PHP_SELF']) == 'sejarah_transaksi.php') ? 'active' : ''; ?>">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Transaksi</a>
          <div class="dropdown-menu">
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'potongan_gaji.php') ? 'active' : ''; ?>" href="../transaksi/potongan_gaji.php">Potongan Gaji</a>
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'transaksi_lain.php') ? 'active' : ''; ?>" href="../transaksi/transaksi_lain.php">Lain-lain</a>
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'sejarah_transaksi.php') ? 'active' : ''; ?>" href="../transaksi/sejarah_transaksi.php">Sejarah Transaksi</a>
          </div>
        </li>

        <li class="nav-item dropdown <?php echo (basename($_SERVER['PHP_SELF']) == 'kemaskini_polisi.php' || basename($_SERVER['PHP_SELF']) == 'sejarah_polisi.php') ? 'active' : ''; ?>">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Polisi</a>
          <div class="dropdown-menu">
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'kemaskini_polisi.php') ? 'active' : ''; ?>" href="../kemaskini_polisi/kemaskini_polisi.php">Potongan Gaji</a>
            <a class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'sejarah_polisi.php') ? 'active' : ''; ?>" href="../kemaskini_polisi/sejarah_polisi.php">Sejarah Polisi</a>
          </div>
        </li>
        
        <!-- Other Nav Items -->
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'kemaskini_iklan.php') ? 'active' : ''; ?>"><a class="nav-link" href="../kemaskini_iklan/kemaskini_iklan.php">Kemaskini Iklan</a></li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'view_feedback_admin.php') ? 'active' : ''; ?>"><a class="nav-link" href="../feedback/view_feedback_admin.php">Maklum Balas</a></li>
        <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == '_dashboardLaporan.php') ? 'active' : ''; ?>"><a class="nav-link" href="../report_admin/_dashboardLaporan.php">Laporan</a></li>
      </ul>
    </div>
  </div>
</nav>

    <script src="https://kit.fontawesome.com/7e2061d7a1.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

  