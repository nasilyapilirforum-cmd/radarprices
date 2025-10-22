<?php
// 🤖 AKILLI MAKALE ÜRETİCİ
class AIArticleGenerator {
    
    // 🎯 KONUYA GÖRE MAKALE OLUŞTUR
    public function generateArticle($topic, $selected_products = []) {
        $articles = [
            'Kamera Telefonları Nasıl Seçilir?' => $this->generateCameraPhoneArticle(),
            'Oyun Telefonları Karşılaştırma' => $this->generateGamingPhoneArticle(),
            'Tablet Alırken Nelere Dikkat Edilmeli?' => $this->generateTabletArticle(),
            'Laptop Seçimi Rehberi' => $this->generateLaptopArticle(),
            'Televizyon Alırken Dikkat Edilecekler' => $this->generateTVArticle()
        ];
        
        return $articles[$topic] ?? $this->generateDefaultArticle($topic);
    }
    
    // 📸 KAMERA TELEFONU MAKALESİ
    private function generateCameraPhoneArticle() {
        return [
            'title' => '📸 Kamera Telefonları Nasıl Seçilir? [2025] - Profesyonel Rehber',
            'content' => $this->getCameraPhoneContent(),
            'status' => 'pending',
            'seo_keywords' => 'kamera telefonu, en iyi kamera, fotoğraf kalitesi, 2025 telefon'
        ];
    }
    
    private function getCameraPhoneContent() {
        return [
            'introduction' => '2025 yılında kamera performansı en önemli satın alma kriterlerinden biri haline geldi. İşte kamera telefonu seçerken dikkat etmeniz gereken 5 altın kural:',
            
            'rules' => [
                '🎯 Sensör Kalitesi: 50MP ve üzeri sensörler gerçekten fark yaratıyor mu?',
                '⚡ İşlemci Gücü: Görüntü işleme çipi fotoğraf kalitesini nasıl etkiler?',
                '📷 Lens Çeşitliliği: Ultra geniş, telefoto ve makro lensler ne işe yarar?',
                '🌙 Gece Modu: Düşük ışıkta çekim performansı nasıl değerlendirilmeli?',
                '🎬 Video Kalitesi: 8K video çekim gerçekten gerekli mi?'
            ],
            
            'comparison' => [
                'iPhone 16 Pro Max' => '48MP ana kamera + LiDAR sensörü',
                'Samsung Galaxy S25 Ultra' => '200MP ana kamera + 10x optik zoom',
                'Google Pixel 8 Pro' => 'AI destekli görüntü işleme',
                'Xiaomi 14 Pro' => 'Leica işbirliği ile geliştirilmiş lensler'
            ],
            
            'conclusion' => 'Kamera telefonu seçerken sadece megapiksel sayısına değil, sensör kalitesi, lens performansı ve yazılım optimizasyonuna da dikkat edin.'
        ];
    }
    
    // 🎮 OYUN TELEFONU MAKALESİ
    private function generateGamingPhoneArticle() {
        return [
            'title' => '🎮 Oyun Telefonları Karşılaştırma [2025] - En İyi Performans Rehberi',
            'content' => $this->getGamingPhoneContent(),
            'status' => 'pending',
            'seo_keywords' => 'oyun telefonu, gaming phone, yüksek performans, 2025 oyun'
        ];
    }
    
    private function getGamingPhoneContent() {
        return [
            'introduction' => 'Mobil oyun dünyası hızla büyüyor ve oyun telefonları bu alanda kritik öneme sahip. İşte 2025 yılında oyun telefonu seçerken dikkat etmeniz gerekenler:',
            
            'rules' => [
                '⚡ İşlemci Performansı: Snapdragon 8 Gen 4 vs A18 Pro - Hangisi daha iyi?',
                '🎮 Soğutma Sistemi: Pasif ve aktif soğutma sistemleri performansı nasıl etkiler?',
                '📱 Ekran Özellikleri: 144Hz yenileme hızı ve dokunmatik tepkisi neden önemli?',
                '🔋 Pil Ömrü: Yoğun oyun seanslarında pil dayanıklılığı nasıl olmalı?',
                '🎧 Ses Kalitesi: Stereo hoparlörler ve 3.5mm kulaklık girişi değerlendirmesi'
            ],
            
            'comparison' => [
                'Asus ROG Phone 8' => 'Özel soğutma sistemi ve 165Hz ekran',
                'Xiaomi Black Shark 6' => 'Manyetik tetikleyiciler ve 720Hz dokunmatik örnekleme',
                'Nubia Red Magic 9' => 'İç vantilatörlü aktif soğutma sistemi',
                'iPhone 16 Pro' => 'A18 Pro işlemci ve optimize edilmiş iOS oyunları'
            ],
            
            'conclusion' => 'Oyun telefonu seçerken sadece işlemci gücüne değil, soğutma performansına ve ekran kalitesine de dikkat edin.'
        ];
    }
    
    // 📟 TABLET MAKALESİ
    private function generateTabletArticle() {
        return [
            'title' => '📟 Tablet Alırken Nelere Dikkat Edilmeli? [2025] - Kapsamlı Rehber',
            'content' => $this->getTabletContent(),
            'status' => 'pending', 
            'seo_keywords' => 'tablet, iPad, android tablet, 2025 tablet'
        ];
    }
    
    // 💻 LAPTOP MAKALESİ
    private function generateLaptopArticle() {
        return [
            'title' => '💻 Laptop Seçimi Rehberi [2025] - İş, Oyun ve Günlük Kullanım',
            'content' => $this->getLaptopContent(),
            'status' => 'pending',
            'seo_keywords' => 'laptop, notebook, bilgisayar, 2025 laptop'
        ];
    }
    
    // 📺 TV MAKALESİ
    private function generateTVArticle() {
        return [
            'title' => '📺 Televizyon Alırken Dikkat Edilecekler [2025] - Smart TV Rehberi',
            'content' => $this->getTVContent(),
            'status' => 'pending',
            'seo_keywords' => 'televizyon, smart tv, 4K, 8K, 2025 tv'
        ];
    }
    
    // 🔄 VARSayILAN MAKALE
    private function generateDefaultArticle($topic) {
        return [
            'title' => $topic . ' [2025] - Detaylı Karşılaştırma Rehberi',
            'content' => [
                'introduction' => 'Bu rehberde ' . $topic . ' hakkında detaylı bilgiler bulacaksınız.',
                'rules' => [
                    '✅ İhtiyaç analizi yapın',
                    '✅ Bütçenizi belirleyin', 
                    '✅ Teknik özellikleri karşılaştırın',
                    '✅ Kullanıcı yorumlarını okuyun',
                    '✅ Garanti ve servis koşullarını kontrol edin'
                ],
                'comparison' => [],
                'conclusion' => 'Doğru seçim için detaylı araştırma yapmanızı öneririz.'
            ],
            'status' => 'pending',
            'seo_keywords' => strtolower($topic) . ', 2025, karşılaştırma, rehber'
        ];
    }
    
    // 💾 MAKALEYİ VERİTABANINA KAYDET
    public function saveArticleToDatabase($article_data, $user_id = null) {
        // Bu kısım veritabanı kaydı için - şimdilik dosyaya kaydedelim
        $filename = 'pending_articles/' . time() . '_' . uniqid() . '.json';
        file_put_contents($filename, json_encode($article_data, JSON_PRETTY_PRINT));
        return $filename;
    }
}
?>