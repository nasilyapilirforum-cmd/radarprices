<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Veritabanı bağlantısı
require_once '../config/database.php';

// Sosyal medya paylaşım işlemi
if (isset($_POST['share_to_social'])) {
    $article_id = $_POST['article_id'];
    $social_platforms = $_POST['social_platforms'] ?? [];
    
    if (!empty($social_platforms)) {
        // Seçilen sosyal medyalara paylaşım yap
        foreach ($social_platforms as $platform) {
            // API ile paylaşım yap
            shareToSocialMedia($article_id, $platform);
        }
        
        $_SESSION['message'] = "Makale sosyal medyalarda paylaşıldı!";
    } else {
        $_SESSION['message'] = "Lütfen en az bir sosyal medya platformu seçin!";
    }
    
    header('Location: pending.php');
    exit;
}

// Makale onay/red işlemleri
if (isset($_POST['approve_article'])) {
    $article_id = $_POST['article_id'];
    // Makaleyi onayla
    // UPDATE articles SET status = 'published' WHERE id = ?
    $_SESSION['message'] = "Makale onaylandı!";
    header('Location: pending.php');
    exit;
}

if (isset($_POST['reject_article'])) {
    $article_id = $_POST['article_id'];
    $rejection_reason = $_POST['rejection_reason'] ?? '';
    // Makaleyi reddet
    // UPDATE articles SET status = 'rejected', rejection_reason = ? WHERE id = ?
    $_SESSION['message'] = "Makale reddedildi!";
    header('Location: pending.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onay Bekleyen Makaleler - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Önceki stilleri koruyoruz, sadece yeni stiller ekliyoruz */
        
        .social-share-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .social-share-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 90%;
        }

        .modal-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
        }

        .modal-header h3 {
            color: #2c3e50;
            margin: 0;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5em;
            cursor: pointer;
            color: #7f8c8d;
        }

        .platforms-selection {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-bottom: 25px;
        }

        .platform-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .platform-option:hover {
            background: #e9ecef;
        }

        .platform-option.selected {
            border-color: #3498db;
            background: #d6eaf8;
        }

        .platform-icon {
            font-size: 2em;
            margin-bottom: 8px;
        }

        .platform-name {
            font-size: 0.9em;
            font-weight: 600;
            text-align: center;
        }

        .platform-checkbox {
            display: none;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: #95a5a6;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-cancel:hover {
            background: #7f8c8d;
        }

        .btn-share-now {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-share-now:hover {
            background: linear(135deg, #8e44ad, #7d3c98);
        }

        /* Makale kartına sosyal paylaşım butonu ekle */
        .btn-social-share {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85em;
        }

        .btn-social-share:hover {
            background: linear-gradient(135deg, #8e44ad, #7d3c98);
            transform: scale(1.05);
        }

        /* Makale aksiyonlarına sosyal buton ekle */
        .article-actions {
            padding: 20px;
            background: #f8f9fa;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
            border-top: 1px solid #eee;
        }

        @media (max-width: 768px) {
            .article-actions {
                grid-template-columns: 1fr;
            }
            
            .platforms-selection {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <h1>📝 RadarPrices Admin</h1>
            <p>Onay Bekleyen Makaleler Yönetimi</p>
        </div>

        <!-- Navigation -->
        <nav class="admin-nav">
            <ul>
                <li><a href="dashboard.php">🏠 Dashboard</a></li>
                <li><a href="pending.php" class="active">⏳ Onay Bekleyenler</a></li>
                <li><a href="published.php">📰 Yayınlananlar</a></li>
                <li><a href="affiliates.php">🔗 Affiliate</a></li>
                <li><a href="social.php">📱 Sosyal Medya</a></li>
                <li><a href="logout.php" class="logout-btn">🚪 Çıkış</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="content">
            <div class="page-header">
                <h2>⏳ Onay Bekleyen Makaleler</h2>
                <div class="stats-card">
                    📊 Toplam: 5 Makale
                </div>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success">
                    <?= $_SESSION['message']; ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <!-- Articles Grid -->
            <div class="articles-grid">
                <!-- Örnek Makale 1 -->
                <div class="article-card">
                    <div class="article-header">
                        <h3 class="article-title">En İyi 10 Bluetooth Kulaklık 2024</h3>
                        <div class="article-meta">
                            <span>📅 15 Ara 2024</span>
                            <span>👤 Ahmet Yılmaz</span>
                        </div>
                    </div>
                    <div class="article-content">
                        2024'ün en iyi Bluetooth kulaklık incelemeleri ve karşılaştırmaları. 
                        Ses kalitesi, batarya ömrü, özellikler ve fiyat performans karşılaştırması...
                    </div>
                    <div class="article-actions">
                        <button class="btn btn-approve" onclick="approveArticle(1)">✅ Onayla</button>
                        <button class="btn btn-reject" onclick="rejectArticle(1)">❌ Reddet</button>
                        <button class="btn-social-share" onclick="openSocialShare(1, 'En İyi 10 Bluetooth Kulaklık 2024')">
                            📱 Paylaş
                        </button>
                    </div>
                </div>

                <!-- Örnek Makale 2 -->
                <div class="article-card">
                    <div class="article-header">
                        <h3 class="article-title">iPhone 15 vs Samsung S24 Karşılaştırması</h3>
                        <div class="article-meta">
                            <span>📅 14 Ara 2024</span>
                            <span>👤 Mehmet Demir</span>
                        </div>
                    </div>
                    <div class="article-content">
                        İki dev akıllı telefonun detaylı karşılaştırması. Kamera performansı, 
                        işlemci gücü, ekran kalitesi ve batarya testleri...
                    </div>
                    <div class="article-actions">
                        <button class="btn btn-approve" onclick="approveArticle(2)">✅ Onayla</button>
                        <button class="btn btn-reject" onclick="rejectArticle(2)">❌ Reddet</button>
                        <button class="btn-social-share" onclick="openSocialShare(2, 'iPhone 15 vs Samsung S24 Karşılaştırması')">
                            📱 Paylaş
                        </button>
                    </div>
                </div>

                <!-- Örnek Makale 3 -->
                <div class="article-card">
                    <div class="article-header">
                        <h3 class="article-title">Oyun Bilgisayarı Toplama Rehberi 2024</h3>
                        <div class="article-meta">
                            <span>📅 13 Ara 2024</span>
                            <span>👤 Ayşe Kaya</span>
                        </div>
                    </div>
                    <div class="article-content">
                        Bütçene göre en iyi oyun bilgisayarı nasıl toplanır? İşlemci, ekran kartı, 
                        RAM seçimi ve performans optimizasyonları...
                    </div>
                    <div class="article-actions">
                        <button class="btn btn-approve" onclick="approveArticle(3)">✅ Onayla</button>
                        <button class="btn btn-reject" onclick="rejectArticle(3)">❌ Reddet</button>
                        <button class="btn-social-share" onclick="openSocialShare(3, 'Oyun Bilgisayarı Toplama Rehberi 2024')">
                            📱 Paylaş
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sosyal Paylaşım Modal -->
    <div class="social-share-modal" id="socialShareModal">
        <div class="social-share-content">
            <div class="modal-header">
                <h3>📱 Sosyal Medyada Paylaş</h3>
                <button class="close-modal" onclick="closeSocialShare()">&times;</button>
            </div>
            
            <form method="POST" id="socialShareForm">
                <input type="hidden" name="article_id" id="share_article_id">
                
                <div class="platforms-selection">
                    <label class="platform-option">
                        <input type="checkbox" name="social_platforms[]" value="twitter" class="platform-checkbox" checked>
                        <div class="platform-icon" style="color: #1da1f2;">
                            <i class="fab fa-twitter"></i>
                        </div>
                        <div class="platform-name">Twitter</div>
                    </label>
                    
                    <label class="platform-option">
                        <input type="checkbox" name="social_platforms[]" value="facebook" class="platform-checkbox" checked>
                        <div class="platform-icon" style="color: #1877f2;">
                            <i class="fab fa-facebook"></i>
                        </div>
                        <div class="platform-name">Facebook</div>
                    </label>
                    
                    <label class="platform-option">
                        <input type="checkbox" name="social_platforms[]" value="instagram" class="platform-checkbox">
                        <div class="platform-icon" style="color: #e1306c;">
                            <i class="fab fa-instagram"></i>
                        </div>
                        <div class="platform-name">Instagram</div>
                    </label>
                    
                    <label class="platform-option">
                        <input type="checkbox" name="social_platforms[]" value="linkedin" class="platform-checkbox">
                        <div class="platform-icon" style="color: #0077b5;">
                            <i class="fab fa-linkedin"></i>
                        </div>
                        <div class="platform-name">LinkedIn</div>
                    </label>
                    
                    <label class="platform-option">
                        <input type="checkbox" name="social_platforms[]" value="telegram" class="platform-checkbox">
                        <div class="platform-icon" style="color: #0088cc;">
                            <i class="fab fa-telegram"></i>
                        </div>
                        <div class="platform-name">Telegram</div>
                    </label>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeSocialShare()">İptal</button>
                    <button type="submit" name="share_to_social" class="btn-share-now">
                        <i class="fas fa-paper-plane"></i> Paylaş
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sosyal paylaşım modalını aç
        function openSocialShare(articleId, articleTitle) {
            document.getElementById('share_article_id').value = articleId;
            document.getElementById('socialShareModal').style.display = 'flex';
            
            // Checkbox seçimlerini yönet
            const platformOptions = document.querySelectorAll('.platform-option');
            platformOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const checkbox = this.querySelector('.platform-checkbox');
                    checkbox.checked = !checkbox.checked;
                    this.classList.toggle('selected', checkbox.checked);
                });
                
                // Başlangıç durumunu ayarla
                const checkbox = option.querySelector('.platform-checkbox');
                option.classList.toggle('selected', checkbox.checked);
            });
        }

        // Sosyal paylaşım modalını kapat
        function closeSocialShare() {
            document.getElementById('socialShareModal').style.display = 'none';
        }

        // Modal dışına tıklayınca kapat
        document.getElementById('socialShareModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSocialShare();
            }
        });

        // Önceki fonksiyonları koruyoruz
        function approveArticle(articleId) {
            if (confirm('Bu makaleyi onaylamak istediğinizden emin misiniz?')) {
                fetch('approve_article.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: articleId,
                        action: 'approve'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Makale başarıyla onaylandı!');
                        location.reload();
                    } else {
                        alert('Onaylama işlemi başarısız: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Hata:', error);
                    alert('Bir hata oluştu!');
                });
            }
        }

        function rejectArticle(articleId) {
            const reason = prompt('Reddetme sebebini yazın:');
            if (reason !== null) {
                fetch('reject_article.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: articleId,
                        action: 'reject',
                        reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Makale reddedildi!');
                        location.reload();
                    } else {
                        alert('Reddetme işlemi başarısız: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Hata:', error);
                    alert('Bir hata oluştu!');
                });
            }
        }

        // Sayfa yüklendiğinde animasyon
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.article-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.classList.add('fade-in');
            });
        });
    </script>

    <style>
        .article-card {
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