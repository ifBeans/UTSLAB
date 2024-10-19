<?php

$dbname = 'utslab';

$mysql = new PDO("mysql:host=localhost", 'root', '');
$pstatement = $mysql->prepare("CREATE DATABASE IF NOT EXISTS $dbname");
$pstatement->execute();

$pstatement2 = $mysql->prepare("USE $dbname");
$pstatement2->execute();

$pstatement3 = $mysql->prepare("CREATE TABLE IF NOT EXISTS user(
    ID_User INT AUTO_INCREMENT,
    Username VARCHAR(20) UNIQUE,
    Email VARCHAR(30),
    Password VARCHAR(70),
    PRIMARY KEY (ID_User)
)");

$pstatement3->execute();

$pstatement4 = $mysql->prepare("CREATE TABLE IF NOT EXISTS todo(
    ID_Todo INT AUTO_INCREMENT,
    Description VARCHAR(50),
    Category VARCHAR(20),
    ID_User INT,
    PRIMARY KEY (ID_Todo),
    FOREIGN KEY (ID_User) REFERENCES user(ID_User)
)");

$pstatement4->execute();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <?php

    session_start();

    if (isset($_SESSION['id_user'])) {
        header("Location: main_page.php");
    } else {
    ?>

        <div class="container-fluid content-center p-6">

            <div class="w-25 h-100 mt-3">

                <form class="max-w-sm mx-auto mt-5" action="register.php">

                    <div class="text-center">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Register</button>
                    </div>

                </form>

                <form class="max-w-sm mx-auto mt-5" action="login.php">

                    <div class="text-center">
                        <button type="submit" class="text-white bg-red-700 hover:bg-red-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Login</button>
                    </div>

                </form>

            </div>

        </div>

    <?php
    }
    ?>

</body>

</html>