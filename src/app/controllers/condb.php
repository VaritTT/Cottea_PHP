<?php
// $conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
// if (!$conn) {
//     die("Connect Failed: " . mysqli_connect_error());
// }
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: ") . mysqli_connect_error();
}
