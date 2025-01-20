<?php
session_start();
include '..\header_reg.php'; // Include your header file
include '..\db_connect.php'; // Include database connection

echo "<script>
Swal.fire({
  icon: 'success',
  title: 'Data telah disimpan.',
});</script>";

// Check if member_id is provided in the URL
if (isset($_GET['member_id']) && !empty($_GET['member_id'])) {
  $member_id = intval($_GET['member_id']); // Sanitize input

  // Fetch member details
  $stmt = $con->prepare("SELECT * FROM tb_member 
  LEFT JOIN tb_ugender ON tb_member.m_gender = tb_ugender.ug_gid
  LEFT JOIN tb_ureligion ON tb_member.m_religion = tb_ureligion.ua_rid
  LEFT JOIN tb_urace ON tb_member.m_race = tb_urace.ur_rid
  LEFT JOIN tb_homestate ON tb_member.m_homeState = tb_homestate.st_id
  LEFT JOIN tb_officestate ON tb_member.m_officeState = tb_officestate.st_id
  WHERE m_memberApplicationID = ?");
  $stmt->bind_param("i", $member_id);
  $stmt->execute();
  $member_result = $stmt->get_result();
  $member_data = $member_result->fetch_assoc();

  // Fetch heirs details
  $stmt = $con->prepare("SELECT * FROM tb_heir 
  LEFT JOIN tb_hrelation ON tb_heir.h_relationWithMember = tb_hrelation.hr_rid
  WHERE h_memberApplicationID = ?");
  $stmt->bind_param("i", $member_id);
  $stmt->execute();
  $heirs_result = $stmt->get_result();
  $heirs_data = $heirs_result->fetch_all(MYSQLI_ASSOC);

  $stmt->close();
} else {
  echo "<div class='alert alert-danger'>No member ID provided.</div>";
  exit;
}
?>

<div class="container mt-4">
  <div class="card mb-3">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
      <span>Maklumat Pemohon</span>
      </button>
    </div>
    <div class="card-body">
      <?php if (!empty($member_data)): ?>
        <table class="table table-hover">
          <tbody>
            <tr>
              <td scope="row">Nama</td>
              <td><?= htmlspecialchars($member_data['m_name']) ?></td>
            </tr>
            <tr>
              <td scope="row">No. Kad Pengenalan</td>
              <td><?= htmlspecialchars($member_data['m_ic']) ?></td>
            </tr>
            <tr>
              <td scope="row">E-mel</td>
              <td><?= htmlspecialchars($member_data['m_email']) ?></td>
            </tr>
            <tr>
              <td scope="row">Jantina</td>
              <td><?= htmlspecialchars($member_data['ug_desc']) ?></td>
            </tr>
            <tr>
              <td scope="row">Agama</td>
              <td><?= htmlspecialchars($member_data['ua_desc']) ?></td>
            </tr>
            <tr>
              <td scope="row">Bangsa</td>
              <td><?= htmlspecialchars($member_data['ur_desc']) ?></td>
            </tr>
            <tr>
              <td scope="row">Alamat Rumah</td>
              <td><?= htmlspecialchars($member_data['m_homeAddress']) ?></td>
            </tr>
            <tr>
              <td scope="row">Bandar</td>
              <td><?= htmlspecialchars($member_data['m_homeCity']) ?></td>
            </tr>
            <tr>
              <td scope="row">Negeri</td>
              <td><?= htmlspecialchars($member_data['st_desc']) ?></td>
            </tr>
            <tr>
              <td scope="row">Poskod</td>
              <td><?= htmlspecialchars($member_data['m_homePostcode']) ?></td>
            </tr>
            <tr>
              <td scope="row">Jawatan</td>
              <td><?= htmlspecialchars($member_data['m_position']) ?></td>
            </tr>
            <tr>
              <td scope="row">Gred Jawatan</td>
              <td><?= htmlspecialchars($member_data['m_positionGrade']) ?></td>
            </tr>
            <tr>
              <td scope="row">No. PF</td>
              <td><?= htmlspecialchars($member_data['m_pfNo']) ?></td>
            </tr>
            <tr>
              <td scope="row">Alamat Pejabat</td>
              <td><?= htmlspecialchars($member_data['m_officeAddress']) ?></td>
            </tr>
            <tr>
              <td scope="row">Bandar Pejabat</td>
              <td><?= htmlspecialchars($member_data['m_officeCity']) ?></td>
            </tr>
            <tr>
              <td scope="row">Negeri Pejabat</td>
              <td><?= htmlspecialchars($member_data['st_desc']) ?></td>
            </tr>
            <tr>
              <td scope="row">Poskod Pejabat</td>
              <td><?= htmlspecialchars($member_data['m_officePostcode']) ?></td>
            </tr>
            <tr>
              <td scope="row">No. Telefon Bimbit</td>
              <td><?= htmlspecialchars($member_data['m_phoneNumber']) ?></td>
            </tr>
            <tr>
              <td scope="row">No. Telefon Rumah</td>
              <td><?= htmlspecialchars($member_data['m_homeNumber']) ?></td>
            </tr>
            <tr>
              <td scope="row">Gaji Sebulan</td>
              <td>RM <?= htmlspecialchars($member_data['m_monthlySalary']) ?></td>
            </tr>
          </tbody>
        </table>
      <?php else: ?>
        <div class="alert alert-danger">Maklumat ahli tidak dijumpai.</div>
      <?php endif; ?>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
      Yuran dan Sumbangan
    </div>
    <div class="card-body">
      <table class="table table-hover">
        <tbody>
          <tr>
            <td scope="row">Yuran Masuk</td>
            <td>RM <?= htmlspecialchars($member_data['m_feeMasuk']) ?></td>
          </tr>
          <tr>
            <td scope="row">Modal Syer</td>
            <td>RM <?= htmlspecialchars($member_data['m_modalSyer']) ?></td>
          </tr>
          <tr>
            <td scope="row">Yuran</td>
            <td>RM <?= htmlspecialchars($member_data['m_modalYuran']) ?></td>
          </tr>
          <tr>
            <td scope="row">Anggota</td>
            <td>RM <?= htmlspecialchars($member_data['m_deposit']) ?></td>
          </tr>
          <tr>
            <td scope="row">Al Abrar</td>
            <td>RM <?= htmlspecialchars($member_data['m_alAbrar']) ?></td>
          </tr>
          <tr>
            <td scope="row">Simpanan Tetap</td>
            <td>RM <?= htmlspecialchars($member_data['m_simpananTetap']) ?></td>
          </tr>
          <tr>
            <td scope="row">Lain-lain</td>
            <td>RM <?= htmlspecialchars($member_data['m_feeLain']) ?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header text-white bg-primary d-flex justify-content-between align-items-center">
      Maklumat Waris
    </div>
    <div class="card-body">
      <?php if (!empty($heirs_data)): ?>
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Nama</th>
              <th>No. Kad Pengenalan</th>
              <th>Hubungan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($heirs_data as $heir): ?>
              <tr>
                <td><?= htmlspecialchars($heir['h_name']) ?></td>
                <td><?= htmlspecialchars($heir['h_ic']) ?></td>
                <td><?= htmlspecialchars($heir['hr_desc']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="alert alert-warning">Tiada maklumat waris tersedia.</div>
      <?php endif; ?>
    </div>
  </div>
 <br>
 <div class="d-flex justify-content-center">
  <button type="button" class="btn btn-primary" id="done"> Selesai </button>
  </div>
  <br><br>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const done = document.getElementById("done");

    // Handle 'Simpan' button click
    done.addEventListener("click", function (e) {
        Swal.fire({
            icon: "success",
            title: "Pemohonan Berjaya",
            text: "Pemohonan anda akan diproses oleh pentadbir dalam tempoh sebulan.",
        }).then((result) => {
            if (result.isConfirmed || result.isDismissed) {
                window.location.href = '../login.php';
            }
        });
    });
});
</script>
