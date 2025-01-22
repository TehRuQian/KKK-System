<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}
if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
}

include '../header_admin.php';
include '../db_connect.php';

// Get application ID
$lApplicationID = $_GET['id'];

if ($lApplicationID === 0) {
    echo "<script>alert('ID aplikasi tidak sah.'); window.location.href = 'loan_approval.php';</script>";
    exit;
}

// Retrieve loan details
$sqlLoan = "SELECT l.*, m.m_name, m.m_pfNo, lt.lt_desc, b.lb_id, b.lb_desc
            FROM tb_loan l
            LEFT JOIN tb_member m ON l.l_memberNo = m.m_memberNo
            LEFT JOIN tb_ltype lt ON l.l_loanType = lt.lt_lid
            LEFT JOIN tb_lbank b ON l.l_bankName = b.lb_id
            WHERE l.l_loanApplicationID = '$lApplicationID'";

$resultLoan = mysqli_query($con, $sqlLoan);
if ($rowLoan = mysqli_fetch_assoc($resultLoan)) {
    $guarantorID1 = null;
    $guarantorID2 = null;

    // Fetch the guarantors for this loan application
    $sqlGuarantors = "SELECT g.g_memberNo, g.g_signature FROM tb_guarantor g WHERE g.g_loanApplicationID = '$lApplicationID' LIMIT 2";
    $resultGuarantors = mysqli_query($con, $sqlGuarantors);

    // Assuming there are two guarantors, fetch their member IDs and signatures
    $guarantors = mysqli_fetch_all($resultGuarantors, MYSQLI_ASSOC);
    if (count($guarantors) == 2) {
        $guarantorID1 = $guarantors[0]['g_memberNo'];
        $guarantorID2 = $guarantors[1]['g_memberNo'];
        $guarantor1Signature = $guarantors[0]['g_signature'];
        $guarantor2Signature = $guarantors[1]['g_signature'];
    }

    // Fetch member details for guarantor 1
    $sqlGuarantor1 = "SELECT m.m_name, m.m_ic, m.m_pfNo, m.m_memberNo FROM tb_member m WHERE m.m_memberNo = '$guarantorID1'";
    $resultGuarantor1 = mysqli_query($con, $sqlGuarantor1);
    $guarantor1 = mysqli_fetch_assoc($resultGuarantor1);

    // Fetch member details for guarantor 2
    $sqlGuarantor2 = "SELECT m.m_name, m.m_ic, m.m_pfNo, m.m_memberNo FROM tb_member m WHERE m.m_memberNo = '$guarantorID2'";
    $resultGuarantor2 = mysqli_query($con, $sqlGuarantor2);
    $guarantor2 = mysqli_fetch_assoc($resultGuarantor2);
} else {
    echo "Tiada data ditemui untuk ID aplikasi tersebut.";
    exit;
}

$basePath = $_SERVER['DOCUMENT_ROOT'] . '/KKK-System/loan_application/uploads/';

$selected_signature = $basePath . trim($rowLoan['l_signature']);
$selected_file = $basePath . trim($rowLoan['l_file']);

?>

<div class="container">
    <h2 class="mb-4">Maklumat Peminjam</h2>

    <!-- Loan Applicant Details -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
            Maklumat Peribadi Pemohon
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr><th>No. Aplikasi Pinjaman</th><td><?php echo $rowLoan['l_loanApplicationID']; ?></td></tr>
                <tr><th>No. Anggota</th><td><?php echo $rowLoan['l_memberNo']; ?></td></tr>
                <tr><th>Nama Peminjam</th><td><?php echo $rowLoan['m_pfNo']; ?></td></tr>
                <tr><th>Nama Anggota</th><td><?php echo $rowLoan['m_name']; ?></td></tr>
                <tr><th>Jenis Pinjaman</th><td><?php echo $rowLoan['lt_desc']; ?></td></tr>
                <tr><th>Jumlah Pinjaman</th><td><?php echo $rowLoan['l_appliedLoan']; ?></td></tr>
                <tr><th>Tempoh Pinjaman</th><td><?php echo $rowLoan['l_loanPeriod']; ?></td></tr>
                <tr><th>Ansuran Bulanan</th><td><?php echo $rowLoan['l_monthlyInstalment']; ?></td></tr>
                <tr><th>Akaun Bank</th><td><?php echo $rowLoan['l_bankAccountNo']; ?></td></tr>
                <tr><th>Nama Bank</th><td><?php echo $rowLoan['lb_desc'] ?? 'N/A'; ?></td></tr>
                <tr><th>Gaji Kasar</th><td><?php echo $rowLoan['l_monthlyGrossSalary']; ?></td></tr>
                <tr><th>Gaji Bersih</th><td><?php echo $rowLoan['l_monthlyNetSalary']; ?></td></tr>
                <tr>
                    <th>Tandatangan</th>
                    <td>
                        <?php
                        $signature_url = '../loan_application/uploads/' . basename($selected_signature);
                        if (file_exists($selected_signature)) : ?>
                            <img src="<?php echo $signature_url; ?>" alt="Signature" style="max-width: 200px; height: auto;">
                        <?php else : ?>
                            <span>Tiada tandatangan.</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Pengesahan Majikan</th>
                    <td>
                        <?php
                        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/KKK-System/loan_application/uploads/' . basename($selected_file);
                        $pdf_url = 'http://' . $_SERVER['HTTP_HOST'] . '/KKK-System/loan_application/uploads/' . basename($selected_file);

                        if (file_exists($file_path)) : ?>
                            <a href="<?php echo $pdf_url; ?>" class="btn btn-primary" target="_blank">
                                <i class="fas fa-external-link"></i> Lihat
                            </a>
                        <?php else : ?>
                            <span>Tiada dokumen PDF.</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr><th>Tarikh Pohon</th><td><?php echo $rowLoan['l_applicationDate']; ?></td></tr>
            </table>
        </div>
    </div>

    <!-- Guarantor 1 Details -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
            Maklumat Penjamin 1
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr><th>No. Anggota</th><td><?php echo $guarantor1['m_memberNo'] ?? 'N/A'; ?></td></tr>
                <tr><th>Nama Penjamin</th><td><?php echo $guarantor1['m_name'] ?? 'N/A'; ?></td></tr>
                <tr><th>No. Kad Pengenalan</th><td><?php echo $guarantor1['m_ic'] ?? 'N/A'; ?></td></tr>
                <tr><th>No. PF</th><td><?php echo $guarantor1['m_pfNo'] ?? 'N/A'; ?></td></tr>
                <tr>
                    <th>Tandatangan Penjamin 1</th>
                    <td>
                        <?php if (!empty($guarantor1Signature)) : ?>
                            <img src="../loan_application/uploads/<?php echo basename($guarantor1Signature); ?>" alt="Signature" style="max-width: 200px; height: auto;">
                        <?php else : ?>
                            <span>Tiada tandatangan.</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Guarantor 2 Details -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
            Maklumat Penjamin 2
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr><th>No. Anggota</th><td><?php echo $guarantor2['m_memberNo'] ?? 'N/A'; ?></td></tr>
                <tr><th>Nama Penjamin</th><td><?php echo $guarantor2['m_name'] ?? 'N/A'; ?></td></tr>
                <tr><th>No. Kad Pengenalan</th><td><?php echo $guarantor2['m_ic'] ?? 'N/A'; ?></td></tr>
                <tr><th>No. PF</th><td><?php echo $guarantor2['m_pfNo'] ?? 'N/A'; ?></td></tr>
                <tr>
                    <th>Tandatangan Penjamin 2</th>
                    <td>
                        <?php if (!empty($guarantor2Signature)) : ?>
                            <img src="../loan_application/uploads/<?php echo basename($guarantor2Signature); ?>" alt="Signature" style="max-width: 200px; height: auto;">
                        <?php else : ?>
                            <span>Tiada tandatangan.</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>


<form method="POST" action="loan_approval_process.php" onsubmit="return validateForm()"> 
    <input type="hidden" name="lApplicationID" value="<?php echo $lApplicationID; ?>">
    <fieldset>
        <div class="container" style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
            <label class="form-label mt-4" style="justify-content: center">Status Anggota</label>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Pilih Status
                </button>
                <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                    <?php
                    $sql = "SELECT * FROM tb_status WHERE s_sid IN (2, 3)";
                    $result = mysqli_query($con, $sql);

                    while ($rowStatus = mysqli_fetch_array($result)) {
                        $selected = ($rowStatus['s_sid'] == 1) ? 'selected' : '';
                        echo "<li><a class='dropdown-item' href='#' onclick='setStatus(event, ".$rowStatus['s_sid'].", \"".$rowStatus['s_desc']."\")'>".$rowStatus['s_desc']."</a></li>";
                    }
                    ?>
                </ul>
            </div>    
            <br>

            <?php // To store the status ?>
            <input type="hidden" name="lstatus" id="lstatus" value="1" />

            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="btn btn-primary" onclick="window.location.href='loan_approval.php'">Kembali</button>
                <button type="submit" class="btn btn-primary">Hantar</button>
            </div>
        </div>
    </fieldset>
</form>
<br>
<script>
function setStatus(event, status, statusDesc) {
    event.preventDefault();  // Prevent the page from scrolling up when changing different status

    // Set the hidden input to the selected status
    document.getElementById('lstatus').value = status;

    // Change the button text and colour
    const statusButton = document.getElementById('statusDropdown');
    statusButton.textContent = statusDesc;

    if (status == 1) {
        statusButton.classList.remove('btn-warning', 'btn-danger', 'btn-success');
        statusButton.classList.add('btn-secondary');  // Sedang Diproses
    } else if (status == 2) {
        statusButton.classList.remove('btn-secondary', 'btn-success', 'btn-warning');
        statusButton.classList.add('btn-danger');  // Ditolak
    } else if (status == 3) {
        statusButton.classList.remove('btn-secondary', 'btn-danger', 'btn-warning');
        statusButton.classList.add('btn-success');  // Diluluskan
    }
}

function validateForm() {
    const status = document.getElementById('lstatus').value;

    if (status == 2) {
        return confirm("Adakah anda pasti untuk menolak permohonan ini?");
    } else if (status == 3) {
        return confirm("Adakah anda pasti mahu meluluskan permohonan ini?");
    }
    return true; 
}

setStatus(null, 1, "Sedang Diproses");  // Default status
</script>

