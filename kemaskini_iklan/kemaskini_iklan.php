<?php 
  include '../header_admin.php';
  include '../db_connect.php';

  // Retrieve latest policy with newest ID
  $sql = "SELECT * FROM tb_banner;";
  $banners = mysqli_query($con, $sql);
?>

<!-- Main Content -->
<div class="container">
    <h2>Kemaskini Iklan</h2>

    <div class="card mb-3">
      <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
        Iklan Semasa
        <button type="button" class="btn btn-info"  onclick="window.location.href='kemaskini_iklan_paparan.php'">
            Kemaskini
        </button>
      </div>
      <div class="card-body">
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php while ($row = mysqli_fetch_assoc($banners)) { ?>
                    <?php if ($row['b_status'] == 1) { ?>
                    <div class="carousel-item active">
                    <img src="../img/iklan/<?php echo $row['b_banner']; ?>" class="d-block w-100" alt="Banner Image" style="height: 450px; width: 100%; object-fit: cover;">
                    </div>
                <?php }} ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
      </div>
    </div>


    <h5>Muat Naik Iklan Baru</h5>
    <form method="POST" action="kemaskini_iklan_muat_naik.php" enctype="multipart/form-data">
      <div>
        <label class="form-label mt-4">Banner Baru</label>
        <input type="text" class="form-control" name="bannerName" required>
        <label for="formFile" class="form-label mt-4">Muat Naik Iklan Baharu</label>
        <input class="form-control" type="file" id="banner" name="banner" accept="image/*" required>
      </div>

      <br>

      <div class="d-flex justify-content-center">
          <button type="submit" class="btn btn-primary">Muat Naik</button>
      </div>
    </form>
</div>