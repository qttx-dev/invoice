-- Tabelle für Artikel
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    unit VARCHAR(50) NOT NULL
);

-- Tabelle für Kunden
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    address TEXT
);

-- Tabelle für Rechnungen
CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    invoice_date DATE NOT NULL,
    due_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('Ausstehend', 'Bezahlt', 'Storniert') NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- Tabelle für Rechnungsposten
CREATE TABLE invoice_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    article_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id),
    FOREIGN KEY (article_id) REFERENCES articles(id)
);

-- Tabelle für Benutzer
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabelle für Rollen
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Tabelle für Benutzerrollen
CREATE TABLE user_roles (
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Tabelle für Module
CREATE TABLE modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Tabelle für Rollenberechtigungen
CREATE TABLE role_permissions (
    role_id INT NOT NULL,
    module_id INT NOT NULL,
    can_access BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (role_id, module_id),
    FOREIGN KEY (role_id) REFERENCES roles(id),
    FOREIGN KEY (module_id) REFERENCES modules(id)
);

-- Tabelle für Zahlungen
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id)
);

-- Tabelle für Mahnungen
CREATE TABLE reminders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    reminder_date DATE NOT NULL,
    message TEXT NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id)
);
