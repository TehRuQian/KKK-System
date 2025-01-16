<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';

// Check if the user is logged in by verifying the session variable
// if (!isset($_SESSION['uid'])) {
//   // If not logged in, redirect to login page
//   header('Location: login.php');
//   exit(); 
// }

// Check if the 'status' parameter is present in the URL
if (isset($_GET['status']) && $_GET['status'] == 'success') {
  echo '<script>alert("Anda telah berjaya disimpan.");</script>';
}

// Loan
if (!isset($_SESSION['loanApplicationID'])) {
    die('Error: Loan application ID is missing.');
}

$loanApplicationID = $_SESSION['loanApplicationID']; 

// Guarantor
if (!isset($_SESSION['guarantorID2'])) {
  die('Error: Guarantor ID2 is missing.');
}

$guarantorID2 = $_SESSION['guarantorID2']; 

// Loan details
$selected_jenis_pembiayaan_ID = '';
$selected_jenis_pembiayaan = '';
$selected_amaunDipohon = '';
$selected_tempohPembiayaan = '';
$selected_ansuranBulanan = '';
$selected_namaBank_ID = '';
$selected_namaBank = '';
$selected_bankAcc = '';
$selected_gajiKasar = '';
$selected_gajiBersih = '';
$selected_signature = '';

// Fetch data
$sql = "SELECT l_loanType, l_appliedLoan, l_loanPeriod, l_monthlyInstalment, l_bankAccountNo, l_bankName, l_monthlyGrossSalary, l_monthlyNetSalary, l_signature FROM tb_loan WHERE l_loanApplicationID = $loanApplicationID";
$result = mysqli_query($con, $sql);

if ($row = mysqli_fetch_assoc($result)) {
  $selected_jenis_pembiayaan_ID = htmlspecialchars($row['l_loanType']);  
  $selected_amaunDipohon = htmlspecialchars($row['l_appliedLoan']);  
  $selected_tempohPembiayaan = htmlspecialchars($row['l_loanPeriod']);  
  $selected_ansuranBulanan = htmlspecialchars($row['l_monthlyInstalment']);  
  $selected_namaBank_ID = htmlspecialchars($row['l_bankName']);  
  $selected_bankAcc = htmlspecialchars($row['l_bankAccountNo']);  
  $selected_gajiKasar = htmlspecialchars($row['l_monthlyGrossSalary']); 
  $selected_gajiBersih = htmlspecialchars($row['l_monthlyNetSalary']);
  $selected_signature = htmlspecialchars($row['l_signature']);  
}

// Fetch the description if an ID is selected
// loan id
if ($selected_jenis_pembiayaan_ID) {
    $sql = "SELECT lt_desc FROM tb_ltype WHERE lt_lid = $selected_jenis_pembiayaan_ID";
    $result = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $selected_jenis_pembiayaan = $row['lt_desc'];
    }
}

if ($selected_namaBank_ID) {
    $sql = "SELECT lb_desc FROM tb_lbank WHERE lb_id = $selected_namaBank_ID";
    $result = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $selected_namaBank = $row['lb_desc'];
    }
}

// personal id
//Extract from database
$memberNo = isset($_SESSION['uid']) ? $_SESSION['uid'] : null;
// Member personal data
$memberName = '';
$memberIC = '';
$memberGender_ID = '';
$memberGender = null;
$memberRace_ID = '';
$memberRace = null;
$memberReligion_ID = '';
$memberReligion = null;
$memberMaritalStatus_ID = '';
$memberMaritalStatus = null;
$memberpfNo = '';
$memberMonthlySalary = '';
// Alamat Rumah
$memberHomeAdd = '';
$memberHomePostcode = '';
$memberHomeCity = '';
$memberHomeState_ID = '';
$memberHomeState = '';
// Jawatan
$memberPositionGrade = '';
$memberPosition = '';
// Alamat Pejabat
$memberOfficeAdd = '';
$memberOfficePostcode = '';
$memberOfficeCity = '';
$memberOfficeState_ID = '';
$memberOfficeState = '';
// Telephone
$memberHomeTele = '';
$memberPhoneTele = '';


// Fetch data
if ($memberNo !== null) {
  $sql = "SELECT m_name, m_ic, m_gender, m_religion, m_race, m_maritalStatus, m_pfNo,
                   m_monthlySalary, m_homeAddress, m_homePostcode, m_homeCity, m_homeState,
                   m_positionGrade, m_position, m_officeAddress, m_officePostcode,
                   m_officeCity, m_officeState, m_homeNumber, m_phoneNumber FROM tb_member WHERE m_memberNo = '$memberNo'";
  $result = mysqli_query($con, $sql);

  if ($row = mysqli_fetch_assoc($result)) {
    $memberName = htmlspecialchars($row['m_name']); 
    $memberIC = htmlspecialchars($row['m_ic']);
    $memberGender_ID = $row['m_gender'];
    $memberReligion_ID = ($row['m_religion']); 
    $memberRace_ID = ($row['m_race']); 
    $memberMaritalStatus_ID = ($row['m_maritalStatus']);
    $memberpfNo = htmlspecialchars($row['m_pfNo']);
    $memberHomeAdd = htmlspecialchars($row['m_homeAddress']);
    $memberHomePostcode = htmlspecialchars($row['m_homePostcode']);
    $memberHomeCity = htmlspecialchars($row['m_homeCity']);
    $memberHomeState_ID = ($row['m_homeState']);
    $memberPositionGrade = htmlspecialchars($row['m_positionGrade']);
    $memberPosition = htmlspecialchars($row['m_position']);
    $memberOfficeAdd = htmlspecialchars($row['m_officeAddress']);
    $memberOfficePostcode = htmlspecialchars($row['m_officePostcode']);
    $memberOfficeCity = htmlspecialchars($row['m_officeCity']);
    $memberOfficeState_ID = ($row['m_officeState']);
    $memberHomeTele = htmlspecialchars($row['m_homeNumber']);
    $memberPhoneTele = htmlspecialchars($row['m_phoneNumber']);
    $memberMonthlySalary = htmlspecialchars($row['m_monthlySalary']);

  }
}

if ($memberGender_ID) {
    $sql = "SELECT ug_desc FROM tb_ugender WHERE ug_gid = $memberGender_ID";
    $result = mysqli_query($con, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $memberGender = $row['ug_desc'];
    }
}

if ($memberReligion_ID) {
  $sql = "SELECT ua_desc FROM tb_ureligion WHERE ua_rid = $memberReligion_ID";
  $result = mysqli_query($con, $sql);
  if ($row = mysqli_fetch_assoc($result)) {
      $memberReligion = $row['ua_desc'];
  }
}

if ($memberRace_ID) {
  $sql = "SELECT ur_desc FROM tb_urace WHERE ur_rid = $memberRace_ID";
  $result = mysqli_query($con, $sql);
  if ($row = mysqli_fetch_assoc($result)) {
      $memberRace = $row['ur_desc'];
  }
}

if ($memberMaritalStatus_ID) {
  $sql = "SELECT um_desc FROM tb_umaritalstatus WHERE um_mid = $memberMaritalStatus_ID";
  $result = mysqli_query($con, $sql);
  if ($row = mysqli_fetch_assoc($result)) {
      $memberMaritalStatus = $row['um_desc'];
  }
}

if ($memberHomeState_ID) {
  $sql = "SELECT st_desc FROM tb_homestate WHERE st_id = $memberHomeState_ID";
  $result = mysqli_query($con, $sql);
  if ($row = mysqli_fetch_assoc($result)) {
      $memberHomeState = $row['st_desc'];
  }
}

if ($memberOfficeState_ID) {
  $sql = "SELECT st_desc FROM tb_homestate WHERE st_id = $memberOfficeState_ID";
  $result = mysqli_query($con, $sql);
  if ($row = mysqli_fetch_assoc($result)) {
      $memberOfficeState = $row['st_desc'];
  }
}

// Guarantor details
$anggotaPenjamin1 = '';
$namaPenjamin1 = '';
$icPenjamin1 = '';
$pfPenjamin1 = '';
$signaturePenjamin1 = '';
$anggotaPenjamin2 = '';
$namaPenjamin2 = '';
$icPenjamin2 = '';
$pfPenjamin2 = '';
$signaturePenjamin2 = '';

// Fetch data
$sql = "SELECT g_memberNo, g_signature FROM tb_guarantor WHERE g_loanApplicationID = $loanApplicationID";
$result = mysqli_query($con, $sql);

if ($row = mysqli_fetch_assoc($result)) {
  $anggotaPenjamin1 = $row['g_memberNo'];  
  $signaturePenjamin1 = $row['g_signature'];  
}

if ($anggotaPenjamin1) {
  $sql = "SELECT m_name, m_ic, m_pfNo FROM tb_member WHERE m_memberNo = $anggotaPenjamin1";
  $result = mysqli_query($con, $sql);
  if ($row = mysqli_fetch_assoc($result)) {
      $namaPenjamin1 = $row['m_name'];
      $icPenjamin1 = $row['m_ic'];
      $pfPenjamin1 = $row['m_pfNo'];
  }
}

// Fetch penjamin2
$sql2 = "SELECT g_memberNo, g_signature FROM tb_guarantor WHERE g_guarantorID = $guarantorID2";
$result = mysqli_query($con, $sql2);

if ($row = mysqli_fetch_assoc($result)) {
  // penjamin2 
  $anggotaPenjamin2 = $row['g_memberNo'];  
  $signaturePenjamin2 = $row['g_signature'];  
}

if ($anggotaPenjamin2) {
  $sql2 = "SELECT m_name, m_ic, m_pfNo FROM tb_member WHERE m_memberNo = $anggotaPenjamin2";
  $result = mysqli_query($con, $sql2);
  if ($row = mysqli_fetch_assoc($result)) {
      // penjamin2 
      $namaPenjamin2 = $row['m_name'];
      $icPenjamin2 = $row['m_ic'];
      $pfPenjamin2 = $row['m_pfNo'];
  }
}


if ($row = mysqli_fetch_assoc($result)) {
  // penjamin2 
  $anggotaPenjamin2 = $row['g_memberNo'];  
  $signaturePenjamin2 = $row['g_signature'];  
}
?>

<form method = "post" action = "semakan_butiran_process.php">
  <fieldset>
    <!--Semakan Butiran-->
    <div class="container">
      <br>
      <div class="jumbotron">
          <h2>Semakan Butiran</h2>
          <div class="card mb-3">
            <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
                Butir-butir Pembiayaan
                <button type="button" class="btn btn-info"  onclick="window.location.href='semakan_pinjaman.php'">
                    Kemaskini
                </button>
            </div>
            <div class="card-body">

                <table class="table table-hover">
                <tbody>
                    <tr>
                    <td scope="row">Jenis Pembiayaan</td>
                    <td><?php echo $selected_jenis_pembiayaan; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Amoun Dipohon</td>
                    <td><?php echo "RM ", $selected_amaunDipohon; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Tempoh Pembiayaan</td>
                    <td><?php echo $selected_tempohPembiayaan, " bulan"; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Ansuran Bulanan</td>
                    <td><?php echo "RM ", $selected_ansuranBulanan; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Nama Bank/Cawangan</td>
                    <td><?php echo $selected_namaBank; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Bank Account</td>
                    <td><?php echo $selected_bankAcc; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Gaji Kasar Bulanan</td>
                    <td><?php echo "RM ", $selected_gajiKasar; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Gaji Bersih Bulanan</td>
                    <td><?php echo "RM ", $selected_gajiBersih; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Signature</td>
                    <td>
                    <?php if (!empty($selected_signature)) : ?>
                        <img src="<?php echo $selected_signature; ?>" alt="Signature" style="max-width: 200px; height: auto;">
                    <?php  else : ?>
                        <span>No signature available</span>
                    <?php endif; ?>
                      </td>
                    </tr>
                </tbody>
                </table>
            
            </div>

    </div>

    </hr>
      <hr class="my-4">
        <p class="lead">
        </p>
      </hr>


        <div class="card mb-3">
            <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
                Butir-butir Peribadi Pemohon
                <button type="button" class="btn btn-info"  onclick="window.location.href='semakan_butir_peribadi.php'">
                    Kemaskini
                </button>
            </div>
            <div class="card-body">

                <table class="table table-hover">
                <tbody>
                    <tr>
                    <td scope="row">Nama</td>
                    <td><?php echo $memberName; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">No. Kad Pengenalan</td>
                    <td><?php echo $memberIC; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Jantina</td>
                    <td><?php echo $memberGender; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Agama</td>
                    <td><?php echo $memberReligion; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Bangsa</td>
                    <td><?php echo $memberRace; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Taraf Perkahwinan</td>
                    <td><?php echo $memberMaritalStatus; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">No. Anggota</td>
                    <td><?php echo $memberNo; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">No. PF</td>
                    <td><?php echo $memberpfNo; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Alamat Rumah</td>
                    <td><?php echo $memberHomeAdd; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Poskod</td>
                    <td><?php echo $memberHomePostcode; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Bandar</td>
                    <td><?php echo $memberHomeCity; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Negeri</td>
                    <td><?php echo $memberHomeState; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Jawatan Gred</td>
                    <td><?php echo $memberPositionGrade; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Jawatan</td>
                    <td><?php echo $memberPosition; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Alamat Pejabat (Tempat Bertugas)</td>
                    <td><?php echo $memberOfficeAdd; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Poskod</td>
                    <td><?php echo $memberOfficePostcode; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Bandar</td>
                    <td><?php echo $memberOfficeCity; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">No. Telefon Rumah</td>
                    <td><?php echo $memberHomeTele; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">No. Telefon Bimbit</td>
                    <td><?php echo $memberPhoneTele; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Gaji Bulanan</td>
                    <td><?php echo "RM ", $memberMonthlySalary; ?></td>
                    </tr>


                </tbody>
                </table>
            
            </div>

    </div>
      <hr class="my-4">
        <p class="lead"></p>
      </hr>
    </div>
    
    <div class="card mb-3">
            <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
                Butir-butir Penjamin
                <button type="button" class="btn btn-info"  onclick="window.location.href='semakan_penjamin.php'">
                    Kemaskini
                </button>
            </div>
            <div class="card-body">

                <table class="table table-hover">
                <tbody>
                    <!-- Penjamin 1-->
                    <tr>
                    <td scope="row">Penjamin 1</td>
                    <td></td>
                    </tr>

                    <tr>
                    <td scope="row">No. Anggota</td>
                    <td><?php echo $anggotaPenjamin1; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Nama</td>
                    <td><?php echo $namaPenjamin1; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">No. Kad Pengenalan</td>
                    <td><?php echo $icPenjamin1; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">No. PF</td>
                    <td><?php echo $pfPenjamin1; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Signature</td>
                    <td>
                    <?php if (!empty($signaturePenjamin1)) : ?>
                        <img src="<?php echo $signaturePenjamin1; ?>" alt="Signature" style="max-width: 200px; height: auto;">
                    <?php  else : ?>
                        <span>No signature available</span>
                    <?php endif; ?>
                      </td>
                    </tr>

                    <!-- Penjamin 2-->
                    <tr>
                    <td scope="row">Penjamin 2</td>
                    <td></td>
                    </tr>

                    <tr>
                    <td scope="row">No. Anggota</td>
                    <td><?php echo $anggotaPenjamin2; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Nama</td>
                    <td><?php echo $namaPenjamin2; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">No. Kad Pengenalan</td>
                    <td><?php echo $icPenjamin2; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">No. PF</td>
                    <td><?php echo $pfPenjamin2; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Signature</td>
                    <td>
                    <?php if (!empty($signaturePenjamin2)) : ?>
                        <img src="<?php echo $signaturePenjamin2; ?>" alt="Signature" style="max-width: 200px; height: auto;">
                    <?php  else : ?>
                        <span>No signature available</span>
                    <?php endif; ?>
                      </td>
                    </tr>

                </tbody>
                </table>
            </div>
    </div>
    <hr class="my-4">
      <p class="lead"></p>
    </hr>
    <div class="card mb-3">
              <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
                  Pengesahan Majikan
                  <button type="button" class="btn btn-info"  onclick="window.location.href='semakan_pinjaman.php'">
                      Kemaskini
                  </button>
              </div>

            <div class="card-body">

                <table class="table table-hover">
                <tbody>
                    <tr>
                    <td scope="row">Gaji Pokok Bulanan</td>
                    <td><?php echo "RM ", $selected_gajiKasar; ?></td>
                    </tr>

                    <tr>
                    <td scope="row">Gaji Bersih Bulanan</td>
                    <td><?php echo "RM ", $selected_gajiBersih; ?></td>
                    </tr>

                </tbody>
                </table>
            
            </div>
    </div>
    
    <link href="bootstrap.css" rel="stylesheet">

    <hr class="my-4">
      <p class="lead">
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmationModal">Hantar</button>
      </p>
    </hr>

<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Sahkan Tindakan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Adakah anda ingin meneruskan tindakan HANTAR?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" onclick="submitForm()">Ya, Hantar</button>
      </div>
    </div>
  </div>
</div>

  </fieldset>
</form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->

<script>
  // Function to handle the form submission after confirmation
  function submitForm() {
    document.forms[0].submit(); // Submit the form (change index if necessary)
  }
</script>

</body>
</html>

<!--?php include 'footer.php';?-->