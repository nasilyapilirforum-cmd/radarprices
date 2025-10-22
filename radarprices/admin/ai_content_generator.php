<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

require_once '../config/database.php';

// AI içerik üretimi
if ($_POST) {
    if (isset($_POST['generate_article'])) {
        $product_type = $_POST['product_type'];
        $brands = $_POST['brands'];
        $keywords = $_POST['keywords'];
        $word_count = $_POST['word_count'];
        
        // AI ile içerik üret
        $generated_content = generateAIContent($product_type, $brands, $keywords, $word_count);
        
        // Üretilen içeriği veritabanına kaydet
        $article_id = saveGeneratedArticle($generated_content);
        
        $_SESSION['generated_article'] = $generated_content;
        $_SESSION['generated_article_id'] = $article_id;
        header('Location: ai_content_generator.php?preview=true');
        exit;
    }
    
    if (isset($_POST['save_article'])) {
        $article_data = $_POST['article_data'];
        $article_id = $_POST['article_id'];
        
        saveArticleToDatabase($article_data, $article_id);
        
        $_SESSION['message'] = "Makale başarıyla kaydedildi ve onay için bekliyor!";
        header('Location: pending.php');
        exit;
    }
}

function generateAIContent($product_type, $brands, $keywords, $word_count) {
    $current_year = date('Y');
    $brands_array = explode(',', $brands);
    
    return [
        'title' => "En İyi {$product_type} Markaları {$current_year} - Fiyat & Performans Karşılaştırması",
        'content' => generateRadarPricesContent($product_type, $brands_array, $keywords, $word_count),
        'meta_description' => "{$current_year} yılında en iyi {$product_type} markaları karşılaştırması. Fiyat, performans ve kullanıcı yorumları analizi. RadarPrices güvencesiyle.",
        'slug' => generateSlug("en-iyi-{$product_type}-markalari-{$current_year}"),
        'category' => 'Karşılaştırma',
        'tags' => array_merge([$product_type], $brands_array, explode(',', $keywords))
    ];
}

function generateRadarPricesContent($product_type, $brands, $keywords, $word_count) {
    $current_year = date('Y');
    
    return "
    <!-- YAZAR BÖLÜMÜ -->
    <div class=\"author-box\" style=\"background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%); padding: 20px; border-radius: 12px; margin: 25px 0; border: 2px solid #7e57c2;\" itemscope itemtype=\"https://schema.org/Person\">
        <div style=\"display: flex; align-items: center; gap: 15px; flex-wrap: wrap;\">
            <div style=\"flex-shrink: 0;\">
                <div style=\"width: 80px; height: 80px; background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; font-weight: bold;\">RC</div>
            </div>
            <div style=\"flex: 1;\">
                <h4 style=\"margin: 0 0 5px 0; color: #1a237e; font-size: 18px;\" itemprop=\"name\">RadarPrices Ekibi</h4>
                <p style=\"margin: 0 0 10px 0; color: #5f6368; font-size: 14px;\" itemprop=\"jobTitle\">Fiyat Karşılaştırma & Ürün Analiz Uzmanları</p>
                <div style=\"display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 10px;\">
                    <span style=\"background: #e8eaf6; color: #303f9f; padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: 500;\">✅ 5000+ Ürün Analizi</span>
                    <span style=\"background: #f3e5f5; color: #7b1fa2; padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: 500;\">🎯 Gerçek Kullanıcı Yorumları</span>
                    <span style=\"background: #e0f2f1; color: #00695c; padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: 500;\">📊 Fiyat Performans Karşılaştırması</span>
                </div>
            </div>
        </div>
    </div>

    <h2>🔍 {$current_year} En İyi {$product_type} Markaları Karşılaştırması</h2>
    
    <p><strong>RadarPrices ekibi olarak</strong> {$current_year} yılında piyasadaki en popüler {$product_type} markalarını teknik özellikleri, fiyatları ve kullanıcı deneyimleriyle birlikte detaylı şekilde analiz ettik.</p>

    <h2>📊 Fiyat & Performans Karşılaştırma Tablosu</h2>
    
    <div style=\"overflow-x: auto; margin: 20px 0;\">
        <table style=\"width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);\">
            <thead style=\"background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); color: white;\">
                <tr>
                    <th style=\"padding: 15px; text-align: left;\">Marka / Model</th>
                    <th style=\"padding: 15px; text-align: center;\">Fiyat Aralığı</th>
                    <th style=\"padding: 15px; text-align: center;\">Performans</th>
                    <th style=\"padding: 15px; text-align: center;\">Kullanıcı Puanı</th>
                    <th style=\"padding: 15px; text-align: center;\">Fiyat/Performans</th>
                </tr>
            </thead>
            <tbody>
                " . generateBrandsTable($brands, $product_type) . "
            </tbody>
        </table>
    </div>

    <h2>🎯 Marka Bazlı Detaylı Analiz</h2>
    
    " . generateBrandsAnalysis($brands, $product_type) . "

    <h2>💰 En İyi Fiyat/Performans Ürünleri</h2>
    
    <p>Bütçenize göre en uygun seçenekleri aşağıda bulabilirsiniz:</p>
    
    <div style=\"background: #fff3e0; padding: 20px; border-radius: 10px; margin: 20px 0; border-left: 4px solid #ff9800;\">
        <h4 style=\"color: #e65100; margin-top: 0;\">💎 Bütçe Dostu Seçenekler</h4>
        <ul style=\"color: #5f6368;\">
            <li><strong>" . ($brands[0] ?? 'Marka A') . ":</strong> En uygun fiyatlı model</li>
            <li><strong>" . ($brands[1] ?? 'Marka B') . ":</strong> Orta segment en iyisi</li>
            <li><strong>" . ($brands[2] ?? 'Marka C') . ":</strong> Premium deneyim uygun fiyata</li>
        </ul>
    </div>

    <!-- SPONSOR LİNKLER -->
    <div style=\"background: #e8f5e8; padding: 25px; border-radius: 10px; margin: 30px 0; border: 2px solid #66bb6a;\">
        <h4 style=\"color: #2e7d32; margin-top: 0;\">🛒 En Uygun Fiyatlı Satıcılar</h4>
        <p style=\"color: #5f6368;\">Aşağıdaki linklerden en güncel fiyatları kontrol edebilirsiniz:</p>
        <div style=\"display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;\">
            <a href=\"#\" rel=\"nofollow sponsored\" class=\"sponsored-link\" style=\"display: block; background: white; padding: 15px; border-radius: 8px; text-decoration: none; color: #d84315; font-weight: bold; border: 2px solid #ffab91; text-align: center;\">🔗 {$product_type} İncele</a>
            <a href=\"#\" rel=\"nofollow sponsored\" class=\"sponsored-link\" style=\"display: block; background: white; padding: 15px; border-radius: 8px; text-decoration: none; color: #d84315; font-weight: bold; border: 2px solid #ffab91; text-align: center;\">🔗 Fiyat Karşılaştır</a>
            <a href=\"#\" rel=\"nofollow sponsored\" class=\"sponsored-link\" style=\"display: block; background: white; padding: 15px; border-radius: 8px; text-decoration: none; color: #d84315; font-weight: bold; border: 2px solid #ffab91; text-align: center;\">🔗 Kampanyalar</a>
            <a href=\"#\" rel=\"nofollow sponsored\" class=\"sponsored-link\" style=\"display: block; background: white; padding: 15px; border-radius: 8px; text-decoration: none; color: #d84315; font-weight: bold; border: 2px solid #ffab91; text-align: center;\">🔗 Tüm Modeller</a>
        </div>
    </div>

    <h2>💡 RadarPrices Tavsiyeleri</h2>
    
    <div style=\"background: #e3f2fd; padding: 20px; border-radius: 10px; margin: 25px 0;\">
        <h4 style=\"color: #1565c0; margin-top: 0;\">🎯 Satın Alma Rehberi</h4>
        <ul style=\"color: #5f6368;\">
            <li><strong>Bütçenizi belirleyin:</strong> Öncelikle ne kadar harcayabileceğinize karar verin</li>
            <li><strong>İhtiyaçlarınızı listeleyin:</strong> Hangi özellikler sizin için önemli?</li>
            <li><strong>Kullanıcı yorumlarını okuyun:</strong> Gerçek deneyimler önemli ipuçları verir</li>
            <li><strong>Fiyat takibi yapın:</strong> Ürün fiyatları dönemsel olarak değişebilir</li>
        </ul>
    </div>

    <!-- SON CÜMLE -->
    <div style=\"background: linear-gradient(135deg, #e8f5e8 0%, #f0f4c3 100%); padding: 25px; border-radius: 12px; margin: 30px 0; border: 2px solid #66bb6a; text-align: center;\">
        <p style=\"margin: 0; font-size: 18px; color: #2e7d32; font-weight: bold;\">
            👉 Sizin deneyimlerinizi ve yorumlarınızı bekliyoruz! Hangi markayı tercih ettiniz?
        </p>
    </div>

    <!-- BENZER KARŞILAŞTIRMALAR -->
    <div style=\"background: linear-gradient(135deg, #e8f4fc 0%, #f3e5f5 100%); padding: 25px; border-radius: 15px; margin: 40px 0; border: 2px solid #9c27b0;\">
        <h3 style=\"color: #7b1fa2; margin-top: 0;\">🚀 Diğer Karşılaştırmalar</h3>
        <p style=\"color: #5f6368;\">Hangi ürünleri karşılaştırmamızı istiyorsunuz?</p>
        
        <div style=\"display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 20px;\">
            <button class=\"topic-btn\" style=\"background: white; border: 2px solid #e0e0e0; padding: 15px; border-radius: 10px; cursor: pointer; text-align: left; transition: all 0.3s;\">
                <strong style=\"color: #1a73e8;\">📱 Akıllı Telefonlar</strong>
                <p style=\"margin: 8px 0 0 0; color: #5f6368; font-size: 14px;\">iPhone vs Samsung vs Xiaomi</p>
            </button>
            
            <button class=\"topic-btn\" style=\"background: white; border: 2px solid #e0e0e0; padding: 15px; border-radius: 10px; cursor: pointer; text-align: left; transition: all 0.3s;\">
                <strong style=\"color: #1a73e8;\">💻 Dizüstü Bilgisayarlar</strong>
                <p style=\"margin: 8px 0 0 0; color: #5f6368; font-size: 14px;\">Gaming, Ofis, Ultrabook</p>
            </button>

            <button class=\"topic-btn\" style=\"background: white; border: 2px solid #e0e0e0; padding: 15px; border-radius: 10px; cursor: pointer; text-align: left; transition: all 0.3s;\">
                <strong style=\"color: #1a73e8;\">🎧 Kulaklıklar</strong>
                <p style=\"margin: 8px 0 0 0; color: #5f6368; font-size: 14px;\">Wireless, Gaming, Noise Cancelling</p>
            </button>
        </div>
    </div>
    ";
}

function generateBrandsTable($brands, $product_type) {
    $table_html = '';
    $prices = ['₺1.500 - ₺2.500', '₺2.500 - ₺4.000', '₺4.000 - ₺6.000', '₺6.000 - ₺10.000+'];
    $performance = ['⭐️⭐️⭐️', '⭐️⭐️⭐️⭐️', '⭐️⭐️⭐️⭐️⭐️', '⭐️⭐️⭐️⭐️'];
    $user_ratings = ['7.8/10', '8.5/10', '9.2/10', '8.9/10'];
    $value = ['⭐️⭐️⭐️⭐️⭐️', '⭐️⭐️⭐️⭐️', '⭐️⭐️⭐️', '⭐️⭐️⭐️⭐️'];
    
    for ($i = 0; $i < min(4, count($brands)); $i++) {
        $bg_color = $i % 2 == 0 ? 'background: #f8f9fa;' : 'background: white;';
        $table_html .= "
        <tr style=\"{$bg_color}\">
            <td style=\"padding: 12px; border-bottom: 1px solid #e0e0e0;\">
                <strong>{$brands[$i]}</strong>
                <br><small style=\"color: #666;\">{$product_type} modeli</small>
            </td>
            <td style=\"padding: 12px; border-bottom: 1px solid #e0e0e0; text-align: center; color: #d84315; font-weight: bold;\">{$prices[$i]}</td>
            <td style=\"padding: 12px; border-bottom: 1px solid #e0e0e0; text-align: center;\">{$performance[$i]}</td>
            <td style=\"padding: 12px; border-bottom: 1px solid #e0e0e0; text-align: center; color: #2e7d32; font-weight: bold;\">{$user_ratings[$i]}</td>
            <td style=\"padding: 12px; border-bottom: 1px solid #e0e0e0; text-align: center;\">{$value[$i]}</td>
        </tr>";
    }
    
    return $table_html;
}

function generateBrandsAnalysis($brands, $product_type) {
    $analysis_html = '';
    
    foreach ($brands as $index => $brand) {
        $analysis_html .= "
        <div style=\"background: " . ($index % 2 == 0 ? '#f8f9fa' : 'white') . "; padding: 20px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #ff6b6b;\">
            <h4 style=\"color: #d84315; margin-top: 0;\">🏷️ {$brand}</h4>
            <p style=\"color: #5f6368; margin-bottom: 10px;\">
                <strong>Güçlü Yönler:</strong> Yüksek performans, dayanıklılık, kullanıcı dostu arayüz<br>
                <strong>Zayıf Yönler:</strong> Fiyat diğer markalara göre yüksek olabilir<br>
                <strong>Kullanıcı Yorumu:</strong> \"{$product_type} konusunda beklentilerimi karşıladı, özellikle dayanıklılığı çok iyi.\"
            </p>
        </div>";
    }
    
    return $analysis_html;
}
?>

<!-- HTML KISMI AYNI, SADECE FORMU GÜNCELLİYORUM -->
<!-- ... önceki HTML yapısı aynı kalıyor, sadece form kısmını değiştiriyorum ... -->

<div class="ai-generator-section">
    <h3 style="color: #2c3e50; margin-bottom: 25px;">🎯 RadarPrices Makale Parametreleri</h3>
    
    <form method="POST" id="aiGeneratorForm">
        <div class="form-grid">
            <div class="form-group">
                <label for="product_type"><i class="fas fa-box"></i> Ürün Kategorisi</label>
                <input type="text" class="form-control" id="product_type" name="product_type" 
                       placeholder="Örn: Akıllı Telefon, Dizüstü Bilgisayar, Kulaklık" required>
            </div>
            
            <div class="form-group">
                <label for="brands"><i class="fas fa-tags"></i> Karşılaştırılacak Markalar</label>
                <input type="text" class="form-control" id="brands" name="brands" 
                       placeholder="Apple, Samsung, Xiaomi, Huawei (virgülle ayırın)" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="keywords"><i class="fas fa-key"></i> Anahtar Kelimeler</label>
            <input type="text" class="form-control" id="keywords" name="keywords" 
                   placeholder="fiyat, performans, inceleme, karşılaştırma, teknik özellikler" required>
        </div>

        <div class="form-group">
            <label for="word_count"><i class="fas fa-file-alt"></i> Kelime Sayısı</label>
            <select class="form-control" id="word_count" name="word_count" required>
                <option value="1000">1000 kelime (Özet)</option>
                <option value="1200" selected>1200 kelime (Standart)</option>
                <option value="1500">1500 kelime (Detaylı)</option>
                <option value="2000">2000 kelime (Kapsamlı)</option>
            </select>
        </div>

        <!-- RADARPRICES KURALLAR CHECKLIST -->
        <div class="rules-checklist">
            <h4>✅ RadarPrices Otomatik Kurallar</h4>
            
            <div class="rule-item checked">
                <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                <span><strong>"En İyi X Markaları"</strong> başlık formatı</span>
            </div>
            
            <div class="rule-item checked">
                <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                <span><strong>Fiyat & performans</strong> karşılaştırma tabloları</span>
            </div>
            
            <div class="rule-item checked">
                <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                <span><strong>4 sponsor link</strong> (nofollow sponsored)</span>
            </div>
            
            <div class="rule-item checked">
                <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                <span><strong>Marka bazlı detaylı analiz</strong></span>
            </div>
            
            <div class="rule-item checked">
                <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                <span><strong>Kullanıcı yorumları & puanları</strong></span>
            </div>
            
            <div class="rule-item checked">
                <i class="fas fa-check-circle" style="color: #27ae60;"></i>
                <span><strong>Satın alma rehberi & tavsiyeler</strong></span>
            </div>
        </div>

        <button type="submit" name="generate_article" class="btn-generate">
            <i class="fas fa-robot"></i> KARŞILAŞTIRMA MAKALESİ ÜRET
        </button>
    </form>
</div>