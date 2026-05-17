-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2026 at 12:50 PM
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
-- Database: `smart_quran_schooli`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` enum('general','admin','urgent') DEFAULT 'general',
  `target_role` enum('all','student','teacher','employee','admin') DEFAULT 'all',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `type`, `target_role`, `created_by`, `created_at`) VALUES
(1, 'امتحانات تقيمية', 'يوجد امتحان تقيمي لكل المستويات على جميع التلاميذ مراجعة احزابهم', 'urgent', 'student', 1, '2026-04-24 13:14:47'),
(2, 'مسابقة', 'توجد مسابقات على مستوى قسم النشاط الطلابي \r\nيرجى من المهتمين التقرب الى القسم من اجل التسجيل او معرفة معلومات وتفاصيل اكثر', 'general', 'all', 1, '2026-04-24 13:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('حاضر','غائب','متأخر') DEFAULT 'حاضر',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `group_id`, `date`, `status`, `notes`, `created_at`) VALUES
(109, 83, 1, '2026-05-03', 'غائب', NULL, '2026-05-03 21:22:32'),
(110, 85, 1, '2026-05-03', 'حاضر', NULL, '2026-05-03 21:22:32'),
(111, 73, 1, '2026-05-03', 'حاضر', NULL, '2026-05-03 21:22:32'),
(112, 82, 1, '2026-05-03', 'غائب', NULL, '2026-05-03 21:22:32'),
(113, 81, 1, '2026-05-03', 'حاضر', NULL, '2026-05-03 21:22:32'),
(114, 84, 1, '2026-05-03', 'حاضر', NULL, '2026-05-03 21:22:32'),
(115, 80, 1, '2026-05-03', 'حاضر', NULL, '2026-05-03 21:22:32'),
(116, 83, 1, '2026-05-04', 'غائب', NULL, '2026-05-04 09:11:38'),
(117, 85, 1, '2026-05-04', 'حاضر', NULL, '2026-05-04 09:11:38'),
(118, 73, 1, '2026-05-04', 'حاضر', NULL, '2026-05-04 09:11:38'),
(119, 82, 1, '2026-05-04', 'حاضر', NULL, '2026-05-04 09:11:38'),
(120, 81, 1, '2026-05-04', 'حاضر', NULL, '2026-05-04 09:11:38'),
(121, 84, 1, '2026-05-04', 'حاضر', NULL, '2026-05-04 09:11:38'),
(122, 80, 1, '2026-05-04', 'حاضر', NULL, '2026-05-04 09:11:38'),
(123, 83, 1, '2026-05-05', 'غائب', NULL, '2026-05-05 11:44:42'),
(124, 85, 1, '2026-05-05', 'حاضر', NULL, '2026-05-05 11:44:42'),
(125, 73, 1, '2026-05-05', 'حاضر', NULL, '2026-05-05 11:44:42'),
(126, 82, 1, '2026-05-05', 'حاضر', NULL, '2026-05-05 11:44:42'),
(127, 81, 1, '2026-05-05', 'حاضر', NULL, '2026-05-05 11:44:42'),
(128, 84, 1, '2026-05-05', 'حاضر', NULL, '2026-05-05 11:44:42'),
(129, 80, 1, '2026-05-05', 'حاضر', NULL, '2026-05-05 11:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `daily_evaluation`
--

CREATE TABLE `daily_evaluation` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `evaluation_date` date NOT NULL,
  `session_time` time NOT NULL,
  `surah_name` varchar(100) NOT NULL COMMENT 'اسم السورة',
  `from_ayah` int(5) NOT NULL COMMENT 'من آية',
  `to_ayah` int(5) NOT NULL COMMENT 'إلى آية',
  `total_ayahs` int(5) NOT NULL COMMENT 'عدد الآيات المحفوظة في هذه الحصة',
  `memorization_score` tinyint(2) DEFAULT 0 COMMENT 'درجة الحفظ (0-10)',
  `recitation_score` tinyint(2) DEFAULT 0 COMMENT 'درجة التلاوة (0-10)',
  `tajweed_score` tinyint(2) DEFAULT 0 COMMENT 'درجة التجويد (0-10)',
  `status` enum('حاضر','غائب','متأخر') DEFAULT 'حاضر',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_evaluation`
--

INSERT INTO `daily_evaluation` (`id`, `student_id`, `group_id`, `teacher_id`, `evaluation_date`, `session_time`, `surah_name`, `from_ayah`, `to_ayah`, `total_ayahs`, `memorization_score`, `recitation_score`, `tajweed_score`, `status`, `notes`, `created_at`) VALUES
(90, 85, 1, 1, '2026-05-03', '23:22:32', 'الفاتحة', 1, 7, 0, 18, 0, 0, 'حاضر', NULL, '2026-05-03 21:22:32'),
(91, 73, 1, 1, '2026-05-03', '23:22:32', 'الفاتحة', 1, 7, 0, 19, 0, 0, 'حاضر', NULL, '2026-05-03 21:22:32'),
(92, 81, 1, 1, '2026-05-03', '23:22:32', 'الفاتحة', 1, 7, 0, 18, 0, 0, 'حاضر', NULL, '2026-05-03 21:22:32'),
(93, 84, 1, 1, '2026-05-03', '23:22:32', 'الفاتحة', 1, 7, 0, 18, 0, 0, 'حاضر', NULL, '2026-05-03 21:22:32'),
(94, 80, 1, 1, '2026-05-03', '23:22:32', 'الفاتحة', 1, 7, 0, 19, 0, 0, 'حاضر', NULL, '2026-05-03 21:22:32'),
(95, 85, 1, 1, '2026-05-04', '11:11:38', 'الفاتحة', 8, 8, 0, 18, 0, 0, 'حاضر', NULL, '2026-05-04 09:11:38'),
(96, 73, 1, 1, '2026-05-04', '11:11:38', 'البقرة', 1, 37, 0, 18, 0, 0, 'حاضر', NULL, '2026-05-04 09:11:38'),
(97, 82, 1, 1, '2026-05-04', '11:11:38', 'الفاتحة', 1, 7, 0, 16, 0, 0, 'حاضر', NULL, '2026-05-04 09:11:38'),
(98, 81, 1, 1, '2026-05-04', '11:11:38', 'البقرة', 1, 40, 0, 16, 0, 0, 'حاضر', NULL, '2026-05-04 09:11:38'),
(99, 84, 1, 1, '2026-05-04', '11:11:38', 'البقرة', 1, 21, 0, 18, 0, 0, 'حاضر', NULL, '2026-05-04 09:11:38'),
(100, 80, 1, 1, '2026-05-04', '11:11:38', 'البقرة', 1, 40, 0, 17, 0, 0, 'حاضر', NULL, '2026-05-04 09:11:38'),
(101, 85, 1, 1, '2026-05-05', '13:44:42', 'البقرة', 1, 43, 0, 19, 0, 0, 'حاضر', NULL, '2026-05-05 11:44:42'),
(102, 73, 1, 1, '2026-05-05', '13:44:42', 'البقرة', 38, 101, 0, 14, 0, 0, 'حاضر', NULL, '2026-05-05 11:44:42'),
(103, 82, 1, 1, '2026-05-05', '13:44:42', 'البقرة', 1, 46, 0, 16, 0, 0, 'حاضر', NULL, '2026-05-05 11:44:42'),
(104, 81, 1, 1, '2026-05-05', '13:44:42', 'البقرة', 41, 83, 0, 17, 0, 0, 'حاضر', NULL, '2026-05-05 11:44:42'),
(105, 84, 1, 1, '2026-05-05', '13:44:42', 'البقرة', 22, 40, 0, 16, 0, 0, 'حاضر', NULL, '2026-05-05 11:44:42'),
(106, 80, 1, 1, '2026-05-05', '13:44:42', 'البقرة', 41, 83, 0, 15, 0, 0, 'حاضر', NULL, '2026-05-05 11:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `exam_title` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `exam_date` date NOT NULL,
  `exam_type` enum('weekly','monthly','final') DEFAULT 'weekly',
  `max_hifz_score` int(3) DEFAULT 8 COMMENT 'الحد الأقصى للحفظ (8)',
  `max_ahkam_score` int(3) DEFAULT 8 COMMENT 'الحد الأقصى للأحكام (8)',
  `max_makharij_score` int(3) DEFAULT 4 COMMENT 'الحد الأقصى للمخارج (4)',
  `total_max_score` int(3) DEFAULT 20 COMMENT 'المجموع الكلي (20)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `exam_title`, `group_id`, `teacher_id`, `exam_date`, `exam_type`, `max_hifz_score`, `max_ahkam_score`, `max_makharij_score`, `total_max_score`, `created_at`) VALUES
(1, 'اختبار أسبوعي', 1, 1, '2026-04-24', '', 8, 8, 4, 20, '2026-04-24 16:48:59'),
(2, 'اختبار أسبوعي - 2026-04-24', 1, 1, '2026-04-24', '', 8, 8, 4, 20, '2026-04-24 16:54:27'),
(3, 'اختبار أسبوعي - 2026-04-24', 1, 1, '2026-04-24', '', 8, 8, 4, 20, '2026-04-24 17:00:15'),
(4, 'اختبار أسبوعي - 2026-04-24', 1, 1, '2026-04-24', '', 8, 8, 4, 20, '2026-04-24 18:50:06'),
(5, 'اختبار أسبوعي - 2026-04-26', 1, 1, '2026-04-26', '', 8, 8, 4, 20, '2026-04-26 09:08:30'),
(6, 'اختبار أسبوعي - 2026-04-27', 1, 1, '2026-04-27', '', 8, 8, 4, 20, '2026-04-27 13:24:32'),
(7, 'اختبار أسبوعي - 2026-05-04', 1, 1, '2026-05-04', '', 8, 8, 4, 20, '2026-05-04 09:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `exam_results`
--

CREATE TABLE `exam_results` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `hifz_score` int(2) DEFAULT 0 COMMENT 'درجة الحفظ (من 8)',
  `ahkam_score` int(2) DEFAULT 0 COMMENT 'درجة الأحكام (من 8)',
  `makharij_score` int(2) DEFAULT 0 COMMENT 'درجة المخارج (من 4)',
  `total_score` decimal(5,2) DEFAULT 0.00 COMMENT 'المجموع الكلي (من 20)',
  `stars` int(1) DEFAULT 0 COMMENT 'عدد النجوم (1-5 حسب النسبة)',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_results`
--

INSERT INTO `exam_results` (`id`, `exam_id`, `student_id`, `hifz_score`, `ahkam_score`, `makharij_score`, `total_score`, `stars`, `notes`, `created_at`) VALUES
(23, 7, 85, 7, 7, 4, 18.00, 0, NULL, '2026-05-04 09:12:32'),
(24, 7, 73, 8, 6, 3, 17.00, 0, NULL, '2026-05-04 09:12:32'),
(25, 7, 82, 5, 6, 3, 14.00, 0, NULL, '2026-05-04 09:12:32'),
(26, 7, 81, 7, 8, 4, 19.00, 0, NULL, '2026-05-04 09:12:32'),
(27, 7, 84, 8, 7, 4, 19.00, 0, NULL, '2026-05-04 09:12:32'),
(28, 7, 80, 7, 8, 4, 19.00, 0, NULL, '2026-05-04 09:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(100) NOT NULL,
  `teacher_name` varchar(255) NOT NULL,
  `academic_level` varchar(50) DEFAULT NULL,
  `max_students` int(5) DEFAULT 20,
  `current_students` int(5) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `teacher_name`, `academic_level`, `max_students`, `current_students`, `status`, `created_at`) VALUES
(1, 'فوج الإناث - إبتدائي (أ)', 'rania ra', 'إبتدائي', 25, 0, 'active', '2026-04-23 15:29:43'),
(2, 'فوج الإناث - إبتدائي (ب)', 'samia bou', 'إبتدائي', 25, 0, 'active', '2026-04-23 15:29:43'),
(3, 'فوج الذكور - إبتدائي (أ)', 'yacine yacine', 'إبتدائي', 25, 0, 'active', '2026-04-23 15:29:43'),
(4, 'فوج الذكور - إبتدائي (ب)', 'Ali ali', 'إبتدائي', 25, 0, 'active', '2026-04-23 15:29:43'),
(5, 'فوج الإناث - متوسط (أ)', 'rania ra', 'متوسط', 20, 0, 'active', '2026-04-26 09:37:31'),
(6, 'فوج الإناث - متوسط (ب)', 'samia bou', 'متوسط', 20, 0, 'active', '2026-04-26 09:37:31'),
(7, 'فوج ذكور - متوسط (أ)', 'yacine yacine', 'متوسط', 20, 0, 'active', '2026-04-26 09:37:31'),
(8, 'فوج ذكور - متوسط (ب)', 'Ali ali', 'متوسط', 20, 0, 'active', '2026-04-26 09:37:31'),
(9, 'فوج الإناث - ثانوي', 'rania ra', 'ثانوي', 20, 0, 'active', '2026-04-26 09:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `days` int(3) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `document_path` varchar(500) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `user_id`, `user_name`, `days`, `start_date`, `end_date`, `reason`, `document_path`, `status`, `admin_notes`, `created_at`, `updated_at`) VALUES
(5, 70, 'rania ra', 5, '2026-05-20', '2026-05-10', 'عطلة', NULL, 'approved', NULL, '2026-05-03 11:29:28', '2026-05-04 14:01:11'),
(6, 68, 'ikram bachare', 4, '2026-05-20', '2026-05-09', 'LEAVE', NULL, 'approved', NULL, '2026-05-03 11:34:07', '2026-05-03 11:34:29');

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE `parts` (
  `id` int(11) NOT NULL,
  `part_number` int(3) NOT NULL COMMENT 'رقم الحزب (1-60)',
  `start_surah` varchar(100) NOT NULL,
  `start_ayah` int(4) NOT NULL,
  `end_surah` varchar(100) NOT NULL,
  `end_ayah` int(4) NOT NULL,
  `total_ayahs` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`id`, `part_number`, `start_surah`, `start_ayah`, `end_surah`, `end_ayah`, `total_ayahs`) VALUES
(1, 1, 'الفاتحة', 1, 'البقرة', 73, 73),
(2, 2, 'البقرة', 74, 'البقرة', 141, 68),
(3, 3, 'البقرة', 142, 'البقرة', 202, 61),
(4, 4, 'البقرة', 203, 'البقرة', 252, 50),
(5, 5, 'البقرة', 253, 'البقرة', 286, 34);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `period_months` int(3) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `payment_date` date NOT NULL,
  `subscription_end_old` date DEFAULT NULL,
  `subscription_end_new` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `full_name`, `amount`, `period_months`, `payment_type`, `payment_date`, `subscription_end_old`, `subscription_end_new`, `created_at`) VALUES
(35, 73, 'Lina daira', 3500.00, 1, 'شهري', '2026-05-03', NULL, '2026-06-03', '2026-05-03 19:41:43'),
(36, 80, 'Mariem bou', 3500.00, 1, 'شهري', '2026-05-03', NULL, '2026-06-03', '2026-05-03 21:07:38'),
(37, 81, 'Manale zaiter', 3500.00, 1, 'شهري', '2026-05-03', NULL, '2026-06-03', '2026-05-03 21:08:28'),
(38, 82, 'Maissa maissa', 3500.00, 1, 'شهري', '2026-05-03', NULL, '2026-06-03', '2026-05-03 21:09:22'),
(39, 83, 'Amani mehaya', 3500.00, 1, 'شهري', '2026-05-03', NULL, '2026-06-03', '2026-05-03 21:10:08'),
(40, 84, 'Mariam ben', 3500.00, 1, 'شهري', '2026-05-03', NULL, '2026-06-03', '2026-05-03 21:10:43'),
(41, 85, 'Khouloud  bou', 3500.00, 1, 'شهري', '2026-05-03', NULL, '2026-06-03', '2026-05-03 21:11:23');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `capacity` int(5) DEFAULT 30,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `capacity`, `status`, `created_at`) VALUES
(1, 'قاعة 01', 30, 'active', '2026-04-23 15:29:43'),
(2, 'قاعة 02', 25, 'active', '2026-04-23 15:29:43'),
(3, 'قاعة 03', 25, 'active', '2026-04-23 15:29:43'),
(4, '01 قاعة', 25, 'active', '2026-04-26 09:37:31'),
(5, '02 قاعة', 30, 'active', '2026-04-26 09:37:31'),
(6, '03 قاعة', 20, 'active', '2026-04-26 09:37:31'),
(7, ' قاعة 04', 25, 'active', '2026-04-26 09:37:31'),
(8, '05 قاعة', 35, 'active', '2026-04-26 09:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `teacher_name` varchar(255) NOT NULL,
  `day` enum('السبت','الأحد','الإثنين','الثلاثاء','الأربعاء','الخميس','الجمعة') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `semester` enum('الأول','الثاني','الصيفي') DEFAULT 'الأول',
  `status` enum('active','cancelled') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `group_id`, `room_id`, `teacher_name`, `day`, `start_time`, `end_time`, `subject`, `semester`, `status`, `created_at`) VALUES
(3, 2, 3, 'samia bou', 'السبت', '08:00:00', '10:00:00', NULL, 'الأول', 'active', '2026-04-23 15:29:43'),
(16, 1, 1, 'rania ra', 'السبت', '08:00:00', '10:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(17, 4, 3, 'Ali ali', 'السبت', '10:00:00', '12:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(18, 8, 2, 'Ali ali', 'السبت', '14:00:00', '16:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(19, 3, 1, 'yacine yacine', 'الأحد', '08:00:00', '10:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(20, 7, 4, 'yacine yacine', 'الأحد', '10:00:00', '12:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(21, 5, 2, 'rania ra', 'الإثنين', '10:00:00', '12:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(22, 9, 5, 'rania ra', 'الإثنين', '14:00:00', '16:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(23, 1, 2, 'rania ra', 'الخميس', '10:00:00', '12:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(25, 2, 1, 'samia bou', 'الأربعاء', '10:00:00', '12:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `session_date` date NOT NULL,
  `session_number` int(2) NOT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `group_id`, `session_date`, `session_number`, `is_completed`, `created_at`) VALUES
(11, 1, '2026-04-01', 1, 0, '2026-04-24 20:09:15'),
(12, 1, '2026-04-02', 2, 0, '2026-04-24 20:09:15'),
(13, 1, '2026-04-03', 3, 0, '2026-04-24 20:09:15'),
(14, 1, '2026-04-04', 4, 0, '2026-04-24 20:09:15'),
(15, 1, '2026-04-05', 5, 0, '2026-04-24 20:09:15'),
(16, 1, '2026-04-06', 6, 0, '2026-04-24 20:09:15'),
(17, 1, '2026-04-07', 7, 0, '2026-04-24 20:09:15'),
(18, 1, '2026-04-08', 8, 0, '2026-04-24 20:09:15'),
(19, 1, '2026-04-09', 9, 0, '2026-04-24 20:09:15'),
(20, 1, '2026-04-24', 10, 0, '2026-04-24 20:09:15');

-- --------------------------------------------------------

--
-- Table structure for table `student_progress`
--

CREATE TABLE `student_progress` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `current_surah` varchar(100) DEFAULT 'الفاتحة',
  `current_ayah` int(5) DEFAULT 0,
  `memorized_parts` int(3) DEFAULT 0,
  `memorized_juz` int(3) DEFAULT 0,
  `total_score` int(5) DEFAULT 0,
  `rank_in_group` int(3) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_progress`
--

INSERT INTO `student_progress` (`id`, `student_id`, `current_surah`, `current_ayah`, `memorized_parts`, `memorized_juz`, `total_score`, `rank_in_group`, `updated_at`) VALUES
(7, 73, 'البقرة', 101, 1, 1, 51, 3, '2026-05-06 10:25:51'),
(8, 85, 'البقرة', 43, 3, 1, 55, 0, '2026-05-05 11:44:42'),
(9, 81, 'البقرة', 83, 3, 1, 51, 0, '2026-05-05 11:44:42'),
(10, 84, 'البقرة', 40, 3, 1, 52, 0, '2026-05-05 11:44:42'),
(11, 80, 'البقرة', 83, 3, 1, 51, 0, '2026-05-05 11:44:42'),
(12, 82, 'البقرة', 46, 2, 1, 32, 0, '2026-05-05 11:44:42');

-- --------------------------------------------------------

--
-- Table structure for table `surahs`
--

CREATE TABLE `surahs` (
  `id` int(11) NOT NULL,
  `surah_name` varchar(100) NOT NULL,
  `surah_name_ar` varchar(100) DEFAULT NULL,
  `surah_number` int(3) NOT NULL,
  `total_ayahs` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surahs`
--

INSERT INTO `surahs` (`id`, `surah_name`, `surah_name_ar`, `surah_number`, `total_ayahs`) VALUES
(1, 'Al-Fatiha', 'الفاتحة', 1, 7),
(2, 'Al-Baqarah', 'البقرة', 2, 286),
(3, 'Aal-E-Imran', 'آل عمران', 3, 200),
(4, 'An-Nisa', 'النساء', 4, 176),
(5, 'Al-Maida', 'المائدة', 5, 120);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `subscription` varchar(50) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('طالب','مدير','أستاذ','موظف') DEFAULT 'طالب',
  `status` varchar(50) DEFAULT NULL,
  `is_verified` tinyint(4) DEFAULT 0,
  `gender` enum('ذكر','أنثى') NOT NULL,
  `academic_level` enum('إبتدائي','متوسط','ثانوي') NOT NULL,
  `subscription_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `phone`, `email`, `birth_date`, `subscription`, `group_id`, `duration`, `password`, `role`, `status`, `is_verified`, `gender`, `academic_level`, `subscription_end`) VALUES
(1, 'randa debab', '0777641503', 'benchimk.norelhouda2004@gmail.com', '2026-04-06', NULL, NULL, 'شهر', '$2y$10$gyyEBybZ9iAkBwAEnJqFn.EJ9mQPVMahvZcvTebn8YIpi0lxpKVgi', 'مدير', 'active', 1, 'أنثى', '', NULL),
(68, 'ikram bachare', '0783456755', NULL, '1999-02-23', NULL, NULL, NULL, '$2y$10$Bh6Q6S48R9Fkf012.iNWRO8xjO4mrWn3BYF8dF2U2qnnPQ8t42waO', 'موظف', 'active', 1, 'ذكر', 'إبتدائي', NULL),
(70, 'rania ra', '0783230473', NULL, '0000-00-00', NULL, NULL, NULL, '$2y$10$UdJCxvOxn7H2j8EOsAsSFelydJCl2b8WK7LEreq96FZrE5qFkONI.', 'أستاذ', 'active', 1, 'ذكر', 'إبتدائي', NULL),
(73, 'Lina daira', '0555123430', NULL, '2017-12-18', 'شهر', 1, NULL, '$2y$10$Tj0lngnj4WFNe00Wuoada.WJEDU/0qkDmgHqQuIhI775JZGWjujMC', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-06-03'),
(74, 'yacine yacine', '0555123430', NULL, '2000-02-01', NULL, NULL, NULL, '$2y$10$AtmHaULTt.H9nOU1xVijpOykBmzr0BEqXKzsDhsMNQykvMxExYhve', 'أستاذ', 'active', 1, 'ذكر', 'إبتدائي', NULL),
(75, 'Ali ali', '0555123430', NULL, '1999-06-23', NULL, NULL, NULL, '$2y$10$s9dq7QEC8vTUGRiHSo8R3.6181uGWRJpijF9UAQSk.vgNxFqRWtAa', 'أستاذ', 'active', 1, 'ذكر', 'إبتدائي', NULL),
(76, 'Samia bou', '0783456755', NULL, '1997-08-24', NULL, NULL, NULL, '$2y$10$tMOqlwx5baLH0USBJySZ..jZJw.aLfx7rx9K6Fmrn5h6TxonKr2OG', 'أستاذ', 'active', 1, 'أنثى', 'إبتدائي', NULL),
(77, 'morad ben', '0783456755', NULL, '2009-03-16', 'شهر', NULL, NULL, '$2y$10$tNGgcmNBBwTXam6SztIKruJlPUmzgS1ODB5b.JDgIR2.uRr4PBI.6', 'طالب', 'pending', 0, 'ذكر', 'إبتدائي', NULL),
(78, 'Mohamed ben', '0783456755', NULL, '2012-10-11', 'شهر', NULL, NULL, '$2y$10$SPz6A8Dj2NpOR3oNIxwBxeX9HSKeDzJXBjYvtQMgdXe3Gz3K06Md.', 'طالب', 'pending', 0, 'ذكر', 'إبتدائي', NULL),
(79, 'Houda Benchikh', '0655123430', NULL, '2016-01-23', 'شهر', NULL, NULL, '$2y$10$vHEa/yYeGG9lMXBZc7b3BuLFotomtvmMwId79ScOyU1HzGu9xVYYK', 'طالب', 'pending', 0, 'ذكر', 'إبتدائي', NULL),
(80, 'Mariem bou', '0655123430', NULL, '2016-01-23', 'شهر', 1, '1 أشهر', '$2y$10$PzgoQvB.uWIAdBfwxjWRgunzZiZEohLJRbVjgrtpsAAJ5LQGQ30Wy', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-06-03'),
(81, 'Manale zaiter', '0783456755', NULL, '2016-02-12', 'شهر', 1, '1 أشهر', '$2y$10$AW.n5Rxso2f3DoNOalXnYudXokEKw6VwBhCMErdAYn4zR4MnwiBTW', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-06-03'),
(82, 'Maissa maissa', '0783230473', NULL, '2015-12-29', 'شهر', 1, '1 أشهر', '$2y$10$C9yxTBvTNi90JznNPulX9uoURu0NDoqu2fBJGXae2/PeeMAP6K6K6', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-06-03'),
(83, 'Amani mehaya', '0783456755', NULL, '2014-12-23', 'شهر', 1, '1 أشهر', '$2y$10$VftaPUCSFNj8zJsJq2GAJOC7Z4ylWO.HrKJFt0Sqi0brdMYstv3ha', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-06-03'),
(84, 'Mariam ben', '0655123430', NULL, '2016-01-23', 'شهر', 1, '1 أشهر', '$2y$10$MeSOZf5xGVTbuqPTIXTui.lgqyW/Wdh4U1owrWG2h1WSxh48H9VWe', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-06-03'),
(85, 'Khouloud  bou', '0655123430', NULL, '2017-12-24', 'شهر', 1, '1 أشهر', '$2y$10$UhsypCqc6yiTgLKwPCxoGOCrov40TwY1ewyOSF6Q8cNEFH4fb/Fjy', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-06-03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`student_id`,`date`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `daily_evaluation`
--
ALTER TABLE `daily_evaluation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_id` (`exam_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_name` (`group_name`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `parts`
--
ALTER TABLE `parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_session` (`group_id`,`session_date`);

--
-- Indexes for table `student_progress`
--
ALTER TABLE `student_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student` (`student_id`);

--
-- Indexes for table `surahs`
--
ALTER TABLE `surahs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `daily_evaluation`
--
ALTER TABLE `daily_evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `exam_results`
--
ALTER TABLE `exam_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `student_progress`
--
ALTER TABLE `student_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `surahs`
--
ALTER TABLE `surahs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_evaluation`
--
ALTER TABLE `daily_evaluation`
  ADD CONSTRAINT `daily_evaluation_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_evaluation_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_evaluation_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_results`
--
ALTER TABLE `exam_results`
  ADD CONSTRAINT `exam_results_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_results_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_progress`
--
ALTER TABLE `student_progress`
  ADD CONSTRAINT `student_progress_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
