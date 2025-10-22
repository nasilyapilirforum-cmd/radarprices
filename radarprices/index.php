<?php
include 'includes/config.php';
include 'includes/functions.php';

$page_title = SITE_NAME . " - " . SITE_TAGLINE;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            line-height: 1.6; 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px;
        }
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px 30px;
            border-radius: 20px;
            margin-bottom: 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .header h1 { 
            font-size: 3em; 
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .header p { 
            font-size: 1.3em; 
            opacity: 0.9;
            margin-bottom: 10px;
        }
        .search-box { 
            display: flex; 
            margin: 30px auto;
            max-width: 700px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            position: relative;
        }
        .search-box input { 
            flex: 1; 
            padding: 18px 25px;
            border: none;
            font-size: 16px;
            outline: none;
        }
        .search-box button { 
            padding: 18px 35px;
            background: #ff6b00;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s;
            white-space: nowrap;
        }
        .search-box button:hover { 
            background: #e55a00;
        }
        #searchSuggestions {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 1000;
            max-height: 400px;
            overflow-y: auto;
        }
        .suggestion-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: background 0.2s;
        }
        .suggestion-item:hover {
            background: #f8f9ff;
        }
        .categories-grid { 
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin: 50px 0;
        }
        .category-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            border-color: #667eea;
        }
        .category-icon { 
            font-size: 4em;
            margin-bottom: 20px;
        }
        .category-card h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.5em;
        }
        .category-card > p {
            color: #666;
            margin-bottom: 20px;
            font-size: 1.1em;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        .subcategories {
            margin-top: 20px;
            text-align: left;
            border-top: 1px solid #f0f0f0;
            padding-top: 20px;
        }
        .subcategory-link {
            display: flex;
            align-items: center;
            padding: 12px 0;
            color: #555;
            text-decoration: none;
            border-bottom: 1px solid #f8f8f8;
            transition: all 0.3s;
        }
        .subcategory-link:hover {
            color: #667eea;
            background: #f8f9ff;
            padding-left: 10px;
            border-radius: 8px;
        }
        .subcategory-link:last-child {
            border-bottom: none;
        }
        .admin-link {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #333;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            z-index: 1000;
        }
        .admin-link:hover {
            background: #555;
        }
        .ai-badge {
            background: #9c27b0;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7em;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <a href="admin/dashboard.php" class="admin-link">⚙️ Admin</a>

    <div class="container">
        <div class="header">
            <h1>🛒 <?php echo SITE_NAME; ?></h1>
            <p><?php echo SITE_TAGLINE; ?></p>
            <p>🚀 <?php echo CURRENT_YEAR; ?> Güncel • 2.000.000+ Ürün • 50+ Mağaza</p>
            
            <div class="search-box">
                <div style="position: relative; width: 100%;">
                    <input type="text" id="mainSearch" placeholder="🔍 Yapay Zeka ile ara: 'bütçeye uygun telefon', 'en iyi kamera', 'ucuz laptop'..." 
                           style="width: 100%; padding: 18px 25px; border: none; border-radius: 15px; font-size: 16px; outline: none;"
                           onkeyup="showAISuggestions(this.value)">
                    <div id="searchSuggestions"></div>
                </div>
                <button onclick="performAISearch()">🤖 Akıllı Ara</button>
            </div>
        </div>

        <h2 style="text-align: center; margin: 30px 0; color: #333; font-size: 2em;">🎯 Kategoriler</h2>
        
        <div class="categories-grid">
            <!-- ELEKTRONİK -->
            <div class="category-card">
                <div class="category-icon">📱</div>
                <h3>Elektronik</h3>
                <p>Telefon, Laptop, TV ve daha fazlası</p>
                <a href="category.php?cat=elektronik" class="btn">Karşılaştır</a>
                
                <div class="subcategories">
                    <a href="subcategory.php?cat=elektronik&sub=telefon" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">📱</span>
                        Akıllı Telefonlar
                    </a>
                    <a href="subcategory.php?cat=elektronik&sub=laptop" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">💻</span>
                        Laptop & Bilgisayar
                    </a>
                    <a href="subcategory.php?cat=elektronik&sub=tablet" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">📟</span>
                        Tabletler
                    </a>
                    <a href="subcategory.php?cat=elektronik&sub=televizyon" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">📺</span>
                        Televizyonlar
                    </a>
                </div>
            </div>

            <!-- MARKET -->
            <div class="category-card">
                <div class="category-icon">🛒</div>
                <h3>Market</h3>
                <p>Gıda, İçecek, Temizlik ürünleri</p>
                <a href="category.php?cat=market" class="btn">Karşılaştır</a>
                
                <div class="subcategories">
                    <a href="subcategory.php?cat=market&sub=sut-kahvalti" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">🥛</span>
                        Süt & Kahvaltılık
                    </a>
                    <a href="subcategory.php?cat=market&sub=meyve-sebze" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">🍎</span>
                        Meyve & Sebze
                    </a>
                    <a href="subcategory.php?cat=market&sub=et" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">🍗</span>
                        Et & Tavuk
                    </a>
                    <a href="subcategory.php?cat=market&sub=icecek" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">🧃</span>
                        İçecekler
                    </a>
                </div>
            </div>

            <!-- GİYİM -->
            <div class="category-card">
                <div class="category-icon">👕</div>
                <h3>Giyim & Ayakkabı</h3>
                <p>Moda ve günlük giyim</p>
                <a href="category.php?cat=giyim-ayakkabi" class="btn">Karşılaştır</a>
                
                <div class="subcategories">
                    <a href="subcategory.php?cat=giyim-ayakkabi&sub=erkek" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">👔</span>
                        Erkek Giyim
                    </a>
                    <a href="subcategory.php?cat=giyim-ayakkabi&sub=kadin" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">👗</span>
                        Kadın Giyim
                    </a>
                    <a href="subcategory.php?cat=giyim-ayakkabi&sub=ayakkabi" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">👟</span>
                        Spor Ayakkabı
                    </a>
                    <a href="subcategory.php?cat=giyim-ayakkabi&sub=spor" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">🎽</span>
                        Spor Giyim
                    </a>
                </div>
            </div>

            <!-- ANNE BEBEK -->
            <div class="category-card">
                <div class="category-icon">👶</div>
                <h3>Anne & Bebek</h3>
                <p>Bebek ürünleri ve ihtiyaçlar</p>
                <a href="category.php?cat=anne-bebek" class="btn">Karşılaştır</a>
                
                <div class="subcategories">
                    <a href="subcategory.php?cat=anne-bebek&sub=mama" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">🍼</span>
                        Bebek Beslenme
                    </a>
                    <a href="subcategory.php?cat=anne-bebek&sub=bakim" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">🧴</span>
                        Bebek Bakım
                    </a>
                    <a href="subcategory.php?cat=anne-bebek&sub=giyim" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">👕</span>
                        Bebek Giyim
                    </a>
                    <a href="subcategory.php?cat=anne-bebek&sub=oyuncak" class="subcategory-link">
                        <span style="font-size: 1.2em; margin-right: 10px;">🧸</span>
                        Oyuncaklar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
    // YAPAY ZEKA OTOMATİK TAMAMLAMA
    function showAISuggestions(query) {
        if (query.length < 2) {
            document.getElementById('searchSuggestions').style.display = 'none';
            return;
        }
        
        const container = document.getElementById('searchSuggestions');
        
        // Yapay zeka önerileri
        const aiSuggestions = generateAISuggestions(query);
        
        if (aiSuggestions.length > 0) {
            container.innerHTML = aiSuggestions.map(suggestion => `
                <div class="suggestion-item" onclick="selectAISuggestion('${suggestion.type}', '${suggestion.query.replace(/'/g, "\\'")}')">
                    <span style="font-size: 1.3em; color: #667eea;">${suggestion.icon}</span>
                    <div style="flex: 1;">
                        <div style="font-weight: 500; color: #333;">${suggestion.title}</div>
                        <div style="font-size: 0.85em; color: #666; margin-top: 4px;">${suggestion.description}</div>
                    </div>
                    <span style="font-size: 0.8em; color: #9c27b0; background: #f3e5f5; padding: 4px 8px; border-radius: 12px;">AI</span>
                </div>
            `).join('');
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    }

    // YAPAY ZEKA ÖNERİLERİ OLUŞTUR
    function generateAISuggestions(query) {
        const suggestions = [];
        const lowerQuery = query.toLowerCase();
        
        // Bütçe önerileri
        if (lowerQuery.includes('bütçe') || lowerQuery.includes('ucuz') || lowerQuery.includes('fiyat')) {
            suggestions.push({
                type: 'budget',
                icon: '💰',
                title: 'Bütçe Dostu Ürünler',
                description: 'En iyi fiyat/performans ürünlerini bul',
                query: 'bütçe dostu ' + query
            });
        }
        
        // Karşılaştırma önerileri
        if (lowerQuery.includes('en iyi') || lowerQuery.includes('karşılaştır') || lowerQuery.includes('vs')) {
            suggestions.push({
                type: 'compare',
                icon: '⚖️',
                title: 'Ürün Karşılaştırması',
                description: 'Benzer ürünleri detaylı karşılaştır',
                query: 'karşılaştırma ' + query
            });
        }
        
        // Teknik özellik önerileri
        if (lowerQuery.includes('özellik') || lowerQuery.includes('teknik') || lowerQuery.includes('detay')) {
            suggestions.push({
                type: 'specs',
                icon: '🔧',
                title: 'Teknik Özellik Analizi',
                description: 'Detaylı teknik özellikleri incele',
                query: 'teknik özellik ' + query
            });
        }
        
        // Yeni ürün önerileri
        if (lowerQuery.includes('yeni') || lowerQuery.includes('2024') || lowerQuery.includes('2025')) {
            suggestions.push({
                type: 'new',
                icon: '🆕',
                title: 'Yeni Çıkan Ürünler',
                description: 'En yeni modelleri keşfet',
                query: 'yeni ' + query
            });
        }
        
        // Genel arama önerileri
        if (suggestions.length === 0) {
            suggestions.push({
                type: 'search',
                icon: '🔍',
                title: `"${query}" için arama yap`,
                description: 'Binlerce ürün içinde ara',
                query: query
            });
            
            // Kategori önerileri
            if (lowerQuery.includes('telefon') || lowerQuery.includes('iphone') || lowerQuery.includes('samsung')) {
                suggestions.push({
                    type: 'category',
                    icon: '📱',
                    title: 'Akıllı Telefonlar',
                    description: 'Telefon karşılaştırma ve fiyatlar',
                    query: 'telefon'
                });
            }
            
            if (lowerQuery.includes('laptop') || lowerQuery.includes('bilgisayar') || lowerQuery.includes('macbook')) {
                suggestions.push({
                    type: 'category',
                    icon: '💻',
                    title: 'Laptop & Bilgisayar',
                    description: 'Bilgisayar karşılaştırma ve fiyatlar',
                    query: 'laptop'
                });
            }

            if (lowerQuery.includes('market') || lowerQuery.includes('gıda') || lowerQuery.includes('süt')) {
                suggestions.push({
                    type: 'category',
                    icon: '🛒',
                    title: 'Market Ürünleri',
                    description: 'Gıda fiyat karşılaştırması',
                    query: 'market'
                });
            }
        }
        
        return suggestions.slice(0, 5);
    }

    function selectAISuggestion(type, query) {
        document.getElementById('mainSearch').value = query;
        document.getElementById('searchSuggestions').style.display = 'none';
        performAISearch();
    }

    function performAISearch() {
        const query = document.getElementById('mainSearch').value.trim();
        if (query) {
            // Yapay zeka arama sonuçları sayfasına yönlendir
            window.location.href = 'ai-search-results.php?q=' + encodeURIComponent(query) + '&ai=true';
        } else {
            alert('Lütfen arama yapmak için bir şeyler yazın!');
        }
    }

    // Tıklama dışındaki alanlara tıklayınca suggestions'ı kapat
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-box')) {
            document.getElementById('searchSuggestions').style.display = 'none';
        }
    });

    // Enter tuşu ile arama
    document.getElementById('mainSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performAISearch();
        }
    });

    // Input'a odaklan
    document.getElementById('mainSearch').focus();
    </script>
</body>
</html>