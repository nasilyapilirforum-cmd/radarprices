<?php
include 'includes/config.php';
include 'includes/functions.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';

if (empty($query)) {
    echo json_encode([]);
    exit;
}

$products = searchProducts($query, 8);

// Debug için
error_log("Arama sorgusu: " . $query);
error_log("Bulunan ürün sayısı: " . count($products));

echo json_encode($products);
?>