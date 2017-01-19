-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2017 at 08:00 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `temabd`
--

-- --------------------------------------------------------

--
-- Table structure for table `afectiuni`
--

CREATE TABLE `afectiuni` (
  `id_afectiune` int(7) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `afectiuni`
--

INSERT INTO `afectiuni` (`id_afectiune`, `name`) VALUES
(2, 'Raceala si Gripa'),
(3, 'Durere'),
(4, 'Oftalmologie si ORL'),
(5, 'Cardiovascular'),
(6, 'Dermatologie'),
(7, 'Gastrointestinale'),
(8, 'Vitamine si Minerale'),
(9, 'Altele');

-- --------------------------------------------------------

--
-- Table structure for table `categorii`
--

CREATE TABLE `categorii` (
  `id_categorie` int(7) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categorii`
--

INSERT INTO `categorii` (`id_categorie`, `name`) VALUES
(8, 'Antiseptice'),
(9, 'Antibiotice'),
(10, 'Naturiste'),
(11, 'Cosmetice'),
(12, 'Pentru slabit'),
(13, 'Viata sexuala'),
(14, 'Aparate'),
(15, 'Unguente'),
(16, 'Comprimate');

-- --------------------------------------------------------

--
-- Table structure for table `comenzi`
--

CREATE TABLE `comenzi` (
  `id_comanda` int(7) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `pret` float NOT NULL,
  `time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comenzi`
--

INSERT INTO `comenzi` (`id_comanda`, `id_user`, `pret`, `time`) VALUES
(1, 1, 65, '2017-01-17 14:53:22'),
(3, 1, 66.77, '2017-01-17 15:24:24'),
(4, 1, 88.63, '2017-01-17 15:47:47'),
(8, 1, 690.73, '2017-01-17 19:39:17'),
(9, 5, 141.3, '2017-01-17 20:37:56'),
(10, 5, 121.76, '2017-01-17 20:38:00'),
(11, 6, 351.94, '2017-01-17 20:38:50'),
(12, 1, 33.99, '2017-01-18 19:59:37');

-- --------------------------------------------------------

--
-- Table structure for table `produse`
--

CREATE TABLE `produse` (
  `id_produs` int(5) NOT NULL,
  `name` varchar(30) NOT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `id_afectiune` int(11) DEFAULT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produse`
--

INSERT INTO `produse` (`id_produs`, `name`, `id_categorie`, `id_afectiune`, `price`) VALUES
(2, 'Ambroxol', 8, 2, 32),
(3, 'Aspirin plus', 8, 2, 14),
(4, 'Coldrex', 10, 2, 51),
(5, 'Aspirina', 8, 3, 15),
(6, 'Diclofenac', 15, 3, 42.99),
(7, 'Ibalgin forte', 16, 3, 23.78),
(8, 'Virilmax', 13, 9, 33.99),
(9, 'Durex', 13, 9, 12.33),
(10, 'Visislim', 12, 9, 44.3),
(11, 'Obegrass', 12, 9, 22.1),
(12, 'Gel calgel', 15, 4, 39.99),
(13, 'Paracetamol', 16, 3, 21);

-- --------------------------------------------------------

--
-- Table structure for table `produse_vandute`
--

CREATE TABLE `produse_vandute` (
  `id_comanda` int(11) DEFAULT NULL,
  `id_produs` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produse_vandute`
--

INSERT INTO `produse_vandute` (`id_comanda`, `id_produs`) VALUES
(1, 4),
(1, 3),
(3, 7),
(3, 6),
(4, 10),
(4, 9),
(4, 2),
(8, 9),
(8, 10),
(8, 11),
(8, 4),
(8, 4),
(8, 4),
(8, 4),
(8, 4),
(8, 4),
(8, 4),
(8, 4),
(8, 4),
(8, 4),
(8, 4),
(8, 4),
(9, 4),
(9, 3),
(9, 2),
(9, 10),
(10, 7),
(10, 6),
(10, 5),
(10, 12),
(11, 2),
(11, 3),
(11, 4),
(11, 5),
(11, 12),
(11, 12),
(11, 12),
(11, 12),
(11, 12),
(11, 12),
(12, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(7) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `email`, `admin`) VALUES
(1, 'doru', 'doru', 'doru@gmail.com', 1),
(5, 'ion', 'ion', 'ion@ion.com', 0),
(6, 'admin', 'admin', 'admin@admins.com', 1),
(7, 'Jack', 'jack', 'Jack@sparrow.com', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `afectiuni`
--
ALTER TABLE `afectiuni`
  ADD PRIMARY KEY (`id_afectiune`);

--
-- Indexes for table `categorii`
--
ALTER TABLE `categorii`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Indexes for table `comenzi`
--
ALTER TABLE `comenzi`
  ADD PRIMARY KEY (`id_comanda`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `produse`
--
ALTER TABLE `produse`
  ADD PRIMARY KEY (`id_produs`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `id_afectiune` (`id_afectiune`);

--
-- Indexes for table `produse_vandute`
--
ALTER TABLE `produse_vandute`
  ADD KEY `id_comanda` (`id_comanda`),
  ADD KEY `id_produs` (`id_produs`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `afectiuni`
--
ALTER TABLE `afectiuni`
  MODIFY `id_afectiune` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `categorii`
--
ALTER TABLE `categorii`
  MODIFY `id_categorie` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `comenzi`
--
ALTER TABLE `comenzi`
  MODIFY `id_comanda` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `produse`
--
ALTER TABLE `produse`
  MODIFY `id_produs` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comenzi`
--
ALTER TABLE `comenzi`
  ADD CONSTRAINT `comenzi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `produse`
--
ALTER TABLE `produse`
  ADD CONSTRAINT `produse_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorii` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produse_ibfk_2` FOREIGN KEY (`id_afectiune`) REFERENCES `afectiuni` (`id_afectiune`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `produse_vandute`
--
ALTER TABLE `produse_vandute`
  ADD CONSTRAINT `produse_vandute_ibfk_1` FOREIGN KEY (`id_comanda`) REFERENCES `comenzi` (`id_comanda`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `produse_vandute_ibfk_2` FOREIGN KEY (`id_produs`) REFERENCES `produse` (`id_produs`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
