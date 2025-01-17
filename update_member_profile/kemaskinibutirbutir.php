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
               tb_member.m_memberApplicationID,
               l_bankName,
               l_bankAccountNo
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

return $row;

}

$row = callResult($con, $u_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['nama'];
    $agama = $_POST['agama'];
    $race = $_POST['race'];
    $maritalstatus = $_POST['maritalstatus'];
    $emel = $_POST['emel'];
    $homeaddress = $_POST['homeaddress'];
    $homepostcode = $_POST['homepostcode'];
    $homecity = $_POST['homecity'];
    $homestate = $_POST['homestate'];
    $position = $_POST['position'];
    $grade = $_POST['grade'];
    $officeaddress = $_POST['officeaddress'];
    $officepostcode = $_POST['officepostcode'];
    $officecity = $_POST['officecity'];
    $officestate = $_POST['officestate'];
    $phonenum = $_POST['phonenum'];
    $homephonenum = $_POST['homephonenum'];
    $monthlysalary = $_POST['monthlysalary'];
    $bankname = $_POST['bankname'];
    $accountnum = $_POST['accountnum'];


if(!empty($_POST)) {

  $sql = "UPDATE tb_member
          Left JOIN tb_loan ON tb_member.m_memberNo =tb_loan.l_memberNo
  SET 
    m_name = '$name', 
    m_religion = '$agama',
    m_race = '$race',
    m_maritalStatus = '$maritalstatus',
    m_email = '$emel',
    m_homeAddress = '$homeaddress',
    m_homePostcode = '$homepostcode',
    m_homeCity = '$homecity',
    m_homeState = '$homestate',
    m_position = '$position',
    m_positionGrade = '$grade',
    m_officeAddress = '$officeaddress',
    m_officePostcode = '$officepostcode',
    m_officeCity = '$officecity',
    m_officeState = '$officestate',
    m_phoneNumber = '$phonenum',
    m_homeNumber = '$homephonenum',
    m_monthlySalary = '$monthlysalary',
    l_bankName = '$bankname',
    l_bankAccountNo = '$accountnum'
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
<!--
  <table class="fixed-table-container">
  <thead>
    <tr>
      <th scope="col" style="text-align: center; font-size:20px; padding: 20px;">Butir-butir Peribadi Pemohon</th>
    </tr>
  </thead>
</table>
-->
  
  <div class="my-3"></div>

    <h2 style="text-align: center;">Butir-butir Peribadi Pemohon</h2>

  <div class="my-3"></div>

<form class="form-container" method="post" action="">
  <fieldset>

    <div class="row">
      <div class="col-sm-10">
    </div>
    <div>
      <label class="form-label mt-4">Nama <span class="required">*</span></label>
      <input type="text" class="form-control" name="nama" value="<?= $row['m_name']; ?>" required>
    </div>
    <div>
      <fieldset>
        <label class="form-label mt-4">No. Kad Pengenalan</label>
        <input class="form-control" type="text" name="ic" placeholder="<?= $row['m_ic']; ?>"  disabled="">
      </fieldset>
    </div>
    <div>
      <fieldset>
        <label class="form-label mt-4">Tarikh Lahir</label>
        <?php
          $year=substr($row['m_ic'],0,2);
          $month=substr($row['m_ic'],2,2);
          $day=substr($row['m_ic'],4,2);
          if($year>=50){
            $fullyear="19$year";
          }
          else{
            $fullyear="20$year";
          }
          $tarikhlahir="$day-$month-$fullyear";
        ?>
        <input class="form-control" type="text" placeholder="<?=$tarikhlahir ?>"  disabled="">
        
      </fieldset>
    </div>
 <fieldset>
      <label class="form-label mt-4">Jantina</label>
      <div class="row justify-content-center">
        <div class="form-check col d-flex align-items-center">
      
      <div class="form-check col"><input class="form-check-input" type="radio" name="gender" placeholder="1" <?= $row['m_gender'] == 1 ? 'checked' : ''; ?> disabled="">
      <label class="form-check-label" for="jantinalelaki">Lelaki</label></div>

      <div class="form-check col"><input class="form-check-input" type="radio" name="gender" value="2" <?= $row['m_gender'] == 2 ? 'checked' : ''; ?> disabled>
      <label class="form-check-label" for="jantinaperempuan">Perempuan</label></div>

    </div>
    </div>
</fieldset>  

<fieldset>
    <label class="form-label mt-4">Agama <span class="required">*</span></label>
    <div class="row justify-content-center">
        <div class="form-check col d-flex align-items-center">
            <div class="form-check col"><input class="form-check-input" type="radio" name="agama" id="islam" value="1" <?= $row['m_religion'] == 1 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="agama_islam">Islam</label></div>
        </div>
        <div class="form-check col">
            <div class="form-check col"><input class="form-check-input" type="radio" name="agama" id="buddha" value="2" <?= $row['m_religion'] == 2 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="agama_buddha">Buddha</label></div>
        </div>
        <div class="form-check col">
            <div class="form-check col"><input class="form-check-input" type="radio" name="agama" id="hindu" value="3" <?= $row['m_religion'] == 3 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="agama_hindu">Hindu</label></div>
        </div>
        <div class="form-check col">
            <div class="form-check col"><input class="form-check-input" type="radio" name="agama" id="kristian" value="4" <?= $row['m_religion'] == 4 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="agama_kristian">Kristian</label></div>
        </div>
        <div class="form-check col">
            <div class="form-check col"><input class="form-check-input" type="radio" name="agama" id="lain" value="5" <?= $row['m_religion'] == 5 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="agama_lain">Lain-lain</label></div>
        </div>
    </div>
</fieldset>

<fieldset>
    <label class="form-label mt-4">Bangsa <span class="required">*</span></label>
    <div class="row justify-content-center">
        <div class="form-check col d-flex align-items-center">
            <div class="form-check col"><input class="form-check-input" type="radio" name="race" id="melayu" value="1" <?= $row['m_race'] == 1 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="agama_islam">Melayu</label></div>
        </div>
        <div class="form-check col">
            <div class="form-check col"><input class="form-check-input" type="radio" name="race" id="cina" value="2" <?= $row['m_race'] == 2 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="agama_buddha">Cina</label></div>
        </div>
        <div class="form-check col">
            <div class="form-check col"><input class="form-check-input" type="radio" name="race" id="india" value="3" <?= $row['m_race'] == 3 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="agama_hindu">India</label></div>
        </div>
        <div class="form-check col">
            <div class="form-check col"><input class="form-check-input" type="radio" name="race" id="other" value="4" <?= $row['m_race'] == 4 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="agama_lain">Lain-lain</label></div>
        </div>
    </div>
</fieldset>
<fieldset>
    <label class="form-label mt-4">Taraf Perkahwinan <span class="required">*</span></label>
    <div class="row justify-content-center">
        <div class="form-check col d-flex align-items-center">
            <div class="form-check col"><input class="form-check-input" type="radio" name="maritalstatus" id="bujang" value="1" <?= $row['m_maritalStatus'] == 1 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="bujang">Bujang</label></div>
        </div>
        <div class="form-check col">
            <div class="form-check col"><input class="form-check-input" type="radio" name="maritalstatus" id="kahwin" value="2" <?= $row['m_maritalStatus'] == 2 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="kahwin">Kahwin</label></div>
        </div>
        <div class="form-check col">
            <div class="form-check col"><input class="form-check-input" type="radio" name="maritalstatus" id="cerai" value="3" <?= $row['m_maritalStatus'] == 3 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="cerai">Cerai</label></div>
        </div>
        <div class="form-check col">
            <div ><input class="form-check-input" type="radio" name="maritalstatus" id="kametianpasangan" value="4" <?= $row['m_maritalStatus'] == 4 ? 'checked' : ''; ?>>
            <label class="form-check-label" for="kematianpasangan">Kematian Pasangan</label></div>
        </div>
    </div>
</fieldset>
    <div>
      <label class="form-label mt-4">E-mel <span class="required">*</span></label>
      <input type="text" class="form-control" name="emel" value="<?= $row['m_email']; ?>" required>
    </div>
<div>
      <label class="form-label mt-4">Alamat Rumah <span class="required">*</span></label>
      <input type="text" class="form-control" name="homeaddress" value="<?= $row['m_homeAddress']; ?>" required>
    </div>
<div class="row justify-content-center">
  <div class="col">
    <div>
      <label class="form-label mt-4">Poskod <span class="required">*</span></label>
      <input type="text" class="form-control" name="homepostcode" value="<?= $row['m_homePostcode']; ?>" required>
    </div>
  </div>
  <div class="col">
    <div>
      <label class="form-label mt-4">Bandar <span class="required">*</span></label>
      <input type="text" class="form-control" name="homecity" value="<?= $row['m_homeCity']; ?>" required>
    </div>
  </div>
</div>
    <div>
      <label class="form-label mt-4">Negeri <span class="required">*</span></label>
      <select class="form-select" name="homestate" placeholder="<?= $row['homeState']; ?>" required>
        <option value="1" <?= $row['m_homeState'] == 1 ? 'selected' : ''; ?>>Johor</option>
        <option value="2" <?= $row['m_homeState'] == 2 ? 'selected' : ''; ?>>Kedah</option>
        <option value="3" <?= $row['m_homeState'] == 3 ? 'selected' : ''; ?>>Kelantan</option>
        <option value="4" <?= $row['m_homeState'] == 4 ? 'selected' : ''; ?>>Melaka</option>
        <option value="5" <?= $row['m_homeState'] == 5 ? 'selected' : ''; ?>>Negeri Sembilan</option>
        <option value="6" <?= $row['m_homeState'] == 6 ? 'selected' : ''; ?>>Pahang</option>
        <option value="7" <?= $row['m_homeState'] == 7 ? 'selected' : ''; ?>>Penang</option>
        <option value="8" <?= $row['m_homeState'] == 8 ? 'selected' : ''; ?>>Sabah</option>
        <option value="9" <?= $row['m_homeState'] == 9 ? 'selected' : ''; ?>>Sarawak</option>
        <option value="10" <?= $row['m_homeState'] == 10 ? 'selected' : ''; ?>>Selangor</option>
        <option value="11" <?= $row['m_homeState'] == 11 ? 'selected' : ''; ?>>Terengganu</option>
        <option value="12" <?= $row['m_homeState'] == 12 ? 'selected' : ''; ?>>WP Kuala Lumpur</option>
        <option value="13" <?= $row['m_homeState'] == 13 ? 'selected' : ''; ?>>WP Labuan</option>
        <option value="14" <?= $row['m_homeState'] == 14 ? 'selected' : ''; ?>>WP Putrajaya</option>
        <option value="15" <?= $row['m_homeState'] == 15 ? 'selected' : ''; ?>>Perak</option>
        <option value="16" <?= $row['m_homeState'] == 16 ? 'selected' : ''; ?>>Perlis</option>
      </select>
    </div>
    <div class="row justify-content-center">
  <div class="col">
    <div>
      <label disabled="" class="form-label mt-4">No. Anggota</label>
      <input type="text" class="form-control" name="memberno" placeholder="<?= $row['m_memberNo']; ?>"  disabled="">
    </div>
  </div>
  <div class="col">
    <div>
      <label class="form-label mt-4">No. PF</label>
      <input type="text" class="form-control" name="pfno" placeholder="<?= $row['m_pfNo']; ?>"  disabled="">
    </div>
  </div>
</div>
    <div>
      <label class="form-label mt-4">Jawatan <span class="required">*</span></label>
      <input type="text" class="form-control"  name="position" value="<?= $row['m_position']; ?>" required>
    </div>
      <div>
      <label class="form-label mt-4">Gred <span class="required">*</span></label>
      <input type="text" class="form-control" name="grade" value="<?= $row['m_positionGrade']; ?>" required>
    </div>
    <div>
      <label class="form-label mt-4">Alamat Pejabat (Tempat Bertugas) <span class="required">*</span></label>
      <input type="text" class="form-control" name="officeaddress" value="<?= $row['m_officeAddress']; ?>" required>
    </div>
<div class="row justify-content-center">
  <div class="col">
    <div>
      <label class="form-label mt-4">Poskod <span class="required">*</span></label>
      <input type="text" class="form-control" name="officepostcode" value="<?= $row['m_officePostcode']; ?>" required>
    </div>
  </div>
  <div class="col">
    <div>
      <label class="form-label mt-4">Bandar <span class="required">*</span></label>
      <input type="text" class="form-control" name="officecity" value="<?= $row['m_officeCity']; ?>" required>
    </div>
  </div>
</div>
    <div>
      <label class="form-label mt-4">Negeri <span class="required">*</span></label>
      <select class="form-select" name="officestate" required>
        <option value="1" <?= $row['m_officeState'] == 1 ? 'selected' : ''; ?>>Johor</option>
        <option value="2" <?= $row['m_officeState'] == 2 ? 'selected' : ''; ?>>Kedah</option>
        <option value="3" <?= $row['m_officeState'] == 3 ? 'selected' : ''; ?>>Kelantan</option>
        <option value="4" <?= $row['m_officeState'] == 4 ? 'selected' : ''; ?>>Melaka</option>
        <option value="5" <?= $row['m_officeState'] == 5 ? 'selected' : ''; ?>>Negeri Sembilan</option>
        <option value="6" <?= $row['m_officeState'] == 6 ? 'selected' : ''; ?>>Pahang</option>
        <option value="7" <?= $row['m_officeState'] == 7 ? 'selected' : ''; ?>>Penang</option>
        <option value="8" <?= $row['m_homeState'] == 8 ? 'selected' : ''; ?>>Sabah</option>
        <option value="9" <?= $row['m_homeState'] == 9 ? 'selected' : ''; ?>>Sarawak</option>
        <option value="10" <?= $row['m_homeState'] == 10 ? 'selected' : ''; ?>>Selangor</option>
        <option value="11" <?= $row['m_homeState'] == 11 ? 'selected' : ''; ?>>Terengganu</option>
        <option value="12" <?= $row['m_homeState'] == 12 ? 'selected' : ''; ?>>WP Kuala Lumpur</option>
        <option value="13" <?= $row['m_homeState'] == 13 ? 'selected' : ''; ?>>WP Labuan</option>
        <option value="14" <?= $row['m_homeState'] == 14 ? 'selected' : ''; ?>>WP Putrajaya</option>
        <option value="15" <?= $row['m_homeState'] == 15 ? 'selected' : ''; ?>>Perak</option>
        <option value="16" <?= $row['m_homeState'] == 16 ? 'selected' : ''; ?>>Perlis</option>
      </select>
    </div>

    <div class="row justify-content-center">
  <div class="col">
    <div>
      <label class="form-label mt-4">No. Telefon Rumah</label>
      <input type="text" class="form-control" name="homephonenum" value="<?= $row['m_homeNumber']; ?>" required>
    </div>
  </div>
  <div class="col">
    <div>
      <label class="form-label mt-4">No. Telefon Bimbit <span class="required">*</span></label>
      <input type="text" class="form-control" name="phonenum" value="<?= $row['m_phoneNumber']; ?>" required>
    </div>
  </div>
</div>
  <div>
      <label class="form-label mt-4">Gaji Bulanan (RM) <span class="required">*</span></label>
      <input type="text" class="form-control" name=" monthlysalary" value="<?= $row['m_monthlySalary']; ?>" required>
    </div>
  <div>
      <label class="form-label mt-4">Nama Bank</label>
      <select class="form-select"  name="bankname">
        <option value="0" <?= $row['l_bankName'] == 0 ? 'selected' : ''; ?>>Tiada</option>
        <option value="1" <?= $row['l_bankName'] == 1 ? 'selected' : ''; ?>>Affin Bank</option>
        <option value="2" <?= $row['l_bankName'] == 2 ? 'selected' : ''; ?>>Agrobank</option>
        <option value="3" <?= $row['l_bankName'] == 3 ? 'selected' : ''; ?>>Al Rajhi Bank Malaysia</option>
        <option value="4" <?= $row['l_bankName'] == 4 ? 'selected' : ''; ?>>Alliance Bank</option>
        <option value="5" <?= $row['l_bankName'] == 5 ? 'selected' : ''; ?>>AmBank</option>
        <option value="6" <?= $row['l_bankName'] == 6 ? 'selected' : ''; ?>>Bank Islam</option>
        <option value="7" <?= $row['l_bankName'] == 7 ? 'selected' : ''; ?>>Bank Muamalat</option>
        <option value="8" <?= $row['l_bankName'] == 8 ? 'selected' : ''; ?>>Bank Rakyat</option>
        <option value="9" <?= $row['l_bankName'] == 9 ? 'selected' : ''; ?>>BSN</option>
        <option value="10" <?= $row['l_bankName'] == 10 ? 'selected' : ''; ?>>CIMB</option>
        <option value="11" <?= $row['l_bankName'] == 11 ? 'selected' : ''; ?>>Citybank Malaysia</option>
        <option value="12" <?= $row['l_bankName'] == 12 ? 'selected' : ''; ?>>Co-op Bank Pertama</option>
        <option value="13" <?= $row['l_bankName'] == 13 ? 'selected' : ''; ?>>Hong Leong Bank</option>
        <option value="14" <?= $row['l_bankName'] == 14 ? 'selected' : ''; ?>>HSBC Malaysia</option>
        <option value="15" <?= $row['l_bankName'] == 15 ? 'selected' : ''; ?>>Maybank</option>
        <option value="16" <?= $row['l_bankName'] == 16 ? 'selected' : ''; ?>>MBSB Bank</option>
        <option value="17" <?= $row['l_bankName'] == 17 ? 'selected' : ''; ?>>OCBC Malaysia</option>
        <option value="18" <?= $row['l_bankName'] == 18 ? 'selected' : ''; ?>>Public Bank</option>
        <option value="19" <?= $row['l_bankName'] == 19 ? 'selected' : ''; ?>>RHB</option>
        <option value="20" <?= $row['l_bankName'] == 20 ? 'selected' : ''; ?>>Standard Chartered Malaysia</option>
        <option value="21" <?= $row['l_bankName'] == 21 ? 'selected' : ''; ?>>UOB Malaysia</option>
      </select>
    </div>
    <div>
      <label class="form-label mt-4">No. Akaun Bank</label>
      <input type="text" class="form-control" name="accountnum" value="<?= $row['l_bankAccountNo'] ?? '-'; ?>">
    </div>
    <div class="d-flex justify-content-center">
      <button onclick="return confirmation(event);" class="btn btn-primary mt-4">Simpan</button>
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

</body>
</html>


<div class="my-5"></div><br>
  
</body>
</html>

<?php include '../footer.php';?>
