<head>
    <style>
    
      .required {
        color: red;
        font-weight: bold;
      }
          
    </style>
</head>

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

// var_dump($_SESSION['funame']);


$sql = "
    SELECT * FROM tb_policies
    ORDER BY p_policyID DESC
    LIMIT 1;";
  $result = mysqli_query($con, $sql);
  $policy = mysqli_fetch_assoc($result);

?>

<div class="">
<form method = "post" action = "semakan_pinjaman_process.php" enctype="multipart/form-data">
  <fieldset>
    <div class="container">
      <br>
      <div class="jumbotron">
        <h2>Butir-Butir Pembiayaan</h2>
        <label class="form-label mt-4">Jenis Pembiayaan<span class="required">*</span></label>
        <br>
        <select class="form-select" name="loanType" class="form-select">
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
        <input type="hidden" id="kadarKeuntungan" value="<?php echo $policy['p_profitRate']; ?>">
        <input type="hidden" id="maxFinancingAmt" value="<?php echo $policy['p_maxFinancingAmt']; ?>">
        <input type="hidden" id="maxInstallmentPeriod" value="<?php echo $policy['p_maxInstallmentPeriod']; ?>">
        <input type="hidden" name="tunggakan" id="tunggakan" value="0.00">

        <div>
          <label class="form-label mt-4">Amaun Dipohon<span class="required">*</span></label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span>
            <input type="text" name="amaunDipohon" class="form-control" id="amaunDipohon" aria-label="amaunDipohon" placeholder="0.00" value="<?php echo isset($_COOKIE['amaunDipohon']) ? $_COOKIE['amaunDipohon'] : ''; ?>" required>
          </div>
          <small id="amaunError" class="text-danger" style="display: none;">Amaun Dipohon telah melebihi maksimum: RM<?php echo number_format($policy['p_maxFinancingAmt'], 2); ?></small>
        </div>

        <div>
          <label class="form-label mt-4">Tempoh Pembiayaan<span class="required">*</span></label>
          <div class="input-group mt-2">
            <input type="text" name="tempohPembiayaan" class="form-control" id="tempohPembiayaan" aria-label="tempohPembiayaan" placeholder="0" value="<?php echo isset($_COOKIE['tempohPembiayaan']) ? $_COOKIE['tempohPembiayaan'] : ''; ?>" required>  
            <span class="input-group-text">tahun</span>

          </div>
            <small id="tempohError" class="text-danger" style="display: none;">Tempoh Pembiayaan telah melebihi maksimum: <?php echo number_format($policy['p_maxInstallmentPeriod'], 2); ?>tahun</small>
          </div>

        <div>
          <label class="form-label mt-4">Ansuran Bulanan<span class="required">*</span></label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name="ansuranBulanan" class="form-control" id="ansuranBulanan" aria-label="ansuranBulanan" placeholder="0.00" value="<?php echo isset($_COOKIE['ansuranBulanan']) ? $_COOKIE['ansuranBulanan'] : ''; ?>" required>          
          </div>  
        </div>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Sila rujuk jadual pembayaran balik pembiayaan skim </p>

        <div>
          <label class="form-label mt-4">Nama Bank/Cawangan<span class="required">*</span></label>

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
          <label class="form-label mt-4">Bank Account<span class="required">*</span></label>
          <div class="input-group mt-2">
          <input type="text" name="bankAcc" class="form-control" id="bankAcc" aria-label="bankAcc" placeholder="000000000" value="<?php echo isset($_COOKIE['bankAcc']) ? $_COOKIE['bankAcc'] : ''; ?>" required>          
        </div>  
        </div>

        <div>
          <label class="form-label mt-4">Gaji Kasar Bulanan<span class="required">*</span></label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name="gajiKasar" class="form-control" id="gajiKasar" aria-label="gajiKasar" placeholder="0.00" value="<?php echo isset($_COOKIE['gajiKasar']) ? $_COOKIE['gajiKasar'] : ''; ?>" required>          
          </div>  
        </div>

        <div>
          <label class="form-label mt-4">Gaji Bersih Bulanan<span class="required">*</span></label>
          <div class="input-group mt-2">
            <span class="input-group-text">RM</span> 
            <input type="text" name="gajiBersih" class="form-control" id="gajiBersih" aria-label="gajiBersih" placeholder="0.00" value="<?php echo isset($_COOKIE['gajiBersih']) ? $_COOKIE['gajiBersih'] : ''; ?>" required>
          </div>  
        </div>

        <div>
          <label for="fileSign" class="form-label mt-4">Tandatangan<span class="required">*</span></label>
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
const maxFinancingAmt1 = <?php echo floatval($policy['p_maxFinancingAmt']); ?>;
const maxInstallmentPeriod1 = <?php echo floatval($policy['p_maxInstallmentPeriod']); ?>;

function calculateInstallment(){
  const profitRate = parseFloat(document.getElementById('kadarKeuntungan').value) || 0;
  const amaunDipohon = parseFloat(document.getElementById('amaunDipohon').value) || 0;
  const tempohPembiayaan = parseFloat(document.getElementById('tempohPembiayaan').value) || 0;

  // Warning if amount exceeds RM 40,000
  if (amaunDipohon > maxFinancingAmt1) {
    alert(`Amaun Dipohon telah melebihi RM ${maxFinancingAmt1.toFixed(2)}! Sila masukkan amaun yang sah.`);
    document.getElementById('amaunDipohon').value = ''; 
    document.getElementById('ansuranBulanan').value = '0.00';
    document.getElementById('tunggakan').value = '0.00';
    return; 
  }

  if (tempohPembiayaan > maxInstallmentPeriod1) {
    alert(`Tempoh Pembiayaan telah melebihi ${maxInstallmentPeriod1} tahun! Sila masukkan tempoh yang sah.`);
    document.getElementById('tempohPembiayaan').value = ''; 
    document.getElementById('ansuranBulanan').value = '0.00';
    document.getElementById('tunggakan').value = '0.00';
    return; 
  }

  if(amaunDipohon > 0 && tempohPembiayaan > 0){
    const totalPayable = amaunDipohon * (1+(profitRate*tempohPembiayaan)/100);
    const installment = totalPayable/ (tempohPembiayaan*12);

    // Update the installment field
    document.getElementById('ansuranBulanan').value = installment.toFixed(2);
    document.getElementById('tunggakan').value = totalPayable.toFixed(2);

    console.log('Total Payable:', totalPayable.toFixed(2));
    console.log('Monthly Installment:', installment.toFixed(2));
    } else {
      document.getElementById('ansuranBulanan').value = '0.00';
      document.getElementById('tunggakan').value = '0.00';
    }
  }

      // Attach event listeners to inputs
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
  alert("Maklumat anda telah berjaya disimpan!");
}

if (window.location.search.includes('status=success')) {
    showSuccessNotification();
  }


</script>

<?php include '../footer.php'; ?>