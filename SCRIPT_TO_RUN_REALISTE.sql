-- SCRIPT_TO_RUN.sql
-- Consolidated SQL script combining schema and seed files from app/Database/06-05-2026 and base/
-- Generated: 2026-05-12
-- Recommended: review before running. Run on a test DB first.

-- Disable foreign key checks during setup
SET FOREIGN_KEY_CHECKS=0;

-- ---------------------------------------------------------------------
-- Source: app/Database/06-05-2026/init.sql
-- ---------------------------------------------------------------------
-- Active: 1772534195135@@127.0.0.1@3306@regime_app
CREATE DATABASE IF NOT EXISTS regime_app;
USE regime_app;


CREATE TABLE IF NOT EXISTS user(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user'
);

CREATE TABLE IF NOT EXISTS client(
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    genre VARCHAR(50) NOT NULL,
    dateNaissance DATE NOT NULL,
    poids FLOAT NOT NULL,
    taille FLOAT NOT NULL,
    estGold BOOLEAN NOT NULL DEFAULT FALSE,
    argent FLOAT NOT NULL DEFAULT 0,
    FOREIGN KEY (id_user) REFERENCES user(id)
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
);

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
    montant FLOAT NOT NULL,
    utilisé BOOLEAN NOT NULL DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS historiquetransaction(
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    code_id INT,
    type VARCHAR(50) NOT NULL,
    montant FLOAT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client(id),
    FOREIGN KEY (code_id) REFERENCES code(id)
);


-- ---------------------------------------------------------------------
-- Source: app/Database/06-05-2026/09-05-2026-tojo-data.sql
-- ---------------------------------------------------------------------

INSERT INTO objectif(libelle) VALUES
('Augmenter mon poids'),
('Perdre du poids'),
('Atteindre mon IMC idéal');

INSERT INTO user (username, password_hash,role) VALUES 
('client','$2y$12$ozwDYEWdWwscqRp.NHdlwOQ1yibIb4wSTKXLLPe3pOVv2axhy6Sc2','user'),
('demo','$2y$12$b.wF10ejdNgwj53ZZZ5X8ekZckHYPuiikDfNRmo9uXQM.JviP4cFa','user'),
('user','$2y$12$ts747UTSJTnCb13VFjMzQuTXXcnO3r48lLSFoxPZuadDzAdGoQqLC','user');

INSERT INTO user (username, password_hash,role) VALUES ('tojo','$2y$12$IgVrPmLWt7eOx1rQGOagQOIc.GVaAA9P5De5.EvwAfJvBuUiScmF.','user');

;

INSERT INTO client(id_user,email,genre,dateNaissance,poids,taille,estGold,argent) VALUES 
(1,'client@gmail.com','Homme','2008-06-06',60,165,0,0),
(2,'demo@gmail.com','Homme','2005-03-16',90,175,0,0),
(3,'user@gmail.com','Femme','2004-01-15',60,170,0,0);
INSERT INTO client(id_user,email,genre,dateNaissance,poids,taille,estGold,argent) VALUES 
(4,'tojo@gmail.com','Homme','2008-06-06',45,165,0,0);


INSERT INTO goalpoids (client_id,objectif_id,poids_cible,duree) VALUES
(1,1,70,30),
(2,2,60,45),
(3,3,80,35);
INSERT INTO goalpoids (client_id,objectif_id,poids_cible,duree) VALUES
(4,1,50,20);


SELECT COUNT(*),o.libelle FROM goalpoids gp JOIN objectif o ON gp.objectif_id = o.id GROUP BY o.id  ;
ALTER TABLE goalpoids ADD UNIQUE KEY uq_goalpoids_objectif_client (objectif_id, client_id);

SELECT COUNT(*) FROM client GROUP BY estGold;


-- ---------------------------------------------------------------------
-- Source: app/Database/06-05-2026/9-5-2026-fifa.sql
-- ---------------------------------------------------------------------

ALTER TABLE sport
ADD UNIQUE (libelle);

ALTER TABLE regime
ADD UNIQUE (libelle);

INSERT INTO regime 
(
    libelle,
    description,
    pourcentage_viande,
    pourcentage_poisson,
    pourcentage_volaille,
    variation_poids,
    prix
)
VALUES
(
    'Keto Boost',
    'Regime faible en glucides pour une perte de poids rapide.',
    40,
    20,
    40,
    -5,
    120000
),
(
    'Detox Nature',
    'Regime detoxifiant à base de fruits et légumes frais.',
    20,
    30,
    50,
    -3,
    90000
),
(
    'Muscle Max',
    'Nutrition riche en proteines pour prise de masse musculaire.',
    50,
    10,
    40,
    4,
    180000
);

INSERT INTO sport
(libelle, pourcentage_reduction)
VALUES
('Cardio Fit', 15),
('Yoga Relax', 10),
('HIIT Extreme', 20),
('Pilates Core', 12),
('Musculation Pro', 25);

ALTER TABLE regime ADD image VARCHAR(255);
ALTER TABLE sport ADD image VARCHAR(255);

-- atao ao anaty public/assets/images/programs/ ny sary

UPDATE regime SET image = 'keto.jpg' WHERE libelle = 'Keto Boost';
UPDATE regime SET image = 'detox.jpg' WHERE libelle = 'Detox Nature';
UPDATE regime SET image = 'muscle.jpg' WHERE libelle = 'Muscle Max';

UPDATE sport SET image = 'cardio.jpg' WHERE libelle = 'Cardio Fit';
UPDATE sport SET image = 'yoga.jpg' WHERE libelle = 'Yoga Relax';
UPDATE sport SET image = 'hiit.jpg' WHERE libelle = 'HIIT Extreme';
UPDATE sport SET image = 'pilates.jpg' WHERE libelle = 'Pilates Core';
UPDATE sport SET image = 'musculation.jpg' WHERE libelle = 'Musculation Pro';

-- Run these on your DB so signup doesn't hard-fail on NOT NULL
ALTER TABLE client MODIFY dateNaissance DATE NULL;
ALTER TABLE goalpoids MODIFY poids_cible FLOAT NULL;
ALTER TABLE goalpoids MODIFY duree INT NULL;


-- ---------------------------------------------------------------------
-- Source: app/Database/06-05-2026/09-05-2026-tojo-client-regime.sql
-- ---------------------------------------------------------------------

CREATE TABLE regimeclient(
    id INT PRIMARY KEY AUTO_INCREMENT,
    regime_id INT REFERENCES Regime(id),
    client_id INT REFERENCES Client(id)
);
INSERT INTO regimeclient(regime_id,client_id) VALUES
(4,1),
(4,2),
(4,3),
(5,1),
(5,2),
(6,3);

SELECT COUNT(*) nombre, r.libelle FROM regimeclient rc JOIN regime r ON r.id = rc.regime_id GROUP BY regime_id ORDER BY nombre DESC LIMIT 5;  


-- ---------------------------------------------------------------------
-- Source: app/Database/06-05-2026/10-05-2026-code-tojo.sql
-- ---------------------------------------------------------------------

CREATE TABLE StatutCode (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(30)
);
INSERT INTO StatutCode (libelle) VALUES 
('Valide'),
('En attende de validation'),
('Utilisé');

ALTER TABLE code
ADD COLUMN statut_code_id INT NOT NULL DEFAULT 1,
ADD CONSTRAINT fk_code_statut
    FOREIGN KEY (statut_code_id)
    REFERENCES StatutCode(id);


INSERT INTO code (token, montant, statut_code_id) VALUES
('VAR001-5000',   5000,   1),
('VAR002-10000',  10000,  1),
('VAR003-15000',  15000,  1),
('VAR004-20000',  20000,  1),
('VAR005-25000',  25000,  1),
('VAR006-30000',  30000,  1),
('VAR007-40000',  40000,  1),
('VAR008-50000',  50000,  1),
('VAR009-75000',  75000,  1),
('VAR010-100000', 100000, 1),
('VAR011-125000', 125000, 1),
('VAR012-150000', 150000, 1),
('VAR013-200000', 200000, 1),
('VAR014-250000', 250000, 1),
('VAR015-500000', 500000, 1);

INSERT INTO StatutCode (libelle) VALUES 
('Refusé');

ALTER TABLE historiquetransaction ADD COLUMN type ENUM('debit','credit');
ALTER TABLE historiquetransaction ADD COLUMN montant DECIMAL(9,2);


-- ---------------------------------------------------------------------
-- Source: app/Database/06-05-2026/11-05-2026-transaction-tojo.sql
-- ---------------------------------------------------------------------

ALTER TABLE historiquetransaction
MODIFY code_id INT NULL;


-- ---------------------------------------------------------------------
-- Source: app/Database/06-05-2026/07-05-2026-test-admin.sql
-- ---------------------------------------------------------------------

INSERT INTO user(username,password_hash,role) VALUES ('admin','$2y$12$rEu5nzaxaZLRq396OvrkFOpXYR8tvkxqE6Lw/rokk93FtvDBLFyim','admin');


-- ---------------------------------------------------------------------
-- Source: base/2026-05-10-create-app_settings.sql
-- ---------------------------------------------------------------------

CREATE TABLE `app_settings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  libelle VARCHAR(65),
  `value` DECIMAL(9,2) DEFAULT NULL
);

INSERT INTO `app_settings` (`libelle`, `value`)
VALUES ('gold_price',150000);

INSERT INTO `app_settings` (`libelle`, `value`) VALUES
('gold_discount', '15');


-- ---------------------------------------------------------------------
-- Source: base/07-05-2026-test-regime-1.sql
-- ---------------------------------------------------------------------

INSERT INTO regime (libelle,description,variation_poids,pourcentage_viande,pourcentage_poisson,pourcentage_volaille,prix)
VALUES ('Test','test',12,12,12,12,12);


-- ---------------------------------------------------------------------
-- Source: base/07-05-2026-test-admin.sql
-- ---------------------------------------------------------------------

INSERT INTO user(username,password_hash,role) VALUES ('admin','$2y$12$rEu5nzaxaZLRq396OvrkFOpXYR8tvkxqE6Lw/rokk93FtvDBLFyim','admin');


-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS=1;

-- End of SCRIPT_TO_RUN.sql



-- ==========================================================
-- DONNEES REALISTES (5 SPORTS, 5 REGIMES, 5 CLIENTS)
-- ==========================================================

-- Nettoyage des données existantes (en conservant les utilisateurs déjà créés)
DELETE FROM regimeclient;
DELETE FROM sportclient;
DELETE FROM historiquetransaction;
DELETE FROM client;
DELETE FROM sport;
DELETE FROM regime;

-- ----------------------------------------------------------
-- SPORTS
-- ----------------------------------------------------------
INSERT INTO sport (libelle, pourcentage_reduction) VALUES
('Marche rapide', 5.00),
('Course à pied', 8.00),
('Musculation', 12.00),
('Natation', 10.00),
('Cyclisme', 7.50);

-- ----------------------------------------------------------
-- REGIMES
-- libelle, description, variation_poids, pourcentage_viande,
-- pourcentage_poisson, pourcentage_volaille, prix, image
-- ----------------------------------------------------------
INSERT INTO regime
(libelle, description, variation_poids, pourcentage_viande, pourcentage_poisson, pourcentage_volaille, prix, image)
VALUES
(
    'Régime méditerranéen',
    'Alimentation équilibrée riche en légumes, huile d''olive, poisson et céréales complètes.',
    -2.5,
    15,
    25,
    10,
    45000,
    'mediterranean.jpg'
),
(
    'Régime hyperprotéiné',
    'Programme riche en protéines pour préserver la masse musculaire et favoriser la perte de graisse.',
    -4.0,
    35,
    15,
    30,
    60000,
    'high-protein.jpg'
),
(
    'Régime prise de masse',
    'Excédent calorique contrôlé pour augmenter le poids et développer la masse musculaire.',
    3.5,
    30,
    10,
    35,
    55000,
    'mass-gain.jpg'
),
(
    'Régime faible en glucides',
    'Réduction des sucres rapides pour stabiliser la glycémie et favoriser la perte de poids.',
    -3.0,
    25,
    20,
    20,
    50000,
    NULL
),
(
    'Régime équilibré',
    'Répartition harmonieuse des macronutriments pour maintenir un IMC idéal.',
    -1.0,
    20,
    15,
    20,
    40000,
    NULL
);

-- ----------------------------------------------------------
-- CLIENTS
-- Utilise les 5 premiers utilisateurs avec role = 'user'
-- (ids 2 à 6 dans ton script actuel)
-- ----------------------------------------------------------
INSERT INTO client
(id_user, email, genre, dateNaissance, poids, taille, estGold, argent)
VALUES
(2, 'client@gmail.com', 'Homme', '2008-06-06', 60, 165, 0, 150000),
(3, 'demo@gmail.com', 'Homme', '2005-03-16', 90, 175, 0, 80000),
(4, 'user@gmail.com', 'Femme', '2004-01-15', 60, 170, 0, 120000),
(5, 'tojo@gmail.com', 'Homme', '2008-06-06', 45, 165, 1, 200000),
(6, 'tojomarofitahiana@gmail.com', 'Homme', '2000-01-01', 45, 165, 0, 50000);


