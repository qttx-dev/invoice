<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Customer.php';

$customer = new Customer($conn);

if (isset($_GET['id'])) {
    $customer->id = $_GET['id'];
    $customer->readOne();
} else {
    echo "<div class='alert alert-danger'>Kunden-ID nicht angegeben.</div>";
    exit();
}
?>

<div class="container">
    <h2>Kundendetails</h2>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($customer->name); ?></p>
    <p><strong>E-Mail:</strong> <?php echo htmlspecialchars($customer->email); ?></p>
    <p><strong>Adresse:</strong> <?php echo htmlspecialchars($customer->address); ?></p>
    <a href="customers.php" class="btn btn-secondary">Zurück zur Kundenliste</a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
