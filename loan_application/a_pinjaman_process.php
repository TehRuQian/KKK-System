<?php
include('../kkksession.php');
if(!session_id())
{
  session_start();
}
if ($_SESSION['u_type'] != 2) {
    header('Location: ../login.php');
    exit();
  }
  
// Logout logic: Clear cookies when logging out
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    // Clear cookies by setting them with an expiration time in the past
    setcookie("jenis_pembiayaan", "", time() - 3600, "/");
    setcookie("amaunDipohon", "", time() - 3600, "/");
    setcookie("tempohPembiayaan", "", time() - 3600, "/");
    setcookie("ansuranBulanan", "", time() - 3600, "/");
    setcookie("namaBank", "", time() - 3600, "/");
    setcookie("bankAcc", "", time() - 3600, "/");
    setcookie("gajiKasar", "", time() - 3600, "/");
    setcookie("gajiBersih", "", time() - 3600, "/");
    setcookie("fileSign", "", time() - 3600, "/");

    // Destroy the session
    session_unset();
    session_destroy();
    header('Location: ../login.php');  // Redirect to login page after logout
    exit();
}

if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:../login.php'); 
  exit();
}

include '../headermember.php';
include '../db_connect.php';
$memberNo = $_SESSION['funame'];

// var_dump($_SESSION['funame']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store form data in cookies
    
    setcookie("jenis_pembiayaan", $_POST['jenis_pembiayaan'], time() + (86400 * 30), "/"); // expires in 30 days
    setcookie("amaunDipohon", $_POST['amaunDipohon'], time() + (86400 * 30), "/");
    setcookie("tempohPembiayaan", $_POST['tempohPembiayaan'], time() + (86400 * 30), "/");
    setcookie("ansuranBulanan", $_POST['ansuranBulanan'], time() + (86400 * 30), "/");
    setcookie("namaBank", $_POST['namaBank'], time() + (86400 * 30), "/");
    setcookie("bankAcc", $_POST['bankAcc'], time() + (86400 * 30), "/");
    setcookie("gajiKasar", $_POST['gajiKasar'], time() + (86400 * 30), "/");
    setcookie("gajiBersih", $_POST['gajiBersih'], time() + (86400 * 30), "/");
    setcookie("fileSign", $_FILES['fileSign']['name'], time() + (86400 * 30), "/");

} 



// Retrieve data from form
$jenis_pembiayaan = $_POST['jenis_pembiayaan'];
$amaunDipohon = $_POST['amaunDipohon'];
$tempohPembiayaan = $_POST['tempohPembiayaan'];
$ansuranBulanan = $_POST['ansuranBulanan'];
$namaBank = $_POST['namaBank'];
$bankAcc = $_POST['bankAcc'];
$gajiKasar = $_POST['gajiKasar'];
$gajiBersih = $_POST['gajiBersih'];
$tunggakan = $_POST['tunggakan'];

// File upload
$fileSign = '';
if (isset($_FILES['fileSign']) && $_FILES['fileSign']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['fileSign']['tmp_name'];
    $fileName = $_FILES['fileSign']['name'];
    $fileSize = $_FILES['fileSign']['size'];
    $fileType = $_FILES['fileSign']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Allowed file extensions and size limit (5 MB)
    $allowedExtensions = ['png', 'jpg', 'jpeg'];
    $maxFileSize = 5 * 1024 * 1024; // 5 MB

    if (!in_array($fileExtension, $allowedExtensions)) {
        die('Error: Invalid file type. Only PNG, JPG, and JPEG files are allowed.');
    }
    if ($fileSize > $maxFileSize) {
        die('Error: File size exceeds the 5MB limit.');
    }

    // Get the next loan ID
    $query = "SELECT MAX(l_loanApplicationID) as max_id FROM tb_loan";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $nextLoanID = $row['max_id'] ? $row['max_id'] + 1 : 1;

    // Create new filename with loan application ID
    $newFileName = "gambar_pemohon_" . $nextLoanID . "." . $fileExtension;

    // Directory to store the file
    $uploadFileDir = './uploads/';
    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0777, true); // Create the directory if it doesn't exist
    }
    $dest_path = $uploadFileDir . $newFileName;

    // Move the uploaded file to the destination directory
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $fileSign = $newFileName;
    } else {
        die('Error: File upload failed. Please try again.');
    }
} else {
    die('Error: No file uploaded or file upload error.');
}



// SQL Insert Operation
$sql = "INSERT INTO tb_loan (l_memberNo, l_loanType, l_appliedLoan, l_loanPeriod, l_monthlyInstalment, l_loanPayable, l_bankAccountNo, l_bankName, l_monthlyGrossSalary, l_monthlyNetSalary, l_signature, l_status, l_applicationDate) 
        VALUES ('$memberNo', '$jenis_pembiayaan', '$amaunDipohon', '$tempohPembiayaan', '$ansuranBulanan', '$tunggakan', '$bankAcc', '$namaBank', '$gajiKasar', '$gajiBersih', '$fileSign', 0, CURRENT_TIMESTAMP())";

if (mysqli_query($con, $sql)) {
    // Store the loanApplicationID in the session for the next page
    $loanApplicationID = mysqli_insert_id($con);
    $_SESSION['loanApplicationID'] = $loanApplicationID;

    // Redirect to butir_peribadi.php
    header('Location: b_butir_peribadi.php?status=success');
    exit();
} else {
    die('Error: Failed to submit the loan application. ' . mysqli_error($con));
}


// Close connection
$con->close();


?>
