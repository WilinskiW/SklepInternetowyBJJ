<?php
// Ustawienie czasu wygaśnięcia sesji na 30 minut
$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

include_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['reset-submit-input'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['account_type']);
    header("Location: ../index.php");
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel admina</title>
</head>
<body>
<form method="get" id="log-out">
    <div class="input">
        <input id="log-out-submit" class="submit" type="submit" name="reset-submit-input" value="Wyloguj się">
    </div>
</form>

<form method="post">
    <div class="input">
        <input id="dump-db" class="submit" type="submit" name="dump-db" value="Zrób zrzut bazy danych">
    </div>
</form>
</body>
</html>
