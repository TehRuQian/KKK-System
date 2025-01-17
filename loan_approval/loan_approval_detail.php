<?php
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Get application ID
$lApplicationID = $_GET['id'];

if ($lApplicationID === 0) {
    echo "<script>alert('ID aplikasi tidak sah.'); window.location.href = 'loan_approval.php';</script>";
    exit;
}

// Retrieve member details
$sql = "SELECT tb_loan.*, tb_member.m_name, tb_member.m_pfNo, tb_ltype.lt_desc, tb_lbank.lb_desc
        FROM tb_loan
        LEFT JOIN tb_member ON tb_loan.l_memberNo = tb_member.m_memberNo
        LEFT JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
        LEFT JOIN tb_lbank ON tb_loan.l_bankName = tb_lbank.lb_id
        WHERE tb_loan.l_loanApplicationID = '$lApplicationID'";

// Execute the SQL statement on DB
$result = mysqli_query($con, $sql);

if ($result && $row = mysqli_fetch_array($result)) {
    // Loan data successfully retrieved
} else {
    echo "Tiada data ditemui untuk ID aplikasi tersebut.";
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
            <input type="hidden" name="lstatus" id="lstatus"> 

            <div style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="btn btn-primary" onclick="window.location.href='loan_approval.php'">Kembali</button>
                <button type="submit" class="btn btn-primary">Hantar</button>
            </div>
        </fieldset>
    </form>
    <br>
</div>


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
        statusButton.classList.add('btn-secondary');
    } else if (status == 2) {
        statusButton.classList.remove('btn-secondary', 'btn-success', 'btn-warning');
        statusButton.classList.add('btn-danger');
    } else if (status == 3) {
        statusButton.classList.remove('btn-secondary', 'btn-danger', 'btn-warning');
        statusButton.classList.add('btn-success');
    }
}

function confirmSubmission() {
    const status = document.getElementById('lstatus').value;

    if (status == 2) {
        return confirm("Adakah anda pasti untuk menolak permohonan ini?");
    } else if (status == 3) {
        return confirm("Adakah anda pasti mahu meluluskan permohonan ini?");
    }
    return true;
}

setStatus(null, 1, "Sedang Diproses");
</script>
