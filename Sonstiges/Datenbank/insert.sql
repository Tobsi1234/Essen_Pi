--
-- Datenbank: `tobsi`
--

USE tobsi;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`u_ID`, `email`, `passwort`, `username`, `created_at`, `updated_at`) VALUES
(1, 'tobi@stein.bruck', '$2y$10$HACmKF8VuYsRGXNv7K6tj.o.uLEozvjJafiMNLmGt5QN78qdOoUCG', 'Tobsi', '2016-03-22 08:39:49', NULL),
(2, 'quentin.popp@t-online.de', '$2y$10$wxbVk1PeZvxXs/vLC8qfk.Pp0UFBfPUe1.V0w5qcfc.o61kN7zsDC', 'Q', '2016-03-22 09:04:06', NULL),
(3, 'dominik.widera@t-online.de', '$2y$10$1Fe8dJqcA6qx6pDMrtN1a.IHS1J58m7uOsMgi7sCo4cvUkuUCBktu', 'Dominik', '2016-04-02 10:38:09', NULL);

--
-- Daten für Tabelle `location`
--

INSERT INTO `location` (`l_ID`, `name`, `link`, `u_ID`) VALUES
  (1, 'Milaneo', 'www.milaneo.de', 3),
  (2, 'Das Gerber', 'www.gerber.de', 3),
  (3, 'Stern DÃ¶ner', 'www.stern.de', 3),
  (4, 'Food Lounge', 'www.foodlounge.de', 3),
  (5, 'Daheim', 'www.daheim.de', 3);


--
-- Daten für Tabelle `essen`
--

INSERT INTO `essen` (`name`, `u_ID`) VALUES
('GebÃ¤ck', 2),
('Kochen', 2),
('DÃ¶ner', 2),
('Hearthstone', 2),
('Indisch', 2),
('Italienisch', 2),
('Burger', 2),
('Pizza', 2),
('Chicken', 2),
('Wir essen Dominik auf', 2),
('Schlecht gekochtes Essen', 3);


--
-- Daten für Tabelle `gruppe`
--
INSERT INTO `gruppe` (`g_ID`, `name`, `u_ID`) VALUES
  (1, 'Gruppe X', 3),
  (2, 'Die BambuswÃ¼rmer', 2);


--
-- Daten für Tabelle `locessen`
--

INSERT INTO `locessen` (`l_ID`, `e_ID`) VALUES
  (1, 3),
  (1, 4),
  (1, 5),
  (1, 7),
  (2, 1),
  (2, 5),
  (2, 7),
  (2, 8),
  (2, 9),
  (3, 1),
  (3, 3),
  (3, 5),
  (3, 8),
  (4, 3),
  (4, 5),
  (4, 9),
  (5, 11);


--
-- Daten für Tabelle `abstimmung_ergebnis´
--

INSERT INTO `abstimmung_ergebnis` (`l_ID`, `datum`, `g_ID`) VALUES
  (1, '2016-04-02', 1),
  (3, '2016-04-03', 1),
  (3, '2016-04-02', 2),
  (2, '2016-04-03', 2),
  (4, '2016-04-15', 1),
  (3, '2016-04-16', 1),
  (1, '2016-04-15', 2),
  (2, '2016-04-16', 2),
  (5, '2016-02-07', 1),
  (4, '2016-02-29', 1),
  (4, '2016-02-07', 2),
  (1, '2016-02-29', 2);

  
--
-- Daten für Tabelle `abstimmung_ergebnis´
--
  INSERT INTO `abstimmen` (`u_ID`, `datum`, `e_ID1`, `e_ID2`, `g_ID`) VALUES
(1, '2016-04-02', 11, 1, 1),
(1, '2016-04-03', 4, 4, 1),
(2, '2016-04-15', 11, 2, 2),
(2, '2016-04-16', 5, 6, 2),
(3, '2016-04-15', 11, 7, 1),
(3, '2016-04-16', 3, 8, 1);
  
--
-- Fremdschlüssel-Daten für Tabelle 'users'
--
UPDATE `users` SET g_ID = 1 WHERE u_ID = 1;
UPDATE `users` SET g_ID = 2 WHERE u_ID = 2;
UPDATE `users` SET g_ID = 1 WHERE u_ID = 3;