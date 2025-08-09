-- Update Newsletter Subscribers Table
-- Add name column to existing table

USE krisah_db;

-- Add name column to existing newsletter_subscribers table
ALTER TABLE newsletter_subscribers ADD COLUMN name VARCHAR(100) NOT NULL AFTER id;

-- Update existing records with a default name if any exist
UPDATE newsletter_subscribers SET name = 'Subscriber' WHERE name = '' OR name IS NULL;

-- Show the updated table structure
DESCRIBE newsletter_subscribers; 