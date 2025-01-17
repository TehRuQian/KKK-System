<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

// Check if the 'status' parameter is present in the URL
if (isset($_GET['status']) && $_GET['status'] == 'success') {
  echo '<script>alert("Anda telah berjaya disimpan.");</script>';
}

// Guarantor data 
if (!isset($_SESSION['guarantorID1']) || !isset($_SESSION['guarantorID2'])) {
  echo "<script>
          alert('Sila simpan maklumat Butir-Butir Penjamin.');
          window.location.href = 'd_penjamin.php'; 
        </script>";
        exit();
}

$guarantorID1 = $_SESSION['guarantorID1'];
$guarantorID2 = $_SESSION['guarantorID2'];
?>

<head>
    <style>
        .row-spacing {
            margin-bottom: 4rem;
        }

        a:hover {}

        a:active,
        a.active {
            color: black !important;
        }

        a {
            text-decoration: none;
            margin-bottom: 0.5rem;
        }

        .container-fluid {
            padding-left: 0;   
            padding-right: 0;
        }

        .is-invalid {
            border: 2px solid red;
        }
        
        .sidebar {
          position: fixed;    
            top: 60px;           
            left: 0;        
            width: 16.666667%; 
            min-height: 100vh;  
            background-color: #9ccfff;
            padding-top: 20px;
            z-index: 1000;   
        }
        
         
        .sidebar .row {
            width: 100%;
            padding: 10px;
        }

        .sidebar a {
            display: block;
            width: 100%;
            text-decoration: none;
            margin-bottom: 0.5rem;
        }

        .sidebar hr {
            width: 100%;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .row {
            margin: 0;    
        }

        .col-2, .col-10 {
            padding: 0;    
        }

        .main-content {
            margin-left: 16.666667%; 
        }

        footer {
        width: calc(100% - 16.666667%) !important; 
        margin-left: 16.666667% !important; 
        padding: 0 20px !important;
    }

        
        footer .container,
        footer .container-fluid {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
          
    </style>
</head>

<div class="container-fluid">
  <div class="row">
    <div class="col-2 sidebar">
        <div class="row">
          <a href="a_pinjaman.php" class="text-center"><br>Butir-Butir Pembiayaan</a>
          <hr>
        </div>
        <div class="row">
          <a href="b_butir_peribadi.php" class="text-center">Butir-Butir Peribadi Pemohon</a>
          <hr>
        </div>
        <div class="row">
          <a href="c_pengakuan_pemohon.php" class="text-center">Pengakuan Pemohon<br></a>
          <hr>
        </div>
        <div class="row">
          <a href="d_penjamin.php" class="text-center">Butir-Butir Penjamin<br></a>
          <hr>
        </div>
        <div class="row">
          <a href="e_pengesahan_majikan.php" class="text-center">Pengesahan Majikan<br></a>
          <hr>
        </div>
        <div class="row">
          <a href="f_akuan_kebenaran.php" class="text-center active">Akuan Kebenaran<br></a>
          <hr>
        </div>
  </div>
</div>


<div class="col-10 main-content">

<form method = "post" action = "f_akuan_kebenaran_process.php">
  <fieldset>
    <!-- Akuan Kebenaran -->
    <div class="container">
      <br>
      <div class="jumbotron">
          <h2>Akuan Kebenaran</h2> 
        <div>
            <br>
            <label>Saya mengaku bahawa semua maklumat yang diberi adalah benar dan betul. Sekiranya saya didapati 
                   memberikan maklumat tidak benar atau palsu, saya boleh disabitkan kesalahan di bawah seksyen 193 Kanun 
                   Keseksaan (Akta 574) dan boleh dikenakan hukuman penjara selama tempoh yang boleh sampai tiga (3) tahun dan 
                   boleh juga dikenakan denda.</label>
        </div>

        <label style="display: flex; justify-content: center; align-items: center; font-size: 18px; cursor: pointer;">
            <input type="radio" name="setuju" value="setuju" required>
            Setuju
        </label>
    </hr>
      <hr class="my-4">
        <p class="lead">
        <!--button type="simpan" class="btn btn-primary" fdprocessedid="m3vqi">Simpan</button-->
        <button type="submit" class="btn btn-primary">Simpan</button>
        </p>
      </hr>

    </div>   
  </fieldset>
</form>
</div>



</body>
</html>

<?php include '../footer.php';?>