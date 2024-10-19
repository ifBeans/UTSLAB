<?php

session_start();
require_once('db.php');

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

    if (!password_verify($password, $row['password'])) {
        header('Location: login.php');
        echo "Wrong password";
    } else {
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['username'] = $row['username'];
        header('Location: main_page.php');
        exit();
    }
}
