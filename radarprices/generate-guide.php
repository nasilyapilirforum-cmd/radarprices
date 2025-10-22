<?php
include 'includes/config.php';
include 'includes/functions.php';

header('Content-Type: application/json');

$topic = $_GET['topic'] ?? '';
$category = $_GET['category'] ?? 'genel';

if (empty($topic)) {
    echo json_encode(['success' => false, 'error' => 'Konu belirtilmedi']);
    exit;
}

// Yapay zeka ile rehber oluştur
$guide = generateAIGuide($topic, $category);

// Backlink ekle
$guide['content'] = addBacklinksToContent($guide['content']);

// Makaleyi oluştur (direkt yayınla - onaysız)
if (createNewArticle($guide['title'], $guide['content'], 1, 1)) {
    echo json_encode([
        'success' => true, 
        'article_url' => 'ai-guide.php?title=' . urlencode($guide['title']) . '&content=' . urlencode($guide['content']),
        'title' => $guide['title']
    ]);
} else {
    // Database yoksa session'a kaydet
    $_SESSION['ai_guide'] = $guide;
    echo json_encode([
        'success' => true, 
        'article_url' => 'ai-guide.php',
        'title' => $guide['title']
    ]);
}
?>