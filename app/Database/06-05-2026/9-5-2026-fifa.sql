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

INSERT INTO objectif (libelle) VALUES
('Augmenter son poids'),
('Réduire son poids'),
('Atteindre son IMC idéal');

-- Run these on your DB so signup doesn't hard-fail on NOT NULL
ALTER TABLE client MODIFY dateNaissance DATE NULL;
ALTER TABLE goalpoids MODIFY poids_cible FLOAT NULL;
ALTER TABLE goalpoids MODIFY duree INT NULL;