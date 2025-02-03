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

// Number of records per page
$records_per_page = 10;

// Get the current page from the URL (default to 1 if not set)
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

// Get the sort and order from the URL
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'm_approvalDate'; // Default sort column
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc'; // Default order

// Get the search query
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Base SQL query
$sql = "SELECT *, DATE_FORMAT(m_approvalDate, '%d-%m-%Y') AS formattedDate 
        FROM tb_member 
        WHERE (m_status = '3' OR m_status = '6')";

if (!empty($search_query)) {
    $sql .= " AND (m_memberNo LIKE '%$search_query%' OR m_name LIKE '%$search_query%' OR m_pfNo LIKE '%$search_query%')";
}

$sql .= " ORDER BY m_approvalDate ASC";
$sql .= " LIMIT $start_from, $records_per_page";

// Execute the SQL query
$result = mysqli_query($con, $sql);

// Get total records count for pagination
$total_sql = "SELECT COUNT(*) 
              FROM tb_member 
              WHERE (m_status = '3' OR m_status = '6')";

if (!empty($search_query)) {
    $total_sql .= " AND (m_memberNo LIKE '%$search_query%' OR m_name LIKE '%$search_query%' OR m_pfNo LIKE '%$search_query%')";
}

$total_result = mysqli_query($con, $total_sql);
$total_row = mysqli_fetch_row($total_result);
$total_records = $total_row[0];
$total_pages = ceil($total_records / $records_per_page);

// Determine the next order direction
$next_order = ($order === 'asc') ? 'desc' : 'asc';
?>

<div class="container">
    <h2>Senarai Anggota Semasa</h2>
    
    <!-- Form for Search and Editing Mode -->
    <form method="GET" action="">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Switch for Editing Mode -->
            <div class="form-check form-switch">
                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    id="editModeSwitch" 
                    name="edit_mode" 
                    value="1" 
                    onchange="this.form.submit()" 
                    <?= isset($_GET['edit_mode']) && $_GET['edit_mode'] == 1 ? 'checked' : ''; ?>>
                <label class="form-check-label" for="editModeSwitch">Mod Penyuntingan</label>
            </div>

            <!-- Search Bar -->
            <div class="input-group" style="max-width: 300px;">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control form-control-sm" 
                    placeholder="Cari anggota..."
                    value="<?= isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button class="btn btn-primary btn-sm ms-2" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <table class="table table-hover table-responsive">
    <thead>
        <tr>
            <th scope="col" class='text-center'>No. Anggota</th>
            <th scope="col" class='text-center'>No. PF</th>
            <th scope="col">Nama Anggota</th>
            <th scope="col">No. Telefon</th>
            <th scope="col">Email</th>
            <th scope="col">Status</th>
            <th scope="col" class='text-center'>Tarikh Masuk</th>
            <th scope="col" class='text-center'>Butiran</th>
            <?php if (isset($_GET['edit_mode']) && $_GET['edit_mode'] == 1): ?>
                    <th scope="col" class='text-center'>Tindakan</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                // Determine the status label
                if ($row['m_status'] == 3) {
                    $status_label = 'Aktif';
                } elseif ($row['m_status'] == 6) {
                    $status_label = 'Pencen';
                } else {
                    $status_label = 'Unknown';
                }

                echo "<tr>";
                echo "<td class='text-center'>".$row['m_memberNo']."</td>";        
                echo "<td class='text-center'>".$row['m_pfNo']."</td>";           
                echo "<td>".$row['m_name']."</td>";
                echo "<td>".$row['m_phoneNumber']."</td>";
                echo "<td>".$row['m_email']."</td>";
                echo "<td>".$status_label."</td>"; // Display the status label
                echo "<td class='text-center'>".$row['formattedDate']."</td>"; 
                echo "<td class='text-center'>";                                 
                echo "<a href='member_details.php?id=".$row['m_memberNo']."' title='View Details'>";
                echo "<i class='fa fa-ellipsis-h' aria-hidden='true'></i>";       
                echo "</a>";
                echo "</td>";

                // If editing mode is enabled, add the dropdown to change status
                if (isset($_GET['edit_mode']) && $_GET['edit_mode'] == 1) {
                    echo "<td class='text-center'>";
                    echo "<form method='POST' action='' id='statusChangeForm_".$row['m_memberNo']."'>";
                    echo "<select name='status_change' class='form-select form-select-sm' id='status_".$row['m_memberNo']."' onchange='confirmStatusChange(".$row['m_memberNo'].")'>";
                    echo "<option value=''>Pilih Status</option>";
                    echo "<option value='6' ".($row['m_status'] == 6 ? 'selected' : '').">Pencen</option>";
                    echo "<option value='5' ".($row['m_status'] == 5 ? 'selected' : '').">Berhenti</option>";
                    echo "</select>";
                    echo "<input type='hidden' name='member_no' value='".$row['m_memberNo']."'>";
                    echo "</form>";
                    echo "</td>";
                }

                echo "</tr>";
            }
        } else {
            echo "<tr>";
            echo "<td colspan='8' class='text-center text-muted'>Tiada maklumat untuk dipaparkan.</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
// Handle Status Change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status_change']) && isset($_POST['member_no'])) {
    $new_status = $_POST['status_change'];
    $member_no = $_POST['member_no'];

    // Update the member's status
    $update_sql = "UPDATE tb_member SET m_status = '$new_status' WHERE m_memberNo = '$member_no'";
    if (mysqli_query($con, $update_sql)) {
        // If the status is 'Berhenti', send an email
        if ($new_status == 5) {
            $query = "SELECT m_email, m_name FROM tb_member WHERE m_memberNo = '$member_no'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $email = $row['m_email'];
            $name = $row['m_name'];

            $subject = "Status Anggota: Berhenti";
            $message = "Salam $name,\n\nKami ingin memaklumkan bahawa anda telah dikeluarkan sebagai anggota Koperasi Kakitangan KADA.\n\n Terima kasih atas sokongan anda. Sebarang pertanyaan boleh merujuk ke pejabat pihak kami.\n\nSalam hormat.";
            $headers = "From: admin@example.com";

            mail($email, $subject, $message, $headers);
        }

        echo "<script>
            Swal.fire({
                title: 'Berjaya!',
                text: 'Status anggota telah dikemaskini!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?page=$current_page&edit_mode=1'; // Maintain the current page and edit mode
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal mengemaskini status anggota!',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '?page=$current_page&edit_mode=1'; // Maintain the current page and edit mode
                }
            });
        </script>";
    }
}
?>

<!-- Pagination -->
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
</div>

<script>
    function confirmStatusChange(memberNo) {
        var status = document.getElementById('status_' + memberNo).value;
        if (status === '5' || status === '6') {
            Swal.fire({
                title: 'Adakah anda pasti?',
                text: "Status anggota ini akan dikemaskini ke status yang dipilih.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kemaskini!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if confirmed
                    document.getElementById('statusChangeForm_' + memberNo).submit();
                } else {
                    // Reset the dropdown if cancelled
                    document.getElementById('status_' + memberNo).value = '';
                }
            });
        }
    }
</script>
<br>
