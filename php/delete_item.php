<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/login.html");
    exit();
}

require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id'])) {
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT);
    try {
        $stmt = $conn->prepare("DELETE FROM items WHERE id = :item_id AND user_id = :user_id");
        $stmt->execute(['item_id' => $item_id, 'user_id' => $_SESSION['user_id']]);
        if ($stmt->rowCount() === 0) {
            header("Location: profile.php?error=Item%20not%20found");
            exit();
        }
        header("Location: profile.php");
        exit();
    } catch (PDOException $e) {
        file_put_contents('delete_item_error.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
        header("Location: profile.php?error=Database%20error");
        exit();
    }
}

header("Location: profile.php");
exit();
?>