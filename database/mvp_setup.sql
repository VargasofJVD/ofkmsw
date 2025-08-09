-- MVP Database Setup for Krisah Montessori School
-- This script removes unnecessary tables and adds required tables for MVP

USE krisah_db;

-- Drop unnecessary tables for MVP
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS admission_forms;
DROP TABLE IF EXISTS gallery;
DROP TABLE IF EXISTS gallery_categories;
DROP TABLE IF EXISTS contact_messages;

-- Create newsletter_subscribers table
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Add index for email lookups
CREATE INDEX idx_newsletter_email ON newsletter_subscribers(email);
CREATE INDEX idx_newsletter_active ON newsletter_subscribers(is_active);

-- Update testimonials table to add is_approved column if it doesn't exist
ALTER TABLE testimonials ADD COLUMN IF NOT EXISTS is_approved BOOLEAN DEFAULT TRUE;

-- Insert some sample data for testing
INSERT INTO newsletter_subscribers (email) VALUES 
('test@example.com'),
('parent@example.com')
ON DUPLICATE KEY UPDATE updated_at = CURRENT_TIMESTAMP;

-- Create messages table for contact form
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Show current tables for verification
SHOW TABLES; 