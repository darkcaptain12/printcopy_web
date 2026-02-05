<?php
// Simple Thank You page
$orderNo = htmlspecialchars($_GET['order'] ?? '');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Teşekkürler - PrintCopy</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-body min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow-lg border text-center max-w-lg mx-auto">
        <div class="text-green-600 text-4xl mb-4">✔</div>
        <h1 class="text-2xl font-bold mb-2">Siparişiniz alındı</h1>
        <?php if($orderNo): ?>
            <p class="text-muted mb-4">Sipariş Numaranız: <strong><?= $orderNo ?></strong></p>
        <?php endif; ?>
        <p class="text-muted mb-6">Ödeme bilgilendirmesi e-posta adresinize gönderilecektir. Sorularınız için bizimle iletişime geçebilirsiniz.</p>
        <div class="flex gap-3 justify-center">
            <a href="/products" class="btn btn-outline">Alışverişe Devam Et</a>
            <a href="/contact" class="btn btn-primary">Destek Al</a>
        </div>
    </div>
</body>
</html>
