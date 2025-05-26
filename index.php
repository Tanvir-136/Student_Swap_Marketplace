<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
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
                <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a>
                <a href="php/listings.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'listings.html' ? 'active' : ''; ?>"><i class="fas fa-search"></i> Browse</a>
                <a href="php/post_item.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'post_item.php' ? 'active' : ''; ?>"><i class="fas fa-plus-circle"></i> Sell</a>
                <div class="navbar-dropdown">
                    <button class="dropdown-btn">
                        <i class="fas fa-user"></i> Account <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="php/profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : ''; ?>"><i class="fas fa-user-circle"></i> Profile</a>
                            <a href="php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        <?php else: ?>
                            <a href="php/login.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'login.php' ? 'active' : ''; ?>"><i class="fas fa-sign-in-alt"></i> Login</a>
                            <a href="php/register.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'register.php' ? 'active' : ''; ?>"><i class="fas fa-user-plus"></i> Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <button class="navbar-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Buy & Sell Within Your Campus</h1>
                <p class="hero-subtitle">Save money and reduce waste by trading with fellow students</p>
                <div class="hero-buttons">
                    <a href="php/listings.php" class="btn btn-primary">
                        <i class="fas fa-search"></i> Browse Items
                    </a>
                    <a href="php/register.php" class="btn btn-secondary">
                        <i class="fas fa-hand-holding-usd"></i> Start Selling
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" alt="Students exchanging items">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Why Choose StudentSwap?</h2>
            <p class="section-subtitle">The easiest way to buy and sell on campus</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Verified Students</h3>
                    <p>All users must have a university email address</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3>Save Money</h3>
                    <p>Get items at student-friendly prices</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Quick & Easy</h3>
                    <p>List items in just a few minutes</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories">
        <div class="container">
            <h2 class="section-title">Popular Categories</h2>
            <p class="section-subtitle">Find what you're looking for</p>
            
            <div class="categories-grid">
                <a href="listings.html?category=textbooks" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>Textbooks</h3>
                    <p>Required course materials</p>
                </a>
                
                <a href="listings.html?category=electronics" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h3>Electronics</h3>
                    <p>Laptops, phones, and more</p>
                </a>
                
                <a href="listings.html?category=furniture" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-couch"></i>
                    </div>
                    <h3>Furniture</h3>
                    <p>Dorm essentials</p>
                </a>
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
                    <a href="index.php">Home</a>
                    <a href="php/listings.php">Browse</a>
                    <a href="php/post_item.php">Sell</a>
                    <a href="php/login.php">Login</a>
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
    <script src="js/main.js"></script>
</body>
</html>