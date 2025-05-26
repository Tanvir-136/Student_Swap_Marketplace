<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/login.html");
    exit();
}

require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['item_id'])) {
    $item_id = filter_input(INPUT_GET, 'item_id', FILTER_SANITIZE_NUMBER_INT);
    try {
        $stmt = $conn->prepare("SELECT title, category, price, description FROM items WHERE id = :item_id AND user_id = :user_id");
        $stmt->execute(['item_id' => $item_id, 'user_id' => $_SESSION['user_id']]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$item) {
            header("Location: profile.php?error=Item%20not%20found");
            exit();
        }
    } catch (PDOException $e) {
        file_put_contents('edit_item_error.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
        header("Location: profile.php?error=Database%20error");
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    $errors = [];
    if (empty($title) || empty($category) || empty($price) || empty($description)) {
        $errors[] = "All fields are required.";
    }

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("UPDATE items SET title = :title, category = :category, price = :price, description = :description WHERE id = :item_id AND user_id = :user_id");
            $stmt->execute([
                'title' => $title,
                'category' => $category,
                'price' => $price,
                'description' => $description,
                'item_id' => $item_id,
                'user_id' => $_SESSION['user_id']
            ]);
            header("Location: profile.php");
            exit();
        } catch (PDOException $e) {
            file_put_contents('edit_item_error.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
            header("Location: edit_item.php?item_id=$item_id&error=Database%20error");
            exit();
        }
    } else {
        header("Location: edit_item.php?item_id=$item_id&error=" . urlencode(implode(" ", $errors)));
        exit();
    }
} else {
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item - StudentSwap</title>
    <link rel="stylesheet" href="../css/post_item.css">
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
                <a href="../index.php" class="active"><i class="fas fa-home"></i> Home</a>
                <a href="listings.php"><i class="fas fa-search"></i> Browse</a>
                <a href="post_item.php"><i class="fas fa-plus-circle"></i> Sell</a>
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
        <div class="post-form">
            <h2>Edit Item</h2>
            <?php if (isset($_GET['error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            <form action="../php/edit_item.php" method="post">
                <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item_id); ?>">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                        <option value="textbooks" <?php echo $item['category'] === 'textbooks' ? 'selected' : ''; ?>>Textbooks</option>
                        <option value="electronics" <?php echo $item['category'] === 'electronics' ? 'selected' : ''; ?>>Electronics</option>
                        <option value="others" <?php echo $item['category'] === 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price ($)</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($item['price']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
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
                    <a href="index.html">Home</a>
                    <a href="html/listings.html">Browse</a>
                    <a href="html/post_item.html">Sell</a>
                    <a href="html/login.html">Login</a>
                </div>
                
                <div class="footer-contact">
                    <h4>Contact</h4>
                    <p><i class="fas fa-envelope"></i> contact@studentswap.com</p>
                    <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2023 StudentSwap. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script src="../js/main.js"></script>
</body>
</html>