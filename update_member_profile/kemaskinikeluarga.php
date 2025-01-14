<?php 
include('../kkksession.php');
if(!session_id())
{
  session_start();
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
               tb_ugender.ug_desc AS gender,
               tb_urace.ur_desc AS race,
               tb_ureligion.ua_desc AS religion,
               tb_umaritalstatus.um_desc AS maritalstatus,
               tb_lbank.lb_desc AS bankname,
               tb_loan.l_bankAccountNo AS bankaccount,
               tb_homeState.st_desc AS homeState,
               tb_officeState.st_desc AS officeState,
               tb_member.m_memberApplicationID
        FROM tb_member
        LEFT JOIN tb_ugender ON tb_member.m_gender=tb_ugender.ug_gid
        LEFT JOIN tb_urace ON tb_member.m_race=tb_urace.ur_rid
        LEFT JOIN tb_ureligion ON tb_member.m_religion=tb_ureligion.ua_rid
        LEFT JOIN tb_umaritalstatus ON tb_member.m_maritalStatus=tb_umaritalstatus.um_mid
        LEFT JOIN tb_loan ON tb_member.m_memberNo = tb_loan.l_memberNo
        LEFT JOIN tb_lbank ON tb_loan.l_bankName=tb_lbank.lb_id
        LEFT JOIN tb_homeState ON tb_member.m_homeState=tb_homeState.st_id
        LEFT JOIN tb_officeState ON tb_member.m_officeState=tb_officeState.st_id
        WHERE tb_member.m_memberNo = '$u_id'";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: ".mysqli_error($con));
}

$row = mysqli_fetch_assoc($result);

$memberApplicationID = $row['m_memberApplicationID']; 

$heir_query = "SELECT tb_heir.* ,
                      tb_hrelation.hr_desc AS heirrelation
                      FROM tb_heir 
                      LEFT JOIN tb_hrelation ON tb_heir.h_relationWithMember = tb_hrelation.hr_rid
                      WHERE h_memberApplicationID = '$memberApplicationID' ORDER BY h_heirID";

$heir_result = mysqli_query($con, $heir_query);

if (!$heir_result) {
  die("Query failed: ".mysqli_error($con));
}

return $heir_result;
}

$heir_result = callResult($con, $u_id);

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['heir'])){
  foreach ($_POST['heir'] as $heir){
    $heirID = $heir['relation'] ? mysqli_real_escape_string($con,$heir['id']): '';
    $name = $heir['relation'] ? mysqli_real_escape_string($con,$heir['name']): '';
    $ic = $heir['relation'] ? mysqli_real_escape_string($con,$heir['ic']): '';
    $relation = $heir['relation'] ?  mysqli_real_escape_string($con,$heir['relation']): '';


if(!empty($_POST)) {
    $update_query="UPDATE tb_heir
                  SET 
                    h_name='$name',
                    h_ic='$ic',
                    h_relationWithMember='$relation'
                  WHERE h_heirID='$heirID'";

    if(!mysqli_query($con, $update_query)) {
      die("Update failed!" . mysqli_error($con));
    }
    else{
      header('Location:profilmember.php');
    }
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
<!--
  <table class="fixed-table-container">
  <thead>
    <tr>
      <th scope="col" style="text-align: center; font-size:20px; padding: 20px;">Maklumat Keluarga dan Pewaris</th>
    </tr>
  </thead>
</table>
-->
  
  <div class="my-3"></div>

    <p style="text-align: center; font-size:30px;"><b>Maklumat Keluarga dan Pewaris</b></p>

  <div class="my-3"></div>


<form class="form-container" method="post" action="">
  <fieldset>

  <br><a style="color: red;">* Perhatian: Sekurang-kurangnya SATU pewaris diperlukan! *</a><br>
  <a style="color: red;">* Perhatian: Untuk pewaris lebih, sila masukkan "-" untuk Nama dan No.KP / No. Srt Beranak, dan pilih "Tiada Hubungan" untuk Hubungan *</a>

<?php
$count = 1;
  while ($heir_row = mysqli_fetch_assoc($heir_result)) {
    if($count > 5) break; 
    $heirID=$heir_row['h_heirID'];
?>
      <div class="my-3"></div>
      <label class="form-label mt-4"><h5>Maklumat Pewaris <?= $count++; ?></h5></label>
      <input type="hidden" name="heir[<?= $heirID ?>][id]" value="<?= $heirID ?>">
      <div>
        <fieldset>
          <label class="form-label">Nama <span class="required">*</span></label>
            <input type="text" class="form-control" name="heir[<?= $heirID ?>][name]" value="<?= $heir_row['h_name']; ?>" required>
        </fieldset>
      </div>
      <div class="my-3">
        <fieldset>
          <label class="form-label">No. KP / No. Srt Beranak <span class="required">*</span></label>
            <input type="text" class="form-control" name="heir[<?= $heirID ?>][ic]" value="<?= $heir_row['h_ic']; ?>" required>
        </fieldset>
      </div>
      <div>
        <fieldset>
          <label class="form-label">Hubungan <span class="required">*</span></label>
          <select class="form-select" name="heir[<?= $heirID ?>][relation]" placeholder="<?= $heir_row['heirrelation']; ?>" required>
            <option value="7" <?= $heir_row['h_relationWithMember'] == 7 ? 'selected' : ''; ?>>Tiada Hubungan</option>
            <option value="1" <?= $heir_row['h_relationWithMember'] == 1 ? 'selected' : ''; ?>>Suami Isteri</option>
            <option value="2" <?= $heir_row['h_relationWithMember'] == 2 ? 'selected' : ''; ?>>Anak</option>
            <option value="3" <?= $heir_row['h_relationWithMember'] == 3 ? 'selected' : ''; ?>>Keturunan</option>
            <option value="4" <?= $heir_row['h_relationWithMember'] == 4 ? 'selected' : ''; ?>>Orang Tua</option>
            <option value="5" <?= $heir_row['h_relationWithMember'] == 5 ? 'selected' : ''; ?>>Saudara Kandung</option>
            <option value="6" <?= $heir_row['h_relationWithMember'] == 6 ? 'selected' : ''; ?>>Lain-lain</option>
          </select>

        </fieldset>
      </div>
<?php 
    }
?>

    <div class="d-flex justify-content-center">
      <button onclick="confirmation(event)" class="btn btn-primary mt-4">Simpan</button>
    </div>
  </fieldset>
</form>
        
<script>
  function confirmation(event){
    const fields = document.querySelectorAll("[required]");
    for (let field of fields) {
      if (field.value.trim() === "") {
        alert("Sila isi semua medan yang diperlukan.");
        event.preventDefault();
        return false;
      }
    }
    let msg="Adakah anda pasti ingin mengemaskini maklumat anda?";
    if(confirm(msg)==true){
      alert("Tahniah! Maklumat anda telah berjaya dikemaskini!");
    }
    
    else{
      event.preventDefault();
      alert("Maklumat anda tidak dikemaskini.");
      window.location.href="profilmember.php";
    }
  }
</script>

<div class="my-5"></div><br>
  
</body>
</html>

<?php include '../footer.php';?>
