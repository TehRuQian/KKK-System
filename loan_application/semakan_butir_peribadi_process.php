<?php
include('../kkksession.php');
if(!session_id())
{
  session_start();
}

if(isset($_SESSION['u_id']) != session_id())
{
  header('Location:../login.php'); 
}

include '../headermember.php';
include '../db_connect.php';
$memberNo = $_SESSION['funame'];

// member personal data
$nama = $_POST['nama']; 
$noKad = $_POST['noKad'];
$selectedGender = $_POST['ugender'];
$selectedReligion = $_POST['ureligion'];
$selectedRace = $_POST['urace'];
$selectedMaritalRace = $_POST['umaritalStatus'];
$selectedMonthlySalary = $_POST['gajiBulanan'];
// Alamat Rumah
$selectedHomeAdd = $_POST['alamatRumah'];
$selectedHomePostcode = $_POST['postcodeRumah'];
$selectedHomeCity = $_POST['cityRumah'];
$selectedHomeState = $_POST['stateRumah'];
// Jawatan
$selectedPositionGrade = $_POST['jawatanGred'];
$selectedPosition = $_POST['jawatan'];
// Alamat Pejabat 
$selectedOfficeAdd = $_POST['alamatPejabat'];
$selectedOfficePostcode = $_POST['postcodePejabat'];
$selectedOfficeCity = $_POST['cityPejabat'];
$selectedOfficeState = $_POST['statePejabat'];
// Telephone
$selectedNoTelePej = $_POST['noTeleRum'];
$selectedNoTeleBim = $_POST['noTeleBim'];

// Update the user's data in the database
if (!empty($selectedGender) && !empty($memberNo)) {
    $sql = "UPDATE tb_member 
            SET m_name = '$nama', 
                m_gender = '$selectedGender', 
                m_religion = '$selectedReligion', 
                m_race = '$selectedRace', 
                m_maritalStatus = '$selectedMaritalRace',
                m_monthlySalary = '$selectedMonthlySalary',

                m_homeAddress = '$selectedHomeAdd', 
                m_homePostcode = '$selectedHomePostcode',
                m_homeCity = '$selectedHomeCity',
                m_homeState = '$selectedHomeState',

                m_position = '$selectedPosition',
                m_positionGrade = '$selectedPositionGrade',

                m_officeAddress = '$selectedOfficeAdd',
                m_officePostcode = '$selectedOfficePostcode',
                m_officeCity = '$selectedOfficeCity',
                m_officeState = '$selectedOfficeState',

                m_phoneNumber = '$selectedNoTeleBim', 
                m_homeNumber = '$selectedNoTelePej' 
            WHERE m_memberNo = '$memberNo'";
    
    if (mysqli_query($con, $sql)) {
  
        header('Location: semakan_butiran.php');
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
} else {
    echo "Error: Missing member number.";
}

//close SQL
mysqli_close($con);
?>