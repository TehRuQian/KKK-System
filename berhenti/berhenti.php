<?php 
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

if ($_SESSION['u_type']!= 2){
    header('Location: ../login.php');
    exit();
}

if (isset($_SESSION['u_id'])!=session_id()){
    header('Location: ../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$u_id = $_SESSION['funame'];

function callResult($con, $u_id){
    $sql="SELECT m_memberNo, m_name FROM tb_member WHERE m_memberNo='$u_id'";
    $result=mysqli_query($con, $sql);
    if (!$result){
        die("Query failed: " . mysqli_error($con));
    }
    return mysqli_fetch_assoc($result);
}

$row=callResult($con, $u_id);

function checkLoanStatus($con,$u_id){
    $sql ="SELECT COUNT(*) AS loan_count 
           FROM tb_loan 
           WHERE l_memberNo='$u_id' AND l_status =3";
    $result=mysqli_query($con,$sql);
    if (!$result){
        die("Query failed: " . mysqli_error($con));
    }
    $data=mysqli_fetch_assoc($result);
    return $data['loan_count'] === '0';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (checkLoanStatus($con,$u_id)){
        $td_alasan=$_POST['td_alasan'];
        $td_submitDate=date('Y-m-d H:i:s');
        $td_status=1;

        $sql="INSERT INTO tb_tarikdiri (td_memberNo,td_alasan,td_submitDate, td_status) 
              VALUES ('$u_id','$td_alasan','$td_submitDate','$td_status')";

        if (!mysqli_query($con,$sql)){
            die("Error: " . mysqli_error($con));
        } 
        else{
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Permohonan Berjaya!',
                        text: 'Permohonan Berhenti Menjadi Anggota anda telah dihantar.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '../member_main/member.php';
                    });
                  </script>";
        }
    } 
    else{
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Pinjaman Tidak Selesai!',
                    text: 'Anda tidak boleh memohon untuk berhenti selagi pinjaman belum selesai.',
                }).then(() => {
                    window.location.href = '../member_main/member.php';
                });
              </script>";
    }
}
?>

<head>
    <style>
        body{
            background-color: #f9f9f9;
        }
        .form-container{
            max-width: 700px;
            margin: 0 auto;
        }
        .required{
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Permohonan Berhenti Menjadi Anggota</h2>
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
                <label class="form-label mt-4">Sebab Berhenti Menjadi Anggota <span class="required">*</span></label>
                <textarea class="form-control" name="td_alasan" rows="5" required></textarea>
            </div>

            <div class="d-flex justify-content-center">
                <a href="../member_main/member.php">
                    <button type="button" class="btn btn-primary mt-4 me-3">Kembali</button>
                </a>
                <button onclick="return confirmation(event);" class="btn btn-primary mt-4">Hantar</button>
            </div>
        </fieldset>
    </form>

    <script>
    function confirmation(event){
        const fields=document.querySelectorAll("[required]");
        for (let field of fields){
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
            text: 'Permohonan Berhenti Menjadi Anggota anda akan dihantar!',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, hantar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('form').submit();
            }
            else {
                Swal.fire({
                    icon: 'info',
                    title: 'Dibatalkan!',
                    text: 'Permohonan anda tidak dihantar.',
                }).then(() => {
                    // Optional: Redirect or refresh the page
                    window.location.href = '../member_main/member.php';
                });
            }
        });
    }
    </script>

</body>
</html>

<?php include '../footer.php';?>
