<?php 
include('../kkksession.php');
if(!session_id())
{
  session_start();
}

if ($_SESSION['u_type']!=1) {
  header('Location: ../login.php');
  exit();
}

if(isset($_SESSION['u_id'])!=session_id())
{
  header('Location:../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$u_id=$_SESSION['funame'];

$feedbackID=$_GET['feedbackID'] ?? '';

if (empty($feedbackID)){
    die("No feedback ID provided.");
}

$sql = "SELECT tb_feedback.*, 
               tb_status.s_desc AS status,
               tb_ftype.fb_desc AS type,
               tb_member.m_name AS nama 
        FROM tb_feedback
        LEFT JOIN tb_status ON tb_feedback.fb_status=tb_status.s_sid
        LEFT JOIN tb_ftype ON tb_feedback.fb_type=tb_ftype.fb_id
        LEFT JOIN tb_member ON tb_feedback.fb_memberNo=tb_member.m_memberNo
        WHERE tb_feedback.fb_feedbackID='$feedbackID'";

$result = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($result); 

if (!$result){
    die("Query failed: " . mysqli_error($con));
}

date_default_timezone_set('Asia/Kuala_Lumpur');

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $new_status=$_POST['fb_status'];
    $new_comment=$_POST['fb_comment'];
    $edit_date=date('Y-m-d H:i:s');

    $update_sql = "UPDATE tb_feedback 
                   SET fb_status='$new_status', fb_comment='$new_comment', fb_editStatusDate='$edit_date', fb_adminID='$u_id' 
                   WHERE fb_feedbackID='$feedbackID'";

    if (!mysqli_query($con,$update_sql)){
        die("Error: " . mysqli_error($con));
    } 
    else{
      header('Location:view_feedback_admin.php');
    }
}
?>

<style>
    body {
      background-color: #f9f9f9;
    }

    .fixed-table-container {
      position: fixed;
      top: 60px;
      width: 100%;
      background-color: white;
      z-index: 1;
    }

    .required {
      color: red;
      font-weight: bold;
        }

</style>
</head>
<body>

<h2 style="text-align: center;">Butiran Maklum Balas</h2>

<div class="card mb-3 col-10 my-5 mx-auto">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Butiran Maklum Balas
    </div>
    <div class="card-body">
        <table class="table table-hover">
            <tbody>
                <tr>
                    <td scope="row">ID</td>
                    <td><?= $row['fb_feedbackID']; ?></td>
                </tr>
                <tr>
                    <td scope="row">No. Anggota</td>
                    <td><?= $row['fb_memberNo']; ?></td>
                </tr>
                <tr>
                    <td scope="row">Nama Anggota</td>
                    <td><?= $row['nama']; ?></td>
                </tr>
                <tr>
                    <td scope="row">Jenis</td>
                    <td><?= $row['type']; ?></td>
                </tr>
                <tr>
                    <td scope="row">Kandungan</td>
                    <td><?= $row['fb_content']; ?></td>
                </tr>
                <tr>
                    <td scope="row">Tarikh Hantar</td>
                    <td><?= $row['fb_submitDate']; ?></td>
                </tr>
                <tr>
                    <td scope="row">Status</td>
                    <td><?= $row['status']; ?></td>
                </tr>
                <tr>
                    <td scope="row">No. Pentadbir</td>
                    <td><?= !empty($row['fb_adminID']) ? $row['fb_adminID'] : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td scope="row">Komen Pentadbir</td>
                    <td><?= !empty($row['fb_comment']) ? $row['fb_comment'] : 'N/A'; ?></td>
                </tr>
                <tr>
                    <td scope="row">Tarikh Disemak</td>
                    <td><?= !empty($row['fb_editStatusDate']) ? $row['fb_editStatusDate'] : 'N/A'; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Form for updating status and comment -->
<div class="card mb-3 col-10 my-5 mx-auto">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Ubah Status dan Komen
    </div>
    <div class="card-body">
        <form method="post">
            <div class="form-group">
                <label for="fb_status">Ubah Status 
                  <span class="required">*</span>
                </label>
                <select class="form-control" name="fb_status" id="fb_status" required>
                  <option value="7" <?= $row['fb_status'] == '7' ? 'selected' : ''; ?>>Diterima</option>
                    <option value="1" <?= $row['fb_status'] == '1' ? 'selected' : ''; ?>>Sedang Diproses</option>
                    <option value="8" <?= $row['fb_status'] == '8' ? 'selected' : ''; ?>>Selesai</option>
                </select>
            </div>
            <br>
            <div class="form-group">
                <label for="fb_comment">Komen Pentadbir</label>
                <textarea class="form-control" name="fb_comment" id="fb_comment" rows="4" placeholder="Masukkan komen anda"><?= $row['fb_comment']; ?></textarea>
            </div>
            <br>
            <div class="d-flex justify-content-center">
              <button type="button" class="btn btn-primary me-2" onclick="window.location.href='view_feedback_admin.php'">Kembali</button>
              <button onclick="return confirmation(event);" class="btn btn-primary">Kemaskini</button>
            </div>
        </form>
    </div>
</div>
</div>
<br><br><br>


<script>
        function confirmation(event) {
            const fields = document.querySelectorAll("[required]");
            for (let field of fields) {
                if (field.value.trim() === "") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Medan diperlukan!',
                        text: 'Sila isi semua medan yang diperlukan.',
                    });
                    event.preventDefault();
                    return false;
                }
            }

            event.preventDefault();

            Swal.fire({
                title: 'Adakah anda pasti?',
                text: 'Status maklum balas akan diubah!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, hantar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status maklum balas telah berjaya diubah!',
                        showConfirmButton: true
                    }).then(() => {
                        document.querySelector('form').submit();
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Status maklum balas tidak diubah!',
                        text: 'Anda telah membatalkan pengubahan status maklum balas.',
                    }).then(() => {
                        window.location.href='view_feedback_admin.php';
                    });
                }
            });
        }
    </script>

</body>
</html>
