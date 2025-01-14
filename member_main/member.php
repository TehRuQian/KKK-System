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

$sql = "SELECT * FROM tb_financial
        WHERE tb_financial.f_memberNo = '$u_id'";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: ".mysqli_error($con));
}

$row = mysqli_fetch_assoc($result);
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
              <td>RM <?= $row['f_shareCapital'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Modal Yuran :</td>
              <td>RM <?= $row['f_feeCapital'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Simpanan Tetap :</td>
              <td>RM <?= $row['f_fixedSaving'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Tabung Anggota :</td>
              <td>RM <?= $row['f_memberFund'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Simpanan Anggota :</td>
              <td>RM <?= $row['f_memberSaving'] ?? '0'; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>


    <div class="card mb-3 col-10 my-5 mx-auto">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Maklumat Pinjaman Ahli
        <button type="button" class="btn btn-info"  onclick="window.location.href='viewrecordbayaranbalik.php'">
            Rekod Transaksi
        </button> 
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <tbody>
            <tr>
              <td scope="row">Al-Bai :</td>
              <td>RM <?= $row['f_alBai'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Al-Innah :</td>
              <td>RM <?= $row['f_alInnah'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">B/Pulih Kenderaan :</td>
              <td>RM <?= $row['f_bPulihKenderaan'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Road Tax & Insuran :</td>
              <td>RM <?= $row['f_roadTaxInsurance'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Khas :</td>
              <td>RM <?= $row['f_specialScheme'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Karnival Musim Istimewa :</td>
              <td>RM <?= $row['f_specialSeasonCarnival'] ?? '0'; ?></td>
            </tr>
            <tr>
              <td scope="row">Al-Qadrul Hassan :</td>
              <td>RM <?= $row['f_alQadrulHassan'] ?? '0'; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  <br><br><br>



<?php //include 'footer.php'; ?>
