<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    
    <div class="container-fluid content-center p-6">

        <div class="w-25 h-100 mt-3">

        <form class="max-w-sm mx-auto" action="register_process.php" method="post">

                <h1 class="text-center text-4xl font-bold">Register Account</h1>

                <div class="mb-3 mt-10">
                    <label class="mb-2 text-lg font-medium text-gray-900">Username</label>
                    <input type="text" name="username" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required/>
                </div>

                <div class="mb-3">
                    <label class="mb-2 text-lg font-medium text-gray-900">Email</label>
                    <input type="text" name="email" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required/>
                </div>

                <div class="mb-3">
                    <label class="mb-2 text-lg font-medium text-gray-900">Password</label>
                    <input type="password" name="password" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" required/>
                </div>

                <div class="text-center">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Register</button>
                </div>

            </form>

        </div>

    </div>
</body>
</html>