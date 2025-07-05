<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Langkaan2_Evacuation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>