<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sklep_internetowy_bjj";
$charset = "utf8";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=$charset", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Błąd połączenia: " . $e->getMessage();
}
?>
