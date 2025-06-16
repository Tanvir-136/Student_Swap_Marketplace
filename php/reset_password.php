<?php
session_start();

require_once 'db_connect.php';

// Initialize variables
$token = isset($_GET['token']) ? filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING) : '';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token']) && isset($_POST['new_password'])) {
    // Correct usage of filter_input with INPUT_POST
    $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING); // Fixed here
    $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);

    if (strlen($new_password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } else {
        try {
            $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()");
            $stmt->execute(['token' => $token]);
            $reset = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($reset === false) {
                $error = 'Invalid or expired token. Debug: No row found for token ' . htmlspecialchars($token);
            } elseif (!is_array($reset)) {
                $error = 'Unexpected query result. Debug: reset is ' . var_export($reset, true);
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = :password WHERE email = :email");
                $update_result = $stmt->execute(['password' => $hashed_password, 'email' => $reset['email']]);

                if ($update_result) {
                    $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = :token");
                    $stmt->execute(['token' => $token]);
                    $success = 'Password reset successful! Redirecting to login...';
                    header("Refresh: 2; URL=login.php");
                } else {
                    $error = 'Failed to update password. Debug: Update query failed.';
                }
            }
        } catch (PDOException $e) {
            $error = 'An error occurred. Please try again later. Debug: ' . $e->getMessage();
            file_put_contents('reset_password_error.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - StudentSwap</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/recover_password.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <i class="fas fa-exchange-alt"></i>
                <span>StudentSwap</span>
            </div>
            <div class="navbar-links">
                <a href="../index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a>
                <a href="listings.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'listings.php' ? 'active' : ''; ?>"><i class="fas fa-search"></i> Browse</a>
                <a href="post_item.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'post_item.php' ? 'active' : ''; ?>"><i class="fas fa-plus-circle"></i> Sell</a>
                <div class="navbar-dropdown">
                    <button class="dropdown-btn">
                        <i class="fas fa-user"></i> Account <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : ''; ?>"><i class="fas fa-user-circle"></i> Profile</a>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'login.php' ? 'active' : ''; ?>"><i class="fas fa-sign-in-alt"></i> Login</a>
                            <a href="register.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'register.php' ? 'active' : ''; ?>"><i class="fas fa-user-plus"></i> Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <button class="navbar-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Reset Password Section -->
    <section class="hero compact">
        <div class="container">
            <div class="hero-content">
                <h1>Reset Password</h1>
                <p class="hero-subtitle">Enter your new password</p>
                <?php if ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <?php if ($success): ?>
                    <p class="success"><?php echo htmlspecialchars($success); ?></p>
                <?php endif; ?>
                <form action="reset_password.php" method="post" class="auth-form">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <div class="form-group">
                        <label for="new_password"><i class="fas fa-lock"></i> New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                        <span class="password-hint">Minimum 8 characters</span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
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