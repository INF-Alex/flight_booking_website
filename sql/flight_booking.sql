-- Create the database
CREATE DATABASE IF NOT EXISTS flight_booking;
USE flight_booking;

-- Create the user table
CREATE TABLE user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    dob DATE NOT NULL,
    sex CHAR(1) NOT NULL,
    email VARCHAR(50),
    phone VARCHAR(30),
    username VARCHAR(20) NOT NULL UNIQUE,
    password CHAR(32) NOT NULL,
    admin_tag TINYINT DEFAULT 0 NOT NULL
) ENGINE=InnoDB;

-- Create the passenger table
CREATE TABLE passenger (
    passenger_id INT AUTO_INCREMENT PRIMARY KEY,
    id CHAR(18) NOT NULL UNIQUE,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(50),
    phone VARCHAR(20) NOT NULL,
    sex CHAR(1) NOT NULL,
    dob DATE
) ENGINE=InnoDB;

-- Create the airport table
CREATE TABLE airport (
    airport_id INT AUTO_INCREMENT PRIMARY KEY,
    iata CHAR(3) NOT NULL UNIQUE,
    icao CHAR(4) NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    city VARCHAR(50),
    country VARCHAR(50),
    latitude DECIMAL(11, 8),
    longitude DECIMAL(11, 8),
    INDEX (name)
) ENGINE=InnoDB;

-- Create the airline table
CREATE TABLE airline (
    airline_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    iata CHAR(2) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Create the airplane table
CREATE TABLE airplane (
    airplane_id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    capacity SMALLINT NOT NULL,
    identifier VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

-- Create the flightschedule table
CREATE TABLE flightschedule (
    flight_no CHAR(8) PRIMARY KEY,
    departure TIME NOT NULL,
    arrival TIME NOT NULL,
    duration SMALLINT NOT NULL,
    monday TINYINT DEFAULT 0,
    tuesday TINYINT DEFAULT 0,
    wednesday TINYINT DEFAULT 0,
    thursday TINYINT DEFAULT 0,
    friday TINYINT DEFAULT 0,
    saturday TINYINT DEFAULT 0,
    sunday TINYINT DEFAULT 0,
    airline_id INT NOT NULL,
    from_airport_id INT NOT NULL,
    to_airport_id INT NOT NULL,
    FOREIGN KEY (airline_id) REFERENCES airline(airline_id),
    FOREIGN KEY (from_airport_id) REFERENCES airport(airport_id),
    FOREIGN KEY (to_airport_id) REFERENCES airport(airport_id)
) ENGINE=InnoDB;

-- Create the flight table
CREATE TABLE flight (
    flight_id INT AUTO_INCREMENT PRIMARY KEY,
    departure DATETIME NOT NULL,
    arrival DATETIME NOT NULL,
    duration SMALLINT NOT NULL,
    flight_no CHAR(8) NOT NULL,
    airplane_id INT NOT NULL,
    from_airport_id INT NOT NULL,
    to_airport_id INT NOT NULL,
    FOREIGN KEY (flight_no) REFERENCES flightschedule(flight_no),
    FOREIGN KEY (airplane_id) REFERENCES airplane(airplane_id),
    FOREIGN KEY (from_airport_id) REFERENCES airport(airport_id),
    FOREIGN KEY (to_airport_id) REFERENCES airport(airport_id)
) ENGINE=InnoDB;

-- Create the ticket table
CREATE TABLE ticket (
    ticket_id INT AUTO_INCREMENT PRIMARY KEY,
    seat CHAR(4),
    price DECIMAL(10,2) NOT NULL,
    flight_id INT NOT NULL,
    user_id INT NOT NULL,
    passenger_id INT NOT NULL,
    FOREIGN KEY (flight_id) REFERENCES flight(flight_id),
    FOREIGN KEY (user_id) REFERENCES user(user_id),
    FOREIGN KEY (passenger_id) REFERENCES passenger(passenger_id)
) ENGINE=InnoDB;


INSERT INTO user (firstname, lastname, dob, sex, username, password, admin_tag)
VALUES ('admin', 'admin', '2024-05-18', 'm', 'admin', MD5('654321'), 1);

INSERT INTO user (firstname, lastname, dob, sex, email, phone, username, password, admin_tag) VALUES
('John', 'Doe', '1990-01-01', 'M', 'john@example.com', '123456789', 'a', MD5('1'), 0),
('Jane', 'Smith', '1988-05-15', 'F', 'jane@example.com', '987654321', 'b', MD5('1'), 0),
('Alice', 'Johnson', '1995-09-20', 'F', 'alice@example.com', '456789123', 'c', MD5('1'), 0),
('Bob', 'Brown', '1992-03-10', 'M', 'bob@example.com', '321654987', 'd', MD5('1'), 0),
('Emily', 'Williams', '1987-11-25', 'F', 'emily@example.com', '789456123', 'e', MD5('1'), 0),
('David', 'Jones', '1983-07-05', 'M', 'david@example.com', '654987321', 'f', MD5('1'), 0),
('Grace', 'Taylor', '1998-12-30', 'F', 'grace@example.com', '159753468', 'g', MD5('1'), 0),
('Henry', 'Martinez', '1991-06-18', 'M', 'henry@example.com', '357159486', 'h', MD5('1'), 0);
