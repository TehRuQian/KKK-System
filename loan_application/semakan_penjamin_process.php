<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit();
}

// Get the loan application ID from the session
$loanApplicationID = $_SESSION['loanApplicationID'];
$guarantorID1 = $_SESSION['guarantorID1'];
$guarantorID2 = $_SESSION['guarantorID2'];

// Debugging
if (!isset($loanApplicationID) || !isset($guarantorID1) || !isset($guarantorID2)) {
    die('Error: Required session data is missing.');
}

// Connect to the database
include('dbconnect.php');


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
    if (!isset($_FILES[$fileKey])) {
        echo "Error: No file uploaded for $fileKey."; // Debugging output
        die('Error: File upload failed.');
    }

    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        echo 'Upload error: ' . $_FILES[$fileKey]['error']; // Debugging output
        die('Error: File upload failed.');
    }

    $fileTmpPath = $_FILES[$fileKey]['tmp_name'];
    $fileNameCmps = explode(".", $_FILES[$fileKey]['name']);
    $fileExtension = strtolower(end($fileNameCmps));
    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    if (!in_array($fileExtension, $allowedExtensions)) {
        die('Error: Invalid file type. Only JPG, JPEG and PNG are allowed.');
    }

    if ($_FILES[$fileKey]['size'] > 5 * 1024 * 1024) {
        die('Error: File size exceeds the maximum limit of 5MB.');
    }

    $newFileName = md5(time() . $_FILES[$fileKey]['name']) . '.' . $fileExtension;
    $dest_path = $uploadFileDir . $newFileName;

    if (file_exists($dest_path)){
        $newFileName = md5(time() . rand()) . '.' . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;
    }


    if (!move_uploaded_file($fileTmpPath, $dest_path)) {
        die('Error: File upload failed.');
    }

    return $dest_path;
}

// Handle file uploads
$fileSignPenjamin1 = mysqli_real_escape_string($con, handleFileUpload('fileSignPenjamin1', $uploadFileDir));
$fileSignPenjamin2 = mysqli_real_escape_string($con, handleFileUpload('fileSignPenjamin2', $uploadFileDir));

echo "File1 path: " . $fileSignPenjamin1;
echo "File2 path: " . $fileSignPenjamin2;

// Prepare SQL query for insertion with quoted string
if (!empty($memberNo1)){
    $sql1 = "UPDATE tb_guarantor
             SET g_memberNo = '$memberNo1', 
                 g_signature = '$fileSignPenjamin1'

            WHERE g_guarantorID = '$guarantorID1'";
    //mysqli_query($con, $sql1);

    if (!mysqli_query($con, $sql1)) {
        die('Error: Unable to update guarantor 1 information. ' . mysqli_error($con));
    }
}

if (!empty($memberNo2)){
    $sql2 = "UPDATE tb_guarantor
             SET g_memberNo = '$memberNo2', 
                 g_signature = '$fileSignPenjamin2'

            WHERE g_guarantorID = '$guarantorID2'";
    mysqli_query($con, $sql2);

    if (!mysqli_query($con, $sql2)) {
        die('Error: Unable to update guarantor 2 information. ' . mysqli_error($con));
    }
}


// Redirect to semakan_butiran page
header('Location: semakan_butiran.php');

// Close the database connection
mysqli_close($con);
?>
