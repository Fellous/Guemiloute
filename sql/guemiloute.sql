-- commit name: create-database-schema
-- - Crée la base de données "guemiloute"
-- - Ajoute toutes les tables pour users, objects, emprunts, annonces, etc.

CREATE DATABASE IF NOT EXISTS guemiloute;
USE guemiloute;

-- Table des utilisateurs (emprunteur, prêteur, admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(50),
    address VARCHAR(255),
    city VARCHAR(100),
    role ENUM('emprunteur','preteur','admin') NOT NULL DEFAULT 'emprunteur',
    additional_info TEXT,
    availability TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des catégories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Table des objets (items)
CREATE TABLE objects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category_id INT,
    state ENUM('neuf','comme neuf','bien','moyen','mauvaise état') NOT NULL DEFAULT 'bien',
    quantity INT NOT NULL DEFAULT 1,
    city VARCHAR(100),
    address VARCHAR(255),
    preteur_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (preteur_id) REFERENCES users(id)
);

-- Table des images d'objets
CREATE TABLE object_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    object_id INT NOT NULL,
    image_url VARCHAR(255),
    FOREIGN KEY (object_id) REFERENCES objects(id)
);

-- Table des emprunts
CREATE TABLE emprunts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    object_id INT NOT NULL,
    emprunteur_id INT NOT NULL,
    preteur_id INT NOT NULL,
    date_start DATETIME NOT NULL,
    date_end DATETIME NOT NULL,
    quantity_borrowed INT NOT NULL DEFAULT 1,
    returned_date DATETIME NULL,
    FOREIGN KEY (object_id) REFERENCES objects(id),
    FOREIGN KEY (emprunteur_id) REFERENCES users(id),
    FOREIGN KEY (preteur_id) REFERENCES users(id)
);

ALTER TABLE emprunts 
ADD COLUMN status ENUM('en_cours','termine') NOT NULL DEFAULT 'en_cours';

-- Table des annonces (dons / ventes)
CREATE TABLE annonces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('don','vente') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) DEFAULT NULL,
    image_url VARCHAR(255),
    reservation_status ENUM('disponible','reserve','donne','annule') NOT NULL DEFAULT 'disponible',
    reserved_by INT DEFAULT NULL,
    reserved_at DATETIME DEFAULT NULL,
    confirmed_by_donor BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (reserved_by) REFERENCES users(id)
);
