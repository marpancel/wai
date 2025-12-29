<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
</head>
<body>

<h2>Rejestracja</h2>

<?php if (!empty($error)): ?>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <div>
        <label>Email</label><br>
        <input type="email" name="email" required>
    </div>

    <div>
        <label>Login</label><br>
        <input type="text" name="login" required>
    </div>

    <div>
        <label>Hasło</label><br>
        <input type="password" name="password" required>
    </div>

    <div>
        <label>Powtórz hasło</label><br>
        <input type="password" name="password2" required>
    </div>

    <div>
        <label>Zdjęcie profilowe</label><br>
        <input type="file" name="profile_photo" accept="image/jpeg,image/png" required>
    </div>

    <button type="submit">Zarejestruj</button>
</form>

</body>
</html>