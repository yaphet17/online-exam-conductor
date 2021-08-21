-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2021 at 04:38 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onec`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `username` varchar(25) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`username`, `password`, `email`) VALUES
('admin', '$2y$10$hnoKHVNnYCtzHiri9YDACeK3WOwtpjoaXtpMUtMRS/.kEfH7.yzUW', 'reciever@localhost');

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `answerId` int(6) NOT NULL,
  `examId` int(6) NOT NULL,
  `candidateId` varchar(25) NOT NULL,
  `questionId` int(6) NOT NULL,
  `answerOption` enum('option1','option2','option3','option4','option5') NOT NULL,
  `status` enum('right','wrong','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `candidateId` varchar(25) NOT NULL,
  `password` varchar(256) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `sex` char(1) NOT NULL,
  `candidateImage` varchar(500) NOT NULL,
  `registrationDate` datetime NOT NULL,
  `sectionId` int(6) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`candidateId`, `password`, `firstName`, `lastName`, `sex`, `candidateImage`, `registrationDate`, `sectionId`, `email`) VALUES
('@chandler', '$2y$10$h4SHU9VzuibQeNFEZNSuDeK5RSzccpN5DUqYIxjrFwN74Ud9xttQq', 'Chandler', 'Bing', 'm', '../candidate-image/611954eecee888.17092311.jpg', '2021-08-15 07:54:55', 2, 'reciever@localhost'),
('@joey', '$2y$10$sovWAl228EaQ7axpr3qVUOONhT0tp1XkX/HoJAGrDrz6Zge/TEn12', 'Joey', 'Tribbiani', 'm', '../candidate-image/6118cb2f835a81.42559988.jpg', '2021-08-15 10:07:11', 1, 'reciever@localhost'),
('@monica', '$2y$10$FIBi7/DfkYVOLZilnxpXKeotg6oqlBZ7paU0hxszMOesm1oCSdRVe', 'Monica', 'Geller', 'f', '../candidate-image/6119558a5d7b61.66935625.jpg', '2021-08-15 07:57:30', 2, 'reciever@localhost'),
('@rachel', '$2y$10$sdZi5S4yWSIOZRAlAIKU0.f6e8WXlD.aOmHsCU5lTLxFEwSES/2K2', 'Rachel', 'Green', 'f', '../candidate-image/611011f2d1f313.09417497.jpg', '2021-08-08 07:18:42', 1, 'reciever@localhost'),
('@ross', '$2y$10$qjbJ7dwOY9xDaAEiVpxozeB7skiiRq6GgiS74RRezbt7NC6YBaB.C', 'Ross', 'Geller', 'm', '../candidate-image/6119555e5e41c4.01910396.jpg', '2021-08-15 07:56:46', 2, 'reciever@localhost');

-- --------------------------------------------------------

--
-- Table structure for table `conductor`
--

CREATE TABLE `conductor` (
  `username` varchar(25) NOT NULL,
  `password` varchar(256) NOT NULL,
  `prefix` enum('Mr.','Mrs.','Mr.','Dr.','Prof.') NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `role` varchar(200) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `conductor`
--

INSERT INTO `conductor` (`username`, `password`, `prefix`, `firstName`, `lastName`, `role`, `email`) VALUES
('@Yafet', '$2y$10$cxoCXU2p0.nnywsBdcerxOiazAhEPphsdlrnAnxZfkJuQiHDKH.62', 'Mr.', 'Yafet', 'Berhanu', 'Instructor', 'reciever@localhost');

-- --------------------------------------------------------

--
-- Table structure for table `examenrollment`
--

CREATE TABLE `examenrollment` (
  `examEnrollmentId` int(6) NOT NULL,
  `candidateId` varchar(25) NOT NULL,
  `examId` int(6) NOT NULL,
  `attendanceStatus` enum('attending','attended','notattended','dispelled','leaved') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `examination`
--

CREATE TABLE `examination` (
  `examId` int(6) NOT NULL,
  `conductorId` varchar(25) NOT NULL,
  `examTitle` varchar(500) NOT NULL,
  `examInstruction` varchar(1000) NOT NULL,
  `examCreationDate` datetime NOT NULL,
  `examDateTime` datetime NOT NULL,
  `examDuration` time NOT NULL,
  `totalQuestion` int(4) NOT NULL,
  `marksPerRightAnswer` int(3) NOT NULL,
  `marksPerWrongAnswer` int(3) NOT NULL,
  `examCode` varchar(7) NOT NULL,
  `examStatus` enum('created','started','completed','canceled','suspended') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `examtoken`
--

CREATE TABLE `examtoken` (
  `id` int(6) NOT NULL,
  `candidateId` varchar(25) NOT NULL,
  `examId` int(6) NOT NULL,
  `token` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mark`
--

CREATE TABLE `mark` (
  `markId` int(6) NOT NULL,
  `candidateId` varchar(25) NOT NULL,
  `examId` int(6) NOT NULL,
  `maximumMark` int(3) NOT NULL,
  `obtainedMark` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `option`
--

CREATE TABLE `option` (
  `optionId` int(6) NOT NULL,
  `questionId` int(6) NOT NULL,
  `optionNumber` int(1) NOT NULL,
  `optionTitle` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `questionId` int(6) NOT NULL,
  `examId` int(6) NOT NULL,
  `questionTitle` varchar(3000) NOT NULL,
  `answerOption` enum('option1','option2','option3','option4','option5') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `sectionId` int(6) NOT NULL,
  `sectionName` varchar(9) NOT NULL,
  `academicYear` int(1) NOT NULL,
  `department` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`sectionId`, `sectionName`, `academicYear`, `department`) VALUES
(1, 'Section 1', 1, 'Pre-Engineering'),
(2, 'Section 2', 1, 'Pre-Engineering'),
(3, 'Section 3', 1, 'Pre-Engineering'),
(4, 'Section 4', 1, 'Pre-Engineering'),
(5, 'Section 1', 2, 'Electrical school');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answerId`),
  ADD KEY `answerCandidateId` (`candidateId`),
  ADD KEY `answerQuestionId` (`questionId`),
  ADD KEY `answerExamId` (`examId`);

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`candidateId`),
  ADD KEY `sectionId` (`sectionId`);

--
-- Indexes for table `conductor`
--
ALTER TABLE `conductor`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `examenrollment`
--
ALTER TABLE `examenrollment`
  ADD PRIMARY KEY (`examEnrollmentId`),
  ADD KEY `examId` (`examId`),
  ADD KEY `examenrollment_ibfk_1` (`candidateId`);

--
-- Indexes for table `examination`
--
ALTER TABLE `examination`
  ADD PRIMARY KEY (`examId`),
  ADD KEY `examination_ibfk_1` (`conductorId`);

--
-- Indexes for table `examtoken`
--
ALTER TABLE `examtoken`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidateId` (`candidateId`),
  ADD KEY `examId` (`examId`);

--
-- Indexes for table `mark`
--
ALTER TABLE `mark`
  ADD PRIMARY KEY (`markId`),
  ADD KEY `examId` (`examId`),
  ADD KEY `candidateId` (`candidateId`);

--
-- Indexes for table `option`
--
ALTER TABLE `option`
  ADD PRIMARY KEY (`optionId`),
  ADD KEY `questionId` (`questionId`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`questionId`),
  ADD KEY `questionExamId` (`examId`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`sectionId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `answerId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `examenrollment`
--
ALTER TABLE `examenrollment`
  MODIFY `examEnrollmentId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `examination`
--
ALTER TABLE `examination`
  MODIFY `examId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `examtoken`
--
ALTER TABLE `examtoken`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `mark`
--
ALTER TABLE `mark`
  MODIFY `markId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `option`
--
ALTER TABLE `option`
  MODIFY `optionId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `questionId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `sectionId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`candidateId`) REFERENCES `candidate` (`candidateId`) ON DELETE CASCADE,
  ADD CONSTRAINT `answer_ibfk_3` FOREIGN KEY (`examId`) REFERENCES `examination` (`examId`) ON DELETE CASCADE,
  ADD CONSTRAINT `answer_ibfk_4` FOREIGN KEY (`questionId`) REFERENCES `question` (`questionId`) ON DELETE CASCADE;

--
-- Constraints for table `candidate`
--
ALTER TABLE `candidate`
  ADD CONSTRAINT `candidate_ibfk_1` FOREIGN KEY (`sectionId`) REFERENCES `section` (`sectionId`) ON DELETE NO ACTION;

--
-- Constraints for table `examenrollment`
--
ALTER TABLE `examenrollment`
  ADD CONSTRAINT `examenrollment_ibfk_1` FOREIGN KEY (`candidateId`) REFERENCES `candidate` (`candidateId`) ON DELETE CASCADE,
  ADD CONSTRAINT `examenrollment_ibfk_2` FOREIGN KEY (`examId`) REFERENCES `examination` (`examId`) ON DELETE CASCADE;

--
-- Constraints for table `examination`
--
ALTER TABLE `examination`
  ADD CONSTRAINT `examination_ibfk_1` FOREIGN KEY (`conductorId`) REFERENCES `conductor` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `examtoken`
--
ALTER TABLE `examtoken`
  ADD CONSTRAINT `examtoken_ibfk_1` FOREIGN KEY (`candidateId`) REFERENCES `candidate` (`candidateId`) ON DELETE NO ACTION,
  ADD CONSTRAINT `examtoken_ibfk_2` FOREIGN KEY (`examId`) REFERENCES `examination` (`examId`) ON DELETE NO ACTION;

--
-- Constraints for table `mark`
--
ALTER TABLE `mark`
  ADD CONSTRAINT `mark_ibfk_1` FOREIGN KEY (`examId`) REFERENCES `examination` (`examId`) ON DELETE NO ACTION,
  ADD CONSTRAINT `mark_ibfk_2` FOREIGN KEY (`candidateId`) REFERENCES `candidate` (`candidateId`) ON DELETE CASCADE;

--
-- Constraints for table `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `option_ibfk_1` FOREIGN KEY (`questionId`) REFERENCES `question` (`questionId`) ON DELETE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`examId`) REFERENCES `examination` (`examId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
