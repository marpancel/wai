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
        <h1 class="text-4xl font-bold mb-2">Zapamiętane zdjęcia</h1>
        <a href="/?route=gallery" class="text-blue-600 hover:underline">
            ← Wróć do galerii
        </a>
    </header>

    <?php if (empty($savedImages)): ?>
        <p class="text-center text-slate-500">
            Brak zapamiętanych zdjęć.
        </p>
    <?php else: ?>
        <form method="post">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php foreach ($savedImages as $file => $data): ?>
                    <div class="bg-white border rounded-xl p-4 shadow-sm">

                        <img
                            src="/thumbs/<?= htmlspecialchars($file) ?>"
                            class="w-full h-40 object-cover rounded-lg mb-3"
                        >

                        <div class="flex justify-between items-center text-sm">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="remove[<?= htmlspecialchars($file) ?>]">
                                Usuń
                            </label>

                            <span class="text-slate-600">
                                Ilość: <strong><?= (int)$data['qty'] ?></strong>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="mt-8 text-center">
                <button
                    type="submit"
                    class="px-6 py-2 rounded-lg bg-red-600 text-white font-semibold
                        hover:bg-red-700 transition"
                    onclick="return confirm('Usunąć zaznaczone zdjęcia?')"
                >
                    Usuń zaznaczone z zapamiętanych
                </button>
            </div>
        </form>
    <?php endif; ?>

</div>

</body>
</html>