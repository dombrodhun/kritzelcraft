-- Tabelle: kunstwerke
CREATE TABLE IF NOT EXISTS `kunstwerke` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `titel` VARCHAR(255) NOT NULL,
    `beschreibung` TEXT,
    `preis` DECIMAL(10, 2) NOT NULL,
    `bild_name` VARCHAR(255) NOT NULL,
    `status` ENUM('verfuegbar', 'reserviert', 'verkauft') DEFAULT 'verfuegbar',
    `reserviert_bis` DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabelle: warenkorb
CREATE TABLE IF NOT EXISTS `warenkorb` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `session_id` VARCHAR(255) NOT NULL,
    `kunstwerk_id` INT NOT NULL,
    `hinzugefuegt_am` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`kunstwerk_id`) REFERENCES `kunstwerke`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabelle: benutzer
CREATE TABLE IF NOT EXISTS `benutzer` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `benutzername` VARCHAR(50) NOT NULL UNIQUE,
    `passwort_hash` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Initialdaten: Die 3 Mock-Kunstwerke
INSERT INTO `kunstwerke` (`titel`, `beschreibung`, `preis`, `bild_name`, `status`) VALUES
('Fröhlicher Sonnentag', 'Ein handgemaltes Kunstwerk mit Wachsmalkreiden. Zeigt eine strahlende Sonne über einer Blumenwiese.', 14.50, 'sonnentag.png', 'verfuegbar'),
('Drache Friedolin', 'Friedolin der Drache in leuchtenden Farben. Perfekt für jedes Kinderzimmer.', 19.90, 'drache.png', 'verfuegbar'),
('Weltraum Abenteuer', 'Eine Reise zu den Sternen mit dieser Rakete aus Filzstift-Farben.', 12.00, 'weltraum.png', 'verfuegbar');
