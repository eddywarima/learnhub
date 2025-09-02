CREATE DATABASE learnhub;
USE learnhub;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') DEFAULT 'student',
    points INT DEFAULT 0,
    badges TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('pdf', 'video', 'note') NOT NULL,
    subject_id INT,
    uploaded_by INT,
    file VARCHAR(255),
    upload_date DATE,
    downloads INT DEFAULT 0,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (subject_id) REFERENCES subjects(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT,
    title VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id)
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    question TEXT NOT NULL,
    option_a TEXT NOT NULL,
    option_b TEXT NOT NULL,
    option_c TEXT NOT NULL,
    option_d TEXT NOT NULL,
    correct_answer ENUM('1', '2', '3', '4') NOT NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

CREATE TABLE results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    quiz_id INT,
    score INT,
    total INT,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
);

-- Insert initial data
INSERT INTO users (name, email, password, role, points) VALUES 
('Admin User', 'admin@learnhub.com', '$2y$10$Hw2PbMM8UnCqJhQBX/Xidu2OGcXf4D3u2l5PwOWwirz9ubz0XRjvi', 'admin', 0),
('John Doe', 'student@learnhub.com', '$2y$10$gpgG2abjW322lgeaIlH8PuyL0rzOe8hrHbxDkQfVal7zHlDbsA7WO', 'student', 0),
('Jane Smith', 'teacher@learnhub.com', '$2y$10$OLVtCNo8E5dpTJzjDmqlle9H/eQndgBwkyj.dcDtLqNRLOuOwnFty', 'teacher', 0);

INSERT INTO subjects (name, image) VALUES
('Mathematics', 'https://placehold.co/600x400/4361ee/white?text=Math'),
('Science', 'https://placehold.co/600x400/3a0ca3/white?text=Science'),
('Languages', 'https://placehold.co/600x400/4cc9f0/white?text=Languages'),
('History', 'https://placehold.co/600x400/f72585/white?text=History'),
('Arts', 'https://placehold.co/600x400/7209b7/white?text=Arts'),
('Computer Science', 'https://placehold.co/600x400/3f37c9/white?text=CS');