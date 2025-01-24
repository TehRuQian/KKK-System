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
  
  $f_salaryDeductionForSaving = $_POST['f_salaryDeductionForSaving'];
  $f_salaryDeductionForMemberFund = $_POST['f_salaryDeductionForMemberFund'];

  // Get other data that are not changed
  $sql = "
    SELECT * FROM tb_policies
    ORDER BY p_policyID DESC
    LIMIT 1;";
  $result = mysqli_query($con, $sql);
  $policy = mysqli_fetch_assoc($result);

  $d_memberRegFee = $policy['p_memberRegFee'];
  $d_returningMemberRegFee = $policy['p_returningMemberRegFee'];
  $d_minShareCapital = $policy['p_minShareCapital'];
  $d_minFeeCapital = $policy['p_minFeeCapital'];
  $d_minFixedSaving = $policy['p_minFixedSaving'];
  $d_minMemberFund = $policy['p_minMemberFund'];
  $d_minMemberSaving = $policy['p_minMemberSaving'];
  $d_minOtherFees = $policy['p_minOtherFees'];

  $d_minShareCapitalForLoan = $policy['p_minShareCapitalForLoan'];
  $d_maxInstallmentPeriod = $policy['p_maxInstallmentPeriod'];

  $d_maxAlBai = $policy['p_maxAlBai'];
  $d_maxAlInnah = $policy['p_maxAlInnah'];
  $d_maxBPulihKenderaan = $policy['p_maxBPulihKenderaan'];
  $d_maxCukaiJalanInsurans = $policy['p_maxCukaiJalanInsurans'];
  $d_maxKhas = $policy['p_maxKhas'];
  $d_maxKarnivalMusim = $policy['p_maxKarnivalMusim'];
  $d_maxAlQadrulHassan = $policy['p_maxAlQadrulHassan'];
  $d_rateAlBai = $policy['p_rateAlBai'];
  $d_rateAlInnah = $policy['p_rateAlInnah'];
  $d_rateBPulihKenderaan = $policy['p_rateBPulihKenderaan'];
  $d_rateCukaiJalanInsurans = $policy['p_rateCukaiJalanInsurans'];
  $d_rateKhas = $policy['p_rateKhas'];
  $d_rateKarnivalMusim = $policy['p_rateKarnivalMusim'];
  $d_rateAlQadrulHassan = $policy['p_rateAlQadrulHassan'];

  // SQL Insert Operation
  // Admin ID needs to be changed later
  $sql = "
    INSERT INTO tb_policies (
      p_memberRegFee, p_minShareCapital, p_minFeeCapital, p_minFixedSaving, p_minMemberFund, p_minMemberSaving, p_minOtherFees,
      p_minShareCapitalForLoan, p_profitRate, p_maxInstallmentPeriod, p_maxFinancingAmt,
      p_salaryDeductionForSaving, p_salaryDeductionForMemberFund,
      p_adminID)
    VALUES (
      '$d_memberRegFee', '$d_minShareCapital', '$d_minFeeCapital', '$d_minFixedSaving', '$d_minMemberFund', '$d_minMemberSaving', '$d_minOtherFees',
      '$d_minShareCapitalForLoan', '$d_profitRate', '$d_maxInstallmentPeriod', '$d_maxFinancingAmt',
      '$f_salaryDeductionForSaving', '$f_salaryDeductionForMemberFund',
      '$admin_id'); ";

  if (mysqli_query($con, $sql)) {
    echo "
        <script>
            Swal.fire({
                title: 'Berjaya!',
                text: 'Data telah berjaya dikemaskini!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'kemaskini_polisi.php';
            });
        </script>";
  } else {
    echo "
        <script>
            Swal.fire({
                title: 'Ralat!',
                text: 'Ralat: " . mysqli_error($con) . "',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'kemaskini_polisi.php';
            });
        </script>";
  }
  mysqli_close($con);
?>