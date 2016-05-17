-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 16, 2016 at 10:49 AM
-- Server version: 5.5.44-0+deb8u1
-- PHP Version: 5.6.20-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tobsi`
--

-- --------------------------------------------------------

--
-- Table structure for table `abstimmen`
--

CREATE TABLE IF NOT EXISTS `abstimmen` (
  `u_ID` int(10) NOT NULL,
  `datum` date NOT NULL,
  `e_ID1` int(10) DEFAULT NULL,
  `e_ID2` int(10) DEFAULT NULL,
  `g_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `abstimmen`
--

INSERT INTO `abstimmen` (`u_ID`, `datum`, `e_ID1`, `e_ID2`, `g_ID`) VALUES
(1, '2016-04-02', 11, 1, 1),
(1, '2016-04-03', NULL, NULL, 1),
(1, '2016-05-12', 14, 12, 1),
(1, '2016-05-13', 12, 14, 1),
(1, '2016-05-15', 12, 11, 1),
(1, '2016-05-16', 11, 7, 1),
(2, '2016-05-12', 12, 13, 1),
(2, '2016-05-13', 11, 9, 1),
(3, '2016-04-15', 11, 7, 1),
(3, '2016-04-16', 3, 8, 1),
(3, '2016-05-12', 12, 13, 1),
(4, '2016-05-12', 3, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `abstimmung_ergebnis`
--

CREATE TABLE IF NOT EXISTS `abstimmung_ergebnis` (
  `l_ID` int(10) NOT NULL,
  `datum` date NOT NULL,
  `g_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `abstimmung_ergebnis`
--

INSERT INTO `abstimmung_ergebnis` (`l_ID`, `datum`, `g_ID`) VALUES
(1, '2016-05-13', 1),
(2, '2016-05-16', 1),
(3, '2016-04-03', 1),
(3, '2016-04-16', 1),
(4, '2016-04-15', 1),
(4, '2016-05-12', 1),
(5, '2016-05-15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
`c_ID` int(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `nachricht` text NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `g_ID` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`c_ID`, `name`, `nachricht`, `ts`, `g_ID`) VALUES
(46, 'Tobsi', 'm', '2016-05-13 14:56:46', 1),
(47, 'Tobsi', 'm', '2016-05-13 14:57:02', 1),
(48, 'Tobsi', 'm', '2016-05-13 14:57:02', 1),
(49, 'Tobsi', 'm', '2016-05-13 14:57:02', 1),
(50, 'Tobsi', 'm', '2016-05-13 14:57:03', 1),
(51, 'Tobsi', 'm', '2016-05-13 14:57:03', 1),
(52, 'Tobsi', 'mm', '2016-05-13 14:57:03', 1),
(53, 'Tobsi', 'm', '2016-05-13 14:57:04', 1),
(54, 'Tobsi', 'mm', '2016-05-13 14:57:04', 1),
(55, 'Tobsi', 'm', '2016-05-13 14:57:12', 1),
(56, 'Tobsi', 'm', '2016-05-13 14:57:12', 1),
(57, 'Tobsi', 'm', '2016-05-13 14:57:12', 1),
(58, 'Tobsi', 'm', '2016-05-13 14:57:12', 1),
(59, 'Tobsi', 'm', '2016-05-13 14:57:13', 1),
(60, 'Tobsi', 'm', '2016-05-13 14:57:13', 1),
(61, 'Tobsi', 'mm', '2016-05-13 14:57:14', 1),
(62, 'Tobsi', 'm', '2016-05-13 14:57:14', 1),
(63, 'Tobsi', 'm', '2016-05-13 14:57:27', 1),
(64, 'Tobsi', 'm', '2016-05-13 14:57:27', 1),
(65, 'Tobsi', 'm', '2016-05-13 14:57:27', 1),
(66, 'Tobsi', 'm', '2016-05-13 14:57:28', 1),
(67, 'Tobsi', 'm', '2016-05-13 14:57:28', 1),
(68, 'Q', '&quot;; Drop theBase;', '2016-05-13 16:58:48', 1),
(69, 'Tobsi', 'haha', '2016-05-13 16:59:12', 1),
(70, 'Dominik', 'm', '2016-05-15 17:14:41', 1),
(71, 'Dominik', 'm', '2016-05-15 17:14:42', 1),
(72, 'Dominik', 'm', '2016-05-15 17:14:43', 1),
(73, 'Dominik', 'mm', '2016-05-15 17:14:43', 1),
(74, 'Dominik', 'm', '2016-05-15 17:14:44', 1),
(75, 'Tobsi', 'ok', '2016-05-15 19:10:37', 1),
(76, 'Tobsi', 'hi', '2016-05-15 21:10:04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `essen`
--

CREATE TABLE IF NOT EXISTS `essen` (
`e_ID` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `u_ID` int(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `essen`
--

INSERT INTO `essen` (`e_ID`, `name`, `u_ID`) VALUES
(1, 'GebÃ¤ck', 2),
(3, 'DÃ¶ner', 2),
(5, 'Indisch', 2),
(7, 'Burger', 2),
(8, 'Pizza', 2),
(9, 'Chicken', 2),
(11, 'Schlecht gekochtes Essen', 3),
(12, 'SpÃ¤tzle', 3),
(13, 'Schnitzel', 3),
(14, 'Maultaschen', 1),
(15, 'Pasta', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gruppe`
--

CREATE TABLE IF NOT EXISTS `gruppe` (
`g_ID` int(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `u_ID` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gruppe`
--

INSERT INTO `gruppe` (`g_ID`, `name`, `u_ID`) VALUES
(1, 'Gruppe X', 3);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
`l_ID` int(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `link` varchar(100) NOT NULL,
  `u_ID` int(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`l_ID`, `name`, `link`, `u_ID`) VALUES
(1, 'Milaneo', 'https://www.milaneo.com/', 3),
(2, 'Das Gerber', 'http://www.das-gerber.de/', 3),
(3, 'Stern DÃ¶ner', 'http://www.yelp.de/biz/kebap-stern-stuttgart', 3),
(4, 'Food Lounge', 'https://www.koenigsbau-passagen.de/', 3),
(5, 'Daheim', '', 3),
(6, 'BÃ¤cker', 'https://www.back-werk.de/', 1);

-- --------------------------------------------------------

--
-- Table structure for table `locessen`
--

CREATE TABLE IF NOT EXISTS `locessen` (
  `l_ID` int(10) NOT NULL,
  `e_ID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locessen`
--

INSERT INTO `locessen` (`l_ID`, `e_ID`) VALUES
(1, 1),
(2, 1),
(6, 1),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(1, 5),
(2, 5),
(4, 5),
(1, 7),
(2, 7),
(1, 8),
(2, 8),
(3, 8),
(1, 9),
(2, 9),
(5, 11),
(1, 12),
(4, 12),
(1, 13),
(4, 13),
(1, 14),
(2, 14),
(4, 14),
(1, 15),
(2, 15),
(4, 15);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`u_ID` int(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwort` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `g_ID` int(10) DEFAULT NULL,
  `verified` int(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`u_ID`, `email`, `passwort`, `username`, `g_ID`, `verified`, `created_at`, `updated_at`) VALUES
(1, 'tobi@stein.bruck', '$2y$10$HACmKF8VuYsRGXNv7K6tj.o.uLEozvjJafiMNLmGt5QN78qdOoUCG', 'Tobsi', 1, 1, '2016-03-22 07:39:49', NULL),
(2, 'quentin.popp@t-online.de', '$2y$10$wxbVk1PeZvxXs/vLC8qfk.Pp0UFBfPUe1.V0w5qcfc.o61kN7zsDC', 'Q', 1, 1, '2016-03-22 08:04:06', NULL),
(3, 'dominik.widera@t-online.de', '$2y$10$1Fe8dJqcA6qx6pDMrtN1a.IHS1J58m7uOsMgi7sCo4cvUkuUCBktu', 'Dominik', 1, 1, '2016-04-02 08:38:09', NULL),
(4, 'tiloullrich28@web.de', '$2y$10$/PwGx8DPwCqmuPc4..1fZO8UJuZCOyIRyIxATu6ubhbVSy7rARv0q', 'tilo', 1, 1, '2016-05-12 07:22:37', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abstimmen`
--
ALTER TABLE `abstimmen`
 ADD PRIMARY KEY (`u_ID`,`datum`), ADD KEY `constraint_essen1` (`e_ID1`), ADD KEY `constraint_essen2` (`e_ID2`), ADD KEY `constraint_abstimmen_gruppe` (`g_ID`);

--
-- Indexes for table `abstimmung_ergebnis`
--
ALTER TABLE `abstimmung_ergebnis`
 ADD PRIMARY KEY (`datum`,`g_ID`), ADD KEY `constraint_location2` (`l_ID`), ADD KEY `constraint_gruppe2` (`g_ID`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
 ADD PRIMARY KEY (`c_ID`), ADD KEY `constraint_username` (`name`), ADD KEY `constraint_gruppe_chat` (`g_ID`);

--
-- Indexes for table `essen`
--
ALTER TABLE `essen`
 ADD PRIMARY KEY (`e_ID`), ADD UNIQUE KEY `name` (`name`), ADD KEY `constraint_anleger` (`u_ID`);

--
-- Indexes for table `gruppe`
--
ALTER TABLE `gruppe`
 ADD PRIMARY KEY (`g_ID`), ADD KEY `constraint_admin` (`u_ID`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
 ADD PRIMARY KEY (`l_ID`), ADD UNIQUE KEY `name` (`name`), ADD KEY `constraint_anleger2` (`u_ID`);

--
-- Indexes for table `locessen`
--
ALTER TABLE `locessen`
 ADD PRIMARY KEY (`l_ID`,`e_ID`), ADD KEY `constraint_essen` (`e_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`u_ID`), ADD UNIQUE KEY `email` (`email`), ADD UNIQUE KEY `username` (`username`), ADD KEY `constraint_gruppe` (`g_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
MODIFY `c_ID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `essen`
--
ALTER TABLE `essen`
MODIFY `e_ID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `gruppe`
--
ALTER TABLE `gruppe`
MODIFY `g_ID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
MODIFY `l_ID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `u_ID` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `abstimmen`
--
ALTER TABLE `abstimmen`
ADD CONSTRAINT `constraint_abstimmen_gruppe` FOREIGN KEY (`g_ID`) REFERENCES `gruppe` (`g_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `constraint_essen1` FOREIGN KEY (`e_ID1`) REFERENCES `essen` (`e_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `constraint_essen2` FOREIGN KEY (`e_ID2`) REFERENCES `essen` (`e_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
ADD CONSTRAINT `constraint_name` FOREIGN KEY (`u_ID`) REFERENCES `users` (`u_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `abstimmung_ergebnis`
--
ALTER TABLE `abstimmung_ergebnis`
ADD CONSTRAINT `constraint_gruppe2` FOREIGN KEY (`g_ID`) REFERENCES `gruppe` (`g_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `constraint_location2` FOREIGN KEY (`l_ID`) REFERENCES `location` (`l_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
ADD CONSTRAINT `constraint_gruppe_chat` FOREIGN KEY (`g_ID`) REFERENCES `gruppe` (`g_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `constraint_username` FOREIGN KEY (`name`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `essen`
--
ALTER TABLE `essen`
ADD CONSTRAINT `constraint_anleger` FOREIGN KEY (`u_ID`) REFERENCES `users` (`u_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `gruppe`
--
ALTER TABLE `gruppe`
ADD CONSTRAINT `constraint_admin` FOREIGN KEY (`u_ID`) REFERENCES `users` (`u_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `location`
--
ALTER TABLE `location`
ADD CONSTRAINT `constraint_anleger2` FOREIGN KEY (`u_ID`) REFERENCES `users` (`u_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `locessen`
--
ALTER TABLE `locessen`
ADD CONSTRAINT `constraint_essen` FOREIGN KEY (`e_ID`) REFERENCES `essen` (`e_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `constraint_location` FOREIGN KEY (`l_ID`) REFERENCES `location` (`l_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `constraint_gruppe` FOREIGN KEY (`g_ID`) REFERENCES `gruppe` (`g_ID`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
