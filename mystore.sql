-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2024 at 04:04 AM
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
-- Database: `mystore`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `Brand_Id` int(11) NOT NULL,
  `Brand_Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`Brand_Id`, `Brand_Name`) VALUES
(1, 'Nike'),
(2, 'Thursday Boots'),
(4, 'Paul Parkman Boots'),
(5, 'Oxfords'),
(6, 'Zara'),
(7, 'bata');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `Cart_Id` int(11) NOT NULL,
  `User_Id` int(11) DEFAULT NULL,
  `Product_Name` varchar(255) NOT NULL,
  `Product_Price` decimal(10,2) DEFAULT NULL,
  `Product_Quantity` int(11) DEFAULT NULL,
  `Product_Image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`Cart_Id`, `User_Id`, `Product_Name`, `Product_Price`, `Product_Quantity`, `Product_Image`) VALUES
(1, 2, 'Asdgasdfgazsdfg', 234.00, 3, 'images/part4.png'),
(2, 4, 'Asdgasdfgazsdfg', 234.00, 2, 'images/part4.png'),
(3, 5, 'Asdgasdfgsdfhsdf', 21.00, 2, 'images/part3.png'),
(4, 5, 'Asdgasdfgazsdfg', 234.00, 1, 'images/part4.png');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Category_Id` int(11) NOT NULL,
  `Category_Name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_Id`, `Category_Name`) VALUES
(2, 'Chukka Boots'),
(5, 'Chelsea Boots'),
(6, 'oxford'),
(8, 'wing tip'),
(9, 'heels'),
(10, 'Boots'),
(11, 'choes');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_Id` int(11) NOT NULL,
  `Category_Id` int(11) DEFAULT NULL,
  `Brand_Id` int(11) DEFAULT NULL,
  `Product_Name` varchar(255) NOT NULL,
  `Product_Description` text DEFAULT NULL,
  `Gender` varchar(255) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `Discount` decimal(5,2) DEFAULT NULL,
  `ProductImage1` varchar(255) DEFAULT NULL,
  `ProductImage2` varchar(255) DEFAULT NULL,
  `ProductImage3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_Id`, `Category_Id`, `Brand_Id`, `Product_Name`, `Product_Description`, `Gender`, `Price`, `Discount`, `ProductImage1`, `ProductImage2`, `ProductImage3`) VALUES
(3, 2, 4, 'Asdgasdfgazsdfg', 'Afdsgadfgafdga', 'Male', 234.00, 0.00, 'images/part4.png', 'images/part4.png', 'images/part4.png'),
(6, 5, 2, 'Chelsea 11124', 'Tjebajsbhldfkjasdf', 'Male', 23.00, 0.00, 'images/3-Figure1-1.png', 'images/image-3.png', 'images/part1.png'),
(9, 5, 4, 'Ykuioyuioyui', 'Sthsdfhdfghd', 'Male', 34.00, 0.00, 'images/admin.png', 'images/admin.png', 'images/admin.png'),
(11, 5, 6, 'Asdfasdfasgsgfhdfh', 'Ovbjnpfoghnjpfgkh', 'Male', 45.00, 0.00, 'images/Priority non-premtatiove Scheduling Algorithm.png', 'images/Round Robin Scheduling Algorithm.png', 'images/Dead Lock Algorithm.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_Id` int(11) NOT NULL,
  `User_Name` varchar(255) NOT NULL,
  `User_Email` varchar(255) NOT NULL,
  `User_Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_Id`, `User_Name`, `User_Email`, `User_Password`) VALUES
(1, 'faheem', 'faheem@gmail.com', '$2y$10$3C3EgOKaDRtQNIIoy7oH0umbkughoz45ufmZJI7rNE5vfgf4sTToO'),
(2, 'Brad', 'Brad@gmail.com', '$2y$10$aTIurCY5qTY/GUBe6EltmuOE/R8Fxi6RuFfbf.qycH1mvt6JkWgbG'),
(3, 'Sami', 'Sami@gmail.com', '$2y$10$5e/oYtXy0m2CHMQv0wunguCYcmfatAGpp515rmq3iSxwn3t1GDCIS'),
(4, 'Faarah Faarah w11', 'faarah@gmail.com', '$2y$10$ARL3kirVdzvo2krHH9Hm.OgkmAyQbClduSCb8Kk7wSs1vM65TlscS'),
(5, 'Lazba', 'Lazba@gmail.com', '$2y$10$DDqWITr5y1QB9.rjWOd0/OBiM3pjLOA0NxyaWNHpHRKSx4UndKpc6'),
(10, 'the#', '', '$2y$10$DoCJ0csPtUFuEg3d71HMAuwSBzoXNDzxlmn6YStk.hQZL/AZZqzT2'),
(11, 'the#', 'the@gmail.com', '$2y$10$bMdh2GcZbZrfaN22Kp247eXheY/F7QVbPg8ppHfHhHuQm4PwdMvqG'),
(12, 'theif', 'theif@gmail.com', '$2y$10$cjl8JiQ79.Rki8Pl7ZhUIuPzgelH8Lu.z1E6CqEYTPcu9j5kmI9Dq'),
(13, 'rahema', 'rahema@gmail.com', '$2y$10$Ai1f2fgvsce5T/n9Se6fe.bv/yRGV9dFyTBqQlDIBe7.b7b4Dolku'),
(14, 'jt', 'jt@gmail.com', '$2y$10$YxadSCTkbr/YZf/aAkmNiuMcZCYe.qouDvxWLg/5XgAtnTA1kfsli');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`Brand_Id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`Cart_Id`),
  ADD KEY `User_Id` (`User_Id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Category_Id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_Id`),
  ADD KEY `Category_Id` (`Category_Id`),
  ADD KEY `Brand_Id` (`Brand_Id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `Brand_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `Cart_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `Category_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`User_Id`) REFERENCES `users` (`User_Id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`Category_Id`) REFERENCES `category` (`Category_Id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`Brand_Id`) REFERENCES `brand` (`Brand_Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
