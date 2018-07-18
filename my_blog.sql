-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 18 Juillet 2018 à 14:15
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `my_blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `author` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `addDate` datetime NOT NULL,
  `updateDate` datetime DEFAULT NULL,
  `validationComment` tinyint(1) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `comments`
--

INSERT INTO `comments` (`id`, `author`, `content`, `addDate`, `updateDate`, `validationComment`, `post_id`) VALUES
(259, 'Greatalf', 'Ok, ce commentaire est maintenant modifié correctement ;)', '2018-07-09 11:45:06', '2018-07-09 19:50:48', 1, 100),
(260, 'Greatalf', '1er test', '2018-07-09 16:50:09', '2018-07-09 19:53:39', 1, 100),
(261, 'Greatalf', '2test 789', '2018-07-09 19:50:55', '2018-07-09 21:43:10', 1, 100),
(262, 'aerfgv', 'test', '2018-07-11 11:41:31', NULL, 1, 101),
(264, 'Greatalf', 'Je ne sais pas pourquoi... !', '2018-07-16 17:05:12', '2018-07-16 17:05:53', 1, 86);

-- --------------------------------------------------------

--
-- Structure de la table `connect`
--

CREATE TABLE `connect` (
  `id` int(11) NOT NULL,
  `userIp` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(5) UNSIGNED NOT NULL,
  `author` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `chapo` text NOT NULL,
  `content` longtext NOT NULL,
  `addDate` datetime NOT NULL,
  `updateDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `posts`
--

INSERT INTO `posts` (`id`, `author`, `title`, `chapo`, `content`, `addDate`, `updateDate`) VALUES
(85, 'Greatalf', 'D\'où vient-il?', 'Contrairement à une opinion répandue, le Lorem Ipsum n\'est pas simplement du texte aléatoire. Il trouve ses racines dans une oeuvre de la littérature latine classique datant de 45 av. J.-C., le rendant vieux de 2000 ans.', 'Un professeur du Hampden-Sydney College, en Virginie, s\'est intéressé à un des mots latins les plus obscurs, consectetur, extrait d\'un passage du Lorem Ipsum, et en étudiant tous les usages de ce mot dans la littérature classique, découvrit la source incontestable du Lorem Ipsum. Il provient en fait des sections 1.10.32 et 1.10.33 du &quot;De Finibus Bonorum et Malorum&quot; (Des Suprêmes Biens et des Suprêmes Maux) de Cicéron. Cet ouvrage, très populaire pendant la Renaissance, est un traité sur la théorie de l\'éthique. Les premières lignes du Lorem Ipsum, &quot;Lorem ipsum dolor sit amet...&quot;, proviennent de la section 1.10.32.', '2018-06-17 23:24:46', '2018-06-20 23:00:05'),
(86, 'Greatalf', 'Pourquoi l\'utiliser?', 'On sait depuis longtemps que travailler avec du texte lisible et contenant du sens est source de distractions, et empêche de se concentrer sur la mise en page elle-même.', 'L\'avantage du Lorem Ipsum sur un texte générique comme \'Du texte. Du texte. Du texte.\' est qu\'il possède une distribution de lettres plus ou moins normale, et en tout cas comparable avec celle du français standard. De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour \'Lorem Ipsum\' vous conduira vers de nombreux sites qui n\'en sont encore qu\'à leur phase de construction. Plusieurs versions sont apparues avec le temps, parfois par accident, souvent intentionnellement (histoire d\'y rajouter de petits clins d\'oeil, voire des phrases embarassantes).', '2018-06-17 23:24:52', '2018-06-20 22:58:56'),
(100, 'Greatalf', 'lihgkfjtdhrgcn', 'rydthggtr', 'tuyikul', '2018-07-09 00:27:11', '2018-07-09 13:36:07'),
(101, 'Greatalf', 'Un nouvel article', 'Le chapo du nouvel article', 'le corps du nouvel article!!\r\nytudyfcgvhbkjl', '2018-07-09 21:44:35', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rank` int(1) NOT NULL,
  `confirmation_token` varchar(60) DEFAULT NULL,
  `confirmedAt` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `lastname`, `firstname`, `email`, `username`, `password`, `rank`, `confirmation_token`, `confirmedAt`) VALUES
(55, 'Jean', 'Sérien', 'jean-serien@gmail.com', 'Jean', '$2y$10$Hq0bjLvMvmYzoVzgMlEjXux0ORdSbDUe5BD55qKQC2q6CYCCnuLcG', 1, NULL, '2018-07-11 09:47:00'),
(195, 'Avril', 'Laurent', 'avril.laurent@gmail.fr', 'Greatalfus', '$2y$10$fd9KBzO2zNrr3N8sSSKhJ.o3dGDiaXwoFZnv4g4QMp64UmIKFM4rC', 2, NULL, '2018-07-11 11:22:14'),
(207, 'Payet', 'Julien', 'pay.juju@gmail.com', 'Chinois', '$2y$10$wUlzvdbiyLLDjOdTz2p1zubOc3TZGYM5dOpppzhWRAEXoeG3A/nd.', 1, NULL, '2018-07-13 11:18:44'),
(208, 'Céline', 'Lacaze', 'cece-lac@gmail.com', 'cécéLaBénie!!', '$2y$10$HHrmk1NsoknCbSbh0OCdKulde9/tvWvUT9lu4ZUhxQE2uNQy5R85u', 1, NULL, '2018-07-13 11:31:30'),
(201, 'efrgthj', 'erfghj', 'afgh@poi.ki', 'aerfgv', '$2y$10$Hq0bjLvMvmYzoVzgMlEjXux0ORdSbDUe5BD55qKQC2q6CYCCnuLcG', 1, NULL, '2018-07-11 11:41:01'),
(200, 'efrgthj', 'erfghj', 'fgh@poi.ki', 'erfgv', '$2y$10$1AjDXMXOzcCKXJhxLJUQFefyErGe1XbqiPjSe0N/CBz3cvAIJ/gdS', 1, NULL, '2018-07-11 11:39:15'),
(211, 'Avril', 'Laurent', 'avril.laurent974@yahoo.fr', 'Greatalf', '$2y$10$TwoPeF9OwkffhJtKkV35j.5OYcUhGZMShGQOcLEs25xr3lm2Zx716', 2, NULL, '2018-07-13 11:49:09'),
(210, 'Céline', 'Lacaze', 'cece-lacazette@gmail.com', 'cécéLacaze!!', '$2y$10$e5StSDKbg6mCkITAtGDAQOGqGggGVtm5XuI0/0GrrfsYYT/KPW9PK', 1, '7jirOIxH2OVrFvcwomHMbLYmyLFjmoS69gDatqleJqtNw0pLZpbDz71xW!nQ', NULL);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Index pour la table `connect`
--
ALTER TABLE `connect`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`id`) USING BTREE;

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;
--
-- AUTO_INCREMENT pour la table `connect`
--
ALTER TABLE `connect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;
--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
