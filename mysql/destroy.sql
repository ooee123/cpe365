ALTER TABLE Accounts
   DROP FOREIGN KEY Accounts_goalId;

ALTER TABLE Users
   DROP FOREIGN KEY Users_defaultAcc;

DROP TABLE Goals;
DROP TABLE Transfers;
DROP TABLE Transactions;
DROP TABLE Categories;
DROP TABLE Accounts;
DROP TABLE Users;