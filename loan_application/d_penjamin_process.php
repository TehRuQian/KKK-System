<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}
if ($_SESSION['u_type'] != 2) {
    header('Location: ../login.php');
    exit();
  }
  
include '../headermember.php';
include '../db_connect.php';

// Get the loan application ID from the session
if (!isset($_SESSION['loanApplicationID'])) {
    die('Error: Loan Application ID is missing.');
}
$loanApplicationID = $_SESSION['loanApplicationID'];

// Debug the loanApplicationID
echo 'Loan Application ID from session: ' . $_SESSION['loanApplicationID'];

// Retrieve data from form and store in session
$memberNo1 = $_POST['anggotaPenjamin1'];
$memberNo2 = $_POST['anggotaPenjamin2'];

$_SESSION['anggotaPenjamin1'] = $_POST['anggotaPenjamin1'];
$_SESSION['namaPenjamin1'] = $_POST['namaPenjamin1'];
$_SESSION['icPenjamin1'] = $_POST['icPenjamin1'];
$_SESSION['pfPenjamin1'] = $_POST['pfPenjamin1'];

$_SESSION['anggotaPenjamin2'] = $_POST['anggotaPenjamin2'];
$_SESSION['namaPenjamin2'] = $_POST['namaPenjamin2'];
$_SESSION['icPenjamin2'] = $_POST['icPenjamin2'];
$_SESSION['pfPenjamin2'] = $_POST['pfPenjamin2'];

// File upload directory
$uploadFileDir = './uploads/';
if (!is_dir($uploadFileDir)) {
    if (!mkdir($uploadFileDir, 0777, true)) {
        die('Error: Failed to create the upload directory.');
    }
}

// Function to handle file uploads
function handleFileUpload($fileKey, $uploadFileDir) {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        die('Error: File upload failed.');
    }

    $fileTmpPath = $_FILES[$fileKey]['tmp_name'];
    $fileNameCmps = explode(".", $_FILES[$fileKey]['name']);
    $fileExtension = strtolower(end($fileNameCmps));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    if (!in_array($fileExtension, $allowedExtensions)) {
        die('Error: Invalid file type. Only JPG, JPEG, PNG, and PDF are allowed.');
    }

    if ($_FILES[$fileKey]['size'] > $maxFileSize) {
        die('Error: File size exceeds 5MB limit for ' . $fileKey);
    }

    
    // Set the filename based on which guarantor (penjamin)
    if (strpos($fileKey, '1') !== false) {
        $newFileName = "gambar_penjamin1_" . $GLOBALS['loanApplicationID'] . "." . $fileExtension;
    } else if (strpos($fileKey, '2') !== false) {
        $newFileName = "gambar_penjamin2_" . $GLOBALS['loanApplicationID'] . "." . $fileExtension;
    } else {
        die('Error: Invalid file key');
    }
    
    $dest_path = $uploadFileDir . $newFileName;

    if (!move_uploaded_file($fileTmpPath, $dest_path)) {
        die('Error: File upload failed.');
    }

    return $newFileName;
}

// Handle file uploads
$fileSignPenjamin1 = mysqli_real_escape_string($con, handleFileUpload('fileSignPenjamin1', $uploadFileDir));
$fileSignPenjamin2 = mysqli_real_escape_string($con, handleFileUpload('fileSignPenjamin2', $uploadFileDir));

// Prepare SQL query for insertion with quoted string
$sql1 = "INSERT INTO tb_guarantor (g_loanApplicationID, g_memberNo, g_signature) 
         VALUES ('$loanApplicationID', '$memberNo1', '$fileSignPenjamin1')";
$sql2 = "INSERT INTO tb_guarantor (g_loanApplicationID, g_memberNo, g_signature) 
         VALUES ('$loanApplicationID', '$memberNo2', '$fileSignPenjamin2')";

// Execute SQL query
if (mysqli_query($con, $sql1)) {
    $guarantorID1 = mysqli_insert_id($con);
    $_SESSION['guarantorID1'] = $guarantorID1;
} else {
    die('Error inserting guarantor 1: ' . mysqli_error($con));
}

if (mysqli_query($con, $sql2)) {
    $guarantorID2 = mysqli_insert_id($con);
    $_SESSION['guarantorID2'] = $guarantorID2;
} else {
    die('Error inserting guarantor 2: ' . mysqli_error($con));
}

// Redirect to success page after successful insertion
header('Location: e_pengesahan_majikan.php?status=success');
exit();

// Close the database connection
mysqli_close($con);
?>
