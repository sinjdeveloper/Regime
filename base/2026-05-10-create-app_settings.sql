CREATE TABLE `app_settings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  libelle VARCHAR(65),
  `value` DECIMAL(9,2) DEFAULT NULL
);

INSERT INTO `app_settings` (`libelle`, `value`)
VALUES ('gold_price',150000);
