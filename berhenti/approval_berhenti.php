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

  $records_per_page = 10;  
  $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
  $start_from = ($current_page - 1) * $records_per_page;

  $sql = "
    SELECT tb_tarikdiri.*, tb_member.m_name 
    FROM tb_tarikdiri
    INNER JOIN tb_member
    ON tb_tarikdiri.td_memberNo = tb_member.m_memberNo
    LIMIT $start_from, $records_per_page;";
  $result_tarikdiri = mysqli_query($con, $sql);

  $total_sql = "SELECT COUNT(*) FROM tb_tarikdiri;";
  $total_result = mysqli_query($con, $total_sql);
  $total_row = mysqli_fetch_row($total_result);
  $total_records = $total_row[0];
  $total_pages = ceil($total_records / $records_per_page);
?>

<style>
  table td, table th {
    vertical-align: middle; 
  }
</style>

<div class="container">
    <h2>Permohonan Berhenti Menjadi Anggota</h2>

    <!-- Form for processing -->
    <form id="tarikdiriForm" method="POST">
      <!-- Table for withdrawal statuses -->
      <table class="table table-hover">
        <thead>
          <tr>
            <!-- Checkbox for batch selection -->
            <th scope="col">
              <input class="form-check-input" type="checkbox" id="select_all" onclick="toggleSelectAll()"> Select All
            </th>
            <th scope="col">ID</th>
            <th scope="col">No. Anggota</th>
            <th scope="col">Nama</th>
            <th scope="col">Alasan</th>
            <th scope="col">Tarikh Hantar</th>
            <th scope="col">Butiran</th>
          </tr>
        </thead>
        <tbody>
          <?php
            while($row = mysqli_fetch_array($result_tarikdiri)){
              echo "<tr>";
                echo "<td><input class='form-check-input' type='checkbox' name='selected_members[]' value='" . $row['td_tarikdiriID'] . "'></td>";
                echo "<td>" . $row['td_tarikdiriID'] . "</td>";
                echo "<td>" . $row['td_memberNo'] . "</td>";
                echo "<td>" . htmlspecialchars($row['m_name']) . "</td>";
                echo "<td><button type='button' class='btn btn-link alasan-btn' data-alasan='" . htmlspecialchars($row['td_alasan']) . "'>Lihat Alasan</button></td>";
                echo "<td>" . date('d-m-Y', strtotime($row['td_submitDate'])) . "</td>";
                echo "<td><a href='butiran_berhenti.php?berhentiID=" . $row['td_tarikdiriID'] . "'><i class='fa fa-ellipsis-h' aria-hidden='true'></i></a></td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>

      <nav>
        <ul class="d-flex justify-content-center pagination pagination-sm">
          <?php if($current_page > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $current_page - 1; ?>">&laquo;</a>
            </li>
          <?php endif; ?>

          <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
            </li>
          <?php endfor; ?>

          <?php if($current_page < $total_pages): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?= $current_page + 1; ?>">&raquo;</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
      <br>
      <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-success mx-2">Lulus</button>
        <button type="submit" class="btn btn-danger mx-2">Tolak</button>
      </div>
      <br>
      <div class="d-flex justify-content-center">
        <button type="submit" class="btn btn-primary mx-2">Submit</button>
      </div>
      <br>
    </form>
</div>

<!-- SweetAlert and JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Handle "Select All" checkbox
  function toggleSelectAll() {
    var checkboxes = document.getElementsByName('selected_members[]');
    var isChecked = document.getElementById('select_all').checked;
    for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].checked = isChecked;
    }
  }

  // Handle individual "Lihat Alasan" button
  document.querySelectorAll('.alasan-btn').forEach(button => {
    button.addEventListener('click', function() {
      const alasan = this.dataset.alasan;

      Swal.fire({
        title: 'Alasan',
        text: alasan,
        icon: 'info',
        confirmButtonText: 'OK',
        allowOutsideClick: false
      });
    });
  });
</script>
</body>
</html>
