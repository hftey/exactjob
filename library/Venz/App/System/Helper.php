<?php
 
class Venz_App_System_Helper extends Zend_Db_Table_Abstract
{
	protected $_db  = NULL;

	public function __construct($DbMode = Zend_Db::FETCH_ASSOC)
	{
		parent::__construct();
		$this->_db = $this->getAdapter();
		$this->_db->setFetchMode($DbMode);
	}
	
	public function setFetchMode($DbMode = Zend_Db::FETCH_ASSOC)
	{
		$this->_db->setFetchMode($DbMode);
	}
	
	public function getTerms($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
    {
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT Terms.ID, Terms.Name, Terms.Code FROM Terms WHERE 1=1 ";
		if ($searchString)
			$sqlAll .= $searchString;
		$sql .= $sqlAll." order by $sql_orderby $sql_limit";


		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql));
    }
	
	public function getCurrency($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
    {
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT Currency.ID, Currency.Name, Currency.Code, Currency.Rate, Currency.LastModified FROM (SELECT * FROM (SELECT * FROM Currency order by ID desc) as Currency GROUP BY Code) as Currency WHERE 1=1 ";
		if ($searchString)
			$sqlAll .= $searchString;
		$sql .= $sqlAll." order by $sql_orderby $sql_limit";


		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql));
    }
		
	public function getSupplier($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
    {
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT Supplier.ID, Supplier.Name, Supplier.Code, Supplier.Phone, Supplier.FaxNo, Supplier.Email, Supplier.PaymentTerms, Supplier.Address, JobPurchase.TotalJob FROM Supplier ".
			" LEFT JOIN (SELECT COUNT(*) as TotalJob, JobPurchase.SupplierID FROM JobPurchase Group BY SupplierID) as JobPurchase ON (JobPurchase.SupplierID=Supplier.ID) WHERE 1=1 ";
		if ($searchString)
			$sqlAll .= $searchString;
		$sql .= $sqlAll." order by $sql_orderby $sql_limit";


		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql));
    }
	
	public function getCustomers($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
    {
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
//		$sqlAll = "SELECT Customers.ID, Customers.Name, Customers.Code, Customers.Phone, Customers.FaxNo, Customers.Email, Customers.PaymentTerms, Customers.Address, Job.TotalJob, Customers.CreditLimit FROM Customers ".
//			" LEFT JOIN (SELECT COUNT(*) as TotalJob, Job.CustomerID FROM Job Group BY CustomerID) as Job ON (Job.CustomerID=Customers.ID) WHERE 1=1 ";
		$sqlAll = "SELECT Customers.ID, Customers.Name, Customers.Code, Customers.Phone, Customers.FaxNo, Customers.Email, ".
            /*6*/ "Customers.PaymentTerms, Customers.Address, Job.TotalJob, Customers.CreditLimit, Customers.Attn FROM  ".
			" (SELECT * FROM Customers order by ID asc) as Customers ".
			" LEFT JOIN (SELECT COUNT(*) as TotalJob, Job.CustomerID, Customers.Name FROM Job LEFT JOIN Customers on (Job.CustomerID=Customers.ID) Group BY Customers.Name) as Job ON (Job.Name=Customers.Name) WHERE 1=1 ";
		if ($searchString)
			$sqlAll .= $searchString;
		
		$sqlAll .= " group by Customers.Name ";
		
		$sql .= $sqlAll." order by $sql_orderby $sql_limit ";


		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql));
    }
 
	public function getJobsPurchase($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null, $searchStringDelivery = null)
    {
		if ($showPage	< 0 || $showPage == "") $showPage = 1;

		$sqlDelivery = "";
		if ($searchStringDelivery) 
			$sqlDelivery = " AND JobPurchaseDelivery.DeliveryAWB IS NOT NULL";
		
		
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT Job.ID, Job.JobNo, Job.CustomerName, Job.CustomerPOReceivedDate, NULL, ".
		/*5*/ "NULL, Job.Items, JobPurchase.PONo, JobPurchase.PODate, JobPurchase.POFaxedDate, JobPurchase.SupplierName, JobPurchase.SupplierCode, ".
		/*12*/ "JobPurchase.PurchaseCurrency, JobPurchase.PurchasePrice, JobPurchase.PurchasePriceExchangeRate, JobPurchase.PurchaseTerms, ".
		/*16*/ "JobPurchase.PurchaseAckNO, JobPurchase.PurchaseShippingDate, JobPurchase.PurchaseShippingActualDate, JobPurchase.PurchaseInvoiceNo, JobPurchase.PurchasePaymentDate, ".
		/*21*/ "JobPurchaseDelivery.DeliveryAWB, JobPurchaseDelivery.DeliveryReceivedDate, JobPurchaseDelivery.DutyTax, JobPurchaseDelivery.FreightCost, JobPurchaseDelivery.Remarks,  ".
		/*26*/ "JobPurchase.ID as JobPurchaseID, (JobPurchase.PurchasePriceExchangeRate * JobPurchase.PurchasePrice) as PurchaseAmountRM, Job.Completed".
		" FROM Job, JobPurchase ".
		" LEFT JOIN (SELECT GROUP_CONCAT(DeliveryAWB separator ',<BR>') as DeliveryAWB, GROUP_CONCAT(IF(DeliveryReceivedDate, DATE_FORMAT(DeliveryReceivedDate, '%d-%m-%Y'), '') separator '<BR>') as DeliveryReceivedDate, ".
		" GROUP_CONCAT(CONCAT(IF (DutyTax, DutyTax, '')) separator '<BR>') as DutyTax, GROUP_CONCAT(CONCAT(IF(FreightCost, FreightCost, '')) separator '<BR>') as FreightCost, GROUP_CONCAT(Remarks separator '<BR>') as Remarks, ".
		" JobPurchaseID FROM (SELECT * FROM JobPurchaseDelivery WHERE 1=1 ".$searchStringDelivery.") as JobPurchaseDelivery GROUP BY JobPurchaseID) as JobPurchaseDelivery ON (JobPurchaseDelivery.JobPurchaseID=JobPurchase.ID) ".
		" WHERE Job.ID=JobPurchase.JobID ".$sqlDelivery;
		
		
		if ($searchString)
			$sqlAll .= $searchString;
		$sql .= $sqlAll." order by $sql_orderby $sql_limit";

	//	print $sql; exit();
		$sqlExport = $sqlAll." order by $sql_orderby ";
		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql), $sqlExport);
    }
	
	public function getJobsSales($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
    {
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT Job.ID as JobID, Job.JobNo, Job.CustomerName, JobSales.CustomerPOReceivedDate, JobSales.EOGSTSBPO, ".
		/*5*/ "JobSales.CustomerPO, Job.Items, JobSales.SalesCurrency, JobSales.SalesPrice, JobSales.SalesTerms, ".
		/*10*/ "JobSales.SalesInspReportNo, JobSales.SalesOrderAckNo, JobSales.SalesExpDate, JobSales.SalesReadyDate, JobSales.SalesInvoiceDate, ".
		/*15*/ "JobSales.SalesInvoiceNo, JobSales.SalesDO, JobSales.EOGSTSBDO, JobSales.ServiceReportNo, ACLUsers.Name as SalesPerson, Job.JobType, ".
		/*21*/ "JobSales.DrawingApprovedDate, Job.Completed, JobPayments.PaymentAmountRM, JobClaims.TotalClaimsRM, (ifnull(JobPayments.PaymentAmountRM, 0) - ifnull(JobClaims.TotalClaimsRM, 0) - ifnull(JobPurchase.TotalDeliveryCost,0)) as MarginRM, ".
		/*26*/ "(JobSales.SalesPrice * JobSales.SalesPriceExchangeRate) as SalesRM, SalesPriceExchangeRate, Job.CompletedDate, JobPurchase.TotalDeliveryCost, ".
		/*30*/ " (ifnull((JobSales.SalesPrice * JobSales.SalesPriceExchangeRate), 0) - ifnull(JobPurchase.TotalPurchasePriceRM, 0) - ifnull(JobClaims.TotalClaimsRM, 0) - ifnull(JobPurchase.TotalDeliveryCost,0)) as ProjectMarginRM, ".
		/*31*/ " JobSales.ID as JobSalesID, Job.PrincipleName ".
		" FROM Job, JobSales LEFT JOIN ACLUsers ON (JobSales.SalesPersonID=ACLUsers.ID) ".
		" LEFT JOIN (SELECT SUM(PaymentAmountRM) as PaymentAmountRM, JobID FROM  ".
		" (SELECT IF(PaymentReceive, PaymentCurrencyExchangeRate * PaymentAmount, PaymentCurrencyExchangeRate * PaymentAmount * -1) as PaymentAmountRM, JobID FROM JobPayments) as JobPayments GROUP BY JobID) as JobPayments ON (JobPayments.JobID = JobSales.JobID) ".
		" LEFT JOIN (SELECT SUM(ClaimCurrencyExchangeRate * ClaimAmount) as TotalClaimsRM, JobID FROM JobClaims GROUP BY JobID) as JobClaims ON (JobClaims.JobID=JobSales.JobID)  ".
		" LEFT JOIN (SELECT SUM(DeliveryCost) as TotalDeliveryCost, SUM(PurchasePriceRM) as TotalPurchasePriceRM, JobPurchase.JobID FROM (select (DutyTax + FreightCost) as DeliveryCost, (PurchasePrice * PurchasePriceExchangeRate) as PurchasePriceRM, JobPurchase.JobID FROM JobPurchase, JobPurchaseDelivery WHERE JobPurchaseDelivery.JobPurchaseID=JobPurchase.ID) as JobPurchase GROUP BY JobPurchase.JobID) as JobPurchase ON (JobPurchase.JobID=JobSales.JobID) ".
		" WHERE Job.ID=JobSales.JobID ";
		
		if ($searchString)
			$sqlAll .= $searchString;
		$sql .= $sqlAll." order by $sql_orderby $sql_limit";

		$sqlExport = $sqlAll." order by $sql_orderby ";
		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql), $sqlExport);
    }


    public function getJobsTotalAmount($searchString = null){

        $sqlAll = "SELECT ".
            /*0*/"sum(JobSales.TotalSalesPriceRM), ".
            /*1*/"sum((ifnull((JobSales.TotalSalesPriceRM), 0) - ifnull(JobPurchase.TotalPurchasePriceRM, 0) - ifnull(JobClaims.TotalClaimsRM, 0) - ifnull(JobPurchase.TotalDeliveryCost,0))) as ProjectMarginRM, ".
            /*2*/"sum((ifnull(JobPayments.PaymentAmountRM, 0) - ifnull(JobClaims.TotalClaimsRM, 0) - ifnull(JobPurchase.TotalDeliveryCost,0))) as MarginRM, ".
            /*3*/"sum(ifnull(JobPurchase.TotalPurchasePriceRM, 0) + ifnull(JobClaims.TotalClaimsRM, 0) + ifnull(JobPurchase.TotalDeliveryCost,0)) as TotalCostRM ".
            " FROM Job ".
            " LEFT JOIN Customers ON (Job.CustomerID=Customers.ID) " .
            " LEFT JOIN (SELECT GROUP_CONCAT(IF(SalesCurrency='RM', '&nbsp;', CONCAT(SalesCurrency, ' ', SalesPrice)) separator '<BR>') as SalesPrice, GROUP_CONCAT(CONCAT(SalesPrice * SalesPriceExchangeRate) separator '<BR>') as SalesPriceRM, ".
            " GROUP_CONCAT(SalesPerson separator '<BR>') as SalesPerson, GROUP_CONCAT(SalesPersonGroupID separator ',') as SalesPersonGroupID, ".
            " JobID FROM (SELECT JobSales.*, ACLUsers.Name as SalesPerson, ACLUsers.ID as SalesPersonGroupID FROM JobSales LEFT JOIN ACLUsers ON (ACLUsers.ID=JobSales.SalesPersonID)) as JobSales GROUP BY JobID) as JobSalesData ON (JobSalesData.JobID=Job.ID) ".

            " LEFT JOIN (SELECT SUM(PaymentAmountRM) as PaymentAmountRM, JobID FROM (SELECT IF(PaymentReceive, PaymentCurrencyExchangeRate * PaymentAmount, PaymentCurrencyExchangeRate * PaymentAmount * -1) as PaymentAmountRM, JobID FROM JobPayments) as JobPayments GROUP BY JobID) as JobPayments ON (JobPayments.JobID = Job.ID) ".
            " LEFT JOIN (SELECT SUM(ClaimCurrencyExchangeRate * ClaimAmount) as TotalClaimsRM, JobID FROM JobClaims GROUP BY JobID) as JobClaims ON (JobClaims.JobID=Job.ID)  ".
            " LEFT JOIN (SELECT SUM(DeliveryCost) as TotalDeliveryCost, SUM(PurchasePrice * PurchasePriceExchangeRate) as TotalPurchasePriceRM, JobPurchase.JobID FROM (select sum(DutyTax + FreightCost) as DeliveryCost, PurchasePrice, PurchasePriceExchangeRate, JobPurchase.JobID FROM JobPurchase LEFT JOIN JobPurchaseDelivery ON  (JobPurchaseDelivery.JobPurchaseID=JobPurchase.ID) GROUP BY JobPurchase.ID) as JobPurchase GROUP BY JobPurchase.JobID) as JobPurchase ON (JobPurchase.JobID=Job.ID) ".
            " LEFT JOIN (SELECT SUM(SalesPriceRM) as TotalSalesPriceRM, JobSales.JobID FROM (select (SalesPrice * SalesPriceExchangeRate) as SalesPriceRM, JobSales.JobID FROM JobSales) as JobSales GROUP BY JobSales.JobID) as JobSales ON (JobSales.JobID=Job.ID), ".
            " (SELECT ID, IF (Job.Closed, 3, IF(Job.Completed,2, IF(Job.Cancelled, 1, 0))) as Completed FROM Job) as JobSort ".
            " WHERE JobSort.ID=Job.ID ";


        if ($searchString)
            $sqlAll .= $searchString;

        return $this->_db->fetchRow($sqlAll);
    }
 
	public function getJobs($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
    {
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;

		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT Job.ID, Job.JobNo, Job.CustomerName, Job.Items, Job.JobType, Job.Completed, Job.CompletedDate, ".
			/*7*/"(ifnull(JobPayments.PaymentAmountRM, 0) - ifnull(JobClaims.TotalClaimsRM, 0) - ifnull(JobPurchase.TotalDeliveryCost,0)) as MarginRM,  ".
			/*8*/"(ifnull((JobSales.TotalSalesPriceRM), 0) - ifnull(JobPurchase.TotalPurchasePriceRM, 0) - ifnull(JobClaims.TotalClaimsRM, 0) - ifnull(JobPurchase.TotalDeliveryCost,0)) as ProjectMarginRM, ".
			/*9*/"Job.CustomerPOReceivedDate, JobSales.TotalSalesPriceRM, JobPurchase.TotalPurchasePriceRM, JobClaims.TotalClaimsRM,  JobPurchase.TotalDeliveryCost, Job.Cancelled, Job.PrincipleName, ".
			/*16*/"JobSalesData.SalesPrice,JobSalesData.SalesPriceRM,JobSalesData.SalesPerson, Job.Closed, Job.ClosedDate, Customers.PaymentTerms, Customers.CreditLimit, ".
            /*23*/"JobSalesData.SalesPersonGroupID, (ifnull(JobPurchase.TotalPurchasePriceRM, 0) + ifnull(JobClaims.TotalClaimsRM,0) + ifnull(JobPurchase.TotalDeliveryCost,0)) as TotalCostRM, Job.InitialGrossMargin".
				" FROM Job ".
				" LEFT JOIN Customers ON (Job.CustomerID=Customers.ID) " .
				" LEFT JOIN (SELECT GROUP_CONCAT(IF(SalesCurrency='RM', '&nbsp;', CONCAT(SalesCurrency, ' ', SalesPrice)) separator '<BR>') as SalesPrice, GROUP_CONCAT(CONCAT(SalesPrice * SalesPriceExchangeRate) separator '<BR>') as SalesPriceRM, ".
				" GROUP_CONCAT(SalesPerson separator '<BR>') as SalesPerson, GROUP_CONCAT(SalesPersonGroupID separator ',') as SalesPersonGroupID, ".
				" JobID FROM (SELECT JobSales.*, ACLUsers.Name as SalesPerson, ACLUsers.ID as SalesPersonGroupID FROM JobSales LEFT JOIN ACLUsers ON (ACLUsers.ID=JobSales.SalesPersonID)) as JobSales GROUP BY JobID) as JobSalesData ON (JobSalesData.JobID=Job.ID) ".
		
				" LEFT JOIN (SELECT SUM(PaymentAmountRM) as PaymentAmountRM, JobID FROM (SELECT IF(PaymentReceive, PaymentCurrencyExchangeRate * PaymentAmount, PaymentCurrencyExchangeRate * PaymentAmount * -1) as PaymentAmountRM, JobID FROM JobPayments) as JobPayments GROUP BY JobID) as JobPayments ON (JobPayments.JobID = Job.ID) ".
				" LEFT JOIN (SELECT SUM(ClaimCurrencyExchangeRate * ClaimAmount) as TotalClaimsRM, JobID FROM JobClaims GROUP BY JobID) as JobClaims ON (JobClaims.JobID=Job.ID)  ".
				" LEFT JOIN (SELECT SUM(DeliveryCost) as TotalDeliveryCost, SUM(PurchasePrice * PurchasePriceExchangeRate) as TotalPurchasePriceRM, JobPurchase.JobID FROM (select sum(DutyTax + FreightCost) as DeliveryCost, PurchasePrice, PurchasePriceExchangeRate, JobPurchase.JobID FROM JobPurchase LEFT JOIN JobPurchaseDelivery ON  (JobPurchaseDelivery.JobPurchaseID=JobPurchase.ID) GROUP BY JobPurchase.ID) as JobPurchase GROUP BY JobPurchase.JobID) as JobPurchase ON (JobPurchase.JobID=Job.ID) ".
				" LEFT JOIN (SELECT SUM(SalesPriceRM) as TotalSalesPriceRM, JobSales.JobID FROM (select (SalesPrice * SalesPriceExchangeRate) as SalesPriceRM, JobSales.JobID FROM JobSales) as JobSales GROUP BY JobSales.JobID) as JobSales ON (JobSales.JobID=Job.ID), ".
				" (SELECT ID, IF (Job.Closed, 3, IF(Job.Completed,2, IF(Job.Cancelled, 1, 0))) as Completed FROM Job) as JobSort ".
				" WHERE JobSort.ID=Job.ID ";
		if ($searchString)
			$sqlAll .= $searchString;
		$sql .= $sqlAll." order by $sql_orderby $sql_limit";

		$sqlExport = $sqlAll." order by $sql_orderby ";
		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql), $sqlExport);
    }
	
    public function getLog($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
    {
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT ACLUsers.Name, SYSLog.username, SYSLog.role, SYSLog.logtime, SYSLog.zendmodule, SYSLog.zendcontroller, SYSLog.zendaction, SYSLog.postdata, SYSLog.getdata, SYSLog.ID, SYSLog.IP
			FROM SYSLog LEFT JOIN ACLUsers on (ACLUsers.Username=SYSLog.username) WHERE 1=1 ";		
		if ($searchString)
			$sqlAll .= $searchString;
		

		$sql .= $sqlAll." order by ".$sql_orderby." ". $sql_limit;
	//	$sqlCount = "SELECT  COUNT(*) as Num FROM SYSLog LEFT JOIN ACLUsers on (ACLUsers.Username=SYSLog.username) WHERE 1=1 ";		
	//	$arrCount = $this->_db->fetchRow($sqlCount);
		
		
		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql));
    }



    public function getTempCost($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
    {
        if ($showPage	< 0 || $showPage == "") $showPage = 1;

        $sql_orderby =  is_null($orderby) ? "ID" : $orderby;
        $sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
        $count = $showPage -1;

        $sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";

        $sqlAll = "SELECT Job.ID, Job.JobNo, Job.CustomerName, Job.Items, Job.JobType, Job.Completed, Job.CompletedDate, ".
            /*7*/"JobPurchase.LatestReceivedDate, JobPurchase.TotalDutyTax, JobPurchase.TotalFreightCost, JobPurchase.TotalPurchasePriceRM, ".
            /*11*/"(IF(JobPurchase.TotalDutyTax IS NULL,0,JobPurchase.TotalDutyTax)+IF(JobPurchase.TotalFreightCost IS NULL,0,JobPurchase.TotalFreightCost)+JobPurchase.TotalPurchasePriceRM) as TotalPurchase, ".
            /*12*/"JobSales.TotalSalesPriceRM, JobPurchase.PartialDelivery, ".
            /*14*/"(IF (JobSales.PartialDeliveryAmount IS NULL, 0, JobSales.PartialDeliveryAmount) + IF (JobSalesDelivered.TotalSalesPriceRM IS NULL, 0, JobSalesDelivered.TotalSalesPriceRM)) AS TotalSalesDelivered, ".
            /*15*/"JobSalesPure.TotalSalesPriceRMAll, ".
            /*16*/"(
                       (1 - (IF(JobSales.PartialDeliveryAmount IS NULL, 0, JobSales.PartialDeliveryAmount) + IF (JobSalesDelivered.TotalSalesPriceRM IS NULL, 0, JobSalesDelivered.TotalSalesPriceRM)) / JobSalesPure.TotalSalesPriceRMAll) * 

                                (IF(JobPurchase.TotalDutyTax IS NULL,0,JobPurchase.TotalDutyTax)+IF(JobPurchase.TotalFreightCost IS NULL,0,JobPurchase.TotalFreightCost)+JobPurchase.TotalPurchasePriceRM)

                                    ) as TemporaryCost
                
                
                FROM Job LEFT JOIN
            (
                SELECT Count(*) as TotalPurchase, Job.ID as JobID, MAX(DeliveryReceivedDate) as LatestReceivedDate, Sum(DutyTax) as TotalDutyTax, Sum(FreightCost) as TotalFreightCost, 
                Sum(JobPurchaseDelivery.PurchasePriceRM) as TotalPurchasePriceRM, Sum(JobPurchaseDelivery.PartialDeliveryAmount) as PartialDeliveryAmount, PartialDelivery FROM Job LEFT JOIN
                (SELECT IF(JobPurchase.PartialDelivery = 1, sum(PartialDeliveryAmount),(JobPurchase.PurchasePrice * JobPurchase.PurchasePriceExchangeRate)) as PurchasePriceRM, JobPurchaseDelivery.ID, JobPurchaseDelivery.JobID, sum(DutyTax) as DutyTax,sum(FreightCost) as FreightCost,sum(PartialDeliveryAmount) as PartialDeliveryAmount, MAX(DeliveryReceivedDate) as DeliveryReceivedDate,JobPurchase.PartialDelivery FROM JobPurchase, JobPurchaseDelivery WHERE JobPurchase.ID=JobPurchaseDelivery.JobPurchaseID AND DeliveryReceivedDate IS NOT NULL GROUP BY JobPurchaseDelivery.JobPurchaseID) as JobPurchaseDelivery
                ON (JobPurchaseDelivery.JobID=Job.ID AND DeliveryReceivedDate IS NOT NULL)
                WHERE JobPurchaseDelivery.ID IS NOT NULL AND (Job.JobType!='R' AND Job.JobType!='L') GROUP BY Job.ID
            ) as JobPurchase ON Job.ID=JobPurchase.JobID LEFT JOIN
            (
                SELECT Count(*) as TotalSales, JobSales.ID as SalesID, Job.ID as JobID, Sum(JobSales.TotalSalesPriceRM) as TotalSalesPriceRM, JobSales.SalesReadyDate, sum(JobSales.PartialDeliveryAmount) as PartialDeliveryAmount FROM Job,  
                (
                    SELECT JobSales.ID, JobSales.PartialDelivery, JobSales.JobID,IF(JobSales.PartialDelivery = 1,JobSalesDelivery.PartialDeliveryAmount,(JobSales.SalesPrice * JobSales.SalesPriceExchangeRate)) AS TotalSalesPriceRM, JobSales.SalesReadyDate,PartialDeliveryAmount FROM JobSales LEFT JOIN
                    (
                        SELECT sum(PartialDeliveryAmount) as PartialDeliveryAmount, JobSalesID FROM JobSalesDelivery GROUP BY JobSalesID
                    ) JobSalesDelivery ON (JobSalesDelivery.JobSalesID=JobSales.ID) WHERE JobSales.SalesReadyDate IS NULL
                    
                ) as JobSales WHERE (JobSales.JobID=Job.ID AND Job.JobType!='R' AND Job.JobType!='L'  AND (JobSales.SalesReadyDate IS NULL AND JobSales.ID IS NOT NULL)) GROUP BY Job.ID ORDER BY JobID DESC
            ) as JobSales ON Job.ID=JobSales.JobID LEFT JOIN
            (
                SELECT Count(*) as TotalSales, JobSales.ID as SalesID, Job.ID as JobID, Sum(JobSales.TotalSalesPriceRM) as TotalSalesPriceRM, JobSales.SalesReadyDate, sum(JobSales.PartialDeliveryAmount) as PartialDeliveryAmount FROM Job,  
                (
                    SELECT JobSales.ID, JobSales.PartialDelivery, JobSales.JobID,IF(JobSales.PartialDelivery = 1,JobSalesDelivery.PartialDeliveryAmount,(JobSales.SalesPrice * JobSales.SalesPriceExchangeRate)) AS TotalSalesPriceRM, JobSales.SalesReadyDate,PartialDeliveryAmount FROM JobSales LEFT JOIN
                    (
                        SELECT sum(PartialDeliveryAmount) as PartialDeliveryAmount, JobSalesID FROM JobSalesDelivery GROUP BY JobSalesID
                    ) JobSalesDelivery ON (JobSalesDelivery.JobSalesID=JobSales.ID) WHERE JobSales.SalesReadyDate IS NOT NULL
                    
                ) as JobSales WHERE (JobSales.JobID=Job.ID AND Job.JobType!='R' AND Job.JobType!='L'  AND (JobSales.SalesReadyDate IS NOT NULL AND JobSales.ID IS NOT NULL)) GROUP BY Job.ID ORDER BY JobID DESC
            ) as JobSalesDelivered ON Job.ID=JobSalesDelivered.JobID LEFT JOIN (
                SELECT SUM(JobSales.SalesPrice * JobSales.SalesPriceExchangeRate) as TotalSalesPriceRMAll, JobSales.JobID From JobSales GROUP BY JobSales.JobID
            )JobSalesPure ON (Job.ID=JobSalesPure.JobID)
            WHERE JobPurchase.LatestReceivedDate IS NOT NULL AND JobSales.SalesID IS NOT NULL ";


        if ($searchString)
            $sqlAll .= $searchString;
        $sql .= $sqlAll." order by $sql_orderby $sql_limit";

        $sqlExport = $sqlAll." order by $sql_orderby ";
        return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql), $sqlExport);
    }


    public function getTempCostAmount($searchString = null){

        $sqlAll = "SELECT sum(JobPurchase.TotalDutyTax) as TotalDutyTax, sum(JobPurchase.TotalFreightCost) as TotalFreightCost, sum(JobPurchase.TotalPurchasePriceRM) as TotalPurchasePriceRM, ".
            /*3*/"sum((IF(JobPurchase.TotalDutyTax IS NULL,0,JobPurchase.TotalDutyTax)+IF(JobPurchase.TotalFreightCost IS NULL,0,JobPurchase.TotalFreightCost)+JobPurchase.TotalPurchasePriceRM)) as TotalPurchase, ".
            /*4*/"sum(JobSales.TotalSalesPriceRM) as TotalSalesPriceRMEx, sum(JobSales.PartialDeliveryAmount) as PartialDeliveryAmount, ".
            /*5*/"sum(JobSalesDelivered.TotalSalesPriceRM) as TotalSalesPriceRM
            
            FROM Job LEFT JOIN
            (
                SELECT Count(*) as TotalPurchase, Job.ID as JobID, MAX(DeliveryReceivedDate) as LatestReceivedDate, Sum(DutyTax) as TotalDutyTax, Sum(FreightCost) as TotalFreightCost, 
                Sum(JobPurchaseDelivery.PurchasePriceRM) TotalPurchasePriceRM, Sum(JobPurchaseDelivery.PartialDeliveryAmount) as PartialDeliveryAmount, PartialDelivery FROM Job LEFT JOIN
                (SELECT (IF(JobPurchase.PartialDelivery = 1, sum(PartialDeliveryAmount),(JobPurchase.PurchasePrice * JobPurchase.PurchasePriceExchangeRate))) as PurchasePriceRM, JobPurchaseDelivery.ID, JobPurchaseDelivery.JobID, sum(DutyTax) as DutyTax,sum(FreightCost) as FreightCost,sum(PartialDeliveryAmount) as PartialDeliveryAmount, MAX(DeliveryReceivedDate) as DeliveryReceivedDate,JobPurchase.PartialDelivery FROM JobPurchase, JobPurchaseDelivery WHERE JobPurchase.ID=JobPurchaseDelivery.JobPurchaseID AND DeliveryReceivedDate IS NOT NULL GROUP BY JobPurchaseDelivery.JobPurchaseID) as JobPurchaseDelivery
                ON (JobPurchaseDelivery.JobID=Job.ID AND DeliveryReceivedDate IS NOT NULL)
                WHERE JobPurchaseDelivery.ID IS NOT NULL AND (Job.JobType!='R' AND Job.JobType!='L') GROUP BY Job.ID
            ) as JobPurchase ON Job.ID=JobPurchase.JobID LEFT JOIN
            (
                SELECT Count(*) as TotalSales, JobSales.ID as SalesID, Job.ID as JobID, Sum(JobSales.TotalSalesPriceRM) as TotalSalesPriceRM, JobSales.SalesReadyDate, sum(JobSales.PartialDeliveryAmount) as PartialDeliveryAmount FROM Job,  
                (
                    SELECT JobSales.ID, JobSales.PartialDelivery, JobSales.JobID,IF(JobSales.PartialDelivery = 1,JobSalesDelivery.PartialDeliveryAmount,(JobSales.SalesPrice * JobSales.SalesPriceExchangeRate)) AS TotalSalesPriceRM, JobSales.SalesReadyDate,PartialDeliveryAmount FROM JobSales LEFT JOIN
                    (
                        SELECT sum(PartialDeliveryAmount) as PartialDeliveryAmount, JobSalesID FROM JobSalesDelivery GROUP BY JobSalesID
                    ) JobSalesDelivery ON (JobSalesDelivery.JobSalesID=JobSales.ID) WHERE JobSales.SalesReadyDate IS NULL
                    
                ) as JobSales WHERE (JobSales.JobID=Job.ID AND Job.JobType!='R' AND Job.JobType!='L'  AND (JobSales.SalesReadyDate IS NULL AND JobSales.ID IS NOT NULL)) GROUP BY Job.ID ORDER BY JobID DESC
            ) as JobSales ON Job.ID=JobSales.JobID LEFT JOIN
            (
                SELECT Count(*) as TotalSales, JobSales.ID as SalesID, Job.ID as JobID, Sum(JobSales.TotalSalesPriceRM) as TotalSalesPriceRM, JobSales.SalesReadyDate, sum(JobSales.PartialDeliveryAmount) as PartialDeliveryAmount FROM Job,  
                (
                    SELECT JobSales.ID, JobSales.PartialDelivery, JobSales.JobID,IF(JobSales.PartialDelivery = 1,JobSalesDelivery.PartialDeliveryAmount,(JobSales.SalesPrice * JobSales.SalesPriceExchangeRate)) AS TotalSalesPriceRM, JobSales.SalesReadyDate,PartialDeliveryAmount FROM JobSales LEFT JOIN
                    (
                        SELECT sum(PartialDeliveryAmount) as PartialDeliveryAmount, JobSalesID FROM JobSalesDelivery GROUP BY JobSalesID
                    ) JobSalesDelivery ON (JobSalesDelivery.JobSalesID=JobSales.ID) WHERE JobSales.SalesReadyDate IS NOT NULL
                    
                ) as JobSales WHERE (JobSales.JobID=Job.ID AND Job.JobType!='R' AND Job.JobType!='L'  AND (JobSales.SalesReadyDate IS NOT NULL AND JobSales.ID IS NOT NULL)) GROUP BY Job.ID ORDER BY JobID DESC
            ) as JobSalesDelivered ON Job.ID=JobSalesDelivered.JobID LEFT JOIN (
                SELECT SUM(JobSales.SalesPrice * JobSales.SalesPriceExchangeRate) as TotalSalesPriceRMAll, JobSales.JobID From JobSales GROUP BY JobSales.JobID
            )JobSalesPure ON (Job.ID=JobSalesPure.JobID)
            WHERE JobPurchase.LatestReceivedDate IS NOT NULL AND JobSales.SalesID IS NOT NULL ";




        if ($searchString)
            $sqlAll .= $searchString;

        return $this->_db->fetchRow($sqlAll);
    }
 
}
?>