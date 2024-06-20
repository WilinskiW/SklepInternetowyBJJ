<?php
// Rozpoczęcie sesji
session_start();

// Dołączenie skryptu do połączenia z bazą danych
include '../db_connect.php';

$product_id = $_GET['product_id'];

$stmt = $conn->prepare("SELECT Products.*, Warehouse.Amount FROM Products 
    JOIN Warehouse ON Products.ID = Warehouse.Products_ID WHERE Products.ID = :product_id");
$stmt->execute(['product_id' => $product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= $product['Name'] ?></title>
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
<!-- reszta strony -->
<main>
    <h1><?= $product['Name'] ?></h1>
    <img src="data:image/jpeg;base64,<?= base64_encode($product['Image']) ?>" alt="<?= $product['Name'] ?>">
    <p><?= $product['Description'] ?></p>
    <p>Cena: <?= $product['Price'] ?> zł</p>
    <p>Ilość w magazynie: <?= $product['Amount'] ?></p>
</main>
<!-- reszta strony -->
</body>
</html>
