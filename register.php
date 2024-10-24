<?php

session_start();
unset($_SESSION['login_error']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register </title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
</head>
<body class="bg-gradient-to-r from-blue-200 to-cyan-200">
    
    <div class="container-fluid content-center p-6">

        <div class="w-25 h-100 mt-3">

        <form class="max-w-sm mx-auto" action="register_process.php" method="post">

            <h1 class="text-center text-4xl mt-5 font-bold">Register Account</h1>

            <?php if (isset($_SESSION['register_error'])): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded mt-10">
                    <?php echo $_SESSION['register_error']; ?>
                </div>
            <?php endif; ?>

            
            <form action="register_process.php" method="post" class="space-y-4">

                <div class="mt-10">
                    <label for="username" class="block text-gray-700 font-medium">Username</label>
                    <input type="text" name="username" class="w-full my-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>

                
                <div class="mt-2">
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" class="w-full my-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>

                
                <div class="mt-2">
                    <label for="password" class="block text-gray-700 font-medium">Password</label>
                    <input type="password" name="password" class="w-full my-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>

                
                <div>
                    <button type="submit" class="w-full mt-5 bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        Register
                    </button>
                </div>
            </form>

            
            <div class="mt-6 text-center">
                <a href="index.php" class="text-indigo-600 hover:underline">Back to Login</a>
            </div>

        </div>

    </div>
</body>
</html>