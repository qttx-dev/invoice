# 🧾 Rechnungstool

Ein modernes und flexibles Rechnungstool zur Verwaltung von Rechnungen, Kunden und Zahlungen. Dieses System bietet eine benutzerfreundliche Oberfläche und eine leistungsstarke API für die Integration in andere Anwendungen.

## 🚀 Funktionen

- **Benutzerverwaltung**: Registrierung, Login und Rollenverwaltung für Administratoren, Mitarbeiter und Kunden.
- **Artikelverwaltung**: Erstellen, Bearbeiten, Anzeigen und Löschen von Artikeln.
- **Kundenverwaltung**: Erstellen, Bearbeiten, Anzeigen und Löschen von Kunden.
- **Rechnungsverwaltung**: Erstellen, Bearbeiten, Anzeigen und Löschen von Rechnungen.
- **PDF-Generierung**: Rechnungen können als PDF generiert und heruntergeladen werden.
- **E-Mail-Versand**: Rechnungen können per E-Mail an Kunden gesendet werden.
- **Zahlungsverwaltung**: Erfassen und Verwalten von Zahlungen für Rechnungen.
- **Mahnwesen**: Erstellen und Verwalten von Mahnungen für überfällige Rechnungen.
- **API**: RESTful API zur Integration in externe Anwendungen mit Swagger-Dokumentation.
- **Such- und Filterfunktionen**: Schnelles Finden von Artikeln und Kunden.

## 📦 Installation

### Voraussetzungen

- PHP 7.4 oder höher
- MySQL oder MariaDB
- Webserver (Apache, Nginx, etc.)
- Composer (optional, für Abhängigkeiten)

### Schritte zur Installation

1. **Repository klonen**:
   ```bash
   git clone https://github.com/qttx-dev/invoice.git
   cd invoice
   ```

2. **Datenbank erstellen**:
   - Erstellen Sie eine neue Datenbank in MySQL oder MariaDB.
   - Fügen Sie die SQL-Befehle aus der `db.sql`-Datei in die Datenbank ein.

3. **Konfiguration**:
   - Passen Sie die `config/database.php`-Datei an, um Ihre Datenbankverbindungsdetails einzugeben.

4. **Setup-Skript ausführen**:
   - Stellen Sie sicher, dass das `temp`-Verzeichnis im Hauptverzeichnis vorhanden ist.
   - Führen Sie das `setup.php`-Skript im Browser aus (`http://yourdomain.com/setup.php`), um die Datenbank zu initialisieren und alle erforderlichen Tabellen zu erstellen.

5. **Webserver-Konfiguration**:
   - Stellen Sie sicher, dass der Webserver auf den `public`-Ordner verweist, wo sich die `index.php`-Datei befindet.

### Umleitung auf den `public`-Ordner mit `.htaccess`

Wenn Sie Apache verwenden, können Sie eine `.htaccess`-Datei im Hauptverzeichnis Ihres Projekts erstellen, um die Umleitung auf den `public`-Ordner zu konfigurieren:

```apache
RewriteEngine On
RewriteRule ^(.*)$ public/$1 [L]
```

### Verwendung von ISPConfig

Wenn Sie ISPConfig verwenden, können Sie den `DocumentRoot` für Ihre Website auf den `public`-Ordner einstellen:

1. Melden Sie sich bei ISPConfig an.
2. Gehen Sie zu **Websites** und wählen Sie die gewünschte Website aus.
3. Ändern Sie den **DocumentRoot** auf den Pfad zu Ihrem `public`-Ordner, z.B. `/var/www/yourdomain.com/public`.
4. Speichern Sie die Änderungen und starten Sie den Webserver neu.

## 🛠 Nutzung

- Besuchen Sie die Anmeldeseite (`/login.php`), um sich anzumelden oder zu registrieren.
- Verwenden Sie die Navigationsleiste, um auf die verschiedenen Funktionen des Systems zuzugreifen.
- Nutzen Sie die API-Dokumentation unter `/swagger/`, um die API zu erkunden und zu testen.

## 📄 Lizenz

Dieses Projekt ist unter der MIT-Lizenz lizenziert. Weitere Informationen finden Sie in der LICENSE-Datei.