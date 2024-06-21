<?php
$expire = 30 * 60; // 30 minut
session_set_cookie_params($expire);
session_start();

// Dołączenie skryptu do połączenia z bazą danych
include '../db_connect.php';

if (!isset($_GET['product_id'])) {
    header("Location: ../index.php");
}

$product_id = $_GET['product_id'];

$stmt = $conn->prepare("SELECT Products.*, Warehouse.Amount FROM Products 
    JOIN Warehouse ON Products.ID = Warehouse.Products_ID WHERE Products.ID = :product_id");
$stmt->execute(['product_id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Dodajemy ID produktu do pliku cookie
if (isset($_COOKIE['recently_viewed'])) {
    $recently_viewed = json_decode($_COOKIE['recently_viewed'], true);
} else {
    $recently_viewed = array();
}

if (!in_array($product_id, $recently_viewed)) {
    $recently_viewed[] = $product_id;
    setcookie('recently_viewed', json_encode($recently_viewed), time() + (86400 * 30), "/"); // Plik cookie wygasa po 30 dniach
}

?>

<?php
// Jeśli formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && isset($_POST['rating'])) {
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];

    // Zapisz komentarz i ocenę w bazie danych
    $stmt = $conn->prepare("INSERT INTO ratings (Users_ID, Products_ID, Rating, Comment) VALUES (:product_id, :user_id, :comment, :ratingW)");
    $stmt->execute(['user_id' => $_SESSION['user_id'], 'product_id' => $product_id, 'rating' => $rating, 'comment' => $comment]);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Bjj.com - <?= $product['Name'] ?></title>
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
<header>
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
                <?php if (isset($_SESSION['account_type']) && ($_SESSION['account_type'] == 'admin')) { ?>
                    <a href="../admin_panel/index.php">Panel administracji</a>
                <?php } else if (isset($_SESSION['user_id'])) { ?>
                    <a href="../account_info/index.php">Twoje konto</a>
                <?php } else { ?>
                    <a href="../signin/index.php">Zaloguj się</a>
                <?php } ?>
            </div>
            <div id="option_menu_shopping_cart" class="header_option">
                <i class="fa-solid fa-cart-shopping"></i>
                <a href="../shopping_cart/index.php">Koszyk</a>
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
                                                      href="../product_category/index.php?category=Mężczyźni">Mężczyźni</a>
            </div>
            <div id="woman-products" class="product"><a class="category-href"
                                                        href="../product_category/index.php?category=Kobiety">Kobiety</a>
            </div>
            <div id="kid-products" class="product"><a class="category-href"
                                                      href="../product_category/index.php?category=Dzieci">Dzieci</a>
            </div>
        </nav>
    </header>
</header>
<main>
    <div id="product-info-container">
        <div class="product-block">
            <img class="product-image" src="data:image/jpeg;base64,<?= base64_encode($product['Image']) ?>"
                 alt="<?= $product['Name'] ?>">
            <div class="product-wrapper">
                <div class="product-info">
                    <h4>
                        <a class="product-name" href="../product_info/index.php?product_id=
                                <?= $product_id ?>"><?= $product['Name'] ?></a>

                    </h4>
                    <p><strong><?= $product['Price'] ?> zł</strong></p>
                </div>

                <div class="add-to-cart-button">
                    <a href="../add_to_cart.php?product_id=<?= $product['ID'] ?>">
                        <div class="cart-button-area">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div id="container-container">
            <div id="more-info-block">
                <div id="more-info">
                    <h2><?= $product['Name'] ?></h2>
                    <div class="product-description">
                        <h4>Opis produktu:</h4>
                        <p class="description-text"><?= $product['Description'] ?></p>
                    </div>
                    <div class="product-description">
                        <h4>Stan produktu w magazynie:</h4>
                        <p class="description-text">Aktualna ilość: <?= $product['Amount'] ?> </p>
                    </div>
                    <div class="product-description">
                        <h4>Cena produktu:</h4>
                        <p class="description-text"><strong><?= $product['Price'] ?> zł</strong></p>
                    </div>
                </div>
            </div>
            <h3>Dodaj komentarz i oceń!</h3>
                <div id="add-comment-rating-container">
                    <?php if (isset($_SESSION['user_id'])) { ?>
                    <form id="add-comment-rating-form" method="post">
                        <textarea id="comment-area" name="comment" placeholder="Dodaj komentarz"></textarea>
                        <input id="rating-input" type="number" name="rating" min="1" max="5" placeholder="Ocena (1-5)">
                        <input id="add-comment-input" type="submit" value="Dodaj komentarz">
                    </form>

                    <!--                Wyświetlanie komentarzy i ocen -->
                    <!--                <div id="reviews">-->
                    <!--                    --><?php //foreach ($reviews as $review): ?>
                    <!--                        <div class="review">-->
                    <!--                            <div class="rating">Ocena: -->
                    <?php //= $review['Rating'] ?><!--/5</div>-->
                    <!--                            <div class="comment">--><?php //= $review['Comment'] ?><!--</div>-->
                    <!--                            <div class="date">--><?php //= $review['Date'] ?><!--</div>-->
                    <!--                        </div>-->
                    <!--                    --><?php //endforeach; ?>
                    <!--                </div>-->
                </div>
            <?php } else { ?>
                <p style="font-family: 'Montserrat', sans-serif;">
                    Aby dodać komentarz i ocenę trzeba być <a href="../signin/index.php">zalogowanym!</a></p>
            <?php } ?>
        </div>
    </div>
</main>
<footer>
    <p>&COPY; Wszelkie prawa zastrzeżone</p>
</footer>
</body>
</html>