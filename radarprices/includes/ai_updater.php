<?php
// 🤖 AKILLI ÜRÜN GÜNCELLEYİCİ
class ProductAIUpdater {
    private $api_key;
    private $update_frequency = 7; // 7 günde bir
    
    public function __construct($api_key = null) {
        $this->api_key = $api_key;
    }
    
    // 🎯 MARKALARI TARA VE GÜNCELLE
    public function updatePhoneDatabase() {
        $brands = ['Apple', 'Samsung', 'Xiaomi', 'Google', 'OnePlus', 'Realme', 'Oppo', 'Vivo', 'Huawei', 'Honor'];
        $updated_products = [];
        
        foreach ($brands as $brand) {
            $new_models = $this->fetchLatestModels($brand);
            $updated_products = array_merge($updated_products, $new_models);
        }
        
        $this->cleanOldProducts($updated_products);
        return $updated_products;
    }
    
    // 🔍 EN YENİ MODELLERİ GETİR
    private function fetchLatestModels($brand) {
        // Simüle edilmiş AI veri çekme
        $current_year = date('Y');
        $models = [];
        
        switch($brand) {
            case 'Apple':
                $models = [
                    "iPhone 16 Pro Max" => $this->generatePhoneSpecs('Apple', 'A18 Pro', '8 GB', '256 GB/512 GB/1 TB'),
                    "iPhone 16 Pro" => $this->generatePhoneSpecs('Apple', 'A18 Pro', '8 GB', '128 GB/256 GB/512 GB'),
                    "iPhone 16" => $this->generatePhoneSpecs('Apple', 'A18', '8 GB', '128 GB/256 GB/512 GB'),
                    "iPhone 15 Pro Max" => $this->generatePhoneSpecs('Apple', 'A17 Pro', '8 GB', '256 GB/512 GB/1 TB')
                ];
                break;
                
            case 'Samsung':
                $models = [
                    "Samsung Galaxy S25 Ultra" => $this->generatePhoneSpecs('Samsung', 'Snapdragon 8 Gen 4', '12 GB', '256 GB/512 GB/1 TB'),
                    "Samsung Galaxy S25+" => $this->generatePhoneSpecs('Samsung', 'Snapdragon 8 Gen 4', '12 GB', '256 GB/512 GB'),
                    "Samsung Galaxy S25" => $this->generatePhoneSpecs('Samsung', 'Snapdragon 8 Gen 4', '8 GB', '128 GB/256 GB'),
                    "Samsung Galaxy Z Fold6" => $this->generatePhoneSpecs('Samsung', 'Snapdragon 8 Gen 3', '12 GB', '256 GB/512 GB/1 TB')
                ];
                break;
                
            case 'Xiaomi':
                $models = [
                    "Xiaomi 15 Pro" => $this->generatePhoneSpecs('Xiaomi', 'Snapdragon 8 Gen 4', '12 GB', '256 GB/512 GB/1 TB'),
                    "Xiaomi 15" => $this->generatePhoneSpecs('Xiaomi', 'Snapdragon 8 Gen 4', '8 GB', '128 GB/256 GB'),
                    "Xiaomi Redmi Note 14 Pro+" => $this->generatePhoneSpecs('Xiaomi', 'Dimensity 7300', '8 GB', '128 GB/256 GB')
                ];
                break;
        }
        
        return $models;
    }
    
    // 📊 TEKNİK ÖZELLİK OLUŞTUR
    private function generatePhoneSpecs($brand, $cpu, $ram, $storage) {
        $specs = [
            'İşlemci' => $cpu,
            'RAM' => $ram,
            'Depolama' => $storage,
            'Ekran' => $this->generateScreenSpecs($brand),
            'Kamera' => $this->generateCameraSpecs($brand),
            'Pil' => $this->generateBatterySpecs($brand),
            'İşletim Sistemi' => $this->getOS($brand)
        ];
        
        return ['brand' => $brand, 'specs' => $specs];
    }
    
    private function generateScreenSpecs($brand) {
        $screens = [
            'Apple' => '6.9" Super Retina XDR ProMotion',
            'Samsung' => '6.8" Dynamic AMOLED 2X 120Hz',
            'Xiaomi' => '6.7" AMOLED 120Hz',
            'Google' => '6.3" OLED 120Hz'
        ];
        return $screens[$brand] ?? '6.7" AMOLED 120Hz';
    }
    
    private function generateCameraSpecs($brand) {
        $cameras = [
            'Apple' => '48 MP + 12 MP + 12 MP + LiDAR',
            'Samsung' => '200 MP + 50 MP + 12 MP + 10 MP',
            'Xiaomi' => '50 MP + 50 MP + 50 MP',
            'Google' => '50 MP + 48 MP + 48 MP'
        ];
        return $cameras[$brand] ?? '50 MP + 12 MP + 8 MP';
    }
    
    private function generateBatterySpecs($brand) {
        $batteries = [
            'Apple' => '4676 mAh',
            'Samsung' => '5000 mAh', 
            'Xiaomi' => '5000 mAh',
            'Google' => '5050 mAh'
        ];
        return $batteries[$brand] ?? '4500 mAh';
    }
    
    private function getOS($brand) {
        $os = [
            'Apple' => 'iOS 18',
            'Samsung' => 'Android 15',
            'Xiaomi' => 'Android 15',
            'Google' => 'Android 15'
        ];
        return $os[$brand] ?? 'Android 15';
    }
    
    // 🗑️ ESKİ ÜRÜNLERİ TEMİZLE
    private function cleanOldProducts($new_products) {
        $current_products = getPhoneDatabase();
        $current_names = array_keys($current_products);
        $new_names = array_keys($new_products);
        
        // 2 yıldan eski modelleri kaldır
        $current_year = date('Y');
        foreach ($current_names as $product_name) {
            if (!in_array($product_name, $new_names)) {
                $product_year = $this->extractYearFromProduct($product_name);
                if ($product_year && ($current_year - $product_year) >= 2) {
                    unset($current_products[$product_name]);
                }
            }
        }
        
        return $current_products;
    }
    
    private function extractYearFromProduct($product_name) {
        if (preg_match('/(\d{4})/', $product_name, $matches)) {
            return (int)$matches[1];
        }
        
        // Model numarasından yıl tahmini
        $year_patterns = [
            'iPhone 16' => 2024,
            'iPhone 15' => 2023,
            'Samsung Galaxy S25' => 2025,
            'Samsung Galaxy S24' => 2024,
            'Xiaomi 15' => 2024,
            'Xiaomi 14' => 2023
        ];
        
        foreach ($year_patterns as $pattern => $year) {
            if (strpos($product_name, $pattern) !== false) {
                return $year;
            }
        }
        
        return null;
    }
    
    // 📅 OTOMATİK GÜNCELLEME KONTROLÜ
    public function checkAndUpdate() {
        $last_update = $this->getLastUpdateTime();
        $current_time = time();
        
        if (($current_time - $last_update) >= ($this->update_frequency * 24 * 60 * 60)) {
            $this->updatePhoneDatabase();
            $this->setLastUpdateTime($current_time);
            return true;
        }
        
        return false;
    }
    
    private function getLastUpdateTime() {
        return file_exists('last_update.txt') ? (int)file_get_contents('last_update.txt') : 0;
    }
    
    private function setLastUpdateTime($time) {
        file_put_contents('last_update.txt', $time);
    }
}

// 🎯 GERÇEK ZAMANLI ARAMA İYİLEŞTİRMESİ
function enhanceSearch($query, $category_database) {
    $results = [];
    $query = strtolower(trim($query));
    
    // Arama terimlerini ayır
    $terms = explode(' ', $query);
    
    foreach ($category_database as $product_name => $product_data) {
        $score = 0;
        $product_lower = strtolower($product_name);
        $brand_lower = strtolower($product_data['brand']);
        
        // Tam eşleşme
        if ($product_lower === $query) {
            $score += 100;
        }
        
        // Marka eşleşmesi
        if ($brand_lower === $query) {
            $score += 80;
        }
        
        // Kelime eşleşmeleri
        foreach ($terms as $term) {
            if (strlen($term) < 2) continue;
            
            if (strpos($product_lower, $term) !== false) {
                $score += 10;
            }
            
            if (strpos($brand_lower, $term) !== false) {
                $score += 15;
            }
            
            // Model numarası eşleşmesi
            if (preg_match('/\d+/', $term) && preg_match('/' . $term . '/', $product_name)) {
                $score += 20;
            }
        }
        
        if ($score > 0) {
            $results[$product_name] = $score;
        }
    }
    
    // Skora göre sırala
    arsort($results);
    return array_keys($results);
}
?>