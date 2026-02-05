<?php 
require __DIR__ . '/../partials/header.php'; 
?>

<div class="container" style="padding: 50px 0;">
    <div style="max-width: 800px; margin: 0 auto; text-align: center;">
        <h1 class="text-2xl font-bold mb-4">Ödeme Yap</h1>
        
        <div class="bg-white p-6 rounded shadow mb-6 text-left">
            <h2 class="text-lg font-bold mb-2">Sipariş Özeti</h2>
            <div class="flex justify-between border-b pb-2 mb-2">
                <span>Sipariş No:</span>
                <span class="font-bold"><?= htmlspecialchars($order['order_number']) ?></span>
            </div>
            <div class="flex justify-between border-b pb-2 mb-2">
                <span>Tutar:</span>
                <span class="font-bold text-blue-600"><?= number_format($order['total_amount'], 2) ?> TL</span>
            </div>
            <div class="flex justify-between">
                <span>Müşteri:</span>
                <span><?= htmlspecialchars($order['customer_name']) ?></span>
            </div>
        </div>

        <div id="paytr_iframe_container" style="width: 100%;">
            <p>Ödeme formu yükleniyor, lütfen bekleyiniz...</p>
        </div>
    </div>
</div>

<!-- PayTR Script -->
<script src="https://www.paytr.com/js/iframeResizer.min.js"></script>
<iframe src="https://www.paytr.com/odeme/guvenli/<?= $token_data['paytr_token'] ?>" id="paytriframe" frameborder="0" scrolling="no" style="width: 100%;"></iframe>
<script>iFrameResize({},'#paytriframe');</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
