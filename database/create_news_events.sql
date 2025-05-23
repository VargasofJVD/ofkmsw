-- Create news_events table
CREATE TABLE IF NOT EXISTS news_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    event_date DATE,
    is_event BOOLEAN DEFAULT FALSE,
    is_featured BOOLEAN DEFAULT FALSE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert some sample news items
INSERT INTO news_events (title, content, is_event, is_featured, created_at) VALUES
('Welcome to the New Academic Year', 'We are excited to welcome all students and parents to the new academic year. Our dedicated team of teachers is ready to provide an enriching learning experience.', FALSE, TRUE, NOW()),
('Annual Sports Day', 'Join us for our annual sports day celebration. Students will participate in various athletic events and team sports.', TRUE, TRUE, NOW()),
('Parent-Teacher Meeting', 'We invite all parents to attend our quarterly parent-teacher meeting to discuss student progress and development.', FALSE, FALSE, NOW()); 