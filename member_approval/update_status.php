<?php 

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

include '../header_admin.php';
include '../db_connect.php';
// Check if session variables are set
if (!isset($_SESSION['uid'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Loan
if (!isset($_SESSION['loanApplicationID'])) {
    die('Error: Loan application ID is missing.');
}

$loanApplicationID = $_SESSION['loanApplicationID']; 

$loanType = $_POST['loanType']; 
$amaunDipohon = $_POST['amaunDipohon']; 
$tempohPembiayaan = $_POST['tempohPembiayaan']; 
$ansuranBulanan = $_POST['ansuranBulanan']; 
$namaBank = $_POST['namaBank']; 
$bankAcc = $_POST['bankAcc']; 
$gajiKasar = $_POST['gajiKasar']; 
$gajiBersih = $_POST['gajiBersih']; 
$signature = $_POST['signature']; 


// Update the user's data in the database
if (!empty($loanApplicationID)) {
    $sql = "UPDATE tb_loan 
            SET l_loanType = '$loanType', 
                l_appliedLoan = '$amaunDipohon', 
                l_loanPeriod = '$tempohPembiayaan', 
                l_monthlyInstalment = '$ansuranBulanan', 
                l_bankName = '$namaBank',
                l_bankAccountNo = '$bankAcc',
                l_monthlyGrossSalary = '$gajiKasar',
                l_monthlyNetSalary = '$gajiBersih',
                l_signature = '$signature'

            WHERE l_loanApplicationID = '$loanApplicationID'";
    
    if (mysqli_query($con, $sql)) {
  
        header('Location: semakan_butiran.php');
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
} else {
    echo "Error: Missing member number.";
}

//close SQL
mysqli_close($con);
?>