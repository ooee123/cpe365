-- KEVIN LY
-- JOSEPHINE SUEN

CREATE TABLE Users (
   id INT PRIMARY KEY AUTO_INCREMENT,
   firstName VARCHAR(32) NOT NULL,
   lastName VARCHAR(32) NOT NULL,
   nickName VARCHAR(32),
   userName VARCHAR(32) UNIQUE,
   password VARCHAR(32) NOT NULL,
   defaultAcc INT
);

CREATE TABLE Accounts ( 
   accId INT PRIMARY KEY AUTO_INCREMENT,
   userId INT NOT NULL,
   accType VARCHAR(16) NOT NULL, -- Either 'checkings' OR 'savings'
   goalId INT,
   accName VARCHAR(32) NOT NULL DEFAULT 'Primary Account',
   CONSTRAINT Accounts_userId FOREIGN KEY(userId) REFERENCES Users(id)
);

CREATE TABLE Categories (
   catId INT PRIMARY KEY AUTO_INCREMENT,
   category VARCHAR(32) NOT NULL
);

CREATE TABLE Transactions (
   transId INT PRIMARY KEY AUTO_INCREMENT,
   accId INT NOT NULL,
   payDate DATE NOT NULL,
   paidToFrom VARCHAR(32),
   amount INT NOT NULL,
   description VARCHAR(128),
   category INT,
   CONSTRAINT Transactions_accID FOREIGN KEY(accId) REFERENCES Accounts(accId),
   CONSTRAINT Transactions_category FOREIGN KEY(category) REFERENCES Categories(catId)
);

CREATE TABLE Transfers ( -- Between accounts of the same user
   transId INT PRIMARY KEY AUTO_INCREMENT,
   accId INT NOT NULL,
   transferTo INT NOT NULL,
   date DATE NOT NULL,
   amount INT NOT NULL,
   description VARCHAR(128),
   CONSTRAINT Transfers_accId FOREIGN KEY(accId) REFERENCES Accounts(accId),
   CONSTRAINT Transfers_transferTo FOREIGN KEY(transferTo) REFERENCES Accounts(accId)
);

CREATE TABLE Goals(
   goalId INT PRIMARY KEY AUTO_INCREMENT,
   accId INT NOT NULL,
   amount INT NOT NULL,
   startDate DATE, -- A savings goal does not expect start date
   endDate DATE, -- Do the math computing the end date on the form
   CONSTRAINT Goals_accId FOREIGN KEY(accId) REFERENCES Accounts(accId)
);

ALTER TABLE Users
   ADD CONSTRAINT Users_defaultAcc FOREIGN KEY(defaultAcc) REFERENCES Accounts(accId);

ALTER TABLE Accounts
   ADD CONSTRAINT Accounts_goalId FOREIGN KEY(goalId) REFERENCES Goals(goalId);