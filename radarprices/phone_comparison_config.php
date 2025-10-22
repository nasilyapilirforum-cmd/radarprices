<?php
// phone_comparison_config.php - Vodafone tarzı 3'lü karşılaştırma

function getPhoneDatabase() {
    return [
        [
            'id' => 1, 'name' => 'iPhone 15 Pro Max', 'brand' => 'Apple', 
            'display' => '6.7 inç Super Retina XDR', 'camera' => '48MP+12MP+12MP', 
            'processor' => 'A17 Pro', 'battery' => '4422mAh', 'ram' => '8GB', 
            'storage' => '256GB/512GB/1TB', 'os' => 'iOS 17', 'price' => '₺64.999',
            'image' => 'iphone15promax.jpg', 'rating' => 4.8
        ],
        [
            'id' => 2, 'name' => 'Samsung Galaxy S24 Ultra', 'brand' => 'Samsung', 
            'display' => '6.8 inç Dynamic AMOLED', 'camera' => '200MP+50MP+10MP+12MP', 
            'processor' => 'Snapdragon 8 Gen 3', 'battery' => '5000mAh', 'ram' => '12GB', 
            'storage' => '256GB/512GB/1TB', 'os' => 'Android 14', 'price' => '₺54.999',
            'image' => 's24ultra.jpg', 'rating' => 4.7
        ],
        [
            'id' => 3, 'name' => 'Xiaomi 14 Pro', 'brand' => 'Xiaomi', 
            'display' => '6.73 inç LTPO AMOLED', 'camera' => '50MP+50MP+50MP', 
            'processor' => 'Snapdragon 8 Gen 3', 'battery' => '4880mAh', 'ram' => '12GB', 
            'storage' => '256GB/512GB', 'os' => 'HyperOS', 'price' => '₺34.999',
            'image' => 'xiaomi14pro.jpg', 'rating' => 4.6
        ],
        [
            'id' => 4, 'name' => 'Google Pixel 8 Pro', 'brand' => 'Google', 
            'display' => '6.7 inç LTPO OLED', 'camera' => '50MP+48MP+48MP', 
            'processor' => 'Google Tensor G3', 'battery' => '5050mAh', 'ram' => '12GB', 
            'storage' => '128GB/256GB', 'os' => 'Android 14', 'price' => '₺42.999',
            'image' => 'pixel8pro.jpg', 'rating' => 4.5
        ]
    ];
}

function displayVodafoneStyleComparison($phone1_id, $phone2_id, $phone3_id) {
    $phones = getPhoneDatabase();
    $selected_phones = [];
    
    foreach ([$phone1_id, $phone2_id, $phone3_id] as $phone_id) {
        if ($phone_id > 0) {
            $phone = array_filter($phones, function($p) use ($phone_id) {
                return $p['id'] == $phone_id;
            });
            if (!empty($phone)) {
                $selected_phones[] = reset($phone);
            }
        }
    }
    
    if (empty($selected_phones)) return;
    
    echo '<div class="vodafone-comparison">';
    echo '<div class="comparison-header">';
    echo '<h2>📱 Telefon Karşılaştırma</h2>';
    echo '<p>Seçtiğiniz telefonları detaylı karşılaştırın</p>';
    echo '</div>';
    
    echo '<div class="phones-grid">';
    foreach ($selected_phones as $index => $phone) {
        echo '<div class="phone-column">';
        echo '<div class="phone-card">';
        echo '<div class="phone-image">';
        echo '<img src="images/' . $phone['image'] . '" alt="' . $phone['name'] . '" onerror="this.style.display=\'none\'">';
        echo '<div class="phone-emoji">📱</div>';
        echo '</div>';
        echo '<h3>' . $phone['brand'] . ' ' . $phone['name'] . '</h3>';
        echo '<div class="phone-price">' . $phone['price'] . '</div>';
        echo '<div class="phone-rating">★ ' . $phone['rating'] . '</div>';
        echo '</div>';
        
        // Özellikler
        echo '<div class="features-list">';
        echo '<div class="feature-item"><span>İşlemci:</span> ' . $phone['processor'] . '</div>';
        echo '<div class="feature-item"><span>Ekran:</span> ' . $phone['display'] . '</div>';
        echo '<div class="feature-item"><span>Kamera:</span> ' . $phone['camera'] . '</div>';
        echo '<div class="feature-item"><span>Pil:</span> ' . $phone['battery'] . '</div>';
        echo '<div class="feature-item"><span>RAM/Depolama:</span> ' . $phone['ram'] . ' / ' . $phone['storage'] . '</div>';
        echo '<div class="feature-item"><span>İşletim Sistemi:</span> ' . $phone['os'] . '</div>';
        echo '</div>';
        echo '</div>';
        
        // VS separator (son telefon hariç)
        if ($index < count($selected_phones) - 1) {
            echo '<div class="vs-separator"><div class="vs-circle">VS</div></div>';
        }
    }
    echo '</div>';
    echo '</div>';
}
?>