<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

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

// Policy data
$policyQuery = "SELECT p_maxFinancingAmt FROM tb_policies ORDER BY p_policyID DESC LIMIT 1";
$policyResult = mysqli_query($con, $policyQuery);
$policy = mysqli_fetch_assoc($policyResult);
$maxFinancingAmt = $policy['p_maxFinancingAmt'];

// Validate loan amount
if ($amaunDipohon > $maxFinancingAmt) {
    die('Error: Amaun Dipohon telah melebihi maksimum. Sila isi kurang daripada RM' . number_format($maxFinancingAmt, 2));
}

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

    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    // Directory to store the file
    $uploadFileDir = './uploads/';
    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0777, true); // Create the directory if it doesn't exist
    }
    $dest_path = $uploadFileDir . $newFileName;

    // Move the uploaded file to the destination directory
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $fileSign = $dest_path; // Store the file path in the database
    } else {
        die('Error: File upload failed. Please try again.');
    }
} else {
    die('Error: No file uploaded or file upload error.');
}

// Get the logged-in user's ID from the session
$memberNo = $_SESSION['u_id'];

// Validate member number exists in tb_member
$checkMemberQuery = "SELECT COUNT(*) AS count FROM tb_member WHERE m_memberNo = '$memberNo'";
$checkMemberResult = mysqli_query($con, $checkMemberQuery);
$checkMember = mysqli_fetch_assoc($checkMemberResult);

if ($checkMember['count'] == 0) {
    die('Error: Member number does not exist in the tb_member table.');
}

// SQL Insert Operation
$sql = "INSERT INTO tb_loan (l_memberNo, l_loanType, l_appliedLoan, l_loanPeriod, l_monthlyInstalment, l_bankAccountNo, l_bankName, l_monthlyGrossSalary, l_monthlyNetSalary, l_signature, l_status, l_applicationDate) 
        VALUES ('$memberNo', '$jenis_pembiayaan', '$amaunDipohon', '$tempohPembiayaan', '$ansuranBulanan', '$bankAcc', '$namaBank', '$gajiKasar', '$gajiBersih', '$fileSign', 1, CURRENT_TIMESTAMP())";

if (mysqli_query($con, $sql)) {
    // Store the loanApplicationID in the session for the next page
    $loanApplicationID = mysqli_insert_id($con);
    $_SESSION['loanApplicationID'] = $loanApplicationID;

    // Redirect to butir_peribadi.php
    header('Location: butir_peribadi.php?status=success');
    exit();
} else {
    die('Error: Failed to submit the loan application. ' . mysqli_error($con));
}

// Close connection
$con->close();
?>
