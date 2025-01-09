<?php

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

include '../header_admin.php';
include '../db_connect.php';

// Retrieve all registered Course
$sql = "SELECT * FROM tb_loan 
        LEFT JOIN tb_member ON tb_loan.l_memberNo = tb_member.m_memberNo 
        WHERE l_status = 3";

//  Execute the SQL statement on DB
$result = mysqli_query($con, $sql);
?>

<div class="container">
<h2>Senarai Peminjam</h2>
    <br>
    <table class="table table-hover">
        <thead>
        <tr>
        <th scope="col">No. Pinjaman</th>
        <th scope="col">No. Anggota</th>
        <th scope="col">Nama Anggota</th>
        <th scope="col">Jumlah Yang Dipinjam</th>
        <th scope="col">Tarikh Lulus</th>
        <th scope="col">Butiran</th>
        </tr>
        </thead>
        <tbody>
            <?php
            while($row = mysqli_fetch_array($result))
            {
                echo "<tr>";
                echo "<td>".$row['l_loanApplicationID']."</td>";
                echo "<td>".$row['l_memberNo']."</td>";
                echo "<td>".$row['m_name']."</td>";
                echo "<td>".$row['l_appliedLoan']."</td>";
                echo "<td>".$row['l_approvalDate']."</td>";
                echo "<td><a href='loan_details.php?id=".$row['l_loanApplicationID']."' title='View Details'>...</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</div>

