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