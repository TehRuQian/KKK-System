<?php 
include('../kkksession.php');
if(!session_id())
{
  session_start();
}
if ($_SESSION['u_type']!=2) {
  header('Location: ../login.php');
  exit();
}

if(isset($_SESSION['u_id'])!=session_id())
{
  header('Location:../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$u_id=$_SESSION['funame'];


$status_filter=$_GET['status'] ?? '';

$type_filter=$_GET['type'] ?? '';

$where_clauses=["tb_feedback.fb_memberNo='$u_id'"];

if (!empty($status_filter)){
    $where_clauses[]="tb_feedback.fb_status='$status_filter'";
}

if (!empty($type_filter)){
    $where_clauses[]="tb_feedback.fb_type='$type_filter'";
}

$where_sql=implode(' AND ',$where_clauses);

$records_per_page=10;
$current_page=isset($_GET['page']) ? (int)$_GET['page']:1;
$offset=($current_page-1)*$records_per_page;

$count_sql="SELECT COUNT(*) AS total 
            FROM tb_feedback 
            WHERE $where_sql";

$count_result=mysqli_query($con,$count_sql);
$total_records=mysqli_fetch_assoc($count_result)['total'];

$total_pages=ceil($total_records/$records_per_page);

$sql ="SELECT tb_feedback.*, 
               tb_status.s_desc AS status,
               tb_ftype.fb_desc AS type 
      FROM tb_feedback
      LEFT JOIN tb_status ON tb_feedback.fb_status=tb_status.s_sid
      LEFT JOIN tb_ftype ON tb_feedback.fb_type=tb_ftype.fb_id
      WHERE $where_sql
      ORDER BY tb_feedback.fb_submitDate DESC
      LIMIT $records_per_page OFFSET $offset";

$result=mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}
?>

<style>
  body {
    background-color: #f9f9f9;
  }

  table thead th {
    text-align: center;
    background-color: #f1f1f1;
  }

  table tbody td {
    text-align: center;
  }
  
</style>

<div class="my-3"></div>
<h2 style="text-align:center;">Status Maklum Balas</h2><br>

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

<div class="card mb-3 col-10 my-5 mx-auto">
  <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
    <span>Status Maklum Balas</span>
    <div>
      <button type="button" class="btn btn-success me-2" onclick="window.location.href='submit_feedback.php'">Membuat Maklum Balas</button> 
      <button type="button" class="btn btn-info" onclick="window.location.href='../member_main/member.php'">Kembali</button>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Jenis</th>
          <th scope="col">Tarikh Hantar</th>
          <th scope="col">Status</th>
          <th scope="col">Butiran</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row=mysqli_fetch_assoc($result)) {
        ?>
        <tr>
          <td scope="row"><?= $row['fb_feedbackID']; ?></td>
          <td scope="row"><?= $row['type']; ?></td>
          <td><?= date('d-m-Y H:i:s', strtotime($row['fb_submitDate'])); ?></td>
          <td><?= $row['status']; ?></td>
          <td>
            <a href="butiran_feedback.php?feedbackID=<?= $row['fb_feedbackID']; ?>"><i class='fa fa-ellipsis-h' aria-hidden='true'></i>
            </a>
          </td>
        </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<nav>
  <ul class="pagination justify-content-center">
    <?php if ($current_page>1): ?>
    <li class="page-item">
      <a class="page-link" href="?page=<?= $current_page-1; ?>">&laquo;</a>
    </li>
    <?php endif; ?>

    <?php for ($i=1;$i<=$total_pages;$i++): ?>
    <li class="page-item <?= ($i==$current_page) ? 'active' : ''; ?>">
      <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
    </li>
    <?php endfor; ?>

    <?php if ($current_page<$total_pages): ?>
    <li class="page-item">
      <a class="page-link" href="?page=<?= $current_page+1; ?>">&raquo;</a>
    </li>
    <?php endif; ?>
  </ul>
</nav>

<br><br><br>
<?php include '../footer.php'; ?>
