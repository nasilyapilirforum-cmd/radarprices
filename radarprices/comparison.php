<?php
// comparison.php
include 'header.php';

$productIds = isset($_GET['products']) ? explode(',', $_GET['products']) : [];
?>

<div class="container">
    <h1>⚖️ Ürün Karşılaştırma</h1>
    
    <?php if(empty($productIds)): ?>
        <div style="text-align: center; padding: 40px;">
            <h3>Karşılaştırılacak ürün bulunamadı</h3>
            <p>Lütfen önce karşılaştırmak istediğiniz ürünleri ekleyin.</p>
            <a href="search.php" style="background: #2563eb; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none;">Ürün Ara</a>
        </div>
    <?php else: ?>
        <div class="comparison-container">
            <p><strong><?= count($productIds) ?> ürün</strong> karşılaştırılıyor</p>
            
            <div style="display: grid; grid-template-columns: repeat(<?= count($productIds) ?>, 1fr); gap: 20px; margin-top: 30px;">
                <?php foreach($productIds as $productId): ?>
                <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 15px;">📱</div>
                    <h3>Ürün <?= $productId ?></h3>
                    <p style="color: #dc2626; font-size: 1.5rem; font-weight: bold;">₺ <?= number_format(rand(5000, 50000), 0, ',', '.') ?></p>
                    <button onclick="removeFromComparison(<?= $productId ?>)" style="background: #ef4444; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer;">Kaldır</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function removeFromComparison(productId) {
    if (window.comparisonSystem) {
        window.comparisonSystem.removeFromComparison(productId);
        alert('Ürün karşılaştırmadan kaldırıldı');
        window.location.reload();
    }
}
</script>

<?php include 'footer.php'; ?>