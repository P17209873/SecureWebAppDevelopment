DROP DATABASE IF EXISTS swadcoursework;

CREATE DATABASE swadcoursework;

USE swadcoursework;

GRANT ALL ON swadcoursework.* to 'coursework'@'localhost' IDENTIFIED BY 'password';

DROP TABLE IF EXISTS users;

CREATE TABLE users (
	userid INT(11) NOT NULL AUTO_INCREMENT,
	userusername VARCHAR(25) NOT NULL,
	userpassword VARCHAR(256) NOT NULL,
	useremail VARCHAR(250) NOT NULL,
	userfirstname VARCHAR(30) NOT NULL,
	userlastname VARCHAR(30) NOT NULL,
	usercreationtimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (userid)
);

DROP TABLE IF EXISTS circuitboardstates;

CREATE TABLE circuitboardstates (
	stateid INT(11) NOT NULL AUTO_INCREMENT,
	switch01state VARCHAR(5) NOT NULL,
	switch02state VARCHAR(5) NOT NULL,
	switch03state VARCHAR(5) NOT NULL,
	switch04state VARCHAR(5) NOT NULL,
	fanstate VARCHAR(10) NOT NULL,
	heatertemperature VARCHAR(10) NOT NULL,
	keypadvalue VARCHAR(1) NOT NULL,
	PRIMARY KEY (stateid)
);

DROP TABLE IF EXISTS retrievedmessages;

CREATE TABLE retrievedmessages(
	retrievedmessageid INT(11) NOT NULL AUTO_INCREMENT,
	messagesentto VARCHAR(35) NOT NULL,
	messagesentfrom VARCHAR(35) NOT NULL,
	receivedtime VARCHAR(70),
	bearer VARCHAR(5),
	messageref VARCHAR (5),
	stateid INT(11) NOT NULL,
	PRIMARY KEY (retrievedmessageid),
  	FOREIGN KEY (stateid) REFERENCES  circuitboardstates(stateid)
);

DROP TABLE IF EXISTS userloginlogs;

CREATE TABLE userloginlogs (
	userloginlogsid INT(11) NOT NULL AUTO_INCREMENT,
	userid INT(11) NOT NULL,
	logincompleted BOOLEAN,
	logintimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (userloginlogsid),
	FOREIGN KEY (userid) REFERENCES users(userid)
);
