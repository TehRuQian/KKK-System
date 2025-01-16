<?php 
  include '../header_admin.php';
  include '../db_connect.php';

  // Get selected members and action
  if (isset($_POST['selected_members']) && !empty($_POST['selected_members'])) {
    $selectedMembers = $_POST['selected_members'];
    $f_month = $_POST['f_month'];
    $f_year = $_POST['f_year'];
  } else {
      echo "
        <script>
            alert ('No members selected.');
            window.location.href = 'transaksi.php';
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
          <th scope="col">Status Semasa</th>
          <th scope="col">Status Baru</th>
          <th scope="col">Tindakan</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($selectedMembers as $memberNo){
            $sql = "SELECT * FROM tb_financial WHERE f_memberNo = $memberNo;";
            $result = mysqli_query($con, $sql);
            $financial = mysqli_fetch_assoc($result);

            $sql = "SELECT m_name FROM tb_member WHERE m_memberNo = $memberNo;";
            $result = mysqli_query($con, $sql);
            $name = mysqli_fetch_assoc($result)['m_name'];

            $sql = "SELECT * FROM tb_loan 
                    WHERE l_memberNo = $memberNo AND l_status = 3;";

            $newShareCapital = $financial['f_shareCapital'];
            $newMemberFund = $financial['f_memberFund'];
            $newFixedSaving = $financial['f_fixedSaving'];
            $newAlBai = $financial['f_alBai'];
            $newAlInnah = $financial['f_alInnah'];
            $newBPulihKenderaan = $financial['f_bPulihKenderaan'];
            $newRoadTaxInsurance = $financial['f_roadTaxInsurance'];
            $newSpecialScheme = $financial['f_specialScheme'];
            $newSpecialSeasonCarnival = $financial['f_specialSeasonCarnival'];
            $newAlQadrulHassan = $financial['f_alQadrulHassan'];
            
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

            // if ($newAlBai != 0){
            //   if($newAlBai - )
            // }

            echo "<tr id='member_{$memberNo}'>";
                echo "<td>" . $financial['f_memberNo'] . "</td>";
                echo "<td>" . $name . "</td>";
                echo "<td>
                        Modah Syer: " . number_format($financial['f_shareCapital'], 2) . "<br>
                        Tabung Anggota: " . number_format($financial['f_memberFund'], 2) . "<br>
                        Simpanan Anggota: " . number_format($financial['f_fixedSaving'], 2) . "
                      </td>";
                echo "<td>
                        Modah Syer: " . number_format($newShareCapital, 2) . "<br>
                        Tabung Anggota: " . number_format($newMemberFund, 2) . "<br>
                        Simpanan Anggota: " . number_format($newFixedSaving, 2) . "
                      </td>";
                echo "<td><button type='button' class='btn btn-warning' onclick='removeMember(" . $financial['f_memberNo'] . ")'>Keluarkan</button></td>";
                echo "</tr>";
          }
        ?>
      </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Hantar</button>
  </form>
</div>

<script>
  function removeMember(memberNo) {
    var row = document.getElementById('member_' + memberNo);
    row.remove();

    var selectedMembersInput = document.getElementById('selected_members');
    var currentSelection = selectedMembersInput.value.split(',');

    // Remove the memberNo from the array
    var newSelection = currentSelection.filter(function(item) {
      return item !== memberNo.toString();
    });

    // Update the hidden input with the new selected members
    selectedMembersInput.value = newSelection.join(',');

    // Debugging: log the new value of selected_members
    console.log("Updated selected_members:", selectedMembersInput.value);
  }
</script>
