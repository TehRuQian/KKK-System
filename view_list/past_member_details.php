<?php

include('../kkksession.php');
if (!session_id()) {
    session_start();
}
if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
}

include '../header_admin.php';
include '../db_connect.php';

// Get the member ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$member = null;

$sql = "SELECT * FROM tb_member
        LEFT JOIN tb_ugender ON tb_member.m_gender = tb_ugender.ug_gid
        LEFT JOIN tb_ureligion ON tb_member.m_religion = tb_ureligion.ua_rid
        LEFT JOIN tb_urace ON tb_member.m_race = tb_urace.ur_rid
        LEFT JOIN tb_umaritalstatus ON tb_member.m_maritalStatus = tb_umaritalstatus.um_mid
        LEFT JOIN tb_homeState ON tb_member.m_homeState = tb_homeState.st_id
        WHERE m_pfNo = '$id' AND m_status = 5"; 

$result = mysqli_query($con, $sql);
$member = mysqli_fetch_array($result);

$tarikdiri = "SELECT *
              FROM tb_tarikdiri
              LEFT JOIN tb_status ON tb_tarikdiri.td_status = tb_status.s_sid
              LEFT JOIN tb_member ON tb_tarikdiri.td_memberNo = tb_member.m_memberNo
              WHERE m_pfNo = '$id'";

$result2 = mysqli_query($con, $tarikdiri);
$tarik = mysqli_fetch_array($result2);

?>

<div class="container">
    <h2>Maklumat Anggota Lepas</h2>
    <?php if (!empty($member)) { ?>

    <!-- Member Information -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
            Maklumat Peribadi Anggota Lepas
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
                    <td>Nama Anggota</td>
                    <td><?php echo $member['m_name']; ?></td>
                </tr>
                <tr>
                    <td>Jantina</td>
                    <td><?php echo $member['ug_desc']; ?></td>
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
            </table>
        </div>
    </div>

    <!-- Stop Information -->
    <div class="card mb-3 col-10 my-5 mx-auto">
        <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
            Maklumat Berhenti Sebagai Anggota
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <tr>
                    <td>No. Aplikasi</td>
                    <td><?php echo $tarik['td_tarikdiriID']; ?></td>
                </tr>
                <tr>
                    <td>Alasan</td>
                    <td><?php echo $tarik['td_alasan']; ?></td>
                </tr>
                <tr>
                    <td>Tarikh Berhenti</td>
                    <td><?php echo $tarik['td_approvalDate']; ?></td>
                </tr>
                <tr>
                    <td>Admin yang Luluskan</td>
                    <td><?php echo $tarik['td_adminID']; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <?php } else { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Maklumat Tidak Dijumpai',
            text: 'Anggota bukan kategori "Pass Member" atau data tidak tersedia.',
            confirmButtonText: 'Kembali ke Senarai',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'view_member_list.php'; // Redirect back to the member list
            }
        });
    </script>
    <?php } ?>


    <div style="display: flex; gap: 10px; justify-content: center;">
        <button type="button" class="btn btn-primary" onclick="window.location.href='view_past_member_list.php'">Kembali</button>
    </div>
</div>
<br>
