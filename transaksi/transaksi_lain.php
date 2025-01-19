<?php 
  include('../kkksession.php');
  if (!session_id()) {
      session_start();
  }

  include '../header_admin.php';
  include '../db_connect.php';
  $admin_id = $_SESSION['u_id'];

  $financial = null;
  if (isset($_POST['f_memberNo']) && !empty($_POST['f_memberNo'])){
    $memberNo = $_POST['f_memberNo'];
    // $memberNo = 1;

    $sql = "
        SELECT tb_financial.*, tb_member.m_name, tb_member.m_ic, tb_member.m_pfNo
        FROM tb_financial
        INNER JOIN tb_member
        ON tb_financial.f_memberNo=tb_member.m_memberNo
        WHERE f_memberNo = '$memberNo';";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) == 1){
        $financial = mysqli_fetch_assoc($result);
    }
  }

?>

<style>
  table td, table th {
    vertical-align: middle; 
  }
</style>

<div class="container">
  <h2>Transaksi Lain</h2>
  <form method="POST" action="">
    <div>
      <label class="form-label mt-4">No Anggota</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="f_memberNo" value="">
      </div>
    </div>
  </form>

  <?php if($financial){ ?>
    
    <h5>Maklumat Anggota</h5>
    <table class="table table-hover">
        <tr>
            <td>Name</td>
            <td><?php echo ($financial['m_name']) ?></td>
        </tr>
        <tr>
            <td>IC</td>
            <td><?php echo ($financial['m_ic']) ?></td>
        </tr>
        <tr>
            <td>No. Anggota</td>
            <td><?php echo ($financial['f_memberNo']) ?></td>
        </tr>
        <tr>
            <td>No. PF</td>
            <td><?php echo ($financial['m_pfNo']) ?></td>
        </tr>
    </table>

    <h5>Maklumat Kewangan</h5>
    <form method="POST" action="transaksi_lain_process.php">
        <input type="hidden" name="memberNo" value="<?php echo ($financial['f_memberNo']) ?>">
        <table class="table table-hover">
        <tr>
            <th> </th>
            <th>Status Semasa</th>
            <th>Perubahan</th>
            <th>Status Baru</th>
        </tr>
        <tr>
            <th>Modal Syer</th>
            <td id="shareCapital"><?php echo number_format($financial['f_shareCapital'], 2); ?></td>
            <td><input type="text" class="form-control" id="shareCapitalChange" name="shareCapitalChange" value="0.00"></td>
            <td id="shareCapitalNew"><?php echo number_format($financial['f_shareCapital'], 2); ?></span></td>
        </tr>
        <tr>
            <th>Modal Yuran</th>
            <td id="feeCapital"><?php echo number_format($financial['f_feeCapital'], 2); ?></td>
            <td><input type="text" class="form-control" id="feeCapitalChange" name="feeCapitalChange" value="0.00"></td>
            <td id="feeCapitalNew"><?php echo number_format($financial['f_feeCapital'], 2); ?></span></td>
        </tr>
        <tr>
            <th>Simpanan Tetap</th>
            <td id="fixedSaving"><?php echo number_format($financial['f_fixedSaving'], 2); ?></td>
            <td><input type="text" class="form-control" id="fixedSavingChange" name="fixedSavingChange" value="0.00"></td>
            <td id="fixedSavingNew"><?php echo number_format($financial['f_fixedSaving'], 2); ?></span></td>
        </tr>
        <tr>
            <th>Simpanan Anggota</th>
            <td id="memberSaving"><?php echo number_format($financial['f_memberSaving'], 2); ?></td>
            <td><input type="text" class="form-control" id="memberSavingChange" name="memberSavingChange" value="0.00"></td>
            <td id="memberSavingNew"><?php echo number_format($financial['f_memberSaving'], 2); ?></span></td>
        </tr>
        <tr>
            <th>Tabung Anggota</th>
            <td id="memberFund"><?php echo number_format($financial['f_memberFund'], 2); ?></td>
            <td><input type="text" class="form-control" id="memberFundChange" name="memberFundChange" value="0.00"></td>
            <td id="memberFundNew"><?php echo number_format($financial['f_memberFund'], 2); ?></span></td>
        </tr>
        <tr>
            <th>Al-Bai</th>
            <td id="alBai"><?php echo number_format($financial['f_alBai'], 2); ?></td>
            <td><input type="text" class="form-control" id="alBaiChange" name="alBaiChange" value="0.00"></td>
            <td id="alBaiNew"><?php echo number_format($financial['f_alBai'], 2); ?></span></td>
        </tr>
        <tr>
            <th>Al-Innah</th>
            <td id="alInnah"><?php echo number_format($financial['f_alInnah'], 2); ?></td>
            <td><input type="text" class="form-control" id="alInnahChange" name="alInnahChange" value="0.00"></td>
            <td id="alInnahNew"><?php echo number_format($financial['f_alInnah'], 2); ?></span></td>
        </tr>
        <tr>
            <th>B/Pulih Kenderaan</th>
            <td id="bPulihKenderaan"><?php echo number_format($financial['f_bPulihKenderaan'], 2); ?></td>
            <td><input type="text" class="form-control" id="bPulihKenderaanChange" name="bPulihKenderaanChange" value="0.00"></td>
            <td id="bPulihKenderaanNew"><?php echo number_format($financial['f_bPulihKenderaan'], 2); ?></span></td>
        </tr>
        <tr>
            <th>Road Tax & Insuran</th>
            <td id="roadTaxInsurance"><?php echo number_format($financial['f_roadTaxInsurance'], 2); ?></td>
            <td><input type="text" class="form-control" id="roadTaxInsuranceChange" name="roadTaxInsuranceChange" value="0.00"></td>
            <td id="roadTaxInsuranceNew"><?php echo number_format($financial['f_roadTaxInsurance'], 2); ?></span></td>
        </tr>
        <tr>
            <th>Khas</th>
            <td id="specialScheme"><?php echo number_format($financial['f_specialScheme'], 2); ?></td>
            <td><input type="text" class="form-control" id="specialSchemeChange" name="specialSchemeChange" value="0.00"></td>
            <td id="specialSchemeNew"><?php echo number_format($financial['f_specialScheme'], 2); ?></span></td>
        </tr>
        <tr>
            <th>Karnival Musim Istimewa</th>
            <td id="specialSeasonCarnival"><?php echo number_format($financial['f_specialSeasonCarnival'], 2); ?></td>
            <td><input type="text" class="form-control" id="specialSeasonCarnivalChange" name="specialSeasonCarnivalChange" value="0.00"></td>
            <td id="specialSeasonCarnivalNew"><?php echo number_format($financial['f_specialSeasonCarnival'], 2); ?></span></td>
        </tr>
        <tr>
            <th>Al-Qadrul Hassan</th>
            <td id="alQadrulHassan"><?php echo number_format($financial['f_alQadrulHassan'], 2); ?></td>
            <td><input type="text" class="form-control" id="alQadrulHassanChange" name="alQadrulHassanChange" value="0.00"></td>
            <td id="alQadrulHassanNew"><?php echo number_format($financial['f_alQadrulHassan'], 2); ?></span></td>
        </tr>
        </table>
        <div>
          <label class="form-label mt-4">Ulasan</label>
            <input type="text" class="form-control" name="f_desc" required>
        </div>
        <br>
        <div class="d-flex justify-content-center">
          <button type="submit" class="btn btn-primary">Hantar</button>
        </div>
    </form>
  <br>
  <?php } ?>
</div>

<script>
  function calculateNewValue(currentID, changeID, newID) {
    const current = parseFloat(document.getElementById(currentID).textContent.replace(/,/g, '') || 0);
    const change = parseFloat(document.getElementById(changeID).value || 0);
    const newValue = current + change;
    document.getElementById(newID).textContent = newValue.toFixed(2);
  }

  document.querySelectorAll('[id$="Change"]').forEach(function(input) {
    input.addEventListener('input', function(e) {
      const id = e.target.id.replace('Change', ''); // Remove "Change" suffix
      calculateNewValue(id, e.target.id, id + 'New');
    });
  });
</script>