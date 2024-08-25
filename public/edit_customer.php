<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Customer.php';

$customer = new Customer($conn);

if (isset($_GET['id'])) {
    $customer->id = $_GET['id'];
    $customer->readOne();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer->id = $_POST['id'];
    $customer->name = sanitize_input($_POST['name']);
    $customer->email = sanitize_input($_POST['email']);
    $customer->address = sanitize_input($_POST['address']);

    if ($customer->update()) {
        echo "<p>Kunde erfolgreich aktualisiert.</p>";
    } else {
        echo "<p>Fehler beim Aktualisieren des Kunden.</p>";
    }
}
?>

<h2>Kunden bearbeiten</h2>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $customer->id; ?>">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $customer->name; ?>" required><br>
    <label for="email">E-Mail:</label>
    <input type="email" id="email" name="email" value="<?php echo $customer->email; ?>" required><br>
    <label for="address">Adresse:</label>
    <textarea id="address" name="address"><?php echo $customer->address; ?></textarea><br>
    <input type="submit" value="Kunden aktualisieren">
</form>

<a href="customers.php">Zurück zur Kundenliste</a>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
