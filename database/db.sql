CREATE DATABASE IF NOT EXISTS rizzort;

USE rizzort;

CREATE TABLE IF NOT EXISTS TblGuest (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Arrival_Date Date,
    Guest_Name VARCHAR(255),
    Guest_Email VARCHAR(255),
    Guest_Contact VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Tblreserve (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Guest_Id INT,
    Guest_Room VARCHAR(255),
    Guest_Type VARCHAR(255),
    Guest_Number INT,
    Guest_Price INT
);