<?php

require_once('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $en_pass = password_hash($password, PASSWORD_BCRYPT);

    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.php?error=" . urlencode("All fields are required."));
        exit();
    }

    $checkAccount = "SELECT * FROM user WHERE username = :username OR email = :email";
    $checkStmt = $db->prepare($checkAccount);
    $checkStmt->bindParam(':username', $username, PDO::PARAM_STR);
    $checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
    $checkStmt->execute();

    if ($checkStmt->rowCount() > 0) {
        $_SESSION['register_error'] = "Username or email already exists";
        header("Location: register.php");
        exit();
    }

    else{

        $sql = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";

        try {
            $result = $db->prepare($sql);
            $result->bindParam(':username', $username, PDO::PARAM_STR);
            $result->bindParam(':email', $email, PDO::PARAM_STR);
            $result->bindParam(':password', $en_pass, PDO::PARAM_STR);
            $result->execute();

            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            header("Location: register.php?error=" . urlencode("Registration failed: " . $e->getMessage()));
            exit();
        }
    }
}

else {
    header("Location: register.php");
    exit();
}

?>