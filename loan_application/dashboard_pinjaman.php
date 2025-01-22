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
  echo '<script>
          Swal.fire({
              title: "Berjaya!",
              text: "Maklumat anda telah berjaya disimpan!",
              icon: "success",
              confirmButtonText: "OK"
          });
        </script>';
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
                    <th>Tempoh Pinjaman (Tahun)</th>
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
                $sql = "SELECT l_loanType, l_appliedLoan, l_loanPeriod, l_monthlyInstalment, l_loanPayable, l_status, l_applicationDate, l_loanApplicationID FROM tb_loan WHERE l_memberNo= $memberNo";
                $result = mysqli_query($con, $sql);

                // 检查查询是否成功
                if (!$result) {
                    die("Query failed: " . mysqli_error($con));
                }

                while ($row = mysqli_fetch_assoc($result)) {

                    $selected_jenis_pembiayaan_ID = htmlspecialchars($row['l_loanType']);  
                    $selected_amaunDipohon = htmlspecialchars($row['l_appliedLoan']);  
                    $selected_tempohPembiayaan = htmlspecialchars($row['l_loanPeriod']);  
                    $selected_ansuranBulanan = htmlspecialchars($row['l_monthlyInstalment']);   
                    $selected_tunggakan = htmlspecialchars($row['l_loanPayable']);
                    $status_loan_ID = htmlspecialchars($row['l_status']);  
                    $application_date = htmlspecialchars($row['l_applicationDate']);  
                    $loanApplicationID = htmlspecialchars($row['l_loanApplicationID']); // 获取贷款申请ID

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
                    if ($status_loan_ID !== null) { 
                      $sql_status = "SELECT s_desc FROM tb_status WHERE s_sid = $status_loan_ID";
                      $status_result = mysqli_query($con, $sql_status);
                      if ($status_row = mysqli_fetch_assoc($status_result)) {
                          $status_loan = $status_row['s_desc'];
                      }
                    }

                    echo "<tr>";
                    echo "<td>{$count}</td>";
                    echo "<td>{$selected_jenis_pembiayaan}</td>";
                    echo "<td>" . number_format($selected_amaunDipohon, 2) . "</td>";
                    echo "<td>{$selected_tempohPembiayaan}</td>"; 
                    echo "<td>" . number_format($selected_ansuranBulanan, 2) . "</td>"; 
                    echo "<td>" . number_format($selected_tunggakan, 2) . "</td>";
                    echo "<td>{$application_date}</td>"; 
                    
                    // 如果状态为 0，显示为按钮
                    if ($status_loan_ID == 0) {
                        echo "<td><a href='semakan_butiran.php?loan_id={$loanApplicationID}' class='btn btn-primary'>Draf</a></td>"; // 使用贷款申请ID
                    } else {
                        echo "<td>{$status_loan}</td>"; // 否则显示状态描述
                    }
                    
                    echo "</tr>";
                    
                    $count++;
                }
                ?>
                </tbody>
                </table>

            </div>
    </div>

      <a href="a_pinjaman.php" class="btn btn-primary">Mohon Pinjaman</a>

  </fieldset>

</form>



</div>


</body>
</html>

<?php include '../footer.php'; ?>