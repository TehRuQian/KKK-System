<?php 
  if (!session_id()) {
      session_start();
  }

  include '../header_admin.php';
  include '../db_connect.php';
  $admin_id = $_SESSION['u_id'];

  // Retrieve Policy  Info
  $sql = "SELECT p_minShareCapital, p_salaryDeductionForSaving, p_salaryDeductionForMemberFund
          FROM tb_policies
          ORDER BY p_policyID DESC
          LIMIT 1";
  $result = mysqli_query($con, $sql);
  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $minShareCapital = $row['p_minShareCapital'];
    $salaryDeductionForSaving = $row['p_salaryDeductionForSaving'];
    $salaryDeductionForMemberFund = $row['p_salaryDeductionForMemberFund'];
  } else {
      echo "Error: " . mysqli_error($con);
  }

  $f_month = $_POST['f_month'];
  $f_year = $_POST['f_year'];
  if (isset($_POST['selected_members']) && !empty($_POST['selected_members'])) {
    $selectedMembers = explode(',', $_POST['selected_members']);
    $action = $_POST['action'];

    foreach ($selectedMembers as $memberNo) {
      $sql_financial = "SELECT * FROM tb_financial WHERE f_memberNo = $memberNo;";
      $result_financial = mysqli_query($con, $sql_financial);
      $financial = mysqli_fetch_assoc($result_financial);

      $sql_loan = "SELECT tb_loan.*, tb_ltype.lt_desc
                   FROM tb_loan 
                   JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
                   WHERE tb_loan.l_memberNo = $memberNo AND tb_loan.l_status = 3;";
      $result_loan = mysqli_query($con, $sql_loan);

      $newShareCapital = $financial['f_shareCapital'];
      $newMemberFund = $financial['f_memberFund'];
      $newFixedSaving = $financial['f_fixedSaving'];
      
      if ($financial['f_shareCapital'] < $minShareCapital) {
        $newShareCapital += $salaryDeductionForSaving;
        if($newShareCapital > $minShareCapital) {
          $newFixedSaving += $newShareCapital - $minShareCapital;
          $newShareCapital = $minShareCapital;
        }
        $newMemberFund += $salaryDeductionForMemberFund;
      }
      else {
        $newMemberFund += $salaryDeductionForMemberFund;
        $newFixedSaving += $salaryDeductionForSaving;
      }

      // Update financial status
      $sql = "UPDATE tb_financial
              SET f_shareCapital = $newShareCapital,
                  f_memberFund = $newMemberFund,
                  f_fixedSaving = $newFixedSaving
              WHERE f_memberNo=$memberNo;";
      mysqli_query($con, $sql);
    
      // Log Update to Transaction
      if($newShareCapital != $financial['f_shareCapital']){
        $difference = $newShareCapital - $financial['f_shareCapital'];
        $sql = "INSERT INTO tb_transaction(t_transactionType, t_transactionAmt, t_month, t_year, t_desc, t_memberNo, t_adminID)
                VALUES ('1', '$difference', '$f_month', '$f_year', 'Potongan Gaji', '$memberNo', '$admin_id')";
        mysqli_query($con, $sql);
      }
      if($newFixedSaving != $financial['f_fixedSaving']){
        $difference = $newFixedSaving - $financial['f_fixedSaving'];
        $sql = "INSERT INTO tb_transaction(t_transactionType, t_transactionAmt, t_month, t_year, t_desc, t_memberNo, t_adminID)
                VALUES ('3', '$difference', '$f_month', '$f_year', 'Potongan Gaji', '$memberNo', '$admin_id')";
        mysqli_query($con, $sql);
      }
      if($newMemberFund != $financial['f_memberFund']){
        $difference = $newMemberFund - $financial['f_memberFund'];
        $sql = "INSERT INTO tb_transaction(t_transactionType, t_transactionAmt, t_month, t_year, t_desc, t_memberNo, t_adminID)
                VALUES ('4', '$difference', '$f_month', '$f_year',  'Potongan Gaji', '$memberNo', '$admin_id')";
        mysqli_query($con, $sql);
      }

      // Process Loans
      if(mysqli_num_rows($result_loan) > 0){
        while($row = mysqli_fetch_assoc($result_loan)){
            $loanID = $row['l_loanApplicationID'];
            $difference = $row['l_monthlyInstalment'];
            $status = 3;
            if ($difference > $row['l_loanPayable']){
                $difference = $row['l_loanPayable'];
                $status = 4;
            }
            $difference *= -1;
            $newLoanPayable = $row['l_loanPayable'] + $difference;
            $transactionType = $row['l_loanType'] + 5;
            $desc = "Potongan Gaji Bayaran Balik " . $loanID;
            
            $sql = "INSERT INTO tb_transaction(t_transactionType, t_transactionAmt, t_month, t_year, t_desc, t_memberNo, t_adminID)
                    VALUES ('$transactionType', $difference, '$f_month', '$f_year', '$desc', '$memberNo', '$admin_id');";
            mysqli_query($con, $sql);

            $sql = "UPDATE tb_loan
                    SET l_loanPayable = $newLoanPayable,
                        l_status = $status
                    WHERE l_loanApplicationID = $loanID;";
            mysqli_query($con, $sql);
        }
      }

    }

    // Redirect back to the transaction page after processing
    echo "
        <script>
            alert ('Data berjaya dikemaskini.');
            window.location.href = 'potongan_gaji.php';
        </script>";
  }
  else {
    echo "
        <script>
            alert ('Sila pilih sekurang-kurangnya satu anggota.');
            window.location.href = 'potongan_gaji.php';
        </script>";
  }
?>
