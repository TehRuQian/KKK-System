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
        LEFT JOIN tb_hrelation ON tb_member.m_maritalStatus = tb_hrelation.hr_rid

        WHERE m_memberNo = '$member_id'";

        $result = mysqli_query($con, $sql);
        $member = mysqli_fetch_array($result);

?>

<div class="container">
    <h2>Maklumat Anggota</h2>
    <?php if (!empty($member)) { ?>
    <table class="table table-hover">
        <tr>
            <th>No. Aplikasi Anggota:</th>
            <td><?php echo $member['m_memberApplicationID']; ?></td>
        </tr>
        <tr>
            <th>No. Anggota:</th>
            <td><?php echo $member['m_memberNo']; ?></td>
        </tr>
        <tr>
            <th>No. PF:</th>
            <td><?php echo $member['m_pfNo']; ?></td>
        </tr>
        <tr>
            <th>Nama Anggota:</th>
            <td><?php echo $member['m_name']; ?></td>
        </tr>
        <tr>
            <th>No. Kad Pengenalan:</th>
            <td><?php echo $member['m_ic']; ?></td>
        </tr>
        <tr>
            <th>Jantina:</th>
            <td><?php echo $member['ug_desc']; ?></td>
        </tr>
        <tr>
            <th>Agama:</th>
            <td><?php echo $member['ua_desc']; ?></td>
        </tr>
        <tr>
            <th>Bangsa:</th>
            <td><?php echo $member['ur_desc']; ?></td>
        </tr>
        <tr>
            <th>Status Perkahwinan:</th>
            <td><?php echo $member['hr_desc']; ?></td>
        </tr>
        <tr>
            <th>Alamat Rumah:</th>
            <td><?php echo $member['m_homeAddress']; ?></td>
        </tr>
        <tr>
            <th>No. Telefon:</th>
            <td><?php echo $member['m_phoneNumber']; ?></td>
        </tr>
        <tr>
            <th>Email:</th>
            <td><?php echo $member['m_email']; ?></td>
        </tr>
        <tr>
            <th>No. Telefon Rumah:</th>
            <td><?php echo $member['m_homeNumber']; ?></td>
        </tr>
        <tr>
            <th>Jawatan:</th>
            <td><?php echo $member['m_position']; ?></td>
        </tr>
        <tr>
            <th>Gred:</th>
            <td><?php echo $member['m_positionGrade']; ?></td>
        </tr>
        <tr>
            <th>Alamat Pejabat:</th>
            <td><?php echo $member['m_officeAddress']; ?></td>
        </tr>
        <tr>
            <th>Gaji Bulanan:</th>
            <td><?php echo $member['m_monthlySalary']; ?></td>
        </tr>
        <tr>
            <th>Tarikh Pohon:</th>
            <td><?php echo $member['m_applicationDate']; ?></td>
        </tr>
        <tr>
            <th>Tarikh Lulus:</th>
            <td><?php echo $member['m_approvalDate']; ?></td>
        </tr>

    </table>
    <?php } ?>
</div>


<div style="display: flex; gap: 10px; justify-content: center;">
            <button type="button" class="btn btn-primary" onclick="window.location.href='view_member_list.php'">Kembali</button>
</div>

<br>