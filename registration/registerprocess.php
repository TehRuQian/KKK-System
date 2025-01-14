<?php
session_start();
include('functions.php');

if (isset($_SESSION['funame']) && !empty($_SESSION['funame'])) {
    $funame = $_SESSION['funame'];
    $fuic = $_SESSION['fuic'];
    $femail = $_SESSION['femail'];
    $fgender = $_SESSION['fgender'];
    $freligion = $_SESSION['freligion'];
    $frace = $_SESSION['frace'];
    $fmariage = $_SESSION['fmariage'];
    $fhomeaddress = $_SESSION['fhomeaddress'];
    $fcity = $_SESSION['fcity'];
    $fstate = $_SESSION['fstate'];
    $fhomezip = $_SESSION['fhomezip'];
    $fposition = $_SESSION['fposition'];
    $fgrade = $_SESSION['fgrade'];
    $fpfno = $_SESSION['fpfno'];
    $fofficeaddress = $_SESSION['fofficeaddress'];
    $fofficecity = $_SESSION['fofficecity'];
    $fofficestate = $_SESSION['fofficestate'];
    $fofficezip = $_SESSION['fofficezip'];
    $ftelno = $_SESSION['ftelno'];
    $fhomeno = $_SESSION['fhomeno'];
    $fsalary = $_SESSION['fsalary'];
    $fstatus = $_SESSION['fstatus'];


    $ffee = $_SESSION['ffee'];
    $fmodal = $_SESSION['fmodal'];
    $fyuran = $_SESSION['fyuran'];
    $fanggota = $_SESSION['fanggota'];
    $fabrar = $_SESSION['fabrar'];
    $ftetap = $_SESSION['ftetap'];
    $fother = $_SESSION['fother'];

    $heirs = [];
    $i = 1;
    
    // Process heirs dynamically
    while (isset($_SESSION["wname$i"]) && (!empty($_SESSION["wname$i"]) || !empty($_SESSION["wic$i"]) || !empty($_SESSION["wrelation$i"]))) {
        if (!empty($_SESSION["wname$i"]) && !empty($_SESSION["wic$i"]) && !empty($_SESSION["wrelation$i"])) {
            $heirs[] = [
                'name' => $_SESSION["wname$i"],
                'ic' => $_SESSION["wic$i"],
                'relation' => $_SESSION["wrelation$i"]
            ];
        }
        $i++;
    }
    

    // If no heirs are found, stop here
    if (empty($heirs)) {
        echo "<script>alert('No heirs data to save.');</script>";
        exit();
    }

    // Database connection
    include('..\db_connect.php');
    $fdate = date('Y-m-d H:i:s');

    // Start transaction
    $con->begin_transaction();

    // Insert into tb_member
    $stmt = $con->prepare("
        INSERT INTO tb_member (
          m_name, m_ic, m_email, m_gender, m_religion, m_race, 
          m_maritalStatus, m_homeAddress, m_homeCity, m_homeState, m_homePostcode, 
          m_position, m_positionGrade, m_pfNo, m_officeAddress, m_officeCity, m_officeState, m_officePostcode, 
          m_phoneNumber, m_homeNumber, m_monthlySalary, m_feeMasuk, m_modalSyer, m_modalYuran, m_deposit, 
          m_alAbrar, m_simpananTetap, m_feeLain, m_applicationDate, m_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssssssssssssssssssssssssssssi",
        $funame,
        $fuic,
        $femail,
        $fgender,
        $freligion,
        $frace,
        $fmariage,
        $fhomeaddress,
        $fcity,
        $fstate,
        $fhomezip,
        $fposition,
        $fgrade,
        $fpfno,
        $fofficeaddress,
        $fofficecity,
        $fofficestate,
        $fofficezip,
        $ftelno,
        $fhomeno,
        $fsalary,
        $ffee,
        $fmodal,
        $fyuran,
        $fanggota,
        $fabrar,
        $ftetap,
        $fother,
        $fdate,
        $fstatus
    );

    if ($stmt->execute()) {
        // Get the last inserted member ID
        $member_id = $con->insert_id;

        // Prepare heir insert statement
        $stmt_heir = $con->prepare("
            INSERT INTO tb_heir (h_memberApplicationID, h_name, h_ic, h_relationWithMember) 
            VALUES (?, ?, ?, ?)
        ");

        if ($stmt_heir) {
            // Insert heirs
            foreach ($heirs as $heir) {
                $stmt_heir->bind_param("isss", $member_id, $heir['name'], $heir['ic'], $heir['relation']);
                if (!$stmt_heir->execute()) {
                    // Rollback on error
                    $con->rollback();
                    echo "<script>alert('Error inserting heir data: " . $stmt_heir->error . "');</script>";
                    exit();
                }
            }

            // Commit the transaction
            $con->commit();

            // Redirect to the display page with the member ID
            header("Location: register_display.php?member_id=" . $member_id);
            // Clear session data and close connection
            session_unset();
            session_destroy();
            mysqli_close($con);
            exit();
        } 
    }
}
?>
