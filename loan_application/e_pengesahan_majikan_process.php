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

$memberNo = $_SESSION['funame'];

if (!isset($_SESSION['u_id'])) {
    echo "<script>
            window.location.href = '../login.php';
          </script>";
    exit();
}

if (!isset($_SESSION['loanApplicationID'])) {
    echo "<script>
        alert('Sila simpan maklumat Butir-Butir Pembiayaan.');
        window.location.href = 'a_pinjaman.php'; 
        </script>";
    exit();
}

$loanApplicationID = $_SESSION['loanApplicationID'];

// File upload handling
if(isset($_FILES['pengesahanMajikan'])) {
    $file = $_FILES['pengesahanMajikan'];
    
    // File properties
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];
    
    // Get file extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    // Allowed file types
    $allowed = array('pdf');
    
    if(in_array($file_ext, $allowed)) {
        if($file_error === 0) {
            if($file_size <= 5242880) { // 5MB limit (5 * 1024 * 1024)
                
                // Create unique filename
                $file_name_new = "pengesahan_majikan_" . $loanApplicationID . "_" . time() . "." . $file_ext;
                $file_destination = './uploads/' . $file_name_new; 
                
                // Create uploads directory if it doesn't exist
                if (!file_exists('../uploads/')) {
                    mkdir('../uploads/', 0777, true);
                }
                
                if(move_uploaded_file($file_tmp, $file_destination)) {
                    // Update database with file path
                    $sql = "UPDATE tb_loan SET 
                            l_file= ?
                            WHERE l_loanApplicationID = ?";
                            
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_bind_param($stmt, "si", $file_name_new, $loanApplicationID);
                    
                    if(mysqli_stmt_execute($stmt)) {
                        header('Location:f_akuan_kebenaran.php?status=success');
                        exit();
                    } else {
                        echo "<script>
                                alert('Database update failed. Please try again.');
                                window.location.href = 'e_pengesahan_majikan.php';
                              </script>";
                    }
                } else {
                    echo "<script>
                            alert('Failed to upload file. Please try again.');
                            window.location.href = 'e_pengesahan_majikan.php';
                          </script>";
                }
            } else {
                echo "<script>
                        alert('File is too large. Maximum size is 5MB.');
                        window.location.href = 'e_pengesahan_majikan.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error uploading file. Please try again.');
                    window.location.href = 'e_pengesahan_majikan.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Invalid file type. Only PDF files are allowed.');
                window.location.href = 'e_pengesahan_majikan.php';
              </script>";
    }
} else {
    echo "<script>
            alert('No file uploaded. Please select a file.');
            window.location.href = 'e_pengesahan_majikan.php';
          </script>";
}


?>