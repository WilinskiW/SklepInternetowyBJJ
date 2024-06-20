<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    var_dump($_GET);
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Zalogowany użytkownik

        $stmt = $conn->prepare("SELECT * FROM Shopping_cart WHERE Users_ID = :user_id AND Products_ID = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($item) {
            $new_quantity = $item['Quantity'] + 1;
            $stmt = $conn->prepare("UPDATE Shopping_cart SET Quantity = :quantity WHERE Users_ID = :user_id AND Products_ID = :product_id");
            $stmt->execute(['quantity' => $new_quantity, 'user_id' => $user_id, 'product_id' => $product_id]);
            var_dump($stmt);
        } else {
            $stmt = $conn->prepare("INSERT INTO Shopping_cart (Users_ID, Products_ID, Quantity) VALUES (:user_id, :product_id, 1)");
            $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        }

    } else {
        // Użytkownik nie jest zalogowany, użyj sesji do przechowywania koszyka
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        if (array_key_exists($product_id, $_SESSION['cart'])) {
            // Produkt jest już w koszyku, zaktualizuj ilość
            $_SESSION['cart'][$product_id]++;
        } else {
            // Produktu nie ma jeszcze w koszyku, dodaj nowy wpis
            $_SESSION['cart'][$product_id] = 1;
        }

    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>