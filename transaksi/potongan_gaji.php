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

  $records_per_page = isset($_GET['recordPerPage']) ? $_GET['recordPerPage'] : 20;
  $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
  $start_from = ($current_page - 1) * $records_per_page;

  $filter_month = $_GET['filter_month'] ?? '';
  $filter_year = $_GET['filter_year'] ?? '';
  $filter_member = $_GET['filter_member'] ?? '';

  $where_sql = "WHERE tb_member.m_status = 3";

  $sql_cutoffday = "SELECT p_cutOffDay FROM tb_policies ORDER BY p_policyID DESC LIMIT 1";
  $result_cutoffday = mysqli_query($con, $sql_cutoffday);
  $cutoffday = mysqli_fetch_assoc($result_cutoffday)['p_cutOffDay'];
  $filter_date = $filter_year . '-' . $filter_month . '-' . $cutoffday;

    if (!empty($filter_month)  &&  !empty($filter_year)) {
      $where_sql = "LEFT JOIN tb_transaction ON tb_financial.f_memberNo = tb_transaction.t_memberNo
        AND tb_transaction.t_month = '$filter_month'
        AND tb_transaction.t_year = '$filter_year'
        AND tb_transaction.t_method = 'Potongan Gaji'
        WHERE DATE(tb_member.m_approvalDate) < '$filter_date'
        AND tb_transaction.t_memberNo IS NULL";
    }
    if (!empty($filter_member)){
        $where_sql = "WHERE tb_financial.f_memberNo = '$filter_member'";
    }

  // Retrieve financial list of all members
  $sql_financial = "SELECT DISTINCT tb_financial.*, tb_member.m_name
    FROM tb_financial
    INNER JOIN tb_member ON tb_financial.f_memberNo=tb_member.m_memberNo
    $where_sql
    LIMIT $start_from, $records_per_page";

  $result_financial = mysqli_query($con, $sql_financial);

  $total_sql = "SELECT COUNT(DISTINCT tb_financial.f_memberNo) FROM tb_financial 
    INNER JOIN tb_member ON tb_financial.f_memberNo=tb_member.m_memberNo
    $where_sql";
  $total_result = mysqli_query($con, $total_sql);
  $total_row = mysqli_fetch_row($total_result);
  $total_records = $total_row[0];
  $total_pages = ceil($total_records / $records_per_page);

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
    <h2>Potongan Gaji</h2>
  
    <div class="row justify-content-center">
  `    <!-- Filters -->
      <div class="col-md-4">
        <label class="mb-3 d-flex justify-content-center"class="mb-3 d-flex justify-content-center">
          Anggota yang belum dipotong gaji untuk</label>
        <form method="GET" action="" class="mb-3 d-flex justify-content-center">
          <select name="filter_month" class="form-select me-2" style="width: 200px;">
              <option value="">Pilih Bulan</option>
              <?php
              $month_query = "SELECT * FROM tb_rmonth";
              $month_result = mysqli_query($con, $month_query);
              while ($month = mysqli_fetch_assoc($month_result)) {
                  $selected = (isset($_GET['filter_month']) && $_GET['filter_month'] == $month['rm_id']) ? 'selected' : '';
                  echo "<option value='{$month['rm_id']}' $selected>{$month['rm_desc']}</option>";
              }
              ?>
          </select>
          <select name="filter_year" class="form-select me-2" style="width: 200px;">
              <option value="">Pilih Tahun</option>
              <?php
              $currentYear = date('Y');
              $previousYear = $currentYear - 1;
              $nextYear = $currentYear + 1;

              function isSelected($year, $selectedYear) {
                return $year == $selectedYear ? 'selected' : '';
              }
              ?>
              <option value="<?php echo $previousYear; ?>" <?php echo isSelected($previousYear, $_GET['filter_year'] ?? ''); ?>>
                  <?php echo $previousYear; ?>
              </option>
              <option value="<?php echo $currentYear; ?>" <?php echo isSelected($currentYear, $_GET['filter_year'] ?? ''); ?>>
                  <?php echo $currentYear; ?>
              </option>
              <option value="<?php echo $nextYear; ?>" <?php echo isSelected($nextYear, $_GET['filter_year'] ?? ''); ?>>
                  <?php echo $nextYear; ?>
              </option>
          </select>
          <button type="submit" class="btn btn-primary">Tapis</button>
        </form>
      </div>

      <!-- Find -->
      <div class="col-md-4">
        <label class="mb-3 d-flex justify-content-center"class="mb-3 d-flex justify-content-center">
        Cari Anggota</label>
        <form method="GET" action="" class="mb-3 d-flex justify-content-center">
            <input type="text" name="filter_member" class="form-control me-2" placeholder="No Anggota" value="<?= $filter_member; ?>" style="width: 200px;">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
      </div>
    </div>

    <!-- Form for batch processing -->
    <form id="transaksiForm" method="POST" action='potongan_gaji_pengesahan.php' enctype="multipart/form-data">
      <!-- Table for financial statuses -->
      <table class="table table-hover">
        <thead>
          <tr>
            <!-- Checkbox for batch selection -->
            <th scope="col">
              <input class="form-check-input" type="checkbox" id="select_all" onclick="toggleSelectAll()"> Pilih Semua
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
                    echo "<td><input class='form-check-input' type='checkbox' name='selected_members[]' value='" . $memberNo . "'></td>";
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

      <nav>
        <ul class="d-flex justify-content-center pagination pagination-sm">
          <?php if($current_page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $current_page - 1; ?>&filter_month=<?= $filter_month; ?>&filter_year=<?= $filter_year; ?>&filter_member=<?= $filter_member; ?>">&laquo;</a>
            </li>
          <?php endif; ?>

          <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?= $i; ?>&filter_month=<?= $filter_month; ?>&filter_year=<?= $filter_year; ?>&filter_member=<?= $filter_member; ?>"><?= $i; ?></a>
            </li>
          <?php endfor; ?>

          <?php if($current_page < $total_pages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $current_page + 1; ?>&filter_month=<?= $filter_month; ?>&filter_year=<?= $filter_year; ?>&filter_member=<?= $filter_member; ?>">&raquo;</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>

          <!-- Input for months to be deducted -->
    <p class="mb-3 d-flex justify-content-center"class="mb-3 d-flex justify-content-center">
    Potongan Gaji untuk bulan</p>
      <div class="mb-3 d-flex justify-content-center">
        <!-- Drop down for month -->
          <select class="form-select me-2" id="f_month" name="f_month" style="width: 200px;">
            <?php
              $sql = "SELECT * FROM tb_rmonth;";
              $months = mysqli_query($con, $sql);
              $currentMonth = date('n');

              while ($row = mysqli_fetch_array($months)) {
                $selected = ($currentMonth == $row['rm_id']) ? 'selected' : '';
                echo "<option value='" . $row['rm_id'] . "' $selected>" . $row['rm_desc'] . "</option>";
              }
            ?>
          </select>
        <!-- Dropdown for years -->
          <?php
            $currentYear = date('Y');
            $previousYear = $currentYear - 1;
            $nextYear = $currentYear + 1;
          ?>
          <select class="form-select  me-2" id="f_year" name="f_year" style="width: 200px;">
              <option value="<?php echo $previousYear; ?>"><?php echo $previousYear; ?></option>
              <option value="<?php echo $currentYear; ?>" selected><?php echo $currentYear; ?></option>
              <option value="<?php echo $nextYear; ?>"><?php echo $nextYear; ?></option>
          </select>
      </div>

      <div class="d-flex justify-content-center">
        <button type="button" class="btn btn-primary mx-2" onclick="submitForm()">Potongan Gaji</button>
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

  function submitForm() {
    document.getElementById('transaksiForm').submit();
    // document.getElementById("monthForm").submit();
  }
</script>

</body>
</html>
