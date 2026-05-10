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