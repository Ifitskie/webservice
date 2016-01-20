-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2016 at 04:38 PM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webservice`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Author` varchar(255) NOT NULL,
  `Series` varchar(255) NOT NULL,
  `HaveBook` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`id`, `Title`, `Author`, `Series`, `HaveBook`) VALUES
(1, 'The Hunger Games', 'Suzanne Collins', 'The Hunger Games', 1),
(2, 'Catching Fire', 'Suzanne Collins', 'The Hunger Games', 1),
(3, 'Mockingjay', 'Suzanne Collins', 'The Hunger Games', 1),
(4, 'The Vampire Narcise', 'Colleen Gleason', 'Regency Draculia', 1),
(5, 'The Vampire Voss', 'Colleen Gleason', 'Regency Draculia', 0),
(6, 'The Vampire Dimitri', 'Colleen Gleason', 'Regency Draculia', 0),
(7, 'The Prodigial Mage', 'Karen Miller', 'Fisherman Children', 1),
(8, 'The Reluctant Mage', 'Karen Miller', 'Fisherman Children', 0),
(9, 'Battle of Kings', 'M K Hume', 'Merlin Prophecy', 1),
(10, 'Death of an Empire', 'M K Hume', 'Merlin Prophecy', 0),
(11, 'Hunting with Gods', 'M K Hume', 'Merlin Prophecy', 0),
(13, 'A Living Nightmare', 'Darren Shan', 'Cirque Du Freak', 1),
(17, 'Vampire Mountain', 'Darren Shan', 'Cirque Du Freak', 0),
(18, 'Tunnels of Blood', 'Darren Shan', 'Cirque Du Freak', 1),
(20, 'kaas', 'Kaas', '', 0),
(21, 'Kaas', 'kaas', '', 0),
(22, 'Kaas', 'kaas', '', 0),
(23, 'Kaas', 'kaas', '', 0),
(24, 'aaaaa', 'kaas', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
