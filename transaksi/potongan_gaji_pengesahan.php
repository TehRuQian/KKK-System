<?php 
  include('../kkksession.php');
  if (!session_id()) {
      session_start();
  }

  if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
  }
  
  include '../header_admin.php';
  include '../db_connect.php';
  $admin_id = $_SESSION['u_id'];

  // Get selected members and action
  if (isset($_POST['selected_members']) && !empty($_POST['selected_members'])) {
    $selectedMembers = $_POST['selected_members'];
    $f_month = $_POST['f_month'];
    $f_year = $_POST['f_year'];
  } else {
      echo "
        <script>
            Swal.fire({
                title: 'Ralat!',
                text: 'Sila pilih sekurang-kurangnya satu anggota.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'potongan_gaji.php';
            });
        </script>";
  }

  // Retrieve Policy Info
  $sql = "SELECT p_minShareCapital, p_salaryDeductionForSaving, p_salaryDeductionForMemberFund
          FROM tb_policies
          ORDER BY p_policyID DESC
          LIMIT 1";
  $result = mysqli_query($con, $sql);
  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $minShareCapital = $row['p_minShareCapital'];
    $salaryDeductionForSaving = $row['p_salaryDeductionForSaving'];
    $salaryDeductionForMemberFund = $row['p_salaryDeductionForMemberFund'];
  } else {
      echo "Error: " . mysqli_error($con);
  }

  $sql = "SELECT lt_lid, lt_desc FROM tb_ltype";
  $loanType = mysqli_query($con, $sql);
?>

<div class="container">
  <h2>Pengesahan Transaksi Potongan Gaji</h2>

  <form method="POST" action="potongan_gaji_process.php">
    <input type="hidden" name="f_month" value="<?php echo $f_month; ?>">
    <input type="hidden" name="f_year" value="<?php echo $f_year; ?>">
    <input type="hidden" name="selected_members" id="selected_members" value="<?php echo implode(',', $selectedMembers); ?>">

    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">No Ahli</th>
          <th scope="col">Nama</th>
          <th scope="col">Perubahan Status</th>
          <th scope="col">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($selectedMembers as $memberNo){
            $sql_financial = "SELECT * FROM tb_financial WHERE f_memberNo = $memberNo;";
            $result_financial = mysqli_query($con, $sql_financial);
            $financial = mysqli_fetch_assoc($result_financial);

            $sql_member = "SELECT * FROM tb_member WHERE m_memberNo = $memberNo;";
            $result_member = mysqli_query($con, $sql_member);
            $member = mysqli_fetch_assoc($result_member);

            $sql_loan = "SELECT tb_loan.*, tb_ltype.lt_desc
                         FROM tb_loan 
                         JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
                         WHERE tb_loan.l_memberNo = $memberNo AND tb_loan.l_status = 3;";
            $result_loan = mysqli_query($con, $sql_loan);

            $sql_transaction = "SELECT COUNT(t_transactionID) FROM tb_transaction
                                WHERE t_memberNo = $memberNo
                                AND t_month = $f_month
                                AND t_year = $f_year
                                AND t_method = 'Potongan Gaji';";
            $result_transaction = mysqli_query($con, $sql_transaction);
            $record_exists = mysqli_fetch_row($result_transaction)[0] > 0;

            $newShareCapital = $financial['f_shareCapital'];
            $newFeeCapital = $financial['f_feeCapital'];
            $newFixedSaving = $financial['f_fixedSaving'];
            $newMemberFund = $financial['f_memberFund'];
            $newMemberSaving = $financial['f_memberSaving'];
            
            if ($financial['f_shareCapital'] < $minShareCapital) {
              $newShareCapital += $salaryDeductionForSaving;
              if($newShareCapital > $minShareCapital) {
                $newFixedSaving += $newShareCapital - $minShareCapital;
                $newShareCapital = $minShareCapital;
              }
              $newMemberFund += $salaryDeductionForMemberFund;
            }
            else {
              $newMemberFund += $salaryDeductionForMemberFund;
              $newFixedSaving += $salaryDeductionForSaving;
            }

            $totalAmount = $salaryDeductionForMemberFund + $salaryDeductionForSaving;
            // if ($record_exists) {
            //   echo "<tr id='member_{$memberNo}' class='table-danger'>";
            // }
            // else{
              echo "<tr id='member_{$memberNo}'>";
            // }
                echo "<td>" . $financial['f_memberNo'] . "</td>";
                echo "<td>" . $member['m_name'] . "</td>";
                echo "<td>
                        <table class='table table-hover'>
                          <tr>
                            <th>Perkara</th>
                            <th>Status Semasa</th>
                            <th>Perubahan</th>
                            <th>Status Baharu</th>
                          </tr>";
                          if($newShareCapital - $financial['f_shareCapital'] != 0){
                            echo "
                                <tr>
                                <td>Modah Syer</td>
                                <td>" . number_format($financial['f_shareCapital'], 2) . "</td>
                                <td>" . number_format($newShareCapital - $financial['f_shareCapital'], 2) . "</td>
                                <td>" . number_format($newShareCapital, 2) . "</td>
                            </tr>";
                          };
                          if($newMemberFund - $financial['f_memberFund'] != 0){
                            echo "
                                <tr>
                                <td>Tabung Anggota</td>
                                <td>" . number_format($financial['f_memberFund'], 2) . "</td>
                                <td>" . number_format($newMemberFund - $financial['f_memberFund'], 2) . "</td>
                                <td>" . number_format($newMemberFund, 2) . "</td>
                            </tr>";
                          }
                          if($newFixedSaving - $financial['f_fixedSaving'] != 0){
                            echo "
                                <tr>
                                <td>Simpanan Anggota</td>
                                <td>" . number_format($financial['f_fixedSaving'], 2) . "</td>
                                <td>" . number_format($newFixedSaving - $financial['f_fixedSaving'], 2) . "</td>
                                <td>" . number_format($newFixedSaving, 2) . "</td>
                            </tr>";
                          }
                    echo"</table>";
                    if(mysqli_num_rows($result_loan) > 0){
                        echo "<table class='table table-hover'>
                                <tr>
                                    <th>ID</th>
                                    <th>Pinjaman</th>
                                    <th>Tunggakan Semasa</th>
                                    <th>Bayaran</th>
                                    <th>Tunggakan Baharu</th>
                                </tr>";
                        while($row = mysqli_fetch_assoc($result_loan)){
                            $difference = $row['l_monthlyInstalment'];
                            if ($difference > $row['l_loanPayable']){
                                $difference = $row['l_loanPayable'];
                            }
                            $newLoanPayable = $row['l_loanPayable'] - $difference;
                            $totalAmount += $difference;
                            echo "<tr>";
                                echo "<td>" . $row['l_loanApplicationID'] . "</td>";
                                echo "<td>" . $row['lt_desc'] . "</td>";
                                echo "<td>" . number_format($row['l_loanPayable'], 2) . "</td>";
                                echo "<td>" . number_format($difference, 2) . "</td>";
                                echo "<td>" . number_format($newLoanPayable, 2) . "</td>";
                            echo "</tr>";
                        }
                        echo"</table>";
                    }
                echo "Jumlah Potongan Gaji: RM " . number_format($totalAmount, 2);
                echo "</td>";
                echo "<td>";
                if ($record_exists) {
                  // echo "<p class='text-danger'>Transaksi telah wujud untuk bulan ini!</p>";
                  echo "
                  <div class='alert alert-dismissible alert-danger'>
                    <strong>Amaran: </strong>Transaksi telah <br> wujud untuk bulan ini!
                  </div>";
                }
                echo "<button type='button' class='btn btn-warning' onclick='removeMember(" . $financial['f_memberNo'] . ")'>Keluarkan</button></td>";
            echo "</tr>";
          }
        ?>
      </tbody>
    </table>
    <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-primary">Hantar</button>
    </div>
  </form>
  <br>
</div>

<script>
  function removeMember(memberNo) {
    var row = document.getElementById('member_' + memberNo);
    row.remove();

    var selectedMembersInput = document.getElementById('selected_members');
    var currentSelection = selectedMembersInput.value.split(',');
    var newSelection = currentSelection.filter(function(item) {
      return item !== memberNo.toString();
    });

    selectedMembersInput.value = newSelection.join(',');

  }
</script>
