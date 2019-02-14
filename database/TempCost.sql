1) Quotation - Relook
2) Exact Oil - Website
3) Inventory - Rental - Asset - Value depreciate - Rental sub entry - Available for rental
	- available - Cost - value - depreciate value
	- Out for rental..
	- return from customer (available)
	- Send for service inspection
	- Return from inspection - available for rental
	- Depreciated - write off
	- link with order processing - customer
	- estimated return date, actual return date


	- retal report monthly - like stock count

4) order processing)
- receive PO - .. create job number
- temporary cost report.


I wanted to separate out rental units from stock units. Currently if the status is Rental (means out for rental). Then when customer return, they change it to reserve. This is not very good.
I wanted to segragate for rental business, means units issued out from stock for rental, the stock status will be 'Issue out rental'. Then in the Rental control inventory will have 'Available'. Customer send PO for rental, then status change to 'Out for Rental'. Customer return, status is 'Return from Customer'. Then 'Send to Service Inspection'. Then 'Available'.
One more status is 'Write Off' for damage unit.
Those existing rental units we will have to issue out from current inventory & transfer Rental. For example, 198 units under rental, & 216 units under reserve. There is confusion whether the units are with customer on rental or customer return the units after rental period expired.


SELECT * FROM JobPurchase, JobPurchaseDelivery


SELECT Job.ID as JobID, JobPurchaseDelivery.* FROM Job LEFT JOIN JobPurchaseDelivery ON (JobPurchaseDelivery.JobID=Job.ID) WHERE JobPurchaseDelivery.ID IS NOT NULL

---------------------------------------------------
---------------------------------------------------

SELECT count(*) as Total, Job.ID as JobID FROM Job LEFT JOIN JobPurchaseDelivery ON (JobPurchaseDelivery.JobID=Job.ID AND DeliveryReceivedDate IS NOT NULL) WHERE JobPurchaseDelivery.ID IS NOT NULL GROUP BY Job.ID ORDER BY Total desc

SELECT count(*) as Total, Job.ID as JobID, MAX(DeliveryReceivedDate) as LatestReceivedDate, Sum(DutyTax) as TotalDutyTax, Sum(FreightCost) as TotalFreightCost, GROUP_CONCAT(JobPurchaseDelivery.JobPurchaseID) FROM Job LEFT JOIN JobPurchaseDelivery ON (JobPurchaseDelivery.JobID=Job.ID AND DeliveryReceivedDate IS NOT NULL) WHERE JobPurchaseDelivery.ID IS NOT NULL GROUP BY Job.ID ORDER BY Total desc


------ **************** --------------
SELECT Job.ID as JobID, MAX(DeliveryReceivedDate) as LatestReceivedDate, Sum(DutyTax) as TotalDutyTax, Sum(FreightCost) as TotalFreightCost, Sum(JobPurchaseDelivery.PurchasePriceRM) TotalPurchasePriceRM FROM Job
LEFT JOIN
(SELECT (JobPurchase.PurchasePrice * JobPurchase.PurchasePriceExchangeRate) as PurchasePriceRM, JobPurchaseDelivery.ID, JobPurchaseDelivery.JobID, sum(DutyTax) as DutyTax,sum(FreightCost) as FreightCost, MAX(DeliveryReceivedDate) as DeliveryReceivedDate FROM JobPurchase, JobPurchaseDelivery WHERE JobPurchase.ID=JobPurchaseDelivery.JobPurchaseID AND DeliveryReceivedDate IS NOT NULL GROUP BY JobPurchaseDelivery.JobPurchaseID) as JobPurchaseDelivery
ON (JobPurchaseDelivery.JobID=Job.ID AND DeliveryReceivedDate IS NOT NULL)
WHERE JobPurchaseDelivery.ID IS NOT NULL AND (Job.JobType!='R' AND Job.JobType!='L') GROUP BY Job.ID
---------------------------------------------------



------ **************** --------------
SELECT count(*) as Total, Job.ID as JobID, Sum(JobSales.SalesPrice * SalesPriceExchangeRate) FROM Job LEFT JOIN JobSales ON (JobSales.JobID=Job.ID AND SalesReadyDate IS NOT NULL) WHERE JobSales.ID IS NOT NULL AND Job.JobType='T' GROUP BY Job.ID ORDER BY Total desc, JobID
SELECT count(*) as Total, Job.ID as JobID, Sum(JobSales.SalesPrice * SalesPriceExchangeRate) FROM Job LEFT JOIN JobSales ON (JobSales.JobID=Job.ID AND SalesReadyDate IS NULL) WHERE JobSales.ID IS NOT NULL AND Job.JobType='T' GROUP BY Job.ID ORDER BY Total desc, JobID
---------------------------------------------------


------ **************** --------------
SELECT JobPurchase.*, JobSales.* FROM Job LEFT JOIN
(
	SELECT Count(*) as TotalPurchase, Job.ID as JobID, MAX(DeliveryReceivedDate) as LatestReceivedDate, Sum(DutyTax) as TotalDutyTax, Sum(FreightCost) as TotalFreightCost, Sum(JobPurchaseDelivery.PurchasePriceRM) TotalPurchasePriceRM FROM Job
	LEFT JOIN
	(SELECT (JobPurchase.PurchasePrice * JobPurchase.PurchasePriceExchangeRate) as PurchasePriceRM, JobPurchaseDelivery.ID, JobPurchaseDelivery.JobID, sum(DutyTax) as DutyTax,sum(FreightCost) as FreightCost, MAX(DeliveryReceivedDate) as DeliveryReceivedDate FROM JobPurchase, JobPurchaseDelivery WHERE JobPurchase.ID=JobPurchaseDelivery.JobPurchaseID AND DeliveryReceivedDate IS NOT NULL GROUP BY JobPurchaseDelivery.JobPurchaseID) as JobPurchaseDelivery
	ON (JobPurchaseDelivery.JobID=Job.ID AND DeliveryReceivedDate IS NOT NULL)
	WHERE JobPurchaseDelivery.ID IS NOT NULL AND (Job.JobType!='R' AND Job.JobType!='L') GROUP BY Job.ID
) as JobPurchase ON Job.ID=JobPurchase.JobID LEFT JOIN
(
	SELECT Count(*) as TotalSales, Job.ID as JobID, Sum(JobSales.SalesPrice * SalesPriceExchangeRate) as TotalSalesPriceRM, JobSales.SalesReadyDate FROM Job LEFT JOIN JobSales ON (JobSales.JobID=Job.ID) WHERE (Job.JobType!='R' AND Job.JobType!='L') GROUP BY Job.ID
) as JobSales ON Job.ID=JobSales.JobID WHERE JobPurchase.LatestReceivedDate IS NOT NULL AND JobSales.SalesReadyDate IS NULL

---------------------------------------------------








----------------------------------------------------
----------------------------------------------------
Item to check
http://exactjob.macpro/default/index/index/edit_job/2464#tabs1
- 4013 - Delivery Details contains remarks but the Good Received is empty
- 4013-B - Multiple Delivery Details with some entry that has Good Received is empty
- 4013-F - With Duty and Tax but Good Received is empty

http://exactjob.macpro/default/index/index/edit_job/3007

http://exactjob.macpro/default/index/index/edit_job/1039

