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


// Check if the 'status' parameter is present in the URL
if (isset($_GET['status']) && $_GET['status'] == 'success') {
  echo '<script>alert("Anda telah berjaya disimpan.");</script>';
}

//Extract from database
$memberNo = isset($_SESSION['funame']) ? $_SESSION['funame'] : null;

// Member personal data
$memberName = '';
$memberIC = '';

// Fetch data
if ($memberNo !== null) {
  $sql = "SELECT m_name, m_ic FROM tb_member WHERE m_memberNo = '$memberNo'";
  $result = mysqli_query($con, $sql);

  if ($row = mysqli_fetch_assoc($result)) {
    $memberName = htmlspecialchars($row['m_name']); 
    $memberIC = htmlspecialchars($row['m_ic']);
  }
}

?>

<form method = "post" action = "c_pengakuan_pemohon_process.php">
  <fieldset>
    <!--Personal details check-->
    <div class="container">
      <br>
      <div class="jumbotron">
          <h2>Pengakuan Pemohon</h2>
        <div>
          <label class="form-label mt-4">Saya</label>
            <div class="input-group mt-2">
              <input type="text" name = "nama" class="form-control" id="nama" aria-label="nama" placeholder="Ali bin Abu" value="<?php echo $memberName; ?>" readonly required style="background-color: #f0f0f0; color: #000;">
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
        <br>
        <label>dengan ini memberi kuasa kepada KOPERASI KAKITANGAN KADA KELANTAN BHD atau wakilnya yang sah untuk mendapat apa-apa maklumat yang 
           diperlukan dan juga mendapatkan bayaran balik dari potangan gaji dan emolumen saya 
           sebagaimana amaun yang dipinjamkan. Saya juga bersetuju menerima sebarang keputusan dari
           KOPERASI ini untuk menolok permohonan tanpa memberi sebarang alasan.</label>
     </div>

    <label style="display: flex; justify-content: center; align-items: center; font-size: 18px; cursor: pointer;">
        <input type="radio" name="setuju" value="setuju" required>
        Setuju
    </label>
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

<script>
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

// Save form data to cookies
function saveFormDataToCookies() {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        if (input.type === 'radio' && input.checked) {
            setCookie(input.name, input.value, 7); // Save radio button value
        } else if (input.type === 'text') {
            setCookie(input.name, input.value, 7); // Save text input value
        }
    });
}


function restoreFormDataFromCookies() {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        const cookieValue = getCookie(input.name);
        if (cookieValue !== null) {
            if (input.type === 'radio') {
                if (input.value === cookieValue) {
                    input.checked = true;
                }
            } else if (input.type === 'text') {
                input.value = cookieValue;
            }
        }
    });
}


document.querySelectorAll('input').forEach(input => {
    input.addEventListener('input', saveFormDataToCookies);
    input.addEventListener('change', saveFormDataToCookies);
});


window.addEventListener('DOMContentLoaded', restoreFormDataFromCookies);

</script>

</body>
</html>

<?php include '../footer.php'; ?>