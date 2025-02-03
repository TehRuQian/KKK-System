<?php

include('../kkksession.php');
if(!session_id())
{
  session_start();
}
if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
}
  
include '../header_admin.php';
include '../db_connect.php';

if (isset($_GET['id']))
    $member_id = $_GET['id'];

    $member = null;
        $sql = "SELECT * FROM tb_member
        LEFT JOIN tb_ugender ON tb_member.m_gender = tb_ugender.ug_gid
        LEFT JOIN tb_ureligion ON tb_member.m_religion = tb_ureligion.ua_rid
        LEFT JOIN tb_urace ON tb_member.m_race = tb_urace.ur_rid
        LEFT JOIN tb_umaritalstatus ON tb_member.m_maritalStatus = tb_umaritalstatus.um_mid
        LEFT JOIN tb_homeState ON tb_member.m_homeState = tb_homeState.st_id
        LEFT JOIN tb_officeState ON tb_member.m_officeState = tb_officeState.st_id

        WHERE m_memberNo = '$member_id'";

        $result = mysqli_query($con, $sql);
        $member = mysqli_fetch_array($result);

        // Fetch heir information
        $sqlHeirs = "SELECT * FROM tb_heir 
                     LEFT JOIN tb_hrelation ON tb_heir.h_relationWithMember = tb_hrelation.hr_rid
                     WHERE h_memberApplicationID = '{$member['m_memberApplicationID']}'";

        $resultHeirs = mysqli_query($con, $sqlHeirs);
        $heirs = mysqli_fetch_all($resultHeirs, MYSQLI_ASSOC);
        while ($row = mysqli_fetch_assoc($resultHeirs)) {
            print_r($row);
        }
        

?>

<div class="container">
    <h2>Maklumat Anggota</h2>
    <?php if (!empty($member)) { ?>

    <!-- Member Information -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
      Maklumat Peribadi Ahli
      </div>
      <div class="card-body">
        <table class="table table-hover">
            <tr>
                <td>No. Aplikasi Anggota</td>
                <td><?php echo $member['m_memberApplicationID']; ?></td>
            </tr>
            <tr>
                <td>No. Anggota</td>
                <td><?php echo $member['m_memberNo']; ?></td>
            </tr>
            <tr>
                <td>No. PF</td>
                <td><?php echo $member['m_pfNo']; ?></td>
            </tr>
            <tr>
                <td>Nama Anggota</td>
                <td><?php echo $member['m_name']; ?></td>
            </tr>
            <tr>
                <td>No. Kad Pengenalan</td>
                <td><?php echo $member['m_ic']; ?></td>
            </tr>
            <tr>
                <td>Jantina</td>
                <td><?php echo $member['ug_desc']; ?></td>
            </tr>
            <tr>
                <td>Agama</td>
                <td><?php echo $member['ua_desc']; ?></td>
            </tr>
            <tr>
                <td>Bangsa</td>
                <td><?php echo $member['ur_desc']; ?></td>
            </tr>
            <tr>
                <td>Status Perkahwinan</td>
                <td><?php echo $member['um_desc']; ?></td>
            </tr>
            <tr>
                <td>Alamat Rumah</td>
                <td><?php echo $member['m_homeAddress'] . ', ' . $member['m_homePostcode'] . ' ' . $member['m_homeCity'] . ', ' . $member['st_desc']; ?></td>
            </tr>
            <tr>
                <td>No. Telefon</td>
                <td><?php echo $member['m_phoneNumber']; ?></td>
            </tr>
            <tr>
                <td>No. Telefon Rumah</td>
                <td><?php echo !empty($member['m_homeNumber']) ? $member['m_homeNumber'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <td>No. Fax</td>
                <td><?php echo !empty($member['m_taxNumber']) ? $member['m_taxNumber'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo $member['m_email']; ?></td>
            </tr>
            <tr>
                <td>Jawatan</td>
                <td><?php echo $member['m_position']; ?></td>
            </tr>
            <tr>
                <td>Gred</td>
                <td><?php echo $member['m_positionGrade']; ?></td>
            </tr>
            <tr>
                <td>Alamat Pejabat</td>
                <td><?php echo $member['m_officeAddress'] . ', ' . $member['m_officePostcode'] . ' ' . $member['m_officeCity'] . ', ' . $member['st_desc']; ?></td>
            </tr>
            <tr>
                <td>Gaji Bulanan (RM)</td>
                <td><?php echo number_format($member['m_monthlySalary'], 2); ?></td>
            </tr>
            <tr>
            <tr>
                <td>Tarikh Pohon:</td>
                <td><?php echo $member['m_applicationDate']; ?></td>
            </tr>
            <tr>
                <td>Tarikh Lulus</td>
                <td><?php echo $member['m_approvalDate']; ?></td>
            </tr>
        </table>
        </div>
    </div>

    <!-- Member Shares Information -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
      Maklumat Saham Ahli
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <td>Fee Masuk (RM)</td>
            <td><?php echo number_format($member['m_feeMasuk'], 2); ?></td>
        </tr>
        <tr>
            <td>Modal Yuran (RM)</td>
            <td><?php echo number_format($member['m_modalYuran'], 2); ?></td>
        </tr>
        <tr>
            <td>Deposit (RM)</td>
            <td><?php echo number_format($member['m_deposit'], 2); ?></td>
        </tr>
        <tr>
            <td>alAbrar (RM)</td>
            <td><?php echo number_format($member['m_alAbrar'], 2); ?></td>
        </tr>
        <tr>
            <td>Simpanan Tetap (RM)</td>
            <td><?php echo number_format($member['m_simpananTetap'], 2); ?></td>
        </tr>
        <tr>
            <td>Fee Lain (RM)</td>
            <td><?php echo number_format($member['m_feeLain'], 2); ?></td>
        </tr>
        </table>
      </div>
    <?php } ?>
</div>

<!-- Heir Information -->
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



<div style="display: flex; gap: 10px; justify-content: center;">
            <button type="button" class="btn btn-primary" onclick="window.location.href='view_member_list.php'">Kembali</button>
</div>

<br>