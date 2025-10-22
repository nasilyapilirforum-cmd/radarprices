<?php
include 'includes/config.php';
include 'includes/functions.php';

$category_slug = $_GET['cat'] ?? 'elektronik';

// Kategoriyi getir
$category = getCategoryBySlug($category_slug);
if (!$category) {
    header("Location: index.php");
    exit;
}

$page_title = $category['name'] . " Fiyat Karşılaştırma - " . SITE_NAME;
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
        
        .category-header { 
            background: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .category-icon { font-size: 4em; margin-bottom: 15px; }
        .category-header h1 { color: #333; margin-bottom: 10px; }
        .category-stats { color: #666; font-size: 1.1em; }
        
        .subcategories-grid { 
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        .subcategory-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .subcategory-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .subcategory-icon { font-size: 3em; margin-bottom: 15px; }
        .subcategory-card h3 { color: #333; margin-bottom: 10px; }
        
        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
        }
        .breadcrumb a { color: #667eea; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <!-- BREADCRUMB -->
        <nav class="breadcrumb">
            <a href="index.php">🏠 Ana Sayfa</a> > 
            <span><?php echo $category['name']; ?></span>
        </nav>
        
        <!-- KATEGORİ BAŞLIĞI -->
        <div class="category-header">
            <div class="category-icon"><?php echo $category['icon']; ?></div>
            <h1><?php echo $category['name']; ?> Fiyat Karşılaştırma</h1>
            <p class="category-stats">50.000+ ürün • 50+ mağaza • Gerçek zamanlı fiyatlar</p>
        </div>
        
        <!-- ALT KATEGORİLER -->
        <h2 style="text-align: center; margin: 30px 0; color: #333;">Alt Kategoriler</h2>
        
        <div class="subcategories-grid">
            <?php
            // Kategoriye göre alt kategorileri göster
            $subcategories = [];
            
            switch($category_slug) {
                case 'elektronik':
                    $subcategories = [
                        ['slug' => 'telefon', 'name' => 'Akıllı Telefonlar', 'icon' => '📱'],
                        ['slug' => 'laptop', 'name' => 'Laptop & Bilgisayar', 'icon' => '💻'],
                        ['slug' => 'tablet', 'name' => 'Tabletler', 'icon' => '📟'],
                        ['slug' => 'televizyon', 'name' => 'Televizyonlar', 'icon' => '📺'],
                        ['slug' => 'kulaklik', 'name' => 'Kulaklıklar', 'icon' => '🎧'],
                        ['slug' => 'oyun-konsolu', 'name' => 'Oyun Konsolları', 'icon' => '🎮']
                    ];
                    break;
                    
                case 'market':
                    $subcategories = [
                        ['slug' => 'sut-kahvalti', 'name' => 'Süt & Kahvaltılık', 'icon' => '🥛'],
                        ['slug' => 'meyve-sebze', 'name' => 'Meyve & Sebze', 'icon' => '🍎'],
                        ['slug' => 'et-balik', 'name' => 'Et & Balık', 'icon' => '🍗'],
                        ['slug' => 'temel-gida', 'name' => 'Temel Gıda', 'icon' => '🍞'],
                        ['slug' => 'icecek', 'name' => 'İçecekler', 'icon' => '🧃'],
                        ['slug' => 'atistirmalik', 'name' => 'Atıştırmalık', 'icon' => '🍫']
                    ];
                    break;
                    
                case 'giyim-ayakkabi':
                    $subcategories = [
                        ['slug' => 'erkek', 'name' => 'Erkek Giyim', 'icon' => '👔'],
                        ['slug' => 'kadin', 'name' => 'Kadın Giyim', 'icon' => '👗'],
                        ['slug' => 'cocuk', 'name' => 'Çocuk Giyim', 'icon' => '👶'],
                        ['slug' => 'ayakkabi', 'name' => 'Ayakkabı', 'icon' => '👟'],
                        ['slug' => 'spor', 'name' => 'Spor Giyim', 'icon' => '🎽'],
                        ['slug' => 'aksesuar', 'name' => 'Aksesuar', 'icon' => '🕶️']
                    ];
                    break;
                    
                case 'anne-bebek':
                    $subcategories = [
                        ['slug' => 'mama', 'name' => 'Bebek Beslenme', 'icon' => '🍼'],
                        ['slug' => 'bakim', 'name' => 'Bebek Bakım', 'icon' => '🧴'],
                        ['slug' => 'giyim', 'name' => 'Bebek Giyim', 'icon' => '👕'],
                        ['slug' => 'oyuncak', 'name' => 'Oyuncaklar', 'icon' => '🧸'],
                        ['slug' => 'guvenlik', 'name' => 'Güvenlik', 'icon' => '🚼'],
                        ['slug' => 'hamile', 'name' => 'Hamile Ürünleri', 'icon' => '🤰']
                    ];
                    break;
                    
                default:
                    $subcategories = [
                        ['slug' => 'urunler', 'name' => 'Tüm Ürünler', 'icon' => '📦'],
                        ['slug' => 'yeni', 'name' => 'Yeni Ürünler', 'icon' => '🆕'],
                        ['slug' => 'populer', 'name' => 'Popüler', 'icon' => '🔥'],
                        ['slug' => 'indirim', 'name' => 'İndirimli', 'icon' => '💸']
                    ];
            }
            
            foreach($subcategories as $subcat):
            ?>
            <a href="subcategory.php?cat=<?php echo $category_slug; ?>&sub=<?php echo $subcat['slug']; ?>" class="subcategory-card">
                <div class="subcategory-icon"><?php echo $subcat['icon']; ?></div>
                <h3><?php echo $subcat['name']; ?></h3>
                <p>5000+ ürün</p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>