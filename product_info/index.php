<?php
session_start();

// Dołączenie skryptu do połączenia z bazą danych
include '../db_connect.php';

if (!isset($_GET['product_id'])) {
    header("Location: ../index.php");
}

$product_id = $_GET['product_id'];

//Wyszukaj informacje o produkcie
$stmt = $conn->prepare("SELECT Products.*, Warehouse.Amount FROM Products 
    JOIN Warehouse ON Products.ID = Warehouse.Products_ID WHERE Products.ID = :product_id");
$stmt->execute(['product_id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);


addToRecentViewed($product_id);

//Wyszukaj wszystkie recenzje o produkcie
$stmt = $conn->prepare("SELECT Users_ID,Rating, Comment FROM ratings WHERE Products_ID = :product_id");
$stmt->execute(['product_id' => $product_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);


function addToRecentViewed($product_id)
{
    if (isset($_SESSION['recently_viewed'])) {
        $recently_viewed = $_SESSION['recently_viewed'];
    } else {
        $recently_viewed = array();
    }

    if (!in_array($product_id, $recently_viewed)) {
        $recently_viewed[] = $product_id;
        $_SESSION['recently_viewed'] = $recently_viewed;
    }
}
?>

<?php
// Jeśli formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];
    $user_id = $_SESSION['user_id'];

    if(!isset($comment)){
        $comment = "";
    }

    // Zapisz komentarz i ocenę w bazie danych
    $stmt = $conn->prepare("INSERT INTO ratings (Users_ID, Products_ID, Rating, Comment) 
    VALUES ($user_id, $product_id,$rating,'$comment')");
    $stmt->execute();

    header("Location: index.php?product_id=$product_id");
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
                        <p class="description-text">
                            <?php if ($amount = $product['Amount'] > 0){
                            echo "Aktualna ilość: " . $product['Amount'];
                            }
                            else{
                                echo "Niedostępny";
                            } ?>
                        </p>
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
                    <textarea id="comment-area" name="comment" placeholder="Dodaj komentarz" required></textarea>
                    <input id="rating-input" type="number" name="rating" min="1" max="5" required placeholder="Ocena (1-5)">
                    <input id="add-comment-input" type="submit" value="Dodaj komentarz">
                </form>
            </div>
            <?php if (isset($reviews) && $reviews != null) { ?>
                <div id="reviews">
                    <h3 id="review-h3">Oceny produktu:</h3>
                    <?php foreach ($reviews as $review ): ?>
                        <div class="review">
                            <div class="rating"><strong>Ocena: </strong><span style="color: var(--red-color)"><?= $review['Rating'] ?></span></div>
                            <div class="comment"><strong>Komentarz: </strong><?= $review['Comment'] ?></div>
                            <br>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php } ?>
        </div>
        <?php } else { ?>
            <p style="font-family: 'Montserrat', sans-serif;">
                Aby dodać komentarz i ocenę trzeba być <a style="color: var(--red-color); text-decoration: underline"
                                                          href="../signin/index.php">zalogowanym!</a></p>
        <?php } ?>
    </div>
</main>
<footer>
    <p>&COPY; Wszelkie prawa zastrzeżone</p>
</footer>
</body>
</html>