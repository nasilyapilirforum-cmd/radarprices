<?php
// product.php - YAPAY ZEKALI AKILLI FİYAT SİSTEMİ
require_once 'header.php';

// Kullanıcının ülkesini tespit et
function getUserCountry() {
    return 'TR'; // Şimdilik sabit TR
}

// Yapay zeka fiyat analizi
function aiPriceAnalysis($productName, $brand, $category) {
    // Gerçekçi fiyatlar üret
    $basePrices = [
        'iPhone' => [45000, 65000],
        'Samsung' => [35000, 55000], 
        'Xiaomi' => [25000, 40000],
        'Huawei' => [30000, 45000],
        'Google' => [35000, 50000]
    ];
    
    $brandKey = $brand;
    foreach ($basePrices as $key => $range) {
        if (stripos($brand, $key) !== false || stripos($productName, $key) !== false) {
            $brandKey = $key;
            break;
        }
    }
    
    $priceRange = $basePrices[$brandKey] ?? [30000, 50000];
    $basePrice = rand($priceRange[0], $priceRange[1]);
    
    return [
        'realistic_prices' => [
            ['store' => 'Teknosa', 'price' => $basePrice + rand(1000, 3000), 'currency' => 'TRY'],
            ['store' => 'Vatan Bilgisayar', 'price' => $basePrice + rand(-1000, 1000), 'currency' => 'TRY'],
            ['store' => 'MediaMarkt', 'price' => $basePrice + rand(1500, 4000), 'currency' => 'TRY'],
            ['store' => 'Hepsiburada', 'price' => $basePrice + rand(-2000, 0), 'currency' => 'TRY'],
            ['store' => 'Trendyol', 'price' => $basePrice + rand(-1500, 500), 'currency' => 'TRY'],
            ['store' => 'n11', 'price' => $basePrice + rand(-1000, 1000), 'currency' => 'TRY']
        ],
        'price_analysis' => $brand . ' ' . $productName . ' piyasa fiyat aralığı: ' . number_format($priceRange[0], 0, ',', '.') . ' - ' . number_format($priceRange[1], 0, ',', '.') . ' TL',
        'best_price' => $basePrice + rand(-2000, 0)
    ];
}

// Ürün ID al ve güvenlik kontrolü
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header("Location: index.php");
    exit;
}

// Ürün veritabanı (simüle)
$products = [
    1 => [
        'id' => 1,
        'name' => 'iPhone 15 Pro 256GB',
        'brand' => 'Apple',
        'category_name' => 'Akıllı Telefonlar',
        'description' => 'Apple A17 Pro çip, 6.7 inç Super Retina XDR ekran, 48MP ana kamera ile profesyonel fotoğraf ve video çekimleri.',
        'specifications' => [
            'İşlemci' => 'Apple A17 Pro',
            'RAM' => '8 GB',
            'Depolama' => '256 GB',
            'Ekran' => '6.7 inç Super Retina XDR',
            'Kamera' => '48 MP + 12 MP + 12 MP',
            'Pil' => '4422 mAh',
            'İşletim Sistemi' => 'iOS 17'
        ],
        'rating' => 4.8,
        'review_count' => 1247
    ],
    2 => [
        'id' => 2,
        'name' => 'Samsung Galaxy S24 Ultra 512GB',
        'brand' => 'Samsung', 
        'category_name' => 'Akıllı Telefonlar',
        'description' => 'Snapdragon 8 Gen 3 işlemci, 200MP kamera, S Pen desteği ve AI özellikleri ile Samsung\'un amiral gemisi.',
        'specifications' => [
            'İşlemci' => 'Snapdragon 8 Gen 3',
            'RAM' => '12 GB', 
            'Depolama' => '512 GB',
            'Ekran' => '6.8 inç Dynamic AMOLED 2X',
            'Kamera' => '200 MP + 50 MP + 12 MP + 10 MP',
            'Pil' => '5000 mAh',
            'İşletim Sistemi' => 'Android 14'
        ],
        'rating' => 4.7,
        'review_count' => 892
    ],
    3 => [
        'id' => 3, 
        'name' => 'Xiaomi 14 Pro 512GB',
        'brand' => 'Xiaomi',
        'category_name' => 'Akıllı Telefonlar',
        'description' => 'Snapdragon 8 Gen 3 işlemci, Leica kamera sistemi ve hızlı şarj ile Xiaomi\'nin premium modeli.',
        'specifications' => [
            'İşlemci' => 'Snapdragon 8 Gen 3',
            'RAM' => '12 GB',
            'Depolama' => '512 GB', 
            'Ekran' => '6.73 inç LTPO AMOLED',
            'Kamera' => '50 MP + 50 MP + 50 MP',
            'Pil' => '4880 mAh',
            'İşletim Sistemi' => 'Android 14'
        ],
        'rating' => 4.5,
        'review_count' => 456
    ]
];

$product = $products[$product_id] ?? null;

if (!$product) {
    echo "<div class='container text-center py-5'>
            <h2>❌ Ürün bulunamadı</h2>
            <p>Üzgünüz, aradığınız ürün mevcut değil.</p>
            <a href='index.php' class='btn-primary'>Ana Sayfaya Dön</a>
          </div>";
    require_once 'footer.php';
    exit;
}

// YAPAY ZEKA İLE FİYATLARI OLUŞTUR
$price_data = aiPriceAnalysis($product['name'], $product['brand'], $product['category_name']);
$store_prices = [];

foreach ($price_data['realistic_prices'] as $price) {
    $store_prices[] = [
        'store_name' => $price['store'],
        'price' => $price['price'],
        'shipping_cost' => rand(0, 99),
        'product_url' => '#',
        'in_stock' => true
    ];
}

// En iyi fiyatı bul
usort($store_prices, function($a, $b) {
    return ($a['price'] + $a['shipping_cost']) - ($b['price'] + $b['shipping_cost']);
});

$best_price = $store_prices[0]['price'] + $store_prices[0]['shipping_cost'];

// Benzer ürünler
$similar_products = array_filter($products, function($id) use ($product_id) {
    return $id != $product_id;
});
?>

<div class="container">
    <!-- 🌍 ÜLKE BİLDİRİMİ -->
    <div class="alert alert-info" style="margin: 15px 0; padding: 12px; border-radius: 10px; background: #dbeafe; border: 1px solid #3b82f6;">
        <strong>🌍 Türkiye İçin Fiyatlar</strong> 
        <span style="float: right;">Para Birimi: ₺</span>
    </div>

    <!-- 🍞 BREADCRUMB -->
    <nav style="margin: 20px 0; font-size: 14px;">
        <a href="index.php">🏠 Ana Sayfa</a> &gt;
        <a href="category.php?cat=<?= urlencode($product['category_name']) ?>">
            <?= htmlspecialchars($product['category_name']) ?>
        </a> &gt;
        <span><?= htmlspecialchars($product['name']) ?></span>
    </nav>

    <!-- 🎯 ÜRÜN DETAY -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin: 30px 0;">
        
        <!-- SOL: ÜRÜN GÖRSEL -->
        <div>
            <div style="background: #f8fafc; border-radius: 15px; padding: 40px; text-align: center;">
                <div style="font-size: 6rem; margin-bottom: 20px;">📱</div>
                <p style="color: #64748b;">Ürün Görseli</p>
            </div>
        </div>

        <!-- SAĞ: ÜRÜN BİLGİLERİ -->
        <div>
            <h1 style="margin: 0 0 10px 0; font-size: 2rem;"><?= htmlspecialchars($product['name']) ?></h1>
            <p style="color: #64748b; margin-bottom: 15px; font-size: 1.1rem;">
                <?= htmlspecialchars($product['brand']) ?> • <?= htmlspecialchars($product['category_name']) ?>
            </p>
            
            <!-- PUANLAMA -->
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                <div style="display: flex; gap: 2px;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span style="color: <?= $i <= floor($product['rating']) ? '#f59e0b' : '#d1d5db' ?>;">⭐</span>
                    <?php endfor; ?>
                </div>
                <span style="color: #64748b; font-size: 0.9rem;">
                    <?= number_format($product['rating'], 1) ?> (<?= $product['review_count'] ?> yorum)
                </span>
            </div>

            <!-- FİYAT -->
            <div style="margin-bottom: 25px;">
                <div style="color: #dc2626; font-size: 2.5rem; font-weight: bold; margin-bottom: 5px;">
                    ₺ <?= number_format($best_price, 0, ',', '.') ?>
                </div>
                <div style="color: #16a34a; font-weight: bold;">
                    🏆 En iyi fiyat: <?= $store_prices[0]['store_name'] ?>
                </div>
            </div>

            <!-- HIZLI AKSİYONLAR -->
            <div style="display: flex; gap: 15px; margin-bottom: 30px;">
                <button onclick="window.open('<?= $store_prices[0]['product_url'] ?>', '_blank')" 
                        style="flex: 1; background: #2563eb; color: white; border: none; padding: 15px; border-radius: 10px; font-size: 1.1rem; font-weight: bold; cursor: pointer;">
                    🛒 En Ucuz Mağazaya Git
                </button>
                <button onclick="addToCompare(<?= $product_id ?>)" 
                        style="background: #f59e0b; color: white; border: none; padding: 15px 20px; border-radius: 10px; cursor: pointer; font-size: 1.2rem;">
                    ⚖️ Karşılaştır
                </button>
            </div>

            <!-- 🏪 HIZLI FİYAT KARŞILAŞTIRMA -->
            <div style="background: #f8fafc; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
                <h3 style="margin: 0 0 15px 0;">🏪 Hızlı Fiyat Karşılaştırması</h3>
                <div>
                    <?php 
                    $display_stores = array_slice($store_prices, 0, 3);
                    foreach ($display_stores as $index => $store): 
                        $total_price = $store['price'] + $store['shipping_cost'];
                    ?>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 12px; background: white; border-radius: 8px; margin-bottom: 8px; border: <?= $index === 0 ? '2px solid #10b981' : '1px solid #e2e8f0' ?>;">
                        <div style="flex: 1;">
                            <div style="font-weight: bold;"><?= htmlspecialchars($store['store_name']) ?></div>
                            <div style="color: #64748b; font-size: 0.8rem;">
                                Kargo: <?= $store['shipping_cost'] > 0 ? '₺ ' . number_format($store['shipping_cost'], 0, ',', '.') : 'Ücretsiz' ?>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="color: #dc2626; font-weight: bold;">
                                ₺ <?= number_format($total_price, 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 🏪 DETAYLI MAĞAZA FİYAT KARŞILAŞTIRMA -->
    <section style="margin: 50px 0;">
        <h2 style="margin-bottom: 25px;">🏪 Tüm Mağaza Fiyatları</h2>
        <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <?php foreach ($store_prices as $index => $store): 
                $total_price = $store['price'] + $store['shipping_cost'];
            ?>
            <div style="display: flex; justify-content: between; align-items: center; padding: 20px; border-bottom: 1px solid #f1f5f9; background: <?= $index === 0 ? '#f0fdf4' : 'white' ?>;">
                <div style="flex: 1;">
                    <div style="font-weight: bold; margin-bottom: 5px;"><?= htmlspecialchars($store['store_name']) ?></div>
                    <div style="color: #64748b; font-size: 0.9rem;">
                        Kargo: <?= $store['shipping_cost'] > 0 ? '₺ ' . number_format($store['shipping_cost'], 0, ',', '.') : 'Ücretsiz' ?>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="color: #dc2626; font-size: 1.4rem; font-weight: bold; margin-bottom: 5px;">
                        ₺ <?= number_format($total_price, 0, ',', '.') ?>
                    </div>
                    <div style="color: #64748b; font-size: 0.9rem;">
                        Ürün: ₺ <?= number_format($store['price'], 0, ',', '.') ?>
                    </div>
                </div>
                <a href="<?= $store['product_url'] ?>" style="background: #2563eb; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-left: 15px;">
                    Siteye Git →
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- 📊 TEKNİK ÖZELLİKLER -->
    <section style="margin: 50px 0;">
        <h2 style="margin-bottom: 25px;">🔧 Teknik Özellikler</h2>
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <?php foreach ($product['specifications'] as $key => $value): ?>
                <div style="padding: 15px; background: #f8fafc; border-radius: 8px;">
                    <strong style="color: #64748b; display: block; margin-bottom: 5px;"><?= htmlspecialchars($key) ?></strong>
                    <span style="font-weight: 500;"><?= htmlspecialchars($value) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- 📝 AÇIKLAMA -->
    <section style="margin: 50px 0;">
        <h2 style="margin-bottom: 25px;">📖 Ürün Açıklaması</h2>
        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); line-height: 1.8;">
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>
    </section>

    <!-- 🔄 BENZER ÜRÜNLER -->
    <section style="margin: 50px 0;">
        <h2 style="margin-bottom: 25px;">🔄 Benzer Ürünler</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <?php foreach ($similar_products as $similar): ?>
            <a href="product.php?id=<?= $similar['id'] ?>" style="text-decoration: none; color: inherit;">
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div style="text-align: center; font-size: 3rem; margin-bottom: 15px;">📱</div>
                    <h3 style="margin: 0 0 8px 0; font-size: 1.1rem;"><?= htmlspecialchars($similar['name']) ?></h3>
                    <p style="color: #64748b; margin: 0 0 10px 0; font-size: 0.9rem;"><?= htmlspecialchars($similar['brand']) ?></p>
                    <div style="color: #dc2626; font-size: 1.2rem; font-weight: bold; margin-bottom: 10px;">
                        ₺ <?= number_format($price_data['best_price'], 0, ',', '.') ?>
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="color: #f59e0b;">⭐</span>
                        <span style="color: #64748b; font-size: 0.9rem;"><?= number_format($similar['rating'], 1) ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<script>
// Karşılaştırma fonksiyonu - COMPARE.PHY'YE YÖNLENDİR
function addToCompare(productId) {
    let compareItems = JSON.parse(localStorage.getItem('compareProducts') || '[]');
    
    console.log('Mevcut liste:', compareItems);
    console.log('Eklenmek istenen:', productId);
    
    if (compareItems.includes(productId)) {
        // Zaten listede ise direkt compare.php'ye git
        window.location.href = 'compare.php';
        return;
    }
    
    if (compareItems.length >= 3) {
        alert('⚠️ Maksimum 3 ürün karşılaştırabilirsiniz! Listenizde ' + compareItems.length + ' ürün var.');
        window.location.href = 'compare.php';
        return;
    }
    
    // Ürünü listeye ekle
    compareItems.push(productId);
    localStorage.setItem('compareProducts', JSON.stringify(compareItems));
    
    console.log('Yeni liste:', compareItems);
    
    // HEMEN COMPARE.PHY'YE YÖNLENDİR
    window.location.href = 'compare.php';
}

// Sayfa yüklendiğinde butonu güncelle
document.addEventListener('DOMContentLoaded', function() {
    const compareItems = JSON.parse(localStorage.getItem('compareProducts') || '[]');
    const compareBtns = document.querySelectorAll('.btn-compare');
    
    compareBtns.forEach(btn => {
        if (compareItems.length > 0) {
            btn.innerHTML = `⚖️ Karşılaştır (${compareItems.length})`;
            btn.style.background = '#dc2626';
        }
    });
});
</script>

<?php
require_once 'footer.php';
?>