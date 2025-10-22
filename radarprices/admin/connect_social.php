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
$platform = $input['platform'] ?? '';
$accountId = $input['account_id'] ?? '';

if (!$platform || !$accountId) {
    echo json_encode(['success' => false, 'message' => 'Platform ve hesap ID gerekli']);
    exit;
}

try {
    // Burada sosyal medya API bağlantısı yapılacak
    // Platforma göre API entegrasyonu
    
    // Veritabanına kaydetme
    // INSERT/UPDATE social_media SET account_id = ?, status = 'connected' WHERE platform = ?
    
    echo json_encode(['success' => true, 'message' => 'Bağlantı başarılı']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>