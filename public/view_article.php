<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Article.php';

$article = new Article($conn);

if (isset($_GET['id'])) {
    $article->id = $_GET['id'];
    $article->readOne();
} else {
    echo "<div class='alert alert-danger'>Artikel-ID nicht angegeben.</div>";
    exit();
}
?>

<div class="container">
    <h2>Artikeldetails</h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($article->name); ?></p>
    <p><strong>Beschreibung:</strong> <?php echo htmlspecialchars($article->description); ?></p>
    <p><strong>Preis:</strong> <?php echo htmlspecialchars(number_format($article->price, 2)); ?> €</p>
    <p><strong>Einheit:</strong> <?php echo htmlspecialchars($article->unit); ?></p>
    <a href="articles.php" class="btn btn-secondary">Zurück zur Artikelliste</a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
