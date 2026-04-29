-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 11:17 AM
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
(1, 49, 1, '2026-04-24', 'حاضر', NULL, '2026-04-24 17:35:17'),
(2, 56, 1, '2026-04-24', 'حاضر', NULL, '2026-04-24 17:35:18'),
(3, 60, 1, '2026-04-24', 'حاضر', NULL, '2026-04-24 17:35:18'),
(4, 50, 1, '2026-04-24', 'حاضر', NULL, '2026-04-24 17:35:18'),
(29, 49, 1, '2026-04-26', 'حاضر', NULL, '2026-04-26 19:50:20'),
(30, 56, 1, '2026-04-26', 'حاضر', NULL, '2026-04-26 19:50:20'),
(31, 60, 1, '2026-04-26', 'حاضر', NULL, '2026-04-26 19:50:20'),
(32, 50, 1, '2026-04-26', 'حاضر', NULL, '2026-04-26 19:50:20'),
(57, 49, 1, '2026-04-27', 'حاضر', NULL, '2026-04-27 08:56:35'),
(58, 56, 1, '2026-04-27', 'حاضر', NULL, '2026-04-27 08:56:35'),
(59, 60, 1, '2026-04-27', 'حاضر', NULL, '2026-04-27 08:56:35'),
(60, 50, 1, '2026-04-27', 'حاضر', NULL, '2026-04-27 08:56:35');

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
(1, 60, 1, 1, '2026-04-24', '19:04:45', 'سورة البقرة', 1, 10, 0, 15, 0, 0, 'حاضر', NULL, '2026-04-24 17:04:45'),
(2, 56, 1, 1, '2026-04-24', '19:15:25', 'سورة البقرة', 1, 7, 0, 18, 0, 0, 'حاضر', NULL, '2026-04-24 17:15:25'),
(14, 49, 1, 1, '2026-04-24', '21:03:05', 'سورة الفاتحة', 1, 1, 0, 17, 0, 0, 'حاضر', NULL, '2026-04-24 19:03:05'),
(17, 50, 1, 1, '2026-04-24', '21:03:05', 'سورة الفاتحة', 1, 1, 0, 16, 0, 0, 'حاضر', NULL, '2026-04-24 19:03:05'),
(22, 49, 1, 1, '2026-04-24', '21:41:35', 'سورة الفاتحة', 1, 1, 0, 15, 0, 0, 'حاضر', NULL, '2026-04-24 19:41:35'),
(23, 56, 1, 1, '2026-04-24', '21:41:35', 'سورة الفاتحة', 1, 1, 0, 16, 0, 0, 'حاضر', NULL, '2026-04-24 19:41:35'),
(24, 60, 1, 1, '2026-04-24', '21:41:35', 'سورة الفاتحة', 1, 1, 0, 15, 0, 0, 'حاضر', NULL, '2026-04-24 19:41:35'),
(25, 50, 1, 1, '2026-04-24', '21:41:35', 'سورة الفاتحة', 1, 1, 0, 19, 0, 0, 'حاضر', NULL, '2026-04-24 19:41:35'),
(26, 49, 1, 1, '2026-04-26', '21:50:20', 'سورة الفاتحة', 1, 7, 0, 20, 0, 0, 'حاضر', NULL, '2026-04-26 19:50:20'),
(27, 56, 1, 1, '2026-04-26', '21:50:20', 'سورة الفاتحة', 1, 3, 0, 19, 0, 0, 'حاضر', NULL, '2026-04-26 19:50:20'),
(28, 60, 1, 1, '2026-04-26', '21:50:20', 'سورة الفاتحة', 1, 3, 0, 19, 0, 0, 'حاضر', NULL, '2026-04-26 19:50:20'),
(29, 50, 1, 1, '2026-04-26', '21:50:20', 'سورة الفاتحة', 1, 7, 0, 18, 0, 0, 'حاضر', NULL, '2026-04-26 19:50:20'),
(30, 49, 1, 1, '2026-04-26', '22:19:17', 'سورة الفاتحة', 1, 7, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:19:17'),
(31, 49, 1, 1, '2026-04-26', '22:29:30', 'سورة الفاتحة', 1, 3, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:29:30'),
(32, 49, 1, 1, '2026-04-26', '22:31:26', 'سورة البقرة', 1, 10, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:31:26'),
(33, 56, 1, 1, '2026-04-26', '22:31:26', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:31:26'),
(34, 60, 1, 1, '2026-04-26', '22:31:26', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:31:26'),
(35, 50, 1, 1, '2026-04-26', '22:31:26', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:31:26'),
(36, 49, 1, 1, '2026-04-26', '22:43:45', 'سورة الفاتحة', 1, 6, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:43:45'),
(37, 56, 1, 1, '2026-04-26', '22:43:45', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:43:45'),
(38, 60, 1, 1, '2026-04-26', '22:43:45', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:43:45'),
(39, 50, 1, 1, '2026-04-26', '22:43:45', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:43:45'),
(40, 49, 1, 1, '2026-04-26', '22:44:13', 'سورة البقرة', 1, 32, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:44:13'),
(41, 56, 1, 1, '2026-04-26', '22:44:13', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:44:13'),
(42, 60, 1, 1, '2026-04-26', '22:44:13', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:44:13'),
(43, 50, 1, 1, '2026-04-26', '22:44:13', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 20:44:13'),
(44, 49, 1, 1, '2026-04-26', '23:06:07', 'سورة البقرة', 1, 10, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 21:06:07'),
(45, 56, 1, 1, '2026-04-26', '23:06:07', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 21:06:07'),
(46, 60, 1, 1, '2026-04-26', '23:06:07', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 21:06:07'),
(47, 50, 1, 1, '2026-04-26', '23:06:07', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-26 21:06:07'),
(48, 49, 1, 1, '2026-04-27', '10:56:35', 'سورة البقرة', 1, 101, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-27 08:56:35'),
(49, 56, 1, 1, '2026-04-27', '10:56:35', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-27 08:56:35'),
(50, 60, 1, 1, '2026-04-27', '10:56:35', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-27 08:56:35'),
(51, 50, 1, 1, '2026-04-27', '10:56:35', 'سورة الفاتحة', 1, 1, 0, 0, 0, 0, 'حاضر', NULL, '2026-04-27 08:56:35');

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
(5, 'اختبار أسبوعي - 2026-04-26', 1, 1, '2026-04-26', '', 8, 8, 4, 20, '2026-04-26 09:08:30');

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
(9, 3, 49, 7, 6, 4, 17.00, 0, NULL, '2026-04-24 17:00:15'),
(10, 3, 56, 8, 8, 3, 19.00, 0, NULL, '2026-04-24 17:00:15'),
(11, 3, 60, 8, 7, 4, 19.00, 0, NULL, '2026-04-24 17:00:15'),
(12, 3, 50, 7, 8, 4, 19.00, 0, NULL, '2026-04-24 17:00:15'),
(13, 4, 56, 7, 7, 4, 18.00, 0, NULL, '2026-04-24 18:50:06'),
(14, 4, 60, 8, 8, 3, 19.00, 0, NULL, '2026-04-24 18:50:06'),
(15, 4, 50, 6, 7, 3, 16.00, 0, NULL, '2026-04-24 18:50:06'),
(16, 5, 49, 6, 6, 3, 15.00, 0, NULL, '2026-04-26 09:08:30'),
(17, 5, 56, 7, 7, 4, 18.00, 0, NULL, '2026-04-26 09:08:30'),
(18, 5, 60, 5, 6, 3, 14.00, 0, NULL, '2026-04-26 09:08:30'),
(19, 5, 50, 8, 7, 4, 19.00, 0, NULL, '2026-04-26 09:08:30');

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
(1, 'فوج الإناث - إبتدائي (أ)', 'أ. فاطمة الزهراء', 'إبتدائي', 25, 0, 'active', '2026-04-23 15:29:43'),
(2, 'فوج الإناث - إبتدائي (ب)', 'أ. سارة أحمد', 'إبتدائي', 25, 0, 'active', '2026-04-23 15:29:43'),
(3, 'فوج الذكور - إبتدائي (أ)', 'أ. ياسين بلقاسم', 'إبتدائي', 25, 0, 'active', '2026-04-23 15:29:43'),
(4, 'فوج الذكور - إبتدائي (ب)', 'أ. محمد رضا', 'إبتدائي', 25, 0, 'active', '2026-04-23 15:29:43'),
(5, 'فوج النور', 'أ. فاطمة الزهراء', 'إبتدائي', 20, 0, 'active', '2026-04-26 09:37:31'),
(6, 'فوج الفردوس', 'أ. فاطمة الزهراء', 'متوسط', 20, 0, 'active', '2026-04-26 09:37:31'),
(7, 'فوج اليقين', 'أ. فاطمة الزهراء', 'ثانوي', 20, 0, 'active', '2026-04-26 09:37:31'),
(8, 'فوج الإخلاص', 'أ. فاطمة الزهراء', 'إبتدائي', 20, 0, 'active', '2026-04-26 09:37:31'),
(9, 'فوج الرحمة', 'أ. محمد رضا', 'متوسط', 20, 0, 'active', '2026-04-26 09:37:31');

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
(1, 64, 'أ. فاطمة الزهراء', 10, '2026-04-27', '2026-05-06', 'عطلة مرضية', '../uploads/leaves/leave_64_1777206056.png', 'pending', NULL, '2026-04-26 12:20:56', '2026-04-26 12:20:56'),
(2, 65, 'ريما', 5, '2026-04-26', '2026-04-30', 'عطلة مرضية', '../uploads/leaves/leave_65_1777206754.png', 'pending', NULL, '2026-04-26 12:32:34', '2026-04-26 12:32:34');

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
(1, 57, 'morad', 3500.00, 1, 'شهري', '2026-04-23', NULL, '2026-05-23', '2026-04-23 11:43:10'),
(2, 58, 'Meriam bou', 9000.00, 3, 'فصلي', '2026-04-23', NULL, '2026-07-23', '2026-04-23 11:48:35'),
(3, 53, 'morad', 3500.00, 1, 'شهري', '2026-04-23', '2026-07-23', '2026-08-23', '2026-04-23 12:00:17'),
(4, 52, 'Houda', 9000.00, 3, 'فصلي', '2026-04-23', '2026-05-23', '2026-08-23', '2026-04-23 12:08:36'),
(5, 51, 'Mohammed', 3500.00, 1, 'شهري', '2026-04-23', '2026-05-23', '2026-06-23', '2026-04-23 12:14:50'),
(6, 52, 'Houda', 3500.00, 1, 'شهري', '2026-04-23', '2026-08-23', '2026-09-23', '2026-04-23 12:24:54'),
(7, 57, 'morad', 9000.00, 3, 'فصلي', '2026-04-23', '2026-05-23', '2026-08-23', '2026-04-23 12:27:55'),
(8, 58, 'Meriam bou', 3500.00, 1, 'شهري', '2026-04-23', '2026-07-23', '2026-08-23', '2026-04-23 14:24:21'),
(9, 59, 'Aicha benchikh', 3500.00, 1, 'شهري', '2026-04-23', NULL, '2026-05-23', '2026-04-23 15:01:15'),
(10, 60, 'selsabil', 3500.00, 1, 'شهري', '2026-04-23', NULL, '2026-05-23', '2026-04-23 15:06:31'),
(11, 59, 'Aicha benchikh', 9000.00, 3, 'فصلي', '2026-04-23', '2026-05-23', '2026-08-23', '2026-04-23 15:07:33'),
(12, 60, 'selsabil', 3500.00, 1, 'شهري', '2026-04-23', '2026-05-23', '2026-06-23', '2026-04-23 15:08:05'),
(13, 52, 'Houda', 3500.00, 1, 'شهري', '2026-04-24', '2026-09-23', '2026-10-23', '2026-04-24 12:08:02'),
(14, 58, 'Meriam bou', 3500.00, 1, 'شهري', '2026-04-24', '2026-08-23', '2026-09-23', '2026-04-24 13:32:56'),
(15, 62, 'Amine', 3500.00, 1, 'شهري', '2026-04-24', NULL, '2026-05-24', '2026-04-24 13:35:27'),
(16, 46, 'Rima', 3500.00, 1, 'شهري', '2026-04-24', NULL, '2026-05-24', '2026-04-24 14:12:42'),
(17, 52, 'Houda', 3500.00, 1, 'شهري', '2026-04-24', '2026-10-23', '2026-11-23', '2026-04-24 14:42:43'),
(18, 45, 'nour', 3500.00, 1, 'شهري', '2026-04-24', NULL, '2026-05-24', '2026-04-24 14:43:13'),
(19, 6, 'randa debab', 3500.00, 1, 'شهري', '2026-04-24', NULL, '2026-05-24', '2026-04-24 15:20:11'),
(20, 58, 'Meriam bou', 3500.00, 1, 'شهري', '2026-04-24', '2026-09-23', '2026-10-23', '2026-04-24 15:20:47'),
(21, 58, 'Meriam bou', 3500.00, 1, 'شهري', '2026-04-26', '2026-10-23', '2026-11-23', '2026-04-26 08:47:42'),
(22, 43, 'nour', 3500.00, 1, 'شهري', '2026-04-26', NULL, '2026-05-26', '2026-04-26 08:48:03'),
(23, 63, 'نورالهدى', 3500.00, 1, 'شهري', '2026-04-26', NULL, '2026-05-26', '2026-04-26 08:49:05'),
(24, 63, 'نورالهدى', 30000.00, 12, 'سنوي', '2026-04-26', '2026-05-26', '2027-05-26', '2026-04-26 09:57:10'),
(25, 62, 'Amine', 3500.00, 1, 'شهري', '2026-04-26', '2026-05-24', '2026-06-24', '2026-04-26 09:59:13'),
(26, 63, 'نورالهدى', 3500.00, 1, 'شهري', '2026-04-26', '2027-05-26', '2027-06-26', '2026-04-26 19:46:30'),
(27, 18, 'saraa', 3500.00, 1, 'شهري', '2026-04-26', NULL, '2026-05-26', '2026-04-26 19:46:53'),
(28, 11, 'randoch', 3500.00, 1, 'شهري', '2026-04-26', NULL, '2026-05-26', '2026-04-26 19:47:13');

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
(3, 2, 3, 'أ. سارة أحمد', 'الأحد', '08:00:00', '10:00:00', NULL, 'الأول', 'active', '2026-04-23 15:29:43'),
(16, 1, 1, 'أ. فاطمة الزهراء', 'السبت', '08:00:00', '10:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(17, 3, 3, 'أ. فاطمة الزهراء', 'السبت', '14:00:00', '16:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(18, 2, 2, 'أ. فاطمة الزهراء', 'الأحد', '10:00:00', '12:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(19, 1, 1, 'أ. فاطمة الزهراء', 'الإثنين', '08:00:00', '10:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(20, 2, 4, 'أ. فاطمة الزهراء', 'الثلاثاء', '14:00:00', '16:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(21, 3, 2, 'أ. فاطمة الزهراء', 'الأربعاء', '10:00:00', '12:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(22, 1, 5, 'أ. فاطمة الزهراء', 'الخميس', '08:00:00', '10:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(23, 1, 2, 'أ. محمد رضا', 'السبت', '10:00:00', '12:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12'),
(25, 3, 1, 'أ. ياسين بلقاسم', 'الإثنين', '10:00:00', '16:00:00', NULL, 'الأول', 'active', '2026-04-26 09:47:12');

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
(1, 49, 'سورة البقرة', 101, 5, 3, 52, 1, '2026-04-27 09:02:05'),
(2, 56, 'سورة الفاتحة', 1, 0, 0, 0, 0, '2026-04-27 08:56:35'),
(3, 60, 'سورة الفاتحة', 1, 0, 0, 0, 0, '2026-04-27 08:56:35'),
(4, 50, 'سورة الفاتحة', 1, 0, 0, 0, 0, '2026-04-27 08:56:35');

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
(1, 'randa debab', '0777641503', NULL, '2026-04-06', NULL, NULL, 'شهر', '$2y$10$IBLSzo1Dc.g9bQZi7NMGOeRjKFI5lQjGBnyKPJX1q0B366SwO8ieG', 'مدير', 'active', 0, 'أنثى', '', NULL),
(3, 'd deva', '0777641500', NULL, '2026-04-08', NULL, NULL, 'شهر', '$2y$10$6B0x/Qhff4MC.srGZ6AB4u6EHSyYgeWw2YRcsuceEaBH5rvAKs3h6', 'طالب', 'active', 1, 'أنثى', '', '2024-12-30'),
(4, 'randa debab', '0777641503', NULL, '2026-04-30', NULL, NULL, NULL, '$2y$10$MxWfeHDYLIi9P5SRO1EPB.OaKLE5F4ng5hsSS7XOMePqg.NV/pV36', 'طالب', 'suspended', 1, 'أنثى', '', '2024-12-30'),
(5, 'randa debab', '0777641500', NULL, '2026-04-08', NULL, NULL, NULL, '$2y$10$oxpXKHrOPUtV/F.qq8HZye/GG48YhFtInfgvMuX6Uh0Url0HLLS5S', 'طالب', 'pending', 0, 'أنثى', '', '2023-01-01'),
(6, 'randa debab', '0777641503', NULL, '2026-04-30', NULL, NULL, NULL, '$2y$10$4Ad81JbyVtj9MXRz4uT9/.L120tUQ0/4/k.2Uj1aEevUecOE0BrwG', 'طالب', 'active', 1, 'أنثى', '', '2026-05-24'),
(7, 'randa debab', '0777641503', NULL, '2026-04-30', NULL, NULL, NULL, '$2y$10$AZ0fJs5mgPSCpzozPt.VwON2DkVt/zayHldl9nNxQreJ3ouHl69bS', 'طالب', 'pending', 0, 'أنثى', '', NULL),
(8, 'randa debab', '0777641503', NULL, '2026-04-29', NULL, NULL, NULL, '$2y$10$ZTZL8zbbelahPd.a3TbhqegIAv8RWvNXDaDueOM6AJf20JUR29nIm', 'طالب', 'pending', 0, 'أنثى', '', NULL),
(9, 'nor', '0777641503', NULL, '2026-04-29', NULL, NULL, NULL, '$2y$10$C28zfzOZ6Zlr23epAagoEObLrtFKCWM0tBqsAssndslPckcM8541e', 'طالب', 'pending', 0, 'أنثى', '', NULL),
(10, 'randoch', '0777641503', NULL, '2026-04-08', NULL, NULL, NULL, '$2y$10$vT/UVKHqWiErnYAR9f7Kq.c/5keX8LlgUwMa8YXtSEEmnvXE14VGi', 'طالب', 'pending', 0, 'أنثى', '', NULL),
(11, 'randoch', '0777641503', NULL, '2026-04-08', 'شهر', NULL, NULL, '$2y$10$r6r1dhpeuFg2hzoBvhAwS.ZdiVXtSCLjkc66QbcsPB5rxhGaA1iJa', 'طالب', 'active', 1, 'أنثى', '', '2026-05-26'),
(12, 'randoch', '0777641503', NULL, '2026-04-08', 'شهر', NULL, NULL, '$2y$10$EoqAH/DBvJMrJfR34ChYuO97URd5YQzqiY2I5ucXXqjdZAgaZ2fGG', 'طالب', 'pending', 0, 'أنثى', '', NULL),
(13, 'randoch', '0777641503', NULL, '2026-04-08', 'شهر', NULL, NULL, '$2y$10$bF2tQ8CtP.cp7ymEXgJQ/uTvgvSkrKwlotBxb99hCS6buygq64SHm', 'طالب', 'pending', 0, 'أنثى', '', NULL),
(15, 'ma', '0770871617', NULL, '2026-04-08', 'شهر', NULL, NULL, '$2y$10$lS8nFdVZ5XYQvRzTzmnEf.B449btj65O4MTBU5s/HnBnz/WSzw0Bm', 'طالب', 'pending', 0, 'أنثى', '', NULL),
(17, 'asma', '0888888889', NULL, '2026-04-08', 'شهر', NULL, NULL, '$2y$10$4zh68DyyQ5y1Qtl.hY/YDeCPX2vfi1mGLLg4XNbjtG9joboNmjuea', 'طالب', 'active', 0, 'أنثى', '', NULL),
(18, 'saraa', '0777641503', NULL, '2026-04-09', 'شهر', NULL, NULL, '$2y$10$RYWbxybiA6Nk3NgIA4Ex7ece4GfD15HU2oKnSGYcakZF.BBQp/sv2', 'طالب', 'active', 1, 'أنثى', '', '2026-05-26'),
(43, 'nour', '0783230473', NULL, '2009-01-23', NULL, NULL, NULL, '$2y$10$SyzyCLPHv/PlDzZA6Uptre5O9CR6lR/K85KQ8VrJ3nOmn5q/axt4y', 'طالب', 'active', 1, 'أنثى', 'ثانوي', '2026-05-26'),
(45, 'nour', '0783230473', NULL, '2009-01-23', NULL, NULL, '1 months', '$2y$10$SFQsm76AyW1GUAHfygkrSO6uvlAywVuOIbQYODe7cqXK5QYk/zwzW', 'طالب', 'active', 1, 'أنثى', 'ثانوي', '2026-05-24'),
(46, 'Rima', '0783456755', NULL, '2010-02-22', NULL, NULL, '3 months', '$2y$10$n6MSc5V8dKIZWWOD88.ZEuH53PqCg8BcGDvOFmPFThIzr95NWf9p.', 'طالب', 'active', 1, 'أنثى', 'متوسط', '2026-05-24'),
(47, 'AYA', '0783230473', NULL, '2015-05-25', NULL, NULL, '3 months', '$2y$10$b10F/J93V93ATa2L4HMb2uPnc29RKC0RgtOlCTZAy5LNdULmoycmC', 'طالب', 'active', 1, 'أنثى', 'متوسط', '2015-08-25'),
(49, 'Amira', '0783230473', NULL, '2017-06-26', NULL, 1, '1 months', '$2y$10$3jg5zWhlxrP6oYu0iTm/yuZLdKByp/TmYQ434wvYpAQFCIyHjgXFG', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-05-23'),
(50, 'Somia', '0783230473', NULL, '2017-07-24', NULL, 1, '1 months', '$2y$10$5ebJFWFgUSFcu1gFaVfZy.w5yNFkqPLyvHARExqWuUpKIi3.AZDB.', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-05-23'),
(51, 'Mohammed', '0783230473', NULL, '2019-11-27', NULL, 4, '1 months', '$2y$10$8nKtfuqfjo1KHhhi3k.M1u7okTSreKg5TQjruCLsuKKYGNUixk2ti', 'طالب', 'active', 1, 'ذكر', 'إبتدائي', '2026-06-23'),
(52, 'Houda', '0783456755', NULL, '2020-02-11', NULL, 2, '1 months', '$2y$10$mWLqe0wwZ8JHjxloioLYSefploVHeky4brFwJV3V6x4oV6oqp10lW', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-11-23'),
(53, 'morad', '0783230473', NULL, '2016-03-06', NULL, 3, '3 months', '$2y$10$IqpNjJt5L3pjTcOIF48dhuAodmeF2Fo2oRE/ZFWn/FiO5CFm/bADy', 'طالب', 'active', 1, 'ذكر', 'متوسط', '2026-08-23'),
(54, 'morad', '0783456755', NULL, '2016-02-22', NULL, 3, '1 months', '$2y$10$WgSn.QRzDFefr2/teEaT9.6Ud1AxNkOebHrDWID1SjPFMxrwPXZFq', 'طالب', 'active', 1, 'ذكر', 'متوسط', '2026-05-23'),
(55, 'ALI', '0783230473', NULL, '2018-03-04', NULL, 3, '1 months', '$2y$10$xn8WpJZPH4AnzuxKMZor/OtqlT/k/wo4sAhz0QJ4tKmVJvPEV7m8W', 'طالب', 'active', 1, 'ذكر', 'إبتدائي', '2026-05-23'),
(56, 'ranya', '0783230473', NULL, '2019-12-23', NULL, 1, '1 months', '$2y$10$j42/7MR7fLyLg204M0sL4evHxX0Di.NGe7B5TD2nO2v5ubdDDKJF.', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-05-23'),
(57, 'morad', '0783230473', NULL, '2003-09-08', NULL, NULL, '1 أشهر', '$2y$10$CDzH7J5sG3UBgdsuCF2fPu1C40FPKkbgwPbfMnVMf8nT.cptKEVzi', 'طالب', 'active', 1, 'ذكر', 'متوسط', '2026-08-23'),
(58, 'Meriam bou', '0783230473', NULL, '2012-09-30', NULL, NULL, '3 أشهر', '$2y$10$Jv2/mF1ZKQyEBc0FYiqblOCNH7nBL2gqT19ybCHuVR9Kzl5pKfBey', 'طالب', 'active', 1, 'أنثى', 'متوسط', '2026-11-23'),
(59, 'Aicha benchikh', '0783230473', NULL, '2009-11-06', NULL, NULL, '1 أشهر', '$2y$10$k7dd92/ZUmfV4mJAYCHl9OoJQmSy20PI2ZFTJeP23Mh5.dxrDWyfu', 'طالب', 'active', 1, 'أنثى', 'ثانوي', '2026-08-23'),
(60, 'selsabil', '0783456755', NULL, '2022-02-22', NULL, 1, '1 أشهر', '$2y$10$c4n2ub1aWD2/PeIoMATR6eVheJvvZQ6DJSw5tfR483e3Eonw8rqK6', 'طالب', 'active', 1, 'أنثى', 'إبتدائي', '2026-06-23'),
(61, 'nourelhouda', '0555123456', NULL, '1999-06-25', NULL, NULL, NULL, 'e10adc3949ba59abbe56e057f20f883e', 'موظف', 'active', 0, 'أنثى', '', NULL),
(62, 'Amine', '0783456755', NULL, '2009-03-26', NULL, NULL, '1 أشهر', '$2y$10$2HPQmgLtZBsR1Tgf1/szguhkFk66HXOzo78rnwviRwhZrT9/2rnju', 'طالب', 'active', 1, 'ذكر', 'ثانوي', '2026-06-24'),
(63, 'نورالهدى', '0783456755', NULL, '2013-12-31', NULL, NULL, '1 أشهر', '$2y$10$.wbDVs4tk44GoS.vhO58j.W6bDN2YRQPP8tuA7jAu/QZZtLX00b6.', 'طالب', 'active', 1, 'أنثى', 'متوسط', '2027-06-26'),
(64, 'أ. فاطمة الزهراء', '0555123430', 'fatima.zahra@example.com', '1980-01-01', NULL, NULL, NULL, '$2y$10$YOUR_GENERATED_HASH_HERE', 'أستاذ', 'active', 0, 'أنثى', 'إبتدائي', NULL),
(65, 'ريما ', '0555123457', 'reception@example.com', '1990-01-01', NULL, NULL, NULL, '2d73067c9d5e8194bb0d5c53bfdc2c5b', 'موظف', 'active', 0, 'ذكر', 'إبتدائي', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `daily_evaluation`
--
ALTER TABLE `daily_evaluation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `exam_results`
--
ALTER TABLE `exam_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `parts`
--
ALTER TABLE `parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `surahs`
--
ALTER TABLE `surahs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

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
