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

    if(isset($_SESSION['id_user'])){
        header("Location: main_page.php");
    }

    else{
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