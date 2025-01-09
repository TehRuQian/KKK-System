<?php

include('../kkksession.php');
if(!session_id())
{
  session_start();
}

include '../header_admin.php';
include '../db_connect.php';

$mApplicationID = $_GET['id'];
?>

<div class="container">
<h2>Maklumat Pemohon</h2>
<form method="POST" action="member_approval_process.php">
    <input type="hidden" name="mApplicationID" value="<?php echo $mApplicationID; ?>">
    <fieldset>
        <div>
            <label class="form-label mt-4">Status Anggota</label>
            <select class="form-select" name="mstatus">
            <?php
            $sql="SELECT * FROM tb_status";
            $result=mysqli_query($con,$sql);

            while($row=mysqli_fetch_array($result))
            {
            echo"<option value='".$row['s_sid']."'>".$row['s_desc']."</option>";
            }
            ?>
            </select>
        </div><br>
        <button type="submit" class="btn btn-primary">Hantar</button>


    </fieldset>
</form>
</div>

