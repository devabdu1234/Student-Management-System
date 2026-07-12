-- ============================================================
-- ICST Academic Management System - Database Schema
-- HDIT21193 - User Experience and Interface Design
-- Compatible with MySQL 5.7+ and XAMPP
-- ============================================================

DROP DATABASE IF EXISTS `student-management-system`;
CREATE DATABASE `student-management-system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `student-management-system`;

-- ============================================================
-- USERS — assignment required fields:
-- user_id, fullname, email, password, role, phone, created_at
-- ============================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Lecturer','Student','Parent') NOT NULL DEFAULT 'Student',
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Passwords are bcrypt hashes of "1234"
INSERT INTO `users` (`user_id`, `fullname`, `email`, `password`, `role`, `phone`) VALUES
(1,  'Admin User',           'admin@icst.ac.lk',     '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Admin',    '0112345678'),
(2,  'Nimal Perera',         'lecturer@icst.ac.lk',  '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Lecturer', '0712345678'),
(3,  'Kamala Vithanage',     'kamala@icst.ac.lk',    '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Lecturer', '0723456789'),
(4,  'Saman Gunasekara',     'saman@icst.ac.lk',     '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Lecturer', '0734567890'),
(5,  'Kasun Chamara',        'kasun@icst.ac.lk',     '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Student',  '0771234567'),
(6,  'Dasun Shanuka',        'dasun@icst.ac.lk',     '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Student',  '0772345678'),
(7,  'Amaya Dilshani',       'amaya@icst.ac.lk',     '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Student',  '0773456789'),
(8,  'Ruwan Wickrama',       'ruwan@icst.ac.lk',     '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Student',  '0774567890'),
(9,  'Kelly Perera',         'parent1@icst.ac.lk',   '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Parent',   '0771234567'),
(10, 'Nadeeka Jayawardena',  'parent2@icst.ac.lk',   '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Parent',   '0772345678'),
(11, 'Priyantha Silva',      'parent3@icst.ac.lk',   '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Parent',   '0773456789'),
(12, 'Sachini Nisansala',    'nisansala@icst.ac.lk', '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Student',  '0775678901'),
(13, 'Tharindu Bandara',     'tharindu@icst.ac.lk',  '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Student',  '0776789012'),
(14, 'Dilini Fernando',      'dilini@icst.ac.lk',    '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Student',  '0777890123'),
(15, 'Nipuna Silva',         'nipuna@icst.ac.lk',    '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Student',  '0778901234'),
(16, 'Hashini Perera',       'hashini@icst.ac.lk',   '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Student',  '0779012345'),
(17, 'Lahiru Rajapaksa',     'lahiru@icst.ac.lk',    '$2y$10$rLeWP2UpEJAEctKfTAaAeO.GGQ84vyU8CLKseWzh/U4XjqsBv4saS', 'Student',  '0780123456');

-- ============================================================
-- TEACHER
-- ============================================================
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `tid` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `bday` date DEFAULT NULL,
  `skill` varchar(500) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`tid`),
  UNIQUE KEY `teacher_email` (`email`),
  CONSTRAINT `teacher_user_fk` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `teacher` (`tid`, `fname`, `lname`, `address`, `contact`, `bday`, `skill`, `gender`, `email`) VALUES
('TCH0001', 'Nimal',   'Perera',     '85 Kandy Road, Nittambuwa',     '0712345678', '1980-03-15', 'Science, Mathematics, ICT',      'Male',   'lecturer@icst.ac.lk'),
('TCH0002', 'Kamala',  'Vithanage',  '22 Temple Road, Kandy',         '0723456789', '1985-07-22', 'English, Literature, History',    'Female', 'kamala@icst.ac.lk'),
('TCH0003', 'Saman',   'Gunasekara', '110 Galle Road, Colombo 03',    '0734567890', '1978-11-02', 'Physics, Chemistry, Mathematics', 'Male',   'saman@icst.ac.lk');

-- ============================================================
-- PARENT
-- ============================================================
DROP TABLE IF EXISTS `parent`;
CREATE TABLE `parent` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `job` varchar(50) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `nic` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `parent_email` (`email`),
  CONSTRAINT `parent_user_fk` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `parent` (`pid`, `fname`, `lname`, `contact`, `job`, `address`, `gender`, `nic`, `email`) VALUES
(1, 'Kelly',     'Perera',      '0771234567', 'Engineer',     '15 Main Street, Kandy',       'Male',   '851234567V', 'parent1@icst.ac.lk'),
(2, 'Nadeeka',   'Jayawardena', '0772345678', 'Teacher',      '88 Lake Road, Colombo 05',    'Female', '862345678V', 'parent2@icst.ac.lk'),
(3, 'Priyantha', 'Silva',       '0773456789', 'Businessman',  '204 High Level Rd, Nugegoda', 'Male',   '873456789V', 'parent3@icst.ac.lk');

-- ============================================================
-- CLASSROOM
-- ============================================================
DROP TABLE IF EXISTS `classroom`;
CREATE TABLE `classroom` (
  `hno` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `capacity` int(3) NOT NULL,
  PRIMARY KEY (`hno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `classroom` (`hno`, `title`, `location`, `capacity`) VALUES
('CR-A01', 'A01 - Lecture Hall A',  'Block A, Ground Floor', 120),
('CR-A02', 'A02 - Lecture Hall B',  'Block A, First Floor',  80),
('CR-B01', 'B01 - Lab 1',           'Block B, Ground Floor', 30),
('CR-B02', 'B02 - Lab 2',           'Block B, Ground Floor', 30),
('CR-C01', 'C01 - Seminar Room',    'Block C, Ground Floor', 50),
('CR-C02', 'C02 - Tutorial Room',   'Block C, First Floor',  40);

-- ============================================================
-- SUBJECT
-- ============================================================
DROP TABLE IF EXISTS `subject`;
CREATE TABLE `subject` (
  `sid` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `subject` (`sid`, `title`, `description`) VALUES
('SCM4251', 'Science and Technology',     'Fundamentals of science and modern technology applications'),
('MAT3120', 'Mathematics for Computing',  'Discrete mathematics, linear algebra, and statistics'),
('ENG2101', 'English for Academics',      'Academic writing, reading comprehension, and presentation skills'),
('ICT4010', 'Information & Communication Technology', 'Computer systems, networks, and ICT fundamentals'),
('PHY3101', 'Physics',                    'Mechanics, thermodynamics, and wave theory'),
('CHE2201', 'Chemistry',                  'Organic and inorganic chemistry fundamentals'),
('HIS1101', 'History of Technology',      'Evolution of technology and its societal impact'),
('BUS3001', 'Business Management',        'Principles of management, marketing, and entrepreneurship'),
('PRG4201', 'Programming Fundamentals',   'Introduction to programming using Python'),
('DBA4101', 'Database Systems',           'Relational databases, SQL, and data modeling');

-- ============================================================
-- STUDENT
-- ============================================================
DROP TABLE IF EXISTS `student`;
CREATE TABLE `student` (
  `sid` varchar(25) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `bday` date DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `parent` int(10) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `classroom` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`sid`),
  UNIQUE KEY `student_email` (`email`),
  KEY `student_parent_fk` (`parent`),
  KEY `student_classroom_fk` (`classroom`),
  CONSTRAINT `student_user_fk` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `student_parent_fk` FOREIGN KEY (`parent`) REFERENCES `parent` (`pid`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `student_classroom_fk` FOREIGN KEY (`classroom`) REFERENCES `classroom` (`hno`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `student` (`sid`, `fname`, `lname`, `bday`, `address`, `parent`, `gender`, `classroom`, `email`) VALUES
('STU20240001', 'Kasun',   'Chamara',   '2002-05-19', '85 Kandy Road, Nittambuwa',     1, 'Male',   'CR-A01', 'kasun@icst.ac.lk'),
('STU20240002', 'Dasun',   'Shanuka',   '2003-08-12', '15 Main Street, Kandy',         1, 'Male',   'CR-A01', 'dasun@icst.ac.lk'),
('STU20240003', 'Amaya',   'Dilshani',  '2004-01-25', '88 Lake Road, Colombo 05',      2, 'Female', 'CR-A02', 'amaya@icst.ac.lk'),
('STU20240004', 'Ruwan',   'Wickrama',  '2003-11-07', '204 High Level Rd, Nugegoda',    3, 'Male',   'CR-B01', 'ruwan@icst.ac.lk'),
('STU20240005', 'Sachini', 'Nisansala', '2004-03-14', '33 Park Street, Colombo 02',     2, 'Female', 'CR-A02', 'nisansala@icst.ac.lk'),
('STU20240006', 'Tharindu','Bandara',   '2002-09-30', '78 Temple Road, Kandy',          1, 'Male',   'CR-B01', 'tharindu@icst.ac.lk'),
('STU20240007', 'Dilini',  'Fernando',  '2004-06-18', '56 Galle Road, Colombo 04',      3, 'Female', 'CR-A01', 'dilini@icst.ac.lk'),
('STU20240008', 'Nipuna',  'Silva',     '2003-02-22', '12 Marine Drive, Colombo 07',    2, 'Male',   'CR-C01', 'nipuna@icst.ac.lk'),
('STU20240009', 'Hashini', 'Perera',    '2004-08-05', '45 High Level Road, Nugegoda',   3, 'Female', 'CR-C01', 'hashini@icst.ac.lk'),
('STU20240010', 'Lahiru',  'Rajapaksa', '2002-12-01', '90 Kandy Road, Peradeniya',     1, 'Male',   'CR-B02', 'lahiru@icst.ac.lk');

-- ============================================================
-- SCHEDULE
-- ============================================================
DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(50) DEFAULT NULL,
  `teacher` varchar(50) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `stime` time DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `etime` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedule_subject_fk` (`subject`),
  KEY `schedule_teacher_fk` (`teacher`),
  KEY `schedule_classroom_fk` (`class`),
  CONSTRAINT `schedule_subject_fk` FOREIGN KEY (`subject`) REFERENCES `subject` (`sid`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `schedule_teacher_fk` FOREIGN KEY (`teacher`) REFERENCES `teacher` (`tid`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `schedule_classroom_fk` FOREIGN KEY (`class`) REFERENCES `classroom` (`hno`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `schedule` (`id`, `subject`, `teacher`, `day`, `stime`, `class`, `etime`) VALUES
(1, 'SCM4251', 'TCH0001', 'Monday',    '08:30:00', 'CR-A01', '10:30:00'),
(2, 'MAT3120', 'TCH0001', 'Monday',    '11:00:00', 'CR-A01', '13:00:00'),
(3, 'ENG2101', 'TCH0002', 'Tuesday',   '08:30:00', 'CR-A02', '10:30:00'),
(4, 'ICT4010', 'TCH0003', 'Tuesday',   '11:00:00', 'CR-B01', '13:00:00'),
(5, 'PHY3101', 'TCH0003', 'Wednesday', '08:30:00', 'CR-C01', '10:30:00'),
(6, 'CHE2201', 'TCH0001', 'Wednesday', '11:00:00', 'CR-C01', '13:00:00'),
(7, 'BUS3001', 'TCH0002', 'Thursday',  '08:30:00', 'CR-A02', '10:30:00'),
(8, 'PRG4201', 'TCH0003', 'Thursday',  '11:00:00', 'CR-B02', '13:00:00'),
(9, 'DBA4101', 'TCH0003', 'Friday',    '08:30:00', 'CR-B01', '10:30:00'),
(10,'HIS1101', 'TCH0002', 'Friday',    '11:00:00', 'CR-C02', '13:00:00');

-- ============================================================
-- ATTENDANCE
-- ============================================================
DROP TABLE IF EXISTS `attendance`;
CREATE TABLE `attendance` (
  `aid` int(10) NOT NULL AUTO_INCREMENT,
  `sid` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`aid`),
  KEY `attendance_schedule_fk` (`sid`),
  CONSTRAINT `attendance_schedule_fk` FOREIGN KEY (`sid`) REFERENCES `schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `attendance` (`aid`, `sid`, `date`) VALUES
(1, 1, '2024-01-15'),
(2, 1, '2024-01-22'),
(3, 2, '2024-01-15'),
(4, 3, '2024-01-16'),
(5, 4, '2024-01-16'),
(6, 5, '2024-01-17'),
(7, 6, '2024-01-17'),
(8, 7, '2024-01-18'),
(9, 8, '2024-01-18'),
(10, 9, '2024-01-19');

-- ============================================================
-- ATTENDANCEREPORT
-- ============================================================
DROP TABLE IF EXISTS `attendancereport`;
CREATE TABLE `attendancereport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(10) DEFAULT NULL,
  `sid` varchar(50) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Present',
  PRIMARY KEY (`id`),
  KEY `attendancereport_aid_fk` (`aid`),
  KEY `attendancereport_student_fk` (`sid`),
  CONSTRAINT `attendancereport_aid_fk` FOREIGN KEY (`aid`) REFERENCES `attendance` (`aid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `attendancereport_student_fk` FOREIGN KEY (`sid`) REFERENCES `student` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `attendancereport` (`id`, `aid`, `sid`, `status`) VALUES
(1,  1, 'STU20240001', 'Present'),
(2,  1, 'STU20240002', 'Present'),
(3,  1, 'STU20240007', 'Absent'),
(4,  1, 'STU20240010', 'Present'),
(5,  2, 'STU20240001', 'Present'),
(6,  2, 'STU20240002', 'Absent'),
(7,  2, 'STU20240007', 'Present'),
(8,  3, 'STU20240001', 'Present'),
(9,  3, 'STU20240002', 'Present'),
(10, 3, 'STU20240010', 'Present'),
(11, 4, 'STU20240003', 'Present'),
(12, 4, 'STU20240005', 'Present'),
(13, 4, 'STU20240008', 'Present'),
(14, 5, 'STU20240004', 'Absent'),
(15, 5, 'STU20240006', 'Present'),
(16, 6, 'STU20240004', 'Present'),
(17, 6, 'STU20240006', 'Present'),
(18, 7, 'STU20240003', 'Present'),
(19, 7, 'STU20240005', 'Absent'),
(20, 8, 'STU20240004', 'Present'),
(21, 8, 'STU20240006', 'Present'),
(22, 8, 'STU20240009', 'Present'),
(23, 9, 'STU20240004', 'Present'),
(24, 9, 'STU20240006', 'Absent'),
(25, 10,'STU20240008', 'Present'),
(26, 10,'STU20240009', 'Present');

-- ============================================================
-- EXAM
-- ============================================================
DROP TABLE IF EXISTS `exam`;
CREATE TABLE `exam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(50) DEFAULT NULL,
  `teacher` varchar(50) DEFAULT NULL,
  `classroom` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `stime` time DEFAULT NULL,
  `etime` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exam_subject_fk` (`subject`),
  KEY `exam_teacher_fk` (`teacher`),
  KEY `exam_classroom_fk` (`classroom`),
  CONSTRAINT `exam_subject_fk` FOREIGN KEY (`subject`) REFERENCES `subject` (`sid`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `exam_teacher_fk` FOREIGN KEY (`teacher`) REFERENCES `teacher` (`tid`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `exam_classroom_fk` FOREIGN KEY (`classroom`) REFERENCES `classroom` (`hno`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `exam` (`id`, `subject`, `teacher`, `classroom`, `date`, `stime`, `etime`) VALUES
(1, 'SCM4251', 'TCH0001', 'CR-A01', '2024-02-12', '09:00:00', '11:00:00'),
(2, 'MAT3120', 'TCH0001', 'CR-A01', '2024-02-14', '09:00:00', '11:00:00'),
(3, 'ENG2101', 'TCH0002', 'CR-A02', '2024-02-16', '10:00:00', '12:00:00'),
(4, 'ICT4010', 'TCH0003', 'CR-B01', '2024-02-19', '09:00:00', '11:00:00'),
(5, 'PHY3101', 'TCH0003', 'CR-C01', '2024-02-21', '09:00:00', '11:00:00'),
(6, 'CHE2201', 'TCH0001', 'CR-C01', '2024-02-23', '10:00:00', '12:00:00'),
(7, 'BUS3001', 'TCH0002', 'CR-A02', '2024-02-26', '09:00:00', '11:00:00'),
(8, 'PRG4201', 'TCH0003', 'CR-B02', '2024-02-28', '09:00:00', '11:00:00'),
(9, 'DBA4101', 'TCH0003', 'CR-B01', '2024-03-01', '10:00:00', '12:00:00'),
(10,'HIS1101', 'TCH0002', 'CR-C02', '2024-03-04', '09:00:00', '11:00:00');

-- ============================================================
-- EXAMRESULT
-- ============================================================
DROP TABLE IF EXISTS `examresult`;
CREATE TABLE `examresult` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam` int(11) DEFAULT NULL,
  `student` varchar(50) DEFAULT NULL,
  `marks` int(10) NOT NULL DEFAULT 0,
  `grade` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `exam_student_unique` (`exam`,`student`),
  KEY `examresult_student_fk` (`student`),
  CONSTRAINT `examresult_exam_fk` FOREIGN KEY (`exam`) REFERENCES `exam` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `examresult_student_fk` FOREIGN KEY (`student`) REFERENCES `student` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `examresult` (`id`, `exam`, `student`, `marks`, `grade`) VALUES
(1,  1, 'STU20240001', 85, 'A'),
(2,  1, 'STU20240002', 72, 'B+'),
(3,  1, 'STU20240007', 45, 'C'),
(4,  1, 'STU20240010', 91, 'A+'),
(5,  2, 'STU20240001', 78, 'B+'),
(6,  2, 'STU20240002', 65, 'B'),
(7,  2, 'STU20240010', 88, 'A'),
(8,  3, 'STU20240003', 92, 'A+'),
(9,  3, 'STU20240005', 74, 'B+'),
(10, 3, 'STU20240008', 58, 'C+'),
(11, 4, 'STU20240004', 70, 'B'),
(12, 4, 'STU20240006', 82, 'A'),
(13, 5, 'STU20240004', 63, 'B'),
(14, 5, 'STU20240006', 55, 'C+'),
(15, 6, 'STU20240004', 77, 'B+'),
(16, 6, 'STU20240006', 90, 'A'),
(17, 7, 'STU20240003', 68, 'B'),
(18, 7, 'STU20240005', 81, 'A'),
(19, 8, 'STU20240004', 73, 'B+'),
(20, 8, 'STU20240006', 60, 'B-'),
(21, 8, 'STU20240009', 47, 'C'),
(22, 9, 'STU20240004', 85, 'A'),
(23, 9, 'STU20240006', 79, 'B+'),
(24, 10,'STU20240008', 62, 'B'),
(25, 10,'STU20240009', 71, 'B+');

-- ============================================================
-- NOTICE
-- ============================================================
DROP TABLE IF EXISTS `notice`;
CREATE TABLE `notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `notice` text NOT NULL,
  `odience` varchar(50) NOT NULL DEFAULT 'All',
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `notice` (`id`, `notice`, `odience`, `date`) VALUES
(1, 'Welcome to the new academic year 2024! All students are required to confirm their registration by January 30th. Please visit the academic office with your completed forms.', 'All', '2024-01-05 09:00:00'),
(2, 'The first round of assessments will begin on February 12th. Please check the exam schedule on the notice board and plan your studies accordingly.', 'Student', '2024-01-20 10:30:00'),
(3, 'Parent-Teacher Meeting scheduled for February 24th at 9:00 AM in the Main Auditorium. Your participation is highly valued.', 'Parent', '2024-02-01 08:00:00'),
(4, 'Library will remain open until 8:00 PM during the examination period starting February 5th. All students are encouraged to utilize this facility.', 'Student', '2024-02-03 11:00:00'),
(5, 'ICT Lab maintenance scheduled for February 10th. Lab B01 and B02 will be closed from 8:00 AM to 2:00 PM. Plan your practical sessions accordingly.', 'All', '2024-02-08 09:15:00'),
(6, 'Congratulations to all students who achieved Distinction grades in the previous semester! Awards ceremony will be held on March 15th.', 'All', '2024-02-20 14:00:00'),
(7, 'Final year project submissions are due on March 30th. Please submit three bound copies to the department office. Late submissions will incur penalties.', 'Student', '2024-03-01 10:00:00'),
(8, 'School will remain closed on March 5th for National Independence Day celebrations. All classes and activities are suspended.', 'All', '2024-03-03 07:30:00'),
(9, 'Second semester enrollment is now open. Please complete your course selections online before April 10th. Contact your academic advisor for guidance.', 'Student', '2024-03-15 09:00:00'),
(10,'Sports Day will be held on April 20th. Parents are cordially invited to attend and support the students.', 'Parent', '2024-03-25 11:00:00');

-- ============================================================
-- FEATURES — assignment required fields:
-- Features_id, Features_name, description, facilities, user, image, created_at
-- ============================================================
DROP TABLE IF EXISTS `features`;
CREATE TABLE `features` (
  `Features_id` int(11) NOT NULL AUTO_INCREMENT,
  `Features_name` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `facilities` text DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Features_id`),
  UNIQUE KEY `feature_name` (`Features_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `features` (`Features_id`, `Features_name`, `description`, `facilities`, `user`, `image`) VALUES
(1,  'Student Management',     'Register, update, and manage all student records with full profile management',     'Student registration, Profile updates, Academic history tracking', 'Admin, Lecturer', 'features_student.jpg'),
(2,  'Lecturer Management',    'Maintain lecturer profiles, contact details, skills, and teaching assignments',  'Profile management, Skill tracking, Assignment scheduling', 'Admin', 'features_lecturer.jpg'),
(3,  'Subject Management',     'Define academic subjects, course codes, syllabi, and credit allocations',         'Subject creation, Syllabus management, Credit allocation', 'Admin, Lecturer', 'features_subject.jpg'),
(4,  'Classroom Management',   'Manage lecture halls, laboratory rooms, and seminar spaces with capacity tracking', 'Room allocation, Capacity tracking, Availability checking', 'Admin, Lecturer', 'features_classroom.jpg'),
(5,  'Schedule Management',    'Create and manage weekly class timetables with conflict prevention',               'Timetable creation, Conflict detection, Schedule publishing', 'Admin, Lecturer', 'features_schedule.jpg'),
(6,  'Attendance Tracking',    'Mark daily attendance and generate attendance reports per session',               'Attendance marking, Report generation, Absence tracking', 'Admin, Lecturer', 'features_attendance.jpg'),
(7,  'Assessment Management',  'Schedule examinations, assign invigilators, and allocate examination halls',       'Exam scheduling, Invigilator assignment, Hall allocation', 'Admin, Lecturer', 'features_assessment.jpg'),
(8,  'Results Management',     'Record and publish student marks and grades with GPA calculation',                'Grade entry, Result publication, GPA calculation', 'Admin, Lecturer', 'features_results.jpg'),
(9,  'Notice System',          'Send targeted announcements to students, parents, or all stakeholders',           'Notice creation, Audience targeting, Publishing', 'Admin, Lecturer', 'features_notice.jpg'),
(10, 'User Administration',    'Create and manage system user accounts with role-based access control',            'User creation, Role assignment, Access management', 'Admin', 'features_users.jpg'),
(11, 'Eligibility Tracking',   'Evaluate student eligibility for exams and academic awards',                     'Eligibility checks, Rule engine, Compliance reporting', 'Admin, Lecturer', 'features_eligibility.jpg');

-- ============================================================
-- ADD (feature addition log) — assignment required fields:
-- add_id, user_id, Features_name, status
-- ============================================================
DROP TABLE IF EXISTS `add`;
CREATE TABLE `add` (
  `add_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `Features_name` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`add_id`),
  KEY `add_user_fk` (`user_id`),
  CONSTRAINT `add_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `add` (`add_id`, `user_id`, `Features_name`, `status`, `added_at`) VALUES
(1,  1, 'Student Management',     'active', '2024-01-01 08:00:00'),
(2,  1, 'Lecturer Management',    'active', '2024-01-01 08:05:00'),
(3,  1, 'Subject Management',     'active', '2024-01-01 08:10:00'),
(4,  1, 'Classroom Management',   'active', '2024-01-02 09:00:00'),
(5,  1, 'Schedule Management',    'active', '2024-01-03 10:00:00'),
(6,  2, 'Attendance Tracking',    'active', '2024-01-05 08:30:00'),
(7,  3, 'Assessment Management',  'active', '2024-01-10 09:00:00'),
(8,  2, 'Results Management',     'active', '2024-01-15 10:00:00'),
(9,  1, 'Notice System',          'active', '2024-01-20 11:00:00'),
(10, 1, 'User Administration',    'active', '2024-01-25 08:00:00'),
(11, 1, 'Eligibility Tracking',   'active', '2024-02-01 08:00:00');

-- ============================================================
-- Legacy user table for backward compatibility
-- ============================================================
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `role` varchar(50) NOT NULL DEFAULT 'Student',
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `user` (`role`, `email`, `password`)
SELECT `role`, `email`, `password` FROM `users`;
