<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Get application ID
$mApplicationID = $_GET['id'];

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
    echo "No data found for the specified application ID.";
    exit;
}

// Fetch all existing member numbers
$memberNumbers = [];
$sql = "SELECT m_memberNo FROM tb_member WHERE m_memberNo IS NOT NULL";
$result = mysqli_query($con, $sql);

while ($rowMember = mysqli_fetch_assoc($result)) {
    $memberNumbers[] = $rowMember['m_memberNo'];
}

// Pass the data as a JavaScript array
echo "<script>const existingMemberNumbers = " . json_encode($memberNumbers) . ";</script>";

// Suggested Member No
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
        <div style="width: 40%; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
            <label class="form-label mt-4">Status Anggota</label>
            <select class="form-select" name="mstatus" id="mstatus" onchange="toggleMemberNoInput()">
                <?php
                // Fetch available statuses from the database
                $sql = "SELECT * FROM tb_status";
                $result = mysqli_query($con, $sql);

                while ($rowStatus = mysqli_fetch_array($result)) {
                    $selected = ($rowStatus['s_sid'] == 1) ? 'selected' : '';
                    echo "<option value='".$rowStatus['s_sid']."' $selected>".$rowStatus['s_desc']."</option>";
                }
                ?>
            </select>
        </div><br>

        <div id="memberNoContainer" style="display:none;">
            <label for="mMemberNo">Member No:</label>
            <input type="text" class="form-control" id="mMemberNo" name="mMemberNo" value="<?php echo $suggestedMemberNo; ?>" />
            <small>Suggested: <?php echo $suggestedMemberNo; ?></small>
        </div><br>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button type="button" class="btn btn-primary" onclick="window.location.href='member_approval.php'">Kembali</button>
            <button type="submit" class="btn btn-primary">Hantar</button>
        </div>
    </fieldset>
</form>
<br>

<script>
function toggleMemberNoInput() {
    const status = document.getElementById('mstatus').value;
    const memberNoContainer = document.getElementById('memberNoContainer');
    
    if (status == 3) { // If "Dilulus" (approved), show member number input
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

    // Validate memberNo locally if the status is "Dilulus" (3)
    if (status == 3 && memberNo) {
        if (existingMemberNumbers.includes(memberNo)) {
            alert("Member No already exists! Please enter a different Member No.");
            return false; // Prevent form submission
        }
    }

    return confirmSubmission(); // Proceed with confirmation
}

function confirmSubmission() {
    const status = document.getElementById('mstatus').value;

    if (status == 2) {
        return confirm("Are you sure you want to reject this application?");
    } else if (status == 3) {
        return confirm("Are you sure you want to approve this application?");
    }

    return true; // No confirmation needed for other statuses
}

toggleMemberNoInput();
</script>

