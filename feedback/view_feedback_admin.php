<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}
if ($_SESSION['u_type']!= 1) {
    header('Location: ../login.php');
    exit();
}

include '../header_admin.php';
include '../db_connect.php';

$status_filter=$_GET['status'] ?? '';

$type_filter=$_GET['type'] ?? '';

$where_clauses=[];

if (!empty($status_filter)){
    $where_clauses[]="tb_feedback.fb_status='$status_filter'";
}

if (!empty($type_filter)){
    $where_clauses[]="tb_feedback.fb_type='$type_filter'";
}

$where_sql=!empty($where_clauses) ? "WHERE " . implode(' AND ',$where_clauses) : '';

$records_per_page=10;

$current_page=isset($_GET['page']) ? $_GET['page'] : 1;
$start_from=($current_page-1)*$records_per_page;

$total_sql="SELECT COUNT(*) FROM tb_feedback
            $where_sql";
$total_result=mysqli_query($con, $total_sql);
$total_row=mysqli_fetch_row($total_result);
$total_records=$total_row[0];
$total_pages=ceil($total_records/$records_per_page);

$sql = "SELECT tb_feedback.*, 
               tb_status.s_desc AS status,
               tb_ftype.fb_desc AS type 
        FROM tb_feedback
        LEFT JOIN tb_status ON tb_feedback.fb_status=tb_status.s_sid
        LEFT JOIN tb_ftype ON tb_feedback.fb_type=tb_ftype.fb_id
        $where_sql
        ORDER BY tb_feedback.fb_submitDate DESC
        LIMIT $records_per_page OFFSET $start_from";
$result=mysqli_query($con, $sql);

if (!$result){
    die("Database query failed: " . mysqli_error($con));
}

?>

<style>
  table thead th{
    text-align: center;
    background-color: #f1f1f1;
  }

  table tbody td{
    text-align: center;
  }
  
</style>

<div class="container">
    <br>
    <h2>Senarai Maklum Balas</h2>
    <br>
    <form method="GET" action="" class="mb-3 d-flex justify-content-center">
        <select name="type" class="form-select me-2" style="width: 200px;">
            <option value="">-- Semua Jenis --</option>
            <?php
            $type_query="SELECT * FROM tb_ftype";
            $type_result=mysqli_query($con,$type_query);
            while ($type=mysqli_fetch_assoc($type_result)){
                $selected=(isset($_GET['filter_type']) && $_GET['filter_type']==$type['fb_id']) ? 'selected' : '';
                echo "<option value='{$type['fb_id']}' $selected>{$type['fb_desc']}</option>";
            }
            ?>
        </select>
        <select name="status" class="form-select me-2" style="width: 200px;">
            <option value="">-- Semua Status --</option>
            <?php
            $status_query="SELECT * FROM tb_status WHERE s_sid IN (1,7,8)";
            $status_result=mysqli_query($con,$status_query);
            while ($status=mysqli_fetch_assoc($status_result)){
                $selected=(isset($_GET['filter_status']) && $_GET['filter_status']==$status['s_sid']) ? 'selected' : '';
                echo "<option value='{$status['s_sid']}' $selected>{$status['s_desc']}</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">Tapis</button>
    </form>
<br>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Jenis</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-center">Tarikh Hantar</th>
                <th scope="col" class="text-center">Butiran</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_array($result)) : ?>
                <tr>
                    <td><?= $row['fb_feedbackID']; ?></td>
                    <td><?= $row['type']; ?></td>
                    <td><?= $row['status']; ?></td>
                    <td class="text-center"><?= date('d-m-Y H:i:s', strtotime($row['fb_submitDate'])); ?></td>
                    <td class="text-center">
                        <a href="butiran_feedback_admin.php?feedbackID=<?= $row['fb_feedbackID']; ?>"><i class='fa fa-ellipsis-h' aria-hidden='true'>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<br>
<nav>
    <ul class="d-flex justify-content-center pagination pagination-sm">
        <?php if ($current_page>1) : ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $current_page-1; ?>">&laquo;</a>
            </li>
        <?php endif; ?>

        <?php for ($i=1;$i<=$total_pages;$i++) : ?>
            <li class="page-item <?= ($i==$current_page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($current_page<$total_pages) : ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $current_page+1; ?>">&raquo;</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<br><br><br>