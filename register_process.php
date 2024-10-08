<?php

require_once('db.php');

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$en_pass = password_hash($password, PASSWORD_BCRYPT);

$sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";

$result = $db->prepare($sql);
$result->execute([$username, $email, $en_pass]);

header("Location: index.php");

?>