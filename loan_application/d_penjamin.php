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

if (!isset($_SESSION['loanApplicationID'])) {
    die('Error: Loan application ID is missing.');
}

$loanApplicationID = $_SESSION['loanApplicationID']; // Retrieve from session

// Check if the 'status' parameter is present in the URL
if (isset($_GET['status']) && $_GET['status'] == 'success') {
  echo '<script>alert("Anda telah berjaya disimpan.");</script>';
}


?>

<form method = "post" action = "d_penjamin_process.php" enctype="multipart/form-data">
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
          <input type="text" name="anggotaPenjamin1" class="form-control" id="anggotaPenjamin1" 
          placeholder="1" value="<?php echo isset($_SESSION['anggotaPenjamin1']) ? $_SESSION['anggotaPenjamin1'] : (isset($_COOKIE['anggotaPenjamin1']) ? $_COOKIE['anggotaPenjamin1'] : ''); ?>" required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">Nama</label>
          <div class="input-group mt-2">
          <input type="text" name="namaPenjamin1" class="form-control" id="namaPenjamin1" 
          placeholder="Ali bin Abu" value="<?php echo isset($_SESSION['namaPenjamin1']) ? $_SESSION['namaPenjamin1'] : (isset($_COOKIE['anggotaPenjamin1']) ? $_COOKIE['anggotaPenjamin1'] :''); ?>" required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">No. Kad Pengenalan</label>
          <div class="input-group mt-2">
          <input type="text" name="icPenjamin1" class="form-control" id="icPenjamin1" 
          placeholder="000000-00-0000" value="<?php echo isset($_SESSION['icPenjamin1']) ? $_SESSION['icPenjamin1'] : (isset($_COOKIE['anggotaPenjamin1']) ? $_COOKIE['anggotaPenjamin1'] : ''); ?>" required>
                  </div>
        </div>

        <div>
          <label class="form-label mt-4">No. PF</label>
          <div class="input-group mt-2">
          <input type="text" name="pfPenjamin1" class="form-control" id="pfPenjamin1" 
          placeholder="1001" value="<?php echo isset($_SESSION['pfPenjamin1']) ? $_SESSION['pfPenjamin1'] : (isset($_COOKIE['anggotaPenjamin1']) ? $_COOKIE['anggotaPenjamin1'] : ''); ?>" required>
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
          <input type="text" name="anggotaPenjamin2" class="form-control" id="anggotaPenjamin2" 
          placeholder="2" value="<?php echo isset($_SESSION['anggotaPenjamin2']) ? $_SESSION['anggotaPenjamin2'] : (isset($_COOKIE['anggotaPenjamin2']) ? $_COOKIE['anggotaPenjamin2'] : ''); ?>" required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">Nama</label>
          <div class="input-group mt-2">
          <input type="text" name="namaPenjamin2" class="form-control" id="namaPenjamin2" 
          placeholder="Ali bin Abu" value="<?php echo isset($_SESSION['namaPenjamin2']) ? $_SESSION['namaPenjamin2'] : (isset($_COOKIE['anggotaPenjamin2']) ? $_COOKIE['anggotaPenjamin2'] :''); ?>" required>
        </div>
        </div>

        <div>
          <label class="form-label mt-4">No. Kad Pengenalan</label>
          <div class="input-group mt-2">
            <input type="text" name="icPenjamin2" class="form-control" id="icPenjamin2" aria-label="icPenjamin2"
             placeholder="000000-00-0001" value="<?php echo isset($_SESSION['icPenjamin2']) ? $_SESSION['icPenjamin2'] : (isset($_COOKIE['anggotaPenjamin2']) ? $_COOKIE['anggotaPenjamin2'] :''); ?>"  required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">No. PF</label>
          <div class="input-group mt-2">
            <input type="text" name="pfPenjamin2" class="form-control" id="pfPenjamin2" aria-label="pfPenjamin2"
             placeholder="1002"  value="<?php echo isset($_SESSION['pfPenjamin2']) ? $_SESSION['pfPenjamin2'] : (isset($_COOKIE['anggotaPenjamin2']) ? $_COOKIE['anggotaPenjamin2'] :''); ?>" required>
          </div>
        </div>

        <div>
          <label for="fileSignPenjamin2" class="form-label mt-4">Signature</label>
          <input class="form-control" type="file" id="fileSignPenjamin2" name="fileSignPenjamin2" accept=".png, .jpg, .jpeg" required>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Fail yang dibenarkan adalah dalam format PNG, JPG, dan JPEG sahaja. Sila pastikan saiz fail tidak melebihi 5MB.</p>
        </div>

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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  console.log('Cookie for anggotaPenjamin1: ' + getCookie('anggotaPenjamin1'));
  console.log('Cookie for anggotaPenjamin2: ' + getCookie('anggotaPenjamin2'));

  // Function to set a cookie
  function setCookie(name, value, days) {
      const expires = new Date();
      expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
      document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
  }

  // Function to get a cookie value by name
  function getCookie(name) {
      const nameEQ = name + "=";
      const ca = document.cookie.split(';');
      for (let i = 0; i < ca.length; i++) {
          let c = ca[i];
          while (c.charAt(0) == ' ') c = c.substring(1, c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
  }

  // Function to handle saving form input data in cookies
  function saveFormDataToCookies1() {
    setCookie('anggotaPenjamin1', document.getElementById('anggotaPenjamin1').value, 7);
    setCookie('namaPenjamin1', document.getElementById('namaPenjamin1').value, 7);
    setCookie('icPenjamin1', document.getElementById('icPenjamin1').value, 7);
    setCookie('pfPenjamin1', document.getElementById('pfPenjamin1').value, 7);
  }

  function saveFormDataToCookies2() {
    setCookie('anggotaPenjamin2', document.getElementById('anggotaPenjamin2').value, 7);
    setCookie('namaPenjamin2', document.getElementById('namaPenjamin2').value, 7);
    setCookie('icPenjamin2', document.getElementById('icPenjamin2').value, 7);
    setCookie('pfPenjamin2', document.getElementById('pfPenjamin2').value, 7);
  }

  


  // Function to restore saved form data from cookies
  function restoreFormDataFromCookies() {
    // Penjamin 1
    const anggotaPenjamin1 = getCookie('anggotaPenjamin1');
    if (anggotaPenjamin1) {
        document.getElementById('anggotaPenjamin1').value = anggotaPenjamin1;
    }

    const namaPenjamin1 = getCookie('namaPenjamin1');
    if (namaPenjamin1) {
        document.getElementById('namaPenjamin1').value = namaPenjamin1;
    }

    const icPenjamin1 = getCookie('icPenjamin1');
    if (icPenjamin1) {
        document.getElementById('icPenjamin1').value = icPenjamin1;
    }

    const pfPenjamin1 = getCookie('pfPenjamin1');
    if (pfPenjamin1) {
        document.getElementById('pfPenjamin1').value = pfPenjamin1;
    }

    // Penjamin 2
    const anggotaPenjamin2 = getCookie('anggotaPenjamin2');
    if (anggotaPenjamin2) {
        document.getElementById('anggotaPenjamin2').value = anggotaPenjamin2;
    }

    const namaPenjamin2 = getCookie('namaPenjamin2');
    if (namaPenjamin2) {
        document.getElementById('namaPenjamin2').value = namaPenjamin2;
    }

    const icPenjamin2 = getCookie('icPenjamin2');
    if (icPenjamin2) {
        document.getElementById('icPenjamin2').value = icPenjamin2;
    }

    const pfPenjamin2 = getCookie('pfPenjamin2');
    if (pfPenjamin2) {
        document.getElementById('pfPenjamin2').value = pfPenjamin2;
    }
  }
  $(document).ready(function () {
    // Restore saved data on page load
    restoreFormDataFromCookies();

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

      // Handle saving data when input changes for both Penjamin 1 and Penjamin 2
  $('#anggotaPenjamin1, #namaPenjamin1, #icPenjamin1, #pfPenjamin1').on('input', function () {
    saveFormDataToCookies1();
  });
  $('#anggotaPenjamin2, #namaPenjamin2, #icPenjamin2, #pfPenjamin2').on('input', function () {
    saveFormDataToCookies2();
  });


  });



</script>

</body>
</html>

<?php include '../footer.php'; ?>