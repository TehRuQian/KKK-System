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
  
  $f_minShareCapitalForLoan = $_POST['f_minShareCapitalForLoan'];
  $f_profitRate = $_POST['f_profitRate'];
  $f_maxInstallmentPeriod = $_POST['f_maxInstallmentPeriod'];
  $f_maxFinancingAmt = $_POST['f_maxFinancingAmt'];

  // Get other data that are not changed
  $sql = "
    SELECT * FROM tb_policies
    ORDER BY p_policyID DESC
    LIMIT 1;";
  $result = mysqli_query($con, $sql);
  $policy = mysqli_fetch_assoc($result);

  $d_memberRegFee = $policy['p_memberRegFee'];
  $d_minShareCapital = $policy['p_minShareCapital'];
  $d_minFeeCapital = $policy['p_minFeeCapital'];
  $d_minFixedSaving = $policy['p_minFixedSaving'];
  $d_minMemberFund = $policy['p_minMemberFund'];
  $d_minMemberSaving = $policy['p_minMemberSaving'];
  $d_minOtherFees = $policy['p_minOtherFees'];
  
  $d_salaryDeductionForSaving = $policy['p_salaryDeductionForSaving'];
  $d_salaryDeductionForMemberFund = $policy['p_salaryDeductionForMemberFund'];

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
      '$f_minShareCapitalForLoan', '$f_profitRate', '$f_maxInstallmentPeriod', '$f_maxFinancingAmt',
      '$d_salaryDeductionForSaving', '$d_salaryDeductionForMemberFund',
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