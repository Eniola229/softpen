// In dbcon.php
<?php
$hostname = "localhost";
$username = "root";
$password = "eniolajoshua2022$";
$databaseName = "softpen";

$con = mysqli_connect($hostname, $username, $password, $databaseName);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
