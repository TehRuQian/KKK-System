
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php 

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

include '../header_admin.php';
include '../db_connect.php';


if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:../login.php'); 
}

$u_id = $_SESSION['funame'];

$sql_members="SELECT COUNT(*) AS total_members FROM tb_member WHERE m_status='3'";
$result_members=mysqli_query($con,$sql_members);
if($result_members){
  $member_count=mysqli_fetch_assoc($result_members)['total_members'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_pending_members="SELECT COUNT(*) AS pending_members FROM tb_member WHERE m_status='1'";
$result_pending_members=mysqli_query($con,$sql_pending_members);
if($result_pending_members){
  $pending_member_count=mysqli_fetch_assoc($result_pending_members)['pending_members'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_loan_borrowers="SELECT COUNT(DISTINCT l_memberNo) AS loan_borrowers FROM tb_loan WHERE l_status='3'";
$result_loan_borrowers=mysqli_query($con,$sql_loan_borrowers);
if($result_loan_borrowers){
  $loan_borrower_count=mysqli_fetch_assoc($result_loan_borrowers)['loan_borrowers'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_pending_loans="SELECT COUNT(*) AS pending_loans FROM tb_loan WHERE l_status='1'";
$result_pending_loans=mysqli_query($con,$sql_pending_loans);
if($result_pending_loans){
  $pending_loan_count=mysqli_fetch_assoc($result_pending_loans)['pending_loans'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$currentYear = date("Y");

$sql_pending_member_applications="SELECT COUNT(*) AS pending_member_applications FROM tb_member WHERE m_status='1' AND m_applicationDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_pending_member_applications=mysqli_query($con,$sql_pending_member_applications);
if($result_pending_member_applications){
  $pending_member_applications_count=mysqli_fetch_assoc($result_pending_member_applications)['pending_member_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_rejected_member_applications="SELECT COUNT(*) AS rejected_member_applications FROM tb_member WHERE m_status='2' AND m_approvalDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_rejected_member_applications=mysqli_query($con,$sql_rejected_member_applications);
if($result_rejected_member_applications){
  $rejected_member_applications_count=mysqli_fetch_assoc($result_rejected_member_applications)['rejected_member_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_approved_member_applications="SELECT COUNT(*) AS approved_member_applications FROM tb_member WHERE m_status='3' AND m_approvalDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_approved_member_applications=mysqli_query($con,$sql_approved_member_applications);
if($result_approved_member_applications){
  $approved_member_applications_count=mysqli_fetch_assoc($result_approved_member_applications)['approved_member_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_pending_loan_applications="SELECT COUNT(*) AS pending_loan_applications FROM tb_loan WHERE l_status='1' AND l_applicationDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_pending_loan_applications=mysqli_query($con,$sql_pending_loan_applications);
if($result_pending_loan_applications){
  $pending_loan_applications_count=mysqli_fetch_assoc($result_pending_loan_applications)['pending_loan_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_rejected_loan_applications="SELECT COUNT(*) AS rejected_loan_applications FROM tb_loan WHERE l_status='2' AND l_approvalDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_rejected_loan_applications=mysqli_query($con,$sql_rejected_loan_applications);
if($result_rejected_loan_applications){
  $rejected_loan_applications_count=mysqli_fetch_assoc($result_rejected_loan_applications)['rejected_loan_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_approved_loan_applications="SELECT COUNT(*) AS approved_loan_applications FROM tb_loan WHERE l_status='3' AND l_approvalDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_approved_loan_applications=mysqli_query($con,$sql_approved_loan_applications);
if($result_approved_loan_applications){
  $approved_loan_applications_count=mysqli_fetch_assoc($result_approved_loan_applications)['approved_loan_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_al_bai_loan_applications="SELECT COUNT(*) AS al_bai_loan_applications FROM tb_loan WHERE l_loanType='1' AND l_applicationDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_al_bai_loan_applications=mysqli_query($con,$sql_al_bai_loan_applications);
if($result_al_bai_loan_applications){
  $al_bai_loan_applications_count=mysqli_fetch_assoc($result_al_bai_loan_applications)['al_bai_loan_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_al_innah_loan_applications="SELECT COUNT(*) AS al_innah_loan_applications FROM tb_loan WHERE l_loanType='2' AND l_applicationDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_al_innah_loan_applications=mysqli_query($con,$sql_al_innah_loan_applications);
if($result_al_innah_loan_applications){
  $al_innah_loan_applications_count=mysqli_fetch_assoc($result_al_innah_loan_applications)['al_innah_loan_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_kenderaan_loan_applications="SELECT COUNT(*) AS kenderaan_loan_applications FROM tb_loan WHERE l_loanType='3' AND l_applicationDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_kenderaan_loan_applications=mysqli_query($con,$sql_kenderaan_loan_applications);
if($result_kenderaan_loan_applications){
  $kenderaan_loan_applications_count=mysqli_fetch_assoc($result_kenderaan_loan_applications)['kenderaan_loan_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_road_tax_loan_applications="SELECT COUNT(*) AS road_tax_loan_applications FROM tb_loan WHERE l_loanType='4' AND l_applicationDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_road_tax_loan_applications=mysqli_query($con,$sql_road_tax_loan_applications);
if($result_road_tax_loan_applications){
  $road_tax_loan_applications_count=mysqli_fetch_assoc($result_road_tax_loan_applications)['road_tax_loan_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_khas_loan_applications="SELECT COUNT(*) AS khas_loan_applications FROM tb_loan WHERE l_loanType='5' AND l_applicationDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_khas_loan_applications=mysqli_query($con,$sql_khas_loan_applications);
if($result_khas_loan_applications){
  $khas_loan_applications_count=mysqli_fetch_assoc($result_khas_loan_applications)['khas_loan_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_karnival_loan_applications="SELECT COUNT(*) AS karnival_loan_applications FROM tb_loan WHERE l_loanType='6' AND l_applicationDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_karnival_loan_applications=mysqli_query($con,$sql_karnival_loan_applications);
if($result_karnival_loan_applications){
  $karnival_loan_applications_count=mysqli_fetch_assoc($result_karnival_loan_applications)['karnival_loan_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$sql_al_qadrul_loan_applications="SELECT COUNT(*) AS al_qadrul_loan_applications FROM tb_loan WHERE l_loanType='7' AND l_applicationDate BETWEEN '$currentYear-01-01' AND '$currentYear-12-31'";
$result_al_qadrul_loan_applications=mysqli_query($con,$sql_al_qadrul_loan_applications);
if($result_al_qadrul_loan_applications){
  $al_qadrul_loan_applications_count=mysqli_fetch_assoc($result_al_qadrul_loan_applications)['al_qadrul_loan_applications'];
}
else{
  die("Query failed: ".mysqli_error($con));
}

$months = [
  'JAN'=>'01','FEB'=>'02','MAC'=>'03','APR'=>'04','MEI'=>'05','JUN'=>'06','JUL'=>'07','OGS'=>'08','SEP'=>'09','OCT'=>'10', 'NOV'=>'11','DEC'=>'12'
];

$member_applications=[];
$loan_applications=[];

foreach ($months as $month => $num) {
  $start_date="$currentYear-$num-01";
  $end_date=date("Y-m-t",strtotime($start_date));
  
  $query = "SELECT COUNT(*) AS count FROM tb_member WHERE m_applicationDate BETWEEN '$start_date' AND '$end_date'";
  $result = mysqli_query($con, $query);
  $member_applications[$month] = $result ? mysqli_fetch_assoc($result)['count'] : 0;

  $query = "SELECT COUNT(*) AS count FROM tb_loan WHERE l_applicationDate BETWEEN '$start_date' AND '$end_date'";
  $result = mysqli_query($con, $query);
  $loan_applications[$month] = $result ? mysqli_fetch_assoc($result)['count'] : 0;
}


?>


  <style>
    body {
      background-color: #f9f9f9;
    }

    .container{
      
    }

    tr th:hover {
      background-color: #C7EDE8;
    }

    canvas {
      max-width: 100%;
      height: auto;
      max-height: 100%;
    }

  </style>
</head>
<body>
  
<div class="my-3"></div><br>

<div class="container mt-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Statistic Ringkas</h5><br>
        

        
        <div class="row">
          
          <div class="col-md-3">
            <div class="card">
              <div class="card-body" style="background-color:#FFC3DC;">

                <div class="container mt-3">           
                <img src="../img/member.webp" class="mx-auto d-block rounded-circle" width="70" height="70">
                </div>

                <h1 class="card-title" style="font-size: 45px;"><?= $member_count;?></h1>
                <p class="card-text" data-toggle="tooltip" title="Jumlah anggota semasa yang terdaftar.">Anggota Semasa</p>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card">
              <div class="card-body" style="background-color:#FFD4AE;">

                <div class="container mt-3">           
                <img src="../img/addmember.webp" class="mx-auto d-block rounded-circle" width="70" height="70">
                </div>

                <h1 class="card-title" style="font-size: 45px;"><?= $pending_member_count;?></h1>
                <p class="card-text" data-toggle="tooltip" title="Jumlah orang yang memohon menjadi anggota.">Permohonan Anggota Baru</p>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card">
              <div class="card-body" style="background-color:#C9FFAE;">

                <div class="container mt-3">           
                <img src="../img/loan.png" class="mx-auto d-block rounded-circle" width="70" height="70">
                </div>

                <h1 class="card-title" style="font-size: 45px;"><?= $loan_borrower_count;?></h1>
                <p class="card-text" data-toggle="tooltip" title="Jumlah anggota semasa yang membuat pinjaman.">Peminjam Semasa</p>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card">
              <div class="card-body" style="background-color:#EFAEFF;">

                <div class="container mt-3">           
                <img src="../img/applyloan.webp" class="mx-auto d-block" width="70" height="70">
                </div>

                <h1 class="card-title" style="font-size: 45px;"><?= $pending_loan_count;?></h1>
                <p class="card-text" data-toggle="tooltip" title="Jumlah anggota semasa yang memohon pinjaman.">Permohonan Pinjaman Semasa</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
<div class="my-5"></div>

<div class="container mt-5">

<div class="row justify-content-center">
          
<div class="col-md-4 d-flex justify-content-center"><!-- col-md-4 -->
  <div class="card">
    <div class="card-body" style="background-color:white;">
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="card-title" data-toggle="tooltip" title="Jumlah permohonan menjadi anggota dan permohonan peminjam yang sedang diproses." style="font-size: 20px; ">Statistic Permohonan</h1>
        <!-- <button class="btn btn-outline-primary" type="button">Butiran</button> -->
      </div>
      <p class="card-text">Tahun <?php echo $currentYear; ?></p>
      <canvas id="barChart" style="width:100%; max-width: 400px; height:300px;"></canvas>

<script>
  const bar=document.getElementById('barChart').getContext('2d');
  new Chart(bar, {
    type: 'bar',
    data: {
      labels: ['JAN','FEB','MAC','APR','MEI','JUN','JUL','OGS', 'SEP','OKT','NOV','DIS'],
      datasets: [
        {
          label:'Permohonan Anggota',
          data:[<?= implode(',', $member_applications) ?>],
          backgroundColor: '#4e73df',
        },
        {
          label:'Permohonan Pinjaman',
          data:[<?= implode(',', $loan_applications) ?>],
          backgroundColor: '#a4bdfc',
        }
      ]
    },
    options: {
        responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'bottom'
          }
        }
      }
  });
;
</script>


    </div>
  </div>
</div>



<div class="col-md-4 d-flex justify-content-center"><!-- col-md-4 -->
  <div class="card">
    <div class="card-body" style="background-color:white;">
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="card-title" data-toggle="tooltip" title="Jumlah permohonan menjadi anggota dan permohonan peminjam ikut status permohonan." style="font-size: 20px;">Status Permohonan</h1>
        <!-- <button class="btn btn-outline-primary" type="button">Butiran</button>  -->
      </div>
      <p class="card-text">Tahun <?php echo $currentYear; ?></p>
      <canvas id="donutChart" style="width:100%; max-width: 300px; height:300px;" ></canvas>
      <script>
        const donut= document.getElementById('donutChart').getContext('2d');
        new Chart(donut, {
          type:'doughnut',
          data:{
            labels:['Sedang Diproses','Ditolak','Dilulus'],
            datasets:[{
              data: [<?=$pending_member_applications_count?>+<?=$pending_loan_applications_count?>, <?=$rejected_member_applications_count?>+<?=$rejected_loan_applications_count?>, <?=$approved_member_applications_count?>+<?=$approved_loan_applications_count?>],
              backgroundColor:['#ff6b6b', '#48c774', '#63cdda']
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: true,
                position: 'bottom'
              }
            },
            cutout:'65%',
          }
        });
    </script>

    </div>
  </div>
</div>

<div class="col-md-4 d-flex justify-content-center">
  <div class="card">
    <div class="card-body" style="background-color:white;">
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="card-title" data-toggle="tooltip" title="Jumlah permohonan peminjam ikut jenis peminjaman dimohon." style="font-size: 20px;">Tren Permohonan Pinjaman</h1>
        </div>
        <p class="card-text">Tahun <?php echo $currentYear; ?></p>
        <canvas id="pieChart" style="width:100%; max-width: 300px; height:300px;" ></canvas>
        <script>
          const pie= document.getElementById('pieChart').getContext('2d');
          new Chart(pie, {
            type:'pie',
            data:{
              labels:['Al-Bai','Al-Innah','Baik Pulih Kenderaan','Road Tax dan Insurans','Khas','Karnival Musim Istimewa','Al-Qadrul Hassan'],
              datasets:[{
                data: [<?=$al_bai_loan_applications_count?>,<?=$al_innah_loan_applications_count?>,<?=$kenderaan_loan_applications_count?>,<?=$road_tax_loan_applications_count?>,<?=$khas_loan_applications_count?>,<?=$karnival_loan_applications_count?>,<?=$al_qadrul_loan_applications_count?>],
                backgroundColor:['#FF8A8B','#FFC78A','#FFFC8A','#CBFF8A','#8AD1FF','#948AFF','#EA8AFF'],
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: {
                  display: true,
                  position: 'bottom'
                }
              },
            }
          });
        </script>
  </div>
</div>
</div>
</div>
</div><br><br> 

<div class="my-5"></div>
</div>      
</body>
</html>

