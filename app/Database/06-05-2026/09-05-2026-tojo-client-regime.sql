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