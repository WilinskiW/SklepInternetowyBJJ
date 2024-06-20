<?php
// Ustawienie czasu wygaśnięcia sesji na 30 minut
$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

include '../db_connect.php';

if (!isset($user['user_id'])) {
    header("Location: ../index.php");
}

if (isset($conn)) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM Users WHERE ID = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    throw new Exception("Nie połączono się z bazą");
}

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['reset-submit-input'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['account_type']);
    header("Location: ../index.php");
}

// Obsługa formularza resetowania hasła
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset-text-input'])) {
    $user_id = $_SESSION['user_id']; // Zalogowany użytkownik
    $new_password = password_hash($_POST['reset-text-input'], PASSWORD_DEFAULT); // Nowe hasło

    // Aktualizacja hasła w bazie danych
    $stmt = $conn->prepare("UPDATE Users SET Password = :new_password WHERE ID = :user_id");
    $stmt->execute(['new_password' => $new_password, 'user_id' => $user_id]);

    echo "Hasło zostało zresetowane!";
}

?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/css/logo.css">
    <link rel="stylesheet" href="/css/header_footer.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@300..700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <title>BJJ.com - Twoje konto</title>
</head>
<body>
<header>
    <div class="logo-block">
        <div class="logo">
            <div class="bjj-text">
                <span>BJJ.c</span>
            </div>
            <a id="logo-png" href="../index.php"><img src="../png/logo.png" alt="" width="54" height="50"></a>
            <div class="bjj-text">
                <span>m</span>
            </div>
        </div>
    </div>
</header>
<main>
    <h1>Twoje konto</h1>
    <div class="user-box">
        <div class="user-info">
            <p><label>Imię: </label><?= $user['Firstname'] ?></p>
            <p><label>Nazwisko: </label><?= $user['Lastname'] ?></p>
            <p><label>Email: </label><?= $user['Email'] ?></p>
            <p><label>Adres: </label><?= $user['Address'] ?></p>
            <p><label>Kod pocztowy: </label><?= $user['Postel_Code'] ?></p>
            <form method="post" id="reset-password">
                <label>Zresetuj hasło:</label>
                <input class="input" type="password" name="reset-text-input" required>
                <div class="input">
                    <input class="submit" type="submit" name="reset-submit-input" value="Zatwiedź nowe hasło">
                </div>
            </form>
            <form method="get" id="log-out">
                <div class="input">
                    <input id="log-out-submit" class="submit" type="submit" name="reset-submit-input"
                           value="Wyloguj się">
                </div>
            </form>
        </div>
    </div>
</main>
<footer>
    <p>&COPY; Wszelkie prawa zastrzeżone</p>
</footer>
</body>
</html>
