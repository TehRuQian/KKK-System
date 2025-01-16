<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../headermember.php';
include '../db_connect.php';
// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['uid'])) {
  // If not logged in, redirect to login page
  header('Location: login.php');
  exit(); // Ensure no further code is executed
}

// Loan
// if (!isset($_SESSION['loanApplicationID'])) {
//     die('Error: Loan application ID is missing.');
// }

$loanApplicationID = $_SESSION['loanApplicationID']; // Retrieve from session

// Debugging the session variables
// echo '<pre>';
// var_dump($_SESSION['guarantorID1'], $_SESSION['guarantorID2']);
// echo '</pre>';

if (!isset($_SESSION['guarantorID1']) || !isset($_SESSION['guarantorID2'])) {
  die('Error: Guarantor ID1 or Guarantor ID2 is missing.');
}

$guarantorID1 = $_SESSION['guarantorID1'];
$guarantorID2 = $_SESSION['guarantorID2'];

// Guarantor data
$memberNo1 = '';
$namePenjamin1 = '';
$icPenjamin1 = '';
$icPenjamin1 = '';
$pfNoPenjamin1 = '';
$signaturePenjamin1 = '';
//Penjamin 2
$memberNo2 = '';
$namePenjamin2 = '';
$icPenjamin2 = '';
$icPenjamin2 = '';
$pfNoPenjamin2 = '';
$signaturePenjamin2 = '';

// Fetch data
if ($loanApplicationID !== null) {
    $sql = "SELECT g_memberNo, g_signature, m.m_name, m.m_ic, m.m_pfNo 
            FROM tb_guarantor g 
            LEFT JOIN tb_member m ON g.g_memberNo = m.m_memberNo 
            WHERE g.g_loanApplicationID = '$loanApplicationID'";
    $result = mysqli_query($con, $sql);
  
    if ($row = mysqli_fetch_assoc($result)) {
        $memberNo1 = htmlspecialchars($row['g_memberNo']);
        $signaturePenjamin1 = htmlspecialchars($row['g_signature']);
        $namePenjamin1 = htmlspecialchars($row['m_name']);
        $icPenjamin1 = htmlspecialchars($row['m_ic']);
        $pfNoPenjamin1 = htmlspecialchars($row['m_pfNo']);
    }
  }

  if ($guarantorID2 !== null) {
    $sql = "SELECT g_memberNo, g_signature, m.m_name, m.m_ic, m.m_pfNo 
            FROM tb_guarantor g 
            LEFT JOIN tb_member m ON g.g_memberNo = m.m_memberNo 
            WHERE g.g_loanApplicationID = '$loanApplicationID' AND g.g_guarantorID = '$guarantorID2'";
    $result = mysqli_query($con, $sql);
  
    if ($row = mysqli_fetch_assoc($result)) {
        $memberNo2 = htmlspecialchars($row['g_memberNo']);
        $signaturePenjamin2 = htmlspecialchars($row['g_signature']);
        $namePenjamin2 = htmlspecialchars($row['m_name']);
        $icPenjamin2 = htmlspecialchars($row['m_ic']);
        $pfNoPenjamin2 = htmlspecialchars($row['m_pfNo']);
    }
  }
?>

<form method = "post" action = "semakan_penjamin_process.php"  enctype="multipart/form-data">
  <input type="file" name="fileSignPenjamin1">
  <input type="file" name="fileSignPenjamin2">
  <fieldset>
    <div class="container">
      <br>
      <div class="jumbotron">
        <h2>Butir-Butir Penjamin</h2>

        <!-- Penjamin 1 -->
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Penjamin 1" readonly style="background-color: #6a9bf0; color: #00000;">
        </div>

        <div>
          <label class="form-label mt-4">No. Anggota</label>
          <div class="input-group mt-2">
            <input type="text" name = "anggotaPenjamin1" class="form-control" id="anggotaPenjamin1" aria-label="anggotaPenjamin1" placeholder="1" value = "<?php echo $memberNo1; ?>"required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">Nama</label>
          <div class="input-group mt-2">
            <input type="text" name = "namaPenjamin1" class="form-control" id="namaPenjamin1" aria-label="namaPenjamin1" placeholder="Ali bin Abu"  value="<?php echo $namePenjamin1; ?>" readonly required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">No. Kad Pengenalan</label>
          <div class="input-group mt-2">
            <input type="text" name = "icPenjamin1" class="form-control" id="icPenjamin1" aria-label="icPenjamin1" placeholder="000000-00-0000"  value="<?php echo $icPenjamin1; ?>" readonly required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">No. PF</label>
          <div class="input-group mt-2">
            <input type="text" name = "pfPenjamin1" class="form-control" id="pfPenjamin1" aria-label="pfPenjamin1" placeholder="1001"  value="<?php echo $pfNoPenjamin1; ?>" reaonly required>
          </div>
        </div>

        <div>
          <label for="fileSignPenjamin1" class="form-label mt-4">Signature</label>
          <input class="form-control" type="file" id="fileSignPenjamin1" name="fileSignPenjamin1" accept=".png, .jpg, .jpeg" required>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Fail yang dibenarkan adalah dalam format PNG, JPG, dan JPEG sahaja. Sila pastikan saiz fail tidak melebihi 5MB.</p>
        </div>

      <!-- Penjamin 2 -->
      <div class="input-group mt-4">
            <input type="text" class="form-control" placeholder="Penjamin 2" readonly style="background-color: #6a9bf0; color: #00000;">
        </div>

        <div>
          <label class="form-label mt-4">No. Anggota</label>
          <div class="input-group mt-2">
            <input type="text" name="anggotaPenjamin2" class="form-control" id="anggotaPenjamin2" aria-label="anggotaPenjamin2" placeholder="2"  value = "<?php echo $memberNo2; ?>" required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">Nama</label>
          <div class="input-group mt-2">
            <input type="text" name="namaPenjamin2" class="form-control" id="namaPenjamin2" aria-label="namaPenjamin2" placeholder="Abu bin Ali" value="<?php echo $namePenjamin2; ?>" readonly required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">No. Kad Pengenalan</label>
          <div class="input-group mt-2">
            <input type="text" name="icPenjamin2" class="form-control" id="icPenjamin2" aria-label="icPenjamin2" placeholder="000000-00-0001" value="<?php echo $icPenjamin2; ?>" readonly  required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">No. PF</label>
          <div class="input-group mt-2">
            <input type="text" name="pfPenjamin2" class="form-control" id="pfPenjamin2" aria-label="pfPenjamin2" placeholder="1002" value="<?php echo $pfNoPenjamin2; ?>" reaonly  required>
          </div>
        </div>

        <div>
          <label for="fileSignPenjamin1" class="form-label mt-4">Signature</label>
          <input class="form-control" type="file" id="fileSignPenjamin1" name="fileSignPenjamin1" accept=".png, .jpg, .jpeg" required>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Fail yang dibenarkan adalah dalam format PNG, JPG, dan JPEG sahaja. Sila pastikan saiz fail tidak melebihi 5MB.</p>
        </div>

      <hr class="my-4">
        <p class="lead">
        <button type="submit" class="btn btn-primary">Simpan</button>
        </p>
      </hr>
    </div>   
  </fieldset>
</form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    // Handle input event for No. Anggota
    $('#anggotaPenjamin1').on('input', function () {
      const anggotaPenjamin1 = $(this).val();

      // Check if input is not empty
      if (anggotaPenjamin1.trim() !== '') {
        $.ajax({
          url: 'fetch_member.php', // Backend script to fetch data
          type: 'POST',
          data: { anggotaPenjamin1: anggotaPenjamin1},
          success: function (response) {
            const data = JSON.parse(response);
            if (data.penjamin1) {
              $('#namaPenjamin1').val(data.penjamin1.m_name);
              $('#icPenjamin1').val(data.penjamin1.m_ic);
              $('#pfPenjamin1').val(data.penjamin1.m_pfNo);
            } else {
              $('#namaPenjamin1').val('');
              $('#icPenjamin1').val('');
              $('#pfPenjamin1').val('');
              alert(data.penjamin1_error || 'No member found.');
            }
          },
          error: function () {
            alert('An error occurred while fetching data.');
          }
        });
      } else {
        $('#namaPenjamin1').val('');
        $('#icPenjamin1').val('');
        $('#pfPenjamin1').val('');
      }
    });

    $('#anggotaPenjamin2').on('input', function () {
      const anggotaPenjamin2 = $(this).val();

      // Check if input is not empty
      if (anggotaPenjamin2.trim() !== '') {
        $.ajax({
          url: 'fetch_member.php', // Backend script to fetch data
          type: 'POST',
          data: { anggotaPenjamin2: anggotaPenjamin2},
          success: function (response) {
            const data = JSON.parse(response);
            if (data.penjamin2) {
              $('#namaPenjamin2').val(data.penjamin2.m_name);
              $('#icPenjamin2').val(data.penjamin2.m_ic);
              $('#pfPenjamin2').val(data.penjamin2.m_pfNo);
            } else {
              $('#namaPenjamin2').val('');
              $('#icPenjamin2').val('');
              $('#pfPenjamin2').val('');
              alert(data.penjamin2_error || 'No member found.');
            }
          },
          error: function () {
            alert('An error occurred while fetching data.');
          }
        });
      } else {
        $('#namaPenjamin2').val('');
        $('#icPenjamin2').val('');
        $('#pfPenjamin2').val('');
      }
    });
  });
</script>

</body>
</html>

<!--?php include 'footer.php';?-->