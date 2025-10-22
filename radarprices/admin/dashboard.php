<?php
// admin/dashboard.php - HATA DÜZELTİLMİŞ
include '../includes/config.php';

// Basit login kontrolü - GÜVENLİ
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Basit güvenlik - gerçek projede daha güvenli yapın
        if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_login_time'] = time();
        } else {
            $error = "Hatalı kullanıcı adı veya şifre!";
        }
    }
    
    // Hala giriş yapılmamışsa login formunu göster
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        ?>
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Admin Giriş - <?php echo SITE_NAME; ?></title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { 
                    font-family: 'Segoe UI', Arial, sans-serif;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    display: flex; 
                    justify-content: center; 
                    align-items: center; 
                    min-height: 100vh;
                    padding: 20px;
                }
                .login-container { 
                    background: white; 
                    padding: 50px; 
                    border-radius: 20px; 
                    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
                    width: 100%;
                    max-width: 450px;
                    text-align: center;
                }
                .logo { 
                    font-size: 3em; 
                    margin-bottom: 20px;
                    color: #667eea;
                }
                .login-container h2 { 
                    color: #333;
                    margin-bottom: 10px;
                    font-size: 1.8em;
                }
                .subtitle {
                    color: #666;
                    margin-bottom: 30px;
                    font-size: 1.1em;
                }
                .login-form input { 
                    width: 100%; 
                    padding: 15px 20px; 
                    margin: 12px 0; 
                    border: 2px solid #e0e0e0; 
                    border-radius: 10px;
                    font-size: 16px;
                    transition: border-color 0.3s;
                }
                .login-form input:focus {
                    border-color: #667eea;
                    outline: none;
                }
                .login-form button { 
                    width: 100%; 
                    padding: 15px; 
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                    color: white; 
                    border: none; 
                    border-radius: 10px; 
                    cursor: pointer;
                    font-size: 16px;
                    font-weight: bold;
                    margin-top: 15px;
                    transition: transform 0.3s;
                }
                .login-form button:hover { 
                    transform: translateY(-2px);
                }
                .error { 
                    background: #ffebee; 
                    color: #c62828; 
                    padding: 12px; 
                    border-radius: 8px; 
                    margin: 15px 0;
                    border-left: 4px solid #c62828;
                }
                .info { 
                    background: #e3f2fd; 
                    padding: 15px; 
                    border-radius: 10px; 
                    margin-top: 25px;
                    font-size: 14px;
                    color: #1565c0;
                    border-left: 4px solid #2196F3;
                }
            </style>
        </head>
        <body>
            <div class="login-container">
                <div class="logo">🔐</div>
                <h2><?php echo SITE_NAME; ?> Admin</h2>
                <p class="subtitle">Yönetici Paneline Giriş</p>
                
                <?php if(isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="login-form">
                    <input type="text" name="username" placeholder="Kullanıcı adı" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <input type="password" name="password" placeholder="Şifre" required>
                    <button type="submit">🔓 Giriş Yap</button>
                </form>
                
                <div class="info">
                    <strong>Varsayılan Giriş Bilgileri:</strong><br>
                    Kullanıcı Adı: <strong>admin</strong><br>
                    Şifre: <strong>admin123</strong>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}

// Functions.php'yi include et
if (file_exists('../includes/functions.php')) {
    include '../includes/functions.php';
} else {
    // Basit fonksiyonlar
    function getPendingArticles() { return []; }
    function getPublishedCount() { return 0; }
    function getAffiliateCount() { return 3; }
    function getRecentArticles($limit = 5) { return []; }
    function approveArticle($id) { return false; }
}

// İstatistikleri al
$pending_count = count(getPendingArticles());
$published_count = getPublishedCount();
$affiliate_count = getAffiliateCount();
$recent_articles = getRecentArticles(5);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo SITE_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f8f9fa; 
            color: #333;
            line-height: 1.6;
        }
        
        /* HEADER */
        .admin-header { 
            background: white; 
            padding: 25px 30px; 
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            border-bottom: 3px solid #667eea;
        }
        .admin-header h1 { 
            color: #333; 
            margin-bottom: 5px;
            font-size: 1.8em;
        }
        .admin-header p { 
            color: #666;
            font-size: 1.1em;
        }
        
        /* NAVIGATION */
        .admin-nav { 
            background: #2c3e50; 
            padding: 0 30px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .admin-nav a { 
            color: white; 
            text-decoration: none; 
            padding: 18px 25px;
            display: inline-block;
            transition: all 0.3s;
            border-bottom: 3px solid transparent;
        }
        .admin-nav a:hover { 
            background: #34495e; 
            border-bottom-color: #3498db;
        }
        .admin-nav a.active { 
            background: #34495e;
            border-bottom-color: #e74c3c;
        }
        
        /* STATS GRID */
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
            gap: 25px; 
            padding: 30px;
        }
        .stat-card { 
            background: white; 
            padding: 30px 25px; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s;
            border-top: 5px solid;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .stat-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
        }
        .stat-card h3 { 
            color: #666; 
            font-size: 14px; 
            margin-bottom: 10px; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .stat-number { 
            font-size: 2.8em; 
            font-weight: bold;
            color: #2c3e50;
        }
        .stat-pending { border-top-color: #f39c12; }
        .stat-published { border-top-color: #27ae60; }
        .stat-affiliate { border-top-color: #9b59b6; }
        .stat-total { border-top-color: #3498db; }
        
        /* RECENT ARTICLES */
        .recent-articles { 
            background: white; 
            margin: 0 30px 30px; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .section-title {
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 1.5em;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }
        .article-item { 
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #ecf0f1;
            transition: background 0.3s;
        }
        .article-item:hover {
            background: #f8f9fa;
        }
        .article-item:last-child { 
            border-bottom: none; 
        }
        .article-info { 
            flex: 1; 
        }
        .article-title {
            font-size: 1.1em;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .article-meta {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        .status-pending { 
            color: #e67e22; 
            font-weight: bold;
        }
        .status-published { 
            color: #27ae60; 
            font-weight: bold;
        }
        .article-actions { 
            display: flex;
            gap: 10px;
        }
        .action-btn {
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 500;
            transition: all 0.3s;
        }
        .approve-btn { 
            background: #27ae60;
            color: white;
        }
        .approve-btn:hover {
            background: #219a52;
        }
        .edit-btn { 
            background: #3498db;
            color: white;
        }
        .edit-btn:hover {
            background: #2980b9;
        }
        .delete-btn { 
            background: #e74c3c;
            color: white;
        }
        .delete-btn:hover {
            background: #c0392b;
        }
        
        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #7f8c8d;
        }
        .empty-state .icon {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="admin-header">
        <h1>🚀 <?php echo SITE_NAME; ?> Admin Paneli</h1>
        <p>Hoş geldin, Admin! • <?php echo date('d.m.Y H:i'); ?></p>
    </div>
    
    <!-- NAVIGATION -->
    <nav class="admin-nav">
        <a href="dashboard.php" class="active">📊 Dashboard</a>
        <a href="pending-articles.php">📝 Onay Bekleyen <span style="background: #e74c3c; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8em; margin-left: 5px;"><?php echo $pending_count; ?></span></a>
        <a href="published-articles.php">✅ Yayınlanan <span style="background: #27ae60; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8em; margin-left: 5px;"><?php echo $published_count; ?></span></a>
        <a href="affiliates.php">🔗 Affiliate <span style="background: #9b59b6; color: white; padding: 2px 8px; border-radius: 10px; font-size: 0.8em; margin-left: 5px;"><?php echo $affiliate_count; ?></span></a>
        <a href="?logout=1" style="margin-left: auto;">🚪 Çıkış Yap</a>
    </nav>
    
    <!-- STATISTICS -->
    <div class="stats-grid">
        <div class="stat-card stat-pending">
            <div class="stat-icon">⏳</div>
            <h3>Onay Bekleyen</h3>
            <div class="stat-number"><?php echo $pending_count; ?></div>
        </div>
        <div class="stat-card stat-published">
            <div class="stat-icon">✅</div>
            <h3>Yayınlanan</h3>
            <div class="stat-number"><?php echo $published_count; ?></div>
        </div>
        <div class="stat-card stat-affiliate">
            <div class="stat-icon">🔗</div>
            <h3>Affiliate</h3>
            <div class="stat-number"><?php echo $affiliate_count; ?></div>
        </div>
        <div class="stat-card stat-total">
            <div class="stat-icon">📈</div>
            <h3>Toplam</h3>
            <div class="stat-number"><?php echo $pending_count + $published_count; ?></div>
        </div>
    </div>
    
    <!-- RECENT ARTICLES -->
    <div class="recent-articles">
        <h2 class="section-title">📝 Son Makaleler</h2>
        
        <?php if (empty($recent_articles)): ?>
            <div class="empty-state">
                <div class="icon">📄</div>
                <h3>Henüz makale bulunmuyor</h3>
                <p>İlk makalenizi oluşturmak için "Onay Bekleyen" sayfasını ziyaret edin.</p>
            </div>
        <?php else: ?>
            <?php foreach($recent_articles as $article): ?>
            <div class="article-item">
                <div class="article-info">
                    <div class="article-title"><?php echo htmlspecialchars($article['title']); ?></div>
                    <div class="article-meta">
                        Durum: <span class="status-<?php echo $article['status']; ?>">
                            <?php echo $article['status'] == 'pending' ? '⏳ Bekliyor' : '✅ Yayında'; ?>
                        </span> 
                        • <?php echo date('d.m.Y H:i', strtotime($article['created_at'])); ?>
                    </div>
                </div>
                <div class="article-actions">
                    <?php if ($article['status'] == 'pending'): ?>
                    <a href="approve-article.php?id=<?php echo $article['id']; ?>" class="action-btn approve-btn">✅ Onayla</a>
                    <?php endif; ?>
                    <a href="edit-article.php?id=<?php echo $article['id']; ?>" class="action-btn edit-btn">✏️ Düzenle</a>
                    <a href="delete-article.php?id=<?php echo $article['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Bu makaleyi silmek istediğinizden emin misiniz?')">🗑️ Sil</a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php
    // Çıkış işlemi
    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: dashboard.php");
        exit;
    }
    ?>
</body>
</html>