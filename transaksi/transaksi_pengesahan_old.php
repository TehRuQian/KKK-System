<?php 
  include '../header_admin.php';
  include '../db_connect.php';

  // Get selected members and action
  if (isset($_POST['selected_members']) && !empty($_POST['selected_members'])) {
    $selectedMembers = $_POST['selected_members'];
    $action = $_POST['action'];
  } 
  else {
      echo "
        <script>
            alert ('No members selected.');
            window.location.href = 'transaksi.php';
        </script>";
  }

  // Retrieve Policy  Info
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

?>

<div class="container">
  <h2>Pengesahan Transaksi Potongan Gaji</h2>

  <form method="POST" action="transaksi_process.php">
    <input type="hidden" name="action" value="<?php echo $action; ?>">
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

            $newShareCapital = $financial['f_shareCapital'];
            $newMemberFund = $financial['f_memberFund'];
            $newFixedSaving = $financial['f_fixedSaving'];
            
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
    <button type="submit" class="btn btn-primary">Seterusnya</button>
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
  }
</script>