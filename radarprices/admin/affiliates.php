<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Veritabanı bağlantısı
require_once '../config/database.php';

// Affiliate link işlemleri
if ($_POST) {
    if (isset($_POST['add_store'])) {
        $store_name = $_POST['store_name'];
        $affiliate_link = $_POST['affiliate_link'];
        
        // Veritabanına ekleme işlemi
        // INSERT INTO affiliate_stores (store_name, affiliate_link) VALUES (?, ?)
        
        $_SESSION['message'] = "Mağaza başarıyla eklendi!";
        header('Location: affiliates.php');
        exit;
    }
    
    if (isset($_POST['update_store'])) {
        $store_id = $_POST['store_id'];
        $affiliate_link = $_POST['affiliate_link'];
        
        // Veritabanı güncelleme işlemi
        // UPDATE affiliate_stores SET affiliate_link = ? WHERE id = ?
        
        $_SESSION['message'] = "Link başarıyla güncellendi!";
        header('Location: affiliates.php');
        exit;
    }
}

// Mağazaları veritabanından çek
// $stores = SELECT * FROM affiliate_stores ORDER BY store_name
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliate Yönetimi - Admin Panel</title>
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

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 4px solid #3498db;
        }

        .stat-card.green { border-left-color: #27ae60; }
        .stat-card.orange { border-left-color: #f39c12; }
        .stat-card.purple { border-left-color: #9b59b6; }

        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        /* Mağaza Grid */
        .stores-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .store-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-left: 5px solid #3498db;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .store-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .store-header {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .store-name {
            font-size: 1.3em;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .store-content {
            padding: 20px;
        }

        .link-input-group {
            margin-bottom: 15px;
        }

        .link-input-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
        }

        .link-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .link-input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }

        .link-input::placeholder {
            color: #adb5bd;
        }

        .store-actions {
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

        .btn-save {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }

        .btn-save:hover {
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

        .btn-copy {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
        }

        .btn-copy:hover {
            background: linear-gradient(135deg, #8e44ad, #7d3c98);
            transform: scale(1.05);
        }

        /* Yeni Mağaza Ekleme */
        .add-store-section {
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
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .stores-grid {
                grid-template-columns: 1fr;
            }
            
            .store-actions {
                flex-direction: column;
            }
            
            .admin-nav ul {
                flex-direction: column;
            }
            
            .page-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <h1>🔗 RadarPrices Affiliate</h1>
            <p>Mağaza Link Yönetim Sistemi</p>
        </div>

        <!-- Navigation -->
        <nav class="admin-nav">
            <ul>
                <li><a href="dashboard.php">🏠 Dashboard</a></li>
                <li><a href="pending.php">⏳ Onay Bekleyenler</a></li>
                <li><a href="published.php">📰 Yayınlananlar</a></li>
                <li><a href="affiliates.php" class="active">🔗 Affiliate</a></li>
                <li><a href="social.php">📱 Sosyal Medya</a></li>
                <li><a href="logout.php" class="logout-btn">🚪 Çıkış</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="content">
            <div class="page-header">
                <h2>🔗 Affiliate Mağaza Yönetimi</h2>
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Toplam Mağaza</div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-number">8</div>
                        <div class="stat-label">Aktif Link</div>
                    </div>
                    <div class="stat-card orange">
                        <div class="stat-number">4</div>
                        <div class="stat-label">Link Bekleyen</div>
                    </div>
                </div>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="message success">
                    <?= $_SESSION['message']; ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <!-- Yeni Mağaza Ekleme -->
            <div class="add-store-section">
                <h3 class="section-title"><i class="fas fa-plus-circle"></i> Yeni Mağaza Ekle</h3>
                <form method="POST">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="store_name">Mağaza Adı</label>
                            <input type="text" class="form-control" id="store_name" name="store_name" placeholder="Örn: Amazon, Trendyol" required>
                        </div>
                        <div class="form-group">
                            <label for="affiliate_link">Affiliate Link</label>
                            <input type="url" class="form-control" id="affiliate_link" name="affiliate_link" placeholder="https://..." required>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="add_store" class="btn-add">
                                <i class="fas fa-plus"></i> Mağaza Ekle
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Mağaza Listesi -->
            <div class="stores-grid">
                <!-- Mağaza 1 -->
                <div class="store-card">
                    <div class="store-header">
                        <div class="store-name">🛍️ Amazon</div>
                    </div>
                    <div class="store-content">
                        <div class="link-input-group">
                            <label for="amazon_link">Affiliate Link:</label>
                            <input type="url" class="link-input" id="amazon_link" 
                                   value="https://www.amazon.com.tr/?tag=radarprices-21" 
                                   placeholder="Link buraya gir...">
                        </div>
                    </div>
                    <div class="store-actions">
                        <button class="btn btn-save" onclick="updateLink(1, 'amazon_link')">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                        <button class="btn btn-test" onclick="testLink('amazon_link')">
                            <i class="fas fa-external-link-alt"></i> Test
                        </button>
                        <button class="btn btn-copy" onclick="copyLink('amazon_link')">
                            <i class="fas fa-copy"></i> Kopyala
                        </button>
                    </div>
                </div>

                <!-- Mağaza 2 -->
                <div class="store-card">
                    <div class="store-header">
                        <div class="store-name">👕 Trendyol</div>
                    </div>
                    <div class="store-content">
                        <div class="link-input-group">
                            <label for="trendyol_link">Affiliate Link:</label>
                            <input type="url" class="link-input" id="trendyol_link" 
                                   value="https://www.trendyol.com/?affiliate=radarprices" 
                                   placeholder="Link buraya gir...">
                        </div>
                    </div>
                    <div class="store-actions">
                        <button class="btn btn-save" onclick="updateLink(2, 'trendyol_link')">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                        <button class="btn btn-test" onclick="testLink('trendyol_link')">
                            <i class="fas fa-external-link-alt"></i> Test
                        </button>
                        <button class="btn btn-copy" onclick="copyLink('trendyol_link')">
                            <i class="fas fa-copy"></i> Kopyala
                        </button>
                    </div>
                </div>

                <!-- Mağaza 3 -->
                <div class="store-card">
                    <div class="store-header">
                        <div class="store-name">📱 Hepsiburada</div>
                    </div>
                    <div class="store-content">
                        <div class="link-input-group">
                            <label for="hepsiburada_link">Affiliate Link:</label>
                            <input type="url" class="link-input" id="hepsiburada_link" 
                                   placeholder="Link buraya gir...">
                        </div>
                    </div>
                    <div class="store-actions">
                        <button class="btn btn-save" onclick="updateLink(3, 'hepsiburada_link')">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                        <button class="btn btn-test" onclick="testLink('hepsiburada_link')">
                            <i class="fas fa-external-link-alt"></i> Test
                        </button>
                        <button class="btn btn-copy" onclick="copyLink('hepsiburada_link')">
                            <i class="fas fa-copy"></i> Kopyala
                        </button>
                    </div>
                </div>

                <!-- Mağaza 4 -->
                <div class="store-card">
                    <div class="store-header">
                        <div class="store-name">🎧 n11</div>
                    </div>
                    <div class="store-content">
                        <div class="link-input-group">
                            <label for="n11_link">Affiliate Link:</label>
                            <input type="url" class="link-input" id="n11_link" 
                                   placeholder="Link buraya gir...">
                        </div>
                    </div>
                    <div class="store-actions">
                        <button class="btn btn-save" onclick="updateLink(4, 'n11_link')">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                        <button class="btn btn-test" onclick="testLink('n11_link')">
                            <i class="fas fa-external-link-alt"></i> Test
                        </button>
                        <button class="btn btn-copy" onclick="copyLink('n11_link')">
                            <i class="fas fa-copy"></i> Kopyala
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Link güncelleme
        function updateLink(storeId, inputId) {
            const linkInput = document.getElementById(inputId);
            const link = linkInput.value;

            if (!link) {
                alert('Lütfen bir link girin!');
                return;
            }

            // AJAX ile güncelleme
            fetch('update_affiliate.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    store_id: storeId,
                    affiliate_link: link
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Link başarıyla güncellendi!');
                } else {
                    alert('Güncelleme başarısız: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Hata:', error);
                alert('Bir hata oluştu!');
            });
        }

        // Link test etme
        function testLink(inputId) {
            const linkInput = document.getElementById(inputId);
            const link = linkInput.value;

            if (!link) {
                alert('Lütfen önce bir link girin!');
                return;
            }

            window.open(link, '_blank');
        }

        // Link kopyalama
        function copyLink(inputId) {
            const linkInput = document.getElementById(inputId);
            const link = linkInput.value;

            if (!link) {
                alert('Kopyalanacak link bulunamadı!');
                return;
            }

            navigator.clipboard.writeText(link).then(() => {
                alert('Link panoya kopyalandı!');
            }).catch(err => {
                console.error('Kopyalama hatası:', err);
                alert('Kopyalama başarısız!');
            });
        }

        // Sayfa yüklendiğinde animasyon
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.store-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = (index * 0.1) + 's';
                card.classList.add('fade-in');
            });
        });
    </script>

    <style>
        .store-card {
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