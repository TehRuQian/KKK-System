<?php 
include('../kkksession.php');
if(!session_id())
{
  session_start();
}

if ($_SESSION['u_type']!=2) {
  header('Location: ../login.php');
  exit();
}

if(isset($_SESSION['u_id'])!= session_id())
{
  header('Location:../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$u_id = $_SESSION['funame'];

$feedbackID=$_GET['feedbackID'] ?? '';

if (empty($feedbackID)) {
    die("No feedback ID provided.");
}

$sql = "SELECT tb_feedback.*, 
               tb_status.s_desc AS status,
               tb_ftype.fb_desc AS type,
               tb_member.m_name AS nama 
        FROM tb_feedback
        LEFT JOIN tb_status ON tb_feedback.fb_status=tb_status.s_sid
        LEFT JOIN tb_ftype ON tb_feedback.fb_type=tb_ftype.fb_id
        LEFT JOIN tb_member ON tb_feedback.fb_memberNo=tb_member.m_memberNo
        WHERE tb_feedback.fb_feedbackID='$feedbackID'";

$result=mysqli_query($con,$sql);

$row=mysqli_fetch_assoc($result); 

if (!$result){
    die("Query failed: " . mysqli_error($con));
}

?>

  <style>
    body{
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

    <div class="my-3"></div>

    <h2 style="text-align: center;">Butiran Maklum Balas</h2>

    <div class="my-4"></div>

      <div class="card mb-3 col-10 my-5 mx-auto">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Butiran Maklum Balas
        <button type="button" class="btn btn-info"  onclick="window.location.href='track_feedback.php'">
            Kembali
        </button> 
      </div>
      <div class="card-body">
        <table class="table table-hover">
          <tbody>
            <tr>
              <td scope="row">ID</td>
              <td><?= $row['fb_feedbackID']; ?></td>
            </tr>
            <tr>
              <td scope="row">No. Anggota</td>
              <td><?= $row['fb_memberNo']; ?></td>
            </tr>
            <tr>
              <td scope="row">Nama Anggota</td>
              <td><?= $row['nama']; ?></td>
            </tr>
            <tr>
              <td scope="row">Jenis</td>
              <td><?= $row['type']; ?></td>
            </tr>
            <tr>
              <td scope="row">Kandungan</td>
              <td><?= $row['fb_content']; ?></td>
            </tr>
            <tr>
              <td scope="row">Tarikh Hantar</td>
              <td><?= $row['fb_submitDate']; ?></td>
            </tr>
            <tr>
              <td scope="row">Status</td>
              <td><?= $row['status']; ?></td>
            </tr>
            <tr>
              <td scope="row">No. Pentadbir</td>
              <td><?php echo !empty($row['fb_adminID']) ? $row['fb_adminID'] : 'N/A'; ?></td>
            </tr>
            <tr>
              <td scope="row">Komen Pentadbir</td>
              <td><?php echo !empty($row['fb_comment']) ? $row['fb_comment'] : 'N/A'; ?></td>
            </tr>
            <tr>
              <td scope="row">Tarikh Disemak</td>
              <td><?= !empty($row['fb_editStatusDate']) ? $row['fb_editStatusDate'] : 'N/A'; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

<div class="my-5"></div><br>
  
</body>
</html>

<?php include '../footer.php';?>
