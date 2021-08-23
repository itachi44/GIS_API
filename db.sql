-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 23 août 2021 à 15:41
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
  `longitude` varchar(12) DEFAULT NULL,
  `latitude` varchar(11) DEFAULT NULL,
  `district_sanitaire` varchar(18) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `centroids79districts`
--

INSERT INTO `centroids79districts` (`longitude`, `latitude`, `district_sanitaire`) VALUES
('Longitude', 'Latitude', 'District_sanitaire'),
('-16.22216687', '12.51580947', 'ZIGUINCHOR'),
('-16.61544292', '12.47418596', 'OUSSOUYE'),
('-16.16068321', '12.906471', 'BIGNONA'),
('-16.59570466', '12.92597876', 'DIOULOULOU'),
('-16.46284813', '12.73949185', 'THIONCK-ESSYL'),
('-17.07178086', '14.61639591', 'POPENGUINE'),
('-16.83484927', '15.07439107', 'TIVAOUANE'),
('-16.43077657', '15.12489901', 'MEKHE'),
('-16.95041897', '14.45658477', 'MBOUR'),
('-16.71251518', '14.77918049', 'KHOMBOLE'),
('-17.01117706', '14.79928841', 'POUT'),
('-16.79197549', '14.25919244', 'JOAL'),
('-16.70671389', '14.51300549', 'THIADIAYE'),
('-16.87260553', '14.64921441', 'THIES'),
('-14.31108864', '14.18041567', 'KOUPENTOUM'),
('-12.19997906', '14.00050946', 'KIDIRA'),
('-12.4450916', '14.80085431', 'BAKEL'),
('-13.37693037', '13.46782841', 'TAMBACOUNDA'),
('-12.64558916', '13.76428314', 'DIANKHEMAKHAN'),
('-13.10561135', '14.14755557', 'GOUDIRY'),
('-14.23677841', '13.6626569', 'MAKACOULIBANTANG'),
('-15.51013299', '12.63298302', 'GOUDOMP'),
('-15.60281623', '13.13357149', 'BOUNKILING'),
('-15.61518508', '12.80570351', 'SEDHIOU'),
('-16.37399598', '15.95504335', 'SAINT-LOUIS'),
('-16.09469256', '16.28198715', 'RICHARD-TOLL'),
('-14.90280357', '16.32247122', 'PODOR'),
('-14.23199106', '16.04282155', 'PETE'),
('-15.56426913', '16.25452182', 'DAGANA'),
('-16.30539863', '15.77589175', 'SAKAL'),
('-16.18908936', '15.58484462', 'LOUGA'),
('-15.83675366', '15.83922571', 'KEUR-MOMAR-SARR'),
('-16.42524839', '15.40917919', 'KEBEMER'),
('-14.99023961', '15.20891563', 'LINGUERE'),
('-15.4139388', '15.51035368', 'DAHRA'),
('-15.9973071', '15.15214191', 'DAROU-MOUSTY'),
('-15.96076697', '15.49239133', 'COKI'),
('-14.86500281', '13.21400568', 'MEDINA-YORO-FOULAH'),
('-13.87470385', '12.98490885', 'VELINGARA'),
('-14.65574686', '12.85479621', 'KOLDA'),
('-11.82930724', '12.97256958', 'SARAYA'),
('-12.79785214', '12.60031655', 'SALEMATA'),
('-12.43488023', '12.76554789', 'KEDOUGOU'),
('-15.83397989', '13.73250701', 'NIORO DU RIP'),
('-16.01041495', '13.9995786', 'NDOFFANE'),
('-15.91284708', '14.2820569', 'GUINGUINEO'),
('-16.20665609', '14.23270908', 'KAOLACK'),
('-15.25398256', '14.35463082', 'MALEM-HODDAR'),
('-14.85714566', '14.20339805', 'KOUNGUEUL'),
('-15.44638518', '14.12869694', 'KAFFRINE'),
('-15.68222738', '14.04959593', 'BIRKELANE'),
('-16.21636758', '14.76648767', 'DIOURBEL'),
('-16.48308766', '14.80147197', 'BAMBEY'),
('-15.822732', '14.87459921', 'TOUBA'),
('-15.83429212', '14.74584831', 'MBACKE'),
('-17.22083423', '14.80656994', 'SANGALKAM'),
('-17.39638223', '14.75287578', 'PIKINE'),
('-17.31898822', '14.78915991', 'KEUR-MASSAR'),
('-17.17218771', '14.71005856', 'DIAMNIADIO'),
('-17.3946307', '14.77505614', 'GUEDIAWAYE'),
('-17.33511537', '14.74736364', 'MBAO'),
('-17.44903397', '14.73634429', 'DAKAR-NORD'),
('-17.44547783', '14.67426822', 'DAKAR-SUD'),
('-17.43321656', '14.71200008', 'DAKAR-CENTRE'),
('-17.48317313', '14.73031601', 'DAKAR-OUEST'),
('-14.13708972', '15.02581041', 'RANEROU'),
('-13.12836149', '14.97401521', 'KANEL'),
('-13.73934713', '15.89056804', 'THILOGNE'),
('-13.57683986', '15.63622875', 'MATAM'),
('-16.38891057', '13.72519766', 'SOKONE'),
('-16.35856856', '14.01392341', 'PASSY'),
('-16.61166729', '13.93467222', 'FOUNDIOUGNE'),
('-16.62792796', '14.17373309', 'DIOFFIOR'),
('-16.40182859', '14.49762123', 'NIAKHAR'),
('-15.83807076', '14.52623571', 'GOSSASS'),
('-16.28392518', '14.39015081', 'DIAKHAO'),
('-17.35403951', '14.77684264', 'YEUMBEUL'),
('-16.52920706', '14.39058989', 'FATICK'),
('-17.27537563', '14.72877149', 'RUFISQUE');

-- --------------------------------------------------------

--
-- Structure de la table `Marked_completed`
--

CREATE TABLE `Marked_completed` (
  `latitude` varchar(150) NOT NULL,
  `longitude` varchar(150) NOT NULL,
  `district` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Marked_completed`
--

INSERT INTO `Marked_completed` (`latitude`, `longitude`, `district`) VALUES
('14.72877149', '-17.27537563', 'RUFISQUE');

-- --------------------------------------------------------

--
-- Structure de la table `Marked_inProgress`
--

CREATE TABLE `Marked_inProgress` (
  `latitude` varchar(150) NOT NULL,
  `longitude` varchar(150) NOT NULL,
  `district` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Marked_inProgress`
--

INSERT INTO `Marked_inProgress` (`latitude`, `longitude`, `district`) VALUES
('14.71005856', '-17.17218771', 'DIAMNIADIO'),
('14.72877149', '-17.27537563', 'RUFISQUE');
