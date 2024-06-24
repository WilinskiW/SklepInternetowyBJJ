<?php
session_start();

include '../db_connect.php';

//Wyszukaj ordery


//Pobierz informacje o użytkowniku
if (isset($conn)) {
    $user = getInfoAboutUser($conn);
    $orders = getOrders($conn);
} else {
    throw new Exception("Nie połączono się z bazą");
}

//Zresetuj hasło
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['reset-submit-input'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['account_type']);
    header("Location: ../index.php");
}

// Obsługa formularza resetowania hasła
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset-text-input'])) {
    $user_id = $_SESSION['user_id']; // Zalogowany użytkownik
    $new_password = password_hash($_POST['reset-text-input'], PASSWORD_DEFAULT); // Nowe hasło

    $stmt = $conn->prepare("UPDATE Users SET Password = :new_password WHERE ID = :user_id");
    $stmt->execute(['new_password' => $new_password, 'user_id' => $user_id]);

    echo "Hasło zostało zresetowane!";
}

function getInfoAboutUser($conn)
{
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM Users WHERE ID = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getOrders($conn)
{
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT Orders.ID as Order_ID, 
       Orders.Order_Date, 
       Products.ID as Products_ID, 
       Products.Name as Product_Name, 
       Order_Details.Price as Price_Per_Product,
       Order_Details.Quantity, 
       Orders.Status, 
       Orders.Total_Price FROM Orders JOIN Order_Details ON Orders.ID = Order_Details.Orders_ID 
                                      JOIN Products ON Order_Details.Products_ID = Products.ID 
                                       WHERE Orders.Users_ID = '$user_id';
");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/css/logo.css">
    <link rel="stylesheet" href="/css/header_footer.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Teko:wght@300..700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">

    <title>BJJ.com - Twoje konto</title>
</head>
<body>
<header>
    <div class="logo-block">
        <div class="logo">
            <div class="bjj-text">
                <span>BJJ.c</span>
            </div>
            <a id="logo-png" href="../index.php"><img src="../png/logo.png" alt="" width="54" height="50"></a>
            <div class="bjj-text">
                <span>m</span>
            </div>
        </div>
    </div>
</header>
<main>
    <h1>Twoje konto</h1>
    <div class="user-container">
        <div class="user-info-box">
            <h3>Informacje o produkcie</h3>
            <div id="user-info">
                <p><label>Imię: </label><?= $user['Firstname'] ?></p>
                <p><label>Nazwisko: </label><?= $user['Lastname'] ?></p>
                <p><label>Email: </label><?= $user['Email'] ?></p>
                <p><label>Adres: </label><?= $user['Address'] ?></p>
                <p><label>Kod pocztowy: </label><?= $user['Postel_Code'] ?></p>
                <form method="post" id="reset-password">
                    <label>Zresetuj hasło:</label>
                    <input class="input" type="password" name="reset-text-input" required>
                    <div class="input">
                        <input class="submit" type="submit" name="reset-submit-input" value="Zatwiedź nowe hasło">
                    </div>
                </form>
                <form method="get" id="log-out">
                    <div class="input">
                        <input id="log-out-submit" class="submit" type="submit" name="reset-submit-input"
                               value="Wyloguj się">
                    </div>
                </form>
            </div>
        </div>
        <div class="user-orders">
            <h3>Historia zamówień</h3>
            <?php if(isset($orders)) { ?>
            <table id="order-table" class="info-table">
                <tr>
                    <th>Numer zamówienia</th>
                    <th>Data zamówienia</th>
                    <th>Nazwa produktu</th>
                    <th>Cena produktu</th>
                    <th>Ilość</th>
                    <th>Status</th>
                    <th>Łączna cena za produkty</th>
                </tr>
                <tr>
                   <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['Order_ID'] ?></td>
                    <td><?= $order['Order_Date'] ?></td>
                    <td><a class="product-name" href="../product_info/index.php?product_id=
                                <?= $order['Products_ID'] ?>"><?= $order['Product_Name']?></a></td>
                    <td><?= $order['Price_Per_Product'] ?> zł</td>
                    <td><?= $order['Quantity'] ?></td>
                    <td><?= $order['Status'] ?></td>
                    <td><?= $order['Total_Price'] ?></td>
                </tr>
                <?php
            endforeach; ?>
            </table>
            <?php } ?>
        </div>
    </div>
</main>
<footer>
    <p>&COPY; Wszelkie prawa zastrzeżone</p>
</footer>
</body>
</html>
