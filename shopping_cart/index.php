<?php
// Rozpoczęcie sesji
session_start();

// Dołączenie skryptu do połączenia z bazą danych
include '../db_connect.php';

// Obsługa dodawania produktu do koszyka
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['product_id'])) {
    $user_id = $_SESSION['user_id']; // Zalogowany użytkownik
    $product_id = $_GET['product_id']; // ID produktu do dodania

    // Sprawdzenie, czy produkt jest już w koszyku
    $stmt = $conn->prepare("SELECT * FROM Shopping_cart WHERE Users_ID = :user_id AND Products_ID = :product_id");
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        // Produkt jest już w koszyku, zaktualizuj ilość
        $new_quantity = $item['Quantity'] + 1;
        $stmt = $conn->prepare("UPDATE Shopping_cart SET Quantity = :quantity WHERE Users_ID = :user_id AND Products_ID = :product_id");
        $stmt->execute(['quantity' => $new_quantity, 'user_id' => $user_id, 'product_id' => $product_id]);
    } else {
        // Produktu nie ma jeszcze w koszyku, dodaj nowy wpis
        $stmt = $conn->prepare("INSERT INTO Shopping_cart (Users_ID, Products_ID, Quantity) VALUES (:user_id, :product_id, 1)");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    }

    echo "Produkt dodany do koszyka!";
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep BJJ - najlepszy sprzęt sportowy do brazyliskiego jiu jitsu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/product_gallery.css">
    <link rel="stylesheet" href="/css/logo.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@300..700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <script src="https://kit.fontawesome.com/ea8ae8bb67.js" crossorigin="anonymous"></script>
</head>
<body>
<header class="main-panel">
    <div id="header-contact">
        <div id="phone-number-contact" class="contact-type">
            <i class="fa-solid fa-phone"></i>
            <a href="tel:732 071 830">732 071 830</a>
        </div>
        <div id="email-contact" class="contact-type">
            <i class="fa-solid fa-envelope"></i>
            <a href="mailto:sklep@bjj.com">sklep@bjj.com</a>
        </div>
    </div>
    <div id="menu-search">
        <div id="search-block">
            <link rel="stylesheet"
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <form class="search-bar">
                <input type="text" placeholder="Wpisz czego szukasz" name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
    <div id="options">
        <div id="option_menu_userAccount" class="header_option">
            <i class="fa-solid fa-user"></i>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){?>
                <a href="../account_info/index.php">Twoje konto</a>
            <?php } else { ?>
                <a href="../account_info/index.php">Zaloguj się</a>
            <?php } ?>
        </div>
        <div id="option_menu_shopping_cart" class="header_option">
            <i class="fa-solid fa-cart-shopping"></i>
            <a href="#">Koszyk</a>
        </div>
        <div id="option_menu_sell_product" class="header_option">
            <i class="fa-solid fa-paper-plane"></i>
            <a href="../signin/index.php">Dodaj ogłoszenie</a>
        </div>
    </div>
    <div class="logo-block">
        <div class="logo">
            <div class="bjj-text">
                <span>BJJ.c</span>
            </div>
            <a id="logo-png" href="../index.php"><img src="/png/logo.png" alt="" width="54" height="50"></a>
            <div class="bjj-text">
                <span>m</span>
            </div>
        </div>
    </div>
    <nav id="products-block">
        <div id="men-products" class="product"><a class="category-href"
                                                  href="../product_category/index.php">Mężczyźni</a>
        </div>
        <div id="woman-products" class="product"><a class="category-href"
                                                    href="../product_category/index.php">Kobiety</a>
        </div>
        <div id="kid-products" class="product"><a class="category-href" href="../product_category/index.php">Dzieci</a>
        </div>
    </nav>
</header>
<main>
    <div class="container">
        <div id="no-product-info">
            <h1>Twój koszyk jest pusty</h1>
            <p>Dodaj produkty do koszyka!</p>
        </div>
    </div>
</main>
<footer>
    <p>&COPY; Wszelkie prawa zastrzeżone</p>
</footer>
</body>
</html>

