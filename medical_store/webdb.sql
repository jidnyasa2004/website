-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 17, 2025 at 07:40 PM
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
-- Table structure for table `credential`
--

CREATE TABLE `credential` (
  `u_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `user_type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `credential`
--

INSERT INTO `credential` (`u_id`, `username`, `email`, `password`, `user_type`) VALUES
(1, 'abc', 'abc@gmail.com', '1234', 'user'),
(2, 'jid', 'jidnyasapatil474@gmail.com', '1234', 'user'),
(3, 'jidnyasa', 'jidnyasap2004@gmail.com', '12345', 'user'),
(4, 'admin', 'jidnyasapatil474@gmail.com', '1111', 'admin'),
(5, 'admin', 'admin@gmail.com', '1111', 'admin'),
(7, 'admin', 'admin@gmail.com', '1111', 'admin'),
(8, 'new', 'new@gmail.com', '1234', 'user'),
(9, 'shree', 'shreejee@gmail.com', '1234', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `c_id` int(11) NOT NULL,
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
(11, 'raj', '1234567960', 'mulund east', 'raj@gmail.com', 2);

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
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `med_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `quantity` int(10) NOT NULL,
  `exp_date` date NOT NULL,
  `mfg_date` date NOT NULL,
  `price` int(20) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`med_id`, `name`, `description`, `type`, `quantity`, `exp_date`, `mfg_date`, `price`, `image_path`) VALUES
(16, 'Himalaya Koflet H Orange Flavo', ' Relief From Cough & Sore Throat Strip Of 6 Lozeng', 'tablet', 41, '2027-10-23', '2025-01-23', 35, 'uploads/himalayakoflet.webp'),
(17, ' Pentaloc 40mg Strip Of 15 Tab', 'Pentaloc tablet is a medicine used to reduce acid ', 'tablet', 50, '2027-05-05', '2025-01-23', 130, 'uploads/pentaloc.webp'),
(18, 'Cheston Cold New Formula Strip', 'Cheston Cold tablet is a combination medicine used', 'tablet', 48, '2027-11-23', '2024-07-10', 60, 'uploads/cheston.webp'),
(19, 'Cetrizine Bottle Of 60ml Syrup', 'Cetirizine is an antihistamine that is used to tre', 'syrup', 50, '2026-10-21', '2024-05-07', 43, 'uploads/cetrizine_bottle.webp'),
(20, 'Zerodol Sp Strip Of 10 Tablets', 'edical Description\r\nZerodol-SP tablet is a pain-re', 'tablet', 40, '2027-05-04', '2023-02-04', 140, 'uploads/zerodol_sp.webp'),
(21, ' Myospaz Forte Strip Of 10 Tab', 'Myospaz forte tablet is used for the symptomatic t', 'tablet', 50, '2025-05-11', '2022-12-11', 351, 'uploads/myospaz.webp'),
(22, 'Moktel Strip Of 15 Tablets', 'Moktel tablet is a health supplement containing es', 'tablet', 50, '2026-12-10', '2023-02-01', 207, 'uploads/moktel.webp'),
(23, 'Shelcal 500mg Strip Of 15 Tabl', 'Shelcal 500 Tablet is a vitamin and mineral supple', 'tablet', 40, '2026-03-03', '2023-01-01', 145, 'uploads/shelcal500.webp'),
(24, 'Martifur Mr 100mg Strip Of 10 ', 'Martifur MR 100 mg tablet is used for the treatmen', 'tablet', 50, '2026-12-12', '2022-04-02', 212, 'uploads/martifur.webp'),
(25, 'Drotin Ds 80mg Strip Of 15 Tab', 'Drotin DS tablets help relieve muscle spasms in th', 'tablet', 50, '2027-12-10', '2025-01-23', 250, 'uploads/drotin.webp'),
(26, 'Sinarest New Strip Of 15 Table', 'Sinarest New Tablet is a medication used to treat ', 'tablet', 50, '2026-10-22', '2025-01-29', 120, 'uploads/sinarest.webp'),
(27, 'Dolo 650mg Strip Of 15 Tablets', 'Dolo 650 tablets relieve pain, fever, headaches, t', 'tablet', 50, '2027-07-29', '2024-11-29', 35, 'uploads/dolo.webp'),
(28, 'New Saridon Strip Of 10 Tablet', 'Saridon is a remedy for Headache trusted since 193', 'tablet', 50, '2027-07-29', '2024-05-29', 50, 'uploads/saridon.webp'),
(29, 'Cofsils Ginger Lemon Lozenges ', 'These lozenges from Cofsils are perfect during col', 'tablet', 40, '2025-05-02', '2024-01-14', 35, 'uploads/cofsils.webp'),
(30, 'Ecosprin 75mg Strip Of 14 Tabl', 'Ecosprin 75 tablet is a medication that helps prev', 'tablet', 40, '2025-10-22', '2023-12-14', 6, 'uploads/ecosprin.webp'),
(31, ' Evion 600mg Strip Of 10 Capsu', 'These capsules play a crucial role in nourishing y', 'tablet', 40, '2025-11-14', '2024-01-14', 60, 'uploads/evion.webp'),
(32, 'Liveasy Wellness Burns Cream T', 'LivEasy Wellness Burns Cream is a topical ointment', 'cream', 1, '2027-12-14', '2025-02-28', 85, 'uploads/liveasycream.webp'),
(33, 'Venusia Derm Moisturizing Crea', 'Venusia DERM Moisturizing Lotion on your face and ', 'cream', 1, '2026-10-14', '2024-06-14', 310, 'uploads/venusiacream.webp'),
(34, ' Himalaya Baby Cream Tube', 'Himalaya Baby Cream is a nourishing baby cream tha', 'cream', 12, '2027-10-14', '2024-05-14', 200, 'uploads/himalayababycream.webp'),
(35, 'Liveasy Wellness Clotrimazole ', 'Fungal infections are quite common in a hot and hu', 'cream', 15, '2026-05-14', '2024-01-14', 68, 'uploads/clotrimazolecream.webp');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `order_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `no_of_items` int(11) NOT NULL,
  `med_id` int(11) NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_name`, `order_date`, `total_amount`, `no_of_items`, `med_id`, `date_time`) VALUES
(12345, 'suraj', '0000-00-00', 150.00, 10, 15, '2025-01-27 22:30:00'),
(12346, '', '0000-00-00', 0.00, 1, 11, '2025-01-27 18:29:22'),
(12347, '', '0000-00-00', 0.00, 1, 12, '2025-01-27 18:38:56'),
(12348, '', '0000-00-00', 0.00, 1, 12, '2025-01-29 16:11:15'),
(12349, '', '0000-00-00', 0.00, 1, 13, '2025-01-29 16:11:17'),
(12350, '', '0000-00-00', 0.00, 1, 26, '2025-02-06 07:57:42'),
(12351, '', '0000-00-00', 0.00, 1, 20, '2025-02-06 18:56:56'),
(12352, '', '0000-00-00', 0.00, 1, 21, '2025-02-06 18:57:01'),
(12353, '', '0000-00-00', 0.00, 1, 17, '2025-02-06 19:16:45'),
(12354, '', '0000-00-00', 0.00, 1, 23, '2025-02-14 08:29:02'),
(12355, '', '0000-00-00', 0.00, 1, 17, '2025-02-14 08:30:08'),
(12356, '', '0000-00-00', 0.00, 1, 18, '2025-02-14 08:30:11'),
(12357, '', '0000-00-00', 0.00, 1, 31, '2025-02-17 07:15:53'),
(12358, '', '0000-00-00', 0.00, 1, 24, '2025-02-17 08:26:54'),
(12359, '', '0000-00-00', 0.00, 1, 25, '2025-02-17 08:26:59'),
(12360, '', '0000-00-00', 0.00, 1, 25, '2025-02-17 09:20:08'),
(12361, '', '0000-00-00', 0.00, 1, 19, '2025-02-17 10:48:55'),
(12362, '', '0000-00-00', 0.00, 1, 20, '2025-02-17 10:49:19');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `quantity` int(20) NOT NULL,
  `med_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `name`, `description`, `type`, `quantity`, `med_id`) VALUES
(6, '', '', 'syrup', 30, 19),
(7, '', '', 'tablet', 41, 16),
(8, '', '', 'tablet', 50, 17),
(11, '', '', 'syrup', 50, 19),
(12, '', '', 'tablet', 40, 20),
(13, '', '', 'tablet', 50, 21),
(14, '', '', 'tablet', 50, 22),
(15, '', '', 'tablet', 40, 23),
(16, '', '', 'tablet', 50, 24),
(17, '', '', 'tablet', 50, 25),
(18, '', '', 'tablet', 50, 26),
(19, '', '', 'tablet', 50, 27),
(20, '', '', 'tablet', 50, 28),
(21, '', '', 'cream', 30, 34);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `credential`
--
ALTER TABLE `credential`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fb_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`med_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `fk_med_id` (`med_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `credential`
--
ALTER TABLE `credential`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `med_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12363;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fk_med_id` FOREIGN KEY (`med_id`) REFERENCES `medicines` (`med_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
