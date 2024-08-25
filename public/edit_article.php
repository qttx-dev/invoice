<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Article.php';

$article = new Article($conn);

if (isset($_GET['id'])) {
    $article->id = $_GET['id'];
    $article->readOne();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $article->id = $_POST['id'];
    $article->name = sanitize_input($_POST['name']);
    $article->description = sanitize_input($_POST['description']);
    $article->price = floatval($_POST['price']);
    $article->unit = sanitize_input($_POST['unit']);

    if ($article->update()) {
        echo "<p>Artikel erfolgreich aktualisiert.</p>";
    } else {
        echo "<p>Fehler beim Aktualisieren des Artikels.</p>";
    }
}
?>

<h2>Artikel bearbeiten</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $article->id; ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $article->name; ?>" required><br>
    <label for="description">Beschreibung:</label>
    <textarea id="description" name="description"><?php echo $article->description; ?></textarea><br>
    <label for="price">Preis:</label>
    <input type="number" id="price" name="price" step="0.01" value="<?php echo $article->price; ?>" required><br>
    <label for="unit">Einheit:</label>
    <select id="unit" name="unit">
        <option value="Stück" <?php echo ($article->unit == 'Stück') ? 'selected' : ''; ?>>Stück</option>
        <option value="Stunde" <?php echo ($article->unit == 'Stunde') ? 'selected' : ''; ?>>Stunde</option>
        <!-- Fügen Sie hier weitere Einheiten hinzu -->
    </select><br>
    <input type="submit" value="Artikel aktualisieren">
</form>

<a href="articles.php">Zurück zur Artikelliste</a>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
