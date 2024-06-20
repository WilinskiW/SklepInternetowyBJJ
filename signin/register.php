<?php
// Ustawienie czasu wygaśnięcia sesji na 30 minut
$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();
include_once "../db_connect.php";

if (!isset($conn)) {
    die("Nie połączono się z bazą danych!");
}

$firstname = $_POST['register-firstname'];
$lastname = $_POST['register-lastname'];
$email = $_POST['register-email'];
$address = $_POST['register-address'];
$postal_code = $_POST['register-postal_code'];
$password = $_POST['register-password'];
$hash = password_hash($_POST['register-password'], PASSWORD_DEFAULT);


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit();
}

$stmt = $conn->prepare("
    INSERT INTO Users (Firstname, Lastname, Email, Address, Postel_Code, Password) 
    VALUES (:firstname, :lastname, :email, :address, :postal_code, :password)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':address', $address);
$stmt->bindParam(':postal_code', $postal_code);
$stmt->bindParam(':password', $hash);
$stmt->execute();
session_abort();
header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
