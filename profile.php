<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['id_user']) && !isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id_user'];

$sql = "SELECT username, email FROM user WHERE ID_User = :ID_User";
$stmt = $db->prepare($sql);
$stmt->bindParam(':ID_User', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <!-- <link href="./src/output.css" rel="stylesheet"> -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
</head>

<body class="bg-gradient-to-r from-blue-200 to-cyan-200">
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-bold mb-4">Profile</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">User Information</h2>
            <div class="mb-4">
                <label class="block text-lg font-medium text-gray-700">Username</label>
                <input type="text" value="<?php echo htmlspecialchars($user['username'] ?? 'N/A'); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
            </div>
            <div class="mb-4">
                <label class="block text-lg font-medium text-gray-700">Email</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" readonly>
            </div>
            <div class="text-right">
                <form method="post" action="edit_profile.php">
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Edit Profile</button>
                </form>
                <form method="post" action="main_page.php" class="mt-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Back to Main Page</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>