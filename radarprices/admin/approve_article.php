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
$articleId = $input['id'] ?? null;

if (!$articleId) {
    echo json_encode(['success' => false, 'message' => 'Makale ID gerekli']);
    exit;
}

try {
    // Burada veritabanı güncelleme işlemi yapılacak
    // Örnek: UPDATE articles SET status = 'published' WHERE id = ?
    
    // Şimdilik örnek başarılı yanıt
    echo json_encode(['success' => true, 'message' => 'Makale onaylandı']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>