<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
</head>
<body>

<h2>Logowanie</h2>

<?php if (!empty($error)): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post">
    <div>
        <label>Login</label><br>
        <input type="text" name="login" required>
    </div>

    <div>
        <label>Hasło</label><br>
        <input type="password" name="password" required>
    </div>

    <button type="submit">Zaloguj</button>
</form>

<p>
    Nie masz konta?
    <a href="/?route=register">Zarejestruj się</a>
</p>

</body>
</html>