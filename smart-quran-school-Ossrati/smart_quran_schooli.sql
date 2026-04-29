-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 22, 2026 at 10:19 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_quran_school_ossrati`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `subscription` varchar(50) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('طالب','مدير','أستاذ','موظف') DEFAULT 'طالب',
  `status` varchar(50) DEFAULT NULL,
  `is_verified` tinyint(4) DEFAULT 0,
  `subscription_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `phone`, `birth_date`, `subscription`, `duration`, `password`, `role`, `status`, `is_verified`, `subscription_end`) VALUES
(1, 'randa debab', '0777641503', '2026-04-06', NULL, 'شهر', '$2y$10$IBLSzo1Dc.g9bQZi7NMGOeRjKFI5lQjGBnyKPJX1q0B366SwO8ieG', 'مدير', 'active', 0, NULL),
(2, 'iness lekhal', '0777641503', '2026-04-10', NULL, 'شهر', '$2y$10$wxjNAktRlJOUQCdM7vmMLu50xzfNVLwSrMzWwbRlZ6t8TBh2eVdA.', '', 'active', 1, '2024-12-30'),
(3, 'd deva', '0777641500', '2026-04-08', NULL, 'شهر', '$2y$10$6B0x/Qhff4MC.srGZ6AB4u6EHSyYgeWw2YRcsuceEaBH5rvAKs3h6', 'طالب', 'active', 1, '2024-12-30'),
(4, 'randa debab', '0777641503', '2026-04-30', NULL, NULL, '$2y$10$MxWfeHDYLIi9P5SRO1EPB.OaKLE5F4ng5hsSS7XOMePqg.NV/pV36', 'طالب', 'suspended', 1, '2024-12-30'),
(5, 'randa debab', '0777641500', '2026-04-08', NULL, NULL, '$2y$10$oxpXKHrOPUtV/F.qq8HZye/GG48YhFtInfgvMuX6Uh0Url0HLLS5S', 'طالب', 'pending', 0, '2023-01-01'),
(6, 'randa debab', '0777641503', '2026-04-30', NULL, NULL, '$2y$10$4Ad81JbyVtj9MXRz4uT9/.L120tUQ0/4/k.2Uj1aEevUecOE0BrwG', 'طالب', 'pending', 0, NULL),
(7, 'randa debab', '0777641503', '2026-04-30', NULL, NULL, '$2y$10$AZ0fJs5mgPSCpzozPt.VwON2DkVt/zayHldl9nNxQreJ3ouHl69bS', 'طالب', 'pending', 0, NULL),
(8, 'randa debab', '0777641503', '2026-04-29', NULL, NULL, '$2y$10$ZTZL8zbbelahPd.a3TbhqegIAv8RWvNXDaDueOM6AJf20JUR29nIm', 'طالب', 'pending', 0, NULL),
(9, 'nor', '0777641503', '2026-04-29', NULL, NULL, '$2y$10$C28zfzOZ6Zlr23epAagoEObLrtFKCWM0tBqsAssndslPckcM8541e', 'طالب', 'pending', 0, NULL),
(10, 'randoch', '0777641503', '2026-04-08', NULL, NULL, '$2y$10$vT/UVKHqWiErnYAR9f7Kq.c/5keX8LlgUwMa8YXtSEEmnvXE14VGi', 'طالب', 'pending', 0, NULL),
(11, 'randoch', '0777641503', '2026-04-08', 'شهر', NULL, '$2y$10$r6r1dhpeuFg2hzoBvhAwS.ZdiVXtSCLjkc66QbcsPB5rxhGaA1iJa', 'طالب', 'pending', 0, NULL),
(12, 'randoch', '0777641503', '2026-04-08', 'شهر', NULL, '$2y$10$EoqAH/DBvJMrJfR34ChYuO97URd5YQzqiY2I5ucXXqjdZAgaZ2fGG', 'طالب', 'pending', 0, NULL),
(13, 'randoch', '0777641503', '2026-04-08', 'شهر', NULL, '$2y$10$bF2tQ8CtP.cp7ymEXgJQ/uTvgvSkrKwlotBxb99hCS6buygq64SHm', 'طالب', 'pending', 0, NULL),
(15, 'ma', '0770871617', '2026-04-08', 'شهر', NULL, '$2y$10$lS8nFdVZ5XYQvRzTzmnEf.B449btj65O4MTBU5s/HnBnz/WSzw0Bm', 'طالب', 'pending', 0, NULL),
(17, 'asma', '0888888889', '2026-04-08', 'شهر', NULL, '$2y$10$4zh68DyyQ5y1Qtl.hY/YDeCPX2vfi1mGLLg4XNbjtG9joboNmjuea', 'طالب', 'active', 0, NULL),
(18, 'saraa', '0777641503', '2026-04-09', 'شهر', NULL, '$2y$10$RYWbxybiA6Nk3NgIA4Ex7ece4GfD15HU2oKnSGYcakZF.BBQp/sv2', 'طالب', 'pending', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
