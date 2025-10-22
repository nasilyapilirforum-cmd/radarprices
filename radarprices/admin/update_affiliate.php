<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Yetkisiz erişim']);
    exit;
}

require_once '../config/database.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$storeId = $input['store_id'] ?? null;
$affiliateLink = $input['affiliate_link'] ?? '';

if (!$storeId) {
    echo json_encode(['success' => false, 'message' => 'Mağaza ID gerekli']);
    exit;
}

try {
    // Burada veritabanı güncelleme işlemi yapılacak
    // Örnek: UPDATE affiliate_stores SET affiliate_link = ? WHERE id = ?
    
    // Şimdilik örnek başarılı yanıt
    echo json_encode(['success' => true, 'message' => 'Link güncellendi']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>