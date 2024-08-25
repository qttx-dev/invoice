<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Article.php';

$article = new Article($conn);

$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$result = $article->read($search); // Passen Sie die read-Methode an, um Suchparameter zu akzeptieren

// Verarbeitung des Formulars
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $article->name = sanitize_input($_POST['name']);
                $article->description = sanitize_input($_POST['description']);
                $article->price = floatval($_POST['price']);
                $article->unit = sanitize_input($_POST['unit']);
                if ($article->create()) {
                    echo "<div class='alert alert-success'>Artikel erfolgreich erstellt.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Fehler beim Erstellen des Artikels.</div>";
                }
                break;
        }
    }
}
?>

<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" class="form-control" name="search" placeholder="Nach Artikel suchen..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button class="btn btn-outline-secondary" type="submit">Suchen</button>
    </div>
</form>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Artikelverwaltung</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#createArticleModal">Neuen Artikel erstellen</button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Name</th>
                <th>Beschreibung</th>
                <th>Preis</th>
                <th>Einheit</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $article->read();
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($row['price'], 2)); ?> €</td>
                    <td><?php echo htmlspecialchars($row['unit']); ?></td>
                    <td>
                        <a href="edit_article.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-secondary">Bearbeiten</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="submit" class="btn btn-sm btn-outline-danger" value="Löschen">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal für neuen Artikel -->
<div class="modal fade" id="createArticleModal" tabindex="-1" aria-labelledby="createArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createArticleModalLabel">Neuen Artikel erstellen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="action" value="create">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Beschreibung</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Preis</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="unit" class="form-label">Einheit</label>
                        <select class="form-select" id="unit" name="unit">
                            <option value="Stück">Stück</option>
                            <option value="Stunde">Stunde</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Artikel erstellen</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
