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

// Get the search query
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Base SQL query
$sql = "SELECT tb_member.*, tb_status.s_sid, tb_status.s_desc, 
        tb_tarikdiri.td_tarikdiriID, 
        DATE_FORMAT(tb_member.m_approvalDate, '%d-%m-%Y') AS formattedDate
        FROM tb_member
        LEFT JOIN tb_status ON tb_member.m_status = tb_status.s_sid
        LEFT JOIN tb_tarikdiri ON tb_member.m_memberNo = tb_tarikdiri.td_memberNo
        WHERE tb_member.m_status = 5";

// If a search query is provided, add it to the SQL query
if (!empty($search_query)) {
    $sql .= " AND (m_pfNo LIKE '%$search_query%' OR m_name LIKE '%$search_query%')";
}

// Add pagination
$sql .= " LIMIT $start_from, $records_per_page";

// Execute the SQL query
$result = mysqli_query($con, $sql);

if (!$result) {
    // If the query fails, output the error for debugging
    die("Query failed: " . mysqli_error($con));
}

// Get total records count for pagination
$total_sql = "SELECT COUNT(*) FROM tb_member WHERE m_status = 5";

if (!empty($search_query)) {
    $total_sql .= " AND (m_pfNo LIKE '%$search_query%' OR m_name LIKE '%$search_query%')";
}

$total_result = mysqli_query($con, $total_sql);

if (!$total_result) {
    die("Total count query failed: " . mysqli_error($con));
}

$total_row = mysqli_fetch_row($total_result);
$total_records = $total_row[0];
$total_pages = ceil($total_records / $records_per_page);

?>

<div class="container">
    <h2>Senarai Anggota Lepas</h2>
    
    <!-- Form for Search -->
    <form method="GET" action="">
        <div class="d-flex justify-content-end align-items-center mb-3">
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
                <th scope="col" class='text-center'>No. Applikasi</th>
                <th scope="col" class='text-center'>No. PF</th>
                <th scope="col">Nama</th>
                <th scope="col">No. Telefon</th>
                <th scope="col">Email</th>
                <th scope="col">Status</th>
                <th scope="col">Tarikh Berhenti</th>
                <th scope="col" class='text-center'>Butiran</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td class='text-center'>".$row['td_tarikdiriID']."</td>";        
                    echo "<td class='text-center'>".$row['m_pfNo']."</td>";           
                    echo "<td>".$row['m_name']."</td>";
                    echo "<td>".$row['m_phoneNumber']."</td>";
                    echo "<td>".$row['m_email']."</td>";
                    echo "<td>".$row['s_desc']."</td>";
                    echo "<td>".$row['formattedDate']."</td>";
                    echo "<td class='text-center'>";                                 
                    echo "<a href='past_member_details.php?id=".$row['m_pfNo']."' title='View Details'>";
                    echo "<i class='fa fa-ellipsis-h' aria-hidden='true'></i>";       
                    echo "</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='7' class='text-center text-muted'>Tiada maklumat untuk dipaparkan.</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <nav>
        <ul class="d-flex justify-content-center pagination pagination-sm">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page - 1; ?>&search=<?= urlencode($search_query) ?>">&laquo;</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search_query) ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page + 1; ?>&search=<?= urlencode($search_query) ?>">&raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
<br>
