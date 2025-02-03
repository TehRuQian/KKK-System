<?php 
include('../kkksession.php');
if (!session_id()) {
    session_start();
}

if ($_SESSION['u_type'] != 2) {
    header('Location: ../login.php');
    exit();
  }
  
if (isset($_SESSION['u_id']) != session_id()) {
    header('Location:../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$u_id = $_SESSION['funame'];

function callResult($con, $u_id) {
    $u_id = mysqli_real_escape_string($con, $u_id);
    $sql = "SELECT tb_member.*, 
                   tb_ugender.ug_desc AS gender, 
                   tb_urace.ur_desc AS race, 
                   tb_ureligion.ua_desc AS religion, 
                   tb_umaritalstatus.um_desc AS maritalstatus, 
                   tb_homestate.st_desc AS homeState, 
                   tb_officestate.st_desc AS officeState, 
                   tb_member.m_memberApplicationID
            FROM tb_member
            LEFT JOIN tb_ugender ON tb_member.m_gender=tb_ugender.ug_gid
            LEFT JOIN tb_urace ON tb_member.m_race=tb_urace.ur_rid
            LEFT JOIN tb_ureligion ON tb_member.m_religion=tb_ureligion.ua_rid
            LEFT JOIN tb_umaritalstatus ON tb_member.m_maritalStatus=tb_umaritalstatus.um_mid
            LEFT JOIN tb_homestate ON tb_member.m_homeState=tb_homestate.st_id
            LEFT JOIN tb_officestate ON tb_member.m_officeState=tb_officestate.st_id
            WHERE tb_member.m_memberNo = '$u_id'";

    return mysqli_query($con, $sql);
}

function getHeirs($con, $memberApplicationID) {
    $memberApplicationID = mysqli_real_escape_string($con, $memberApplicationID);
    $sql = "SELECT tb_heir.*,tb_hrelation.hr_desc AS heirrelation
            FROM tb_heir 
            LEFT JOIN tb_hrelation ON tb_heir.h_relationWithMember=tb_hrelation.hr_rid
            WHERE h_memberApplicationID='$memberApplicationID' 
            ORDER BY h_heirID";

    $result=mysqli_query($con,$sql);
    if (!$result){
        die("Query failed: ".mysqli_error($con));
    }
    return $result;
}


function addOrUpdateHeir($con, $memberApplicationID, $heirID,$heirName,$heirIC,$heirRelation) {
    $memberApplicationID=mysqli_real_escape_string($con, $memberApplicationID);
    $heirName=mysqli_real_escape_string($con,$heirName);
    $heirIC=mysqli_real_escape_string($con,$heirIC);
    $heirRelation=mysqli_real_escape_string($con, $heirRelation);

    if (!empty($heirID)){
        $sql = "UPDATE tb_heir SET h_name='$heirName',h_ic='$heirIC',h_relationWithMember='$heirRelation'
                WHERE h_heirID='$heirID'";
    } 

    else{
        $check_sql = "SELECT * FROM tb_heir WHERE h_memberApplicationID='$memberApplicationID' AND h_ic = '$heirIC'";
        $check_result = mysqli_query($con, $check_sql);
        
        if (mysqli_num_rows($check_result) == 0){
            $sql = "INSERT INTO tb_heir (h_memberApplicationID, h_name, h_ic, h_relationWithMember) 
                    VALUES ('$memberApplicationID', '$heirName', '$heirIC', '$heirRelation')";
        } 
        
        else{
            return;
        }
    }

    if (!mysqli_query($con, $sql)){
        die("Query failed: " . mysqli_error($con));
    } 
}

$result = callResult($con, $u_id);
if (!$result) {
    die("Query failed: ".mysqli_error($con));
}

$row =mysqli_fetch_assoc($result);

$memberApplicationID = $row['m_memberApplicationID'];

$heir_result=getHeirs($con,$memberApplicationID);

if (!$heir_result) {
    die("Failed to retrieve heirs: " . mysqli_error($con));
}

$heirCount=mysqli_num_rows($heir_result);


function deleteHeir($con, $heirId,$heirCount) {
    $heirId = mysqli_real_escape_string($con,$heirId);

    if ($heirCount<=3) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Tidak boleh memadam pewaris!',
                text: 'Bilangan pewaris mestilah sekurang-kurangnya 3.',
            });
          </script>";
        return false;
    }
    else{
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Pewaris telah dipadam!',
            });
          </script>";
    }

    $sql = "DELETE FROM tb_heir WHERE h_heirID = '$heirId'";
    $heirCount--;

    if (!mysqli_query($con, $sql)) {
        die("Failed to retrieve heirs: " . mysqli_error($con));
    } 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['heirs'])) {
        foreach ($_POST['heirs'] as $heir) {
            $heirID = isset($heir['id']) ? $heir['id'] : '';
            $name = $heir['name'];
            $ic = $heir['ic'];
            $relation = $heir['relation'];
            addOrUpdateHeir($con, $_POST['memberApplicationID'], $heirID, $name, $ic, $relation);
        }
    }

    if (isset($_POST['delete_heir'])) {
        $heirId = $_POST['delete_heir'];
        deleteHeir($con, $heirId,$heirCount);
    }
}


$result = callResult($con, $u_id);
if (!$result) {
    die("Query failed: ".mysqli_error($con));
}
$row = mysqli_fetch_assoc($result);
$memberApplicationID = $row['m_memberApplicationID']; 

$heir_result = getHeirs($con, $memberApplicationID);
if (!$heir_result) {
    die("Query failed: ".mysqli_error($con));
}

?>

<style>
    body {
        background-color: #f9f9f9;
    }

    .form-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .required {
        color: red;
        font-weight: bold;
    }
</style>

<body>
    <form class="form-container" method="post" action="">
        <fieldset>
            <br>
            <h2 style="text-align: center;">Maklumat Keluarga dan Pewaris</h2>
            
            <?php 
            $count=1;
            while ($heir = mysqli_fetch_assoc($heir_result)){ ?>
                <div class="d-flex align-items-center justify-content-between mt-4">
                    <label class="form-label mt-4"><h5>Maklumat Pewaris <?= $count; ?></h5></label>
                    <button type="submit" class="btn btn-danger mt-4" onclick="confirmDelete(<?= $heir['h_heirID']; ?>)">
                        <i class="fa-solid fa-trash"></i> Padam
                    </button>  
                    
                </div>
                
                <input type="hidden" name="memberApplicationID" value="<?= $memberApplicationID ?>">
                <input type="hidden" name="heirs[<?= $count ?>][id]" value="<?= $heir['h_heirID']; ?>">
                <div>
                    <label class="form-label">Nama <span class="required">*</span></label>
                    <input type="text" class="form-control" name="heirs[<?= $count ?>][name]" value="<?= htmlspecialchars($heir['h_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>
                <div class="my-3">
                    <label class="form-label">No. KP / No. Srt Beranak <span class="required">*</span></label>
                    <input type="text" class="form-control" name="heirs[<?= $count ?>][ic]" value="<?= htmlspecialchars($heir['h_ic'], ENT_QUOTES, 'UTF-8'); ?>"  pattern="\d{6}-\d{2}-\d{4}" required>
                </div>
                <div>
                    <label class="form-label">Hubungan <span class="required">*</span></label>
                    <select class="form-select" name="heirs[<?= $count ?>][relation]" required>
                        <option value="1" <?= $heir['h_relationWithMember'] == 1 ? 'selected' : ''; ?>>Suami Isteri</option>
                        <option value="2" <?= $heir['h_relationWithMember'] == 2 ? 'selected' : ''; ?>>Anak</option>
                        <option value="3" <?= $heir['h_relationWithMember'] == 3 ? 'selected' : ''; ?>>Keturunan</option>
                        <option value="4" <?= $heir['h_relationWithMember'] == 4 ? 'selected' : ''; ?>>Orang Tua</option>
                        <option value="5" <?= $heir['h_relationWithMember'] == 5 ? 'selected' : ''; ?>>Saudara Kandung</option>
                        <option value="6" <?= $heir['h_relationWithMember'] == 6 ? 'selected' : ''; ?>>Lain-lain</option>
                    </select>
                </div>
            <?php $count++; } ?>

            <div id="new-heirs-container"></div>

            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-success mt-4" id="add_heir">Tambah Pewaris</button>
            </div>
            <div class="d-flex justify-content-center">
            <button onclick="confirmation(event)" class="btn btn-primary mt-4">Simpan</button>
            </div>
        </fieldset>
    </form>

    <div class="d-flex justify-content-center">
        <a href="profilmember.php"><button  class="btn btn-primary mt-4">Kembali</button></a>
    </div><br>
    

<script>
    function confirmDelete(heirId) {
        const confirmation = confirm("Adakah anda pasti ingin memadam pewaris ini?");
        if (confirmation) {
            const deleteField = document.createElement("input");
            deleteField.type = "hidden";
            deleteField.name = "delete_heir";
            deleteField.value = heirId;

            const form = document.querySelector("form");
            form.appendChild(deleteField);
            form.submit();
        }
    }
        
        function removeHeir(button) {
            const heirDiv = button.closest('div').parentNode;
            heirDiv.remove();
            heirCount--;
        }

        let heirCount = <?= $count; ?>;

        document.getElementById('add_heir').addEventListener('click', function() {
        let heirDiv = document.createElement('div');
        heirDiv.innerHTML = `
            <div class="d-flex align-items-center justify-content-between mt-4">
                <label class="form-label mt-4"><h5>Maklumat Pewaris Baru</h5></label>
                <button type="submit" class="btn btn-danger mt-4" onclick="removeHeir(this)">
                    <i class="fa-solid fa-trash"></i> Padam
                </button>
            </div> 
            <div>
                <label class="form-label">Nama <span class="required">*</span></label>
                <input type="text" class="form-control" name="heirs[${heirCount +1}][name]" required>
            </div>
            <div class="my-3">
                <label class="form-label">No. KP / No. Srt Beranak <span class="required">*</span></label>
                <input type="text" class="form-control" name="heirs[${heirCount +1}][ic]" pattern="\\d{6}-\\d{2}-\\d{4}" required>
            </div>
            <div>
                <label class="form-label">Hubungan <span class="required">*</span></label>
                <select class="form-select" name="heirs[${heirCount +1}][relation]" required>
                    <option value="1">Suami Isteri</option>
                    <option value="2">Anak</option>
                    <option value="3">Keturunan</option>
                    <option value="4">Orang Tua</option>
                    <option value="5">Saudara Kandung</option>
                    <option value="6">Lain-lain</option>
                </select>
            </div><br>
        `;

        document.getElementById('new-heirs-container').appendChild(heirDiv);
        heirCount++;
    });


        function confirmation(event) {
            const fields = document.querySelectorAll("[required]");
            for (let field of fields) {
                if (field.value.trim() === "") {
                    Swal.fire({
                icon: 'error',
                title: 'Sila isi semua medan yang diperlukan.',
                text: 'Tolong pastikan semua medan yang diperlukan diisi.',
            });
                    event.preventDefault();
                    return false;
                }
                if(!field.checkValidity()){
                    Swal.fire({
                icon: 'error',
                title: `Error: ${field.name || field.placeholder}`,
                text: 'Medan ini adalah wajib atau tidak sah.',
            });
                    event.preventDefault();
                    return false;
                }
            }
            event.preventDefault();

            Swal.fire({
                title: 'Adakah anda pasti?',
                text: 'Maklumat pewaris dan keluarga akan dikemaskini!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, hantar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Maklumat pewaris dan keluarga telah berjaya dikemaskini!',
                        showConfirmButton: true
                    }).then(() => {
                        document.querySelector('form').submit();
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Maklumat pewaris dan keluarga tidak dikemaskini!',
                    }).then(() => {
                        window.location.href='profilmember.php';
                    });
                }
            });
        }
</script> 

</body>
</html>

<?php include '../footer.php'; ?>
