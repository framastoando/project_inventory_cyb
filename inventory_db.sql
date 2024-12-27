-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql210.byetcluster.com
-- Generation Time: Dec 27, 2024 at 01:45 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_37550033_inventaris_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `keluar`
--

CREATE TABLE `keluar` (
  `idkeluar` int(11) NOT NULL,
  `idbarang` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `penerima` varchar(25) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keluar`
--

INSERT INTO `keluar` (`idkeluar`, `idbarang`, `tanggal`, `penerima`, `qty`) VALUES
(1, 1, '2024-11-18 09:00:31', 'mursid', 300),
(3, 10, '2024-11-29 01:35:38', 'Teknisi a', 10),
(4, 12, '2024-11-29 01:40:32', 'Teknisi b', 2),
(5, 13, '2024-11-29 01:40:40', 'Teknisi b', 30),
(6, 11, '2024-11-29 01:41:15', 'Teknisi c', 2),
(22, 10, '2024-12-02 09:20:55', 'Teknisi c', 20),
(23, 10, '2024-12-10 07:27:17', 'Teknisi c', 10),
(24, 10, '2024-12-10 08:46:11', 'Teknisi c', 19);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `iduser` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`iduser`, `email`, `password`) VALUES
(1, 'test@gmail.com', '1234'),
(2, 'cs@gmail.com', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `masuk`
--

CREATE TABLE `masuk` (
  `idmasuk` int(11) NOT NULL,
  `idbarang` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `keterangan` varchar(25) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `masuk`
--

INSERT INTO `masuk` (`idmasuk`, `idbarang`, `tanggal`, `keterangan`, `qty`) VALUES
(2, 2, '2024-11-25 08:23:28', 'Muani', 5),
(3, 1, '2024-11-25 08:23:41', 'budi', 10),
(4, 7, '2024-11-28 08:19:24', 'Muani', 20),
(5, 10, '2024-11-29 01:32:58', 'supplier A', 100),
(6, 11, '2024-11-29 01:33:19', 'supplier B', 50),
(7, 10, '2024-11-29 01:33:47', 'supplier C', 30),
(8, 12, '2024-11-29 01:34:05', 'supplier A', 10),
(9, 13, '2024-11-29 01:39:25', 'supplier C', 200),
(10, 10, '2024-12-10 07:27:00', 'supplier B', 10),
(11, 10, '2024-12-10 08:44:52', 'supplier B', 19);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `idbarang` int(11) NOT NULL,
  `barcode` varchar(100) NOT NULL,
  `namabarang` varchar(25) NOT NULL,
  `deskripsi` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(99) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`idbarang`, `barcode`, `namabarang`, `deskripsi`, `stock`, `image`) VALUES
(9, '9988776655443', 'Patch Cord 1m', 'Patch cord panjang 1m konektor sc', 0, '4f97728b5457b761323e10947a9e7988.jpg'),
(10, '8S5A10J75115L1CZ8610JWO', 'Patch Cord 3m', 'Patch cord panjang 3m konektor sc', 100, 'd7cc8f151b7bb5ab032ede6b01f74ed3.jpg'),
(11, '8886008101053', 'Ont zte F670L', 'Router ont klien merk zte F670L', 48, '7a1f004dd8ae17c81f5019deeb184157.jpg'),
(12, '4567891234567', 'ODP Pole 8 core', 'ODP pole 8 core konektor sc', 8, 'bb9a47c9a6cc15dc0822b69d266177e4.jpeg'),
(13, '7891234567890', 'Pigtail 1.5m', 'Pigtail konektor sc 0.9mm', 170, 'aaa6b4153937d1eea71bfac7610df802.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `keluar`
--
ALTER TABLE `keluar`
  ADD PRIMARY KEY (`idkeluar`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `masuk`
--
ALTER TABLE `masuk`
  ADD PRIMARY KEY (`idmasuk`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`idbarang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `keluar`
--
ALTER TABLE `keluar`
  MODIFY `idkeluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `masuk`
--
ALTER TABLE `masuk`
  MODIFY `idmasuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `idbarang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
