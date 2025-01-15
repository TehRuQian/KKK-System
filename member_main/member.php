<?php 

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$u_id = $_SESSION['funame'];

$sql_saham = "SELECT * FROM tb_financial
        WHERE tb_financial.f_memberNo = '$u_id'";

$result_saham = mysqli_query($con, $sql_saham);

if (!$result_saham) {
    die("Query failed: ".mysqli_error($con));
}

$row_saham = mysqli_fetch_assoc($result_saham);

$sql_pinjaman= "SELECT
  tb_ltype.lt_desc AS loanName,
  COALESCE(SUM(tb_loan.l_loanPayable)) AS totalLoan
  FROM tb_ltype
    LEFT JOIN tb_loan 
      ON tb_loan.l_loanType=tb_ltype.lt_lid
      AND tb_loan.l_memberNo='$u_id'
      AND tb_loan.l_status=3
    GROUP BY tb_ltype.lt_desc
    ORDER BY tb_ltype.lt_desc";

$result_pinjaman = mysqli_query($con, $sql_pinjaman);

if (!$result_pinjaman) {
    die("Query failed: ".mysqli_error($con));
}

$loanTotals=[];
while($row_pinjaman=mysqli_fetch_assoc($result_pinjaman)){
  $loanTotals[$row_pinjaman['loanName']]=$row_pinjaman['totalLoan'];
}

?>

<style>
  body {
    background-color: #f9f9f9;
  }
  .hero-img {
    width: 100%;
    height: auto;
    max-height: 550px;
    object-fit: cover;
  }
</style>

<img src="../img/advertisement.jpg" alt="Advertisement" class="hero-img">
    <div class="card mb-3 col-10 my-5 mx-auto">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Maklumat Saham Ahli
        <button type="button" class="btn btn-info"  onclick="window.location.href='viewrecordpotongangaji.php'">
            Rekod Transaksi
        </button> 
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <tbody>
            <tr>
              <td scope="row">Modah Syer :</td>
              <td>RM <?= $row_saham['f_shareCapital'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Modal Yuran :</td>
              <td>RM <?= $row_saham['f_feeCapital'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Simpanan Tetap :</td>
              <td>RM <?= $row_saham['f_fixedSaving'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Tabung Anggota :</td>
              <td>RM <?= $row_saham['f_memberFund'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Simpanan Anggota :</td>
              <td>RM <?= $row_saham['f_memberSaving'] ?? '0'; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

<div class="card mb-3 col-10 my-5 mx-auto">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Maklumat Pinjaman Ahli
        <button type="button" class="btn btn-info" onclick="window.location.href='viewrecordbayaranbalik.php'">
            Rekod Transaksi
        </button>
    </div>
    <div class="card-body">
      <table class="table table-hover">
        <tbody>
          <?php if(!empty($loanTotals)):?>
          <?php foreach($loanTotals AS $loanName=>$totalLoan):?>
          <tr>
            <td scope="row"><?= $loanName; ?> :</td>
            <td>RM <?= $totalLoan ?? 0; ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<br><br><br>


<?php include '../footer.php'; ?>
