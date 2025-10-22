<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/ai_updater.php';

// 🤖 OTOMATİK GÜNCELLEME
$updater = new ProductAIUpdater();
if ($updater->checkAndUpdate()) {
    echo "✅ Ürün veritabanı güncellendi!";
    
    // Yeni verileri göster
    $new_products = $updater->updatePhoneDatabase();
    echo "<h3>Güncel Ürün Listesi:</h3>";
    foreach ($new_products as $product => $specs) {
        echo "<p><strong>{$product}</strong> - {$specs['brand']}</p>";
    }
} else {
    echo "ℹ️ Son güncelleme: " . date('d.m.Y H:i', $updater->getLastUpdateTime());
}
?>