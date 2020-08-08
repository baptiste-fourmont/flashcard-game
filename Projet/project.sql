-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 09, 2020 at 03:39 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `cartes`
--

CREATE TABLE `cartes` (
  `id` int(11) NOT NULL,
  `question` varchar(100) NOT NULL,
  `reponse` varchar(100) NOT NULL,
  `theme` varchar(100) NOT NULL,
  `difficulty` varchar(100) DEFAULT NULL,
  `createur` varchar(100) DEFAULT NULL,
  `valide` varchar(100) DEFAULT NULL,
  `note` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cartes`
--

INSERT INTO `cartes` (`id`, `question`, `reponse`, `theme`, `difficulty`, `createur`, `valide`, `note`) VALUES
(38, 'Comment dit-on ordinateur en anglais ?', 'Computer', 'Anglais', 'Facile', 'redacteur', '1', NULL),
(39, 'Comment dit-on clavier en anglais ?', 'Keyboard', 'Anglais', 'Facile', 'redacteur', '1', NULL),
(40, 'Le mot chien se dit-il réellement cat en anglais ?', 'Non', 'Anglais', 'Facile', 'redacteur', '1', NULL),
(41, 'L\'Irlande est-elle un pays anglophone ?', 'Oui', 'Anglais', 'Facile', 'redacteur', '1', NULL),
(42, 'How to say the word \'Mouse\' in French', 'Souris', 'Anglais', 'Facile', 'redacteur', '1', NULL),
(43, 'Comment dit-on ici en anglais', 'Here', 'Anglais', 'Facile', 'redacteur', '1', NULL),
(44, 'Comment dit-on manette en anglais ?', 'Controller', 'Anglais', 'Facile', 'redacteur', '1', NULL),
(45, 'Quelle est la capitale de l\'Angleterre ?', 'Londres', 'Anglais', 'Facile', 'redacteur', '1', NULL),
(46, 'Combien de pays dans le monde ont pour langue officielle l\'anglais ?', '67', 'Anglais', 'Difficile', 'redacteur', '1', NULL),
(47, 'Qui est actuellement la Reine d\'Angleterre ?', 'Elisabeth II', 'Anglais', 'Facile', 'redacteur', '1', NULL),
(48, 'Qui a réalisé La Joconde ?', 'Léonard de Vinci', 'Art', 'Facile', 'redacteur', '1', NULL),
(49, 'De quelle nationalité est l\'artiste Paul Klee', 'Suisse', 'Art', 'Normale', 'redacteur', '1', NULL),
(50, 'Chez Kant, le jugement esthétique est :', 'Universel', 'Art', 'Difficile', 'redacteur', '1', NULL),
(51, 'Selon Hegel quel est le but de l\'art ?', 'La vérité', 'Art', 'Difficile', 'redacteur', '1', NULL),
(52, 'Pour Freud, l\'art est-il sublimatoire ?', 'Oui', 'Art', 'Normale', 'redacteur', '1', NULL),
(55, 'Qui a peint la Nuit Etoilée ?', 'Vincent van Gogh', 'Art', 'Facile', 'redacteur', '1', NULL),
(56, 'Qui a peint Guernica ?', 'Pablo Picasso', 'Art', 'Facile', 'redacteur', '1', NULL),
(57, 'Quel est le pays que représentait Guernica ?', 'L\'Espagne', 'Art', 'Facile', 'redacteur', '1', NULL),
(58, 'La Cène a-t-elle été peinte par Michel-Ange ?', 'Non', 'Art', 'Normale', 'redacteur', '1', NULL),
(59, 'La Liberté guidant le peuple représente quel pays ?', 'La France', 'Art', 'Facile', 'redacteur', '1', NULL),
(60, 'Quel est la capitale de la France ?', 'Paris', 'Geographie', 'Facile', 'redacteur', '1', NULL),
(61, 'Quel est la capitale du Japon ?', 'Tokyo', 'Geographie', 'Facile', 'redacteur', '1', NULL),
(62, 'Quel est la capitale de l\'Australie ?', 'Camberra', 'Geographie', 'Facile', 'redacteur', '1', NULL),
(63, 'Quel est la capitale de l\'Autriche ?', 'Vienne', 'Geographie', 'Facile', 'redacteur', '1', NULL),
(64, 'Quel est la capitale de la Biélorussie ? ', 'Minsk', 'Geographie', 'Difficile', 'redacteur', '1', NULL),
(65, 'Quel est la capitale du Brésil ?', 'Brasilia', 'Geographie', 'Facile', 'redacteur', '1', NULL),
(66, 'Quel est la capitale de l\'Espagne ?', 'Madrid', 'Geographie', 'Facile', 'redacteur', '1', NULL),
(67, 'Quel est la capitale de l\'Algérie ?', 'Alger', 'Geographie', 'Facile', 'redacteur', '1', NULL),
(68, 'Quel est la capitale de la Bulgarie ?', 'Sofia', 'Geographie', 'Facile', 'redacteur', '1', NULL),
(69, 'Quel est la capitale du Chili ?', 'Santiago', 'Geographie', 'Difficile', 'redacteur', '1', NULL),
(70, 'Quel est le modèle politique de la Chine en 1949', 'Le communisme ', 'Histoire', 'Facile', 'redacteur', '1', NULL),
(71, 'A quel grand du monde la Chine est-elle alliée en 1949 ?', 'L\'URSS', 'Histoire', 'Facile', 'redacteur', '1', NULL),
(72, 'Quel est l\'homme à la tête de la Chine en 1949 ?', 'Mao Zedong', 'Histoire', 'Difficile', 'redacteur', '1', NULL),
(73, 'En quelle année la Chine commence-elle à s\'ouvrir au monde ?', '1976', 'Histoire', 'Difficile', 'redacteur', '1', NULL),
(74, 'Via quel moyen la Chine s\'ouvre-t-elle au monde ?', 'La mondialisation', 'Histoire', 'Normale', 'redacteur', '1', NULL),
(75, 'Quel pays fut le rival de la Chine au niveau de la mondialisation ?', 'Les Etats-Unis', 'Histoire', 'Normale', 'redacteur', '1', NULL),
(76, 'La Chine est-elle maintenant une hyperpuissance ?', 'Non', 'Histoire', 'Difficile', 'redacteur', '1', NULL),
(77, 'En quelle année la Chine entre-t-elle dans l\'OMC ?', '2001', 'Histoire', 'Normale', 'redacteur', '1', NULL),
(78, 'Que fait régner Mao durant son règne ?', 'La terreur', 'Histoire', 'Difficile', 'redacteur', '1', NULL),
(79, 'Quel était le nom du grand allié de Mao ?', 'Staline', 'Histoire', 'Facile', 'redacteur', '1', NULL),
(80, 'Quelle nation a gagné la coupe du monde de football en 1998 ?', 'La France', 'Sport', 'Facile', 'redacteur', '1', NULL),
(81, 'Quel est le nom du joueur ayant gagné le ballon d\'or 2019 ?', 'Messi', 'Sport', 'Facile', 'redacteur', '1', NULL),
(82, 'Quel est le nom du meilleur buteur de l\'histoire du Real Madrid ?', 'Ronaldo', 'Sport', 'Facile', 'redacteur', '1', NULL),
(83, 'Quel est le nom du meilleur buteur de l\'histoire du FC Barcelone ?', 'Messi', 'Sport', 'Facile', 'redacteur', '1', NULL),
(84, 'Quelle nation a gagné la coupe du monde de football en 2010 ?', 'L\'Espagne', 'Sport', 'Facile', 'redacteur', '1', NULL),
(85, 'Combien de différentes nations ont gagné la coupe du monde ?', '13', 'Sport', 'Difficile', 'redacteur', '1', NULL),
(86, 'Combien de finales de la coupe du monde le Brésil a-t-il perdu ?', '2', 'Sport', 'Difficile', 'redacteur', '1', NULL),
(87, 'Quel est le nom du meilleur buteur de l\'histoire de la coupe du monde ? ', 'Klose', 'Sport', 'Difficile', 'redacteur', '1', NULL),
(89, 'Combien de coupe du monde l\'Allemagne a-t-elle actuellement gagnée ?', '4', 'Sport', 'Facile', 'redacteur', '1', NULL),
(90, 'Les Etats-Unis ont-ils déjà gagné la coupe du monde ?', 'Non', 'Sport', 'Facile', 'redacteur', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `valide` varchar(100) DEFAULT NULL,
  `auteur` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `valide`, `auteur`) VALUES
(59, 'Musique', '1', 'dany'),
(60, 'Sport', '1', 'dany'),
(61, 'Anglais', '1', 'dany'),
(62, 'Art', '1', 'dany'),
(63, 'Geographie', '1', 'dany'),
(64, 'Histoire', '1', 'dany'),
(65, 'Littérature', '1', 'dany'),
(66, 'Informatique', '1', 'dany'),
(67, 'Cinéma', '1', 'dany'),
(73, ' Arbres', '1', 'redacteur');

-- --------------------------------------------------------

--
-- Table structure for table `deck`
--

CREATE TABLE `deck` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `nomdeck` varchar(100) NOT NULL,
  `categorie` varchar(100) NOT NULL,
  `q1` varchar(100) DEFAULT NULL,
  `q2` varchar(100) DEFAULT NULL,
  `q3` varchar(100) DEFAULT NULL,
  `q4` varchar(100) DEFAULT NULL,
  `q5` varchar(100) DEFAULT NULL,
  `q6` varchar(100) DEFAULT NULL,
  `q7` varchar(100) DEFAULT NULL,
  `q8` varchar(100) DEFAULT NULL,
  `q9` varchar(100) DEFAULT NULL,
  `q10` varchar(100) DEFAULT NULL,
  `playcount` int(10) NOT NULL DEFAULT 0,
  `valide` varchar(100) DEFAULT NULL,
  `difficulte` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `deck`
--

INSERT INTO `deck` (`id`, `nom`, `nomdeck`, `categorie`, `q1`, `q2`, `q3`, `q4`, `q5`, `q6`, `q7`, `q8`, `q9`, `q10`, `playcount`, `valide`, `difficulte`) VALUES
(47, 'redacteur', 'Anglais Basique 1', 'Anglais', 'Comment dit-on ordinateur en anglais ?', 'Comment dit-on clavier en anglais ?', 'Le mot chien se dit-il réellement cat en anglais ?', 'L\'Irlande est-elle un pays anglophone ?', 'How to say the word \'Mouse\' in French', 'Comment dit-on ici en anglais', 'Comment dit-on manette en anglais ?', 'Quelle est la capitale de l\'Angleterre ?', 'Combien de pays dans le monde ont pour langue officielle l\'anglais ?', 'Qui est actuellement la Reine d\'Angleterre ?', 7, '1', 'Facile'),
(48, 'redacteur', ' Quiz sur l\'art et la philosophie', 'Art', 'Qui a réalisé La Joconde ?', 'De quelle nationalité est l\'artiste Paul Klee', 'Chez Kant, le jugement esthétique est :', 'Selon Hegel quel est le but de l\'art ?', 'Pour Freud, l\'art est-il sublimatoire ?', 'Qui a peint la Nuit Etoilée ?', 'Qui a peint Guernica ?', 'Quel est le pays que représentait Guernica ?', 'La Cène a-t-elle été peinte par Michel-Ange ?', 'La Liberté guidant le peuple représente quel pays ?', 0, '1', 'Difficile'),
(49, 'redacteur', 'Les capitales dans le monde', 'Geographie', 'Quel est la capitale de la France ?', 'Quel est la capitale du Japon ?', 'Quel est la capitale de l\'Australie ?', 'Quel est la capitale de l\'Autriche ?', 'Quel est la capitale de la Biélorussie ? ', 'Quel est la capitale du Brésil ?', 'Quel est la capitale de l\'Espagne ?', 'Quel est la capitale de l\'Algérie ?', 'Quel est la capitale de la Bulgarie ?', 'Quel est la capitale du Chili ?', 0, '1', 'Facile'),
(50, 'redacteur', 'La Chine et le monde depuis 1949', 'Histoire', 'Quel est le modèle politique de la Chine en 1949', 'A quel grand du monde la Chine est-elle alliée en 1949 ?', 'Quel est l\'homme à la tête de la Chine en 1949 ?', 'En quelle année la Chine commence-elle à s\'ouvrir au monde ?', 'Via quel moyen la Chine s\'ouvre-t-elle au monde ?', 'Quel pays fut le rival de la Chine au niveau de la mondialisation ?', 'La Chine est-elle maintenant une hyperpuissance ?', 'En quelle année la Chine entre-t-elle dans l\'OMC ?', 'Que fait régner Mao durant son règne ?', 'Quel était le nom du grand allié de Mao ?', 0, '1', 'Difficile'),
(52, 'redacteur', 'Quiz culture footballistique', 'Sport', 'Quelle nation a gagné la coupe du monde de football en 1998 ?', 'Quel est le nom du joueur ayant gagné le ballon d\'or 2019 ?', 'Quel est le nom du meilleur buteur de l\'histoire du Real Madrid ?', 'Quel est le nom du meilleur buteur de l\'histoire du FC Barcelone ?', 'Quelle nation a gagné la coupe du monde de football en 2010 ?', 'Combien de différentes nations ont gagné la coupe du monde ?', 'Combien de finales de la coupe du monde le Brésil a-t-il perdu ?', 'Quel est le nom du meilleur buteur de l\'histoire de la coupe du monde ? ', 'Combien de coupe du monde l\'Allemagne a-t-elle actuellement gagnée ?', 'Les Etats-Unis ont-ils déjà gagné la coupe du monde ?', 1, '1', 'Difficile');

-- --------------------------------------------------------

--
-- Table structure for table `JeuEnCours`
--

CREATE TABLE `JeuEnCours` (
  `id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `theme` varchar(100) DEFAULT NULL,
  `r1` varchar(100) DEFAULT NULL,
  `r2` varchar(100) DEFAULT NULL,
  `r3` varchar(100) DEFAULT NULL,
  `r4` varchar(100) DEFAULT NULL,
  `r5` varchar(100) DEFAULT NULL,
  `r6` varchar(100) DEFAULT NULL,
  `r7` varchar(100) DEFAULT NULL,
  `r8` varchar(100) DEFAULT NULL,
  `r9` varchar(100) DEFAULT NULL,
  `r10` varchar(100) DEFAULT NULL,
  `scorefinal` int(11) NOT NULL,
  `fini` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `JeuEnCours`
--

INSERT INTO `JeuEnCours` (`id`, `user`, `theme`, `r1`, `r2`, `r3`, `r4`, `r5`, `r6`, `r7`, `r8`, `r9`, `r10`, `scorefinal`, `fini`) VALUES
(2, 'dany', 'Anglais Basique 1', 'computer', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0'),
(28, 'redacteur', 'Deck Test', 'l\'espagne', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0'),
(29, 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),
(31, 'olver', 'Anglais Basique 1', 'computer', 'keyboard', 'non', 'oui', 'souris', 'here', 'oui', 'london', 'tchiip', 'elizabeth', 6, '1'),
(32, 'babou', 'Anglais Basique 1', '', '', '', '', '', '', NULL, NULL, NULL, NULL, 0, '0'),
(33, 'rayan', 'Anglais Basique 1', 'fdsd', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '0'),
(34, 'louis', 'Anglais Basique 1', 'computer', 'keyboard', 'non', 'oui', 'souris', 'here', ' ', 'londres', '5', 'elisabeth 2', 7, '1'),
(36, 'utili', 'Anglais Basique 1', 'computer', '', '', '', '', '', '', '', '', '', 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `probleme` varchar(1000) NOT NULL,
  `auteur` varchar(100) NOT NULL,
  `ndd` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `userMAJ` varchar(30) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `pswd` varchar(100) NOT NULL,
  `UserType` varchar(100) NOT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `Prenom` varchar(100) DEFAULT NULL,
  `Nom` varchar(100) DEFAULT NULL,
  `Telephone` varchar(100) DEFAULT NULL,
  `adresse` varchar(100) DEFAULT NULL,
  `theme` varchar(100) DEFAULT NULL,
  `point` varchar(100) DEFAULT '0',
  `jeu` varchar(100) DEFAULT NULL,
  `ptsactu` int(11) DEFAULT 0,
  `carteamodif` varchar(100) DEFAULT NULL,
  `oldcardname` varchar(100) DEFAULT NULL,
  `graph` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user`, `userMAJ`, `mail`, `pswd`, `UserType`, `pays`, `Prenom`, `Nom`, `Telephone`, `adresse`, `theme`, `point`, `jeu`, `ptsactu`, `carteamodif`, `oldcardname`, `graph`) VALUES
(1, 'dany', 'Dany', 'day@live.fr', '$2y$12$Ml.W3ydFMUmKu4FEP.aWc.ZSimpWMTnqn8NLS0oyh1JCOl59bO.y2', '3', NULL, NULL, NULL, NULL, NULL, NULL, '127', 'Anglais Basique 1', 1, '87', NULL, '4'),
(2, 'redacteur', 'redacteur', 'redacteur@gmail.com', '$2y$12$rbG3wfU5LqxZ7I8DYcS6JOsBM2zbwFqn0fJJVoiFqO/WngQDCGXW6', '2', NULL, NULL, NULL, NULL, NULL, NULL, '33', 'Deck Test', 1, '38', NULL, '1'),
(36, 'admin', 'admin', 'bab@yopmail.com', '$2y$12$OZ0X2ePbn1VP63kVH67QaO5CmtgYEEZBgexApzUpg8DBH8kVDQWrK', '3', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, 0, NULL, NULL, NULL),
(38, 'olver', 'Olver', 'ddhzdb@jndzjd.fr', '$2y$12$cxWnO5nPsOdsxymNsWK1fO7isG/Mtn0T7QkRC.E7A8RxJOxFArhde', '1', NULL, NULL, NULL, NULL, NULL, NULL, '6', 'Anglais Basique 1', 0, NULL, NULL, NULL),
(39, 'babou', 'babou', 'babou@gmail.com', '$2y$12$sY4YlzFrpe72aBiy9N2CHevZ3.AuyRVx5CNoGv0egIAkX4M/nkAW6', '2', NULL, NULL, NULL, NULL, NULL, NULL, '0', 'Anglais Basique 1', 0, '38', 'Comment dit-on ordinateur en anglais ?', '4'),
(40, 'rayan', 'rayan', 'rayan@gam.com', '$2y$12$G4vM57j5ebOe3b83iMD4s.6/D0a7vSSrTU2PsceRaHIGgc3zQoef6', '1', NULL, NULL, NULL, NULL, NULL, NULL, '0', 'Anglais Basique 1', 0, NULL, NULL, ''),
(41, 'louis', 'louis', 'louis@louis.fr', '$2y$12$q07MxOnscXh4wQNf2Yj9quApeXS.VbDeLkKsRAJiaEEBZ7M10Wra2', '2', NULL, NULL, NULL, NULL, NULL, NULL, '7', 'Anglais Basique 1', 0, NULL, NULL, '1'),
(43, 'utili', 'utili', 'utili@gmail.com', '$2y$12$womDImQ64Y1dxnYrHGzdlOjbpJBBlnS1RTf.pHuzoixmPF4jPsfQG', '1', NULL, NULL, NULL, NULL, NULL, NULL, '1', 'Anglais Basique 1', 0, NULL, NULL, '4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartes`
--
ALTER TABLE `cartes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deck`
--
ALTER TABLE `deck`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `JeuEnCours`
--
ALTER TABLE `JeuEnCours`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cartes`
--
ALTER TABLE `cartes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `deck`
--
ALTER TABLE `deck`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `JeuEnCours`
--
ALTER TABLE `JeuEnCours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
