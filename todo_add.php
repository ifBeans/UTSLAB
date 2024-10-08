<?php

session_start();
require_once('db.php');

$kategori = "temp";
$description = $_POST['todo'];
$completion = 0;
$id_user = $_SESSION['id_user'];

$sql = "INSERT INTO todo (kategori, description, completion, id_user) VALUES (?, ?, ?, ?)";

$result = $db->prepare($sql);
$result->execute([$kategori, $description, $completion, $id_user]);

header("Location: main_page.php");

?>