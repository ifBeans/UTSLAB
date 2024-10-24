<?php

session_start();
unset($_SESSION['register_error']);
unset($_SESSION['email_success']);
unset($_SESSION['email_fail']);
unset($_SESSION['reset_success']);
unset($_SESSION['reset_fail']);
$_SESSION['filter'] = "all";

$dbname = 'utslab';

$mysql = new PDO("mysql:host=localhost", 'root', '');
$pstatement = $mysql->prepare("CREATE DATABASE IF NOT EXISTS $dbname");
$pstatement->execute();

$pstatement2 = $mysql->prepare("USE $dbname");
$pstatement2->execute();

$pstatement3 = $mysql->prepare("CREATE TABLE IF NOT EXISTS user(
    ID_User INT AUTO_INCREMENT,
    Username VARCHAR(20) UNIQUE,
    Email VARCHAR(50) NOT NULL,
    Password VARCHAR(70) NOT NULL,
    PRIMARY KEY (ID_User)
)");

$pstatement3->execute();

$pstatement4 = $mysql->prepare("CREATE TABLE IF NOT EXISTS todo(
    ID_Todo INT AUTO_INCREMENT,
    Description VARCHAR(50) NOT NULL,
    Category VARCHAR(20) NOT NULL,
    Completion INT NOT NULL,
    ID_User INT,
    PRIMARY KEY (ID_Todo),
    FOREIGN KEY (ID_User) REFERENCES user(ID_User)
)");

$pstatement4->execute();

$pstatement5 = $mysql->prepare("CREATE TABLE IF NOT EXISTS reset_password(
    ID_Reset INT AUTO_INCREMENT,
    Email VARCHAR(50) NOT NULL,
    Token VARCHAR(255) NOT NULL,
    Expire INT NOT NULL,
    PRIMARY KEY (ID_Reset)
)");

$pstatement5->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link href="./src/output.css" rel="stylesheet"> -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
</head>
<body class="bg-gradient-to-r from-blue-200 to-cyan-200">

    <div class="container-fluid content-center p-6">

        <div class="w-25 h-100 mt-3">

            <form class="max-w-sm mx-auto" action="login_process.php" method="post">

                <h1 class="text-center text-4xl font-bold">Login</h1>

                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="bg-red-100 text-red-700 p-4 rounded mt-10">
                        <?php echo $_SESSION['login_error']; ?>
                    </div>
                <?php endif; ?>

                <div class="mt-10">
                    <label for="username" class="block text-gray-700 font-medium">Username</label>
                    <input type="text" name="username" class="w-full my-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter Your Username" required>
                </div>

                <div class="mt-2">
                    <label for="password" class="block text-gray-700 font-medium">Password</label>
                    <input type="password" name="password" class="w-full my-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter Your Password" required>
                </div>

                <div>
                    <button type="submit" name="login" class="w-full my-5 bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Login
                    </button>
                </div>
            </form>

            <div class="mt-2 text-center">
                <a href="register.php" class="text-indigo-600 hover:underline">Don't have an account? Register here</a>
            </div>

            <div class="mt-2 text-center">
                <a href="forgot_password.php" class="text-indigo-600 hover:underline">Forgot Password?</a>
            </div>

        </div>

    </div>
</body>
</html>