<?php

session_start();
require_once('db.php');

$category = $_POST['category'];
$description = $_POST['todo'];
$completion = 0;
$id_user = $_SESSION['id_user'];

$sql = "INSERT INTO todo (category, description, completion, id_user) VALUES (:category, :desc, :completion, :ID_User)";

$result = $db->prepare($sql);
$result->bindParam(':category', $category, PDO::PARAM_STR);
$result->bindParam(':desc', $description, PDO::PARAM_STR);
$result->bindParam(':completion', $completion, PDO::PARAM_INT);
$result->bindParam(':ID_User', $id_user, PDO::PARAM_INT);
$result->execute();

$_SESSION['filter'] = "all";
unset($_SESSION['search']);

header("Location: main_page.php");
exit();

?>