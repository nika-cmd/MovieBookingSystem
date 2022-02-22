-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 14, 2021 at 04:54 AM
-- Server version: 8.0.17
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cpsc471_project`
--
DROP DATABASE IF EXISTS CPSC471_PROJECT;
CREATE DATABASE CPSC471_PROJECT;
USE CPSC471_PROJECT;

-- used in customer
DROP PROCEDURE IF EXISTS addCustomerAccount;
DROP PROCEDURE IF EXISTS cancelBookingSeats;
DROP PROCEDURE IF EXISTS checkCustomerAccount;
DROP PROCEDURE IF EXISTS createBooking;
DROP PROCEDURE IF EXISTS createContainsFoodOrder;
DROP PROCEDURE IF EXISTS createFoodOrder;
DROP PROCEDURE IF EXISTS deleteBooking;
DROP PROCEDURE IF EXISTS deleteCustomerFood;
DROP PROCEDURE IF EXISTS deleteCustomerFoodOrder;
DROP PROCEDURE IF EXISTS getAllFood;
DROP PROCEDURE IF EXISTS getAllFoodCost;
DROP PROCEDURE IF EXISTS getAvailableMovieShowings;
DROP PROCEDURE IF EXISTS getBookingCost;
DROP PROCEDURE IF EXISTS getBookingCustomer;
DROP PROCEDURE IF EXISTS getCustomerBookings;
DROP PROCEDURE IF EXISTS getCustomerFood;
DROP PROCEDURE IF EXISTS getCustomerInfo;
DROP PROCEDURE IF EXISTS getCustomerSeat;
DROP PROCEDURE IF EXISTS getFoodByID;
DROP PROCEDURE IF EXISTS getFoodPrice;
DROP PROCEDURE IF EXISTS getFoodQuantity;
DROP PROCEDURE IF EXISTS getMovieID;
DROP PROCEDURE IF EXISTS getMovieShowingFood;
DROP PROCEDURE IF EXISTS getMovieShowingSeats;
DROP PROCEDURE IF EXISTS getOrderNumber;
DROP PROCEDURE IF EXISTS updateBookingCost;
DROP PROCEDURE IF EXISTS updateCostNofSeatsBooking;

-- used in employee
DROP PROCEDURE IF EXISTS addEmployeeAccount;
DROP PROCEDURE IF EXISTS checkManagerSSN;
DROP PROCEDURE IF EXISTS getEmployeeInfo;
DROP PROCEDURE IF EXISTS getEmployeeSSN;
DROP PROCEDURE IF EXISTS getManagerAndEmployeeInfo;
DROP PROCEDURE IF EXISTS getFoodItemsbyOrderNo;
DROP PROCEDURE IF EXISTS getFoodOrders;
DROP PROCEDURE IF EXISTS getSeatIDs;
DROP PROCEDURE IF EXISTS updateDeliverStatus;

-- used in manager

DROP PROCEDURE IF EXISTS addMovie;
DROP PROCEDURE IF EXISTS addMovieShowing;
DROP PROCEDURE IF EXISTS addSeatsForShowing;

DROP PROCEDURE IF EXISTS getAllMovie;
DROP PROCEDURE IF EXISTS getAllShowings;
DROP PROCEDURE IF EXISTS getMovieLength;
DROP PROCEDURE IF EXISTS getShowingsInRoom;
DROP PROCEDURE IF EXISTS getTheatreLocation;
DROP PROCEDURE IF EXISTS getTheatreRooms;

DROP PROCEDURE IF EXISTS removeMovie;
DROP PROCEDURE IF EXISTS removeMovieShowing;


DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `addCustomerAccount` (IN `cfirstname` VARCHAR(20), IN `clastname` VARCHAR(20), IN `cemail` VARCHAR(20), IN `cpassword` VARCHAR(20))  NO SQL
BEGIN
INSERT INTO Customer (FirstName, LastName, Email, Password) VALUES (cfirstname, clastname, cemail, cpassword);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addEmployeeAccount` (IN `essn` INT, IN `fname` VARCHAR(20), IN `lname` VARCHAR(20), IN `dob` DATE, IN `address` VARCHAR(50), IN `mssn` INT, IN `theatreid` INT, IN `eemail` VARCHAR(50), IN `epassword` VARCHAR(50))  NO SQL
BEGIN
INSERT INTO Employee (SSN, First_Name, Last_Name, DOB, Address, Mgr_SSN, TheatreID, Email_Address, Password) VALUES 
(essn, fname, lname, dob, address, mssn, theatreid, eemail, epassword);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addMovie` (IN `mname` VARCHAR(25), IN `mgenre` VARCHAR(25), IN `mduration` INT)  NO SQL
BEGIN
INSERT INTO Movie(Name, Genre, Duration) VALUES (mname, mgenre, mduration);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addMovieShowing` (IN `dt` VARCHAR(25), IN `rm` VARCHAR(25), IN `mid` INT)  NO SQL
BEGIN
INSERT INTO movie_showing VALUES (dt, rm, mid);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addSeatsForShowing` (IN `dt` DATETIME, IN `roomNo` INT)  NO SQL
BEGIN
INSERT INTO seat (SeatID, Seat_Type, Room_No, CustomerID, DateTime) VALUES
('A01', 'Wheelchair', roomNo, NULL, dt),
('A02', 'Wheelchair', roomNo, NULL, dt),
('A03', 'Normal', roomNo, NULL, dt),
('A04', 'Normal', roomNo, NULL, dt),
('A05', 'Normal', roomNo, NULL, dt),
('B01', 'Wheelchair', roomNo, NULL, dt),
('B02', 'Wheelchair', roomNo, NULL, dt),
('B03', 'Normal', roomNo, NULL, dt),
('B04', 'Normal', roomNo, NULL, dt),
('B05', 'Normal', roomNo, NULL, dt),
('C01', 'Normal', roomNo, NULL, dt),
('C02', 'Normal', roomNo, NULL, dt),
('C03', 'Normal', roomNo, NULL, dt),
('C04', 'Normal', roomNo, NULL, dt),
('C05', 'Normal', roomNo, NULL, dt),
('D01', 'Normal', roomNo, NULL, dt),
('D02', 'Normal', roomNo, NULL, dt),
('D03', 'Normal', roomNo, NULL, dt),
('D04', 'Normal', roomNo, NULL, dt),
('D05', 'Normal', roomNo, NULL, dt),
('E01', 'Normal', roomNo, NULL, dt),
('E02', 'Normal', roomNo, NULL, dt),
('E03', 'Normal', roomNo, NULL, dt),
('E04', 'Normal', roomNo, NULL, dt),
('E05', 'Normal', roomNo, NULL, dt),
('F01', 'Normal', roomNo, NULL, dt),
('F02', 'Normal', roomNo, NULL, dt),
('F03', 'Normal', roomNo, NULL, dt),
('F04', 'Normal', roomNo, NULL, dt),
('F05', 'Normal', roomNo, NULL, dt);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cancelBookingSeats` (IN `customerID` INT(11), IN `roomNo` INT(11), IN `dateTime` DATETIME(6))  NO SQL
BEGIN
UPDATE Seat SET seat.CustomerID = NULL WHERE seat.CustomerID = customerID AND seat.Room_No = roomNo AND seat.DateTime = dateTime;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkCustomerAccount` (IN `cemail` VARCHAR(20), IN `cpassword` VARCHAR(20))  NO SQL
BEGIN
SELECT CustomerID FROM Customer WHERE Customer.Email = cemail AND Customer.Password = cpassword;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `checkManagerSSN` (IN `mssn` INT)  NO SQL
BEGIN
SELECT Mgr_SSN FROM Cinema_Theatre WHERE Mgr_SSN = mssn;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createBooking` (IN `customerID` INT(11), IN `movieID` INT(11), IN `dateTime` DATETIME(6), IN `cost` INT(11), IN `noOfSeats` INT(11), IN `roomNo` INT(11))  NO SQL
BEGIN
INSERT INTO Booking (booking.CustomerID, booking.MovieID, booking.DateTime, booking.Cost, booking.No_of_seats, booking.Room_No) VALUES (customerID, movieID, dateTime, cost, noOfSeats, roomNo);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createContainsFoodOrder` (IN `orderNumber` INT(11), IN `customerID` INT(11), IN `foodID` INT(11), IN `quantity` INT(11))  NO SQL
BEGIN
INSERT INTO contains_food_order (Order_Number, CustomerID, FoodID, Quantity) VALUES (orderNumber, customerID, foodID, quantity);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createFoodOrder` (IN `customerID` INT(11), IN `roomNo` INT(11), IN `dateTime` DATETIME(6))  NO SQL
BEGIN
INSERT INTO food_order (CustomerID, RoomNo, DateTime, ESSN, Deliver_Status) VALUES (customerID, roomNo, dateTime, NULL, false);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteBooking` (IN `customerID` INT(11), IN `movieID` INT(11), IN `dateTime` DATETIME(6), IN `roomNo` INT(11))  NO SQL
BEGIN
DELETE FROM Booking WHERE booking.CustomerID = customerID AND booking.MovieID = movieID AND booking.DateTime = dateTime AND booking.Room_No = roomNo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteCustomerFood` (IN `orderNumber` INT(11), IN `customerID` INT(11))  NO SQL
BEGIN
DELETE FROM contains_food_order WHERE contains_food_order.Order_Number = orderNumber AND contains_food_order.CustomerID = customerID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteCustomerFoodOrder` (IN `orderNumber` INT(11), IN `customerID` INT(11), IN `roomNo` INT(11), IN `dateTime` DATETIME(6))  NO SQL
BEGIN
DELETE FROM food_order WHERE food_order.Order_Number = orderNumber AND food_order.CustomerID = customerID AND food_order.RoomNo = roomNo AND food_order.DateTime = dateTime;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllFood` ()  NO SQL
BEGIN
SELECT * FROM food;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllFoodCost` (IN `orderNumber` INT(11), IN `customerID` INT(11))  NO SQL
BEGIN
SELECT contains_food_order.Quantity, Food.Price  FROM contains_food_order, Food WHERE contains_food_order.Order_Number = orderNumber AND contains_food_order.CustomerID = customerID AND contains_food_order.FoodID = Food.FoodID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllMovie` ()  NO SQL
BEGIN
SELECT * FROM movie;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllShowings` (IN `id` INT)  NO SQL
BEGIN
SELECT m.Name "Name", ms.RoomNo "RoomNo", ms.DateTime "DateTime" 
FROM movie_showing as ms, movie as m, theatre_room as t
WHERE t.Room_No = ms.RoomNo and  m.MovieID = ms.MovieID and t.TheatreID = id
ORDER BY DateTime;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAvailableMovieShowings` (IN `dateTime` DATETIME(6))  NO SQL
BEGIN
SELECT Cinema_Theatre.Location, Movie_Showing.RoomNo, Movie_Showing.DateTime, Movie.Name, Movie.Genre, Movie.Duration FROM Movie_Showing, Movie, Theatre_Room, Cinema_Theatre WHERE Movie_Showing.DateTime > dateTime AND Movie_Showing.MovieID = Movie.MovieID AND Movie_Showing.RoomNo = Theatre_Room.Room_No AND Theatre_Room.TheatreID = Cinema_Theatre.TheatreID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBookingCost` (IN `customerID` INT(11), IN `movieID` INT(11), IN `dateTime` DATETIME(6), IN `roomNo` INT(11))  NO SQL
BEGIN
SELECT booking.Cost FROM Booking WHERE booking.CustomerID = customerID AND booking.MovieID = movieID AND booking.DateTime = dateTime AND booking.Room_No = roomNo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getBookingCustomer` (IN `customerID` INT(11), IN `movieID` INT(11), IN `dateTime` DATETIME(6), IN `roomNo` INT(11))  NO SQL
BEGIN
SELECT booking.CustomerID FROM Booking WHERE booking.CustomerID = customerID AND booking.MovieID = movieID AND booking.DateTime = dateTime AND booking.Room_No = roomNo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCustomerBookings` (IN `dateTime` DATETIME(6), IN `customerID` INT(11))  NO SQL
BEGIN
SELECT Movie.Name, Movie.Genre, Movie.Duration, Booking.DateTime, Booking.Cost, Booking.Room_No, Cinema_Theatre.Location, Booking.MovieID FROM Movie, Booking, Cinema_Theatre, Theatre_Room WHERE DATE(Booking.DateTime) > dateTime AND Booking.CustomerID = customerID AND Booking.MovieID = Movie.MovieID AND Booking.Room_No = Theatre_Room.Room_No AND Theatre_Room.TheatreID = Cinema_Theatre.TheatreID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCustomerFood` (IN `customerID` INT(11), IN `roomNo` INT(11), IN `dateTime` DATETIME(6))  NO SQL
BEGIN
SELECT contains_food_order.FoodID, contains_food_order.Quantity FROM food_order, contains_food_order WHERE food_order.CustomerID = customerID AND food_order.RoomNo = roomNo AND food_order.DateTime = dateTime AND food_order.Order_Number = contains_food_order.Order_Number AND food_order.CustomerID = contains_food_order.CustomerID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCustomerInfo` (IN `cemail` VARCHAR(20), IN `cpassword` VARCHAR(20))  NO SQL
BEGIN
SELECT * FROM customer WHERE customer.Email = cemail AND customer.Password = cpassword;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCustomerSeat` (IN `roomNo` INT(11), IN `customerID` INT(11), IN `dateTime` DATETIME(6))  NO SQL
BEGIN
SELECT Seat.SeatID, Seat.Seat_Type FROM Seat WHERE Seat.Room_No = roomNo AND Seat.CustomerID = customerID AND Seat.DateTime = dateTime;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getEmployeeInfo` (IN `eemail` VARCHAR(20), IN `epassword` VARCHAR(20))  NO SQL
BEGIN
SELECT * FROM Employee WHERE Employee.Email_Address = eemail AND Employee.Password = epassword;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getEmployeeSSN` (IN `eemail` VARCHAR(20), IN `epassword` VARCHAR(20))  NO SQL
BEGIN
SELECT SSN FROM Employee WHERE Employee.Email_Address = eemail AND Employee.Password = epassword;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getFoodByID` (IN `food` INT(11))  NO SQL
BEGIN
SELECT * FROM Food WHERE FoodID = food;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getFoodItemsbyOrderNo` (IN `orderNo` INT)  NO SQL
BEGIN
SELECT * FROM Contains_Food_Order, Food WHERE Contains_Food_Order.Order_Number = orderNo AND Contains_Food_Order.FoodID = Food.FoodID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getFoodOrders` (IN `delivered` INT, IN `today` DATETIME, IN `eemail` VARCHAR(50))  NO SQL
BEGIN
SELECT DISTINCT Food_Order.Order_Number, Food_Order.DateTime, Seat.Room_No, Food_Order.CustomerID, Food_Order.Deliver_Status, Food_Order.ESSN
From Food_Order, Seat, Theatre_Room, Employee 
WHERE Deliver_Status <> delivered AND Food_Order.DateTime > today AND Seat.DateTime = Food_Order.DateTime AND Seat.CustomerID = Food_Order.CustomerID AND Seat.Room_No = Theatre_Room.Room_No AND Theatre_Room.TheatreID = Employee.TheatreID AND Employee.Email_Address = eemail
ORDER BY Food_Order.Deliver_Status DESC, Food_Order.DateTime;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getFoodPrice` (IN `food` INT(11))  NO SQL
BEGIN
SELECT Food.Price FROM Food WHERE Food.FoodID = food;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getFoodQuantity` (IN `orderNumber` INT(11), IN `customerID` INT(11), IN `foodID` INT(11))  NO SQL
BEGIN
SELECT contains_food_order.Quantity FROM contains_food_order WHERE contains_food_order.Order_Number = orderNumber AND contains_food_order.CustomerID = customerID AND contains_food_order.FoodID = foodID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getManagerAndTheatreInfo` (IN `loc` VARCHAR(50))  NO SQL
BEGIN
SELECT Mgr_SSN, TheatreID FROM Cinema_Theatre WHERE Location = loc;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getMovieID` (IN `dateTime` DATETIME(6), IN `roomNo` INT(11))  NO SQL
BEGIN
SELECT movie_showing.MovieID FROM movie_showing WHERE movie_showing.DateTime = dateTime AND movie_showing.RoomNo = roomNo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getMovieLength` (IN `id` INT)  NO SQL
BEGIN
SELECT Duration FROM movie WHERE MovieID = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getMovieShowingFood` (IN `dateTime` DATETIME(6), IN `customerID` INT(11))  NO SQL
BEGIN
SELECT Movie.Name, Booking.DateTime, Booking.Room_No FROM Movie, Booking WHERE DATE(Booking.DateTime) > dateTime AND Booking.CustomerID = customerID AND Booking.MovieID = Movie.MovieID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getMovieShowingSeats` (IN `roomNo` INT(11), IN `dateTime` DATETIME(6))  NO SQL
BEGIN
SELECT Seat.SeatID, Seat.Seat_Type FROM Seat, Movie_Showing WHERE Movie_Showing.RoomNo = roomNo AND Seat.DateTime = dateTime AND Movie_Showing.RoomNo = Seat.Room_No AND Seat.CustomerID IS NULL AND Movie_Showing.DateTime = dateTime;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getOrderNumber` (IN `customerID` INT(11), IN `roomNo` INT(11), IN `dateTime` DATETIME(6))  NO SQL
BEGIN
SELECT food_order.Order_Number FROM food_order WHERE food_order.CustomerID = customerID AND food_order.RoomNo = roomNo AND food_order.DateTime = dateTime;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getSeatIDs` (IN `customerid` VARCHAR(50), IN `dt` DATETIME)  NO SQL
BEGIN
SELECT Seat.SeatID FROM Seat WHERE Seat.CustomerID = customerid AND Seat.DateTime = dt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getShowingsInRoom` (IN `room` INT)  NO SQL
BEGIN
SELECT m.Name "Name", m.Duration "Duration", ms.RoomNo "RoomNo", ms.DateTime "DateTime" 
FROM movie_showing as ms, movie as m, theatre_room as t
WHERE t.Room_No = ms.RoomNo and  m.MovieID = ms.MovieID and t.Room_No = room
ORDER BY DateTime;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getTheatreLocation` (IN `id` INT)  NO SQL
BEGIN
SELECT Location FROM cinema_theatre WHERE TheatreID = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getTheatreRooms` (IN `id` INT)  NO SQL
BEGIN
SELECT Room_No FROM theatre_room WHERE TheatreID= id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `removeMovie` (IN `id` INT)  NO SQL
BEGIN 
DELETE FROM movie WHERE MovieID = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `removeMovieShowing` (IN `date` INT, IN `room` INT)  NO SQL
BEGIN 
DELETE FROM movie_showing WHERE DateTime = date AND RoomNo = room;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateBookingCost` (IN `cost` INT(11), IN `customerID` INT(11), IN `movieID` INT(11), IN `dateTime` DATETIME(6), IN `roomNo` INT(11))  NO SQL
BEGIN
UPDATE Booking SET booking.Cost = cost WHERE booking.CustomerID = customerID AND booking.MovieID = movieID AND booking.DateTime = dateTime AND booking.Room_No = roomNo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCostNofSeatsBooking` (IN `cost` INT(11), IN `noOfSeats` INT(11), IN `customerID` INT(11), IN `movieID` INT(11), IN `dateTime` DATETIME(6), IN `roomNo` INT(11))  NO SQL
BEGIN
UPDATE Booking SET booking.Cost = cost, booking.No_of_seats = noOfSeats WHERE booking.CustomerID = customerID AND booking.MovieID = movieID AND booking.DateTime = dateTime AND booking.Room_No = roomNo;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateCustomerSeat` (IN `cID` INT(11), IN `sID` INT(11), IN `roomNo` INT(11), IN `dateTime` DATETIME(6))  NO SQL
BEGIN
UPDATE Seat SET seat.CustomerID = cID WHERE seat.SeatID = sID AND seat.Room_No = roomNo AND seat.DateTime = dateTime;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateDeliverStatus` (IN `Essn` INT, IN `dstatus` INT, IN `orderNo` INT, IN `customerID` INT, IN `roomNo` INT, IN `dt` DATETIME)  NO SQL
BEGIN
UPDATE Food_Order SET ESSN = Essn, Deliver_Status = dstatus WHERE Order_Number = orderNo AND CustomerID = customerID AND RoomNo = roomNo AND Food_Order.DateTime = dt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateQuantity` (IN `quantity` INT(11), IN `orderNumber` INT(11), IN `customerID` INT(11), IN `foodID` INT(11))  NO SQL
BEGIN
UPDATE contains_food_order SET contains_food_order.Quantity = quantity WHERE contains_food_order.Order_Number = orderNumber AND contains_food_order.CustomerID = customerID AND contains_food_order.FoodID = foodID;
END$$

DELIMITER ;

-- --------------------------------------------------------
/* TABLE STRUCTURES */

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--
DROP TABLE IF EXISTS booking;
CREATE TABLE `booking` (
  `CustomerID` int NOT NULL,
  `MovieID` int NOT NULL,
  `DateTime` datetime NOT NULL,
  `Cost` int NOT NULL,
  `No_of_seats` int NOT NULL,
  `Room_No` int NOT NULL
);

-- populating booking

INSERT INTO `booking`(`CustomerID`, `MovieID`, `DateTime`, `Cost`, `No_of_seats`, `Room_No`) VALUES
(1, 1, '2021-12-25 13:30:00', 30, 2, 2),
(2, 1, '2021-12-25 13:30:00', 42, 2, 2),
(2, 1, '2021-12-25 17:30:00', 14, 1, 2),
(3, 1, '2021-12-25 13:30:00', 41, 2, 2),
(4, 2, '2021-12-25 15:30:00', 18, 1, 6);
-- --------------------------------------------------------

--
-- Table structure for table `cinema_theatre`
--
DROP TABLE IF EXISTS cinema_theatre;
CREATE TABLE `cinema_theatre` (
  `TheatreID` int NOT NULL,
  `Location` varchar(25) NOT NULL,
  `Mgr_SSN` int DEFAULT NULL
) ;

-- populating cinema_theatre table

INSERT INTO `cinema_theatre` (`TheatreID`, `Location`, `Mgr_SSN`) VALUES
(1, 'Calgary', 1),
(2, 'Okotoks', 4);

-- --------------------------------------------------------

--
-- Table structure for table `contains_food_order`
--
DROP TABLE IF EXISTS contains_food_order;
CREATE TABLE `contains_food_order` (
  `Order_Number` int NOT NULL,
  `CustomerID` int NOT NULL,
  `FoodID` int NOT NULL,
  `Quantity` int NOT NULL
);

-- populating contains_food_order

INSERT INTO `contains_food_order` (`Order_Number`, `CustomerID`, `FoodID`, `Quantity`) VALUES
(1, 1, 4, 1),
(1, 1, 10, 1),
(2, 2, 1, 1),
(2, 2, 22, 2),
(3, 3, 3, 2),
(3, 3, 19, 1),
(4, 4, 16, 1),
(5, 2, 15, 1);
-- --------------------------------------------------------

--
-- Table structure for table `customer`
--
DROP TABLE IF EXISTS customer;
CREATE TABLE `customer` (
  `CustomerID` int NOT NULL,
  `FirstName` varchar(20) NOT NULL,
  `LastName` varchar(20) NOT NULL,
  `Email` varchar(20) NOT NULL,
  `Password` varchar(20) NOT NULL
);
  
  
-- populating customer table

INSERT INTO `customer` (`CustomerID`, `FirstName`, `LastName`, `Email`, `Password`) VALUES
(1, 'Sanchit', 'Kumar', 'sanchitk99@gmail.com', 'Sanchit'),
(2, 'Sara', 'Lin', 'saral@gmail.com', '12345'),
(3, 'Oliver', 'Yang', 'olivery@gmail.com', '12345'),
(4, 'Mary', 'Jane',  'maryj@gmail.com', '12345'),
(12, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--
DROP TABLE IF EXISTS employee;
CREATE TABLE `employee` (
  `SSN` int NOT NULL,
  `First_Name` varchar(25) NOT NULL,
  `Last_Name` varchar(25)  NOT NULL,
  `DOB` date NOT NULL,
  `Address` varchar(25) NOT NULL,
  `Mgr_SSN` int DEFAULT NULL,
  `TheatreID` int NOT NULL,
  `Email_Address` varchar(25)  NOT NULL,
  `Password` varchar(25) NOT NULL
);

-- populating employee table

 INSERT INTO employee (SSN, First_Name, Last_Name, DOB, Address, Mgr_SSN, TheatreID, Email_Address, Password) VALUES
 (1, 'Sanchit', 'Kumar', '1929-12-03', '52 Rainbow Road', null, 1, 'sanchitk99@gmail.com', '12345'),
 (2, 'Alliana', 'Dela Pena', '1941-05-24', '52 Rainbow Road', 1, 1, 'allianad@gmail.com', '12345'),
 (3, 'Nika', 'Magadia', '1982-03-12', '52 Rainbow Road', 1, 1, 'nikam@gmail.com', '12345'),
 (4, 'Ryan', 'Potter', '1990-01-23', '123 Name St', null, 2, 'ryanp@email.com', '12345'),
 (5, 'Amanda','McKay', '1992-11-11', '12 Another St', 4, 2, 'amandam@email.com', '12345');
-- --------------------------------------------------------

--
-- Table structure for table `food`
--
DROP TABLE IF EXISTS food;
CREATE TABLE `food` (
  `FoodID` int NOT NULL,
  `Size` varchar(1) NOT NULL,
  `Description` varchar(25) NOT NULL,
  `Price` int NOT NULL,
  `Popcorn` tinyint(1) NOT NULL,
  `Drink` tinyint(1) NOT NULL,
  `Candy` tinyint(1) NOT NULL,
  `Poutine` tinyint(1) NOT NULL,
  `Nacho` tinyint(1) NOT NULL
);

-- populating food table
-- S small, M medium, L large, R regular

INSERT INTO `food` (`FoodID`, `Size`, `Description`, `Price`, `Popcorn`, `Drink`, `Candy`, `Poutine`, `Nacho`) VALUES
(1, 'S',  'Coke', 2,  0,  1,  0,  0,  0),
(2, 'M',  'Coke', 4,  0,  1,  0,  0,  0),
(3, 'L',  'Coke', 5,  0,  1,  0,  0,  0),
(4, 'S',  'Sprite', 2,  0,  1,  0,  0, 0),
(5, 'M',  'Sprite', 4,  0,  1,  0,  0,  0),
(6, 'L',  'Sprite', 5,  0,  1,  0,  0,  0),
(7, 'S',  'Regular', 6,  1,  0,  0, 0,  0),
(8, 'M',  'Regular', 8,  1,  0,  0, 0,  0),
(9, 'L',  'Regular', 9,  1,  0,  0,  0,  0),
(10, 'S',  'Buttered', 8,  1,  0,  0, 0,  0),
(11, 'M',  'Buttered', 10,  1,  0,  0,  0,  0),
(12, 'L',  'Buttered', 11,  1,  0, 0, 0,  0),
(13, 'R',  'Smarties', 4,  0,  0, 1, 0,  0),
(14, 'R',  'Kit-Kat', 4,  0,  0, 1, 0,  0),
(15, 'R',  'M&Ms', 4,  0,  0, 1, 0,  0),
(16, 'R',  'Classic', 8,  0,  0, 0, 1,  0),
(17, 'L',  'Classic', 11,  0,  0, 0, 1,  0),
(18, 'R',  'Veggie', 8,  0,  0, 0, 1,  0),
(19, 'L',  'Veggie', 11,  0,  0, 0, 1,  0),
(20, 'R',  'Traditional', 9,  0,  0, 0, 0,  1),
(21, 'L',  'Traditional', 12,  0,  0, 0, 0,  1),
(22, 'R',  'Beef', 10,  0,  0, 0, 0,  1),
(23, 'L',  'Beef', 13,  0,  0, 0, 0,  1);

-- --------------------------------------------------------

--
-- Table structure for table `food_order`
--
DROP TABLE IF EXISTS food_order;
CREATE TABLE `food_order` (
  `Order_Number` int NOT NULL,
  `CustomerID` int NOT NULL,
  `RoomNo` int NOT NULL,
  `DateTime` datetime NOT NULL,
  `ESSN` int DEFAULT NULL,
  `Deliver_Status` tinyint(1) NOT NULL DEFAULT '0'
);

-- populating food_order

INSERT INTO `food_order` (`Order_Number`, `CustomerID`, `RoomNo`, `DateTime`, `ESSN`, `Deliver_Status`) VALUES
(1, 1, 2, '2021-12-25 13:30:00', 1, 1),
(2, 2, 2, '2021-12-25 13:30:00', null, 0),
(3, 3, 2, '2021-12-25 13:30:00', null, 0),
(4, 4, 6, '2021-12-25 15:30:00', null, 0),
(5, 2, 2, '2021-12-25 17:30:00', null, 0);

-- --------------------------------------------------------

--
-- Table structure for table `movie`
--
DROP TABLE IF EXISTS movie;
CREATE TABLE `movie` (
  `MovieID` int NOT NULL,
  `Name` varchar(25) NOT NULL,
  `Genre` varchar(25) NOT NULL,
  `Duration` int NOT NULL
);

-- populating movie table

INSERT INTO `movie` (`MovieID`, `Name`, `Genre`, `Duration`) VALUES
(1, 'Encanto', 'Family', 109),
(2, 'Sing 2', 'Family', 110);

-- --------------------------------------------------------

--
-- Table structure for table `movie_showing`
--
DROP TABLE IF EXISTS movie_showing;
CREATE TABLE `movie_showing` (
  `DateTime` datetime NOT NULL,
  `RoomNo` int NOT NULL,
  `MovieID` int NOT NULL
);

--
-- Dumping data for table `movie_showing`
--

INSERT INTO `movie_showing` (`DateTime`, `RoomNo`, `MovieID`) VALUES
('2021-12-25 13:30:00', 2, 1),
('2021-12-25 17:30:00', 2, 1),
('2021-12-18 17:30:00', 2, 1),
('2021-12-18 13:00:00', 2, 1),
('2021-12-25 15:30:00', 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `seat`
--
DROP TABLE IF EXISTS seat;
CREATE TABLE `seat` (
  `SeatID` varchar(3) NOT NULL,
  `Seat_Type` varchar(25) NOT NULL,
  `Room_No` int NOT NULL,
  `CustomerID` int DEFAULT NULL,
  `DateTime` datetime DEFAULT NULL
);

-- populating seat table

INSERT INTO `seat` (`SeatID`, `Seat_Type`, `Room_No`, `CustomerID`, `DateTime`) VALUES
('A01', 'Wheelchair', 2, 1, '2021-12-25 13:30:00'),
('A02', 'Normal', 2, 1, '2021-12-25 13:30:00'),
('B01', 'Wheelchair', 2, 2, '2021-12-25 13:30:00'),
('B02', 'Normal', 2, 2, '2021-12-25 13:30:00'),
('C01', 'Wheelchair', 2, 3, '2021-12-25 13:30:00'),
('C02', 'Normal', 2, 3, '2021-12-25 13:30:00'),
('D01', 'Wheelchair', 2, NULL, '2021-12-25 13:30:00'),
('D02', 'Normal', 2, NULL, '2021-12-25 13:30:00'),
('E02', 'Normal', 6, 4, '2021-12-25 15:30:00'),
('E03', 'Normal', 2, 2, '2021-12-25 17:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `theatre_room`
--
DROP TABLE IF EXISTS theatre_room;
CREATE TABLE `theatre_room` (
  `Room_No` int NOT NULL,
  `TheatreID` int NOT NULL
);

-- populating theatre_room

INSERT INTO `theatre_room` (`Room_No`, `TheatreID`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 2),
(7, 2),
(8, 2);

/* ADDING KEYS */

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`CustomerID`,`MovieID`,`DateTime`),
  ADD KEY `Booking_MovieID_FK` (`MovieID`),
  ADD KEY `Booking_RoomNo_FK` (`Room_No`),
  ADD KEY `Booking_DateTime_FK` (`DateTime`);

--
-- Indexes for table `cinema_theatre`
--
ALTER TABLE `cinema_theatre`
  ADD PRIMARY KEY (`TheatreID`),
  ADD UNIQUE KEY `TheatreID` (`TheatreID`),
  ADD KEY `Cinema_MgrSSN_FK` (`Mgr_SSN`);

--
-- Indexes for table `contains_food_order`
--
ALTER TABLE `contains_food_order`
  ADD PRIMARY KEY (`Order_Number`,`CustomerID`,`FoodID`),
  ADD KEY `Contains_CustomerID_FK` (`CustomerID`),
  ADD KEY `Contains_FoodID_FK` (`FoodID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `CustomerID` (`CustomerID`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`SSN`),
  ADD UNIQUE KEY `SSN` (`SSN`),
  ADD UNIQUE KEY `SSN_2` (`SSN`),
  ADD KEY `Employee_MgrSSN_FK` (`Mgr_SSN`),
  ADD KEY `Employee_ThreatreID_FK` (`TheatreID`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`FoodID`);

--
-- Indexes for table `food_order`
--
ALTER TABLE `food_order`
  ADD PRIMARY KEY (`Order_Number`,`CustomerID`,`RoomNo`,`DateTime`),
  ADD UNIQUE KEY `Order Number` (`Order_Number`),
  ADD KEY `FoodOrder_CustomerID_FK` (`CustomerID`),
  ADD KEY `FoodOrder_ESSN_FK` (`ESSN`),
  ADD KEY `FoodOrder_RoomNo_FK` (`RoomNo`),
  ADD KEY `FoodOrder_DateTime_FK` (`DateTime`);

--
-- Indexes for table `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`MovieID`),
  ADD UNIQUE KEY `MovieID` (`MovieID`);

--
-- Indexes for table `movie_showing`
--
ALTER TABLE `movie_showing`
  ADD PRIMARY KEY (`DateTime`,`RoomNo`),
  ADD KEY `MovieShowing_RoomNo_FK` (`RoomNo`),
  ADD KEY `MovieShowing_MovieID_FK` (`MovieID`);

--
-- Indexes for table `seat`
--
ALTER TABLE `seat`
  ADD PRIMARY KEY (`SeatID`, `Room_No`, `DateTime`),
  ADD KEY `Seat_RoomNo_FK` (`Room_No`),
  ADD KEY `Seat_CustomerID_FK` (`CustomerID`),
  ADD KEY `Seat_DateTime_FK` (`DateTime`);

--
-- Indexes for table `theatre_room`
--
ALTER TABLE `theatre_room`
  ADD PRIMARY KEY (`Room_No`),
  ADD UNIQUE KEY `Room_No` (`Room_No`),
  ADD KEY `TheatreRoom_TheatreID_FK` (`TheatreID`);



/* AUTO INCREMENT */

-- auto increment CustomerID
ALTER TABLE `customer`
	MODIFY `CustomerID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
    
-- autoincrement Order_Number
ALTER TABLE `food_order`
  MODIFY `Order_Number`  int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
  

-- auto increment 
ALTER TABLE `movie`
  MODIFY `MovieID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

/* FOREIGN KEY CONSTRAINTS */

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `Booking_CustomerID_FK` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE RESTRICT,
  ADD CONSTRAINT `Booking_DateTime_FK` FOREIGN KEY (`DateTime`) REFERENCES `movie_showing` (`DateTime`) ON DELETE CASCADE,
  ADD CONSTRAINT `Booking_MovieID_FK` FOREIGN KEY (`MovieID`) REFERENCES `movie` (`MovieID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Booking_RoomNo_FK` FOREIGN KEY (`Room_No`) REFERENCES `theatre_room` (`Room_No`) ON DELETE RESTRICT;

--
-- Constraints for table `cinema_theatre`
--
ALTER TABLE `cinema_theatre`
  ADD CONSTRAINT `Cinema_MgrSSN_FK` FOREIGN KEY (`Mgr_SSN`) REFERENCES `employee` (`Mgr_SSN`) ON DELETE RESTRICT;

--
-- Constraints for table `contains_food_order`
--
ALTER TABLE `contains_food_order`
  ADD CONSTRAINT `Contains_CustomerID_FK` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Contains_FoodID_FK` FOREIGN KEY (`FoodID`) REFERENCES `food` (`FoodID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Contains_OrderNumber_FK` FOREIGN KEY (`Order_Number`) REFERENCES `food_order` (`Order_Number`) ON DELETE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `Employee_MgrSSN_FK` FOREIGN KEY (`Mgr_SSN`) REFERENCES `employee` (`SSN`) ON DELETE RESTRICT,
  ADD CONSTRAINT `Employee_ThreatreID_FK` FOREIGN KEY (`TheatreID`) REFERENCES `cinema_theatre` (`TheatreID`) ON DELETE RESTRICT;

--
-- Constraints for table `food_order`
--
ALTER TABLE `food_order`
  ADD CONSTRAINT `FoodOrder_CustomerID_FK` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE RESTRICT,
  ADD CONSTRAINT `FoodOrder_DateTime_FK` FOREIGN KEY (`DateTime`) REFERENCES `movie_showing` (`DateTime`) ON DELETE CASCADE,
  ADD CONSTRAINT `FoodOrder_RoomNo_FK` FOREIGN KEY (`RoomNo`) REFERENCES `theatre_room` (`Room_No`) ON DELETE CASCADE,
  ADD CONSTRAINT `FoodOrder_ESSN_FK` FOREIGN KEY (`ESSN`) REFERENCES `employee` (`SSN`) ON DELETE RESTRICT;

--
-- Constraints for table `movie_showing`
--
ALTER TABLE `movie_showing`
  ADD CONSTRAINT `MovieShowing_MovieID_FK` FOREIGN KEY (`MovieID`) REFERENCES `movie` (`MovieID`) ON DELETE CASCADE,
  ADD CONSTRAINT `MovieShowing_RoomNo_FK` FOREIGN KEY (`RoomNo`) REFERENCES `theatre_room` (`Room_No`) ON DELETE RESTRICT;

--
-- Constraints for table `seat`
--
ALTER TABLE `seat`
  ADD CONSTRAINT `Seat_CustomerID_FK` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`) ON DELETE RESTRICT,
  ADD CONSTRAINT `Seat_DateTime_FK` FOREIGN KEY (`DateTime`) REFERENCES `movie_showing` (`DateTime`) ON DELETE CASCADE,
  ADD CONSTRAINT `Seat_RoomNo_FK` FOREIGN KEY (`Room_No`) REFERENCES `theatre_room` (`Room_No`) ON DELETE RESTRICT;

--
-- Constraints for table `theatre_room`
--
ALTER TABLE `theatre_room`
  ADD CONSTRAINT `TheatreRoom_TheatreID_FK` FOREIGN KEY (`TheatreID`) REFERENCES `cinema_theatre` (`TheatreID`) ON DELETE RESTRICT;

  