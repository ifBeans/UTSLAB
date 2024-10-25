<?php

session_start();
unset($_SESSION['reset_success']);
unset($_SESSION['reset_fail']);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'];

    $sql = $db->prepare("SELECT email FROM user WHERE email = :email");
    $sql->bindParam(':email', $email, PDO::PARAM_STR);
    $sql->execute();
    $user = $sql->fetch();

    if ($user) {
        
        $token = bin2hex(random_bytes(50));
        $expire = date('U') + 1800;

        $sql = $db->prepare("INSERT INTO reset_password (email, token, expire) VALUES (:email, :token, :expire)");
        $sql->bindParam(':email', $email, PDO::PARAM_STR);
        $sql->bindParam(':token', $token, PDO::PARAM_STR);
        $sql->bindParam(':expire', $expire, PDO::PARAM_INT);
        $sql->execute();

        $mail = new PHPMailer(true);

        try {
            
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'fabiandustin27@gmail.com';
            $mail->Password = 'rgia wbwe giuu auze';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('fabiandustin27@gmail.com', 'To Do List');
            $mail->addAddress($email);

            $resetLink = "http://localhost/WebProg/forgor/reset_password?token=" . $token;

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "<p>Click the link below to reset your password:</p>
                            <p><a href='$resetLink'>Reset Password</a></p>
                            <p>If you did not request a password reset, please ignore this email.</p>";

            $mail->send();

            $_SESSION['email_success'] = 'Password reset link has been sent to your email.';
            unset( $_SESSION['email_fail']);

        } catch (Exception $e) {
            $_SESSION['email_fail'] = "Message could not be sent.";
            unset( $_SESSION['email_success']);
        }
    } else {
        $_SESSION['email_fail'] = "No account found with that email.";
        unset( $_SESSION['email_success']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
</head>

<body class="bg-gradient-to-r from-blue-200 to-cyan-200">

  <div class="container-fluid content-center p-6">

    <div class="w-25 h-100 mt-3">

        <form class="max-w-sm mx-auto" method="post">

            <h1 class="text-center text-4xl font-bold">Reset Password</h1>

            <?php if (isset($_SESSION['email_success'])): ?>
                <div class="bg-green-100 text-green-700 p-4 rounded mt-10">
                    <?php echo $_SESSION['email_success']; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['email_fail'])): ?>
                <div class="bg-red-100 text-red-700 p-4 rounded mt-10">
                    <?php echo $_SESSION['email_fail']; ?>
                </div>
            <?php endif; ?>
            <div class="mt-10">
                <label for="username" class="block text-gray-700 font-medium">Enter Your Email</label>
                <input type="email" name="email" class="w-full my-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div>
                <button type="submit" class="w-full my-5 bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    Send Email
                </button>
            </div>


        </form>

    </div>

</div>

</body>
</html>