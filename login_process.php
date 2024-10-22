<?php

session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$row) {
        $_SESSION['login_error'] = "User Not Found";
        header('Location: index.php');
        exit();
    } else {

        if (password_verify($password, $row['Password'])) {
            $_SESSION['id_user'] = $row['ID_User'];
            $_SESSION['username'] = $row['Username'];
            header('Location: main_page.php');
        } else {
            $_SESSION['login_error'] = "Wrong Password";
            header('Location: index.php');
            exit();
        }
        
    }
}

else {
    header('Location: index.php');
    exit();
}
