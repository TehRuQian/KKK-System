<?php 
  include '../header_admin.php';
  include '../db_connect.php';

  // Retrieve latest policy with newest ID
  $sql = "
    SELECT tb_financial.*, tb_member.m_name
    FROM tb_financial
    INNER JOIN tb_member
    ON tb_financial.f_memberNo=tb_member.m_memberNo;";
  $result = mysqli_query($con, $sql);
?>

<div class="container">
    <h2>Transaksi</h2>
    <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No Ahli</th>
        <th scope="col">Nama</th>
        <th scope="col">Modah Syer</th>
        <th scope="col">Modal Yuran</th>
        <th scope="col">Simpanan Tetap</th>
        <th scope="col">Tabung Anggota</th>
        <th scope="col">Simpanan Anggota</th>
        <th scope="col">Al-Bai</th>
        <th scope="col">Al-Innah</th>
        <th scope="col">B/Pulih Kenderaan</th>
        <th scope="col">Road Tax & Insuran</th>
        <th scope="col">Khas</th>
        <th scope="col">Karnival Musim Istimewa</th>
        <th scope="col">Al-Qadrul Hassan</th>
        <th scope="col">Dikemaskini</th>
      </tr>
    </thead>
    <tbody>
      <?php
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['f_memberNo'] . "</td>";
                echo "<td>" . $row['m_name'] . "</td>";
                echo "<td>" . number_format($row['f_shareCapital'], 2) . "</td>";
                echo "<td>" . number_format($row['f_feeCapital'], 2) . "</td>";
                echo "<td>" . number_format($row['f_fixedSaving'], 2) . "</td>";
                echo "<td>" . number_format($row['f_memberFund'], 2) . "</td>";
                echo "<td>" . number_format($row['f_memberSaving'], 2) . "</td>";
                echo "<td>" . number_format($row['f_alBai'], 2) . "</td>";
                echo "<td>" . number_format($row['f_alInnah'], 2) . "</td>";
                echo "<td>" . number_format($row['f_bPulihKenderaan'], 2) . "</td>";
                echo "<td>" . number_format($row['f_roadTaxInsurance'], 2) . "</td>";
                echo "<td>" . number_format($row['f_specialScheme'], 2) . "</td>";
                echo "<td>" . number_format($row['f_specialSeasonCarnival'], 2) . "</td>";
                echo "<td>" . number_format($row['f_alQadrulHassan'], 2) . "</td>";
                echo "<td>" . date('d-m-Y', strtotime($row['f_dateUpdated'])) . "</td>";
            echo "</tr>";
        }
      ?>
    </tbody>
  </table>

</div>
</body>
</html>