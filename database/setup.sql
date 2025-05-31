-- Database setup for Endorse Me website
CREATE DATABASE IF NOT EXISTS endorse_me;
USE endorse_me;

-- Endorsements table
CREATE TABLE endorsements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    message TEXT NOT NULL,
    avatar_path VARCHAR(255),
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_approved TINYINT(1) DEFAULT 1,
    INDEX idx_created_at (created_at),
    INDEX idx_approved (is_approved)
);

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Site settings table
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_title', 'Endorse Phurutsi - For A Smarter, Stronger Council'),
('site_description', 'Vote for Mashitishi B. Phurutsi - Tech-driven educator and digital innovator'),
('maintenance_mode', '0'),
('max_endorsements_per_ip', '3');

-- Sample endorsements for testing
INSERT INTO endorsements (name, email, message, avatar_path) VALUES
('Dr. Sarah Johnson', 'sarah.j@example.com', 'Mashitishi has been an exceptional leader in educational technology. His vision for transforming higher education is exactly what we need.', NULL),
('Prof. Michael Chen', 'mchen@university.edu', 'The ICEP program under Mashitishi\'s leadership has produced some of our finest graduates. His commitment to student success is unmatched.', NULL),
('Lisa Mbeki', 'lisa.mbeki@tech.co.za', 'As a former hackathon participant, I can attest to Mashitishi\'s ability to inspire innovation and bring communities together.', NULL);