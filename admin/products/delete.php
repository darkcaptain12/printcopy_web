<?php
require_once '../includes/init.php';
check_auth();

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    set_flash('success', 'Product deleted successfully');
}
redirect('index.php');
