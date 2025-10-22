<?php
// compare.php - AKILLI ÜRÜN KARŞILAŞTIRMA + ARAMA ENTEGRASYONU
require_once 'header.php';

// ARAMA FONKSİYONU
function smartSearch($query, $limit = 10) {
    $all_products = [
        1 => ['id' => 1, 'name' => 'iPhone 15 Pro 256GB', 'brand' => 'Apple', 'price' => 54999, 'rating' => 4.8],
        2 => ['id' => 2, 'name' => 'Samsung Galaxy S24 Ultra', 'brand' => 'Samsung', 'price' => 47999, 'rating' => 4.7],
        3 => ['id' => 3, 'name' => 'Xiaomi 14 Pro 512GB', 'brand' => 'Xiaomi', 'price' => 32999, 'rating' => 4.5],
        4 => ['id' => 4, 'name' => 'Google Pixel 8 Pro', 'brand' => 'Google', 'price' => 41999, 'rating' => 4.6],
        5 => ['id' => 5, 'name' => 'iPhone 14 Pro', 'brand' => 'Apple', 'price' => 45999, 'rating' => 4.7],
        6 => ['id' => 6, 'name' => 'Samsung Galaxy S23', 'brand' => 'Samsung', 'price' => 34999, 'rating' => 4.4],
        7 => ['id' => 7, 'name' => 'Xiaomi 13T Pro', 'brand' => 'Xiaomi', 'price' => 27999, 'rating' => 4.3],
        8 => ['id' => 8, 'name' => 'Google Pixel 7', 'brand' => 'Google', 'price' => 32999, 'rating' => 4.5]
    ];
    
    $results = [];
    $query = strtolower(trim($query));
    
    foreach ($all_products as $product) {
        $product_name = strtolower($product['name']);
        $brand_name = strtolower($product['brand']);
        
        if (strpos($product_name, $query) !== false || strpos($brand_name, $query) !== false) {
            $results[] = $product;
        }
        
        if (count($results) >= $limit) break;
    }
    
    return $results;
}

// ARAMA YAPILDIYSA
$search_results = [];
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = trim($_GET['search']);
    $search_results = smartSearch($search_query, 6);
}

// LocalStorage'dan karşılaştırma listesini al
$compare_items = [];
if (isset($_GET['items'])) {
    $compare_items = explode(',', $_GET['items']);
}

// URL'den items al
if (isset($_GET['items'])) {
    $compare_items = array_map('intval', explode(',', $_GET['items']));
    $compare_items = array_filter($compare_items);
}

// Ürün veritabanı
$products = [
    1 => [
        'id' => 1,
        'name' => 'iPhone 15 Pro 256GB',
        'brand' => 'Apple',
        'category_name' => 'Akıllı Telefonlar',
        'price' => 54999,
        'original_price' => 59999,
        'rating' => 4.8,
        'review_count' => 1247,
        'description' => 'Apple A17 Pro çip, 6.7 inç Super Retina XDR ekran, 48MP ana kamera ile profesyonel fotoğraf ve video çekimleri.',
        'specifications' => [
            'İşlemci' => 'Apple A17 Pro',
            'RAM' => '8 GB',
            'Depolama' => '256 GB',
            'Ekran' => '6.7 inç Super Retina XDR',
            'Kamera' => '48 MP + 12 MP + 12 MP',
            'Pil' => '4422 mAh',
            'İşletim Sistemi' => 'iOS 17',
            '5G Desteği' => 'Var',
            'Suya Dayanıklılık' => 'IP68',
            'Ağırlık' => '221g',
            'Şarj' => 'USB-C, 20W'
        ]
    ],
    2 => [
        'id' => 2,
        'name' => 'Samsung Galaxy S24 Ultra 512GB',
        'brand' => 'Samsung', 
        'category_name' => 'Akıllı Telefonlar',
        'price' => 47999,
        'original_price' => 52999,
        'rating' => 4.7,
        'review_count' => 892,
        'description' => 'Snapdragon 8 Gen 3 işlemci, 200MP kamera, S Pen desteği ve AI özellikleri ile Samsung\'un amiral gemisi.',
        'specifications' => [
            'İşlemci' => 'Snapdragon 8 Gen 3',
            'RAM' => '12 GB', 
            'Depolama' => '512 GB',
            'Ekran' => '6.8 inç Dynamic AMOLED 2X',
            'Kamera' => '200 MP + 50 MP + 12 MP + 10 MP',
            'Pil' => '5000 mAh',
            'İşletim Sistemi' => 'Android 14',
            '5G Desteği' => 'Var',
            'Suya Dayanıklılık' => 'IP68',
            'Ağırlık' => '232g',
            'Şarj' => 'USB-C, 45W'
        ]
    ],
    3 => [
        'id' => 3, 
        'name' => 'Xiaomi 14 Pro 512GB',
        'brand' => 'Xiaomi',
        'category_name' => 'Akıllı Telefonlar',
        'price' => 32999,
        'original_price' => 35999,
        'rating' => 4.5,
        'review_count' => 456,
        'description' => 'Snapdragon 8 Gen 3 işlemci, Leica kamera sistemi ve hızlı şarj ile Xiaomi\'nin premium modeli.',
        'specifications' => [
            'İşlemci' => 'Snapdragon 8 Gen 3',
            'RAM' => '12 GB',
            'Depolama' => '512 GB', 
            'Ekran' => '6.73 inç LTPO AMOLED',
            'Kamera' => '50 MP + 50 MP + 50 MP',
            'Pil' => '4880 mAh',
            'İşletim Sistemi' => 'Android 14',
            '5G Desteği' => 'Var',
            'Suya Dayanıklılık' => 'IP68',
            'Ağırlık' => '225g',
            'Şarj' => 'USB-C, 120W'
        ]
    ],
    4 => [
        'id' => 4,
        'name' => 'Google Pixel 8 Pro',
        'brand' => 'Google',
        'category_name' => 'Akıllı Telefonlar',
        'price' => 41999,
        'original_price' => 45999,
        'rating' => 4.6,
        'review_count' => 678,
        'description' => 'Google Tensor G3 işlemci, gelişmiş AI özellikleri ve mükemmel kamera sistemi.',
        'specifications' => [
            'İşlemci' => 'Google Tensor G3',
            'RAM' => '12 GB',
            'Depolama' => '256 GB',
            'Ekran' => '6.7 inç OLED',
            'Kamera' => '50 MP + 48 MP + 48 MP',
            'Pil' => '5050 mAh',
            'İşletim Sistemi' => 'Android 14',
            '5G Desteği' => 'Var',
            'Suya Dayanıklılık' => 'IP68',
            'Ağırlık' => '213g',
            'Şarj' => 'USB-C, 30W'
        ]
    ]
];

// Karşılaştırılacak ürünleri filtrele
$compare_products = [];
foreach ($compare_items as $item_id) {
    if (isset($products[$item_id])) {
        $compare_products[] = $products[$item_id];
    }
}

// Eğer karşılaştırılacak ürün yoksa
if (empty($compare_products)) {
    echo "<div class='container text-center py-5'>
            <div style='font-size: 4rem; margin-bottom: 20px;'>⚖️</div>
            <h2>Karşılaştırma Listesi Boş</h2>
            <p>Ürünleri karşılaştırmak için arama yapın veya product sayfalarından ürün ekleyin.</p>
            <div style='margin-top: 30px;'>
                <a href='product.php?id=1' class='btn-primary' style='display: inline-block; padding: 12px 25px; background: #2563eb; color: white; text-decoration: none; border-radius: 8px; margin: 0 10px;'>📱 iPhone Ekle</a>
                <a href='product.php?id=2' class='btn-primary' style='display: inline-block; padding: 12px 25px; background: #2563eb; color: white; text-decoration: none; border-radius: 8px; margin: 0 10px;'>📱 Samsung Ekle</a>
                <a href='product.php?id=3' class='btn-primary' style='display: inline-block; padding: 12px 25px; background: #2563eb; color: white; text-decoration: none; border-radius: 8px; margin: 0 10px;'>📱 Xiaomi Ekle</a>
            </div>
          </div>";
    require_once 'footer.php';
    exit;
}

// Tüm teknik özellikleri topla
$all_specs = [];
foreach ($compare_products as $product) {
    foreach ($product['specifications'] as $spec => $value) {
        if (!in_array($spec, $all_specs)) {
            $all_specs[] = $spec;
        }
    }
}

// AI KARŞILAŞTIRMA ANALİZİ
function generateComparisonAnalysis($products) {
    $analysis = "";
    
    // Fiyat analizi
    $prices = array_column($products, 'price');
    $min_price = min($prices);
    $max_price = max($prices);
    
    $analysis .= "## 💰 Fiyat Karşılaştırması\n\n";
    $analysis .= "**Fiyat aralığı:** " . number_format($min_price, 0, ',', '.') . " TL - " . number_format($max_price, 0, ',', '.') . " TL\n\n";
    
    // En iyi değer analizi
    $best_value = null;
    $best_value_score = 0;
    
    foreach ($products as $product) {
        $value_score = ($product['rating'] * 1000) / $product['price'];
        if ($value_score > $best_value_score) {
            $best_value_score = $value_score;
            $best_value = $product;
        }
    }
    
    $analysis .= "**En iyi fiyat/performans:** " . $best_value['brand'] . " " . $best_value['name'] . " (" . number_format($best_value['price'], 0, ',', '.') . " TL)\n\n";
    
    // Özellik karşılaştırması
    $analysis .= "## 🔧 Teknik Özellik Karşılaştırması\n\n";
    
    // İşlemci
    $analysis .= "**İşlemci Performansı:**\n";
    foreach ($products as $product) {
        $analysis .= "- " . $product['brand'] . ": " . $product['specifications']['İşlemci'] . "\n";
    }
    $analysis .= "\n";
    
    // Kamera
    $analysis .= "**Kamera Sistemleri:**\n";
    foreach ($products as $product) {
        $analysis .= "- " . $product['brand'] . ": " . $product['specifications']['Kamera'] . "\n";
    }
    $analysis .= "\n";
    
    // Pil ömrü
    $analysis .= "**Pil Kapasiteleri:**\n";
    foreach ($products as $product) {
        $analysis .= "- " . $product['brand'] . ": " . $product['specifications']['Pil'] . "\n";
    }
    $analysis .= "\n";
    
    // Kullanıcı deneyimi
    $analysis .= "## ⭐ Kullanıcı Deneyimi\n\n";
    foreach ($products as $product) {
        $analysis .= "- " . $product['brand'] . ": " . $product['rating'] . "/5 (" . $product['review_count'] . " yorum)\n";
    }
    $analysis .= "\n";
    
    // Sonuç ve öneri
    $analysis .= "## 🏆 Sonuç ve Öneriler\n\n";
    
    if (count($products) == 2) {
        $product1 = $products[0];
        $product2 = $products[1];
        
        if ($product1['price'] < $product2['price'] && $product1['rating'] >= $product2['rating']) {
            $analysis .= "**" . $product1['brand'] . "** daha uygun fiyatlı ve benzer performans sunuyor.\n\n";
        } elseif ($product2['price'] < $product1['price'] && $product2['rating'] >= $product1['rating']) {
            $analysis .= "**" . $product2['brand'] . "** daha uygun fiyatlı ve benzer performans sunuyor.\n\n";
        } else {
            $analysis .= "**" . $best_value['brand'] . "** en iyi fiyat/performans oranına sahip.\n\n";
        }
    } else {
        $analysis .= "**" . $best_value['brand'] . "** en iyi fiyat/performans oranına sahip.\n\n";
    }
    
    $analysis .= "### 📋 Satın Alma Önerileri:\n";
    $analysis .= "- **Bütçe odaklı kullanıcılar için:** " . $best_value['brand'] . "\n";
    $analysis .= "- **En yüksek performans isteyenler için:** " . $products[array_search(max($prices), $prices)]['brand'] . "\n";
    $analysis .= "- **Kamera meraklıları için:** Samsung (200MP ana kamera)\n";
    $analysis .= "- **Pil ömrü önceliği için:** Samsung (5000 mAh)\n";
    
    return $analysis;
}

$ai_analysis = generateComparisonAnalysis($compare_products);
?>

<div class="container">
    <!-- 🔍 AKILLI ARAMA ÇUBUĞU -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 15px; margin: 20px 0;">
        <h2 style="margin: 0 0 15px 0; text-align: center;">🔍 Karşılaştırma İçin Ürün Ara</h2>
        
        <form method="GET" action="compare.php" style="display: flex; gap: 10px; max-width: 600px; margin: 0 auto;">
            <input type="text" 
                   name="search" 
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                   placeholder="Ürün adı veya marka yazın (iPhone, Samsung, Xiaomi...)" 
                   style="flex: 1; padding: 12px 15px; border: none; border-radius: 8px; font-size: 1rem;">
            <button type="submit" 
                    style="background: #f59e0b; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: bold;">
                🔍 Ara
            </button>
        </form>
        
        <?php if (isset($_GET['search'])): ?>
        <div style="text-align: center; margin-top: 15px;">
            <small>
                "<?= htmlspecialchars($_GET['search']) ?>" için 
                <strong><?= count($search_results) ?></strong> sonuç bulundu
            </small>
        </div>
        <?php endif; ?>
    </div>

    <!-- ARAMA SONUÇLARI -->
    <?php if (!empty($search_results)): ?>
    <div style="background: white; border-radius: 15px; padding: 20px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 20px 0; color: #374151;">🔍 Arama Sonuçları</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 15px;">
            <?php foreach ($search_results as $product): ?>
            <div style="background: #f8fafc; padding: 15px; border-radius: 10px; border: 2px solid #e2e8f0;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="width: 50px; height: 50px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        📱
                    </div>
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 4px 0; font-size: 1rem;"><?= htmlspecialchars($product['name']) ?></h4>
                        <p style="margin: 0; color: #64748b; font-size: 0.85rem;"><?= htmlspecialchars($product['brand']) ?></p>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div style="color: #dc2626; font-size: 1.1rem; font-weight: bold;">
                        ₺ <?= number_format($product['price'], 0, ',', '.') ?>
                    </div>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <span style="color: #f59e0b;">⭐</span>
                        <span style="color: #64748b; font-size: 0.9rem;"><?= $product['rating'] ?></span>
                    </div>
                </div>
                
                <button onclick="quickAddToCompare(<?= $product['id'] ?>)" 
                        style="width: 100%; background: #10b981; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; font-weight: 500; font-size: 0.9rem;">
                    ➕ Karşılaştırmaya Ekle
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- 🎯 SAYFA BAŞLIĞI -->
    <div style="text-align: center; margin: 30px 0;">
        <h1 style="margin: 0 0 10px 0; font-size: 2.5rem;">⚖️ Ürün Karşılaştırma</h1>
        <p style="color: #64748b; font-size: 1.1rem;">
            <?= count($compare_products) ?> ürün detaylı karşılaştırma
        </p>
    </div>

    <!-- 🎯 KARŞILAŞTIRMA KONTROLLERİ -->
    <div style="background: #f8fafc; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <div>
                <strong>Karşılaştırılan Ürünler:</strong>
                <?php foreach ($compare_products as $product): ?>
                <span style="background: white; padding: 8px 15px; border-radius: 20px; margin: 0 5px; font-size: 0.9rem; border: 2px solid #3b82f6;">
                    <?= $product['brand'] . ' ' . $product['name'] ?>
                </span>
                <?php endforeach; ?>
            </div>
            <div>
                <button onclick="clearComparison()" style="background: #dc2626; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer; font-size: 0.9rem;">
                    🗑️ Listeyi Temizle
                </button>
            </div>
        </div>
    </div>

    <!-- 📊 HIZLI KARŞILAŞTIRMA ÖZETİ -->
    <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px; text-align: center;">📊 Hızlı Karşılaştırma Özeti</h2>
        
        <div style="display: grid; grid-template-columns: repeat(<?= count($compare_products) + 1 ?>, 1fr); gap: 10px; margin-bottom: 30px;">
            <!-- Başlık Sütunu -->
            <div style="font-weight: bold; padding: 15px; background: #f8fafc; border-radius: 8px; display: flex; align-items: center;">Özellik</div>
            
            <!-- Ürün Sütunları -->
            <?php foreach ($compare_products as $product): ?>
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 8px;">
                <div style="font-size: 2rem; margin-bottom: 10px;">📱</div>
                <strong style="color: #1e40af;"><?= $product['brand'] ?></strong>
                <div style="font-size: 0.8rem; color: #64748b; margin-top: 5px;"><?= $product['name'] ?></div>
            </div>
            <?php endforeach; ?>
            
            <!-- Fiyat Satırı -->
            <div style="font-weight: bold; padding: 15px; background: #f0fdf4; border-radius: 8px; display: flex; align-items: center;">💰 Fiyat</div>
            <?php foreach ($compare_products as $product): ?>
            <div style="text-align: center; padding: 15px; background: #f0fdf4; border-radius: 8px;">
                <div style="color: #dc2626; font-size: 1.2rem; font-weight: bold;">
                    ₺ <?= number_format($product['price'], 0, ',', '.') ?>
                </div>
                <?php if ($product['original_price'] > $product['price']): ?>
                <div style="color: #16a34a; font-size: 0.8rem; font-weight: bold;">
                    🔥 <?= number_format((($product['original_price'] - $product['price']) / $product['original_price']) * 100, 0) ?>% indirim
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            
            <!-- Puan Satırı -->
            <div style="font-weight: bold; padding: 15px; background: #fffbeb; border-radius: 8px; display: flex; align-items: center;">⭐ Puan</div>
            <?php foreach ($compare_products as $product): ?>
            <div style="text-align: center; padding: 15px; background: #fffbeb; border-radius: 8px;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                    <span style="color: #f59e0b;">⭐</span>
                    <strong><?= $product['rating'] ?></strong>
                </div>
                <div style="color: #64748b; font-size: 0.7rem;">
                    (<?= $product['review_count'] ?> yorum)
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 🤖 AI KARŞILAŞTIRMA ANALİZİ -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 30px; margin-bottom: 30px;">
        <h2 style="margin-bottom: 20px; text-align: center;">🤖 AI Karşılaştırma Analizi</h2>
        
        <div style="background: rgba(255,255,255,0.1); padding: 25px; border-radius: 10px; backdrop-filter: blur(10px);">
            <div style="white-space: pre-line; line-height: 1.6; font-size: 0.95rem;">
                <?= $ai_analysis ?>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 25px;">
            <small style="opacity: 0.8;">⚡ Bu analiz AI tarafından gerçek zamanlı olarak oluşturulmuştur</small>
        </div>
    </div>

    <!-- 🔧 DETAYLI TEKNİK KARŞILAŞTIRMA -->
    <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px; text-align: center;">🔧 Detaylı Teknik Karşılaştırma</h2>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                <thead>
                    <tr>
                        <th style="padding: 15px; background: #1e40af; color: white; text-align: left; border-bottom: 2px solid #e2e8f0;">Teknik Özellik</th>
                        <?php foreach ($compare_products as $product): ?>
                        <th style="padding: 15px; background: #1e40af; color: white; text-align: center; border-bottom: 2px solid #e2e8f0;">
                            <?= $product['brand'] ?>
                        </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_specs as $index => $spec): ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 15px; background: <?= $index % 2 == 0 ? '#f8fafc' : 'white' ?>; font-weight: bold; color: #64748b;">
                            <?= $spec ?>
                        </td>
                        <?php foreach ($compare_products as $product): ?>
                        <td style="padding: 15px; text-align: center; background: <?= $index % 2 == 0 ? '#f8fafc' : 'white' ?>;">
                            <?= $product['specifications'][$spec] ?? '—' ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🏪 MAĞAZA FİYAT KARŞILAŞTIRMASI -->
    <div style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 20px; text-align: center;">🏪 Mağaza Fiyat Karşılaştırması</h2>
        
        <?php foreach ($compare_products as $product): ?>
        <div style="margin-bottom: 25px;">
            <h3 style="color: #1e40af; margin-bottom: 15px;"><?= $product['brand'] ?> <?= $product['name'] ?></h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <?php
                $stores = [
                    ['name' => 'Teknosa', 'price' => $product['price'] + rand(-2000, 2000), 'shipping' => 'Ücretsiz'],
                    ['name' => 'Vatan', 'price' => $product['price'] + rand(-1500, 1500), 'shipping' => 'Ücretsiz'],
                    ['name' => 'MediaMarkt', 'price' => $product['price'] + rand(-1000, 3000), 'shipping' => '+49 TL'],
                    ['name' => 'Hepsiburada', 'price' => $product['price'] + rand(-2500, 1000), 'shipping' => 'Ücretsiz']
                ];
                
                usort($stores, function($a, $b) {
                    return $a['price'] - $b['price'];
                });
                
                foreach ($stores as $store): 
                    $total_price = $store['price'] + ($store['shipping'] == '+49 TL' ? 49 : 0);
                ?>
                <div style="background: #f8fafc; padding: 15px; border-radius: 8px; text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 8px;"><?= $store['name'] ?></div>
                    <div style="color: #dc2626; font-size: 1.1rem; font-weight: bold; margin-bottom: 5px;">
                        ₺ <?= number_format($total_price, 0, ',', '.') ?>
                    </div>
                    <div style="color: #64748b; font-size: 0.8rem;">
                        <?= $store['shipping'] ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- 🎯 HIZLI AKSİYONLAR -->
    <div style="text-align: center; padding: 30px; background: #f8fafc; border-radius: 15px;">
        <h3 style="margin-bottom: 20px;">Yeni karşılaştırma yapmak ister misiniz?</h3>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="compare.php?search=iphone" style="display: inline-block; background: #2563eb; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                📱 iPhone Ara
            </a>
            <a href="compare.php?search=samsung" style="display: inline-block; background: #2563eb; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                📱 Samsung Ara
            </a>
            <a href="compare.php?search=xiaomi" style="display: inline-block; background: #2563eb; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                📱 Xiaomi Ara
            </a>
            <a href="index.php" style="display: inline-block; background: #64748b; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                🏠 Ana Sayfa
            </a>
        </div>
    </div>
</div>

<script>
// Hızlı ürün ekleme - ARAMA SONUÇLARI İÇİN
function quickAddToCompare(productId) {
    let compareItems = JSON.parse(localStorage.getItem('compareProducts') || '[]');
    
    console.log('Ürün ekleniyor:', productId);
    console.log('Mevcut liste:', compareItems);
    
    if (compareItems.includes(productId)) {
        alert('❌ Bu ürün zaten karşılaştırma listesinde!');
        window.location.href = 'compare.php?items=' + compareItems.join(',');
        return;
    }
    
    if (compareItems.length >= 3) {
        alert('⚠️ Maksimum 3 ürün karşılaştırabilirsiniz!');
        window.location.href = 'compare.php?items=' + compareItems.join(',');
        return;
    }
    
    compareItems.push(productId);
    localStorage.setItem('compareProducts', JSON.stringify(compareItems));
    window.location.href = 'compare.php?items=' + compareItems.join(',');
}

// Karşılaştırma listesini temizle
function clearComparison() {
    if (confirm('Karşılaştırma listesini temizlemek istediğinizden emin misiniz?')) {
        localStorage.removeItem('compareProducts');
        window.location.href = 'compare.php';
    }
}

// Sayfa yüklendiğinde
document.addEventListener('DOMContentLoaded', function() {
    const compareItems = JSON.parse(localStorage.getItem('compareProducts') || '[]');
    console.log('Karşılaştırma listesi:', compareItems);
    
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && !searchInput.value) {
        setTimeout(() => searchInput.focus(), 500);
    }
});
</script>

<style>
@media (max-width: 768px) {
    .container {
        overflow-x: auto;
    }
    table {
        font-size: 0.8rem;
    }
}
</style>

<?php
require_once 'footer.php';
?>