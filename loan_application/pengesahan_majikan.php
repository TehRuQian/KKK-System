<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

// Check if the user is logged in by verifying the session variable
// if (!isset($_SESSION['uid'])) {
//   // If not logged in, redirect to login page
//   header('Location: ../login.php');
//   exit(); 
// }

// Check if the 'status' parameter is present in the URL
if (isset($_GET['status']) && $_GET['status'] == 'success') {
  echo '<script>alert("Anda telah berjaya disimpan.");</script>';
}

if (!isset($_SESSION['loanApplicationID'])) {
      echo "<script>
              alert('Error: Sila simpan maklumat pemohonan pinjaman.');
              window.location.href = 'pinjaman.php';
          </script>";
          exit();
}

$loanApplicationID = $_SESSION['loanApplicationID']; 

// Guarantor data
if (!isset($_SESSION['guarantorID1']) || !isset($_SESSION['guarantorID2'])) {
  echo "<script>
          alert('Sila simpan maklumat butir-butir penjamin.');
          window.location.href = 'penjamin.php'; 
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

<form method = "post" action = "pengesahan_majikan_process.php">
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
      <hr class="my-4">
        <p class="lead">
        <button type="submit" class="btn btn-primary">Simpan</button>
        </p>
      </hr>

      <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="float: right;">
          <div class="btn-group" role="group" aria-label="Page navigation">
            <a href="pinjaman.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'pinjaman.php' ? 'btn-primary' : 'btn-secondary'; ?>">1</a>
            <a href="butir_peribadi.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'butir_peribadi.php' ? 'btn-primary' : 'btn-secondary'; ?>">2</a>
            <a href="pengakuan_pemohon.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'pengakuan_pemohon.php' ? 'btn-primary' : 'btn-secondary'; ?>">3</a>
            <a href="penjamin.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'penjamin.php' ? 'btn-primary' : 'btn-secondary'; ?>">4</a>
            <a href="pengesahan_majikan.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'pengesahan_majikan.php' ? 'btn-primary' : 'btn-secondary'; ?>">5</a>
            <a href="akuan_kebenaran.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'akuan_kebenaran.php' ? 'btn-primary' : 'btn-secondary'; ?>">6</a>
          </div>
      </div>
      
      </div>
    </div>
    </div>   
  </fieldset>
</form>
</div>



</body>
</html>

<!--?php include 'footer.php';?-->