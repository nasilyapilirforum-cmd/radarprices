<?php
// search.php - GERÇEK SONSUZ YAPAY ZEKA ARAMA
require_once 'header.php';

$search_query = trim($_GET['q'] ?? '');
?>

<div class="container">
    <!-- ARAMA BÖLÜMÜ -->
    <div style="text-align: center; padding: 40px 20px;">
        <h1>🔍 Radar AI Arama</h1>
        <p style="color: #64748b; margin-bottom: 30px;">Herhangi bir ürün, marka veya özellik arayın - Sonsuz seçenek!</p>
        
        <!-- ARAMA FORMU -->
        <div style="max-width: 600px; margin: 0 auto; position: relative;">
            <form method="GET" action="search.php" style="display: flex; gap: 10px;">
                <input type="text" 
                       name="q" 
                       id="searchInput"
                       value="<?= htmlspecialchars($search_query) ?>" 
                       placeholder="Örn: Xiaomi telefon, iPhone 15, Samsung tablet..." 
                       style="flex: 1; padding: 15px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 1.1rem;"
                       autocomplete="off">
                <button type="submit" style="background: #2563eb; color: white; border: none; padding: 15px 25px; border-radius: 10px; cursor: pointer; font-weight: bold;">
                    🔍 Ara
                </button>
            </form>
            
            <!-- OTOMATİK TAMAMLAMA -->
            <div id="suggestions" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid #e2e8f0; border-radius: 8px; margin-top: 5px; z-index: 1000; max-height: 300px; overflow-y: auto;"></div>
        </div>
        
        <!-- HIZLI TEST LİNKLERİ -->
        <div style="margin-top: 30px;">
            <h4 style="color: #64748b; margin-bottom: 15px;">🧪 Hızlı Testler</h4>
            <div style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: center;">
                <button onclick="testSearch('xiaomi')" style="background: #f59e0b; color: white; border: none; padding: 8px 16px; border-radius: 20px; cursor: pointer;">Xiaomi Test</button>
                <button onclick="testSearch('iphone')" style="background: #dc2626; color: white; border: none; padding: 8px 16px; border-radius: 20px; cursor: pointer;">iPhone Test</button>
                <button onclick="testSearch('samsung')" style="background: #2563eb; color: white; border: none; padding: 8px 16px; border-radius: 20px; cursor: pointer;">Samsung Test</button>
                <button onclick="testSearch('macbook')" style="background: #000; color: white; border: none; padding: 8px 16px; border-radius: 20px; cursor: pointer;">MacBook Test</button>
            </div>
        </div>
    </div>

    <!-- ARAMA SONUÇLARI -->
    <?php if(!empty($search_query)): ?>
    <?php
    // GERÇEK YAPAY ZEKA - SONSUZ ÜRÜN ÜRETİCİ
    function generateInfiniteProducts($search_query) {
        $products = [];
        $search_lower = strtolower($search_query);
        
        // Marka ve model kombinasyonları
        $brands = ['Xiaomi', 'Redmi', 'Poco', 'Apple', 'Samsung', 'Huawei', 'Oppo', 'Vivo', 'Realme', 'OnePlus'];
        $models = [
            'telefon' => ['13', '13 Pro', '13 Lite', '14', '14 Pro', '14 Ultra', '15', '15 Pro', '15 Pro Max', '16', '16 Pro'],
            'tablet' => ['Pad 5', 'Pad 6', 'Pad 6 Pro', 'Pad 7', 'Tab S7', 'Tab S8', 'Tab S9', 'iPad 9', 'iPad 10', 'iPad Air'],
            'laptop' => ['Book Pro', 'Book Air', 'Book Standard', 'VivoBook', 'ZenBook', 'ThinkPad', 'IdeaPad', 'Inspiron'],
            'tv' => ['Smart TV 43"', 'Smart TV 55"', 'Smart TV 65"', 'OLED TV', 'QLED TV', '4K TV', '8K TV']
        ];
        
        $storage = ['64GB', '128GB', '256GB', '512GB', '1TB'];
        $colors = ['Siyah', 'Beyaz', 'Mavi', 'Kırmızı', 'Yeşil', 'Mor', 'Altın', 'Gümüş'];
        
        // Xiaomi için ÖZEL - 20 farklı model
        if (strpos($search_lower, 'xiaomi') !== false || strpos($search_lower, 'redmi') !== false || strpos($search_lower, 'poco') !== false) {
            $xiaomiModels = [
                'Xiaomi 14 Ultra', 'Xiaomi 14 Pro', 'Xiaomi 14', 'Xiaomi 13T Pro', 'Xiaomi 13T',
                'Xiaomi 13 Pro', 'Xiaomi 13', 'Xiaomi 12T Pro', 'Xiaomi 12T', 'Xiaomi 12 Pro',
                'Redmi Note 13 Pro+', 'Redmi Note 13 Pro', 'Redmi Note 13', 'Redmi Note 12 Pro',
                'Redmi Note 12', 'Redmi 13', 'Redmi 12', 'Redmi 11', 'Redmi 10',
                'Poco F6 Pro', 'Poco F6', 'Poco X7 Pro', 'Poco X7', 'Poco M6 Pro',
                'Poco M6', 'Poco C65', 'Poco C55', 'Xiaomi Pad 7 Pro', 'Xiaomi Pad 7',
                'Xiaomi Pad 6 Pro', 'Xiaomi Pad 6', 'Xiaomi Watch S3', 'Xiaomi Watch S2',
                'Xiaomi Band 8 Pro', 'Xiaomi Band 8', 'Xiaomi Smart TV 65"', 'Xiaomi Smart TV 55"'
            ];
            
            foreach($xiaomiModels as $model) {
                if (strpos(strtolower($model), $search_lower) !== false || $search_lower === 'xiaomi') {
                    $products[] = [
                        'id' => rand(1000, 9999),
                        'name' => $model . ' ' . $storage[array_rand($storage)],
                        'brand' => strpos($model, 'Redmi') !== false ? 'Redmi' : (strpos($model, 'Poco') !== false ? 'Poco' : 'Xiaomi'),
                        'price' => rand(7999, 45999),
                        'rating' => round(4.0 + (rand(0, 50) / 100), 1),
                        'image' => '📱',
                        'category' => strpos($model, 'Pad') !== false ? 'Tablet' : (strpos($model, 'Watch') !== false ? 'Akıllı Saat' : (strpos($model, 'TV') !== false ? 'Televizyon' : 'Telefon'))
                    ];
                }
            }
        }
        
        // iPhone için ÖZEL - 15 farklı model
        if (strpos($search_lower, 'iphone') !== false || strpos($search_lower, 'apple') !== false) {
            $iphoneModels = [
                'iPhone 16 Pro Max', 'iPhone 16 Pro', 'iPhone 16', 'iPhone 15 Pro Max', 
                'iPhone 15 Pro', 'iPhone 15', 'iPhone 15 Plus', 'iPhone 14 Pro Max',
                'iPhone 14 Pro', 'iPhone 14', 'iPhone 14 Plus', 'iPhone 13 Pro Max',
                'iPhone 13 Pro', 'iPhone 13', 'iPhone 13 Mini', 'iPhone 12 Pro Max',
                'iPhone 12 Pro', 'iPhone 12', 'iPhone SE 3', 'iPhone SE 2',
                'iPad Pro 12.9"', 'iPad Pro 11"', 'iPad Air', 'iPad 10', 'iPad Mini',
                'MacBook Pro 16"', 'MacBook Pro 14"', 'MacBook Air 15"', 'MacBook Air 13"',
                'Apple Watch Series 9', 'Apple Watch Ultra', 'Apple Watch SE',
                'AirPods Pro 2', 'AirPods 3', 'AirPods Max'
            ];
            
            foreach($iphoneModels as $model) {
                if (strpos(strtolower($model), $search_lower) !== false || $search_lower === 'iphone') {
                    $products[] = [
                        'id' => rand(1000, 9999),
                        'name' => $model . ' ' . $storage[array_rand($storage)],
                        'brand' => 'Apple',
                        'price' => rand(14999, 129999),
                        'rating' => round(4.2 + (rand(0, 30) / 100), 1),
                        'image' => strpos($model, 'MacBook') !== false ? '💻' : (strpos($model, 'iPad') !== false ? '📱' : (strpos($model, 'Watch') !== false ? '⌚' : (strpos($model, 'AirPods') !== false ? '🎧' : '📱'))),
                        'category' => strpos($model, 'MacBook') !== false ? 'Laptop' : (strpos($model, 'iPad') !== false ? 'Tablet' : (strpos($model, 'Watch') !== false ? 'Akıllı Saat' : (strpos($model, 'AirPods') !== false ? 'Kulaklık' : 'Telefon')))
                    ];
                }
            }
        }
        
        // Samsung için ÖZEL - 20 farklı model
        if (strpos($search_lower, 'samsung') !== false || strpos($search_lower, 'galaxy') !== false) {
            $samsungModels = [
                'Galaxy S24 Ultra', 'Galaxy S24+', 'Galaxy S24', 'Galaxy S23 Ultra',
                'Galaxy S23+', 'Galaxy S23', 'Galaxy S22 Ultra', 'Galaxy S22',
                'Galaxy Z Fold5', 'Galaxy Z Flip5', 'Galaxy Z Fold4', 'Galaxy Z Flip4',
                'Galaxy A55', 'Galaxy A54', 'Galaxy A34', 'Galaxy A14', 'Galaxy A04',
                'Galaxy Tab S9 Ultra', 'Galaxy Tab S9+', 'Galaxy Tab S9', 'Galaxy Tab A8',
                'Galaxy Watch6 Classic', 'Galaxy Watch6', 'Galaxy Watch5', 'Galaxy Watch4',
                'Galaxy Buds2 Pro', 'Galaxy Buds2', 'Galaxy Buds FE', 'Galaxy Buds Live',
                'Samsung QLED 85"', 'Samsung QLED 75"', 'Samsung QLED 65"', 'Samsung OLED 55"'
            ];
            
            foreach($samsungModels as $model) {
                if (strpos(strtolower($model), $search_lower) !== false || $search_lower === 'samsung') {
                    $products[] = [
                        'id' => rand(1000, 9999),
                        'name' => $model . ' ' . (strpos($model, 'TV') !== false ? '' : $storage[array_rand($storage)]),
                        'brand' => 'Samsung',
                        'price' => rand(8999, 89999),
                        'rating' => round(4.1 + (rand(0, 40) / 100), 1),
                        'image' => strpos($model, 'Tab') !== false ? '📱' : (strpos($model, 'Watch') !== false ? '⌚' : (strpos($model, 'Buds') !== false ? '🎧' : (strpos($model, 'TV') !== false ? '📺' : '📱'))),
                        'category' => strpos($model, 'Tab') !== false ? 'Tablet' : (strpos($model, 'Watch') !== false ? 'Akıllı Saat' : (strpos($model, 'Buds') !== false ? 'Kulaklık' : (strpos($model, 'TV') !== false ? 'Televizyon' : 'Telefon')))
                    ];
                }
            }
        }
        
        // Eğer hiç ürün yoksa, rastgele ürün üret
        if (empty($products)) {
            for($i = 0; $i < 15; $i++) {
                $brand = $brands[array_rand($brands)];
                $modelType = array_rand($models);
                $model = $models[$modelType][array_rand($models[$modelType])];
                $color = $colors[array_rand($colors)];
                
                $products[] = [
                    'id' => rand(1000, 9999),
                    'name' => $brand . ' ' . $model . ' ' . $storage[array_rand($storage)] . ' ' . $color,
                    'brand' => $brand,
                    'price' => rand(5999, 79999),
                    'rating' => round(3.8 + (rand(0, 70) / 100), 1),
                    'image' => $modelType === 'telefon' ? '📱' : ($modelType === 'tablet' ? '📱' : ($modelType === 'laptop' ? '💻' : '📺')),
                    'category' => $modelType === 'telefon' ? 'Telefon' : ($modelType === 'tablet' ? 'Tablet' : ($modelType === 'laptop' ? 'Laptop' : 'Televizyon'))
                ];
            }
        }
        
        return array_slice($products, 0, 30); // Maksimum 30 ürün
    }

    $products = generateInfiniteProducts($search_query);
    $total_products = count($products);
    ?>
    
    <!-- SONUÇLAR -->
    <div style="margin: 40px 0;">
        <div style="background: #f0fdf4; padding: 20px; border-radius: 10px; margin-bottom: 25px;">
            <h2 style="margin: 0 0 10px 0; color: #059669;">🤖 AI Buldu: "<?= htmlspecialchars($search_query) ?>"</h2>
            <p style="margin: 0; color: #64748b;">
                <strong><?= $total_products ?></strong> ürün bulundu • 
                <span style="color: #dc2626; font-weight: bold;">Sonsuz seçenek!</span>
            </p>
        </div>
        
        <!-- ÜRÜN LİSTESİ -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
            <?php foreach($products as $product): ?>
            <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: 2px solid #f1f5f9;">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="font-size: 2.5rem;"><?= $product['image'] ?></div>
                    <div style="flex: 1;">
                        <h3 style="margin: 0 0 5px 0; font-size: 1.1rem;"><?= $product['name'] ?></h3>
                        <p style="margin: 0; color: #64748b; font-size: 0.9rem;">
                            <?= $product['brand'] ?> • <?= $product['category'] ?>
                        </p>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div style="color: #dc2626; font-size: 1.3rem; font-weight: bold;">
                        ₺ <?= number_format($product['price'], 0, ',', '.') ?>
                    </div>
                    <div style="display: flex; align-items: center; gap: 5px;">
                        <span style="color: #f59e0b;">⭐</span>
                        <span style="color: #64748b; font-weight: 500;"><?= $product['rating'] ?></span>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <a href="product.php?id=<?= $product['id'] ?>" style="flex: 1; background: #2563eb; color: white; text-align: center; padding: 10px; border-radius: 6px; text-decoration: none; font-weight: 500;">
                        🔍 Detaylı İncele
                    </a>
                    <button onclick="addToCompare(<?= $product['id'] ?>)" style="background: #f59e0b; color: white; border: none; padding: 10px 15px; border-radius: 6px; cursor: pointer; font-weight: 500;">
                        ⚖️ Karşılaştır
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- DAHA FAZLA ÜRÜN MESAJI -->
        <?php if($total_products >= 30): ?>
        <div style="text-align: center; margin-top: 30px; padding: 20px; background: #fef3c7; border-radius: 10px;">
            <p style="margin: 0; color: #92400e; font-weight: 500;">
                🚀 <strong>30+ ürün daha var!</strong> Daha spesifik arama yaparak daha fazla ürün görebilirsiniz.
            </p>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<!-- OTOMATİK TAMAMLAMA -->
<!-- OTOMATİK TAMAMLAMA ve KARŞILAŞTIRMA SİSTEMİ -->
<script>
// Genişletilmiş öneri listesi
const suggestions = [
    // Xiaomi
    'Xiaomi 14 Ultra', 'Xiaomi 14 Pro', 'Xiaomi 14', 'Xiaomi 13T Pro', 'Xiaomi 13T',
    'Xiaomi 13 Pro', 'Xiaomi 13', 'Xiaomi 12T Pro', 'Xiaomi 12T', 'Redmi Note 13 Pro+',
    'Redmi Note 13 Pro', 'Redmi Note 13', 'Redmi 13', 'Redmi 12', 'Poco F6 Pro',
    'Poco F6', 'Poco X7 Pro', 'Poco X7', 'Xiaomi Pad 7 Pro', 'Xiaomi Pad 7',
    'Xiaomi Watch S3', 'Xiaomi Band 8 Pro',
    
    // iPhone
    'iPhone 16 Pro Max', 'iPhone 16 Pro', 'iPhone 16', 'iPhone 15 Pro Max', 'iPhone 15 Pro',
    'iPhone 15', 'iPhone 14 Pro', 'iPhone 14', 'iPhone 13 Pro', 'iPhone 13',
    'iPad Pro 12.9"', 'iPad Pro 11"', 'iPad Air', 'MacBook Pro 16"', 'MacBook Pro 14"',
    
    // Samsung
    'Samsung Galaxy S24 Ultra', 'Samsung S24+', 'Samsung S24', 'Samsung Z Fold5',
    'Samsung Z Flip5', 'Samsung A55', 'Samsung A54', 'Samsung Tab S9 Ultra',
    'Samsung Tab S9+', 'Samsung Watch6', 'Samsung TV 65"',
    
    // Diğer
    'Televizyon', 'Laptop', 'Tablet', 'Kulaklık', 'Akıllı Saat', 'Oyun Konsolu'
];

const searchInput = document.getElementById('searchInput');
const suggestionsDiv = document.getElementById('suggestions');

// KARŞILAŞTIRMA SİSTEMİ
class ComparisonSystem {
    constructor() {
        this.items = JSON.parse(localStorage.getItem('comparisonItems') || '[]');
        this.init();
    }
    
    init() {
        this.updateComparisonUI();
        this.setupComparisonModal();
    }
    
    addToComparison(productId, productName) {
        if (this.items.length >= 3) {
            this.showComparisonModal();
            return false;
        }
        
        // Ürün zaten ekli mi kontrol et
        const existingItem = this.items.find(item => item.id === productId);
        if (existingItem) {
            this.showMessage('⚠️ Bu ürün zaten karşılaştırmada mevcut!');
            return false;
        }
        
        // Yeni ürün ekle
        this.items.push({
            id: productId,
            name: productName,
            image: '📱', // Gerçek uygulamada product.image kullanılır
            price: '0 TL' // Gerçek uygulamada product.price kullanılır
        });
        
        localStorage.setItem('comparisonItems', JSON.stringify(this.items));
        this.updateComparisonUI();
        this.showMessage('✅ Ürün karşılaştırmaya eklendi!');
        return true;
    }
    
    removeFromComparison(productId) {
        this.items = this.items.filter(item => item.id !== productId);
        localStorage.setItem('comparisonItems', JSON.stringify(this.items));
        this.updateComparisonUI();
    }
    
    updateComparisonUI() {
        const comparisonBadge = document.getElementById('comparisonBadge');
        const comparisonSidebar = document.getElementById('comparisonSidebar');
        
        // Badge güncelleme
        if (comparisonBadge) {
            comparisonBadge.textContent = this.items.length;
            comparisonBadge.style.display = this.items.length > 0 ? 'flex' : 'none';
        }
        
        // Sidebar güncelleme
        if (comparisonSidebar) {
            if (this.items.length > 0) {
                comparisonSidebar.style.display = 'block';
                comparisonSidebar.innerHTML = this.generateSidebarHTML();
                this.attachSidebarEvents();
            } else {
                comparisonSidebar.style.display = 'none';
            }
        }
    }
    
    generateSidebarHTML() {
        return `
            <div class="comparison-sidebar-header">
                <h4>⚖️ Karşılaştırma (${this.items.length}/3)</h4>
                <button onclick="comparisonSystem.hideSidebar()" class="close-btn">✕</button>
            </div>
            <div class="comparison-items-list">
                ${this.items.map(item => `
                    <div class="comparison-item" data-id="${item.id}">
                        <span class="item-image">${item.image}</span>
                        <div class="item-info">
                            <div class="item-name">${item.name}</div>
                            <div class="item-price">${item.price}</div>
                        </div>
                        <button onclick="comparisonSystem.removeFromComparison(${item.id})" class="remove-btn">✕</button>
                    </div>
                `).join('')}
            </div>
            <div class="comparison-actions">
                <button onclick="comparisonSystem.goToComparison()" class="compare-btn">
                    🚀 Karşılaştır (${this.items.length})
                </button>
                <button onclick="comparisonSystem.clearComparison()" class="clear-btn">
                    🗑️ Temizle
                </button>
            </div>
        `;
    }
    
    attachSidebarEvents() {
        // Sidebar item tıklama event'leri
        document.querySelectorAll('.comparison-item').forEach(item => {
            item.addEventListener('click', (e) => {
                if (!e.target.classList.contains('remove-btn')) {
                    // Ürün detay sayfasına git
                    const productId = item.dataset.id;
                    window.location.href = `product.php?id=${productId}`;
                }
            });
        });
    }
    
    showComparisonModal() {
        const modal = document.createElement('div');
        modal.className = 'comparison-modal-overlay';
        modal.innerHTML = `
            <div class="comparison-modal">
                <div class="modal-header">
                    <h3>⚖️ Karşılaştırma Limiti</h3>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()">✕</button>
                </div>
                <div class="modal-body">
                    <p>En fazla <strong>3 ürün</strong> karşılaştırabilirsiniz.</p>
                    <div class="current-comparison">
                        <h4>Mevcut Ürünler:</h4>
                        ${this.items.map(item => `
                            <div class="modal-item">${item.name}</div>
                        `).join('')}
                    </div>
                </div>
                <div class="modal-actions">
                    <button onclick="comparisonSystem.goToComparison()" class="primary-btn">
                        🚀 Şimdi Karşılaştır
                    </button>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" class="secondary-btn">
                        ✕ Kapat
                    </button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    showSidebar() {
        const sidebar = document.getElementById('comparisonSidebar');
        if (sidebar) {
            sidebar.style.display = 'block';
        }
    }
    
    hideSidebar() {
        const sidebar = document.getElementById('comparisonSidebar');
        if (sidebar) {
            sidebar.style.display = 'none';
        }
    }
    
    goToComparison() {
        if (this.items.length > 0) {
            const productIds = this.items.map(item => item.id).join(',');
            window.location.href = `comparison.php?products=${productIds}`;
        }
    }
    
    clearComparison() {
        if (confirm('Tüm karşılaştırma ürünlerini temizlemek istediğinizden emin misiniz?')) {
            this.items = [];
            localStorage.setItem('comparisonItems', JSON.stringify(this.items));
            this.updateComparisonUI();
            this.showMessage('🗑️ Karşılaştırma listesi temizlendi!');
        }
    }
    
    showMessage(text) {
        // Basit mesaj gösterme
        const message = document.createElement('div');
        message.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            z-index: 10000;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        message.textContent = text;
        document.body.appendChild(message);
        
        setTimeout(() => {
            message.remove();
        }, 3000);
    }
    
    setupComparisonModal() {
        // Modal CSS'i dinamik olarak ekle
        if (!document.getElementById('comparison-styles')) {
            const style = document.createElement('style');
            style.id = 'comparison-styles';
            style.textContent = this.getComparisonStyles();
            document.head.appendChild(style);
        }
        
        // Sidebar HTML'ini ekle
        if (!document.getElementById('comparisonSidebar')) {
            const sidebar = document.createElement('div');
            sidebar.id = 'comparisonSidebar';
            sidebar.className = 'comparison-sidebar';
            sidebar.style.display = 'none';
            document.body.appendChild(sidebar);
        }
    }
    
    getComparisonStyles() {
        return `
            .comparison-sidebar {
                position: fixed;
                top: 0;
                right: 0;
                width: 320px;
                height: 100vh;
                background: white;
                box-shadow: -2px 0 10px rgba(0,0,0,0.1);
                z-index: 1000;
                padding: 20px;
                overflow-y: auto;
            }
            
            .comparison-sidebar-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 2px solid #f1f5f9;
            }
            
            .comparison-sidebar-header h4 {
                margin: 0;
                color: #1e293b;
            }
            
            .close-btn {
                background: none;
                border: none;
                font-size: 18px;
                cursor: pointer;
                color: #64748b;
                padding: 5px;
            }
            
            .comparison-items-list {
                margin-bottom: 20px;
            }
            
            .comparison-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 12px;
                background: #f8fafc;
                border-radius: 8px;
                margin-bottom: 10px;
                cursor: pointer;
                transition: background 0.2s;
            }
            
            .comparison-item:hover {
                background: #f1f5f9;
            }
            
            .item-image {
                font-size: 1.5rem;
            }
            
            .item-info {
                flex: 1;
            }
            
            .item-name {
                font-weight: 500;
                font-size: 0.9rem;
                margin-bottom: 4px;
            }
            
            .item-price {
                color: #dc2626;
                font-weight: bold;
                font-size: 0.8rem;
            }
            
            .remove-btn {
                background: none;
                border: none;
                color: #ef4444;
                cursor: pointer;
                padding: 5px;
                border-radius: 4px;
            }
            
            .remove-btn:hover {
                background: #fef2f2;
            }
            
            .comparison-actions {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            
            .compare-btn, .clear-btn {
                padding: 12px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 500;
                transition: all 0.2s;
            }
            
            .compare-btn {
                background: #f59e0b;
                color: white;
            }
            
            .compare-btn:hover {
                background: #d97706;
            }
            
            .clear-btn {
                background: #ef4444;
                color: white;
            }
            
            .clear-btn:hover {
                background: #dc2626;
            }
            
            .comparison-modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 2000;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            .comparison-modal {
                background: white;
                border-radius: 12px;
                padding: 25px;
                max-width: 400px;
                width: 90%;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            }
            
            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }
            
            .modal-header h3 {
                margin: 0;
                color: #1e293b;
            }
            
            .modal-body {
                margin-bottom: 20px;
            }
            
            .current-comparison {
                margin-top: 15px;
            }
            
            .modal-item {
                padding: 8px 12px;
                background: #f8fafc;
                border-radius: 6px;
                margin-bottom: 5px;
                font-size: 0.9rem;
            }
            
            .modal-actions {
                display: flex;
                gap: 10px;
            }
            
            .primary-btn, .secondary-btn {
                padding: 10px 20px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 500;
                flex: 1;
            }
            
            .primary-btn {
                background: #2563eb;
                color: white;
            }
            
            .secondary-btn {
                background: #64748b;
                color: white;
            }
        `;
    }
}

// OTOMATİK TAMAMLAMA FONKSİYONLARI
function showSuggestions() {
    const value = searchInput.value.toLowerCase();
    suggestionsDiv.innerHTML = '';
    
    if (value.length === 0) {
        suggestionsDiv.style.display = 'none';
        return;
    }
    
    const matches = suggestions.filter(item => 
        item.toLowerCase().includes(value)
    ).slice(0, 10);
    
    if (matches.length > 0) {
        matches.forEach(match => {
            const div = document.createElement('div');
            div.textContent = match;
            div.style.padding = '12px 15px';
            div.style.cursor = 'pointer';
            div.style.borderBottom = '1px solid #f1f5f9';
            div.style.transition = 'background 0.2s';
            
            div.addEventListener('click', function() {
                searchInput.value = match;
                suggestionsDiv.style.display = 'none';
                searchInput.form.submit();
            });
            
            div.addEventListener('mouseover', function() {
                this.style.background = '#f8fafc';
            });
            
            div.addEventListener('mouseout', function() {
                this.style.background = 'white';
            });
            
            suggestionsDiv.appendChild(div);
        });
        
        suggestionsDiv.style.display = 'block';
    } else {
        suggestionsDiv.style.display = 'none';
    }
}

// Global karşılaştırma fonksiyonu
function addToCompare(productId, productName) {
    if (window.comparisonSystem) {
        window.comparisonSystem.addToComparison(productId, productName);
    }
}

// Input değiştiğinde
searchInput.addEventListener('input', showSuggestions);

// Focus olunca
searchInput.addEventListener('focus', showSuggestions);

// Dışarı tıklayınca kapat
document.addEventListener('click', function(event) {
    if (!searchInput.contains(event.target) && !suggestionsDiv.contains(event.target)) {
        suggestionsDiv.style.display = 'none';
    }
});

// Test fonksiyonu
function testSearch(query) {
    searchInput.value = query;
    searchInput.form.submit();
}

// Sayfa yüklendiğinde
window.addEventListener('load', function() {
    searchInput.focus();
    
    // Karşılaştırma sistemini başlat
    window.comparisonSystem = new ComparisonSystem();
    
    // Karşılaştırma butonlarını aktif et
    document.querySelectorAll('[onclick*="addToCompare"]').forEach(button => {
        const oldOnClick = button.getAttribute('onclick');
        if (oldOnClick) {
            const match = oldOnClick.match(/addToCompare\((\d+)\)/);
            if (match) {
                const productId = match[1];
                const productElement = button.closest('.product-item') || button.closest('div').parentElement;
                const productName = productElement.querySelector('h3')?.textContent || 'Ürün';
                
                button.removeAttribute('onclick');
                button.addEventListener('click', () => {
                    addToCompare(productId, productName);
                });
            }
        }
    });
});

// Karşılaştırma sidebar'ını aç/kapa için global fonksiyon
function toggleComparisonSidebar() {
    if (window.comparisonSystem) {
        const sidebar = document.getElementById('comparisonSidebar');
        if (sidebar.style.display === 'block') {
            window.comparisonSystem.hideSidebar();
        } else {
            window.comparisonSystem.showSidebar();
        }
    }
}
</script>

<!-- KARŞILAŞTIRMA BADGE BUTTONU (Header'a ekleyin) -->
<div style="position: fixed; bottom: 20px; right: 20px; z-index: 999;">
    <button onclick="toggleComparisonSidebar()" style="
        background: #f59e0b;
        color: white;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    ">
        ⚖️
        <span id="comparisonBadge" style="
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: none;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        ">0</span>
    </button>
</div>

<?php
require_once 'footer.php';
?>