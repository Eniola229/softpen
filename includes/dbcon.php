// In dbcon.php
<?php
$hostname = "localhost";
$username = "root";
$password = "eniolajoshua2022$"; //my db password
$databaseName = "softpen"; //your database name

$con = mysqli_connect($hostname, $username, $password, $databaseName);

//to check if it was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
