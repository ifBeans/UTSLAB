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

$username = $user['username'] ?? '';
$email = $user['email'] ?? '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    
    if (empty($errors)) {
        $hashedPassword = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

        $sql = "UPDATE user SET username = :username, email = :email" . ($hashedPassword ? ", password = :password" : "") . " WHERE ID_User = :id";
        $stmt = $db->prepare($sql);
        $params = [
            ':username' => $username,
            ':email' => $email,
            ':id' => $id
        ];
        if ($hashedPassword) {
            $params[':password'] = $hashedPassword;
        }
        $stmt->execute($params);

        header("Location: profile.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- <link href="./src/output.css" rel="stylesheet"> -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
</head>
<body class="bg-gradient-to-r from-blue-200 to-cyan-200">
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-bold mb-4">Edit Profile</h1>
        <div class="bg-white shadow-md rounded-lg p-6">
            <?php if (!empty($errors)): ?>
                <div class="mb-4 text-red-600">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-4">
                    <label class="block text-lg font-medium text-gray-700">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-lg font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label class="block text-lg font-medium text-gray-700">New Password (leave blank to keep current)</label>
                    <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Update Profile</button>
                </div>
            </form>
            <div class="mt-4 text-right">
                <form method="post" action="profile.php">
                    <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
