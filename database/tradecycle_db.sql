-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 06, 2024 at 04:46 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tradecycle_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_carts`
--

CREATE TABLE `tbl_carts` (
  `cart_id` int(5) NOT NULL,
  `item_id` varchar(5) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `item_price` decimal(10,0) NOT NULL,
  `cart_price` float NOT NULL,
  `cart_qty` int(5) NOT NULL,
  `image1_path` varchar(30) NOT NULL,
  `user_id` varchar(5) NOT NULL,
  `cart_date` datetime(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_items`
--

CREATE TABLE `tbl_items` (
  `item_id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `item_name` varchar(50) NOT NULL,
  `item_category` varchar(50) NOT NULL,
  `item_price` decimal(10,0) NOT NULL,
  `item_quantity` int(4) NOT NULL,
  `item_desc` varchar(255) NOT NULL,
  `item_location` varchar(50) NOT NULL,
  `item_pickup` varchar(20) NOT NULL,
  `image1_path` varchar(30) NOT NULL,
  `image2_path` varchar(30) NOT NULL,
  `image3_path` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_items`
--

INSERT INTO `tbl_items` (`item_id`, `user_id`, `item_name`, `item_category`, `item_price`, `item_quantity`, `item_desc`, `item_location`, `item_pickup`, `image1_path`, `image2_path`, `image3_path`) VALUES
(5, 4, 'Iphone 14 pro max phone case', 'Mobile Phone', '30', 2, 'Color: Black\r\nMaterial: Silicon\r\nReasons to sold: Accidentally bought extra', '', 'Pick-up', 'assets/items/phonecase(1).jpeg', 'assets/items/phonecase.jpeg', 'assets/items/phonecase(3).jpeg'),
(6, 4, 'Neutrovis 3 -ply face mask', 'Health & Equipment', '15', 5, 'Different color available (purple, blue, yellow, pink); Earloop', '', 'Pick-up', 'assets/items/mask1.jpg', 'assets/items/mask2.png', 'assets/items/mask3.jpg'),
(7, 3, 'Ear Rings', 'Accessories', '200', 2, 'Stainless, fashion women earrings', '', 'Delivery', 'assets/items/earring.jpeg', 'assets/items/earring2.jpg', 'assets/items/earring3.jpg'),
(8, 3, 'Notebook', 'Books & Stationaries', '7', 9, 'A5 Size, single line notebook', '', 'Delivery', 'assets/items/notebook.jpg', 'assets/items/notebook 2.jpg', 'assets/items/notebook 3.jpg'),
(9, 3, 'Highlighter Pen', 'Books & Stationaries', '18', 19, 'Zebra Mildliner Highlighter Pen,various color available', '', 'Delivery', 'assets/items/highlighter.jpeg', 'assets/items/highlighter2.jpeg', 'assets/items/highlighter3.jpeg'),
(10, 5, 'Macbook Air 2020', 'Computer', '2500', 1, 'Color-Silver; 13-inch M1 chip, comes with adapter and charger', '', 'Pick-up', 'assets/items/macbook.jpg', 'assets/items/macbook2.jpg', 'assets/items/macbook3.jpeg'),
(11, 5, 'AirPods ', 'Gadgets', '430', 2, '2nd generation, still in good quality', '', 'Delivery', 'assets/items/airpods.jpeg', 'assets/items/airpods2.jpeg', 'assets/items/airpods3.png'),
(12, 5, 'Lightbulb', 'Furniture & Home Liv', '10', 3, 'Brand: IKEA', '', 'Pick-up', 'assets/items/lightbulb.jpeg', 'assets/items/lightbulb2.jpeg', 'assets/items/lightbulb3.jpg'),
(13, 3, 'Perfume', 'Beauty & Personal Care', '280', 1, 'Coco Chanel EAU DE PARFUM SPRAY 100ml', '', 'Pick-up', 'assets/items/coco.jpeg', 'assets/items/coco2.jpeg', 'assets/items/coco3.jpeg'),
(14, 3, 'Summer dress', 'Women Fashion', '20', 1, 'Summer floral dress, wore once only', '', 'Delivery', 'assets/items/dress.jpg', 'assets/items/dress2.jpeg', 'assets/items/dress3.jpeg'),
(15, 4, 'Nivea Men Face Wash', 'Beauty & Personal Care', '40', 3, 'Brand new never open before', '', 'Delivery', 'assets/items/niveaman.jpg', 'assets/items/niveaman2.jpeg', 'assets/items/niveaman3.jpeg'),
(16, 4, 'Nike Air Jordan', 'Men Fashion', '550', 1, 'Size UK5, black color, condition 99%', '', 'Pick-up', 'assets/items/jordan1.png', 'assets/items/jordan2.jpeg', 'assets/items/jordan3.jpeg'),
(17, 4, 'ADLV T-shirt', 'Men Fashion', '100', 2, 'Oversize fashion brand ADLV, condition 99%', '', 'Pick-up', 'assets/items/adlv.jpg', 'assets/items/adlv2.jpeg', 'assets/items/adlv3.jpeg'),
(18, 4, 'Computer Bag', 'Gadgets', '60', 1, 'M A H Computer Bag, color- black', '', 'Delivery', 'assets/items/bag.jpeg', 'assets/items/bag2.jpg', 'assets/items/bag3.jpg'),
(21, 2, 'Classic Backpack', 'Accessories', '200', 25, 'Good Quality', '', 'Delivery', 'assets/items/backpack.png', 'assets/items/backpack2.png', 'assets/items/backpack3.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `order_id` int(5) NOT NULL,
  `order_bill` varchar(8) NOT NULL,
  `item_id` int(5) NOT NULL,
  `order_paid` float NOT NULL,
  `user_id` varchar(5) NOT NULL,
  `uploader_id` varchar(5) NOT NULL,
  `order_date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `order_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_phone` varchar(12) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `name`, `user_name`, `user_email`, `user_phone`, `user_password`) VALUES
(1, 'Abu', 'Abu1234', 'Abu123@gmail.com', '012-2332532', '$2y$10$WeMOk3V5VFyDRJyLNhVRLudVt8gKyECGZnrd9HL6olYDgE6ASz4ia'),
(2, 'Goh Koon Loong', 'goh_koon_loong', 'gohkoonloong@gmail.com', '0185755357', '$2y$10$L2VFoFUYt3icS5Cz13/jiOXxV1NQVIRLMp8ggn7wnezHrbbbKwpv.'),
(3, 'Choong Qian Yu', 'Qian Yu', 'qianyu@gmail.com', '0119886776', '$2y$10$tq0bWH9i8qTw6JMQuhhSfOSo06m4Tnh3lk8JSBueWkCpJM/vK3QzS'),
(4, 'Pan Kah Heng', 'Samuel', 'pankahheng@gmail.com', '0165838247', '$2y$10$WdkwXD2kf6sdVLASSSqfPeB8ZK1lI75D7Vp0AQk7jZeb/5CDtejei'),
(5, 'Na Jae Min', 'Jae Min', 'najaemin@gmail.com', '0198649283', '$2y$10$dmJ30QsGTG5x6ds553gLJ.khHtb5mqqUbMYEgutHIInEouebNUJ9e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_carts`
--
ALTER TABLE `tbl_carts`
  ADD PRIMARY KEY (`cart_id`);

--
-- Indexes for table `tbl_items`
--
ALTER TABLE `tbl_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email_2` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_carts`
--
ALTER TABLE `tbl_carts`
  MODIFY `cart_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_items`
--
ALTER TABLE `tbl_items`
  MODIFY `item_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `order_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
