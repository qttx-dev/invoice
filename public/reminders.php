<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Reminder.php';
require_once __DIR__ . '/../src/Invoice.php';

$reminder = new Reminder($conn);
$invoice = new Invoice($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reminder->invoice_id = $_POST['invoice_id'];
    $reminder->reminder_date = date('Y-m-d');
    $reminder->message = $_POST['message'];

    if ($reminder->create()) {
        echo "<div class='alert alert-success'>Mahnung erfolgreich erstellt.</div>";
    } else {
        echo "<div class='alert alert-danger'>Fehler beim Erstellen der Mahnung.</div>";
    }
}

$invoices = $invoice->read();
?>

<div class="container">
    <h2>Mahnwesen verwalten</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="invoice_id" class="form-label">Rechnung</label>
            <select class="form-select" id="invoice_id" name="invoice_id" required>
                <?php while ($row = $invoices->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['invoice_number']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Mahnungstext</label>
            <textarea class="form-control" id="message" name="message" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Mahnung erstellen</button>
    </form>

    <h3>Erstellte Mahnungen</h3>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Rechnung</th>
                <th>Datum</th>
                <th>Nachricht</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Hier können Sie die Mahnungen für eine bestimmte Rechnung abrufen
            $reminders = $reminder->readByInvoice($invoice->id); // Passen Sie dies an, um die Mahnungen für die ausgewählte Rechnung abzurufen
            while ($row = $reminders->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['invoice_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['reminder_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
