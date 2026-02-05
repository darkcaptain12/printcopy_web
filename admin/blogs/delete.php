<?php
require_once '../includes/init.php';
check_auth();
$id = $_GET['id'] ?? null;
if ($id) {
    $pdo->prepare("DELETE FROM blogs WHERE id = ?")->execute([$id]);
    set_flash('success', 'Post deleted');
}
redirect('index.php');
