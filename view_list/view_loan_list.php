<?php

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Number of records of member per page
$records_per_page = 10;  

// Get the current page from the URL (default to 1 if not set)
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

// Retrieve all registered Course
$sql = "SELECT * FROM tb_loan 
        LEFT JOIN tb_member ON tb_loan.l_memberNo = tb_member.m_memberNo 
        WHERE l_status = 3
        LIMIT $start_from, $records_per_page";

//  Execute the SQL statement on DB
$result = mysqli_query($con, $sql);

// Get total records count for pagination
$total_sql = "SELECT COUNT(*) FROM tb_loan WHERE l_status = 3";
$total_result = mysqli_query($con, $total_sql);
$total_row = mysqli_fetch_row($total_result);
$total_records = $total_row[0];
$total_pages = ceil($total_records / $records_per_page);
?>

<div class="container">
<h2>Senarai Peminjam</h2>
    <br>
    <table class="table table-hover">
        <thead>
        <tr>
        <th scope="col" class='text-center'>No. Pinjaman</th>
        <th scope="col" class='text-center'>No. Anggota</th>
        <th scope="col">Nama Anggota</th>
        <th scope="col" class='text-center'>Jumlah Yang Dipinjam</th>
        <th scope="col" class='text-center'>Tarikh Lulus</th>
        <th scope="col" class='text-center'>Butiran</th>
        </tr>
        </thead>
        <tbody>
            <?php
            while($row = mysqli_fetch_array($result))
            {
                echo "<tr>";
                echo "<td class='text-center'>".$row['l_loanApplicationID']."</td>";
                echo "<td class='text-center'>".$row['l_memberNo']."</td>";
                echo "<td>".$row['m_name']."</td>";
                echo "<td class='text-center'>".$row['l_appliedLoan']."</td>";
                echo "<td class='text-center'>".$row['l_approvalDate']."</td>";
                echo "<td class='text-center'>";
                echo "<a href='loan_details.php?id=".$row['l_loanApplicationID']."' title='View Details'>";
                echo "<i class='fa fa-ellipsis-h' aria-hidden='true'></i>";       
                echo "</a>";
                echo "</td>";
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

</div>
<br><br>
<?php
include '../footer.php';
?>
