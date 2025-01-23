<?php 

include('../kkksession.php');
if(!session_id())
{
  session_start();
}
if ($_SESSION['u_type'] != 2) {
  header('Location: ../login.php');
  exit();
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

$sql = "SELECT * FROM tb_banner;";
$banners = mysqli_query($con, $sql);

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

<div class="card-body">
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php while ($row = mysqli_fetch_assoc($banners)) { ?>
                    <?php if ($row['b_status'] == 1) { ?>
                    <div class="carousel-item active">
                    <img src="../img/iklan/<?php echo $row['b_banner']; ?>" class="d-block w-100" alt="Banner Image" style="height: 450px; width: 100%; object-fit: cover;">
                    </div>
                <?php }} ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
      </div>
    </div>
    
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
              <td scope="row">Modah Syer</td>
              <td>RM <?= number_format($row_saham['f_shareCapital'] ?? 0, 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Modal Yuran</td>
              <td>RM <?= number_format($row_saham['f_feeCapital'] ?? 0, 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Simpanan Tetap</td>
              <td>RM <?= number_format($row_saham['f_fixedSaving'] ?? 0, 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Tabung Anggota</td>
              <td>RM <?= number_format($row_saham['f_memberFund'] ?? 0, 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Simpanan Anggota</td>
              <td>RM <?= number_format($row_saham['f_memberSaving'] ?? 0, 2); ?></td>
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
            <td scope="row"><?= $loanName; ?></td>
            <td>RM <?= number_format($totalLoan ?? 0, 2); ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<br><br><br>


<?php include '../footer.php'; ?>
