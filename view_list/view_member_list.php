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

// Check for session variable to show success message once
if (isset($_SESSION['status_update_success']) && $_SESSION['status_update_success']) {
    echo "<script>
        Swal.fire('Berjaya!', 'Status anggota telah dikemaskini!', 'success');
    </script>";
    unset($_SESSION['status_update_success']); 
}

$records_per_page = 10;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT *, DATE_FORMAT(m_approvalDate, '%d-%m-%Y') AS formattedDate 
        FROM tb_member 
        WHERE (m_status = '3' OR m_status = '6')";

if (!empty($search_query)) {
    $sql .= " AND (m_memberNo LIKE '%$search_query%' OR m_name LIKE '%$search_query%' OR m_pfNo LIKE '%$search_query%')";
}

$sql .= " ORDER BY m_approvalDate ASC LIMIT $start_from, $records_per_page";
$result = mysqli_query($con, $sql);

$total_sql = "SELECT COUNT(*) FROM tb_member WHERE (m_status = '3' OR m_status = '6')";
if (!empty($search_query)) {
    $total_sql .= " AND (m_memberNo LIKE '%$search_query%' OR m_name LIKE '%$search_query%' OR m_pfNo LIKE '%$search_query%')";
}
$total_result = mysqli_query($con, $total_sql);
$total_row = mysqli_fetch_row($total_result);
$total_records = $total_row[0];
$total_pages = ceil($total_records / $records_per_page);
?>

<div class="container">
    <h2>Senarai Anggota Semasa</h2>
    
    <form method="GET" action="">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="editModeSwitch" name="edit_mode" value="1" onchange="this.form.submit()" <?= isset($_GET['edit_mode']) && $_GET['edit_mode'] == 1 ? 'checked' : ''; ?>>
                <label class="form-check-label" for="editModeSwitch">Mod Penyuntingan</label>
            </div>

            <div class="input-group" style="max-width: 300px;">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari anggota..." value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button class="btn btn-primary btn-sm ms-2" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </form>

    <table class="table table-hover table-responsive">
    <thead>
        <tr>
            <th class='text-center'>No. Anggota</th>
            <th class='text-center'>No. PF</th>
            <th>Nama Anggota</th>
            <th>No. Telefon</th>
            <th>Email</th>
            <th>Status</th>
            <th class='text-center'>Tarikh Masuk</th>
            <th class='text-center'>Butiran</th>
            <?php if (isset($_GET['edit_mode']) && $_GET['edit_mode'] == 1): ?>
                <th class='text-center'>Tindakan</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = mysqli_fetch_array($result)) {
            $status_label = ($row['m_status'] == 3) ? 'Aktif' : 'Pencen';
            echo "<tr>";
            echo "<td class='text-center'>{$row['m_memberNo']}</td>";        
            echo "<td class='text-center'>{$row['m_pfNo']}</td>";           
            echo "<td>{$row['m_name']}</td>";
            echo "<td>{$row['m_phoneNumber']}</td>";
            echo "<td>{$row['m_email']}</td>";
            echo "<td>{$status_label}</td>";
            echo "<td class='text-center'>{$row['formattedDate']}</td>"; 
            echo "<td class='text-center'><a href='member_details.php?id={$row['m_memberNo']}'><i class='fa fa-ellipsis-h'></i></a></td>";

            if (isset($_GET['edit_mode']) && $_GET['edit_mode'] == 1) {
                echo "<td class='text-center'>"; 
                echo "<select class='form-select form-select-sm' id='status_{$row['m_memberNo']}' onchange='confirmStatusChange({$row['m_memberNo']}, {$row['m_status']})'>"; 
                echo "<option value=''>Pilih Status</option>"; 
                echo "<option value='6'>Pencen</option>"; 
                echo "<option value='5'>Berhenti</option>"; 
                echo "</select>"; 
                echo "</td>";
            } 
            echo "</tr>"; 
        } 
        ?>
    </tbody>
</table>

<nav>
    <ul class="d-flex justify-content-center pagination pagination-sm">
        <?php if ($current_page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $current_page - 1; ?>&search=<?= urlencode($search_query) ?>&edit_mode=<?= isset($_GET['edit_mode']) ? $_GET['edit_mode'] : '' ?>">&laquo;</a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search_query) ?>&edit_mode=<?= isset($_GET['edit_mode']) ? $_GET['edit_mode'] : '' ?>"><?= $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $current_page + 1; ?>&search=<?= urlencode($search_query) ?>&edit_mode=<?= isset($_GET['edit_mode']) ? $_GET['edit_mode'] : '' ?>">&raquo;</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status_change'], $_POST['member_no'], $_POST['ulasan'])) {
    $_SESSION['status_update_success'] = false;

    $new_status = $_POST['status_change'];
    $member_no = $_POST['member_no'];
    $ulasan = $_POST['ulasan'];
    $admin_id = $_SESSION['u_id'];
    $approval_date = date('Y-m-d');

    $update_sql = "UPDATE tb_member SET m_status = '$new_status' WHERE m_memberNo = '$member_no'";
    if (mysqli_query($con, $update_sql)) {
        if ($new_status == 5) {
            $insert_tarikdiri = "INSERT INTO tb_tarikdiri (td_memberNo, td_ulasan, td_status, td_approvalDate, td_adminID) 
                                 VALUES ('$member_no', '$ulasan', '3', '$approval_date', '$admin_id')";
            mysqli_query($con, $insert_tarikdiri);
        }
        $_SESSION['status_update_success'] = true; // Set success flag
        echo "<script>
            Swal.fire('Berjaya!', 'Status anggota telah dikemaskini!', 'success').then(() => {
                window.location.href = 'view_member_list.php';
            });
        </script>";
    } else {
        echo "<script>Swal.fire('Gagal!', 'Gagal mengemaskini status anggota!', 'error');</script>";
    }
}
?>

<script>
function confirmStatusChange(memberNo, currentStatus) {
    var status = document.getElementById('status_' + memberNo).value;
    if (!status) return;

    Swal.fire({
        title: 'Adakah anda pasti?',
        text: "Status anggota ini akan dikemaskini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Kemaskini!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            if (status == '5' && currentStatus == '3') {
                Swal.fire({
                    title: 'Masukkan Ulasan',
                    input: 'text',
                    inputPlaceholder: 'Masukkan ulasan...',
                    showCancelButton: true,
                    confirmButtonText: 'Hantar'
                }).then((inputResult) => {
                    if (inputResult.isConfirmed) {
                        submitForm(memberNo, status, inputResult.value);
                    }
                });
            } else {
                submitForm(memberNo, status, currentStatus == 6 ? 'Pencen' : '');
            }
        }
    });
}

function submitForm(memberNo, status, ulasan) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.innerHTML = `<input type='hidden' name='status_change' value='${status}'>
                      <input type='hidden' name='member_no' value='${memberNo}'>
                      <input type='hidden' name='ulasan' value='${ulasan}'>`;
    document.body.appendChild(form);
    form.submit();
}
</script>
