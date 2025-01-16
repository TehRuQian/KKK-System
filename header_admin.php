<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>KKK Online System</title>
      <link href="../bootstrap.css" rel="stylesheet">
      <link href="../img/kkk_logo.png" rel="icon" type="/image/x-icon">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
    <nav class="navbar navbar-expand-lg" data-bs-theme="light" style="background-color:#82C8FF; position: sticky; top: 0; z-index: 1030; "">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarColor02">
          <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="../member_approval/member_approval.php">Permohonan Anggota</a></li>
            <li class="nav-item"><a class="nav-link" href="../loan_approval/loan_approval.php">Permohonan Pinjaman</a></li>
            <li class="nav-item"><a class="nav-link" href="../view_list/view_member_list.php">Senarai Anggota</a></li>
            <li class="nav-item"><a class="nav-link" href="../view_list/view_loan_list.php">Senarai Peminjam</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Transaksi</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="../transaksi/potongan_gaji.php">Potongan Gaji</a>
                <a class="dropdown-item" href="../transaksi/transaksi_lain.php">Lain-lain</a>
              </div>
            </li>
            <li class="nav-item"><a class="nav-link" href="../kemaskini_iklan/kemaskini_iklan.php">Kemaskini Iklan</a></li>
            <li class="nav-item"><a class="nav-link" href="../report_admin/_dashboardLaporan.php">Laporan</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>