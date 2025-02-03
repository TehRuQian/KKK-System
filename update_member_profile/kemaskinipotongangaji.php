<?php 
include('../kkksession.php');
if(!session_id())
{
  session_start();
}

if ($_SESSION['u_type'] != 2) {
  header('Location: ../login.php');
  exit();
}

if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$u_id = $_SESSION['funame'];

function callResult($con, $u_id) {

$sql = "SELECT tb_member.*,
               tb_member.m_memberApplicationID
        FROM tb_member
        WHERE tb_member.m_memberNo = '$u_id'";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: ".mysqli_error($con));
}

$row = mysqli_fetch_assoc($result);

return $row;

}

$row = callResult($con, $u_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $m_alAbrar = $_POST['m_alAbrar'];
    $m_simpananTetap = $_POST['m_simpananTetap'];


if(!empty($_POST)) {
  $sql = "UPDATE tb_member
  SET 
    m_alAbrar = '$m_alAbrar',
    m_simpananTetap = '$m_simpananTetap'
    WHERE m_memberNo='$u_id'";

    if(!mysqli_query($con, $sql)) {
      die("Update failed!" . mysqli_error($con));
    }
    else{
      header('Location:profilmember.php');
    }
}

}

?>


  <style>
    body {
      background-color: #f9f9f9;
    }

    .fixed-table-container{
      position: fixed;
      top: 60px;
      width:100%;
      background-color: White;
      z-index:1;
    }

    .form-container{
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
  
  <div class="my-3"></div>

    <h2 style="text-align: center;">Potongan Gaji</h2>

  <div class="my-3"></div>

<form class="form-container" method="post" action="">
  <fieldset>

    <div class="row">
      <div class="col-sm-10">
    </div>
    <div>
      <label class="form-label mt-4">Simapanan Tetap <span class="required">*</span></label>
      <input type="text" class="form-control" name="m_simpananTetap" value="<?= $row['m_simpananTetap']; ?>" required>
    </div>
    <div>
      <fieldset>
        <label class="form-label mt-4">Tabung Anggota <span class="required">*</span></label>
        <input class="form-control" type="text" name="m_alAbrar" value="<?= $row['m_alAbrar']; ?>" required>
      </fieldset>
    </div>
    
    <div class="d-flex justify-content-center">
      <button onclick="return confirmation(event);" class="btn btn-primary mt-4">Simpan</button>
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
                text: 'Potongan gaji anda akan dikemaskini!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, hantar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Potongan gaji anda telah berjaya dikemaskini!',
                        showConfirmButton: true
                    }).then(() => {
                        document.querySelector('form').submit();
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Potongan gaji anda tidak dikemaskini!',
                    }).then(() => {
                        window.location.href='profilmember.php';
                    });
                }
            });
        }
</script>


</body>
</html>


<div class="my-5"></div><br>
  
</body>
</html>

<?php include '../footer.php';?>
