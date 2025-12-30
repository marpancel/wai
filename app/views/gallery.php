<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Galeria zdjęć</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        img { image-rendering: auto; }
    </style>
</head>

<body class="bg-slate-100 min-h-screen text-slate-800">

<div class="max-w-6xl mx-auto px-6 py-10">

    <!-- Header -->
    <header class="mb-10 text-center">
        <h1 class="text-4xl font-bold tracking-tight mb-2">
            Galeria zdjęć
        </h1>
        <p class="text-slate-500">
            Marcel Panc s208336
        </p>
    </header>

    <!-- Status logowania -->
    <div class="mb-8 text-center">
        <?php if ($user): ?>
            <div class="inline-block bg-white border rounded-xl px-6 py-4 shadow-sm">
                <strong>Zalogowany jako:</strong>
                <?= htmlspecialchars($user['login']) ?><br>
                <img
                    src="/profiles/<?= htmlspecialchars($user['profile_photo']) ?>"
                    width="80"
                    class="mx-auto mt-2 rounded-full border"
                >
                <div class="mt-2">
                    <a href="/?route=logout"
                       class="text-sm text-red-600 hover:underline">
                        Wyloguj
                    </a>
                </div>
            </div>
        <?php else: ?>
            <a href="/?route=login"
               class="inline-block px-6 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                Zaloguj się
            </a>
        <?php endif; ?>
    </div>

    <!-- Upload (tylko dla zalogowanych) -->
    <?php if ($user): ?>
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
                    class="block w-full text-sm text-slate-700
                           file:mr-4 file:py-2 file:px-4
                           file:rounded-lg file:border-0
                           file:text-sm file:font-semibold
                           file:bg-slate-200 file:text-slate-800
                           hover:file:bg-slate-300"
                >
            </div>

            <button
                type="submit"
                class="px-6 py-2 rounded-lg bg-blue-600 text-white font-semibold
                       hover:bg-blue-700 transition shadow-sm">
                Wyślij
            </button>
        </form>
    </section>
    <?php endif; ?>

    <!-- Galeria -->
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
                    <a href="/images/<?= htmlspecialchars($file) ?>" target="_blank"
                       class="group block rounded-xl overflow-hidden border bg-slate-50
                              hover:shadow-md transition">

                        <div class="aspect-[16/10] overflow-hidden">
                            <img
                                src="/thumbs/<?= htmlspecialchars($file) ?>"
                                alt="Miniatura"
                                class="w-full h-full object-cover
                                       group-hover:scale-105 transition-transform duration-300"
                            >
                        </div>

                        <div class="px-3 py-2 text-sm text-slate-600 truncate">
                            <?= htmlspecialchars($file) ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </section>

    <!-- Paginacja -->
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

    <!-- Footer -->
    <footer class="mt-16 text-center text-sm text-slate-400">
        :)
    </footer>

</div>

</body>
</html>