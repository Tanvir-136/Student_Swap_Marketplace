<?php
session_start();

// Redirect to profile if logged in (optional consistency with other pages)
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - StudentSwap</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/about_us.css"> <!-- Custom CSS for About Us -->
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

    <!-- About Us Section -->
    <section class="hero about-us">
        <div class="container">
            <div class="hero-content">
                <h1>About Us</h1>
                <p class="hero-subtitle">Explore the Core of StudentSwap</p>

                <div class="about-sections">
                    <!-- Vision Section -->
                    <div class="section">
                        <i class="fas fa-eye section-icon"></i>
                        <h2>Our Vision</h2>
                        <p>We aim to create a sustainable student community where item exchanges reduce waste and promote collaboration worldwide by 2030.</p>
                    </div>

                    <!-- Mission Section -->
                    <div class="section">
                        <i class="fas fa-bullseye section-icon"></i>
                        <h2>Our Mission</h2>
                        <p>Our mission is to provide a secure platform for students to trade items, supported by a MySQL database with tables like <code>users</code>, <code>listings</code>, and <code>transactions</code>.</p>
                    </div>

                    <!-- Advantages Section -->
                    <div class="section">
                        <i class="fas fa-star section-icon"></i>
                        <h2>Advantages</h2>
                        <ul>
                            <li><strong>Affordable Deals:</strong> Buy and sell at student-friendly prices.</li>
                            <li><strong>Secure Trades:</strong> Track via <code>transactions</code> with foreign keys.</li>
                            <li><strong>Fast Recovery:</strong> Reset passwords with <code>password_resets</code> (valid 1 hour).</li>
                            <li><strong>Connect Easily:</strong> Message others with <code>messages</code>.</li>
                            <li><strong>Go Green:</strong> Reuse items from <code>listings</code>.</li>
                            <li><strong>24/7 Access:</strong> Always available with a reliable database.</li>
                        </ul>
                    </div>
                </div>

                <div class="call-to-action">
                    <p>Join us! <a href="mailto:contact@studentswap.com">Contact us</a> or call (123) 456-7890.</p>
                </div>
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