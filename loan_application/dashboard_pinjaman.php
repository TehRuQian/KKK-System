<?php
include('../kkksession.php');
if(!session_id())
{
  session_start();
}

if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$memberNo = $_SESSION['funame'];

// Check if the 'status' parameter is present in the URL
if (isset($_GET['status']) && $_GET['status'] == 'success') {
  echo '<script>alert("Maklumat anda telah berjaya disimpan!");</script>';
}

// Personal Data
$memberNo = isset($_SESSION['funame']) ? $_SESSION['funame'] : null;

?>

<form method = "post" action = "dashboard_pinjaman_process.php">
  <fieldset>
    <!--Loan details-->
    <div class="container">
      <br>
      <div class="jumbotron">
          <div class="card mb-3">
            <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
            Papan Pemuka Pinjaman
            </div>
            <div class="card-body">

                <table class="table table-hover  align-middle">
                  <thead class="table-hover">
                    <tr>
                    <th>No.</th>
                    <th>Jenis Pinjaman</th>
                    <th>Jumlah Permohonan (RM)</th>
                    <th>Tempoh Pinjaman (Bulan)</th>
                    <th>Ansuran Bulanan (RM)</th>
                    <th>Tunggakan (RM)</th>
                    <th>Tarikh Permohonan</th>
                    <th>Status</th>
                    </tr>
                  </thead>
                <tbody>
                <?php 
                // Loan details
                $count = 1;

                // Fetch data
                $sql = "SELECT l_loanType, l_appliedLoan, l_loanPeriod, l_monthlyInstalment, l_loanPayable, l_status, l_applicationDate FROM tb_loan WHERE l_memberNo= $memberNo";
                $result = mysqli_query($con, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                  $selected_jenis_pembiayaan_ID = htmlspecialchars($row['l_loanType']);  
                  $selected_amaunDipohon = htmlspecialchars($row['l_appliedLoan']);  
                  $selected_tempohPembiayaan = htmlspecialchars($row['l_loanPeriod']);  
                  $selected_ansuranBulanan = htmlspecialchars($row['l_monthlyInstalment']);   
                  $selected_tunggakan = htmlspecialchars($row['l_loanPayable']);
                  $status_loan_ID = htmlspecialchars($row['l_status']);  
                  $application_date = htmlspecialchars($row['l_applicationDate']);  


                // Fetch the description if an ID is selected
                // loan id
                $selected_jenis_pembiayaan = '';
                if ($selected_jenis_pembiayaan_ID) {
                    $sql_desc = "SELECT lt_desc FROM tb_ltype WHERE lt_lid = $selected_jenis_pembiayaan_ID";
                    $desc_result = mysqli_query($con, $sql_desc);
                    if ($desc_row = mysqli_fetch_assoc($desc_result)) {
                        $selected_jenis_pembiayaan = $desc_row['lt_desc'];
                    }
                }
                $status_loan = '';
                if ($status_loan_ID) {
                  $sql_status = "SELECT s_desc FROM tb_status WHERE s_sid = $status_loan_ID";
                  $status_result = mysqli_query($con, $sql_status);
                  if ($status_row = mysqli_fetch_assoc($status_result)) {
                      $status_loan = $status_row['s_desc'];
                  }
                }

                  echo "<tr>";
                  echo "<td>{$count}</td>";
                  echo "<td>{$selected_jenis_pembiayaan}</td>";
                  echo "<td>{$selected_amaunDipohon}</td>";
                  echo "<td>{$selected_tempohPembiayaan}</td>";
                  echo "<td>{$selected_ansuranBulanan}</td>";
                  echo "<td>{$selected_tunggakan}</td>";
                  echo "<td>{$application_date}</td>";
                  echo "<td>{$status_loan}</td>";
                  echo "</tr>";
                  
                  $count++;
                }
                ?>
                </tbody>
                </table>

            </div>
    </div>

 
    <hr class="my-4">
      <p class="lead">
      <a href="a_pinjaman.php" class="btn btn-primary">Mohon Pinjaman</a>
      </p>
    </hr>

  </fieldset>

</form>



</div>


</body>
</html>

<?php include '../footer.php'; ?>