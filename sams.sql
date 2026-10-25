-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2026 at 08:53 AM
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
-- Database: `sams`
--

-- --------------------------------------------------------

--
-- Table structure for table `board_membership`
--

CREATE TABLE `board_membership` (
  `membership_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `board_id` int(11) NOT NULL,
  `membership_status` varchar(30) DEFAULT NULL,
  `member_role` varchar(50) DEFAULT NULL,
  `confirmed` tinyint(1) DEFAULT 0,
  `offer_sent_date_time` datetime DEFAULT NULL,
  `acknowledgement_date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `course_title` varchar(150) NOT NULL,
  `credits` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deadlines`
--

CREATE TABLE `deadlines` (
  `cycle_id` int(11) NOT NULL,
  `deadline_id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `deadline_type` varchar(50) DEFAULT NULL,
  `due_date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `examination`
--

CREATE TABLE `examination` (
  `examination_id` int(11) NOT NULL,
  `examination_name` varchar(100) NOT NULL,
  `cycle_id` int(11) NOT NULL,
  `exam_date` date DEFAULT NULL,
  `exam_time` time DEFAULT NULL,
  `venue` varchar(150) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `examination_officer`
--

CREATE TABLE `examination_officer` (
  `user_id` int(11) NOT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `examination_paper`
--

CREATE TABLE `examination_paper` (
  `paper_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `paper_title` varchar(150) NOT NULL,
  `paper_type` varchar(50) DEFAULT NULL,
  `marking_scheme_file` varchar(255) DEFAULT NULL,
  `total_marks` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_cycle`
--

CREATE TABLE `exam_cycle` (
  `cycle_id` int(11) NOT NULL,
  `cycle_name` varchar(100) NOT NULL,
  `academic_year` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `managed_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_cycle`
--

INSERT INTO `exam_cycle` (`cycle_id`, `cycle_name`, `academic_year`, `start_date`, `end_date`, `status`, `managed_by`) VALUES
(2, 'semester 1 examination', 2023, '2026-02-05', '2026-12-23', 'Ongoing', 1);

-- --------------------------------------------------------

--
-- Table structure for table `exception_request`
--

CREATE TABLE `exception_request` (
  `exception_id` int(11) NOT NULL,
  `cycle_id` int(11) NOT NULL,
  `deadline_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `decision_date` date DEFAULT NULL,
  `decision_by` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `new_deadline` datetime DEFAULT NULL,
  `support_doc_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `final_report`
--

CREATE TABLE `final_report` (
  `final_report_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `report_file` varchar(255) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `submit_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `final_submission`
--

CREATE TABLE `final_submission` (
  `submission_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `submission_date_time` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `submitted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `user_id` int(11) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `building_no` varchar(20) DEFAULT NULL,
  `room_no` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moderation_board`
--

CREATE TABLE `moderation_board` (
  `board_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `moderation_doc_mode` varchar(50) DEFAULT NULL,
  `board_number` int(11) DEFAULT NULL,
  `submitted_date` date DEFAULT NULL,
  `review_date` date DEFAULT NULL,
  `moderation_status` varchar(30) DEFAULT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moderation_report`
--

CREATE TABLE `moderation_report` (
  `report_id` int(11) NOT NULL,
  `board_id` int(11) NOT NULL,
  `report_file` varchar(255) DEFAULT NULL,
  `moderation_status` varchar(30) DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `recommendations` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moderator`
--

CREATE TABLE `moderator` (
  `user_id` int(11) NOT NULL,
  `internal_external` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moderator_assignment`
--

CREATE TABLE `moderator_assignment` (
  `assignment_id` int(11) NOT NULL,
  `moderator_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `assignment_date` date DEFAULT NULL,
  `acceptance_status` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paper_session`
--

CREATE TABLE `paper_session` (
  `paper_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paper_setter`
--

CREATE TABLE `paper_setter` (
  `setter_id` int(11) NOT NULL,
  `lecturer_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `assigned_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revision`
--

CREATE TABLE `revision` (
  `revision_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `revision_number` int(11) NOT NULL,
  `revision_date` date DEFAULT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scrutiny_board`
--

CREATE TABLE `scrutiny_board` (
  `board_id` int(11) NOT NULL,
  `board_name` varchar(150) NOT NULL,
  `scheduled_date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scrutiny_session`
--

CREATE TABLE `scrutiny_session` (
  `session_id` int(11) NOT NULL,
  `board_id` int(11) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `exam_paper` varchar(150) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email_id` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `full_name`, `email_id`, `password`, `role`) VALUES
(1, 'Admin User', 'admin@sams.com', '$2y$10$EFUh.eREuszr2/RIN..UoeZ0Rh7.YFZDCY3lc5LbSPRdxE4Eh6yAS', 'Admin'),
(2, 'Kaushani Rathnayaka', 'rathnayakakaushani2005@gmail.com', '$2y$10$6Qqa/FUTyUXfoGhiwZu/ue0odOkcW3.WP6J6A69SdFqWanNM2lFvu', 'Lecturer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `board_membership`
--
ALTER TABLE `board_membership`
  ADD PRIMARY KEY (`membership_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`board_id`),
  ADD KEY `board_id` (`board_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `deadlines`
--
ALTER TABLE `deadlines`
  ADD PRIMARY KEY (`cycle_id`,`deadline_id`);

--
-- Indexes for table `examination`
--
ALTER TABLE `examination`
  ADD PRIMARY KEY (`examination_id`),
  ADD KEY `cycle_id` (`cycle_id`),
  ADD KEY `fk_examination_course` (`course_id`);

--
-- Indexes for table `examination_officer`
--
ALTER TABLE `examination_officer`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `examination_paper`
--
ALTER TABLE `examination_paper`
  ADD PRIMARY KEY (`paper_id`),
  ADD KEY `exam_id` (`exam_id`);

--
-- Indexes for table `exam_cycle`
--
ALTER TABLE `exam_cycle`
  ADD PRIMARY KEY (`cycle_id`),
  ADD KEY `fk_exam_cycle_manager` (`managed_by`);

--
-- Indexes for table `exception_request`
--
ALTER TABLE `exception_request`
  ADD PRIMARY KEY (`exception_id`),
  ADD KEY `cycle_id` (`cycle_id`,`deadline_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `decision_by` (`decision_by`);

--
-- Indexes for table `final_report`
--
ALTER TABLE `final_report`
  ADD PRIMARY KEY (`final_report_id`),
  ADD UNIQUE KEY `paper_id` (`paper_id`);

--
-- Indexes for table `final_submission`
--
ALTER TABLE `final_submission`
  ADD PRIMARY KEY (`submission_id`),
  ADD UNIQUE KEY `paper_id` (`paper_id`),
  ADD KEY `submitted_by` (`submitted_by`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `moderation_board`
--
ALTER TABLE `moderation_board`
  ADD PRIMARY KEY (`board_id`),
  ADD UNIQUE KEY `paper_id` (`paper_id`);

--
-- Indexes for table `moderation_report`
--
ALTER TABLE `moderation_report`
  ADD PRIMARY KEY (`report_id`),
  ADD UNIQUE KEY `board_id` (`board_id`);

--
-- Indexes for table `moderator`
--
ALTER TABLE `moderator`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `moderator_assignment`
--
ALTER TABLE `moderator_assignment`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `moderator_id` (`moderator_id`),
  ADD KEY `paper_id` (`paper_id`);

--
-- Indexes for table `paper_session`
--
ALTER TABLE `paper_session`
  ADD PRIMARY KEY (`paper_id`,`session_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `paper_setter`
--
ALTER TABLE `paper_setter`
  ADD PRIMARY KEY (`setter_id`),
  ADD UNIQUE KEY `lecturer_id` (`lecturer_id`,`paper_id`),
  ADD KEY `paper_id` (`paper_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `revision`
--
ALTER TABLE `revision`
  ADD PRIMARY KEY (`revision_id`),
  ADD UNIQUE KEY `paper_id` (`paper_id`,`revision_number`);

--
-- Indexes for table `scrutiny_board`
--
ALTER TABLE `scrutiny_board`
  ADD PRIMARY KEY (`board_id`);

--
-- Indexes for table `scrutiny_session`
--
ALTER TABLE `scrutiny_session`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `board_id` (`board_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_id` (`email_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `board_membership`
--
ALTER TABLE `board_membership`
  MODIFY `membership_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `examination`
--
ALTER TABLE `examination`
  MODIFY `examination_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `examination_paper`
--
ALTER TABLE `examination_paper`
  MODIFY `paper_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_cycle`
--
ALTER TABLE `exam_cycle`
  MODIFY `cycle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exception_request`
--
ALTER TABLE `exception_request`
  MODIFY `exception_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `final_report`
--
ALTER TABLE `final_report`
  MODIFY `final_report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `final_submission`
--
ALTER TABLE `final_submission`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moderation_board`
--
ALTER TABLE `moderation_board`
  MODIFY `board_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moderation_report`
--
ALTER TABLE `moderation_report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `moderator_assignment`
--
ALTER TABLE `moderator_assignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paper_setter`
--
ALTER TABLE `paper_setter`
  MODIFY `setter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revision`
--
ALTER TABLE `revision`
  MODIFY `revision_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scrutiny_board`
--
ALTER TABLE `scrutiny_board`
  MODIFY `board_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scrutiny_session`
--
ALTER TABLE `scrutiny_session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `board_membership`
--
ALTER TABLE `board_membership`
  ADD CONSTRAINT `board_membership_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `board_membership_ibfk_2` FOREIGN KEY (`board_id`) REFERENCES `scrutiny_board` (`board_id`);

--
-- Constraints for table `deadlines`
--
ALTER TABLE `deadlines`
  ADD CONSTRAINT `deadlines_ibfk_1` FOREIGN KEY (`cycle_id`) REFERENCES `exam_cycle` (`cycle_id`);

--
-- Constraints for table `examination`
--
ALTER TABLE `examination`
  ADD CONSTRAINT `examination_ibfk_1` FOREIGN KEY (`cycle_id`) REFERENCES `exam_cycle` (`cycle_id`),
  ADD CONSTRAINT `fk_examination_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `examination_officer`
--
ALTER TABLE `examination_officer`
  ADD CONSTRAINT `examination_officer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `examination_paper`
--
ALTER TABLE `examination_paper`
  ADD CONSTRAINT `examination_paper_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `examination` (`examination_id`);

--
-- Constraints for table `exam_cycle`
--
ALTER TABLE `exam_cycle`
  ADD CONSTRAINT `fk_exam_cycle_manager` FOREIGN KEY (`managed_by`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `exception_request`
--
ALTER TABLE `exception_request`
  ADD CONSTRAINT `exception_request_ibfk_1` FOREIGN KEY (`cycle_id`,`deadline_id`) REFERENCES `deadlines` (`cycle_id`, `deadline_id`),
  ADD CONSTRAINT `exception_request_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `exception_request_ibfk_3` FOREIGN KEY (`decision_by`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `final_report`
--
ALTER TABLE `final_report`
  ADD CONSTRAINT `final_report_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `examination_paper` (`paper_id`);

--
-- Constraints for table `final_submission`
--
ALTER TABLE `final_submission`
  ADD CONSTRAINT `final_submission_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `examination_paper` (`paper_id`),
  ADD CONSTRAINT `final_submission_ibfk_2` FOREIGN KEY (`submitted_by`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD CONSTRAINT `lecturer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `moderation_board`
--
ALTER TABLE `moderation_board`
  ADD CONSTRAINT `moderation_board_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `examination_paper` (`paper_id`);

--
-- Constraints for table `moderation_report`
--
ALTER TABLE `moderation_report`
  ADD CONSTRAINT `moderation_report_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `moderation_board` (`board_id`);

--
-- Constraints for table `moderator`
--
ALTER TABLE `moderator`
  ADD CONSTRAINT `moderator_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `moderator_assignment`
--
ALTER TABLE `moderator_assignment`
  ADD CONSTRAINT `moderator_assignment_ibfk_1` FOREIGN KEY (`moderator_id`) REFERENCES `moderator` (`user_id`),
  ADD CONSTRAINT `moderator_assignment_ibfk_2` FOREIGN KEY (`paper_id`) REFERENCES `examination_paper` (`paper_id`);

--
-- Constraints for table `paper_session`
--
ALTER TABLE `paper_session`
  ADD CONSTRAINT `paper_session_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `examination_paper` (`paper_id`),
  ADD CONSTRAINT `paper_session_ibfk_2` FOREIGN KEY (`session_id`) REFERENCES `scrutiny_session` (`session_id`);

--
-- Constraints for table `paper_setter`
--
ALTER TABLE `paper_setter`
  ADD CONSTRAINT `paper_setter_ibfk_1` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturer` (`user_id`),
  ADD CONSTRAINT `paper_setter_ibfk_2` FOREIGN KEY (`paper_id`) REFERENCES `examination_paper` (`paper_id`);

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk_password_resets_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `revision`
--
ALTER TABLE `revision`
  ADD CONSTRAINT `revision_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `examination_paper` (`paper_id`);

--
-- Constraints for table `scrutiny_session`
--
ALTER TABLE `scrutiny_session`
  ADD CONSTRAINT `scrutiny_session_ibfk_1` FOREIGN KEY (`board_id`) REFERENCES `scrutiny_board` (`board_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
