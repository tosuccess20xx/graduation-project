-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2019 at 12:12 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `technical_education`
--

-- --------------------------------------------------------

--
-- Table structure for table `band`
--

CREATE TABLE `band` (
  `Band_ID` tinyint(3) NOT NULL,
  `Band_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `band`
--

INSERT INTO `band` (`Band_ID`, `Band_Name`) VALUES
(2, 'الفرقة الأولى'),
(4, 'الفرقة الثالثة'),
(3, 'الفرقة الثانية'),
(6, 'الفرقة الخامسة'),
(5, 'الفرقة الرابعة'),
(1, 'عام');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `Book_ID` int(11) NOT NULL,
  `Book_Title` varchar(255) NOT NULL,
  `Book_Cover` varchar(255) NOT NULL,
  `Book_Source` varchar(255) NOT NULL,
  `Create_Date` date NOT NULL,
  `Course_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`Book_ID`, `Book_Title`, `Book_Cover`, `Book_Source`, `Create_Date`, `Course_ID`) VALUES
(1, 'تكنولوجيا التبريد والتكييف', '429472_usa.png', '272381_technical_education.pdf', '2019-03-21', 1),
(6, 'التخطيط وإدارة الإنتاج', '133639_usa.png', '129581_technical_education.pdf', '2019-04-16', 6);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `Comment_ID` int(11) NOT NULL,
  `Comment_Text` text NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT '1',
  `Comment_Date` datetime NOT NULL,
  `Video_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`Comment_ID`, `Comment_Text`, `Status`, `Comment_Date`, `Video_ID`, `User_ID`) VALUES
(1, 'شرح رائع وجميل', 1, '2019-03-22 00:00:00', 1, 2),
(2, 'شرح رائع نرجو المزيد من الدورات', 1, '2019-04-08 00:00:00', 1, 2),
(3, 'شرح رائع نرجو المزيد من الدورات', 1, '2019-04-13 00:02:58', 1, 2),
(4, 'شرح رائع وجميل', 1, '2019-04-13 00:12:13', 1, 2),
(5, 'شرح ممتاز بالتوفيق إنشاء الله', 1, '2019-04-14 22:05:40', 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `Course_ID` int(11) NOT NULL COMMENT 'To Identify Course',
  `Title` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Create_Date` date NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Approve` tinyint(1) NOT NULL DEFAULT '0',
  `Section_ID` int(11) NOT NULL,
  `Band_ID` tinyint(3) NOT NULL,
  `Teacher_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`Course_ID`, `Title`, `Description`, `Create_Date`, `Image`, `Approve`, `Section_ID`, `Band_ID`, `Teacher_ID`) VALUES
(1, 'شرح مادة محركات كهربية', 'شرح مادة محركات كهربية', '2019-03-21', '678317_img-4.jpg', 1, 2, 4, 2),
(2, 'شرح مادة دوائر إلكترونية', 'شرح مادة دوائر إلكترونية', '2019-03-31', '871391_university-computer-engineer-education-academic.jpg', 1, 5, 5, 2),
(6, 'التخطيط وإدارة الإنتاج', 'شرح مادة التخطيط وإدارة الإنتاج', '2019-04-12', '556990_blue-electric-motor-student-laboratory.jpg', 1, 3, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `courses_videos`
--

CREATE TABLE `courses_videos` (
  `Video_ID` int(11) NOT NULL COMMENT 'To Identify Video',
  `Title` varchar(255) CHARACTER SET utf16 NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Video_Source` varchar(255) NOT NULL,
  `Create_Date` date NOT NULL,
  `Course_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses_videos`
--

INSERT INTO `courses_videos` (`Video_ID`, `Title`, `Image`, `Video_Source`, `Create_Date`, `Course_ID`) VALUES
(1, 'أنواع المحركات الكهربية', '71128_Digital-technology-Feature.jpg', '175917_video.mp4', '2019-03-21', 1),
(2, 'العدد والأدوات المستخدمة', '18436_Digital-technology-Feature.jpg', '949665_movie.mp4', '2019-03-21', 1),
(3, 'كيفية عمل دائرة كهربية', '561966_students-robotics-robot.jpg', '649598_Types of Electric Motors - أنواع المحركات الكهربائية.mp4', '2019-03-31', 6);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Ordering` int(11) NOT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '1',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '1',
  `Allow_Courses` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`ID`, `Name`, `Description`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Courses`) VALUES
(1, 'عام', 'قسم عام', 1, 1, 1, 1),
(2, 'تركيبات ومعدات كهربية', 'قسم تركيبات ومعدات كهربية', 2, 1, 1, 1),
(3, 'التبريد والتكييف', 'قسم التبريد والتكييف', 3, 1, 1, 1),
(4, 'الحاسبات', 'قسم الحاسبات', 4, 1, 1, 1),
(5, 'الإلكترونيات', 'قسم الإلكترونيات', 5, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To Identify User',
  `Username` varchar(255) NOT NULL COMMENT 'Username To Login',
  `Password` varchar(255) NOT NULL COMMENT 'Password To Login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL COMMENT 'To Identify User Group',
  `SectionID` int(11) NOT NULL COMMENT 'To Identify Student Section',
  `BandID` tinyint(3) NOT NULL COMMENT 'To Identify Student Band',
  `Image` varchar(255) NOT NULL,
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'User Activation',
  `RegDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `SectionID`, `BandID`, `Image`, `RegStatus`, `RegDate`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@admin.educ', 'admin admin', 1, 1, 1, '', 1, '2019-03-20'),
(2, 'teacher', '8d5e957f297893487bd98fa830fa6413', 'teacher@teacher.educ', 'teacher teacher', 2, 4, 4, 'steve.jpg', 1, '2019-03-21'),
(3, 'student', '0c74b7f78409a4022a2c4c5a5ca3ee19', 'student@student.com', 'student student', 3, 5, 2, '', 1, '2019-03-21');

-- --------------------------------------------------------

--
-- Table structure for table `users_courses`
--

CREATE TABLE `users_courses` (
  `Subscribe_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Course_ID` int(11) NOT NULL,
  `Subscribe_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_courses`
--

INSERT INTO `users_courses` (`Subscribe_ID`, `User_ID`, `Course_ID`, `Subscribe_Date`) VALUES
(1, 2, 1, '2019-04-13 18:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `GroupID` int(11) NOT NULL,
  `GroupName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`GroupID`, `GroupName`) VALUES
(3, 'طالب'),
(2, 'مدرب'),
(1, 'مدير');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `band`
--
ALTER TABLE `band`
  ADD PRIMARY KEY (`Band_ID`),
  ADD UNIQUE KEY `Band_Name` (`Band_Name`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`Book_ID`),
  ADD KEY `Course-ID` (`Course_ID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Comment_ID`),
  ADD KEY `Video_ID` (`Video_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`Course_ID`),
  ADD KEY `Section_ID` (`Section_ID`),
  ADD KEY `Teacher_ID` (`Teacher_ID`),
  ADD KEY `BandID` (`Band_ID`);

--
-- Indexes for table `courses_videos`
--
ALTER TABLE `courses_videos`
  ADD PRIMARY KEY (`Video_ID`),
  ADD KEY `Course_ID` (`Course_ID`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Group_ID` (`GroupID`),
  ADD KEY `Band_ID` (`BandID`),
  ADD KEY `SectionID` (`SectionID`);

--
-- Indexes for table `users_courses`
--
ALTER TABLE `users_courses`
  ADD PRIMARY KEY (`Subscribe_ID`),
  ADD KEY `UserID` (`User_ID`),
  ADD KEY `CourseID` (`Course_ID`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`GroupID`),
  ADD UNIQUE KEY `GroupName` (`GroupName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `band`
--
ALTER TABLE `band`
  MODIFY `Band_ID` tinyint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `Book_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `Comment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `Course_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify Course', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `courses_videos`
--
ALTER TABLE `courses_videos`
  MODIFY `Video_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify Video', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_courses`
--
ALTER TABLE `users_courses`
  MODIFY `Subscribe_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `GroupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `Course-ID` FOREIGN KEY (`Course_ID`) REFERENCES `courses` (`Course_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `User_ID` FOREIGN KEY (`User_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Video_ID` FOREIGN KEY (`Video_ID`) REFERENCES `courses_videos` (`Video_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `BandID` FOREIGN KEY (`Band_ID`) REFERENCES `band` (`Band_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Section_ID` FOREIGN KEY (`Section_ID`) REFERENCES `sections` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Teacher_ID` FOREIGN KEY (`Teacher_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `courses_videos`
--
ALTER TABLE `courses_videos`
  ADD CONSTRAINT `Course_ID` FOREIGN KEY (`Course_ID`) REFERENCES `courses` (`Course_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `Band_ID` FOREIGN KEY (`BandID`) REFERENCES `band` (`Band_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Group_ID` FOREIGN KEY (`GroupID`) REFERENCES `users_groups` (`GroupID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SectionID` FOREIGN KEY (`SectionID`) REFERENCES `sections` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_courses`
--
ALTER TABLE `users_courses`
  ADD CONSTRAINT `CourseID` FOREIGN KEY (`Course_ID`) REFERENCES `courses` (`Course_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UserID` FOREIGN KEY (`User_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
