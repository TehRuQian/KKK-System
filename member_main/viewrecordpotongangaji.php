<?php 

include('../kkksession.php');
if(!session_id())
{
  session_start();
}


if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$u_id = $_SESSION['funame'];

$records_per_page=10;

$current_page=isset($_GET['page'])?(int)$_GET['page']:1;

$offset=($current_page-1)*$records_per_page;

$count_sql="SELECT COUNT(*) AS total FROM tb_transaction 
            WHERE t_memberNo='$u_id' AND t_transactionType IN (1,2,3,4,5)";
$count_result=mysqli_query($con,$count_sql);
$total_records=mysqli_fetch_assoc($count_result)['total'];

$total_pages=ceil($total_records/$records_per_page);


$sql = "SELECT tb_transaction.*,
               tb_ttype.tt_desc AS type,
               tb_rmonth.rm_desc AS month
        FROM tb_transaction
        LEFT JOIN tb_ttype ON tb_transaction.t_transactionType=tb_ttype.tt_id
        LEFT JOIN tb_rmonth ON tb_transaction.t_month=tb_rmonth.rm_id
        WHERE tb_transaction.t_memberNo = '$u_id' AND tb_transaction.t_transactionType IN (1,2,3,4,5)
        ORDER BY tb_transaction.t_transactionDate DESC
        LIMIT $records_per_page OFFSET $offset";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: ".mysqli_error($con));
}


?>

<style>
  body {
    background-color: #f9f9f9;
  }
  
</style>

    <div class="my-3"></div>
    <h2>Rekod Potongan Gaji</h2>

    <nav>
      <ul class="dflex justify-content-center pagination ms-5 pagination-sm">
        <?php if($current_page>1): ?>
        <li class="page-item">
          <a class="page-link" href="?page=<?=$current_page-1;?>">&laquo</a>
        </li>
        <?php endif;?>

        <?php for($i=1;$i<=$total_pages;$i++):?>
        <li class="page-item <?=($i==$current_page)?'active':'';?>">
          <a class="page-link" href="?page=<?=$i;?>"><?=$i;?></a>
        </li>
        <?php endfor;?>
        <?php if($current_page<$total_pages):?>
        <li class="page-item">
          <a class="page-link" href="?page=<?=$current_page+1;?>">&raquo</a>
        </li>
        <?php endif;?>
      </ul>
    </nav>
  </div>
</div>

    <div class="card mb-3 col-10 my-5 mx-auto">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Rekod Potongan Gaji
        <button type="button" class="btn btn-info"  onclick="window.location.href='member.php'">
            Kembali
        </button> 
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <tbody>
            <tr>
              <td scope="row">ID</td>
              <td>Jenis</td>
              <td>Amaun (RM)</td>
              <td>Bulan</td>
              <td>Tahun</td>
              <td>Tarikh Direkod</td>
            </tr>
            <?php
            while($row = mysqli_fetch_assoc($result)){
            ?>
            <tr>
              <td scope="row"><?= $row['t_transactionID']; ?></td>
              <td><?= $row['type']; ?></td>
              <td><?= $row['t_transactionAmt']; ?></td>
              <td><?= $row['month']; ?></td>
              <td><?= $row['t_year']; ?></td>
              <td><?php
                $date=$row['t_transactionDate'];
                $newDate=date("d-m-Y",strtotime($date));
                echo $newDate;
              ?></td>
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
