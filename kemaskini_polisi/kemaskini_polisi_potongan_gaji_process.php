<?php 
  include '../header_admin.php';
  include '../db_connect.php';

  // Retrieve data from form
  // $u_id = $_SESSION['u_id'];
  $admin_id = 200;                    //! Needs to change
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
  $d_minShareCapital = $policy['p_minShareCapital'];
  $d_minFeeCapital = $policy['p_minFeeCapital'];
  $d_minFixedSaving = $policy['p_minFixedSaving'];
  $d_minMemberFund = $policy['p_minMemberFund'];
  $d_minOtherFees = $policy['p_minOtherFees'];

  $d_minShareCapitalForLoan = $policy['p_minShareCapitalForLoan'];
  $d_profitRate = $policy['p_profitRate'];
  $d_maxInstallmentPeriod = $policy['p_maxInstallmentPeriod'];
  $d_maxFinancingAmt = $policy['p_maxFinancingAmt'];

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