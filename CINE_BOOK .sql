-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 07, 2025 at 10:11 AM
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
-- Database: `CINE BOOK`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
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
  `booking_id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `seat_id` text NOT NULL,
  `movie_price` decimal(10,2) DEFAULT NULL,
  `no_of_seats` int(11) DEFAULT NULL CHECK (`no_of_seats` > 0),
  `total_price` decimal(10,2) DEFAULT NULL CHECK (`total_price` >= 0),
  `booking_date` date DEFAULT NULL,
  `upi_id` varchar(50) DEFAULT NULL,
  `upi_password` varchar(50) DEFAULT NULL,
  `show_time` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `features` varchar(30) DEFAULT NULL,
  `show_times` text NOT NULL,
  `cancelation` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cinemas`
--

INSERT INTO `cinemas` (`id`, `name`, `features`, `show_times`, `cancelation`) VALUES
(1, 'Cosmoplex Multiplex : Rajkot', 'M-Ticket', '06:45 PM, 07:30 PM, 10:00 PM', 'Non-cancellable'),
(3, 'MOVIE TIME : Crystal Mall', 'F&B', '12:15 PM , 12:55 PM', 'Non- Cancelation');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `movie_name` text NOT NULL,
  `movie_img` varchar(50) NOT NULL,
  `available_in` varchar(50) NOT NULL,
  `language` varchar(50) NOT NULL,
  `duration` varchar(10) NOT NULL,
  `type` varchar(70) DEFAULT NULL,
  `certification` varchar(4) NOT NULL,
  `release_date` date NOT NULL,
  `movie_price` int(11) NOT NULL,
  `description` text NOT NULL,
  `cast` text NOT NULL,
  `cast_img` text NOT NULL,
  `trailer` text NOT NULL,
  `gradient` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `movie_name`, `movie_img`, `available_in`, `language`, `duration`, `type`, `certification`, `release_date`, `movie_price`, `description`, `cast`, `cast_img`, `trailer`, `gradient`) VALUES
(1, 'Baaghi 4', 'baaghi 4.avif', '2D', 'Hindi', '2h 37m', '', 'A', '2025-09-05', 200, 'A darker spirit, a bloodier mission. This time he is not the same!', 'Tiger Shroff, Sanjay Dutt, Harnaaz Kaur Sandhu, Sonam Bajwa', 'Tiger Shroff.avif, Sanjay Dutt.avif, Harnaaz Kaur Sandhu.avif, Sonam Bajwa.avif', 'https://youtu.be/58909OjAfeg?si=p5RpV3msx1LH6VMR', 'vasu'),
(2, 'Mastiii 4', 'masti 4.avif', '2D', 'Hindi', '2h 22m', 'Adult, Comedy', 'A', '2025-12-02', 550, 'Three frustrated husbands seek freedom from their dull marriages. A wild idea promises escape and excitement. But what follows is anything but expected.', 'Riteish Deshmukh, Nargis Fakhri, Elnaaz Norouzi', 'Ajay Devgn.webp, akshaye khanna.avif, allu arjun.avif', 'https://youtu.be/m43HC9T9YM8?si=7XC6o5m11-CNKxgw', 'vasu');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `seat_id` text NOT NULL,
  `movie_id` int(11) NOT NULL,
  `cinema_id` int(11) NOT NULL
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
  `id` int(15) NOT NULL,
  `name` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `email` text NOT NULL,
  `contact_no` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `contact_no`, `created_at`) VALUES
(1, 'Vasu', 'v', '$2y$10$zQZPpcPpPNAGlMVuYXcz4.XMxUbxZTL9Cq0yrDEy7tqLEgcisw9uS', 'vpansuriya@gmail.com', '9316881521', '2025-09-24 17:10:10'),
(2, 'Mihir', 'm', '$2y$10$x9SJNsmy6av/TMU3YTUCU.lEOTyv9t8J1tIgW0txpv.V.qDoEXAXi', 'm@gmail.com', '1234567890', '2025-09-29 03:00:26');

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cinemas`
--
ALTER TABLE `cinemas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
