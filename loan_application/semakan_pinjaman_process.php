<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';


// Loan
if (!isset($_SESSION['loanApplicationID'])) {
    die('Error: Loan application ID is missing.');
}

$loanApplicationID = $_SESSION['loanApplicationID']; 

$loanType = $_POST['loanType']; 
$amaunDipohon = $_POST['amaunDipohon']; 
$tempohPembiayaan = $_POST['tempohPembiayaan']; 
$ansuranBulanan = $_POST['ansuranBulanan']; 
$tunggakan = $_POST['tunggakan'];
$namaBank = $_POST['namaBank']; 
$bankAcc = $_POST['bankAcc']; 
$gajiKasar = $_POST['gajiKasar']; 
$gajiBersih = $_POST['gajiBersih']; 

echo "Received POST data:<br>";
echo "loanApplicationID: " . $loanApplicationID . "<br>";
echo "loanType: " . $loanType . "<br>";
echo "amaunDipohon: " . $amaunDipohon . "<br>";
echo "tempohPembiayaan: " . $tempohPembiayaan . "<br>";
echo "ansuranBulanan: " . $ansuranBulanan . "<br>";
echo "tunggakan: " . $tunggakan . "<br>";
echo "namaBank: " . $namaBank . "<br>";
echo "bankAcc: " . $bankAcc . "<br>";
echo "gajiKasar: " . $gajiKasar . "<br>";
echo "gajiBersih: " . $gajiBersih . "<br>";

// signature image
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
        die('Error: Invalid file type. Only PNG, JPG, JPEG, and PDF files are allowed.');
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

// Update the user's data in the database
if (!empty($loanApplicationID)) {
    $sql = "UPDATE tb_loan 
            SET l_loanType = '$loanType', 
                l_appliedLoan = '$amaunDipohon', 
                l_loanPeriod = '$tempohPembiayaan', 
                l_monthlyInstalment = '$ansuranBulanan', 
                l_loanPayable = '$tunggakan',
                l_bankName = '$namaBank',
                l_bankAccountNo = '$bankAcc',
                l_monthlyGrossSalary = '$gajiKasar',
                l_monthlyNetSalary = '$gajiBersih',
                l_signature = '$fileSign'

            WHERE l_loanApplicationID = '$loanApplicationID'";

    echo "<br>SQL Query: " . $sql . "<br>";
    
    if (mysqli_query($con, $sql)) {
        echo "Update successful!<br>";
        header('Location: semakan_butiran.php?status=success');
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
} else {
    echo "Error: Missing member number.";
}

echo "<br>Session data:<br>";
echo "Session ID: " . session_id() . "<br>";
echo "loanApplicationID in session: " . $_SESSION['loanApplicationID'] . "<br>";

//close SQL
mysqli_close($con);
?>