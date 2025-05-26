<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include PDO database connection
require_once 'db_connect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT, FILTER_SANITIZE_NUMBER_INT);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($title) || empty($category) || empty($price) || empty($description)) {
        $error = "All fields are required.";
    } else {
        try {
            // Insert into database with user_id
            $stmt = $conn->prepare("INSERT INTO items (title, category, price, description, user_id) VALUES (:title, :category, :price, :description, :user_id)");
            $stmt->execute([
                'title' => $title,
                'category' => $category,
                'price' => $price,
                'description' => $description,
                'user_id' => $_SESSION['user_id']
            ]);

            // Redirect to listings page on success
            header("Location: listings.php");
            exit();
        } catch (PDOException $e) {
            $error = "Failed to post item: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudentSwap - Sell Item</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/post_item.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar (Same as index.php, register.php, login.php) -->
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

    <!-- Hero Section (Compact Form, Centered) -->
    <section class="hero compact">
        <div class="container">
            <div class="hero-content">
                <h1>Sell Your Item</h1>
                <p class="hero-subtitle">List your item for sale or trade on campus</p>
                <form action="post_item.php" method="post" class="auth-form">
                    <?php if (isset($error)): ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="title"><i class="fas fa-tag"></i> Item Title</label>
                        <input type="text" id="title" name="title" value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>" placeholder="e.g., Calculus Textbook" required>
                    </div>
                    <div class="form-group">
                        <label for="category"><i class="fas fa-list"></i> Category</label>
                        <select id="category" name="category" required>
                            <option value="">Select a category</option>
                            <option value="textbooks" <?php echo isset($category) && $category === 'textbooks' ? 'selected' : ''; ?>>Textbooks</option>
                            <option value="electronics" <?php echo isset($category) && $category === 'electronics' ? 'selected' : ''; ?>>Electronics</option>
                            <option value="other" <?php echo isset($category) && $category === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price"><i class="fas fa-dollar-sign"></i> Price ($)</label>
                        <input type="number" id="price" name="price" min="0" step="0.01" value="<?php echo isset($price) ? htmlspecialchars($price) : ''; ?>" placeholder="e.g., 29.99" required>
                    </div>
                    <div class="form-group">
                        <label for="description"><i class="fas fa-align-left"></i> Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Describe your item..." required><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Post Item</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer (Same as index.php, register.php, login.php) -->
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