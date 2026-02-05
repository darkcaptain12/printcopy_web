<?php
namespace App\Core;

class EmailTemplates {

    private static $colors = [
        'primary' => '#2563eb', // Blue-600
        'bg' => '#f3f4f6',      // Gray-100
        'text' => '#1f2937',    // Gray-800
        'border' => '#e5e7eb',  // Gray-200
        'white' => '#ffffff'
    ];

    private static function getHeader($title) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$title}</title>
        </head>
        <body style='margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; background-color: " . self::$colors['bg'] . "; color: " . self::$colors['text'] . ";'>
            <div style='max-width: 600px; margin: 0 auto; background-color: " . self::$colors['white'] . "; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden;'>
                
                <!-- Logo Header -->
                <div style='background-color: " . self::$colors['primary'] . "; padding: 30px; text-align: center;'>
                    <h1 style='margin: 0; color: " . self::$colors['white'] . "; font-size: 24px; letter-spacing: 1px;'>PrintCopy</h1>
                </div>";
    }

    private static function getFooter() {
        return "
                <!-- Footer -->
                <div style='background-color: " . self::$colors['text'] . "; color: #9ca3af; padding: 20px; text-align: center; font-size: 12px;'>
                    <p style='margin: 0 0 10px 0;'>&copy; " . date('Y') . " PrintCopy Dijital Baskı Merkezi</p>
                    <p style='margin: 0;'>Bu e-posta otomatik olarak oluşturulmuştur, lütfen cevaplamayınız.</p>
                </div>
            </div>
        </body>
        </html>";
    }

    /**
     * 1. Sipariş Onay Maili (Müşteri)
     */
    public static function customerOrderSuccess($order, $items) {
        $header = self::getHeader("Siparişiniz Alındı");
        $footer = self::getFooter();
        $total = number_format($order['total_amount'], 2);
        $date = date('d.m.Y H:i', strtotime($order['created_at']));

        $itemsHtml = '';
        foreach ($items as $item) {
            $price = number_format($item['price'], 2);
            $rowTotal = number_format($item['price'] * $item['quantity'], 2);
            $itemsHtml .= "
            <tr style='border-bottom: 1px solid " . self::$colors['border'] . ";'>
                <td style='padding: 12px;'>
                    <strong>{$item['name']}</strong>
                </td>
                <td style='padding: 12px; text-align: center;'>{$item['quantity']}</td>
                <td style='padding: 12px; text-align: right;'>₺{$rowTotal}</td>
            </tr>";
        }

        return $header . "
            <div style='padding: 40px 30px;'>
                <h2 style='color: " . self::$colors['primary'] . "; margin-top: 0;'>Siparişiniz Onaylandı!</h2>
                <p style='line-height: 1.6;'>Sayın <strong>{$order['customer_name']}</strong>,</p>
                <p style='line-height: 1.6;'>Siparişiniz başarıyla alınmıştır. Ödemeniz onaylandı ve baskı süreci için hazırlıklara başlandı.</p>

                <div style='background-color: " . self::$colors['bg'] . "; border-radius: 8px; padding: 20px; margin: 30px 0;'>
                    <table style='width: 100%;'>
                        <tr>
                            <td style='padding-bottom: 8px; color: #6b7280;'>Sipariş No:</td>
                            <td style='padding-bottom: 8px; font-weight: bold; text-align: right;'>#{$order['order_number']}</td>
                        </tr>
                        <tr>
                            <td style='padding-bottom: 8px; color: #6b7280;'>Tarih:</td>
                            <td style='padding-bottom: 8px; font-weight: bold; text-align: right;'>{$date}</td>
                        </tr>
                        <tr>
                            <td style='padding-top: 8px; border-top: 1px solid #d1d5db; font-size: 18px; font-weight: bold;'>Toplam Tutar:</td>
                            <td style='padding-top: 8px; border-top: 1px solid #d1d5db; font-size: 18px; font-weight: bold; text-align: right; color: " . self::$colors['primary'] . ";'>₺{$total}</td>
                        </tr>
                    </table>
                </div>

                <h3 style='border-bottom: 2px solid " . self::$colors['border'] . "; padding-bottom: 10px; margin-bottom: 20px;'>Sipariş Detayı</h3>
                <table style='width: 100%; border-collapse: collapse; font-size: 14px;'>
                    <thead>
                        <tr style='background-color: " . self::$colors['bg'] . ";'>
                            <th style='padding: 10px; text-align: left;'>Ürün</th>
                            <th style='padding: 10px; text-align: center;'>Adet</th>
                            <th style='padding: 10px; text-align: right;'>Tutar</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$itemsHtml}
                    </tbody>
                </table>

                <div style='margin-top: 30px; border-top: 1px solid " . self::$colors['border'] . "; padding-top: 20px;'>
                    <h4 style='margin: 0 0 10px 0;'>Teslimat Adresi</h4>
                    <p style='margin: 0; color: #4b5563; line-height: 1.5;'>" . nl2br($order['address']) . "</p>
                </div>
            </div>" . $footer;
    }

    /**
     * 2. Yeni Sipariş Bildirimi (Admin)
     */
    public static function adminNewOrder($order, $items) {
        $header = self::getHeader("Yeni Sipariş Bildirimi");
        $footer = self::getFooter();
        $total = number_format($order['total_amount'], 2);

        $itemsHtml = '';
        foreach ($items as $item) {
            $itemsHtml .= "
            <li style='margin-bottom: 5px;'>
                {$item['quantity']}x <strong>{$item['name']}</strong>
            </li>";
        }

        return $header . "
            <div style='padding: 40px 30px;'>
                <div style='background-color: #dbeafe; border-left: 4px solid " . self::$colors['primary'] . "; padding: 15px; margin-bottom: 25px;'>
                    <h2 style='margin: 0; color: #1e40af; font-size: 20px;'>🔔 Yeni Sipariş Alındı</h2>
                </div>

                <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid " . self::$colors['border'] . ";'>Sipariş No:</td>
                        <td style='padding: 8px 0; border-bottom: 1px solid " . self::$colors['border'] . "; font-weight: bold;'>#{$order['order_number']}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid " . self::$colors['border'] . ";'>Müşteri:</td>
                        <td style='padding: 8px 0; border-bottom: 1px solid " . self::$colors['border'] . "; font-weight: bold;'>{$order['customer_name']}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid " . self::$colors['border'] . ";'>E-posta:</td>
                        <td style='padding: 8px 0; border-bottom: 1px solid " . self::$colors['border'] . ";'>{$order['customer_email']}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; border-bottom: 1px solid " . self::$colors['border'] . ";'>Telefon:</td>
                        <td style='padding: 8px 0; border-bottom: 1px solid " . self::$colors['border'] . ";'>{$order['customer_phone']}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; font-size: 18px; font-weight: bold;'>Tutar:</td>
                        <td style='padding: 8px 0; font-size: 18px; font-weight: bold; color: " . self::$colors['primary'] . ";'>₺{$total}</td>
                    </tr>
                </table>

                <h3 style='margin-top: 30px;'>Sipariş İçeriği:</h3>
                <ul style='background-color: " . self::$colors['bg'] . "; padding: 20px 40px; border-radius: 8px;'>
                    {$itemsHtml}
                </ul>

                <div style='text-align: center; margin-top: 30px;'>
                    <a href='http://localhost:8000/admin/orders/detail.php?id={$order['id']}' style='background-color: " . self::$colors['primary'] . "; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>Siparişi Yönet</a>
                </div>
            </div>" . $footer;
    }

    /**
     * 3. İletişim Formu Bildirimi
     * @param array $data ['name', 'email', 'subject', 'message']
     */
    public static function contactFormNotification($data) {
        $header = self::getHeader("İletişim Formu Mesajı");
        $footer = self::getFooter();
        
        $message = nl2br(htmlspecialchars($data['message']));

        return $header . "
            <div style='padding: 40px 30px;'>
                <div style='background-color: #fef3c7; border-left: 4px solid #d97706; padding: 15px; margin-bottom: 25px;'>
                    <h2 style='margin: 0; color: #92400e; font-size: 20px;'>📩 Yeni İletişim Mesajı</h2>
                </div>

                <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
                    <tr>
                        <td style='padding: 10px; background: " . self::$colors['bg'] . "; width: 30%;'><strong>Gönderen:</strong></td>
                        <td style='padding: 10px; border-bottom: 1px solid " . self::$colors['border'] . ";'>{$data['name']}</td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; background: " . self::$colors['bg'] . ";'><strong>E-posta:</strong></td>
                        <td style='padding: 10px; border-bottom: 1px solid " . self::$colors['border'] . ";'>
                            <a href='mailto:{$data['email']}' style='color: " . self::$colors['primary'] . ";'>{$data['email']}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 10px; background: " . self::$colors['bg'] . ";'><strong>Konu:</strong></td>
                        <td style='padding: 10px; border-bottom: 1px solid " . self::$colors['border'] . ";'>{$data['subject']}</td>
                    </tr>
                </table>

                <h3 style='margin-top: 30px;'>Mesaj:</h3>
                <div style='background-color: " . self::$colors['white'] . "; border: 1px solid " . self::$colors['border'] . "; padding: 20px; border-radius: 8px; line-height: 1.6;'>
                    {$message}
                </div>
            </div>" . $footer;
    }
}
