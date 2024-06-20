<?php
class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function loginUser($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM Users WHERE Email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['account_type'] = $user['Account_Type'];
            header('Location: ../index.php');
        } else {
            $_SESSION['lock_fail'] = true;
            header("Location: index.php");
        }
    }

    public function registerUser($firstname, $lastname, $email, $address, $postal_code, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit();
        }

        $stmt = $this->conn->prepare("
        INSERT INTO Users (Firstname, Lastname, Email, Address, Postel_Code, Password) 
        VALUES (:firstname, :lastname, :email, :address, :postal_code, :password)");
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':postal_code', $postal_code);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        session_abort();
    }
}
?>

