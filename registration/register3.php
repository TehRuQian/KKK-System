<?php
include '..\header_reg.php';
session_start();
include('functions.php');
?>
<!DOCTYPE html>

<head>
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
                    <a href="register2.php" class="text-center">Yuran dan Sumbangan<br></a>
                    <hr>
                </div>
                <div class="row">
                    <a href="#" class="text-center active">Akaun Kebenaran<br></a>
                </div>
            </div>

            <div class="col-10" style="height: 88vh; overflow-y: auto">
                <div class="container">
                    <br>
                    <div class="text-center">
                        <h3>Akuan Kebenaran</h3>
                    </div>

                    <p class="text-dark"> Saya mengaku bahawa semua maklumat yang diberi adalah benar dan betul.
                        Sekiranya
                        saya didapati
                        memberikan maklumat tidak benar atau palsu, saya boleh disabitkan kesalahan di bawah seksyen 193
                        Kanun
                        Keseksaan (Akta 574) dan boleh dikenakan hukuman penjara selama tempoh yang boleh sampai tiga
                        (3)
                        tahun dan
                        boleh juga dikenakan denda.
                    </p>

                    <fieldset>
                        <div class="d-flex justify-content-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="agreeRadio" value="1">
                                <label for="agreeRadio">Setuju</label>
                            </div>
                        </div>
                    </fieldset>

                    <br>
                    <div class="d-flex justify-content-center">
                    <form method="post" id="registrationForm" action="registerprocess.php">
    <button type="button" class="btn btn-light ms-2 me-2" onclick="location.href='register2.php';">
        &lt; Kembali
    </button>
    <!-- Changed the type to "button" -->
    <button type="button" class="btn btn-primary ms-2 me-2" id="simpanBtn">Simpan</button>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const agreeRadio = document.getElementById("agreeRadio");
    const simpanBtn = document.getElementById("simpanBtn");
    const registrationForm = document.getElementById("registrationForm");

    // Handle 'Simpan' button click
    simpanBtn.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent default form submission initially

        if (!agreeRadio.checked) {
            // If the radio button is unchecked, show SweetAlert error
            Swal.fire({
                icon: "error",
                title: "Perhatian",
                text: "Sila bersetuju dengan terma dan syarat sebelum meneruskan.",
            });
        } else {
            // If the radio button is checked, show SweetAlert confirmation
            Swal.fire({
                icon: "question",
                title: "Pengesahan",
                text: "Adakah anda pasti untuk menghantar maklumat ini?",
                showCancelButton: true, // Enable Cancel button
                confirmButtonText: "Ya, hantar", // Text for Confirm button
                cancelButtonText: "Batal", // Text for Cancel button
            }).then((result) => {
                if (result.isConfirmed) {
                    // The "Ya, hantar" button was clicked
                    registrationForm.submit(); // Submit the form
                } else if (result.isDismissed) {
                    // The "Batal" button was clicked
                    Swal.fire({
                        icon: "info",
                        title: "Dibatalkan",
                        text: "Maklumat anda tidak dihantar.",
                    });
                }
            });
        }
    });
});


</script>



</body>
