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
          <a href="b_butir_peribadi.php" class="text-center">Butir-Butir Peribadi Pemohon</a>
          <hr>
        </div>
        <div class="row">
          <a href="c_pengakuan_pemohon.php" class="text-center active">Pengakuan Pemohon<br></a>
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