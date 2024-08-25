<?php
require_once __DIR__ . '/../includes/header.php';

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Willkommen, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
</div>

<div class="row">
    <div class="col">
        <h3>Ihre Rechnungen</h3>
        <p>Hier können Sie Ihre Rechnungen einsehen.</p>
        <!-- Hier können Sie eine Liste oder eine Tabelle für Rechnungen einfügen -->
    </div>
</div>

<p><a href="logout.php" class="btn btn-danger">Abmelden</a></p>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
