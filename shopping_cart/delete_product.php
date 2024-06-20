<?php
// Rozpoczęcie sesji
session_start();

// Dołączenie skryptu do połączenia z bazą danych
include '../db_connect.php';

// Obsługa usuwania produktu z koszyka
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id']; // ID produktu do usunięcia

    // Sprawdzenie, czy użytkownik jest zalogowany
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Zalogowany użytkownik

        // Pobranie produktu z koszyka
        $stmt = $conn->prepare("SELECT * FROM Shopping_cart WHERE Users_ID = :user_id AND Products_ID = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            if ($item['Quantity'] > 1) {
                // Jest więcej niż jeden produkt w koszyku, zaktualizuj ilość
                $new_quantity = $item['Quantity'] - 1;
                $stmt = $conn->prepare("UPDATE Shopping_cart SET Quantity = :quantity WHERE Users_ID = :user_id AND Products_ID = :product_id");
                $stmt->execute(['quantity' => $new_quantity, 'user_id' => $user_id, 'product_id' => $product_id]);
            } else {
                // Jest tylko jeden produkt w koszyku, usuń go
                $stmt = $conn->prepare("DELETE FROM Shopping_cart WHERE Users_ID = :user_id AND Products_ID = :product_id");
                $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
            }
        }
    } else {
        // Użytkownik nie jest zalogowany, użyj sesji do przechowywania koszyka
        if (isset($_SESSION['cart'])) {
            if ($_SESSION['cart'][$product_id] > 1) {
                // Jest więcej niż jeden produkt w koszyku, zaktualizuj ilość
                $_SESSION['cart'][$product_id]--;
            } else {
                // Jest tylko jeden produkt w koszyku, usuń go
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }

    echo "Produkt usunięty z koszyka!";
    header("Location: ./index.php"); // Przekierowanie z powrotem do koszyka
}
?>
