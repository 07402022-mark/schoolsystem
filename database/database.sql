DROP DATABASE IF EXISTS school_db;
CREATE DATABASE school_db;
USE school_db;

CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_number VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    department VARCHAR(100) NOT NULL,
    record_status ENUM('active','deleted') DEFAULT 'active'
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_number VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    course VARCHAR(100) NOT NULL,
    year_level VARCHAR(50) NOT NULL,
    academic_status ENUM('active','passed','failed','dropped') DEFAULT 'active',
    record_status ENUM('active','deleted') DEFAULT 'active'
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','teacher','student') NOT NULL,
    status ENUM('active','inactive','deleted') DEFAULT 'active',
    student_id INT NULL,
    teacher_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
);

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    teacher_id INT NULL,
    year_level VARCHAR(50) NOT NULL,
    course VARCHAR(100) NOT NULL,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
);

CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NULL,
    grade INT NOT NULL,
    remarks VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
);

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present','absent','late') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
);

CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('school','class') NOT NULL,
    author_role VARCHAR(50) NOT NULL,
    author_id INT NOT NULL,
    subject_id INT NULL,
    publish_at DATETIME,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL
);

INSERT INTO users (
    name,
    email,
    password,
    role,
    status
)
VALUES (
    'Admin',
    'admin@school.test',
    '$2y$10$i.oT6TOdT/cMQO4TiVDAvOttSLtk8riZA/AM4rwP4GSJAtpQgBvEi',
    'admin',
    'active'
);