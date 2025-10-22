<?php
include 'includes/config.php';
include 'includes/functions.php';

$category_slug = $_GET['cat'] ?? 'elektronik';
$subcategory_slug = $_GET['sub'] ?? 'telefon';

// Kategori bilgilerini al
$category = getCategoryBySlug($category_slug);
if (!$category) {
    header("Location: index.php");
    exit;
}

// Alt kategori başlıklarını belirle
$subcategory_titles = [
    'telefon' => ['title' => 'Akıllı Telefonlar', 'icon' => '📱', 'search_placeholder' => 'iPhone 15 Pro, Samsung S24, Xiaomi 14...'],
    'laptop' => ['title' => 'Laptop & Bilgisayar', 'icon' => '💻', 'search_placeholder' => 'MacBook Air, Dell XPS, Asus Zenbook...'],
    'tablet' => ['title' => 'Tabletler', 'icon' => '📟', 'search_placeholder' => 'iPad Pro, Samsung Tab, Huawei MatePad...'],
    'televizyon' => ['title' => 'Televizyonlar', 'icon' => '📺', 'search_placeholder' => 'Samsung QLED, LG OLED, Sony Bravia...'],
    'sut-kahvalti' => ['title' => 'Süt & Kahvaltılık', 'icon' => '🥛', 'search_placeholder' => 'Süt, Peynir, Yumurta, Zeytin...'],
    'meyve-sebze' => ['title' => 'Meyve & Sebze', 'icon' => '🍎', 'search_placeholder' => 'Elma, Muz, Domates, Salatalık...'],
    'et-balik' => ['title' => 'Et & Balık', 'icon' => '🍗', 'search_placeholder' => 'Kıyma, Tavuk, Somon, Dana...'],
    'erkek' => ['title' => 'Erkek Giyim', 'icon' => '👔', 'search_placeholder' => 'Tişört, Pantolon, Ceket...'],
    'kadin' => ['title' => 'Kadın Giyim', 'icon' => '👗', 'search_placeholder' => 'Elbise, Bluz, Etek...'],
    'mama' => ['title' => 'Bebek Beslenme', 'icon' => '🍼', 'search_placeholder' => 'Mama, Biberon, Emzik...']
];

$subcategory_info = $subcategory_titles[$subcategory_slug] ?? ['title' => 'Ürünler', 'icon' => '📦', 'search_placeholder' => 'Ürün ara...'];

$page_title = $subcategory_info['title'] . " Karşılaştırma [2025] - " . SITE_NAME;

// Market kategorisinde konum bazlı fiyat göster
$user_location = ($category_slug == 'market') ? getUserLocation() : null;
$nearby_markets = ($category_slug == 'market') ? getNearbyMarkets($user_location) : [];
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
            background: #f8f9fa; 
            color: #333;
            line-height: 1.6;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        
        .subcategory-header { 
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .subcategory-icon { font-size: 3em; margin-bottom: 15px; }
        .subcategory-header h1 { color: #333; margin-bottom: 10px; }
        
        /* MARKET KONUM BİLGİSİ */
        .location-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        
        /* KARŞILAŞTIRMA FORMU */
        .comparison-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .product-selectors {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .product-selector {
            border: 2px dashed #ddd;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .product-selector:hover {
            border-color: #667eea;
            background: #f8f9ff;
        }
        .compare-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            transition: transform 0.3s;
        }
        .compare-btn:hover {
            transform: scale(1.02);
        }
        
        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
        }
        .breadcrumb a { color: #667eea; text-decoration: none; }
        
        /* YAPAY ZEKA REHBER BUTONLARI */
        .ai-guide-btn {
            background: white;
            border: 2px solid #e0e0e0;
            padding: 20px;
            border-radius: 10px;
            cursor: pointer;
            text-align: left;
            transition: all 0.3s;
            width: 100%;
            margin-bottom: 15px;
        }
        .ai-guide-btn:hover {
            border-color: #9c27b0;
            box-shadow: 0 5px 15px rgba(156, 39, 176, 0.2);
        }

        /* ÜRÜN KARTLARI */
        .product-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        /* ÜRÜN GÖRSEL ALANI - RENKLİ GRADIENT */
        .product-image {
            height: 140px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
            font-weight: bold;
            font-size: 1.1em;
        }
        
        /* Kategoriye göre renkler */
        .electronic-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .market-bg { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); }
        .clothing-bg { background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%); }
        .baby-bg { background: linear-gradient(135deg, #E91E63 0%, #C2185B 100%); }
        
        .price-tag {
            color: #2e7d32;
            font-weight: bold;
            font-size: 1.3em;
            margin: 10px 0;
        }
        
        .add-to-compare {
            background: #ff6b00;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background 0.3s;
        }
        .add-to-compare:hover {
            background: #e55a00;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- BREADCRUMB -->
        <nav class="breadcrumb">
            <a href="index.php">🏠 Ana Sayfa</a> > 
            <a href="category.php?cat=<?php echo $category_slug; ?>"><?php echo $category['name']; ?></a> > 
            <span><?php echo $subcategory_info['title']; ?></span>
        </nav>
        
        <!-- ALT KATEGORİ BAŞLIĞI -->
        <div class="subcategory-header">
            <div class="subcategory-icon"><?php echo $subcategory_info['icon']; ?></div>
            <h1><?php echo $subcategory_info['title']; ?> Karşılaştırma [2025]</h1>
            <p>En popüler modelleri karşılaştırın - Güncel fiyatlar ve teknik özellikler</p>
            
            <?php if($category_slug == 'market' && $user_location): ?>
            <div class="location-info">
                <strong>📍 Konumunuz:</strong> <?php echo $user_location['city'] . ', ' . $user_location['district']; ?> 
                | <a href="#" onclick="changeLocation()" style="color: #667eea;">Konumu değiştir</a>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- FİLTRELEME -->
        <div style="background: white; padding: 20px; border-radius: 10px; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                <span>Filtrele:</span>
                <input type="number" placeholder="Min TL" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 120px;">
                <span>-</span>
                <input type="number" placeholder="Max TL" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px; width: 120px;">
                <button style="padding: 10px 20px; background: #667eea; color: white; border: none; border-radius: 5px; cursor: pointer;">Filtrele</button>
            </div>
        </div>
        
        <!-- ÜRÜN ARA -->
        <div style="background: white; padding: 20px; border-radius: 10px; margin: 20px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <input type="text" placeholder="🔍 <?php echo $subcategory_info['search_placeholder']; ?>" 
                   style="width: 100%; padding: 15px; border: 2px solid #ddd; border-radius: 8px; font-size: 16px;">
        </div>

        <!-- KATEGORİYE ÖZEL İÇERİK -->
        <?php if ($category_slug == 'elektronik'): ?>
        <!-- ELEKTRONİK ÖZEL - 3'LÜ KARŞILAŞTIRMA -->
        <div class="comparison-form">
            <h3>🎯 3'lü Ürün Karşılaştırma</h3>
            <p>Karşılaştırmak istediğiniz 3 ürünü seçin:</p>
            
            <div class="product-selectors">
                <div class="product-selector" onclick="addProduct(1)">
                    <div style="font-size: 2em;">+</div>
                    <div>Ürün 1 Seçin</div>
                </div>
                <div class="product-selector" onclick="addProduct(2)">
                    <div style="font-size: 2em;">+</div>
                    <div>Ürün 2 Seçin</div>
                </div>
                <div class="product-selector" onclick="addProduct(3)">
                    <div style="font-size: 2em;">+</div>
                    <div>Ürün 3 Seçin</div>
                </div>
            </div>
            
            <button class="compare-btn" onclick="startComparison()">🚀 Karşılaştırmayı Başlat</button>
        </div>

        <!-- ELEKTRONİK ÜRÜNLER -->
        <?php
        $electronic_products = [
            ['id' => 1, 'name' => 'iPhone 15 Pro', 'brand' => 'Apple', 'price' => 54999, 'specs' => '6.1" • A17 Pro • 128GB'],
            ['id' => 2, 'name' => 'Samsung Galaxy S24', 'brand' => 'Samsung', 'price' => 42999, 'specs' => '6.2" • Snapdragon 8 • 256GB'],
            ['id' => 3, 'name' => 'Xiaomi 14 Pro', 'brand' => 'Xiaomi', 'price' => 34999, 'specs' => '6.73" • Snapdragon 8 • 512GB'],
            ['id' => 4, 'name' => 'MacBook Air M3', 'brand' => 'Apple', 'price' => 42999, 'specs' => '13.6" • M3 • 8GB • 256GB'],
            ['id' => 5, 'name' => 'Samsung Tab S9', 'brand' => 'Samsung', 'price' => 28999, 'specs' => '11" • S-Pen • 128GB'],
            ['id' => 6, 'name' => 'Sony WH-1000XM5', 'brand' => 'Sony', 'price' => 8999, 'specs' => 'Noise Cancelling • 30h']
        ];
        ?>
        <div style="margin: 40px 0;">
            <h3>📱 Popüler Elektronik Ürünler</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0;">
                <?php foreach($electronic_products as $product): ?>
                <div class="product-card">
                    <div class="product-image electronic-bg">
                        <?php echo $product['brand']; ?><br><?php echo explode(' ', $product['name'])[0]; ?>
                    </div>
                    <div style="font-size: 0.9em; color: #666; margin-bottom: 5px;"><?php echo $product['brand']; ?></div>
                    <div style="font-weight: 500; margin-bottom: 8px; font-size: 1.1em;"><?php echo $product['name']; ?></div>
                    <div style="font-size: 0.8em; color: #666; margin-bottom: 10px;"><?php echo $product['specs']; ?></div>
                    <div class="price-tag">₺<?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                    <button class="add-to-compare">+ Karşılaştır</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($category_slug == 'market'): ?>
        <!-- MARKET ÖZEL TASARIM -->
        <?php
        $market_products = [
            ['id' => 1, 'name' => 'Ayçiçek Yağı 5L', 'brand' => 'Biryem', 'price' => 189.90, 'unit' => '5L'],
            ['id' => 2, 'name' => 'Zeytinyağı 2L', 'brand' => 'Komili', 'price' => 249.90, 'unit' => '2L'],
            ['id' => 3, 'name' => 'Süt 1L', 'brand' => 'Sütaş', 'price' => 24.90, 'unit' => '1L'],
            ['id' => 4, 'name' => 'Peynir 500g', 'brand' => 'Pınar', 'price' => 89.90, 'unit' => '500g'],
            ['id' => 5, 'name' => 'Yumurta 30\'lu', 'brand' => 'Köy', 'price' => 129.90, 'unit' => '30 adet'],
            ['id' => 6, 'name' => 'Domates 1kg', 'brand' => '', 'price' => 34.90, 'unit' => '1kg']
        ];
        ?>
        <div style="margin: 40px 0;">
            <h3>🏪 Popüler Market Ürünleri</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0;">
                <?php foreach($market_products as $product): ?>
                <div class="product-card">
                    <div class="product-image market-bg">
                        <?php echo $product['brand'] ?: 'MARKET'; ?><br><?php echo explode(' ', $product['name'])[0]; ?>
                    </div>
                    <?php if($product['brand']): ?>
                    <div style="font-size: 0.9em; color: #666; margin-bottom: 5px;"><?php echo $product['brand']; ?></div>
                    <?php endif; ?>
                    <div style="font-weight: 500; margin-bottom: 8px; font-size: 1.1em;"><?php echo $product['name']; ?></div>
                    <div style="font-size: 0.8em; color: #666; margin-bottom: 10px;"><?php echo $product['unit']; ?></div>
                    <div class="price-tag">₺<?php echo number_format($product['price'], 2, ',', '.'); ?></div>
                    <button class="add-to-compare">+ Karşılaştır</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($category_slug == 'giyim-ayakkabi'): ?>
        <!-- GİYİM ÖZEL TASARIM -->
        <?php
        $clothing_products = [
            ['id' => 1, 'name' => 'Spor Ayakkabı', 'brand' => 'Nike', 'price' => 1299.90, 'size' => '38-45'],
            ['id' => 2, 'name' => 'Tişört Beyaz', 'brand' => 'Mavi', 'price' => 299.90, 'size' => 'S-XXL'],
            ['id' => 3, 'name' => 'Kot Pantolon', 'brand' => 'LC Waikiki', 'price' => 599.90, 'size' => '28-40'],
            ['id' => 4, 'name' => 'Sweatshirt', 'brand' => 'Puma', 'price' => 399.90, 'size' => 'S-XXL'],
            ['id' => 5, 'name' => 'Eşofman Altı', 'brand' => 'Adidas', 'price' => 349.90, 'size' => 'S-XXL'],
            ['id' => 6, 'name' => 'Gömlek', 'brand' => 'Defacto', 'price' => 199.90, 'size' => 'S-XXL']
        ];
        ?>
        <div style="margin: 40px 0;">
            <h3>👕 Popüler Giyim Ürünleri</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0;">
                <?php foreach($clothing_products as $product): ?>
                <div class="product-card">
                    <div class="product-image clothing-bg">
                        <?php echo $product['brand']; ?><br>MODA
                    </div>
                    <div style="font-size: 0.9em; color: #666; margin-bottom: 5px;"><?php echo $product['brand']; ?></div>
                    <div style="font-weight: 500; margin-bottom: 8px; font-size: 1.1em;"><?php echo $product['name']; ?></div>
                    <div style="font-size: 0.8em; color: #666; margin-bottom: 10px;">Beden: <?php echo $product['size']; ?></div>
                    <div class="price-tag">₺<?php echo number_format($product['price'], 2, ',', '.'); ?></div>
                    <button class="add-to-compare">+ Karşılaştır</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($category_slug == 'anne-bebek'): ?>
        <!-- ANNE BEBEK ÖZEL TASARIM -->
        <?php
        $baby_products = [
            ['id' => 1, 'name' => 'Bebek Bezi', 'brand' => 'Sleepy', 'price' => 189.90, 'size' => '4 Beden'],
            ['id' => 2, 'name' => 'Bebek Maması', 'brand' => 'Aptamil', 'price' => 249.90, 'size' => '900g'],
            ['id' => 3, 'name' => 'Bebek Arabası', 'brand' => 'Chicco', 'price' => 2999.90, 'size' => '3\'ü 1 arada'],
            ['id' => 4, 'name' => 'Bebek Tişört', 'brand' => 'LC Waikiki', 'price' => 49.90, 'size' => '1-2 Yaş'],
            ['id' => 5, 'name' => 'Emzik', 'brand' => 'NUK', 'price' => 39.90, 'size' => '0-6 ay'],
            ['id' => 6, 'name' => 'Bebek Şampuanı', 'brand' => 'Mustela', 'price' => 89.90, 'size' => '500ml']
        ];
        ?>
        <div style="margin: 40px 0;">
            <h3>👶 Anne & Bebek Ürünleri</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0;">
                <?php foreach($baby_products as $product): ?>
                <div class="product-card">
                    <div class="product-image baby-bg">
                        <?php echo $product['brand']; ?><br>BEBEK
                    </div>
                    <div style="font-size: 0.9em; color: #666; margin-bottom: 5px;"><?php echo $product['brand']; ?></div>
                    <div style="font-weight: 500; margin-bottom: 8px; font-size: 1.1em;"><?php echo $product['name']; ?></div>
                    <div style="font-size: 0.8em; color: #666; margin-bottom: 10px;"><?php echo $product['size']; ?></div>
                    <div class="price-tag">₺<?php echo number_format($product['price'], 2, ',', '.'); ?></div>
                    <button class="add-to-compare">+ Karşılaştır</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- YAKINDAKİ MARKETLER (SADECE MARKET KATEGORİSİNDE) -->
        <?php if($category_slug == 'market' && !empty($nearby_markets)): ?>
        <div style="background: white; padding: 25px; border-radius: 15px; margin: 30px 0; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <h3>🛒 Yakındaki Marketler</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                <?php foreach($nearby_markets as $market): ?>
                <div style="border: 1px solid #e0e0e0; padding: 15px; border-radius: 10px; text-align: center; transition: all 0.3s;">
                    <div style="font-size: 2em;">🛒</div>
                    <strong><?php echo $market['name']; ?></strong>
                    <p style="color: #666; font-size: 0.9em; margin: 5px 0;"><?php echo $market['distance']; ?> • <?php echo $market['delivery_time']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- YAPAY ZEKA İLE SONSUZ REHBERLER -->
        <div id="smart-next-guides" style="background: linear-gradient(135deg, #e8f4fc 0%, #f3e5f5 100%); padding: 30px; border-radius: 15px; margin: 40px 0; border: 2px solid #9c27b0;">
            <h3 style="color: #7b1fa2; margin-top: 0;">🚀 Yapay Zeka ile Anında Rehber Oluştur!</h3>
            <p style="color: #5f6368;">Hangi konuda rehber istiyorsunuz? Tıklayın, AI sizin için hemen oluştursun!</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; margin-top: 20px;">
                <?php foreach(getAIGuideTopics($category_slug, $subcategory_slug) as $topic): ?>
                <button class="ai-guide-btn" onclick="generateAIGuide('<?php echo $topic['id']; ?>', '<?php echo $category_slug; ?>')">
                    <strong style="color: #1a73e8; display: block; margin-bottom: 8px; font-size: 1.1em;"><?php echo $topic['icon']; ?> <?php echo $topic['title']; ?></strong>
                    <p style="margin: 0; color: #5f6368; font-size: 14px; line-height: 1.4;"><?php echo $topic['description']; ?></p>
                    <div style="margin-top: 10px; font-size: 12px; color: #9c27b0; font-weight: 500;">
                        🤖 AI ile anında oluşturulacak
                    </div>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
    function addProduct(number) {
        alert('Ürün ' + number + ' seçim ekranı açılacak');
    }
    
    function startComparison() {
        const productIds = [1, 2, 3];
        window.location.href = 'comparison-result.php?products=' + productIds.join(',');
    }
    
    function generateAIGuide(topicId, category) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<div style="text-align: center; color: #9c27b0;">🤖 AI rehber oluşturuluyor...</div>';
        button.disabled = true;
        
        fetch('generate-guide.php?topic=' + topicId + '&category=' + category)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    window.location.href = data.article_url;
                } else {
                    alert('Rehber oluşturulamadı: ' + data.error);
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                alert('Bir hata oluştu: ' + error);
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }
    
    function changeLocation() {
        alert('Konum değiştirme ekranı açılacak');
    }
    </script>
</body>
</html>