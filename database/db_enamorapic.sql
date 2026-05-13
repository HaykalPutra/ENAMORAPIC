-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2025 at 06:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_enamorapic`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `nama_client` varchar(100) NOT NULL,
  `tgl_booking` date NOT NULL,
  `tgl_acara` date NOT NULL,
  `total_transaksi` double NOT NULL,
  `status_pembayaran` enum('Lunas','DP') NOT NULL,
  `status_acara` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `customer_id`, `nama_client`, `tgl_booking`, `tgl_acara`, `total_transaksi`, `status_pembayaran`, `status_acara`) VALUES
(1, 1, 'Wedding prewedd diaz wo', '2025-01-03', '2025-01-03', 4000000, 'Lunas', 'Selesai'),
(2, 2, 'Prewedd sore', '2025-01-11', '2025-01-11', 2000000, 'Lunas', 'Selesai'),
(3, 3, 'wedding mahligai selasih', '2025-01-12', '2025-01-12', 4000000, 'Lunas', 'Selesai'),
(4, 4, 'wedding Kadedeuh wo', '2025-01-26', '2025-01-26', 4000000, 'Lunas', 'Selesai'),
(5, 5, 'Ptewedd Junaidi', '2025-01-27', '2025-01-27', 2000000, 'Lunas', 'Selesai'),
(6, 6, 'wedding cinta', '2025-01-25', '2025-01-25', 4000000, 'Lunas', 'Selesai'),
(7, 7, 'prewedd pagi', '2025-01-28', '2025-01-28', 2000000, 'Lunas', 'Selesai'),
(8, 8, 'Siraman uyung', '2025-01-31', '2025-01-31', 1500000, 'Lunas', 'Selesai'),
(9, 9, 'Wedding uyung', '2025-02-01', '2025-02-01', 4000000, 'Lunas', 'Selesai'),
(10, 10, 'Nawrah subang', '2025-02-08', '2025-02-08', 1500000, 'Lunas', 'Selesai'),
(11, 11, 'prewedd unpar sore', '2025-02-15', '2025-02-15', 2000000, 'Lunas', 'Selesai'),
(12, 12, 'wedding Adik ari', '2025-02-16', '2025-02-16', 4000000, 'Lunas', 'Selesai'),
(13, 13, 'Wedding jam 2 isma&imam', '2025-02-16', '2025-02-16', 4000000, 'Lunas', 'Selesai'),
(14, 14, 'prewedd aca jam 10pagi', '2025-02-17', '2025-02-17', 2000000, 'Lunas', 'Selesai'),
(15, 15, 'Prewedd Hazel', '2025-02-21', '2025-02-21', 2000000, 'Lunas', 'Selesai'),
(16, 16, 'Prewedd subuh tiara', '2025-02-21', '2025-02-21', 2000000, 'Lunas', 'Selesai'),
(17, 17, 'Wedding Yunita Bratayuda', '2025-02-23', '2025-02-23', 4000000, 'Lunas', 'Selesai'),
(18, 18, 'prewedd subuh jam 4', '2025-02-26', '2025-02-26', 2000000, 'Lunas', 'Selesai'),
(19, 19, 'Prewedd dea sore', '2025-02-27', '2025-02-27', 2000000, 'Lunas', 'Selesai'),
(20, 20, 'Prewedd mega pagi aljabar', '2025-02-27', '2025-02-27', 2000000, 'Lunas', 'Selesai'),
(21, 21, 'Prewedd Reza', '2025-03-16', '2025-03-16', 2000000, 'Lunas', 'Selesai'),
(22, 22, 'prewed giti subuh', '2025-03-17', '2025-03-17', 2000000, 'Lunas', 'Selesai'),
(23, 23, 'Photoshoot kawah putih', '2025-03-23', '2025-03-23', 2000000, 'Lunas', 'Selesai'),
(24, 24, 'Prewedd client bocil', '2025-03-27', '2025-03-27', 2000000, 'Lunas', 'Selesai'),
(25, 25, 'Engagement Tiara Kania', '2025-04-05', '2025-04-05', 7000000, 'Lunas', 'Selesai'),
(26, 26, 'Prewedd sore studio pasar baru', '2025-04-05', '2025-04-05', 2000000, 'Lunas', 'Selesai'),
(27, 27, 'Prewedd situ patenggang', '2025-04-07', '2025-04-07', 2000000, 'Lunas', 'Selesai'),
(28, 28, 'Siraman inten', '2025-04-10', '2025-04-10', 1500000, 'Lunas', 'Selesai'),
(29, 29, 'Wedding inten', '2025-04-12', '2025-04-12', 4000000, 'Lunas', 'Selesai'),
(30, 30, 'Wedding dayeuhkolot', '2025-04-13', '2025-04-13', 4000000, 'Lunas', 'Selesai'),
(31, 31, 'Wedding kadedeuh RM.Pohon mangga', '2025-04-13', '2025-04-13', 4000000, 'Lunas', 'Selesai'),
(32, 32, 'Birthday party korean house sore', '2025-04-13', '2025-04-13', 1500000, 'Lunas', 'Selesai'),
(33, 33, 'Wedding Ari Wo', '2025-04-13', '2025-04-13', 4000000, 'Lunas', 'Selesai'),
(34, 34, 'Freelance andri abay', '2025-04-15', '2025-04-15', 1500000, 'Lunas', 'Selesai'),
(35, 35, 'Foto Wisuda Tk At tashdiq', '2025-04-17', '2025-04-17', 1500000, 'Lunas', 'Selesai'),
(36, 36, 'Prewedd Bella Dreambelle jam 3sore', '2025-04-18', '2025-04-18', 2000000, 'Lunas', 'Selesai'),
(37, 37, 'Wedding akay', '2025-04-18', '2025-04-18', 4000000, 'Lunas', 'Selesai'),
(38, 38, 'Engagement Cindy', '2025-04-19', '2025-04-19', 1500000, 'Lunas', 'Selesai'),
(39, 39, 'prewedd Adrian pagi', '2025-04-20', '2025-04-20', 2000000, 'Lunas', 'Selesai'),
(40, 40, 'prewedd Tesa', '2025-04-24', '2025-04-24', 2000000, 'Lunas', 'Selesai'),
(41, 41, 'prewedd Nurul', '2025-04-29', '2025-04-29', 2000000, 'Lunas', 'Selesai'),
(42, 42, 'Freelance andri abay', '2025-05-03', '2025-05-03', 1500000, 'Lunas', 'Selesai'),
(43, 43, 'prewedd uzi', '2025-05-06', '2025-05-06', 2000000, 'Lunas', 'Selesai'),
(44, 44, 'prewed ina rose package', '2025-05-07', '2025-05-07', 4000000, 'Lunas', 'Selesai'),
(45, 45, 'Prewedd dw', '2025-05-08', '2025-05-08', 2000000, 'Lunas', 'Selesai'),
(46, 46, 'Prewedd Risma subuh braga', '2025-05-09', '2025-05-09', 2000000, 'Lunas', 'Selesai'),
(47, 47, 'Enggagement kota baru', '2025-05-10', '2025-05-10', 1500000, 'Lunas', 'Selesai'),
(48, 48, 'sumpah dokter', '2025-05-10', '2025-05-10', 1500000, 'Lunas', 'Selesai'),
(49, 49, 'prewedd muti', '2025-05-11', '2025-05-11', 2000000, 'Lunas', 'Selesai'),
(50, 50, 'Prewedd Dw', '2025-05-12', '2025-05-12', 2000000, 'Lunas', 'Selesai'),
(51, 51, 'Engagement tobias martupol', '2025-05-17', '2025-05-17', 1500000, 'Lunas', 'Selesai'),
(52, 52, 'Wedding Gitia', '2025-05-18', '2025-05-18', 4000000, 'Lunas', 'Selesai'),
(53, 53, 'Wedding eddong MC', '2025-05-18', '2025-05-18', 4000000, 'Lunas', 'Selesai'),
(54, 54, 'Prewedd Gita', '2025-05-24', '2025-05-24', 2000000, 'Lunas', 'Selesai'),
(55, 55, 'prewedd subuh braga', '2025-05-24', '2025-05-24', 2000000, 'Lunas', 'Selesai'),
(56, 56, 'Wedding ari wo', '2025-05-25', '2025-05-25', 4000000, 'Lunas', 'Selesai'),
(57, 57, 'intimate marhiza hotel jam 8 standby', '2025-05-25', '2025-05-25', 4000000, 'Lunas', 'Selesai'),
(58, 58, 'prewedd riri', '2025-05-29', '2025-05-29', 2000000, 'Lunas', 'Selesai'),
(59, 59, 'prewedd patenggang', '2025-06-03', '2025-06-03', 2000000, 'Lunas', 'Selesai'),
(60, 60, 'Prewedd Nimo eye pangalengan', '2025-06-04', '2025-06-04', 2000000, 'Lunas', 'Selesai'),
(61, 61, 'Prewedd atlet lari', '2025-06-07', '2025-06-07', 2000000, 'Lunas', 'Selesai'),
(62, 62, 'Wedding wo lyora HBS Cimareme', '2025-06-08', '2025-06-08', 4000000, 'Lunas', 'Selesai'),
(63, 63, 'wedding dw kopo', '2025-06-08', '2025-06-08', 4000000, 'Lunas', 'Selesai'),
(64, 64, 'Wedding korean house acara sore', '2025-06-08', '2025-06-08', 4000000, 'Lunas', 'Selesai'),
(65, 65, 'Prewedd Abc cindy', '2025-06-10', '2025-06-10', 2000000, 'Lunas', 'Selesai'),
(66, 66, 'Prewedd Kadedeuh', '2025-06-10', '2025-06-10', 2000000, 'Lunas', 'Selesai'),
(67, 67, 'Prewedding Classic Package Fathur', '2025-06-11', '2025-06-11', 2000000, 'Lunas', 'Selesai'),
(68, 68, 'prewedd risha', '2025-06-13', '2025-06-13', 2000000, 'Lunas', 'Selesai'),
(69, 69, 'Wedding Khatulistiwa wo', '2025-06-14', '2025-06-14', 4000000, 'Lunas', 'Selesai'),
(70, 70, 'Wedding Extra time', '2025-06-14', '2025-06-14', 4000000, 'Lunas', 'Selesai'),
(71, 71, 'foto keluarga nikahan boda jam 4sore', '2025-06-14', '2025-06-14', 1500000, 'Lunas', 'Selesai'),
(72, 72, 'Ari Wo pagi lagadar', '2025-06-15', '2025-06-15', 4000000, 'Lunas', 'Selesai'),
(73, 73, 'Wedding Nurul hasanan', '2025-06-15', '2025-06-15', 4000000, 'Lunas', 'Selesai'),
(74, 74, 'Darmawangsa', '2025-06-21', '2025-06-21', 4000000, 'Lunas', 'Selesai'),
(75, 75, 'Darmawangsa', '2025-06-22', '2025-06-22', 4000000, 'Lunas', 'Selesai'),
(76, 76, 'DW Wedding', '2025-06-22', '2025-06-22', 4000000, 'Lunas', 'Selesai'),
(77, 77, 'Enggagement cicalengka', '2025-06-22', '2025-06-22', 1500000, 'Lunas', 'Selesai'),
(78, 78, 'Inten sunatan', '2025-06-23', '2025-06-23', 1500000, 'Lunas', 'Selesai'),
(79, 79, 'Foto only request omen', '2025-06-23', '2025-06-23', 1500000, 'Lunas', 'Selesai'),
(80, 80, 'Prewedd emili wildan', '2025-06-24', '2025-06-24', 2000000, 'Lunas', 'Selesai'),
(81, 81, 'prewedd Ajeng', '2025-06-27', '2025-06-27', 2000000, 'Lunas', 'Selesai'),
(82, 82, 'Prewedd Dw', '2025-06-27', '2025-06-27', 2000000, 'Lunas', 'Selesai'),
(83, 83, 'Prewedd studio', '2025-06-29', '2025-06-29', 2000000, 'Lunas', 'Selesai'),
(84, 84, 'Prewedding andika', '2025-06-30', '2025-06-30', 2000000, 'Lunas', 'Selesai'),
(85, 85, 'Wedding indira', '2025-07-06', '2025-07-06', 4000000, 'Lunas', 'Selesai'),
(86, 86, 'prewedd ari wo jam 2 subuh', '2025-07-09', '2025-07-09', 2000000, 'Lunas', 'Selesai'),
(87, 87, 'Prewedd Alda inc makeup', '2025-07-13', '2025-07-13', 2000000, 'Lunas', 'Selesai'),
(88, 88, 'Khitanan', '2025-07-17', '2025-07-17', 1500000, 'Lunas', 'Selesai'),
(89, 89, 'Prewedd eja', '2025-07-18', '2025-07-18', 2000000, 'Lunas', 'Selesai'),
(90, 90, 'prewedd Upas', '2025-07-20', '2025-07-20', 2000000, 'Lunas', 'Selesai'),
(91, 91, 'Prewedd rangga', '2025-07-22', '2025-07-22', 2000000, 'Lunas', 'Selesai'),
(92, 92, 'Engagement', '2025-07-24', '2025-07-24', 1500000, 'Lunas', 'Selesai'),
(93, 93, 'Prewedd kadedeuh', '2025-08-04', '2025-08-04', 2000000, 'Lunas', 'Selesai'),
(94, 94, 'Prewedd selvia', '2025-08-09', '2025-08-09', 2000000, 'Lunas', 'Selesai'),
(95, 95, 'Wedding Ina Rose package', '2025-08-10', '2025-08-10', 4000000, 'Lunas', 'Selesai'),
(96, 96, 'Wedding kadedeuh', '2025-08-10', '2025-08-10', 4000000, 'Lunas', 'Selesai'),
(97, 97, 'Prewedd Roro', '2025-08-12', '2025-08-12', 2000000, 'Lunas', 'Selesai'),
(98, 98, 'Prewedd Annisa robbiatun', '2025-08-18', '2025-08-18', 2000000, 'Lunas', 'Selesai'),
(99, 99, 'Prewedd Crystal', '2025-08-18', '2025-08-18', 2000000, 'Lunas', 'Selesai'),
(100, 100, 'Prewedd situ lembang', '2025-08-27', '2025-08-27', 2000000, 'Lunas', 'Selesai'),
(101, 101, 'prewedd situpatenggang', '2025-08-30', '2025-08-30', 2000000, 'Lunas', 'Selesai'),
(102, 102, 'Foto only Arie nu teu jadi tea', '2025-08-31', '2025-08-31', 1500000, 'Lunas', 'Selesai'),
(103, 103, 'Prewedd mahligai', '2025-09-01', '2025-09-01', 2000000, 'Lunas', 'Selesai'),
(104, 104, 'engagement foto only ujung berung', '2025-09-06', '2025-09-06', 1500000, 'Lunas', 'Selesai'),
(105, 105, 'Wedding mahligai', '2025-09-07', '2025-09-07', 4000000, 'Lunas', 'Selesai'),
(106, 106, 'Prewedd cihapit', '2025-09-08', '2025-09-08', 2000000, 'Lunas', 'Selesai'),
(107, 107, 'Prewed karen at Situ patenggang', '2025-09-12', '2025-09-12', 2000000, 'Lunas', 'Selesai'),
(108, 108, 'prewedd rizal uber sore ke malam', '2025-09-14', '2025-09-14', 2000000, 'Lunas', 'Selesai'),
(109, 109, 'Aris natasia braga + studio classic', '2025-09-19', '2025-09-19', 2000000, 'Lunas', 'Selesai'),
(110, 110, 'Wedding kadedeuh acra sore', '2025-09-27', '2025-09-27', 4000000, 'Lunas', 'Selesai'),
(111, 111, 'Prewedd Kwp wedding 12nov', '2025-09-30', '2025-09-30', 2000000, 'Lunas', 'Selesai'),
(112, 112, 'aprisi alwedd pagi', '2025-10-05', '2025-10-05', 4000000, 'Lunas', 'Selesai'),
(113, 113, 'Prewedd gedung sate', '2025-10-06', '2025-10-06', 2000000, 'Lunas', 'Selesai'),
(114, 114, 'prewedd engko', '2025-10-08', '2025-10-08', 2000000, 'Lunas', 'Selesai'),
(115, 115, 'Prewedd santa pinehills', '2025-10-11', '2025-10-11', 2000000, 'Lunas', 'Selesai'),
(116, 116, 'Wedding', '2025-10-12', '2025-10-12', 4000000, 'Lunas', 'Selesai'),
(117, 117, 'Wisuda upi', '2025-10-14', '2025-10-14', 1500000, 'Lunas', 'Selesai'),
(118, 118, 'Wedding Zoya', '2025-10-25', '2025-10-25', 4000000, 'Lunas', 'Selesai'),
(119, 119, 'prewedd patenggang', '2025-10-29', '2025-10-29', 2000000, 'Lunas', 'Selesai'),
(120, 120, 'wedd & prewedd wen jesika', '2025-11-01', '2025-11-01', 4000000, 'Lunas', 'Selesai'),
(121, 121, 'Kadedeuh gkn wedding only', '2025-11-02', '2025-11-02', 4000000, 'Lunas', 'Selesai'),
(122, 122, 'Prewedd pagwangi dome stanby 8:30', '2025-11-04', '2025-11-04', 2000000, 'Lunas', 'Selesai'),
(123, 123, 'siraman inten', '2025-11-07', '2025-11-07', 1500000, 'Lunas', 'Selesai'),
(124, 124, 'resepsi inten', '2025-11-09', '2025-11-09', 4000000, 'Lunas', 'Selesai'),
(125, 125, 'Wedding Kwp', '2025-11-12', '2025-11-12', 4000000, 'Lunas', 'Selesai'),
(126, 126, 'Wisuda Salma', '2025-11-15', '2025-11-15', 1500000, 'Lunas', 'Selesai'),
(127, 127, 'engagement andika putera', '2025-11-15', '2025-11-15', 1500000, 'Lunas', 'Selesai'),
(128, 128, 'Prewedd pinehilss nadya', '2025-11-17', '2025-11-17', 2000000, 'Lunas', 'Selesai'),
(129, 129, 'Inten wedding', '2025-11-18', '2025-11-18', 4000000, 'Lunas', 'Selesai'),
(130, 130, 'prewedd dimimpi villa', '2025-11-19', '2025-11-19', 2000000, 'Lunas', 'Selesai'),
(131, 131, 'Siraman pengajian', '2025-11-20', '2025-11-20', 1500000, 'Lunas', 'Selesai'),
(132, 132, 'Prewedd grungr febri', '2025-11-21', '2025-11-21', 2000000, 'Lunas', 'Selesai'),
(133, 133, 'prewedd isfa', '2025-11-25', '2025-11-25', 2000000, 'Lunas', 'Selesai'),
(134, 134, 'Prewedd Aulia rose', '2025-11-27', '2025-11-27', 4000000, 'Lunas', 'Selesai'),
(135, 135, 'Wedding Kwp', '2025-12-07', '2025-12-07', 4000000, 'DP', 'Booked'),
(136, 136, 'wedding imah poetih baleendah', '2025-12-07', '2025-12-07', 4000000, 'DP', 'Booked'),
(137, 137, 'prewedd sibyh asia agriaka only', '2025-12-09', '2025-12-09', 2000000, 'DP', 'Booked'),
(138, 138, 'prewedd patenggang chica', '2025-12-12', '2025-12-12', 2000000, 'DP', 'Booked'),
(139, 139, 'paket resto uda riko', '2025-12-18', '2025-12-18', 1500000, 'DP', 'Booked'),
(140, 140, 'Prewedd antika', '2025-12-21', '2025-12-21', 2000000, 'DP', 'Booked'),
(141, 141, 'Isfa rose package', '2025-12-28', '2025-12-28', 4000000, 'DP', 'Booked'),
(142, 142, 'client gina (omennumoto)', '2025-12-28', '2025-12-28', 1500000, 'DP', 'Booked'),
(143, 143, 'Wedding dulur cecep', '2026-01-10', '2026-01-10', 4000000, 'DP', 'Booked'),
(144, 144, 'Atiek Allin Wedding prewedd', '2026-01-11', '2026-01-11', 4000000, 'DP', 'Booked'),
(145, 145, 'Aulia wedding paket rose', '2026-01-17', '2026-01-17', 4000000, 'DP', 'Booked'),
(146, 146, 'Wedding awiw enterpreneur', '2026-01-18', '2026-01-18', 4000000, 'DP', 'Booked'),
(147, 147, 'Siraman Febri kania', '2026-01-23', '2026-01-23', 1500000, 'DP', 'Booked'),
(148, 148, 'Febri kania package', '2026-01-25', '2026-01-25', 7000000, 'DP', 'Booked'),
(149, 149, 'Relyanahrida kopo wedding', '2026-02-08', '2026-02-08', 4000000, 'DP', 'Booked'),
(150, 150, 'Resepsi chica', '2026-02-08', '2026-02-08', 4000000, 'DP', 'Booked'),
(151, 151, 'Badarudamsi Alwedd', '2026-04-04', '2026-04-04', 4000000, 'DP', 'Booked'),
(152, 152, 'wedding mica temen ummi', '2026-04-05', '2026-04-05', 4000000, 'DP', 'Booked'),
(153, 153, 'Baratayudha', '2026-06-06', '2026-06-06', 4000000, 'DP', 'Booked'),
(154, 154, 'Nabilah Wo wedding', '2026-06-06', '2026-06-06', 4000000, 'DP', 'Booked'),
(155, 155, 'Lazieta', '2026-06-07', '2026-06-07', 4000000, 'DP', 'Booked'),
(156, 156, 'Anggie lania package', '2026-06-20', '2026-06-20', 4000000, 'DP', 'Booked');

-- --------------------------------------------------------

--
-- Table structure for table `booking_detail`
--

CREATE TABLE `booking_detail` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(50) NOT NULL,
  `paket_id` int(11) NOT NULL,
  `harga_saat_ini` decimal(15,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_detail`
--

INSERT INTO `booking_detail` (`id`, `booking_id`, `paket_id`, `harga_saat_ini`) VALUES
(1, '1', 6, 4000000),
(2, '2', 1, 2000000),
(3, '3', 6, 4000000),
(4, '4', 6, 4000000),
(5, '5', 1, 2000000),
(6, '6', 6, 4000000),
(7, '7', 1, 2000000),
(8, '8', 13, 1500000),
(9, '9', 6, 4000000),
(10, '10', 13, 1500000),
(11, '11', 1, 2000000),
(12, '12', 6, 4000000),
(13, '13', 6, 4000000),
(14, '14', 1, 2000000),
(15, '15', 1, 2000000),
(16, '16', 1, 2000000),
(17, '17', 6, 4000000),
(18, '18', 1, 2000000),
(19, '19', 1, 2000000),
(20, '20', 1, 2000000),
(21, '21', 1, 2000000),
(22, '22', 1, 2000000),
(23, '23', 1, 2000000),
(24, '24', 1, 2000000),
(25, '25', 9, 7000000),
(26, '26', 1, 2000000),
(27, '27', 1, 2000000),
(28, '28', 13, 1500000),
(29, '29', 6, 4000000),
(30, '30', 6, 4000000),
(31, '31', 6, 4000000),
(32, '32', 13, 1500000),
(33, '33', 6, 4000000),
(34, '34', 13, 1500000),
(35, '35', 13, 1500000),
(36, '36', 1, 2000000),
(37, '37', 6, 4000000),
(38, '38', 13, 1500000),
(39, '39', 1, 2000000),
(40, '40', 1, 2000000),
(41, '41', 1, 2000000),
(42, '42', 13, 1500000),
(43, '43', 1, 2000000),
(44, '44', 6, 4000000),
(45, '45', 1, 2000000),
(46, '46', 1, 2000000),
(47, '47', 13, 1500000),
(48, '48', 13, 1500000),
(49, '49', 1, 2000000),
(50, '50', 1, 2000000),
(51, '51', 13, 1500000),
(52, '52', 6, 4000000),
(53, '53', 6, 4000000),
(54, '54', 1, 2000000),
(55, '55', 1, 2000000),
(56, '56', 6, 4000000),
(57, '57', 6, 4000000),
(58, '58', 1, 2000000),
(59, '59', 1, 2000000),
(60, '60', 1, 2000000),
(61, '61', 1, 2000000),
(62, '62', 6, 4000000),
(63, '63', 6, 4000000),
(64, '64', 6, 4000000),
(65, '65', 1, 2000000),
(66, '66', 1, 2000000),
(67, '67', 1, 2000000),
(68, '68', 1, 2000000),
(69, '69', 6, 4000000),
(70, '70', 6, 4000000),
(71, '71', 13, 1500000),
(72, '72', 6, 4000000),
(73, '73', 6, 4000000),
(74, '74', 6, 4000000),
(75, '75', 6, 4000000),
(76, '76', 6, 4000000),
(77, '77', 13, 1500000),
(78, '78', 13, 1500000),
(79, '79', 13, 1500000),
(80, '80', 1, 2000000),
(81, '81', 1, 2000000),
(82, '82', 1, 2000000),
(83, '83', 1, 2000000),
(84, '84', 1, 2000000),
(85, '85', 6, 4000000),
(86, '86', 1, 2000000),
(87, '87', 1, 2000000),
(88, '88', 13, 1500000),
(89, '89', 1, 2000000),
(90, '90', 1, 2000000),
(91, '91', 1, 2000000),
(92, '92', 13, 1500000),
(93, '93', 1, 2000000),
(94, '94', 1, 2000000),
(95, '95', 6, 4000000),
(96, '96', 6, 4000000),
(97, '97', 1, 2000000),
(98, '98', 1, 2000000),
(99, '99', 1, 2000000),
(100, '100', 1, 2000000),
(101, '101', 1, 2000000),
(102, '102', 13, 1500000),
(103, '103', 1, 2000000),
(104, '104', 13, 1500000),
(105, '105', 6, 4000000),
(106, '106', 1, 2000000),
(107, '107', 1, 2000000),
(108, '108', 1, 2000000),
(109, '109', 1, 2000000),
(110, '110', 6, 4000000),
(111, '111', 1, 2000000),
(112, '112', 6, 4000000),
(113, '113', 1, 2000000),
(114, '114', 1, 2000000),
(115, '115', 1, 2000000),
(116, '116', 6, 4000000),
(117, '117', 13, 1500000),
(118, '118', 6, 4000000),
(119, '119', 1, 2000000),
(120, '120', 6, 4000000),
(121, '121', 6, 4000000),
(122, '122', 1, 2000000),
(123, '123', 13, 1500000),
(124, '124', 6, 4000000),
(125, '125', 6, 4000000),
(126, '126', 13, 1500000),
(127, '127', 13, 1500000),
(128, '128', 1, 2000000),
(129, '129', 6, 4000000),
(130, '130', 1, 2000000),
(131, '131', 13, 1500000),
(132, '132', 1, 2000000),
(133, '133', 1, 2000000),
(134, '134', 6, 4000000),
(135, '135', 6, 4000000),
(136, '136', 6, 4000000),
(137, '137', 1, 2000000),
(138, '138', 1, 2000000),
(139, '139', 13, 1500000),
(140, '140', 1, 2000000),
(141, '141', 6, 4000000),
(142, '142', 13, 1500000),
(143, '143', 6, 4000000),
(144, '144', 6, 4000000),
(145, '145', 6, 4000000),
(146, '146', 6, 4000000),
(147, '147', 13, 1500000),
(148, '148', 9, 7000000),
(149, '149', 6, 4000000),
(150, '150', 6, 4000000),
(151, '151', 6, 4000000),
(152, '152', 6, 4000000),
(153, '153', 6, 4000000),
(154, '154', 6, 4000000),
(155, '155', 6, 4000000),
(156, '156', 6, 4000000);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `nama_client` varchar(100) NOT NULL,
  `no_wa` varchar(20) DEFAULT '-',
  `alamat` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `nama_client`, `no_wa`, `alamat`) VALUES
(1, 'Wedding prewedd diaz wo', '-', 0),
(2, 'Prewedd sore', '-', 0),
(3, 'wedding mahligai selasih', '-', 0),
(4, 'wedding Kadedeuh wo', '-', 0),
(5, 'Ptewedd Junaidi', '-', 0),
(6, 'wedding cinta', '-', 0),
(7, 'prewedd pagi', '-', 0),
(8, 'Siraman uyung', '-', 0),
(9, 'Wedding uyung', '-', 0),
(10, 'Nawrah subang', '-', 0),
(11, 'prewedd unpar sore', '-', 0),
(12, 'wedding Adik ari', '-', 0),
(13, 'Wedding jam 2 isma&imam', '-', 0),
(14, 'prewedd aca jam 10pagi', '-', 0),
(15, 'Prewedd Hazel', '-', 0),
(16, 'Prewedd subuh tiara', '-', 0),
(17, 'Wedding Yunita Bratayuda', '-', 0),
(18, 'prewedd subuh jam 4', '-', 0),
(19, 'Prewedd dea sore', '-', 0),
(20, 'Prewedd mega pagi aljabar', '-', 0),
(21, 'Prewedd Reza', '-', 0),
(22, 'prewed giti subuh', '-', 0),
(23, 'Photoshoot kawah putih', '-', 0),
(24, 'Prewedd client bocil', '-', 0),
(25, 'Engagement Tiara Kania', '-', 0),
(26, 'Prewedd sore studio pasar baru', '-', 0),
(27, 'Prewedd situ patenggang', '-', 0),
(28, 'Siraman inten', '-', 0),
(29, 'Wedding inten', '-', 0),
(30, 'Wedding dayeuhkolot', '-', 0),
(31, 'Wedding kadedeuh RM.Pohon mangga', '-', 0),
(32, 'Birthday party korean house sore', '-', 0),
(33, 'Wedding Ari Wo', '-', 0),
(34, 'Freelance andri abay', '-', 0),
(35, 'Foto Wisuda Tk At tashdiq', '-', 0),
(36, 'Prewedd Bella Dreambelle jam 3sore', '-', 0),
(37, 'Wedding akay', '-', 0),
(38, 'Engagement Cindy', '-', 0),
(39, 'prewedd Adrian pagi', '-', 0),
(40, 'prewedd Tesa', '-', 0),
(41, 'prewedd Nurul', '-', 0),
(42, 'Freelance andri abay', '-', 0),
(43, 'prewedd uzi', '-', 0),
(44, 'prewed ina rose package', '-', 0),
(45, 'Prewedd dw', '-', 0),
(46, 'Prewedd Risma subuh braga', '-', 0),
(47, 'Enggagement kota baru', '-', 0),
(48, 'sumpah dokter', '-', 0),
(49, 'prewedd muti', '-', 0),
(50, 'Prewedd Dw', '-', 0),
(51, 'Engagement tobias martupol', '-', 0),
(52, 'Wedding Gitia', '-', 0),
(53, 'Wedding eddong MC', '-', 0),
(54, 'Prewedd Gita', '-', 0),
(55, 'prewedd subuh braga', '-', 0),
(56, 'Wedding ari wo', '-', 0),
(57, 'intimate marhiza hotel jam 8 standby', '-', 0),
(58, 'prewedd riri', '-', 0),
(59, 'prewedd patenggang', '-', 0),
(60, 'Prewedd Nimo eye pangalengan', '-', 0),
(61, 'Prewedd atlet lari', '-', 0),
(62, 'Wedding wo lyora HBS Cimareme', '-', 0),
(63, 'wedding dw kopo', '-', 0),
(64, 'Wedding korean house acara sore', '-', 0),
(65, 'Prewedd Abc cindy', '-', 0),
(66, 'Prewedd Kadedeuh', '-', 0),
(67, 'Prewedding Classic Package Fathur', '-', 0),
(68, 'prewedd risha', '-', 0),
(69, 'Wedding Khatulistiwa wo', '-', 0),
(70, 'Wedding Extra time', '-', 0),
(71, 'foto keluarga nikahan boda jam 4sore', '-', 0),
(72, 'Ari Wo pagi lagadar', '-', 0),
(73, 'Wedding Nurul hasanan', '-', 0),
(74, 'Darmawangsa', '-', 0),
(75, 'Darmawangsa', '-', 0),
(76, 'DW Wedding', '-', 0),
(77, 'Enggagement cicalengka', '-', 0),
(78, 'Inten sunatan', '-', 0),
(79, 'Foto only request omen', '-', 0),
(80, 'Prewedd emili wildan', '-', 0),
(81, 'prewedd Ajeng', '-', 0),
(82, 'Prewedd Dw', '-', 0),
(83, 'Prewedd studio', '-', 0),
(84, 'Prewedding andika', '-', 0),
(85, 'Wedding indira', '-', 0),
(86, 'prewedd ari wo jam 2 subuh', '-', 0),
(87, 'Prewedd Alda inc makeup', '-', 0),
(88, 'Khitanan', '-', 0),
(89, 'Prewedd eja', '-', 0),
(90, 'prewedd Upas', '-', 0),
(91, 'Prewedd rangga', '-', 0),
(92, 'Engagement', '-', 0),
(93, 'Prewedd kadedeuh', '-', 0),
(94, 'Prewedd selvia', '-', 0),
(95, 'Wedding Ina Rose package', '-', 0),
(96, 'Wedding kadedeuh', '-', 0),
(97, 'Prewedd Roro', '-', 0),
(98, 'Prewedd Annisa robbiatun', '-', 0),
(99, 'Prewedd Crystal', '-', 0),
(100, 'Prewedd situ lembang', '-', 0),
(101, 'prewedd situpatenggang', '-', 0),
(102, 'Foto only Arie nu teu jadi tea', '-', 0),
(103, 'Prewedd mahligai', '-', 0),
(104, 'engagement foto only ujung berung', '-', 0),
(105, 'Wedding mahligai', '-', 0),
(106, 'Prewedd cihapit', '-', 0),
(107, 'Prewed karen at Situ patenggang', '-', 0),
(108, 'prewedd rizal uber sore ke malam', '-', 0),
(109, 'Aris natasia braga + studio classic', '-', 0),
(110, 'Wedding kadedeuh acra sore', '-', 0),
(111, 'Prewedd Kwp wedding 12nov', '-', 0),
(112, 'aprisi alwedd pagi', '-', 0),
(113, 'Prewedd gedung sate', '-', 0),
(114, 'prewedd engko', '-', 0),
(115, 'Prewedd santa pinehills', '-', 0),
(116, 'Wedding', '-', 0),
(117, 'Wisuda upi', '-', 0),
(118, 'Wedding Zoya', '-', 0),
(119, 'prewedd patenggang', '-', 0),
(120, 'wedd & prewedd wen jesika', '-', 0),
(121, 'Kadedeuh gkn wedding only', '-', 0),
(122, 'Prewedd pagwangi dome stanby 8:30', '-', 0),
(123, 'siraman inten', '-', 0),
(124, 'resepsi inten', '-', 0),
(125, 'Wedding Kwp', '-', 0),
(126, 'Wisuda Salma', '-', 0),
(127, 'engagement andika putera', '-', 0),
(128, 'Prewedd pinehilss nadya', '-', 0),
(129, 'Inten wedding', '-', 0),
(130, 'prewedd dimimpi villa', '-', 0),
(131, 'Siraman pengajian', '-', 0),
(132, 'Prewedd grungr febri', '-', 0),
(133, 'prewedd isfa', '-', 0),
(134, 'Prewedd Aulia rose', '-', 0),
(135, 'Wedding Kwp', '-', 0),
(136, 'wedding imah poetih baleendah', '-', 0),
(137, 'prewedd sibyh asia agriaka only', '-', 0),
(138, 'prewedd patenggang chica', '-', 0),
(139, 'paket resto uda riko', '-', 0),
(140, 'Prewedd antika', '-', 0),
(141, 'Isfa rose package', '-', 0),
(142, 'client gina (omennumoto)', '-', 0),
(143, 'Wedding dulur cecep', '-', 0),
(144, 'Atiek Allin Wedding prewedd', '-', 0),
(145, 'Aulia wedding paket rose', '-', 0),
(146, 'Wedding awiw enterpreneur', '-', 0),
(147, 'Siraman Febri kania', '-', 0),
(148, 'Febri kania package', '-', 0),
(149, 'Relyanahrida kopo wedding', '-', 0),
(150, 'Resepsi chica', '-', 0),
(151, 'Badarudamsi Alwedd', '-', 0),
(152, 'wedding mica temen ummi', '-', 0),
(153, 'Baratayudha', '-', 0),
(154, 'Nabilah Wo wedding', '-', 0),
(155, 'Lazieta', '-', 0),
(156, 'Anggie lania package', '-', 0);

-- --------------------------------------------------------

--
-- Table structure for table `freelance`
--

CREATE TABLE `freelance` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `gear` varchar(255) DEFAULT NULL,
  `role` enum('Photographer','Videographer','Assistant','Editor') DEFAULT NULL,
  `harga` decimal(15,2) DEFAULT NULL,
  `domisili` varchar(100) DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `freelance`
--

INSERT INTO `freelance` (`id`, `nama`, `gear`, `role`, `harga`, `domisili`, `no_wa`) VALUES
(1, 'Andi Saputra', 'Sony A7III + Tamron 28-75mm', 'Photographer', 1500000.00, 'Bandung', '6281234567891'),
(2, 'Bayu Nugraha', 'Panasonic GH5 + Gimbal Ronin', 'Videographer', 2000000.00, 'Jakarta', '6281234567892'),
(3, 'Citra Lestari', 'Macbook Pro M1', 'Editor', 750000.00, 'Bandung', '6281234567893'),
(4, 'Dedi Kusnadi', 'Canon R6 + 50mm f1.2', 'Photographer', 1800000.00, 'Cimahi', '6281234567894'),
(5, 'Eka Pratiwi', 'Reflector & Light Stand', 'Assistant', 300000.00, 'Bandung', '6281234567895'),
(6, 'Fajar Sidik', 'Sony A7SIII', 'Videographer', 2500000.00, 'Jakarta', '6281234567896'),
(7, 'Gilang Ramadhan', 'Fujifilm XT-4', 'Photographer', 1200000.00, 'Bogor', '6281234567897'),
(8, 'Hani Wulandari', 'PC Ryzen 7 + RTX 3060', 'Editor', 800000.00, 'Bandung', '6281234567898'),
(9, 'Indra Wijaya', 'Nikon Z6 II', 'Photographer', 1600000.00, 'Depok', '6281234567899'),
(10, 'Joko Susilo', 'Lighting Godox SK400', 'Assistant', 350000.00, 'Bandung', '6281234567800'),
(11, 'Kiki Amalia', 'Sony FX3', 'Videographer', 3000000.00, 'Jakarta', '6281234567801'),
(12, 'Lina Marlina', 'Canon 5D Mark IV', 'Photographer', 1400000.00, 'Bandung', '6281234567802'),
(13, 'Miko Prasetyo', 'Laptop Asus ROG', 'Editor', 900000.00, 'Cimahi', '6281234567803'),
(14, 'Nina Zatulini', 'Sony A7C', 'Photographer', 1300000.00, 'Bandung', '6281234567804'),
(15, 'Oki Setiawan', 'Blackmagic Pocket 4K', 'Videographer', 2200000.00, 'Bandung', '6281234567805'),
(16, 'Andi Pratama', 'Sony A7III + Lensa 24-70mm GM', 'Photographer', 1500000.00, 'Bandung', '6281234567801'),
(17, 'Budi Santoso', 'Panasonic GH5 + Gimbal Ronin S', 'Videographer', 2000000.00, 'Jakarta Selatan', '6281234567802'),
(18, 'Citra Lestari', 'Canon EOS R6 + 50mm f1.2', 'Photographer', 1800000.00, 'Bandung', '6281234567803'),
(19, 'Doni Kurniawan', 'Godox Lighting Set + Reflector', 'Assistant', 500000.00, 'Cimahi', '6281234567804'),
(20, 'Eko Saputra', 'Sony A7SIII + Ninja V', 'Videographer', 2500000.00, 'Jakarta Barat', '6281234567805'),
(21, 'Fajar Hidayat', 'Fujifilm X-T4 + 18-55mm', 'Photographer', 1200000.00, 'Bandung', '6281234567806'),
(22, 'Gilang Ramadhan', 'Sony A6400 + Crane M2', 'Videographer', 1000000.00, 'Sumedang', '6281234567807'),
(23, 'Hani Puspita', 'Nikon Z6 II + 85mm', 'Photographer', 1600000.00, 'Jakarta Timur', '6281234567808'),
(24, 'Indra Wijaya', 'Boom Pole + Zoom H6 Audio', 'Assistant', 450000.00, 'Bandung', '6281234567809'),
(25, 'Joko Susilo', 'Blackmagic Pocket 4K', 'Videographer', 2200000.00, 'Bogor', '6281234567810'),
(26, 'Kartika Sari', 'Canon 5D Mark IV', 'Photographer', 1700000.00, 'Depok', '6281234567811'),
(27, 'Lukman Hakim', 'Drone DJI Mavic Air 2', 'Videographer', 1500000.00, 'Bandung', '6281234567812'),
(28, 'Maya Anggraeni', 'Laptop Macbook Pro (Retouch)', 'Editor', 1000000.00, 'Bekasi', '6281234567813'),
(29, 'Nanda Putra', 'Sony A7C + 35mm', 'Photographer', 1400000.00, 'Jakarta Pusat', '6281234567814'),
(30, 'Oscar Mahendra', 'Sony FX3 Cinema Line', 'Videographer', 3000000.00, 'Tangerang', '6281234567815');

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `paket_id` int(11) NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `kategori` enum('Wedding','Pre-Wedding','Engagement','Other') NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` decimal(15,0) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`paket_id`, `nama_paket`, `kategori`, `deskripsi`, `harga`, `gambar`) VALUES
(1, 'Couple Session - Photo Only', 'Pre-Wedding', '1 Day Session (5 Hours), 1 Photographer, 1 Assistant. Output: 100++ Edited Photos (Tone), 2 Prints 12RP w/ Frame, Soft File via Drive. (Weekday Only).', 2000000, 'prewedd.jpeg'),
(2, 'Couple Session - Photo Video', 'Pre-Wedding', '1 Day Session (5 Hours), 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic Video, 2 Prints 16RP + 4 Prints 4R w/ Frame. (Weekday Only).', 3000000, 'prewedd.jpeg'),
(3, 'Wedding Intimate - Photo Only', 'Wedding', 'Durasi 4 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP w/ Frame, Soft File via Drive.', 2000000, 'wedding.jpeg'),
(4, 'Wedding Intimate - Photo Video', 'Wedding', 'Durasi 4 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic, 30-60 Min Video Documentation, 2 Album Magazine, Flashdisk.', 3000000, 'wedding.jpeg'),
(5, 'Wedding Halfday - Photo Only', 'Wedding', 'Durasi 8 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP w/ Frame, Soft File via Drive.', 2500000, 'wedding.jpeg'),
(6, 'Wedding Halfday - Photo Video', 'Wedding', 'Durasi 8 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic, 30-60 Min Video Documentation, 2 Album Magazine, Flashdisk.', 4000000, 'wedding.jpeg'),
(7, 'Wedding Fullday - Photo Only', 'Wedding', 'Durasi 16 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP w/ Frame, Soft File via Drive.', 4000000, 'wedding.jpeg'),
(8, 'Wedding Fullday - Photo Video', 'Wedding', 'Durasi 16 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic, 30-60 Min Video Documentation, 2 Album Magazine, Flashdisk.', 7000000, 'wedding.jpeg'),
(9, 'Bundling KANIA (Halfday)', 'Wedding', 'WEDDING (8 Jam) + PREWEDD. 1 Photo & 1 Video on Wedding & Prewedd. Include: All Files, Cinematic Video, Album Magazine, Prints, Flashdisk.', 7000000, 'wedding.jpeg'),
(10, 'Bundling KANIA (Fullday)', 'Wedding', 'WEDDING (16 Jam) + PREWEDD. 1 Photo & 1 Video on Wedding & Prewedd. Include: All Files, Cinematic Video, Album Magazine, Prints, Flashdisk.', 10000000, 'wedding.jpeg'),
(11, 'Bundling CALIA (Halfday)', 'Wedding', 'WEDDING (8 Jam) + PREWEDD. Wedding: 2 Photographer + 1 Videographer. Prewedd: 1 Photo + 1 Video. Include: All Files, Cinematic, Album Magazine, Prints.', 9000000, 'wedding.jpeg'),
(12, 'Bundling CALIA (Fullday)', 'Wedding', 'WEDDING (16 Jam) + PREWEDD. Wedding: 2 Photographer + 1 Videographer. Prewedd: 1 Photo + 1 Video. Include: All Files, Cinematic, Album Magazine, Prints.', 12000000, 'wedding.jpeg'),
(13, 'Special Day - Photo Only', 'Engagement', 'Engagement/Birthday/Aqiqah. Durasi 5 Jam. 1 Photographer, 1 Assistant. Output: 100++ Edited Photos, 2 Prints 12RP, Soft File.', 1500000, 'engagement.jpeg'),
(14, 'Special Day - Photo Video', 'Engagement', 'Engagement/Birthday/Aqiqah. Durasi 5 Jam. 1 Photo, 1 Video, 1 Assistant. Output: 100++ Edited Photos, 1-2 Min Cinematic Video.', 2500000, 'engagement.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `gear` varchar(255) DEFAULT NULL,
  `role` enum('Photographer','Videographer','Admin','Editor','Assistant') DEFAULT NULL,
  `gaji` decimal(15,2) DEFAULT NULL,
  `domisili` varchar(100) DEFAULT NULL,
  `no_wa` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `nama`, `gear`, `role`, `gaji`, `domisili`, `no_wa`) VALUES
(2, 'Reza Rahardian', 'PC Editing Ryzen 9 + RTX 3060', 'Editor', 5500000.00, 'Bandung', '6281122334402'),
(3, 'Dimas Anggara', 'Sony A1 + G Master Lens Set', 'Photographer', 7500000.00, 'Jakarta', '6281122334403'),
(4, 'Vino Bastian', 'Red Komodo + Cine Lens Kit', 'Videographer', 8000000.00, 'Jakarta', '6281122334404'),
(5, 'Putri Marinoo', 'Macbook Pro M2 Max', 'Editor', 6000000.00, 'Bandung', '6281122334405');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan_website`
--

CREATE TABLE `pesanan_website` (
  `pesanan_id` int(11) NOT NULL,
  `paket_id` int(11) NOT NULL,
  `nama_pemesan` varchar(100) NOT NULL,
  `no_wa` varchar(20) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `lokasi` text NOT NULL,
  `status` enum('pending','done') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan_website`
--

INSERT INTO `pesanan_website` (`pesanan_id`, `paket_id`, `nama_pemesan`, `no_wa`, `tanggal_booking`, `lokasi`, `status`, `created_at`) VALUES
(1, 3, 'Haykal', '08980564584', '2025-12-31', 'Jl. Sari Indah', 'done', '2025-12-19 13:12:50'),
(2, 1, 'asdasd', '08980564584', '2025-12-31', 'jl.saasf', 'done', '2025-12-19 13:23:13'),
(3, 1, 'wrw', '08980564584', '2025-12-29', '1231231', 'done', '2025-12-19 13:24:07'),
(4, 2, '12321', '412414', '2025-12-31', 'asdasd', 'done', '2025-12-19 13:25:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('CEO','ADMIN') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(1, 'ceo', '0192023a7bbd73250516f069df18b500', 'CEO Enamora', 'CEO'),
(2, 'admin', '0192023a7bbd73250516f069df18b500', 'Admin Staff', 'ADMIN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `booking_detail`
--
ALTER TABLE `booking_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `freelance`
--
ALTER TABLE `freelance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`paket_id`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan_website`
--
ALTER TABLE `pesanan_website`
  ADD PRIMARY KEY (`pesanan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `booking_detail`
--
ALTER TABLE `booking_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT for table `freelance`
--
ALTER TABLE `freelance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `paket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pesanan_website`
--
ALTER TABLE `pesanan_website`
  MODIFY `pesanan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
