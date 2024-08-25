<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/User.php';

$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Die Passwörter stimmen nicht überein.";
    } else {
        $result = $user->register($username, $email, $password);
        if ($result === true) {
            $_SESSION['message'] = "Registrierung erfolgreich. Sie können sich jetzt anmelden.";
            header("Location: login.php");
            exit();
        } else {
            $error = $result;
        }
    }
}
?>

<div class="container">
    <h2>Registrierung</h2>

    <?php
    if (isset($error)) {
        echo "<div class='alert alert-danger'>$error</div>";
    }
    ?>

    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Benutzername</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-Mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Passwort</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Passwort bestätigen</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Registrieren</button>
    </form>

    <p>Bereits registriert? <a href="login.php">Hier anmelden</a></p>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
