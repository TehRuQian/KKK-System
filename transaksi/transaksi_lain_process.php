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

  $memberNo = $_POST['memberNo'];
  $shareCapitalChange = $_POST['shareCapitalChange'];
  $feeCapitalChange = $_POST['feeCapitalChange'];
  $fixedSavingChange = $_POST['fixedSavingChange'];
  $memberSavingChange = $_POST['memberSavingChange'];
  $memberFundChange = $_POST['memberFundChange'];
  $desc = $_POST['f_desc'];
  $resitNo = $_POST['f_resitNo'];
  $file = $_FILES['transactionProof'];

  $target_dir = "bukti_transaksi/";
  $currentTimestamp = time();
  $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  $target_file = $target_dir . "transaksi_tambahan_" . $currentTimestamp . "." . $fileType;
  $uploadOk = 1;

  // Check if file is an image or a valid PDF
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

      // Handle transaction
      $currentMonth = date('n');
      $currentYear = date('Y');

      $sql = "
        SELECT tb_financial.*, tb_member.m_name, tb_member.m_ic, tb_member.m_pfNo
        FROM tb_financial
        INNER JOIN tb_member
        ON tb_financial.f_memberNo=tb_member.m_memberNo
        WHERE f_memberNo = '$memberNo';";
      $result = mysqli_query($con, $sql);

      if ($result && mysqli_num_rows($result) == 1){
        $financial = mysqli_fetch_assoc($result);
      }

      // Update transaction table
      $transactionTypes = [
        1 => $shareCapitalChange,
        2 => $feeCapitalChange,
        3 => $fixedSavingChange,
        4 => $memberSavingChange,
        5 => $memberFundChange,
      ];

      foreach ($transactionTypes as $type => $changeAmount) {
        if ($changeAmount != 0) {
          $sql = "INSERT INTO tb_transaction (t_transactionType, t_method, t_transactionAmt, t_month, t_year, t_desc, t_resitNo, t_proof, t_memberNo, t_adminID)
                  VALUES ('$type', 'Transaksi Tambahan', '$changeAmount', '$currentMonth', '$currentYear', '$desc', '$resitNo', '$proofPath', '$memberNo', '$admin_id')";
          mysqli_query($con, $sql);
        }
      }

      if (isset($_POST['payment']) && is_array($_POST['payment'])) {
        foreach ($_POST['payment'] as $loanID => $paymentAmount) {
          if($paymentAmount != 0){
            $sql = "SELECT * FROM tb_loan WHERE l_loanApplicationID = '$loanID' AND l_memberNo = '$memberNo'";
            $result_loan = mysqli_query($con, $sql);
            if ($result_loan && mysqli_num_rows($result_loan) == 1) {
              $loan = mysqli_fetch_assoc($result_loan);
              $newLoanPayable = $loan['l_loanPayable'] - $paymentAmount;

              if($newLoanPayable == 0){
                $status = 4;
              }
              else{
                $status = 3;
              }

              // Update loan table
              $sql = "UPDATE tb_loan 
                      SET l_loanPayable = '$newLoanPayable',
                      l_status = '$status'
                      WHERE l_loanApplicationID = '$loanID'";
              mysqli_query($con, $sql);

              $sql = "SELECT l_loanType from tb_loan 
                      WHERE l_loanApplicationID = '$loanID'";
              $result = mysqli_fetch_assoc(mysqli_query($con, $sql));
              $ttype = $result['l_loanType'] + 5;
              $desc .= ": Bayaran Balik " . $loanID;

              // Log transaction table
              $sql = "INSERT INTO tb_transaction (t_transactionType, t_method, t_transactionAmt, t_month, t_year, t_desc, t_resitNo, t_proof, t_memberNo, t_adminID)
                      VALUES ('$ttype', 'Transaksi Tambahan', '$paymentAmount', '$currentMonth', '$currentYear', '$desc', '$resitNo', '$proofPath', '$memberNo', '$admin_id')";
              mysqli_query($con, $sql);
            }
          }
        }
      }

      $sql = "UPDATE tb_financial
            SET 
                f_shareCapital = f_shareCapital + '$shareCapitalChange',
                f_feeCapital = f_feeCapital + '$feeCapitalChange',
                f_fixedSaving = f_fixedSaving + '$fixedSavingChange',
                f_memberSaving = f_memberSaving + '$memberSavingChange',
                f_memberFund = f_memberFund + '$memberFundChange'
            WHERE f_memberNo = '$memberNo'";
      mysqli_query($con, $sql);

      echo "
        <script>
            Swal.fire({
                title: 'Berjaya!',
                text: 'Data berjaya dikemaskini.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'transaksi_lain.php';
            });
        </script>";

    } else {
      // echo "Sorry, there was an error uploading your file.";
      $uploadOk == 0;
      $mssg = "Maaf, terdapat ralat semasa memuat naik fail anda.";
      $alertType = 'error';
    }
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
          window.location.href = 'transaksi_lain.php';
        }
      });
    </script>";
  }
?>