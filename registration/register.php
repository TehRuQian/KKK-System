<?php
include '../header_reg.php';
session_start();
$errors = [];
include 'functions.php';

if (isset($_POST['reset_session'])) {
  session_unset(); // Clear all session variables
  session_destroy(); // Destroy the session
  header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page to refresh
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cleanPost = cleanPost($_POST);

    // Save data into the session
    $_SESSION['funame'] = $cleanPost['funame'];
    $_SESSION['fuic'] = $cleanPost['fuic'];
    $_SESSION['femail'] = $cleanPost['femail'];
    $_SESSION['fgender'] = $cleanPost['fgender'];
    $_SESSION['freligion'] = $cleanPost['freligion'];
    $_SESSION['frace'] = $cleanPost['frace'];
    $_SESSION['fmariage'] = $cleanPost['fmariage'];
    $_SESSION['fhomeaddress'] = $cleanPost['fhomeaddress'];
    $_SESSION['fcity'] = $cleanPost['fcity'];
    $_SESSION['fstate'] = $cleanPost['fstate'];
    $_SESSION['fhomezip'] = $cleanPost['fhomezip'];
    $_SESSION['fposition'] = $cleanPost['fposition'];
    $_SESSION['fgrade'] = $cleanPost['fgrade'];
    $_SESSION['fpfno'] = $cleanPost['fpfno'];
    $_SESSION['fofficeaddress'] = $cleanPost['fofficeaddress'];
    $_SESSION['fofficecity'] = $cleanPost['fofficecity'];
    $_SESSION['fofficestate'] = $cleanPost['fofficestate'];
    $_SESSION['fofficezip'] = $cleanPost['fofficezip'];
    $_SESSION['ftelno'] = $cleanPost['ftelno'];
    $_SESSION['fhomeno'] = $cleanPost['fhomeno'];
    $_SESSION['fsalary'] = $cleanPost['fsalary'];
    $_SESSION['fstatus'] = 1;

    if (empty($errors)) {

      $fuic = $cleanPost['fuic'];
      $sql = "SELECT * FROM tb_member WHERE m_ic = ?";
      $binds = [$fuic];
      $result = query($sql, $binds);
  
      $row = mysqli_fetch_assoc($result);
  
      if ($row) {
      echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'No. Kad Pengenalan ini telah didaftar.',
        });</script>";
        $errors[]="error";
      }

      $femail = $cleanPost['femail'];
      $sql_email = "SELECT * FROM tb_member WHERE m_email = ?";
      $binds_email = [$femail];
      $result_email = query($sql_email, $binds_email);

  
      if ($row_email = mysqli_fetch_assoc($result_email)) {
        echo "<script>
        Swal.fire({
          icon: 'error',
          title: 'E-mel ini telah didaftar.',
        });</script>";
        $errors[]="error";
      }
    }
  
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  if (empty($errors)) {
      $_SESSION['form_completed'] = true; // Mark form as completed
      echo "<script>
      Swal.fire({
        icon: 'success',
        title: 'Data telah disimpan.',
      });</script>";
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

        .container {
            width: 850px;
            margin: 0 auto;
        }

        .is-invalid {
            border: 2px solid red;
        }
    </style>
</head>

<div class="container-fluid">
    <div class="row g-4">
        <div class="col-2" style="background-color: #9ccfff;">
            <div class="row">
                <a href="#" class="text-center active"><br>Maklumat Pemohon</a>
                <hr>
            </div>
            <div class="row">
                <a href="#" class="text-center" id="pewaris-link">Maklumat Pewaris</a>
                <hr>
            </div>
            <div class="row">
                <a href="#" class="text-center" id="yuran-link">Yuran dan Sumbangan<br></a>
                <hr>
            </div>
            <div class="row">
                <a href="#" class="text-center" id="kebenaran-link">Akaun Kebenaran<br></a>
            </div>
        </div>

        <div class="col-10" style="max-height: 88vh; overflow-y: auto">
            <div class="container">
                <br>
                <div class="text-center">
                    <h3>Maklumat Pemohon</h3>
                </div>
                <form method="post" autocomplete="on" id="main-form">
                <fieldset>
                
        <div>
            <label class="form-label mt-4">Nama</label>
            <input type="text" name="funame" class="form-control" placeholder="Seperti dalam IC" 
            value="<?php echo (getValue('funame')); ?>" 
            pattern="[A-Za-z@/'\s]+"
            title="Nama hanya boleh mengandungi huruf dan ruang." required>
        </div>

        <div>
            <label for="fuic" class="form-label mt-4">No. Kad Pengenalan</label>
            <input type="text" name="fuic" class="form-control" id= fuic placeholder="xxxxxx-xx-xxxx" 
            value="<?php echo (getValue('fuic')); ?>" 
            pattern="\d{6}-\d{2}-\d{4}" 
            title="Kad Pengenalan must be in the format xxxxxx-xx-xxxx"
            required>
            <span id="idMessage"></span> <!-- This is where the message will be displayed -->
            
        </div>

        <div>
            <label class="form-label mt-4">Emel</label>
            <input type="email" name="femail" class="form-control" placeholder="" 
            value="<?php echo (getValue('femail')); ?>"  
            required>
        </div>
        

<div class="mb-1">
    <legend class="mt-4">Jantina</legend>
    <label class="form-check-label ps-4">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="fgender" value="1" 
            <?php echo isChecked('fgender', 1); ?> required>
            Lelaki
        </div>
    </label>
    <label class="form-check-label ps-4">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="fgender" value="2" 
            <?php echo isChecked('fgender', 2); ?> required>
            Perempuan
        </div>
    </label>
</div>

<div class="mb-1">
    <legend class="mt-4">Agama</legend>
    <?php 
        $religions = [
            '1' => 'Islam', 
            '2' => 'Buddha', 
            '3' => 'Hindu', 
            '4' => 'Kristian', 
            '5' => 'Lain-lain'
        ];

        foreach ($religions as $key => $value): 
    ?>
    <label class="form-check-label ps-4">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="freligion" value="<?php echo $key; ?>" 
            <?php echo isChecked('freligion', $key); ?>>
            <?php echo ($value); ?>
        </div>
    </label>
    <?php endforeach; ?>
</div>
        

        
            <legend class="mt-4">Bangsa</legend>
            <?php 
                $races = ['1' => 'Melayu', '2' => 'Cina', '3' => 'India', '4' => 'Lain-lain'];
                foreach ($races as $key => $value): 
            ?>
            <label class="form-check-label ps-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="frace" value="<?php echo $key; ?>" 
                    <?php echo isChecked('frace', $key); ?>>
            <?php echo ($value); ?>
                </div>
            </label>
            <?php endforeach; ?>
        

        <div>
            <label class="form-label mt-4">Taraf Perkahwinan</label>
            <select class="form-select" name="fmariage" required>
                <option value="" disabled hidden <?php echo (!isset($_SESSION['fmariage']) && !isset($_POST['fmariage'])) ? 'selected' : ''; ?>></option>
                <?php 
                    $marital_status = ['1' => 'Bujang', '2' => 'Kahwin', '3' => 'Cerai', '4' => 'Kematian Pasangan'];
                    foreach ($marital_status as $key => $value): 
                ?>
                <option value="<?php echo $key; ?>" 
                <?php echo isSelected('fmariage', $key); ?>>
                <?php echo ($value); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="form-label mt-4">Alamat Rumah</label>
            <textarea class="form-control" rows="2" name="fhomeaddress" required><?php echo (getValue('fhomeaddress')); ?></textarea>
        </div><br>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Bandar</label>
                <input type="text" class="form-control" name="fcity" 
                value="<?php echo (getValue('fcity')); ?>" 
                pattern="[A-Za-z\s]+" 
                required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Negeri</label>
                <select class="form-select" name="fstate" required>
                    <option value="" disabled hidden <?php echo (!isset($_SESSION['fstate']) && !isset($_POST['fstate'])) ? 'selected' : ''; ?>></option>
                    <?php 
                        $states = ['1' => 'Johor', '2' => 'Kedah', '3' => 'Kelantan', '4' => 'Melaka', '5' => 'Negeri Sembilan', '6' => 'Pahang', '7' => 'Pulau Pinang', '8' => 'Sabah', '9' => 'Sarawak', '10' => 'Selangor', '11' => 'Terengganu', '12' => 'WP Kuala Lumpur', '13' => 'WP Labuan', '14' => 'WP Putrajaya'];
                        foreach ($states as $key => $value): 
                    ?>
                    <option value="<?php echo $key; ?>" 
                    <?php echo isSelected('fstate', $key); ?>>
                    <?php echo ($value); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Poskod</label>
                <input type="text" class="form-control" name="fhomezip" 
                value="<?php echo (getValue('fhomezip')); ?>" 
                pattern="\d{5}" 
                title="Sila ikut format poskod yang betul." required>
            </div>
                    <br>
                    <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Jawatan</label>
                <input type="text" class="form-control" name="fposition" 
                value="<?php echo (getValue('fposition')); ?>" 
                pattern="[A-Za-z\s]+" 
                required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Gred Jawatan</label>
                <input type="text" class="form-control" name="fgrade" 
                value="<?php echo (getValue('fgrade')); ?>" 
                required>
            </div>
            <div class="col-md-3">
                <label class="form-label">PF No.</label>
                <input type="text" class="form-control" name="fpfno" 
                value="<?php echo (getValue('fpfno')); ?>" 
                required>
            </div>
        </div>
        <div>

   <div>
            <label class="form-label mt-4">Alamat Pejabat</label>
            <textarea class="form-control" rows="2" name="fofficeaddress" required><?php echo (getValue('fofficeaddress')); ?></textarea>
        </div>

        <br>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Bandar</label>
                <input type="text" class="form-control" placeholder="Bandar Pejabat" name="fofficecity" 
                value="<?php echo (getValue('fofficecity')); ?>" 
                required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Negeri</label>
                <select class="form-select" name="fofficestate" required>
                    <option value="" disabled hidden <?php echo (!isset($_SESSION['fofficestate']) && !isset($_POST['fofficestate'])) ? 'selected' : ''; ?>></option>
                    <?php 
                        $states = ['1' => 'Johor', '2' => 'Kedah', '3' => 'Kelantan', '4' => 'Melaka', '5' => 'Negeri Sembilan', '6' => 'Pahang', '7' => 'Pulau Pinang', '8' => 'Sabah', '9' => 'Sarawak', '10' => 'Selangor', '11' => 'Terengganu', '12' => 'WP Kuala Lumpur', '13' => 'WP Labuan', '14' => 'WP Putrajaya'];
                        foreach ($states as $key => $value): 
                    ?>
                    <option value="<?php echo $key; ?>" 
                    <?php echo isSelected('fofficestate', $key); ?>>
                    <?php echo ($value); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Poskod</label>
                <input type="text" class="form-control" placeholder="Poskod Pejabat" name="fofficezip" 
                value="<?php echo (getValue('fofficezip')); ?>" 
                pattern="\d{5}" 
                title="Sila ikut format poskod yang betul." required>
            </div>
        </div>

        <br>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">No. Telefon</label>
                <input type="text" class="form-control" placeholder="Nombor Telefon Tanpa '-'" name="ftelno" 
                value="<?php echo (getValue('ftelno')); ?>" 
                pattern="\d{10,11}" 
                title="Masukkan nombor telefon tanpa '-'." required>
            </div>
            <div class="col-md-6">
                <label class="form-label">No. Telefon Rumah</label>
                <input type="text" class="form-control" placeholder="Talian Rumah Tanpa '-'" name="fhomeno" 
                value="<?php echo (getValue('fhomeno')); ?>" 
                pattern="\d{9}" 
                title="Masukkan nombor telefon rumah tanpa '-'." >
            </div>
        </div>

        <br>
        <div class="mb-3">
            <label class="form-label">Gaji Sebulan</label>
            <div class="input-group">
            <span class="input-group-text">RM</span>
            <input type="number" step="0.01" class="form-control" placeholder="0.00" name="fsalary" 
            value="<?php echo getValueDecimal('fsalary'); ?>" 
            required>
    </div>
</div>

        <br>
        <div class="d-flex justify-content-center">
                        <button type="submit" name="submit" class="btn btn-primary ms-2 me-2" id="save-btn">
                            Simpan
                        </button>
                        <button type="button" class="btn btn-light" id="proceed-btn">Seterusnya</button>
                    </div>
                </form>
                <br><br>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const saveBtn = document.getElementById("save-btn");
        const proceedBtn = document.getElementById("proceed-btn");
        const mainForm = document.getElementById("main-form");
        const links = document.querySelectorAll("#pewaris-link, #yuran-link, #kebenaran-link");

        // Check form completion states from PHP session
        const FormCompleted = <?php echo isset($_SESSION['form_completed']) ? 'true' : 'false'; ?>;
        const Form1Completed = <?php echo isset($_SESSION['form1_completed']) ? 'true' : 'false'; ?>;
        const Form2Completed = <?php echo isset($_SESSION['form2_completed']) ? 'true' : 'false'; ?>;

        links.forEach(link => {
            link.addEventListener("click", function (e) {
                if (Form2Completed) {
                    // Redirect based on link ID
                    if (link.id === "pewaris-link") {
                        window.location.href = "register1.php";
                    } else if (link.id === "yuran-link") {
                        window.location.href = "register2.php";
                    } else if (link.id === "kebenaran-link") {
                        window.location.href = "register3.php";
                    }
                } else if (Form1Completed) {
                    if (link.id === "pewaris-link") {
                        window.location.href = "register1.php";
                    } else if (link.id === "yuran-link") {
                        window.location.href = "register2.php";
                    } else {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: 'Lengkapkan dan simpan maklumat sebelum meneruskan!',
                        });
                    }
                } else if (FormCompleted) {
                    if (link.id === "pewaris-link") {
                        window.location.href = "register1.php";
                    } else {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian',
                            text: 'Lengkapkan dan simpan maklumat sebelum meneruskan!',
                        });
                    }
                } else {
                    // Show alert if no form is completed
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Lengkapkan dan simpan maklumat sebelum meneruskan!',
                    });
                }
            });
        });

        // Handle 'Simpan' button click
        saveBtn.addEventListener("click", function (e) {
            const requiredFields = mainForm.querySelectorAll("[required]");
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add("is-invalid");
                    isValid = false;
                } else {
                    field.classList.remove("is-invalid");
                }
            });

            if (!isValid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Sila lengkapkan semua medan yang diperlukan!',
                });
            }
        });

        // Handle 'Seterusnya' button click
        proceedBtn.addEventListener("click", function () {
            if (<?php echo isset($_SESSION['form_completed']) ? 'true' : 'false'; ?>) {
                window.location.href = 'register1.php';
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Sila simpan maklumat sebelum meneruskan!',
                });
            }
        });
    });
</script>

</body>


