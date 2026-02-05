<?php
// tools/migrate_admin_note.php

require_once __DIR__ . '/../config/database.php';

try {
    $db = (new Database())->getConnection();
    
    // Check if column exists
    $stmt = $db->query("SHOW COLUMNS FROM orders LIKE 'admin_note'");
    $exists = $stmt->fetch();
    
    if (!$exists) {
        $db->exec("ALTER TABLE orders ADD COLUMN admin_note TEXT NULL DEFAULT NULL");
        echo "Column 'admin_note' added successfully.\n";
    } else {
        echo "Column 'admin_note' already exists.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
