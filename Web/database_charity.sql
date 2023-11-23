
CREATE SCHEMA Charity;
USE Charity;
CREATE TABLE Admin(
	Name VARCHAR(15) NOT NULL ,
    Email VARCHAR(30) NOT NULL PRIMARY KEY,
    Password VARCHAR(15) NOT NULL
);
CREATE TABLE User(
	Name VARCHAR(25) NOT NULL,
    Email VARCHAR(30) NOT NULL PRIMARY KEY,
    Password VARCHAR(15) NOT NULL,
    Address VARCHAR(150) NOT NULL,
    User_type ENUM('Donar','Donee') NOT NULL
);
CREATE TABLE money_donation(
	Donation_id varchar(255) not null PRIMARY KEY,
    Email VARCHAR(30) NOT NULL,
    Amount DECIMAL(10,2) not null,
    FOREIGN KEY(Email) references User(Email)
);
CREATE TABLE other_donation(
	Donation_id varchar(255) not null primary key,
    Email varchar(30) not null ,
    Description varchar(500) not null,
    FOREIGN KEY(Email) references User(Email)
);
CREATE TABLE Request_donation (
	Request_id varchar(255) not null primary key,
    Email varchar(30) not null,
    Description varchar(500) not null,
    foreign key(Email) references User(Email)
);
alter table money_donation add column Payment_type enum('UPI' , 'Credit');

ALTER TABLE Admin MODIFY COLUMN Password VARCHAR(255);

ALTER TABLE User MODIFY COLUMN Password VARCHAR(255);

ALTER TABLE User ADD COLUMN Contact_details VARCHAR(10) NOT NULL;

INSERT INTO Admin VALUES ('Admin', 'admin1@email.com', '$2y$10$2Whqc.kBtZDlPVIw0Ck9b.c6JWY1OHVO.jhnGCf1phLwfJqioIKgS');
select * from admin;
select * from user;
select * from money_donation;
select * from other_donation;

/*
CREATE TABLE Donation(
	Email VARCHAR(30) NOT NULL,
    Items ENUM('Money','Cloths','Medicines'),
    Details VARCHAR(250) NOT NULL,
    FOREIGN KEY(Email) REFERENCES User(Email)
);
*/
/*
DELIMITER //
CREATE PROCEDURE InsertDonation(
    IN p_email VARCHAR(255),
    IN p_donation_type VARCHAR(50),
    IN p_amount DECIMAL(10, 2),
    IN p_payment_type VARCHAR(50),
    IN p_description TEXT
)
BEGIN
    DECLARE donation_id INT;
    -- Generate a unique donation ID
    SET donation_id = FLOOR(RAND() * (999999 - 100000) + 100000);
    -- Insert into the appropriate donation table based on donation type
    IF p_donation_type = 'money' THEN
        INSERT INTO money_donation (Donation_id, Email, Amount, Payment_type)
        VALUES (donation_id, p_email, p_amount, p_payment_type);
    ELSEIF p_donation_type = 'clothes' THEN
        INSERT INTO other_donation (Donation_id, Email, Description)
        VALUES (donation_id, p_email, p_description);
    END IF;
    -- You can add additional logic or error handling here
END //
DELIMITER ;
*/
-- alter table money_donation modify column Amount int;
-- drop procedure InsertMoney; 
/*
DELIMITER //
CREATE PROCEDURE InsertMoney(
    IN p_email VARCHAR(255),
    IN p_amount int,
    IN p_payment_type VARCHAR(50)
)
BEGIN
    DECLARE donation_id varchar(255);
    SET donation_id = UUID();
    INSERT INTO money_donation (Donation_id, Email, Amount, Payment_type)
        VALUES (donation_id, p_email, p_amount, p_payment_type);
END //
DELIMITER ;
*/
-- select * from charity.money_donation;
-- alter table charity.other_donation add column Item varchar(25);
-- drop procedure InsertOthers;
/*
DELIMITER //
CREATE PROCEDURE InsertOthers(
    IN p_email VARCHAR(50),
    IN p_item varchar(25),
    IN p_description VARCHAR(500)
)
BEGIN
    DECLARE donation_id varchar(255);
    SET donation_id = UUID();
    INSERT INTO other_donation (Donation_id, Email, Item, Description)
        VALUES (donation_id, p_email, p_item, p_description);
END //
DELIMITER ;
alter table Request_donation add column Item varchar(25) not null;
*/
DELIMITER //
CREATE PROCEDURE InsertRequests(
    IN p_email VARCHAR(50),
    IN p_item varchar(25),
    IN p_description VARCHAR(500)
)
BEGIN
    DECLARE request_id varchar(255);
    SET request_id = UUID();
    INSERT INTO Request_donation (Request_id, Email, Item, Description)
        VALUES (request_id, p_email, p_item, p_description);
END //
DELIMITER ;

ALTER TABLE Request_donation MODIFY COLUMN Email VARCHAR(30) NOT NULL, DROP FOREIGN KEY other_donation_ibfk_1,ADD CONSTRAINT other_donation_ibfk_1 FOREIGN KEY (Email) REFERENCES User(Email) ON UPDATE CASCADE;

alter table Request_donation add column Status enum('Pending','Approved','Rejected') default 'Pending';

UPDATE Charity.Request_donation SET Status = 'Approved' WHERE Request_id = '348e248c-882f-11ee-b548-d88083d593b0';

