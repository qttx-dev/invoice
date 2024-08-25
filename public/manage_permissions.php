<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/Role.php';
require_once __DIR__ . '/../src/Module.php';
require_once __DIR__ . '/../src/Permission.php';

$role = new Role($conn);
$module = new Module($conn);
$permission = new Permission($conn);

$roles = $role->read();
$modules = $module->read();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role_id = $_POST['role_id'];
    $module_id = $_POST['module_id'];
    $can_access = $_POST['can_access'] ? 1 : 0;

    // Setzen Sie die Berechtigung
    $permission->role_id = $role_id;
    $permission->module_id = $module_id;
    $permission->can_access = $can_access;

    if ($permission->setPermission()) {
        echo "<div class='alert alert-success'>Berechtigung erfolgreich gesetzt.</div>";
    } else {
        echo "<div class='alert alert-danger'>Fehler beim Setzen der Berechtigung.</div>";
    }
}
?>

<div class="container">
    <h2>Berechtigungen verwalten</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="role_id" class="form-label">Rolle</label>
            <select class="form-select" id="role_id" name="role_id" required>
                <?php while ($row = $roles->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="module_id" class="form-label">Modul</label>
            <select class="form-select" id="module_id" name="module_id" required>
                <?php while ($row = $modules->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="can_access" class="form-label">Zugriff erlauben</label>
            <input type="checkbox" id="can_access" name="can_access" value="1">
        </div>
        <button type="submit" class="btn btn-primary">Berechtigung setzen</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
