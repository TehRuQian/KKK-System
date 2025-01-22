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
  
  $f_memberRegFee = $_POST['f_memberRegFee'];
  $f_minShareCapital = $_POST['f_minShareCapital'];
  $f_minFeeCapital = $_POST['f_minFeeCapital'];
  $f_minFixedSaving = $_POST['f_minFixedSaving'];
  $f_minMemberFund = $_POST['f_minMemberFund'];
  $f_minMemberSaving = $_POST['f_minMemberSaving'];
  $f_minOtherFees = $_POST['f_minOtherFees'];

  // Get other data that are not changed
  $sql = "
    SELECT * FROM tb_policies
    ORDER BY p_policyID DESC
    LIMIT 1;";
  $result = mysqli_query($con, $sql);
  $policy = mysqli_fetch_assoc($result);

  $d_minShareCapitalForLoan = $policy['p_minShareCapitalForLoan'];
  $d_profitRate = $policy['p_profitRate'];
  $d_maxInstallmentPeriod = $policy['p_maxInstallmentPeriod'];
  $d_maxFinancingAmt = $policy['p_maxFinancingAmt'];
  $d_salaryDeductionForSaving = $policy['p_salaryDeductionForSaving'];
  $d_salaryDeductionForMemberFund = $policy['p_salaryDeductionForMemberFund'];

  // Insert
  $sql = "
    INSERT INTO tb_policies (
      p_memberRegFee, p_minShareCapital, p_minFeeCapital, p_minFixedSaving, p_minMemberFund, p_minMemberSaving, p_minOtherFees,
      p_minShareCapitalForLoan, p_profitRate, p_maxInstallmentPeriod, p_maxFinancingAmt,
      p_salaryDeductionForSaving, p_salaryDeductionForMemberFund,
      p_adminID)
    VALUES (
      '$f_memberRegFee', '$f_minShareCapital', '$f_minFeeCapital', '$f_minFixedSaving', '$f_minMemberFund', '$f_minMemberSaving', '$f_minOtherFees',
      '$d_minShareCapitalForLoan', '$d_profitRate', '$d_maxInstallmentPeriod', '$d_maxFinancingAmt',
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