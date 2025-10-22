<?php
// includes/geo.php
class PriceRadar {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function detectUserCountry() {
        // Cloudflare ülke tespiti
        if(isset($_SERVER['HTTP_CF_IPCOUNTRY']) && strlen($_SERVER['HTTP_CF_IPCOUNTRY']) == 2) {
            return strtoupper($_SERVER['HTTP_CF_IPCOUNTRY']);
        }
        
        // IPAPI fallback
        $ip = $_SERVER['REMOTE_ADDR'];
        if($ip === '127.0.0.1') $ip = '8.8.8.8'; // Localhost test
        
        try {
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode");
            $data = json_decode($response, true);
            return $data['countryCode'] ?? 'TR';
        } catch(Exception $e) {
            return 'TR';
        }
    }
    
    public function getCurrencySettings($country_code) {
        $currencies = [
            'TR' => ['TRY', '₺', 'TL', 'Türk Lirası'],
            'US' => ['USD', '$', 'USD', 'US Dollar'],
            'GB' => ['GBP', '£', 'GBP', 'British Pound'],
            'DE' => ['EUR', '€', 'EUR', 'Euro'],
            'FR' => ['EUR', '€', 'EUR', 'Euro'],
            'SA' => ['SAR', '﷼', 'SAR', 'Saudi Riyal'],
            'AE' => ['AED', 'د.إ', 'AED', 'UAE Dirham']
        ];
        
        return $currencies[$country_code] ?? $currencies['TR'];
    }
    
    public function convertPrice($price_try, $target_currency) {
        // Gerçek döviz kuru API (TL -> Hedef para)
        $exchange_rates = [
            'TRY' => 1,
            'USD' => 0.033,
            'EUR' => 0.030,
            'GBP' => 0.026,
            'SAR' => 0.12,
            'AED' => 0.12
        ];
        
        $rate = $exchange_rates[$target_currency] ?? 1;
        return $price_try * $rate;
    }
    
    public function formatPrice($price, $currency) {
        $settings = $this->getCurrencySettings($currency);
        return $settings[1] . ' ' . number_format($price, 2);
    }
    
    // TARİH OTOMASYONU
    public function getCurrentYear() {
        return date('Y');
    }
    
    public function autoUpdateDates() {
        $currentYear = $this->getCurrentYear();
        echo "<script>
            // Title güncelleme
            document.title = document.title.replace(/\\[\\d{4}\\]/, '[$currentYear]');
            // Sayfa içindeki tarihleri güncelle
            document.querySelectorAll('[data-year]').forEach(el => {
                el.textContent = '$currentYear';
            });
        </script>";
    }
}
?>