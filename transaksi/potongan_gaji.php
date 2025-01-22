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

  // Retrieve financial list of all members
  $sql = "
    SELECT tb_financial.*, tb_member.m_name
    FROM tb_financial
    INNER JOIN tb_member
    ON tb_financial.f_memberNo=tb_member.m_memberNo;";
  $result_financial = mysqli_query($con, $sql);

  $sql_loan = "SELECT l_memberNo, l_loanType, COALESCE(SUM(l_loanPayable), 0) AS totalLoan
             FROM tb_loan
             WHERE l_status = 3
             GROUP BY l_memberNo, l_loanType
             ORDER BY l_memberNo, l_loanType;";
  $result_loan = mysqli_query($con, $sql_loan);

  $loanData = [];
  while ($row_loan = mysqli_fetch_array($result_loan)) {
      $loanData[$row_loan['l_memberNo']][$row_loan['l_loanType']] = $row_loan['totalLoan'];
  }
?>

<style>
  table td, table th {
    vertical-align: middle; 
  }
</style>

<div class="container">
    <h2>Transaksi</h2>

    <!-- Form for batch processing -->
    <form id="transaksiForm" method="POST">
    <div class="d-flex justify-content-center align-items-center mb-3">
        <!-- Drop down for month -->
        <div class="mb-3">
          <select class="form-select" id="f_month" name="f_month">
            <?php
              $sql = "SELECT * FROM tb_rmonth;";
              $months = mysqli_query($con, $sql);
              $currentMonth = date('n');

              while ($row = mysqli_fetch_array($months)) {
                $selected = ($currentMonth == $row['rm_id']) ? 'selected' : '';
                echo "<option value='" . $row['rm_id'] . "'>" . $row['rm_desc'] . "</option>";
              }
            ?>
          </select>
        </div>
        <!-- Dropdown for years -->
        <div class="mb-3">
          <?php
            $currentYear = date('Y');
            $previousYear = $currentYear - 1;
            $nextYear = $currentYear + 1;
          ?>
          <select class="form-select" id="f_year" name="f_year">
              <option value="<?php echo $previousYear; ?>"><?php echo $previousYear; ?></option>
              <option value="<?php echo $currentYear; ?>" selected><?php echo $currentYear; ?></option>
              <option value="<?php echo $nextYear; ?>"><?php echo $nextYear; ?></option>
          </select>
        </div>
      </div>

      <!-- Table for financial statuses -->
      <table class="table table-hover">
        <thead>
          <tr>
            <!-- Checkbox for batch selection -->
            <th scope="col">
              <input type="checkbox" id="select_all" onclick="toggleSelectAll()"> Select All
            </th>
            <th scope="col">No Ahli</th>
            <th scope="col">Nama</th>
            <th scope="col">Modah Syer</th>
            <th scope="col">Modal Yuran</th>
            <th scope="col">Simpanan Tetap</th>
            <th scope="col">Tabung Anggota</th>
            <th scope="col">Simpanan Anggota</th>
            <?php 
              $sql = "SELECT * FROM tb_ltype
                      ORDER BY lt_lid;";
              $result_loanType = mysqli_query($con, $sql);
              $loanTypes = [];
              while ($row = mysqli_fetch_array($result_loanType)){
                $loanTypes[] = $row['lt_lid'];
                echo "<th scope='col'>" . $row['lt_desc'] . "</th>";
              }
            ?>
            <th scope="col">Dikemaskini</th>
          </tr>
        </thead>
        <tbody>
          <?php
            while($row = mysqli_fetch_array($result_financial)){
              $memberNo = $row['f_memberNo'];
                echo "<tr onclick='toggleCheckbox(this)' data-member-id='" . $memberNo . "'>";
                    echo "<td><input type='checkbox' name='selected_members[]' value='" . $memberNo . "'></td>";
                    echo "<td>" . $memberNo . "</td>";
                    echo "<td>" . htmlspecialchars($row['m_name']) . "</td>";
                    echo "<td>" . number_format($row['f_shareCapital'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_feeCapital'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_fixedSaving'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_memberFund'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_memberSaving'], 2) . "</td>";

                    foreach ($loanTypes as $loanType) {
                        $totalLoan = isset($loanData[$memberNo][$loanType]) ? $loanData[$memberNo][$loanType] : 0;
                        echo "<td>" . number_format($totalLoan, 2) . "</td>";
                    }

                    echo "<td>" . date('d-m-Y', strtotime($row['f_dateUpdated'])) . "</td>";
                echo "</tr>";
            }
          ?>
        </tbody>
      </table>

      <div class="d-flex justify-content-center">
        <button type="button" class="btn btn-primary mx-2" onclick="submitForm('potongan_gaji_pengesahan.php')">Potongan Gaji</button>
      </div>
      <br>
    </form>
</div>

<!-- JavaScript to handle "Select All" checkbox -->
<script>
  function toggleCheckbox(row) {
    var checkbox = row.querySelector("input[type='checkbox']");
    checkbox.checked = !checkbox.checked;
  } 

  function toggleSelectAll() {
    var checkboxes = document.getElementsByName('selected_members[]');
    var isChecked = document.getElementById('select_all').checked;
    for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].checked = isChecked;
    }
  }

  function submitForm(actionPage) {
    var form = document.getElementById('transaksiForm');
    form.action = actionPage; 
    form.submit(); 
  }
</script>

</body>
</html>
