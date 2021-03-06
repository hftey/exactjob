SELECT COUNT(*) AS counter, Name, ID FROM (SELECT * FROM Customers ORDER BY ID ASC) as Customers GROUP BY Name ORDER BY counter DESC


DELETE FROM Customers WHERE Customers.ID IN (
	SELECT * FROM (
		SELECT Customers.ID FROM Customers, (SELECT COUNT(*), Name, ID FROM (SELECT * FROM Customers ORDER BY ID ASC) as Customers GROUP BY Name) as CustomerData WHERE Customers.Name=CustomerData.Name AND Customers.ID!=CustomerData.ID
	) as p
)

UPDATE Job, (SELECT COUNT(*), Name, ID FROM (SELECT * FROM Customers ORDER BY ID ASC) as Customers GROUP BY Name) as CustomerData SET
Job.CustomerID=CustomerData.ID WHERE Job.CustomerName=CustomerData.Name

SELECT Job.CustomerName, Job.CustomerID, CustomerData.ID FROM Job, (SELECT COUNT(*), Name, ID FROM (SELECT * FROM Customers ORDER BY ID ASC) as Customers GROUP BY Name) as CustomerData WHERE Job.CustomerName=CustomerData.Name



SELECT COUNT(*), Name, ID FROM (SELECT * FROM Customers ORDER BY ID ASC) as Customers GROUP BY Name

SELECT COUNT(*), CustomerName, CustomerID, GROUP_CONCAT(CustomerID) FROM (SELECT * FROM Job ORDER BY CustomerID ASC) as Job GROUP BY CustomerName





//////////////////////////////////////////////////////////////
SELECT COUNT(*), Name, CODE, ID FROM (SELECT * FROM Supplier ORDER BY ID ASC) as Supplier GROUP BY Name

SELECT COUNT(*), SupplierName, SupplierID, GROUP_CONCAT(SupplierID) FROM (SELECT * FROM JobPurchase ORDER BY SupplierID ASC) as JobPurchase GROUP BY SupplierName


DELETE FROM Supplier WHERE Supplier.ID IN (
	SELECT * FROM (
		SELECT Supplier.ID FROM Supplier, (SELECT COUNT(*), Name, ID FROM (SELECT * FROM Supplier ORDER BY ID ASC) as Supplier GROUP BY Name) as SupplierData WHERE Supplier.Name=SupplierData.Name AND Supplier.ID!=SupplierData.ID
	) as p
)


UPDATE JobPurchase, (SELECT COUNT(*), Name, ID FROM (SELECT * FROM Supplier ORDER BY ID ASC) as Supplier GROUP BY Name) as SupplierData SET
JobPurchase.SupplierID=SupplierData.ID WHERE JobPurchase.SupplierName=SupplierData.Name


ALTER TABLE Supplier DROP COLUMN `ID`;
ALTER TABLE Supplier ADD COLUMN `ID` int(10) unsigned primary KEY AUTO_INCREMENT FIRST;