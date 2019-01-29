<?php

class Admin_SystemController extends Venz_Zend_Controller_Action
{

    public function init()
    {
        $actionName = $this->getRequest()->getActionName();
		switch ($actionName){
			default: parent::init("private");
		}		
		
    }
	
	public function importJobAction()
    {
		exit();
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$row = 0;
		$arrInsert = array();$arrInsertSales = array();$arrInsertPurchase = array();$arrInsertDelivery = array();
		if (($handle = fopen("./import/Job16_20160613.csv", "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				//echo "<p> $num fields in line $row: <br /></p><BR>";
				if ($row > 0)
				{
					for ($c=0; $c < $num; $c++) {
						//echo $data[$c] . " - ";
						if ($c == 0)
							$arrInsert[$row]['JobType'] = $data[$c];
						if ($c == 1)
							$arrInsert[$row]['JobNo'] = $arrInsert[$row]['JobType'].$data[$c];
						if ($c == 2)
							$arrInsert[$row]['CustomerPOReceivedDate'] = strtotime($data[$c]) ? Date("Y-m-d", strtotime($data[$c])) : "";
						if ($c == 3)
						{
							$CustomerName = "";
							if (preg_match('#\((.*?)\)#', $data[$c]) && (strpos($data[$c], "(M)") === FALSE) && (strpos($data[$c], "(Malaysia)") === FALSE))
							{
								$arrData = explode(" (", $data[$c]);
								preg_match('#\((.*?)\)#', $data[$c], $match);
								$arrInsert[$row]['CustomerName'] = $arrData[0];
								$CustomerName = $arrData[0];
								$arrInsert[$row]['PrincipleName'] = $match[1];
								
							}
							else
							{
								$arrInsert[$row]['CustomerName'] = $data[$c];
								$CustomerName =  $data[$c];
							}

							$arrCustomer = $db->fetchRow("SELECT * FROM Customers WHERE Name='".mysql_real_escape_string($CustomerName)."'");
							
							if ($arrCustomer)
							{
								$arrInsert[$row]['CustomerID'] = $arrCustomer['ID'];
							}else
							{
								$db->insert("Customers", array("Name"=>$CustomerName));
								$arrInsert[$row]['CustomerID'] = $db->lastInsertId();
							}
							
							
						}
						
						if ($c == 4){} // EOG & STSB PO
						if ($c == 5){} // Customer PO
						if ($c == 6){
							$arrInsert[$row]['Items'] = $data[$c];
						}
						$arrInsert[$row]['CreatedOn'] = new Zend_Db_Expr('now()');						
						$arrInsert[$row]['CreatedBy'] = $this->userInfo->ID;	
						
						$arrInsertSales[$row]['CustomerPOReceivedDate'] = $arrInsert[$row]['CustomerPOReceivedDate'];
						if ($c == 7){
							if ($data[$c])
							{
								$arrInsertSales[$row]['SalesCurrency'] =  $data[$c];
								$Currency =  trim($data[$c]);
								if ($Currency == "RM")
									$arrInsertSales[$row]['SalesPriceExchangeRate']  = 1.00;
								else
									$arrInsertSales[$row]['SalesPriceExchangeRate']  = 0;
								
								$arrCurrency = $db->fetchRow("SELECT * FROM Currency WHERE Name='".$Currency."'");
								if ($arrCurrency)
								{
									$arrInsertSales[$row]['SalesCurrencyID'] = $arrCurrency['ID'];
								}else
								{
									$db->insert("Currency", array("Name"=>$data[$c], "Code"=>$data[$c]));
									$arrInsertSales[$row]['SalesCurrencyID'] = $db->lastInsertId();
								}
								
							}
						}
						
						if ($c == 8){
							$arrInsertSales[$row]['SalesPrice'] =  str_replace(",", "", $data[$c]);
						}
												
						if ($c == 9){
							$arrInsertSales[$row]['SalesTerms'] =  $data[$c];
						}
						
						if ($c == 10){ $arrInsertSales[$row]['SalesInspReportNo'] =  $data[$c];} // Inspection Report NO
						if ($c == 11){ $arrInsertSales[$row]['SalesOrderAckNo'] =  $data[$c];} // Sales ORder Ack
						if ($c == 12){ $arrInsertSales[$row]['SalesExpDate'] = strtotime($data[$c]) ? Date("Y-m-d", strtotime($data[$c])) : "";}
						if ($c == 13){ $arrInsertSales[$row]['SalesReadyDate'] = strtotime($data[$c]) ? Date("Y-m-d", strtotime($data[$c])) : "";}
						if ($c == 14){ $arrInsertSales[$row]['SalesInvoiceDate'] = strtotime($data[$c]) ? Date("Y-m-d", strtotime($data[$c])) : "";}
						if ($c == 15){ $arrInsertSales[$row]['SalesInvoiceNo'] = $data[$c];} 
						if ($c == 16){ $arrInsertSales[$row]['SalesDO'] = $data[$c];} 
						if ($c == 17){ $arrInsertSales[$row]['EOGSTSBDO'] = $data[$c];} 
						if ($c == 18){ $arrInsertSales[$row]['ServiceReportNo'] = $data[$c];} 
						if ($c == 19){ 
							$username = $data[$c];
							$arrUserData = $db->fetchRow("SELECT * FROM ACLUsers WHERE username='".$username."'");
							$arrInsertSales[$row]['SalesPersonID'] = $arrUserData['ID'];
						
						} // Sales Person 
						$arrInsertSales[$row]['CreatedOn'] = new Zend_Db_Expr('now()');						
						$arrInsertSales[$row]['CreatedBy'] = $this->userInfo->ID;	
						
						
						if ($c == 20){ $arrInsertPurchase[$row]['PODate'] = strtotime($data[$c]) ? Date("Y-m-d", strtotime($data[$c])) : "";}
						if ($c == 21){ $arrInsertPurchase[$row]['POFaxedDate'] = strtotime($data[$c]) ? Date("Y-m-d", strtotime($data[$c])) : "";}
						if ($c == 22){ $arrInsertPurchase[$row]['PONo'] = strtotime($data[$c]) ? Date("Y-m-d", strtotime($data[$c])) : "";}
						
						if ($c == 23)
						{
							if ($data[$c])
							{
								$arrInsertPurchase[$row]['SupplierName'] = $data[$c];
								$SupplierName =  $data[$c];
							
								$arrSupplier = $db->fetchRow("SELECT * FROM Supplier WHERE Name='".mysql_real_escape_string($SupplierName)."'");
								
								if ($arrSupplier)
								{
									$db->update("Supplier", array("Code"=>$data[24]), "ID=".$arrSupplier['ID']);
									$arrInsertPurchase[$row]['SupplierID'] = $arrSupplier['ID'];
								}else
								{
									$db->insert("Supplier", array("Name"=>$SupplierName, "Code"=>$data[24]));
									$arrInsertPurchase[$row]['SupplierID'] = $db->lastInsertId();
								}
								
							}
							
							
						}
						
						if ($c == 25){
							if ($data[$c])
							{
								$arrInsertPurchase[$row]['PurchaseCurrency'] =  $data[$c];
								$Currency =  trim($data[$c]);
								if ($Currency == "RM")
									$arrInsertPurchase[$row]['PurchasePriceExchangeRate']  = 1.00;
								else
									$arrInsertPurchase[$row]['PurchasePriceExchangeRate']  = 0;
								
								$arrCurrency = $db->fetchRow("SELECT * FROM Currency WHERE Name='".$Currency."'");
								if ($arrCurrency)
								{
									$arrInsertPurchase[$row]['PurchaseCurrencyID'] = $arrCurrency['ID'];
								}else
								{
									$db->insert("Currency", array("Name"=>$data[$c], "Code"=>$data[$c]));
									$arrInsertPurchase[$row]['PurchaseCurrencyID'] = $db->lastInsertId();
								}
								
							}
						}
						
						if ($c == 26){
							$arrInsertPurchase[$row]['PurchasePrice'] =  str_replace(",", "", $data[$c]);
						}
						
						if ($c == 27){
							$arrInsertPurchase[$row]['PurchaseTerms'] =  $data[$c];
						}
						
						if ($c == 28){
							$arrInsertPurchase[$row]['PurchaseAckNO'] =  $data[$c];
						}

						if ($c == 29){
							$arrInsertPurchase[$row]['PurchaseShippingDate'] =  strtotime($data[$c]) ? Date("Y-m-d", strtotime($data[$c])) : "";
						}

						if ($c == 30){
							$arrInsertPurchase[$row]['PurchaseShippingActualDate'] =  strtotime($data[$c]) ? Date("Y-m-d", strtotime($data[$c])) : "";
						}
						
						if ($c == 32){
							$arrInsertPurchase[$row]['PurchaseInvoiceNo'] = $data[$c];
						}
						$arrInsertPurchase[$row]['CreatedOn'] = new Zend_Db_Expr('now()');						
						$arrInsertPurchase[$row]['CreatedBy'] = $this->userInfo->ID;	
						
						if ($c == 33){
							$arrInsertDelivery[$row]['DeliveryAWB'] = $data[$c];
						}
						if ($c == 34){
							$arrInsertDelivery[$row]['DeliveryReceivedDate'] = strtotime($data[$c]) ? Date("Y-m-d", strtotime($data[$c])) : "";
						}
						
						if ($c == 35){
							$arrInsertDelivery[$row]['DutyTax'] =  str_replace(",", "", $data[$c]);
						}
						
						if ($c == 36){
							$arrInsertDelivery[$row]['FreightCost'] =  str_replace(",", "", $data[$c]);
						}
						if ($c == 37){
							$arrInsertDelivery[$row]['Remarks'] =  str_replace(",", "", $data[$c]);
						}
						$arrInsertDelivery[$row]['CreatedOn'] = new Zend_Db_Expr('now()');						
						$arrInsertDelivery[$row]['CreatedBy'] = $this->userInfo->ID;	
						
						
					}
				}
				$row++;
			}
			fclose($handle);
		}
		
		foreach ($arrInsert as $index => $arrData)
		{
			print "<HR>".$arrData['JobNo'];
			$arrJob = $db->fetchRow("SELECT * FROM Job WHERE JobNo='".$arrData['JobNo']."'");
			if (!$arrJob)
			{
				$db->insert("Job", $arrData);
				$JobID = $db->lastInsertId();
			}else
				$JobID = $arrJob['ID'];
				
			$arrSales = $arrInsertSales[$index];
			$arrSales['JobID'] = $JobID;
			$db->insert("JobSales", $arrSales);
			
			$arrPurchase = $arrInsertPurchase[$index];
			$gotPurchase = false;
			foreach ($arrPurchase as $index2 => $val)
			{
				if ($index2 != "CreatedOn" && $index2 != "CreatedBy")
				{
					if ($val)
					{
						$gotPurchase = true;
						
					}
					
				}
			}
			
			if ($gotPurchase)
			{
				$arrPurchase['JobID'] = $JobID;
				$db->insert("JobPurchase", $arrPurchase);
				$JobPurchaseID = $db->lastInsertId();
				
				$arrDelivery = $arrInsertDelivery[$index];
				$arrDelivery['JobPurchaseID'] = $JobPurchaseID;
				$db->insert("JobPurchaseDelivery", $arrDelivery);
				
			}
			
			
		}
		
		exit();
		
    }

    public function indexAction()
    {
	
		
    }
	
	public function ajaxCalendarAction()
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		
		$arrPublicHolidayAll = $db->fetchAll("SELECT * FROM PublicHoliday Order by PHDate Desc");
		$arrReturn = array();
		foreach ($arrPublicHolidayAll as $arrPublicHoliday)
		{
			$arrReturn[] = array("id"=>$arrPublicHoliday['ID'], "title"=>$arrPublicHoliday['PHDescription'],"start"=>$arrPublicHoliday['PHDate']);
			
		}
		
		echo json_encode($arrReturn);
		exit();
	}
	
	public function deleteCalendarAction()
    {
		$Request = $this->getRequest();	
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		$PublicHolidayID = $Request->getParam('PublicHolidayID');
		$db->delete("PublicHoliday", "ID=".$PublicHolidayID);
		
		$arrPublicHolidayAll = $db->fetchAll("SELECT * FROM PublicHoliday Order by PHDate Desc");
		$returnData = "";
		foreach ($arrPublicHolidayAll as $arrPublicHoliday)
		{
			$PHDate = $dispFormat->format_date_db_to_simple($arrPublicHoliday['PHDate']);
			$returnData .=<<<END
			<div class="uk-form-row">
				<div class="uk-grid">
					<div class="uk-width-medium-1-6">$PHDate</div>
					<div class="uk-width-medium-3-6">$arrPublicHoliday[PHDescription]</div>
					<div class="uk-width-medium-2-6">
						<input type=button name='Edit' id='Edit' PublicHolidayID=$arrPublicHoliday[ID] value='Edit'>
						<input type=button name='Delete' id='Delete' PublicHolidayID=$arrPublicHoliday[ID] value='Delete'>
					</div>
				</div>
			</div>
END;
			
		}
		
		echo $returnData;
		exit();
		
    }
	
	public function saveCalendarAction()
    {
		$Request = $this->getRequest();	
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		$PHDate = $Request->getParam('PHDate');
		$PHDescription = $Request->getParam('PHDescription');
		$PublicHolidayID = $Request->getParam('PublicHolidayID');
		if ($PHDate && $PHDescription)
		{
			if ($PublicHolidayID)
			{
				$arrUpdate = array("PHDate"=>$dispFormat->format_date_simple_to_db($PHDate), "PHDescription"=>$PHDescription);	
				$db->UPdate("PublicHoliday", $arrUpdate, "ID=".$PublicHolidayID);
				
			}else
			{
				$arrInsert = array("PHDate"=>$dispFormat->format_date_simple_to_db($PHDate), "PHDescription"=>$PHDescription);	
				$db->Insert("PublicHoliday", $arrInsert);
				$PublicHolidayID = $db->lastInsertId();
				
			}
		}
		
		$arrPublicHolidayAll = $db->fetchAll("SELECT * FROM PublicHoliday Order by PHDate Desc");
		$returnData = "";
		foreach ($arrPublicHolidayAll as $arrPublicHoliday)
		{
			$PHDate = $dispFormat->format_date_db_to_simple($arrPublicHoliday['PHDate']);
			$returnData .=<<<END
			<div class="uk-form-row">
				<div class="uk-grid">
					<div class="uk-width-medium-1-6">$PHDate</div>
					<div class="uk-width-medium-3-6">$arrPublicHoliday[PHDescription]</div>
					<div class="uk-width-medium-2-6">
						<input type=button name='Edit' id='Edit' PublicHolidayID=$arrPublicHoliday[ID] value='Edit'>
						<input type=button name='Delete' id='Delete' PublicHolidayID=$arrPublicHoliday[ID] value='Delete'>
					</div>
				</div>
			</div>
END;
			
		}
		
		echo $returnData;
		exit();
		
    }
	
	public function getCalendarAction()
    {
		$Request = $this->getRequest();	
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		$PublicHolidayID = $Request->getParam('PublicHolidayID');
		$arrPublicHoliday = $db->fetchRow("SELECT * FROM PublicHoliday WHERE ID=".$PublicHolidayID);
		$arrPublicHoliday['PHDate'] = $dispFormat->format_date_db_to_simple($arrPublicHoliday['PHDate']);
		echo json_encode($arrPublicHoliday);
		exit();
    }
	
	public function calendarAction()
    {
		$Request = $this->getRequest();	
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		$arrPublicHolidayAll = $db->fetchAll("SELECT * FROM PublicHoliday Order by PHDate Desc");
		$returnData = "";
		foreach ($arrPublicHolidayAll as $arrPublicHoliday)
		{
			$PHDate = $dispFormat->format_date_db_to_simple($arrPublicHoliday['PHDate']);
			$returnData .=<<<END
			<div class="uk-form-row">
				<div class="uk-grid">
					<div class="uk-width-medium-1-6">$PHDate</div>
					<div class="uk-width-medium-3-6">$arrPublicHoliday[PHDescription]</div>
					<div class="uk-width-medium-2-6">
						<input type=button name='Edit' id='Edit' PublicHolidayID=$arrPublicHoliday[ID] value='Edit'>
						<input type=button name='Delete' id='Delete' PublicHolidayID=$arrPublicHoliday[ID] value='Delete'>
					</div>
				</div>
			</div>
END;
		}
		
		$this->view->PublicHolidayList = $returnData;
    }
	
	public function deleteObjectivesAction()
    {
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$ReportObjectivesID = $Request->getParam('ReportObjectivesID');	
		$db->delete("ReportObjectives", "ID=".$ReportObjectivesID);
	}
	
	public function reportObjectivesAction()
    {
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$type = $db->fetchRow( "SHOW COLUMNS FROM ReportObjectives WHERE Field = 'ObjectiveType'" );
		
	//	print_r($type);
		
		preg_match("/^enum\(\'(.*)\'\)$/", $type['Type'], $matches);
		$enum = explode("','", $matches[1]);
	//	print_r($enum);
	
		$add_deliveryobjective = $Request->getParam('add_deliveryobjective');	
		if ($add_deliveryobjective)
		{
			$deliveryobjective = $Request->getParam('deliveryobjective') ? $Request->getParam('deliveryobjective') : "15";	
			$deliveryobjective_year = $Request->getParam('deliveryobjective_year') ? $Request->getParam('deliveryobjective_year') : "2016";			
			
			$arrInsert = array("ObjectiveType"=>"deliveryobjective","ObjectiveValue"=>$deliveryobjective,"ObjectiveYear"=>$deliveryobjective_year);
			$db->insert("ReportObjectives", $arrInsert);
			
			$this->appMessage->setNotice(1, "New report objectives added.");
			$this->_redirect('/admin/system/report-objectives'); 			
		}
		
		$add_purchaseobjective = $Request->getParam('add_purchaseobjective');	
		if ($add_purchaseobjective)
		{
			$purchaseobjective = $Request->getParam('purchaseobjective') ? $Request->getParam('purchaseobjective') : "15";	
			$purchaseobjective_year = $Request->getParam('purchaseobjective_year') ? $Request->getParam('purchaseobjective_year') : "2016";			
			
			$arrInsert = array("ObjectiveType"=>"purchaseobjective","ObjectiveValue"=>$purchaseobjective,"ObjectiveYear"=>$purchaseobjective_year);
			$db->insert("ReportObjectives", $arrInsert);
			
			$this->appMessage->setNotice(1, "New report objectives added.");
			$this->_redirect('/admin/system/report-objectives'); 			
		}
		
		$add_purchaseobjectivedays = $Request->getParam('add_purchaseobjectivedays');	
		if ($add_purchaseobjectivedays)
		{
			$purchaseobjectivedays = $Request->getParam('purchaseobjectivedays') ? $Request->getParam('purchaseobjectivedays') : "15";	
			$purchaseobjectivedays_year = $Request->getParam('purchaseobjectivedays_year') ? $Request->getParam('purchaseobjectivedays_year') : "2016";			
			
			$arrInsert = array("ObjectiveType"=>"purchaseobjectivedays","ObjectiveValue"=>$purchaseobjectivedays,"ObjectiveYear"=>$purchaseobjectivedays_year);
			$db->insert("ReportObjectives", $arrInsert);
			
			$this->appMessage->setNotice(1, "New report objectives added.");
			$this->_redirect('/admin/system/report-objectives'); 			
		}
		
		$add_latedelivery = $Request->getParam('add_latedelivery');	
		if ($add_latedelivery)
		{
			$latedelivery = $Request->getParam('latedelivery') ? $Request->getParam('latedelivery') : "15";	
			$latedelivery_year = $Request->getParam('latedelivery_year') ? $Request->getParam('latedelivery_year') : "2016";			
			
			$arrInsert = array("ObjectiveType"=>"latedelivery","ObjectiveValue"=>$latedelivery,"ObjectiveYear"=>$latedelivery_year);
			$db->insert("ReportObjectives", $arrInsert);
			
			$this->appMessage->setNotice(1, "New report objectives added.");
			$this->_redirect('/admin/system/report-objectives'); 			
		}
		
		$add_purchaseobjective2 = $Request->getParam('add_purchaseobjective2');	
		if ($add_purchaseobjective2)
		{
			$purchaseobjective2 = $Request->getParam('purchaseobjective2') ? $Request->getParam('purchaseobjective2') : "10";	
			$purchaseobjective2_year = $Request->getParam('purchaseobjective2_year') ? $Request->getParam('purchaseobjective2_year') : "2016";			
			
			$arrInsert = array("ObjectiveType"=>"purchaseobjective2","ObjectiveValue"=>$purchaseobjective2,"ObjectiveYear"=>$purchaseobjective2_year);
			
	//		print_r($arrInsert); exit();
			$db->insert("ReportObjectives", $arrInsert);
			
			$this->appMessage->setNotice(1, "New report objectives added.");
			$this->_redirect('/admin/system/report-objectives'); 			
		}
		
		$add_purchaseobjectivedays2 = $Request->getParam('add_purchaseobjectivedays2');	
		if ($add_purchaseobjectivedays2)
		{
			$purchaseobjectivedays2 = $Request->getParam('purchaseobjectivedays2') ? $Request->getParam('purchaseobjectivedays2') : "14";	
			$purchaseobjectivedays2_year = $Request->getParam('purchaseobjectivedays2_year') ? $Request->getParam('purchaseobjectivedays2_year') : "2016";			
			
			$arrInsert = array("ObjectiveType"=>"purchaseobjectivedays2","ObjectiveValue"=>$purchaseobjectivedays2,"ObjectiveYear"=>$purchaseobjectivedays2_year);
			$db->insert("ReportObjectives", $arrInsert);
			
			$this->appMessage->setNotice(1, "New report objectives added.");
			$this->_redirect('/admin/system/report-objectives'); 			
		}
		
/*		
		$add_drawingapproval = $Request->getParam('add_drawingapproval');	
		if ($add_drawingapproval)
		{
			$drawingapproval = $Request->getParam('drawingapproval') ? $Request->getParam('drawingapproval') : "15";	
			$drawingapproval_year = $Request->getParam('drawingapproval_year') ? $Request->getParam('drawingapproval_year') : "2016";			
			
			$arrInsert = array("ObjectiveType"=>"drawingapproval","ObjectiveValue"=>$drawingapproval,"ObjectiveYear"=>$drawingapproval_year);
			$db->insert("ReportObjectives", $arrInsert);
			
			$this->appMessage->setNotice(1, "New report objectives added.");
			$this->_redirect('/admin/system/report-objectives'); 			
		}
		
		$add_drawingapprovalcases = $Request->getParam('add_drawingapprovalcases');	
		if ($add_drawingapprovalcases)
		{
			$drawingapprovalcases = $Request->getParam('drawingapprovalcases') ? $Request->getParam('drawingapprovalcases') : "15";	
			$drawingapprovalcases_year = $Request->getParam('drawingapprovalcases_year') ? $Request->getParam('drawingapprovalcases_year') : "2016";			
			
			$arrInsert = array("ObjectiveType"=>"drawingapprovalcases","ObjectiveValue"=>$drawingapprovalcases,"ObjectiveYear"=>$drawingapprovalcases_year);
			$db->insert("ReportObjectives", $arrInsert);
			
			$this->appMessage->setNotice(1, "New report objectives added.");
			$this->_redirect('/admin/system/report-objectives'); 			
		}
*/		
		$this->view->deliveryobjective = "";$this->view->purchaseobjective = "";
		$arrObjectivesAll = $db->fetchAll("SELECT * FROM ReportObjectives order by ObjectiveYear DESC");
		foreach ($arrObjectivesAll as $arrObjectives)
		{
			if ($arrObjectives['ObjectiveType'] == "deliveryobjective")
			{
				$this->view->deliveryobjective .= "<TR class=report_even><TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveValue']."</TD>".
					"<TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveYear']."</TD>".
					"<TD class=report_cell style='text-align: center'><img style='cursor: pointer' src='/images/icons/IconTrash2.png' class='clsDeleteObjective' ReportObjectivesID=".$arrObjectives[ID]."></TD></TR>";
				
			}
			
			if ($arrObjectives['ObjectiveType'] == "purchaseobjective")
			{
				$this->view->purchaseobjective .= "<TR class=report_even><TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveValue']."</TD>".
					"<TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveYear']."</TD>".
					"<TD class=report_cell style='text-align: center'><img style='cursor: pointer' src='/images/icons/IconTrash2.png' class='clsDeleteObjective' ReportObjectivesID=".$arrObjectives[ID]."></TD></TR>";
				
			}
			
			if ($arrObjectives['ObjectiveType'] == "purchaseobjectivedays")
			{
				$this->view->purchaseobjectivedays .= "<TR class=report_even><TD class=report_cell style='text-align: center'>".number_format($arrObjectives['ObjectiveValue'],0)."</TD>".
					"<TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveYear']."</TD>".
					"<TD class=report_cell style='text-align: center'><img style='cursor: pointer' src='/images/icons/IconTrash2.png' class='clsDeleteObjective' ReportObjectivesID=".$arrObjectives[ID]."></TD></TR>";
				
			}
			
			if ($arrObjectives['ObjectiveType'] == "latedelivery")
			{
				$this->view->latedelivery .= "<TR class=report_even><TD class=report_cell style='text-align: center'>".number_format($arrObjectives['ObjectiveValue'],0)."</TD>".
					"<TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveYear']."</TD>".
					"<TD class=report_cell style='text-align: center'><img style='cursor: pointer' src='/images/icons/IconTrash2.png' class='clsDeleteObjective' ReportObjectivesID=".$arrObjectives[ID]."></TD></TR>";
				
			}
			
			if ($arrObjectives['ObjectiveType'] == "purchaseobjective2")
			{
				$this->view->purchaseobjective2 .= "<TR class=report_even><TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveValue']."</TD>".
					"<TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveYear']."</TD>".
					"<TD class=report_cell style='text-align: center'><img style='cursor: pointer' src='/images/icons/IconTrash2.png' class='clsDeleteObjective' ReportObjectivesID=".$arrObjectives[ID]."></TD></TR>";
				
			}
			
			if ($arrObjectives['ObjectiveType'] == "purchaseobjectivedays2")
			{
				$this->view->purchaseobjectivedays2 .= "<TR class=report_even><TD class=report_cell style='text-align: center'>".number_format($arrObjectives['ObjectiveValue'],0)."</TD>".
					"<TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveYear']."</TD>".
					"<TD class=report_cell style='text-align: center'><img style='cursor: pointer' src='/images/icons/IconTrash2.png' class='clsDeleteObjective' ReportObjectivesID=".$arrObjectives[ID]."></TD></TR>";
				
			}
/*			
			if ($arrObjectives['ObjectiveType'] == "drawingapproval")
			{
				$this->view->drawingapproval .= "<TR class=report_even><TD class=report_cell style='text-align: center'>".number_format($arrObjectives['ObjectiveValue'],0)."</TD>".
					"<TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveYear']."</TD>".
					"<TD class=report_cell style='text-align: center'><img style='cursor: pointer' src='/images/icons/IconTrash2.png' class='clsDeleteObjective' ReportObjectivesID=".$arrObjectives[ID]."></TD></TR>";
				
			}
			
			if ($arrObjectives['ObjectiveType'] == "drawingapprovalcases")
			{
				$this->view->drawingapprovalcases .= "<TR class=report_even><TD class=report_cell style='text-align: center'>".number_format($arrObjectives['ObjectiveValue'],0)."</TD>".
					"<TD class=report_cell style='text-align: center'>".$arrObjectives['ObjectiveYear']."</TD>".
					"<TD class=report_cell style='text-align: center'><img style='cursor: pointer' src='/images/icons/IconTrash2.png' class='clsDeleteObjective' ReportObjectivesID=".$arrObjectives[ID]."></TD></TR>";
				
			}
*/			
		}
		
	}
	

   public function customersAction()
    {
		try {
			$Request = $this->getRequest();			
			$db = Zend_Db_Table::getDefaultAdapter(); 
			$sysHelper = new Venz_App_System_Helper();
			$libDb = new Venz_App_Db_Table();

			$isAdmin = $this->userInfo->ACLRole == 'AdminSystem' || $this->userInfo->ACLRole == 'Admin';
			$this->view->isAdmin = $isAdmin;



			/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
			$sortby = $Request->getParam('sortby');			
			if (strlen($sortby) == 0) $sortby = 'Customers.Name';
				
			$ascdesc = $Request->getParam('ascdesc');			
			if (strlen($ascdesc) == 0) $ascdesc = 'asc'; 
			
			$showPage = $Request->getParam('Pagerpagenum');			
			if (!$showPage) $showPage = 1; 
				
			$pagerNext = $Request->getParam('Pager_next_page');			
			if (strlen($pagerNext) > 0) $showPage++; 	

			$pagerPrev = $Request->getParam('Pager_prev_page');			
			if (strlen($pagerPrev) > 0) $showPage--; 	
			
			$recordsPerPage = 30 ;
			////////////////////////////////////////////////////////////////////////////////////////
			
			$add_customer = $Request->getParam('add_customer');	
			if ($add_customer && $isAdmin)
			{
				$Name = $Request->getParam('Name') ? $Request->getParam('Name') : new Zend_Db_Expr('NULL');	
				$Code = $Request->getParam('Code') ? $Request->getParam('Code') : new Zend_Db_Expr('NULL');	
				$FaxNo = $Request->getParam('FaxNo') ? $Request->getParam('FaxNo') : new Zend_Db_Expr('NULL');	
				$PaymentTerms = $Request->getParam('PaymentTerms') ? $Request->getParam('PaymentTerms') : new Zend_Db_Expr('NULL');
                $Attn = $Request->getParam('Attn') ? $Request->getParam('Attn') : new Zend_Db_Expr('NULL');
                $CreditLimit = $Request->getParam('CreditLimit') ? $Request->getParam('CreditLimit') : new Zend_Db_Expr('NULL');
				$Phone = $Request->getParam('Phone') ? $Request->getParam('Phone') : new Zend_Db_Expr('NULL');			
				$Email = $Request->getParam('Email') ? $Request->getParam('Email') : new Zend_Db_Expr('NULL');			
				$Address = $Request->getParam('Address') ? $Request->getParam('Address') : new Zend_Db_Expr('NULL');			
				
				$arrInsert = array("Name"=>$Name,"Phone"=>$Phone,"Email"=>$Email,"FaxNo"=>$FaxNo,"Attn"=>$Attn,"PaymentTerms"=>$PaymentTerms,"CreditLimit"=>$CreditLimit,"Address"=>$Address);
				$db->insert("Customers", $arrInsert);
				
				$this->appMessage->setNotice(1, "New customer \"<B>".$Name."</B>\" has been created.");
				$this->_redirect('/admin/system/customers'); 
								
			}
			
			$remove_customer = $Request->getParam('remove_customer');	
			if ($remove_customer && $isAdmin)
			{
				$db->delete("Customers", "ID=".$remove_customer);
				$this->appMessage->setNotice(1, "The customer has been deleted.");
				$this->_redirect('/admin/system/customers'); 
				
			}
			
			
			$save_customer = $Request->getParam('save_customer');	
			if ($save_customer && $isAdmin)
			{
				$Name = $Request->getParam('Name') ? $Request->getParam('Name') : new Zend_Db_Expr('NULL');	
				$Code = $Request->getParam('Code') ? $Request->getParam('Code') : new Zend_Db_Expr('NULL');	
				$FaxNo = $Request->getParam('FaxNo') ? $Request->getParam('FaxNo') : new Zend_Db_Expr('NULL');	
				$PaymentTerms = $Request->getParam('PaymentTerms') ? $Request->getParam('PaymentTerms') : new Zend_Db_Expr('NULL');
                $Attn = $Request->getParam('Attn') ? $Request->getParam('Attn') : new Zend_Db_Expr('NULL');
                $CreditLimit = $Request->getParam('CreditLimit') ? $Request->getParam('CreditLimit') : new Zend_Db_Expr('NULL');
				$Phone = $Request->getParam('Phone') ? $Request->getParam('Phone') : new Zend_Db_Expr('NULL');			
				$Email = $Request->getParam('Email') ? $Request->getParam('Email') : new Zend_Db_Expr('NULL');			
				$Address = $Request->getParam('Address') ? $Request->getParam('Address') : new Zend_Db_Expr('NULL');			
				$customerID = $Request->getParam('customerID');	
		
				$arrUpdate = array("Name"=>$Name, "Phone"=>$Phone,"Email"=>$Email,"Address"=>$Address,"FaxNo"=>$FaxNo,"Attn"=>$Attn,"PaymentTerms"=>$PaymentTerms,"CreditLimit"=>$CreditLimit);
				$db->update("Customers", $arrUpdate, "ID=".$customerID);
			
				// UPDATE ALL JOBS
				$arrUpdateJob = array("CustomerName"=>$Name);
				$db->update("Job", $arrUpdateJob, "CustomerID=".$customerID);
			
			
				$this->appMessage->setNotice(1, "Details for \"<B>".$Name."</B>\" has been saved.");
				$this->_redirect('/admin/system/customers/edit_customer/'.$customerID.'/#tabs1'); 
				
			}
				
			
			$this->view->edit_customer = "";
			$edit_customer = $Request->getParam('edit_customer');	
			if ($edit_customer && $isAdmin)
			{
				$customerID = $edit_customer;
				$this->view->edit_customer = $edit_customer;
				$arrCustomer = $db->fetchRow("SELECT * FROM Customers where ID=".$customerID);
							
								
				$this->view->Name = $arrCustomer['Name'];	
				$this->view->Phone = $arrCustomer['Phone'];	
				$this->view->Email = $arrCustomer['Email'];	
				$this->view->Code = $arrCustomer['Code'];	
				$this->view->FaxNo = $arrCustomer['FaxNo'];	
				$this->view->PaymentTerms = $arrCustomer['PaymentTerms'];
                $this->view->Attn = $arrCustomer['Attn'];
                $this->view->CreditLimit = $arrCustomer['CreditLimit'];
				$this->view->Address = $arrCustomer['Address'];	
				
			}
			
				
			
			
			$sqlSearch = "";
			$search_customer = $Request->getParam('search_customer');	
			
			$strHiddenSearch = "";
			if ($search_customer)
			{
				$this->view->searchBranches = true;
				$searchName = $Request->getParam('searchName');	
				$searchPhone = $Request->getParam('searchPhone');					
				$searchCode = $Request->getParam('searchCode');					
				$searchFaxNo = $Request->getParam('searchFaxNo');					
				$searchPaymentTerms = $Request->getParam('searchPaymentTerms');
                $searchAttn = $Request->getParam('searchAttn');
                $SearchCreditLimitFrom = $Request->getParam('SearchCreditLimitFrom');
				$SearchCreditLimitTo = $Request->getParam('SearchCreditLimitTo');					
				$searchEmail = $Request->getParam('searchEmail');					
				$searchAddress = $Request->getParam('searchAddress');					
				
				$sqlSearch .= $searchName ? " and Customers.Name LIKE '%".$searchName."%'" : "";
				$sqlSearch .= $searchPhone ? " and Customers.Phone LIKE '%".$searchPhone."%'" : "";
				$sqlSearch .= $searchCode ? " and Customers.Code LIKE '%".$searchCode."%'" : "";
				$sqlSearch .= $searchFaxNo ? " and Customers.FaxNo LIKE '%".$searchFaxNo."%'" : "";
				$sqlSearch .= $searchPaymentTerms ? " and Customers.PaymentTerms LIKE '%".$searchPaymentTerms."%'" : "";
                $sqlSearch .= $searchAttn ? " and Customers.Attn LIKE '%".$searchAttn."%'" : "";
                $sqlSearch .= $SearchCreditLimitFrom ? " and Customers.CreditLimit >= ".$SearchCreditLimitFrom : "";
				$sqlSearch .= $SearchCreditLimitTo ? " and Customers.CreditLimit <= ".$SearchCreditLimitTo : "";
				$sqlSearch .= $searchEmail ? " and Customers.Email LIKE '%".$searchEmail."%'" : "";
				$sqlSearch .= $searchAddress ? " and Customers.Address LIKE '%".$searchAddress."%'" : "";
				
				//print $sqlSearch; exit();
				$this->view->searchName = $searchName ? $searchName : "";
				$this->view->searchPhone = $searchPhone ? $searchPhone : "";
				$this->view->searchCode = $searchCode ? $searchCode : "";
				$this->view->searchFaxNo = $searchFaxNo ? $searchFaxNo : "";
				$this->view->searchPaymentTerms = $searchPaymentTerms ? $searchPaymentTerms : "";
                $this->view->searchAttn = $searchAttn ? $searchAttn : "";
                $this->view->SearchCreditLimitFrom = $SearchCreditLimitFrom ? $SearchCreditLimitFrom : "";
				$this->view->SearchCreditLimitTo = $SearchCreditLimitTo ? $SearchCreditLimitTo : "";
				$this->view->searchEmail = $searchEmail ? $searchEmail : "";
				$this->view->searchAddress = $searchAddress ? $searchAddress : "";

				$strHiddenSearch = "<input type=hidden name='search_customer' value='true'>";
				$strHiddenSearch .= "<input type=hidden name='searchName' value='".$searchName."'>";
				$strHiddenSearch .= "<input type=hidden name='searchCode' value='".$searchCode."'>";
				$strHiddenSearch .= "<input type=hidden name='searchFaxNo' value='".$searchFaxNo."'>";
				$strHiddenSearch .= "<input type=hidden name='searchPaymentTerms' value='".$searchPaymentTerms."'>";
                $strHiddenSearch .= "<input type=hidden name='searchAttn' value='".$searchAttn."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchCreditLimitFrom' value='".$SearchCreditLimitFrom."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchCreditLimitTo' value='".$SearchCreditLimitTo."'>";
				$strHiddenSearch .= "<input type=hidden name='searchEmail' value='".$searchEmail."'>";
				$strHiddenSearch .= "<input type=hidden name='searchPhone' value='".$searchPhone."'>";
				$strHiddenSearch .= "<input type=hidden name='searchAddress' value='".$searchAddress."'>";

			}
			
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_NUM);

			$arrCustomer = $sysHelper->getCustomers($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$dataCustomer = $arrCustomer[1];
			

			$sessionCustomer = new Zend_Session_Namespace('sessionCustomer');
			$sessionCustomer->numCounter = $recordsPerPage * ($showPage-1);
			function format_counter($colnum, $rowdata)
			{
				$sessionCustomer = new Zend_Session_Namespace('sessionCustomer');
				$sessionCustomer->numCounter++;
				return $sessionCustomer->numCounter;
			}
			
			
			function format_action($colnum, $rowdata)
			{
                $systemSetting = new Zend_Session_Namespace('systemSetting');
                $isAdmin = $systemSetting->userInfo->ACLRole == 'AdminSystem' || $systemSetting->userInfo->ACLRole == 'Admin';

                if ($isAdmin) {
                    $strDelete = " | <a href='javascript:void(0);' onclick='OnDeleteCustomer(".$rowdata[0].")'><img border=0 src='/images/icons/IconTrash2.png'></a>";
                    if ($rowdata[5] > 0)
                        $strDelete = " | <img border=0 title='Deletion not allowed. Job exist for the customer' src='/images/icons/IconTrash2X.png'>";
                    return "<a href='/admin/system/customers/edit_customer/".$rowdata[0]."#tabs1'><img border=0 src='/images/icons/IconEdit.gif'></a>".$strDelete;
                }else{
                    return "";

                }

			}
			
			function format_phone($colnum, $rowdata)
			{
				return str_replace(",","<BR>", str_replace("/","<BR>", $rowdata[3]));
				
			}

            if ($isAdmin){
                $arrHeader = array ('', 'Name', 'Code', 'Phone #', 'Fax #', 'Attn', 'Email', 'Payment Terms','Credit Limit', 'Address', 'Total Jobs', '');
                $arrFormat = array('{format_counter}','%1%', '%2%', '{format_phone}', '%4%', '%10%','%5%', '%6%','%9%', '%7%', '%8%', '{format_action}');
            }else{
                $arrHeader = array ('', 'Name', 'Code', 'Phone #', 'Fax #', 'Attn', 'Email', 'Payment Terms','Credit Limit', 'Address', 'Total Jobs');
                $arrFormat = array('{format_counter}','%1%', '%2%', '{format_phone}', '%4%', '%10%','%5%', '%6%','%9%', '%7%', '%8%');
            }
			$arrSort = array('','Customers.Name', 'Customers.Code', 'Customers.Phone', 'Customers.FaxNo', 'Customers.Attn', 'Customers.Email', 'Customers.PaymentTerms', 'Customers.CreditLimit', 'Customers.Address', '');
			$aligndata = 'CLCLLLLCCLCCC'; $tablewidth = '1550px';
			
			$displayTable = new Venz_App_Display_Table(
				array (
					 'data' => $dataCustomer,
					 'headings' => $arrHeader,
					 'format' 		=> $arrFormat,					 
					 'sort_column' 	=> $arrSort,
					 'alllen' 		=> $arrCustomer[0],
					 'title'		=> $this->translate->_('Customer List: ').$arrCustomer[0],					 
					 'aligndata' 	=> $aligndata,
					 'pagelen' 		=> $recordsPerPage,
					 'numcols' 		=> sizeof($arrHeader),
					 'tablewidth' 	=> $tablewidth,
					 'sortby' 		=> $sortby,
					 'ascdesc' 		=> $ascdesc,
					 'hiddenparam' 	=> $strHiddenSearch,
				)
			);
			$this->view->content_customer = $displayTable->render();
			
			
			
			
		}catch (Exception $e) {
		
			echo $e->getMessage();
		}	
	
	
    }		

	public function supplierAction()
    {
		try {
			$Request = $this->getRequest();			
			$db = Zend_Db_Table::getDefaultAdapter(); 
			$sysHelper = new Venz_App_System_Helper();
			$libDb = new Venz_App_Db_Table();



			/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
			$sortby = $Request->getParam('sortby');			
			if (strlen($sortby) == 0) $sortby = 'Supplier.Name';
				
			$ascdesc = $Request->getParam('ascdesc');			
			if (strlen($ascdesc) == 0) $ascdesc = 'asc'; 
			
			$showPage = $Request->getParam('Pagerpagenum');			
			if (!$showPage) $showPage = 1; 
				
			$pagerNext = $Request->getParam('Pager_next_page');			
			if (strlen($pagerNext) > 0) $showPage++; 	

			$pagerPrev = $Request->getParam('Pager_prev_page');			
			if (strlen($pagerPrev) > 0) $showPage--; 	
			
			$recordsPerPage = 30 ;
			////////////////////////////////////////////////////////////////////////////////////////
			
			$add_supplier = $Request->getParam('add_supplier');	
			if ($add_supplier)
			{
				$Name = $Request->getParam('Name') ? $Request->getParam('Name') : new Zend_Db_Expr('NULL');	
				$Code = $Request->getParam('Code') ? $Request->getParam('Code') : new Zend_Db_Expr('NULL');	
				$FaxNo = $Request->getParam('FaxNo') ? $Request->getParam('FaxNo') : new Zend_Db_Expr('NULL');	
				$PaymentTerms = $Request->getParam('PaymentTerms') ? $Request->getParam('PaymentTerms') : new Zend_Db_Expr('NULL');	
				$Phone = $Request->getParam('Phone') ? $Request->getParam('Phone') : new Zend_Db_Expr('NULL');			
				$Email = $Request->getParam('Email') ? $Request->getParam('Email') : new Zend_Db_Expr('NULL');			
				$Address = $Request->getParam('Address') ? $Request->getParam('Address') : new Zend_Db_Expr('NULL');			
				
				$arrInsert = array("Name"=>$Name,"Code"=>$Code,"Phone"=>$Phone,"Email"=>$Email,"Address"=>$Address);
				$db->insert("Supplier", $arrInsert);
				
				$this->appMessage->setNotice(1, "New supplier \"<B>".$Name."</B>\" has been created.");
				$this->_redirect('/admin/system/supplier'); 
								
			}
			
			$remove_supplier = $Request->getParam('remove_supplier');	
			if ($remove_supplier)
			{
				$db->delete("Supplier", "ID=".$remove_supplier);
				$this->appMessage->setNotice(1, "The supplier has been deleted.");
				$this->_redirect('/admin/system/supplier'); 
				
			}
			
			
			$save_supplier = $Request->getParam('save_supplier');	
			if ($save_supplier)
			{
				$Name = $Request->getParam('Name') ? $Request->getParam('Name') : new Zend_Db_Expr('NULL');	
				$Code = $Request->getParam('Code') ? $Request->getParam('Code') : new Zend_Db_Expr('NULL');	
				$Phone = $Request->getParam('Phone') ? $Request->getParam('Phone') : new Zend_Db_Expr('NULL');			
				$FaxNo = $Request->getParam('FaxNo') ? $Request->getParam('FaxNo') : new Zend_Db_Expr('NULL');	
				$PaymentTerms = $Request->getParam('PaymentTerms') ? $Request->getParam('PaymentTerms') : new Zend_Db_Expr('NULL');	
				$Email = $Request->getParam('Email') ? $Request->getParam('Email') : new Zend_Db_Expr('NULL');			
				$Address = $Request->getParam('Address') ? $Request->getParam('Address') : new Zend_Db_Expr('NULL');			
				$supplierID = $Request->getParam('supplierID');	
		
				$arrUpdate = array("Name"=>$Name, "Code"=>$Code, "Phone"=>$Phone,"Email"=>$Email,"Address"=>$Address,"FaxNo"=>$FaxNo,"PaymentTerms"=>$PaymentTerms);
				$db->update("Supplier", $arrUpdate, "ID=".$supplierID);
			
				// UPDATE ALL JOBS
				$arrUpdateJob = array("SupplierName"=>$Name, "SupplierCode"=>$Code);
				$db->update("JobPurchase", $arrUpdateJob, "SupplierID=".$supplierID);
			
			
				$this->appMessage->setNotice(1, "Details for \"<B>".$Name."</B>\" has been saved.");
				$this->_redirect('/admin/system/supplier/edit_supplier/'.$supplierID.'/#tabs1'); 
				
			}
				
			
			$this->view->edit_supplier = "";
			$edit_supplier = $Request->getParam('edit_supplier');	
			if ($edit_supplier)
			{
				$supplierID = $edit_supplier;
				$this->view->edit_supplier = $edit_supplier;
				$arrSupplier = $db->fetchRow("SELECT * FROM Supplier where ID=".$supplierID);
				
				$this->view->Name = $arrSupplier['Name'];	
				$this->view->Code = $arrSupplier['Code'];	
				$this->view->Phone = $arrSupplier['Phone'];	
				$this->view->Email = $arrSupplier['Email'];	
				$this->view->Address = $arrSupplier['Address'];	
				$this->view->FaxNo = $arrSupplier['FaxNo'];	
				$this->view->PaymentTerms = $arrSupplier['PaymentTerms'];	
				
			}
			
				
			
			
			$sqlSearch = "";
			$search_supplier = $Request->getParam('search_supplier');	
			
			$strHiddenSearch = "";
			if ($search_supplier)
			{
				$this->view->searchBranches = true;
				$searchName = $Request->getParam('searchName');	
				$searchCode = $Request->getParam('searchCode');	
				$searchFaxNo = $Request->getParam('searchFaxNo');					
				$searchPaymentTerms = $Request->getParam('searchPaymentTerms');					
				$searchPhone = $Request->getParam('searchPhone');					
				$searchEmail = $Request->getParam('searchEmail');					
				$searchAddress = $Request->getParam('searchAddress');					
				
				$sqlSearch .= $searchName ? " and Supplier.Name LIKE '%".$searchName."%'" : "";
				$sqlSearch .= $searchCode ? " and Supplier.Code LIKE '%".$searchCode."%'" : "";
				$sqlSearch .= $searchFaxNo ? " and Supplier.FaxNo LIKE '%".$searchFaxNo."%'" : "";
				$sqlSearch .= $searchPaymentTerms ? " and Supplier.PaymentTerms LIKE '%".$searchPaymentTerms."%'" : "";
				$sqlSearch .= $searchPhone ? " and Supplier.Phone LIKE '%".$searchPhone."%'" : "";
				$sqlSearch .= $searchEmail ? " and Supplier.Email LIKE '%".$searchEmail."%'" : "";
				$sqlSearch .= $searchAddress ? " and Supplier.Address LIKE '%".$searchAddress."%'" : "";
				
				//print $sqlSearch; exit();
				$this->view->searchName = $searchName ? $searchName : "";				
				$this->view->searchCode = $searchCode ? $searchCode : "";				
				$this->view->searchFaxNo = $searchFaxNo ? $searchFaxNo : "";
				$this->view->searchPaymentTerms = $searchPaymentTerms ? $searchPaymentTerms : "";
				$this->view->searchPhone = $searchPhone ? $searchPhone : "";
				$this->view->searchEmail = $searchEmail ? $searchEmail : "";
				$this->view->searchAddress = $searchAddress ? $searchAddress : "";

				$strHiddenSearch = "<input type=hidden name='search_supplier' value='true'>";
				$strHiddenSearch .= "<input type=hidden name='searchName' value='".$searchName."'>";
				$strHiddenSearch .= "<input type=hidden name='searchCode' value='".$searchCode."'>";
				$strHiddenSearch .= "<input type=hidden name='searchFaxNo' value='".$searchFaxNo."'>";
				$strHiddenSearch .= "<input type=hidden name='searchPaymentTerms' value='".$searchPaymentTerms."'>";
				$strHiddenSearch .= "<input type=hidden name='searchEmail' value='".$searchEmail."'>";
				$strHiddenSearch .= "<input type=hidden name='searchPhone' value='".$searchPhone."'>";
				$strHiddenSearch .= "<input type=hidden name='searchAddress' value='".$searchAddress."'>";

			}
			
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_NUM);

			$arrSupplier = $sysHelper->getSupplier($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$dataSupplier = $arrSupplier[1];
			

			$sessionSupplier = new Zend_Session_Namespace('sessionSupplier');
			$sessionSupplier->numCounter = $recordsPerPage * ($showPage-1);
			function format_counter($colnum, $rowdata)
			{
				$sessionSupplier = new Zend_Session_Namespace('sessionSupplier');
				$sessionSupplier->numCounter++;
				return $sessionSupplier->numCounter;
			}
			
			
			function format_action($colnum, $rowdata)
			{
				$strDelete = " | <a href='javascript:void(0);' onclick='OnDeleteSupplier(".$rowdata[0].")'><img border=0 src='/images/icons/IconTrash2.png'></a>";
				if ($rowdata[8] > 0)
					$strDelete = " | <img border=0 title='Deletion not allowed. Job purchases exist for the supplier' src='/images/icons/IconTrash2X.png'>";
				return "<a href='/admin/system/supplier/edit_supplier/".$rowdata[0]."#tabs1'><img border=0 src='/images/icons/IconEdit.gif'></a>".$strDelete;
				
			}
			
			function format_phone($colnum, $rowdata)
			{
				return str_replace(",","<BR>", str_replace("/","<BR>", $rowdata[3]));
				
			}
			
			

		//	$arrHeader = array ('', $this->translate->_('Name'), $this->translate->_('Code'), $this->translate->_('Phone #'), $this->translate->_('Email'), $this->translate->_('Address'), $this->translate->_('Total Jobs'), $this->translate->_(''));
			$arrHeader = array ('', 'Name', 'Code', 'Phone #', 'Fax #', 'Email', 'Payment Terms', 'Address', 'Total Jobs', '');
			$arrFormat = array('{format_counter}','%1%', '%2%', '{format_phone}', '%4%', '%5%', '%6%', '%7%', '%8%', '{format_action}');

			//$arrFormat = array('{format_counter}','%1%', '%2%', '%3%', '%4%', '%5%', '%6%', '{format_action}');
			$arrSort = array('','Supplier.Name', 'Supplier.Code', 'Supplier.Phone', 'Supplier.FaxNo', 'Supplier.Email', 'Supplier.PaymentTerms', 'Supplier.Address', 'JobPurchase.TotalJob');
			$aligndata = 'CLCLLLCLCC'; $tablewidth = '1450px';
			
			$displayTable = new Venz_App_Display_Table(
				array (
					 'data' => $dataSupplier,
					 'headings' => $arrHeader,
					 'format' 		=> $arrFormat,					 
					 'sort_column' 	=> $arrSort,
					 'alllen' 		=> $arrSupplier[0],
					 'title'		=> $this->translate->_('Supplier List: ').$arrSupplier[0],					 
					 'aligndata' 	=> $aligndata,
					 'pagelen' 		=> $recordsPerPage,
					 'numcols' 		=> sizeof($arrHeader),
					 'tablewidth' 	=> $tablewidth,
					 'sortby' 		=> $sortby,
					 'ascdesc' 		=> $ascdesc,
					 'hiddenparam' 	=> $strHiddenSearch,
				)
			);
			$this->view->content_supplier = $displayTable->render();
			
			
			
			
		}catch (Exception $e) {
		
			echo $e->getMessage();
		}	
	
	
    }



	public function currencyAction()
    {
		try {
			$Request = $this->getRequest();			
			$db = Zend_Db_Table::getDefaultAdapter(); 
			$sysHelper = new Venz_App_System_Helper();
			$libDb = new Venz_App_Db_Table();



			/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
			$sortby = $Request->getParam('sortby');			
			if (strlen($sortby) == 0) $sortby = 'Currency.ID';
				
			$ascdesc = $Request->getParam('ascdesc');			
			if (strlen($ascdesc) == 0) $ascdesc = 'asc'; 
			
			$showPage = $Request->getParam('Pagerpagenum');			
			if (!$showPage) $showPage = 1; 
				
			$pagerNext = $Request->getParam('Pager_next_page');			
			if (strlen($pagerNext) > 0) $showPage++; 	

			$pagerPrev = $Request->getParam('Pager_prev_page');			
			if (strlen($pagerPrev) > 0) $showPage--; 	
			
			$recordsPerPage = 30 ;
			////////////////////////////////////////////////////////////////////////////////////////
			
			$add_currency = $Request->getParam('add_currency');	
			if ($add_currency)
			{
				$Name = $Request->getParam('Name') ? $Request->getParam('Name') : new Zend_Db_Expr('NULL');	
				$Code = $Request->getParam('Code') ? $Request->getParam('Code') : new Zend_Db_Expr('NULL');	
				$Rate = $Request->getParam('Rate') ? $Request->getParam('Rate') : new Zend_Db_Expr('NULL');			
				
				$arrInsert = array("Name"=>$Name,"Code"=>$Code,"Rate"=>$Rate);
				$db->insert("Currency", $arrInsert);
				
				$this->appMessage->setNotice(1, "New currency \"<B>".$Name."</B>\" has been created.");
				$this->_redirect('/admin/system/currency'); 
								
			}
			
			$remove_currency = $Request->getParam('remove_currency');	
			if ($remove_currency)
			{
				$db->delete("Currency", "ID=".$remove_currency);
				$this->appMessage->setNotice(1, "The currency has been deleted.");
				$this->_redirect('/admin/system/currency'); 
				
			}
			
			
			$save_currency = $Request->getParam('save_currency');	
			if ($save_currency)
			{
				$Name = $Request->getParam('Name') ? $Request->getParam('Name') : new Zend_Db_Expr('NULL');	
				$Code = $Request->getParam('Code') ? $Request->getParam('Code') : new Zend_Db_Expr('NULL');	
				$Rate = $Request->getParam('Rate') ? $Request->getParam('Rate') : new Zend_Db_Expr('NULL');			
				$currencyID = $Request->getParam('currencyID');	
		
				$arrUpdate = array("Name"=>$Name, "Code"=>$Code, "Rate"=>$Rate);
				$db->update("Currency", $arrUpdate, "ID=".$currencyID);
			
				// UPDATE ALL JOBS
			//	$arrUpdateJob = array("SupplierName"=>$Name, "SupplierCode"=>$Code);
			//	$db->update("JobPurchase", $arrUpdateJob, "SupplierID=".$supplierID);
			
			
				$this->appMessage->setNotice(1, "Details for \"<B>".$Name."</B>\" has been saved.");
				$this->_redirect('/admin/system/currency/edit_currency/'.$currencyID.'/#tabs1'); 
				
			}
				
			
			$this->view->edit_currency = "";
			$edit_currency = $Request->getParam('edit_currency');	
			if ($edit_currency)
			{
				$currencyID = $edit_currency;
				$this->view->edit_currency = $edit_currency;
				$arrCurrency = $db->fetchRow("SELECT * FROM Currency where ID=".$currencyID);
				
				$this->view->Name = $arrCurrency['Name'];	
				$this->view->Code = $arrCurrency['Code'];	
				$this->view->Rate = $arrCurrency['Rate'];		
				
			}
			
				
			
			
			$sqlSearch = "";
			$search_currency = $Request->getParam('search_currency');	
			
			$strHiddenSearch = "";
			if ($search_currency)
			{
				$this->view->searchBranches = true;
				$searchName = $Request->getParam('searchName');	
				$searchCode = $Request->getParam('searchCode');	
				
				$sqlSearch .= $searchName ? " and Currency.Name LIKE '%".$searchName."%'" : "";
				$sqlSearch .= $searchCode ? " and Currency.Code LIKE '%".$searchCode."%'" : "";
				
				//print $sqlSearch; exit();
				$this->view->searchName = $searchName ? $searchName : "";				
				$this->view->searchCode = $searchCode ? $searchCode : "";				

				$strHiddenSearch = "<input type=hidden name='search_currency' value='true'>";
				$strHiddenSearch .= "<input type=hidden name='searchName' value='".$searchName."'>";
				$strHiddenSearch .= "<input type=hidden name='searchCode' value='".$searchCode."'>";

			}
			
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_NUM);

			$arrCurrency = $sysHelper->getCurrency($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$dataCurrency = $arrCurrency[1];
			

			$sessionCurrency = new Zend_Session_Namespace('sessionCurrency');
			$sessionCurrency->numCounter = $recordsPerPage * ($showPage-1);
			function format_counter($colnum, $rowdata)
			{
				$sessionCurrency = new Zend_Session_Namespace('sessionCurrency');
				$sessionCurrency->numCounter++;
				return $sessionCurrency->numCounter;
			}
			
			
			function format_action($colnum, $rowdata)
			{
				$strDelete = " | <a href='javascript:void(0);' onclick='OnDeleteCurrency(".$rowdata[0].")'><img border=0 src='/images/icons/IconTrash2.png'></a>";
				if ($rowdata[2] == "RM")
					return " - ";
				else
					return "<a href='/admin/system/currency/edit_currency/".$rowdata[0]."#tabs1'><img border=0 src='/images/icons/IconEdit.gif'></a>".$strDelete;
				
			}
			
			
			function format_lastmodified($colnum, $rowdata)
			{
				$dispFormat = new Venz_App_Display_Format();
		
				return $dispFormat->format_datetime_simple($rowdata[4], "<BR>");
				
			}
			

			$arrHeader = array ('', $this->translate->_('Name'), $this->translate->_('Code'), $this->translate->_('Current Rate'), $this->translate->_('Last Modified'), $this->translate->_(''));
			$arrFormat = array('{format_counter}','%1%', '%2%', '%3%', '{format_lastmodified}', '{format_action}');
			$arrSort = array('','Currency.Name', 'Currency.Code', 'Currency.Rate', 'Currency.LastModified', '');
			$aligndata = 'CLCCCC'; $tablewidth = '550px';
			
			$displayTable = new Venz_App_Display_Table(
				array (
					 'data' => $dataCurrency,
					 'headings' => $arrHeader,
					 'format' 		=> $arrFormat,					 
					 'sort_column' 	=> $arrSort,
					 'alllen' 		=> $arrCurrency[0],
					 'title'		=> $this->translate->_('Currency List: ').$arrCurrency[0],					 
					 'aligndata' 	=> $aligndata,
					 'pagelen' 		=> $recordsPerPage,
					 'numcols' 		=> sizeof($arrHeader),
					 'tablewidth' 	=> $tablewidth,
					 'sortby' 		=> $sortby,
					 'ascdesc' 		=> $ascdesc,
					 'hiddenparam' 	=> $strHiddenSearch,
				)
			);
			$this->view->content_currency = $displayTable->render();
			
			
			
			
		}catch (Exception $e) {
		
			echo $e->getMessage();
		}	
	
	
    }	

	public function termsAction()
    {
		try {
			$Request = $this->getRequest();			
			$db = Zend_Db_Table::getDefaultAdapter(); 
			$sysHelper = new Venz_App_System_Helper();
			$libDb = new Venz_App_Db_Table();



			/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
			$sortby = $Request->getParam('sortby');			
			if (strlen($sortby) == 0) $sortby = 'Terms.ID';
				
			$ascdesc = $Request->getParam('ascdesc');			
			if (strlen($ascdesc) == 0) $ascdesc = 'asc'; 
			
			$showPage = $Request->getParam('Pagerpagenum');			
			if (!$showPage) $showPage = 1; 
				
			$pagerNext = $Request->getParam('Pager_next_page');			
			if (strlen($pagerNext) > 0) $showPage++; 	

			$pagerPrev = $Request->getParam('Pager_prev_page');			
			if (strlen($pagerPrev) > 0) $showPage--; 	
			
			$recordsPerPage = 30 ;
			////////////////////////////////////////////////////////////////////////////////////////
			
			$add_terms = $Request->getParam('add_terms');	
			if ($add_terms)
			{
				$Name = $Request->getParam('Name') ? $Request->getParam('Name') : new Zend_Db_Expr('NULL');	
				$Code = $Request->getParam('Code') ? $Request->getParam('Code') : new Zend_Db_Expr('NULL');	
				
				$arrInsert = array("Name"=>$Name,"Code"=>$Code);
				$db->insert("Terms", $arrInsert);
				
				$this->appMessage->setNotice(1, "New terms \"<B>".$Name."</B>\" has been created.");
				$this->_redirect('/admin/system/terms'); 
								
			}
			
			$remove_terms = $Request->getParam('remove_terms');	
			if ($remove_terms)
			{
				$db->delete("Terms", "ID=".$remove_terms);
				$this->appMessage->setNotice(1, "The terms has been deleted.");
				$this->_redirect('/admin/system/terms'); 
				
			}
			
			
			$save_terms = $Request->getParam('save_terms');	
			if ($save_terms)
			{
				$Name = $Request->getParam('Name') ? $Request->getParam('Name') : new Zend_Db_Expr('NULL');	
				$Code = $Request->getParam('Code') ? $Request->getParam('Code') : new Zend_Db_Expr('NULL');	
				$termsID = $Request->getParam('termsID');	
		
				$arrUpdate = array("Name"=>$Name, "Code"=>$Code);
				$db->update("Terms", $arrUpdate, "ID=".$termsID);
			
				// UPDATE ALL JOBS
			//	$arrUpdateJob = array("SupplierName"=>$Name, "SupplierCode"=>$Code);
			//	$db->update("JobPurchase", $arrUpdateJob, "SupplierID=".$supplierID);
			
			
				$this->appMessage->setNotice(1, "Details for \"<B>".$Name."</B>\" has been saved.");
				$this->_redirect('/admin/system/terms/edit_terms/'.$termsID.'/#tabs1'); 
				
			}
				
			
			$this->view->edit_terms = "";
			$edit_terms = $Request->getParam('edit_terms');	
			if ($edit_terms)
			{
				$termsID = $edit_terms;
				$this->view->edit_terms = $edit_terms;
				$arrTerms = $db->fetchRow("SELECT * FROM Terms where ID=".$termsID);
				
				$this->view->Name = $arrTerms['Name'];	
				$this->view->Code = $arrTerms['Code'];	
				
			}
			
				
			
			
			$sqlSearch = "";
			$search_terms = $Request->getParam('search_terms');	
			
			$strHiddenSearch = "";
			if ($search_terms)
			{
				$this->view->searchBranches = true;
				$searchName = $Request->getParam('searchName');	
				$searchCode = $Request->getParam('searchCode');	
				
				$sqlSearch .= $searchName ? " and Terms.Name LIKE '%".$searchName."%'" : "";
				$sqlSearch .= $searchCode ? " and Terms.Code LIKE '%".$searchCode."%'" : "";
				
				//print $sqlSearch; exit();
				$this->view->searchName = $searchName ? $searchName : "";				
				$this->view->searchCode = $searchCode ? $searchCode : "";				

				$strHiddenSearch = "<input type=hidden name='search_terms' value='true'>";
				$strHiddenSearch .= "<input type=hidden name='searchName' value='".$searchName."'>";
				$strHiddenSearch .= "<input type=hidden name='searchCode' value='".$searchCode."'>";

			}
			
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_NUM);

			$arrTerms = $sysHelper->getTerms($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$dataTerms = $arrTerms[1];
			

			$sessionTerms = new Zend_Session_Namespace('sessionTerms');
			$sessionTerms->numCounter = $recordsPerPage * ($showPage-1);
			function format_counter($colnum, $rowdata)
			{
				$sessionTerms = new Zend_Session_Namespace('sessionTerms');
				$sessionTerms->numCounter++;
				return $sessionTerms->numCounter;
			}
			
			
			function format_action($colnum, $rowdata)
			{
				$strDelete = " | <a href='javascript:void(0);' onclick='OnDeleteTerms(".$rowdata[0].")'><img border=0 src='/images/icons/IconTrash2.png'></a>";
				if ($rowdata[2] == "RM")
					return " - ";
				else
					return "<a href='/admin/system/terms/edit_terms/".$rowdata[0]."#tabs1'><img border=0 src='/images/icons/IconEdit.gif'></a>".$strDelete;
				
			}
			
			
			function format_lastmodified($colnum, $rowdata)
			{
				$dispFormat = new Venz_App_Display_Format();
		
				return $dispFormat->format_datetime_simple($rowdata[4], "<BR>");
				
			}
			

			$arrHeader = array ('', $this->translate->_('Name'), $this->translate->_('Code'), $this->translate->_(''));
			$arrFormat = array('{format_counter}','%1%', '%2%', '{format_action}');
			$arrSort = array('','Terms.Name', 'Terms.Code', '');
			$aligndata = 'CLCCCC'; $tablewidth = '550px';
			
			$displayTable = new Venz_App_Display_Table(
				array (
					 'data' => $dataTerms,
					 'headings' => $arrHeader,
					 'format' 		=> $arrFormat,					 
					 'sort_column' 	=> $arrSort,
					 'alllen' 		=> $arrTerms[0],
					 'title'		=> $this->translate->_('Terms List: ').$arrTerms[0],					 
					 'aligndata' 	=> $aligndata,
					 'pagelen' 		=> $recordsPerPage,
					 'numcols' 		=> sizeof($arrHeader),
					 'tablewidth' 	=> $tablewidth,
					 'sortby' 		=> $sortby,
					 'ascdesc' 		=> $ascdesc,
					 'hiddenparam' 	=> $strHiddenSearch,
				)
			);
			$this->view->content_terms = $displayTable->render();
			
			
			
			
		}catch (Exception $e) {
		
			echo $e->getMessage();
		}	
	
	
    }		

	
	
	public function logAction()   
	{
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$sysHelper = new Venz_App_System_Helper();
		$libDb = new Venz_App_Db_Table();
		

		
		/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
		
		
		$sortby = $Request->getParam('sortby');			
		if (strlen($sortby) == 0) $sortby = 'SYSLog.logtime';
			
		$ascdesc = $Request->getParam('ascdesc');			
		if (strlen($ascdesc) == 0) $ascdesc = 'desc'; 
		
		$showPage = $Request->getParam('Pagerpagenum');			
		if (!$showPage) $showPage = 1; 
			
		$pagerNext = $Request->getParam('Pager_next_page');			
		if (strlen($pagerNext) > 0) $showPage++; 	

		$pagerPrev = $Request->getParam('Pager_prev_page');			
		if (strlen($pagerPrev) > 0) $showPage--; 	
		
		$recordsPerPage = 30 ;
		////////////////////////////////////////////////////////////////////////////////////////

		$sqlSearch = "";
		$search_log = $Request->getParam('search_log');	
		$strHiddenSearch = "";
		if ($search_log)
		{
			$Name = $Request->getParam('Name');	
			$sqlSearch .= $Name ? " and ACLUsers.Name LIKE '%".$Name."%'" : "";
			
			$Username = $Request->getParam('Username');	
			$sqlSearch .= $Username ? " and SYSLog.username LIKE '%".$Username."%'" : "";
			
			$Role = $Request->getParam('Role');	
			$sqlSearch .= $Role ? " and SYSLog.role LIKE '%".$Role."%'" : "";
			
			$Module = $Request->getParam('Module');	
			$sqlSearch .= $Module ? " and SYSLog.zendmodule LIKE '%".$Module."%'" : "";

			$Controller = $Request->getParam('Controller');	
			$sqlSearch .= $Controller ? " and SYSLog.zendcontroller LIKE '%".$Controller."%'" : "";
			
			$Action = $Request->getParam('Action');	
			$sqlSearch .= $Action ? " and SYSLog.zendaction LIKE '%".$Action."%'" : "";
			
		
			$GetData = $Request->getParam('GetData');	
			$sqlSearch .= $GetData ? " and SYSLog.getdata LIKE '%".$GetData."%'" : "";

			$PostData = $Request->getParam('PostData');	
			$sqlSearch .= $PostData ? " and SYSLog.postdata LIKE '%".$PostData."%'" : "";

			$IP = $Request->getParam('IP');	
			$sqlSearch .= $IP ? " and SYSLog.IP LIKE '%".$IP."%'" : "";

			$this->view->FromDateTime = $Request->getParam('FromDateTime');
			$FromDateTime = $this->view->FromDateTime;
			if ($FromDateTime){
				$FromDateTime = substr($FromDateTime, 6, 4)."-".substr($FromDateTime, 3, 2)."-".substr($FromDateTime, 0, 2);	
				$sqlSearch .= " and SYSLog.logtime >= '".$FromDateTime."'";	
			}
			
			$this->view->ToDateTime = $Request->getParam('ToDateTime');
			$ToDateTime = $this->view->ToDateTime;
			if ($ToDateTime){
				$ToDateTime = substr($ToDateTime, 6, 4)."-".substr($ToDateTime, 3, 2)."-".substr($ToDateTime, 0, 2);	
				$sqlSearch .= " and SYSLog.logtime <= '".$ToDateTime." 23:59:59'"; 	
			}				
			$this->view->Name = $Name ? $Name : "";				
			$this->view->Username = $Username ? $Username : "";				
			$this->view->Role = $Role ? $Role : "";				
			$this->view->Module = $Module ? $Module : "";				
			$this->view->Controller = $Controller ? $Controller : "";				
			$this->view->Action = $Action ? $Action : "";				
			$this->view->GetData = $GetData ? $GetData : "";				
			$this->view->PostData = $PostData ? $PostData : "";	
			$this->view->IP = $IP ? $IP : "";	
			
			
			$strHiddenSearch = "<input type=hidden name='search_log' value='true'>";
			$strHiddenSearch .= "<input type=hidden name='Username' value='".$Username."'>";
			$strHiddenSearch .= "<input type=hidden name='Role' value='".$Role."'>";
			$strHiddenSearch .= "<input type=hidden name='Module' value='".$Module."'>";
			$strHiddenSearch .= "<input type=hidden name='Controller' value='".$Controller."'>";
			$strHiddenSearch .= "<input type=hidden name='Action' value='".$Action."'>";
			$strHiddenSearch .= "<input type=hidden name='GetData' value='".$GetData."'>";
			$strHiddenSearch .= "<input type=hidden name='PostData' value='".$PostData."'>";
			$strHiddenSearch .= "<input type=hidden name='IP' value='".$IP."'>";
			$strHiddenSearch .= "<input type=hidden name='FromDateTime' value='".$this->view->FromDateTime."'>";
			$strHiddenSearch .= "<input type=hidden name='ToDateTime' value='".$this->view->ToDateTime."'>";

		}
		//$sqlSearch .= " and SYSLog.EntityID = ".$this->userInfo->EntityID;
			
		
		$this->view->chkActive = "";
		$this->view->chkNotActive = "";
		if (!$this->view->Active && !is_null($this->view->Active))
		{
			$this->view->chkActive = "";
			$this->view->chkNotActive = "checked";
		}else
		{
			$this->view->chkActive = "checked";
			$this->view->chkNotActive = "";
		
		}
		$sysHelper->setFetchMode(Zend_Db::FETCH_NUM);

		$arrDesignationLevel = $sysHelper->getLog($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
		
		$dataDesignationLevel = $arrDesignationLevel[1];
		$exportReport = new Venz_App_Report_Excel(array('exportsql'=> $exportSql, 'hiddenparam'=>'<input type=hidden name="Search" value="Search">'));	
		
		
		$sessionDesignationLevel = new Zend_Session_Namespace('sessionDesignationLevel');
		$sessionDesignationLevel->numCounter = $recordsPerPage * ($showPage-1);
		function format_counter($colnum, $rowdata)
		{
			$sessionDesignationLevel = new Zend_Session_Namespace('sessionDesignationLevel');
			$sessionDesignationLevel->numCounter++;
			return $sessionDesignationLevel->numCounter;
		}
		function format_postdata($colnum, $rowdata)
		{
			return ($rowdata[7] ? "<a href='/admin/system/ajaxgetlog/ID/".$rowdata[9]."?width=450' class='jTip' id='".$rowdata[9]."' name='Post Data'><img src='/images/icons/IconApproved.gif'></a>" : "");
		}					
		$arrHeader = array ('', $this->translate->_('IP'), $this->translate->_('Name'), $this->translate->_('Username'), $this->translate->_('Role'), $this->translate->_('Log Time')
		, $this->translate->_('Module'), $this->translate->_('Controller'), $this->translate->_('Action'), $this->translate->_('Post Data'), $this->translate->_('Get Data'));
		$displayTable = new Venz_App_Display_Table(
			array (
				 'data' => $dataDesignationLevel,
				 'headings' => $arrHeader,
				 'format' 		=> array('{format_counter}','%10%','%0%','%1%','%2%','%3%','%4%','%5%','%6%','{format_postdata}','%8%'),					 
				 'sort_column' 	=> array('', 'SYSLog.IP','ACLUsers.Name','SYSLog.username','SYSLog.role','SYSLog.logtime','SYSLog.zendmodule', 'SYSLog.zendcontroller', 'SYSLog.zendaction', 'SYSLog.postdata', 'SYSLog.getdata', 'SYSLog.IP'),
				 'alllen' 		=> $arrDesignationLevel[0],
				 'title'		=> $this->translate->_('Activity Log'),					 
				 'aligndata' 	=> 'LLLLLLLLLCC',
				 'pagelen' 		=> $recordsPerPage,
				 'numcols' 		=> sizeof($arrHeader),
				 'tablewidth' => "1000px",
				 'sortby' => $sortby,
				 'ascdesc' => $ascdesc,
				 'hiddenparam' => $strHiddenSearch,
			)
		);
		$this->view->content_log = $displayTable->render();

	
	}


	

}

