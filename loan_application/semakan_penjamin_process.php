<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

// Get the loan application ID from the session
$loanApplicationID = $_SESSION['loanApplicationID'];
$guarantorID1 = $_SESSION['guarantorID1'];
$guarantorID2 = $_SESSION['guarantorID2'];

// Debugging
if (!isset($loanApplicationID) || !isset($guarantorID1) || !isset($guarantorID2)) {
    die('Error: Required session data is missing.');
}

// Retrieve data from form
$memberNo1 = $_POST['anggotaPenjamin1'];
$memberNo2 = $_POST['anggotaPenjamin2'];

// File upload directory
$uploadFileDir = './uploads/';
if (!is_dir($uploadFileDir)) {
    if (!mkdir($uploadFileDir, 777, true)) {
        die('Error: Failed to create the upload directory.');
    }
}

// Function to handle file uploads
function handleFileUpload($fileKey, $uploadFileDir) {
    
    if (!isset($_FILES[$fileKey]) || empty($_FILES[$fileKey]['name'])) {
        return ''; 
    }

    if ($_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'File size exceeds the limit set in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'File size exceeds the limit set in the form',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Temporary folder not found',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk'
        ];
        $errorMessage = isset($errorMessages[$_FILES[$fileKey]['error']]) 
            ? $errorMessages[$_FILES[$fileKey]['error']] 
            : 'error in uploading file';
        error_log("File upload error for $fileKey: $errorMessage");
        return '';
    }

    
    $fileName = $_FILES[$fileKey]['name'];
    $tmpName = $_FILES[$fileKey]['tmp_name'];
    $fileSize = $_FILES[$fileKey]['size'];
    $fileType = $_FILES[$fileKey]['type'];

    
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $fileExtension;
    $dest_path = $uploadFileDir . $newFileName;

    
    if (!move_uploaded_file($tmpName, $dest_path)) {
        error_log("Failed to move uploaded file: $fileKey");
        return '';
    }

    return $dest_path;
}


$sql_check1 = "SELECT g_signature FROM tb_guarantor WHERE g_guarantorID = '$guarantorID1'";
$sql_check2 = "SELECT g_signature FROM tb_guarantor WHERE g_guarantorID = '$guarantorID2'";

$result1 = mysqli_query($con, $sql_check1);
$result2 = mysqli_query($con, $sql_check2);

$existing_signature1 = '';
$existing_signature2 = '';

if ($row1 = mysqli_fetch_assoc($result1)) {
    $existing_signature1 = $row1['g_signature'];
}
if ($row2 = mysqli_fetch_assoc($result2)) {
    $existing_signature2 = $row2['g_signature'];
}

$fileSignPenjamin1 = $existing_signature1; 
$fileSignPenjamin2 = $existing_signature2; 


if (isset($_FILES['fileSignPenjamin1']) && !empty($_FILES['fileSignPenjamin1']['name'])) {
    
    if (!empty($existing_signature1) && file_exists($existing_signature1)) {
        unlink($existing_signature1);
    }
    $newFile1 = handleFileUpload('fileSignPenjamin1', $uploadFileDir);
    if ($newFile1 !== '') {
        $fileSignPenjamin1 = mysqli_real_escape_string($con, $newFile1);
        error_log("Debug - New Guarantor 1 file path: " . $fileSignPenjamin1);
    }
}


if (isset($_FILES['fileSignPenjamin2']) && !empty($_FILES['fileSignPenjamin2']['name'])) {
    
    if (!empty($existing_signature2) && file_exists($existing_signature2)) {
        unlink($existing_signature2);
    }
    $newFile2 = handleFileUpload('fileSignPenjamin2', $uploadFileDir);
    if ($newFile2 !== '') {
        $fileSignPenjamin2 = mysqli_real_escape_string($con, $newFile2);
        error_log("Debug - New Guarantor 2 file path: " . $fileSignPenjamin2);
    }
}


if (!empty($memberNo1)) {
    $sql1 = "UPDATE tb_guarantor 
             SET g_memberNo = '$memberNo1', 
                 g_signature = '$fileSignPenjamin1'
             WHERE g_guarantorID = '$guarantorID1'";
    
    error_log("Debug - SQL Query 1: " . $sql1);
    
    if (!mysqli_query($con, $sql1)) {
        error_log("Debug - MySQL Error 1: " . mysqli_error($con));
        die('Error: Unable to update guarantor 1 information. ' . mysqli_error($con));
    }
}


if (!empty($memberNo2)) {
    $sql2 = "UPDATE tb_guarantor 
             SET g_memberNo = '$memberNo2', 
                 g_signature = '$fileSignPenjamin2'
             WHERE g_guarantorID = '$guarantorID2'";
    
    error_log("Debug - SQL Query 2: " . $sql2);
    
    if (!mysqli_query($con, $sql2)) {
        error_log("Debug - MySQL Error 2: " . mysqli_error($con));
        die('Error: Unable to update guarantor 2 information. ' . mysqli_error($con));
    }
}


error_log("Debug - Update completed successfully");

// Redirect to semakan_butiran page
header('Location: semakan_butiran.php?status=success');

// Close the database connection
mysqli_close($con);
?>