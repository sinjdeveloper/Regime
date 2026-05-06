-- Active: 1772534195135@@127.0.0.1@3306@regime_app
CREATE DATABASE IF NOT EXISTS regime_app;
USE regime_app;


CREATE TABLE IF NOT EXISTS user(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
);

CREATE TABLE IF NOT EXISTS client(
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    genre VARCHAR(50) NOT NULL,
    dateNaissance DATE NOT NULL,
    poids FLOAT NOT NULL,
    taille FLOAT NOT NULL,
    estGold BOOLEAN NOT NULL DEFAULT FALSE,
    argent FLOAT NOT NULL DEFAULT 0,
);

CREATE TABLE IF NOT EXISTS objectif(
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS goalpoids(
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    objectif_id INT NOT NULL,
    poids_cible FLOAT NOT NULL,
    duree INT NOT NULL,
    FOREIGN KEY (client_id) REFERENCES client(id),
    FOREIGN KEY (objectif_id) REFERENCES objectif(id)
);

CREATE TABLE IF NOT EXISTS regime(
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    variation_poids FLOAT NOT NULL,
    pourcentage_viande FLOAT NOT NULL,
    pourcentage_poisson FLOAT NOT NULL,
    pourcentage_volaille FLOAT NOT NULL,
    prix FLOAT NOT NULL
);

CREATE TABLE IF NOT EXISTS sport(
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    pourcentage_reduction FLOAT NOT NULL
)

CREATE TABLE IF NOT EXISTS suggestion(
    id INT AUTO_INCREMENT PRIMARY KEY,
    regime_id INT NOT NULL,
    sport_id INT NOT NULL,
    FOREIGN KEY (regime_id) REFERENCES regime(id),
    FOREIGN KEY (sport_id) REFERENCES sport(id)
);

CREATE TABLE IF NOT EXISTS code(
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(255) NOT NULL UNIQUE,
    montant FLOAT NOT NULL
)

CREATE TABLE IF NOT EXISTS historiquetransaction(
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    code_id INT NOT NULL,
    FOREIGN KEY (client_id) REFERENCES client(id),
    FOREIGN KEY (code_id) REFERENCES code(id)
);