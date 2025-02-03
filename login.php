<?php 
include 'headermain.php'; 
include 'login_function.php';
include 'db_connect.php'; // Include your database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .top-half {
            height: 60%;
            background: url('img/rice.jpg') center top no-repeat;
            background-size: cover;
        }

        .bottom-half {
            height: 40%;
            background: white;
        }

        .login-container {
            height: 350px;
            width: 430px;
            margin: auto;
            position: absolute;
            top: 53%;
            left: 50%;
            transform: translate(-50%, -30%);
            background: rgba(255, 255, 255, 0.8);
            border-radius: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 300px;
            height: 250px;
            margin: 0 auto;
        }

        img {
        display: block;
        margin: auto;
        }

        h1 {
        font-family: 'Inter';font-size: 38px;
        font-style: italic; 
        font-weight: bold; 
        color: white;
        }

        h2 {
        font-family: 'Inter';font-size: 43px;
        font-style: italic; 
        font-weight: bold; 
        color: white;
        }

        h6 {
        font-family: 'Inter';font-size: 12px;
        color:black;
        }

        .custom-width {
        width: 200px;
        }

       .center-button {
        margin: 0;
        position: absolute;
        left: 50%;
        transform: translate(-50%, -4%);
       }

       a:link {
       text-decoration: underline;
       }
       a:hover {
       text-decoration: underline;
       }
       a:active {
       text-decoration: underline;
       }

    </style>
</head>

<body>
    <div class="top-half">
        <br>
        <img src="img/kkk_logo.png" style="width:10%">
        <h2 class="text-center">Sistem Digital Bersepadu</h2>
        <h1 class="text-center">Koperasi Kakitangan KADA Kelantan Berhad </h1>
    </div>
    <div class="bottom-half">
        <div class="login-container">
            <div class="container">
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <br><br>
                <h4 class="text-center">Log Masuk</h4>
                <form method="post" action="loginprocess.php">
                    <fieldset>
                        <div>
                            <div class="form-floating mb-3 mt-2">
                                <input type="text" name="funame" class="form-control" id="floatingInput" placeholder="" required>
                                <label for="floatingInput" class="text-muted d-flex justify-content-center">Pengguna ID</label>
                            </div>
                            <div class="form-floating">
                                <input type="password" name="fpwd" class="form-control" id="floatingPassword" placeholder="" autocomplete="off" required>
                                <label for="floatingPassword" class="text-muted d-flex justify-content-center">Kata Laluan</label>
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary custom-width center-button">Log Masuk</button>
                        <br><br>
                        <h6 class="text-center"><b><a href="#" id="applyMember">Permohonan Menjadi Anggota</a></b></h6>
                        <h6 class="text-center"><b><a href="forgot_password.php">Lupa Kata Laluan</a></b></h6>
                        <br><br><br>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('applyMember').addEventListener('click', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Adakah anda pernah menjadi anggota?',
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Ya',
                denyButtonText: 'Tidak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sila masukkan nombor P.F. anda',
                        input: 'text',
                        inputPlaceholder: 'Masukkan nombor P.F.',
                        showCancelButton: true,
                        confirmButtonText: 'Hantar',
                        cancelButtonText: 'Batal',
                        preConfirm: (pfNumber) => {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = ''; // Submit to the same page

                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'pfNumber';
                            input.value = pfNumber;

                            form.appendChild(input);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                } else if (result.isDenied) {
                    window.location.href = "registration/register.php";
                }
            });
        });
    </script>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pfNumber'])) {
        $pfNumber = $_POST['pfNumber'];

        // Database Query
        $query = "SELECT m_status FROM tb_member WHERE m_pfNo = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $pfNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = $row['m_status'];

            switch ($status) {
                case 1:
                    echo "<script>
                        Swal.fire('Tunggu Admin Approve', 'Permohonan anda sedang diproses.', 'info').then(() => {
                            window.location.href = 'login.php';
                        });
                    </script>";
                    break;

                case 2:
                    echo "<script>
                        Swal.fire('Ditolak', 'Permohonan anda telah ditolak. Sila mohon semula.', 'error').then(() => {
                            window.location.href = 'registration/register.php';
                        });
                    </script>";
                    break;

                case 3:
                    echo "<script>
                        Swal.fire('Anda Sudah Menjadi Ahli', 'Akaun anda aktif.', 'success').then(() => {
                            window.location.href = 'login.php';
                        });
                    </script>";
                    break;

                case 5:
                    echo "<script>
                        Swal.fire('Maklumat telah dijumpa', 'Anda akan diarahkan ke halaman permohonan.', 'info').then(() => {
                            window.location.href = 'registration/register.php?pfNumber={$pfNumber}';
                        });
                    </script>";
                    break;

                default:
                    echo "<script>Swal.fire('Kesalahan', 'Status tidak diketahui.', 'error');</script>";
            }
        } else {
            echo "<script>
                Swal.fire('Tidak Dijumpai', 'Nombor P.F. tidak wujud dalam sistem. Sila mohon jadi anggota.', 'error');
                    window.location.href = 'registration/register.php';
            </script>";
        }
    }
    ?>
</body>
</html>
