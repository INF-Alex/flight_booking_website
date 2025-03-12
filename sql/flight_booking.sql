DROP DATABASE IF EXISTS flight_booking;
DROP FUNCTION IF EXISTS rand_date;
DROP FUNCTION IF EXISTS rand_int;

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
('Henry', 'Martinez', '1991-06-18', 'M', 'henry@example.com', '357159486', 'h', MD5('1'), 0),
('Alex', 'Xin', '2024-05-21', 'M', 'alex@example.com', '715976561', 'alex', MD5('123456'), 0);

INSERT INTO airport (iata, icao, name, city, country, latitude, longitude) VALUES
('PEK', 'ZBAA', 'Beijing Capital International Airport', 'Beijing', 'China', 40.080111, 116.584556),
('PKX', 'ZBAD', 'Beijing Daxing International Airport', 'Beijing', 'China', 39.509722, 116.410278),
('WUH', 'ZHHH', 'Wuhan Tianhe International Airport', 'Wuhan', 'China', 30.783758, 114.2081),
('HKG', 'VHHH', 'Hong Kong International Airport', 'Hong Kong', 'China', 22.308889, 113.914722),
('PVG', 'ZSPD', 'Shanghai Pudong International Airport', 'Shanghai', 'China', 31.143378, 121.805214),
('SHA', 'ZSSS', 'Shanghai Hongqiao International Airport', 'Shanghai', 'China', 31.1979, 121.3363),
('CAN', 'ZGGG', 'Guangzhou Baiyun International Airport', 'Guangzhou', 'China', 23.392436, 113.298786),
('CTU', 'ZUUU', 'Chengdu Shuangliu International Airport', 'Chengdu', 'China', 30.578528, 103.947086),
('SZX', 'ZGSZ', 'Shenzhen Bao an International Airport', 'Shenzhen', 'China', 22.639258, 113.810664),
('XIY', 'ZLXY', 'Xi an Xianyang International Airport', 'Xi an', 'China', 34.447119, 108.751592),
('CKG', 'ZUCK', 'Chongqing Jiangbei International Airport', 'Chongqing', 'China', 29.719166, 106.641667),
('KMG', 'ZPPP', 'Kunming Changshui International Airport', 'Kunming', 'China', 25.101944, 102.929167),
('TAO', 'ZSQD', 'Qingdao Liuting International Airport', 'Qingdao', 'China', 36.266111, 120.374444),
('SYD', 'YSSY', 'Sydney Kingsford Smith International Airport', 'Sydney', 'Australia', -33.939922, 151.175276),
('LAX', 'KLAX', 'Los Angeles International Airport', 'Los Angeles', 'USA', 33.942536, -118.408075),
('JFK', 'KJFK', 'John F. Kennedy International Airport', 'New York', 'USA', 40.641311, -73.778139),
('LHR', 'EGLL', 'London Heathrow Airport', 'London', 'United Kingdom', 51.470020, -0.454295),
('CDG', 'LFPG', 'Charles de Gaulle Airport', 'Paris', 'France', 49.009722, 2.547778),
('NRT', 'RJAA', 'Narita International Airport', 'Tokyo', 'Japan', 35.764722, 140.386389),
('HND', 'RJTT', 'Haneda Airport', 'Tokyo', 'Japan', 35.549393, 139.779839),
('DXB', 'OMDB', 'Dubai International Airport', 'Dubai', 'United Arab Emirates', 25.253175, 55.365673),
('SIN', 'WSSS', 'Singapore Changi Airport', 'Singapore', 'Singapore', 1.350189, 103.994433),
('ICN', 'RKSI', 'Incheon International Airport', 'Seoul', 'South Korea', 37.460191, 126.440696),
('FRA', 'EDDF', 'Frankfurt am Main Airport', 'Frankfurt', 'Germany', 50.037933, 8.562152),
('AMS', 'EHAM', 'Amsterdam Schiphol Airport', 'Amsterdam', 'Netherlands', 52.310538, 4.768274),
('ATL', 'KATL', 'Hartsfield-Jackson Atlanta International Airport', 'Atlanta', 'USA', 33.640411, -84.419853);

INSERT INTO airline (name, iata) VALUES
('China Eastern Airlines', 'MU'),
('Air China', 'CA'),
('China Southern Airlines', 'CZ'),
('Hainan Airlines', 'HU'),
('Xiamen Airlines', 'MF'),
('Shandong Airlines', 'SC'),
('Shenzhen Airlines', 'ZH'),
('Sichuan Airlines', '3U'),
('Spring Airlines', '9C'),
('Juneyao Airlines', 'HO'),
('Tianjin Airlines', 'GS'),
('Okay Airways', 'BK'),
('Chengdu Airlines', 'EU'),
('Donghai Airlines', 'DZ'),
('Fuzhou Airlines', 'FU'),
('Hebei Airlines', 'NS');

INSERT INTO passenger (id, firstname, lastname, email, phone, sex, dob) VALUES
('110105198501152345', 'Wei', 'Wang', 'wei.wang@example.com', '13800138000', 'M', '1985-01-15'),
('310110199006252345', 'Li', 'Zhang', 'li.zhang@example.com', '13900139000', 'F', '1990-06-25'),
('320102197509303456', 'Jie', 'Li', 'jie.li@example.com', '13600136000', 'M', '1975-09-30'),
('330103198812053567', 'Xiao', 'Chen', 'xiao.chen@example.com', '13500135000', 'F', '1988-12-05'),
('440104199211203678', 'Peng', 'Liu', 'peng.liu@example.com', '13400134000', 'M', '1992-11-20');

INSERT INTO airplane (type, capacity, identifier) VALUES
('B737-300', 149, 'B-12345'),
('A320-200', 180, 'B-67890'),
('B777-300ER', 396, 'B-11223'),
('A380-800', 853, 'B-44556'),
('B787-9', 280, 'B-77889');

INSERT INTO flightschedule (flight_no, monday, tuesday, wednesday, thursday, friday, saturday, sunday, airline_id, from_airport_id, to_airport_id) VALUES
('MU123456', 1, 0, 1, 0, 0, 0, 1, 1, 1, 2),
('CA654321', 1, 0, 0, 0, 1, 0, 0, 2, 2, 3),
('CZ111111', 1, 1, 0, 1, 0, 1, 0, 3, 3, 4),
('HU222222', 0, 1, 0, 1, 0, 0, 1, 4, 4, 1),
('MF333333', 0, 1, 1, 0, 1, 0, 0, 5, 1, 3),
('SC444444', 1, 0, 0, 1, 0, 1, 0, 6, 2, 4),
('DZ555555', 1, 1, 1, 0, 1, 0, 0, 7, 4, 3),
('ZH666666', 0, 1, 1, 1, 1, 0, 1, 8, 3, 1);

set global log_bin_trust_function_creators = 1;

DELIMITER //
CREATE FUNCTION rand_date(a DATETIME, min INT, max INT)
RETURNS DATETIME
BEGIN
    DECLARE return_date DATETIME DEFAULT a;
    DECLARE i INT DEFAULT 0;
    SET i = FLOOR(RAND() * (max-min)) + min;
    SET return_date = ADDTIME(a, SEC_TO_TIME(i*60));
    RETURN return_date;
END //

CREATE FUNCTION rand_int(min INT, max INT)
RETURNS INT
BEGIN
    DECLARE i INT DEFAULT 0;
    SET i = FLOOR(RAND() * (max-min+1)) + min;
    RETURN i;
END //
delimiter ;


DROP TABLE IF EXISTS temp1;
DROP TABLE IF EXISTS temp2;

CREATE TABLE temp1(
    departure DATETIME,
    arrival DATETIME,
    flight_no CHAR(8),
    airplane_id INT,
    from_airport_id INT,
    to_airport_id INT);

CREATE TABLE temp2(
    departure DATETIME,
    flight_no CHAR(8),
    airplane_id INT,
    from_airport_id INT,
    to_airport_id INT);

INSERT INTO temp2(departure, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, flight_no, airplane_id, from_airport_id, to_airport_id 
    FROM 
    (SELECT 
        flight_no,
        from_airport_id,
        to_airport_id,
        rand_date('2024-05-13 00:00:00', 0, 1200) AS departure,
        rand_int(1,(SELECT COUNT(*) FROM airplane)) AS airplane_id
        FROM
            flightschedule
        WHERE
            monday <> 0
        ) AS subquery1
    );

INSERT INTO temp1(departure, arrival, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, rand_date(departure,60,120) AS arrival, flight_no, airplane_id, from_airport_id, to_airport_id FROM temp2);

INSERT INTO flight (departure, arrival, duration, flight_no, airplane_id, from_airport_id, to_airport_id)
SELECT departure, arrival, TIMESTAMPDIFF(MINUTE, departure, arrival), flight_no, airplane_id, from_airport_id, to_airport_id FROM temp1;


delete from temp1;
delete from temp2;
INSERT INTO temp2(departure, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, flight_no, airplane_id, from_airport_id, to_airport_id 
    FROM 
    (SELECT 
        flight_no,
        from_airport_id,
        to_airport_id,
        rand_date('2024-05-14 00:00:00', 0, 1200) AS departure,
        rand_int(1,(SELECT COUNT(*) FROM airplane)) AS airplane_id
        FROM
            flightschedule
        WHERE
            tuesday <> 0
        ) AS subquery1
    );

INSERT INTO temp1(departure, arrival, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, rand_date(departure,60,120) AS arrival, flight_no, airplane_id, from_airport_id, to_airport_id FROM temp2);

INSERT INTO flight (departure, arrival, duration, flight_no, airplane_id, from_airport_id, to_airport_id)
SELECT departure, arrival, TIMESTAMPDIFF(MINUTE, departure, arrival), flight_no, airplane_id, from_airport_id, to_airport_id FROM temp1;


delete from temp1;
delete from temp2;
INSERT INTO temp2(departure, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, flight_no, airplane_id, from_airport_id, to_airport_id 
    FROM 
    (SELECT 
        flight_no,
        from_airport_id,
        to_airport_id,
        rand_date('2024-05-15 00:00:00', 0, 1200) AS departure,
        rand_int(1,(SELECT COUNT(*) FROM airplane)) AS airplane_id
        FROM
            flightschedule
        WHERE
            wednesday <> 0
        ) AS subquery1
    );

INSERT INTO temp1(departure, arrival, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, rand_date(departure,60,120) AS arrival, flight_no, airplane_id, from_airport_id, to_airport_id FROM temp2);

INSERT INTO flight (departure, arrival, duration, flight_no, airplane_id, from_airport_id, to_airport_id)
SELECT departure, arrival, TIMESTAMPDIFF(MINUTE, departure, arrival), flight_no, airplane_id, from_airport_id, to_airport_id FROM temp1;


delete from temp1;
delete from temp2;
INSERT INTO temp2(departure, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, flight_no, airplane_id, from_airport_id, to_airport_id 
    FROM 
    (SELECT 
        flight_no,
        from_airport_id,
        to_airport_id,
        rand_date('2024-05-16 00:00:00', 0, 1200) AS departure,
        rand_int(1,(SELECT COUNT(*) FROM airplane)) AS airplane_id
        FROM
            flightschedule
        WHERE
            thursday <> 0
        ) AS subquery1
    );

INSERT INTO temp1(departure, arrival, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, rand_date(departure,60,120) AS arrival, flight_no, airplane_id, from_airport_id, to_airport_id FROM temp2);

INSERT INTO flight (departure, arrival, duration, flight_no, airplane_id, from_airport_id, to_airport_id)
SELECT departure, arrival, TIMESTAMPDIFF(MINUTE, departure, arrival), flight_no, airplane_id, from_airport_id, to_airport_id FROM temp1;


delete from temp1;
delete from temp2;
INSERT INTO temp2(departure, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, flight_no, airplane_id, from_airport_id, to_airport_id 
    FROM 
    (SELECT 
        flight_no,
        from_airport_id,
        to_airport_id,
        rand_date('2024-05-17 00:00:00', 0, 1200) AS departure,
        rand_int(1,(SELECT COUNT(*) FROM airplane)) AS airplane_id
        FROM
            flightschedule
        WHERE
            friday <> 0
        ) AS subquery1
    );

INSERT INTO temp1(departure, arrival, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, rand_date(departure,60,120) AS arrival, flight_no, airplane_id, from_airport_id, to_airport_id FROM temp2);

INSERT INTO flight (departure, arrival, duration, flight_no, airplane_id, from_airport_id, to_airport_id)
SELECT departure, arrival, TIMESTAMPDIFF(MINUTE, departure, arrival), flight_no, airplane_id, from_airport_id, to_airport_id FROM temp1;


delete from temp1;
delete from temp2;
INSERT INTO temp2(departure, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, flight_no, airplane_id, from_airport_id, to_airport_id 
    FROM 
    (SELECT 
        flight_no,
        from_airport_id,
        to_airport_id,
        rand_date('2024-05-18 00:00:00', 0, 1200) AS departure,
        rand_int(1,(SELECT COUNT(*) FROM airplane)) AS airplane_id
        FROM
            flightschedule
        WHERE
            saturday <> 0
        ) AS subquery1
    );

INSERT INTO temp1(departure, arrival, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, rand_date(departure,60,120) AS arrival, flight_no, airplane_id, from_airport_id, to_airport_id FROM temp2);

INSERT INTO flight (departure, arrival, duration, flight_no, airplane_id, from_airport_id, to_airport_id)
SELECT departure, arrival, TIMESTAMPDIFF(MINUTE, departure, arrival), flight_no, airplane_id, from_airport_id, to_airport_id FROM temp1;


delete from temp1;
delete from temp2;
INSERT INTO temp2(departure, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, flight_no, airplane_id, from_airport_id, to_airport_id 
    FROM 
    (SELECT 
        flight_no,
        from_airport_id,
        to_airport_id,
        rand_date('2024-05-19 00:00:00', 0, 1200) AS departure,
        rand_int(1,(SELECT COUNT(*) FROM airplane)) AS airplane_id
        FROM
            flightschedule
        WHERE
            sunday <> 0
        ) AS subquery1
    );

INSERT INTO temp1(departure, arrival, flight_no, airplane_id, from_airport_id, to_airport_id)
(SELECT departure, rand_date(departure,60,120) AS arrival, flight_no, airplane_id, from_airport_id, to_airport_id FROM temp2);

INSERT INTO flight (departure, arrival, duration, flight_no, airplane_id, from_airport_id, to_airport_id)
SELECT departure, arrival, TIMESTAMPDIFF(MINUTE, departure, arrival), flight_no, airplane_id, from_airport_id, to_airport_id FROM temp1;

DROP TABLE temp1;
DROP TABLE temp2;