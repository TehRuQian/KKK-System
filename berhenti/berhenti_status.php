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

$sql ="SELECT tb_tarikdiri.*, 
               tb_status.s_desc AS status
      FROM tb_tarikdiri
      LEFT JOIN tb_status ON tb_tarikdiri.td_status=tb_status.s_sid
      WHERE td_memberNo = $u_id
      ORDER BY tb_tarikdiri.td_submitDate DESC";

$result=mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}
?>

<style>
  body{
    background-color: #f9f9f9;
  }

  table thead th{
    text-align: center;
    background-color: #f1f1f1;
  }

  table tbody td{
    text-align: center;
  }
  
</style>

<div class="my-3"></div>
<h2 style="text-align:center;">Status Permohonan Berhenti Menjadi Anggota</h2><br>


<div class="card mb-3 col-10 my-5 mx-auto">
  <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
    <span>Status Permohonan Berhenti Menjadi Anggota</span>
  </div>
  <div class="card-body">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Tarikh Hantar</th>
          <th scope="col">Status</th>
          <th scope="col">Ulasan</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row=mysqli_fetch_assoc($result)) {
        ?>
        <tr>
          <td scope="row"><?= $row['td_tarikdiriID']; ?></td>
          <td><?= date('d-m-Y H:i:s', strtotime($row['td_submitDate'])); ?></td>
          <td><?= $row['status']; ?></td>
          <td scope="row"><?= $row['td_ulasan']; ?></td>
        </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</div>


<br><br><br>
<?php include '../footer.php'; ?>
