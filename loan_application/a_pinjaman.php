<!DOCTYPE html>
<html>
<head>   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<?php
ob_start(); 

include('../kkksession.php');
if(!session_id())
{
  session_start();
}
if ($_SESSION['u_type'] != 2) {
  header('Location: ../login.php');
  exit();
}

if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:../login.php'); 
}


include '../headermember.php';
include '../db_connect.php';
$memberNo = $_SESSION['funame'];


$sql = "
    SELECT * FROM tb_policies
    ORDER BY p_policyID DESC
    LIMIT 1;";
  $result = mysqli_query($con, $sql);
  $policy = mysqli_fetch_assoc($result);

  $sql = "SELECT f_shareCapital, m_status FROM tb_financial JOIN tb_member ON tb_financial.f_memberNo = tb_member.m_memberNo WHERE f_memberNo = $memberNo";
  $result = mysqli_query($con, $sql);

  if ($row = mysqli_fetch_assoc($result)) {
    $shareCapital = htmlspecialchars($row['f_shareCapital']); 
    $m_status = $row['m_status'];
  }

  $errors = [];

  if ($shareCapital < $policy['p_minShareCapitalForLoan']) {
    $errors[] = "Modal Syer anda (RM" . number_format($shareCapital, 2) . ") kurang daripada jumlah minimum yang diperlukan (RM" . number_format($policy['p_minShareCapitalForLoan'], 2) . ").";
  }

  if (in_array($m_status, [5, 6])) {
    $errors[] = "Status keanggotaan anda tidak membenarkan.";
  }

  if (!empty($errors)) {
    $errorMessage = implode(" ", $errors);
    echo "<script>
            Swal.fire({
                title: 'Maaf!',
                text: '$errorMessage',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'dashboard_pinjaman.php';
                }
            });
        </script>";
    exit();
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

      .required {
        color: red;
        font-weight: bold;
      }
          
    </style>
</head>

<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-2 sidebar">
        <div class="row">
          <a href="a_pinjaman.php" class="text-center active"><br>Butir-Butir Pembiayaan</a>
          <hr>
        </div>
        <div class="row">
          <a href="b_butir_peribadi.php" class="text-center">Butir-Butir Peribadi Pemohon</a>
          <hr>
        </div>
        <div class="row">
          <a href="c_pengakuan_pemohon.php" class="text-center">Pengakuan Pemohon<br></a>
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
<form method = "post" action = "a_pinjaman_process.php" enctype="multipart/form-data">
  <fieldset>
    <div class="container">
      <br>
      <div class="jumbotron">
        <h2>Butir-Butir Pembiayaan</h2>
        <label class="form-label mt-4">Jenis Pembiayaan <span class="required">*</span> </label>
        <br>
        <select class="form-select" name="jenis_pembiayaan" class="form-select">
        <?php
        $sql="SELECT * FROM tb_ltype";
        $result=mysqli_query($con,$sql);

        while($row=mysqli_fetch_array($result))
        {
          $selected = isset($_COOKIE['jenis_pembiayaan']) && $_COOKIE['jenis_pembiayaan'] == $row['lt_lid'] ? 'selected' : '';
          echo '<option value="' . $row['lt_lid'] . '" ' . $selected . '>' . $row['lt_desc'] . '</option>';
        }
        ?>
        </select>

        <!-- Hidden input to store profit rate -->
        <input type="hidden" id="rateAlBai" value="<?php echo $policy['p_rateAlBai']; ?>">
        <input type="hidden" id="rateAlInnah" value="<?php echo $policy['p_rateAlInnah']; ?>">
        <input type="hidden" id="rateBPulihKenderaan" value="<?php echo $policy['p_rateBPulihKenderaan']; ?>">
        <input type="hidden" id="rateCukaiJalanInsurans" value="<?php echo $policy['p_rateCukaiJalanInsurans']; ?>">
        <input type="hidden" id="rateKhas" value="<?php echo $policy['p_rateKhas']; ?>">
        <input type="hidden" id="rateKarnivalMusim" value="<?php echo $policy['p_rateKarnivalMusim']; ?>">
        <input type="hidden" id="rateAlQadrulHassan" value="<?php echo $policy['p_rateAlQadrulHassan']; ?>">

        <input type="hidden" id="maxAlBai" value="<?php echo $policy['p_maxAlBai']; ?>" data-jenis="1">
        <input type="hidden" id="maxAlInnah" value="<?php echo $policy['p_maxAlInnah']; ?>" data-jenis="2">
        <input type="hidden" id="maxBPulihKenderaan" value="<?php echo $policy['p_maxBPulihKenderaan']; ?>" data-jenis="3">
        <input type="hidden" id="maxCukaiJalanInsurans" value="<?php echo $policy['p_maxCukaiJalanInsurans']; ?>" data-jenis="4">
        <input type="hidden" id="maxKhas" value="<?php echo $policy['p_maxKhas']; ?>" data-jenis="5">
        <input type="hidden" id="maxKarnivalMusim" value="<?php echo $policy['p_maxKarnivalMusim']; ?>" data-jenis="6">
        <input type="hidden" id="maxAlQadrulHassan" value="<?php echo $policy['p_maxAlQadrulHassan']; ?>" data-jenis="7">
        <input type="hidden" id="maxInstallmentPeriod" value="<?php echo $policy['p_maxInstallmentPeriod']; ?>">
        <input type="hidden" name="tunggakan" id="tunggakan" value="0.00">

        <div>
          <label class="form-label mt-4">Amaun Dipohon <span class="required">*</span></label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span>
            <input type="text" name="amaunDipohon" class="form-control" id="amaunDipohon" aria-label="amaunDipohon" placeholder="0.00" value="<?php echo isset($_COOKIE['amaunDipohon']) ? $_COOKIE['amaunDipohon'] : ''; ?>" required>
          </div>
          <small id="amaunError" class="text-danger" style="display: none;">Amaun Dipohon telah melebihi maksimum: RM<?php echo number_format($policy['p_maxFinancingAmt'], 2); ?></small>
        </div>

        <div>
          <label class="form-label mt-4">Tempoh Pembiayaan <span class="required">*</span></label>
          <div class="input-group mt-2">
            <input type="text" name="tempohPembiayaan" class="form-control" id="tempohPembiayaan" aria-label="tempohPembiayaan" placeholder="0" value="<?php echo isset($_COOKIE['tempohPembiayaan']) ? $_COOKIE['tempohPembiayaan'] : ''; ?>" required>  
            <span class="input-group-text">tahun</span>

          </div>
            <small id="tempohError" class="text-danger" style="display: none;">Tempoh Pembiayaan telah melebihi maksimum: <?php echo number_format($policy['p_maxInstallmentPeriod'], 2); ?>tahun</small>
          </div>

        <div>
          <label class="form-label mt-4">Ansuran Bulanan <span class="required">*</span></label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name="ansuranBulanan" class="form-control" id="ansuranBulanan" aria-label="ansuranBulanan" placeholder="0.00" value="<?php echo isset($_COOKIE['ansuranBulanan']) ? $_COOKIE['ansuranBulanan'] : ''; ?>" required>         
          </div>  
        </div>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Sila rujuk jadual pembayaran balik pembiayaan skim </p>

        <div>
          <label class="form-label mt-4">Nama Bank/Cawangan <span class="required">*</span></label>

          <?php
            $sql = "SELECT * FROM tb_lbank";
            $result = mysqli_query($con, $sql);

            echo '<select class="form-select" name="namaBank" id="namaBank">';
            while ($row = mysqli_fetch_array($result)) {
                $selected = isset($_COOKIE['namaBank']) && $_COOKIE['namaBank'] == $row['lb_id'] ? 'selected' : '';
                echo '<option value="' . $row['lb_id'] . '" ' . $selected . '>' . $row['lb_desc'] . '</option>';
            }
            echo '</select>';
            ?>
        </div>

        <div>
          <label class="form-label mt-4">Bank Account <span class="required">*</span></label>
          <div class="input-group mt-2">
          <input type="text" name="bankAcc" class="form-control" id="bankAcc" aria-label="bankAcc" placeholder="000000000" value="<?php echo isset($_COOKIE['bankAcc']) ? $_COOKIE['bankAcc'] : ''; ?>" required>          </div>  
        </div>

        <div>
          <label class="form-label mt-4" for="gajiKasar">Gaji Kasar Bulanan <span class="required">*</span></label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name="gajiKasar" class="form-control" id="gajiKasar" aria-label="gajiKasar" placeholder="0.00" value="<?php echo isset($_COOKIE['gajiKasar']) ? $_COOKIE['gajiKasar'] : ''; ?>" required>          </div>  
        </div>

        <div>
          <label class="form-label mt-4">Gaji Bersih Bulanan <span class="required">*</span></label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name="gajiBersih" class="form-control" id="gajiBersih" aria-label="gajiBersih" placeholder="0.00" value="<?php echo isset($_COOKIE['gajiBersih']) ? $_COOKIE['gajiBersih'] : ''; ?>" required>
          </div>  
        </div>

        <div>
          <label for="fileSign" class="form-label mt-4">Tandatangan <span class="required">*</span> </label>
          <input class="form-control" type="file" id="fileSign" name="fileSign" accept=".png, .jpg, .jpeg" required>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Fail yang dibenarkan adalah dalam format PNG, JPG dan JPEG sahaja. Sila pastikan saiz fail tidak melebihi 5MB.</p>
        </div>
          
        <div style="text-align: center;">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>

</div>
    </div>   
  </fieldset>
</form>
</div>
</div>
</body>
</html>

<script>

const maxInstallmentPeriod1 = <?php echo floatval($policy['p_maxInstallmentPeriod']); ?>;

function showError(message) {
        Swal.fire({
            title: 'Maaf!',
            text: message,
            icon: 'error',
            confirmButtonText: 'OK'
        });
}
      
function calculateInstallment() {
    const amaunDipohon = parseFloat(document.getElementById('amaunDipohon').value) || 0;
    const tempohPembiayaan = parseFloat(document.getElementById('tempohPembiayaan').value) || 0;

    const jenisPembiayaan = parseInt(document.querySelector('select[name="jenis_pembiayaan"]').value);
    
    let profitRate = 0; 
    let maxFinancingAmt = 0; 

    switch (jenisPembiayaan) {
        case 1:
            profitRate = <?php echo json_encode(floatval($policy['p_rateAlBai'])); ?>;
            maxFinancingAmt = <?php echo json_encode(floatval($policy['p_maxAlBai'])); ?>;
            break;
        case 2:
            profitRate = <?php echo json_encode(floatval($policy['p_rateAlInnah'])); ?>;
            maxFinancingAmt = <?php echo json_encode(floatval($policy['p_maxAlInnah'])); ?>;
            break;
        case 3:
            profitRate = <?php echo json_encode(floatval($policy['p_rateBPulihKenderaan'])); ?>;
            maxFinancingAmt = <?php echo json_encode(floatval($policy['p_maxBPulihKenderaan'])); ?>;
            break;
        case 4:
            profitRate = <?php echo json_encode(floatval($policy['p_rateCukaiJalanInsurans'])); ?>;
            maxFinancingAmt = <?php echo json_encode(floatval($policy['p_maxCukaiJalanInsurans'])); ?>;
            break;
        case 5:
            profitRate = <?php echo json_encode(floatval($policy['p_rateKhas'])); ?>;
            maxFinancingAmt = <?php echo json_encode(floatval($policy['p_maxKhas'])); ?>;
            break;
        case 6:
            profitRate = <?php echo json_encode(floatval($policy['p_rateKarnivalMusim'])); ?>;
            maxFinancingAmt = <?php echo json_encode(floatval($policy['p_maxKarnivalMusim'])); ?>;
            break;
        case 7:
            profitRate = <?php echo json_encode(floatval($policy['p_rateAlQadrulHassan'])); ?>;
            maxFinancingAmt = <?php echo json_encode(floatval($policy['p_maxAlQadrulHassan'])); ?>;
            break;
        default:
            profitRate = 0;
            maxFinancingAmt = 0;
            break;
    }

    if (amaunDipohon > maxFinancingAmt) {
        alert(`Amaun Dipohon telah melebihi RM ${maxFinancingAmt.toFixed(2)}! Sila masukkan amaun yang sah.`);
        document.getElementById('amaunDipohon').value = '';
        document.getElementById('ansuranBulanan').value = '0.00';
        return;
    }

    if (tempohPembiayaan > maxInstallmentPeriod1) {
        alert(`Tempoh Pembiayaan telah melebihi ${maxInstallmentPeriod1} tahun! Sila masukkan tempoh yang sah.`);
        document.getElementById('tempohPembiayaan').value = '';
        document.getElementById('ansuranBulanan').value = '0.00';
        return;
    }

    if (amaunDipohon > 0 && tempohPembiayaan > 0) {
        const totalPayable = amaunDipohon * (1 + (profitRate * tempohPembiayaan) / 100);
        const installment = totalPayable / (tempohPembiayaan * 12);

        document.getElementById('ansuranBulanan').value = installment.toFixed(2);
        document.getElementById('tunggakan').value = totalPayable.toFixed(2);
    } else {
        document.getElementById('ansuranBulanan').value = '0.00';
        document.getElementById('tunggakan').value = '0.00';
    }
}

document.getElementById('amaunDipohon').addEventListener('input', calculateInstallment);
document.getElementById('tempohPembiayaan').addEventListener('input', calculateInstallment);

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
function saveFormDataToCookies() {
    // Save all relevant inputs to cookies
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        if (input.type === 'radio') {
            // For radio buttons, save the selected value
            if (input.checked) {
                setCookie(input.name, input.value, 7);
            }
        } else {
            // For text inputs and other types, save their values
            setCookie(input.name, input.value, 7);
        }
    });
}

// Function to restore saved form data from cookies
function restoreFormDataFromCookies() {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        const cookieValue = getCookie(input.name);
        if (cookieValue !== null) {
            if (input.type === 'radio') {
                // For radio buttons, check the saved value
                if (input.value === cookieValue) {
                    input.checked = true;
                }
            } else {
                // For text inputs and other types, set the saved value
                input.value = cookieValue;
            }
        }
    });
}

// Event listener for input changes to save data to cookies
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('input', saveFormDataToCookies);
    input.addEventListener('change', saveFormDataToCookies); // For radio buttons
});

// Call restore function when the page loads
window.addEventListener('DOMContentLoaded', restoreFormDataFromCookies);

// Function to show success alert
function showSuccessNotification() {
    Swal.fire({
        title: 'Berjaya!',
        text: 'Maklumat anda telah berjaya disimpan!',
        icon: 'success',
        confirmButtonText: 'OK'
    });
}

if (window.location.search.includes('status=success')) {
    showSuccessNotification();
}


</script>

<?php include '../footer.php'; ?>

<?php
$content = ob_get_clean(); 
echo $content; 
?>