<?php
// Ustawienie czasu wygaśnięcia sesji na 30 minut
$expire = 30*60; // 30 minut
session_set_cookie_params($expire);
session_start();
include '../db_connect.php';

// Pobranie produktów w koszyku
$cart_items = array();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Zalogowany użytkownik

    $stmt = $conn->prepare("SELECT Products.*, Shopping_cart.Quantity FROM Shopping_cart JOIN Products ON Shopping_cart.Products_ID = Products.ID WHERE Shopping_cart.Users_ID = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt = $conn->prepare("SELECT * FROM Products WHERE ID = :product_id");
            $stmt->execute(['product_id' => $product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $product['Quantity'] = $quantity;
                $cart_items[] = $product;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep BJJ - najlepszy sprzęt sportowy do brazyliskiego jiu jitsu</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/css/logo.css">
    <link rel="stylesheet" href="/css/header_footer.css">
    <link rel="stylesheet" href="/css/product_gallery.css">

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
            <form class="search-bar" action="../search_results/index.php">
                <input type="text" placeholder="Wpisz czego szukasz" name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
    <div id="options">
        <div id="option_menu_userAccount" class="header_option">
            <i class="fa-solid fa-user"></i>
            <?php if(isset($_SESSION['account_type']) && ($_SESSION['account_type'] == 'admin')){?>
                <a href="../admin_panel/index.php">Panel administracji</a>
            <?php } else if(isset($_SESSION['user_id'])){ ?>
                <a href="../account_info/index.php">Twoje konto</a>
            <?php } else { ?>
                <a href="../signin/index.php">Zaloguj się</a>
            <?php } ?>
        </div>
        <div id="option_menu_shopping_cart" class="header_option">
            <i class="fa-solid fa-cart-shopping"></i>
            <a href="#">Koszyk</a>
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
        <div id="men-products" class="product"><a class="category-href" href="../product_category/index.php?category=Mężczyźni">Mężczyźni</a>
        </div>
        <div id="woman-products" class="product"><a class="category-href" href="../product_category/index.php?category=Kobiety">Kobiety</a>
        </div>
        <div id="kid-products" class="product"><a class="category-href" href="../product_category/index.php?category=Dzieci">Dzieci</a>
        </div>
    </nav>
</header>
<main>
    <div id="cart-container">
        <?php if (empty($cart_items)): ?>
            <div id="no-product-info">
                <h1>Twój koszyk jest pusty</h1>
                <p>Dodaj produkty do koszyka!</p>
            </div>
        <?php else: ?>
        <h1>Twój koszyk:</h1>
        <table class="product-table">
            <tr>
                <th>Podgląd produktu</th>
                <th>Nazwa</th>
                <th>Cena</th>
                <th>Ilość</th>
                <th>Akcje</th>
            </tr>
            <?php
            $sum_price = 0;
            foreach ($cart_items as $item): ?>
                <tr>
                    <td class="img-cell"><img class="cart-image"
                                              src="data:image/jpeg;base64,<?= base64_encode($item['Image']) ?>"
                                              alt="<?= $item['Name'] ?>"></td>
                    <td><a class="product-name" href="/product_info/index.php?product_id=
                                <?= $item['ID'] ?>"><?= $item['Name'] ?></a></td>
                    <td><?= $item['Price'] ?> zł</td>
                    <td><?= $item['Quantity'] ?> szt.</td>
                    <td class="actions-box">
                        <form class="action-box" method="post" action="edit_product.php">
                            <input type="hidden" name="product_id" value="<?= $item['ID'] ?>">
                            <input type="hidden" name="action" value="increase">
                            <input id="increase-input" class="action-button"  type="submit" value="+">
                        </form>
                        <form id="decrease-form" class="action-box" method="post" action="edit_product.php">
                            <input type="hidden" name="product_id" value="<?= $item['ID'] ?>">
                            <input type="hidden" name="action" value="decrease">
                            <input id="decrease-input" class="action-button" type="submit" value="-">
                        </form>
                        <form id="delete-form" class="action-box" method="post" action="delete_product.php">
                            <input type="hidden" name="product_id" value="<?= $item['ID'] ?>">
                            <input id="delete-input" class="action-button" type="submit" value="Usuń">
                        </form>
                    </td>
                </tr>
            <?php
            $sum_price += $item['Price']*$item['Quantity'];
            endforeach; ?>
        </table>
        <div id="summarize-container">
            <div id="summarize-text-box">
                <p id="sum-text">Łącznie do zapłaty: <?= $sum_price ?> zł</p>
            </div>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div id="checkout-container">
                <form method="post" action="../checkout/index.php">
                    <input class="input-submit" type="submit" value="Złóż zamówienie">
                </form>
            </div>
        <?php else: ?>
            <div id="login-reminder">
                <p>Musisz się <a style="color: var(--red-color); text-decoration: underline"
                                 href="../signin/index.php">zalogować</a>, aby złożyć zamówienie.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</main>

<footer>
    <p>&COPY; Wszelkie prawa zastrzeżone</p>
</footer>
</body>
</html>

