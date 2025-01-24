<?php
// Set DB Parameter
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_kkk1 21.01 11.11pm (demo version)";


// Connect DB
$con = mysqli_connect($servername, $username, $password, $dbname);

// Connection Check (continue as indivual project)
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
  }
?>