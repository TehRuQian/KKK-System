<?php

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

include '../header_admin.php';
include '../db_connect.php';

if (isset($_GET['id'])) {
    $loan_id = $_GET['id'];
    $loan = null;
    $sql = "SELECT tb_loan.*, tb_member.m_name, tb_member.m_pfNo, tb_ltype.lt_desc, tb_lbank.lb_desc
            FROM tb_loan
            LEFT JOIN tb_member ON tb_loan.l_memberNo = tb_member.m_memberNo
            LEFT JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
            LEFT JOIN tb_lbank ON tb_loan.l_bankName = tb_lbank.lb_id
            WHERE tb_loan.l_loanApplicationID = '$loan_id'";

    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $loan = mysqli_fetch_assoc($result);
            $guarantorID1 = null;
            $guarantorID2 = null;
        
            // Fetch the guarantors for this loan application
            $sqlGuarantors = "SELECT g.g_memberNo, g.g_signature FROM tb_guarantor g WHERE g.g_loanApplicationID = '$loan_id' LIMIT 2";
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
    }
}
?>

<div class="container">
    <h2>Maklumat Peminjam</h2>
    <?php if (!empty($loan)) { ?>
    
    <!-- Maklumat Pinjaman -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
            Maklumat Pinjaman
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th>No. Aplikasi Pinjaman</th>
                    <td><?php echo $loan['l_loanApplicationID']; ?></td>
                </tr>
                <tr>
                    <th>Jenis Pinjaman</th>
                    <td><?php echo $loan['lt_desc']; ?></td>
                </tr>
                <tr>
                    <th>Jumlah Pinjaman</th>
                    <td><?php echo $loan['l_appliedLoan']; ?></td>
                </tr>
                <tr>
                    <th>Tempoh Pinjaman</th>
                    <td><?php echo $loan['l_loanPeriod']; ?></td>
                </tr>
                <tr>
                    <th>Ansuran Bulanan</th>
                    <td><?php echo $loan['l_monthlyInstalment']; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Maklumat Peribadi Peminjam -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
            Maklumat Peribadi Peminjam
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th>No. Anggota</th>
                    <td><?php echo $loan['l_memberNo']; ?></td>
                </tr>
                <tr>
                    <th>No. PF</th>
                    <td><?php echo $loan['m_pfNo']; ?></td>
                </tr>
                <tr>
                    <th>Nama Anggota</th>
                    <td><?php echo $loan['m_name']; ?></td>
                </tr>
                <tr>
                    <th>Akaun Bank</th>
                    <td><?php echo $loan['l_bankAccountNo']; ?></td>
                </tr>
                <tr>
                    <th>Nama Bank</th>
                    <td><?php echo $loan['lb_desc']; ?></td>
                </tr>
                <tr>
                    <th>Gaji Kasar</th>
                    <td><?php echo $loan['l_monthlyGrossSalary']; ?></td>
                </tr>
                <tr>
                    <th>Gaji Bersih</th>
                    <td><?php echo $loan['l_monthlyNetSalary']; ?></td>
                </tr>
                <tr>
                    <th>Tarikh Pohon</th>
                    <td><?php echo $loan['l_applicationDate']; ?></td>
                </tr>
                <tr>
                    <th>Tarikh Lulus</th>
                    <td><?php echo $loan['l_approvalDate']; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Maklumat Penjamin 1 -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
            Maklumat Penjamin 1
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th>No. Anggota</th>
                    <td><?php echo $guarantor1['m_memberNo'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>Nama Penjamin</th>
                    <td><?php echo $guarantor1['m_name'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>No. Kad Pengenalan</th>
                    <td><?php echo $guarantor1['m_ic'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>No. PF</th>
                    <td><?php echo $guarantor1['m_pfNo'] ?? 'N/A'; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Maklumat Penjamin 2 -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary justify-content-between align-items-center">
            Maklumat Penjamin 2
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <th>No. Anggota</th>
                    <td><?php echo $guarantor2['m_memberNo'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>Nama Penjamin</th>
                    <td><?php echo $guarantor2['m_name'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>No. Kad Pengenalan</th>
                    <td><?php echo $guarantor2['m_ic'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                    <th>No. PF</th>
                    <td><?php echo $guarantor2['m_pfNo'] ?? 'N/A'; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <?php } else { ?>
        <p>Maklumat peminjam tidak dijumpai.</p>
    <?php } ?>
</div>

<div style="display: flex; gap: 10px; justify-content: center;">
            <button type="button" class="btn btn-primary" onclick="window.location.href='view_loan_list.php'">Kembali</button>
</div>
<br>