<?php
session_start();
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id']; //

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Zalogowany użytkownik
        $stmt = $conn->prepare("DELETE FROM Shopping_cart WHERE Users_ID = :user_id AND Products_ID = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    } else {
        // Użytkownik nie jest zalogowany (SESJA)
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header("Location: index.php");
}
?>
