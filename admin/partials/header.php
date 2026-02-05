<?php require_once __DIR__ . '/../includes/init.php'; check_auth(); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= t('common.admin_panel') ?> - PrintCopy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- Include Sidebar -->
    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white shadow flex items-center justify-between px-6">
            <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex items-center gap-6 ml-auto">
                <span class="text-sm text-gray-600">Admin: <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
            <?= get_flash() ?>
