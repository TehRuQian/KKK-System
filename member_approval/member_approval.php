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

// Number of records of member per page
$records_per_page = 10;  

// Get the current page from the URL (default to 1 if not set)
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

// Get current date and time in timestamp format
$currentDate = date('Y-m-d H:i:s');

// Get User ID
$uid = $_SESSION['u_id'];

// Modify SQL query to format m_applicationDate to date-month-year format
$sql = "SELECT *,
        DATE_FORMAT(tb_member.m_applicationDate, '%d-%m-%Y') AS formattedDate
        FROM tb_member 
        WHERE m_status = 1
        LIMIT $start_from, $records_per_page;";

// Execute the SQL statement on DB
$result = mysqli_query($con, $sql);

// Get total records count for pagination
$total_sql = "SELECT COUNT(*) FROM tb_member WHERE m_status = 1";
$total_result = mysqli_query($con, $total_sql);
$total_row = mysqli_fetch_row($total_result);
$total_records = $total_row[0];
$total_pages = ceil($total_records / $records_per_page);
?>

<div class="container">
    <h2>Permohonan Anggota</h2>
    <br>
    <form id="memberapplicationForm" method="POST" action="member_batch_approval_process.php">
        <input type="hidden" id="action" name="action" value=""> 
        <input type="hidden" name="mstatus" id="mstatus">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="select_all" onclick="toggleSelectAll()"> Pilih Semua
                    </th>
                    <th scope="col">No. Aplikasi</th>
                    <th scope="col">No. PF</th>
                    <th scope="col">Nama Anggota</th>
                    <th scope="col">No. Telefon</th>
                    <th scope="col">Email</th>
                    <th scope="col" class='text-center'>Tarikh Pohon</th>
                    <th scope="col" class='text-center'>Butiran</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='selected_members[]' data-name='" . htmlspecialchars($row['m_name']) . "' value='" . $row['m_memberApplicationID'] . "'></td>";
                        echo "<td>".$row['m_memberApplicationID'] . "</td>";
                        echo "<td>".$row['m_pfNo'] . "</td>";
                        echo "<td>".$row['m_name'] . "</td>";
                        echo "<td>".$row['m_phoneNumber'] . "</td>";
                        echo "<td>".$row['m_email'] . "</td>";
                        echo "<td class='text-center'>".$row['formattedDate'] . "</td>";
                        echo "<td class='text-center'>";
                        echo "<a href='member_approval_detail.php?id=".$row['m_memberApplicationID']."' title='View Details'>";
                        echo "<i class='fa fa-ellipsis-h' aria-hidden='true'></i>";
                        echo "</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Tiada permohonan buat masa ini.</td></tr>";
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

        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-success mx-1" onclick="confirmApproval()">Lulus</button>
            <button type="button" class="btn btn-danger mx-1" onclick="confirmRejection()">Tolak</button>
        </div>
    </form>
</div>

<script>
<?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
    // Show the success notification
    Swal.fire({
      title: "Berjaya!",
      text: "<?php echo isset($_GET['message']) ? htmlspecialchars($_GET['message']) : ''; ?>",
      icon: "success"
    }).then(() => {
      // After showing the notification, remove the query parameters from the URL
      history.replaceState(null, '', window.location.pathname);
    });
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
      document.getElementById('memberapplicationForm').submit();
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
      document.getElementById('action').value = "reject";
      document.getElementById('memberapplicationForm').submit();
    }
  });
}
</script>
