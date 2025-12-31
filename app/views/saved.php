<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zapamiętane zdjęcia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen text-slate-800">

<div class="max-w-5xl mx-auto px-6 py-10">

    <header class="mb-10 text-center">
        <h1 class="text-3xl font-bold">
            Zapamiętane zdjęcia
        </h1>
        <a href="/?route=gallery"
           class="text-blue-600 hover:underline text-sm">
            ← Wróć do galerii
        </a>
    </header>

    <?php if (empty($saved)): ?>
        <p class="text-center text-slate-500">
            Brak zapamiętanych zdjęć.
        </p>
    <?php else: ?>

        <form method="post" class="space-y-4">

            <?php foreach ($saved as $file => $qty): ?>
                <div class="flex items-center gap-4 bg-white border rounded-xl p-4 shadow-sm">

                    <img
                        src="/thumbs/<?= htmlspecialchars($file) ?>"
                        class="w-32 h-20 object-cover rounded-lg border"
                    >

                    <div class="flex-1">
                        <div class="font-medium">
                            <?= htmlspecialchars($file) ?>
                        </div>
                        <div class="text-sm text-slate-500">
                            Ilość: <?= (int)$qty ?>
                        </div>
                    </div>

                    <button
                        type="submit"
                        name="remove"
                        value="<?= htmlspecialchars($file) ?>"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm hover:bg-red-700">
                        Usuń
                    </button>

                </div>
            <?php endforeach; ?>

            <div class="text-center pt-6">
                <button
                    type="submit"
                    name="clear_all"
                    class="px-6 py-2 rounded-lg bg-gray-700 text-white hover:bg-gray-800">
                    Wyczyść wszystko
                </button>
            </div>

        </form>

    <?php endif; ?>

</div>

</body>
</html>