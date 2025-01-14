<?php 
session_start();
// Connect to DB
include('dbconnect.php');

//Retrieve data from form
$uid= $_POST['uid'];
$upwd= $_POST['upwd'];

//SQL_Retrieve Operation to get user data from db(login)
$sql="SELECT * FROM tb_user
    WHERE u_id = '$uid' AND u_pwd = '$upwd'";

//execute SQL
$result= mysqli_query($con, $sql);

//Retrieve db
$row= mysqli_fetch_array($result);

//Count result to check
$count= mysqli_num_rows($result);

if($count == 1) //check if data exist
{
    
    $_SESSION['u_id']=session_id();
    $_SESSION['uid'] =$uid;

    
    if($row['u_type'] == 2)  //check user type
    {
        //direct to dashboard_pinjaman Page
        header('Location: dashboard_pinjaman.php');
    }
}

else
{
    // Redirect to login error page
    header('Location: login.php');   //temporary redirect page
}
//close SQL
mysqli_close($con);

