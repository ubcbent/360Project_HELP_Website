/*For Clearing Orders */
DELETE FROM Orders;
DELETE FROM InOrder;
DELETE FROM Customer;
DELETE FROM Address;

DROP TABLE Charges;
DROP TABLE Payment;
DROP TABLE InOrder;
DROP TABLE Orders;
DROP TABLE Supplies;
DROP TABLE Supplier;
DROP TABLE Drug;
DROP TABLE Administrator;
DROP TABLE Customer;
DROP TABLE Address;

CREATE TABLE Address (
	ad_id INTEGER NOT NULL,
	street CHAR(36),
	city CHAR(12),
	state CHAR(12),
	province CHAR(12),
	country CHAR(30),
	postalCode CHAR(7),
	PRIMARY KEY (ad_id)
);

CREATE TABLE Administrator (
	adminId INTEGER NOT NULL,
	password VARCHAR(12),
	acType VARCHAR(6),
	adminUserName CHAR(30),
	PRIMARY KEY (adminId)
);

CREATE TABLE Customer (
	customerId INTEGER NOT NULL,
	password VARCHAR(24),
	acType VARCHAR(12),
	cname VARCHAR(50),
	email VARCHAR(50),
	ad_id INTEGER,
	PRIMARY KEY (customerId),
	FOREIGN KEY (ad_id) REFERENCES Address(ad_id) ON DELETE CASCADE
);


/*CREATE TABLE BankTransfer (
	p_id INTEGER NOT NULL,
	p_type CHAR(12),
	customerId INTEGER,
	bankAccNum CHAR(16),
	bankName CHAR(12),
	branchNum INTEGER,
	PRIMARY KEY (p_id),
	FOREIGN KEY (customerId) REFERENCES Customer(customerId) ON DELETE CASCADE
);*/

CREATE TABLE Payment (
	p_id INTEGER NOT NULL,
	p_type CHAR(12),
	customerId INTEGER,
	cardNum CHAR(16),
	expiry DATE,
	cvc INTEGER,
	PRIMARY KEY (p_id),
	FOREIGN KEY (customerId) REFERENCES Customer(customerId) ON DELETE CASCADE
);

/*CREATE TABLE PayPal (
	p_id INTEGER NOT NULL,
	p_type CHAR(12),
	customerId INTEGER,
	p_accNum CHAR(16),
	PRIMARY KEY (p_id),
	FOREIGN KEY (customerId) REFERENCES Customer(customerId) ON DELETE CASCADE
);*/

CREATE TABLE Drug (
	d_id INTEGER NOT NULL,
	d_name VARCHAR(36),
	categoryName varchar(50),
	packageDesc	varchar(320),
	d_price DECIMAL(9,2),
	PRIMARY KEY (d_id)
);

CREATE TABLE Supplier (
	s_id INTEGER NOT NULL,
	s_name varchar(30),
	ad_id INTEGER,
	PRIMARY KEY (s_id),
	FOREIGN KEY (ad_id) REFERENCES Address(ad_id) ON DELETE CASCADE
);

CREATE TABLE Supplies(
	s_id INTEGER,
	d_id INTEGER,
	FOREIGN KEY (d_id) REFERENCES Drug(d_id) ON DELETE CASCADE,
	FOREIGN KEY (s_id) REFERENCES Supplier(s_id) ON DELETE CASCADE
);

CREATE TABLE Orders (
	orderId INTEGER NOT NULL,
	o_date DATE,
	customerId INTEGER,
	totalAmount DECIMAL(9,2),
	PRIMARY KEY (orderId),
	FOREIGN KEY (customerId) REFERENCES Customer(customerId) ON DELETE CASCADE
);

CREATE TABLE InOrder (
	orderId INTEGER NOT NULL,
	d_id INTEGER NOT NULL,
	s_id INTEGER NOT NULL,
	qty INTEGER,
	price DECIMAL(9,2),
	PRIMARY KEY (orderId, d_id, s_Id),
	FOREIGN KEY (orderId) REFERENCES Orders(orderId) ON DELETE CASCADE,
	FOREIGN KEY (d_id) REFERENCES Drug(d_id) ON DELETE CASCADE,
	FOREIGN KEY (s_id) REFERENCES Supplier(s_id) ON DELETE CASCADE
);

CREATE TABLE Charges (
	orderId INTEGER NOT NULL,
	p_id INTEGER NOT NULL,
	amount double(9,2),
	PRIMARY KEY (orderId, p_id),
	FOREIGN KEY (orderId) REFERENCES Orders(orderId) ON DELETE CASCADE,
	FOREIGN KEY (orderId) REFERENCES Payment(p_id) ON DELETE CASCADE
);

/* Insert Values into relations */
INSERT INTO Address VALUES(1,'333 Central Crescent','Tulum','Sinaloa','','Mexico','10210');
INSERT INTO Address VALUES(2,'333 Central Crescent','Kabul','','','Afganistan','20210');
INSERT INTO Address VALUES(3,'333 Central Crescent','Bogota','','','Columbia','30210');
INSERT INTO Address VALUES(4,'333 Central Crescent','Kelowna','','BC','Canada','V1X2T9');
INSERT INTO Address VALUES(5,'333 Central Crescent','Chicago','IL','','USA','90210');
INSERT INTO Address VALUES(6,'333 Central Crescent','Chicago','IL','','USA','90210');

INSERT INTO Administrator VALUES(1,'west','admin','plamondon');
INSERT INTO Administrator VALUES(2,'west','admin','englehart');

INSERT INTO Customer VALUES ( 1, 'password1','customer', 'A. Anderson', 'aanderson@anywhere.com',1);
INSERT INTO Customer VALUES ( 2, 'badpass','customer', 'B. Brown','bbrown@bigcompany.com', 2);
INSERT INTO Customer VALUES ( 3, 'AxBC12', 'customer','C. Cole','cole@charity.org',3 );
INSERT INTO Customer VALUES ( 4, '1234abc', 'customer','D. Doe','doe@doe.com',4 );
INSERT INTO Customer VALUES ( 5, 'ABCD1245', 'customer','E. Elliott', 'engel@uiowa.edu',5);

INSERT INTO Drug VALUES(1,'Black Tar Heroin','Heroin','Tar-like sticky feel. Usually injected into the veins. ',98.00);
INSERT INTO Drug VALUES(2,'Brown Herion','Heroin','Brown heroin is produced in the first stage of purification of the drug',110.00);
INSERT INTO Drug VALUES(3,'White Heroin','Heroin','Purest form of the drug available',122.00);
INSERT INTO Drug VALUES(4,'Liquid Extacy','Extacy','Liquid form highly water soluble.',99.00);
INSERT INTO Drug VALUES(5,'5mg Pill Extacy','Extacy','MDMA,Cocain & Speed together',21.35);
INSERT INTO Drug VALUES(6,'MDMA','Extacy','Purest form',25.00);
INSERT INTO Drug VALUES(7,'Liquid LSD','Lsd','Liquid form',10.00);
INSERT INTO Drug VALUES(8,'Paper LSD','Lsd','Sheets',30.00);
INSERT INTO Drug VALUES(9,'Bubba Kush','Pot','Dried Buds',5.00);
INSERT INTO Drug VALUES(10,'Hash','Pot','Bubble Hash',10.00);
INSERT INTO Drug VALUES(11,'Budder','Pot','Substrate',40.00);
INSERT INTO Drug VALUES(12,'Magical Coconut Oil','Pot','Cannibis Infused Coconut Oil',20.00);
INSERT INTO Drug VALUES(13,'Oil','Pot','Pure Alcohol Extract',20.00);

INSERT INTO Supplier VALUES(1,'Mexico','1');
INSERT INTO Supplier VALUES(2,'Afganistan','2');
INSERT INTO Supplier VALUES(3,'Columbia','3');
INSERT INTO Supplier VALUES(4,'Kelowna','4');

INSERT INTO Supplies VALUES(2,1);
INSERT INTO Supplies VALUES(2,2);
INSERT INTO Supplies VALUES(2,3);
INSERT INTO Supplies VALUES(4,4);
INSERT INTO Supplies VALUES(4,5);
INSERT INTO Supplies VALUES(4,6);
INSERT INTO Supplies VALUES(3,7);
INSERT INTO Supplies VALUES(3,8);
INSERT INTO Supplies VALUES(1,9);
INSERT INTO Supplies VALUES(1,10);
INSERT INTO Supplies VALUES(1,11);
INSERT INTO Supplies VALUES(1,12);
INSERT INTO Supplies VALUES(1,13);
	
INSERT INTO Orders VALUES (1,'2017-01-22',1,174.05);
INSERT INTO InOrder VALUES (1,1,3,1,98.0);
INSERT INTO InOrder VALUES (1,5,1,3,21.35);

/* INSERT INTO Orders(o_date, customerId) VALUES ('2017-07-11',1);*/

/* Triggers */
/*CREATE TRIGGER insertOrders
	BEFORE INSERT ON Orders
	REFERENCING NEW ROW AS A
	FOR EACH ROW
	WHEN (A.orderId = NULL)
	BEGIN
		UPDATE Orders SET orderId = (SELECT MAX(orderId) FROM Orders GROUP BY orderId) + 1;
	END;*/

