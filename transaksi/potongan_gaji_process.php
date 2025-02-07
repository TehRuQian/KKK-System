<?php 
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
  $resitNo = $_POST['f_resitNo'];
  $file = $_FILES['transactionProof'];

  $target_dir = "bukti_transaksi/";
  $currentTimestamp = time();
  $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  $target_file = $target_dir . "potongan_gaji" . $currentTimestamp . "." . $fileType;
  $uploadOk = 1;

  if(isset($_POST["submit"])) {
    if ($fileType == "pdf") {
        $uploadOk = 1;
    } else {
        $check = getimagesize($_FILES["transactionProof"]["tmp_name"]);
        if($check !== false) {
            // The file is an image
            $uploadOk = 1;
        } else {
            $mssg = "Minta maaf, hanya fail JPG, JPEG, PNG, GIF, PDF dibenarkan.";
            $uploadOk = 0;
        }
    }
  }

  // Check file size
  if ($_FILES["transactionProof"]["size"] > 5242880) {
  //   echo "Sorry, your file is too large.";
    $mssg = "Minta maaf, fail ini terlalu besar.";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
  //   echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
    $alertType = 'error';
  } else {
    if (move_uploaded_file($_FILES["transactionProof"]["tmp_name"], $target_file)) {
      // echo "The file ". htmlspecialchars( basename( $_FILES["banner"]["name"])). " has been uploaded.";
      $proofPath = htmlspecialchars(basename($target_file));
    }
  }

  if (isset($_POST['selected_members']) && !empty($_POST['selected_members'])) {
    $selectedMembers = explode(',', $_POST['selected_members']);

    foreach ($selectedMembers as $memberNo) {
      $sql_financial = "SELECT * FROM tb_financial WHERE f_memberNo = $memberNo;";
      $result_financial = mysqli_query($con, $sql_financial);
      $financial = mysqli_fetch_assoc($result_financial);

      $sql_member = "SELECT * FROM tb_member WHERE m_memberNo = $memberNo;";
      $result_member = mysqli_query($con, $sql_member);
      $member = mysqli_fetch_assoc($result_member);

      $sql_loan = "SELECT tb_loan.*, tb_ltype.lt_desc
                    FROM tb_loan 
                    JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
                    WHERE tb_loan.l_memberNo = $memberNo AND tb_loan.l_status = 3;";
      $result_loan = mysqli_query($con, $sql_loan);

      if($salaryDeductionForSaving != $member['m_simpananTetap']){
        $balanceForSavingSalaryDeduction = $member['m_simpananTetap'];
      }
      else{
        $balanceForSavingSalaryDeduction = $salaryDeductionForSaving;
      }

      if($salaryDeductionForMemberFund != $member['m_alAbrar']){
        $balanceForFundSalaryDeduction = $member['m_alAbrar'];
      }
      else{
        $balanceForFundSalaryDeduction = $salaryDeductionForMemberFund;
      }
      $totalAmount = $balanceForSavingSalaryDeduction + $balanceForFundSalaryDeduction;

      $newShareCapital = $financial['f_shareCapital'];
      $newFeeCapital = $financial['f_feeCapital'];
      $newFixedSaving = $financial['f_fixedSaving'];
      $newMemberFund = $financial['f_memberFund'];
      $newMemberSaving = $financial['f_memberSaving'];
      
      // If fee not made yet
      if ($financial['f_feeCapital'] < $member['m_feeMasuk'] + $member['m_modalYuran']){
        if($balanceForSavingSalaryDeduction <= $member['m_feeMasuk'] + $member['m_modalYuran'] - $financial['f_feeCapital']){
          $newFeeCapital += $balanceForSavingSalaryDeduction;
          $balanceForSavingSalaryDeduction = 0;
        }
        else{
          $balanceForSavingSalaryDeduction -= $member['m_feeMasuk'] + $member['m_modalYuran'] - $financial['f_feeCapital'];
          $newFeeCapital += $member['m_feeMasuk'] + $member['m_modalYuran'] - $financial['f_feeCapital'];
        }
      }
      if ($financial['f_shareCapital'] < $member['m_modalSyer']) {
        $newShareCapital += $balanceForSavingSalaryDeduction;
        if($newShareCapital > $minShareCapital) {
          $newFixedSaving += $newShareCapital - $minShareCapital;
          $newShareCapital = $minShareCapital;
        }
        $newMemberFund += $balanceForFundSalaryDeduction;
      }
      else {
        $newMemberFund += $balanceForFundSalaryDeduction;
        $newFixedSaving += $balanceForSavingSalaryDeduction;
      }

      // Update financial status
      $sql = "UPDATE tb_financial
              SET f_shareCapital = $newShareCapital,
                  f_feeCapital = $newFeeCapital,
                  f_fixedSaving = $newFixedSaving,
                  f_memberFund = $newMemberFund,
                  f_memberSaving = $newMemberSaving
              WHERE f_memberNo=$memberNo;";
      mysqli_query($con, $sql);
    
      // Log Update to Transaction
      if($newShareCapital != $financial['f_shareCapital']){
        $difference = $newShareCapital - $financial['f_shareCapital'];
        $sql = "INSERT INTO tb_transaction(t_transactionType, t_method, t_transactionAmt, t_month, t_year, t_desc, t_resitNo, t_proof, t_memberNo, t_adminID)
                VALUES ('1', 'Potongan Gaji', '$difference', '$f_month', '$f_year', 'Potongan Gaji', '$resitNo', '$proofPath', '$memberNo','$admin_id')";
        mysqli_query($con, $sql);
      }
      if($newFeeCapital != $financial['f_feeCapital']){
        $difference = $newFeeCapital - $financial['f_feeCapital'];
        $sql = "INSERT INTO tb_transaction(t_transactionType, t_method, t_transactionAmt, t_month, t_year, t_desc, t_resitNo, t_proof, t_memberNo, t_adminID)
                VALUES ('2', 'Potongan Gaji', '$difference', '$f_month', '$f_year', 'Potongan Gaji', '$resitNo', '$proofPath', '$memberNo','$admin_id')";
        mysqli_query($con, $sql);
      }
      if($newFixedSaving != $financial['f_fixedSaving']){
        $difference = $newFixedSaving - $financial['f_fixedSaving'];
        $sql = "INSERT INTO tb_transaction(t_transactionType, t_method, t_transactionAmt, t_month, t_year, t_desc, t_resitNo, t_proof, t_memberNo, t_adminID)
                VALUES ('3', 'Potongan Gaji', '$difference', '$f_month', '$f_year', 'Potongan Gaji', '$resitNo', '$proofPath', '$memberNo','$admin_id')";
        mysqli_query($con, $sql);
      }
      if($newMemberFund != $financial['f_memberFund']){
        $difference = $newMemberFund - $financial['f_memberFund'];
        $sql = "INSERT INTO tb_transaction(t_transactionType, t_method, t_transactionAmt, t_month, t_year, t_desc, t_resitNo, t_proof, t_memberNo, t_adminID)
                VALUES ('4', 'Potongan Gaji', '$difference', '$f_month', '$f_year',  'Potongan Gaji', '$resitNo', '$proofPath', '$memberNo','$admin_id')";
        mysqli_query($con, $sql);
      }
      if($newMemberSaving != $financial['f_memberSaving']){
        $difference = $newMemberSaving - $financial['f_memberSaving'];
        $sql = "INSERT INTO tb_transaction(t_transactionType, t_method, t_transactionAmt, t_month, t_year, t_desc, t_resitNo, t_proof, t_memberNo, t_adminID)
                VALUES ('5', 'Potongan Gaji', '$difference', '$f_month', '$f_year',  'Potongan Gaji', '$resitNo', '$proofPath', '$memberNo','$admin_id')";
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
            $newLoanPayable = $row['l_loanPayable'] - $difference;
            $transactionType = $row['l_loanType'] + 5;
            $desc = "Potongan Gaji Bayaran Balik " . $loanID;
            
            $sql = "INSERT INTO tb_transaction(t_transactionType, t_method, t_transactionAmt, t_month, t_year, t_desc, t_resitNo, t_proof, t_memberNo, t_adminID)
                    VALUES ('$transactionType', 'Potongan Gaji', $difference, '$f_month', '$f_year', '$desc', '$resitNo', '$proofPath', '$memberNo','$admin_id');";
            mysqli_query($con, $sql);

            $sql = "UPDATE tb_loan
                    SET l_loanPayable = $newLoanPayable,
                        l_status = $status
                    WHERE l_loanApplicationID = $loanID;";
            mysqli_query($con, $sql);
        }
      }

    }
    echo "
        <script>
            Swal.fire({
                title: 'Berjaya!',
                text: 'Data berjaya dikemaskini.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'potongan_gaji.php';
            });
        </script>";
  } else {
    echo "
        <script>
            Swal.fire({
                title: 'Ralat!',
                text: 'Sila pilih sekurang-kurangnya satu anggota.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'potongan_gaji.php';
            });
        </script>";
  }

  if($uploadOk == 0){
    echo "
      <script>
        Swal.fire({
          text: '$mssg',
          title: 'Ralat',
          icon: '$alertType',
          confirmButtonText: 'OK',
          willClose: () => {
            window.location.href = 'potongan_gaji.php';
          }
        });
      </script>";
    }
?>
