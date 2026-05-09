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