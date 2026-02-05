<?php
require_once '../includes/init.php';
check_auth();

$id = $_GET['id'] ?? null;
if ($id) {
    $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
    set_flash('success', 'Category deleted');
}
redirect('index.php');
