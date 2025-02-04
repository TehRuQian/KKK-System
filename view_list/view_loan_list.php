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

$records_per_page = 10;  

$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$start_from = ($current_page - 1) * $records_per_page;

// Get the search query from the URL if available
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT tb_loan.*, 
               tb_member.m_name, tb_member.m_memberNo, tb_member.m_pfNo, 
               tb_ltype.lt_desc,
               DATE_FORMAT(tb_loan.l_approvalDate, '%d-%m-%Y') AS formattedDate 
        FROM tb_loan 
        LEFT JOIN tb_member ON tb_loan.l_memberNo = tb_member.m_memberNo 
        LEFT JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
        WHERE l_status = 3";

if (!empty($search_query)) {
    $sql .= " AND (
                tb_member.m_memberNo LIKE '%$search_query%' 
                OR tb_member.m_pfNo LIKE '%$search_query%'
                OR tb_loan.l_loanApplicationID LIKE '%$search_query%'
                OR tb_ltype.lt_desc LIKE '%$search_query%'
            )";
}

$sql .= " LIMIT $start_from, $records_per_page";

$result = mysqli_query($con, $sql);

$total_sql = "SELECT COUNT(*) FROM tb_loan 
              LEFT JOIN tb_member ON tb_loan.l_memberNo = tb_member.m_memberNo 
              LEFT JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
              WHERE l_status = 3";

if (!empty($search_query)) {
    $total_sql .= " AND (
                        tb_member.m_memberNo LIKE '%$search_query%' 
                        OR tb_member.m_pfNo LIKE '%$search_query%'
                        OR tb_loan.l_loanApplicationID LIKE '%$search_query%'
                        OR tb_ltype.lt_desc LIKE '%$search_query%'
                    )";
}

$total_result = mysqli_query($con, $total_sql);
$total_row = mysqli_fetch_row($total_result);
$total_records = $total_row[0];
$total_pages = ceil($total_records / $records_per_page);
?>

<div class="container">
    <h2>Senarai Peminjam Semasa</h2>

    <!-- Search Bar -->
    <div class="d-flex justify-content-end mb-3">
        <form method="GET" action="" class="d-flex">
            <input 
                type="text" 
                name="search" 
                class="form-control form-control-sm me-2" 
                placeholder="Cari Pinjaman..."
                value="<?= htmlspecialchars($search_query); ?>">
            <button class="btn btn-primary btn-sm" type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>


    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col" class='text-center'>No. Pinjaman</th>
            <th scope="col" class='text-center'>No. Anggota</th>
            <th scope="col" class='text-center'>No. PF</th>
            <th scope="col">Nama Anggota</th>
            <th scope="col" class="text-center">Jenis <br>Pinjaman</th>
            <th scope="col" class="text-center">Jumlah Permohonan (RM)</th>
            <th scope="col" class="text-center">Tempoh Pinjaman (Bulan)</th>
            <th scope="col" class="text-center">Ansuran Bulanan (RM)</th>
            <th scope="col" class="text-center">Tunggakan (RM)</th>
            <th scope="col" class='text-center'>Tarikh Lulus Pinjaman</th>
            <th scope="col" class='text-center'>Butiran</th>
        </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td class='text-center'>".$row['l_loanApplicationID']."</td>";
                    echo "<td class='text-center'>".$row['m_memberNo']."</td>";
                    echo "<td class='text-center'>".$row['m_pfNo']."</td>";
                    echo "<td>".$row['m_name']."</td>";
                    echo "<td class='text-center'>".$row['lt_desc']."</td>";
                    echo "<td class='text-center'>" . number_format($row['l_appliedLoan'], 2)."</td>";
                    echo "<td class='text-center'>".$row['l_loanPeriod']."</td>";
                    echo "<td class='text-center'>". number_format($row['l_monthlyInstalment'], 2)."</td>";
                    echo "<td class='text-center'>". number_format($row['l_loanPayable'], 2)."</td>";
                    echo "<td class='text-center'>".$row['formattedDate']."</td>"; 
                    echo "<td class='text-center'>";
                    echo "<a href='loan_details.php?id=".$row['l_loanApplicationID']."' title='View Details'>";
                    echo "<i class='fa fa-ellipsis-h' aria-hidden='true'></i>";       
                    echo "</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='11' class='text-center'>Tiada pinjaman diluluskan buat masa ini.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <nav>
        <ul class="d-flex justify-content-center pagination pagination-sm">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page - 1; ?>&search=<?= urlencode($search_query); ?>">&laquo;</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search_query); ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page + 1; ?>&search=<?= urlencode($search_query); ?>">&raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
