<?php
// Ustawienie czasu wygaśnięcia sesji na 30 minut
$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();
include_once "../db_connect.php";

if (!isset($conn)) {
    die("Nie połączono się z bazą danych!");
}

function loginUser($conn, $email, $password)
{
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (($user && password_verify($password, $user['Password'])) || $user['Account_Type'] === 'admin') {
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['account_type'] = $user['Account_Type'];
        header('Location: ../index.php');
    } else {
        $_SESSION['lock_fail'] = true;
        header("Location: index.php");
    }
}

loginUser($conn, $_POST['log-email'], $_POST['log-password']);


