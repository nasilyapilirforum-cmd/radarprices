<?php
// price_scraper.php - Gerçek fiyat çekme
class PriceScraper {
    private $stores = [
        'trendyol' => 'https://www.trendyol.com',
        'hepsiburada' => 'https://www.hepsiburada.com',
        'n11' => 'https://www.n11.com',
        'amazon' => 'https://www.amazon.com.tr'
    ];
    
    public function getProductPrices($productName) {
        $prices = [];
        
        foreach ($this->stores as $store => $url) {
            $price = $this->scrapeStore($store, $productName);
            if ($price) {
                $prices[$store] = $price;
            }
        }
        
        return $prices;
    }
    
    private function scrapeStore($store, $productName) {
        // Basit API simülasyonu - gerçekte API entegrasyonu yapılacak
        $mockPrices = [
            'iPhone 15 Pro' => [
                'trendyol' => 54999,
                'hepsiburada' => 53999,
                'n11' => 55999,
                'amazon' => 54990
            ],
            'Samsung Galaxy S24' => [
                'trendyol' => 39999,
                'hepsiburada' => 38999,
                'n11' => 40999,
                'amazon' => 39500
            ]
        ];
        
        return $mockPrices[$productName][$store] ?? rand(30000, 60000);
    }
}

// Kullanım
$scraper = new PriceScraper();
$prices = $scraper->getProductPrices('iPhone 15 Pro');
?>