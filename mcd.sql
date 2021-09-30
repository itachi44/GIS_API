-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 30, 2021 at 02:13 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `districts`
--

-- --------------------------------------------------------

--
-- Table structure for table `mcd`
--

DROP TABLE IF EXISTS `mcd`;
CREATE TABLE IF NOT EXISTS `mcd` (
  `id_mcd` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `region_medicale` varchar(255) NOT NULL,
  `id_district` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_mcd`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mcd`
--

INSERT INTO `mcd` (`id_mcd`, `nom`, `telephone`, `mobile`, `fax`, `email`, `region_medicale`, `id_district`) VALUES
(1, 'Dr Ndeye Maguette NDIAYE (MCR)', '33 832 29 19', '77 640 05 22', '33 832 59 04', 'magattendome@gmail.com', 'Dakar', NULL),
(2, 'Dr Aly Ngone TAMBEDOU (MCR Adjoint)', '', '77 535 58 64', '', 'tambedou_aly@yahoo.fr ', 'Dakar', NULL),
(3, 'Dr Daba SY DIAO', '33 824 33 57', '77 550 71 72', '33 835 98 89', 'dabasydiao@yahoo.fr/districtnorddakar@yahoo.fr', 'Dakar', 2),
(4, 'Maty Diouf ', '33 822 86 32', '77 649 07 12', '33 822 86 32', '  jacndour@yahoo.fr/districtdakarsud15@gmail.com', 'Dakar', 4),
(5, 'Dr Mbengue ', '', '77 551 05 31 ', '', 'modouthioro@gmail.com/centredistrict@yahoo.fr', 'Dakar', 1),
(6, 'Dr Abdou Karim DIOP ', '33 820 12 28', '77 648 23 15', '33 820 35 77', 'akdnse@gmail.com/dsdouest@gmail.com ', 'Dakar', 3),
(7, 'Dr Mamadou DIENG', '33 971 10 23/33 971 28 60  ', '77 501 90 83/76 501 90 8/76 682 33 073', '33 971 28 60/33 971 22 63', 'zeusdieng@yahoo.fr', 'Diourbel', NULL),
(8, 'Dr Moussa NDIAYE', '33 971 14 74', '77 651 05 42', '33 971 14 74', 'tonsndiaye@hotmail.fr', 'Diourbel', 14),
(9, 'Dr Standeur Nabi KALY', '33 973 61 35', '775447330', '33 973 61 35', 'standeurnabikaly@gmail.com', 'Diourbel', 13),
(10, 'Dr Adama AIDARA MBACKE', '33 976 10 40', '77 647 82 91', '33 976 10 40', 'dockarara1@yahoo.fr', 'Diourbel', 15),
(11, 'Dr Ndeye Maguette DIOP', '33 978 13 70', '77 650 08 00', '33 978 93 06', 'cstouba@yahoo.fr ', 'Diourbel', 16),
(12, 'Dr Abib NDIAYE', '33 949 13 61', '77 574 87 52', '33 949 16 57', 'ndiaye_abib@yahoo.fr/regionmedicalefatick@gmail.com/annasall@yahoo.fr', 'Fatick', NULL),
(13, 'Dr Abiboulaye SALL', '33 949 10 97', '77 506 95 76', '33 949 10 97', 'docteurabibsall@gmail.com', 'Fatick', 18),
(14, 'Dr Mamadou NDIAYE', '33 949 83 07', '77 542 65 02', '33 949 83 07', 'districtdiofior@yahoo.fr', 'Fatick', 17),
(15, 'Dr Faly DIOP NDIAYE', '33 948 11 24/33 948 12 28', '77 550 74 80/70 821 76 70', '33 948 11 24', 'ndiayefaly@yahoo.fr', 'Fatick', 19),
(16, 'Dr Ahmadou Bouya NDAO', '33 948 31 15', '77 532 95 08', '33 948 31 15', ' bouyandao2017@gmail.com', 'Fatick', 23),
(17, 'Dr Mbaye THIOYE', '33 948 54 25', '776405093', '', 'mbayethioye72@gmail.com', 'Fatick', 22),
(18, 'Dr Amady BA', '33 947 11 07', '77 541 48 80', '33 947 12 12', 'bamady1@yahoo.fr', 'Fatick', 20),
(19, 'Dr Felix DIOUF', '', '775394954', '', 'diouffelix@gmail.com', 'Fatick', 21),
(20, 'Dr Mamadou Moustapha DIOP', '33 946 17 39', '77556 59 37', '33 946 17 39', 'moustafdiop@yahoo.fr/fakasse@yahoo.fr (77 556 23 87)', 'Kaffrine', NULL),
(21, 'Dr  Ndeye Mbacke KANE', '33 946 10 04', '77 657 58 37', '33 946 10 04', 'mbackekane2007@yahoo.fr ', 'Kaffrine', 25),
(22, 'Dr Ibrahima DIALLO', '33 946 26 55', '77 617 59 93', '', 'dialloocci@yahoo.fr', 'Kaffrine', 27),
(23, 'DR El Hadji Malick NIANG', '33 946 71 29', '77 544 33 17', '', 'drelmalickniang@gmail.com', 'Kaffrine', 26),
(24, 'Dr Papa Birame SECK', '33 946 60 64', '77 502 74 15', '', 'seckpapabirahim@yahoo.fr', 'Kaffrine', 24),
(25, 'Dr Aichatou BARRY', '33 941 15 39/33 941 21 43', '77 656 82 31/70 567 90 66', '33 941 15 39/33 941 21 43', ' amarapapy2000@yahoo.fr/ madamedioufseynabou@yahoo.fr', 'Kaolack', NULL),
(26, 'Dr Niene SECK', '33 941 39 69', '77 533 29 70', '33 941 39 69', 'nieneseck@gmail.com', 'Kaolack', 29),
(27, 'Dr Amadou Mbaye DIOUF', '33 947 31 01', '77 533 54 96/70 208 82 75', '33 947 31 01', 'ambdiouf@yahoo.fr', 'Kaolack', 28),
(28, 'Dr Demba War SALL DIENG', '33 943 22 62', '77 657 57 42', '', 'dembawar75@yahoo.fr', 'Kaolack', 30),
(29, 'Dr Aboubakry   KABA', '33 944 31 04', '77 897 73 69/77 552 03 21', '33 944 31 04', 'aboukaba78@yahoo.fr', 'Kaolack', 31),
(30, 'Dr DIOP', '33 985 18 93/33 985 18 95', '', '33 985 18 95', 'drjoob@yahoo.fr', 'Kedougou', NULL),
(31, 'Dr Fode DANFAKHA (MCA)', '33 985 10 04', '775704991', '33 985 14 25', 'deffode47@gmail.com', 'Kedougou', 76),
(32, 'Dr Evrard Jocelyn Desire KABOU', '33 937 96 93/33 985 70 12', '77 648 93 36/77 954 90 13/77 847 25 25', '33 985 70 12', 'evjodeka@yahoo.fr', 'Kedougou', 78),
(33, 'Dr DIENE', '33 981 91 41', '77 458 59 08', '', 'dienedjibril@yahoo.fr', 'Kedougou', 77),
(34, 'Dr Yaya BALDE', '33 996 11 20/33 996 12 45', '77 654 01 09/77 958 00 51', '33 996 12 45', 'bibakolda@yahoo.fr', 'Kolda', NULL),
(35, 'Dr Souleymane SAGNA', '33 996 11 05', '775480703', '33 996 11 05', 'sagna81souleymane@yahoo.fr', 'Kolda', 37),
(36, 'Dr Omar SANE', '33 997 11 10', '77 645 47 13', '33 997 11 10', 'omarsane77@gmail.com', 'Kolda', 39),
(37, 'Dr Boubacar KANDE', '', '77 565 55 72', '', 'drkande2011@yahoo.fr', 'Kolda', 38),
(38, 'Dr Cheikh Sadibou SENGHOR ', '33 967 12 17/33 967 10 45', '77 650 07 76', '33 967 38 77', 'cheikhssenghor@gmail.com', 'Louga', NULL),
(39, 'Dr Kalidou BA', '33 967 10 24', '775314136', '33 967 10 24', 'kalsdouba@gmail.com', 'Louga', 46),
(40, 'Dr Pape Saliou NDOYE', '33 968 10 05', '778472525', '33 968 40 02', 'dslinguere@hotmail.com/bayezale@hotmail.com', 'Louga', 45),
(41, 'Dr Adou     NDIAYE ', '33 968 61 43', '77 551 05 31', '33 968 60 00', 'modouthioro@gmail.com', 'Louga', 41),
(42, 'Dr Ababacar MBAYE', '33 969 10 01', '77 636 26 19', '33 969 12 04', 'khalifaababacar123@yahoo.fr', 'Louga', 43),
(43, 'Dr Mamadou NDIAYE', '33 969 81 04', '77 443 94 66', '33 969 81 16', 'drndiaye@yahoo.fr', 'Louga', 42),
(44, 'Dr Babacar SALL', '', '77 555 07 75', '', 'sallbabs@yahoo.fr', 'Louga', 44),
(45, 'Dr El Hadj Malick DIOUF', '', '77 419 12 69', '', 'elhadjimalickdiouf20@yahoo.fr', 'Louga', 40),
(46, 'Mame Late MBENGUE', '', '773652334', '', 'mamelate@live.fr', 'Louga', 47),
(47, 'Dr Mama Moussa DIAW', '33 966 66 04', '77 640 26 84', '33 966 66 04', 'dimadiaw@yahoo.fr/astadady@gmail.com', 'Matam', NULL),
(48, 'Dr Latyr DIOUF', '33 966 61 09', '77 551 65 01', '33 966 66 04', 'latyrb@yahoo.fr', 'Matam', 49),
(49, 'Dr Khalifa Ababacar FALL', '33 966 85 74', '775701930', '33 966 85 74', 'fallkhalifaababacar@hotmail.com', 'Matam', 48),
(50, 'Dr Aliou NDOUR', '33 966 34 41', '774276292', '33 966 34 41', 'alguereo@yahoo.fr', 'Matam', 50),
(51, 'DR Mamadou Sarifou BA', '', '77 578 61 81', '', 'sarifouba10@yahoo.fr', 'Matam', 51),
(52, 'Dr Seynabou NDIAYE', '33 961 13 88', '77 545 80 98', '33 961 13 88', 'zeynab43@yahoo.fr/regionmedicalesl@gmail.com', 'Saint-Louis', NULL),
(53, 'Dr SERIGNE AMDY THIAM', '33 961 92 85/33 961 32 29', '77 557 92 69', '33 961 92 85', 'serignamdy@gmail.com', 'Saint-Louis', 56),
(54, 'Dr Hamidou DIALLO ', '33 963 11 24', '77 651 70 44', '33 963 11 24', 'midzodia77@hotmail.fr/districtdagana@yahoo.fr', 'Saint-Louis', 52),
(55, 'Dr Coumba Ndoffene DIOUF', '33 963 32 71/33 960 29 98', '77 650 62 99/76 477 89 57', '33 963 32 71', 'cndiouf@yahoo.fr/ndof27@hotmail.com', 'Saint-Louis', 55),
(56, 'Dr Malick Hanne', '33 965 11 08', '775220321', '33 965 11 08', 'nawndu.e@gmail.com', 'Saint-Louis', 54),
(57, 'Dr Mamadou NDIAYE', '33 965 85 16', '77 274 94 96/76 334 99 77', '33 965 85 16', 'mbolle9@yahoo.fr  ', 'Saint-Louis', 53),
(58, 'Dr Amadou Yeri CAMARA', '33 995 00 68', '77 637 28 10', '33 995 11 13', 'yeri2203@yahoo.fr/mariejeanneassine@yahoo.fr', 'Sedhiou', NULL),
(59, 'Dr Diabele DRAME', '33 995 11 13', '77?528 13 35', '33 995 11 13', 'diabeledrame@yahoo.fr', 'Sedhiou', 59),
(60, 'Dr Christophe Koidi KANFOM', '33 995 51 39', '77 608 75 77', '33 995 51 39', 'drkanfom2012@gmail.com', 'Sedhiou', 58),
(61, 'Dr Bou DIARRA', '', '77 651 71 10', '33 995 00 72', 'arraiduab@yahoo.fr', 'Sedhiou', 57),
(62, 'Dr Bayal CISSE', '33 981 11 64/33 981 10 26', '33 981 11 64', '33 981 10 77', 'moundel@yahoo.fr', 'Tambacounda', NULL),
(63, 'Dr Tidiane GADIAGA', '33 981 16 76', '77 533 70 11/70 840 16 68', '33 981 16 76', 'tidianegadiaga@yahoo.fr/districtamba@yahoo.fr', 'Tambacounda', 66),
(64, 'Dr El Hadji Malick Abdoulaye DIOP', '33 980 41 23', '780183833', '33 983 12 75', 'docdiop82@gmail.com', 'Tambacounda', 64),
(65, 'Dr Alseyni DIALLO', '33 982 40 06', '77 520 91 04', '33 982 40 13', 'docalseyni2@gmail.com', 'Tambacounda', 65),
(66, 'Dr Doudou DIALLO', '33 983 51 02', '775718496', '33 983 51 02', 'dialdoudou1@yahoo.fr', 'Tambacounda', 60),
(67, 'Faliliou   GUEYE', '33 983 71 12', '772031507', '33 983 71 12', 'faliliougueye@yahoo.fr', 'Tambacounda', 62),
(68, 'Dr Tahirou MBAYE', '33 983 84 03', '77 533 71 97', '33 983 84 03', 'mbayetairou76@hotmail.fr', 'Tambacounda', 61),
(69, 'Dr Dame', '', '773620190', '', 'damnd@hotmail.fr ', 'Tambacounda', 63),
(70, 'Dr El Hadji Malick NDIAYE', '33 951 36 79', '77 630 67 27', '33 951 53 74', 'elmalickndiaye12@yahoo.fr/malickndiaye12@hotmail.com/rgmth@orange.sn', 'Thies', NULL),
(71, 'Dr Moustapha M. FAYE', '33  951 34 19', '77 650 00 33', '33 951 34 19', ' moustapha75@gmail.com', 'Thies', 74),
(72, 'Dr Malick BADIANE', '33 953 42 61', '77 645 33 29', '33 953 42 62', 'serignemalick@yahoo.fr', 'Thies', 72),
(73, 'Dr El Hadji DOUCOURE', '33 953 16 43', '77 501 85 54/70 676 04 54', '33 953 16 43', 'ladji79@gmail.com', 'Thies', 68),
(74, 'Dr Pape Ibrahima CAMARA', '33 955 15 54/33 955 15 27', '77 645 73 62', '33 965 11 08/33 955 15 27', 'pikamara@yahoo.fr', 'Thies', 75),
(75, 'Dr Ndeye Amy BA', '33 955 50 27', '775384064', '33 955 55 55', 'ndeyeamyba@yahoo.fr', 'Thies', 70),
(76, 'Dr Fatma FALL', '33 957 10 30', '77 648 26 05', '33 957 10 30', 'fsfall@hotmail.fr ', 'Thies', 69),
(77, 'Dr Ndeytou DIAGNE SEYE', '33 957 61 15/33 957 61 88', '77 650 79 21', '33 957 71 26', 'mamndey@yahoo.fr', 'Thies', 67),
(78, 'Dr Youssouph TINE', '33 957 71 26', '77 557 85 29/77 639 79 95', '33 957 71 26', 'youtine@gmail.com ', 'Thies', 71),
(79, 'Dr Youssou MBAYE', '33 957 81 14', '77 550 08 23', '33 957 81 14', 'youmbaye9@yahoo.fr/thiadiayedistrict@yahoo.fr', 'Thies', 73),
(80, 'Medecin Lieutenant-Colonel Maodo Malick DIOP', '33 991 12 75', '77 655 94 08', '33 991 12 75', 'maododiopmalick@gmail.com', 'Ziguinchor', NULL),
(81, 'Dr Jean Jacques MALOMAR', '33 991 15 32', '77 556 24 31', '33 991 15 32', 'jjmalomar@gmail.com', 'Ziguinchor', 36),
(82, 'Dr Gabriel Massene SENGHOR', '33 993 11 05', '77 524 95 37', '33 993 12 40', 'gabimassene@gmail.com', 'Ziguinchor', 34),
(83, 'Mamadou Lamine SAGNA', '33 994 11 22', '77 576 13 43', '33 994 11 33', 'mlsagna@yahoo.fr', 'Ziguinchor', 32),
(84, 'Dr Mahmadou NDIAYE', '', '77 645 33 22/76 010 78 21/70 604 82 95', '', 'mahmadou2002@yahoo.fr', 'Ziguinchor', 33),
(85, 'Abdel Kader Souandy SARR', '33 994 40 45', '775706091', '33 994 40 45', 'souandysarr@yahoo.fr', 'Ziguinchor', 35);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
