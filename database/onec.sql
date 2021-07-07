-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2021 at 03:42 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

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
('admin', '$2y$10$YhXNJ4kP9kQgIozyBzide.1lpOMHC2/uiELA2Qh7Rg34KA3zpAcHy', 'reciever@localhost');

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
  `candidateId` varchar(12) NOT NULL,
  `password` varchar(256) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `sex` char(1) NOT NULL,
  `candidateImage` varchar(500) NOT NULL,
  `registrationDate` datetime NOT NULL,
  `sectionId` int(6) NOT NULL,
  `email` varchar(50) NOT NULL,
  `verificationCode` varchar(7) NOT NULL,
  `verificationStatus` enum('verified','unverified','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`candidateId`, `password`, `firstName`, `lastName`, `sex`, `candidateImage`, `registrationDate`, `sectionId`, `email`, `verificationCode`, `verificationStatus`) VALUES
('ugr/17975/11', '$2y$10$Ltgm265WKR9h9WwLR18loePiDzLoi/bLeUGux97u./t9W4yXgX9Xu', 'Yafet', 'Abera', 'm', 'candidate-image/60e3f9bf544a58.58419377.jpg', '2021-07-06 08:35:43', 2, 'abushberhanu5@gmail.com', '2212a94', 'unverified'),
('ugr/18884/11', '$2y$10$RuM4phE6hvP/1TrDZgzXJuRCEZu5vbZarOfVKiZRnjJoB.Q1calTu', 'Yared', 'fmmfu', 'm', 'candidate-image/60e2b1ae4c8d38.78159019.jpg', '2021-07-05 09:15:58', 1, 'reciever@localhost', '27e088f', 'unverified'),
('yafet123', '$2y$10$wrs0mz/qeFwR4qLiGw7s/OD7WDBua1U0D5XihUGrjFnUWtGsgpWjC', 'Yafet', 'Assefa', 'm', 'candidate-image/60e586fc74b484.85959474.jpg', '2021-07-07 12:50:36', 2, 'yafetberhanu3@gmail.com', '1086ce7', 'unverified'),
('yaredabate', '$2y$10$0PGqmYpsHkyNSCAx.tML/e/rGABG.xmwJiDJfrkNSJTnehnhs/fBi', 'yared', 'abate', 'm', 'candidate-image/60e5940ac2a092.79396867.jpg', '2021-07-07 01:46:18', 3, 'abushberhanu5@gmail.com', '125e1fa', 'unverified');

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
  `email` varchar(50) NOT NULL,
  `verificationCode` varchar(7) NOT NULL,
  `verificationStatus` enum('verified','unverified','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `conductor`
--

INSERT INTO `conductor` (`username`, `password`, `prefix`, `firstName`, `lastName`, `role`, `email`, `verificationCode`, `verificationStatus`) VALUES
('@Nemeraa', '123', 'Mr.', 'Yared', 'mmm', 'dfj', 'reciever@localhost', 'c5c9935', 'unverified'),
('yafet123', '$2y$10$krRSj2c0xN0vl2zpumnrRuFGAdVhqIgqsu/yiukrblBqSNvt1vxuG', 'Mrs.', 'mmm', 'mmm', 'dfj', 'reciever@localhost', 'dca1092', 'unverified');

-- --------------------------------------------------------

--
-- Table structure for table `examenrollment`
--

CREATE TABLE `examenrollment` (
  `examEnrollmentId` int(6) NOT NULL,
  `candidateId` varchar(25) NOT NULL,
  `examId` int(6) NOT NULL,
  `attendanceStatus` enum('attended','notattended','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `examenrollment`
--

INSERT INTO `examenrollment` (`examEnrollmentId`, `candidateId`, `examId`, `attendanceStatus`) VALUES
(6, 'ugr/18884/11', 1, 'notattended'),
(7, 'ugr/17975/11', 1, 'notattended'),
(8, 'ugr/17975/11', 1, 'notattended'),
(9, 'yafet123', 1, 'notattended'),
(10, 'yaredabate', 1, 'notattended');

-- --------------------------------------------------------

--
-- Table structure for table `examination`
--

CREATE TABLE `examination` (
  `examId` int(6) NOT NULL,
  `conductorId` varchar(25) NOT NULL,
  `examTitle` varchar(500) NOT NULL,
  `examCreationDate` datetime NOT NULL,
  `examDateTime` datetime NOT NULL,
  `examDuration` time NOT NULL,
  `totalQuestion` int(4) NOT NULL,
  `marksPerRightAnswer` int(3) NOT NULL,
  `marksPerWrongAnswer` int(3) NOT NULL,
  `examCode` varchar(7) NOT NULL,
  `examStatus` enum('created','started','completed','canceled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `examination`
--

INSERT INTO `examination` (`examId`, `conductorId`, `examTitle`, `examCreationDate`, `examDateTime`, `examDuration`, `totalQuestion`, `marksPerRightAnswer`, `marksPerWrongAnswer`, `examCode`, `examStatus`) VALUES
(1, '@Nemeraa', 'exam 1', '2021-07-05 09:42:55', '2021-07-02 00:42:44', '02:00:00', 10, 1, 0, 'a3c664c', 'created');

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

--
-- Dumping data for table `option`
--

INSERT INTO `option` (`optionId`, `questionId`, `optionNumber`, `optionTitle`) VALUES
(26, 6, 1, 'aaaa'),
(27, 6, 2, 'aa'),
(28, 7, 1, 'aa'),
(29, 7, 2, 'aa'),
(30, 6, 1, 'aa'),
(31, 6, 2, 'aa'),
(32, 6, 1, 'aa'),
(33, 6, 2, 'aa'),
(34, 6, 1, 'aa'),
(35, 6, 2, 'aa'),
(36, 6, 1, 'aa'),
(37, 6, 2, 'aa'),
(38, 6, 1, 'aa'),
(39, 6, 2, 'aa'),
(40, 6, 1, 'aa'),
(41, 6, 2, 'aa'),
(42, 6, 1, 'aa'),
(43, 6, 2, 'aa'),
(44, 6, 1, 'aa'),
(45, 6, 2, 'aa');

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

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`questionId`, `examId`, `questionTitle`, `answerOption`) VALUES
(6, 1, 'aa', 'option1'),
(7, 1, 'aaa', 'option1'),
(8, 1, 'aa', 'option2'),
(9, 1, 'aa', 'option1'),
(10, 1, 'aa', 'option1'),
(11, 1, 'aa', 'option2'),
(12, 1, 'aa', 'option2'),
(13, 1, 'aa', 'option2'),
(14, 1, 'aa', 'option3'),
(15, 1, 'aa', 'option4');

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
(1, 'section 1', 1, 'Pre-Engineering'),
(2, 'section 2', 1, 'Pre-Engineering'),
(3, 'section 1', 2, 'Electrical school');

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
  MODIFY `answerId` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `examenrollment`
--
ALTER TABLE `examenrollment`
  MODIFY `examEnrollmentId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `examination`
--
ALTER TABLE `examination`
  MODIFY `examId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `option`
--
ALTER TABLE `option`
  MODIFY `optionId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `questionId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `sectionId` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`candidateId`) REFERENCES `candidate` (`candidateId`) ON DELETE NO ACTION,
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
  ADD CONSTRAINT `examenrollment_ibfk_1` FOREIGN KEY (`candidateId`) REFERENCES `candidate` (`candidateId`) ON DELETE NO ACTION,
  ADD CONSTRAINT `examenrollment_ibfk_2` FOREIGN KEY (`examId`) REFERENCES `examination` (`examId`) ON DELETE CASCADE;

--
-- Constraints for table `examination`
--
ALTER TABLE `examination`
  ADD CONSTRAINT `examination_ibfk_1` FOREIGN KEY (`conductorId`) REFERENCES `conductor` (`username`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
