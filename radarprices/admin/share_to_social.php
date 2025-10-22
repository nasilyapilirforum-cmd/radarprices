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
$articleId = $input['article_id'] ?? null;
$platforms = $input['platforms'] ?? [];

if (!$articleId || empty($platforms)) {
    echo json_encode(['success' => false, 'message' => 'Makale ID ve platformlar gerekli']);
    exit;
}

try {
    // Makale bilgilerini al
    // $article = SELECT * FROM articles WHERE id = ?
    
    // Her platform için paylaşım yap
    foreach ($platforms as $platform) {
        switch ($platform) {
            case 'twitter':
                shareToTwitter($articleId, $article);
                break;
            case 'facebook':
                shareToFacebook($articleId, $article);
                break;
            case 'instagram':
                shareToInstagram($articleId, $article);
                break;
            case 'linkedin':
                shareToLinkedIn($articleId, $article);
                break;
            case 'telegram':
                shareToTelegram($articleId, $article);
                break;
        }
    }
    
    echo json_encode(['success' => true, 'message' => 'Makale sosyal medyalarda paylaşıldı']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Sosyal medya paylaşım fonksiyonları
function shareToTwitter($articleId, $article) {
    // Twitter API entegrasyonu
    // Tweet oluştur ve paylaş
}

function shareToFacebook($articleId, $article) {
    // Facebook API entegrasyonu
    // Facebook post oluştur ve paylaş
}

function shareToInstagram($articleId, $article) {
    // Instagram API entegrasyonu
    // Instagram post oluştur ve paylaş
}

function shareToLinkedIn($articleId, $article) {
    // LinkedIn API entegrasyonu
    // LinkedIn post oluştur ve paylaş
}

function shareToTelegram($articleId, $article) {
    // Telegram API entegrasyonu
    // Telegram kanalına mesaj gönder
}
?>