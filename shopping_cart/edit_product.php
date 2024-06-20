<?php
session_start();
include '../db_connect.php';

// Obsługa edycji ilości produktu w koszyku
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id']) && isset($_POST['action'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("SELECT * FROM Shopping_cart WHERE Users_ID = :user_id AND Products_ID = :product_id");
        $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            if ($action == 'increase') {
                $new_quantity = $item['Quantity'] + 1;
            } elseif ($action == 'decrease' && $item['Quantity'] > 1) {
                $new_quantity = $item['Quantity'] - 1;
            } else {
                $stmt = $conn->prepare("DELETE FROM Shopping_cart WHERE Users_ID = :user_id AND Products_ID = :product_id");
                $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
                header("Location: ./index.php"); // Przekierowanie z powrotem do koszyka
                exit;
            }

            $stmt = $conn->prepare("UPDATE Shopping_cart SET Quantity = :quantity WHERE Users_ID = :user_id AND Products_ID = :product_id");
            $stmt->execute(['quantity' => $new_quantity, 'user_id' => $user_id, 'product_id' => $product_id]);
        }
    } else {
        if (isset($_SESSION['cart'])) {
            if ($action == 'increase') {
                $_SESSION['cart'][$product_id]++;
            } elseif ($action == 'decrease' && $_SESSION['cart'][$product_id] > 1) {
                $_SESSION['cart'][$product_id]--;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
    header("Location: index.php");
}
?>
