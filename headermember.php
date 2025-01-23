<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KKK Online System</title>
    <link href="../bootstrap.css" rel="stylesheet">
    <link href="../img/kkk_logo.png" rel="icon" type="/image/x-icon">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  </head>
<style>
  ul li:hover{
    background-color: #5AB0FF;
    border-radius: 10px;
  }
</style>
<body>

<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark" style="position: sticky; top: 0; z-index: 1030; ">
  <div class="container-fluid">
    <a class="navbar-brand" href="../member_main/member.php">
        <img src="../img/kkk_logo_cropped.png" alt="kkk" style="height: 30px;">
    </a>
     
    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active ms-2" href="../member_main/member.php">Laman Utama</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active ms-2" href="../update_member_profile/profilmember.php">Profil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active ms-2" href="../loan_application/dashboard_pinjaman.php">Pinjaman</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active ms-2" href="../feedback/track_feedback.php">Maklum Balas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active ms-2" href="../tarik_diri/submit_tarik_diri.php">Tarik Diri</a>
        </li>
        <li>
          <button onclick="window.location.href='../logout.php';" class="btn btn-dark">Log Keluar</button>
        </li>
      </ul>
    </div>
  </div>
</nav>


