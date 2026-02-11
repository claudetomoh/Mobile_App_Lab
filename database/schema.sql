-- Contact Management System Database Schema
-- Database: mobileapps_2026B_tomoh_ikfingeh
-- Table: contacts

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS mobileapps_2026B_tomoh_ikfingeh;
USE mobileapps_2026B_tomoh_ikfingeh;

-- Drop table if exists (for fresh setup)
DROP TABLE IF EXISTS contacts;

-- Create contacts table
CREATE TABLE contacts (
    pid INT(11) AUTO_INCREMENT PRIMARY KEY,
    pname VARCHAR(255) NOT NULL,
    pphone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_name (pname),
    INDEX idx_phone (pphone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for testing
INSERT INTO contacts (pname, pphone) VALUES
('John Doe', '+233501234567'),
('Jane Smith', '+233241234567'),
('Michael Johnson', '+233551234567'),
('Emily Brown', '+233271234567'),
('David Wilson', '+233201234567');

-- Verify data
SELECT * FROM contacts;

-- Display table structure
DESCRIBE contacts;

-- Display table info
SHOW CREATE TABLE contacts;
