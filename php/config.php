<?php

$dbhost = "localhost";
$port = "3307";
$dbuser = "root";
$dbpass = "12345";
$dbname = "quickgomart_db";

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $port);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


