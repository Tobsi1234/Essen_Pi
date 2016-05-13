--
-- Datenbank: `tobsi`
--
DROP SCHEMA IF EXISTS tobsi ;
CREATE SCHEMA IF NOT EXISTS tobsi DEFAULT CHARACTER SET UTF8;
USE tobsi;
-- --------------------------------------------------------
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Tabellenstruktur für Tabelle `gruppe`
--

CREATE TABLE IF NOT EXISTS `gruppe` (
  `g_ID` int(10) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(30) NOT NULL,
  `u_ID` int(10) NOT NULL,
  PRIMARY KEY (`g_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `u_ID` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `passwort` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `g_ID` int(10) DEFAULT NULL,
  `verified` int(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`u_ID`),
  UNIQUE KEY (`email`),
  UNIQUE KEY (`username`),
  CONSTRAINT `constraint_gruppe` FOREIGN KEY (`g_ID`) REFERENCES `gruppe` (`g_ID`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- constraint sind erst nach erstellen der Tabelle (auf die sie sich beziehen) möglich
ALTER TABLE gruppe
ADD CONSTRAINT `constraint_admin` FOREIGN KEY (`u_ID`) REFERENCES `users` (`u_ID`) ON UPDATE CASCADE ON DELETE CASCADE;

-- --------------------------------------------------------


--
-- Tabellenstruktur für Tabelle `essen`
--

CREATE TABLE IF NOT EXISTS `essen` (
  `e_ID` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `u_ID` int(10) NULL,
  PRIMARY KEY (`e_ID`),
  UNIQUE KEY (`name`),
  CONSTRAINT `constraint_anleger` FOREIGN KEY (`u_ID`) REFERENCES `users` (`u_ID`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `location`
--

CREATE TABLE IF NOT EXISTS `location` (
	`l_ID` int(10) NOT NULL AUTO_INCREMENT,
	`name` varchar(30) NOT NULL,
	`link` varchar(100) NOT NULL,
  `u_ID` int(10) NULL,
	 PRIMARY KEY (`l_ID`),
	 UNIQUE KEY (`name`),
   CONSTRAINT `constraint_anleger2` FOREIGN KEY (`u_ID`) REFERENCES `users` (`u_ID`) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `locessen`
--

CREATE TABLE IF NOT EXISTS `locessen` (
  `l_ID` int(10) NOT NULL,
  `e_ID` int(10) NOT NULL,
  PRIMARY KEY (`l_ID`,`e_ID`),
  CONSTRAINT `constraint_location` FOREIGN KEY (`l_ID`) REFERENCES `location` (`l_ID`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `constraint_essen` FOREIGN KEY (`e_ID`) REFERENCES `essen` (`e_ID`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abstimmen` (ehemals tabbez)
--

CREATE TABLE IF NOT EXISTS `abstimmen` (
  `u_ID` int(10) NOT NULL,
  `datum` DATE NOT NULL,
  `e_ID1` int(10),
  `e_ID2` int(10),
  `g_ID` int(10) NOT NULL,
  PRIMARY KEY (`u_ID`,`datum`),
  CONSTRAINT `constraint_name` FOREIGN KEY (`u_ID`) REFERENCES `users` (`u_ID`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `constraint_essen1` FOREIGN KEY (`e_ID1`) REFERENCES `essen` (`e_ID`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `constraint_essen2` FOREIGN KEY (`e_ID2`) REFERENCES `essen` (`e_ID`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `constraint_abstimmen_gruppe` FOREIGN KEY (`g_ID`) REFERENCES `gruppe` (`g_ID`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `c_ID` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `nachricht` text NOT NULL,
  `ts` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `g_ID` int(10) NOT NULL,
  PRIMARY KEY (`c_ID`),
  CONSTRAINT `constraint_username` FOREIGN KEY (`name`) REFERENCES `users` (`username`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `constraint_gruppe_chat` FOREIGN KEY (`g_ID`) REFERENCES `gruppe` (`g_ID`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `abstimmung_ergebnis`
--

CREATE TABLE `abstimmung_ergebnis` (
  `l_ID` int(10) NOT NULL,
  `datum` date NOT NULL,
  `g_ID` int(10) NOT NULL,
  PRIMARY KEY (datum, g_ID),
  CONSTRAINT `constraint_location2` FOREIGN KEY (`l_ID`) REFERENCES `location` (`l_ID`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `constraint_gruppe2` FOREIGN KEY (`g_ID`) REFERENCES `gruppe` (`g_ID`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;