<?php
// ai/ProductAI.php - TAM KOD

class ProductAI {
    private $api_key;
    
    public function __construct() {
        echo "🎉 ProductAI sınıfı BAŞARIYLA OLUŞTURULDU!<br>";
        
        // Config dosyasını dene
        $config_path = __DIR__ . '/../config/ai_config.php';
        if (file_exists($config_path)) {
            require_once $config_path;
            $this->api_key = defined('OPENAI_API_KEY') ? OPENAI_API_KEY : 'API_ANAHTARI_YOK';
            echo "✅ Config dosyası yüklendi<br>";
        } else {
            $this->api_key = 'API_ANAHTARI_YOK';
            echo "⚠️ Config dosyası bulunamadı, test modu<br>";
        }
    }
    
    public function testMethod() {
        return "🤖 AI test metodu ÇALIŞIYOR! API Key: " . substr($this->api_key, 0, 10) . "...<br>";
    }
    
    public function analyzeProductComparison($products) {
        return "🤖 Ürün analizi yapılacak. " . count($products) . " ürün alındı.<br>";
    }
    
    public function generateProductDescription($product) {
        return "🤖 Ürün açıklaması oluşturulacak: " . $product['name'] . "<br>";
    }
}
?>