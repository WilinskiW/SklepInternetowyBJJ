<?php
session_start();

include_once '../db_connect.php';

$stmt = $conn->prepare("SELECT * FROM Users WHERE Account_Type != 'admin'");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['reset-submit-input'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['account_type']);
    header("Location: ../index.php");
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Panel admina</title>
</head>
<body>
<form method="get" id="log-out">
    <div class="input">
        <input id="log-out-submit" class="submit" type="submit" name="reset-submit-input" value="Wyloguj się">
    </div>
</form>
<table>
    <tr>
        <th>ID</th>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Email</th>
        <th>Address</th>
        <th>Postel_Code</th>
        <th>Account_Type</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['ID'] ?></td>
            <td><?= $user['Firstname'] ?></td>
            <td><?= $user['Lastname'] ?></td>
            <td><?= $user['Email'] ?></td>
            <td><?= $user['Address'] ?></td>
            <td><?= $user['Postel_Code'] ?></td>
            <td><?= $user['Account_Type'] ?></td>
            <td>
                <form action="delete_user.php" method="post">
                    <input type="hidden" name="user_id" value="<?= $user['ID'] ?>">
                    <input type="submit" value="Usuń">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
