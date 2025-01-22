<?php 
  include('../kkksession.php');
  if (!session_id()) {
      session_start();
  }

  if ($_SESSION['u_type'] != 1) {
    header('Location: ../login.php');
    exit();
  }
  
  include '../header_admin.php';
  include '../db_connect.php';
  $admin_id = $_SESSION['u_id'];

  $totalAmount = 0;
  $financial = null;
  if (isset($_POST['f_memberNo']) && !empty($_POST['f_memberNo'])){
    $memberNo = $_POST['f_memberNo'];

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

    $sql_loan = "SELECT tb_loan.*, tb_ltype.lt_desc
                 FROM tb_loan 
                 JOIN tb_ltype ON tb_loan.l_loanType = tb_ltype.lt_lid
                 WHERE tb_loan.l_memberNo = $memberNo AND tb_loan.l_status = 3;";
    $result_loan = mysqli_query($con, $sql_loan);
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

    <h5>Maklumat Saham Ahli</h5>
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
        </table>
        <?php
        if(mysqli_num_rows($result_loan) > 0){
          echo "<h5>Maklumat Pinjaman Ahli</h5>";
          echo "<table class='table table-hover'>
                  <tr>
                      <th>ID</th>
                      <th>Pinjaman</th>
                      <th>Tunggakan Semasa</th>
                      <th>Ansuran Bulanan</th>
                      <th>Bayaran</th>
                      <th>Tunggakan Baharu</th>
                  </tr>";
          while($row = mysqli_fetch_assoc($result_loan)){
              $newLoanPayable = $row['l_loanPayable'];
              echo "<tr>";
                  echo "<td>" . $row['l_loanApplicationID'] . "</td>";
                  echo "<td>" . $row['lt_desc'] . "</td>";
                  echo "<td id='loanPayable_" . $row['l_loanApplicationID'] . "'>" . number_format($row['l_loanPayable'], 2) . "</td>";
                  echo "<td>" . $row['l_monthlyInstalment'] . "</td>";
                  echo "<td><input type='number' class='form-control' id='payment_" . $row['l_loanApplicationID'] . "' name='payment[" . $row['l_loanApplicationID'] . "]' value='0.00' min='0' step='0.01' data-max='" . $row['l_loanPayable'] . "' oninput='validateLoanPayment(" . $row['l_loanApplicationID'] . ")'></td>";
                  echo "<td id='newLoan_" . $row['l_loanApplicationID'] . "'>" . number_format($newLoanPayable, 2) . "</td>";
              echo "</tr>";
          }
          echo"</table>";
        }
        ?>
        <div>
          <p id="totalAmount">Jumlah Bayaran: RM 0.00</p>
        </div>
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
  let totalAmount = 0;
  function calculateNewValue(currentID, changeID, newID) {
    const current = parseFloat(document.getElementById(currentID).textContent.replace(/,/g, '') || 0);
    const change = parseFloat(document.getElementById(changeID).value || 0);
    const newValue = current + change;
    document.getElementById(newID).textContent = newValue.toFixed(2);
    updateTotalAmount();
  }

  document.querySelectorAll('[id$="Change"]').forEach(function(input) {
    input.addEventListener('input', function(e) {
      const id = e.target.id.replace('Change', ''); // Remove "Change" suffix
      calculateNewValue(id, e.target.id, id + 'New');
    });
  });

  function validateLoanPayment(loanID) {
    const loanPayable = parseFloat(document.getElementById('loanPayable_' + loanID).textContent.replace(/,/g, ''));
    let payment = parseFloat(document.getElementById('payment_' + loanID).value);
    
    // If exceed loan payable
    if (payment > loanPayable) {
      payment = loanPayable;
      document.getElementById('payment_' + loanID).value = payment.toFixed(2);
    }

    let newLoanPayable = loanPayable - payment;
    document.getElementById('newLoan_' + loanID).textContent = newLoanPayable.toFixed(2);
    updateTotalAmount();
  }

  function updateTotalAmount(){
    totalAmount = 0;

    const fieldsToSum = [
      'shareCapitalChange', 'feeCapitalChange', 'fixedSavingChange', 
      'memberSavingChange', 'memberFundChange'
    ];

    fieldsToSum.forEach(function(field) {
      const value = parseFloat(document.getElementById(field).value || 0);
      totalAmount += value;
    });

    const loanPayments = document.querySelectorAll('[id^="payment_"]');
    loanPayments.forEach(function(input) {
      const payment = parseFloat(input.value || 0);
      totalAmount += payment;
    });

    document.getElementById('totalAmount').textContent = 'Jumlah Bayaran: RM ' + totalAmount.toFixed(2);
  }
</script>
