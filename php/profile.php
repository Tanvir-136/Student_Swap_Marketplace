<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'db_connect.php';
try {
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        session_destroy();
        header("Location: login.php?error=User%20not%20found");
        exit();
    }
} catch (PDOException $e) {
    header("Location: login.php?error=Database%20error");
    exit();
}

try {
    $stmt = $conn->prepare("SELECT id, title, category, price, description, created_at FROM items WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $items = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - StudentSwap</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <i class="fas fa-exchange-alt"></i>
                <span>StudentSwap</span>
            </div>
            <div class="navbar-links">
                <a href="../index.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'index.html' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a>
                <a href="../php/listings.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'listings.html' ? 'active' : ''; ?>"><i class="fas fa-search"></i> Browse</a>
                <a href="post_item.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'post_item.php' ? 'active' : ''; ?>"><i class="fas fa-plus-circle"></i> Sell</a>
                <div class="navbar-dropdown">
                    <button class="dropdown-btn">
                        <i class="fas fa-user"></i> Account <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : ''; ?>"><i class="fas fa-user-circle"></i> Profile</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
            <button class="navbar-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <main class="container">
        <?php if (isset($_GET['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <div class="profile-container">
            <h2 class="welcome">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
            <div class="my-items">
                <h3>My Items</h3>
                <?php if (empty($items)): ?>
                    <p class="no-items">No items listed yet. <a href="post_item.php">List your first item</a></p>
                <?php else: ?>
                    <div class="items-grid">
                        <?php foreach ($items as $item): ?>
                            <div class="item-card">
                                <div class="item-content">
                                    <h3 class="item-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                                    <div class="item-price">$<?php echo number_format($item['price'], 2); ?></div>
                                    <span class="item-category"><?php echo htmlspecialchars($item['category']); ?></span>
                                    <p class="item-description"><?php echo htmlspecialchars($item['description']); ?></p>
                                    <div class="item-meta">
                                        <span>Posted <?php echo date('M j, Y', strtotime($item['created_at'])); ?></span>
                                        <span class="status-badge status-available">Available</span>
                                    </div>
                                    <div class="item-actions">
                                        <form action="edit_item.php" method="get">
                                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</button>
                                        </form>
                                        <form action="delete_item.php" method="post" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" class="btn delete-btn"><i class="fas fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

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
                <p>&copy; <?php echo date('Y'); ?> StudentSwap. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script src="../js/main.js"></script>
</body>
</html>