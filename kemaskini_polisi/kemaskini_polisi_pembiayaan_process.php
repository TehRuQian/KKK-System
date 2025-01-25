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

  // var_dump($_POST);
  
  $f_minShareCapitalForLoan = $_POST['f_minShareCapitalForLoan'];
  $f_maxInstallmentPeriod = $_POST['f_maxInstallmentPeriod'];

  $f_maxAlBai = $_POST['f_maxAlBai'];
  $f_maxAlInnah = $_POST['f_maxAlInnah'];
  $f_maxBPulihKenderaan = $_POST['f_maxBPulihKenderaan'];
  $f_maxCukaiJalanInsurans = $_POST['f_maxCukaiJalanInsurans'];
  $f_maxKhas = $_POST['f_maxKhas'];
  $f_maxKarnivalMusim = $_POST['f_maxKarnivalMusim'];
  $f_maxAlQadrulHassan = $_POST['f_maxAlQadrulHassan'];

  // $f_rateAlBai = $_POST['f_rateAlBai'];
  // $f_rateAlInnah = $_POST['f_rateAlInnah'];
  // $f_rateBPulihKenderaan = $_POST['f_rateBPulihKenderaan'];
  // $f_rateCukaiJalanInsurans = $_POST['f_rateCukaiJalanInsurans'];
  // $f_rateKhas = $_POST['f_rateKhas'];
  // $f_rateKarnivalMusim = $_POST['f_rateKarnivalMusim'];
  // $f_rateAlQadrulHassan = $_POST['f_rateAlQadrulHassan'];

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
  
  $d_salaryDeductionForSaving = $policy['p_salaryDeductionForSaving'];
  $d_salaryDeductionForMemberFund = $policy['p_salaryDeductionForMemberFund'];

  if (isset($_POST['f_sameProfitRate']) && $_POST['f_sameProfitRate'] == 1) {
    $f_rate = $_POST['f_sameRate'];
    $f_rateAlBai = $f_rate;
    $f_rateAlInnah = $f_rate;
    $f_rateBPulihKenderaan = $f_rate;
    $f_rateCukaiJalanInsurans = $f_rate;
    $f_rateKhas = $f_rate;
    $f_rateKarnivalMusim = $f_rate;
    $f_rateAlQadrulHassan = $f_rate;
  } 
  else {
    $f_rateAlBai = $_POST['f_rateAlBai'];
    $f_rateAlInnah = $_POST['f_rateAlInnah'];
    $f_rateBPulihKenderaan = $_POST['f_rateBPulihKenderaan'];
    $f_rateCukaiJalanInsurans = $_POST['f_rateCukaiJalanInsurans'];
    $f_rateKhas = $_POST['f_rateKhas'];
    $f_rateKarnivalMusim = $_POST['f_rateKarnivalMusim'];
    $f_rateAlQadrulHassan = $_POST['f_rateAlQadrulHassan'];
  }

  // SQL Insert Operation
  $sql = "
    INSERT INTO tb_policies (
      p_memberRegFee, p_returningMemberRegFee, 
      p_minShareCapital, p_minFeeCapital, p_minFixedSaving, p_minMemberFund, p_minMemberSaving, p_minOtherFees,
      p_minShareCapitalForLoan, p_maxInstallmentPeriod, 
      p_maxAlBai, p_maxAlInnah, p_maxBPulihKenderaan, p_maxCukaiJalanInsurans, p_maxKhas, p_maxKarnivalMusim, p_maxAlQadrulHassan, 
      p_rateAlBai, p_rateAlInnah, p_rateBPulihKenderaan, p_rateCukaiJalanInsurans, p_rateKhas, p_rateKarnivalMusim, p_rateAlQadrulHassan, 
      p_salaryDeductionForSaving, p_salaryDeductionForMemberFund, p_adminID)
    VALUES (
      '$d_memberRegFee', '$d_returningMemberRegFee', 
      '$d_minShareCapital', '$d_minFeeCapital', '$d_minFixedSaving', '$d_minMemberFund', '$d_minMemberSaving', '$d_minOtherFees',
      '$f_minShareCapitalForLoan', '$f_maxInstallmentPeriod', 
      '$f_maxAlBai', '$f_maxAlInnah', '$f_maxBPulihKenderaan', '$f_maxCukaiJalanInsurans', '$f_maxKhas', '$f_maxKarnivalMusim', '$f_maxAlQadrulHassan', 
      '$f_rateAlBai', '$f_rateAlInnah', '$f_rateBPulihKenderaan', '$f_rateCukaiJalanInsurans', '$f_rateKhas', '$f_rateKarnivalMusim', '$f_rateAlQadrulHassan', 
      '$d_salaryDeductionForSaving', '$d_salaryDeductionForMemberFund', '$admin_id');";

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