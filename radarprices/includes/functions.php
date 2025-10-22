<?php
// includes/functions.php - TAM KOD (HATA DÜZELTİLMİŞ)

// "nasıl" KELİMESİNE BACKLINK EKLEME
function addBacklinksToContent($content) {
    $backlink_url = "https://www.nasil-yapilir.com.tr";
    
    $pattern = '/\bnasıl\b/i';
    $count = 0;
    $content = preg_replace_callback($pattern, function($matches) use (&$count, $backlink_url) {
        $count++;
        if ($count <= 3) {
            return '<a href="' . $backlink_url . '" target="_blank" style="color: #1a73e8; text-decoration: none; font-weight: 500;">nasıl</a>';
        }
        return $matches[0];
    }, $content);
    
    return $content;
}

// KATEGORİ İŞLEMLERİ
function getCategoryBySlug($slug) {
    global $pdo;
    
    if (!$pdo) {
        $default_categories = [
            'elektronik' => ['id' => 1, 'name' => 'Elektronik', 'slug' => 'elektronik', 'icon' => '📱'],
            'market' => ['id' => 2, 'name' => 'Market', 'slug' => 'market', 'icon' => '🛒'],
            'giyim-ayakkabi' => ['id' => 3, 'name' => 'Giyim & Ayakkabı', 'slug' => 'giyim-ayakkabi', 'icon' => '👕'],
            'anne-bebek' => ['id' => 4, 'name' => 'Anne & Bebek', 'slug' => 'anne-bebek', 'icon' => '👶'],
            'ev-yasam' => ['id' => 5, 'name' => 'Ev & Yaşam', 'slug' => 'ev-yasam', 'icon' => '🏠'],
            'spor-outdoor' => ['id' => 6, 'name' => 'Spor & Outdoor', 'slug' => 'spor-outdoor', 'icon' => '⚽']
        ];
        return $default_categories[$slug] ?? null;
    }
    
    try {
        $sql = "SELECT * FROM categories WHERE slug = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$slug]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$category) {
            $default_categories = [
                'elektronik' => ['id' => 1, 'name' => 'Elektronik', 'slug' => 'elektronik', 'icon' => '📱'],
                'market' => ['id' => 2, 'name' => 'Market', 'slug' => 'market', 'icon' => '🛒'],
                'giyim-ayakkabi' => ['id' => 3, 'name' => 'Giyim & Ayakkabı', 'slug' => 'giyim-ayakkabi', 'icon' => '👕'],
                'anne-bebek' => ['id' => 4, 'name' => 'Anne & Bebek', 'slug' => 'anne-bebek', 'icon' => '👶']
            ];
            return $default_categories[$slug] ?? null;
        }
        
        return $category;
    } catch(PDOException $e) {
        return null;
    }
}

// OTOMATİK TAMAMLAMA İÇİN ÜRÜN ARAMA
function searchProducts($query, $limit = 10) {
    // Örnek ürünler döndür - database bağlantısı olmasa bile çalışsın
    return getSampleProducts($query, $limit);
}

// ÖRNEK ÜRÜNLER (Database yoksa)
function getSampleProducts($query = '', $limit = 10) {
    $all_products = [
        ['id' => 1, 'name' => 'iPhone 15 Pro', 'category_id' => 1, 'brand' => 'Apple'],
        ['id' => 2, 'name' => 'Samsung Galaxy S24', 'category_id' => 1, 'brand' => 'Samsung'],
        ['id' => 3, 'name' => 'Xiaomi 14 Pro', 'category_id' => 1, 'brand' => 'Xiaomi'],
        ['id' => 4, 'name' => 'MacBook Air M3', 'category_id' => 1, 'brand' => 'Apple'],
        ['id' => 5, 'name' => 'Samsung Galaxy Tab S9', 'category_id' => 1, 'brand' => 'Samsung'],
        ['id' => 6, 'name' => 'Sony WH-1000XM5', 'category_id' => 1, 'brand' => 'Sony'],
        ['id' => 7, 'name' => 'Sut 1L', 'category_id' => 2, 'brand' => 'Sutas'],
        ['id' => 8, 'name' => 'Peynir 500g', 'category_id' => 2, 'brand' => 'Pinar'],
        ['id' => 9, 'name' => 'Elma 1kg', 'category_id' => 2, 'brand' => ''],
        ['id' => 10, 'name' => 'Domates 1kg', 'category_id' => 2, 'brand' => ''],
        ['id' => 11, 'name' => 'Tisort Beyaz', 'category_id' => 3, 'brand' => 'Mavi'],
        ['id' => 12, 'name' => 'Spor Ayakkabi', 'category_id' => 3, 'brand' => 'Nike'],
        ['id' => 13, 'name' => 'Bebek Bezi', 'category_id' => 4, 'brand' => 'Sleepy'],
        ['id' => 14, 'name' => 'Bebek Mamasi', 'category_id' => 4, 'brand' => 'Aptamil']
    ];
    
    if (empty($query)) {
        return array_slice($all_products, 0, $limit);
    }
    
    $filtered = array_filter($all_products, function($product) use ($query) {
        return stripos($product['name'], $query) !== false;
    });
    
    return array_slice($filtered, 0, $limit);
}

// YAPAY ZEKA İLE MAKALE OLUŞTUR
function generateAIGuide($topic, $category = 'genel') {
    $current_year = date('Y');
    
    $guides = [
        'camera-phones' => [
            'title' => "En İyi Kamera Telefonları Nasıl Seçilir? [$current_year]",
            'content' => generateCameraPhoneGuide()
        ],
        'gaming-phones' => [
            'title' => "Oyun Telefonları Karşılaştırması ve Seçim Rehberi [$current_year]", 
            'content' => generateGamingPhoneGuide()
        ],
        'battery-life' => [
            'title' => "Pil Ömrü Uzun Telefonlar - Detaylı İnceleme [$current_year]",
            'content' => generateBatteryLifeGuide()
        ],
        'fresh-products' => [
            'title' => "Taze Meyve Sebze Nasıl Seçilir? - Uzman Rehberi [$current_year]",
            'content' => generateFreshProductsGuide()
        ],
        'budget-phones' => [
            'title' => "Bütçe Dostu Telefonlar - En İyi Fiyat/Performans [$current_year]",
            'content' => generateBudgetPhoneGuide()
        ]
    ];
    
    $guide = $guides[$topic] ?? [
        'title' => "$topic - Detaylı İnceleme ve Karşılaştırma [$current_year]",
        'content' => generateDefaultGuide($topic)
    ];
    
    return $guide;
}

// REHBER İÇERİKLERİ
function generateCameraPhoneGuide() {
    $content = "<h2>📸 Kamera Telefonu Seçerken Nelere Dikkat Edilmeli?</h2>";
    $content .= "<p>Kamera performansı <a href='https://www.nasil-yapilir.com.tr/kamera-telefon-secimi' target='_blank'>nasıl</a> değerlendirilir? İşte uzman tavsiyeleri...</p>";
    $content .= "<h3>🎯 Ana Kamera Sensörü</h3>";
    $content .= "<p>MP değeri tek başına yeterli değil. Sensör boyutu ve pixel başına düşen ışık miktarı önemli.</p>";
    $content .= "<h3>🌟 Low-Light Performansı</h3>";
    $content .= "<p>Gece çekimlerinde hangi telefon daha iyi? Night mode algoritmalarını inceliyoruz.</p>";
    $content .= "<div class='sponsored-section'><h4>💎 Sponsor İçerik</h4><p><a href='#' rel='nofollow sponsored' class='sponsored-link'>Kamera telefonları için özel aksesuarlar!</a></p></div>";
    return $content;
}

function generateGamingPhoneGuide() {
    $content = "<h2>🎮 Oyun Telefonu Alırken Dikkat Edilmesi Gerekenler</h2>";
    $content .= "<p>Oyun performansı <a href='https://www.nasil-yapilir.com.tr/oyun-telefon-secimi' target='_blank'>nasıl</a> ölçülür? İşlemci, ekran yenileme hızı ve soğutma sistemleri...</p>";
    $content .= "<h3>⚡ İşlemci ve GPU</h3>";
    $content .= "<p>Snapdragon 8 Gen 3 vs Dimensity 9200+ - Hangisi daha iyi?</p>";
    $content .= "<h3>🖥️ Ekran Özellikleri</h3>";
    $content .= "<p>144Hz vs 120Hz - Gerçekten fark ediyor mu?</p>";
    return $content;
}

function generateBatteryLifeGuide() {
    $content = "<h2>🔋 Pil Ömrü Uzun Telefonlar - Test Sonuçları</h2>";
    $content .= "<p>Pil dayanıklılığı <a href='https://www.nasil-yapilir.com.tr/pil-omru-testi' target='_blank'>nasıl</a> test edilir? Gerçek kullanım senaryoları...</p>";
    $content .= "<h3>📊 Pil Testi Sonuçları</h3>";
    $content .= "<p>5000mAh pil gerçekte ne kadar dayanıyor?</p>";
    $content .= "<h3>⚡ Hızlı Şarj Teknolojileri</h3>";
    $content .= "<p>120W şarj güvenli mi? Pil ömrünü etkiler mi?</p>";
    return $content;
}

function generateFreshProductsGuide() {
    $content = "<h2>🥦 Taze Meyve Sebze Seçme Rehberi</h2>";
    $content .= "<p>Tazelik <a href='https://www.nasil-yapilir.com.tr/taze-urun-secimi' target='_blank'>nasıl</a> anlaşılır? Görsel ipuçları ve püf noktalar...</p>";
    $content .= "<h3>🍎 Meyve Seçimi</h3>";
    $content .= "<p>Elma, armut, portakal - En taze nasıl seçilir?</p>";
    $content .= "<h3>🥬 Sebze Seçimi</h3>";
    $content .= "<p>Marul, domates, salatalık - Tazelik belirtileri</p>";
    return $content;
}

function generateBudgetPhoneGuide() {
    $content = "<h2>💰 Bütçe Dostu Telefonlar - Akıllı Seçim Rehberi</h2>";
    $content .= "<p>Uygun fiyatlı telefon <a href='https://www.nasil-yapilir.com.tr/uygun-telefon-secimi' target='_blank'>nasıl</a> seçilir? En iyi fiyat/performans modelleri...</p>";
    $content .= "<h3>🎯 5.000-10.000 TL Arası En İyiler</h3>";
    $content .= "<p>Bu bütçede hangi marka ve modeller öne çıkıyor?</p>";
    $content .= "<h3>⚡ Performans Odaklı Seçim</h3>";
    $content .= "<p>Günlük kullanım için yeterli performansı sunan modeller</p>";
    return $content;
}

function generateDefaultGuide($topic) {
    $content = "<h2>🔍 $topic - Detaylı İnceleme</h2>";
    $content .= "<p>Bu rehberde, $topic hakkında detaylı bilgiler ve karşılaştırmalar bulacaksınız.</p>";
    $content .= "<p>Doğru seçim yapmak için kriterleri <a href='https://www.nasil-yapilir.com.tr/urun-secimi' target='_blank'>nasıl</a> değerlendireceğinizi öğrenin.</p>";
    return $content;
}

// YAPAY ZEKA REHBER KONULARI
function getAIGuideTopics($category, $subcategory) {
    $all_topics = [
        'elektronik' => [
            ['id' => 'camera-phones', 'icon' => '📸', 'title' => 'Kamera Telefonları Nasıl Seçilir?', 'description' => 'En iyi kamera performansı için detaylı rehber'],
            ['id' => 'gaming-phones', 'icon' => '🎮', 'title' => 'Oyun Telefonları Karşılaştırma', 'description' => 'Oyun performansı en yüksek modeller ve özellikler'],
            ['id' => 'battery-life', 'icon' => '🔋', 'title' => 'Pil Ömrü Uzun Telefonlar', 'description' => 'Günlük kullanımda en uzun pil ömrü sunan modeller'],
            ['id' => 'budget-phones', 'icon' => '💰', 'title' => 'Bütçe Dostu Telefonlar', 'description' => 'En iyi fiyat/performans oranına sahip telefonlar']
        ],
        'market' => [
            ['id' => 'fresh-products', 'icon' => '🥦', 'title' => 'Taze Ürünler Nasıl Seçilir?', 'description' => 'Meyve sebze tazelik kontrolü ve seçim ipuçları'],
            ['id' => 'discount-strategies', 'icon' => '💸', 'title' => 'Market Alışverişinde Tasarruf', 'description' => 'En iyi indirim taktikleri ve tasarruf yöntemleri'],
            ['id' => 'quality-meat', 'icon' => '🥩', 'title' => 'Kaliteli Et Nasıl Anlaşılır?', 'description' => 'Et seçimi, tazelik kontrolü ve saklama yöntemleri']
        ]
    ];
    
    return $all_topics[$category] ?? [
        ['id' => 'buying-guide', 'icon' => '📖', 'title' => 'Satın Alma Rehberi', 'description' => 'Doğru ürün seçimi için kapsamlı rehber'],
        ['id' => 'price-comparison', 'icon' => '💰', 'title' => 'Fiyat Karşılaştırma Tüyoları', 'description' => 'En uygun fiyatı bulma yöntemleri ve ipuçları']
    ];
}

// DİĞER FONKSİYONLAR
function getPendingArticles() {
    global $pdo;
    if (!$pdo) return [];
    try {
        $sql = "SELECT a.*, c.name as category_name FROM articles a LEFT JOIN categories c ON a.category_id = c.id WHERE a.status = 'pending' ORDER BY a.created_at DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}

function getPublishedCount() {
    global $pdo;
    if (!$pdo) return 0;
    try {
        $sql = "SELECT COUNT(*) as count FROM articles WHERE status = 'published'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    } catch(PDOException $e) {
        return 0;
    }
}

function getAffiliateCount() {
    return 3;
}

function getRecentArticles($limit = 5) {
    global $pdo;
    if (!$pdo) return [];
    try {
        $sql = "SELECT * FROM articles ORDER BY created_at DESC LIMIT ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}

function approveArticle($article_id) {
    global $pdo;
    if (!$pdo) return false;
    try {
        $sql = "UPDATE articles SET status = 'published', published_at = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$article_id]);
    } catch(PDOException $e) {
        return false;
    }
}

function createNewArticle($title, $content, $category_id = 1, $author_id = 1) {
    global $pdo;
    if (!$pdo) return false;
    try {
        $sql = "INSERT INTO articles (title, content, category_id, author_id, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$title, $content, $category_id, $author_id]);
    } catch(PDOException $e) {
        return false;
    }
}

function getUserLocation() {
    return ['city' => 'İstanbul', 'district' => 'Kadıköy', 'latitude' => 40.9923307, 'longitude' => 29.1244229];
}

// YAKINDAKİ MARKETLERİ BUL
function getNearbyMarkets($user_location, $radius_km = 5) {
    return [
        [
            'name' => 'Migros',
            'distance' => '0.3 km', 
            'delivery_time' => '15-30 dk',
            'logo' => '🛒'
        ],
        [
            'name' => 'CarrefourSA',
            'distance' => '0.8 km', 
            'delivery_time' => '20-40 dk',
            'logo' => '🛒'
        ],
        [
            'name' => 'A101',
            'distance' => '0.2 km',
            'delivery_time' => '10-25 dk', 
            'logo' => '🛒'
        ],
        [
            'name' => 'Şok',
            'distance' => '0.5 km',
            'delivery_time' => '15-30 dk',
            'logo' => '🛒'
        ]
    ];
}
?>