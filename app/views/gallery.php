<h1>Galeria</h1>
<h2>Upload zdjęcia</h2>

<?php if (!empty($error)): ?>
  <p style="color:red"><?= $error ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" action="/gallery/upload">
  <input type="file" name="image" required>
  <button type="submit">Wyślij</button>
</form>