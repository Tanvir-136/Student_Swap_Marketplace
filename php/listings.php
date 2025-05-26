<?php
session_start();
require_once 'db_connect.php';

// Get search and category from form
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

try {
    $sql = "SELECT items.id, items.title, items.category, items.price, items.description, users.username, users.email 
            FROM items 
            LEFT JOIN users ON items.user_id = users.id";
    $conditions = [];
    $params = [];

    if ($search) {
        $conditions[] = "items.title LIKE :search";
        $params[':search'] = '%' . $search . '%';
    }

    if ($category) {
        $conditions[] = "items.category = :category";
        $params[':category'] = $category;
    }

    if ($conditions) {
        $sql .= " WHERE " . implode(' AND ', $conditions);
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $items = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Items - StudentSwap</title>
    <link rel="stylesheet" href="../css/listings.css">
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

    <section class="listings">
        <div class="container">
            <h2 class="section-title">Available Items</h2>
            <div class="search-filter">
                <form action="listings.php" method="get">
                    <input type="text" name="search" placeholder="Search items..." value="<?php echo htmlspecialchars($search); ?>">
                    <select name="category">
                        <option value="">All</option>
                        <option value="textbooks" <?php echo $category === 'textbooks' ? 'selected' : ''; ?>>Textbooks</option>
                        <option value="electronics" <?php echo $category === 'electronics' ? 'selected' : ''; ?>>Electronics</option>
                        <option value="other" <?php echo $category === 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="item-grid">
                <?php if (empty($items)): ?>
                    <p class="no-items">No items found.</p>
                <?php else: ?>
                    <?php foreach ($items as $index => $item): ?>
                        <div class="item-card">
                            <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                            <p class="price">$<?php echo number_format($item['price'], 2); ?></p>
                            <p class="description"><?php echo htmlspecialchars($item['description']); ?></p>
                            <button class="btn btn-primary contact-btn" onclick="toggleContact(<?php echo $index; ?>)">Contact Seller</button>
                            <div class="contact-info" id="contact-<?php echo $index; ?>">
                                <?php if ($item['username'] && $item['email']): ?>
                                    <p>Seller: <?php echo htmlspecialchars($item['username']); ?></p>
                                    <p>Contact: <a href="mailto:<?php echo htmlspecialchars($item['email']); ?>"><?php echo htmlspecialchars($item['email']); ?></a></p>
                                <?php else: ?>
                                    <p>Seller info not available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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
    <script>
        function toggleContact(index) {
            const contactDiv = document.getElementById('contact-' + index);
            contactDiv.classList.toggle('show');
        }
    </script>
</body>
</html>