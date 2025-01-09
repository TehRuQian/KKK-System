<?php

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

include '../header_admin.php';
include '../db_connect.php';

$sql = "SELECT * FROM tb_member 
        LEFT JOIN tb_status ON tb_member.m_status = tb_status.s_sid
        LEFT JOIN tb_ugender ON tb_member.m_gender = tb_ugender.ug_gid
        LEFT JOIN tb_ureligion ON tb_member.m_religion = tb_ureligion.ua_rid
        LEFT JOIN tb_urace ON tb_member.m_race = tb_urace.ur_rid
        LEFT JOIN tb_hrelation ON tb_member.m_maritalStatus = tb_hrelation.hr_rid";

//  Execute the SQL statement on DB
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);

// Default values
$statusText = "Sedang Diproses";
$buttonClass = "btn-warning";

// Check for status in URL
if (isset($_GET['status'])) {
    switch ($_GET['status']) {
        case '3':
            $statusText = "Diterima";
            $buttonClass = "btn-success";
            break;
        case '2':
            $statusText = "Ditolak";
            $buttonClass = "btn-danger";
            break;
        case '1':
            $statusText = "Sedang Diproses";
            $buttonClass = "btn-warning";
            break;
    }
}
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
            <td><?php echo $row['m_applicationDate']; ?></td>
        </tr>

    </table>

<div class="btn-group ">
    <button class="btn <?php echo $buttonClass; ?> dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php echo $statusText; ?>
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="?status=3">Diterima</a></li>
        <li><a class="dropdown-item" href="?status=2">Ditolak</a></li>
        <li><a class="dropdown-item" href="?status=1">Sedang Diproses</a></li>
    </ul>
</div>


    <br><br>

</div>