-- Specify the database separately
CREATE DATABASE IF NOT EXISTS venkatesh2;
USE venkatesh2;

-- Table for storing user information
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    username VARCHAR(50) NOT NULL,
    uid VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255),
    pin VARCHAR(6), -- Add a pin column for the group
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for storing friend requests
CREATE TABLE friend_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    requester_id INT NOT NULL,
    receiver_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (requester_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
);

-- Table for storing groups
CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(255) NOT NULL,
    group_description TEXT NOT NULL,
    pin VARCHAR(6) NOT NULL UNIQUE -- Add a pin column for group access
);

-- Table for storing group members
CREATE TABLE group_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    user_id INT NOT NULL,
    role ENUM('admin', 'officer', 'invigilator', 'member') DEFAULT 'member',
    FOREIGN KEY (group_id) REFERENCES groups(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY unique_group_user (group_id, user_id)
);

-- Table for storing chat messages
CREATE TABLE messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    group_id INT, -- Add a group_id column
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id),
    FOREIGN KEY (group_id) REFERENCES groups(id)
);

-- Table for storing friendslist information
CREATE TABLE friendslist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    friend_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (friend_id) REFERENCES users(id),
    UNIQUE KEY unique_friendship (user_id, friend_id)
);


CREATE TABLE pins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    group_id INT NOT NULL,
    member_pin VARCHAR(6) NOT NULL,
    admin_pin VARCHAR(6) NOT NULL,
    officer_pin VARCHAR(6) NOT NULL,
    invigilator_pin VARCHAR(6) NOT NULL,
    FOREIGN KEY (group_id) REFERENCES groups(id),
    UNIQUE KEY unique_group_pin (group_id)
);

CREATE TABLE friends (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    friend_name VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
