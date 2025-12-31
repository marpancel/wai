<?php
$total = 0;

if (isset($_SESSION['saved_images'])) {
    foreach ($_SESSION['saved_images'] as $item) {
        $total += (int)$item['qty'];
    }
}
?>

<a href="/?route=saved"
   class="relative inline-flex items-center gap-2
          px-4 py-2 bg-white border rounded-xl shadow-sm
          hover:shadow-md transition">

    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-6 h-6 text-slate-700"
         fill="none" viewBox="0 0 24 24"
         stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4
                 M7 13l-1.5 7h13L17 13
                 M7 13h10
                 M10 21a1 1 0 100-2 1 1 0 000 2
                 M17 21a1 1 0 100-2 1 1 0 000 2"/>
    </svg>

    <span class="text-sm font-medium">
        Zapamiętane
    </span>

    <?php if ($total > 0): ?>
        <span class="absolute -top-2 -right-2
                     bg-blue-600 text-white text-xs
                     w-6 h-6 rounded-full
                     flex items-center justify-center">
            <?= $total ?>
        </span>
    <?php endif; ?>

</a>