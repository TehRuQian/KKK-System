<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

// Check if the 'status' parameter is present in the URL
if (isset($_SESSION['loanApplicationID'])) {
    $loanApplicationID = $_SESSION['loanApplicationID'];
  } elseif (isset($_GET['loan_id'])) {
    $loanApplicationID = $_GET['loan_id'];
  } else {
    die("Error: Loan application ID is missing.");
}

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


<form method = "post" action = "semakan_pengesahan_majikan_process.php?loan_id=<?php echo $loanApplicationID; ?>" enctype="multipart/form-data">
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