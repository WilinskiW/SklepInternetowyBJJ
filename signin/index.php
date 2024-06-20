<?php
// Ustawienie czasu wygaśnięcia sesji na 30 minut
$expire = 30*60; // 30 minut
session_set_cookie_params($expire);
session_start();

include_once '../db_connect.php';
include_once 'User.php';

$user = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['log-email']) && isset($_POST['log-password'])) {
        $user->loginUser($_POST['log-email'], $_POST['log-password']);
    } elseif (isset($_POST['register-firstname'])
        && isset($_POST['register-lastname'])
        && isset($_POST['register-email'])
        && isset($_POST['register-address'])
        && isset($_POST['register-postal_code'])
        && isset($_POST['register-password'])) {
        $user->registerUser($_POST['register-firstname'],
            $_POST['register-lastname'], $_POST['register-email'],
            $_POST['register-address'], $_POST['register-postal_code'],
            password_hash($_POST['register-password'], PASSWORD_DEFAULT));
    }
}
?>

<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BJJ.com - Zaloguj się/Zarejestruj się</title>
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
    <div class="container-row">
        <div id="login" class="container-block">
            <h1>Zaloguj się</h1>
            <div class="form-block">
                <form method="post" id="login-form-input" class="form-input">
                    <div class="input-text">
                        <input type="text" id="log-login" class="input" name="log-email" placeholder="Email"
                               autocomplete="off" required>
                    </div>
                    <div class="input-text">
                        <input type="password" id="log-password" class="input" name="log-password" placeholder="Hasło"
                               autocomplete="off" required>
                    </div>
                    <?php if(isset($_SESSION['lock_fail']) && $_SESSION['lock_fail']){ ?>
                        <div id="login-error" style="color: red; font-weight: bold;">Błędny login lub hasło!</div>
                        <?php
                        unset($_SESSION['lock_fail']);
                    }
                    ?>
                    <div class="input-submit">
                        <input id="login-submit" class="submit" type="submit" value="Zaloguj się">
                    </div>
                </form>
            </div>
        </div>
        <div id="register" class="container-block">
            <h1>Zarejestruj się</h1>
            <div class="form-block">
                <form method="post" id="register-form-input" class="form-input">
                    <div class="input-text">
                        <input type="text" class="input" name="register-firstname" placeholder="Imię"
                               autocomplete="off" required>
                    </div>
                    <div class="input-text">
                        <input type="text" class="input" name="register-lastname" placeholder="Nazwisko"
                               autocomplete="off" required>
                    </div>
                    <div class="input-text">
                        <input type="text" class="input" name="register-email" placeholder="Email"
                               autocomplete="off" required>
                    </div>
                    <div class="input-text">
                        <input type="text" class="input" name="register-address" placeholder="Adres zamieszkania (Ulica)"
                               autocomplete="off" required>
                    </div>
                    <div class="input-text">
                        <input type="text" class="input" name="register-postal_code" placeholder="Kod pocztowy"
                               autocomplete="off" required>
                    </div>
                    <div class="input-text">
                        <input type="password" id="log-password" class="input" name="register-password" placeholder="Hasło"
                               autocomplete="off" required>
                    </div>
                    <div class="input-submit">
                        <input id="reqister-submit" class="submit" type="submit" value="Zarejestruj się">
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<footer>
    <p>&COPY; Wszelkie prawa zastrzeżone</p>
</footer>
</body>
</html>
