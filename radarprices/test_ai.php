<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<h2>🚀 AI Test - Sınıf Kontrolü</h2>";

// Dosya yolunu kontrol et
$ai_file_path = __DIR__ . '/ai/ProductAI.php';
echo "Dosya: " . $ai_file_path . "<br>";

if (file_exists($ai_file_path)) {
    echo "✅ Dosya var<br>";
    
    // Dosya boyutunu kontrol et
    $file_size = filesize($ai_file_path);
    echo "Dosya boyutu: " . $file_size . " byte<br>";
    
    // Dosya içeriğini oku (ilk 200 karakter)
    $file_content = file_get_contents($ai_file_path);
    echo "İlk 200 karakter: " . htmlspecialchars(substr($file_content, 0, 200)) . "<br>";
    
    // Dosyayı dahil et
    require_once $ai_file_path;
    echo "✅ Dosya dahil edildi<br>";
    
    // Sınıf var mı kontrol et
    if (class_exists('ProductAI')) {
        echo "✅ ProductAI sınıfı TANIMLI<br>";
        
        // Sınıfı başlat
        $ai = new ProductAI();
        echo $ai->testMethod();
        
        // Test metodları
        $test_products = [
            ['name' => 'Test Ürün 1', 'features' => 'Özellikler...', 'price' => '1000']
        ];
        echo $ai->analyzeProductComparison($test_products);
        echo $ai->generateProductDescription($test_products[0]);
        
    } else {
        echo "❌ ProductAI sınıfı TANIMLI DEĞİL<br>";
        
        // Mevcut sınıfları listele
        echo "<h3>Mevcut Sınıflar:</h3>";
        $defined_classes = get_declared_classes();
        foreach ($defined_classes as $class) {
            echo $class . "<br>";
        }
    }
    
} else {
    echo "❌ Dosya bulunamadı<br>";
}
?>