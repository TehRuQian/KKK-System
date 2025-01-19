<?php 
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

  include '../header_admin.php';
  include '../db_connect.php';

  $records_per_page = 5;  
  $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
  $start_from = ($current_page - 1) * $records_per_page;

  // Retrieve latest banner with newest ID
  $sql = "SELECT * FROM tb_banner 
          ORDER BY b_bannerID ASC 
          LIMIT $start_from, $records_per_page";
//   $sql = "SELECT * FROM tb_banner 
//           ORDER BY b_status DESC, b_bannerID ASC 
//           LIMIT $start_from, $records_per_page";
  $result = mysqli_query($con, $sql);

  $total_sql = "SELECT COUNT(*) FROM tb_banner;";
  $total_result = mysqli_query($con, $total_sql);
  $total_row = mysqli_fetch_row($total_result);
  $total_records = $total_row[0];
  $total_pages = ceil($total_records / $records_per_page);
?>

<!-- Main Content -->
<div class="container">
    <h2>Kemaskini Iklan</h2>

    <div class="alert alert-warning" role="alert" id="active-banner-warning" style="display:none;">
        Tiada iklan aktif. Sila pastikan sekurang-kurangnya satu iklan diaktifkan.
    </div>

    <form method="POST" action="kemaskini_iklan_paparan_process.php">
        <table class="table table-hover" style="text-align: center;">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th style="text-align: center;">Iklan</th>
                    <th>Status</th>
                    <th>Tindakan</th>
                </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td>
                      <p><?php echo $row['b_name']; ?></p>
                    </td>
                    <td style="text-align: center;">
                      <img src="../img/iklan/<?php echo $row['b_banner']; ?>" alt="Banner Image" style="width: 300px; height: auto;">
                    </td>
                    <td>
                      <div class="btn-group" role="group">
                        <button type="button" class="btn btn-<?php echo ($row['b_status'] == 1) ? 'success' : 'primary'; ?> dropdown-toggle" id="btnStatus<?php echo $row['b_bannerID']; ?>" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <?php echo ($row['b_status'] == 1) ? 'Aktif' : 'Tidak aktif'; ?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnStatus<?php echo $row['b_bannerID']; ?>">
                          <a class="dropdown-item" href="#" onclick="toggleStatus(<?php echo $row['b_bannerID']; ?>, 'active')">Mengaktifkan</a>
                          <a class="dropdown-item" href="#" onclick="toggleStatus(<?php echo $row['b_bannerID']; ?>, 'inactive')">Menyahaktifkan</a>
                        </div>
                      </div>
                    </td>
                    <td>
                    <button type="button" class="btn btn-danger" onclick="deleteBanner(<?php echo $row['b_bannerID']; ?>, this.closest('tr'))">
                        <i class="fa fa-trash" aria-hidden="true"></i> Padam
                    </button>
                    </td>
                  </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>

    <nav>
      <ul class="d-flex justify-content-center pagination pagination-sm">
        <?php if($current_page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $current_page - 1; ?>">&laquo;</a>
          </li>
        <?php endif; ?>

        <?php for($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
          </li>
        <?php endfor; ?>

        <?php if($current_page < $total_pages): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $current_page + 1; ?>">&raquo;</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>

    <div class="d-flex justify-content-center">
        <button type="button" class="btn btn-primary" onclick="window.location.href='kemaskini_iklan.php'">Kembali</button>
    </div>
    <br>
</div>

<script>
function deleteBanner(bannerID, row) {
    if (confirm("Adakah anda pasti mahu memadamkan iklan ini?")) {
        fetch('kemaskini_iklan_paparan_process.php', {
            method: 'POST',
            body: new URLSearchParams({
                action: 'delete',
                bannerID: bannerID
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.remove();
                alert("Iklan berjaya dipadam.");
                checkActiveBanners();
            } else {
                alert("Ralat memadamkan iklan: " + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            alert("Ralat: " + error);
        });
    }
}

function toggleStatus(bannerID, status) {
    // Perform a fetch request to update the status
    fetch('kemaskini_iklan_paparan_process.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'update_status',
            bannerID: bannerID,
            status: (status === 'active') ? 1 : 0
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the dropdown text and button class
            let statusButton = document.getElementById('btnStatus' + bannerID);
            if (status === 'active') {
                statusButton.textContent = 'Aktif';
                statusButton.classList.remove('btn-primary');
                statusButton.classList.add('btn-success');
            } else {
                statusButton.textContent = 'Tidak aktif';
                statusButton.classList.remove('btn-success');
                statusButton.classList.add('btn-primary');
            }
            alert("Status berjaya dikemaskini.");
            checkActiveBanners();
        } else {
            alert("Ralat mengemaskini status: " + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        alert("Ralat: " + error);
    });
}

document.addEventListener('DOMContentLoaded', function() { checkActiveBanners(); });

function checkActiveBanners() {
    fetch('kemaskini_iklan_paparan_process.php', {
        method: 'POST',
        body: new URLSearchParams({
            action: 'check_active_banners'
        })
    })
    .then(response => response.json())
    .then(data => {
        const warningElement = document.getElementById('active-banner-warning');
        
        if (data.active_banners_count == 0) {
            warningElement.style.display = 'block';
        } else {
            warningElement.style.display = 'none';
        }
    })
    .catch(error => {
        alert("Ralat: " + error);
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>