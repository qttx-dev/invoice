<?php
require_once 'config/database.php';

// Funktion zum Überprüfen, ob das temp-Verzeichnis beschreibbar ist
function checkTempDirectory($dir) {
    if (!is_writable($dir)) {
        echo "<div class='alert alert-danger'>Das Verzeichnis '$dir' ist nicht beschreibbar. Bitte stellen Sie sicher, dass die Berechtigungen korrekt gesetzt sind.</div>";
        echo "<p>Um das Verzeichnis beschreibbar zu machen, führen Sie bitte die folgenden Schritte aus:</p>";
        echo "<ul>
                <li>Für Linux/Unix: Führen Sie den Befehl <code>chmod 777 $dir</code> im Terminal aus.</li>
                <li>Für Windows: Klicken Sie mit der rechten Maustaste auf das Verzeichnis, wählen Sie 'Eigenschaften', und stellen Sie sicher, dass der Benutzer Schreibzugriff hat.</li>
              </ul>";
        return false;
    }
    return true;
}

// Funktion zum Ausführen der SQL-Befehle aus der db.sql-Datei
function executeSqlFile($conn, $file) {
    $sql = file_get_contents($file);
    if ($conn->multi_query($sql)) {
        do {
            // Nichts tun, bis die letzte Abfrage abgeschlossen ist
        } while ($conn->next_result());
        echo "<div class='alert alert-success'>Datenbank erfolgreich eingerichtet.</div>";
    } else {
        echo "<div class='alert alert-danger'>Fehler beim Einrichten der Datenbank: " . $conn->error . "</div>";
    }
}

// Überprüfen, ob das temp-Verzeichnis beschreibbar ist
$tempDir = __DIR__ . '/temp';
if (checkTempDirectory($tempDir)) {
    // SQL-Befehle aus der db.sql-Datei ausführen
    $sqlFile = __DIR__ . '/db.sql';
    if (file_exists($sqlFile)) {
        executeSqlFile($conn, $sqlFile);
    } else {
        echo "<div class='alert alert-danger'>Die Datei 'db.sql' wurde nicht gefunden.</div>";
    }
}
?>
