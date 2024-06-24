<?php
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['user_id'])){
    include_once '../db_connect.php';
    $user_id = $_POST['user_id'];
    $stmt = $conn->prepare("DELETE FROM Users WHERE ID='$user_id'");
    $stmt->execute();

    header('Location: ' . $_SERVER['HTTP_REFERER']);

}