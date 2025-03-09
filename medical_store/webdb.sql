-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2025 at 08:07 PM
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
-- Database: `webdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` varchar(10) NOT NULL,
  `u_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `role` enum('Super Admin','Admin') DEFAULT 'Super Admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` varchar(10) NOT NULL,
  `u_id` int(11) NOT NULL,
  `med_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `message`) VALUES
(1, 'jid', 'jid@gmail.com', 'hgnhm');

-- --------------------------------------------------------

--
-- Table structure for table `credential`
--

CREATE TABLE `credential` (
  `u_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `user_type` enum('Customer','Admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credential`
--

INSERT INTO `credential` (`u_id`, `username`, `email`, `password`, `user_type`) VALUES
(1, 'abc', 'abc@gmail.com', 'Jidnyasa@12', 'Customer'),
(2, 'jid', 'jidnyasapatil474@gmail.com', 'Jidnyasa@12', 'Customer'),
(3, 'jidnyasa', 'jidnyasap2004@gmail.com', 'Jidnyasa@12', 'Customer'),
(4, 'admin', 'jidnyasapatil474@gmail.com', 'Jidnyasa@12', 'Admin'),
(5, 'admin', 'admin@gmail.com', 'Jidnyasa@12', 'Admin'),
(7, 'admin', 'admin@gmail.com', 'Jidnyasa@12', 'Admin'),
(8, 'new', 'new@gmail.com', '1234', 'Customer'),
(9, 'shree', 'shreejee@gmail.com', '1234', 'Customer'),
(11, 'Vipul04', 'vipulbhoir027@gmail.com', 'Vipul@12', 'Customer');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `c_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `u_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`c_id`, `name`, `contact`, `address`, `email`, `u_id`) VALUES
('C00001', 'Vipul Bhoir', '9000000001', 'G.B.Road', 'vipulbhoir027@gmail.com', 11);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `fb_id` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `feedback` varchar(200) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`fb_id`, `name`, `feedback`, `date`) VALUES
('', 'jid', 'hkjkj', '2025-02-17'),
('FB0002', 'jid', 'hkjkj', '2025-02-17'),
('FB0003', 'new', 'hii', '2025-02-17'),
('FB0004', 'shree', 'hreerg', '2025-02-17'),
('FB0005', 'jid', 'hello', '2025-02-17'),
('FB0006', 'jid', 'hii', '2025-02-17');

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `med_id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `type` enum('Tablet','Cream','Syrup') NOT NULL,
  `price` int(11) NOT NULL,
  `mfg_date` date NOT NULL,
  `exp_date` date NOT NULL,
  `med_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`med_id`, `name`, `description`, `type`, `price`, `mfg_date`, `exp_date`, `med_image`) VALUES
('M00001', 'Himalaya Koflet H Orange Flavour', 'good', 'Tablet', 35, '2025-03-06', '2025-05-28', 'uploads/himalayakoflet.webp'),
('M00002', 'Pentaloc 40mg Strip of 15 Tab', 'intake with water', 'Tablet', 130, '2025-03-07', '2025-09-30', 'uploads/pentaloc.webp'),
('M00003', 'Cheston Cold New Formula Strip', 'multi color tablets', 'Tablet', 60, '2025-03-06', '2025-09-02', 'uploads/cheston.webp'),
('M00004', 'Moktel Strip of 15 Tablets', 'bad taste', 'Tablet', 260, '2025-03-06', '2025-12-03', 'uploads/moktel.webp'),
('M00005', 'Cetrizine Bottle of 60ml Syrup', 'good taste', 'Syrup', 43, '2025-03-06', '2025-10-06', 'uploads/cetrizine_bottle.webp');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(20) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `no_of_items` int(11) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp(),
  `c_id` varchar(20) NOT NULL,
  `order_status` enum('Pending','Completed') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `total_amount`, `no_of_items`, `date_time`, `c_id`, `order_status`) VALUES
('O00001', 1160, 7, '2025-03-07 00:07:40', 'C00001', 'Pending'),
('O00002', 120, 2, '2025-03-07 00:34:38', 'C00001', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `item_id` varchar(20) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `med_id` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `med_id`, `quantity`, `price`) VALUES
('OI00001', 'O00001', 'M00003', 2, 120),
('OI00002', 'O00001', 'M00002', 2, 260),
('OI00003', 'O00001', 'M00004', 3, 780),
('OI00004', 'O00002', 'M00003', 2, 120);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` varchar(20) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_mode` enum('Online','Offline') NOT NULL,
  `payment_status` enum('Pending','Completed','Failed') NOT NULL DEFAULT 'Pending',
  `payment_datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `transaction_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `order_id`, `amount`, `payment_mode`, `payment_status`, `payment_datetime`, `transaction_id`) VALUES
('P00001', 'O00001', 1160, 'Offline', 'Pending', '2025-03-07 00:07:40', NULL),
('P00002', 'O00002', 120, 'Offline', 'Pending', '2025-03-07 00:34:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stk_id` varchar(20) NOT NULL,
  `med_id` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stk_id`, `med_id`, `quantity`) VALUES
('S00001', 'M00001', 50),
('S00002', 'M00002', 50),
('S00003', 'M00003', 100),
('S00004', 'M00004', 75),
('S00005', 'M00005', 50);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `u_id` (`u_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `u_id` (`u_id`),
  ADD KEY `med_id` (`med_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credential`
--
ALTER TABLE `credential`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fb_id`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`med_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `c_id` (`c_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `med_id` (`med_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stk_id`),
  ADD KEY `med_id` (`med_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `credential`
--
ALTER TABLE `credential`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `credential` (`u_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `credential` (`u_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`med_id`) REFERENCES `medicine` (`med_id`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`u_id`) REFERENCES `credential` (`u_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `customer` (`c_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`med_id`) REFERENCES `medicine` (`med_id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`med_id`) REFERENCES `medicine` (`med_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
