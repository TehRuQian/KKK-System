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

  function compare(){

  }
?>

<!-- Main Content -->
<div class="container">
    <h2>Kemaskini Polisi</h2>

    <!-- Card 1: Polisi Asas Pemohonan Anggota -->
    <div class="card mb-3">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Polisi Asas Pemohonan Anggota
      </div>
      <div class="card-body">
        <?php
            $sql_member = "
                SELECT p_memberRegFee, p_returningMemberRegFee, 
                    p_minShareCapital, p_minFeeCapital, p_minFixedSaving, p_minMemberFund, p_minMemberSaving, p_minOtherFees, 
                    p_adminID, p_dateUpdated
                FROM tb_policies
                ORDER BY p_policyID ASC";
            $result_member = mysqli_query($con, $sql_member);
            $temp = mysqli_fetch_assoc($result_member);
            $member_policy[] = $temp;
            while($row = mysqli_fetch_assoc($result_member)){
                $compare_result = false;
                if ($row['p_memberRegFee'] != $temp['p_memberRegFee'] ||
                    $row['p_returningMemberRegFee'] != $temp['p_returningMemberRegFee'] ||
                    $row['p_minShareCapital'] != $temp['p_minShareCapital'] ||
                    $row['p_minFeeCapital'] != $temp['p_minFeeCapital'] ||
                    $row['p_minFixedSaving'] != $temp['p_minFixedSaving'] ||
                    $row['p_minMemberFund'] != $temp['p_minMemberFund'] ||
                    $row['p_minMemberSaving'] != $temp['p_minMemberSaving'] ||
                    $row['p_minOtherFees'] != $temp['p_minOtherFees']) {
                    $compare_result = true;
                }

                if($compare_result){
                    $member_policy[] = $row;
                }
                $temp = $row;
            }
        ?>
        <table class="table table-hover">
          <thead>
            <th>Perkara</th>
            <?php
                foreach($member_policy as $x){
                    echo "<th>" . date("d/m/Y", strtotime($x['p_dateUpdated'])) . "</th>";
                }
            ?>
          </thead>
          <tbody>
            <tr>
              <td scope="row">Fee Masuk</td>
              <?php
                foreach($member_policy as $x){
                    echo "<td>RM" . number_format($x['p_memberRegFee'], 2) . "</td>";
                }
              ?>
            </tr>
            <tr>
              <td scope="row">Fee Masuk Anggota yang Pernah Menjadi Anggota</td>
              <?php
                foreach($member_policy as $x){
                    echo "<td>RM" . number_format($x['p_memberRegFee'], 2) . "</td>";
                }
              ?>
            </tr>
            <tr>
              <td scope="row">Modah Syer Minimum</td>
              <?php
                foreach($member_policy as $x){
                    echo "<td>RM" . number_format($x['p_minShareCapital'], 2) . "</td>";
                }
              ?>
            </tr>
            <tr>
              <td scope="row">Modal Yuran Minimum</td>
              <?php
                foreach($member_policy as $x){
                    echo "<td>RM" . number_format($x['p_minFeeCapital'], 2) . "</td>";
                }
              ?>
            </tr>
            <tr>
              <td scope="row">Wang Deposit Anggota Minimum</td>
              <?php
                foreach($member_policy as $x){
                    echo "<td>RM" . number_format($x['p_minFixedSaving'], 2) . "</td>";
                }
              ?>
            </tr>
            <tr>
              <td scope="row">Sumbangan Tabung Kebajikan Minimum</td>
              <?php
                foreach($member_policy as $x){
                    echo "<td>RM" . number_format($x['p_minMemberFund'], 2) . "</td>";
                }
              ?>
            </tr>
            <tr>
              <td scope="row">Simpanan Tetap Minimum</td>
              <?php
                foreach($member_policy as $x){
                    echo "<td>RM" . number_format($x['p_minMemberSaving'], 2) . "</td>";
                }
              ?>
            </tr>
            <tr>
              <td scope="row">Lain-lain</td>
              <?php
                foreach($member_policy as $x){
                    echo "<td>RM" . number_format($x['p_minOtherFees'], 2) . "</td>";
                }
              ?>
            </tr>
            <tr>
              <td scope="row">Admin ID</td>
              <?php
                foreach($member_policy as $x){
                    echo "<td>" . $x['p_adminID'] . "</td>";
                }
              ?>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Card 2: Polisi Permohonan Pembiayaan -->
    <div class="card mb-3">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Polisi Permohonan Pembiayaan
      </div>
      <div class="card-body">
      <?php
      $sql_loan = "SELECT
          p_minShareCapitalForLoan, p_maxInstallmentPeriod,
          p_maxAlBai, p_maxAlInnah, p_maxBPulihKenderaan, p_maxCukaiJalanInsurans, p_maxKhas, p_maxKarnivalMusim, p_maxAlQadrulHassan, 
          p_rateAlBai, p_rateAlInnah, p_rateBPulihKenderaan, p_rateCukaiJalanInsurans, p_rateKhas, p_rateKarnivalMusim, p_rateAlQadrulHassan,
          p_adminID, p_dateUpdated
        FROM tb_policies
        ORDER BY p_policyID ASC";
      $result_loan = mysqli_query($con, $sql_loan);
      $temp = mysqli_fetch_assoc($result_loan);
      $loan_policy[] = $temp;

      while($row = mysqli_fetch_assoc($result_loan)){
        $compare_result = false;
        if ($row['p_minShareCapitalForLoan'] != $temp['p_minShareCapitalForLoan'] ||
            $row['p_maxInstallmentPeriod'] != $temp['p_maxInstallmentPeriod'] ||
            $row['p_maxAlBai'] != $temp['p_maxAlBai'] ||
            $row['p_maxAlInnah'] != $temp['p_maxAlInnah'] ||
            $row['p_maxBPulihKenderaan'] != $temp['p_maxBPulihKenderaan'] ||
            $row['p_maxCukaiJalanInsurans'] != $temp['p_maxCukaiJalanInsurans'] ||
            $row['p_maxKhas'] != $temp['p_maxKhas'] ||
            $row['p_maxKarnivalMusim'] != $temp['p_maxKarnivalMusim'] ||
            $row['p_maxAlQadrulHassan'] != $temp['p_maxAlQadrulHassan'] ||
            $row['p_rateAlBai'] != $temp['p_rateAlBai'] ||
            $row['p_rateAlInnah'] != $temp['p_rateAlInnah'] ||
            $row['p_rateBPulihKenderaan'] != $temp['p_rateBPulihKenderaan'] ||
            $row['p_rateCukaiJalanInsurans'] != $temp['p_rateCukaiJalanInsurans'] ||
            $row['p_rateKhas'] != $temp['p_rateKhas'] ||
            $row['p_rateKarnivalMusim'] != $temp['p_rateKarnivalMusim'] ||
            $row['p_rateAlQadrulHassan'] != $temp['p_rateAlQadrulHassan']) {
            $compare_result = true;
        }

        if($compare_result){
            $loan_policy[] = $row;
        }
        $temp = $row;
      }
      ?>
      <table class="table table-hover">
        <thead>
            <th>Perkara</th>
            <?php
                foreach($loan_policy as $x){
                    echo "<th>" . date("d/m/Y", strtotime($x['p_dateUpdated'])) . "</th>";
                }
            ?>
        </thead>
        <tbody>
            <tr>
                <td scope="row">Modal Syer Minimum Peminjam</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>" . number_format($x['p_minShareCapitalForLoan'], 2) . "</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Tempoh Ansuran Maksima</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>" .$x['p_maxInstallmentPeriod'] . " tahun</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Pembiayaan Maksima Al-Bai</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>RM" . number_format($x['p_maxAlBai'], 2) . "</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Pembiayaan Maksima Al Innah</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>RM" . number_format($x['p_maxAlInnah'], 2) . "</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Pembiayaan Maksima Baik Pulih Kenderaan</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>RM" . number_format($x['p_maxBPulihKenderaan'], 2) . "</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Pembiayaan Maksima Cukai Jalan dan Insurans</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>RM" . number_format($x['p_maxCukaiJalanInsurans'], 2) . "</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Pembiayaan Maksima Skim Khas</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>RM" . number_format($x['p_maxKhas'], 2) . "</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Pembiayaan Maksima Karnival Musim Istimewa</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>RM" . number_format($x['p_maxKarnivalMusim'], 2) . "</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Pembiayaan Maksima Al-Qadrul Hassan</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>RM" . number_format($x['p_maxAlQadrulHassan'], 2) . "</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Kadar Keuntungan Al-Bai</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>" . number_format($x['p_rateAlBai'], 2) . "%</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Kadar Keuntungan Al-Innah</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>" . number_format($x['p_rateAlInnah'], 2) . "%</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Kadar Keuntungan Baik Pulih Kenderaan</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>" . number_format($x['p_rateBPulihKenderaan'], 2) . "%</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Kadar Keuntungan Cukai Jalan dan Insurans</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>" . number_format($x['p_rateCukaiJalanInsurans'], 2) . "%</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Kadar Keuntungan Skim Khas</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>" . number_format($x['p_rateKhas'], 2) . "%</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Kadar Keuntungan Karnival Musim Istimewa</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>" . number_format($x['p_rateKarnivalMusim'], 2) . "%</td>";
                    }
                ?>
            </tr>
            <tr>
                <td scope="row">Kadar Keuntungan Al-Qadrul Hassan</td>
                <?php
                    foreach($loan_policy as $x){
                        echo "<td>" . number_format($x['p_rateAlQadrulHassan'], 2) . "%</td>";
                    }
                ?>
            </tr>
            <tr>
              <td scope="row">Admin ID</td>
              <?php
                foreach($loan_policy as $x){
                    echo "<td>" . $x['p_adminID'] . "</td>";
                }
              ?>
            </tr>
        </tbody>
      </table>

      </div>
    </div>

    <!-- Card 3: Polisi Potongan Gaji -->
    <div class="card mb-3">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Polisi Potongan Gaji
      </div>
      <div class="card-body">
        <?php
            $sql_salary = "
                SELECT p_salaryDeductionForSaving, p_minSalaryDeductionForSaving, p_salaryDeductionForMemberFund, p_minSalaryDeductionForMemberFund, p_cutOffDay,
                    p_adminID, p_dateUpdated
                FROM tb_policies
                ORDER BY p_policyID ASC";
            $result_salary = mysqli_query($con, $sql_salary);
            $temp = mysqli_fetch_assoc($result_salary);
            $salary_policy[] = $temp;
            while($row = mysqli_fetch_assoc($result_salary)){
                $compare_result = false;
                if ($row['p_salaryDeductionForSaving'] != $temp['p_salaryDeductionForSaving'] ||
                    $row['p_minSalaryDeductionForSaving'] != $temp['p_minSalaryDeductionForSaving'] ||
                    $row['p_salaryDeductionForMemberFund'] != $temp['p_salaryDeductionForMemberFund'] ||
                    $row['p_minSalaryDeductionForMemberFund'] != $temp['p_minSalaryDeductionForMemberFund'] ||
                    $row['p_cutOffDay'] != $temp['p_cutOffDay']) {
                    $compare_result = true;
                }

                if($compare_result){
                    $salary_policy[] = $row;
                }
                $temp = $row;
            }
        ?>
        <table class="table table-hover">
            <thead>
                <th>Perkara</th>
                <?php
                    foreach($salary_policy as $x){
                        echo "<th>" . date("d/m/Y", strtotime($x['p_dateUpdated'])) . "</th>";
                    }
                ?>
            </thead>
            <tbody>
                <tr>
                    <td scope="row">Potongan Gaji untuk Simpanan</td>
                    <?php
                        foreach($salary_policy as $x){
                            echo "<td>RM" . number_format($x['p_salaryDeductionForSaving'], 2) . "</td>";
                        }
                    ?>
                </tr>
                <tr>
                    <td scope="row">Minimum Potongan Gaji untuk Simpanan</td>
                    <?php
                        foreach($salary_policy as $x){
                            echo "<td>RM" . number_format($x['p_minSalaryDeductionForSaving'], 2) . "</td>";
                        }
                    ?>
                </tr>
                <tr>
                    <td scope="row">Potongan Gaji untuk Tabung Kebajikan</td>
                    <?php
                        foreach($salary_policy as $x){
                            echo "<td>RM" . number_format($x['p_salaryDeductionForMemberFund'], 2) . "</td>";
                        }
                    ?>
                </tr>
                <tr>
                    <td scope="row">Minimum Potongan Gaji untuk Tabung Kebajikan</td>
                    <?php
                        foreach($salary_policy as $x){
                            echo "<td>RM" . number_format($x['p_minSalaryDeductionForMemberFund'], 2) . "</td>";
                        }
                    ?>
                </tr>
                <tr>
                    <td scope="row">Hari Cut Off</td>
                    <?php
                        foreach($salary_policy as $x){
                            echo "<td>RM" . number_format($x['p_cutOffDay'], 2) . "</td>";
                        }
                    ?>
                </tr>
                <tr>
                    <td scope="row">Admin ID</td>
                    <?php
                        foreach($salary_policy as $x){
                            echo "<td>" . $x['p_adminID'] . "</td>";
                        }
                    ?>
                </tr>
            </tbody>
        </table>
      </div>
    </div>
</div>