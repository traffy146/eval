-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2026 at 04:11 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evaluation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_list`
--

CREATE TABLE `academic_list` (
  `id` int(30) NOT NULL,
  `year` text NOT NULL,
  `semester` int(30) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0=Pending,1=Start,2=Closed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_list`
--

INSERT INTO `academic_list` (`id`, `year`, `semester`, `is_default`, `status`) VALUES
(5, '2025-2026', 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_type` enum('admin','faculty','student') NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_type`, `user_id`, `username`, `action`, `description`, `timestamp`) VALUES
(1, 'admin', 1, 'admin', 'Login', 'Admin Administrator  logged in', '2026-01-28 18:49:42'),
(2, 'faculty', 38, 'test123', 'Login', 'Faculty test 123 logged in', '2026-01-28 18:56:30'),
(3, 'admin', 1, 'admin', 'Login', 'Admin Administrator  logged in', '2026-01-28 18:58:23'),
(4, 'admin', 1, 'admin', 'Login', 'Admin Administrator  logged in', '2026-01-28 19:07:55'),
(5, 'admin', 5, 'test123', 'Password Changed', 'Admin \'test123\' changed their password', '2026-01-28 19:08:33'),
(6, 'faculty', 38, 'test123', 'Login', 'Faculty test 123 logged in', '2026-01-28 19:11:15'),
(7, 'faculty', 38, 'test123', 'Login', 'Faculty test 123 logged in', '2026-01-29 21:57:53'),
(8, 'admin', 1, 'admin', 'Login', 'Admin Administrator  logged in', '2026-01-29 21:58:45'),
(9, 'student', 20, 'Aizel123', 'Account Updated', 'Student account \'Aizel Dela Cruz\' was updated', '2026-01-29 21:59:54'),
(10, 'student', 90, 'asd123', 'Account Created', 'Student account \'asd 123\' (asd123) was created', '2026-01-29 22:23:09'),
(11, 'student', 90, 'asd123', 'Login', 'Student asd  123 logged in', '2026-01-29 22:23:18'),
(12, 'admin', 1, 'admin', 'Login', 'Admin Administrator   logged in', '2026-01-29 22:24:12'),
(13, 'faculty', 38, 'test123', 'Login', 'Faculty test  123 logged in', '2026-01-29 22:27:30'),
(14, 'student', 90, 'asd123', 'Login', 'Student asd  123 logged in', '2026-01-29 22:27:46'),
(15, 'admin', 1, 'admin', 'Login', 'Admin Administrator   logged in', '2026-01-29 22:28:06'),
(16, 'admin', 1, 'admin', 'Class Created', 'Class \'BSIT 2 - B\' was created', '2026-01-29 22:29:23'),
(17, 'faculty', 38, 'test123', 'Login', 'Faculty test  123 logged in', '2026-01-29 22:43:19'),
(18, 'admin', 1, 'admin', 'Login', 'Admin Administrator   logged in', '2026-01-29 22:43:35'),
(19, 'student', 90, 'asd123', 'Login', 'Student asd  123 logged in', '2026-01-29 22:44:38'),
(20, 'admin', 1, 'admin', 'Login', 'Admin Administrator   logged in', '2026-01-29 22:45:27'),
(21, 'student', 90, 'asd123', 'Account Updated', 'Student account \'asd 123\' was updated', '2026-01-29 22:46:17'),
(22, 'student', 90, 'asd123', 'Login', 'Student asd  123 logged in', '2026-01-29 22:46:30'),
(23, 'faculty', 38, 'test123', 'Login', 'Faculty test  123 logged in', '2026-01-29 22:46:50'),
(24, 'admin', 1, 'admin', 'Login', 'Admin Administrator   logged in', '2026-01-29 22:47:04'),
(25, 'admin', 1, 'admin', 'Class Created', 'Class \'BSIT 3 - C\' was created', '2026-01-29 22:48:55'),
(26, 'admin', 1, 'admin', 'Bulk Student Update', '1 student(s) updated to BSIT 3 - C', '2026-01-29 23:09:29');

-- --------------------------------------------------------

--
-- Table structure for table `additional_feedback`
--

CREATE TABLE `additional_feedback` (
  `id` int(11) NOT NULL,
  `rating` enum('Good','Neutral','Bad','') DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `additional_feedback`
--

INSERT INTO `additional_feedback` (`id`, `rating`, `comment`) VALUES
(4, 'Neutral', 'sdf'),
(5, 'Good', ''),
(6, 'Good', ''),
(7, 'Good', ''),
(8, 'Neutral', ''),
(9, 'Good', ''),
(10, 'Good', '');

-- --------------------------------------------------------

--
-- Table structure for table `class_list`
--

CREATE TABLE `class_list` (
  `id` int(30) NOT NULL,
  `curriculum` text NOT NULL,
  `level` text NOT NULL,
  `section` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_list`
--

INSERT INTO `class_list` (`id`, `curriculum`, `level`, `section`) VALUES
(1, 'BSA', '1', 'A'),
(4, 'BSAIS', '1', 'A'),
(5, 'BSIT', '2', 'B'),
(6, 'BSIT', '3', 'C');

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `id` int(30) NOT NULL,
  `criteria` text NOT NULL,
  `order_by` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria_list`
--

INSERT INTO `criteria_list` (`id`, `criteria`, `order_by`) VALUES
(1, '1. MASTERY AND ATTAINMENT OF OBJECTIVES', 0),
(2, '2. TEACHING STRATEGIES EMPLOYED', 1),
(4, '3. EVALUATION AND ASSESSMENT', 2),
(5, '4. PERSONALITY', 3),
(6, '5. COMMUNICATION SKILLS AND VALUES INTEGRATION', 4),
(7, '6. CLASSROOM MANAGEMENT AND DISCIPLINE', 5);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers`
--

CREATE TABLE `evaluation_answers` (
  `evaluation_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_answers`
--

INSERT INTO `evaluation_answers` (`evaluation_id`, `question_id`, `rate`) VALUES
(1, 1, 5),
(1, 6, 4),
(1, 3, 5),
(2, 1, 5),
(2, 6, 5),
(2, 3, 4),
(3, 1, 5),
(3, 6, 5),
(3, 3, 4),
(4, 8, 5),
(4, 9, 5),
(5, 8, 5),
(5, 9, 5),
(6, 8, 5),
(6, 9, 5),
(1, 1, 5),
(1, 6, 4),
(1, 3, 5),
(2, 1, 5),
(2, 6, 5),
(2, 3, 4),
(3, 1, 5),
(3, 6, 5),
(3, 3, 4),
(4, 8, 5),
(4, 9, 5),
(5, 8, 5),
(5, 9, 5),
(6, 8, 5),
(6, 9, 5),
(7, 10, 5),
(7, 11, 5),
(7, 12, 5),
(7, 13, 5),
(7, 14, 5),
(7, 15, 5),
(7, 16, 5),
(7, 17, 5),
(7, 18, 5),
(7, 19, 5),
(7, 20, 5),
(7, 21, 5),
(7, 22, 5),
(7, 23, 5),
(7, 24, 5),
(7, 25, 5),
(7, 26, 5),
(7, 27, 5),
(7, 28, 5),
(8, 10, 5),
(8, 11, 5),
(8, 12, 5),
(8, 13, 5),
(8, 14, 5),
(8, 15, 5),
(8, 16, 5),
(8, 17, 5),
(8, 18, 5),
(8, 19, 5),
(8, 20, 5),
(8, 21, 5),
(8, 22, 5),
(8, 23, 5),
(8, 24, 5),
(8, 25, 5),
(8, 26, 5),
(8, 27, 5),
(8, 28, 5),
(9, 10, 5),
(9, 11, 5),
(9, 12, 5),
(9, 13, 5),
(9, 14, 5),
(9, 15, 5),
(9, 16, 5),
(9, 17, 5),
(9, 18, 5),
(9, 19, 5),
(9, 20, 5),
(9, 21, 5),
(9, 22, 5),
(9, 23, 5),
(9, 24, 5),
(9, 25, 5),
(9, 26, 5),
(9, 27, 5),
(9, 28, 5),
(10, 10, 1),
(10, 11, 5),
(10, 12, 5),
(10, 13, 5),
(10, 14, 5),
(10, 15, 5),
(10, 16, 3),
(10, 17, 5),
(10, 18, 3),
(10, 19, 5),
(10, 20, 5),
(10, 21, 2),
(10, 22, 5),
(10, 23, 5),
(10, 24, 1),
(10, 25, 5),
(10, 26, 5),
(10, 27, 5),
(10, 28, 5),
(11, 10, 1),
(11, 11, 1),
(11, 12, 1),
(11, 13, 1),
(11, 14, 5),
(11, 15, 5),
(11, 16, 5),
(11, 17, 1),
(11, 18, 1),
(11, 19, 1),
(11, 20, 5),
(11, 21, 5),
(11, 22, 5),
(11, 23, 1),
(11, 24, 1),
(11, 25, 1),
(11, 26, 5),
(11, 27, 5),
(11, 28, 5),
(12, 10, 4),
(12, 11, 4),
(12, 12, 4),
(12, 13, 4),
(12, 14, 3),
(12, 15, 3),
(12, 16, 3),
(12, 17, 2),
(12, 18, 2),
(12, 19, 2),
(12, 20, 5),
(12, 21, 5),
(12, 22, 5),
(12, 23, 2),
(12, 24, 1),
(12, 25, 3),
(12, 26, 1),
(12, 27, 2),
(12, 28, 5),
(13, 10, 5),
(13, 11, 5),
(13, 12, 5),
(13, 13, 5),
(13, 14, 5),
(13, 15, 5),
(13, 16, 5),
(13, 17, 5),
(13, 18, 5),
(13, 19, 5),
(13, 20, 5),
(13, 21, 5),
(13, 22, 5),
(13, 23, 5),
(13, 24, 5),
(13, 25, 5),
(13, 26, 5),
(13, 27, 5),
(13, 28, 5);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list`
--

CREATE TABLE `evaluation_list` (
  `evaluation_id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `restriction_id` int(30) NOT NULL,
  `feedback_id` int(11) DEFAULT NULL,
  `date_taken` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_list`
--

INSERT INTO `evaluation_list` (`evaluation_id`, `academic_id`, `class_id`, `student_id`, `subject_id`, `faculty_id`, `restriction_id`, `feedback_id`, `date_taken`) VALUES
(1, 3, 1, 1, 1, 1, 8, 0, '2020-12-15 16:26:51'),
(2, 3, 2, 2, 2, 1, 9, 0, '2020-12-15 16:33:37'),
(3, 3, 1, 3, 1, 1, 8, 0, '2020-12-15 20:18:49'),
(4, 5, 1, 4, 2, 1, 11, 1, '2025-08-07 19:35:25'),
(5, 5, 1, 1, 2, 1, 11, 2, '2025-08-07 19:37:41'),
(6, 5, 1, 3, 2, 1, 11, 3, '2025-08-07 19:40:14'),
(7, 5, 1, 1, 2, 19, 12, 4, '2025-09-15 05:20:40'),
(8, 5, 1, 1, 3, 32, 13, 5, '2025-09-15 05:37:57'),
(9, 5, 1, 1, 8, 20, 24, 6, '2025-09-15 05:39:02'),
(10, 5, 1, 87, 2, 19, 12, 7, '2025-11-14 19:24:37'),
(11, 5, 1, 87, 1, 22, 14, 8, '2025-11-14 19:26:44'),
(12, 5, 1, 89, 1, 22, 14, 9, '2025-11-14 19:34:03'),
(13, 5, 5, 90, 1, 38, 29, 10, '2026-01-29 22:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_list`
--

CREATE TABLE `faculty_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL DEFAULT '',
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_list`
--

INSERT INTO `faculty_list` (`id`, `school_id`, `username`, `firstname`, `middlename`, `lastname`, `email`, `password`, `avatar`, `date_created`) VALUES
(2, '001', '', 'Remy', '', 'Andrada', 'r.andrada@faculty.com', '286c452b448b85985bc331d3b05f153d', 'no-image-available.png', '2025-09-13 12:28:23'),
(3, '002', '', 'Rod Bryan', '', 'Bacsa', 'rb.bacsa@faculty.com', 'a8c481083830c46390e8996578393b82', 'no-image-available.png', '2025-09-13 12:30:36'),
(4, '003', '', 'Grace', '', 'Cajucom', 'g.cajucom@faculty.com', '7f50e31e109a3ba911caadcdd5ef1b2c', 'no-image-available.png', '2025-09-13 12:32:02'),
(5, '004', '', 'Constantino', '', 'Cuevas', 'c.cuevas@faculty.com', 'ce55f389184a570f5036df3c42f41efa', 'no-image-available.png', '2025-09-13 12:32:55'),
(6, '005', '', 'Regilyn', '', 'Dayon', 'r.dayon@faculty.com', 'bf63db8f7f6c79a581b419a5309c9c05', 'no-image-available.png', '2025-09-13 13:40:36'),
(7, '006', '', 'Jeffry', '', 'Duca', 'j.duca@faculty.com', 'f6fd9bb09682d26c058d83d420ecd1ad', 'no-image-available.png', '2025-09-13 13:42:15'),
(8, '007', '', 'Reynald', '', 'Estanes', 'r.estanes@faculty.com', '539494a0cb87e07518ab1cb75b4969db', 'no-image-available.png', '2025-09-13 13:43:01'),
(9, '008', '', 'Michael', '', 'Esguerra', 'm.esguerra@faculty.com', '1cd06b84114629b4a2e0e55dc0fedb39', 'no-image-available.png', '2025-09-13 13:44:00'),
(10, '009', '', 'Angel Nicole', '', 'Ferrer', 'an.ferrer@faculty.com', '19185566c5fe3e613eb9a686fa3deec2', 'no-image-available.png', '2025-09-13 13:45:06'),
(11, '010', '', 'Zyrish Ann', '', 'Gadiano', 'za.gadiano@faculty.com', 'e9a9c268831035dbb2e0df161a71aedd', 'no-image-available.png', '2025-09-13 13:51:31'),
(12, '011', '', 'Jessie', '', 'Gagelonia', 'j.gagelonia@faculty.com', '87f2902045b0a64590736454950a91c7', 'no-image-available.png', '2025-09-13 13:54:49'),
(13, '012', '', 'Glenn Angelo', '', 'Gonzales', 'ga.gonzales@faculty.com', 'b238019f0e72d26cdad490650c4ff220', 'no-image-available.png', '2025-09-13 13:56:13'),
(14, '013', '', 'Catherine', '', 'Guillermo', 'c.guillermo@faculty.com', '16289f7266ad864c3aec42763a75a0f7', 'no-image-available.png', '2025-09-13 13:57:09'),
(15, '014', '', 'Lorna', '', 'Jardines', 'l.jardinez@faculty.com', 'e767a4fdaf8c9b96bb7dfa55c3f595a6', 'no-image-available.png', '2025-09-13 13:58:24'),
(16, '015', '', 'Jenmark', '', 'Lina', 'j.lina@faculty.com', '528a3c500e49e8d14159dd2935ee36a1', 'no-image-available.png', '2025-09-13 13:59:10'),
(17, '016', '', 'Ariane Kyrie', '', 'Macaslam', 'ak.macaslam@faculty.com', 'e06c2fa9e21039e350b74891d5f6d8cd', 'no-image-available.png', '2025-09-13 14:00:51'),
(18, '017', '', 'Alma', '', 'Makio', 'a.makio@faculty.com', 'cec035ff32e6ef8f2f58e3b6e817d5d2', 'no-image-available.png', '2025-09-13 14:02:27'),
(19, '018', '', 'Kayzel', '', 'Manglicmot', 'k.manglicmot@faculty.com', '683cc745613607770d1a05bb4ce97b4e', 'no-image-available.png', '2025-09-13 14:03:17'),
(20, '019', '', 'Jervin', '', 'Martin', 'j.martin@faculty.com', '34f74c049edea51851c6924f4a386762', 'no-image-available.png', '2025-09-13 14:05:10'),
(21, '020', '', 'Jocelyn', '', 'Matadling', 'j.matadling@faculty.com', '34cbbce5b936a4477af4bddb5022f27c', 'no-image-available.png', '2025-09-13 14:05:47'),
(22, '021', '', 'Roman', '', 'Mendoza', 'r.mendoza@faculty.com', '30df196559f6c591e936d7873119f5c9', 'no-image-available.png', '2025-09-13 14:06:30'),
(23, '022', '', 'John Kenneth', '', 'Meradios', 'jk.meradios@faculty.com', '423843259b685295f3b3f8795c8618a4', 'no-image-available.png', '2025-09-13 14:07:16'),
(24, '023', '', 'Elerizza', '', 'Meteoro', 'e.meteoro@faculty.com', '16addb116c0963a55b86c4ce1789f8dc', 'no-image-available.png', '2025-09-13 14:08:10'),
(25, '024', '', 'Rexie May', '', 'Morales', 'rm.morales@faculty.com', 'edb7f3ff814ddc4a53a00147ac5072cc', 'no-image-available.png', '2025-09-13 14:08:56'),
(26, '025', '', 'Roland', '', 'Morta', 'r.morta@faculty.com', '7389c8577fdb31b0330e1eda1309c452', 'no-image-available.png', '2025-09-13 14:09:36'),
(27, '026', '', 'Maria Angelyn', '', 'Nadora', 'ma.nadora@faculty.com', '7ee79925095bd88fc6b5aa955ff8b412', 'no-image-available.png', '2025-09-13 14:10:57'),
(28, '027', '', 'Arnold', '', 'Nidoy', 'a.nidoy@faculty.com', '88903f0a3f71450c1c9332e2d6f2d12a', 'no-image-available.png', '2025-09-13 14:11:42'),
(29, '028', '', 'Fina-Daye', '', 'Panginen', 'fd.panginen@faculty.com', '6cc569dba71fe67b408795d562f73145', 'no-image-available.png', '2025-09-13 14:13:53'),
(30, '029', '', 'Jeremy', '', 'Pascua', 'j.pascua@faculty.com', '5d96d28df832f52322d27f76a9c14317', 'no-image-available.png', '2025-09-13 14:14:52'),
(31, '030', '', 'Elmer', '', 'Prochina', 'e.prochina@faculty.com', 'eec15def5d46d6f6194c0ba82b03fb33', 'no-image-available.png', '2025-09-13 14:16:51'),
(32, '031', '', 'Rodeliza', '', 'Pungan', 'r.pungan@faculty.com', 'e90c782550fce8b87002c693d0bc3b23', 'no-image-available.png', '2025-09-13 14:17:35'),
(33, '032', '', 'Jayson', '', 'Simbulan', 'j.simbulan@faculty.com', '68649a65891fe16bbb01b28a37c5a3f8', 'no-image-available.png', '2025-09-13 14:18:26'),
(34, '033', '', 'Merly', '', 'Tamayao', 'm.tamayao@faculty.com', '8f998061239b097b8c2df7ef0b6ddf33', 'no-image-available.png', '2025-09-13 14:19:21'),
(35, '034', '', 'Jocelyn', '', 'Toquero', 'j.toquero@faculty.com', 'a9c11f79369826312a7199bb09844db5', 'no-image-available.png', '2025-09-13 14:21:06'),
(36, '035', '', 'Isabel', '', 'Valino', 'i.valino@faculty.com', 'f5675e4dab88afa8e3765df053106539', 'no-image-available.png', '2025-09-13 14:21:51'),
(37, 'SUM2021-01229', '', 'Test', '', 'Ing', 'testing@gmail.com', '0da0c67fbc3c12a17f569e03a8932f55', 'no-image-available.png', '2025-11-10 13:32:03'),
(38, 'SUM2021-12345', 'test123', 'test', '', '123', 'test123@gmail.com', 'cc03e747a6afbbcbf8be7668acfebee5', 'no-image-available.png', '2025-11-14 19:23:35');

-- --------------------------------------------------------

--
-- Table structure for table `irregular_student_subjects`
--

CREATE TABLE `irregular_student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `irregular_student_subjects`
--

INSERT INTO `irregular_student_subjects` (`id`, `student_id`, `subject_id`, `added_at`) VALUES
(1, 20, 4, '2026-01-29 22:08:57');

-- --------------------------------------------------------

--
-- Table structure for table `question_list`
--

CREATE TABLE `question_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `question` text NOT NULL,
  `order_by` int(30) NOT NULL,
  `criteria_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_list`
--

INSERT INTO `question_list` (`id`, `academic_id`, `question`, `order_by`, `criteria_id`) VALUES
(1, 3, 'Sample Question', 0, 1),
(3, 3, 'Test', 2, 2),
(5, 0, 'Question 101', 0, 1),
(6, 3, 'Sample 101', 4, 1),
(7, 4, 'Makes topic understandable', 0, 1),
(10, 5, 'a. Exhibits mastery of the topics discussed.  (Nagpapakita ng karunungan sa mga paksang tinalakay.)', 0, 1),
(11, 5, 'b. Responds to student’s questions with clarity and accuracy and gives concrete and accurate examples.    (Tumutugon sa mga katanungan ng mag-aaral nang may kalinawan at kawastuhan at nagbibigay ng kongkreto at tumpak na mga halimbawa.)', 1, 1),
(12, 5, 'c. Presents the lessons based on the objectives. (Nagpapakita ng mga aralin batay sa mga layunin.)', 2, 1),
(13, 5, 'd. Gives activities which integrates values related to the lesson and are relevant and essential for the attainment of the lesson objectives. (Nagbibigay ng mga gawain na nagsasama ng mga halagang may kaugnayan sa aralin at mahalaga para sa pagkakamit ng mga layunin ng aralin.)', 3, 1),
(14, 5, 'a. Employs effective and relevant motivation in classroom activities. (Gumagamit ng mabisa at may-katuturang pagganyak sa mga gawain pampaaralan.)', 4, 2),
(15, 5, 'Maximizes the available instructional devices, appropriate method and varied techniques for the lesson. (Ginagamit ang mga possible o maaring magamit na aparato sa pagtuturo na naaangkop sa pamamaraan at iba-ibang mga diskarte para sa aralin.)', 5, 2),
(16, 5, 'c. Encourage students’ interaction and participation by asking stimulating and probing questions. (Nanghihikayat ng pakikipag-ugnay at pakikilahok ng mga mag-aaral sa pamamagitan ng pagtatanong ng mga nakapagpapasigla at nagsisiyasat na katanungan.)', 6, 2),
(17, 5, 'a. Ask questions to clarify understanding during discussion (as formative assessment).              (Nagtatanong ng mga katanungan upang linawin ang pag-unawa sa panahon ng talakayan (bilang formative assessment)).', 7, 4),
(18, 5, 'b. Give formative exercises to check the level of student understanding and attainment of objective. (Nagbibigay ng mga formative na pagsasanay upang suriin ang antas ng pag-unawa ng mag-aaral at pagkamit ng layunin.)', 8, 4),
(19, 5, 'c. Utilizes summative evaluations relevant to the lesson objectives. (Gumagamit ng mga pagsusuri sa kabuuan na nauugnay sa mga layunin ng aralin.)                                                             ', 9, 4),
(20, 5, 'a. Appears presentable that postures confidence during virtual meeting thru google meet or any the same platforms.                                                      (Dumadalo ng presentable at nagpapakita ng kumpiyansa tuwing virtual meeting sa pamamagitan ng google meet o anumang mga parehong plataporma.)', 10, 5),
(21, 5, 'b. Shows appropriate command of authority in class. (Nagpapakita ng awtoridad sa klase.)', 11, 5),
(22, 5, 'c. Displays enthusiasm and positive interpersonal skills. (Nagpapakita ng sigasig at positibong pakikisama sa iba.)                                                                                             ', 12, 5),
(23, 5, 'a. Discusses the lesson displaying personal integrity in the classroom by their own use of ethical behaviors and by refusing to encourage or tolerate unethical behavior. (Natatalakay ang aralin na nagpapakita ng personal na integridad sa klase sa pamamagitan ng kanilang sariling paggamit ng etikal na pag-uugali at sa pagtanggi na hikayatin o tiisin ang hindi etikal na pag-uugali.)', 13, 6),
(24, 5, 'b. Presents organize ideas with clarity. (Nagpapakita ng organisadong mga ideya nang may kalinawan.)', 14, 6),
(25, 5, 'c. Uses language suited for the subject.           (Gumagamit ng wikang angkop para sa paksa.)', 15, 6),
(26, 5, 'a. Displays evident positive classroom routines (eg. never misses a class meeting; always checks attendance, etc.)                                                           (Nagpapakita ng mga malinaw na positibong gawain sa silid-aralan (hal. Hindi nakakaligtaan sa isang pagpupulong sa klase; palaging sinusuri ang pagdalo, atbp.)', 16, 7),
(27, 5, 'b. Gives positive reinforcements like praises, rewards and others. (Nagbibigay ng mga positibong feedbcak kagaya ng mga papuri, rewards at iba pa.)                                                                                              ', 17, 7),
(28, 5, 'c. Utilizes effective instructional time for teaching learning process. (Gumagamit ng mabisang oras ng pagtuturo para sa proseso ng pagkatuto ng pagtuturo.)                                                    ', 18, 7);

-- --------------------------------------------------------

--
-- Table structure for table `restriction_list`
--

CREATE TABLE `restriction_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restriction_list`
--

INSERT INTO `restriction_list` (`id`, `academic_id`, `faculty_id`, `class_id`, `subject_id`) VALUES
(8, 3, 1, 1, 1),
(9, 3, 1, 2, 2),
(10, 3, 1, 3, 3),
(18, 5, 19, 4, 4),
(19, 5, 32, 4, 4),
(20, 5, 22, 4, 4),
(21, 5, 25, 4, 4),
(22, 5, 18, 4, 4),
(23, 5, 5, 4, 4),
(25, 5, 20, 4, 4),
(27, 5, 35, 4, 4),
(28, 5, 1, 1, 1),
(29, 5, 38, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL DEFAULT '',
  `lastname` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `class_id` int(30) NOT NULL,
  `is_irregular` tinyint(1) NOT NULL DEFAULT 0,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `school_id`, `firstname`, `middlename`, `lastname`, `username`, `email`, `password`, `class_id`, `is_irregular`, `avatar`, `date_created`) VALUES
(1, 'SUM2021-01229', 'Test', '', 'Account', '', 'test@account.com', 'cc03e747a6afbbcbf8be7668acfebee5', 1, 0, 'no-image-available.png', '2025-09-15 04:50:09'),
(6, '25-0060', 'Jamaica Ashley', '', 'Aguinaldo', '', 'jamaicaashley@student.com', '2cf16f67492c800f603f4889c941a9d2', 1, 0, 'no-image-available.png', '2025-09-08 11:43:02'),
(7, '25-0222', 'Meryll Anthonette', '', 'Aguilar', '', 'meryllanthonette@student.com', '229e1bacd0214d43b0e952e09502719c', 1, 0, 'no-image-available.png', '2025-09-08 11:44:46'),
(8, '25-0009', 'Jhiemaira Victor', '', 'Alarcon', '', 'jhiemairavictor@student.com', 'fbcdd32d9b76542f43409514076cf126', 1, 0, 'no-image-available.png', '2025-09-08 11:46:07'),
(10, '25-0353', 'Maria Cindy', '', 'Garcia', '', 'mariacindy@student.com', '2252025a024140bd165d081cf920a0f5', 4, 0, 'no-image-available.png', '2025-09-08 11:51:16'),
(11, '25-0068', 'Princess Ericka', '', 'Arata', '', 'princessericka@student.com', 'c1866efebf8e392b80f65b2f21060d99', 1, 0, 'no-image-available.png', '2025-09-08 11:52:20'),
(12, '25-0113', 'Raighley', '', 'Baldovino', '', 'raighley@student.com', 'daeb505c51af2b9389c4bace3fa79675', 1, 0, 'no-image-available.png', '2025-09-08 11:53:18'),
(13, '25-0306', 'Trisha Gaile', '', 'Acosta', '', 'trishagaile@student.com', '7bd0c4485665ccf8fca611166f504943', 4, 0, 'no-image-available.png', '2025-09-08 11:53:44'),
(14, '25-0130', 'Mary Grace', '', 'Buenavista', '', 'marygrace@student.com', '234b3e4d6724900a90a5d6386789310f', 1, 0, 'no-image-available.png', '2025-09-08 11:54:15'),
(15, '25-0232', 'Maurene', '', 'Callanta', '', 'maurene@student.com', 'f9a9b26b6f4026881f8fb1166728668c', 1, 0, 'no-image-available.png', '2025-09-08 11:55:09'),
(16, '25-0281', 'Jonalyn', '', 'Arandia', '', 'jonalyn@student.com', 'fd3d28c90ec2e8426f30fe052dda2ea8', 4, 0, 'no-image-available.png', '2025-09-08 11:55:16'),
(17, '25-0025', 'Krisha Joyce', '', 'Carpio', '', 'krishajoyce@student.com', 'a4bd237b48d7ec0557fb26c610f06b4e', 1, 0, 'no-image-available.png', '2025-09-08 11:56:03'),
(18, '25-0226', 'Mark Vincent', '', 'Camposano', '', 'markvincent@student.com', 'e4c830e463978c6b18b54e85e38e2fa4', 4, 0, 'no-image-available.png', '2025-09-08 11:56:44'),
(19, '25-0137', 'John Rein', '', 'Dacumos', '', 'johnrein@student.com', 'fed8fc000de7f6bb9321d1291843e1a0', 1, 0, 'no-image-available.png', '2025-09-08 11:57:01'),
(20, '25-0021', 'Aizel', '', 'Dela Cruz', 'Aizel123', 'aizel@student.com', '3ce8b9c2257b9179f206efbfa8f163a6', 1, 1, 'no-image-available.png', '2025-09-08 11:57:55'),
(21, '25-0377', 'Kristina Cassandra', '', 'De Guzman', '', 'kristinacassandra@student.com', '7a4ff731fdb1916d2b071284b00a642f', 4, 0, 'no-image-available.png', '2025-09-08 11:58:04'),
(22, '25-0019', 'Trisha Mae', '', 'Dela Cruz', '', 'trishamae@student.com', 'd9d5d0ea7d8dff36540ee89902c2903c', 1, 0, 'no-image-available.png', '2025-09-08 11:58:46'),
(23, '25-0302', 'Precilla', '', 'Dela Masa', '', 'precilla@student.com', '469b0c89135a359fc5729312490bfa08', 1, 0, 'no-image-available.png', '2025-09-08 11:59:42'),
(24, '25-0116', 'Ashley Mae', '', 'Flores', '', 'ashleymae@student.com', 'fa5e7d95c3328b374f1734f31aae361b', 4, 0, 'no-image-available.png', '2025-09-08 12:00:01'),
(25, '25-0153', 'Julia Angelene', '', 'Enriquez', '', 'julaiangelene@student.com', 'fc958c4cc6fc00b8041e836c17b92f30', 1, 0, 'no-image-available.png', '2025-09-08 12:00:48'),
(26, '25-0284', 'Tristan Jay', '', 'Libid', '', 'tristanjay@student.com', 'c0ec0cced6d7a971a0466f11c88a1929', 4, 0, 'no-image-available.png', '2025-09-08 12:01:26'),
(27, '25-0002', 'Zandy', '', 'Medina', '', 'zandy@student.com', 'd24da12e036646bc23ee14299a826456', 4, 0, 'no-image-available.png', '2025-09-08 12:02:22'),
(28, '25-0216', 'John Paolo', '', 'Miguel', '', 'johnpaolo@student.com', '2b6f25a0cabe96ad6f0b4a383efbd3fa', 4, 0, 'no-image-available.png', '2025-09-08 12:04:44'),
(29, '25-0194', 'Francis Dion', '', 'Salvador', '', 'francisdion@student.com', 'bf14357628acc81e74c7f9ebdc08fdb1', 4, 0, 'no-image-available.png', '2025-09-09 13:01:31'),
(30, '25-0109', 'Angela', '', 'De Guzman', '', 'angela@student.com', 'f9d1426a1d2bf1b94d016f764143143e', 4, 0, 'no-image-available.png', '2025-09-09 13:02:52'),
(31, '25-0286', 'Jewel Ann', '', 'Centeno', '', 'jewelann@student.com', '9d9fdeb4da0dd574f1639e5591d4cfbc', 4, 0, 'no-image-available.png', '2025-09-09 13:04:50'),
(32, '25-0379', 'Patrick', '', 'Bago', '', 'patrick@student.com', 'b6f020c29a86dfc63abfb36d9ac9b84a', 4, 0, 'no-image-available.png', '2025-09-09 13:06:38'),
(33, '25-0159', 'Pamela Mae', '', 'Acupan', '', 'pamelamae@student.com', '4537c15656e80aaff68df8431c72885c', 4, 0, 'no-image-available.png', '2025-09-09 13:07:56'),
(34, '25-0311', 'Russel Von', '', 'Alarcon', '', 'russelvon@student.com', 'f218397fcbd09ea591291e5869d842ae', 4, 0, 'no-image-available.png', '2025-09-09 13:09:03'),
(35, '25-0313', 'Heart Dezaire', '', 'Barcelo', '', 'heartdezaire@student.com', 'f77aac381faab028672ee9bdf4d98fa6', 4, 0, 'no-image-available.png', '2025-09-09 13:11:38'),
(36, '25-0162', 'Janine', '', 'Sotto', '', 'janine@student.com', '390e61bc56f1b1edfa11920e7c434119', 4, 0, 'no-image-available.png', '2025-09-09 13:13:12'),
(37, '25-0307', 'Cheska Gayle', '', 'Corpuz', '', 'cheskagayle@student.com', '5b36a8ae59566a8bc49b4dd4366c9a64', 4, 0, 'no-image-available.png', '2025-09-09 13:13:26'),
(38, '25-0105', 'princess krisha mae', '', 'muyot', '', 'princesskrishamae@student.com', 'f76cc956511cf025d1b285a18901b703', 4, 0, 'no-image-available.png', '2025-09-09 13:14:42'),
(39, '25-0248', 'Aldrae Nicholas', '', 'Dizon', '', 'aldraenicholas@student.com', '4ca729e39627cf59fbc4478a9a1d7385', 4, 0, 'no-image-available.png', '2025-09-09 13:15:09'),
(40, '25-0298', 'jamilla', '', 'laurente', '', 'jamila@student.com', '2c3d95a638c69be0a891d9cf3df48870', 4, 0, 'no-image-available.png', '2025-09-09 13:17:08'),
(41, '25-0267', 'nicole', '', 'jumao-as', '', 'nicole@student.com', '2a0247e1d3042d1f1926b3ee2fc3e53e', 4, 0, 'no-image-available.png', '2025-09-09 13:18:11'),
(42, '25-0285', 'jeanly vee', '', 'damian', '', 'jeanlyvee@student.com', '0ac3d8ed081d1ba7a6fcfbb091bf7c3e', 4, 0, 'no-image-available.png', '2025-09-09 13:19:12'),
(43, '25-0110', 'Glenn Niño', '', 'Malubag', '', 'glennnino@student.com', '41f499add7f898fc1f55e5f3e18dea1b', 4, 0, 'no-image-available.png', '2025-09-09 13:19:31'),
(44, '25-0269', 'carl lexter', '', 'matadling', '', 'carllexter@student.com', 'bee6dfde149cf1b76110ff1c0467eb3a', 4, 0, 'no-image-available.png', '2025-09-09 13:20:28'),
(45, '25-0266', 'JC Claire', '', 'Montuerto', '', 'jcclaire@student.com', '8a7a83427eab2f42a47bcc36193dba9f', 4, 0, 'no-image-available.png', '2025-09-09 13:21:03'),
(46, '25-0029', 'wilcie joyce', '', 'bayan', '', 'wilciejoyce@student.com', '2a44e97dfbe21b4c7c63d32168d4fc3e', 4, 0, 'no-image-available.png', '2025-09-09 13:21:23'),
(47, '25-0289', 'triztine lee', '', 'arellano', '', 'triztinelee@student.com', '927df513d90899e4bc7baab637acbc76', 4, 0, 'no-image-available.png', '2025-09-09 13:23:18'),
(48, '25-0373', 'bryan', '', 'arazo', '', 'bryan@student.com', 'faceeacc53053b3865fdef38063d06a9', 4, 0, 'no-image-available.png', '2025-09-09 13:24:47'),
(49, '25-0287', 'alexa reena', '', 'bautista', '', 'alexareena@student.com', 'a7e0129571aa25f9056cf754e20ff07f', 4, 0, 'no-image-available.png', '2025-09-09 13:25:44'),
(50, '25-0150', 'janine alexis', '', 'dayao', '', 'janinealexis@student.com', '864b0f82cb843e0b1bf4c68d24b0caba', 4, 0, 'no-image-available.png', '2025-09-09 13:26:48'),
(51, '25-0117', 'Frelli Nicole', '', 'Santos', '', 'frellinicole@student.com', '7d3e447eb849f0277fb25fe4ff3cb679', 4, 0, 'no-image-available.png', '2025-09-09 13:27:24'),
(52, '25-0085', 'michaela angela', '', 'fajardo', '', 'michaelaangela@student.com', '967b32a1cd9bebbb41cfbfa46918e178', 4, 0, 'no-image-available.png', '2025-09-09 13:28:04'),
(53, '25-0166', 'dale matthew', '', 'lazatin', '', 'dalematthew@student.com', '1911df4225d02f2f5396f8e0275a8895', 4, 0, 'no-image-available.png', '2025-09-09 13:30:00'),
(54, '25-0294', 'jomar', '', 'marigsa', '', 'jomar@student.com', 'e7ca5c2a6768b5d001448ba87f842856', 4, 0, 'no-image-available.png', '2025-09-09 13:30:54'),
(55, '25-0043', 'jade ann', '', 'paltao', '', 'jadeann@student.com', '4f30e4b1e9c7edcc9f2cb444449eb49f', 4, 0, 'no-image-available.png', '2025-09-09 13:31:52'),
(56, '25-0191', 'keziah muriel', '', 'tabalno', '', 'keziahmuriel@student.com', 'c6d174062a70a908e8d981e35670a853', 4, 0, 'no-image-available.png', '2025-09-09 13:33:07'),
(57, '25-0367', 'gnash francis', '', 'umali', '', 'gnashfrancis@student.com', 'afb2ccc3f8a90e188e118565f6c654a2', 4, 0, 'no-image-available.png', '2025-09-09 13:34:09'),
(58, '25-0370', 'rianne mae', '', 'urgino', '', 'riannemae@student.com', 'db4f281a09a0de2665c33bb7018ae02a', 4, 0, 'no-image-available.png', '2025-09-09 13:35:06'),
(59, '25-0142', 'gilbert quiel', '', 'villamar', '', 'gilbertquiel@student.com', '449291754c47944e535f158029457b12', 4, 0, 'no-image-available.png', '2025-09-09 13:36:09'),
(60, '25-0339', 'nikki', '', 'malazarte', '', 'nikki@student.com', 'b06eb1b6e94bdaa61e5a90d08c164859', 4, 0, 'no-image-available.png', '2025-09-09 13:37:05'),
(61, '25-0308', 'amy', '', 'espiritu', '', 'amy@student.com', 'ed3f01f9f56b982a896c2f757cebefe6', 4, 0, 'no-image-available.png', '2025-09-09 13:38:00'),
(62, '25-0080', 'Althea', '', 'Escuador', '', 'althea@student.com', 'bd0292ef4256183182e636e9759264d2', 1, 0, 'no-image-available.png', '2025-09-09 13:56:02'),
(63, '25-0022', 'Princess Loraine', '', 'Ferreon', '', 'princessloraine@student.com', 'e61ef0f4c0d804c062af09607ed36790', 1, 0, 'no-image-available.png', '2025-09-09 13:57:08'),
(64, '25-0102', 'Lorie Anne', '', 'Fontanilla', '', 'lorieanne@student.com', '0d56012260dcbf4f365d585e3e7b4947', 1, 0, 'no-image-available.png', '2025-09-09 13:58:05'),
(65, '25-0040', 'Christine Joy', '', 'Florendo', '', 'christinejoy@student.com', 'be5425ddf3473b1eb995d12049bc977b', 1, 0, 'no-image-available.png', '2025-09-09 13:58:55'),
(66, '25-0027', 'Rizza', '', 'Gayla', '', 'rizza@student.com', '05bfee00e3b2cb7ccd3f145f1b198e95', 1, 0, 'no-image-available.png', '2025-09-09 13:59:34'),
(67, '25-0230', 'Maicca Jeanna', '', 'Guiang', '', 'maiccajeanna@student.com', '36b9ddf52bae24f382e80ac86dec8da1', 1, 0, 'no-image-available.png', '2025-09-09 14:01:09'),
(68, '25-0135', 'John Michael', '', 'Indiana', '', 'johnmichael@student.com', 'f7903fb25c6b8deea1b79a484be55767', 1, 0, 'no-image-available.png', '2025-09-09 14:02:02'),
(69, '25-0235', 'Ayhecia Lorreign', '', 'Las Piñas', '', 'ayhecialorreign@student.com', '316a1cf226ede44d6dc4ab6c43c1c5eb', 1, 0, 'no-image-available.png', '2025-09-09 14:09:53'),
(70, '25-0295', 'Mikaella Divine', '', 'Lagsilon', '', 'mikaelladivine@student.com', '7f1f29e692ba010378a9861dab86ca52', 1, 0, 'no-image-available.png', '2025-09-09 14:10:46'),
(71, '25-0084', 'Hazel', '', 'Manaran', '', 'hazel@student.com', '3f7030d12d923f51264a12782c7a6037', 1, 0, 'no-image-available.png', '2025-09-09 14:11:41'),
(72, '25-0083', 'Cherry', '', 'Masaybing', '', 'cherry1@student.com', '7e442dfa0c372b991072484e94abc1df', 1, 0, 'no-image-available.png', '2025-09-09 14:12:57'),
(73, '25-0206', 'Pauline', '', 'Medrano', '', 'pauline@student.com', '344f0a9ced0c0956361a59b29b66d6f2', 1, 0, 'no-image-available.png', '2025-09-09 14:13:45'),
(74, '25-0106', 'Allyssa Joy', '', 'Fajardo', '', 'allyssajoy@student.com', 'a5f9db0084bf7e6a684d10bbc7afa001', 1, 0, 'no-image-available.png', '2025-09-09 14:14:50'),
(75, '25-0042', 'Laralaine', '', 'Ocampo', '', 'laralaine@student.com', 'a61f28e169d2ce005380c2d5ca25cad4', 1, 0, 'no-image-available.png', '2025-09-09 14:15:40'),
(76, '25-0011', 'Mhyla', '', 'Ocumen', '', 'mhyla@student.com', '13b702512348c8416de196888ea57f0b', 1, 0, 'no-image-available.png', '2025-09-09 14:16:22'),
(77, '25-0020', 'Jillian', '', 'Petacio', '', 'jillian@student.com', '24bfe01f2d7840447979f3a81fc36c65', 1, 0, 'no-image-available.png', '2025-09-09 14:18:32'),
(78, '25-0028', 'Andrea Pauleen', '', 'Ponce', '', 'andreapauleen@student.com', 'c532286ad7286925a641f43465dadd4a', 1, 0, 'no-image-available.png', '2025-09-09 14:19:18'),
(79, '25-0227', 'Mary Grace', '', 'Ramos', '', 'marygrace1@student.com', '5f1f1f4e959479e4f53b516e26141b53', 1, 0, 'no-image-available.png', '2025-09-09 14:20:35'),
(80, '25-0198', 'Angelita', '', 'Seradoy', '', 'angelita@student.com', '53aacd219248d7cf0239505007f68799', 1, 0, 'no-image-available.png', '2025-09-09 14:21:33'),
(81, '25-0044', 'Jay Angelo', '', 'Salcedo', '', 'jayangelo@student.com', '3e68ec9cffd8868ee931248385694061', 1, 0, 'no-image-available.png', '2025-09-09 14:22:46'),
(82, '25-0378', 'Jorizza Mae', '', 'Talania', '', 'jorizzamae@student.com', 'd671add8c7d68874a35c0d0c2178a38c', 1, 0, 'no-image-available.png', '2025-09-09 14:24:16'),
(83, '25-0026', 'Lewis Alfred', '', 'Tinio', '', 'lewisalfred@student.com', '064add50182475853694c999f3f1040a', 1, 0, 'no-image-available.png', '2025-09-09 14:25:02'),
(84, '25-0058', 'Reynalyn', '', 'Valenciana', '', 'reynalyn@student.com', 'ab516914b6a2333c8c5972ca2e58f135', 1, 0, 'no-image-available.png', '2025-09-09 14:25:49'),
(85, '25-0037', 'Jonel Bien', '', 'Yabut', '', 'jonelbien@student.com', '26d1da5a27ada6822a681bcdad0dabea', 1, 0, 'no-image-available.png', '2025-09-09 14:26:30'),
(86, '25-0388', 'Leanne Grace', '', 'Perez', '', 'leannegrace@student.com', '8cc3b54855005bd76326aaca5d41d3bf', 1, 0, 'no-image-available.png', '2025-09-09 14:27:19'),
(87, 'SUM2021-12345', 'test', '', '123', '', 'test123@gmail.com', 'cc03e747a6afbbcbf8be7668acfebee5', 1, 0, 'no-image-available.png', '2025-11-14 19:23:05'),
(88, 'SUM2021-12345', 'test1', '', '123', '', 'test1123@gmail.com', 'bc41200b941374013737be5b4fe43640', 0, 0, 'no-image-available.png', '2025-11-14 19:30:51'),
(89, 'SUM2021-02322', 'asd', '', '123', '', 'asd123@gmail.com', 'bfd59291e825b5f2bbf1eb76569f8fe7', 6, 0, 'no-image-available.png', '2025-11-14 19:32:01'),
(90, 'SUM2021-12345', 'asd', '', '123', 'asd123', 'asd123123@gmail.com', 'bfd59291e825b5f2bbf1eb76569f8fe7', 5, 0, 'no-image-available.png', '2026-01-29 22:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `subject_course_mapping`
--

CREATE TABLE `subject_course_mapping` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `curriculum` varchar(100) NOT NULL,
  `level` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_course_mapping`
--

INSERT INTO `subject_course_mapping` (`id`, `subject_id`, `curriculum`, `level`, `created_at`) VALUES
(1, 5, 'BSA', '1', '2026-01-29 22:11:55'),
(4, 1, 'BSA', '2', '2026-01-29 22:22:04');

-- --------------------------------------------------------

--
-- Table structure for table `subject_list`
--

CREATE TABLE `subject_list` (
  `id` int(30) NOT NULL,
  `code` varchar(50) NOT NULL,
  `subject` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_list`
--

INSERT INTO `subject_list` (`id`, `code`, `subject`, `description`) VALUES
(1, 'GE02', 'Readings in Philippine History', ''),
(2, 'GEEL1', 'English +', ''),
(3, 'GE01', 'Understanding the Self', ''),
(4, 'GE04', 'Mathematics in the Modern World', ''),
(5, 'BEEL1', 'Basic Accounting Review', ''),
(6, 'AEC1', 'Managerial Economics', ''),
(7, 'PATHFIT1 ', 'Movement Competency Training', ''),
(8, 'NSTP1', 'National Service Training Program', '');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Faculty Evaluation System', 'info@sample.comm', '+6948 8542 623', '2102  Caldwell Road, Rochester, New York, 14608', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL DEFAULT '',
  `lastname` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `email`, `password`, `avatar`, `date_created`) VALUES
(1, 'Administrator', '', '', 'admin', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', '1607135820_avatar.jpg', '2020-11-26 10:57:04'),
(2, 'Kayzy', '', 'Oligo', '', 'kayzy@admin.com', '8539c819dc813c35ec18bc874e3a0d8a', 'no-image-available.png', '2025-09-08 11:26:53'),
(3, 'Helen ', '', 'Dela Cruz', '', 'helen@admin.com', '3fe4c917f62e537e040eb6424ef3d304', 'no-image-available.png', '2025-09-08 11:27:34'),
(4, 'Alex', '', 'Ociana', '', 'alex@admin.com', 'b75bd008d5fecb1f50cf026532e8ae67', 'no-image-available.png', '2025-09-09 13:09:48'),
(5, 'test', '', '123', 'test123', 'test123@gmail.com', 'cc03e747a6afbbcbf8be7668acfebee5', 'no-image-available.png', '2025-11-14 19:22:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_list`
--
ALTER TABLE `academic_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_type` (`user_type`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indexes for table `additional_feedback`
--
ALTER TABLE `additional_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_list`
--
ALTER TABLE `class_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `irregular_student_subjects`
--
ALTER TABLE `irregular_student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_irregular_subject` (`student_id`,`subject_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `question_list`
--
ALTER TABLE `question_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restriction_list`
--
ALTER TABLE `restriction_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_course_mapping`
--
ALTER TABLE `subject_course_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_mapping` (`subject_id`,`curriculum`,`level`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `curriculum` (`curriculum`),
  ADD KEY `level` (`level`);

--
-- Indexes for table `subject_list`
--
ALTER TABLE `subject_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
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
-- AUTO_INCREMENT for table `academic_list`
--
ALTER TABLE `academic_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `additional_feedback`
--
ALTER TABLE `additional_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `class_list`
--
ALTER TABLE `class_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `faculty_list`
--
ALTER TABLE `faculty_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `irregular_student_subjects`
--
ALTER TABLE `irregular_student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `question_list`
--
ALTER TABLE `question_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `restriction_list`
--
ALTER TABLE `restriction_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `subject_course_mapping`
--
ALTER TABLE `subject_course_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subject_list`
--
ALTER TABLE `subject_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
