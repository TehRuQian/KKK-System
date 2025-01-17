<?php 
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Get application ID
$mApplicationID = $_GET['id'];

if ($lApplicationID === 0) {
    echo "<script>alert('ID aplikasi tidak sah.'); window.location.href = 'loan_approval.php';</script>";
    exit;
}

// Retrieve member details
$sql = "SELECT * FROM tb_member 
        LEFT JOIN tb_status ON tb_member.m_status = tb_status.s_sid
        LEFT JOIN tb_ugender ON tb_member.m_gender = tb_ugender.ug_gid
        LEFT JOIN tb_ureligion ON tb_member.m_religion = tb_ureligion.ua_rid
        LEFT JOIN tb_urace ON tb_member.m_race = tb_urace.ur_rid
        LEFT JOIN tb_hrelation ON tb_member.m_maritalStatus = tb_hrelation.hr_rid
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
?>

<div class="container">
<h2>Maklumat Pemohon</h2>

<table class="table table-hover">
    <tr>
        <th>No. Aplikasi Anggota:</th>
        <td><?php echo $row['m_memberApplicationID']; ?></td>
    </tr>
    <tr>
        <th>No. PF:</th>
        <td><?php echo $row['m_pfNo']; ?></td>
    </tr>
    <tr>
        <th>Nama Anggota:</th>
        <td><?php echo $row['m_name']; ?></td>
    </tr>
    <tr>
        <th>No. Kad Pengenalan:</th>
        <td><?php echo $row['m_ic']; ?></td>
    </tr>
    <tr>
        <th>Jantina:</th>
        <td><?php echo $row['ug_desc']; ?></td>
    </tr>
    <tr>
        <th>Agama:</th>
        <td><?php echo $row['ua_desc']; ?></td>
    </tr>
    <tr>
        <th>Bangsa:</th>
        <td><?php echo $row['ur_desc']; ?></td>
    </tr>
    <tr>
        <th>Status Perkahwinan:</th>
        <td><?php echo $row['hr_desc']; ?></td>
    </tr>
    <tr>
        <th>Alamat Rumah:</th>
        <td><?php echo $row['m_homeAddress']; ?></td>
    </tr>
    <tr>
        <th>No. Telefon:</th>
        <td><?php echo $row['m_phoneNumber']; ?></td>
    </tr>
    <tr>
            <th>Email:</th>
            <td><?php echo $row['m_email']; ?></td>
        </tr>
    <tr>
        <th>No. Telefon Rumah:</th>
        <td><?php echo $row['m_homeNumber']; ?></td>
    </tr>
    <tr>
        <th>Jawatan:</th>
        <td><?php echo $row['m_position']; ?></td>
    </tr>
    <tr>
        <th>Gred:</th>
        <td><?php echo $row['m_positionGrade']; ?></td>
    </tr>
    <tr>
        <th>Alamat Pejabat:</th>
        <td><?php echo $row['m_officeAddress']; ?></td>
    </tr>
    <tr>
        <th>Gaji Bulanan:</th>
        <td><?php echo $row['m_monthlySalary']; ?></td>
    </tr>
    <tr>
        <th>Tarikh Pohon:</th>
        <td><?php echo date('d-m-Y H:i:s', strtotime($row['m_applicationDate'])); ?></td>
    </tr>
</table>

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
                
                $sql = "SELECT * FROM tb_status";
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
    event.preventDefault();  // Prevent the page from scrolling up when changing different status

    // Set the hidden input to the selected status
    document.getElementById('mstatus').value = status;

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
