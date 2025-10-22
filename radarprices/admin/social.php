<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Veritabanı bağlantısı
require_once '../config/database.php';

// Sosyal medya işlemleri
if ($_POST) {
    if (isset($_POST['add_social'])) {
        $social_name = $_POST['social_name'];
        $account_id = $_POST['account_id'];
        
        // Veritabanına ekleme işlemi
        // INSERT INTO social_media (social_name, account_id) VALUES (?, ?)
        
        $_SESSION['message'] = "Sosyal medya başarıyla eklendi!";
        header('Location: social.php');
        exit;
    }
    
    if (isset($_POST['share_article'])) {
        $article_id = $_POST['article_id'];
        $social_platforms = $_POST['social_platforms'] ?? [];
        
        // Seçilen sosyal medyalara paylaşım yap
        foreach ($social_platforms as $platform) {
            // API ile paylaşım yap
            shareToSocialMedia($article_id, $platform);
        }
        
        $_SESSION['message'] = "Makale sosyal medyalarda paylaşıldı!";
        header('Location: pending.php'); // Onay bekleyenlere dön
        exit;
    }
}

// Sosyal medya hesaplarını veritabanından çek
// $social_accounts = SELECT * FROM social_media ORDER BY social_name
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sosyal Medya - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .admin-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .admin-header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .admin-nav {
            background: #34495e;
            padding: 0;
        }

        .admin-nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }

        .admin-nav li {
            margin: 0;
        }

        .admin-nav a {
            color: white;
            text-decoration: none;
            padding: 20px 25px;
            display: block;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }

        .admin-nav a:hover, 
        .admin-nav a.active {
            background: #2c3e50;
            border-bottom: 3px solid #3498db;
        }

        .content {
            padding: 40px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
        }

        .page-header h2 {
            color: #2c3e50;
            font-size: 2em;
        }

        /* Sosyal Medya Kartları */
        .social-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .social-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            border-left: 5px solid #e1306c; /* Varsayılan renk */
        }

        .social-card.twitter { border-left-color: #1da1f2; }
        .social-card.facebook { border-left-color: #1877f2; }
        .social-card.instagram { border-left-color: #e1306c; }
        .social-card.linkedin { border-left-color: #0077b5; }
        .social-card.telegram { border-left-color: #0088cc; }

        .social-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .social-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .social-icon {
            font-size: 2em;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: white;
        }

        .twitter .social-icon { background: #1da1f2; }
        .facebook .social-icon { background: #1877f2; }
        .instagram .social-icon { background: #e1306c; }
        .linkedin .social-icon { background: #0077b5; }
        .telegram .social-icon { background: #0088cc; }

        .social-name {
            font-size: 1.3em;
            color: #2c3e50;
            font-weight: 600;
        }

        .social-content {
            padding: 20px;
        }

        .account-info {
            margin-bottom: 15px;
        }

        .account-info label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
        }

        .account-id {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .account-id:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }

        .social-status {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            font-size: 0.9em;
        }

        .status-connected { color: #27ae60; }
        .status-disconnected { color: #e74c3c; }

        .social-actions {
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            justify-content: center;
            font-size: 14px;
        }

        .btn-connect {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }

        .btn-connect:hover {
            background: linear-gradient(135deg, #229954, #1e8449);
            transform: scale(1.05);
        }

        .btn-test {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-test:hover {
            background: linear-gradient(135deg, #2980b9, #2471a3);
            transform: scale(1.05);
        }

        /* Yeni Sosyal Medya Ekleme */
        .add-social-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.5em;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr;
            gap: 15px;
            align-items: end;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }

        .btn-add {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #e67e22, #d35400);
            transform: scale(1.05);
        }

        /* Hızlı Paylaşım Bölümü */
        .quick-share-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .share-form {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            align-items: end;
        }

        .platforms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .platform-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .platform-checkbox:hover {
            background: #e9ecef;
        }

        .platform-checkbox input {
            margin: 0;
        }

        .platform-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .btn-share {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1em;
            height: fit-content;
        }

        .btn-share:hover {
            background: linear-gradient(135deg, #8e44ad, #7d3c98);
            transform: scale(1.05);
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .form-grid, .share-form {
                grid-template-columns: 1fr;
            }
            
            .social-grid {
                grid-template-columns: 1fr;
            }
            
            .social-actions {
                flex-direction: column;
            }
            
            .platforms-grid {
                grid-template-columns: 1fr;
            }
            
            .admin-nav ul {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <h1>📱 RadarPrices Sosyal Medya</h1>
            <p>Otomatik Paylaşım ve Yönetim Sistemi</p>
        </div>

        <!-- Navigation -->
        <nav class="admin-nav">
            <ul>
                <li><a href="dashboard.php">🏠 Dashboard</a></li>
                <li><a href="pending.php">⏳ Onay Bekleyenler</a></li>
                <li><a href="published.php">📰 Yayınlananlar</a></li>
                <li><a href="affiliates.php">🔗 Affiliate</a></li>
                <li><a href="social.php" class="active">📱 Sosyal Medya</a></li>
                <li><a href="logout.php" class="logout-btn">🚪 Çıkış</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="content">
            <div class="page-header">
                <h2>📱 Sosyal Medya Hesapları</h2>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success">
                    <?= $_SESSION['message']; ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <!-- Hızlı Paylaşım Bölümü -->
            <div class="quick-share-section">
                <h3 class="section-title"><i class="fas fa-share-alt"></i> Hızlı Makale Paylaşımı</h3>
                <form method="POST">
                    <div class="share-form">
                        <div>
                            <label style="display: block; margin-bottom: 15px; color: #2c3e50; font-weight: 600;">
                                Paylaşılacak Platformlar:
                            </label>
                            <div class="platforms-grid">
                                <label class="platform-checkbox">
                                    <input type="checkbox" name="social_platforms[]" value="twitter" checked>
                                    <span class="platform-label">
                                        <i class="fab fa-twitter" style="color: #1da1f2;"></i> Twitter
                                    </span>
                                </label>
                                <label class="platform-checkbox">
                                    <input type="checkbox" name="social_platforms[]" value="facebook" checked>
                                    <span class="platform-label">
                                        <i class="fab fa-facebook" style="color: #1877f2;"></i> Facebook
                                    </span>
                                </label>
                                <label class="platform-checkbox">
                                    <input type="checkbox" name="social_platforms[]" value="instagram">
                                    <span class="platform-label">
                                        <i class="fab fa-instagram" style="color: #e1306c;"></i> Instagram
                                    </span>
                                </label>
                                <label class="platform-checkbox">
                                    <input type="checkbox" name="social_platforms[]" value="linkedin">
                                    <span class="platform-label">
                                        <i class="fab fa-linkedin" style="color: #0077b5;"></i> LinkedIn
                                    </span>
                                </label>
                                <label class="platform-checkbox">
                                    <input type="checkbox" name="social_platforms[]" value="telegram">
                                    <span class="platform-label">
                                        <i class="fab fa-telegram" style="color: #0088cc;"></i> Telegram
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <input type="hidden" name="article_id" value="123"> <!-- Gerçek makale ID'si -->
                            <button type="submit" name="share_article" class="btn-share">
                                <i class="fas fa-paper-plane"></i> TÜMÜNE PAYLAŞ
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Yeni Sosyal Medya Ekleme -->
            <div class="add-social-section">
                <h3 class="section-title"><i class="fas fa-plus-circle"></i> Yeni Sosyal Medya Ekle</h3>
                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="social_name">Sosyal Medya Adı</label>
                            <select class="form-control" id="social_name" name="social_name" required>
                                <option value="">Seçiniz</option>
                                <option value="Twitter">Twitter</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Instagram">Instagram</option>
                                <option value="LinkedIn">LinkedIn</option>
                                <option value="Telegram">Telegram</option>
                                <option value="Pinterest">Pinterest</option>
                                <option value="TikTok">TikTok</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="account_id">Hesap ID / Kullanıcı Adı</label>
                            <input type="text" class="form-control" id="account_id" name="account_id" 
                                   placeholder="@kullaniciadi veya ID" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="add_social" class="btn-add">
                                <i class="fas fa-plus"></i> Sosyal Medya Ekle
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sosyal Medya Hesapları Listesi -->
            <div class="social-grid">
                <!-- Twitter -->
                <div class="social-card twitter">
                    <div class="social-header">
                        <div class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </div>
                        <div class="social-name">Twitter</div>
                    </div>
                    <div class="social-content">
                        <div class="account-info">
                            <label>Hesap ID:</label>
                            <input type="text" class="account-id" value="@radarprices" 
                                   placeholder="Hesap ID buraya gir...">
                        </div>
                        <div class="social-status status-connected">
                            <i class="fas fa-check-circle"></i> Bağlı
                        </div>
                    </div>
                    <div class="social-actions">
                        <button class="btn btn-connect" onclick="connectSocial('twitter')">
                            <i class="fas fa-link"></i> Bağlan
                        </button>
                        <button class="btn btn-test" onclick="testSocial('twitter')">
                            <i class="fas fa-test"></i> Test
                        </button>
                    </div>
                </div>

                <!-- Facebook -->
                <div class="social-card facebook">
                    <div class="social-header">
                        <div class="social-icon">
                            <i class="fab fa-facebook"></i>
                        </div>
                        <div class="social-name">Facebook</div>
                    </div>
                    <div class="social-content">
                        <div class="account-info">
                            <label>Sayfa ID:</label>
                            <input type="text" class="account-id" value="radarprices.page" 
                                   placeholder="Sayfa ID buraya gir...">
                        </div>
                        <div class="social-status status-connected">
                            <i class="fas fa-check-circle"></i> Bağlı
                        </div>
                    </div>
                    <div class="social-actions">
                        <button class="btn btn-connect" onclick="connectSocial('facebook')">
                            <i class="fas fa-link"></i> Bağlan
                        </button>
                        <button class="btn btn-test" onclick="testSocial('facebook')">
                            <i class="fas fa-test"></i> Test
                        </button>
                    </div>
                </div>

                <!-- Instagram -->
                <div class="social-card instagram">
                    <div class="social-header">
                        <div class="social-icon">
                            <i class="fab fa-instagram"></i>
                        </div>
                        <div class="social-name">Instagram</div>
                    </div>
                    <div class="social-content">
                        <div class="account-info">
                            <label>Kullanıcı Adı:</label>
                            <input type="text" class="account-id" value="radarprices" 
                                   placeholder="Kullanıcı adı buraya gir...">
                        </div>
                        <div class="social-status status-disconnected">
                            <i class="fas fa-times-circle"></i> Bağlı Değil
                        </div>
                    </div>
                    <div class="social-actions">
                        <button class="btn btn-connect" onclick="connectSocial('instagram')">
                            <i class="fas fa-link"></i> Bağlan
                        </button>
                        <button class="btn btn-test" onclick="testSocial('instagram')">
                            <i class="fas fa-test"></i> Test
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sosyal medya bağlantısı
        function connectSocial(platform) {
            const accountId = document.querySelector(`.${platform} .account-id`).value;
            
            if (!accountId) {
                alert('Lütfen hesap ID girin!');
                return;
            }

            // API ile bağlantı
            fetch('connect_social.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    platform: platform,
                    account_id: accountId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`${platform} başarıyla bağlandı!`);
                    location.reload();
                } else {
                    alert('Bağlantı başarısız: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Hata:', error);
                alert('Bir hata oluştu!');
            });
        }

        // Sosyal medya testi
        function testSocial(platform) {
            // API test isteği
            fetch('test_social.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    platform: platform
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`${platform} bağlantı testi başarılı!`);
                } else {
                    alert('Test başarısız: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Hata:', error);
                alert('Bir hata oluştu!');
            });
        }

        // Sayfa yüklendiğinde animasyon
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.social-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.classList.add('fade-in');
            });
        });
    </script>

    <style>
        .social-card {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</body>
</html>