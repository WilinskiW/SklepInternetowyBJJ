<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Sklep BJJ - Produkty</title>
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
            <form class="search-bar">
                <input type="text" placeholder="Wpisz czego szukasz" name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
    </div>
    <div id="options">
        <div id="option_menu_userAccount" class="header_option">
            <i class="fa-solid fa-user"></i>
            <a href="../signin/index.php">Twoje konto</a>
        </div>
        <div id="option_menu_shopping_cart" class="header_option">
            <i class="fa-solid fa-cart-shopping"></i>
            <a href="../shopping_cart/index.php">Koszyk</a>
        </div>
        <div id="option_menu_sell_product" class="header_option">
            <i class="fa-solid fa-paper-plane"></i>
            <a href="">Dodaj ogłoszenie</a>
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
        <div id="men-products" class="product"><a class="category-href" href="#">Mężczyźni</a>
        </div>
        <div id="woman-products" class="product"><a class="category-href" href="#">Kobiety</a>
        </div>
        <div id="kid-products" class="product"><a class="category-href" href="#">Dzieci</a>
        </div>
    </nav>
</header>
<main>
    <section class="product-gallery">
        <span id="produkty-span">Produkty</span>
        <div class="image-gallery">
            <div id="product1" class="product-block">
                <img class="product-image" src="" alt="nazwa produktu">
                <div class="product-wrapper">
                    <div class="product-info">
                        <h4>
                            <a class="product-name" href="">Nazwa produktu</a>
                        </h4>
                        <p><strong>Cena(w liczbie)</strong></p>
                    </div>
                    <div class="add-to-cart-button">
                        <div class="cart-button-area">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div id="product2" class="product-block">
                <img class="product-image" src="" alt="nazwa produktu">
                <div class="product-wrapper">
                    <div class="product-info">
                        <h4>
                            <a class="product-name" href="">Nazwa produktu</a>
                        </h4>
                        <p><strong>Cena(w liczbie)</strong></p>
                    </div>
                    <div class="add-to-cart-button">
                        <div class="cart-button-area">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div id="product3" class="product-block">
                <img class="product-image" src="" alt="nazwa produktu">
                <div class="product-wrapper">
                    <div class="product-info">
                        <h4>
                            <a class="product-name" href="">Nazwa produktu</a>
                        </h4>
                        <p><strong>Cena(w liczbie)</strong></p>
                    </div>
                    <div class="add-to-cart-button">
                        <div class="cart-button-area">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div id="product4" class="product-block">
                <img class="product-image" src="" alt="nazwa produktu">
                <div class="product-wrapper">
                    <div class="product-info">
                        <h4>
                            <a class="product-name" href="">Nazwa produktu</a>
                        </h4>
                        <p><strong>Cena(w liczbie)</strong></p>
                    </div>
                    <div class="add-to-cart-button">
                        <div class="cart-button-area">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<footer>
    <p>&COPY; Wszelkie prawa zastrzeżone</p>
</footer>
</body>
</html>
