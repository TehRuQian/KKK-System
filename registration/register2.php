<?php
include '..\header_reg.php';
session_start();
include('functions.php');
include('..\db_connect.php'); // Include database connection

$feeMasukMinValue = null;
$pfNumber = $_SESSION['pfNumber'];
if (isset($pfNumber)) {
    // Set minimum value for Fee Masuk to 100 if pfNumber is provided
    $feeMasukMinValue = 100.00;
} else {
    // Fetch minimum values from the database if no pfNumber is provided
    $query = "
        SELECT 
            p_memberRegFee AS ffee
        FROM tb_policies
        ORDER BY p_policyID DESC
        LIMIT 1";
    $result = mysqli_query($con, $query);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $feeMasukMinValue = (float)$row['ffee'];
    }
}

// Process form submission before rendering the HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $cleanPost = cleanPost($_POST);
    $fields = ['ffee', 'fmodal', 'fyuran', 'fanggota', 'fabrar', 'ftetap', 'fother'];

    foreach ($fields as $field) {
        $_SESSION[$field] = $cleanPost[$field] ?? '';
    }

    if (empty($errors)) {
        $_SESSION['form2_completed'] = true; // Mark form as completed
        echo "<script>
        Swal.fire({
          icon: 'success',
          title: 'Data telah disimpan.',
        });</script>";
    }
}

// Fetch minimum values from the database
$minValues = [];
$query = "
    SELECT 
        p_minShareCapital AS fmodal,
        p_minFeeCapital AS fyuran,
        p_minMemberSaving AS fanggota,
        p_minMemberFund AS fabrar,
        p_minFixedSaving AS ftetap,
        p_minOtherFees AS fother
    FROM tb_policies
    ORDER BY p_policyID DESC
    LIMIT 1";

$result = mysqli_query($con, $query);
if ($result && $row = mysqli_fetch_assoc($result)) {
    $minValues = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="regstyle.css">
</head>
<body>
  <div class="container-fluid">
    <div class="row g-4">
      <div class="col-2" style="background-color: #9ccfff;">
        <div class="row">
          <a href="register.php" class="text-center"><br>Maklumat Pemohon</a>
          <hr>
        </div>
        <div class="row">
          <a href="register1.php" class="text-center">Maklumat Pewaris</a>
          <hr>
        </div>
        <div class="row">
          <a href="#" class="text-center active">Yuran dan Sumbangan<br></a>
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
            <h3>Yuran dan Sumbangan</h3>
          </div>

          <form method="post" autocomplete="on" id="main-form3">
  <div class='mb-3'>
    <label class='form-label'>Fee Masuk</label>
      <div class='input-group'>
            <span class='input-group-text'>RM</span>
            <input type='number' class='form-control' name='ffee' 
            placeholder='Nilai Minima: <?php echo number_format((float)$feeMasukMinValue, 2, ".", ""); ?>' 
            value="<?php echo getValueDecimal('ffee'); ?>" 
            min='<?php echo number_format((float)$feeMasukMinValue, 2, ".", ""); ?>' required>
      </div>
  </div>

              <div class='mb-3'>
                <label class='form-label'>Modal Syer</label>
                <div class='input-group'>
                  <span class='input-group-text'>RM</span>
                  <input type='number' step='0.01' class='form-control' name='fmodal' placeholder='0.00' 
                value='<?php echo number_format((float)$minValues["fmodal"], 2, ".", ""); ?>' readonly="">
                </div>
              </div>
              

          <?php

          $fields = [
            'fyuran' => 'Modal Yuran',
            'fanggota' => 'Wang Deposit Anggota',
            'fabrar' => 'Sumbangan Tabung Kebajikan (Al-Abrar)',
            'ftetap' => 'Simpanan Tetap',
            'fother' => 'Lain-lain'
          ];

          foreach ($fields as $key => $label) {
            $value =  getValueDecimal($key);  // Use session data for the value
            $minValue = isset($minValues[$key]) ? $minValues[$key] : 0; // Default min value is 0 if not found
            echo "
            <div class='mb-3'>
              <label class='form-label'>$label</label>
              <div class='input-group'>
                <span class='input-group-text'>RM</span>
                 <input type='number' step='0.01' class='form-control' name='$key' 
                placeholder='Nilai Minima: " . number_format((float)$minValue, 2, '.', '') . "' 
                value='" . $value . "' 
                min='" . number_format((float)$minValue, 2, '.', '') . "' required>
              </div>
            </div>
            ";
        }
            ?>
            <div class="d-flex justify-content-center">
              <button type="button" class="btn btn-light ms-2 me-2" onclick="location.href='register1.php';">&lt; Kembali</button>
              <button type="submit" name="submit" class="btn btn-primary ms-2 me-2" id="save-btn">Simpan</button>
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
        const mainForm3 = document.getElementById("main-form3");
        const links = document.querySelectorAll("#kebenaran-link");
        const Form2Completed = <?php echo isset($_SESSION['form2_completed']) ? 'true' : 'false'; ?>;

        links.forEach(link => {
            link.addEventListener("click", function (e) {
                if (Form2Completed) {
                    if (link.id === "kebenaran-link") {
                        window.location.href = "register3.php";
                    }
                } else {
                    // Show alert if the form is not completed
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Lengkapkan dan simpan maklumat sebelum meneruskan!',
                    });
                }
            });
        });

        saveBtn.addEventListener("click", function (e) {
            const requiredFields = mainForm3.querySelectorAll("[required]");
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

        proceedBtn.addEventListener("click", function () {
            if (<?php echo isset($_SESSION['form2_completed']) ? 'true' : 'false'; ?>) {
                window.location.href = 'register3.php';
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
</html>
