<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_estudos";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Definir o charset para utf8
$conn->set_charset("utf8");
?>