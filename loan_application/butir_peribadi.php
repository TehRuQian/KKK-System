<?php 
include ('pinjaman_sessions.php');
if (!session_id())
{
    session_start();
}
include ('headermain.php');
include 'dbconnect.php';

// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['uid'])) {
  // If not logged in, redirect to login page
  header('Location: login.php');
  exit(); 
}

// Check if the 'status' parameter is present in the URL
if (isset($_GET['status']) && $_GET['status'] == 'success') {
  echo '<script>alert("Anda telah berjaya disimpan.");</script>';
}

//Extract from database
$memberNo = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
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
  $sql = "SELECT m_name, m_ic, m_gender, m_religion, m_race, m_maritalStatus, m_pfNo,
                   m_monthlySalary, m_homeAddress, m_homePostcode, m_homeCity, m_homeState,
                   m_positionGrade, m_position, m_officeAddress, m_officePostcode,
                   m_officeCity, m_officeState, m_homeNumber, m_phoneNumber FROM tb_member WHERE m_memberNo = '$memberNo'";
  $result = mysqli_query($con, $sql);

  if ($row = mysqli_fetch_assoc($result)) {
    $memberName = htmlspecialchars($row['m_name']); 
    $memberIC = htmlspecialchars($row['m_ic']);
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

<form method = "post" action = "butir_peribadi_process.php">
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

      <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="float: right;">
          <div class="btn-group" role="group" aria-label="Page navigation">
            <a href="pinjaman.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'pinjaman.php' ? 'btn-primary' : 'btn-secondary'; ?>">1</a>
            <a href="butir_peribadi.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'butir_peribadi.php' ? 'btn-primary' : 'btn-secondary'; ?>">2</a>
            <a href="pengakuan_pemohon.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'pengakuan_pemohon.php' ? 'btn-primary' : 'btn-secondary'; ?>">3</a>
            <a href="penjamin.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'penjamin.php' ? 'btn-primary' : 'btn-secondary'; ?>">4</a>
            <a href="pengesahan_majikan.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'pengesahan_majikan.php' ? 'btn-primary' : 'btn-secondary'; ?>">5</a>
            <a href="akuan_kebenaran.php" class="btn <?php echo basename($_SERVER['PHP_SELF']) == 'akuan_kebenaran.php' ? 'btn-primary' : 'btn-secondary'; ?>">6</a>
          </div>
        </div>

        <br> <br>
  
    </div>   
  </fieldset>
</form>
</div>



</body>
</html>

<!--?php include 'footer.php';?-->