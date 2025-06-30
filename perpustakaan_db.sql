-- Disable foreign key checks to avoid errors during import
SET FOREIGN_KEY_CHECKS=0;

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `password` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `firstname` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `lastname` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--
INSERT INTO `users` (`user_id`, `username`, `password`, `firstname`, `lastname`) VALUES
(1, 'admin', '$2y$10$LF.OSu192DukatY/Fm755ek2YZaoOiTn/b9E5iODMG4OAx5ZRSGoy', 'super', 'admin');

--
-- Table structure for table `member`
--
DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `member_id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `lastname` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `gender` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `address` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `contact` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `type` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`member_id`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--
INSERT INTO `member` (`member_id`, `firstname`, `lastname`, `email`, `gender`, `address`, `contact`, `type`, `status`) VALUES
(4, 'Fajar', 'A', 'fajar@gmail.com', 'L', 'depok', '09821392', 'Guru', 1),
(5, 'Sarah', 'Azhari', 'sarah@gmail.co', 'P', 'depok', '09821392', 'Siswa', 1),
(6, 'Valentino', 'Ronald', 'Ronald@gmail.com', 'L', 'Depok', '09821392', 'Siswa', 1);

--
-- Table structure for table `book`
--
DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `book_id` int NOT NULL AUTO_INCREMENT,
  `book_title` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `category` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `author` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `book_copies` int NOT NULL,
  `publisher_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `isbn` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `copyright_year` int NOT NULL,
  `status` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`book_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book`
--
INSERT INTO `book` (`book_id`, `book_title`, `category`, `author`, `book_copies`, `publisher_name`, `isbn`, `copyright_year`, `status`) VALUES
(1, 'Database dan ERD', 'Teknologi', 'Fajar Agung', 4, 'Unpam Press', '1232123432123', 2021, '1'),
(2, 'Matematika Diskrit', 'Pendidikan', 'Saptono', 2, 'Erlangga', '1234243463455', 2021, '1'),
(3, 'Database Relational', 'Teknologi', 'Fajar Agung', 4, 'Unpam Press', '1232123432123', 2022, '1'),
(4, 'Logika Matematika', 'Pendidikan', 'Saptono', 2, 'Erlangga', '1234243463455', 2021, '1'),
(5, 'Database Fundamental', 'Teknologi', 'Fajar Agung', 4, 'Unpam Press', '1232123456454', 2024, '1'),
(6, 'Database Intermediate', 'Teknologi', 'Fajar Agung', 4, 'Unpam Press', '1232178978895', 2021, '1'),
(7, 'Database', 'Teknologi', 'Fajar Agung', 4, 'Unpam Press', '1232123424244', 2000, '0'),
(8, 'Pengantar teknologi', 'Pendidikan', 'Saptono', 2, 'Erlangga', '1234243789634', 2011, '1'),
(9, 'Data Mining', 'Teknologi', 'Fajar Agung', 4, 'Unpam Press', '1232123434533', 2011, '0');

--
-- Table structure for table `borrow`
--
DROP TABLE IF EXISTS `borrow`;
CREATE TABLE IF NOT EXISTS `borrow` (
  `borrow_id` int NOT NULL AUTO_INCREMENT,
  `member_id` int NOT NULL,
  `date_borrow` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`borrow_id`) USING BTREE,
  KEY `fk123` (`member_id`) USING BTREE,
  CONSTRAINT `fk123` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `borrow`
--
INSERT INTO `borrow` (`borrow_id`, `member_id`, `date_borrow`, `due_date`, `status`) VALUES
(1, 4, '2025-06-20', '2025-06-27', 1),
(2, 5, '2025-06-20', '2025-06-27', 1),
(3, 6, '2025-06-20', '2025-06-27', 1),
(4, 6, '2025-06-20', '2025-06-27', 1),
(5, 4, '2025-06-20', '2025-06-27', 0);

--
-- Table structure for table `borrowdetails`
--
DROP TABLE IF EXISTS `borrowdetails`;
CREATE TABLE IF NOT EXISTS `borrowdetails` (
  `borrow_details_id` int NOT NULL AUTO_INCREMENT,
  `book_id` int NOT NULL,
  `borrow_id` int NOT NULL,
  `borrow_status` int DEFAULT '1',
  `date_return` date DEFAULT NULL,
  PRIMARY KEY (`borrow_details_id`) USING BTREE,
  KEY `book_id` (`book_id`) USING BTREE,
  KEY `borrow_id` (`borrow_id`) USING BTREE,
  CONSTRAINT `borrowdetails_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `borrowdetails_ibfk_2` FOREIGN KEY (`borrow_id`) REFERENCES `borrow` (`borrow_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `borrowdetails`
--
INSERT INTO `borrowdetails` (`borrow_details_id`, `book_id`, `borrow_id`, `borrow_status`, `date_return`) VALUES
(1, 5, 1, 1, '2025-06-20'),
(2, 6, 1, 1, '2025-06-20'),
(3, 3, 1, 1, '2025-06-20'),
(4, 7, 2, 1, '2025-06-20'),
(5, 5, 2, 1, '2025-06-20'),
(6, 3, 2, 1, '2025-06-20'),
(7, 4, 2, 1, '2025-06-20'),
(8, 8, 2, 1, '2025-06-20'),
(9, 5, 3, 1, '2025-06-20'),
(10, 6, 3, 1, '2025-06-20'),
(11, 2, 3, 1, '2025-06-20'),
(12, 1, 4, 1, '2025-06-20'),
(13, 3, 4, 1, '2025-06-20'),
(14, 9, 5, 0, NULL),
(15, 7, 5, 0, NULL);

-- Enable foreign key checks after import is complete
SET FOREIGN_KEY_CHECKS=1;