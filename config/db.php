<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = "kasir_sederhana";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $db);

// // Check connection
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }

$koneksi = mysqli_connect($servername, $username, $password, $db);

?>