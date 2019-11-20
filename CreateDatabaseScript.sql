DROP DATABASE IF EXISTS SWADCoursework;

CREATE DATABASE SWADCoursework;

USE SWADCoursework;

GRANT ALL ON SWADCoursework.* to 'coursework'@'localhost' IDENTIFIED BY 'Password';

DROP TABLE IF EXISTS Users;

CREATE TABLE Users (
	UserID INT(11) NOT NULL AUTO_INCREMENT,
	UserUsername VARCHAR(25) NOT NULL,
	UserPassword VARCHAR(50) NOT NULL,
	UserFirstName VARCHAR(30) NOT NULL,
	UserLastName VARCHAR(30) NOT NULL,
	PRIMARY KEY (UserID)
);

DROP TABLE IF EXISTS CircuitBoardStates;

CREATE TABLE CircuitBoardStates (
	StateID INT(11) NOT NULL AUTO_INCREMENT,
	Switch01State VARCHAR(5) NOT NULL,
	Switch02State VARCHAR(5) NOT NULL,
	Switch03State VARCHAR(5) NOT NULL,
	Switch04State VARCHAR(5) NOT NULL,
	FanState VARCHAR(10) NOT NULL,
	HeaterTemperature VARCHAR(10) NOT NULL,
	KeypadValue VARCHAR(1) NOT NULL
);

DROP TABLE IF EXISTS RetrievedMessages;

CREATE TABLE RetrievedMessages(
	RetrievedMessageID INT(11) NOT NULL AUTO_INCREMENT,
	MessageSentTo VARCHAR(35) NOT NULL,
	MessageSentFrom VARCHAR(35) NOT NULL,
	UserID INT(11) NOT NULL,
	StateID INT(11) NOT NULL,
	PRIMARY KEY (RetrievedMessageID),
	FOREIGN KEY (UserID) REFERENCES Users(UserID),
  FOREIGN KEY (StateID) REFERENCES  CircuitBoardStates(StateID)
);

DROP TABLE IF EXISTS Logs;

CREATE TABLE Logs (
	LogID INT(11) NOT NULL AUTO_INCREMENT,
	LogType VARCHAR(25) NOT NULL,
	LogMessage VARCHAR(500) NOT NULL,
	UserID INT(11) NOT NULL,
	RetrievedMessageID INT(11),
	CircuitBoardStateID INT(11),
	PRIMARY KEY (LogID),
	FOREIGN KEY (UserID) REFERENCES Users(UserID),
	FOREIGN KEY (RetrievedMessageID) REFERENCES RetrievedMessages(RetrievedMessageID),
	FOREIGN KEY (CircuitBoardStateID) REFERENCES  CircuitBoardStates(StateID)
);