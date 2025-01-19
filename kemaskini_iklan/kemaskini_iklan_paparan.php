<?php 

  include '../header_admin.php';
  include '../db_connect.php';

  // Retrieve latest policy with newest ID
  $sql = "SELECT * FROM tb_banner ORDER BY b_status DESC, b_name ASC";
  $result = mysqli_query($con, $sql);
?>

<!-- Main Content -->
<div class="container">
    <h2>Kemaskini Iklan</h2>
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
                          <?php echo ($row['b_status'] == 1) ? 'Active' : 'Inactive'; ?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnStatus<?php echo $row['b_bannerID']; ?>">
                          <a class="dropdown-item" href="#" onclick="toggleStatus(<?php echo $row['b_bannerID']; ?>, 'active')">Active</a>
                          <a class="dropdown-item" href="#" onclick="toggleStatus(<?php echo $row['b_bannerID']; ?>, 'inactive')">Inactive</a>
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
</div>

<script>
function deleteBanner(bannerID, row) {
    if (confirm("Adakah anda pasti mahu memadamkan sepanduk ini?")) {
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
                alert("Sepanduk berjaya dipadam.");
            } else {
                alert("Ralat memadamkan sepanduk: " + (data.error || 'Unknown error'));
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
                statusButton.textContent = 'Active';
                statusButton.classList.remove('btn-primary');
                statusButton.classList.add('btn-success');
            } else {
                statusButton.textContent = 'Inactive';
                statusButton.classList.remove('btn-success');
                statusButton.classList.add('btn-primary');
            }
            alert("Status berjaya dikemaskini.");
        } else {
            alert("Ralat mengemaskini status: " + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        alert("Ralat: " + error);
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>