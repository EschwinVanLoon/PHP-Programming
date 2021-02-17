DELIMITER ^^

SET sql_mode='traditional'^^

-- Indien de database al bestaat verwijderen en
-- alles opnieuw opbouwen.
DROP DATABASE IF EXISTS t04m04^^

CREATE DATABASE t04m04
	CHARACTER SET utf8mb4
	COLLATE utf8mb4_general_ci^^
	
USE t04m04^^

CREATE TABLE gebruikers (
  id INT NOT NULL AUTO_INCREMENT,
  naam varchar(30) NOT NULL UNIQUE,
  email varchar(191) NOT NULL UNIQUE,
  wachtwoord varchar(191) NOT NULL,
  PRIMARY KEY(id)
)^^


-- DROP USER t04m04@localhost^^

-- CREATE USER t04m04@localhost
CREATE USER IF NOT EXISTS t04m04@localhost
	IDENTIFIED BY 't04m04'^^

GRANT SELECT, INSERT, UPDATE, DELETE
	ON t04m04.* 
	TO t04m04@localhost^^

