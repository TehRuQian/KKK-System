<?php 
  include '../header_admin.php';
  include '../db_connect.php';

  // Retrieve financial list of all members
  $sql = "
    SELECT tb_financial.*, tb_member.m_name
    FROM tb_financial
    INNER JOIN tb_member
    ON tb_financial.f_memberNo=tb_member.m_memberNo;";
  $result = mysqli_query($con, $sql);

?>

<div class="container">
    <h2>Transaksi</h2>

    <!-- Form for batch processing -->
    <form method="POST" action="transaksi_pengesahan.php">
    <div class="d-flex justify-content-center align-items-center mb-3">
        <!-- Drop down for month -->
        <div class="mb-3">
          <select class="form-select" id="f_month" name="selectedMonth">
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
          <select class="form-select" id="f_year" name="selectedYear">
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
            <th scope="col">Al-Bai</th>
            <th scope="col">Al-Innah</th>
            <th scope="col">B/Pulih Kenderaan</th>
            <th scope="col">Road Tax & Insuran</th>
            <th scope="col">Khas</th>
            <th scope="col">Karnival Musim Istimewa</th>
            <th scope="col">Al-Qadrul Hassan</th>
            <th scope="col">Dikemaskini</th>
          </tr>
        </thead>
        <tbody>
          <?php
            while($row = mysqli_fetch_array($result)){
                echo "<tr>";
                    // Add checkbox for each row
                    echo "<td><input type='checkbox' name='selected_members[]' value='" . $row['f_memberNo'] . "'></td>";
                    echo "<td>" . $row['f_memberNo'] . "</td>";
                    echo "<td>" . $row['m_name'] . "</td>";
                    echo "<td>" . number_format($row['f_shareCapital'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_feeCapital'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_fixedSaving'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_memberFund'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_memberSaving'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_alBai'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_alInnah'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_bPulihKenderaan'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_roadTaxInsurance'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_specialScheme'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_specialSeasonCarnival'], 2) . "</td>";
                    echo "<td>" . number_format($row['f_alQadrulHassan'], 2) . "</td>";
                    echo "<td>" . date('d-m-Y', strtotime($row['f_dateUpdated'])) . "</td>";
                echo "</tr>";
            }
          ?>
        </tbody>
      </table>

      <!-- Batch Action Buttons -->
      <div class="d-flex justify-content-center">
        <button type="submit" name="action" value="potongan_gaji" class="btn btn-primary mx-2">Potongan Gaji</button>
        <button type="submit" name="action" value="delete" class="btn btn-primary mx-2">Bayaran Pinjaman</button>
      </div>
    </form>
</div>

<!-- JavaScript to handle "Select All" checkbox -->
<script>
  function toggleSelectAll() {
    var checkboxes = document.getElementsByName('selected_members[]');
    var isChecked = document.getElementById('select_all').checked;
    for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].checked = isChecked;
    }
  }
</script>

</body>
</html>
