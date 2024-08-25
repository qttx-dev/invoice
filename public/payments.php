<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Payment.php';
require_once __DIR__ . '/../src/Invoice.php';

$payment = new Payment($conn);
$invoice = new Invoice($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment->invoice_id = $_POST['invoice_id'];
    $payment->amount = floatval($_POST['amount']);
    $payment->payment_date = date('Y-m-d');

    if ($payment->create()) {
        echo "<div class='alert alert-success'>Zahlung erfolgreich erfasst.</div>";
    } else {
        echo "<div class='alert alert-danger'>Fehler beim Erfassen der Zahlung.</div>";
    }
}

$invoices = $invoice->read();
?>

<div class="container">
    <h2>Zahlungen verwalten</h2>
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
            <label for="amount" class="form-label">Betrag</label>
            <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Zahlung erfassen</button>
    </form>

    <h3>Erfasste Zahlungen</h3>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Rechnung</th>
                <th>Betrag</th>
                <th>Datum</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Hier können Sie die Zahlungen für eine bestimmte Rechnung abrufen
            $payments = $payment->readByInvoice($invoice->id); // Passen Sie dies an, um die Zahlungen für die ausgewählte Rechnung abzurufen
            while ($row = $payments->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['invoice_id']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($row['amount'], 2)); ?> €</td>
                    <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
