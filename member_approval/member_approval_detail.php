<?php 
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Get application ID
$mApplicationID = $_GET['id'];

if ($mApplicationID === 0) {
    echo "<script>alert('ID aplikasi tidak sah.'); window.location.href = 'member_approval.php';</script>";
    exit;
}

// Retrieve member details
$sql = "SELECT * FROM tb_member 
        LEFT JOIN tb_status ON tb_member.m_status = tb_status.s_sid
        LEFT JOIN tb_ugender ON tb_member.m_gender = tb_ugender.ug_gid
        LEFT JOIN tb_ureligion ON tb_member.m_religion = tb_ureligion.ua_rid
        LEFT JOIN tb_urace ON tb_member.m_race = tb_urace.ur_rid
        LEFT JOIN tb_umaritalstatus ON tb_member.m_maritalStatus = tb_umaritalstatus.um_mid
        WHERE tb_member.m_memberApplicationID = '$mApplicationID'";

// Execute the SQL statement on DB
$result = mysqli_query($con, $sql);

if ($result && $row = mysqli_fetch_array($result)) {
    // Member data successfully retrieved
} else {
    echo "Tiada data ditemui untuk ID aplikasi tersebut.";
    exit;
}

// Fetch all existing member numbers
$memberNumbers = [];
$sql = "SELECT m_memberNo FROM tb_member WHERE m_memberNo IS NOT NULL";
$result = mysqli_query($con, $sql);

// Loop to extract all m_memberNo from query (checking existing member purpose)
while ($rowMember = mysqli_fetch_assoc($result)) {
    $memberNumbers[] = $rowMember['m_memberNo'];
}

// Pass the data as a JavaScript array (checking existing member purpose)
echo "<script>const existingMemberNumbers = " . json_encode($memberNumbers) . ";</script>";

// Suggested Member No for new member
$suggestedMemberNo = null;
$sql = "SELECT m_memberNo FROM tb_member WHERE m_memberNo IS NOT NULL ORDER BY m_memberNo DESC LIMIT 1";
$result = mysqli_query($con, $sql);
$lastMemberNo = 0;

if ($result && $lastRow = mysqli_fetch_array($result)) {
    $lastMemberNo = $lastRow['m_memberNo'];
}
$suggestedMemberNo = $lastMemberNo + 1;

// Fetch heir information
$sqlHeirs = "SELECT * FROM tb_heir 
LEFT JOIN tb_hrelation ON tb_heir.h_relationWithMember = tb_hrelation.hr_rid
WHERE h_memberApplicationID = '{$row['m_memberApplicationID']}'";

$resultHeirs = mysqli_query($con, $sqlHeirs);
$heirs = mysqli_fetch_all($resultHeirs, MYSQLI_ASSOC);
while ($heir = mysqli_fetch_assoc($resultHeirs)) {
print_r($heir);
}
?>

<div class="container">
<h2>Maklumat Pemohon</h2>

<div class="card mb-3 col-10 my-5 mx-auto">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Maklumat Peribadi Pemohon
      </div>
      <div class="card-body">
            <table class="table table-hover">
                <tr><th>No. Aplikasi Anggota</th><td><?php echo $row['m_memberApplicationID']; ?></td></tr>
                <tr><th>No. PF</th><td><?php echo $row['m_pfNo']; ?></td></tr>
                <tr><th>Nama Anggota</th><td><?php echo $row['m_name']; ?></td></tr>
                <tr><th>No. Kad Pengenalan</th><td><?php echo $row['m_ic']; ?></td></tr>
                <tr><th>Jantina</th><td><?php echo $row['ug_desc']; ?></td></tr>
                <tr><th>Agama</th><td><?php echo $row['ua_desc']; ?></td></tr>
                <tr><th>Bangsa</th><td><?php echo $row['ur_desc']; ?></td></tr>
                <tr><th>Status Perkahwinan</th><td><?php echo $row['um_desc']; ?></td></tr>
                <tr><th>Alamat Rumah</th><td><?php echo $row['m_homeAddress']; ?></td></tr>
                <tr><th>No. Telefon</th><td><?php echo $row['m_phoneNumber']; ?></td></tr>
                <tr><th>Email</th><td><?php echo $row['m_email']; ?></td></tr>
                <tr><th>No. Telefon Rumah</th><td><?php echo !empty($row['m_homeNumber']) ? $row['m_homeNumber'] : 'N/A'; ?></td></tr>
                <tr><th>Jawatan</th><td><?php echo $row['m_position']; ?></td></tr>
                <tr><th>Gred</th><td><?php echo $row['m_positionGrade']; ?></td></tr>
                <tr><th>Alamat Pejabat</th><td><?php echo $row['m_officeAddress']; ?></td></tr>
                <tr><th>Gaji Bulanan</th><td><?php echo $row['m_monthlySalary']; ?></td></tr>
                <tr><th>Tarikh Pohon:</th><td><?php echo date('d-m-Y H:i:s', strtotime($row['m_applicationDate'])); ?></td></tr>
            </table>
        </div>
</div>
<div class="card mb-3 col-10 my-5 mx-auto">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
    Maklumat Saham Pemohon
      </div>
      <div class="card-body">
        <table class="table table-hover">
            <th>Fee Masuk</th><td><?php echo $row['m_feeMasuk']; ?></td></tr>
            <tr><th>Modal Yuran</th><td><?php echo $row['m_modalYuran']; ?></td></tr>
            <tr><th>Deposit</th><td><?php echo $row['m_deposit']; ?></td></tr>
            <tr><th>alAbrar</th><td><?php echo $row['m_alAbrar']; ?></td></tr>
            <tr><th>Simpanan Tetap</th><td><?php echo $row['m_simpananTetap']; ?></td></tr>
            <tr><th>Fee Lain</th><td><?php echo $row['m_feeLain']; ?></td></tr>
    </table>
    </div>
</div>

<div class="card mb-3 col-10 my-5 mx-auto">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Maklumat Pewaris
    </div>
    <div class="card-body">
        <?php if (!empty($heirs)) { ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Pewaris</th>
                    <th>No. Kad Pengenalan</th>
                    <th>Hubungan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($heirs as $heir) { ?>
                <tr>
                    <td><?php echo $heir['h_name']; ?></td>
                    <td><?php echo $heir['h_ic']; ?></td>
                    <td><?php echo $heir['hr_desc']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <p>Tiada maklumat pewaris.</p>
        <?php } ?>
    </div>
</div>

<form method="POST" action="member_approval_process.php" onsubmit="return validateForm()">
    <input type="hidden" name="mApplicationID" value="<?php echo $mApplicationID; ?>">
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
        <input type="hidden" name="mstatus" id="mstatus" value="1" />

        <?php // To display the suggested memberNo ?>
        <div id="memberNoContainer" style="display:none;">
            <label for="mMemberNo">Member No:</label>
            <input type="text" class="form-control" id="mMemberNo" name="mMemberNo" value="<?php echo $suggestedMemberNo; ?>" />
            <small>Suggested: <?php echo $suggestedMemberNo; ?></small>
        </div><br>
    </div>

        <div style="display: flex; gap: 10px; justify-content: center;">
            <button type="button" class="btn btn-primary" onclick="window.location.href='member_approval.php'">Kembali</button>
            <button type="submit" class="btn btn-primary">Hantar</button>
        </div>
    </fieldset>
</form>
<br>

<script>
function setStatus(event, status, statusDesc) {
    event.preventDefault();  // Prevent the page from scrolling up
    console.log('Selected status:', status); // Debugging line

    // Set the hidden input to the selected status
    document.getElementById('mstatus').value = status;

    // Change the button text and colour
    const statusButton = document.getElementById('statusDropdown');
    statusButton.textContent = statusDesc;

    // Add or remove classes based on status
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

    // Toggle the visibility of the member number input field based on the selected status
    toggleMemberNoInput(status);
}


function toggleMemberNoInput(status) {
    const memberNoContainer = document.getElementById('memberNoContainer');

    if (status == 3) { 
        memberNoContainer.style.display = 'block';
        document.getElementById('mMemberNo').required = true;
    } else {
        memberNoContainer.style.display = 'none';
        document.getElementById('mMemberNo').required = false;
    }
}

function validateForm() {
    const status = document.getElementById('mstatus').value;
    const memberNo = document.getElementById('mMemberNo').value;

    if (status == 3 && memberNo) {
        if (existingMemberNumbers.includes(memberNo)) {
            alert("No. Anggota sudah wujud! Sila masukkan No. yang berbeza.");
            return false; 
        }
    }
    return confirmSubmission();
}

function confirmSubmission() {
    const status = document.getElementById('mstatus').value;

    if (status == 2) {
        return confirm("Adakah anda pasti untuk menolak permohonan ini?");
    } else if (status == 3) {
        return confirm("Adakah anda pasti mahu meluluskan permohonan ini?");
    }
    return true; 
}

setStatus(null, 1, "Sedang Diproses");
</script>
