<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center text-slate-800">

<div class="w-full max-w-md px-6">

    <div class="bg-white border rounded-2xl shadow-sm p-8">

        <h1 class="text-3xl font-bold text-center mb-6">
            Rejestracja
        </h1>

        <?php if (!empty($error)): ?>
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-red-700 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="space-y-4">

            <div>
                <label class="block text-sm font-medium mb-1">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    required
                    class="w-full rounded-lg border px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Login
                </label>
                <input
                    type="text"
                    name="login"
                    required
                    class="w-full rounded-lg border px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Hasło
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full rounded-lg border px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Powtórz hasło
                </label>
                <input
                    type="password"
                    name="password2"
                    required
                    class="w-full rounded-lg border px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">
                    Zdjęcie profilowe
                </label>
                <input
                    type="file"
                    name="profile_photo"
                    accept="image/jpeg,image/png"
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
                class="w-full mt-2 px-4 py-2 rounded-lg
                       bg-blue-600 text-white font-semibold
                       hover:bg-blue-700 transition"
            >
                Zarejestruj się
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Masz już konto?
            <a href="/?route=login" class="text-blue-600 hover:underline">
                Zaloguj się
            </a>
        </p>

    </div>

</div>

</body>
</html>