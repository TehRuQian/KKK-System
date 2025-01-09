<?php 
  include '../header_admin.php';
  include '../db_connect.php';

  // Retrieve data from form
  // $u_id = $_SESSION['u_id'];
  $admin_id = 200;                    //! Needs to change
  $f_memberRegFee = $_POST['f_memberRegFee'];
  $f_minShareCapital = $_POST['f_minShareCapital'];
  $f_minFeeCapital = $_POST['f_minFeeCapital'];
  $f_minFixedSaving = $_POST['f_minFixedSaving'];
  $f_minMemberFund = $_POST['f_minMemberFund'];
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

  // SQL Insert Operation
  // Admin ID needs to be changed later
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
            alert ('Data has been successfully updated!');
            window.location.href = 'kemaskini_polisi.php';
        </script>";
  }
  else{
    echo "
        <script>
            alert ('Error: " . mysqli_error($con) ."');
            window.location.href = 'kemaskini_polisi.php';
        </script>";
  };
  mysqli_close($con);
?>