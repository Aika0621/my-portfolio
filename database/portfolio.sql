CREATE DATABASE IF NOT EXISTS aika_portfolio
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE aika_portfolio;

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS profile (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    introduction VARCHAR(255) NOT NULL,
    biography TEXT NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(40) DEFAULT NULL,
    location VARCHAR(120) DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS skills (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    level TINYINT UNSIGNED DEFAULT 50,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    technologies VARCHAR(255) DEFAULT NULL,
    category VARCHAR(80) DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    project_url VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS education (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    school VARCHAR(160) NOT NULL,
    qualification VARCHAR(160) NOT NULL,
    details TEXT DEFAULT NULL,
    start_date VARCHAR(20) DEFAULT NULL,
    end_date VARCHAR(20) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS experience (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    position VARCHAR(160) NOT NULL,
    organization VARCHAR(160) NOT NULL,
    details TEXT DEFAULT NULL,
    start_date VARCHAR(20) DEFAULT NULL,
    end_date VARCHAR(20) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS achievements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    organization VARCHAR(160) DEFAULT NULL,
    details TEXT DEFAULT NULL,
    achievement_date VARCHAR(40) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_name VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT DEFAULT NULL
);

INSERT INTO profile (name, introduction, biography, email)
SELECT 'Aika', 'Information Technology Student', 'I am an Information Technology student who enjoys learning about technology and creating simple projects.', 'aika@example.com'
WHERE NOT EXISTS (SELECT id FROM profile);

INSERT INTO skills (name, category, level)
SELECT 'HTML', 'Web Development', 60
WHERE NOT EXISTS (SELECT id FROM skills WHERE name = 'HTML');

INSERT INTO skills (name, category, level)
SELECT 'CSS', 'Web Development', 55
WHERE NOT EXISTS (SELECT id FROM skills WHERE name = 'CSS');

INSERT INTO skills (name, category, level)
SELECT 'JavaScript', 'Web Development', 40
WHERE NOT EXISTS (SELECT id FROM skills WHERE name = 'JavaScript');

INSERT INTO projects (title, description, technologies, category)
SELECT 'Personal Portfolio Website', 'A personal portfolio website that shows my information, skills, and projects.', 'HTML, CSS, JavaScript', 'web'
WHERE NOT EXISTS (SELECT id FROM projects WHERE title = 'Personal Portfolio Website');

INSERT INTO projects (title, description, technologies, category)
SELECT 'AIKÉA FRESH Advertisement', 'An animated advertisement created to promote a fictional fruit drink product.', 'Adobe Animate', 'animation'
WHERE NOT EXISTS (SELECT id FROM projects WHERE title = 'AIKÉA FRESH Advertisement');

INSERT INTO projects (title, description, technologies, category)
SELECT 'Network Simulation', 'A network simulation created to practice connecting computers, switches, and routers.', 'Cisco Packet Tracer', 'networking'
WHERE NOT EXISTS (SELECT id FROM projects WHERE title = 'Network Simulation');

INSERT INTO settings (setting_name, setting_value)
SELECT 'site_title', 'Aika | My Portfolio'
WHERE NOT EXISTS (SELECT id FROM settings WHERE setting_name = 'site_title');
