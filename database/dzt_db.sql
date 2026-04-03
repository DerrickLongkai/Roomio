-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2025 at 03:26 PM
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
-- Database: `dzt_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `landlord`
--

CREATE TABLE `landlord` (
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(10) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `landlord`
--

INSERT INTO `landlord` (`FirstName`, `LastName`, `Email`, `Password`) VALUES
('Longkai', 'Zhang', 'longkai.zhang@student.griffith.ie', '123'),
('Derrick', 'Zhang', 'longkaiz0324@163.com', '123'),
('Jennifer', 'Ring', 'longkaiz0324@gmail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `Id` int(10) NOT NULL,
  `EirCode` varchar(10) NOT NULL,
  `Landlord` varchar(50) NOT NULL,
  `Bedroom` int(10) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Availability` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`Id`, `EirCode`, `Landlord`, `Bedroom`, `Price`, `Availability`) VALUES
(12, 'T23 A4C8', 'longkaiz0324@163.com', 1, 500.00, 'under 1 month'),
(14, 'T23 A4C9', 'longkaiz0324@163.com', 1, 1500.00, 'under 1 month'),
(15, 'T23 A4C1', 'longkaiz0324@163.com', 3, 3000.00, '3-6 months'),
(16, 'T24 C567', 'longkaiz0324@gmail.com', 4, 5000.00, '1 year+'),
(17, 'T24 C789', 'longkaiz0324@gmail.com', 4, 2500.00, '6-12 months'),
(18, 'T24 C123', 'longkaiz0324@gmail.com', 2, 1500.00, '6-12 months'),
(19, 'T12 C123', 'longkai.zhang@student.griffith.ie', 3, 2000.00, '1 year+'),
(20, 'T12 C567', 'longkai.zhang@student.griffith.ie', 1, 420.00, 'under 1 month'),
(21, 'T12 C890', 'longkai.zhang@student.griffith.ie', 3, 2000.00, '6-12 months'),
(22, 'T12 C345', 'longkai.zhang@student.griffith.ie', 1, 450.00, '1 year+');

-- --------------------------------------------------------

--
-- Table structure for table `property_images`
--

CREATE TABLE `property_images` (
  `image_id` int(10) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `image_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_images`
--

INSERT INTO `property_images` (`image_id`, `property_id`, `image_name`) VALUES
(38, 12, 'prop_68a3b986cbbf8.jpeg'),
(39, 12, 'prop_68a3b986cc3d6.jpg'),
(40, 12, 'prop_68a3b986ccb93.jpeg'),
(41, 12, 'prop_68a3b986cd38a.jpeg'),
(42, 12, 'prop_68a3b986cdc56.jpeg'),
(43, 14, 'prop_68a3b9b7162d8.jpeg'),
(44, 14, 'prop_68a3b9b716d07.jpeg'),
(45, 14, 'prop_68a3b9b7177cf.jpeg'),
(46, 14, 'prop_68a3b9b717f2c.jpeg'),
(47, 14, 'prop_68a3b9b718548.jpeg'),
(48, 15, 'prop_68a3b9d66d3e3.jpeg'),
(49, 15, 'prop_68a3b9d66db0d.jpeg'),
(50, 15, 'prop_68a3b9d66e21a.jpeg'),
(51, 15, 'prop_68a3b9d66e8ae.jpeg'),
(52, 15, 'prop_68a3b9d66f027.jpeg'),
(53, 16, 'prop_68a3ba4f6563a.jpeg'),
(54, 16, 'prop_68a3ba4f65d52.jpeg'),
(55, 16, 'prop_68a3ba4f665ff.jpeg'),
(56, 16, 'prop_68a3ba4f66dc8.jpeg'),
(57, 16, 'prop_68a3ba4f67501.jpeg'),
(58, 17, 'prop_68a3ba6b4a9e4.jpeg'),
(59, 17, 'prop_68a3ba6b4b117.jpeg'),
(60, 17, 'prop_68a3ba6b4b752.jpeg'),
(61, 17, 'prop_68a3ba6b4bda7.jpeg'),
(62, 17, 'prop_68a3ba6b4c384.jpeg'),
(63, 19, 'prop_68a3baecaccb8.jpeg'),
(64, 19, 'prop_68a3baecad375.jpeg'),
(65, 19, 'prop_68a3baecad9ce.jpeg'),
(66, 20, 'prop_68a3bb0c7b82a.jpeg'),
(67, 20, 'prop_68a3bb0c7bf34.jpeg'),
(68, 21, 'prop_68a3bb2cdbf96.jpeg'),
(69, 21, 'prop_68a3bb2cdcaab.jpeg'),
(70, 21, 'prop_68a3bb2cdd253.jpeg'),
(71, 21, 'prop_68a3bb2cdd8fa.jpeg'),
(72, 21, 'prop_68a3bb2cde12e.jpeg'),
(73, 22, 'prop_68a3bb495a0c3.jpeg'),
(74, 22, 'prop_68a3bb495a981.jpeg'),
(75, 22, 'prop_68a3bb495afc7.jpeg'),
(76, 22, 'prop_68a3bb495b5f7.jpeg'),
(77, 22, 'prop_68a3bb495be0d.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `tenant`
--

CREATE TABLE `tenant` (
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(20) DEFAULT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant`
--

INSERT INTO `tenant` (`FirstName`, `LastName`, `Email`, `Password`) VALUES
('Jennifer', 'Ring', 'longkai.zhang@student.griffith.ie', '123'),
('Longkai', 'Zhang', 'longkaiz0324@163.com', '123'),
('Derrick Longkai', 'Zhang', 'longkaiz0324@gmail.com', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `testimonial`
--

CREATE TABLE `testimonial` (
  `Id` int(10) NOT NULL,
  `Tenant` varchar(50) NOT NULL,
  `Date` date DEFAULT current_timestamp(),
  `Comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonial`
--

INSERT INTO `testimonial` (`Id`, `Tenant`, `Date`, `Comment`) VALUES
(1, 'longkaiz0324@163.com', '2025-08-12', 'it was very nice!!!!'),
(2, 'longkaiz0324@gmail.com', '2025-08-11', 'I had a wonderful stay.....'),
(3, 'longkai.zhang@student.griffith.ie', '2025-08-01', 'it\'s a really nice house, so nice....');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `landlord`
--
ALTER TABLE `landlord`
  ADD PRIMARY KEY (`Email`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Landlord` (`Landlord`);

--
-- Indexes for table `property_images`
--
ALTER TABLE `property_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `tenant`
--
ALTER TABLE `tenant`
  ADD PRIMARY KEY (`Email`);

--
-- Indexes for table `testimonial`
--
ALTER TABLE `testimonial`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Tenant` (`Tenant`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `property_images`
--
ALTER TABLE `property_images`
  MODIFY `image_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `testimonial`
--
ALTER TABLE `testimonial`
  MODIFY `Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `property_ibfk_1` FOREIGN KEY (`Landlord`) REFERENCES `landlord` (`Email`);

--
-- Constraints for table `testimonial`
--
ALTER TABLE `testimonial`
  ADD CONSTRAINT `testimonial_ibfk_1` FOREIGN KEY (`Tenant`) REFERENCES `tenant` (`Email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
