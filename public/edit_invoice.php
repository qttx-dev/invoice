<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Invoice.php';
require_once __DIR__ . '/../src/InvoiceItem.php';
require_once __DIR__ . '/../src/Article.php';
require_once __DIR__ . '/../src/Customer.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/generate_invoice_pdf.php';

$invoice = new Invoice($conn);
$invoiceItem = new InvoiceItem($conn);
$article = new Article($conn);

if (isset($_GET['id'])) {
    $invoice->id = $_GET['id'];
    $invoice_data = $invoice->readOne();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_item':
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->article_id = $_POST['article_id'];
                $invoiceItem->quantity = $_POST['quantity'];
                $invoiceItem->price = $_POST['price'];
                if ($invoiceItem->create()) {
                    echo "<p>Rechnungsposten erfolgreich hinzugefügt.</p>";
                } else {
                    echo "<p>Fehler beim Hinzufügen des Rechnungspostens.</p>";
                }
                break;
            case 'update_invoice':
                $invoice->id = $_POST['id'];
                $invoice->customer_id = $_POST['customer_id'];
                $invoice->invoice_date = $_POST['invoice_date'];
                $invoice->due_date = $_POST['due_date'];
                $invoice->status = $_POST['status'];
                if ($invoice->update()) {
                    echo "<p>Rechnung erfolgreich aktualisiert.</p>";
                } else {
                    echo "<p>Fehler beim Aktualisieren der Rechnung.</p>";
                }
                break;
        }
    }
}

// Rechnungsdaten anzeigen
?>
<h2>Rechnung bearbeiten</h2>
<form method="POST">
    <input type="hidden" name="action" value="update_invoice">
    <input type="hidden" name="id" value="<?php echo $invoice_data['id']; ?>">
    <label for="invoice_number">Rechnungsnummer:</label>
    <input type="text" id="invoice_number" name="invoice_number" value="<?php echo $invoice_data['invoice_number']; ?>" readonly><br>
    <label for="customer_id">Kunde:</label>
    <input type="text" id="customer_id" name="customer_id" value="<?php echo $invoice_data['customer_name']; ?>" readonly><br>
    <label for="invoice_date">Rechnungsdatum:</label>
    <input type="date" id="invoice_date" name="invoice_date" value="<?php echo $invoice_data['invoice_date']; ?>" required><br>
    <label for="due_date">Fälligkeitsdatum:</label>
    <input type="date" id="due_date" name="due_date" value="<?php echo $invoice_data['due_date']; ?>" required><br>
    <label for="status">Status:</label>
    <select id="status" name="status">
        <option value="Ausstehend" <?php echo ($invoice_data['status'] == 'Ausstehend') ? 'selected' : ''; ?>>Ausstehend</option>
        <option value="Bezahlt" <?php echo ($invoice_data['status'] == 'Bezahlt') ? 'selected' : ''; ?>>Bezahlt</option>
        <option value="Storniert" <?php echo ($invoice_data['status'] == 'Storniert') ? 'selected' : ''; ?>>Storniert</option>
    </select><br>
    <input type="submit" value="Rechnung aktualisieren">
</form>

<h3>Rechnungsposten</h3>
<?php
$items = $invoiceItem->readByInvoice($invoice->id);
if ($items->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Artikel</th><th>Menge</th><th>Preis</th><th>Gesamtpreis</th></tr>";
    $total = 0;
    while ($row = $items->fetch_assoc()) {
        $item_total = $row['quantity'] * $row['price'];
        $total += $item_total;
        echo "<tr>";
        echo "<td>" . $row['article_name'] . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>" . $row['price'] . "</td>";
        echo "<td>" . $item_total . "</td>";
        echo "</tr>";
    }
    echo "<tr><td colspan='3'><strong>Gesamtbetrag</strong></td><td><strong>" . $total . "</strong></td></tr>";
    echo "</table>";
} else {
    echo "Keine Rechnungsposten gefunden.";
}
?>

<h3>Rechnungsposten hinzufügen</h3>
<form method="POST">
    <input type="hidden" name="action" value="add_item">
    <label for="article_id">Artikel:</label>
    <select id="article_id" name="article_id" required>
        <?php
        $result = $article->read();
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['name'] . " - " . $row['price'] . "</option>";
        }
        ?>
    </select><br>

    <label for="quantity">Menge:</label>
    <input type="number" id="quantity" name="quantity" min="1" required><br>
    <label for="price">Preis:</label>
    <input type="number" id="price" name="price" step="0.01" required><br>
    <input type="submit" value="Rechnungsposten hinzufügen">
</form>

<h3>Rechnungsposten</h3>
<?php
$items = $invoiceItem->readByInvoice($invoice->id);
if ($items->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Artikel</th><th>Menge</th><th>Preis</th><th>Gesamtpreis</th><th>Aktionen</th></tr>";
    $total = 0;
    while ($row = $items->fetch_assoc()) {
        $item_total = $row['quantity'] * $row['price'];
        $total += $item_total;
        echo "<tr>";
        echo "<td>" . $row['article_name'] . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>" . $row['price'] . "</td>";
        echo "<td>" . $item_total . "</td>";
        echo "<td>
                <form method='POST' style='display:inline;'>
                    <input type='hidden' name='item_id' value='" . $row['id'] . "'>
                    <input type='hidden' name='action' value='delete_item'>
                    <input type='submit' value='Löschen'>
                </form>
              </td>";
        echo "</tr>";
    }
    echo "<tr><td colspan='3'><strong>Gesamtbetrag</strong></td><td><strong>" . $total . "</strong></td></tr>";
    echo "</table>";
} else {
    echo "Keine Rechnungsposten gefunden.";
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_item') {
    $invoiceItem->id = $_POST['item_id'];
    if ($invoiceItem->delete()) {
        echo "<p>Rechnungsposten erfolgreich gelöscht.</p>";
        header("Location: edit_invoice.php?id=" . $invoice->id);
        exit();
    } else {
        echo "<p>Fehler beim Löschen des Rechnungspostens.</p>";
    }
}
?>


<h3>Rechnung per E-Mail versenden</h3>
<form method="POST">
    <input type="hidden" name="action" value="send_email">
    <label for="email">E-Mail-Adresse:</label>
    <input type="email" id="email" name="email" value="<?php echo $invoice_data['customer_email']; ?>" required><br>
    <label for="custom_message">Benutzerdefinierte Nachricht (optional):</label>
    <textarea id="custom_message" name="custom_message"></textarea><br>
    <input type="submit" value="Rechnung per E-Mail senden">
</form>

<?php
// E-Mail-Versand verarbeiten
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'send_email') {
    $to = $_POST['email'];
    $customMessage = $_POST['custom_message'];
    
    // PDF generieren (Sie müssen sicherstellen, dass dies den Pfad zur generierten PDF zurückgibt)
    $pdfPath = generateInvoicePDF($invoice->id, $conn);
    
    $result = sendInvoiceEmail($to, $invoice_data['invoice_number'], $pdfPath, $customMessage);
    
    if ($result === true) {
        echo "<p>Rechnung wurde erfolgreich per E-Mail versendet.</p>";
    } else {
        echo "<p>Fehler beim Versenden der E-Mail: $result</p>";
    }
}

?>

<a href="generate_invoice_pdf.php?id=<?php echo $invoice->id; ?>">Rechnung als PDF herunterladen</a>

<a href="invoices.php">Zurück zur Rechnungsübersicht</a>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
