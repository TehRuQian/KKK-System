<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';


if (isset($_SESSION['loanApplicationID'])) {
    $loanApplicationID = $_SESSION['loanApplicationID'];
} elseif (isset($_GET['loan_id'])) {
    $loanApplicationID = $_GET['loan_id'];
} else {
    die("Error: Loan application ID is missing. Please check if the loan ID is passed in the URL.");
}

// Guarantor
if (!isset($_SESSION['guarantorID1']) || !isset($_SESSION['guarantorID2'])) {
    if (!isset($_SESSION['guarantorID1'])) {
        $anggotaPenjamin1 = ''; 
        $signaturePenjamin1 = '';
        $namaPenjamin1 = '';
        $icPenjamin1 = '';
        $pfPenjamin1 = '';
        $guarantorID1 = null; 
    } else {
        $guarantorID1 = $_SESSION['guarantorID1']; 
    }

    if (!isset($_SESSION['guarantorID2'])) {
        $anggotaPenjamin2 = '';
        $signaturePenjamin2 = '';
        $namaPenjamin2 = '';
        $icPenjamin2 = '';
        $pfPenjamin2 = '';
        $guarantorID2 = null; 
    } else {
        $guarantorID2 = $_SESSION['guarantorID2']; 
    }
} else {
    $guarantorID1 = $_SESSION['guarantorID1']; 
    $guarantorID2 = $_SESSION['guarantorID2']; 
}
if ($_SESSION['u_type'] != 2) {
    header('Location: ../login.php');
    exit();
  }
  



$memberNo1 = isset($_POST['anggotaPenjamin1']) ? $_POST['anggotaPenjamin1'] : '';
$memberNo2 = isset($_POST['anggotaPenjamin2']) ? $_POST['anggotaPenjamin2'] : '';
$namaPenjamin2 = isset($_POST['namaPenjamin2']) ? $_POST['namaPenjamin2'] : '';

if (empty($memberNo2) || empty($namaPenjamin2)) {
    error_log("Guarantor 2 data is missing.");
} else {
    error_log("Guarantor 2 Member No: $memberNo2");
    error_log("Guarantor 2 Name: $namaPenjamin2");
}

try {
    
    mysqli_begin_transaction($con);

    
    $stmt1 = mysqli_prepare($con, "SELECT g_signature FROM tb_guarantor WHERE g_guarantorID = ?");
    mysqli_stmt_bind_param($stmt1, "s", $guarantorID1);
    mysqli_stmt_execute($stmt1);
    $result1 = mysqli_stmt_get_result($stmt1);
    $existing_signature1 = '';
    if ($row1 = mysqli_fetch_assoc($result1)) {
        $existing_signature1 = $row1['g_signature'];
    }
    mysqli_stmt_close($stmt1);

    
    $stmt2 = mysqli_prepare($con, "SELECT g_signature FROM tb_guarantor WHERE g_guarantorID = ?");
    mysqli_stmt_bind_param($stmt2, "s", $guarantorID2);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);
    $existing_signature2 = '';
    if ($row2 = mysqli_fetch_assoc($result2)) {
        $existing_signature2 = $row2['g_signature'];
    }
    mysqli_stmt_close($stmt2);

    $uploadFileDir = './uploads/';
    if (!is_dir($uploadFileDir)) {
        if (!mkdir($uploadFileDir, 0777, true)) {
            throw new Exception('Failed to create the upload directory.');
        }
    }

    $fileSignPenjamin1 = $existing_signature1;
    if (isset($_FILES['fileSignPenjamin1']) && !empty($_FILES['fileSignPenjamin1']['name'])) {
        
        if (!empty($existing_signature1)) {
            $oldFile = $uploadFileDir . basename($existing_signature1);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        $newFile1 = handleFileUpload('fileSignPenjamin1', $uploadFileDir);
        if ($newFile1 !== '') {
            
            $fileSignPenjamin1 = $newFile1;
        }
    }

    $fileSignPenjamin2 = $existing_signature2;
    if (isset($_FILES['fileSignPenjamin2']) && !empty($_FILES['fileSignPenjamin2']['name'])) {
      
        if (!empty($existing_signature2)) {
            $oldFile = $uploadFileDir . basename($existing_signature2);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }
        $newFile2 = handleFileUpload('fileSignPenjamin2', $uploadFileDir);
        if ($newFile2 !== '') {
           
            $fileSignPenjamin2 = $newFile2;
        }
    }

    
    if (!empty($memberNo1)) {
        error_log("Updating guarantor 1 with ID: $guarantorID1, Member No: $memberNo1, Signature: $fileSignPenjamin1");
        $update_stmt1 = mysqli_prepare($con, "UPDATE tb_guarantor SET g_memberNo = ?, g_signature = ? WHERE g_guarantorID = ?");
        mysqli_stmt_bind_param($update_stmt1, "sss", $memberNo1, $fileSignPenjamin1, $guarantorID1);
        if (!mysqli_stmt_execute($update_stmt1)) {
            error_log("Failed to execute update for guarantor 1: " . mysqli_stmt_error($update_stmt1));
            throw new Exception("Failed to update guarantor 1: " . mysqli_stmt_error($update_stmt1));
        } else {
            error_log("Successfully updated guarantor 1 with ID: " . $guarantorID1);
        }
        mysqli_stmt_close($update_stmt1);
    }

    
    if (!empty($memberNo2)) {
        error_log("Updating guarantor 2 with ID: $guarantorID2, Member No: $memberNo2, Signature: $fileSignPenjamin2");
        $update_stmt2 = mysqli_prepare($con, "UPDATE tb_guarantor SET g_memberNo = ?, g_signature = ? WHERE g_guarantorID = ?");
        mysqli_stmt_bind_param($update_stmt2, "sss", $memberNo2, $fileSignPenjamin2, $guarantorID2);
        if (!mysqli_stmt_execute($update_stmt2)) {
            error_log("Failed to execute update for guarantor 2: " . mysqli_stmt_error($update_stmt2));
            throw new Exception("Failed to update guarantor 2: " . mysqli_stmt_error($update_stmt2));
        } else {
            error_log("Successfully updated guarantor 2 with ID: " . $guarantorID2);
        }
        mysqli_stmt_close($update_stmt2);
    }

    
    mysqli_commit($con);
    
   
    $_SESSION['success_message'] = "successful";
    
   
    header('Location: semakan_butiran.php?status=success&loan_id=' . $loanApplicationID);
    exit();

} catch (Exception $e) {
    
    mysqli_rollback($con);
    error_log("Error updating guarantors: " . $e->getMessage());
    die("Error: " . $e->getMessage());
} finally {
    
    mysqli_close($con);
}


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
            : 'Unknown upload error';
        error_log("File upload error for $fileKey: $errorMessage");
        return '';
    }

    $fileName = $_FILES[$fileKey]['name'];
    $tmpName = $_FILES[$fileKey]['tmp_name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    
    
   // Set the filename based on which guarantor (penjamin)
    if (strpos($fileKey, '1') !== false) {
        $newFileName = "gambar_penjamin1_" .$GLOBALS['loanApplicationID']. "." . $fileExtension;
    } else if (strpos($fileKey, '2') !== false) {
        $newFileName = "gambar_penjamin2_" . $GLOBALS['loanApplicationID']. "." . $fileExtension;
    } else {
        die('Error: Invalid file key');
    }

    $dest_path = $uploadFileDir . $newFileName;

    if (!move_uploaded_file($tmpName, $dest_path)) {
        error_log("Failed to move uploaded file: $fileKey");
        return '';
    }

    return $newFileName;
}
?>