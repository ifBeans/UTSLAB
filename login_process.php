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
    echo '<div class="container-fluid d-flex row justify-content-center p-0">
    
            <div class="w-25 h-100 mt-3">
    
                    <h1 class="text-center">User Not Found</h1>
    
            </div>
    
         </div>
        ';
} else {

    if (!password_verify($password, $row['password'])) {
        header('Location: login.php');
        echo "Wrong password";
    } else {
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['password'] = $row['password'];
        header('Location: main_page.php');
    }
}
