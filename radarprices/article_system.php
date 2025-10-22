<?php
// Makale oluşturma sistemi - Fiyat karşılaştırma odaklı
class ArticleGenerator {
    public function generatePriceComparisonArticle($productType, $products) {
        $currentYear = date('Y');
        
        $title = "En İyi $productType Karşılaştırması ve Fiyat Analizi [$currentYear]";
        
        $content = "<h1>$title</h1>";
        $content .= "<div class='price-comparison-intro'>";
        $content .= "<p>Bu rehberde, 2025 yılında piyasadaki en popüler $productType modellerini detaylı bir şekilde karşılaştıracağız. Fiyat-performans analizi, özellik karşılaştırması ve satın alma tavsiyeleri...</p>";
        $content .= "</div>";
        
        // Fiyat karşılaştırma tablosu
        $content .= $this->generatePriceTable($products);
        
        // Özellik karşılaştırması
        $content .= $this->generateFeatureComparison($products);
        
        // Satın alma tavsiyeleri
        $content .= $this->generateBuyingAdvice($products);
        
        return [
            'title' => $title,
            'content' => $content,
            'status' => 'pending', // Admin onayı bekliyor
            'type' => 'price_comparison'
        ];
    }
    
    private function generatePriceTable($products) {
        $html = "<h2>🏷️ Fiyat Karşılaştırması</h2>";
        $html .= "<div class='price-table'>";
        $html .= "<table><thead><tr><th>Mağaza</th>";
        
        foreach ($products as $product) {
            $html .= "<th>{$product['name']}</th>";
        }
        
        $html .= "</tr></thead><tbody>";
        
        $stores = ['Trendyol', 'Hepsiburada', 'n11', 'Amazon TR'];
        foreach ($stores as $store) {
            $html .= "<tr><td><strong>$store</strong></td>";
            foreach ($products as $product) {
                $price = $this->getProductPrice($product['id'], $store);
                $html .= "<td>{$price} TL</td>";
            }
            $html .= "</tr>";
        }
        
        $html .= "</tbody></table></div>";
        return $html;
    }
}
?>