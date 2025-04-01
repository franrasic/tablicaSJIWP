-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2025 at 02:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trgovina`
--

-- --------------------------------------------------------

--
-- Table structure for table `administratori`
--

CREATE TABLE `administratori` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administratori`
--

INSERT INTO `administratori` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(2, 'admin', '$2y$10$ws3tcwEUvKeMM4iciYonaOxvinRNNQayajASX6zIleL4tNI2amoMG', 'admin@example.com', '2025-03-31 08:41:25');

-- --------------------------------------------------------

--
-- Table structure for table `dobavljac`
--

CREATE TABLE `dobavljac` (
  `IDDobavljac` int(11) NOT NULL,
  `Kontakt` int(11) NOT NULL,
  `Ime` varchar(25) DEFAULT NULL,
  `Kolicina` int(11) NOT NULL,
  `Adresa` varchar(100) NOT NULL DEFAULT 'Nepoznata adresa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dobavljac`
--

INSERT INTO `dobavljac` (`IDDobavljac`, `Kontakt`, `Ime`, `Kolicina`, `Adresa`) VALUES
(1, 991234567, 'Dobavnica d.o.o.', 150, 'Industrijska zona 12, Zagreb'),
(2, 992345678, 'Optimal opskrba', 200, 'Vukovarska 143, Split'),
(3, 993456789, 'Brza dostava', 75, 'Riječka 5, Rijeka'),
(4, 994567890, 'Megatrade', 300, 'Dubrovačka 33, Osijek'),
(5, 995678901, 'Univerzal distribucija', 180, 'Sisačka 22, Zadar'),
(6, 996789012, 'Fina isporuka', 90, 'Varaždinska 78, Varaždin'),
(7, 997890123, 'Kvalitetna roba', 250, 'Krapinska 11, Karlovac'),
(8, 998901234, 'Eurodobavljač', 175, 'Istarska 9, Pula'),
(9, 999012345, 'Balkan snabdevanje', 120, 'Dalmatinska 44, Šibenik'),
(10, 990123456, 'Centar distribucije', 220, 'Slavonska 56, Slavonski Brod');

-- --------------------------------------------------------

--
-- Table structure for table `proizvod`
--

CREATE TABLE `proizvod` (
  `IDProizvod` int(11) NOT NULL,
  `Naziv` varchar(25) NOT NULL,
  `DobavljacID` int(11) NOT NULL,
  `ProizvodacID` int(11) NOT NULL,
  `Cijena` int(11) NOT NULL,
  `Kolicina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proizvod`
--

INSERT INTO `proizvod` (`IDProizvod`, `Naziv`, `DobavljacID`, `ProizvodacID`, `Cijena`, `Kolicina`) VALUES
(1, 'Čokoladno mlijeko', 1, 3, 12, 100),
(2, 'Keksi', 2, 6, 15, 80),
(3, 'Voćni jogurt', 3, 4, 8, 120),
(4, 'Čips', 4, 1, 10, 90),
(5, 'Gazirani sok', 5, 5, 9, 150),
(6, 'Čokoladna ploča', 6, 8, 20, 60),
(7, 'Gumeni bomboni', 7, 9, 12, 110),
(8, 'Piletina u konzervi', 8, 1, 25, 70),
(9, 'Sladoled', 9, 4, 15, 50),
(10, 'Medenjaci', 10, 2, 18, 85);

-- --------------------------------------------------------

--
-- Table structure for table `proizvodac`
--

CREATE TABLE `proizvodac` (
  `IDProizvodac` int(11) NOT NULL,
  `Ime` varchar(25) NOT NULL,
  `Kontakt` int(11) NOT NULL,
  `Adresa` varchar(100) NOT NULL DEFAULT 'Nepoznata adresa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proizvodac`
--

INSERT INTO `proizvodac` (`IDProizvodac`, `Ime`, `Kontakt`, `Adresa`) VALUES
(1, 'Podravka', 98234251, 'Podravka d.d., Matije Gupca 2, Koprivnica'),
(2, 'Kras', 92324523, 'Kras, Ljubljanska cesta 8, Prevalje (Slovenija)'),
(3, 'Zott', 99464566, 'Zott GmbH & Co. KG, Mertinger Str. 3, Mertingen (Njemačka)'),
(4, 'Ledo', 99142323, 'Ledo d.d., Križevcička 4, Zagreb'),
(5, 'Coca-Cola', 98567234, 'The Coca-Cola Company, One Coca-Cola Plaza, Atlanta (SAD)'),
(6, 'Nestlé', 98765321, 'Nestlé S.A., Avenue Nestlé 55, Vevey (Švicarska)'),
(7, 'Orangina', 98654321, 'Orangina Schweppes Group, 62 rue de Lisbonne, Paris (Francuska)'),
(8, 'Milka', 98456789, 'Mondelez Europe GmbH, Lange Str. 31, Bremen (Njemačka)'),
(9, 'Haribo', 98345678, 'Haribo GmbH & Co. KG, Hans-Riegel-Str. 1, Bonn (Njemačka)'),
(10, 'Kandit', 98123456, 'Kandit d.d., Trg Krešimira Ćosića 1, Požega');

-- --------------------------------------------------------

--
-- Table structure for table `racun`
--

CREATE TABLE `racun` (
  `IDRacun` int(11) NOT NULL,
  `UkupnaCijena` int(11) NOT NULL,
  `Datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `racun`
--

INSERT INTO `racun` (`IDRacun`, `UkupnaCijena`, `Datum`) VALUES
(1, 150, '2025-01-15 10:30:00'),
(2, 220, '2025-01-16 11:45:00'),
(3, 95, '2025-01-17 09:15:00'),
(4, 310, '2025-01-18 14:20:00'),
(5, 180, '2025-01-19 16:10:00'),
(6, 75, '2025-01-20 12:30:00'),
(7, 420, '2025-01-21 17:45:00'),
(8, 130, '2025-01-22 10:10:00'),
(9, 260, '2025-01-23 13:25:00'),
(10, 190, '2025-01-24 15:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `stavka_racuna`
--

CREATE TABLE `stavka_racuna` (
  `IDStavkaRacuna` int(11) NOT NULL,
  `RacunID` int(11) NOT NULL,
  `ProizvodID` int(11) NOT NULL,
  `Cijena` int(11) NOT NULL,
  `Kolicina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stavka_racuna`
--

INSERT INTO `stavka_racuna` (`IDStavkaRacuna`, `RacunID`, `ProizvodID`, `Cijena`, `Kolicina`) VALUES
(1, 1, 1, 12, 5),
(2, 1, 3, 8, 3),
(3, 2, 5, 9, 2),
(4, 2, 7, 12, 4),
(5, 3, 2, 15, 1),
(6, 3, 4, 10, 2),
(7, 4, 6, 20, 3),
(8, 4, 8, 25, 4),
(9, 5, 10, 18, 5),
(10, 5, 9, 15, 2),
(11, 6, 1, 12, 2),
(12, 6, 2, 15, 1),
(13, 7, 3, 8, 10),
(14, 7, 4, 10, 8),
(15, 8, 5, 9, 3),
(16, 8, 6, 20, 2),
(17, 9, 7, 12, 5),
(18, 9, 8, 25, 4),
(19, 10, 9, 15, 3),
(20, 10, 10, 18, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administratori`
--
ALTER TABLE `administratori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `dobavljac`
--
ALTER TABLE `dobavljac`
  ADD PRIMARY KEY (`IDDobavljac`);

--
-- Indexes for table `proizvod`
--
ALTER TABLE `proizvod`
  ADD PRIMARY KEY (`IDProizvod`),
  ADD KEY `DobavljacID` (`DobavljacID`),
  ADD KEY `ProizvodacID` (`ProizvodacID`);

--
-- Indexes for table `proizvodac`
--
ALTER TABLE `proizvodac`
  ADD PRIMARY KEY (`IDProizvodac`);

--
-- Indexes for table `racun`
--
ALTER TABLE `racun`
  ADD PRIMARY KEY (`IDRacun`);

--
-- Indexes for table `stavka_racuna`
--
ALTER TABLE `stavka_racuna`
  ADD PRIMARY KEY (`IDStavkaRacuna`),
  ADD KEY `RacunID` (`RacunID`),
  ADD KEY `ProizvodID` (`ProizvodID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administratori`
--
ALTER TABLE `administratori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `proizvod`
--
ALTER TABLE `proizvod`
  ADD CONSTRAINT `proizvod_ibfk_1` FOREIGN KEY (`DobavljacID`) REFERENCES `dobavljac` (`IDDobavljac`),
  ADD CONSTRAINT `proizvod_ibfk_2` FOREIGN KEY (`ProizvodacID`) REFERENCES `proizvodac` (`IDProizvodac`);

--
-- Constraints for table `stavka_racuna`
--
ALTER TABLE `stavka_racuna`
  ADD CONSTRAINT `stavka_racuna_ibfk_1` FOREIGN KEY (`RacunID`) REFERENCES `racun` (`IDRacun`),
  ADD CONSTRAINT `stavka_racuna_ibfk_2` FOREIGN KEY (`ProizvodID`) REFERENCES `proizvod` (`IDProizvod`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
