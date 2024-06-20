<?php
session_start();
include '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = $_POST['product_id']; // ID produktu do edycji
    $quantity = $_POST['quantity']; // Nowa ilość produktu

    // Sprawdzenie, czy użytkownik jest zalogowany
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Zalogowany użytkownik

        // Aktualizacja ilości produktu w koszyka
        $stmt = $conn->prepare("UPDATE Shopping_cart SET Quantity = :quantity WHERE Users_ID = :user_id AND Products_ID = :product_id");
        $stmt->execute(['quantity' => $quantity, 'user_id' => $user_id, 'product_id' => $product_id]);
    } else {
        // Użytkownik nie jest zalogowany, użyj sesji do przechowywania koszyka
        if (isset($_SESSION['cart'])) {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }

    echo "Ilość produktu zaktualizowana!";
    header("Location: ./index.php"); // Przekierowanie z powrotem do koszyka
}
?>
