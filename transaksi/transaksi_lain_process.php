<?php 
  include('../kkksession.php');
  if (!session_id()) {
      session_start();
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
  $alBaiChange = $_POST['alBaiChange'];
  $alInnahChange = $_POST['alInnahChange'];
  $bPulihKenderaanChange = $_POST['bPulihKenderaanChange'];
  $roadTaxInsuranceChange = $_POST['roadTaxInsuranceChange'];
  $specialSchemeChange = $_POST['specialSchemeChange'];
  $specialSeasonCarnivalChange = $_POST['specialSeasonCarnivalChange'];
  $alQadrulHassanChange = $_POST['alQadrulHassanChange'];
  $desc = $_POST['f_desc'];

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
    6 => $alBaiChange,
    7 => $alInnahChange,
    8 => $bPulihKenderaanChange,
    9 => $roadTaxInsuranceChange,
    10 => $specialSchemeChange,
    11 => $specialSeasonCarnivalChange,
    12 => $alQadrulHassanChange
  ];

  foreach ($transactionTypes as $type => $changeAmount) {
    if ($changeAmount != 0) {
      $sql = "INSERT INTO tb_transaction (t_transactionType, t_transactionAmt, t_month, t_year, t_desc, t_memberNo, t_adminID)
              VALUES ('$type', '$changeAmount', '$currentMonth', '$currentYear', '$desc', '$memberNo', '$admin_id')";
      mysqli_query($con, $sql);
    }
  }

  $sql = "UPDATE tb_financial
        SET 
            f_shareCapital = f_shareCapital + '$shareCapitalChange',
            f_feeCapital = f_feeCapital + '$feeCapitalChange',
            f_fixedSaving = f_fixedSaving + '$fixedSavingChange',
            f_memberSaving = f_memberSaving + '$memberSavingChange',
            f_memberFund = f_memberFund + '$memberFundChange',
            f_alBai = f_alBai + '$alBaiChange',
            f_alInnah = f_alInnah + '$alInnahChange',
            f_bPulihKenderaan = f_bPulihKenderaan + '$bPulihKenderaanChange',
            f_roadTaxInsurance = f_roadTaxInsurance + '$roadTaxInsuranceChange',
            f_specialScheme = f_specialScheme + '$specialSchemeChange',
            f_specialSeasonCarnival = f_specialSeasonCarnival + '$specialSeasonCarnivalChange',
            f_alQadrulHassan = f_alQadrulHassan + '$alQadrulHassanChange'
        WHERE f_memberNo = '$memberNo'";
  mysqli_query($con, $sql);

  echo "
    <script>
        alert ('Data has been successfully updated!');
        window.location.href = 'transaksi_lain.php';
    </script>";

?>
