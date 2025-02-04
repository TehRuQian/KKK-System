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

  // Retrieve latest policy with newest ID
  $sql = "
    SELECT * FROM tb_policies
    ORDER BY p_policyID DESC
    LIMIT 1;";
  $result = mysqli_query($con, $sql);
  $policy = mysqli_fetch_assoc($result);
  $sameRate = (
    $policy['p_rateAlBai'] == $policy['p_rateAlInnah'] 
    && $policy['p_rateAlBai'] == $policy['p_rateBPulihKenderaan'] 
    && $policy['p_rateAlBai'] == $policy['p_rateCukaiJalanInsurans']
    && $policy['p_rateAlBai'] == $policy['p_rateKhas']
    && $policy['p_rateAlBai'] == $policy['p_rateKarnivalMusim']
    && $policy['p_rateAlBai'] == $policy['p_rateAlQadrulHassan']);

  $maxFinancingAmt = max(
    $policy['p_maxAlBai'], 
    $policy['p_maxAlInnah'],
    $policy['p_maxBPulihKenderaan'],
    $policy['p_maxCukaiJalanInsurans'],
    $policy['p_maxKhas'],
    $policy['p_maxKarnivalMusim'],
    $policy['p_maxAlQadrulHassan'])
?>

<!-- Main Content -->
<div class="container">
    <h2>Kemaskini Polisi</h2>

    <!-- Card 1: Polisi Asas Pemohonan Anggota -->
    <div class="card mb-3">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Polisi Asas Pemohonan Anggota
        <button type="button" class="btn btn-info"  onclick="window.location.href='kemaskini_polisi_asas.php'">
            Kemaskini
        </button>
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <tbody>
            <tr>
              <td scope="row">Fee Masuk</td>
              <td><?php echo "RM" . number_format($policy['p_memberRegFee'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Fee Masuk Anggota yang Pernah Menjadi Anggota</td>
              <td><?php echo "RM" . number_format($policy['p_returningMemberRegFee'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Modah Syer Minimum</td>
              <td><?php echo "RM" . number_format($policy['p_minShareCapital'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Modal Yuran Minimum</td>
              <td><?php echo "RM" . number_format($policy['p_minFeeCapital'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Wang Deposit Anggota Minimum</td>
              <td><?php echo "RM" . number_format($policy['p_minMemberSaving'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Sumbangan Tabung Kebajikan Minimum</td>
              <td><?php echo "RM" . number_format($policy['p_minMemberFund'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Simpanan Tetap Minimum</td>
              <td><?php echo "RM" . number_format($policy['p_minFixedSaving'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Lain-lain</td>
              <td><?php echo "RM" . number_format($policy['p_minOtherFees'], 2); ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Card 2: Polisi Permohonan Pembiayaan -->
    <div class="card mb-3">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Polisi Permohonan Pembiayaan
        <button type="button" class="btn btn-info" onclick="window.location.href='kemaskini_polisi_pembiayaan.php'">Kemaskini</button>
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <tbody>
            <tr>
              <td scope="row">Modal Syer Minimum Peminjam</td>
              <td>RM <?php echo number_format($policy['p_minShareCapitalForLoan'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Tempoh Ansuran Maksima</td>
              <td><?php echo $policy['p_maxInstallmentPeriod'] . " tahun"; ?></td>
            </tr>
          <?php if ($sameRate): ?>
            <tr>
              <td scope="row">Kadar Keuntungan</td>
              <td><?php echo $policy['p_rateAlBai'] ?>%</td>
            </tr>
            <tr>
              <td scope="row">Pembiayaan Maksima Al-Bai</td>
              <td>RM <?php echo number_format($policy['p_maxAlBai'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Pembiayaan Maksima Al-Innah</td>
              <td>RM <?php echo number_format($policy['p_maxAlInnah'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Pembiayaan Maksima Baik Pulih Kenderaan</td>
              <td>RM <?php echo number_format($policy['p_maxBPulihKenderaan'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Pembiayaan Maksima Cukai Jalan dan Insurans</td>
              <td>RM <?php echo number_format($policy['p_maxCukaiJalanInsurans'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Pembiayaan Maksima Skim Khas</td>
              <td>RM <?php echo number_format($policy['p_maxKhas'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Pembiayaan Maksima Karnival Musim Istimewa</td>
              <td>RM <?php echo number_format($policy['p_maxKarnivalMusim'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Pembiayaan Maksima Al-Qadrul Hassan</td>
              <td>RM <?php echo number_format($policy['p_maxAlQadrulHassan'], 2); ?></td>
            </tr>
          </tbody>
        </table>
        <p class="text-center">Jadual Pembayaran Balik Pembiayaan</p>
        <table class="table table-hover" style="margin: 0 auto; text-align: center;">
          <tr>
            <td scope="col">Kadar Keuntungan</td>
            <td scope="col" colspan="<?php echo $policy['p_maxInstallmentPeriod'] ?>">
              <?php echo number_format($policy['p_rateAlBai'], 2)?> %
            </td>
          </tr>
          <tr>
            <td scope="row">Tempoh (Tahun)</th>
            <?php
              for($x = 1; $x <= $policy['p_maxInstallmentPeriod']; $x++){
                echo "<td>$x</td>";
              }
            ?>
          </tr>
          <tr>
            <td scope="row">Tempoh (Bulan)</th>
            <?php
              for($x = 1; $x <= $policy['p_maxInstallmentPeriod']; $x++){
                echo "<td>" . ($x * 12) . "</td>";
              }
            ?>
          </tr>
          <tr>
            <td scope="row">Jumlah Pembiayaan</th>
            <td scope="col" colspan="<?php echo $policy['p_maxInstallmentPeriod'] ?>">
              Ansuran Bulanan
            </td>
          </tr>
          <?php
            for($x = 1000; $x <= $maxFinancingAmt; $x += 1000){
              echo "<tr>";
                echo "<td>". number_format($x, 2) ."</td>";
                for($y = 1; $y <= $policy['p_maxInstallmentPeriod']; $y++){
                  echo "<td>";
                    $installment = ($x * (1+ $policy['p_rateAlBai']*$y / 100)) / ($y * 12);
                    echo number_format($installment, 2);
                  echo "</td>";
                }
              echo "</tr>";
            }
          ?>
        </table>

        <?php else: ?>
        </table>
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Jenis Pembiayaan</th>
              <th>Kadar Keuntungan</th>
              <th>Pembiayaan Maksima</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Al-Bai</td>
              <td><?php echo number_format($policy['p_rateAlBai'],2) ?>%</td>
              <td>RM <?php echo number_format($policy['p_maxAlBai'], 2); ?></td>
            <tr>
            <tr>
              <td scope="row">Al-Innah</td>
              <td><?php echo number_format($policy['p_rateAlInnah'], 2) ?>%</td>
              <td>RM <?php echo number_format($policy['p_maxAlInnah'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Baik Pulih Kenderaan</td>
              <td><?php echo number_format($policy['p_rateBPulihKenderaan'], 2) ?>%</td>
              <td>RM <?php echo number_format($policy['p_maxBPulihKenderaan'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Cukai Jalan dan Insurans</td>
              <td><?php echo number_format($policy['p_rateCukaiJalanInsurans'], 2) ?>%</td>
              <td>RM <?php echo number_format($policy['p_maxCukaiJalanInsurans'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Skim Khas</td>
              <td><?php echo number_format($policy['p_rateKhas'], 2) ?>%</td>
              <td>RM <?php echo number_format($policy['p_maxKhas'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Karnival Musim Istimewa</td>
              <td><?php echo number_format($policy['p_rateKarnivalMusim'], 2) ?>%</td>
              <td>RM <?php echo number_format($policy['p_maxKarnivalMusim'], 2); ?></td>
            </tr>
            <tr>
              <td scope="row">Al-Qadrul Hassan</td>
              <td><?php echo number_format($policy['p_rateAlQadrulHassan'], 2) ?>%</td>
              <td>RM <?php echo number_format($policy['p_maxAlQadrulHassan'], 2); ?></td>
            </tr>
        </tbody>
        </table>
        <?php endif;?>
      </div>
    </div>

    <!-- Card 3: Polisi Potongan Gaji -->
    <div class="card mb-3">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Polisi Potongan Gaji
        <button type="button" class="btn btn-info" onclick="window.location.href='kemaskini_polisi_potongan_gaji.php'">Kemaskini</button>
      </div>
      <div class="card-body">
      <table class="table table-hover">
        <tbody>
          <tr>
            <td scope="row">Potongan Gaji untuk Simpanan Tetap</td>
            <td>RM <?php echo number_format($policy['p_salaryDeductionForSaving'], 2); ?></td>
          </tr>
          <tr>
            <td scope="row">Minimum Potongan Gaji untuk Simpanan Tetap</td>
            <td>RM <?php echo number_format($policy['p_minSalaryDeductionForSaving'], 2); ?></td>
          </tr>
          <tr>
            <td scope="row">Potongan Gaji untuk Sumbangan Tabung Kebajikan</td>
            <td>RM <?php echo number_format($policy['p_salaryDeductionForMemberFund'], 2); ?></td>
          </tr>
          <tr>
            <td scope="row">Minimum Potongan Gaji untuk Sumbangan Tabung Kebajikan</td>
            <td>RM <?php echo number_format($policy['p_minSalaryDeductionForMemberFund'], 2); ?></td>
          </tr>
          <tr>
            <td scope="row">Hari Cut Off</td>
            <td><?php echo $policy['p_cutOffDay']; ?></td>
          </tr>
        </tbody>
      </table>
      </div>
    </div>
</div>