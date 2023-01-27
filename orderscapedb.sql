-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2022 at 08:43 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orderscapedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `AccountID` int(11) NOT NULL,
  `Account_Type` tinytext NOT NULL,
  `Email` mediumtext NOT NULL,
  `Password` mediumtext NOT NULL,
  `FirstName` mediumtext NOT NULL,
  `MiddleName` mediumtext NOT NULL,
  `LastName` mediumtext NOT NULL,
  `PhoneNumber` mediumtext NOT NULL,
  `CourseOrPosition` tinytext NOT NULL,
  `ActiveStatus` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`AccountID`, `Account_Type`, `Email`, `Password`, `FirstName`, `MiddleName`, `LastName`, `PhoneNumber`, `CourseOrPosition`, `ActiveStatus`) VALUES
(1000000, 'Customer', 'Example@Gmail.com', 'Customer1', 'CustomerEx', 'ex', 'Example', '938478127', 'BSCS301', 'false'),
(2000000, 'Staff', 'ExampleS@gmail.com', 'Staff1', 'StaffEx', 'ex', 'Example', '345346253', 'Chef', 'false'),
(3000000, 'Admin', 'ExampleA@gmail.com', 'Admin', 'AdminEx', 'ex', 'Example', '453634564', 'Admin', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `FoodID` int(11) NOT NULL,
  `FoodName` tinytext NOT NULL,
  `FoodPrice` double NOT NULL DEFAULT 0,
  `Category` text NOT NULL,
  `Image` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`FoodID`, `FoodName`, `FoodPrice`, `Category`, `Image`) VALUES
(1, 'Rice', 30, 'Dish', NULL),
(2, 'Chicken', 43, 'Dish', 0x325075656e7465546872656164436f64652e6a7067),
(3, 'Coffee', 20, 'Drink', NULL),
(7, 'Fried rice', 70, 'Dish', ''),
(8, 'Fonzy', 3000000, 'Dish', ''),
(9, 'Adobo', 50, 'Drink', '');

-- --------------------------------------------------------

--
-- Table structure for table `foodcart`
--

CREATE TABLE `foodcart` (
  `FoodCartID` int(11) NOT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `FoodID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `AccountID` int(11) NOT NULL,
  `OrderPrice` double NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`AccountID`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`FoodID`);

--
-- Indexes for table `foodcart`
--
ALTER TABLE `foodcart`
  ADD PRIMARY KEY (`FoodCartID`),
  ADD KEY `FK__orders` (`OrderID`),
  ADD KEY `FKf__food` (`FoodID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `FK__accounts` (`AccountID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `FoodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `foodcart`
--
ALTER TABLE `foodcart`
  ADD CONSTRAINT `FK__orders` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FKf__food` FOREIGN KEY (`FoodID`) REFERENCES `food` (`FoodID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK__accounts` FOREIGN KEY (`AccountID`) REFERENCES `accounts` (`AccountID`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
