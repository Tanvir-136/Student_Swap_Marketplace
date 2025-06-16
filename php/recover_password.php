<?php
session_start();

// Redirect to profile if logged in
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

require_once 'db_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {
        // Step 1: Handle email submission
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format.';
        } else {
            try {
                // Check if email exists
                $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    // Generate token
                    $token = bin2hex(random_bytes(32));
                    $expires_at = date('Y-m-d H:i:s', strtotime('+7 hours'));

                    // Store token
                    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
                    $stmt->execute(['email' => $email, 'token' => $token, 'expires_at' => $expires_at]);
                    if ($stmt->rowCount() === 0) {
                        $error = 'Failed to store token. Debug: Insert failed.';
                    } else {
                        // Send email using PHPMailer
                        require 'PHPMailer/src/PHPMailer.php';
                        require 'PHPMailer/src/Exception.php';
                        require 'PHPMailer/src/SMTP.php';

                        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'tanvirislam017234@gmail.com';
                            $mail->Password = 'gybe dlkj vail ntpr';
                            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            $mail->setFrom('no-reply@studentswap.com', 'StudentSwap');
                            $mail->addAddress($email);
                            $mail->Subject = 'Password Reset Request';
                            $mail->Body = "Click this link to reset your password: http://localhost/web/php/reset_password.php?token=" . $token . "\nThis link expires in 1 hour.";

                            $mail->send();
                            $success = 'A password reset link has been sent to your email.';
                        } catch (Exception $e) {
                            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                    }
                } else {
                    $error = 'No account found with that email.';
                }
            } catch (PDOException $e) {
                $error = 'An error occurred. Please try again later. Debug: ' . $e->getMessage();
                file_put_contents('recover_password_error.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
            }
        }
    } elseif (isset($_POST['token']) && isset($_POST['new_password'])) {
        // Step 2: Handle password reset
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
        $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);

        if (strlen($new_password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } else {
            try {
                $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
                $stmt->execute(['token' => $token]);
                $reset = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($reset && is_array($reset)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
                    $update_result = $stmt->execute(['password' => $hashed_password, 'email' => $reset['email']]);
                    
                    if ($update_result !== false) { // Explicitly check for successful execution
                        $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = :token");
                        $stmt->execute(['token' => $token]);
                        $success = 'Password reset successful! Redirecting to login...';
                        header("Refresh: 2; URL=login.php");
                    } else {
                        $error = 'Failed to update password. Debug: Update query failed. Affected rows: ' . $stmt->rowCount();
                    }
                } else {
                    $error = 'Invalid or expired token. Debug: No valid reset record found for token ' . htmlspecialchars($token);
                }
            } catch (PDOException $e) {
                $error = 'An error occurred. Please try again later. Debug: ' . $e->getMessage();
                file_put_contents('recover_password_error.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
            }
        }
    }
}

$token = isset($_GET['token']) ? filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING) : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery - StudentSwap</title>
    <link rel="stylesheet" href="../css/recover_password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Navigation Bar (unchanged) -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <i class="fas fa-exchange-alt"></i>
                <span>StudentSwap</span>
            </div>
            <div class="navbar-links">
                <a href="../index.php"
                    class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>"><i
                        class="fas fa-home"></i> Home</a>
                <a href="listings.php"
                    class="<?php echo basename($_SERVER['PHP_SELF']) === 'listings.php' ? 'active' : ''; ?>"><i
                        class="fas fa-search"></i> Browse</a>
                <a href="post_item.php"
                    class="<?php echo basename($_SERVER['PHP_SELF']) === 'post_item.php' ? 'active' : ''; ?>"><i
                        class="fas fa-plus-circle"></i> Sell</a>
                <div class="navbar-dropdown">
                    <button class="dropdown-btn">
                        <i class="fas fa-user"></i> Account <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="profile.php"
                                class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : ''; ?>"><i
                                    class="fas fa-user-circle"></i> Profile</a>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        <?php else: ?>
                            <a href="login.php"
                                class="<?php echo basename($_SERVER['PHP_SELF']) === 'login.php' ? 'active' : ''; ?>"><i
                                    class="fas fa-sign-in-alt"></i> Login</a>
                            <a href="register.php"
                                class="<?php echo basename($_SERVER['PHP_SELF']) === 'register.php' ? 'active' : ''; ?>"><i
                                    class="fas fa-user-plus"></i> Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <button class="navbar-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Recovery Section -->
    <section class="hero compact">
        <div class="container">
            <div class="hero-content">
                <h1>Password Recovery</h1>
                <p class="hero-subtitle">Reset your password to regain access</p>
                <?php if ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <?php if ($success): ?>
                    <p class="success"><?php echo htmlspecialchars($success); ?></p>
                <?php endif; ?>

                <?php if (!$token): ?>
                    <!-- Request Email Form -->
                    <form action="recover_password.php" method="post" class="auth-form">
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
                    </form>
                <?php else: ?>
                    <!-- Reset Password Form -->
                    <form action="recover_password.php" method="post" class="auth-form">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                        <div class="form-group">
                            <label for="new_password"><i class="fas fa-lock"></i> New Password</label>
                            <input type="password" id="new_password" name="new_password" required>
                            <span class="password-hint">Minimum 8 characters</span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer (unchanged) -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <i class="fas fa-exchange-alt"></i>
                    <span>StudentSwap</span>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <a href="../index.php">Home</a>
                    <a href="listings.php">Browse</a>
                    <a href="post_item.php">Sell</a>
                    <a href="login.php">Login</a>
                </div>
                <div class="footer-contact">
                    <h4>Contact</h4>
                    <p><i class="fas fa-envelope"></i> contact@studentswap.com</p>
                    <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© <?php echo date('Y'); ?> StudentSwap. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../js/main.js"></script>
</body>

</html>