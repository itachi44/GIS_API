-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : Dim 05 sep. 2021 à 18:09
-- Version du serveur :  5.7.30
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données : `Districts`
--

-- --------------------------------------------------------

--
-- Structure de la table `centroids79districts`
--

CREATE TABLE `centroids79districts` (
  `id` int(11) NOT NULL,
  `region` varchar(100) NOT NULL,
  `code_region` varchar(100) NOT NULL,
  `code_district` varchar(100) NOT NULL,
  `longitude` varchar(100) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `district_sanitaire` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `centroids79districts`
--

INSERT INTO `centroids79districts` (`id`, `region`, `code_region`, `code_district`, `longitude`, `latitude`, `district_sanitaire`) VALUES
(1, 'DAKAR', 'DAK', 'DKC', '-17.4332', '14.712', 'DAKAR-CENTRE'),
(2, 'DAKAR', 'DAK', 'DKN', '-17.449', '14.7363', 'DAKAR-NORD'),
(3, 'DAKAR', 'DAK', 'DKO', '-17.4832', '14.7303', 'DAKAR-OUEST'),
(4, 'DAKAR', 'DAK', 'DKS', '-17.4455', '14.6743', 'DAKAR-SUD'),
(5, 'DAKAR', 'DAK', 'DIA', '-17.1722', '14.7101', 'DIAMNIADIO'),
(6, 'DAKAR', 'DAK', 'GUE', '-17.3946', '14.7751', 'GUEDIAWAYE'),
(7, 'DAKAR', 'DAK', 'KMA', '-17.3273', '14.7862', 'KEUR MASSAR'),
(8, 'DAKAR', 'DAK', 'MBA', '-17.3364', '14.7474', 'MBAO'),
(9, 'DAKAR', 'DAK', 'PIK', '-17.3983', '14.7531', 'PIKINE'),
(10, 'DAKAR', 'DAK', 'RUF', '-17.2274', '14.7972', 'RUFISQUE'),
(11, 'DAKAR', 'DAK', 'SAN', '-17.22083423', '14.80656994', 'SANGALKAM'),
(12, 'DAKAR', 'DAK', 'YEU', '-17.35403951', '14.77684264', 'YEUMBEUL'),
(13, 'DIOURBEL', 'DIO', 'BAM', '-16.4831', '14.8015', 'BAMBEY'),
(14, 'DIOURBEL', 'DIO', 'DIO', '-16.2164', '14.7665', 'DIOURBEL'),
(15, 'DIOURBEL', 'DIO', 'MBK', '-15.8343', '14.7458', 'MBACKE'),
(16, 'DIOURBEL', 'DIO', 'TOU', '-15.8227', '14.8746', 'TOUBA'),
(17, 'FATICK', 'FAT', 'DIF', '-16.63', '14.1691', 'DIOFFIOR'),
(18, 'FATICK', 'FAT', 'FAT', '-16.3963', '14.3904', 'FATICK'),
(19, 'FATICK', 'FAT', 'FOU', '-16.6087', '13.9354', 'FOUNDIOUGNE'),
(20, 'FATICK', 'FAT', 'GOS', '-15.8381', '14.5262', 'GOSSAS'),
(21, 'FATICK', 'FAT', 'NIA', '-16.4018', '14.4976', 'NIAKHAR'),
(22, 'FATICK', 'FAT', 'PAS', '-16.3586', '14.0139', 'PASSY'),
(23, 'FATICK', 'FAT', 'SOK', '-16.3889', '13.7252', 'SOKONE'),
(24, 'KAFFRINE', 'KAF', 'BIR', '-15.6822', '14.0496', 'BIREKELANE'),
(25, 'KAFFRINE', 'KAF', 'KAF', '-15.4464', '14.1287', 'KAFFRINE'),
(26, 'KAFFRINE', 'KAF', 'KOU', '-14.8571', '14.2034', 'KOUNGHEUL'),
(27, 'KAFFRINE', 'KAF', 'MHD', '-15.254', '14.3546', 'MALEM HODAR'),
(28, 'KAOLACK', 'KAO', 'GUI', '-15.9128', '14.2821', 'GUINGUINEO'),
(29, 'KAOLACK', 'KAO', 'KAO', '-16.2067', '14.2327', 'KAOLACK'),
(30, 'KAOLACK', 'KAO', 'NDO', '-16.0104', '13.9996', 'NDOFFANE'),
(31, 'KAOLACK', 'KAO', 'NIO', '-15.834', '13.7325', 'NIORO DU RIP'),
(32, 'ZIGUINCHOR', 'ZIG', 'BIG', '-16.1607', '12.9065', 'BIGNONA'),
(33, 'ZIGUINCHOR', 'ZIG', 'DIL', '-16.5957', '12.926', 'DIOULOULOU'),
(34, 'ZIGUINCHOR', 'ZIG', 'OUS', '-16.6154', '12.4742', 'OUSSOUYE'),
(35, 'ZIGUINCHOR', 'ZIG', 'TKE', '-16.4628', '12.7395', 'THIONK-ESSYL'),
(36, 'ZIGUINCHOR', 'ZIG', 'ZIG', '-16.2222', '12.5158', 'ZIGUINCHOR'),
(37, 'KOLDA', 'KOL', 'KOL', '-14.6557', '12.8548', 'KOLDA'),
(38, 'KOLDA', 'KOL', 'MYF', '-14.865', '13.214', 'MEDINA YORO FOULAH'),
(39, 'KOLDA', 'KOL', 'VEL', '-13.8747', '12.9849', 'VELINGARA'),
(40, 'LOUGA', 'LOU', 'COK', '-15.9608', '15.4924', 'COKI'),
(41, 'LOUGA', 'LOU', 'DAH', '-15.4139', '15.5104', 'DAHRA'),
(42, 'LOUGA', 'LOU', 'DAR', '-15.9973', '15.1521', 'DAROU MOUSTY'),
(43, 'LOUGA', 'LOU', 'KEB', '-16.4252', '15.4092', 'KEBEMER'),
(44, 'LOUGA', 'LOU', 'KMS', '-15.8368', '15.8392', 'KEUR MOMAR SARR'),
(45, 'LOUGA', 'LOU', 'LIN', '-14.9902', '15.2089', 'LINGUERE'),
(46, 'LOUGA', 'LOU', 'LOU', '-16.1891', '15.5848', 'LOUGA'),
(47, 'LOUGA', 'LOU', 'SAK', '-16.3054', '15.7759', 'SAKAL'),
(48, 'MATAM', 'MAT', 'KAN', '-13.1284', '14.974', 'KANEL'),
(49, 'MATAM', 'MAT', 'MAT', '-13.5768', '15.6363', 'MATAM'),
(50, 'MATAM', 'MAT', 'RAN', '-14.1371', '15.0258', 'RANEROU'),
(51, 'MATAM', 'MAT', 'TIL', '-13.7393', '15.8906', 'THILOGNE'),
(52, 'SAINT-LOUIS', 'STL', 'DAG', '-15.5643', '16.2545', 'DAGANA'),
(53, 'SAINT-LOUIS', 'STL', 'PET', '-14.232', '16.0428', 'PETE'),
(54, 'SAINT-LOUIS', 'STL', 'POD', '-14.9028', '16.3225', 'PODOR'),
(55, 'SAINT-LOUIS', 'STL', 'RTL', '-16.0945', '16.2826', 'RICHARD-TOLL'),
(56, 'SAINT-LOUIS', 'STL', 'STL', '-16.3723', '15.9556', 'SAINT-LOUIS'),
(57, 'SEDHIOU', 'SED', 'BOU', '-15.6028', '13.1336', 'BOUNKILING'),
(58, 'SEDHIOU', 'SED', 'GOD', '-15.5101', '12.633', 'GOUDOMP'),
(59, 'SEDHIOU', 'SED', 'SED', '-15.6152', '12.8057', 'SEDHIOU'),
(60, 'TAMBACOUNDA', 'TAM', 'BAK', '-12.4451', '14.8009', 'BAKEL'),
(61, 'TAMBACOUNDA', 'TAM', 'DIM', '-12.6456', '13.7643', 'DIANKE MAKHAN'),
(62, 'TAMBACOUNDA', 'TAM', 'GOU', '-13.1056', '14.1476', 'GOUDIRY'),
(63, 'TAMBACOUNDA', 'TAM', 'KID', '-12.2', '14.0005', 'KIDIRA'),
(64, 'TAMBACOUNDA', 'TAM', 'KMP', '-14.3397', '14.1304', 'KOUMPENTOUM'),
(65, 'TAMBACOUNDA', 'TAM', 'MAK', '-14.0967', '13.6515', 'MAKA COULIBANTANG'),
(66, 'TAMBACOUNDA', 'TAM', 'TAM', '-13.3769', '13.4678', 'TAMBACOUNDA'),
(67, 'THIES', 'THI', 'JOA', '-16.792', '14.2592', 'JOAL-FADIOUTH'),
(68, 'THIES', 'THI', 'KHO', '-16.7125', '14.7792', 'KHOMBOLE'),
(69, 'THIES', 'THI', 'MBO', '-16.9504', '14.4566', 'MBOUR'),
(70, 'THIES', 'THI', 'MEK', '-16.4308', '15.1249', 'MECKHE'),
(71, 'THIES', 'THI', 'POP', '-17.0718', '14.6164', 'POPENGUINE'),
(72, 'THIES', 'THI', 'POU', '-17.0112', '14.7993', 'POUT'),
(73, 'THIES', 'THI', 'THD', '-16.7067', '14.513', 'THIADIAYE'),
(74, 'THIES', 'THI', 'THI', '-16.8726', '14.6492', 'THIES'),
(75, 'THIES', 'THI', 'TIV', '-16.8348', '15.0744', 'TIVAOUANE'),
(76, 'KEDOUGOU', 'KED', 'KED', '-12.4349', '12.7655', 'KEDOUGOU'),
(77, 'KEDOUGOU', 'KED', 'SAL', '-12.7979', '12.6003', 'SALEMATA'),
(78, 'KEDOUGOU', 'KED', 'SAR', '-11.8293', '12.9726', 'SARAYA'),
(79, 'FATICK', 'FAT', 'DIK', '', '', 'DIAKHAO'),
(80, 'region', 'code_region', 'code_district', 'lng', 'lat', 'district'),
(81, 'DAKAR', 'DAK', 'COV', '', '', 'NA'),
(82, 'DAKAR', 'DAK', 'SNC', '', '', 'SENCOV');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id_comment` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `district_data`
--

CREATE TABLE `district_data` (
  `id_district_data` int(11) NOT NULL,
  `MCD_name` varchar(100) NOT NULL,
  `MCD_tel` varchar(20) NOT NULL,
  `allocated_range` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `starting_time` time NOT NULL,
  `ending time` time NOT NULL,
  `comment` text NOT NULL,
  `used_range` varchar(100) NOT NULL,
  `received_sample` int(11) NOT NULL,
  `tested_sample` int(11) NOT NULL,
  `non-conforming_sample` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Marked_completed`
--

CREATE TABLE `Marked_completed` (
  `latitude` varchar(150) NOT NULL,
  `longitude` varchar(150) NOT NULL,
  `district` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `Marked_inProgress`
--

CREATE TABLE `Marked_inProgress` (
  `latitude` varchar(150) NOT NULL,
  `longitude` varchar(150) NOT NULL,
  `district` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `resource`
--

CREATE TABLE `resource` (
  `id_resource` int(11) NOT NULL,
  `internet_volume` int(11) NOT NULL,
  `number_of_tablets_used` int(11) NOT NULL,
  `id_district_data` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `team`
--

CREATE TABLE `team` (
  `id_team` int(11) NOT NULL,
  `team_name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `telephone` varchar(100) NOT NULL,
  `id_team` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `centroids79districts`
--
ALTER TABLE `centroids79districts`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `user_comment_fk` (`id_user`);

--
-- Index pour la table `district_data`
--
ALTER TABLE `district_data`
  ADD PRIMARY KEY (`id_district_data`),
  ADD KEY `user_data_fk` (`id_user`);

--
-- Index pour la table `resource`
--
ALTER TABLE `resource`
  ADD PRIMARY KEY (`id_resource`),
  ADD KEY `district_data_resource_fk` (`id_district_data`);

--
-- Index pour la table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id_team`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `user_team_fk` (`id_team`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `centroids79districts`
--
ALTER TABLE `centroids79districts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `district_data`
--
ALTER TABLE `district_data`
  MODIFY `id_district_data` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `resource`
--
ALTER TABLE `resource`
  MODIFY `id_resource` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `team`
--
ALTER TABLE `team`
  MODIFY `id_team` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `user_comment_fk` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Contraintes pour la table `district_data`
--
ALTER TABLE `district_data`
  ADD CONSTRAINT `user_data_fk` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Contraintes pour la table `resource`
--
ALTER TABLE `resource`
  ADD CONSTRAINT `district_data_resource_fk` FOREIGN KEY (`id_district_data`) REFERENCES `district_data` (`id_district_data`);

--
-- Contraintes pour la table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_team_fk` FOREIGN KEY (`id_team`) REFERENCES `team` (`id_team`);
