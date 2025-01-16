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

// Guarantor data 
if (!isset($_SESSION['guarantorID1']) || !isset($_SESSION['guarantorID2'])) {
  die('Error: Guarantor ID1 or Guarantor ID2 is missing.');
}

$guarantorID1 = $_SESSION['guarantorID1'];
$guarantorID2 = $_SESSION['guarantorID2'];
?>

<form method = "post" action = "akuan_kebenaran_process.php">
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

        <br> <br>
  
    </div>   
  </fieldset>
</form>
</div>



</body>
</html>

<!--?php include 'footer.php';?-->