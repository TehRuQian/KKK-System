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

  $records_per_page = 20;
  $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
  $start_from = ($current_page - 1) * $records_per_page;

  $filter_month = $_GET['filter_month'] ?? '';
  $filter_year = $_GET['filter_year'] ?? '';
  $filter_member = $_GET['filter_member'] ?? '';

  $where_clauses = ["1 = 1"];
    if (!empty($filter_month)) {
        $where_clauses[] = "t.t_month = '$filter_month'";
    }
    if (!empty($filter_year)) {
        $where_clauses[] = "t.t_year = '$filter_year'";
    }
    if (!empty($filter_member)){
        $where_clauses[] = "t.t_memberNo = '$filter_member'";
    }
  $where_sql = implode(' AND ', $where_clauses);

  $sql_transaction = "SELECT t.*, m.m_name, tt.tt_desc, rm.rm_desc
                      FROM tb_transaction t
                      LEFT JOIN tb_member m ON t.t_memberNo = m.m_memberNo
                      LEFT JOIN tb_ttype tt ON t.t_transactionType = tt.tt_id
                      LEFT JOIN tb_rmonth rm ON t.t_month = rm.rm_id
                      WHERE $where_sql
                      ORDER BY t.t_transactionDate DESC
                      LIMIT $start_from, $records_per_page;";

  $result_transaction = mysqli_query($con, $sql_transaction);

  $total_sql = "SELECT COUNT(*) FROM tb_transaction t WHERE $where_sql;";
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

<!-- Main Content -->
<div class="container">
    <h2>Sejarah Transaksi</h2>
    <!-- Filters -->
    <form method="GET" action="" class="mb-3 d-flex justify-content-center">
        <select name="filter_month" class="form-select me-2" style="width: 200px;">
            <option value="">Pilih Bulan</option>
            <?php
            $month_query = "SELECT * FROM tb_rmonth";
            $month_result = mysqli_query($con, $month_query);
            while ($month = mysqli_fetch_assoc($month_result)) {
                $selected = (isset($_GET['filter_month']) && $_GET['filter_month'] == $month['rm_id']) ? 'selected' : '';
                echo "<option value='{$month['rm_id']}' $selected>{$month['rm_desc']}</option>";
            }
            ?>
        </select>
        <select name="filter_year" class="form-select me-2" style="width: 200px;">
            <option value="">Pilih Tahun</option>
            <?php
            $year_query = "SELECT DISTINCT t_year FROM tb_transaction ORDER BY t_year DESC";
            $year_result = mysqli_query($con, $year_query);
            while ($year = mysqli_fetch_assoc($year_result)) {
                $selected = (isset($_GET['filter_year']) && $_GET['filter_year'] == $year['t_year']) ? 'selected' : '';
                echo "<option value='{$year['t_year']}' $selected>{$year['t_year']}</option>";
            }
            ?>
        </select>
        <input type="text" name="filter_member" class="form-control me-2" placeholder="No Anggota" value="<?= $filter_member; ?>" style="width: 200px;">
        <button type="submit" class="btn btn-primary">Tapis</button>
    </form>

    <table class="table table-hover">
    <!-- <table class="table table-hover" style="text-align: center;"> -->
        <thead>
            <tr>
                <th>ID</th>
                <th>Tarikh</th>
                <th>No Anggota</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Amaun (RM)</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Ulasan</th>
                <th>Bukti</th>
                <th>No Kerani</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result_transaction)) { ?>
                <tr>
                    <td><?php echo $row['t_transactionID'] ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row['t_transactionDate'])) ?></td>
                    <td><?php echo $row['t_memberNo'] ?></td>
                    <td><?php echo $row['m_name'] ?></td>
                    <td><?php echo $row['tt_desc'] ?></td>
                    <td><?php echo number_format($row['t_transactionAmt'],2) ?></td>
                    <td><?php echo $row['rm_desc'] ?></td>
                    <td><?php echo $row['t_year'] ?></td>
                    <td><?php echo $row['t_desc'] ?></td>
                    <td>
                        <?php
                        $file_path = "bukti_transaksi/" . $row['t_proof'];
                        $pdf_url = 'http://' . $_SERVER['HTTP_HOST'] . '/KKK-System/transaksi/' . $file_path;

                        if (file_exists($file_path) && !empty($row['t_proof'])) : ?>
                            <a href="<?php echo $pdf_url; ?>" class="btn btn-primary" target="_blank">
                                <i class="fas fa-external-link"></i> Lihat
                            </a>
                        <?php else : ?>
                            <span>Tiada bukti</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['t_adminID'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <nav>
        <ul class="d-flex justify-content-center pagination pagination-sm">
            <?php if($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page - 1; ?>&filter_month=<?= $filter_month; ?>&filter_year=<?= $filter_year; ?>&filter_member=<?= $filter_member; ?>">&laquo;</a>
                </li>
            <?php endif; ?>

            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?= $i; ?>&filter_month=<?= $filter_month; ?>&filter_year=<?= $filter_year; ?>&filter_member=<?= $filter_member; ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page + 1; ?>&filter_month=<?= $filter_month; ?>&filter_year=<?= $filter_year; ?>&filter_member=<?= $filter_member; ?>">&raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
