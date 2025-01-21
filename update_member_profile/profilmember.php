<?php 
include('../kkksession.php');
if(!session_id())
{
  session_start();
}

if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$u_id = $_SESSION['funame'];

$sql = "SELECT tb_member.*,
               tb_ugender.ug_desc AS gender,
               tb_urace.ur_desc AS race,
               tb_ureligion.ua_desc AS religion,
               tb_umaritalstatus.um_desc AS maritalstatus,
               tb_homeState.st_desc AS homeState,
               tb_officeState.st_desc AS officeState,
               tb_member.m_memberApplicationID
        FROM tb_member
        LEFT JOIN tb_ugender ON tb_member.m_gender=tb_ugender.ug_gid
        LEFT JOIN tb_urace ON tb_member.m_race=tb_urace.ur_rid
        LEFT JOIN tb_ureligion ON tb_member.m_religion=tb_ureligion.ua_rid
        LEFT JOIN tb_umaritalstatus ON tb_member.m_maritalStatus=tb_umaritalstatus.um_mid
        LEFT JOIN tb_homeState ON tb_member.m_homeState=tb_homeState.st_id
        LEFT JOIN tb_officeState ON tb_member.m_officeState=tb_officeState.st_id
        WHERE tb_member.m_memberNo = '$u_id'";

$result = mysqli_query($con, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

$row = mysqli_fetch_assoc($result); 
$memberApplicationID = $row['m_memberApplicationID']; 
?>

  <style>
    body {
      background-color: #f9f9f9;
    }

    
    .fixed-table-container{
      position: fixed;
      top: 60px;
      width:100%;
      background-color: White;
      z-index:1;
    
    }

   

  </style>
</head>
<body>
<!--
  <table class="fixed-table-container">
  <thead>
    <tr>
      <th scope="col" style="text-align: center; font-size:20px; padding: 20px;">Maklumat Peribadi</th>
    </tr>
  </thead>
</table>-->

    <div class="my-3"></div>

    <h2 style="text-align: center;">Maklumat Peribadi</h2>

    <div class="my-4"></div>

      <div class="card mb-3 col-10 my-5 mx-auto">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Butir-butir Peribadi Pemohon
        <button type="button" class="btn btn-info"  onclick="window.location.href='kemaskinibutirbutir.php'">
            Kemaskini
        </button> 
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <tbody>
            <tr>
              <td scope="row">Nama</td>
              <td><?= $row['m_name']; ?></td>
            </tr>
            <tr>
              <td scope="row">No. KP</td>
              <td><?= $row['m_ic']; ?></td>
            </tr>
            <tr>
              <td scope="row">E-mel</td>
              <td><?= $row['m_email']; ?></td>
            </tr>
            <tr>
              <td scope="row">Jantina</td>
              <td><?= $row['gender']; ?></td>
            </tr>
            <tr>
              <td scope="row">Agama</td>
              <td><?= $row['religion']; ?></td>
            </tr>
            <tr>
              <td scope="row">Bangsa</td>
              <td><?= $row['race']; ?></td>
            </tr>
            <tr>
              <td scope="row">Taraf Perkahwinan</td>
              <td><?= $row['maritalstatus']; ?></td>
            </tr>
            <tr>
              <td scope="row">Alamat Rumah</td>
              <td><?= $row['m_homeAddress']; ?></td>
            </tr>
            <tr>
              <td scope="row">Poskod</td>
              <td><?= $row['m_homePostcode']; ?></td>
            </tr>
            <tr>
              <td scope="row">Bandar</td>
              <td><?= $row['m_homeCity']; ?></td>
            </tr>
            <tr>
              <td scope="row">Negeri</td>
              <td><?= $row['homeState']; ?></td>
            </tr>
            <tr>
              <td scope="row">No. Anggota</td>
              <td><?= $row['m_memberNo']; ?></td>
            </tr>
            <tr>
              <td scope="row">No. PF</td>
              <td><?= $row['m_pfNo']; ?></td>
            </tr>
            <tr>
              <td scope="row">Jawatan</td>
              <td><?= $row['m_position']; ?></td>
            </tr>
            <tr>
              <td scope="row">Gred</td>
              <td><?= $row['m_positionGrade']; ?></td>
            </tr>
            <tr>
              <td scope="row">Alamat Pejabat</td>
              <td><?= $row['m_officeAddress']; ?></td>
            </tr>
            <tr>
              <td scope="row">Poskod</td>
              <td><?= $row['m_officePostcode']; ?></td>
            </tr>
            <tr>
              <td scope="row">Bandar</td>
              <td><?= $row['m_officeCity']; ?></td>
            </tr>
            <tr>
              <td scope="row">Negeri</td>
              <td><?= $row['officeState']; ?></td>
            </tr>
            <tr>
              <td scope="row">No. Tel / Fax</td>
              <td><?php echo !empty($row['m_faxNumber']) ? $row['m_faxNumber'] : 'N/A'; ?></td>
            </tr>
            <tr>
              <td scope="row">No. Tel Bimbit</td>
              <td><?= $row['m_phoneNumber']; ?></td>
            </tr>
            <tr>
              <td scope="row">No. Tel Rumah</td>
              <td><?php echo !empty($row['m_homeNumber']) ? $row['m_homeNumber'] : 'N/A'; ?></td>
            </tr>
            <tr>
              <td scope="row">Gaji Bulanan</td>
              <td><?= $row['m_monthlySalary']; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

<div class="my-5"></div>

    <div class="card mb-3 col-10 my-5 mx-auto">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Maklumat Keluarga dan Pewaris
        <button type="button" class="btn btn-info"  onclick="window.location.href='kemaskinikeluarga.php'">
            Kemaskini
        </button> 
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <tbody>

            <?php
              $heir_query = "SELECT tb_heir.* ,
                            tb_hrelation.hr_desc AS heirrelation
                            FROM tb_heir 
                            LEFT JOIN tb_hrelation ON tb_heir.h_relationWithMember = tb_hrelation.hr_rid
                            WHERE h_memberApplicationID = '$memberApplicationID' ORDER BY h_heirID";
              $heir_result = mysqli_query($con, $heir_query);

              if (!$heir_result) {
                  die("Query failed: " . mysqli_error($con));
              }

              $count = 1;
              while ($heir_row = mysqli_fetch_assoc($heir_result)) {
                  if($count > 20) break; 
                  ?>
                  <tr>
                    <td scope="row"><b>Keluarga / Pewaris <?= $count++; ?></b></td>
                    <td></td>
                  </tr>
                  <tr>
                    <td scope="row">Nama</td>
                    <td><?= $heir_row['h_name']; ?></td>
                  </tr>
                  <tr>
                    <td scope="row">No. KP</td>
                    <td><?= $heir_row['h_ic']; ?></td>
                  </tr>
                  <tr>
                    <td scope="row">Hubungan</td>
                    <td><?= $heir_row['heirrelation']; ?></td>
                  </tr>
                  <tr><td><br></td><td><br></td></tr>
                  <?php 
              }
              ?>
          </tbody>
        </table>
      </div>
    </div>

<div class="my-5"></div><br>
  
</body>
</html>

<?php include '../footer.php';?>
