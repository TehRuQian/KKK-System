<?php
include '..\header_reg.php';
include 'functions.php';
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cleanPost = cleanPost($_POST);

    for ($i = 1; $i <= 20; $i++) {
        $_SESSION["wname$i"] = $cleanPost["wname$i"] ?? '';
        $_SESSION["wic$i"] = $cleanPost["wic$i"] ?? '';
        $_SESSION["wrelation$i"] = $cleanPost["wrelation$i"] ?? '';
    }

    $_SESSION['pewarisCount'] = 0;
    for ($i = 1; $i <= 20; $i++) {
        if (!empty($_SESSION["wname$i"])) {
            $_SESSION['pewarisCount']++;
        }
    }
} elseif (!isset($_SESSION['pewarisCount'])) {
    $_SESSION['pewarisCount'] = 3;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (empty($errors)) {
        $_SESSION['form1_completed'] = true; // Mark form as completed
        echo "<script>
        Swal.fire({
          icon: 'success',
          title: 'Data telah disimpan.',
        });</script>";
    }
  }
?>

<html>

<head>
    <style>
        .row-spacing {
            margin-bottom: 4rem;
        }

        a {
            text-decoration: none;
            margin-bottom: 0.5rem;
            display: block;
            text-align: center;
        }

        a:active,
        a.active {
            color: black !important;
        }

        .container {
            width: 850px;
            margin: 0 auto;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section .remove-btn {
            display: inline-block;
            margin-top: 10px;
            color: red;
            cursor: pointer;
        }
    </style>
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
                <a href="#" class="text-center active">Maklumat Pewaris</a>
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
                        <h3>Maklumat Pewaris</h3>
                    </div>

                    <form method="post" autocomplete="on" id="main-form2">
                        <div id="pewaris-fields">
                            <?php for ($i = 1; $i <= $_SESSION['pewarisCount']; $i++): ?>
                                <div class="form-section" id="pewaris-<?= $i ?>">
                                    <h4>Pewaris <?= $i ?></h4>
                                    <fieldset>
                                        <div>
                                            <label class="form-label mt-4">Nama</label>
                                            <input type="text" name="wname<?= $i ?>" class="form-control" placeholder="Seperti dalam IC"
                                                value="<?= $_SESSION["wname$i"] ?? '' ?>"
                                                required pattern="[A-Za-z\s]+" 
                                                title="Nama hanya boleh mengandungi huruf dan ruang.">
                                        </div>
                                        <div>
                                            <label class="form-label mt-4">Nombor KP</label>
                                            <input type="text" class="form-control" name="wic<?= $i ?>"
                                                value="<?= $_SESSION["wic$i"] ?? '' ?>"
                                                required pattern="\d{6}-\d{2}-\d{4}" 
                                                title="Kad Pengenalan must be in the format xxxxxx-xx-xxxx">
                                        </div>
                                        <div>
                                            <label class="form-label mt-4">Hubungan</label>
                                            <select class="form-select" name="wrelation<?= $i ?>" required>
                                                <option value="" disabled <?= (!isset($_SESSION["wrelation$i"])) ? 'selected' : '' ?> hidden></option>
                                                <option value="1" <?= ($_SESSION["wrelation$i"] ?? '') == '1' ? 'selected' : '' ?>>Suami Isteri</option>
                                                <option value="2" <?= ($_SESSION["wrelation$i"] ?? '') == '2' ? 'selected' : '' ?>>Anak</option>
                                                <option value="3" <?= ($_SESSION["wrelation$i"] ?? '') == '3' ? 'selected' : '' ?>>Keturunan</option>
                                                <option value="4" <?= ($_SESSION["wrelation$i"] ?? '') == '4' ? 'selected' : '' ?>>Orang Tua</option>
                                                <option value="5" <?= ($_SESSION["wrelation$i"] ?? '') == '5' ? 'selected' : '' ?>>Saudara Kandung</option>
                                                <option value="6" <?= ($_SESSION["wrelation$i"] ?? '') == '6' ? 'selected' : '' ?>>Lain-lain</option>
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-secondary" id="add-pewaris-btn">Tambah Pewaris</button>
                        </div>
                        <br>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-light ms-2 me-2" onclick="location.href='register.php';">&lt; Kembali</button>
                            <button type="submit" name="submit" class="btn btn-primary ms-2 me-2" id="save-btn">
                            Simpan
                            </button>
                        <button type="button" class="btn btn-light" id="proceed-btn">Seterusnya</button>
                        </div>
                        <br><br>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-pewaris-btn').addEventListener('click', function() {
            const pewarisFields = document.getElementById('pewaris-fields');
            const currentCount = pewarisFields.children.length + 1;

            if (currentCount <= 20) {
                const newPewaris = document.createElement('div');
                newPewaris.classList.add('form-section');
                newPewaris.id = 'pewaris-' + currentCount;
                newPewaris.innerHTML = `
                    <h4>Pewaris ${currentCount}</h4>
                    <fieldset>
                        <div>
                            <label class="form-label mt-4">Nama</label>
                            <input type="text" name="wname${currentCount}" class="form-control" placeholder="Seperti dalam IC"
                                pattern="[A-Za-z\s]+" title="Nama hanya boleh mengandungi huruf dan ruang.">
                        </div>
                        <div>
                            <label class="form-label mt-4">Nombor KP</label>
                            <input type="text" class="form-control" name="wic${currentCount}"
                                pattern="\\d{6}-\\d{2}-\\d{4}" title="Kad Pengenalan must be in the format xxxxxx-xx-xxxx">
                        </div>
                        <div>
                            <label class="form-label mt-4">Hubungan</label>
                            <select class="form-select" name="wrelation${currentCount}">
                                <option value="" disabled selected hidden></option>
                                <option value="1">Suami Isteri</option>
                                <option value="2">Anak</option>
                                <option value="3">Keturunan</option>
                                <option value="4">Orang Tua</option>
                                <option value="5">Saudara Kandung</option>
                                <option value="6">Lain-lain</option>
                            </select>
                        </div>
                    </fieldset>
                `;
                pewarisFields.appendChild(newPewaris);
            } else {
                alert('Maksimum 20 pewaris dibenarkan.');
            }
        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const saveBtn = document.getElementById("save-btn");
        const proceedBtn = document.getElementById("proceed-btn");
        const mainForm2 = document.getElementById("main-form2");
        const links = document.querySelectorAll("#yuran-link, #kebenaran-link");
        const Form1Completed = <?php echo isset($_SESSION['form1_completed']) ? 'true' : 'false'; ?>;
        const Form2Completed = <?php echo isset($_SESSION['form2_completed']) ? 'true' : 'false'; ?>;

        links.forEach(link => {
            link.addEventListener("click", function (e) {
                if (Form2Completed) {
                    if (link.id === "yuran-link") {
                        window.location.href = "register2.php";
                    } else if (link.id === "kebenaran-link") {
                        window.location.href = "register3.php"; // Replace with actual page
                    }
                } else if (Form1Completed) {
                    if (link.id === "yuran-link") {
                        window.location.href = "register2.php";
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

        // Handle 'Simpan' button click
        saveBtn.addEventListener("click", function (e) {
            const requiredFields = mainForm2.querySelectorAll("[required]");
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
            if (<?php echo isset($_SESSION['form1_completed']) ? 'true' : 'false'; ?>) {
                window.location.href = 'register2.php';
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

