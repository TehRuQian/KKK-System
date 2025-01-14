<?php
include '..\header_reg.php';
session_start();
include('functions.php');
include('..\db_connect.php'); // Include database connection

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
        p_memberRegFee AS ffee,
        p_minShareCapital AS fmodal,
        p_minFeeCapital AS fyuran,
        p_minFixedSaving AS fanggota,
        p_minMemberFund AS fabrar,
        p_minMemberSaving AS ftetap,
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
  <style>
    .row-spacing { margin-bottom: 4rem; }
    a:hover {}
    a:active, a.active { color: black !important; }
    a { text-decoration: none; margin-bottom: 0.5rem; }
    .container { width: 850px; margin: 0 auto; }
  </style>
  <title>Yuran dan Sumbangan</title>
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
                  <input type='number' class='form-control' name='ffee' placeholder='0.00' 
                  value="<?php echo (getValue('ffee')); ?>" 
                  min='<?php echo $minValues['ffee']; ?>' required>
                </div>
              </div>

          <div class='mb-3'>
                <label class='form-label'>Modal Syer</label>
                <div class='input-group'>
                  <span class='input-group-text'>RM</span>
                  <input type='number' class='form-control' name='fmodal' placeholder='0.00' 
                  value='<?php echo $minValues['fmodal']; ?>' readonly="">
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
                $value = isset($_SESSION[$key]) ? $_SESSION[$key] : ''; // Use session data for the value
                $minValue = isset($minValues[$key]) ? $minValues[$key] : 0; // Default min value is 0 if not found
                echo "
                <div class='mb-3'>
                  <label class='form-label'>$label</label>
                  <div class='input-group'>
                    <span class='input-group-text'>RM</span>
                    <input type='number' class='form-control' name='$key' placeholder='0.00' value='$value' min='$minValue' required>
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

        if (!<?php echo isset($_SESSION['form2_completed']) ? 'true' : 'false'; ?>) {
            links.forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Lengkapkan dan simpan maklumat sebelum meneruskan!',
                    });
                });
            });
        }

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