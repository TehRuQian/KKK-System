<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Get application ID
$lApplicationID = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($lApplicationID === 0) {
    echo "<script>alert('Invalid application ID.'); window.location.href = 'loan_approval.php';</script>";
    exit;
}

// Retrieve member details
$sql = "SELECT tb_loan.*, tb_member.m_name, tb_member.m_pfNo, tb_ltype.lt_desc, tb_lbank.lb_desc
        FROM tb_loan
        LEFT JOIN tb_member ON tb_loan.l_memberNo = tb_member.m_memberNo
        LEFT JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
        LEFT JOIN tb_lbank ON tb_loan.l_bankName = tb_lbank.lb_id
        WHERE tb_loan.l_loanApplicationID = '$lApplicationID'";

$result = mysqli_query($con, $sql);

if ($result && $row = mysqli_fetch_array($result)) {
    // Loan data successfully retrieved
} else {
    echo "No data found for the specified application ID.";
    exit;
}
?>

<div class="container">
    <h2>Maklumat Peminjam</h2>
    <table class="table table-hover">
        <tr>
            <th>No. Aplikasi Pinjaman:</th>
            <td><?php echo $row['l_loanApplicationID']; ?></td>
        </tr>
        <tr>
            <th>No. Anggota:</th>
            <td><?php echo $row['l_memberNo']; ?></td>
        </tr>
        <tr>
            <th>Nama Peminjam:</th>
            <td><?php echo $row['m_pfNo']; ?></td>
        </tr>
        <tr>
            <th>Nama Anggota:</th>
            <td><?php echo $row['m_name']; ?></td>
        </tr>
        <tr>
            <th>Jenis Pinjaman:</th>
            <td><?php echo $row['lt_desc']; ?></td>
        </tr>
        <tr>
            <th>Jumlah Pinjaman:</th>
            <td><?php echo $row['l_appliedLoan']; ?></td>
        </tr>
        <tr>
            <th>Tempoh Pinjaman:</th>
            <td><?php echo $row['l_loanPeriod']; ?></td>
        </tr>
        <tr>
            <th>Ansuran Bulanan:</th>
            <td><?php echo $row['l_monthlyInstalment']; ?></td>
        </tr>
        <tr>
            <th>Akaun Bank:</th>
            <td><?php echo $row['l_bankAccountNo']; ?></td>
        </tr>
        <tr>
            <th>Nama Bank:</th>
            <td><?php echo $row['lb_desc']; ?></td>
        </tr>
        <tr>
            <th>Gaji Kasar:</th>
            <td><?php echo $row['l_monthlyGrossSalary']; ?></td>
        </tr>
        <tr>
            <th>Gaji Bersih:</th>
            <td><?php echo $row['l_monthlyNetSalary']; ?></td>
        </tr>
        <tr>
            <th>Tarikh Pohon:</th>
            <td><?php echo $row['l_applicationDate']; ?></td>
        </tr>
        <tr>
            <th>Tarikh Lulus:</th>
            <td><?php echo $row['l_approvalDate']; ?></td>
        </tr>
    </table>

    <form method="POST" action="loan_approval_process.php" onsubmit="return confirmSubmission()">
        <input type="hidden" name="lApplicationID" value="<?php echo $lApplicationID; ?>">
        <fieldset>
            <div style="width: 40%; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px;">
                <label class="form-label mt-4">Status Pinjaman</label>
                <select class="form-select" name="loanStatus" id="l_status">
                    <?php
                    $statusSql = "SELECT * FROM tb_status";
                    $statusResult = mysqli_query($con, $statusSql);

                    while ($statusRow = mysqli_fetch_array($statusResult)) {
                        $selected = ($statusRow['s_sid'] == 1) ? 'selected' : '';
                        echo "<option value='" . $statusRow['s_sid'] . "' $selected>" . $statusRow['s_desc'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <br>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="btn btn-primary" onclick="window.location.href='loan_approval.php'">Kembali</button>
                <button type="submit" class="btn btn-primary">Hantar</button>
            </div>
        </fieldset>
    </form>
    <br>
</div>

<script>
function confirmSubmission() {
    const status = document.getElementById('l_status').value;

    if (status == 2) {
        return confirm("Are you sure you want to reject this loan application?");
    } else if (status == 3) {
        return confirm("Are you sure you want to approve this loan application?");
    }

    return true;
}
</script>
