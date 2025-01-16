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
    }
}
?>

<div class="container">
    <h2>Maklumat Peminjam</h2>
    <?php if (!empty($loan)) { ?>
    <table class="table table-hover">
        <tr>
            <th>No. Aplikasi Pinjaman:</th>
            <td><?php echo $loan['l_loanApplicationID']; ?></td>
        </tr>
        <tr>
            <th>No. Anggota:</th>
            <td><?php echo $loan['l_memberNo']; ?></td>
        </tr>
        <tr>
            <th>Nama Peminjam:</th>
            <td><?php echo $loan['m_pfNo']; ?></td>
        </tr>
        <tr>
            <th>Nama Anggota:</th>
            <td><?php echo $loan['m_name']; ?></td>
        </tr>
        <tr>
            <th>Jenis Pinjaman:</th>
            <td><?php echo $loan['lt_desc']; ?></td>
        </tr>
        <tr>
            <th>Jumlah Pinjaman:</th>
            <td><?php echo $loan['l_appliedLoan']; ?></td>
        </tr>
        <tr>
            <th>Tempoh Pinjaman:</th>
            <td><?php echo $loan['l_loanPeriod']; ?></td>
        </tr>
        <tr>
            <th>Ansuran Bulanan:</th>
            <td><?php echo $loan['l_monthlyInstalment']; ?></td>
        </tr>
        <tr>
            <th>Akaun Bank:</th>
            <td><?php echo $loan['l_bankAccountNo']; ?></td>
        </tr>
        <tr>
            <th>Nama Bank:</th>
            <td><?php echo $loan['lb_desc']; ?></td>
        </tr>
        <tr>
            <th>Gaji Kasar:</th>
            <td><?php echo $loan['l_monthlyGrossSalary']; ?></td>
        </tr>
        <tr>
            <th>Gaji Bersih:</th>
            <td><?php echo $loan['l_monthlyNetSalary']; ?></td>
        </tr>
        <tr>
            <th>Tarikh Pohon:</th>
            <td><?php echo $loan['l_applicationDate']; ?></td>
        </tr>
        <tr>
            <th>Tarikh Lulus:</th>
            <td><?php echo $loan['l_approvalDate']; ?></td>
        </tr>
    </table>
    <?php } else { ?>
        <p>Maklumat peminjam tidak dijumpai.</p>
    <?php } ?>
</div>

<div style="display: flex; gap: 10px; justify-content: center;">
            <button type="button" class="btn btn-primary" onclick="window.location.href='member_list.php'">Kembali</button>
</div>

<br>
