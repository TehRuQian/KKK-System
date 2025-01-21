<?php

include('../kkksession.php');
if(!session_id())
{
  session_start();
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
                <th>No. Aplikasi Anggota</th>
                <td><?php echo $member['m_memberApplicationID']; ?></td>
            </tr>
            <tr>
                <th>No. Anggota</th>
                <td><?php echo $member['m_memberNo']; ?></td>
            </tr>
            <tr>
                <th>No. PF</th>
                <td><?php echo $member['m_pfNo']; ?></td>
            </tr>
            <tr>
                <th>Nama Anggota</th>
                <td><?php echo $member['m_name']; ?></td>
            </tr>
            <tr>
                <th>No. Kad Pengenalan</th>
                <td><?php echo $member['m_ic']; ?></td>
            </tr>
            <tr>
                <th>Jantina</th>
                <td><?php echo $member['ug_desc']; ?></td>
            </tr>
            <tr>
                <th>Agama</th>
                <td><?php echo $member['ua_desc']; ?></td>
            </tr>
            <tr>
                <th>Bangsa</th>
                <td><?php echo $member['ur_desc']; ?></td>
            </tr>
            <tr>
                <th>Status Perkahwinan</th>
                <td><?php echo $member['um_desc']; ?></td>
            </tr>
            <tr>
                <th>Alamat Rumah</th>
                <td><?php echo $member['m_homeAddress']; ?></td>
            </tr>
            <tr>
                <th>No. Telefon</th>
                <td><?php echo $member['m_phoneNumber']; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $member['m_email']; ?></td>
            </tr>
            <tr>
                <th>No. Telefon Rumah</th>
                <td><?php echo !empty($row['m_homeNumber']) ? $row['m_homeNumber'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>No. Fax</th>
                <td><?php echo !empty($row['m_taxNumber']) ? $row['m_taxNumber'] : 'N/A'; ?></td>
            </tr>
            <tr>
                <th>Jawatan</th>
                <td><?php echo $member['m_position']; ?></td>
            </tr>
            <tr>
                <th>Gred</th>
                <td><?php echo $member['m_positionGrade']; ?></td>
            </tr>
            <tr>
                <th>Alamat Pejabat</th>
                <td><?php echo $member['m_officeAddress']; ?></td>
            </tr>
            <tr>
                <th>Gaji Bulanan</th>
                <td><?php echo $member['m_monthlySalary']; ?></td>
            </tr>
            <tr>
            <tr>
                <th>Tarikh Pohon:</th>
                <td><?php echo $member['m_applicationDate']; ?></td>
            </tr>
            <tr>
                <th>Tarikh Lulus:</th>
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
          <th>Fee Masuk</th>
            <td><?php echo $member['m_feeMasuk']; ?></td>
        </tr>
        <tr>
            <th>Modal Yuran</th>
            <td><?php echo $member['m_modalYuran']; ?></td>
        </tr>
        <tr>
            <th>Deposit</th>
            <td><?php echo $member['m_deposit']; ?></td>
        </tr>
        <tr>
            <th>alAbrar</th>
            <td><?php echo $member['m_alAbrar']; ?></td>
        </tr>
        <tr>
            <th>Simpanan Tetap</th>
            <td><?php echo $member['m_simpananTetap']; ?></td>
        </tr>
        <tr>
            <th>Fee Lain</th>
            <td><?php echo $member['m_feeLain']; ?></td>
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