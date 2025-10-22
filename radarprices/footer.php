<?php
// footer.php
?>
    </div> <!-- header.php'de açılan div kapanıyor -->

    <footer style="background: #1e293b; color: white; padding: 40px 0; margin-top: 80px;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
                <div>
                    <h3 style="color: #60a5fa; margin-bottom: 20px;">🚀 RadarPrices.com</h3>
                    <p style="color: #cbd5e1; line-height: 1.6;">
                        Akıllı fiyat karşılaştırma radarı. En iyi fırsatları anında keşfedin.
                    </p>
                </div>
                
                <div>
                    <h4 style="color: #60a5fa; margin-bottom: 15px;">Kategoriler</h4>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <?php 
                        $categories = getMainCategories();
                        foreach(array_slice($categories, 0, 5) as $category): 
                        ?>
                        <a href="category.php?slug=<?= $category['slug'] ?>" style="color: #cbd5e1; text-decoration: none;">
                            <?= $category['icon'] ?> <?= $category['name'] ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div>
                    <h4 style="color: #60a5fa; margin-bottom: 15px;">Hızlı Linkler</h4>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <a href="deals.php" style="color: #cbd5e1; text-decoration: none;">🔥 Fırsatlar</a>
                        <a href="compare.php" style="color: #cbd5e1; text-decoration: none;">⚖️ Karşılaştır</a>
                        <a href="about.php" style="color: #cbd5e1; text-decoration: none;">📖 Hakkımızda</a>
                        <a href="contact.php" style="color: #cbd5e1; text-decoration: none;">📞 İletişim</a>
                    </div>
                </div>
            </div>
            
            <div style="border-top: 1px solid #334155; margin-top: 40px; padding-top: 20px; text-align: center; color: #94a3b8;">
                <p>© <?= date('Y') ?> RadarPrices.com - Tüm hakları saklıdır</p>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT -->
    <script>
    // SIK KULLANILANLARA EKLE
    function initFavoriteReminder() {
        const lastHidden = localStorage.getItem('radarFavoriteHidden');
        const oneDayAgo = Date.now() - (24 * 60 * 60 * 1000);
        
        if (!lastHidden || parseInt(lastHidden) < oneDayAgo) {
            setTimeout(() => {
                const reminder = document.querySelector('.favorite-reminder');
                if (reminder) reminder.style.display = 'block';
            }, 5000);
        }
    }

    function hideFavoriteReminder() {
        const reminder = document.querySelector('.favorite-reminder');
        if (reminder) {
            reminder.style.display = 'none';
            localStorage.setItem('radarFavoriteHidden', Date.now());
        }
    }

    // SAYFA YÜKLENDİĞİNDE
    document.addEventListener('DOMContentLoaded', function() {
        initFavoriteReminder();
        
        // Tarih otomasyonu
        document.title = document.title.replace(/\[\d{4}\]/, '[<?= $current_year ?>]');
    });
    </script>
</body>
</html>