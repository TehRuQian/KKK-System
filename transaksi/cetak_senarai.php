<?php
  include('../kkksession.php');
  if (!session_id()) {
      session_start();
  }

  if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
  }

  // Ensure output buffering is turned on to prevent unexpected output
  ob_start();

  include '../db_connect.php';
  require('../fpdf/fpdf.php');

  // Get Year and Month Input
  $month = $_GET['filter_month'];
  $year = $_GET['filter_year'];

  // Retrieve Policy Info
  $sql = "SELECT p_minShareCapital, p_salaryDeductionForSaving, p_salaryDeductionForMemberFund, p_cutOffDay
          FROM tb_policies
          ORDER BY p_policyID DESC
          LIMIT 1";
  $result = mysqli_query($con, $sql);
  if ($result) {
    $policy = mysqli_fetch_assoc($result);
    $minShareCapital = $policy['p_minShareCapital'];
    $salaryDeductionForSaving = $policy['p_salaryDeductionForSaving'];
    $salaryDeductionForMemberFund = $policy['p_salaryDeductionForMemberFund'];
    $cutOffDay = $policy['p_cutOffDay'];
    $cutOffDate = $year . '-' . $month . '-' . $cutOffDay;
    $cutOffDate = date('Y-m-d', strtotime($cutOffDate));
  } else {
      echo "Error: " . mysqli_error($con);
  }

  $sql = "SELECT lt_lid, lt_desc FROM tb_ltype";
  $loanType = mysqli_query($con, $sql);

  $sql = "SELECT rm_desc FROM tb_rmonth
          WHERE rm_id = '$month'";
  $month_result = mysqli_query($con, $sql);
  $month_string = mysqli_fetch_assoc($month_result);
  $month_name = $month_string['rm_desc'];

  // Fetch Data from Database
  $sql = "SELECT *, DATE(m_approvalDate)
          FROM tb_member
          WHERE m_status = 3
          AND m_approvalDate < '$cutOffDate'";

  $result = mysqli_query($con, $sql);
  if (!$result) {
    die('Query Failed: ' . mysqli_error($con));
  }

  // Create PDF
  class PDF extends FPDF {
    function Header() {
      if(file_exists('../img/kkk_logo.png')) {
        $this->Image('../img/kkk_logo.png', 10, 6, 30);
      }
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(0, 5, 'SENARAI POTONGAN GAJI ANGGOTA KOPERASI KAKITANGAN KADA BERHAD', 0, 1, 'C');
      $this->Ln(5);

      global $month_name, $year;
      $subtitle = "Bulan: " . $month_name . "      Tahun: " . $year;
      $this->SetFont('Arial', 'B', 13);
      $this->Cell(0, 5, $subtitle, 0, 1, 'C');
      $this->Ln(10);
    }

    function Footer() {
      $this->SetY(-15);
      $this->SetFont('Arial', 'I', 8);

      date_default_timezone_set('Asia/Kuala_Lumpur');
      $currentDateTime = date('d-m-Y H:i:s');

      $this->SetX(10);
      $this->Cell(0, 10, 'Dihasilkan pada ' . $currentDateTime, 0, 0, 'L');
      
      $this->SetX(-40);
      $this->Cell(0, 10, 'Muka ' . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }
  

    function CreateTable($header, $data) {
      $w = array(5, 10, 45, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18, 18);
      $this->SetFont('Arial', 'B', 6);
      $this->SetFillColor(200, 200, 200);

      // Header
      // $x = $this->GetX();
      // $y = $this->GetY();
      // for($i = 0; $i < count($header); $i++) {
      //   $this->MultiCell($w[$i], 7, $header[$i], 1, 0, 'C', true);
      //   $this->SetXY($x + array_sum(array_slice($w, 0, $i+1)), $y);
      // }
      for($i = 0; $i < count($header); $i++) {
        $this->Cell($w[$i], 6, $header[$i], 1, 0, 'C', true);
      }
      $this->Ln();

      $this->SetFont('Arial', '', 8);
      $this->SetFillColor(240, 240, 240);

      foreach($data as $index => $row) {
        $fill = ($index % 2 == 0);

        $status = ($row[0] == 1) ? '/' : '';
        $row[0] = $status;

        foreach ($row as $key => $col) {
          $this->Cell($w[$key], 6, $col, 1, 0, 'L', $fill);
      }
        $this->Ln();
      }
    }
  }

  // Check if the results are empty and show an alert if no data found
  $data = [];
  $grandTotal = 0;
  $grandTotalTrue = 0;
  $grandTotalFalse = 0;
  while ($member = mysqli_fetch_assoc($result)) {
    $memberNo = $member['m_memberNo'];

    // Get infromation from every member
    $sql_financial = "SELECT * FROM tb_financial WHERE f_memberNo = $memberNo;";
    $result_financial = mysqli_query($con, $sql_financial);
    $financial = mysqli_fetch_assoc($result_financial);

    $sql_loan = "SELECT tb_loan.*, tb_ltype.lt_desc
                  FROM tb_loan 
                  JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
                  WHERE tb_loan.l_memberNo = $memberNo 
                  AND tb_loan.l_status = 3
                  AND DATE(tb_loan.l_approvalDate) < '$cutOffDate' ;";
    $result_loan = mysqli_query($con, $sql_loan);

    $sql_transaction = "SELECT COUNT(t_transactionID) FROM tb_transaction
                        WHERE t_memberNo = $memberNo
                        AND t_month = $month
                        AND t_year = $year
                        AND t_method = 'Potongan Gaji';";
    $result_transaction = mysqli_query($con, $sql_transaction);
    $record_exists = mysqli_fetch_row($result_transaction)[0] > 0;

    // Calculation
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

    $diffShareCapital = $newShareCapital - $financial['f_shareCapital'];
    $diffFeeCapital = $newFeeCapital - $financial['f_feeCapital'];
    $diffFixedSaving = $newFixedSaving - $financial['f_fixedSaving'];
    $diffMemberFund = $newMemberFund - $financial['f_memberFund'];

    $alBai = 0;
    $alInnah = 0;
    $bPulihKenderaan = 0;
    $roadTax = 0;
    $khas = 0;
    $karnivalMusim = 0;
    $alQadrul = 0;

    while($row = mysqli_fetch_assoc($result_loan)){
      $difference = $row['l_monthlyInstalment'];
      if ($difference > $row['l_loanPayable']){
          $difference = $row['l_loanPayable'];
      }
      if($row['l_loanType'] == 1){
        $alBai += $difference;
      }
      else if($row['l_loanType'] == 2){
        $alInnah += $difference;
      }
      else if($row['l_loanType'] == 3){
        $bPulihKenderaan += $difference;
      }
      else if($row['l_loanType'] == 4){
        $roadTax += $difference;
      }
      else if($row['l_loanType'] == 5){
        $khas += $difference;
      }
      else if($row['l_loanType'] == 6){
        $karnivalMusim += $difference;
      }
      else if($row['l_loanType'] == 7){
        $alQadrul += $difference;
      }
    }
    $total = $diffShareCapital + $diffFeeCapital + $diffFixedSaving + $diffMemberFund + $alBai + $alInnah + $bPulihKenderaan + $roadTax + $khas + $karnivalMusim + $alQadrul;

    $data[] = [
      $record_exists,
      $memberNo,
      $member['m_name'],
      number_format($diffShareCapital, 2), 
      number_format($diffFeeCapital, 2),
      number_format($diffFixedSaving, 2),
      number_format($diffMemberFund, 2),
      number_format($alBai, 2),
      number_format($alInnah, 2),
      number_format($bPulihKenderaan, 2),
      number_format($roadTax, 2),
      number_format($khas, 2),
      number_format($karnivalMusim, 2),
      number_format($alQadrul, 2),
      number_format($total, 2)
    ];

    if($record_exists){
      $grandTotalTrue += $total;
    }
    else{
      $grandTotalFalse += $total;
    }
  }
  $grandTotal = $grandTotalTrue + $grandTotalFalse;

  if (empty($data)) {
    echo "
      <script>
        // Show alert if no data is found
        Swal.fire({
          title: 'Tiada Data!',
          text: 'Tiada data ditemui untuk bulan dan tahun yang dipilih.',
          icon: 'warning',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = 'potongan_gaji.php'; // Redirect if no data
        });
      </script>";
    exit;  // Stop the script execution if no data is found
  }

  try {
    $pdf = new PDF('L', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 10);

    // Define the header for the table
    $header = ['', 'No Ahli', 'Nama', 'Modah Syer', 'Modal Yuran', 'Simpanan Tetap', 'Tabung Anggota',
               'Al-Bai', 'Al-Innah', 'BPulih Kenderaan', 'Cukai Jalan', 'Khas', 'Karnival Musim', 'Al-Qadrul Hassan',
                'Jumlah'];

    // Create table with data
    $pdf->CreateTable($header, $data);

    $pdf->Ln(10); 

    // Totals
    $pdf->SetFont('Arial', 'B', 9);

    $pdf->Cell(85, 7, 'Jumlah Potongan Gaji Yang Telah Direkodkan', 0, 0, 'L');
    $pdf->Cell(40, 7, number_format($grandTotalTrue, 2), 1, 1, 'R');

    $pdf->Cell(85, 7, 'Jumlah Potongan Gaji Yang Belum Direkodkan', 0, 0, 'L');
    $pdf->Cell(40, 7, number_format($grandTotalFalse, 2), 1, 1, 'R');

    $pdf->Cell(85, 7, 'Jumlah Keseluruhan Potongan Gaji', 0, 0, 'L');
    $pdf->Cell(40, 7, number_format($grandTotal, 2), 1, 1, 'R');

    // Output the PDF (no further output after this point)
    ob_end_clean(); // Ensure no additional output is sent
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="senarai_potongan_gaji.pdf"');
    $pdf->Output('I');
    exit;
  } catch (Exception $e) {
    error_log("PDF Generation Error: " . $e->getMessage());
    echo "Error generating PDF: " . $e->getMessage();
  }
?>
