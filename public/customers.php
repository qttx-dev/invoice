<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Customer.php';

$customer = new Customer($conn);

$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$result = $customer->read($search); // Passen Sie die read-Methode an, um Suchparameter zu akzeptieren

// Verarbeitung des Formulars
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $customer->name = sanitize_input($_POST['name']);
                $customer->email = sanitize_input($_POST['email']);
                $customer->address = sanitize_input($_POST['address']);
                if ($customer->create()) {
                    echo "<div class='alert alert-success'>Kunde erfolgreich erstellt.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Fehler beim Erstellen des Kunden.</div>";
                }
                break;
        }
    }
}
?>

<form method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" class="form-control" name="search" placeholder="Nach Kunden suchen..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button class="btn btn-outline-secondary" type="submit">Suchen</button>
    </div>
</form>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kundenverwaltung</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#createCustomerModal">Neuen Kunden erstellen</button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Name</th>
                <th>E-Mail</th>
                <th>Adresse</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $customer->read();
            while ($row = $result->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td>
                        <a href="edit_customer.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-secondary">Bearbeiten</a>
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

<!-- Modal für neuen Kunden -->
<div class="modal fade" id="createCustomerModal" tabindex="-1" aria-labelledby="createCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCustomerModalLabel">Neuen Kunden erstellen</h5>
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
                        <label for="email" class="form-label">E-Mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Adresse</label>
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kunden erstellen</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
