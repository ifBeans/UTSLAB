<?php

require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $en_pass = password_hash($password, PASSWORD_BCRYPT);

    if (empty($username) || empty($email) || empty($password)) {
        header("Location: register.php?error=" . urlencode("All fields are required."));
        exit();
    }

    $checkAccount = "SELECT * FROM user WHERE username = ? OR email = ?";
    $checkStmt = $db->prepare($checkAccount);
    $checkStmt->execute([$username, $email]);

    if ($checkStmt->rowCount() > 0) {
        header("Location: register.php?error=" . urlencode("Username or email already exists."));
        exit();
    }

    $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";

    try {
        $result = $db->prepare($sql);
        $result->execute([$username, $email, $en_pass]);

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        header("Location: register.php?error=" . urlencode("Registration failed: " . $e->getMessage()));
        exit();
    }
}

else {
    header("Location: register.php");
    exit();
}

?>