<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/User.php';
require_once __DIR__ . '/../src/Role.php';

$user = new User($conn);
$role = new Role($conn);

$users = $user->read(); // Methode zum Abrufen aller Benutzer
$roles = $role->read(); // Methode zum Abrufen aller Rollen

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $role_id = $_POST['role_id'];

    // Hier können Sie die Zuordnung in der user_roles Tabelle speichern
    // ...
}

?>

<div class="container">
    <h2>Rollen zuweisen</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="user_id" class="form-label">Benutzer</label>
            <select class="form-select" id="user_id" name="user_id" required>
                <?php while ($row = $users->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['username']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="role_id" class="form-label">Rolle</label>
            <select class="form-select" id="role_id" name="role_id" required>
                <?php while ($row = $roles->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Rolle zuweisen</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
