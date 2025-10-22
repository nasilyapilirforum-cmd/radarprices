<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Veritabanı bağlantısı
require_once '../config/database.php';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yayınlanan Makaleler - Admin Panel</title>
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
            max-width: 1400px;
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

        .articles-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            padding: 20px;
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr 1fr 1fr;
            gap: 15px;
            font-weight: bold;
        }

        .table-row {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr 1fr 1fr;
            gap: 15px;
            padding: 20px;
            border-bottom: 1px solid #ecf0f1;
            align-items: center;
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }

        .article-title {
            font-weight: 600;
            color: #2c3e50;
            line-height: 1.4;
        }

        .article-meta {
            font-size: 0.85em;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .seo-score {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .score-excellent { color: #27ae60; }
        .score-good { color: #f39c12; }
        .score-poor { color: #e74c3c; }

        .performance-metric {
            text-align: center;
            font-weight: 600;
        }

        .metric-high { color: #27ae60; }
        .metric-medium { color: #f39c12; }
        .metric-low { color: #e74c3c; }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85em;
        }

        .btn-edit {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #2980b9, #2471a3);
            transform: scale(1.05);
        }

        .btn-view {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
        }

        .btn-view:hover {
            background: linear-gradient(135deg, #229954, #1e8449);
            transform: scale(1.05);
        }

        .btn-analytics {
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            color: white;
        }

        .btn-analytics:hover {
            background: linear-gradient(135deg, #8e44ad, #7d3c98);
            transform: scale(1.05);
        }

        .btn-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            min-width: 150px;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .no-articles {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
            font-size: 1.2em;
        }

        .no-articles i {
            font-size: 3em;
            margin-bottom: 20px;
            display: block;
            color: #bdc3c7;
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

        @media (max-width: 1024px) {
            .table-header, .table-row {
                grid-template-columns: 2fr 1fr 1fr 1fr;
            }
            
            .seo-score, .performance-metric {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .table-header, .table-row {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 10px;
            }
            
            .btn-actions {
                flex-direction: column;
            }
            
            .filters {
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
            <h1>📊 RadarPrices Admin</h1>
            <p>Yayınlanan Makaleler ve Performans Analizi</p>
        </div>

        <!-- Navigation -->
        <nav class="admin-nav">
            <ul>
                <li><a href="dashboard.php">🏠 Dashboard</a></li>
                <li><a href="pending.php">⏳ Onay Bekleyenler</a></li>
                <li><a href="published.php" class="active">📰 Yayınlananlar</a></li>
                <li><a href="affiliates.php">🔗 Affiliate</a></li>
                <li><a href="social.php">📱 Sosyal Medya</a></li>
                <li><a href="logout.php" class="logout-btn">🚪 Çıkış</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="content">
            <div class="page-header">
                <h2>📰 Yayınlanan Makaleler</h2>
                <div class="filters">
                    <input type="text" class="search-box" placeholder="🔍 Makale ara...">
                    <select class="filter-select">
                        <option>Tüm Kategoriler</option>
                        <option>Teknoloji</option>
                        <option>Oyun</option>
                        <option>Mobil</option>
                    </select>
                    <select class="filter-select">
                        <option>Tüm SEO Skorları</option>
                        <option>Mükemmel (90-100)</option>
                        <option>İyi (70-89)</option>
                        <option>Orta (50-69)</option>
                    </select>
                </div>
            </div>

            <!-- İstatistik Kartları -->
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-number">24</div>
                    <div class="stat-label">Toplam Makale</div>
                </div>
                <div class="stat-card green">
                    <div class="stat-number">18</div>
                    <div class="stat-label">Aktif Makale</div>
                </div>
                <div class="stat-card orange">
                    <div class="stat-number">92%</div>
                    <div class="stat-label">Ortalama SEO Skoru</div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-number">1.2K</div>
                    <div class="stat-label">Toplam Görüntülenme</div>
                </div>
            </div>

            <!-- Makaleler Tablosu -->
            <div class="articles-table">
                <div class="table-header">
                    <div>Makale Başlığı</div>
                    <div>SEO Skoru</div>
                    <div>Görüntülenme</div>
                    <div>Tıklanma Oranı</div>
                    <div>Yayın Tarihi</div>
                    <div>İşlemler</div>
                </div>

                <!-- Makale 1 -->
                <div class="table-row">
                    <div>
                        <div class="article-title">En İyi 10 Bluetooth Kulaklık 2024</div>
                        <div class="article-meta">👤 Ahmet Yılmaz • 📁 Teknoloji</div>
                    </div>
                    <div class="seo-score score-excellent">
                        <i class="fas fa-chart-line"></i> 94%
                    </div>
                    <div class="performance-metric metric-high">324</div>
                    <div class="performance-metric metric-high">4.2%</div>
                    <div>15 Ara 2024</div>
                    <div class="btn-actions">
                        <a href="#" class="btn btn-view"><i class="fas fa-eye"></i> Görüntüle</a>
                        <a href="edit_article.php?id=1" class="btn btn-edit"><i class="fas fa-edit"></i> Düzenle</a>
                    </div>
                </div>

                <!-- Makale 2 -->
                <div class="table-row">
                    <div>
                        <div class="article-title">iPhone 15 vs Samsung S24 Karşılaştırması</div>
                        <div class="article-meta">👤 Mehmet Demir • 📁 Mobil</div>
                    </div>
                    <div class="seo-score score-excellent">
                        <i class="fas fa-chart-line"></i> 96%
                    </div>
                    <div class="performance-metric metric-high">587</div>
                    <div class="performance-metric metric-high">5.1%</div>
                    <div>14 Ara 2024</div>
                    <div class="btn-actions">
                        <a href="#" class="btn btn-view"><i class="fas fa-eye"></i> Görüntüle</a>
                        <a href="edit_article.php?id=2" class="btn btn-edit"><i class="fas fa-edit"></i> Düzenle</a>
                    </div>
                </div>

                <!-- Makale 3 -->
                <div class="table-row">
                    <div>
                        <div class="article-title">Oyun Bilgisayarı Toplama Rehberi 2024</div>
                        <div class="article-meta">👤 Ayşe Kaya • 📁 Oyun</div>
                    </div>
                    <div class="seo-score score-good">
                        <i class="fas fa-chart-line"></i> 78%
                    </div>
                    <div class="performance-metric metric-medium">156</div>
                    <div class="performance-metric metric-medium">2.8%</div>
                    <div>13 Ara 2024</div>
                    <div class="btn-actions">
                        <a href="#" class="btn btn-view"><i class="fas fa-eye"></i> Görüntüle</a>
                        <a href="edit_article.php?id=3" class="btn btn-edit"><i class="fas fa-edit"></i> Düzenle</a>
                    </div>
                </div>

                <!-- Makale 4 -->
                <div class="table-row">
                    <div>
                        <div class="article-title">En Uygun Fiyatlı Dizüstü Bilgisayarlar</div>
                        <div class="article-meta">👤 Ali Şen • 📁 Teknoloji</div>
                    </div>
                    <div class="seo-score score-poor">
                        <i class="fas fa-chart-line"></i> 65%
                    </div>
                    <div class="performance-metric metric-low">89</div>
                    <div class="performance-metric metric-low">1.5%</div>
                    <div>12 Ara 2024</div>
                    <div class="btn-actions">
                        <a href="#" class="btn btn-view"><i class="fas fa-eye"></i> Görüntüle</a>
                        <a href="edit_article.php?id=4" class="btn btn-edit"><i class="fas fa-edit"></i> Düzenle</a>
                        <a href="#" class="btn btn-analytics"><i class="fas fa-chart-bar"></i> Analiz</a>
                    </div>
                </div>
            </div>

            <!-- Eğer makale yoksa -->
            <!-- <div class="no-articles">
                <i>📝</i>
                <p>Henüz yayınlanan makale bulunmuyor</p>
            </div> -->
        </div>
    </div>

    <script>
        // Arama ve filtreleme fonksiyonları
        document.addEventListener('DOMContentLoaded', function() {
            const searchBox = document.querySelector('.search-box');
            const filterSelects = document.querySelectorAll('.filter-select');
            const tableRows = document.querySelectorAll('.table-row');

            function filterArticles() {
                const searchTerm = searchBox.value.toLowerCase();
                const categoryFilter = filterSelects[0].value;
                const seoFilter = filterSelects[1].value;

                tableRows.forEach(row => {
                    const title = row.querySelector('.article-title').textContent.toLowerCase();
                    const category = row.querySelector('.article-meta').textContent;
                    const seoScore = row.querySelector('.seo-score').textContent;
                    
                    let visible = true;

                    // Arama filtresi
                    if (searchTerm && !title.includes(searchTerm)) {
                        visible = false;
                    }

                    // Kategori filtresi
                    if (categoryFilter !== 'Tüm Kategoriler' && !category.includes(categoryFilter)) {
                        visible = false;
                    }

                    // SEO filtresi
                    if (seoFilter !== 'Tüm SEO Skorları') {
                        const score = parseInt(seoScore);
                        if (seoFilter.includes('Mükemmel') && (score < 90 || score > 100)) visible = false;
                        if (seoFilter.includes('İyi') && (score < 70 || score > 89)) visible = false;
                        if (seoFilter.includes('Orta') && (score < 50 || score > 69)) visible = false;
                    }

                    row.style.display = visible ? 'grid' : 'none';
                });
            }

            searchBox.addEventListener('input', filterArticles);
            filterSelects.forEach(select => {
                select.addEventListener('change', filterArticles);
            });

            // Animasyon
            tableRows.forEach((row, index) => {
                row.style.animationDelay = (index * 0.1) + 's';
                row.classList.add('fade-in');
            });
        });

        // SEO skoruna göre renklendirme
        function updateSEOScores() {
            document.querySelectorAll('.seo-score').forEach(scoreElement => {
                const score = parseInt(scoreElement.textContent);
                scoreElement.className = 'seo-score ';
                
                if (score >= 90) scoreElement.classList.add('score-excellent');
                else if (score >= 70) scoreElement.classList.add('score-good');
                else scoreElement.classList.add('score-poor');
            });
        }
    </script>

    <style>
        .table-row {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s ease forwards;
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