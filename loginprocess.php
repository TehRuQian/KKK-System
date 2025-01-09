<?php
session_start();

//connect to DB
include('db_connect.php');

//Retrieve data from form
$funame = $_POST['funame'];
$fpwd = $_POST['fpwd'];


//SQL Retrieve Operation to get user data from db(login)
$sql = "SELECT * FROM tb_user
        WHERE u_id= '$funame' AND u_pwd= '$fpwd'";

//execute SQL
$result= mysqli_query($con, $sql);
//Retrieve data
$row= mysqli_fetch_array($result);
//count result to check
$count= mysqli_num_rows($result);

//Rule-based AI login
if($count == 1) //check if user exist
{
   //set session
   $_SESSION['u_id'] = $row['u_id'];
   $_SESSION['funame'] = $funame;
   
 if ($row['u_type'] == 1) //check user type
 {
    //direct to admin page
   header('Location: admin_main/admin.php');
    
 }
 if ($row['u_type'] == 2) //check user type
 {
    //direct to Member page
   header('Location: member.php');

 }

}

else
{
   // redirect to login error page (individual project)
   header('Location: login_error.php'); 
}

//close connection
mysqli_close($con);

//confirmation registration successful or fail (individual project)



?>