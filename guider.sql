-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2026 at 11:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `guider`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `created_at`) VALUES
(1, 3, 'lead.profile_updated', 'Updated student profile for lead \"Mahnoor Iqbal\"', '2026-07-20 03:05:06'),
(2, 3, 'followup.created', 'Added follow-up for \"Mahnoor Iqbal\"', '2026-07-20 03:05:42'),
(3, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-07-20 03:15:56'),
(4, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-07-20 03:15:58'),
(5, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-07-20 03:15:58'),
(6, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-07-20 03:16:06'),
(7, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-07-20 03:16:07'),
(8, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-07-20 03:16:09'),
(9, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-07-20 03:16:13'),
(10, 3, 'lead.created', 'Added lead \"Test Contact Good\"', '2026-07-20 03:48:46'),
(11, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-08-05 03:40:23'),
(12, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-08-05 03:40:24'),
(13, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-08-05 03:41:01'),
(14, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-08-05 03:41:30'),
(15, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-08-05 03:41:37'),
(16, 4, 'followup.created', 'Added follow-up for \"Areeba Khan\"', '2026-08-05 03:41:43'),
(17, 4, 'lead.call_logged', 'Logged call for lead \"Areeba Khan\"', '2026-08-05 04:36:32'),
(18, 4, 'lead.activity_logged', 'Logged messages for lead \"Areeba Khan\"', '2026-08-05 04:36:35'),
(19, 4, 'lead.activity_logged', 'Logged visits for lead \"Areeba Khan\"', '2026-08-05 04:36:36'),
(20, 4, 'lead.activity_logged', 'Logged messages for lead \"Areeba Khan\"', '2026-08-05 04:39:54'),
(21, 4, 'lead.activity_logged', 'Logged visits for lead \"Areeba Khan\"', '2026-08-05 04:39:56'),
(22, 4, 'lead.converted', 'Converted lead \"Areeba Khan\" to a student', '2026-08-05 04:40:25'),
(23, 4, 'lead.call_logged', 'Logged call for lead \"Areeba Khan\"', '2026-08-05 04:40:40'),
(24, 4, 'lead.call_logged', 'Logged call for lead \"Areeba Khan\"', '2026-08-05 04:40:43'),
(25, 4, 'lead.activity_logged', 'Logged messages for lead \"Areeba Khan\"', '2026-08-05 04:40:44'),
(26, 4, 'lead.call_logged', 'Logged call for lead \"Areeba Khan\"', '2026-08-05 04:46:59'),
(27, 4, 'lead.activity_logged', 'Logged messages for lead \"Areeba Khan\"', '2026-08-05 04:47:00'),
(28, 4, 'lead.call_logged', 'Logged call for lead \"Areeba Khan\"', '2026-08-05 04:54:40'),
(29, 4, 'lead.activity_logged', 'Logged visits for lead \"Areeba Khan\"', '2026-08-05 04:54:41'),
(30, 4, 'lead.activity_logged', 'Logged visits for lead \"Areeba Khan\"', '2026-08-05 04:54:43'),
(31, 4, 'lead.activity_logged', 'Logged visits for lead \"Areeba Khan\"', '2026-08-05 04:54:45'),
(32, 4, 'lead.activity_logged', 'Logged messages for lead \"Areeba Khan\"', '2026-08-05 04:55:03'),
(33, 4, 'lead.activity_logged', 'Logged visits for lead \"Areeba Khan\"', '2026-08-05 04:55:05'),
(34, 4, 'lead.activity_logged', 'Logged visits for lead \"Areeba Khan\"', '2026-08-05 05:00:11'),
(35, 4, 'lead.moved_to_followup', 'Moved lead \"Areeba Khan\" to follow-ups', '2026-08-05 05:07:19'),
(36, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-08-05 05:27:17'),
(37, 4, 'lead.profile_updated', 'Updated student profile for lead \"Areeba Khan\"', '2026-08-05 05:27:22'),
(38, 4, 'lead.call_logged', 'Logged call for lead \"Areeba Khan\"', '2026-08-05 05:29:25'),
(39, 4, 'lead.activity_logged', 'Logged visits for lead \"Areeba Khan\"', '2026-08-05 05:29:27'),
(40, 4, 'lead.contract_toggled', 'Toggled contract signed for lead \"Areeba Khan\"', '2026-08-05 05:29:32'),
(41, 4, 'lead.payment_toggled', 'Toggled payment done for lead \"Areeba Khan\"', '2026-08-05 05:29:35'),
(42, 4, 'lead.moved_to_followup', 'Moved lead \"Areeba Khan\" to follow-ups', '2026-08-05 05:29:38'),
(43, 4, 'lead.moved_to_followup', 'Moved lead \"Areeba Khan\" to follow-ups', '2026-08-05 05:29:43'),
(44, 4, 'lead.converted', 'Converted lead \"Areeba Khan\" to registered student (ID: 6)', '2026-08-05 06:20:38'),
(45, 4, 'student.visa_stage_changed', '\"Areeba Khan\" visa stage moved to Fee Paid', '2026-08-05 06:26:09'),
(46, 4, 'student.visa_stage_changed', '\"Areeba Khan\" visa stage moved to Visa Documentation', '2026-08-05 06:26:10'),
(47, 4, 'student.visa_stage_changed', '\"Areeba Khan\" visa stage moved to Visa Process', '2026-08-05 06:26:10'),
(48, 4, 'student.visa_stage_changed', '\"Areeba Khan\" visa stage moved to Visa Approved', '2026-08-05 06:26:10'),
(49, 4, 'student.visa_stage_changed', '\"Areeba Khan\" visa stage moved to Traveling', '2026-08-05 06:26:11'),
(50, 4, 'student.visa_stage_changed', '\"Areeba Khan\" visa stage moved to Visa Rejected', '2026-08-05 06:26:11'),
(51, 4, 'student.visa_stage_changed', '\"Areeba Khan\" visa stage moved to Not Started', '2026-08-05 06:26:12'),
(52, 4, 'student.visa_stage_changed', '\"Areeba Khan\" visa stage moved to Fee Paid', '2026-08-05 06:26:13'),
(53, 4, 'student.visa_stage_changed', '\"Areeba Khan\" visa stage moved to Visa Documentation', '2026-08-05 06:26:13'),
(54, 4, 'student.visa_stage_changed', '\"Areeba Khan\" visa stage moved to Visa Process', '2026-08-05 06:26:14'),
(55, 4, 'student.app_stage_changed', '\"Areeba Khan\" application stage moved to Submitted', '2026-08-05 06:27:33'),
(56, 4, 'student.app_stage_changed', '\"Areeba Khan\" application stage moved to Waiting for Acceptance', '2026-08-05 06:27:34'),
(57, 4, 'student.app_stage_changed', '\"Areeba Khan\" application stage moved to Accepted', '2026-08-05 06:27:35'),
(58, 4, 'lead.created', 'Added lead \"Jimy\"', '2026-08-05 06:46:42'),
(59, 4, 'lead.profile_updated', 'Updated student profile for lead \"Jimy\"', '2026-08-05 06:55:47'),
(60, 4, 'lead.profile_updated', 'Updated student profile for lead \"Jimy\"', '2026-08-05 06:55:55'),
(61, 4, 'lead.profile_updated', 'Updated student profile for lead \"Jimy\"', '2026-08-05 06:55:55'),
(62, 4, 'lead.profile_updated', 'Updated student profile for lead \"Jimy\"', '2026-08-05 06:55:58'),
(63, 4, 'lead.profile_updated', 'Updated student profile for lead \"Jimy\"', '2026-08-05 06:56:01'),
(64, 4, 'lead.profile_updated', 'Updated student profile for lead \"Jimy\"', '2026-08-05 06:56:04'),
(65, 4, 'lead.profile_updated', 'Updated student profile for lead \"Jimy\"', '2026-08-05 06:56:09'),
(66, 4, 'lead.profile_updated', 'Updated student profile for lead \"Jimy\"', '2026-08-05 06:56:20'),
(67, 4, 'lead.call_logged', 'Logged call for lead \"Jimy\"', '2026-08-05 06:56:20'),
(68, 4, 'lead.converted', 'Converted lead \"Jimy\" to registered student (ID: 7)', '2026-08-05 06:56:27'),
(69, 4, 'lead.created', 'Added lead \"Admin\"', '2026-08-05 07:03:39'),
(70, 4, 'lead.call_logged', 'Logged call for lead \"Admin\"', '2026-08-05 07:03:48'),
(71, 4, 'lead.moved_to_followup', 'Moved lead \"Admin\" to follow-ups', '2026-08-05 07:03:55'),
(72, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:04:02'),
(73, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:04:04'),
(74, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:04:08'),
(75, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:04:09'),
(76, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:04:11'),
(77, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:04:15'),
(78, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:04:18'),
(79, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:04:23'),
(80, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:04:28'),
(81, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:04:39'),
(82, 4, 'lead.call_logged', 'Logged call for lead \"Admin\"', '2026-08-05 07:04:44'),
(83, 4, 'lead.call_logged', 'Logged call for lead \"Admin\"', '2026-08-05 07:05:08'),
(84, 4, 'lead.activity_logged', 'Logged visits for lead \"Admin\"', '2026-08-05 07:05:13'),
(85, 4, 'lead.call_logged', 'Logged call for lead \"Admin\"', '2026-08-05 07:05:20'),
(86, 4, 'lead.interest_toggled', 'Toggled highly interested for lead \"Admin\"', '2026-08-05 07:45:20'),
(87, 4, 'lead.interest_toggled', 'Toggled highly interested for lead \"Admin\"', '2026-08-05 07:45:25'),
(88, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:45:43'),
(89, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:45:48'),
(90, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:46:08'),
(91, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:46:20'),
(92, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:46:37'),
(93, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:46:42'),
(94, 4, 'lead.profile_updated', 'Updated student profile for lead \"Admin\"', '2026-08-05 07:46:51'),
(95, 4, 'lead.created', 'Added lead \"Jimy\"', '2026-08-07 06:47:49'),
(96, 4, 'lead.interest_toggled', 'Toggled highly interested for lead \"Jimy\"', '2026-08-07 07:12:31'),
(97, 4, 'lead.interest_toggled', 'Toggled highly interested for lead \"Jimy\"', '2026-08-07 07:12:33'),
(98, 4, 'application.created', 'Added application for \"Jimy\" to \"Journal of Applied Physics\"', '2026-08-19 05:19:00'),
(99, 4, 'invoice.created', 'Generated invoice 001/AUG/2026 for \"Areeba Khan\"', '2026-08-21 06:29:10'),
(100, 4, 'invoice.created', 'Generated invoice 002/AUG/2026 for \"Jimy\"', '2026-08-21 06:35:28'),
(101, 4, 'invoice.created', 'Generated invoice 003/AUG/2026 for \"Jimy\"', '2026-08-21 06:48:35'),
(102, 4, 'invoice.created', 'Generated invoice 004/AUG/2026 for \"Jimy\"', '2026-08-21 06:48:53'),
(103, 1, 'contract.created', 'Created contract for \"Zara Ahmed\"', '2026-08-24 07:48:03'),
(104, 1, 'task.created', 'Created task \"Smoke test task\" assigned to \"Hassan Raza\"', '2026-08-24 07:49:19'),
(105, 1, 'task.completed', '\"Smoke test task\" marked as done', '2026-08-24 07:49:29'),
(106, 4, 'task.created', 'Created task \"Cross-assign smoke test\" assigned to \"Ayesha Siddiqui\"', '2026-08-25 06:50:06'),
(107, 4, 'contract.created', 'Created contract for \"Jimy\"', '2026-08-25 06:55:24'),
(108, 4, 'contract.created', 'Created contract for \"Danish Farooqi\"', '2026-08-25 07:02:53'),
(109, 3, 'lead.email_sent', 'Emailed lead \"Mahnoor Iqbal\": Following up on your Germany application', '2026-08-25 07:14:04'),
(110, 3, 'lead.created', 'Added lead \"Smoke Test Lead\"', '2026-08-25 07:31:47'),
(111, 3, 'lead.profile_updated', 'Updated student profile for lead \"Smoke Test Lead\"', '2026-08-25 07:32:02'),
(112, 3, 'lead.email_sent', 'Emailed lead \"Smoke Test Lead\": Profile-email test', '2026-08-25 07:32:08'),
(113, 4, 'lead.call_logged', 'Logged call for lead \"Jimy\"', '2026-09-02 04:11:00');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `opportunity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `app_stage` enum('Not Applied','Submitted','Waiting for Acceptance','Accepted','Rejected') NOT NULL DEFAULT 'Not Applied',
  `fee_status` enum('Not Set','Pending','Partially Paid','Paid','Overdue') NOT NULL DEFAULT 'Not Set',
  `fee_date` date DEFAULT NULL,
  `visa_stage` enum('Not Started','Documentation','Submitted','Approved','Rejected') NOT NULL DEFAULT 'Not Started',
  `visa_submission_date` date DEFAULT NULL,
  `travel_status` enum('Not Ready','Cleared to Travel','Travelled') NOT NULL DEFAULT 'Not Ready',
  `travel_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `student_id`, `opportunity_id`, `app_stage`, `fee_status`, `fee_date`, `visa_stage`, `visa_submission_date`, `travel_status`, `travel_date`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Accepted', 'Paid', '2026-07-04', 'Submitted', NULL, 'Not Ready', NULL, '2026-08-19 04:59:34', '2026-08-19 05:01:44'),
(2, 2, 1, 'Waiting for Acceptance', 'Not Set', NULL, 'Not Started', NULL, 'Not Ready', NULL, '2026-08-19 04:59:34', '2026-08-19 04:59:34'),
(3, 3, 3, 'Not Applied', 'Not Set', NULL, 'Not Started', NULL, 'Not Ready', NULL, '2026-08-19 04:59:34', '2026-08-19 04:59:34'),
(4, 4, 3, 'Submitted', 'Not Set', NULL, 'Not Started', NULL, 'Not Ready', NULL, '2026-08-19 04:59:34', '2026-08-19 04:59:34'),
(5, 6, NULL, 'Accepted', 'Paid', '2026-08-05', 'Submitted', NULL, 'Not Ready', NULL, '2026-08-19 04:59:34', '2026-08-19 04:59:34'),
(6, 7, 4, 'Not Applied', 'Not Set', NULL, 'Not Started', NULL, 'Not Ready', NULL, '2026-08-19 05:19:00', '2026-08-19 05:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `call_logs`
--

CREATE TABLE `call_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED NOT NULL,
  `call_date` datetime NOT NULL,
  `duration_seconds` int(11) DEFAULT NULL,
  `outcome` enum('completed','no_answer','voicemail','busy','failed') NOT NULL DEFAULT 'completed',
  `notes` text DEFAULT NULL,
  `recording_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `call_logs`
--

INSERT INTO `call_logs` (`id`, `lead_id`, `counselor_id`, `call_date`, `duration_seconds`, `outcome`, `notes`, `recording_url`, `created_at`, `updated_at`) VALUES
(1, 7, 4, '2026-09-02 09:10:59', NULL, 'completed', NULL, NULL, '2026-09-02 04:10:59', '2026-09-02 04:10:59');

-- --------------------------------------------------------

--
-- Table structure for table `clearances`
--

CREATE TABLE `clearances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `tuition_cleared` tinyint(1) NOT NULL DEFAULT 0,
  `service_dues_cleared` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `signed_off` tinyint(1) NOT NULL DEFAULT 0,
  `signed_off_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `signed_off_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communications`
--

CREATE TABLE `communications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('call','whatsapp','sms','email') NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `direction` enum('inbound','outbound') NOT NULL DEFAULT 'outbound',
  `content` text DEFAULT NULL,
  `status` enum('sent','delivered','read','failed') NOT NULL DEFAULT 'sent',
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivered_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `communications`
--

INSERT INTO `communications` (`id`, `lead_id`, `counselor_id`, `type`, `subject`, `direction`, `content`, `status`, `sent_at`, `delivered_at`, `notes`, `created_at`, `updated_at`) VALUES
(3, 7, 4, 'call', NULL, 'outbound', NULL, 'sent', '2026-09-02 04:11:00', NULL, NULL, '2026-09-02 04:11:00', '2026-09-02 04:11:00');

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL DEFAULT 'THE GUIDERS',
  `address` text DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `updated_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `company_name`, `address`, `bank_name`, `account_number`, `branch_code`, `iban`, `notes`, `terms`, `updated_by_id`, `created_at`, `updated_at`) VALUES
(1, 'THE GUIDERS', 'Plot no 44 UBL business Park Gulberg Greens Islamabad', 'UNITED BANK LIMITED', '356485762', '0446', 'PK98UNIL0109000356485762', 'The Guiders Overseas Educational Services Private limited', 'The deposit/payment must be transferred only to the company\'s designated bank account. After completing the payment, kindly share the payment receipt for verification and confirmation.', NULL, '2026-08-19 04:59:34', '2026-08-19 04:59:34');

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `created_by_id` bigint(20) UNSIGNED NOT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `cnic` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `university` varchar(255) DEFAULT NULL,
  `university_tbd` tinyint(1) NOT NULL DEFAULT 0,
  `course` varchar(255) DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `service_detail` text NOT NULL,
  `intake` varchar(255) DEFAULT NULL,
  `total_fee` decimal(12,2) DEFAULT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'PKR',
  `clauses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`clauses`)),
  `custom_terms` text DEFAULT NULL,
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requirements`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `student_id`, `created_by_id`, `guardian_name`, `cnic`, `phone`, `client_email`, `country`, `university`, `university_tbd`, `course`, `service_type`, `service_detail`, `intake`, `total_fee`, `currency`, `clauses`, `custom_terms`, `requirements`, `created_at`, `updated_at`) VALUES
(2, 7, 4, 'test', '345', '1231', NULL, 'Netherlands', NULL, 0, 'asdfd', 'University Admission & Visa Processing', 'rear', 'fa', 25000.00, 'PKR', '[\"scope\",\"payment\",\"refund\",\"confidentiality\",\"responsibility\",\"liability\",\"fakedocs\",\"law\"]', 'eatsd', '[{\"text\":\"Valid passport (minimum 6 months validity)\",\"required\":false},{\"text\":\"Academic transcripts and certificates (attested)\",\"required\":false},{\"text\":\"IELTS \\/ PTE \\/ language test result\",\"required\":false},{\"text\":\"Bank statement \\/ proof of funds\",\"required\":false},{\"text\":\"Statement of Purpose (SOP)\",\"required\":false}]', '2026-08-25 06:55:24', '2026-08-25 06:55:24'),
(3, 2, 4, 'test', '345', '12345678', NULL, 'New Zealand', 'asdf', 0, 'asdf', 'Vocational Program Placement', 'afsd', 'fa', 2500.00, 'PKR', '[\"scope\",\"payment\",\"refund\",\"confidentiality\",\"responsibility\",\"liability\",\"fakedocs\",\"law\"]', 'test', '[{\"text\":\"Valid passport (minimum 6 months validity)\",\"required\":false},{\"text\":\"Academic transcripts and certificates (attested)\",\"required\":false},{\"text\":\"IELTS \\/ PTE \\/ language test result\",\"required\":false},{\"text\":\"Bank statement \\/ proof of funds\",\"required\":false},{\"text\":\"Statement of Purpose (SOP)\",\"required\":false}]', '2026-08-25 07:02:53', '2026-08-25 07:02:53');

-- --------------------------------------------------------

--
-- Table structure for table `contract_installments`
--

CREATE TABLE `contract_installments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contract_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `due_date` date DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contract_installments`
--

INSERT INTO `contract_installments` (`id`, `contract_id`, `description`, `amount`, `due_date`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 2, 'Registration Fee', 1500.00, '2026-08-31', 0, '2026-08-25 06:55:24', '2026-08-25 06:55:24'),
(3, 3, 'Registration Fee', 100.00, '2026-08-31', 0, '2026-08-25 07:02:53', '2026-08-25 07:02:53');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `follow_ups`
--

CREATE TABLE `follow_ups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `student_name` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL DEFAULT 'Call',
  `due_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `follow_ups`
--

INSERT INTO `follow_ups` (`id`, `counselor_id`, `lead_id`, `student_name`, `action`, `due_date`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, 'Danish Farooqi', 'Call', '2026-07-19', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(2, 3, NULL, 'Sana Malik', 'Visit', '2026-07-23', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(3, 3, 1, 'Mahnoor Iqbal', 'Call', '2026-07-26', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(4, 3, 1, 'Mahnoor Iqbal', 'Call', '2026-07-25', '2026-07-20 03:05:42', '2026-07-20 03:05:42'),
(5, 4, 3, 'Areeba Khan', 'Call', '2026-08-05', '2026-08-05 03:41:43', '2026-08-05 03:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `installments`
--

CREATE TABLE `installments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT 'Installment',
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'USD',
  `due_date` date NOT NULL,
  `paid_date` date DEFAULT NULL,
  `paid_amount` decimal(12,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by_id` bigint(20) UNSIGNED NOT NULL,
  `issued_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'PKR',
  `notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `student_id`, `application_id`, `created_by_id`, `issued_date`, `due_date`, `subtotal`, `tax`, `total`, `currency`, `notes`, `terms`, `created_at`, `updated_at`) VALUES
(3, '001/AUG/2026', 5, NULL, 4, '2026-08-21', '2026-08-30', 1000.00, 10.00, 1010.00, '5000', 'The Guiders Overseas Educational Services Private limited', 'The deposit/payment must be transferred only to the company\'s designated bank account. After completing the payment, kindly share the payment receipt for verification and confirmation.', '2026-08-21 06:29:10', '2026-08-21 06:29:10'),
(4, '002/AUG/2026', 7, NULL, 4, '2026-08-21', NULL, 2500.00, 0.00, 2500.00, 'PKR', 'The Guiders Overseas Educational Services Private limited', 'The deposit/payment must be transferred only to the company\'s designated bank account. After completing the payment, kindly share the payment receipt for verification and confirmation.', '2026-08-21 06:35:28', '2026-08-21 06:35:28'),
(5, '003/AUG/2026', 7, NULL, 4, '2026-08-21', NULL, 4750000.00, 0.00, 4750000.00, 'PKR', 'The Guiders Overseas Educational Services Private limited', 'The deposit/payment must be transferred only to the company\'s designated bank account. After completing the payment, kindly share the payment receipt for verification and confirmation.', '2026-08-21 06:48:35', '2026-08-21 06:48:35'),
(6, '004/AUG/2026', 7, NULL, 4, '2026-08-21', '2026-08-21', 4750000.00, 0.00, 4750000.00, 'PKR', 'The Guiders Overseas Educational Services Private limited', 'The deposit/payment must be transferred only to the company\'s designated bank account. After completing the payment, kindly share the payment receipt for verification and confirmation.', '2026-08-21 06:48:53', '2026-08-21 06:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `item` varchar(255) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'PKR',
  `currency_value` decimal(12,2) NOT NULL,
  `exchange_rate` decimal(8,2) NOT NULL DEFAULT 1.00,
  `amount` decimal(12,2) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `item`, `currency`, `currency_value`, `exchange_rate`, `amount`, `sort_order`, `created_at`, `updated_at`) VALUES
(2, 3, 'consult', 'PKR', 1000.00, 1.00, 1000.00, 0, '2026-08-21 06:29:10', '2026-08-21 06:29:10'),
(3, 4, 'consult', 'PKR', 2500.00, 1.00, 2500.00, 0, '2026-08-21 06:35:28', '2026-08-21 06:35:28'),
(4, 5, 'consultasnt fee', 'EUR', 25000.00, 190.00, 4750000.00, 0, '2026-08-21 06:48:35', '2026-08-21 06:48:35'),
(5, 6, 'consultasnt fee', 'EUR', 25000.00, 190.00, 4750000.00, 0, '2026-08-21 06:48:53', '2026-08-21 06:48:53');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `source` varchar(255) NOT NULL DEFAULT 'Website',
  `desired_program` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `last_qualification` varchar(255) DEFAULT NULL,
  `cgpa` varchar(255) DEFAULT NULL,
  `age` tinyint(3) UNSIGNED DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `suggestion` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `highly_interested` tinyint(1) NOT NULL DEFAULT 0,
  `call_log` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`call_log`)),
  `messages` int(11) NOT NULL DEFAULT 0,
  `visits` int(11) NOT NULL DEFAULT 0,
  `funnel_stage` varchar(255) NOT NULL DEFAULT 'Lead',
  `contract_signed` tinyint(1) NOT NULL DEFAULT 0,
  `contract_date` date DEFAULT NULL,
  `payment_done` tinyint(1) NOT NULL DEFAULT 0,
  `payment_date` date DEFAULT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'USD',
  `english_test` varchar(255) NOT NULL DEFAULT 'None',
  `english_score` varchar(255) DEFAULT NULL,
  `status` enum('New','Call','Visit','Registered') DEFAULT 'New',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `counselor_id`, `name`, `contact`, `email`, `source`, `desired_program`, `country`, `budget`, `last_qualification`, `cgpa`, `age`, `city`, `suggestion`, `remarks`, `highly_interested`, `call_log`, `messages`, `visits`, `funnel_stage`, `contract_signed`, `contract_date`, `payment_done`, `payment_date`, `currency`, `english_test`, `english_score`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'Mahnoor Iqbal', 'mahnoor@example.com', 'mahnoor@example.com', 'Social Media', 'MSc AI', 'Germany', 1600.00, 'Bachelor\'s in CS', '3.6/4.0', 23, 'Lahore', 'Updated suggestion text', 'Updated remarks text', 0, NULL, 0, 0, 'Lead', 0, NULL, 0, NULL, 'USD', 'None', NULL, 'New', '2026-07-20 02:57:51', '2026-07-20 03:05:06'),
(2, 3, 'Hamza Yousuf', 'hamza.y@example.com', 'hamza.y@example.com', 'Reference', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, 'Lead', 0, NULL, 0, NULL, 'USD', 'None', NULL, 'Visit', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(3, 4, 'Areeba Khan', 'areeba@example.com', 'areeba@example.com', 'Walk-in', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '[\"2026-08-05\",\"2026-08-05\",\"2026-08-05\",\"2026-08-05\",\"2026-08-05\",\"2026-08-05\"]', 5, 8, 'Converted', 1, '2026-08-05', 1, '2026-08-05', 'USD', 'None', NULL, 'Registered', '2026-07-20 02:57:51', '2026-08-05 06:20:38'),
(5, 4, 'Jimy', '7428730894', NULL, 'Walk-in', 'dest', 'gjk', 2500.00, 'bs', '3.02', 14, 'ict', NULL, 'test', 0, '[\"2026-08-05\"]', 0, 0, 'Converted', 0, NULL, 0, NULL, 'USD', 'None', NULL, 'Registered', '2026-08-05 06:46:42', '2026-08-05 06:56:27'),
(6, 4, 'Admin', '7428730894', NULL, 'Walk-in', 'MBA', 'Canada', 2500.00, 'BBA', '3', 34, 'Toronto', NULL, 'testing', 1, '[\"2026-08-05\",\"2026-08-05\",\"2026-08-05\",\"2026-08-05\"]', 0, 1, 'Follow-up', 0, NULL, 0, NULL, 'USD', 'IELTS', '7', 'New', '2026-08-05 07:03:39', '2026-08-12 23:46:34'),
(7, 4, 'Jimy', '7428730894', NULL, 'Social Media', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '[\"2026-09-02\"]', 0, 0, 'Lead', 0, NULL, 0, NULL, 'USD', 'None', NULL, 'New', '2026-08-07 06:47:49', '2026-09-02 04:10:59');

-- --------------------------------------------------------

--
-- Table structure for table `lead_documents`
--

CREATE TABLE `lead_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int(11) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lead_documents`
--

INSERT INTO `lead_documents` (`id`, `lead_id`, `counselor_id`, `document_type`, `file_name`, `file_path`, `file_size`, `mime_type`, `notes`, `created_at`, `updated_at`) VALUES
(4, 6, 4, 'CV', 'Support.pdf', 'lead_documents/1788438409_Support.pdf', 419609, 'application/pdf', NULL, '2026-09-03 07:26:49', '2026-09-03 07:26:49');

-- --------------------------------------------------------

--
-- Table structure for table `lead_reminders`
--

CREATE TABLE `lead_reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED NOT NULL,
  `reminder_date` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','completed','snoozed') NOT NULL DEFAULT 'pending',
  `snoozed_until` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_15_000001_add_role_to_users_table', 1),
(5, '2026_07_15_000002_create_opportunities_table', 1),
(6, '2026_07_15_000003_create_leads_table', 1),
(7, '2026_07_15_000004_create_students_table', 1),
(8, '2026_07_16_000001_extend_roles_and_add_partner_id', 1),
(9, '2026_07_16_000002_create_papers_table', 1),
(10, '2026_07_16_000003_create_follow_ups_table', 1),
(11, '2026_07_16_000004_create_activity_logs_table', 1),
(12, '2026_07_20_000001_update_leads_status_and_profile_fields', 1),
(13, '2026_07_20_000002_update_follow_ups_for_leads_and_notes', 1),
(14, '2026_07_20_000003_rename_visa_stages_add_traveling', 1),
(15, '2026_07_20_000004_move_suggestion_remarks_to_leads', 1),
(16, '2026_08_05_092941_add_lead_tracking_fields_to_leads_table', 2),
(17, '2026_08_05_101017_add_followup_contract_payment_fields_to_leads_table', 3),
(18, '2026_08_05_121439_create_call_logs_table', 4),
(19, '2026_08_05_121644_create_communications_table', 4),
(20, '2026_08_05_122127_create_lead_documents_table', 5),
(21, '2026_08_06_051524_create_lead_reminders_table', 6),
(22, '2026_08_13_071651_create_opportunity_sessions_table', 7),
(23, '2026_08_19_000001_create_applications_table_and_migrate_students', 8),
(24, '2026_08_19_000002_create_installments_table', 8),
(25, '2026_08_19_000003_create_clearances_table', 8),
(26, '2026_08_19_000004_create_company_settings_table', 8),
(27, '2026_08_19_000005_create_invoices_table', 8),
(28, '2026_08_21_000000_add_due_date_to_invoices_table', 9),
(29, '2026_08_21_000001_rename_rate_quantity_columns_on_invoice_items_table', 10),
(30, '2026_08_21_000002_add_currency_to_invoice_items_table', 11),
(31, '2026_08_24_000000_create_contracts_table', 12),
(32, '2026_08_24_000001_create_tasks_table', 13),
(33, '2026_08_25_000000_add_subject_to_communications_table', 14),
(34, '2026_08_25_000001_add_email_to_leads_table', 15),
(35, '2026_09_03_000000_add_client_email_to_contracts_table', 16);

-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

CREATE TABLE `opportunities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `country` varchar(255) DEFAULT NULL,
  `type` enum('study_abroad','research_publication') NOT NULL DEFAULT 'study_abroad',
  `deadline` date NOT NULL,
  `requirements` text DEFAULT NULL,
  `official_link` varchar(255) DEFAULT NULL,
  `application_link` varchar(255) DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `guidance` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `opportunities`
--

INSERT INTO `opportunities` (`id`, `name`, `country`, `type`, `deadline`, `requirements`, `official_link`, `application_link`, `cost`, `currency`, `guidance`, `created_at`, `updated_at`) VALUES
(1, 'MSc Data Science — TU Munich', 'Germany', 'study_abroad', '2026-08-29', 'IELTS 6.5+, Bachelor\'s in CS/related, 2 reference letters, SOP', 'https://www.tum.de', 'https://www.tum.de/apply', 1200.00, 'USD', 'Application fee $75, visa fee $95, translation/attestation ~$150.\nSteps: IELTS, attested transcripts, SOP + 2 references, submit via portal, blocked account, visa appointment.', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(2, 'Erasmus Mundus Scholarship', 'EU', 'study_abroad', '2026-07-24', 'Bachelor\'s degree, IELTS 6.5, motivation letter, CV', 'https://www.eacea.ec.europa.eu', 'https://www.eacea.ec.europa.eu/apply', 400.00, 'USD', 'Mostly document prep and courier fees — scholarship covers tuition.', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(3, 'MBA — University of Toronto', 'Canada', 'study_abroad', '2026-10-14', 'GMAT 650+, 3 years work experience, IELTS 7.0, 2 essays', 'https://www.rotman.utoronto.ca', 'https://www.rotman.utoronto.ca/apply', 2500.00, 'USD', 'Application fee $200, GMAT $275, IELTS $220, study permit ~$235.', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(4, 'Journal of Applied Physics', 'USA', 'research_publication', '2026-08-03', 'Original research, max 8000 words, APA format, plagiarism <10%', 'https://publishing.aip.org/jap', 'https://www.editorialmanager.com/jap', 350.00, 'USD', 'Submission/processing fee; open-access fee extra if selected.', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(5, 'International Journal of AI Research', 'UK', 'research_publication', '2026-08-15', 'Peer-reviewed original work, max 10 pages, IEEE format', 'https://www.ijair.org', 'https://www.ijair.org/submit', 280.00, 'USD', 'Submission fee; publication fee extra if open access.', '2026-07-20 02:57:51', '2026-07-20 02:57:51');

-- --------------------------------------------------------

--
-- Table structure for table `opportunity_sessions`
--

CREATE TABLE `opportunity_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `opportunity_id` bigint(20) UNSIGNED NOT NULL,
  `session_name` varchar(255) NOT NULL,
  `university` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `papers`
--

CREATE TABLE `papers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `counselor_id` bigint(20) UNSIGNED NOT NULL,
  `opportunity_id` bigint(20) UNSIGNED NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `submitted_date` date NOT NULL,
  `status` enum('Submitted','Under Review','Revisions Requested','Accepted','Rejected') NOT NULL DEFAULT 'Submitted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `papers`
--

INSERT INTO `papers` (`id`, `counselor_id`, `opportunity_id`, `author_name`, `submitted_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 4, 'Owais Anwar', '2026-05-24', 'Under Review', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(2, 3, 5, 'Nimra Javed', '2026-06-15', 'Submitted', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(3, 4, 5, 'Zara Ahmed', '2026-06-29', 'Under Review', '2026-07-20 02:57:51', '2026-07-20 02:57:51');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1G4Nf6gWvgL803cF5dQnWuW8sbmFDWAantq44Tvt', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ4Qmg2TnAySmlDWndBSzRuYmIzRkNrQlFuY25PVkdQS2xYeFhNNVd0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sZWFkc1wvZm9sbG93LXVwcyIsInJvdXRlIjoibGVhZHMuZm9sbG93LXVwcyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo0fQ==', 1788440668);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `counselor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `partner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `counselor_id`, `partner_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 6, 4, NULL, 'Zara Ahmed', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(2, NULL, 3, NULL, 'Danish Farooqi', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(3, NULL, 3, NULL, 'Sana Malik', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(4, NULL, NULL, 5, 'Rayyan Qureshi', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(5, NULL, 4, NULL, 'Areeba Khan', '2026-08-05 04:40:25', '2026-08-05 04:40:25'),
(6, NULL, 4, NULL, 'Areeba Khan', '2026-08-05 06:20:38', '2026-08-05 06:27:35'),
(7, NULL, 4, NULL, 'Jimy', '2026-08-05 06:56:27', '2026-08-05 06:56:27');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `assigned_to_id` bigint(20) UNSIGNED NOT NULL,
  `created_by_id` bigint(20) UNSIGNED NOT NULL,
  `due_date` date DEFAULT NULL,
  `due_time` time DEFAULT NULL,
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `done` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','counselor','processing_team','student','partner') DEFAULT 'student',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Hassan Raza', 'admin@theguiders.com', 'admin', '2026-07-20 02:57:51', '$2y$12$eiygocxYolvf/oK2e3lyUOZAygf4qPISm/0ZHt07pgZROg9mFRSIS', 'Fb2syIoE9c', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(2, 'Fatima Sheikh', 'ops@theguiders.com', 'processing_team', '2026-07-20 02:57:51', '$2y$12$eiygocxYolvf/oK2e3lyUOZAygf4qPISm/0ZHt07pgZROg9mFRSIS', 'D1H5doGANX', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(3, 'Ayesha Siddiqui', 'ayesha@theguiders.com', 'counselor', '2026-07-20 02:57:51', '$2y$12$eiygocxYolvf/oK2e3lyUOZAygf4qPISm/0ZHt07pgZROg9mFRSIS', 'b3wU1eRRWP', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(4, 'Bilal Chaudhry', 'bilal@theguiders.com', 'counselor', '2026-07-20 02:57:51', '$2y$12$eiygocxYolvf/oK2e3lyUOZAygf4qPISm/0ZHt07pgZROg9mFRSIS', 'N3SZjcp7x4Tc6IYl3sCozIE42He6EiihHFN16nYyNTxaDGikfL6e20lLoB5W', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(5, 'Horizon Education Partners', 'partner@theguiders.com', 'partner', '2026-07-20 02:57:51', '$2y$12$eiygocxYolvf/oK2e3lyUOZAygf4qPISm/0ZHt07pgZROg9mFRSIS', 'j6iJQ1I3S6', '2026-07-20 02:57:51', '2026-07-20 02:57:51'),
(6, 'Zara Ahmed', 'student@theguiders.com', 'student', '2026-07-20 02:57:51', '$2y$12$eiygocxYolvf/oK2e3lyUOZAygf4qPISm/0ZHt07pgZROg9mFRSIS', '5GmuWyMA5q', '2026-07-20 02:57:51', '2026-07-20 02:57:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applications_opportunity_id_foreign` (`opportunity_id`),
  ADD KEY `applications_student_id_index` (`student_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `call_logs_lead_id_call_date_index` (`lead_id`,`call_date`),
  ADD KEY `call_logs_counselor_id_index` (`counselor_id`);

--
-- Indexes for table `clearances`
--
ALTER TABLE `clearances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clearances_application_id_foreign` (`application_id`),
  ADD KEY `clearances_signed_off_by_id_foreign` (`signed_off_by_id`);

--
-- Indexes for table `communications`
--
ALTER TABLE `communications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `communications_lead_id_sent_at_index` (`lead_id`,`sent_at`),
  ADD KEY `communications_counselor_id_index` (`counselor_id`),
  ADD KEY `communications_type_index` (`type`);

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_settings_updated_by_id_foreign` (`updated_by_id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contracts_student_id_foreign` (`student_id`),
  ADD KEY `contracts_created_by_id_index` (`created_by_id`);

--
-- Indexes for table `contract_installments`
--
ALTER TABLE `contract_installments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_installments_contract_id_foreign` (`contract_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `follow_ups`
--
ALTER TABLE `follow_ups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follow_ups_counselor_id_foreign` (`counselor_id`),
  ADD KEY `follow_ups_lead_id_foreign` (`lead_id`);

--
-- Indexes for table `installments`
--
ALTER TABLE `installments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `installments_application_id_due_date_index` (`application_id`,`due_date`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_student_id_foreign` (`student_id`),
  ADD KEY `invoices_application_id_foreign` (`application_id`),
  ADD KEY `invoices_created_by_id_index` (`created_by_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_counselor_id_foreign` (`counselor_id`);

--
-- Indexes for table `lead_documents`
--
ALTER TABLE `lead_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_documents_lead_id_document_type_index` (`lead_id`,`document_type`),
  ADD KEY `lead_documents_counselor_id_index` (`counselor_id`);

--
-- Indexes for table `lead_reminders`
--
ALTER TABLE `lead_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lead_reminders_lead_id_reminder_date_index` (`lead_id`,`reminder_date`),
  ADD KEY `lead_reminders_counselor_id_index` (`counselor_id`),
  ADD KEY `lead_reminders_status_index` (`status`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `opportunity_sessions`
--
ALTER TABLE `opportunity_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `opportunity_sessions_opportunity_id_foreign` (`opportunity_id`);

--
-- Indexes for table `papers`
--
ALTER TABLE `papers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `papers_counselor_id_foreign` (`counselor_id`),
  ADD KEY `papers_opportunity_id_foreign` (`opportunity_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `students_user_id_foreign` (`user_id`),
  ADD KEY `students_counselor_id_foreign` (`counselor_id`),
  ADD KEY `students_partner_id_foreign` (`partner_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_assigned_to_id_index` (`assigned_to_id`),
  ADD KEY `tasks_created_by_id_index` (`created_by_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `call_logs`
--
ALTER TABLE `call_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clearances`
--
ALTER TABLE `clearances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `communications`
--
ALTER TABLE `communications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contract_installments`
--
ALTER TABLE `contract_installments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_ups`
--
ALTER TABLE `follow_ups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `installments`
--
ALTER TABLE `installments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lead_documents`
--
ALTER TABLE `lead_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lead_reminders`
--
ALTER TABLE `lead_reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `opportunity_sessions`
--
ALTER TABLE `opportunity_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `papers`
--
ALTER TABLE `papers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_opportunity_id_foreign` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `applications_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `call_logs`
--
ALTER TABLE `call_logs`
  ADD CONSTRAINT `call_logs_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `call_logs_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clearances`
--
ALTER TABLE `clearances`
  ADD CONSTRAINT `clearances_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `clearances_signed_off_by_id_foreign` FOREIGN KEY (`signed_off_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `communications`
--
ALTER TABLE `communications`
  ADD CONSTRAINT `communications_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `communications_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD CONSTRAINT `company_settings_updated_by_id_foreign` FOREIGN KEY (`updated_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contracts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contract_installments`
--
ALTER TABLE `contract_installments`
  ADD CONSTRAINT `contract_installments_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `follow_ups`
--
ALTER TABLE `follow_ups`
  ADD CONSTRAINT `follow_ups_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `follow_ups_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `installments`
--
ALTER TABLE `installments`
  ADD CONSTRAINT `installments_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `invoices_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lead_documents`
--
ALTER TABLE `lead_documents`
  ADD CONSTRAINT `lead_documents_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_documents_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lead_reminders`
--
ALTER TABLE `lead_reminders`
  ADD CONSTRAINT `lead_reminders_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_reminders_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `opportunity_sessions`
--
ALTER TABLE `opportunity_sessions`
  ADD CONSTRAINT `opportunity_sessions_opportunity_id_foreign` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `papers`
--
ALTER TABLE `papers`
  ADD CONSTRAINT `papers_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `papers_opportunity_id_foreign` FOREIGN KEY (`opportunity_id`) REFERENCES `opportunities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_counselor_id_foreign` FOREIGN KEY (`counselor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_id_foreign` FOREIGN KEY (`assigned_to_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_created_by_id_foreign` FOREIGN KEY (`created_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
