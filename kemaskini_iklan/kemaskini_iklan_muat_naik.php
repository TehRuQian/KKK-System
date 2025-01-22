<?php 
  include('../kkksession.php');
  if (!session_id()) {
      session_start();
  }
  if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
  }
  
  include '../header_admin.php';
  include '../db_connect.php';
  $admin_id = $_SESSION['u_id'];

$target_dir = "../img/iklan/";
$target_file = $target_dir . basename($_FILES["banner"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$bannerName = $_POST['bannerName'];

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["banner"]["tmp_name"]);
  if($check !== false) {
    // echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    // echo "File is not an image.";
    $mssg = "Fail yang dimuat naik bukan gambar.";
    $uploadOk = 0;
  }
}

// Check if file already exists
if (file_exists($target_file)) {
//   echo "Sorry, file already exists.";
  $mssg = "Minta maaf, fail ini telah wujud.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["banner"]["size"] > 500000000) {
//   echo "Sorry, your file is too large.";
  $mssg = "Minta maaf, fail ini terlalu besar.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
//   echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $mssg = "Minta maaf, hanya fail JPG, JPEG, PNG & GIF dibenarkan.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
//   echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
  $alertType = 'error';
} else {
  if (move_uploaded_file($_FILES["banner"]["tmp_name"], $target_file)) {
    // echo "The file ". htmlspecialchars( basename( $_FILES["banner"]["name"])). " has been uploaded.";
    $bannerPath = htmlspecialchars(basename($_FILES["banner"]["name"])); 
    $b_status = 1;
    $sql = "INSERT INTO tb_banner (b_banner, b_name, b_status, b_adminID)
            VALUES ('$bannerPath', '$bannerName', '$b_status', '$admin_id')";

    if (mysqli_query($con, $sql)) {
        $mssg = htmlspecialchars( basename( $_FILES["banner"]["name"])). " telah berjaya dimuat naik.";
        $alertType = 'success';
    } else {
        // Database insert failed
        $mssg = "Error: " . mysqli_error($con);
        $alertType = 'error';
    }
  } else {
    // echo "Sorry, there was an error uploading your file.";
    $mssg = "Maaf, terdapat ralat semasa memuat naik fail anda.";
    $alertType = 'error';
  }
}

// echo "
//     <script>
//         alert ('$mssg');
//         window.location.href = 'kemaskini_iklan.php';
//     </script>";

echo "
  <script>
    Swal.fire({
      text: '$mssg',
      title: 'Iklan Baru',
      icon: '$alertType',
      confirmButtonText: 'OK',
      willClose: () => {
        window.location.href = 'kemaskini_iklan.php';
      }
    });
  </script>";
?>