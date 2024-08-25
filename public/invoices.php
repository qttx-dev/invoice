<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Invoice.php';

$invoice = new Invoice($conn);
$invoices = $invoice->read();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Rechnungen</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="create_invoice.php" class="btn btn-sm btn-outline-secondary">Neue Rechnung</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Rechnungsnummer</th>
                <th>Kunde</th>
                <th>Datum</th>
                <th>Fälligkeitsdatum</th>
                <th>Betrag</th>
                <th>Status</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $invoices->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['invoice_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['invoice_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_amount']); ?> €</td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <a href="edit_invoice.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-secondary">Bearbeiten</a>
                        <a href="view_invoice.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">Ansehen</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
