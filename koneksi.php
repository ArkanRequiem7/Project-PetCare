<?php
$host     = "localhost";
$username = "root";
$password = "arkan";
$database = "smart_clinic_queue";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>