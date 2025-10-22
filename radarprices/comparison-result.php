<?php
include 'includes/config.php';
include 'includes/functions.php';

$search_query = $_GET['q'] ?? '';
$is_ai_search = isset($_GET['ai']);

$page_title = $search_query ? $search_query . " - Fiyat Karşılaştırma" : "Fiyat Karşılaştırma";

// Yapay zeka arama sonuçları
if ($is_ai_search) {
    $products = generateAIRecommendations($search_query);
} else {
    $products = searchProducts($search_query, 20);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - <?php echo SITE_NAME; ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .search-header { background: white; padding: 30px; border-radius: 15px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .ai-badge { background: #9c27b0; color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.8em; display: inline-block; margin-left: 10px; }
        .results-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .product-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .price-comparison { margin-top: 15px; }
        .store-price { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
        .best-price { color: #2e7d32; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="search-header">
            <h1>
                "<?php echo htmlspecialchars($search_query); ?>" için Sonuçlar
                <?php if($is_ai_search): ?><span class="ai-badge">Yapay Zeka</span><?php endif; ?>
            </h1>
            <p><?php echo count($products); ?>+ ürün bulundu</p>
        </div>

        <div class="results-grid">
            <?php foreach($products as $product): ?>
            <div class="product-card">
                <h3><?php echo $product['name']; ?></h3>
                <div class="price-comparison">
                    <?php foreach(getProductPrices($product['id']) as $price): ?>
                    <div class="store-price">
                        <span><?php echo $price['store']; ?></span>
                        <span class="<?php echo $price['is_best'] ? 'best-price' : ''; ?>">
                            ₺<?php echo $price['price']; ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button style="background: #ff6b00; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; width: 100%; margin-top: 15px;">
                    + Karşılaştırmaya Ekle
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

<?php
// YAPAY ZEKA ÖNERİLERİ
function generateAIRecommendations($query) {
    $recommendations = [];
    
    if (stripos($query, 'bütçe') !== false || stripos($query, 'ucuz') !== false) {
        $recommendations = [
            ['id' => 1, 'name' => 'Xiaomi Redmi Note 13 - En İyi Bütçe Telefonu', 'prices' => getBudgetPhonePrices()],
            ['id' => 2, 'name' => 'Samsung Galaxy A25 - Uygun Fiyatlı Orta Segment', 'prices' => getMidRangePhonePrices()],
            ['id' => 3, 'name' => 'Realme 11 - Fiyat/Performans Lideri', 'prices' => getValuePhonePrices()]
        ];
    } else {
        $recommendations = searchProducts($query, 10);
    }
    
    return $recommendations;
}

function getProductPrices($productId) {
    return [
        ['store' => 'Trendyol', 'price' => '12.999', 'is_best' => true],
        ['store' => 'Hepsiburada', 'price' => '13.299', 'is_best' => false],
        ['store' => 'n11', 'price' => '12.899', 'is_best' => false],
        ['store' => 'Amazon', 'price' => '13.499', 'is_best' => false]
    ];
}

function getBudgetPhonePrices() {
    return [
        ['store' => 'Trendyol', 'price' => '8.999', 'is_best' => true],
        ['store' => 'Hepsiburada', 'price' => '9.299', 'is_best' => false]
    ];
}
?>