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

// Retrieve loan and guarantor details
$sqlLoan = "SELECT l.*, g1.g_memberNo AS guarantorID1, g2.g_memberNo AS guarantorID2
            FROM tb_loan l
            LEFT JOIN tb_guarantor g1 ON l.l_loanApplicationID = g1.g_loanApplicationID
            LEFT JOIN tb_guarantor g2 ON l.l_loanApplicationID = g2.g_loanApplicationID
            WHERE l.l_loanApplicationID = '$lApplicationID'";

$resultLoan = mysqli_query($con, $sqlLoan);
if ($rowLoan = mysqli_fetch_assoc($resultLoan)) {
    // Loan details
    $guarantorID1 = $rowLoan['guarantorID1'];
    $guarantorID2 = $rowLoan['guarantorID2'];
    
    // Fetch member details
    $sql1 = "SELECT tb_loan.*, tb_member.m_name, tb_member.m_pfNo, tb_ltype.lt_desc, tb_lbank.lb_desc
             FROM tb_loan
             LEFT JOIN tb_member ON tb_loan.l_memberNo = tb_member.m_memberNo
             LEFT JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
             LEFT JOIN tb_lbank ON tb_loan.l_bankName = tb_lbank.lb_id
             WHERE tb_loan.l_loanApplicationID = '$lApplicationID'";

    $result1 = mysqli_query($con, $sql1);
    $loanDetails = mysqli_fetch_assoc($result1);

    // Fetch guarantor 1 details
    $sql2 = "SELECT g.g_memberNo, g.g_signature, m.m_name, m.m_ic, m.m_pfNo 
             FROM tb_guarantor g
             LEFT JOIN tb_member m ON g.g_memberNo = m.m_memberNo 
             WHERE g.g_memberNo = '$guarantorID1'";

    $result2 = mysqli_query($con, $sql2);
    $guarantor1 = mysqli_fetch_assoc($result2);

    // Fetch guarantor 2 details
    $sql3 = "SELECT g.g_memberNo, g.g_signature, m.m_name, m.m_ic, m.m_pfNo 
             FROM tb_guarantor g
             LEFT JOIN tb_member m ON g.g_memberNo = m.m_memberNo 
             WHERE g.g_memberNo = '$guarantorID2'";

    $result3 = mysqli_query($con, $sql3);
    $guarantor2 = mysqli_fetch_assoc($result3);
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
            <td><?php echo $loanDetails['l_loanApplicationID']; ?></td>
        </tr>
        <tr>
            <th>No. Anggota:</th>
            <td><?php echo $loanDetails['l_memberNo']; ?></td>
        </tr>
        <tr>
            <th>Nama Peminjam:</th>
            <td><?php echo $loanDetails['m_pfNo']; ?></td>
        </tr>
        <tr>
            <th>Nama Anggota:</th>
            <td><?php echo $loanDetails['m_name']; ?></td>
        </tr>
        <tr>
            <th>Jenis Pinjaman:</th>
            <td><?php echo $loanDetails['lt_desc']; ?></td>
        </tr>
        <tr>
            <th>Jumlah Pinjaman:</th>
            <td><?php echo $loanDetails['l_appliedLoan']; ?></td>
        </tr>
        <tr>
            <th>Tempoh Pinjaman:</th>
            <td><?php echo $loanDetails['l_loanPeriod']; ?></td>
        </tr>
        <tr>
            <th>Ansuran Bulanan:</th>
            <td><?php echo $loanDetails['l_monthlyInstalment']; ?></td>
        </tr>
        <tr>
            <th>Akaun Bank:</th>
            <td><?php echo $loanDetails['l_bankAccountNo']; ?></td>
        </tr>
        <tr>
            <th>Nama Bank:</th>
            <td><?php echo $loanDetails['lb_desc']; ?></td>
        </tr>
        <tr>
            <th>Gaji Kasar:</th>
            <td><?php echo $loanDetails['l_monthlyGrossSalary']; ?></td>
        </tr>
        <tr>
            <th>Gaji Bersih:</th>
            <td><?php echo $loanDetails['l_monthlyNetSalary']; ?></td>
        </tr>
        
        <tr>
            <td scope="row">Tandatangan</td>
            <td>
                <?php if (!empty($loanDetails['l_signature'])) : ?>
                    <img src="<?php echo $loanDetails['l_signature']; ?>" alt="Signature" style="max-width: 200px; height: auto;">
                <?php  else : ?>
                    <span>No signature available</span>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>Pengesahan Majikan:</th>
            <td>
                <?php
                if (!empty($loanDetails['l_file']) && file_exists($loanDetails['l_file'])) {
                    echo '<a href="' . htmlspecialchars($loanDetails['l_file']) . '" target="_blank">Pengesahan Majikan</a>';
                } else {
                    echo 'No file available.';
                }
                ?>
            </td>
        </tr>

        <tr>
            <th>Tarikh Pohon:</th>
            <td><?php echo $loanDetails['l_applicationDate']; ?></td>
        </tr>
        <tr>
            <th>Tarikh Lulus:</th>
            <td><?php echo $loanDetails['l_approvalDate']; ?></td>
        </tr>
        <tr>
            <th colspan="2">Maklumat Penjamin 1</th>
        </tr>
        <tr>
            <th>No. Anggota:</th>
            <td><?php echo $guarantor1['g_memberNo'] ?? 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Nama Penjamin:</th>
            <td><?php echo $guarantor1['m_name'] ?? 'N/A'; ?></td>
        </tr>
        <tr>
            <th>No. Kad Pengenalan:</th>
            <td><?php echo $guarantor1['m_ic'] ?? 'N/A'; ?></td>
        </tr>
        <tr>
            <th>No. PF:</th>
            <td><?php echo $guarantor1['m_pfNo'] ?? 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Tandatangan Penjamin 1:</th>
            <td>
                <?php if (!empty($guarantor1['g_signature']) && file_exists($guarantor1['g_signature'])) : ?>
                    <img src="<?php echo htmlspecialchars($guarantor1['g_signature']); ?>" alt="Tandatangan Penjamin 1" style="max-width: 200px; height: auto;">
                <?php else : ?>
                    <span>No signature available</span>
                <?php endif; ?>
            </td>
        </tr>

        <tr>
            <th colspan="2">Maklumat Penjamin 2</th>
        </tr>
        <tr>
            <th>No. Anggota:</th>
            <td><?php echo $guarantor2['g_memberNo'] ?? 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Nama Penjamin:</th>
            <td><?php echo $guarantor2['m_name'] ?? 'N/A'; ?></td>
        </tr>
        <tr>
            <th>No. Kad Pengenalan:</th>
            <td><?php echo $guarantor2['m_ic'] ?? 'N/A'; ?></td>
        </tr>
        <tr>
            <th>No. PF:</th>
            <td><?php echo $guarantor2['m_pfNo'] ?? 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Tandatangan Penjamin 2:</th>
            <td>
                <?php if (!empty($guarantor2['g_signature']) && file_exists($guarantor2['g_signature'])) : ?>
                    <img src="<?php echo htmlspecialchars($guarantor2['g_signature']); ?>" alt="Tandatangan Penjamin 2" style="max-width: 200px; height: auto;">
                <?php else : ?>
                    <span>No signature available</span>
                <?php endif; ?>
            </td>
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
