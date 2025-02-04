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

  $sql = "SELECT tb_tarikdiri.*, tb_member.m_name, tb_member.m_memberNo
          FROM tb_tarikdiri
          INNER JOIN tb_member
          ON tb_tarikdiri.td_memberNo = tb_member.m_memberNo
          WHERE tb_tarikdiri.td_status = 1
          LIMIT $start_from, $records_per_page;";
  $result_tarikdiri = mysqli_query($con, $sql);

  $total_sql = "SELECT COUNT(*) FROM tb_tarikdiri WHERE td_status = 1;";
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

    <form id="tarikdiriForm" method="POST" action="berhenti_batch_approval_process.php">
      <input type="hidden" id="action" name="action" value=""> 
      <input type="hidden" id="rejectionData" name="rejectionData" value=""> 

      <table class="table table-hover">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="select_all" onclick="toggleSelectAll()"> Pilih Semua
                    </th>
                    <th>ID</th>
                    <th>No. Anggota</th>
                    <th>Nama</th>
                    <th>Alasan</th>
                    <th>Tarikh Hantar</th>
                    <th>Butiran</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_tarikdiri) > 0): ?>
                    <?php while($row = mysqli_fetch_array($result_tarikdiri)): ?>
                        <tr>
                            <td><input type="checkbox" name="selected_members[]" value="<?= $row['td_tarikdiriID']; ?>" data-name="<?= htmlspecialchars($row['m_name']); ?>"></td>
                            <td><?= $row['td_tarikdiriID']; ?></td>
                            <td><?= $row['m_memberNo']; ?></td>
                            <td><?= htmlspecialchars($row['m_name']); ?></td>
                            <td><button type="button" class="btn btn-link alasan-btn" data-alasan="<?= htmlspecialchars($row['td_alasan']); ?>">Lihat Alasan</button></td>
                            <td><?= date('d-m-Y', strtotime($row['td_submitDate'])); ?></td>
                            <td><a href="berhenti_approval_detail.php?berhentiID=<?= $row['td_tarikdiriID']; ?>&memberNo=<?= $row['m_memberNo']; ?>"><i class="fa fa-ellipsis-h"></i></a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Tiada permohonan pada masa ini</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


      <!-- Pagination Start -->
      <nav>
        <ul class="d-flex justify-content-center pagination pagination-sm">
          <?php if($current_page > 1): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $current_page - 1; ?>">&laquo;</a></li>
          <?php endif; ?>
          <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
            </li>
          <?php endfor; ?>
          <?php if($current_page < $total_pages): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $current_page + 1; ?>">&raquo;</a></li>
          <?php endif; ?>
        </ul>
      </nav>
      <!-- Pagination End -->

      <div class="d-flex justify-content-center">
        <button type="button" class="btn btn-success mx-1" onclick="confirmApproval()">Lulus</button>
        <button type="button" class="btn btn-danger mx-1" onclick="confirmRejection()">Tolak</button>
      </div>
    </form>
</div>

<script>
<?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
  Swal.fire("Berjaya!", "Permohonan telah diproses.", "success");
<?php endif; ?>

function toggleSelectAll() {
  let selectAllCheckbox = document.getElementById('select_all');
  let checkboxes = document.querySelectorAll('input[name="selected_members[]"]');
  checkboxes.forEach((checkbox) => {
    checkbox.checked = selectAllCheckbox.checked;
  });
}

function confirmApproval() {
  let selected = document.querySelectorAll('input[name="selected_members[]"]:checked');

  if (selected.length === 0) {
    Swal.fire("Ralat!", "Sila pilih sekurang-kurangnya satu permohonan.", "error");
    return;
  }

  Swal.fire({
    title: "Adakah anda pasti?",
    text: "Anda akan meluluskan permohonan yang dipilih.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya, teruskan",
    cancelButtonText: "Batal"
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('action').value = "approve";
      document.getElementById('tarikdiriForm').submit();
    }
  });
}

function confirmRejection() {
  let selected = document.querySelectorAll('input[name="selected_members[]"]:checked');

  if (selected.length === 0) {
    Swal.fire("Ralat!", "Sila pilih sekurang-kurangnya satu permohonan untuk ditolak.", "error");
    return;
  }

  Swal.fire({
    title: "Adakah anda pasti?",
    text: "Anda akan menolak permohonan yang dipilih.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Ya, teruskan",
    cancelButtonText: "Batal"
  }).then((result) => {
    if (result.isConfirmed) {
      let rejectionData = [];
      let index = 0;

      function askUlasan() {
        if (index >= selected.length) {
          document.getElementById('rejectionData').value = JSON.stringify(rejectionData);
          document.getElementById('action').value = "reject";
          document.getElementById('tarikdiriForm').submit();
          return;
        }

        let memberName = selected[index].dataset.name;
        let memberID = selected[index].value;

        Swal.fire({
          title: `Masukkan Ulasan untuk ${memberName}`,
          input: "text",
          inputPlaceholder: "Sebab penolakan...",
          inputValidator: (value) => {
            if (!value) return "Ulasan diperlukan!";
          }
        }).then((result) => {
          if (result.isConfirmed) {
            rejectionData.push({ id: memberID, ulasan: result.value });
            index++;
            askUlasan();
          }
        });
      }

      askUlasan();
    }
  });
}

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".alasan-btn").forEach(button => {
        button.addEventListener("click", function () {
            let alasan = this.getAttribute("data-alasan") || "Tiada alasan diberikan.";
            Swal.fire({
                title: "Alasan",
                text: alasan,
                icon: "info"
            });
        });
    });
});

</script>
