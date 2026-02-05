<?php
// app/Views/payment/fail.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow text-center max-w-md">
        <div class="text-red-500 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        <h1 class="text-2xl font-bold mb-2">Payment Failed</h1>
        <p class="text-gray-600 mb-6">Unfortunately, your payment could not be processed. Please try again or contact support.</p>
        <a href="/checkout" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">Try Again</a>
    </div>
</body>
</html>
