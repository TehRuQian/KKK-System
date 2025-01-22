<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}
if ($_SESSION['u_type'] != 2) {
  header('Location: ../login.php');
  exit();
}

include '../headermember.php';
include '../db_connect.php';

// Check if the 'status' parameter is present in the URL
if (isset($_GET['status']) && $_GET['status'] == 'success') {
  echo '<script>
          Swal.fire({
              title: "Berjaya!",
              text: "Maklumat anda telah berjaya disimpan!",
              icon: "success",
              confirmButtonText: "OK"
          });
        </script>';
}

if (!isset($_SESSION['loanApplicationID'])) {
  echo "<script>
  Swal.fire({
    title: 'Peringatan',
    text: 'Sila simpan naklumat Butir-Butir Pembiayaan.',
    icon: 'warning',
    confirmButtonText: 'OK'
  }).then(() => {
    window.location.href = 'a_pinjaman.php';
  });
  </script>";
  exit();
}

$loanApplicationID = $_SESSION['loanApplicationID']; 

// Guarantor data
if (!isset($_SESSION['guarantorID1']) || !isset($_SESSION['guarantorID2'])) {
  echo "<script>
  Swal.fire({
    title: 'Peringatan',
    text: 'Sila simpan maklumat Butir-Butir Penjamin.',
    icon: 'warning',
    confirmButtonText: 'OK'
  }).then(() => {
    window.location.href = 'd_penjamin.php';
  });
  </script>";
  exit();
}


$guarantorID1 = $_SESSION['guarantorID1'];
$guarantorID2 = $_SESSION['guarantorID2'];

// Member personal data
$gajiKasar = '';
$gajiBersih = '';

// Fetch data
  $sql = "SELECT l_monthlyGrossSalary, l_monthlyNetSalary FROM tb_loan WHERE l_loanApplicationID = $loanApplicationID";
  $result = mysqli_query($con, $sql);

  if ($row = mysqli_fetch_assoc($result)) {
    $gajiKasar = htmlspecialchars($row['l_monthlyGrossSalary']); 
    $gajiBersih = htmlspecialchars($row['l_monthlyNetSalary']);
  }

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
          <a href="e_pengesahan_majikan.php" class="text-center active">Pengesahan Majikan<br></a>
          <hr>
        </div>
        <div class="row">
          <a href="f_akuan_kebenaran.php" class="text-center">Akuan Kebenaran<br></a>
          <hr>
        </div>
  </div>
</div>


<div class="col-10 main-content">

<form method = "post" action = "e_pengesahan_majikan_process.php" enctype="multipart/form-data">
  <fieldset>
    <!--Pengesahan Majikan-->
    <div class="container">
      <br>
      <div class="jumbotron">
        <h2>Pengesahan Majikan</h2>

        <label class="form-label mt-4">Gaji Kasar Bulanan</label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name = "gajiKasar" class="form-control" id="gajiKasar" aria-label="gajiKasar" placeholder="0.00"  value="<?php echo $gajiKasar; ?>" readonly required  style="background-color: #f0f0f0; color: #000;">
          </div>  

        <label class="form-label mt-4">Gaji Bersih Bulanan</label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name = "gajiBersih" class="form-control" id="gajiBersih" aria-label="gajiBersih" placeholder="0.00"  value="<?php echo $gajiBersih; ?>" readonly required  style="background-color: #f0f0f0; color: #000;">
          </div> 

        <div>
          <label for=pengesahanMajikan" class="form-label mt-4">Muat Naik Pengesahan Majikan</label>
          <input class="form-control" type="file" id="pengesahanMajikan" name="pengesahanMajikan" accept=".pdf" required>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Fail yang dibenarkan adalah dalam format PDF sahaja. Sila pastikan saiz fail tidak melebihi 5MB.</p>
        </div>
    </hr>

    <div style="text-align: center;">
      <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
      
      </div>
    </div>
    </div>   
  </fieldset>
</form>
</div>



</body>
</html>

<?php include '../footer.php'; ?>