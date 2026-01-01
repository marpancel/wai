<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Galeria zdjęć</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen text-slate-800">

<div class="max-w-6xl mx-auto px-6 py-10">

    <header class="mb-10 text-center">
        <h1 class="text-4xl font-bold tracking-tight mb-2">
            Galeria zdjęć
        </h1>

        <div class="mt-2 text-sm text-slate-600">
            Zapamiętane: <strong><?= count($savedImages ?? []) ?></strong>
            |
            <a href="/?route=saved" class="text-blue-600 hover:underline">
                Przejdź do zapamiętanych
            </a>
        </div>
    </header>

    <div class="mb-8 text-center">
        <?php if ($user): ?>
            <div class="inline-block bg-white border rounded-xl px-6 py-4 shadow-sm">
                <strong>Zalogowany jako:</strong>
                <?= htmlspecialchars($user['login']) ?><br>

                <?php if (!empty($user['profile_photo'])): ?>
                    <img
                        src="/profiles/<?= htmlspecialchars($user['profile_photo']) ?>"
                        width="80"
                        class="mx-auto mt-2 rounded-full border"
                    >
                <?php endif; ?>

                <div class="mt-2 flex justify-center gap-4 text-sm">
                    <a href="/?route=saved" class="text-blue-600 hover:underline">
                        Zapamiętane (<?= count($savedImages ?? []) ?>)
                    </a>
                    <a href="/?route=logout" class="text-red-600 hover:underline">
                        Wyloguj
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <section class="bg-white rounded-2xl shadow-sm border p-6 mb-10">
        <h2 class="text-xl font-semibold mb-4">
            Dodaj nowe zdjęcie
        </h2>

        <?php if (!empty($error)): ?>
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-red-700">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data"
              class="flex flex-col md:flex-row gap-4 items-start md:items-end">

            <div class="flex-1">
                <label class="block text-sm font-medium mb-1">
                    Plik (JPG / PNG, max 2 MB)
                </label>
                <input
                    type="file"
                    name="image"
                    required
                    class="block w-full text-sm
                           file:mr-4 file:py-2 file:px-4
                           file:rounded-lg file:border-0
                           file:bg-slate-200 hover:file:bg-slate-300"
                >
            </div>

            <button
                type="submit"
                name="upload"
                class="px-6 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
                Wyślij
            </button>
        </form>
    </section>

    <form method="post">
        <section class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-xl font-semibold mb-6">
                Galeria
            </h2>

            <?php if (empty($files)): ?>
                <p class="text-slate-500">
                    Brak zdjęć w galerii.
                </p>
            <?php else: ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($files as $file): ?>
                        <div class="border rounded-xl p-3 bg-white shadow-sm">

                            <img
                                src="/thumbs/<?= htmlspecialchars($file) ?>"
                                class="w-full h-40 object-cover rounded-lg mb-3"
                            >

                            <div class="text-sm truncate mb-2">
                                <?= htmlspecialchars($file) ?>
                            </div>

                            <div class="flex items-center justify-between text-sm gap-2">
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        name="images[<?= htmlspecialchars($file) ?>][checked]"
                                        <?= isset($savedImages[$file]) ? 'checked' : '' ?>
                                    >
                                    Zapamiętaj
                                </label>

                                <input
                                    type="number"
                                    name="images[<?= htmlspecialchars($file) ?>][qty]"
                                    value="<?= $savedImages[$file]['qty'] ?? 1 ?>"
                                    min="1"
                                    class="w-16 border rounded px-2 py-1 text-center"
                                >
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-6 text-center">
                    <button
                        type="submit"
                        name="save_selected"
                        class="px-6 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                        Zapamiętaj wybrane
                    </button>
                </div>

            <?php endif; ?>
        </section>
    </form>

    <?php if ($pages > 1): ?>
        <nav class="flex justify-center mt-10 gap-2">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a href="?route=gallery&page=<?= $i ?>"
                   class="px-4 py-2 rounded-lg text-sm font-medium border
                          <?= $i === $page
                              ? 'bg-blue-600 text-white border-blue-600'
                              : 'bg-white hover:bg-slate-100' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>

</div>

</body>
</html>