-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 07 Août 2018 à 20:32
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
(278, 'BigAlex', 'Nos enfants n’apprendront plus un métier pour une vie.  <br />\r\nIls devront avant tout apprendre à apprendre. <br />\r\nApprendre à changer.', '2018-08-08 00:02:16', NULL, 1, 104),
(279, 'BigAlex', 'Apprendre à garder sans cesse un jeu de jambes suffisant sans lequel les plus faibles resteront à la traîne. Voilà l’avenir de l’école, et plus tard, de la formation continue: jamais les sciences humaines n’auront connu pareil avènement.', '2018-08-08 00:03:00', NULL, 1, 103),
(281, 'LechatPoilu', 'Le Web est devenu une bibliothèque d’Alexandrie à ciel ouvert et sans limite de rayonnage!!', '2018-08-08 00:04:07', '2018-08-08 00:08:38', 1, 104),
(282, 'LechatPoilu', 'Le mouvement en cours, nous l’avons dit, repousse l’homme dans ses limites qui sont bien, pourtant, quasi infinies et de ce fait inaccessibles à la machine.', '2018-08-08 00:04:52', NULL, 1, 103),
(283, 'LechatPoilu', 'On le comprend, avec l’IA, il semble que le dernier bastion que l’homme croyait imprenable commence à présenter de sérieuse faille dans ses remparts. Mais faut-il s’inquiéter de ce mouvement, inéluctable qui plus est ?', '2018-08-08 00:05:09', NULL, 1, 102),
(284, 'BigAlex', 'L’homme devra se recentrer sur ce qui fait qu’il est homme et pas autre chose: changement, créativité, inventivité, goût des autres, etc…..', '2018-08-08 00:15:35', NULL, 1, 102);

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
(102, 'Greatalf', 'INTELLIGENCE ARTIFICIELLE VOCALE ET SEO', 'DES CLIENTS AVIDES D’IA VOCALE', 'Cette étude de Cap Gemini de fin d’année 2017 résonne comme un coup de tonnerre dans le Landerneau du commerce. Et, fait marquant, c’est autant le retail de type « brick-and-mortar » qui en sera impacté que l’intouchable digital retail, plus habitué, lui, à asséner les coups qu’à les recevoir. En substance, elle nous indique que l’Intelligence Artificielle est en passe de disrupter l’univers du retail online comme offline au moins autant qu’elle disrupte la santé, la finance ou bien encore l’industrie.\r\n\r\nNous avions annoncé à plusieurs reprises par le passé (voir cet article) l’avènement du Vocal First mais nous ne mesurions honnêtement pas nécessairement à quel point les consommateurs sont en réalité déjà très près pour le tsunami.', '2018-08-07 23:50:07', NULL),
(103, 'Greatalf', 'COMMENT CONDUIRE UN PROJET D’IA', 'IA: LE MYTHE DE LA TECHNO', 'D’abord, soyez rassuré. On en parle beaucoup mais peu de gens savent ce dont il s’agit vraiment. Les compétences sont rares en la matière et, en tant que non-sachant – vous n’êtes certainement pas un cas isolé (là, on se sent déjà moins seul, non ? ;-).\r\n\r\nSi de nombreuses entreprises ont leur patron des systèmes d’information ou délègue à telle ou telle agence cette prestation en externe, les pros du numérique ne sont pour l’heure eux-mêmes généralement pas des pros de l’IA (WebDev, AdminSys, DevOp …) et le Chief Artificial Intelligence Officer (le futur métier à la mode dont on parle) n’est à ce jour qu’un concept.\r\n\r\nPartant de là, comment donc implémenter de l’IA dans mes systèmes, simplement ? Puis-je acheter le soft quelque part et le lancer sur mon réseau via un fichier autoexe?\r\n\r\nQue nenni.  On n’installe pas une techno liée à l’IA comme on achèterait un logiciel « sur l’étagère ». S’il suffit de se procurer un PC pour se servir d’un Windows préinstallé, l’on ne s’équipe en revanche pas d’IA comme d’un Operating System « ready-to-go ». ', '2018-08-07 23:52:04', NULL),
(104, 'Greatalf', 'QUAND L’IA CONTRAINT L’HOMME À TOUT REVOIR', 'L’APTITUDE AU CHANGEMENT, AUX SOURCES DE L’HOMME', 'Revenons d’abord sur ce dernier point. Il est étonnant de voir à quel point l’aptitude au changement est tout à la fois une caractéristique clef de l’homme et en même temps ce à quoi il est généralement le plus farouchement opposé.\r\nPensez-y: de tout temps, l’homme a réussi à survivre dans les conditions les plus abominables: quel que fut le climat (froid, aridité, chaleurs extrêmes,…), quels que furent les congénères (bêtes sauvages, barbares,..) quelles que furent les conditions (pauvreté, famines, …), l’homme s’est adapté. Toujours contraint et forcé de le faire, mais il s’est adapté. C’est peut-être même l’une des qualités qui nous distingue le plus dans la nature: nous sommes des êtres adaptables, nous bougeons, nous survivons. Mais, toujours, parce que nous n’avons pas le choix. Car le changement nous répugne. La réticence au changement à l’échelle du monde, de la famille ou de l’entreprise est aussi pourtant l’une des choses qui nous caractérise le plus.', '2018-08-08 00:00:29', NULL);

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
(215, 'Legrand', 'Alexandre', 'alexandre.legrand@gmail.com', 'BigAlex', '$2y$10$vhzEkUP8rp3mGLUaASJuju4rKdCvqR/5YpGUpS4e8mXgsGej1yrxC', 1, NULL, '2018-08-07 23:37:39'),
(216, 'Lechat', 'Sylvain', 'sylvain-lechat@gmail.com', 'LechatPoilu', '$2y$10$7kzToRvHyGE8GI/OExLpZOz2MH5WTzNeM5NMH0STH5OBsToPqm7QS', 1, NULL, '2018-08-07 23:43:05'),
(211, 'Avril', 'Laurent', 'avril.laurent974@yahoo.fr', 'Greatalf', '$2y$10$VvXSyTb.sSawgZuFgwi2q.vRFLq7possl36S8n6npvYUxuXHBBBs6', 2, NULL, '2018-07-13 11:49:09');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;
--
-- AUTO_INCREMENT pour la table `connect`
--
ALTER TABLE `connect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;
--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
