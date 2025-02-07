<?php 
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

if ($_SESSION['u_type']!= 2) {
    header('Location: ../login.php');
    exit();
}

if (isset($_SESSION['u_id'])!=session_id()) {
    header('Location: ../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$u_id = $_SESSION['funame'];

function callResult($con, $u_id) {
    $sql = "SELECT m_memberNo, m_name 
            FROM tb_member 
            WHERE m_memberNo='$u_id'";
    $result=mysqli_query($con, $sql);
    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }
    return mysqli_fetch_assoc($result);
}

$row = callResult($con, $u_id);

date_default_timezone_set('Asia/Kuala_Lumpur');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fb_type=$_POST['fb_type'];
    $fb_content=$_POST['fb_content'];
    $fb_submitDate=date('Y-m-d H:i:s');
    $fb_status=7;

    $sql="INSERT INTO tb_feedback (fb_memberNo, fb_type, fb_content, fb_submitDate, fb_status) 
          VALUES ('$u_id','$fb_type','$fb_content','$fb_submitDate','$fb_status')";

    if (!mysqli_query($con, $sql)) {
        die("Error: " . mysqli_error($con));
    } 
    else{
      header('Location:track_feedback.php');
    }
}
?>


<head>
    <style>
        body {
            background-color: #f9f9f9;
        }
        .form-container {
            max-width: 700px;
            margin: 0 auto;
        }
        .required {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Hantar Maklum Balas</h2>
    <form class="form-container" method="post" action="">
        <fieldset>
            <div>
                <label class="form-label mt-4">No. Anggota</label>
                <input type="text" class="form-control" value="<?= $row['m_memberNo']; ?>" disabled>
            </div>

            <div>
                <label class="form-label mt-4">Nama</label>
                <input type="text" class="form-control" value="<?= $row['m_name']; ?>" disabled>
            </div>

            <div>
                <label class="form-label mt-4">Jenis Maklum Balas <span class="required">*</span></label>
                <select class="form-select" name="fb_type" required>
                    <option value="">-- Pilih Jenis Maklum Balas --</option>
                    <option value="1">Cadangan</option>
                    <option value="2">Masalah</option>
                </select>
            </div>

            <div>
                <label class="form-label mt-4">Maklumat Maklum Balas <span class="required">*</span></label>
                <textarea class="form-control" name="fb_content" rows="5" required></textarea>
            </div>

            <div class="d-flex justify-content-center">
                <a href="track_feedback.php">
                    <button type="button" class="btn btn-primary mt-4 me-3">Kembali</button>
                </a>
                <button onclick="return confirmation(event);" class="btn btn-primary mt-4">Hantar</button>
            </div>
        </fieldset>
    </form>

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
                text: 'Maklum balas akan dihantar!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, hantar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Maklum balas telah berjaya dihantar!',
                        showConfirmButton: true
                    }).then(() => {
                        document.querySelector('form').submit();
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Maklum balas tidak dihantar!',
                        text: 'Anda telah membatalkan maklum balas.',
                    }).then(() => {
                        window.location.href='track_feedback.php';
                    });
                }
            });
        }
    </script>
</body>
</html>

<?php include '../footer.php';?>