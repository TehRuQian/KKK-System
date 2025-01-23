<head>   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}
if ($_SESSION['u_type'] != 2) {
  header('Location: ../login.php');
  exit();
}

include '../headermember.php';
include '../db_connect.php';

//Loan


$loanApplicationID = $_SESSION['loanApplicationID']; // Retrieve from session
$memberNo = $_SESSION['funame'];

if (!isset($_SESSION['loanApplicationID'])) {
  echo "<script>
  Swal.fire({
    title: 'Peringatan',
    text: 'Sila simpan maklumat Butir-Butir Pembiayaan.',
    icon: 'warning',
    confirmButtonText: 'OK'
  }).then(() => {
    window.location.href = 'a_pinjaman.php';
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
          <a href="c_pengakuan_pemohon.php" class="text-center">Pengakuan Pemohon<br></a>
          <hr>
        </div>
        <div class="row">
          <a href="d_penjamin.php" class="text-center active">Butir-Butir Penjamin<br></a>
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


<div class="container-fluid">
    
    <div class="col-10 main-content">
<form method = "post" action = "d_penjamin_process.php"  enctype="multipart/form-data">
  <fieldset>
    <div class="container">
      <br>
      <div class="jumbotron">
        <h2>Butir-Butir Penjamin</h2>

        <!-- Penjamin 1 -->
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Penjamin 1" readonly style="background-color: #6a9bf0; color: #00000;">
        </div>

        <div>
          <label class="form-label mt-4">No. Anggota</label>
          <div class="input-group mt-2">
            <input type="text" name = "anggotaPenjamin1" class="form-control" id="anggotaPenjamin1" aria-label="anggotaPenjamin1" required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">Nama</label>
          <div class="input-group mt-2">
            <input type="text" name = "namaPenjamin1" class="form-control" id="namaPenjamin1" aria-label="namaPenjamin1" placeholder="Ali bin Abu" readonly>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">No. Kad Pengenalan</label>
          <div class="input-group mt-2">
            <input type="text" name = "icPenjamin1" class="form-control" id="icPenjamin1" aria-label="icPenjamin1" placeholder="000000-00-0000" readonly>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">No. PF</label>
          <div class="input-group mt-2">
            <input type="text" name = "pfPenjamin1" class="form-control" id="pfPenjamin1" aria-label="pfPenjamin1" placeholder="1001"  reaonly >
          </div>
        </div>

        <div>
          <label for="fileSignPenjamin1" class="form-label mt-4">Tandatangan</label>
          <input class="form-control" type="file" id="fileSignPenjamin1" name="fileSignPenjamin1" accept=".png, .jpg, .jpeg" required>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Fail yang dibenarkan adalah dalam format PNG, JPG, dan JPEG sahaja. Sila pastikan saiz fail tidak melebihi 5MB.</p>
        </div>

      <!-- Penjamin 2 -->
      <div class="input-group mt-4">
            <input type="text" class="form-control" placeholder="Penjamin 2" readonly style="background-color: #6a9bf0; color: #00000;">
        </div>

        <div>
          <label class="form-label mt-4">No. Anggota</label>
          <div class="input-group mt-2">
            <input type="text" name="anggotaPenjamin2" class="form-control" id="anggotaPenjamin2" aria-label="anggotaPenjamin2" required>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">Nama</label>
          <div class="input-group mt-2">
            <input type="text" name="namaPenjamin2" class="form-control" id="namaPenjamin2" aria-label="namaPenjamin2" placeholder="Abu bin Ali" readonly>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">No. Kad Pengenalan</label>
          <div class="input-group mt-2">
            <input type="text" name="icPenjamin2" class="form-control" id="icPenjamin2" aria-label="icPenjamin2" placeholder="000000-00-0000" readonly>
          </div>
        </div>

        <div>
          <label class="form-label mt-4">No. PF</label>
          <div class="input-group mt-2">
            <input type="text" name="pfPenjamin2" class="form-control" id="pfPenjamin2" aria-label="pfPenjamin2" placeholder="1002" reaonly >
          </div>
        </div>

        <div>
          <label for="fileSignPenjamin2" class="form-label mt-4">Tandatangan</label>
          <input class="form-control" type="file" id="fileSignPenjamin2" name="fileSignPenjamin2" accept=".png, .jpg, .jpeg" required>
          <p class="mt-2" style="font-size: 0.9rem; color: #6c757d;">*Fail yang dibenarkan adalah dalam format PNG, JPG, dan JPEG sahaja. Sila pastikan saiz fail tidak melebihi 5MB.</p>
        </div>

        <div style="text-align: center;">
            <br>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>   
  </fieldset>
</form>                                 
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
      $('<style>')
        .text(`
            .readonly-field {
                background-color: #e9ecef !important;
                cursor: not-allowed;
            }
        `)
        .appendTo('head');

        $('#namaPenjamin1, #icPenjamin1, #pfPenjamin1, #namaPenjamin2, #icPenjamin2, #pfPenjamin2')
        .addClass('readonly-field');

  
    $('#anggotaPenjamin1').on('change', function() {
        var anggotaPenjamin1 = $(this).val().trim();
        console.log('Input member number:', anggotaPenjamin1);
        
        if (anggotaPenjamin1 === '<?php echo $memberNo; ?>') {
            Swal.fire({
                title: 'Peringatan',
                text: 'Penjamin 1 tidak boleh sama dengan anggota yang sedang log masuk.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            $('#namaPenjamin1, #icPenjamin1, #pfPenjamin1').val('');
            return;
        }

        var anggotaPenjamin2 = $('#anggotaPenjamin2').val().trim();
        if (anggotaPenjamin1 === anggotaPenjamin2 && anggotaPenjamin2 !== '') {
            Swal.fire({
                title: 'Peringatan',
                text: 'Penjamin 1 tidak boleh sama dengan Penjamin 2.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            $('#namaPenjamin1, #icPenjamin1, #pfPenjamin1').val('');
            return;
        }

        if (anggotaPenjamin1 !== '') {
            
            $('#namaPenjamin1, #icPenjamin1, #pfPenjamin1')
                .val('Sedang memuat...')
                .prop('disabled', true);
            
            $.ajax({
                url: 'fetch_member1.php',
                type: 'POST',
                data: { anggotaPenjamin1: anggotaPenjamin1 },
                dataType: 'json',
                success: function(response) {
                    console.log('Server response:', response);
                    
                    if (response.status === 'success' && response.penjamin1) {
                        if (response.penjamin1.m_no === '<?php echo $memberNo; ?>') {
                            alert('Penjamin 1 tidak boleh sama dengan anggota yang sedang log masuk.');
                            $('#namaPenjamin1, #icPenjamin1, #pfPenjamin1').val('');
                        } else {
                            $('#namaPenjamin1').val(response.penjamin1.m_name);
                            $('#icPenjamin1').val(response.penjamin1.m_ic);
                            $('#pfPenjamin1').val(response.penjamin1.m_pfNo);
                        }
                    } else {
                        $('#namaPenjamin1, #icPenjamin1, #pfPenjamin1').val('');
                        console.log('Debug info:', response.debug);
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    $('#namaPenjamin1, #icPenjamin1, #pfPenjamin1').val('');
                    alert('Error: ' + error);
                },
            });

        }
    });

    
    $('#anggotaPenjamin2').on('change', function() {
        var anggotaPenjamin2 = $(this).val().trim();
        
        if (anggotaPenjamin2 === '<?php echo $memberNo; ?>') {
            Swal.fire({
                title: 'Peringatan',
                text: 'Penjamin 2 tidak boleh sama dengan anggota yang sedang log masuk.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            $('#namaPenjamin2, #icPenjamin2, #pfPenjamin2').val('');
            return;
        }

        var anggotaPenjamin1 = $('#anggotaPenjamin1').val().trim();
        if (anggotaPenjamin2 === anggotaPenjamin1 && anggotaPenjamin1 !== '') {
            Swal.fire({
                title: 'Peringatan',
                text: 'Penjamin 2 tidak boleh sama dengan Penjamin 1.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            $('#namaPenjamin2, #icPenjamin2, #pfPenjamin2').val('');
            return;
        }

        if (anggotaPenjamin2 !== '') {
            
            $('#namaPenjamin2, #icPenjamin2, #pfPenjamin2')
                .val('Sedang memuat...')
                .prop('disabled', true);
            
            $.ajax({
                url: 'fetch_member1.php',
                type: 'POST',
                data: { anggotaPenjamin2: anggotaPenjamin2 },
                dataType: 'json',
                success: function(response) {
                    console.log('Server response:', response);
                    
                    if (response.status === 'success' && response.penjamin2) {
                        if (response.penjamin2.m_no === '<?php echo $memberNo; ?>') {
                            alert('Penjamin 2 tidak boleh sama dengan anggota yang sedang log masuk.');
                            $('#namaPenjamin2, #icPenjamin2, #pfPenjamin2').val('');
                        } else {
                            $('#namaPenjamin2').val(response.penjamin2.m_name);
                            $('#icPenjamin2').val(response.penjamin2.m_ic);
                            $('#pfPenjamin2').val(response.penjamin2.m_pfNo);
                        }
                    } else {
                        $('#namaPenjamin2, #icPenjamin2, #pfPenjamin2').val('');
                        alert(response.message || 'Tiada maklumat anggota dijumpai');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    $('#namaPenjamin2, #icPenjamin2, #pfPenjamin2').val('');
                    alert('Ralat semasa mendapatkan maklumat anggota. Sila cuba lagi.');
                },
                complete: function() {
                    $('#namaPenjamin2, #icPenjamin2, #pfPenjamin2').prop('disabled', false);
                }
            });
        } else {
            $('#namaPenjamin2, #icPenjamin2, #pfPenjamin2').val('');
        }
    });
});

</script>



<?php include '../footer.php'; ?>