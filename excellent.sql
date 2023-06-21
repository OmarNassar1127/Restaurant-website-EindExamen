-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2020 at 09:21 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `excellent`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `gerecht_naam` varchar(255) NOT NULL,
  `gerecht_prijs` tinyint(1) NOT NULL,
  `gerecht_omschrijving` varchar(250) NOT NULL,
  `gerecht_afbeelding` varchar(250) NOT NULL,
  `menuType` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `gerecht_naam`, `gerecht_prijs`, `gerecht_omschrijving`, `gerecht_afbeelding`, `menuType`) VALUES
(5, 'Burger', 123, '																																													Lekkere bruger                                                                                                                                                           ', 'burger.jpg', '2'),
(7, 'Steak', 30, 'Ribeye', 'steak.jpg', '2'),
(11, 'Ensalada de patatas', 14, 'Ensalada de patatas', 'patatas.jpg', '1'),
(12, 'Pizza', 13, 'Pizza a primo', 'pizza.jpg', '2'),
(14, 'Cocktail', 12, 'Cocktail', 'cocktail.jpg', '3'),
(15, 'Rijst salade', 9, 'Rijst salade met verschillende ingredienten', 'Rijstsalade.jpg', '1'),
(16, 'Koude pasta', 12, 'Koude pasta prrrrr', 'Koude pasta.jpg', '1'),
(17, 'Cocktail 2', 11, '							Ik weet niet wat er in zit                        ', 'cocktail2.jpg', '3'),
(20, 'Cocktail El D', 12, 'Cocktail El D', 'cocktail El D.jpg', '3');

-- --------------------------------------------------------

--
-- Table structure for table `menulijst`
--

CREATE TABLE `menulijst` (
  `menuLijst_id` int(11) NOT NULL,
  `tafel_id` int(11) NOT NULL,
  `menu_id` int(50) NOT NULL,
  `gemaakt` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menulijst`
--

INSERT INTO `menulijst` (`menuLijst_id`, `tafel_id`, `menu_id`, `gemaakt`) VALUES
(244, 15, 5, 'In behandeling'),
(245, 15, 7, 'In behandeling');

-- --------------------------------------------------------

--
-- Table structure for table `reserveringen`
--

CREATE TABLE `reserveringen` (
  `tafel_id` int(11) NOT NULL,
  `tafel_nmr` int(20) NOT NULL,
  `naam_persoon` varchar(50) NOT NULL,
  `telefoon` varchar(20) NOT NULL,
  `aantal_mensen` int(10) NOT NULL,
  `tijd` varchar(50) NOT NULL,
  `datum` date NOT NULL,
  `drankjes` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reserveringen`
--

INSERT INTO `reserveringen` (`tafel_id`, `tafel_nmr`, `naam_persoon`, `telefoon`, `aantal_mensen`, `tijd`, `datum`, `drankjes`) VALUES
(15, 2, 'Omar Nassar', '+31687838713', 5, '12:21', '2200-12-12', 'nada'),
(23, 4, 'Omar Nassar', '+31687838713', 12, '12:21', '1222-12-12', 'niks');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telefoon` int(11) NOT NULL,
  `user_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `telefoon`, `user_type`) VALUES
(15, 'test', 'test', 'test@test.nl', 123456789, 'admin'),
(24, 'omar', 'Jaguar.12', 'test@test.nl', 2147483647, 'mdw');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `menulijst`
--
ALTER TABLE `menulijst`
  ADD PRIMARY KEY (`menuLijst_id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `tafel_id` (`tafel_id`);

--
-- Indexes for table `reserveringen`
--
ALTER TABLE `reserveringen`
  ADD PRIMARY KEY (`tafel_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `menulijst`
--
ALTER TABLE `menulijst`
  MODIFY `menuLijst_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `reserveringen`
--
ALTER TABLE `reserveringen`
  MODIFY `tafel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menulijst`
--
ALTER TABLE `menulijst`
  ADD CONSTRAINT `menulijst_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`menu_id`),
  ADD CONSTRAINT `menulijst_ibfk_2` FOREIGN KEY (`tafel_id`) REFERENCES `reserveringen` (`tafel_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
