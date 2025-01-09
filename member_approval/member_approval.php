<?php

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

include '../header_admin.php';
include '../db_connect.php';

$currentDate = date('Y-m-d');

// Get User ID
$uid = $_SESSION['u_id'];

$sql = "SELECT * FROM tb_member 
        LEFT JOIN tb_status ON tb_member.m_status = tb_status.s_sid
        WHERE m_applicationDate >= DATE_SUB('$currentDate', INTERVAL 3 MONTH)";

// Execute the SQL statement on DB
$result = mysqli_query($con, $sql);
?>

<div class="container">
<h2>Permohonan Anggota</h2>
    <br>
    <table class="table table-hover">
        <thead>
        <tr>
        <th scope="col">No. Aplikasi</th>
        <th scope="col">No. PF</th>
        <th scope="col">Nama Anggota</th>
        <th scope="col">Status</th>
        <th scope="col">Butiran</th>
        </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>".$row['m_memberApplicationID'] . "</td>";
                echo "<td>".$row['m_pfNo'] . "</td>";
                echo "<td>".$row['m_name'] . "</td>";
                echo "<td>".$row['s_desc'] . "</td>";
                echo "<td>";
                echo "<a href='member_approval_detail.php?id=".$row['m_memberApplicationID']."' title='View Details'>...</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
