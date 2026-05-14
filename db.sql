-- Database schema for MUST Hostel Finder
-- Generated from the PHP application code in the hostels workspace.

DROP DATABASE IF EXISTS `must_hostelfinder`;
CREATE DATABASE `must_hostelfinder` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `must_hostelfinder`;

-- Users table
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fullname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('student','admin') NOT NULL DEFAULT 'student',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Hostels table
CREATE TABLE `hostels` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `hostel_name` VARCHAR(255) NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `room_type` ENUM('single','shared','self-contained') NOT NULL DEFAULT 'single',
  `price` INT UNSIGNED NOT NULL,
  `availability` INT UNSIGNED NOT NULL DEFAULT 0,
  `description` TEXT DEFAULT NULL,
  `contact` VARCHAR(100) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_hostels_room_type` (`room_type`),
  KEY `idx_hostels_availability` (`availability`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bookings table
CREATE TABLE `bookings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `hostel_id` INT UNSIGNED NOT NULL,
  `booking_date` DATE NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `status` ENUM('confirmed','cancelled') NOT NULL DEFAULT 'confirmed',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_bookings_user_id` (`user_id`),
  KEY `idx_bookings_hostel_id` (`hostel_id`),
  KEY `idx_bookings_status` (`status`),
  CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bookings_hostel` FOREIGN KEY (`hostel_id`) REFERENCES `hostels`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample data
INSERT INTO `users` (`fullname`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
('Demo Admin', 'admin@must.ac.ug', '+256 700 000 001', '$2y$10$wHfz7mFZLzIZrK0Ojk4G8OuupC7FLjzGQGzq03ziW4QRxN9v0s6B2', 'admin', NOW()),
('Demo Student', 'student@must.ac.ug', '+256 700 000 002', '$2y$10$wHfz7mFZLzIZrK0Ojk4G8OuupC7FLjzGQGzq03ziW4QRxN9v0s6B2', 'student', NOW());

INSERT INTO `hostels` (`hostel_name`,`location`,`room_type`,`price`,`availability`,`description`,`contact`,`image`,`created_at`) VALUES
('Pearl Student Hostel','Kakoba, 0.3km from MUST','single',850000,5,'Modern single rooms with study area, 24/7 security.','+256 700 111 222','image1.jpg',NOW()),
('Unity Residence','Ruharo, 0.5km from MUST','shared',900000,8,'Affordable shared rooms in a friendly student community.','+256 700 333 444','image2.jpg.png',NOW()),
('Comfort Suites','Kakoba, 0.4km from MUST','self-contained',1200000,3,'Fully self-contained suites with private bathroom and kitchen.','+256 701 555 666','image3.jpg.png',NOW());

-- Notes:
-- The sample password hash above is a bcrypt hash for `admin123`.
-- You can change the values or add more hostels/bookings as needed.
