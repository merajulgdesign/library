-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2025 at 07:09 AM
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
-- Database: `meraj`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `role`) VALUES
(205024027, 'Merajul Islam', 'merajul@gmail.com', '$2y$10$1pwjqXciKo5ym5lgD2pCau1PwrKqGF/R81EBPupJkCJ0Of8tiLEqO', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `edition` varchar(255) NOT NULL,
  `publication` varchar(255) NOT NULL,
  `market_price` int(255) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `available_copies` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `description`, `category`, `isbn`, `edition`, `publication`, `market_price`, `quantity`, `available_copies`, `image`, `added_date`) VALUES
(2, 'To Kill a Mockingbird', 'Harper Lee', 'To Kill a Mockingbird is a 1960 Southern Gothic novel by American author Harper Lee. It became instantly successful after its release; in the United States, it is widely read in high schools and middle schools', 'Literature', '9780060935467', '15th', 'J. B. Lippincott & Co.', 1000, 14, 3, '1760609557_f4322c1e8d62.jpg', '2025-10-16 05:25:10'),
(8, 'The Loneliness of Sonia & Sunny', 'Kiran Desai', 'Booker winner Desai returns 19 years after The Inheritance of Loss with an elegant bildungsroman of two Indian people and their convergence in the early 2000s U.S.', 'Romantic Novel', '978-0-3077-0015-5', '2nd', 'Hogarth', 999, 12, 19, '1760609538_fa4996f669d3.jpg', '2025-10-16 08:37:14');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `fine_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','borrowed','return_requested','returned','rejected') DEFAULT 'pending',
  `return_status` varchar(50) DEFAULT 'none',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `user_id`, `book_id`, `borrow_date`, `due_date`, `return_date`, `fine_amount`, `status`, `return_status`, `created_at`) VALUES
(1, 205024028, 8, '2025-10-17', NULL, '2025-10-17', 101889.58, 'returned', 'none', '2025-10-17 10:42:07'),
(2, 205024028, 8, '2025-10-17', '2025-10-24', '2025-10-17', 0.00, 'returned', 'none', '2025-10-17 10:42:27'),
(3, 205024028, 2, '2025-10-17', '2025-10-24', '2025-10-17', 0.00, 'returned', 'none', '2025-10-17 10:50:58'),
(4, 205024028, 8, '2025-10-17', '2025-10-24', NULL, 0.00, 'borrowed', 'none', '2025-10-17 14:16:37');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `roll` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `admin_reply` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `reply_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `roll`, `email`, `subject`, `message`, `admin_reply`, `created_at`, `reply_date`) VALUES
(1, 'ariful', '123', 'ariful@gmail.com', 'greetings', 'Assalamu Alaikum.', 'walaikum assalam', '2025-10-18 10:43:58', '2025-10-18 10:44:36');

-- --------------------------------------------------------

--
-- Table structure for table `library_cards`
--

CREATE TABLE `library_cards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_number` varchar(50) NOT NULL,
  `issue_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `library_cards`
--

INSERT INTO `library_cards` (`id`, `user_id`, `card_number`, `issue_date`, `expiry_date`, `qr_code`) VALUES
(1, 205024028, 'LIB2025-205024028', '2025-10-17', '2026-10-17', 'qrcodes/LIB2025-205024028.png');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `fine_per_day` decimal(10,2) DEFAULT 5.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `fine_per_day`, `created_at`, `updated_at`) VALUES
(1, 5.00, '2025-10-17 05:56:30', '2025-10-17 05:56:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `roll` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `borrow_limit` int(255) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `roll`, `email`, `password`, `role`, `borrow_limit`) VALUES
(205024027, 'Merajul Islam', 120, 'merajul@gmail.com', '$2y$10$eoMqaaTeQQhFhrYBZCpzwuUGTwgBdMuLY8cZdfEEaTVEEenk/qa7i', 'admin', 3),
(205024028, 'Ariful', 123, 'ariful@gmail.com', '$2y$10$wVs3VV/u/A4UYAJNp7S77uretnPnIa5bt8M1oxbB5/iiY1BJj/T5u', 'user', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `library_cards`
--
ALTER TABLE `library_cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card_number` (`card_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`roll`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205024028;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `library_cards`
--
ALTER TABLE `library_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205024029;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `library_cards`
--
ALTER TABLE `library_cards`
  ADD CONSTRAINT `library_cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
