<?php
session_start();
include '../db_connect.php';

if(!isset($conn)){
    die("Nie połączono się z bazą danych!");
}

if(isset($_POST['submit_order']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT Products.*, Shopping_cart.Quantity FROM Shopping_cart 
    JOIN Products ON Shopping_cart.Products_ID = Products.ID WHERE Shopping_cart.Users_ID = '$user_id'");
    $stmt->execute();
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_price = $_POST['total_price'];

    $stmt = $conn->prepare("INSERT INTO Orders (Users_ID, Order_Date, Total_Price, Status) 
    VALUES ('$user_id', NOW(), '$total_price', 'Nowe zamówienie')");
    $stmt->execute();

    //Pobieramy id zamówienia, aby przepisać szczegóły zamówienia
    $order_id = $conn->lastInsertId();

    foreach ($cart_items as $item) {
        $stmt = $conn->prepare("INSERT INTO Order_Details (Orders_ID, Products_ID, Quantity, Price) 
        VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $item['ID'], $item['Quantity'], $item['Price']]);
    }

    $stmt = $conn->prepare("DELETE FROM Shopping_cart WHERE Users_ID = '$user_id'");
    $stmt->execute();

    header("Location: ../account_info/index.php");
}

