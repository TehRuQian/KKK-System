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

$sql = "SELECT *
        FROM tb_member
        WHERE tb_member.m_memberNo = '$u_id'";

$result_member = mysqli_query($con, $sql);
$member = mysqli_fetch_assoc($result_member);

$sql = "
    SELECT * FROM tb_policies
    ORDER BY p_policyID DESC
    LIMIT 1;";

$result_policies = mysqli_query($con, $sql);
$policy = mysqli_fetch_assoc($result_policies);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $m_alAbrar = $_POST['m_alAbrar'];
  $m_simpananTetap = $_POST['m_simpananTetap'];

  // Check if the values meet the minimum requirements
  if ($m_simpananTetap < $policy['p_minSalaryDeductionForSaving']) {
      echo "<script>
              Swal.fire({
                  icon: 'error',
                  title: 'Potongan Gaji untuk Simpanan Tetap tidak mencukupi!',
                  text: 'Sila pastikan ia tidak kurang dari RM " . $policy['p_minSalaryDeductionForSaving'] . "',
              });
            </script>";
  } elseif ($m_alAbrar < $policy['p_minSalaryDeductionForMemberFund']) {
      echo "<script>
              Swal.fire({
                  icon: 'error',
                  title: 'Potongan Gaji untuk Tabung Anggota tidak mencukupi!',
                  text: 'Sila pastikan ia tidak kurang dari RM " . $policy['p_minSalaryDeductionForMemberFund'] . "',
              });
            </script>";
  } else {
      // Proceed with the database update
      $sql = "UPDATE tb_member SET 
              m_alAbrar = '$m_alAbrar',
              m_simpananTetap = '$m_simpananTetap'
              WHERE m_memberNo='$u_id'";

      if (!mysqli_query($con, $sql)) {
          echo "<script>
                  Swal.fire({
                      icon: 'error',
                      title: 'Kemas Kini Gagal!',
                      text: 'Sila cuba lagi.',
                  });
                </script>";
      } else {
          echo "<script>
                  Swal.fire({
                      icon: 'success',
                      title: 'Potongan Gaji Telah Dikemaskini!',
                      text: 'Kemaskini potongan gaji berjaya!',
                  }).then(() => {
                      window.location.href = 'profilmember.php';
                  });
                </script>";
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

<form class="form-container" method="post" action="">
  <fieldset>

    <div class="row">
      <div class="col-sm-10">
    </div>
    <div>
      <label class="form-label mt-4">Potongan Gaji untuk Simpanan Tetap <span class="required">*</span></label>
      <div class="input-group mb-3">
        <span class="input-group-text">RM</span>
        <input type="number" min="<?php echo htmlspecialchars($policy['p_minSalaryDeductionForSaving']); ?>" step="0.01" class="form-control" name="m_simpananTetap" value="<?= $member['m_simpananTetap']; ?>" required>
      </div>
    </div>

    <div>
      <fieldset>
        <label class="form-label mt-4">Potongan Gaji untuk Tabung Anggota <span class="required">*</span></label>
        <div class="input-group mb-3">
          <span class="input-group-text">RM</span>
          <input class="form-control" type="number" min="<?php echo htmlspecialchars($policy['p_minSalaryDeductionForMemberFund']); ?>" step="0.01" name="m_alAbrar" value="<?= $member['m_alAbrar']; ?>" required>
        </div>
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
                        document.querySelector('form').submit();
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Potongan gaji anda tidak dikemaskini!',
                    }).then(() => {
                      window.location.href = 'profilmember.php';
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
