INSERT INTO Users (firstName, lastName, nickName, userName, password)
VALUES
   ('Kevin', 'Ly', NULL, 'ooee', '5f4dcc3b5aa765d61d8327deb882cf99'),
   ('Abe', 'Allen', 'Abey', 'Abe', '6cb75f652a9b52798eb6cf2201057c73'),
   ('Bernard', 'Black', 'Bernie', 'Bern99', '819b0643d6b89dc9b579fdfc9094f28e'),
   ('Josephine', 'Suen', 'Fin', 'jsuen', '5baa61e4c9b93f3f0682250b6cf8331b'),
   ('Miles', 'Young', 'Meelay', 'myoung', '2aa60a8ff7fcd473d321e0146afd9e26');

INSERT INTO Accounts (userId, accType, accName)
VALUES
   (1, 'checkings', 'General'),
   (1, 'savings', 'Next year seedlings'),
   (2, 'savings', 'Life-insurance savings');

INSERT INTO Categories
VALUES
   (1, 'Bills'),
   (2, 'Entertainment'),
   (3, 'Gas'),
   (4, 'Groceries'),
   (5, 'Merchandise'),
   (6, 'Restaurant'),
   (7, 'Paycheck'),
   (8, 'Other'),
   (9, 'Transfer');

INSERT INTO Transactions (accId, payDate, paidToFrom, amount, description, category)
VALUES
   (1, DATE('2000-01-01'), 'Grandma', -55, 'Birthday', 8),
   (1, DATE('2000-02-01'), 'Grandpa', 55, 'Birthday', 8),
   (1, DATE('2000-03-01'), 'Auntie', 25, 'Birthday', 8),
   (1, DATE('2000-04-01'), 'Paycheck', 150, NULL, 7),
   (1, DATE('2000-05-01'), 'Found it', 5, 'Corner of California & Taft', 8),
   (1, DATE('2000-06-01'), 'Side job', 50, 'Washed cars', 7),
   (1, DATE('2000-07-01'), 'Assassination', 666, 'Archduke Ferdinand', 7),
   (1, DATE('2000-08-01'), 'Drug dealing', 400, 'Cold medicine', 7),
   (1, DATE('2000-09-01'), 'Grandma', 55, 'Half-birthday', 8),
   (1, DATE('2000-05-06'), 'Paycheck', 150, NULL, 7),
   (1, DATE('2000-03-05'), 'Burglary', 200, '123 Sesame Street', 2),
   (1, DATE('2000-02-04'), 'Sold bike', 120, '2010 Schwinn Mountain Bike', 8),
   (1, DATE('2000-01-03'), 'Reverse mortgage', 500, '', 7),
   (2, DATE('2000-12-03'), 'Payday Advance', 500, '', 7),
   (1, DATE('2000-01-01'), 'Bookstore', -20, 'Whiteboard', 8),
   (1, DATE('2000-02-01'), 'Woodstock', -40, 'Extra large pizza', 8),
   (1, DATE('2000-03-01'), 'Kona\'s', -15, 'The Destroyer', 8);


   
