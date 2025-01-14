<?php 
include ('pinjaman_sessions.php');
if (!session_id())
{
    session_start();
}
include ('headermain.php');
include 'dbconnect.php';

// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['uid'])) {
  // If not logged in, redirect to login page
  header('Location: login.php');
  exit(); // Ensure no further code is executed
}

// Loan
if (!isset($_SESSION['loanApplicationID'])) {
    die('Error: Loan application ID is missing.');
}

$loanApplicationID = $_SESSION['loanApplicationID']; 

// Loan data
$loanType = '';
$amaunDipohon = '';
$tempohPembiayaan = '';
$ansuranBulanan = '';
$namaBank = '';
$bankAcc = '';
$gajiKasar = '';
$gajiBersih = '';
$signature = '';

// Fetch data
if ($loanApplicationID !== null) {
  $sql = "SELECT l_loanType, l_appliedLoan, l_loanPeriod, l_monthlyInstalment, l_bankAccountNo, l_bankName, l_monthlyGrossSalary, l_monthlyNetSalary, l_signature FROM tb_loan WHERE l_loanApplicationID = '$loanApplicationID'";
  $result = mysqli_query($con, $sql);

  if ($row = mysqli_fetch_assoc($result)) {
    $loanType = htmlspecialchars($row['l_loanType']); 
    $amaunDipohon = htmlspecialchars($row['l_appliedLoan']); 
    $tempohPembiayaan = htmlspecialchars($row['l_loanPeriod']); 
    $ansuranBulanan = htmlspecialchars($row['l_monthlyInstalment']); 
    $namaBank = htmlspecialchars($row['l_bankName']); 
    $bankAcc = htmlspecialchars($row['l_bankAccountNo']); 
    $gajiKasar = htmlspecialchars($row['l_monthlyGrossSalary']); 
    $gajiBersih = htmlspecialchars($row['l_monthlyNetSalary']); 
    $signature = htmlspecialchars($row['l_signature']); 

  }
}

?>

<form method = "post" action = "semakan_pinjaman_process.php" enctype="multipart/form-data">
  <fieldset>
    <!--Personal details-->
    <div class="container">
      <br>
      <div class="jumbotron">
      <h2>Butir-Butir Pembiayaan</h2>
        <label class="form-label mt-4">Jenis Pembiayaan</label>
        <br>
        <?php
            $sql="SELECT * FROM tb_ltype";
            $result=mysqli_query($con,$sql);

            while($row=mysqli_fetch_array($result))
            {
                $isChecked = ($row['lt_lid'] == $loanType) ? 'checked' : '';
                echo '<input type="radio" id="loanType' . $row['lt_lid'] . '" name="loanType" value="' . $row['lt_lid'] . '" '. $isChecked. '>';
                echo '<label for="loanType' . $row['lt_lid'] . '">' . $row['lt_desc'] . '</label><br>';
            }
        ?>

        <div>
          <label class="form-label mt-4">Amaun Dipohon*</label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span>
            <input type="text" name = "amaunDipohon" class="form-control" id="amaunDipohon" aria-label="amaunDipohon" placeholder="0.00"  value="<?php echo $amaunDipohon; ?>" required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">Tempoh Pembiayaan*</label>
          <div class="input-group mt-2">
            <input type="text" name = "tempohPembiayaan" class="form-control" id="tempohPembiayaan" aria-label="tempohPembiayaan" placeholder="0" value="<?php echo $tempohPembiayaan; ?>" required>
            <span class="input-group-text">bulan</span>
          </div> 
        </div>

        <div>
          <label class="form-label mt-4">Ansuran Bulanan*</label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name = "ansuranBulanan" class="form-control" id="ansuranBulanan" aria-label="ansuranBulanan" placeholder="0.00" value="<?php echo $ansuranBulanan; ?>" required>
          </div>  
        </div>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Sila rujuk jadual pembayaran balik pembiayaan skim </p>

        <label class="form-label mt-4">Nama Bank/Cawangan</label>
        <br>
        <?php
        $sql="SELECT * FROM tb_lbank";
        $result=mysqli_query($con,$sql);

        while($row=mysqli_fetch_array($result))
        {
            $isChecked = ($row['lb_id'] == $namaBank) ? 'checked' : '';
            echo '<input type="radio" id="namaBank' . $row['lb_id'] . '" name="namaBank" value="' . $row['lb_id'] . '" '. $isChecked. '>';
            echo '<label for="namaBank' . $row['lb_id'] . '">' . $row['lb_desc'] . '</label><br>';
        }
        ?>

        <div>
          <label class="form-label mt-4">Bank Account</label>
          <div class="input-group mt-2">
            <input type="text" name = "bankAcc" class="form-control" id="bankAcc" aria-label="bankAcc" placeholder="000000000" value="<?php echo $bankAcc; ?>" required>
          </div>  
        </div>

        <div>
          <label class="form-label mt-4">Gaji Kasar Bulanan</label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name = "gajiKasar" class="form-control" id="gajiKasar" aria-label="gajiKasar" placeholder="0.00" value="<?php echo $gajiKasar; ?>" required>
          </div>  
        </div>

        <div>
          <label class="form-label mt-4">Gaji Bersih Bulanan</label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name = "gajiBersih" class="form-control" id="gajiBersih" aria-label="gajiBersih" placeholder="0.00" value="<?php echo $gajiBersih; ?>" required>
          </div>  
        </div>

        <div>
          <label for="fileSign" class="form-label mt-4">Signature</label>
          <input class="form-control" type="file" id="fileSign" name="fileSign" accept=".png, .jpg, .jpeg" required>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Fail yang dibenarkan adalah dalam format PNG, JPG, dan JPEG sahaja. Sila pastikan saiz fail tidak melebihi 5MB.</p>
        </div>

    
      <hr class="my-4">
        <p class="lead">
        <button type="submit" class="btn btn-primary">Simpan</button>
        </p>
      </hr>

    </div>     
  </fieldset>
</form>
</div>



</body>
</html>

<!--?php include 'footer.php';?-->