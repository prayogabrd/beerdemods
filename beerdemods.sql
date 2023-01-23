-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 23 Jan 2023 pada 15.59
-- Versi server: 10.4.6-MariaDB
-- Versi PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beerdemods`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `account`
--

CREATE TABLE `account` (
  `user_id` int(11) NOT NULL,
  `user_ip` varchar(50) NOT NULL,
  `user_cc` varchar(10) NOT NULL,
  `name` varchar(200) NOT NULL,
  `user_name` varchar(12) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_pass` varchar(300) NOT NULL,
  `user_profile` varchar(2083) NOT NULL DEFAULT '-',
  `user_call` varchar(20) NOT NULL DEFAULT '-',
  `user_access` int(2) NOT NULL DEFAULT 1,
  `user_admin` int(2) NOT NULL DEFAULT -1,
  `is_owner` int(2) NOT NULL DEFAULT -1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `account`
--

INSERT INTO `account` (`user_id`, `user_ip`, `user_cc`, `name`, `user_name`, `user_email`, `user_pass`, `user_profile`, `user_call`, `user_access`, `user_admin`, `is_owner`) VALUES
(1, '::1', 'ID', 'Beerde', 'beerde', 'id.beerde@aol.com', 'Adadirumah1324?!', '-', '-', 1, 1, 1),
(2, '::1', 'ID', 'PrayogaBRD', 'prayogabrd', 'prayogabrd@aol.com', '$2y$10$ZUHmttcfdunse/98dGDYVe2DIsHxQjWoAeTw.E1Kd2EDI6xyfaM0y', 'https://source.unsplash.com/500x500?cat', '087718793287', 1, 1, -1),
(6, '::1', 'ID', 'Beerde Mods', 'beerdemods', 'beerdemods@aol.com', '$2y$10$WyMDk4bDoiSLFr749SbMOOOEEtaukKf2wvHvrQW/ehqkoRY4wQiQO', '-', '-', 1, -1, -1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `account`
--
ALTER TABLE `account`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
