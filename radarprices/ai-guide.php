<?php
include 'includes/config.php';
include 'includes/functions.php';

// Rehber içeriğini getir
if (isset($_GET['title']) && isset($_GET['content'])) {
    $guide = [
        'title' => urldecode($_GET['title']),
        'content' => urldecode($_GET['content'])
    ];
} else {
    $guide = $_SESSION['ai_guide'] ?? [
        'title' => 'Yapay Zeka Rehberi',
        'content' => '<p>Rehber içeriği yükleniyor...</p>'
    ];
}

$page_title = $guide['title'] ?? 'Yapay Zeka Rehberi';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - <?php echo SITE_NAME; ?></title>
    <style>
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            line-height: 1.6; 
            margin: 0; 
            padding: 0; 
            color: #333;
            background: #f8f9fa;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            padding: 20px; 
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .ai-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            display: inline-block;
            margin-bottom: 15px;
            font-weight: 500;
        }
        .guide-content h2 {
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-top: 30px;
        }
        .guide-content h3 {
            color: #555;
            margin-top: 25px;
        }
        .sponsored-section {
            background: #fff3e0;
            padding: 20px;
            border-radius: 10px;
            margin: 25px 0;
            border-left: 4px solid #ff9800;
        }
        .back-button {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="ai-badge">🤖 Yapay Zeka ile Anında Oluşturuldu</div>
        <h1><?php echo htmlspecialchars($guide['title'] ?? 'Rehber'); ?></h1>
        
        <div class="guide-content">
            <?php echo $guide['content'] ?? '<p>Rehber içeriği bulunamadı.</p>'; ?>
        </div>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; text-align: center;">
            <a href="javascript:history.back()" class="back-button">← Geri Dön</a>
            <a href="index.php" style="margin-left: 15px; color: #667eea; text-decoration: none;">🏠 Ana Sayfa</a>
        </div>
    </div>
</body>
</html>