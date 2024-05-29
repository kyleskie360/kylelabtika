-- Table for storing user verification data
CREATE TABLE IF NOT EXISTS users_verification (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    verification_code INT NOT NULL,
    verification_status VARCHAR(20) NOT NULL
);

-- Table for storing user registration data
CREATE TABLE IF NOT EXISTS ipt102_db (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    middle_name VARCHAR(255),
    email VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL,
    active INT NOT NULL
);
