<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../src/User.php';

$user = new User($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];

    $result = $user->login($username, $password);
    if ($result === true) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = $result;
    }
}
?>

<h2>Anmeldung</h2>

<?php
if (isset($_SESSION['message'])) {
    echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
}

if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>

<form method="POST">
    <label for="username">Benutzername:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Passwort:</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Anmelden">
</form>

<p>Noch kein Konto? <a href="register.php">Hier registrieren</a></p>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
