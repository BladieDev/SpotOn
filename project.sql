-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 07:49 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`user_id`, `friend_id`, `created_at`) VALUES
(11, 13, '2024-11-29 18:47:31'),
(12, 13, '2024-11-29 18:43:54'),
(13, 11, '2024-11-29 18:47:31'),
(13, 12, '2024-11-29 18:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `sender_id`, `receiver_id`, `status`, `created_at`) VALUES
(26, 13, 11, 'accepted', '2024-11-29 18:27:29'),
(27, 13, 12, 'accepted', '2024-11-29 18:27:34'),
(28, 13, 13, 'pending', '2024-11-29 18:27:36'),
(29, 12, 8, 'pending', '2024-11-29 18:44:28');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `user_id`, `location_name`, `latitude`, `longitude`) VALUES
(1, 9, 'RZYGI olafa', 53.44452568, 14.54776670),
(2, 9, 'mbepe', 53.42099720, 14.50214715),
(3, 9, ':(', 53.43081670, 14.55516363),
(4, 10, 'c', 53.43674182, 14.51826515),
(5, 10, 'b', 53.43111713, 14.53337493),
(6, 10, 'd', 53.43234440, 14.53448977),
(7, 10, 'e', 53.41935400, 14.53544223),
(8, 10, 'grudziadz 1', 53.48804554, 18.75249126),
(9, 10, 'bb', 50.32880636, 19.20136468),
(10, 10, 'dwa bulki', 53.60308044, 17.78236096),
(12, 10, 'brzoskwinka river', 50.06167786, 19.76486580),
(13, 10, 'spot na ryby trzeba sprawdzic na lato', 53.37185927, 14.33545078),
(14, 10, 'dziwny Krakow', 53.34330111, 14.26399320),
(15, 10, 'Krakow nad morzem', 53.64992898, 12.26835550);

-- --------------------------------------------------------

--
-- Table structure for table `miasta`
--

CREATE TABLE `miasta` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `miasta`
--

INSERT INTO `miasta` (`id`, `nazwa`, `latitude`, `longitude`) VALUES
(1, 'Warszawa', 52.22967600, 21.01222900),
(2, 'Kraków', 50.06465000, 19.94498000),
(3, 'Łódź', 51.75924800, 19.45598300),
(4, 'Wrocław', 51.10788300, 17.03853800),
(5, 'Poznań', 52.40637400, 16.92516800),
(6, 'Gdańsk', 54.35202500, 18.64663800),
(7, 'Szczecin', 53.42854300, 14.55281200),
(8, 'Bydgoszcz', 53.12348200, 18.00843800),
(9, 'Lublin', 51.24645200, 22.56844600),
(10, 'Katowice', 50.26489200, 19.02378100),
(11, 'Białystok', 53.13248800, 23.16884000),
(12, 'Gdynia', 54.51889000, 18.53054000),
(13, 'Częstochowa', 50.81181600, 19.12030900),
(14, 'Radom', 51.40272300, 21.14713300),
(15, 'Sosnowiec', 50.28626400, 19.10407900),
(16, 'Toruń', 53.01379000, 18.59844400),
(17, 'Kielce', 50.86607700, 20.62856800),
(18, 'Gliwice', 50.29449200, 18.67138000),
(19, 'Zabrze', 50.32492800, 18.78571900),
(20, 'Bytom', 50.34838100, 18.91571800),
(21, 'Bielsko-Biała', 49.82237700, 19.05838400),
(22, 'Olsztyn', 53.77842200, 20.48011900),
(23, 'Rzeszów', 50.04118700, 21.99912000),
(24, 'Ruda Śląska', 50.25582800, 18.85557100),
(25, 'Rybnik', 50.09708800, 18.54179700),
(26, 'Tychy', 50.12397300, 18.99743800),
(27, 'Dąbrowa Górnicza', 50.31809600, 19.20455600),
(28, 'Opole', 50.67510600, 17.92129800),
(29, 'Elbląg', 54.15224100, 19.40449000),
(30, 'Płock', 52.54634400, 19.70653600),
(31, 'Wałbrzych', 50.77140700, 16.28432100),
(32, 'Gorzów Wielkopolski', 52.73678800, 15.22878100),
(33, 'Włocławek', 52.64803800, 19.06779700),
(34, 'Zielona Góra', 51.93562100, 15.50618600),
(35, 'Tarnów', 50.01249300, 20.98801400),
(36, 'Chorzów', 50.30581900, 18.97420000),
(37, 'Kalisz', 51.76721800, 18.08549100),
(38, 'Koszalin', 54.19438400, 16.17222300),
(39, 'Legnica', 51.20700600, 16.15538000),
(40, 'Grudziądz', 53.48496400, 18.75362200),
(41, 'Jaworzno', 50.20555800, 19.27409600),
(42, 'Słupsk', 54.46405100, 17.02866200),
(43, 'Jastrzębie-Zdrój', 49.95027000, 18.57498700),
(44, 'Nowy Sącz', 49.61732400, 20.71580800),
(45, 'Jelenia Góra', 50.90487800, 15.71994600),
(46, 'Siedlce', 52.16744200, 22.29099800),
(47, 'Mysłowice', 50.20829400, 19.16667400),
(48, 'Konin', 52.22309800, 18.25183900),
(49, 'Piotrków Trybunalski', 51.40538600, 19.70382600),
(50, 'Inowrocław', 52.79811600, 18.26364700),
(51, 'Lubin', 51.40065200, 16.20198600),
(52, 'Ostrowiec Świętokrzyski', 50.92987800, 21.38508200),
(53, 'Gniezno', 52.53409300, 17.58255700),
(54, 'Suwałki', 54.09948100, 22.93332200),
(55, 'Ostrołęka', 53.08419800, 21.55927500),
(56, 'Stargard', 53.33683900, 15.03264500),
(57, 'Pabianice', 51.66462700, 19.35472700),
(58, 'Łomża', 53.17993800, 22.05904900),
(59, 'Leszno', 51.84153700, 16.57420800),
(60, 'Żory', 50.04503900, 18.70034000),
(61, 'Tomaszów Mazowiecki', 51.53172100, 20.00874800),
(62, 'Przemyśl', 49.78345800, 22.76968900),
(63, 'Stalowa Wola', 50.57107200, 22.05396000),
(64, 'Kędzierzyn-Koźle', 50.35004800, 18.22601500),
(65, 'Łowicz', 52.10592500, 19.94117700),
(66, 'Olkusz', 50.28163200, 19.56219100),
(67, 'Skarżysko-Kamienna', 51.11534500, 20.86748900),
(68, 'Pruszków', 52.17008300, 20.80881100),
(69, 'Świdnica', 50.84367600, 16.48977100),
(70, 'Biała Podlaska', 52.03241000, 23.11688100),
(71, 'Ciechanów', 52.88115000, 20.61473100),
(72, 'Grodzisk Mazowiecki', 52.10377000, 20.62611000),
(73, 'Brodnica', 53.25942800, 19.39953000),
(74, 'Kołobrzeg', 54.17631800, 15.57695000),
(75, 'Wągrowiec', 52.80849600, 17.19473100),
(76, 'Świecie', 53.41028700, 18.43769100),
(77, 'Zgierz', 51.85539800, 19.40937300),
(78, 'Jarosław', 50.01683600, 22.67809500),
(79, 'Bartoszyce', 54.25136100, 20.80867800),
(80, 'Piła', 53.15162800, 16.73848200),
(81, 'Biskupiec', 53.85716800, 20.94994800),
(82, 'Malbork', 54.03625400, 19.03788100),
(83, 'Nowogard', 53.67464200, 15.11657200),
(84, 'Nakło nad Notecią', 53.14175100, 17.60128600),
(85, 'Chełmno', 53.34766100, 18.42525300),
(86, 'Polkowice', 51.50352200, 16.07131100),
(87, 'Radomsko', 51.06804900, 19.44424500),
(88, 'Świnoujście', 53.91020900, 14.24725500),
(89, 'Ząbki', 52.27449200, 21.10428200),
(90, 'Żary', 51.64205700, 15.14053300),
(91, 'Pszczyna', 49.98042300, 18.94748000);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `currency` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `currency`) VALUES
(1, 'Michal', 'michalpocislaw@gmail.com', '1e0959e42c8f0920b3fc6e0a7fee7518', 0),
(8, 'Nikodem', 'nikodem2287@gmail.com', 'd87d24c791fa7b14fc03a0f9c842a5d6', 0),
(9, 'Michalsmierdzipotem', 'huj@gmail.com', 'd87d24c791fa7b14fc03a0f9c842a5d6', 0),
(10, 'Nkdm', 'nkdm@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 0),
(11, 'Nk1', 'nk1@gmail.com', '28a8437b1ab785fecd0270ca8da6c98c', 0),
(12, 'Nk', 'nk@gmail.com', '7d3bbe1a34b64921e0902868320a7ca4', 0),
(13, 'Nk2', 'nk2@gmail.com', '56b6cd74067895a84e31ca4b410a11b6', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`user_id`,`friend_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `miasta`
--
ALTER TABLE `miasta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `miasta`
--
ALTER TABLE `miasta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD CONSTRAINT `friend_requests_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `friend_requests_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `locations`
--
ALTER TABLE `locations`
  ADD CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
