<?php

session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        header('Location: login.php?error=' . urlencode('User Not Found'));
        exit();
    } else {

        if (password_verify($password, $row['Password'])) {
            $_SESSION['id_user'] = $row['ID_User'];
            $_SESSION['username'] = $row['Username'];
            header('Location: main_page.php');
        } else {
            header('Location: login.php');
        }
        
    }
}

else {
    header('Location: login.php');
    exit();
}
