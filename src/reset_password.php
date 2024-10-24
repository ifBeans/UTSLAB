<?php

session_start();
require 'db.php';

if (isset($_GET['token'])) {

    $token = $_GET['token'];
    $time = date('U');

    $sql = $db->prepare("SELECT * FROM reset_password WHERE Token = :token AND Expire > :time");
    $sql->bindParam(':token', $token);
    $sql->bindParam(':time', $time);
    $sql->execute();
    $result = $sql->fetch(PDO::FETCH_ASSOC);

    if ($result) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $newPassword = $_POST['newPassword'];
            $enPass = password_hash($newPassword, PASSWORD_BCRYPT);

            $sql = $db->prepare("UPDATE user SET password = :password WHERE email = :email");
            $sql->bindParam(':password', $enPass);
            $sql->bindParam(':email', $result['Email']);
            $sql->execute();

            $sql = $db->prepare("DELETE FROM reset_password WHERE email = :email");
            $sql->bindParam(':email', $result['Email']);
            $sql->execute();

            $_SESSION['reset_success'] = "Your password has been successfully updated.";
            unset( $_SESSION['reset_fail']);
        } 
    } else {
        echo "nay";
        $_SESSION['reset_fail'] = "Invalid or expired token.";
        unset( $_SESSION['reset_success']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
</head>

<body class="bg-gradient-to-r from-blue-200 to-cyan-200">

    <div class="container-fluid content-center p-6">

       <div class="w-25 h-100 mt-3">

            <form class="max-w-sm mx-auto" method="post">

                <h1 class="text-center text-4xl font-bold">Reset Password</h1>

                <?php if (isset($_SESSION['reset_success'])): ?>
                    <div class="bg-green-100 text-green-700 p-4 rounded mt-10">
                        <?php echo $_SESSION['reset_success']; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['reset_fail'])): ?>
                    <div class="bg-red-100 text-red-700 p-4 rounded mt-10">
                        <?php echo $_SESSION['reset_fail']; ?>
                    </div>
                <?php endif; ?>

                <div class="mt-10">
                    <label for="newPassword" class="block text-gray-700 font-medium">Enter New Password</label>
                    <input type="password" name="newPassword" class="w-full my-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>

                <div>
                    <button type="submit" class="w-full my-5 bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Change Password
                    </button>
                </div>

            </form>

            <div class="mt-2 text-center">
                <a href="index.php" class="text-indigo-600 hover:underline">Back to Login</a>
            </div>

       </div>

    </div>
    
</body>
</html>
