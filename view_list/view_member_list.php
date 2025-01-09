<?php

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

include '../header_admin.php';
include '../db_connect.php';

$sql = "SELECT * FROM tb_member WHERE m_status = 3";

//  Execute the SQL statement on DB
$result = mysqli_query($con, $sql);
?>

<div class="container">
<h2>Senarai Anggota</h2>
    <br>
    <table class="table table-hover">
        <thead>
        <tr>
        <th scope="col">No. Anggota</th>
        <th scope="col">No. PF</th>
        <th scope="col">Nama Anggota</th>
        <th scope="col">Tarikh Masuk</th>
        <th scope="col">Butiran</th>
        </tr>
        </thead>
        <tbody>
            <?php
            while($row = mysqli_fetch_array($result))
            {
                echo "<tr>";
                echo "<td>".$row['m_memberNo']."</td>";
                echo "<td>".$row['m_pfNo']."</td>";
                echo "<td>".$row['m_name']."</td>";
                echo "<td>".$row['m_approvalDate']."</td>";
                echo "<td><a href='member_details.php?id=".$row['m_memberNo']."' title='View Details'>...</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</div>

