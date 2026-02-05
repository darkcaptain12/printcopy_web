<?php
// app/Views/payment/success.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow text-center max-w-md">
        <div class="text-green-500 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h1 class="text-2xl font-bold mb-2">Payment Successful!</h1>
        <p class="text-gray-600 mb-6">Thank you for your order. We have received your payment and will process your order shortly.</p>
        <a href="/" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Return to Home</a>
    </div>
    <?php 
    // Clear cart on success page view (user context)
    if(session_status() == PHP_SESSION_NONE) session_start();
    unset($_SESSION['cart']);
    ?>
</body>
</html>
