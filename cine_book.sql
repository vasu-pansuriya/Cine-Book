-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 08, 2025 at 03:08 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cine book`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int NOT NULL,
  `movie_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `seat_id` text COLLATE utf8mb4_general_ci NOT NULL,
  `movie_price` decimal(10,2) DEFAULT NULL,
  `no_of_seats` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `upi_id` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `upi_password` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `show_time` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `movie_id`, `user_id`, `seat_id`, `movie_price`, `no_of_seats`, `total_price`, `booking_date`, `upi_id`, `upi_password`, `show_time`) VALUES
(1, 1, 1, 'E7,E8', 200.00, 2, 400.00, '2025-10-03', 'v@123', 'vasu', '10:00 PM'),
(2, 1, 1, 'E7,E8', 200.00, 2, 400.00, '2025-10-03', 'v@123', 'vasu', '10:00 PM'),
(3, 1, 1, 'E6,D6', 200.00, 2, 400.00, '2025-10-03', 'v@123', 'vasu', '10:00 PM'),
(4, 1, 1, 'E10,E11', 200.00, 2, 400.00, '2025-10-07', 'v@123', 'vasu', '07:30 PM'),
(5, 1, 1, 'F1,F2,F3,F4,F5,F6,F7,F8,F9,F10,F11,F12,F13,F14,F15,F16,F18,F17,F19,F20', 200.00, 20, 4000.00, '2025-12-08', 'v@123', 'mkiomno', '10:00 PM'),
(6, 1, 1, 'E3,E4', 200.00, 2, 400.00, '2025-12-02', 'v@123', 'vasu', '07:30 PM');

-- --------------------------------------------------------

--
-- Table structure for table `cinemas`
--

CREATE TABLE `cinemas` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `features` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `show_times` text COLLATE utf8mb4_general_ci NOT NULL,
  `cancelation` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cinemas`
--

INSERT INTO `cinemas` (`id`, `name`, `features`, `show_times`, `cancelation`) VALUES
(1, 'Cosmoplex Multiplex : Rajkot', 'M-Ticket', '06:45 PM, 07:30 PM, 10:00 PM', 'Non-cancellable'),
(3, 'MOVIE TIME : Crystal Mall', 'F&B and comforting.', '12:15 PM , 12:55 PM', 'Non- Cancelation');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int NOT NULL,
  `movie_name` text COLLATE utf8mb4_general_ci NOT NULL,
  `movie_img` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `available_in` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `language` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `duration` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `type` varchar(70) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `certification` varchar(4) COLLATE utf8mb4_general_ci NOT NULL,
  `release_date` date NOT NULL,
  `movie_price` int NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `cast` text COLLATE utf8mb4_general_ci NOT NULL,
  `cast_img` text COLLATE utf8mb4_general_ci NOT NULL,
  `trailer` text COLLATE utf8mb4_general_ci NOT NULL,
  `gradient` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_premiere` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `movie_name`, `movie_img`, `available_in`, `language`, `duration`, `type`, `certification`, `release_date`, `movie_price`, `description`, `cast`, `cast_img`, `trailer`, `gradient`, `featured`, `is_premiere`) VALUES
(1, 'Baaghi 4', 'baaghi 4.avif', '2D', 'Hindi', '2h 37m', '', 'A', '2025-09-05', 200, 'A darker spirit, a bloodier mission. This time he is not the same!', 'Tiger Shroff, Sanjay Dutt, Harnaaz Kaur Sandhu, Sonam Bajwa', 'Tiger Shroff.avif, Sanjay Dutt.avif, Harnaaz Kaur Sandhu.avif, Sonam Bajwa.avif', 'https://youtu.be/58909OjAfeg?si=p5RpV3msx1LH6VMR', 'vasu', 0, 0),
(2, 'Masti 4', 'masti 4.avif', '2D', 'Hindi', '2h 22m', 'Adult, Comedy', '0', '2025-12-02', 550, 'Three frustrated husbands seek freedom from their dull marriages. A wild idea promises escape and excitement. But what follows is anything but expected.', 'Riteish Deshmukh, Nargis Fakhri, Elnaaz Norouzi', 'Ajay Devgn.webp, akshaye khanna.avif, allu arjun.avif', 'https://youtu.be/m43HC9T9YM8?si=7XC6o5m11-CNKxgw', 'vasu', 0, 1),
(3, 'Avatar: The Way of Water', 'avatar.jpeg', '', 'English', '', '', 'A', '2025-12-11', 350, '', '', '', '', 'vasu', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int NOT NULL,
  `seat_id` text COLLATE utf8mb4_general_ci NOT NULL,
  `movie_id` int NOT NULL,
  `cinema_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `seat_id`, `movie_id`, `cinema_id`) VALUES
(1, 'E7,E8', 1, 1),
(2, 'E7,E8', 1, 1),
(3, 'E6,D6', 1, 1),
(4, 'E10,E11', 1, 1),
(5, 'F1,F2,F3,F4,F5,F6,F7,F8,F9,F10,F11,F12,F13,F14,F15,F16,F18,F17,F19,F20', 1, 1),
(6, 'E3,E4', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` text COLLATE utf8mb4_general_ci NOT NULL,
  `username` text COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  `email` text COLLATE utf8mb4_general_ci NOT NULL,
  `contact_no` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `contact_no`, `created_at`) VALUES
(1, 'Vasu', 'v', '$2y$10$zQZPpcPpPNAGlMVuYXcz4.XMxUbxZTL9Cq0yrDEy7tqLEgcisw9uS', 'vpansuriya@gmail.com', '9316881521', '2025-09-24 17:10:10'),
(2, 'Mihir', 'm', '$2y$10$x9SJNsmy6av/TMU3YTUCU.lEOTyv9t8J1tIgW0txpv.V.qDoEXAXi', 'm@gmail.com', '1234567890', '2025-09-29 03:00:26'),
(3, 'sneha merja', 'sneha', '$2y$10$aFY6kUDvvf/IdHnmCvM5t.PR5kJHOE.JWFFQXRjxIy8HoVaLMlLJe', 's@gmail.com', '9316881521', '2025-12-07 09:25:43'),
(4, 'william johns', 'willii', '$2y$10$/SyU3F2BqZ7PtmUdMU3bA.QqpSEG2Wj9bPZp97JsFHn0HEFIE349m', 'w@gmail.com', '1234567676', '2025-12-08 10:10:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cinemas`
--
ALTER TABLE `cinemas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cinemas`
--
ALTER TABLE `cinemas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
