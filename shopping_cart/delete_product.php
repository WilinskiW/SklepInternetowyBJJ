<?php
session_start();
include '../db_connect.php';

// Obsługa usuwania produktu z koszyka
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    //Przypadek: Zalogowany
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Pobranie produktu z koszyka
        $stmt = $conn->prepare("SELECT * FROM Shopping_cart WHERE Users_ID = :user_id AND Products_ID = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);


        $stmt = $conn->prepare("DELETE FROM Shopping_cart WHERE Users_ID = :user_id AND Products_ID = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);


    } else {
        //Przypadek: Niezalogowany
        if (isset($_SESSION['cart'])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    header("Location: ./index.php"); // Przekierowanie z powrotem do koszyka
}
?>
