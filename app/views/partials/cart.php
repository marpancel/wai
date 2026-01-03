<?php
$totalQty = 0;

foreach ($savedImages ?? [] as $item) {
    $totalQty += (int)($item['qty'] ?? 0);
}
?>

<div class="flex items-center gap-2">
    <span class="text-2xl">🧺</span>
    <span class="text-sm font-semibold">
        <?= $totalQty ?>
    </span>
</div>