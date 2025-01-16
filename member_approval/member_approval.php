<?php

include('../kkksession.php');
if (!session_id()) {
    session_start();
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
$sql = "SELECT 
            tb_member.m_memberApplicationID,
            tb_member.m_pfNo,
            tb_member.m_name,
            DATE_FORMAT(tb_member.m_applicationDate, '%d-%m-%Y') AS formattedDate,
            tb_status.s_sid 
        FROM tb_member 
        LEFT JOIN tb_status ON tb_member.m_status = tb_status.s_sid
        WHERE m_applicationDate >= DATE_SUB('$currentDate', INTERVAL 3 MONTH) AND m_status = 1";

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
    <table class="table table-hover">
        <thead>
        <tr>
        <th scope="col">No. Aplikasi</th>
        <th scope="col">No. PF</th>
        <th scope="col">Nama Anggota</th>
        <th scope="col" class='text-center'>Tarikh Pohon</th>
        <th scope="col" class='text-center'>Butiran</th>
        </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>".$row['m_memberApplicationID'] . "</td>";
                echo "<td>".$row['m_pfNo'] . "</td>";
                echo "<td>".$row['m_name'] . "</td>";
                echo "<td class='text-center'>".$row['formattedDate'] . "</td>";
                echo "<td class='text-center'>";
                echo "<a href='member_approval_detail.php?id=".$row['m_memberApplicationID']."' title='View Details'>";
                echo "<i class='fa fa-ellipsis-h' aria-hidden='true'></i>"; // Icon for Butiran
                echo "</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

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