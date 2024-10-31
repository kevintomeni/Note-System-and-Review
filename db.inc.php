<?php
$servername = "localhost";
$dbusername ="root";
$dbpss="";
$dbname= "ratingsystem";

$conn = mysqli_connect($servername,$dbusername,$dbpss,$dbname);

if (!$conn) {
    die("connection to the database failed".mysqli_connect_error());
}
?>