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
$memberNo = $_SESSION['funame'];

if (isset($_GET['status']) && $_GET['status'] == 'success') {
  echo '<script>alert("Maklumat anda telah berjaya disimpan!");</script>';
}

//Extract from database
$memberNo = isset($_SESSION['funame']) ? $_SESSION['funame'] : null;

// Member personal data
$memberName = '';
$memberIC = '';
$memberGender = null;
$memberRace = null;
$memberReligion = null;
$memberMaritalStatus = null;
$memberpfNo = '';
$memberMonthlySalary = '';
// Alamat Rumah
$memberHomeAdd = '';
$memberHomePostcode = '';
$memberHomeCity = '';
$memberHomeState = '';
// Jawatan
$memberPositionGrade = '';
$memberPosition = '';
// Alamat Pejabat
$memberOfficeAdd = '';
$memberOfficePostcode = '';
$memberOfficeCity = '';
$memberOfficeState = '';
// Telephone
$memberHomeTele = '';
$memberPhoneTele = '';


// Fetch data from database
if ($memberNo !== null) {
  $sql = "SELECT m_name, m_ic, m_email, m_gender, m_religion, m_race, m_maritalStatus, m_pfNo,
                   m_monthlySalary, m_homeAddress, m_homePostcode, m_homeCity, m_homeState,
                   m_positionGrade, m_position, m_officeAddress, m_officePostcode,
                   m_officeCity, m_officeState, m_homeNumber, m_phoneNumber FROM tb_member WHERE m_memberNo = '$memberNo'";
  $result = mysqli_query($con, $sql);

  if ($row = mysqli_fetch_assoc($result)) {
    $memberName = htmlspecialchars($row['m_name']); 
    $memberIC = htmlspecialchars($row['m_ic']);
    $memberEmail = htmlspecialchars($row['m_email']);
    $memberGender = $row['m_gender'];
    $memberReligion = ($row['m_religion']); 
    $memberRace = ($row['m_race']); 
    $memberMaritalStatus = ($row['m_maritalStatus']);
    $memberpfNo = htmlspecialchars($row['m_pfNo']);
    $memberHomeAdd = htmlspecialchars($row['m_homeAddress']);
    $memberHomePostcode = htmlspecialchars($row['m_homePostcode']);
    $memberHomeCity = htmlspecialchars($row['m_homeCity']);
    $memberHomeState = htmlspecialchars($row['m_homeState']);
    $memberPositionGrade = htmlspecialchars($row['m_positionGrade']);
    $memberPosition = htmlspecialchars($row['m_position']);
    $memberOfficeAdd = htmlspecialchars($row['m_officeAddress']);
    $memberOfficePostcode = htmlspecialchars($row['m_officePostcode']);
    $memberOfficeCity = htmlspecialchars($row['m_officeCity']);
    $memberOfficeState = htmlspecialchars($row['m_officeState']);
    $memberHomeTele = htmlspecialchars($row['m_homeNumber']);
    $memberPhoneTele = htmlspecialchars($row['m_phoneNumber']);
    $memberMonthlySalary = htmlspecialchars($row['m_monthlySalary']);

  }
}

?>

<head>
    <style>
        .row-spacing {
            margin-bottom: 4rem;
        }

        a:hover {}

        a:active,
        a.active {
            color: black !important;
        }

        a {
            text-decoration: none;
            margin-bottom: 0.5rem;
        }

        .container-fluid {
            padding-left: 0;   
            padding-right: 0;
        }

        .is-invalid {
            border: 2px solid red;
        }
        
        .sidebar {
          position: fixed;    
            top: 60px;           
            left: 0;        
            width: 16.666667%; 
            min-height: 100vh;  
            background-color: #9ccfff;
            padding-top: 20px;
            z-index: 1000;   
        }
        
         
        .sidebar .row {
            width: 100%;
            padding: 10px;
        }

        .sidebar a {
            display: block;
            width: 100%;
            text-decoration: none;
            margin-bottom: 0.5rem;
        }

        .sidebar hr {
            width: 100%;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .row {
            margin: 0;    
        }

        .col-2, .col-10 {
            padding: 0;    
        }

        .main-content {
            margin-left: 16.666667%; 
        }

        footer {
        width: calc(100% - 16.666667%) !important; 
        margin-left: 16.666667% !important; 
        padding: 0 20px !important;
    }

        
        footer .container,
        footer .container-fluid {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
          
    </style>
</head>

<div class="container-fluid">
  <div class="row">
    <div class="col-2 sidebar">
        <div class="row">
          <a href="a_pinjaman.php" class="text-center"><br>Butir-Butir Pembiayaan</a>
          <hr>
        </div>
        <div class="row">
          <a href="b_butir_peribadi.php" class="text-center active">Butir-Butir Peribadi Pemohon</a>
          <hr>
        </div>
        <div class="row">
          <a href="c_pengakuan_pemohon.php" class="text-center">Pengakuan Pemohon<br></a>
          <hr>
        </div>
        <div class="row">
          <a href="d_penjamin.php" class="text-center">Butir-Butir Penjamin<br></a>
          <hr>
        </div>
        <div class="row">
          <a href="e_pengesahan_majikan.php" class="text-center">Pengesahan Majikan<br></a>
          <hr>
        </div>
        <div class="row">
          <a href="f_akuan_kebenaran.php" class="text-center">Akuan Kebenaran<br></a>
          <hr>
        </div>
  </div>
</div>


<div class="col-10 main-content">

<form method = "post" action = "b_butir_peribadi_process.php">
  <fieldset>
    <!--Personal details-->
    <div class="container">
      <br>
      <div class="jumbotron">
          <h2>Butir-Butir Peribadi Pemohon</h2>
        <div>
          <label class="form-label mt-4">Nama</label>
            <div class="input-group mt-2">
              <input type="text" name = "nama" class="form-control" id="nama" aria-label="nama" placeholder="Ali bin Abu" value="<?php echo $memberName; ?>" required>
            </div>  
        </div>
      </div>

      <div>
          <label class="form-label mt-4">No. Kad Pengenalan</label>
            <div class="input-group mt-2">
              <input type="text" name = "noKad" class="form-control" id="noKad" aria-label="noKad" placeholder="000000-00-0000" value="<?php echo $memberIC; ?>" readonly required style="background-color: #f0f0f0; color: #000;">
            </div>  
      </div>

      <div>
          <label class="form-label mt-4">Email</label>
            <div class="input-group mt-2">
              <input type="text" name = "email" class="form-control" id="email" aria-label="email" placeholder="abc@gmail.com" value="<?php echo $memberEmail; ?>" required>
            </div>  
      </div>
      
      <label class="form-label mt-4">Jantina</label>
      <br>
      <?php
        $sql="SELECT * FROM tb_ugender";
        $result=mysqli_query($con,$sql);

        while($row=mysqli_fetch_array($result))
        {
          $isChecked = ($row['ug_gid'] == $memberGender) ? 'checked' : '';
          echo '<input type="radio" id="ugender' . $row['ug_gid'] . '" name="ugender" value="' . $row['ug_gid'] . '" '. $isChecked . '>';
          echo '<label for="ugender' . $row['ug_gid'] . '">' . $row['ug_desc'] . '</label><br>';
        }
      ?>
      
      <label class="form-label mt-4">Agama</label>
      <br>
      <?php
        $sql="SELECT * FROM tb_ureligion";
        $result=mysqli_query($con,$sql);

        while ($row = mysqli_fetch_array($result)) {
  
              $isChecked = ($row['ua_rid'] == $memberReligion) ? 'checked' : '';
              echo '<input type="radio" id="ureligion' . $row['ua_rid'] . '" name="ureligion" value="' . $row['ua_rid'] . '" '. $isChecked. '>';
              echo '<label for="ureligion' . $row['ua_rid'] . '">' . $row['ua_desc'] . '</label><br>';
        }
      ?>

      <label class="form-label mt-4">Bangsa</label>
      <br>
      <?php
        $sql="SELECT * FROM tb_urace";
        $result=mysqli_query($con,$sql);

        while ($row = mysqli_fetch_array($result)) {

            $isChecked = ($row['ur_rid'] == $memberRace) ? 'checked' : '';
              echo '<input type="radio" id="urace' . $row['ur_rid'] . '" name="urace" value="' . $row['ur_rid'] . '" '. $isChecked. '>';
              echo '<label for="urace' . $row['ur_rid'] . '">' . $row['ur_desc'] . '</label><br>';
        }
      ?>

      <label class="form-label mt-4">Taraf Perkahwinan</label>
      <br>
      <?php
        $sql="SELECT * FROM tb_umaritalstatus";
        $result=mysqli_query($con,$sql);
        if ($result){
          while ($row = mysqli_fetch_array($result)) {

            $isChecked = ($row['um_mid'] == $memberMaritalStatus) ? 'checked' : '';
            echo '<input type="radio" id="umaritalStatus' . $row['um_mid'] . '" name="umaritalStatus" value="' . $row['um_mid'] . '" '. $isChecked. '>';
            echo '<label for="umaritalStatus' . $row['um_mid'] . '">' . $row['um_desc'] . '</label><br>';
            
          }
      }
    ?>

      <div>
        <label class="form-label mt-4">No. Anggota</label>
          <div class="input-group mt-2">
            <input type="text" name = "anggotaNo" class="form-control" id="anggotaNo" aria-label="anggotaNo" placeholder="1" value="<?php echo $memberNo;?>" readonly required style="background-color: #f0f0f0; color: #000;">
          </div>  
      </div>

      <div>
        <label class="form-label mt-4">No. PF</label>
          <div class="input-group mt-2">
            <input type="text" name = "pfNo" class="form-control" id="pfNo" aria-label="pfNo" placeholder="1" value="<?php echo $memberpfNo;?>" readonly required style="background-color: #f0f0f0; color: #000;">
          </div>  
      </div>

      <div>
        <label class="form-label mt-4">Alamat Rumah</label>
          <div class="input-group mt-2">
            <input type="text" name = "alamatRumah" class="form-control" id="alamatRumah" aria-label="alamatRumah" placeholder="No 12, Jalan UTM, Taman UTM, 12345, Johor." value="<?php echo $memberHomeAdd;?>" required>
          </div>  
      </div>

      <div>
        <label class="form-label mt-4">Poskod</label>
          <div class="input-group mt-2">
            <input type="text" name = "postcodeRumah" class="form-control" id="postcodeRumah" aria-label="postcodeRumah" placeholder="12345" value="<?php echo $memberHomePostcode;?>" required>
          </div>  
      </div>

      <div>
        <label class="form-label mt-4">Bandar</label>
          <div class="input-group mt-2">
            <input type="text" name = "cityRumah" class="form-control" id="cityRumah" aria-label="cityRumah" placeholder="Skudai" value="<?php echo $memberHomeCity;?>" required>
          </div>  
      </div>

      <div>
        <label class="form-label mt-4">Negeri</label>
        <select name="stateRumah" class="form-select" id="stateRumah" required>
          <?php
            $sql="SELECT * FROM tb_homestate";
            $result=mysqli_query($con,$sql);

            while($row=mysqli_fetch_array($result))
            {
              $isSelected = ($row['st_id'] == $memberHomeState) ? 'selected' : '';
              echo '<option value="' . $row['st_id'] . '" ' . $isSelected . '>' . htmlspecialchars($row['st_desc']) . '</option>';
            }
          ?>
        </select>
      </div>

      <div>
        <label class="form-label mt-4">Jawatan Gred</label>
          <div class="input-group mt-2">
            <input type="text" name = "jawatanGred" class="form-control" id="jawatanGred" aria-label="jawatanGred" placeholder="G41" value="<?php echo $memberPositionGrade;?>" required>
          </div>  
      </div>

      <div>
        <label class="form-label mt-4">Jawatan</label>
          <div class="input-group mt-2">
            <input type="text" name = "jawatan" class="form-control" id="jawatan" aria-label="jawatan" placeholder="Pengurus" value="<?php echo $memberPosition;?>" required>
          </div>  
      </div>

      <div>
        <label class="form-label mt-4">Alamat Pejabat (Tempat Bertugas)</label>
          <div class="input-group mt-2">
            <input type="text" name = "alamatPejabat" class="form-control" id="alamatPejabat" aria-label="alamatPejabat" placeholder="No 12, Jalan UTM, Taman UTM, 12345, Johor." value="<?php echo $memberOfficeAdd;?>" required>
          </div>  
      </div>

      <div>
        <label class="form-label mt-4">Poskod</label>
          <div class="input-group mt-2">
            <input type="text" name = "postcodePejabat" class="form-control" id="postcodePejabat" aria-label="postcodePejabat" placeholder="12345" value="<?php echo $memberOfficePostcode;?>" required>
          </div>  
      </div>

      <div>
        <label class="form-label mt-4">Bandar</label>
          <div class="input-group mt-2">
            <input type="text" name = "cityPejabat" class="form-control" id="cityPejabat" aria-label="cityPejabat" placeholder="Skudai" value="<?php echo $memberOfficeCity;?>" required>
          </div>  
      </div>

      <div>
        <label class="form-label mt-4">Negeri</label>
        <select name="statePejabat" class="form-select" id="statePejabat" required>
          <?php
            $sql="SELECT * FROM tb_officestate";
            $result=mysqli_query($con,$sql);

            while($row=mysqli_fetch_array($result))
            {
              $isSelected = ($row['st_id'] == $memberOfficeState) ? 'selected' : '';
              echo '<option value="' . $row['st_id'] . '" ' . $isSelected . '>' . htmlspecialchars($row['st_desc']) . '</option>';
            }
          ?>
        </select>
      </div>
      <div>
      <label class="form-label mt-4">No. Telefon Rumah</label>
        <div class="input-group mt-2">
          <input type="text" name = "noTeleRum" class="form-control" id="noTeleRum" aria-label="noTeleRum" placeholder="061234567" value="<?php echo $memberHomeTele;?>" >
        </div>  
      </div>
      
      <div>
      <label class="form-label mt-4">No. Telefon Bimbit</label>
        <div class="input-group mt-2">
          <input type="text" name = "noTeleBim" class="form-control" id="noTeleBim" aria-label="noTeleBim" placeholder="0161234567" value="<?php echo $memberPhoneTele;?>" required>
        </div>  
      </div>

      <div>
      <label class="form-label mt-4">Gaji Bulanan</label>
        <div class="input-group mt-2">
          <span class="input-group-text">RM</span>
          <input type="text" name = "gajiBulanan" class="form-control" id="gajiBulanan" aria-label="gajiBulanan" placeholder="1000" value="<?php echo $memberMonthlySalary;?>" required>
        </div>  
      </div>

        

    </hr>
      <hr class="my-4">
        <p class="lead">
        <button type="submit" class="btn btn-primary">Simpan</button>
        </p>
      </hr>

    </div>   
  </fieldset>
</form>
</div>



</body>
</html>

<?php include '../footer.php'; ?>