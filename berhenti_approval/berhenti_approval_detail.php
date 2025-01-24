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

// Get application ID (berhentiID)
$td_tarikdiriID = $_GET['berhentiID'] ?? '';

if (empty($td_tarikdiriID)) {
    echo "<script>alert('ID aplikasi tidak sah.'); window.location.href = 'berhenti_approval.php';</script>";
    exit;
}

// Get member number (memberNo)
$td_memberNo = $_GET['memberNo'] ?? '';

if (empty($td_memberNo)) {
    echo "<script>alert('Member number is missing.'); window.location.href = 'berhenti_approval.php';</script>";
    exit;
}

// Retrieve tarik data
$sql1 = "SELECT tb_tarikdiri.* FROM tb_tarikdiri WHERE tb_tarikdiri.td_memberNo = ?";
$stmt1 = $con->prepare($sql1);
$stmt1->bind_param("s", $td_memberNo);
$stmt1->execute();
$result1 = $stmt1->get_result();

if ($result1 && $tarik = $result1->fetch_assoc()) {
    // Tarik diri data successfully retrieved
} else {
    echo "Tiada data ditemui untuk ID aplikasi tersebut.";
    exit;
}

$stmt1->close();

// Retrieve member details
$sql2 = "SELECT * FROM tb_member 
        LEFT JOIN tb_status ON tb_member.m_status = tb_status.s_sid
        LEFT JOIN tb_ugender ON tb_member.m_gender = tb_ugender.ug_gid
        LEFT JOIN tb_ureligion ON tb_member.m_religion = tb_ureligion.ua_rid
        LEFT JOIN tb_urace ON tb_member.m_race = tb_urace.ur_rid
        LEFT JOIN tb_umaritalstatus ON tb_member.m_maritalStatus = tb_umaritalstatus.um_mid
        LEFT JOIN tb_homeState ON tb_member.m_homeState = tb_homeState.st_id
        LEFT JOIN tb_officeState ON tb_member.m_officeState = tb_officeState.st_id
        WHERE tb_member.m_memberNo = ?";

$stmt2 = $con->prepare($sql2);
$stmt2->bind_param("s", $td_memberNo);
$stmt2->execute();
$result2 = $stmt2->get_result();

if ($result2 && $member = $result2->fetch_assoc()) {
    // Member details successfully retrieved
} else {
    echo "Tiada data ditemui untuk member number tersebut.";
    exit;
}

$stmt2->close();

// Retrieve loan details
$sql3 = "SELECT * FROM tb_loan
         LEFT JOIN tb_status ON tb_loan.l_status = tb_status.s_sid
         LEFT JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
         WHERE tb_loan.l_memberNo = ?";

$stmt3 = $con->prepare($sql3);
$stmt3->bind_param("s", $td_memberNo);
$stmt3->execute();
$result3 = $stmt3->get_result();

// Check if loan exists
$loanExists = false;
if ($result3 && $loan = $result3->fetch_assoc()) {
    $loanExists = true;  // Loan data exists
}

$stmt3->close();
?>

<div class="container">
<h2>Maklumat Pemohon</h2>

<!-- Applicant Details -->
<div class="card mb-3 col-10 my-5 mx-auto">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Maklumat Peribadi Pemohon
      </div>
      <div class="card-body">
            <table class="table table-hover">
                <tr><td>No. Aplikasi Anggota</td><td><?php echo $member['m_memberApplicationID']; ?></td></tr>
                <tr><td>No. PF</td><td><?php echo $member['m_pfNo']; ?></td></tr>
                <tr><td>Nama Anggota</td><td><?php echo $member['m_name']; ?></td></tr>
                <tr><td>No. Kad Pengenalan</td><td><?php echo $member['m_ic']; ?></td></tr>
                <tr><td>No. Telefon</td><td><?php echo $member['m_phoneNumber']; ?></td></tr>
                <tr><td>Email</td><td><?php echo $member['m_email']; ?></td></tr>
                <tr><td>Jawatan</td><td><?php echo $member['m_position']; ?></td></tr>
                <tr><td>Gred</td><td><?php echo $member['m_positionGrade']; ?></td></tr>
                <tr><td>Gaji Bulanan (RM)</td><td><?php echo number_format($member['m_monthlySalary'], 2); ?></td></tr>
            </table>
        </div>
</div>

<!-- Applicant Shares Details -->
<div class="card mb-3 col-10 my-5 mx-auto">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
    Maklumat Saham Pemohon
      </div>
      <div class="card-body">
        <table class="table table-hover">
            <td>Fee Masuk (RM)</td><td><?php echo number_format($member['m_feeMasuk'], 2); ?></td></tr>
            <tr><td>Modal Yuran (RM)</td><td><?php echo number_format($member['m_modalYuran'], 2); ?></td></tr>
            <tr><td>Deposit (RM)</td><td><?php echo number_format($member['m_deposit'], 2); ?></td></tr>
            <tr><td>alAbrar (RM)</td><td><?php echo number_format($member['m_alAbrar'], 2); ?></td></tr>
            <tr><td>Simpanan Tetap (RM)</td><td><?php echo number_format($member['m_simpananTetap'], 2); ?></td></tr>
            <tr><td>Fee Lain (RM)</td><td><?php echo number_format($member['m_feeLain'], 2); ?></td></tr>
    </table>
    </div>
</div>

<?php if ($loanExists): ?>
<!-- Applicant Loan Details -->
<div class="card mb-3 col-10 my-5 mx-auto">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
    Maklumat Pinjaman Pemohon
      </div>
      <div class="card-body">
        <table class="table table-hover">
        <tr><td>Status Pinjaman</td><td><?php echo $loan['s_desc']; ?></td></tr>
        <tr><td>No. Aplikasi Pinjaman</td><td><?php echo $loan['l_loanApplicationID']; ?></td></tr>
        <tr><td>Jenis Pinjaman</td><td><?php echo $loan['lt_desc']; ?></td></tr>
        <tr><td>Jumlah Pinjaman (RM)</td><td><?php echo number_format($loan['l_appliedLoan'], 2); ?></td></tr>
        <tr><td>Tempoh Pinjaman (Bulan)</td><td><?php echo $loan['l_loanPeriod']; ?></td> </tr>
        <tr><td>Ansuran Bulanan (RM)</td><td><?php echo number_format($loan['l_monthlyInstalment'], 2); ?></td></tr>
        <tr><td>Tunggakan (RM)</td><td><?php echo number_format($loan['l_loanPayable'], 2); ?></td></tr>
    </table>
    </div>
</div>
<?php endif; ?>

<!-- Applicant Stop Details -->
<div class="card mb-3 col-10 my-5 mx-auto">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
    Alasan Pemohon
      </div>
      <div class="card-body">
        <?php echo $tarik['td_alasan']; ?>
    </div>
</div>

<form method="POST" action="berhenti_approval_process.php" onsubmit="return confirmSubmission(event)">
    <input type="hidden" name="tdApplicationID" value="<?php echo $td_tarikdiriID; ?>">
    <fieldset>
    <div class="container" style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <label class="form-label mt-4" style="justify-content: center">Status</label>
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
        <input type="hidden" name="bstatus" id="bstatus" value="1" />

        <?php // To prompt admin to enter reason ?>
        <div id="ulasanContainer" style="display:none; width: 50%;">
            <label for="tdUlasan">Ulasan :</label>
            <textarea class="form-control" id="tdUlasan" name="tdUlasan" rows="5" required></textarea>
        </div><br>
    </div>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <button type="button" class="btn btn-primary" onclick="window.location.href='berhenti_approval.php'">Kembali</button>
            <button type="submit" class="btn btn-primary">Hantar</button>
        </div>
    </fieldset>
</form>
<br>

<script>
function setStatus(event, status, statusDesc) {
    event.preventDefault();  // Prevent the page from scrolling up
    console.log('Selected status:', status); 

    // Set the hidden input to the selected status
    document.getElementById('bstatus').value = status;

    // Change the button text and colour
    const statusButton = document.getElementById('statusDropdown');
    statusButton.textContent = statusDesc;

    if (status == 1) {
        statusButton.classList.remove('btn-warning', 'btn-danger', 'btn-success');
        statusButton.classList.add('btn-secondary');
    } else if (status == 2) {
        statusButton.classList.remove('btn-secondary', 'btn-success', 'btn-warning');
        statusButton.classList.add('btn-danger');
    } else if (status == 3) {
        statusButton.classList.remove('btn-secondary', 'btn-danger', 'btn-warning');
        statusButton.classList.add('btn-success');
    }

    toggleUlasanInput(status);
}

function toggleUlasanInput(status) {
    const memberNoContainer = document.getElementById('ulasanContainer');

    if (status == 2) { 
        memberNoContainer.style.display = 'block';
        document.getElementById('tdUlasan').required = true;
    } else {
        memberNoContainer.style.display = 'none';
        document.getElementById('tdUlasan').required = false;
    }
}

function confirmSubmission(event) {
    const status = document.getElementById('tdUlasan').value;

    if (status == 2) {
        // Reject confirmation
        Swal.fire({
            title: 'Adakah anda pasti?',
            text: "Anda pasti untuk menolak permohonan berhenti ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('form').submit();
            }
        });
    } else if (status == 3) {
        // Approve confirmation
        Swal.fire({
            title: 'Adakah anda pasti?',
            text: "Anda pasti untuk meluluskan permohonan berhenti ini?",
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Ya, Luluskan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('form').submit();
            }
        });
    }
}
</script>

