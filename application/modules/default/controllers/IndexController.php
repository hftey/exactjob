<?php

class IndexController extends Venz_Zend_Controller_Action {

	private $_PathJobDoc = 'D:/Doc/ExactJobDoc';

	public function init()
	{
		parent::init("private");

		
	}
	
	private function _salesForm($SalesData = NULL)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$systemSetting = new Zend_Session_Namespace('systemSetting');	
		
		$Request = $this->getRequest();	
		$SID = $Request->getParam('SID');	
		
		$libDb = new Venz_App_Db_Table();
		$dispFormat = new Venz_App_Display_Format();
		
		$optionSalesCurrency = $libDb->getSystemOptions("arrCurrency", $SalesData['SalesCurrency']);
		$optionSalesTerms = $libDb->getSystemOptions("arrTerms", $SalesData['SalesTerms']);
		$optionSalesPersonID = $libDb->getTableOptions("ACLUsers", "Name", "ID", $SalesData['SalesPersonID']); 
			
		$JobID = $SalesData['JobID'];
		$JobSalesID = $SalesData['ID'];
		
		
		$SalesData[CustomerPOReceivedDate] = $dispFormat->format_date_db_to_simple($SalesData['CustomerPOReceivedDate']);
		$SalesData[SalesExpDate] = $dispFormat->format_date_db_to_simple($SalesData['SalesExpDate']);
		$SalesData[SalesReadyDate] = $dispFormat->format_date_db_to_simple($SalesData['SalesReadyDate']);
		$SalesData[DrawingApprovedDate] = $dispFormat->format_date_db_to_simple($SalesData['DrawingApprovedDate']);

		$arrDocESPOAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='ESPODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID."  AND JobSalesID=".$JobSalesID." ORDER BY DateSubmitted DESC");
		$listDocESPO = ""; $DocESPOCSS = "";
		foreach ($arrDocESPOAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listDocESPO .= $display;
			$DocESPOCSS = "md-input-success";
			
		}
		
		$arrDocCustPOAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='CustPODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID."  AND JobSalesID=".$JobSalesID." ORDER BY DateSubmitted DESC");
		$listDocCustPO = "";$DocCustPOCSS = "";
		foreach ($arrDocCustPOAll as $arrUploads)
		{
			$display = " <a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listDocCustPO .= $display;
			$DocCustPOCSS = "md-input-success";
		}
		
		
		$arrDocInspReportAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='InspReportDoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobSalesID=".$JobSalesID." ORDER BY DateSubmitted DESC");
		$listDocInspReport = "";$DocInspReportCSS = "";
		foreach ($arrDocInspReportAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listDocInspReport .= $display;
			$DocInspReportCSS = "md-input-success";
		}
		
		$arrDocSOAAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='SOADoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobSalesID=".$JobSalesID." ORDER BY DateSubmitted DESC");
		$listDocSOA = "";$DocSOACSS = "";
		foreach ($arrDocSOAAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listDocSOA .= $display;
			$DocSOACSS = "md-input-success";
		}
		
		$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='ExactInvoiceDoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobSalesID=".$JobSalesID." ORDER BY DateSubmitted DESC");
		$listDocExactInvoice = "";$DocExactInvoiceCSS = "";
		foreach ($arrDocAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listDocExactInvoice .= $display;
			$DocExactInvoiceCSS = "md-input-success";
		}
		

		$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='ExactDODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobSalesID=".$JobSalesID." ORDER BY DateSubmitted DESC");
		$listDocExactDO = "";$DocExactDOCSS = "";
		foreach ($arrDocAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listDocExactDO .= $display;
			$DocExactDOCSS = "md-input-success";
		}
		
		$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='ESDODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobSalesID=".$JobSalesID." ORDER BY DateSubmitted DESC");
		$listDocESDO = "";$DocESDOCSS = "";
		foreach ($arrDocAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listDocESDO .= $display;
			$DocESDOCSS = "md-input-success";
		}
		
		$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='ServiceReportDoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobSalesID=".$JobSalesID." ORDER BY DateSubmitted DESC");
		$thislistDocServiceReport = "";$DocServiceReportCSS = "";
		foreach ($arrDocAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listDocServiceReport .= $display;
			$DocServiceReportCSS = "md-input-success";
		}	
		
		
		$strTerms = $SalesData[SalesTerms];
		$strSalesPrice = "";
		if ($SalesData[SalesPrice])
			$strSalesPrice = number_format($SalesData[SalesPrice], 2, ".", ",");
		
		
		
		if ($SID == $JobSalesID)
		{
			$butExpand = "<img class='clsSalesExpand' style='cursor: pointer' status='show' src='/images/icons/IconExpandHide.png'>";
			$dispExpand = "display: block";
		}
		else{
			$butExpand = "<img class='clsSalesExpand' style='cursor: pointer' status='hide' src='/images/icons/IconExpand.png'>";
			$dispExpand = "display: none";
			
		}
		
		$defaultClass = "uk-width-medium-2-5";
		$defaultDisplay = "display: none";
		if ($PurchaseData[PurchaseCurrency] != "RM")
		{
			$defaultClass = "uk-width-medium-1-5";
			$defaultDisplay = "display: block";
			
		}
		
		
		$strButtons = "";
		if ($this->userInfo->ACLRole == "AdminSystem" || $this->userInfo->ACLRole == "Admin")
		{
			$strButtons =<<<END
			<div class="uk-form-row">
			<div class="uk-grid">
				<div class="uk-width-medium-1-1">
					<div class="md-input-wrapper md-input-wrapper-success">
						
							<input type=submit name='SaveSales' id='SaveSales' value='Save'>
							<input type=submit name='DeleteSales' id='DeleteSales' Class='clsDeleteSales' value='Delete'>
							<input type="hidden" id="JobID" name="JobID" value="$SalesData[JobID]">
							<input type="hidden" id="JobSalesID" name="JobSalesID" value="$SalesData[ID]">
					</div>
				</div>
				
			</div>
		</div>
END;
		}		
		
		
	
			
		
	$CustomerPO = str_replace("|", ", ", substr($SalesData[CustomerPO], 1));
			
		$content = <<<END
<form enctype="multipart/form-data"  action='/default/index/index/#tabs1' method=POST  data-ajax="false">

<div class="md-card">
	<div class="md-card-toolbar">
		<div style='float: right; margin-top: 5px;'>$butExpand</div> 
		<div style='float: right; margin-top: 5px; margin-right: 15px;'><B>$strTerms</B></div> 
		<div style='float: right; margin-top: 5px; margin-right: 5px;'><B>$strSalesPrice</B></div> 
		<div style='float: right; margin-top: 5px; margin-right: 0px;'><B>$SalesData[SalesCurrency]</B></div> 
		<div style='float: right; margin-top: 5px; margin-right: 15px;'>$SupplierCode</div> 
		<div style='float: right; margin-top: 5px; margin-right: 3px;'>$PurchaseData[SupplierName]</div> 
		
		<div style='float: left; margin-top: 5px; margin-right: 15px;'>Sales Details:<BR><B>$CustomerPO</B></div> 
	</div>
	<div class="md-card-content S1" style='$dispExpand'>
	
	<div class="uk-form-row">
		<div class="uk-grid">
			<div class="uk-width-medium-2-5" style='-webkit-transition: width 1s; transition: width 1s;'>
				<div class="md-input-wrapper md-input-wrapper-success"><label>Currency</label>
					<input type="text" id="SalesCurrency" name="SalesCurrency" class="md-input SalesCurrency" value="$SalesData[SalesCurrency]"><span class="md-input-bar"></span>
					<input type="hidden" id="SalesCurrencyID" name="SalesCurrencyID" value="$SalesData[SalesCurrencyID]">
				</div>
				
			</div>
			<div class="uk-width-medium-1-5 clsCurrencyRate" style='display:none;'>
				<div class="md-input-wrapper md-input-wrapper-success"><label>Rate</label>
					<input type="text" id="SalesPriceExchangeRate" name="SalesPriceExchangeRate" class="md-input" value="$SalesData[SalesPriceExchangeRate]">
					<span class="md-input-bar"></span></div>
			</div>
			<div class="uk-width-medium-2-5">
				<div class="md-input-wrapper md-input-wrapper-success"><label>Selling Price</label>
					<input type="text" id="SalesPrice" name="SalesPrice" class="md-input" value="$SalesData[SalesPrice]">
					<span class="md-input-bar"></span></div>
			</div>
			<div class="uk-width-medium-1-5">
				<div class="md-input-wrapper md-input-wrapper-success"><label>Terms</label>
					<input type="text" id="SalesTerms" name="SalesTerms" class="md-input" value="$SalesData[SalesTerms]"><span class="md-input-bar"></span>
					<input type="hidden" id="SalesTermsID" name="SalesTermsID" value="$SalesData[SalesTermsID]">
				</div>
			</div>
		   
		</div>
	</div>
	
	<div class="uk-form-row">
			<div class="uk-grid">
				<div class="uk-width-medium-1-3">
					<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Cust. PO Received* </label>
						<input type="text"  name="CustomerPOReceivedDate" class="md-input CustomerPOReceivedDate" value="$SalesData[CustomerPOReceivedDate]"><span class="md-input-bar"></span>
					</div>
				</div>
				<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Cust Expected Date</label>
					<input type="text"  name="SalesExpDate" class="md-input SalesExpDate" value="$SalesData[SalesExpDate]"><span class="md-input-bar"></span></div>
					
				</div>
				<div class="uk-width-medium-1-3">
					<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Goods Ready Date</label>
						<input type="text" name="SalesReadyDate" class="md-input SalesReadyDate" value="$SalesData[SalesReadyDate]"><span class="md-input-bar"></span></div>
				</div>
				
			</div>
		</div>
		
	
	<div class="uk-form-row">
		<div class="uk-grid">
		
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> EOG & STSB PO</label>
					<div class="md-input md-input-doc $DocESPOCSS" style='height: 100%;width: 100%; display: inline-flex;'>
						<div style='display: inline-block'><img id='idUploadDoc' DocType="ESPODoc" JobID=$JobID  JobSalesID=$SalesData[ID]  style='cursor: pointer' src='/images/icons/IconUpload2.png'> &raquo; </div>
						
						<div style='display: inline-block'>$listDocESPO </div>
						
					</div><span class="md-input-bar"></span>
				</div>
			</div>
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> Customer PO</label>
					<div class="md-input md-input-doc $DocCustPOCSS" style='height: 100%;width: 100%; display: inline-flex; '>
						<div style='display: inline-block;'><img id='idUploadDoc' DocType="CustPODoc" JobID=$JobID  JobSalesID=$SalesData[ID]  style='cursor: pointer' src='/images/icons/IconUpload2.png'> 
						&raquo;</div>
						<div style='display: inline-block'>$listDocCustPO</div>
					</div><span class="md-input-bar"></span>
				</div>
			</div>
		
			
		   
		</div>
	</div>
	
<!--	<div class="uk-form-row">
		<div class="uk-grid">
			<div class="uk-width-medium-1-2">
				<div class="md-input-wrapper md-input-wrapper-success"><label>Inspection Report No.</label>
					<input type="text" id="SalesInspReportNo" name="SalesInspReportNo" class="md-input" value="<?=$this->SalesInspReportNo?>"><span class="md-input-bar"></span></div>
				
			</div>
			<div class="uk-width-medium-1-2">
				<div class="md-input-wrapper md-input-wrapper-success"><label>S.O. Acknowledgement</label>
					<input type="text" id="SalesOrderAckNo" name="SalesOrderAckNo" class="md-input" value="<?=$this->SalesOrderAckNo?>"><span class="md-input-bar"></span></div>
			</div>
		   
		</div>
	</div>
	-->
	<div class="uk-form-row">
		<div class="uk-grid">
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> Inspection Report</label>
					<div class="md-input md-input-doc $DocInspReportCSS" style='height: 100%;width: 100%; display: inline-flex;'>
						<div style='display: inline-block'><img id='idUploadDoc' DocType="InspReportDoc" JobID=$JobID  JobSalesID=$SalesData[ID] style='cursor: pointer' src='/images/icons/IconUpload2.png'> 
						&raquo;</div>
						<div style='display: inline-block'>$listDocInspReport</div>
					</div><span class="md-input-bar"></span>
				</div>
			</div>
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> S.O. Acknowledgements</label>
					<div class="md-input md-input-doc $DocSOACSS" style='height: 100%;width: 100%; display: inline-flex;'>
						<div style='display: inline-block'><img id='idUploadDoc' DocType="SOADoc" JobID=$JobID  JobSalesID=$SalesData[ID] style='cursor: pointer' src='/images/icons/IconUpload2.png'> 
						&raquo;</div>
						<div style='display: inline-block'>$listDocSOA</div>
					</div><span class="md-input-bar"></span>
				</div>
			</div>
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> Service Report</label>
					<div class="md-input md-input-doc $DocServiceReportCSS" style='height: 100%;width: 100%; display: inline-flex;'>
						<div style='display: inline-block'><img id='idUploadDoc' DocType="ServiceReportDoc" JobID=$JobID  JobSalesID=$SalesData[ID] style='cursor: pointer' src='/images/icons/IconUpload2.png'> 
						&raquo;</div>
						<div style='display: inline-block'>$listDocServiceReport</div>
					</div><span class="md-input-bar"></span>
				</div>
			</div>
		   
		</div>
	</div>
	
	<!--
	<div class="uk-form-row">
		<div class="uk-grid">
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success"><label>Exact Invoice No.</label>
					<input type="text" id="SalesInvoiceNo" name="SalesInvoiceNo" class="md-input" value="<?=$this->SalesInvoiceNo?>"><span class="md-input-bar"></span></div>
				
			</div>
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success"><label>Exact DO No.</label>
					<input type="text" id="SalesDO" name="SalesDO" class="md-input" value="<?=$this->SalesDO?>"><span class="md-input-bar"></span></div>
			</div>
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success"><label>EOG & STSB DO No.</label>
					<input type="text" id="EOGSTSBDO" name="EOGSTSBDO" class="md-input" value="<?=$this->EOGSTSBDO?>"><span class="md-input-bar"></span></div>
				
			</div>
		   
		</div>
	</div>
	-->
	
	<div class="uk-form-row">
		<div class="uk-grid">
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> Exact Invoice</label>
					<div class="md-input md-input-doc $DocExactInvoiceCSS" style='height: 100%;width: 100%; display: inline-flex;'>
						<div style='display: inline-block'><img id='idUploadDoc' DocType="ExactInvoiceDoc" JobID=$JobID  JobSalesID=$SalesData[ID] style='cursor: pointer' src='/images/icons/IconUpload2.png'> 
						&raquo;</div>
						<div style='display: inline-block'>$listDocExactInvoice</div>
					</div><span class="md-input-bar"></span>
				</div>
			</div>
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> Exact DO</label>
					<div class="md-input md-input-doc $DocExactDOCSS" style='height: 100%;width: 100%; display: inline-flex;'>
						<div style='display: inline-block'><img id='idUploadDoc' DocType="ExactDODoc" JobID=$JobID  JobSalesID=$SalesData[ID] style='cursor: pointer' src='/images/icons/IconUpload2.png'> 
						&raquo;</div>
						<div style='display: inline-block'>$listDocExactDO</div>
					</div><span class="md-input-bar"></span>
				</div>
			</div>
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> EOG & STSB DO</label>
					<div class="md-input md-input-doc $DocESDOCSS" style='height: 100%;width: 100%; display: inline-flex;'>
						<div style='display: inline-block'><img id='idUploadDoc' DocType="ESDODoc" JobID=$JobID  JobSalesID=$SalesData[ID] style='cursor: pointer' src='/images/icons/IconUpload2.png'> 
						&raquo;</div>
						<div style='display: inline-block'>$listDocESDO</div>
					</div><span class="md-input-bar"></span>
				</div>
			</div>
		   
		</div>
	</div>
	
	<div class="uk-form-row">
		<div class="uk-grid">
			<div class="uk-width-medium-1-3">
				<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Drawing Approved Date</label>
					<input type="text" name="DrawingApprovedDate" class="md-input DrawingApprovedDate" value="$SalesData[DrawingApprovedDate]"><span class="md-input-bar"></span></div>
			</div>
			<div class="uk-width-medium-2-3">
				<div class="md-input-wrapper md-input-wrapper-success"><label>Sales Person</label>
					<select class="md-input" name='SalesPersonID' id='SalesPersonID'>
						<option value=''>
						$optionSalesPersonID
					</select>
					<span class="md-input-bar"></span></div>
				
			</div>
			
		</div>
	</div>	
	<div class="uk-form-row">
		<div class="uk-grid">
			<div class="uk-width-medium-1-1">
				<div class="md-input-wrapper md-input-wrapper-success"><label>Remarks</label>
					<input type="text" id="Remarks" name="Remarks" class="md-input" value="$SalesData[Remarks]"><span class="md-input-bar"></span></div>
			</div>
			
		</div>
	</div>
	$strButtons								
	
	</div>
</div>
</form>	
<BR>
END;
		return $content;
	}
	
	
	private function _purchaseForm($PurchaseData = NULL)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$systemSetting = new Zend_Session_Namespace('systemSetting');	
		
		$Request = $this->getRequest();	
		$PID = $Request->getParam('PID');	
		
		$libDb = new Venz_App_Db_Table();
		$dispFormat = new Venz_App_Display_Format();
		$optionPurchaseCurrency = $libDb->getSystemOptions("arrCurrency", $PurchaseData[PurchaseCurrency]);
		$optionPurchaseTerms = $libDb->getSystemOptions("arrTerms", $PurchaseData[PurchaseTerms]);
		$JobID = $PurchaseData['JobID'];
		$JobPurchaseID = $PurchaseData['ID'];
		$PurchaseData[PODate] = $dispFormat->format_date_db_to_simple($PurchaseData['PODate']);
		$PurchaseData[POFaxedDate] = $dispFormat->format_date_db_to_simple($PurchaseData['POFaxedDate']);
		$PurchaseData[PurchaseShippingDate] = $dispFormat->format_date_db_to_simple($PurchaseData['PurchaseShippingDate']);
		$PurchaseData[PurchaseShippingActualDate] = $dispFormat->format_date_db_to_simple($PurchaseData['PurchaseShippingActualDate']);
		$PurchaseData[PurchasePaymentDate] = $dispFormat->format_date_db_to_simple($PurchaseData['PurchasePaymentDate']);
		$PurchaseData[DeliveryReceivedDate] = $dispFormat->format_date_db_to_simple($PurchaseData['DeliveryReceivedDate']);
		$strTerms = $PurchaseData[PurchaseTerms];
		$strPurchasePrice = "";
		if ($PurchaseData[PurchasePrice])
			$strPurchasePrice = number_format($PurchaseData[PurchasePrice], 2, ".", ",");
		$SupplierCode = "";
		if ($PurchaseData[SupplierCode])
			$SupplierCode = "(".$PurchaseData[SupplierCode].")";
		
		//$strPurchasePrice = $PurchaseData[PurchasePrice];
		
		$arrPurchaseDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='PurchasePODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobPurchaseID=".$JobPurchaseID." ORDER BY DateSubmitted DESC");
		$listDocPurchasePODoc = ""; $DocPurchasePODocCSS = "";
		foreach ($arrPurchaseDocAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listDocPurchasePODoc .= $display;
			$DocPurchasePODocCSS = "md-input-success";
			
		}	

		$arrPurchaseDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='PurchaseAckNODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobPurchaseID=".$JobPurchaseID." ORDER BY DateSubmitted DESC");
		$listPurchaseAckNODoc = ""; $DocPurchaseAckNOCSS = "";
		foreach ($arrPurchaseDocAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listPurchaseAckNODoc .= $display;
			$DocPurchaseAckNOCSS = "md-input-success";
			
		}	

		$arrPurchaseDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='PurchaseInvoiceNoDoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobPurchaseID=".$JobPurchaseID." ORDER BY DateSubmitted DESC");
		$listPurchaseInvoiceNo = ""; $DocPurchaseInvoiceNoCSS = "";
		foreach ($arrPurchaseDocAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
				"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			$listPurchaseInvoiceNo .= $display;
			$DocPurchaseInvoiceNoCSS = "md-input-success";
			
		}		
		
		if ($PID == $JobPurchaseID)
		{
			$butExpand = "<img class='clsPurchaseExpand' style='cursor: pointer' status='show' src='/images/icons/IconExpandHide.png'>";
			$dispExpand = "display: block";
		}
		else{
			$butExpand = "<img class='clsPurchaseExpand' style='cursor: pointer' status='hide' src='/images/icons/IconExpand.png'>";
			$dispExpand = "display: none";
			
		}
		
		$strDelivery = "";
		$arrDeliveryAll = $db->fetchAll("SELECT JobPurchaseDelivery.* FROM JobPurchaseDelivery WHERE JobPurchaseDelivery.JobPurchaseID=".$JobPurchaseID);
		foreach ($arrDeliveryAll as $arrDelivery)
		{
			
			$strButtons = "";
			if ($this->userInfo->ACLRole == "AdminSystem" || $this->userInfo->ACLRole == "Admin")
			{
				$strButtons =<<<END
				<div class="uk-width-medium-1-4">
					<div class="md-input-wrapper md-input-wrapper-success">
						<input type=submit name='SaveDelivery' ID='SaveDelivery' value="Update">
						<input type=submit name='DeleteDelivery' ID='DeleteDelivery' class="clsDeleteDelivery" value="Delete">
						<input type="hidden" id="JobPurchaseDeliveryID" name="JobPurchaseDeliveryID" value="$arrDelivery[ID]">
		
					<span class="md-input-bar"></span></div>
					
				</div>
END;
			}
			
			$arrDelivery[DeliveryReceivedDate] = $dispFormat->format_date_db_to_simple($arrDelivery['DeliveryReceivedDate']);
			
				$strDelivery .=<<<END
<form enctype="multipart/form-data"  action='/default/index/index/#tabs1' method=POST  data-ajax="false">
		<input type="hidden" id="JobID" name="JobID" value="$PurchaseData[JobID]">
		<input type="hidden" id="JobPurchaseID" name="JobPurchaseID" value="$PurchaseData[ID]">
		

		<div class="uk-form-row">
			<div class="uk-grid">
				<div class="uk-width-medium-1-4">
					<div class="md-input-wrapper md-input-wrapper-success"><label>AWB No</label>
						<input type="text" id="DeliveryAWB" name="DeliveryAWB" class="md-input" value="$arrDelivery[DeliveryAWB]"><span class="md-input-bar"></span></div>
					
				</div>
				<div class="uk-width-medium-1-4">
					<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Good Received</label>
						<input type="text" name="DeliveryReceivedDate" class="md-input DeliveryReceivedDate" value="$arrDelivery[DeliveryReceivedDate]"><span class="md-input-bar"></span></div>
				</div>
				<div class="uk-width-medium-1-4">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Duty & Tax (RM)</label>
						<input type="text" id="DutyTax" name="DutyTax" class="md-input" value="$arrDelivery[DutyTax]"><span class="md-input-bar"></span></div>
					
				</div>
				<div class="uk-width-medium-1-4">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Freight Cost (RM)</label>
						<input type="text" id="FreightCost" name="FreightCost" class="md-input" value="$arrDelivery[FreightCost]"><span class="md-input-bar"></span></div>
				</div>
				
			</div>
		</div>
		
		<div class="uk-form-row">
			<div class="uk-grid">
				<div class="uk-width-medium-3-4">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Remarks</label>
						<input type="text" type="text" id="Remarks" name="Remarks" value="$arrDelivery[Remarks]" class="md-input"><span class="md-input-bar"></span></div>
					
				</div>
				$strButtons
			</div>
		</div>
	
		</form>
		<HR>
END;
		}

		
		$strButtons = "";
		if ($this->userInfo->ACLRole == "AdminSystem" || $this->userInfo->ACLRole == "Admin")
		{
			$strButtons =<<<END
			<div class="uk-form-row">
			<div class="uk-grid">
				<div class="uk-width-medium-1-1">
					<div class="md-input-wrapper md-input-wrapper-success">
						
							<input type=submit name='SavePurchase' id='SavePurchase' value='Save'>
							<input type=submit name='DeletePurchase' id='DeletePurchase' Class='clsDeletePurchase' value='Delete'>
							<input type="hidden" id="JobID" name="JobID" value="$PurchaseData[JobID]">
							<input type="hidden" id="JobPurchaseID" name="JobPurchaseID" value="$PurchaseData[ID]">
					</div>
				</div>
				
			</div>
		</div>
END;


			$strButtonsCreateDelivery =<<<END
				<div class="uk-width-medium-1-4">
				<div class="md-input-wrapper md-input-wrapper-success">
					<input type=submit name='CreateDelivery' ID='CreateDelivery' value="Create">
				<span class="md-input-bar"></span></div>
				
			</div>
END;

		}		
		
		
		
			
		
	$PONo = str_replace("|", ", ", substr($PurchaseData[PONo], 1));
			
		$content = <<<END
<form enctype="multipart/form-data"  action='/default/index/index/#tabs1' method=POST  data-ajax="false">
<div class="md-card">
		<div class="md-card-toolbar" style='height: 100%;'>
			
			<div style='float: right; margin-top: 5px;'>$butExpand</div> 
			<div style='float: right; margin-top: 5px; margin-right: 15px;'><B>$strTerms</B></div> 
			<div style='float: right; margin-top: 5px; margin-right: 5px;'><B>$strPurchasePrice</B></div> 
			<div style='float: right; margin-top: 5px; margin-right: 0px;'><B>$PurchaseData[PurchaseCurrency]</B></div> 
			<div style='float: right; margin-top: 5px; margin-right: 15px;'>$PurchaseData[SupplierName] $SupplierCode</div> 
			
			<div style='float: left; margin-top: 5px; margin-right: 15px;'>Purchasing PO No:<BR><B>$PONo</B></div> 
			<div style='clear:both'></div>
		</div>
		<div class="md-card-content P1" style='$dispExpand'>
		<div class="uk-form-row">
			<div class="uk-grid">
				<div class="uk-width-medium-1-5">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Currency</label>
						<input type="text" id="PurchaseCurrency" name="PurchaseCurrency" class="md-input clsPurchaseCurrency" value="$PurchaseData[PurchaseCurrency]"><span class="md-input-bar"></span>
						<input type="hidden" id="PurchaseCurrencyID" name="PurchaseCurrencyID" class="" value="$PurchaseData[PurchaseCurrencyID]">
					</div>
				</div>
				<div class="uk-width-medium-1-5 clsPurchasePriceExchangeRateDiv">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Rate</label>
						<input type="text" id="PurchasePriceExchangeRate" name="PurchasePriceExchangeRate" class="md-input clsPurchasePriceExchangeRate" value="$PurchaseData[PurchasePriceExchangeRate]">
						<span class="md-input-bar"></span></div>
				</div>
				<div class="uk-width-medium-2-5">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Buying Price</label>
						<input type="text" id="PurchasePrice" name="PurchasePrice" class="md-input" value="$PurchaseData[PurchasePrice]">
						<span class="md-input-bar"></span></div>
				</div>
				<div class="uk-width-medium-1-5">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Terms</label>
						<input type="text" id="PurchaseTerms" name="PurchaseTerms" class="md-input clsPurchaseTerms" value="$PurchaseData[PurchaseTerms]"><span class="md-input-bar"></span>
						<input type="hidden" id="PurchaseTermsID" name="PurchaseTermsID" value="$PurchaseData[PurchaseTermsID]">
					</div>
				</div>
			   
			</div>
		</div>
		<div class="uk-form-row">
			<div class="uk-grid">
				
				<div class="uk-width-medium-4-5">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Principle / Suppliers</label>
						<input type="text" id="SupplierName" name="SupplierName" class="md-input SupplierName" value="$PurchaseData[SupplierName]"><span class="md-input-bar"></span>
						<input type="hidden" id="SupplierID" name="SupplierID" Class='clsSupplierID' value="$PurchaseData[SupplierID]">
					</div>
				</div>
				<div class="uk-width-medium-1-5">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Code</label>
						<input type="text" id="SupplierCode" name="SupplierCode" class="md-input" value="$PurchaseData[SupplierCode]"><span class="md-input-bar"></span></div>
					
				</div>
			   
			</div>
		</div>
		
		<div class="uk-form-row">
			<div class="uk-grid">
			<!--	<div class="uk-width-medium-1-3">
					<div class="md-input-wrapper md-input-wrapper-success"><label>PO No.</label>
						<input type="text" id="PONo" name="PONo" class="md-input" value="$PurchaseData[PONo]"><span class="md-input-bar"></span></div>
				</div>
			-->
				<div class="uk-width-medium-2-6">
					<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> PO</label>
						<div class="md-input md-input-doc $DocPurchasePODocCSS" style='height: 100%;width: 100%; display: inline-flex;'>
							<div style='display: inline-block'><img id='idUploadDoc' DocType="PurchasePODoc" JobID=$PurchaseData[JobID] JobPurchaseID=$PurchaseData[ID] style='cursor: pointer' src='/images/icons/IconUpload2.png'> 
							&raquo;</div>
							<div style='display: inline-block'>$listDocPurchasePODoc</div>
						</div><span class="md-input-bar"></span>
					</div>
				</div>
				<div class="uk-width-medium-1-3">
					<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Exact PO Date</label>
						<input type="text" name="PODate" class="md-input PODate" value="$PurchaseData[PODate]"><span class="md-input-bar"></span></div>
					
				</div>
				<div class="uk-width-medium-1-3">
					<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> PO Faxed Out Date</label>
						<input type="text" name="POFaxedDate" class="md-input POFaxedDate" value="$PurchaseData[POFaxedDate]"><span class="md-input-bar"></span></div>
				</div>
			   
			</div>
		</div>

		
		<!--
		<div class="uk-form-row">
			<div class="uk-grid">
				<div class="uk-width-medium-1-2">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Order Ack.</label>
						<input type="text" id="PurchaseAckNO" name="PurchaseAckNO" class="md-input" value="$PurchaseData[PurchaseAckNO]"><span class="md-input-bar"></span></div>
					
				</div>
				<div class="uk-width-medium-1-2">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Invoice No</label>
						<input type="text" id="PurchaseInvoiceNo" name="PurchaseInvoiceNo" class="md-input" value="$PurchaseData[PurchaseInvoiceNo]"><span class="md-input-bar"></span></div>
					
				</div>
			   
			</div>
		</div>	
		-->
		<div class="uk-form-row">
			<div class="uk-grid">
				<div class="uk-width-medium-1-2">
					<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> Order Ack.</label>
						<div class="md-input md-input-doc $DocPurchaseAckNOCSS" style='height: 100%;width: 100%;display: inline-flex;'>
							<div style='display: inline-block'><img id='idUploadDoc' DocType="PurchaseAckNODoc" JobID=$PurchaseData[JobID] JobPurchaseID=$PurchaseData[ID] style='cursor: pointer' src='/images/icons/IconUpload2.png'> 
							&raquo;</div>
							<div style='display: inline-block'>$listPurchaseAckNODoc</div>
						</div><span class="md-input-bar"></span>
					</div>
				</div>
				<div class="uk-width-medium-1-2">
					<div class="md-input-wrapper md-input-wrapper-success md-input-filled"><label><i class="material-icons">&#xE89C;</i> Invoice</label>
						<div class="md-input md-input-doc $DocPurchaseInvoiceNoCSS" style='height: 100%;width: 100%;display: inline-flex;'>
							<div style='display: inline-block'><img id='idUploadDoc' DocType="PurchaseInvoiceNoDoc" JobID=$PurchaseData[JobID] JobPurchaseID=$PurchaseData[ID] style='cursor: pointer' src='/images/icons/IconUpload2.png'> 
							&raquo;</div>
							<div style='display: inline-block'>$listPurchaseInvoiceNo</div>
						</div><span class="md-input-bar"></span>
					</div>
				</div>
			   
			</div>
		</div>		
		<div class="uk-form-row">
			<div class="uk-grid">
				
				<div class="uk-width-medium-1-3">
					<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Scheduled Shipping</label>
						<input type="text" name="PurchaseShippingDate" class="md-input PurchaseShippingDate" value="$PurchaseData[PurchaseShippingDate]"><span class="md-input-bar"></span></div>
				</div>
				<div class="uk-width-medium-1-3">
					<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Actual Shipping</label>
						<input type="text" name="PurchaseShippingActualDate" class="md-input PurchaseShippingActualDate" value="$PurchaseData[PurchaseShippingActualDate]"><span class="md-input-bar"></span></div>
					
				</div>
				<div class="uk-width-medium-1-3">
					<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Payment Date</label>
						<input type="text" name="PurchasePaymentDate" class="md-input PurchasePaymentDate" value="$PurchaseData[PurchasePaymentDate]"><span class="md-input-bar"></span></div>
				</div>
				
			</div>
		</div>	
		$strButtons		
		</form>	
		<BR>
		
		
		<div class="uk-form-row">
			<div class="md-card">
				<div class="md-card-toolbar" style='background: rgba(80,183,220, 0.1);'>
					 <h3 class="md-card-toolbar-heading-text">
						Delivery Details
					</h3>
				</div>
				
				
				<div class="md-card-content">
				
				$strDelivery
				
				<form enctype="multipart/form-data"  action='/default/index/index/#tabs1' method=POST  data-ajax="false">
				<input type="hidden" id="JobID" name="JobID" value="$PurchaseData[JobID]">
				<input type="hidden" id="JobPurchaseID" name="JobPurchaseID" value="$PurchaseData[ID]">
					<div class="uk-form-row">
						<div class="uk-grid">
							<div class="uk-width-medium-1-4">
								<div class="md-input-wrapper md-input-wrapper-success"><label>AWB No</label>
									<input type="text" id="DeliveryAWB" name="DeliveryAWB" class="md-input" value="$PurchaseData[DeliveryAWB]"><span class="md-input-bar"></span></div>
								
							</div>
							<div class="uk-width-medium-1-4">
								<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Good Received</label>
									<input type="text" name="DeliveryReceivedDate" class="md-input DeliveryReceivedDate" value="$PurchaseData[DeliveryReceivedDate]"><span class="md-input-bar"></span></div>
							</div>
							<div class="uk-width-medium-1-4">
								<div class="md-input-wrapper md-input-wrapper-success"><label>Duty & Tax (RM)</label>
									<input type="text" id="DutyTax" name="DutyTax" class="md-input" value="$PurchaseData[DutyTax]"><span class="md-input-bar"></span></div>
								
							</div>
							<div class="uk-width-medium-1-4">
								<div class="md-input-wrapper md-input-wrapper-success"><label>Freight Cost (RM)</label>
									<input type="text" id="FreightCost" name="FreightCost" class="md-input" value="$PurchaseData[FreightCost]"><span class="md-input-bar"></span></div>
							</div>
							
						</div>
					</div>
					
					<div class="uk-form-row">
						<div class="uk-grid">
							<div class="uk-width-medium-3-4">
								<div class="md-input-wrapper md-input-wrapper-success"><label>Remarks</label>
									<input type="text" type="text" id="Remarks" name="Remarks" value="$PurchaseData[Remarks]" class="md-input"><span class="md-input-bar"></span></div>
								
							</div>
							$strButtonsCreateDelivery
						</div>
					</div>
				
				</div>
			</div>
		
		</div>	
		</form>	
		
		
		
		</div>
	</div>	

<BR>
END;
		return $content;
	}
	
	
	
	public function ajaxGetDocAction()
	{
		$Request = $this->getRequest();	
		$strSearch = $Request->getParam('query');
		$JobID = $Request->getParam('JobID');
		$DocType = $Request->getParam('DocType');
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrAll = $db->fetchAll("SELECT Name, ID FROM JobDocuments WHERE JobID = '".$JobID."' AND DocType='".$DocType."' AND Name LIKE '%".$strSearch."%'");
		foreach ($arrAll as $arrData)
		{
			$arrReturn[] = $arrData['Name'];
		}
		echo json_encode($arrReturn);
		
		exit();
	}
	
	public function ajaxGetDocDetailsAction()
	{
		$Request = $this->getRequest();	
		$strSearch = $Request->getParam('query');
		$JobID = $Request->getParam('JobID');
		$DocType = $Request->getParam('DocType');
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrData = $db->fetchRow("SELECT Name, ID FROM JobDocuments WHERE JobID = '".$JobID."' AND DocType='".$DocType."' AND Name LIKE '%".$strSearch."%'");
		if ($arrData){
			echo json_encode($arrData);
		}
		
		exit();
	}
	
	public function ajaxGetItemsAction()
	{
		$Request = $this->getRequest();	
		$Items = $Request->getParam('query');
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrAll = $db->fetchAll("SELECT Items FROM Job WHERE Job.Items LIKE '%".$Items."%' GROUP BY LOWER(TRIM(Job.Items)) LIMIT 15");
		foreach ($arrAll as $arrData)
		{
			$arrReturn[] = trim($arrData['Items']);
		}
		
		echo json_encode($arrReturn);
		
		exit();
	}
	
	public function ajaxGetItemsDetailsAction()
	{
		$Request = $this->getRequest();	
		$Items = $Request->getParam('Items');
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrData = $db->fetchRow("SELECT * FROM Terms WHERE Items='".$Items."'");
		if ($arrData){
			echo json_encode($arrData);
		}
		
		exit();
	}
		
	
	public function ajaxGetTermsAction()
	{
		$Request = $this->getRequest();	
		$TermsCode = $Request->getParam('query');
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrAll = $db->fetchAll("SELECT Name, ID, Code FROM (SELECT * FROM (SELECT * FROM Terms order by ID desc) as Terms GROUP BY Code) as Terms WHERE Terms.Code LIKE '%".$TermsCode."%'");
		foreach ($arrAll as $arrData)
		{
			$arrReturn[] = $arrData['Code'];
		}
		
		echo json_encode($arrReturn);
		
		exit();
	}
	
	public function ajaxGetTermsDetailsAction()
	{
		$Request = $this->getRequest();	
		$termsCode = $Request->getParam('termsCode');
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrData = $db->fetchRow("SELECT * FROM Terms WHERE Code='".$termsCode."' order by ID Desc");
		if ($arrData){
			echo json_encode($arrData);
		}
		
		exit();
	}
	
	
	public function ajaxGetCurrencyAction()
	{
		$Request = $this->getRequest();	
		$CurrencyCode = $Request->getParam('query');
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrAll = $db->fetchAll("SELECT Name, ID, Code, Rate FROM (SELECT * FROM (SELECT * FROM Currency order by ID desc) as Currency GROUP BY Code) as Currency WHERE Currency.Code LIKE '%".$CurrencyCode."%'");
		foreach ($arrAll as $arrData)
		{
			$arrReturn[] = $arrData['Code'];
		}
		
		echo json_encode($arrReturn);
		
		exit();
	}
	
	public function ajaxGetCurrencyDetailsAction()
	{
		$Request = $this->getRequest();	
		$CurrencyCode = $Request->getParam('CurrencyCode');
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrData = $db->fetchRow("SELECT * FROM Currency WHERE Code='".$CurrencyCode."' order by ID Desc");
		if ($arrData){
			echo json_encode($arrData);
		}
		
		exit();
	}
	
	
	public function ajaxGetSupplierAction()
	{
		$Request = $this->getRequest();	
		$SupplierName = $Request->getParam('query');
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrAll = $db->fetchAll("SELECT Name, ID FROM (SELECT * FROM (SELECT * FROM Supplier order by ID desc) as Supplier GROUP BY Name) as Supplier WHERE Supplier.Name LIKE '%".$SupplierName."%' LIMIT 15");
		foreach ($arrAll as $arrData)
		{
			$arrReturn[] = $arrData['Name'];
		}
		
		echo json_encode($arrReturn);
		
		exit();
	}
	
	public function ajaxGetSupplierDetailsAction()
	{
		$Request = $this->getRequest();	
		$SupplierName = $Request->getParam('SupplierName');
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrData = $db->fetchRow("SELECT * FROM Supplier WHERE Name='".$SupplierName."' order by ID Desc");
		if ($arrData){
			echo json_encode($arrData);
		}
		
		exit();
	}
	
	
	public function ajaxGetCustomerAction()
	{
		$Request = $this->getRequest();	
		$CustomerName = $Request->getParam('query');
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrCustomerAll = $db->fetchAll("SELECT Name, ID FROM (SELECT * FROM (SELECT * FROM Customers order by ID desc) as Customers GROUP BY Name) as Customers WHERE Customers.Name LIKE '%".$CustomerName."%' LIMIT 15");
		foreach ($arrCustomerAll as $arrCustomers)
		{
			$arrReturn[] = $arrCustomers['Name'];
		}
		
		echo json_encode($arrReturn);
		
		exit();
	}
	
	public function ajaxGetCustomerDetailsAction()
	{
		$Request = $this->getRequest();	
		$CustomerName = $Request->getParam('CustomerName');
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$arrReturn = array();
		$arrClient = $db->fetchRow("SELECT * FROM Customers WHERE Name='".$CustomerName."' order by ID Desc");
		if ($arrClient){
			echo json_encode($arrClient);
		}
		
		exit();
	}
	
	public function docDeleteAction()
	{
		$layout = $this->_helper->layout();
		$layout->setLayout("ajax");
		$Request = $this->getRequest();	
		$dispFormat = new Venz_App_Display_Format();
		$db = Zend_Db_Table::getDefaultAdapter();
		$JobDocumentsID = $Request->getParam('JobDocumentsID');
		$arrDoc = $db->fetchRow("SELECT * FROM JobDocuments WHERE ID=".$JobDocumentsID);
		unlink($arrDoc['FilePath']);
		
		$arrJob = $db->fetchRow("SELECT * FROM Job where ID=".$arrDoc['JobID']);
		if ($arrDoc['JobPurchaseID'])
			$arrJobPurchase = $db->fetchRow("SELECT * FROM JobPurchase where ID=".$arrDoc['JobPurchaseID']);

		if ($arrDoc['JobSalesID'])
			$arrJobSales = $db->fetchRow("SELECT * FROM JobSales where ID=".$arrDoc['JobSalesID']);

		
		$DocType = $arrDoc['DocType'];
		if ($DocType=="ESPODoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobSales['EOGSTSBPO']);
			$db->Update("JobSales", array("EOGSTSBPO"=>$strName), "ID=".$arrJobSales['ID']);
		}else if ($DocType=="CustPODoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobSales['CustomerPO']);
			$db->Update("JobSales", array("CustomerPO"=>$strName), "ID=".$arrJobSales['ID']);
		}else if ($DocType=="InspReportDoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobSales['SalesInspReportNo']);
			$db->Update("JobSales", array("SalesInspReportNo"=>$strName), "ID=".$arrJobSales['ID']);
		}else if ($DocType=="SOADoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobSales['SalesOrderAckNo']);
			$db->Update("JobSales", array("SalesOrderAckNo"=>$strName), "ID=".$arrJobSales['ID']);
		}else if ($DocType=="ExactInvoiceDoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobSales['SalesInvoiceNo']);
			$db->Update("JobSales", array("SalesInvoiceNo"=>$strName), "ID=".$arrJobSales['ID']);
		}else if ($DocType=="ExactDODoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobSales['SalesDO']);
			$db->Update("JobSales", array("SalesDO"=>$strName), "ID=".$arrJobSales['ID']);
		}else if ($DocType=="ESDODoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobSales['EOGSTSBDO']);
			$db->Update("JobSales", array("EOGSTSBDO"=>$strName), "ID=".$arrJobSales['ID']);
		}else if ($DocType=="ServiceReportDoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobSales['ServiceReportNo']);
			$db->Update("JobSales", array("ServiceReportNo"=>$strName), "ID=".$arrJobSales['ID']);
		}else if ($DocType=="PurchasePODoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobPurchase['PONo']);
			$db->Update("JobPurchase", array("PONo"=>$strName), "ID=".$arrJobPurchase['ID']);
		}else if ($DocType=="PurchaseAckNODoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobPurchase['PurchaseAckNO']);
			$db->Update("JobPurchase", array("PurchaseAckNO"=>$strName), "ID=".$arrJobPurchase['ID']);
		}else if ($DocType=="PurchaseInvoiceNoDoc"){
			$strName = str_replace("|".$arrDoc["Name"], "", $arrJobPurchase['PurchaseInvoiceNo']);
			$db->Update("JobPurchase", array("PurchaseInvoiceNo"=>$strName), "ID=".$arrJobPurchase['ID']);
		}
		
		
		$db->delete("JobDocuments", "ID=".$JobDocumentsID);
		exit();

	}
	
	
	public function docViewImageAction()
	{
		$sysHelper = new Venz_App_System_Helper();
		$Request = $this->getRequest();	
		$db = Zend_Db_Table::getDefaultAdapter();
		$JobDocumentsID = $Request->getParam('JobDocumentsID');
		$arrDoc = $db->fetchRow("SELECT * FROM JobDocuments WHERE ID=".$JobDocumentsID);
		$filename = $arrDoc['FilePath'];
		if (exif_imagetype($filename)){
			header('Content-Description: File Transfer');
			header('Content-Type: content-type: image/jpeg');
			//header('Content-Disposition: attachment; filename="'.$arrDoc['Name'].".".$ext.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($arrDoc['FilePath']));
		}
		readfile($arrDoc['FilePath']);
		
		exit();
	}
	
	public function docViewAction()
	{
		$sysHelper = new Venz_App_System_Helper();
		$Request = $this->getRequest();	
		$db = Zend_Db_Table::getDefaultAdapter();
		$JobDocumentsID = $Request->getParam('JobDocumentsID');
		$arrDoc = $db->fetchRow("SELECT * FROM JobDocuments WHERE ID=".$JobDocumentsID);
		if (is_file($arrDoc['FilePath']))
		{
			$arrTemp = explode(".", $arrDoc['FilePath']);
			$ext = $arrTemp[sizeof($arrTemp) -1];
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.$arrDoc['Name'].".".$ext.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($arrDoc['FilePath']));
			readfile($arrDoc['FilePath']);
			exit();
			
			
		}
		
		exit();
	}
	
	
	public function docUploadAction()
	{
		$layout = $this->_helper->layout();
		$layout->setLayout("ajax");
		$Request = $this->getRequest();	
		$dispFormat = new Venz_App_Display_Format();
		$db = Zend_Db_Table::getDefaultAdapter();
		$JobID = $Request->getParam('JobID');
		$JobPurchaseID = $Request->getParam('JobPurchaseID');
		$JobSalesID = $Request->getParam('JobSalesID');
		if (!$JobID)
			exit();
		
		$DocType = $Request->getParam('DocType');
		$Name = $Request->getParam('Name');
		
		$arrJob = $db->fetchRow("SELECT * FROM Job where ID=".$JobID);
		if ($JobPurchaseID)
			$arrJobPurchase = $db->fetchRow("SELECT * FROM JobPurchase where ID=".$JobPurchaseID);
		if ($JobSalesID)
			$arrJobSales = $db->fetchRow("SELECT * FROM JobSales where ID=".$JobSalesID);
		
		$errorFile = false;
		if ($_FILES['DocUpload'])
		{
			if (!$_FILES['DocUpload']['error'])
			{

				if ($_FILES['DocUpload']['size'] > (5 * 1024 * 1024))
				{
					echo "ERRORSIZE";
					exit();
				}
				
			}
			
		}
		
		if (!$errorFile){
		
			$arrInsert = array("JobID"=>$JobID, "Name"=>$Name, "DocType"=>$DocType, "DateSubmitted"=>new Zend_Db_Expr("NOW()"), "SubmittedBy"=>$this->userInfo->ID);
			if ($JobPurchaseID)
				$arrInsert['JobPurchaseID'] = $JobPurchaseID;
			if ($JobSalesID)
				$arrInsert['JobSalesID'] = $JobSalesID;
			
			$db->Insert("JobDocuments", $arrInsert);
			$docID = $db->lastInsertId();
			
			if ($DocType=="ESPODoc"){
				$db->Update("JobSales", array("EOGSTSBPO"=>$arrJobSales['EOGSTSBPO']."|".$Name), "ID=".$arrJobSales['ID']);
			}else if ($DocType=="CustPODoc"){
				$db->Update("JobSales", array("CustomerPO"=>$arrJobSales['CustomerPO']."|".$Name), "ID=".$arrJobSales['ID']);
			}else if ($DocType=="InspReportDoc"){
				$db->Update("JobSales", array("SalesInspReportNo"=>$arrJobSales['SalesInspReportNo']."|".$Name), "ID=".$arrJobSales['ID']);
			}else if ($DocType=="SOADoc"){
				$db->Update("JobSales", array("SalesOrderAckNo"=>$arrJobSales['SalesOrderAckNo']."|".$Name), "ID=".$arrJobSales['ID']);
			}else if ($DocType=="ExactInvoiceDoc"){
				$db->Update("JobSales", array("SalesInvoiceNo"=>$arrJobSales['SalesInvoiceNo']."|".$Name), "ID=".$arrJobSales['ID']);
			}else if ($DocType=="ExactDODoc"){
				$db->Update("JobSales", array("SalesDO"=>$arrJobSales['SalesDO']."|".$Name), "ID=".$arrJobSales['ID']);
			}else if ($DocType=="ESDODoc"){
				$db->Update("JobSales", array("EOGSTSBDO"=>$arrJobSales['EOGSTSBDO']."|".$Name), "ID=".$arrJobSales['ID']);
			}else if ($DocType=="ServiceReportDoc"){
				$db->Update("JobSales", array("ServiceReportNo"=>$arrJobSales['ServiceReportNo']."|".$Name), "ID=".$arrJobSales['ID']);
			}else if ($DocType=="PurchasePODoc"){
				$db->Update("JobPurchase", array("PONo"=>$arrJobPurchase['PONo']."|".$Name), "ID=".$arrJobPurchase['ID']);
			}else if ($DocType=="PurchaseAckNODoc"){
				$db->Update("JobPurchase", array("PurchaseAckNO"=>$arrJobPurchase['PurchaseAckNO']."|".$Name), "ID=".$arrJobPurchase['ID']);
			}else if ($DocType=="PurchaseInvoiceNoDoc"){
				$db->Update("JobPurchase", array("PurchaseInvoiceNo"=>$arrJobPurchase['PurchaseInvoiceNo']."|".$Name), "ID=".$arrJobPurchase['ID']);
			}
			
			
			


			$filename = $_FILES['DocUpload']['tmp_name'];
			
			if (exif_imagetype($filename) == IMAGETYPE_JPEG || exif_imagetype($filename) == IMAGETYPE_PNG )
			{
				////////////
				define('THUMBNAIL_IMAGE_MAX_WIDTH', 1200);
				define('THUMBNAIL_IMAGE_MAX_HEIGHT', 1200);
				list($source_image_width, $source_image_height, $source_image_type) = getimagesize($filename);
				if (exif_imagetype($filename) == IMAGETYPE_PNG)
					$source_gd_image = imagecreatefrompng($filename);
				else
					$source_gd_image = imagecreatefromjpeg($filename);
				$source_aspect_ratio = $source_image_width / $source_image_height;
				$thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;
				if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
					$thumbnail_image_width = $source_image_width;
					$thumbnail_image_height = $source_image_height;
				} elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
					$thumbnail_image_width = (int) (THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
					$thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
				} else {
					$thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
					$thumbnail_image_height = (int) (THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
				}
				$thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
				imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
				imagejpeg($thumbnail_gd_image, $filename, 60);
				imagedestroy($source_gd_image);
				imagedestroy($thumbnail_gd_image);
				///////////////
			}else
			{
				$arrTemp = explode(".", $_FILES['DocUpload']['name']);
				$ext = strtolower($arrTemp[sizeof($arrTemp) -1]);
				if ($ext != "pdf" && $ext != "xls" && $ext != "doc" && $ext != "docx" && $ext != "xlsx")
				{
					echo "ERROR_IMAGETYPE";
					exit();
				}
			}
			
			
			$handle = fopen($filename, "r");
			$contents = fread($handle, filesize($filename));
			fclose($handle);
			$arrTemp = explode(".", $_FILES['DocUpload']['name']);
			$ext = $arrTemp[sizeof($arrTemp) -1];
			$docPath = $this->_PathJobDoc.'/'.str_pad($JobID, 6, '0', STR_PAD_LEFT)."_".str_replace("/", "_", $arrJob['JobNo']);
			mkdir($docPath);
			
			if ($JobPurchaseID)
			{
				$docPath .= '/Purchase';
				mkdir($docPath);
				$docPath .= '/'.$JobPurchaseID;
				mkdir($docPath);
			}

			if ($JobSalesID)
			{
				$docPath .= '/Sales';
				mkdir($docPath);
				$docPath .= '/'.$JobSalesID;
				mkdir($docPath);
			}

			$docPath .= '/'.$DocType;
			mkdir($docPath);
			
			
			$filepath = $docID.".".$ext;

			$filepath_full = $docPath."/".$filepath;
			$fp = fopen($filepath_full, 'w');
			fwrite($fp, $contents);
			fclose($fp);
			
			$arrUpdate = array("FilePath"=>$filepath_full);
			$db->Update("JobDocuments", $arrUpdate, "ID=".$docID);
			
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$docID."'><img style='height: 65px;' src='/images/icons/IconViewL.png'></a>";
			if (exif_imagetype($filepath_full))
			{
				$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$docID."'>".
					"<img style='height: 65px; max-width: 80%' src='/default/index/doc-view-image/JobDocumentsID/".$docID."'></a>";
				
			}
			
			$returnString = "<div style='padding: 5px; text-align: center; float: left; width: 33%'>".
				$display.
				"<img style='height: 25px; cursor: pointer' id='idDeleteDoc' JobDocumentsID=".$docID." src='/images/icons/IconTrash.png'>".
				"<BR><B style='font-size: 10px'>".$Name."</B>".
				"<BR><B style='font-size: 10px'>".$this->userInfo->Name."</B>".
				"<BR><B style='font-size: 10px'>".$dispFormat->format_datetime_simple(Date("Y-m-d H:i:s", time()))."</B>".
			"</div>";
			
			echo $returnString;
		}
		
		exit();
		

	}
	
	
	public function docGetListAction()
	{
		/*
		$DocType
		ESPODoc - EOG & STSB PO Documents
		CustPODoc - Customer PO Documents
		InspReportDoc - Inspection Report 
		SOADoc - Sales Order Acknowledgement
		ExactInvoiceDoc - Exact Invoice 
		ExactDODoc - Exact DO 
		ESDODoc - EOG & STSB DO 
		ServiceReportDoc - Service Report
		PurchasePODoc - Purchasing PO
		PurchaseAckNODoc - Purchase Ackno Doc
		PurchaseInvoiceNoDoc - Purchase Invoice Doc
		
		*/
		$layout = $this->_helper->layout();
		$layout->setLayout("ajax");
		$Request = $this->getRequest();	
		$dispFormat = new Venz_App_Display_Format();
		$db = Zend_Db_Table::getDefaultAdapter();
		$JobID = $Request->getParam('JobID');
		$JobPurchaseID = $Request->getParam('JobPurchaseID');
		$JobSalesID = $Request->getParam('JobSalesID');
		$DocType = $Request->getParam('DocType');
		$this->view->JobID = $JobID;
		$this->view->JobPurchaseID = $JobPurchaseID;
		$this->view->JobSalesID = $JobSalesID;
		$this->view->DocType = $DocType;
		$arrJob = $db->fetchRow("SELECT * FROM Job where ID=".$JobID);
		
	
		if ($JobPurchaseID)
			$arrUploadsAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='".$DocType."' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobPurchaseID='".$JobPurchaseID."' ORDER BY DateSubmitted DESC");
		else if ($JobSalesID)
			$arrUploadsAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='".$DocType."' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobSalesID='".$JobSalesID."' ORDER BY DateSubmitted DESC");
		else
			$arrUploadsAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='".$DocType."' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." ORDER BY DateSubmitted DESC");
		$listUploads = "<div style='display: inline-block'><img id='idUploadDoc' DocType='".$DocType."' JobID='".$JobID."' style='cursor: pointer' src='/images/icons/IconUpload2.png'> &raquo;</div><div style='display: inline-block'>";
		foreach ($arrUploadsAll as $arrUploads)
		{
			
			$listUploads .= "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
			
		}
		$listUploads .= "</div>";
		echo $listUploads;
		exit();

	}
	
	
	public function docUploadFormAction()
	{
		/*
		$DocType
		ESPODoc - EOG & STSB PO Documents
		CustPODoc - Customer PO Documents
		InspReportDoc - Inspection Report 
		SOADoc - Sales Order Acknowledgement
		ExactInvoiceDoc - Exact Invoice 
		ExactDODoc - Exact DO 
		ESDODoc - EOG & STSB DO 
		ServiceReportDoc - Service Report
		PurchasePODoc - Purchasing PO
		PurchaseAckNODoc - Purchase Ackno Doc
		PurchaseInvoiceNoDoc - Purchase Invoice Doc
		
		*/
		$layout = $this->_helper->layout();
		$layout->setLayout("ajax");
		$Request = $this->getRequest();	
		$dispFormat = new Venz_App_Display_Format();
		$db = Zend_Db_Table::getDefaultAdapter();
		$JobID = $Request->getParam('JobID');
		$JobPurchaseID = $Request->getParam('JobPurchaseID');
		$JobSalesID = $Request->getParam('JobSalesID');
		$DocType = $Request->getParam('DocType');
		$this->view->JobID = $JobID;
		$this->view->JobPurchaseID = $JobPurchaseID;
		$this->view->JobSalesID = $JobSalesID;
		$this->view->DocType = $DocType;
		$arrJob = $db->fetchRow("SELECT * FROM Job where ID=".$JobID);
		
		
		if ($DocType=="ESPODoc"){
			$this->view->DefaultName = $arrJob['EOGSTSBPO'];
			$this->view->DocTitle = "EOG & STSB PO Documents";
			$this->view->DocName = "EOG & STSB PO No.";
		}else if ($DocType=="CustPODoc"){
			$this->view->DefaultName = $arrJob['CustomerPO'];
			$this->view->DocTitle = "Customer PO Documents";
			$this->view->DocName = "Customer PO No.";
		}else if ($DocType=="InspReportDoc"){
			$this->view->DefaultName = $arrJob['SalesInspReportNo'];
			$this->view->DocTitle = "Inspection Report Documents";
			$this->view->DocName = "Inspection Report No.";
		}else if ($DocType=="SOADoc"){
			$this->view->DefaultName = $arrJob['SalesOrderAckNo'];
			$this->view->DocTitle = "Sales Order Acknowledgement Documents";
			$this->view->DocName = "Sales Order Acknowledgement No.";
		}else if ($DocType=="ExactInvoiceDoc"){
			$this->view->DefaultName = $arrJob['SalesInvoiceNo'];
			$this->view->DocTitle = "Exact Invoice Documents";
			$this->view->DocName = "Exact Invoice No.";
		}else if ($DocType=="ExactDODoc"){
			$this->view->DefaultName = $arrJob['SalesDO'];
			$this->view->DocTitle = "Exact DO Documents";
			$this->view->DocName = "Exact DO No.";
		}else if ($DocType=="ESDODoc"){
			$this->view->DefaultName = $arrJob['EOGSTSBDO'];
			$this->view->DocTitle = "EOG & STSB DO Documents";
			$this->view->DocName = "EOG & STSB DO No.";
		}else if ($DocType=="ServiceReportDoc"){
			$this->view->DefaultName = "";
			$this->view->DocTitle = "Service Report Documents";
			$this->view->DocName = "Service Report No.";
		}else if ($DocType=="PurchasePODoc"){
			$this->view->DefaultName = "";
			$this->view->DocTitle = "Purchasing: PO";
			$this->view->DocName = "Purchasing: PO No.";
		}else if ($DocType=="PurchaseAckNODoc"){
			$this->view->DefaultName = "";
			$this->view->DocTitle = "Purchasing: Order Acknowledgement";
			$this->view->DocName = "Purchasing: Order Acknowledgement No.";
		}else if ($DocType=="PurchaseInvoiceNoDoc"){
			$this->view->DefaultName = "";
			$this->view->DocTitle = "Purchasing: Invoice";
			$this->view->DocName = "Purchasing: Invoice No.";
		}
		
	
		if ($JobPurchaseID)
			$arrUploadsAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='".$DocType."' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobPurchaseID='".$JobPurchaseID."' ORDER BY DateSubmitted DESC");
		else if ($JobSalesID)
			$arrUploadsAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='".$DocType."' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." AND JobSalesID='".$JobSalesID."' ORDER BY DateSubmitted DESC");
		else
			$arrUploadsAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='".$DocType."' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." ORDER BY DateSubmitted DESC");
		$this->view->listUploads = "";
		foreach ($arrUploadsAll as $arrUploads)
		{
			$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'><img style='height: 65px;' src='/images/icons/IconViewL.png'></a>";
			if (exif_imagetype($arrUploads['FilePath']))
			{
				$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
					"<img style='height: 65px;  max-width: 80%' src='/default/index/doc-view-image/JobDocumentsID/".$arrUploads['ID']."'></a>";
				
			}
			$strDelete = "";
			if ($arrUploads['SubmittedBy'] == $this->userInfo->ID)
				$strDelete = "<img style='height: 25px; cursor: pointer' id='idDeleteDoc' JobDocumentsID=".$arrUploads['ID']." src='/images/icons/IconTrash.png'>";
			
			$this->view->listUploads .= "<div style='padding: 5px; text-align: center; float: left; width: 33%'>".
				$display.$strDelete.
				"<BR><B style='font-size: 10px'>".$arrUploads['Name']."</B>".
				"<BR><B style='font-size: 10px'>".$arrUploads['Username']."</B>".
				"<BR><B style='font-size: 10px'>".$dispFormat->format_datetime_simple($arrUploads['DateSubmitted'])."</B>".
			"</div>";
			
		}
		

	}
	
	
	public function indexAction() {
		
		try {
			
			$Request = $this->getRequest();			
			$db = Zend_Db_Table::getDefaultAdapter(); 
			$sysHelper = new Venz_App_System_Helper();
			$dispFormat = new Venz_App_Display_Format();
			$sysNotification = new Venz_App_System_Notification();
			$libDb = new Venz_App_Db_Table();
			$systemSetting = new Zend_Session_Namespace('systemSetting');	
			$isAdmin = false;
			if ($this->userInfo->ACLRole == "AdminSystem" || $this->userInfo->ACLRole == "Admin")
				$isAdmin = true;

			$isSales = false;
			if ($this->userInfo->ACLRole == "Sales" || $this->userInfo->ACLRole == "Account" )
				$isSales = true;
			
		//	if ($isSales){
		//		print "IS SALES"; exit();
		//	}
		
			$JobID = $Request->getParam('JobID');	
			
			
			$CreatePayment = $Request->getParam('CreatePayment');	
			if ($CreatePayment && $JobID && ($isAdmin || $isSales))
			{
				$JobID = $Request->getParam('JobID') ? $Request->getParam('JobID') : new Zend_Db_Expr('NULL');	
				$PaymentReceive = $Request->getParam('PaymentReceive') ? $Request->getParam('PaymentReceive') : new Zend_Db_Expr('NULL');	
				$PaymentDate = $Request->getParam('PaymentDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('PaymentDate')) : new Zend_Db_Expr('NULL');	
				$PaymentDescription = $Request->getParam('PaymentDescription') ? $Request->getParam('PaymentDescription') : new Zend_Db_Expr('NULL');	
				$JobDocumentID = $Request->getParam('JobDocumentID') ? $Request->getParam('JobDocumentID') : new Zend_Db_Expr('NULL');	
				$PaymentCurrency = $Request->getParam('PaymentCurrency') ? $Request->getParam('PaymentCurrency') : new Zend_Db_Expr('NULL');	
				$PaymentCurrencyExchangeRate = $Request->getParam('PaymentCurrencyExchangeRate') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('PaymentCurrencyExchangeRate')) : new Zend_Db_Expr('NULL');	

				$PaymentAmount = $Request->getParam('PaymentAmount') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('PaymentAmount')) : new Zend_Db_Expr('NULL');	
				$PaymentInvoice = $Request->getParam('PaymentInvoice') ? $Request->getParam('PaymentInvoice') : new Zend_Db_Expr('NULL');	
				$PaymentAmountRM = 0;
				if ($Request->getParam('PaymentCurrencyExchangeRate') && $Request->getParam('PaymentAmount'))
					$PaymentAmountRM = $PaymentCurrencyExchangeRate * $PaymentAmount;
				
				$arrInsert = array("JobID"=>$JobID,"PaymentReceive"=>$PaymentReceive,"PaymentDate"=>$PaymentDate,"PaymentDescription"=>$PaymentDescription, "JobDocumentID"=>$JobDocumentID, 
				"PaymentCurrency"=>$PaymentCurrency, "PaymentCurrencyExchangeRate"=>$PaymentCurrencyExchangeRate, "PaymentAmount"=>$PaymentAmount, "PaymentAmountRM"=>$PaymentAmountRM, 
				"PaymentInvoice"=>$PaymentInvoice, "EntryDate"=>new Zend_Db_Expr('now()'), "EntryBy"=>$this->userInfo->ID);
			
				if (!$Request->getParam('PaymentCurrencyID') && $Request->getParam('PaymentCurrency'))
				{
					$arrInsertCurrency = array("Name"=>$PaymentCurrency, "Code"=>$PaymentCurrency, "Rate"=>$PaymentCurrencyExchangeRate);
					$db->insert("Currency", $arrInsertCurrency);
					$PaymentCurrencyID = $db->lastInsertId();
					$arrInsert['PaymentCurrencyID'] = $PaymentCurrencyID;
				}else 
				{
					$arrInsert['PaymentCurrencyID'] = $Request->getParam('PaymentCurrencyID');
					
				}	
				if ($Request->getParam('PaymentCurrencyID') && $Request->getParam('PaymentCurrency') && $Request->getParam('PaymentCurrencyExchangeRate'))
				{
					$db->update("Currency", array("Rate"=>$PaymentCurrencyExchangeRate), "ID=".$Request->getParam('PaymentCurrencyID'));
					
				}
			
				$db->insert("JobPayments", $arrInsert);
				
				
				$this->appMessage->setNotice(1, "New payment has been added.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			
			}
			
			$SavePayment = $Request->getParam('SavePayment');	
			$JobPaymentsID = $Request->getParam('JobPaymentsID');	
			if ($SavePayment && $JobID && $JobPaymentsID  && ($isAdmin || $isSales))
			{
				$PaymentReceive = $Request->getParam('PaymentReceive') ? $Request->getParam('PaymentReceive') : new Zend_Db_Expr('NULL');	
				$PaymentDate = $Request->getParam('PaymentDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('PaymentDate')) : new Zend_Db_Expr('NULL');	
				$PaymentDescription = $Request->getParam('PaymentDescription') ? $Request->getParam('PaymentDescription') : new Zend_Db_Expr('NULL');	
				$JobDocumentID = $Request->getParam('JobDocumentID') ? $Request->getParam('JobDocumentID') : new Zend_Db_Expr('NULL');	
				$PaymentCurrency = $Request->getParam('PaymentCurrency') ? $Request->getParam('PaymentCurrency') : new Zend_Db_Expr('NULL');	
				$PaymentCurrencyExchangeRate = $Request->getParam('PaymentCurrencyExchangeRate') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('PaymentCurrencyExchangeRate')) : new Zend_Db_Expr('NULL');	
				$PaymentAmount = $Request->getParam('PaymentAmount') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('PaymentAmount')) : new Zend_Db_Expr('NULL');	
				
				if ($Request->getParam('PaymentReceive'))
					$PaymentInvoice = $Request->getParam('PaymentInvoice') ? $Request->getParam('PaymentInvoice') : new Zend_Db_Expr('NULL');	
				else{
					$PaymentInvoice = $Request->getParam('PaymentInvoicePurchase') ? $Request->getParam('PaymentInvoicePurchase') : new Zend_Db_Expr('NULL');	
				}
				
				$PaymentAmountRM = 0;
				if ($Request->getParam('PaymentCurrencyExchangeRate') && $Request->getParam('PaymentAmount'))
					$PaymentAmountRM = $PaymentCurrencyExchangeRate * $PaymentAmount;
				
				$arrUpdate = array("JobID"=>$JobID,"PaymentReceive"=>$PaymentReceive,"PaymentDate"=>$PaymentDate,"PaymentDescription"=>$PaymentDescription, "JobDocumentID"=>$JobDocumentID, 
				"PaymentCurrency"=>$PaymentCurrency, "PaymentCurrencyExchangeRate"=>$PaymentCurrencyExchangeRate, "PaymentAmount"=>$PaymentAmount, "PaymentAmountRM"=>$PaymentAmountRM, 
				"PaymentInvoice"=>$PaymentInvoice,"EntryDate"=>new Zend_Db_Expr('now()'), "EntryBy"=>$this->userInfo->ID);
			
			
			
				if (!$Request->getParam('PaymentCurrencyID') && $Request->getParam('PaymentCurrency'))
				{
					$arrInsertCurrency = array("Name"=>$PaymentCurrency, "Code"=>$PaymentCurrency, "Rate"=>$PaymentCurrencyExchangeRate);
					$db->insert("Currency", $arrInsertCurrency);
					$PaymentCurrencyID = $db->lastInsertId();
					$arrUpdate['PaymentCurrencyID'] = $PaymentCurrencyID;
				}else 
				{
					$arrUpdate['PaymentCurrencyID'] = $Request->getParam('PaymentCurrencyID');
					
				}	
				if ($Request->getParam('PaymentCurrencyID') && $Request->getParam('PaymentCurrency') && $Request->getParam('PaymentCurrencyExchangeRate'))
				{
					$db->update("Currency", array("Rate"=>$PaymentCurrencyExchangeRate), "ID=".$Request->getParam('PaymentCurrencyID'));
					
				}
				
			
				$db->update("JobPayments", $arrUpdate, "ID=".$JobPaymentsID);
				
				$this->appMessage->setNotice(1, "Payment details has been updated.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			
			}
			
			$DeletePayment = $Request->getParam('DeletePayment');	
			if ($DeletePayment && $JobID && $JobPaymentsID && ($isAdmin || $isSales))
			{
				$db->delete("JobPayments", "ID=".$JobPaymentsID);
				
				$this->appMessage->setNotice(1, "Payment details has been removed.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			
			}
			
			
			$CreateClaims = $Request->getParam('CreateClaims');	
			if ($CreateClaims && $JobID && ($isAdmin || $isSales))
			{
				$ClaimDate = $Request->getParam('ClaimDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('ClaimDate')) : new Zend_Db_Expr('NULL');	
				$ClaimDescription = $Request->getParam('ClaimDescription') ? $Request->getParam('ClaimDescription') : new Zend_Db_Expr('NULL');	
				$ClaimCurrency = $Request->getParam('ClaimCurrency') ? $Request->getParam('ClaimCurrency') : new Zend_Db_Expr('NULL');	
				$ClaimCurrencyExchangeRate = $Request->getParam('ClaimCurrencyExchangeRate') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('ClaimCurrencyExchangeRate')) : new Zend_Db_Expr('NULL');	
				$ClaimAmount = $Request->getParam('ClaimAmount') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('ClaimAmount')) : new Zend_Db_Expr('NULL');	
				$ClaimUserID = $Request->getParam('ClaimUserID') ? $Request->getParam('ClaimUserID') : new Zend_Db_Expr('NULL');	
				
				$arrInsert = array("JobID"=>$JobID,"ClaimDate"=>$ClaimDate,"ClaimDescription"=>$ClaimDescription,"ClaimCurrency"=>$ClaimCurrency, "ClaimCurrencyExchangeRate"=>$ClaimCurrencyExchangeRate, 
				"ClaimAmount"=>$ClaimAmount, "ClaimUserID"=>$ClaimUserID, "DateSubmitted"=>new Zend_Db_Expr('now()'), "SubmittedBy"=>$this->userInfo->ID);
			
				$db->insert("JobClaims", $arrInsert);
				
				$this->appMessage->setNotice(1, "New claims has been added.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			
			}
			
			$SaveClaims = $Request->getParam('SaveClaims');	
			$JobClaimsID = $Request->getParam('JobClaimsID');	
			if ($SaveClaims && $JobID && $JobClaimsID && ($isAdmin || $isSales))
			{
				$ClaimDate = $Request->getParam('ClaimDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('ClaimDate')) : new Zend_Db_Expr('NULL');	
				$ClaimDescription = $Request->getParam('ClaimDescription') ? $Request->getParam('ClaimDescription') : new Zend_Db_Expr('NULL');	
				$ClaimCurrency = $Request->getParam('ClaimCurrency') ? $Request->getParam('ClaimCurrency') : new Zend_Db_Expr('NULL');	
				$ClaimCurrencyExchangeRate = $Request->getParam('ClaimCurrencyExchangeRate') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('ClaimCurrencyExchangeRate')) : new Zend_Db_Expr('NULL');	
				$ClaimAmount = $Request->getParam('ClaimAmount') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('ClaimAmount')) : new Zend_Db_Expr('NULL');	
				$ClaimUserID = $Request->getParam('ClaimUserID') ? $Request->getParam('ClaimUserID') : new Zend_Db_Expr('NULL');	
				
				$arrUpdate = array("JobID"=>$JobID,"ClaimDate"=>$ClaimDate,"ClaimDescription"=>$ClaimDescription,"ClaimCurrency"=>$ClaimCurrency, "ClaimCurrencyExchangeRate"=>$ClaimCurrencyExchangeRate, 
				"ClaimAmount"=>$ClaimAmount, "ClaimUserID"=>$ClaimUserID, "DateSubmitted"=>new Zend_Db_Expr('now()'), "SubmittedBy"=>$this->userInfo->ID);
			
				$db->update("JobClaims", $arrUpdate, "ID=".$JobClaimsID);
				
				$this->appMessage->setNotice(1, "Claims has been updated.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			
			}
			
			$DeleteClaims = $Request->getParam('DeleteClaims');	
			if ($DeleteClaims && $JobID && $JobClaimsID && ($isAdmin || $isSales))
			{
				$db->delete("JobClaims", "ID=".$JobClaimsID);
				
				$this->appMessage->setNotice(1, "Claims has been removed.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			
			}
			
			
			$CreateSales = $Request->getParam('CreateSales');	
			if ($CreateSales && $JobID && $isAdmin)
			{
				$SalesCurrency = $Request->getParam('SalesCurrency') ? $Request->getParam('SalesCurrency') : new Zend_Db_Expr('NULL');	
				$SalesCurrencyID = $Request->getParam('SalesCurrencyID') ? $Request->getParam('SalesCurrencyID') : new Zend_Db_Expr('NULL');	
				$SalesPriceExchangeRate = $Request->getParam('SalesPriceExchangeRate') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('SalesPriceExchangeRate')) : "1";	
				$SalesPrice = $Request->getParam('SalesPrice') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('SalesPrice')) : new Zend_Db_Expr('NULL');	
				$SalesTerms = $Request->getParam('SalesTerms') ? $Request->getParam('SalesTerms') : new Zend_Db_Expr('NULL');	
				$SalesTermsID = $Request->getParam('SalesTermsID') ? $Request->getParam('SalesTermsID') : new Zend_Db_Expr('NULL');	
				
				$CustomerPOReceivedDate = $Request->getParam('CustomerPOReceivedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('CustomerPOReceivedDate')) : new Zend_Db_Expr('NULL');	
				$SalesExpDate = $Request->getParam('SalesExpDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('SalesExpDate')) : new Zend_Db_Expr('NULL');	
				$SalesReadyDate = $Request->getParam('SalesReadyDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('SalesReadyDate')) : new Zend_Db_Expr('NULL');	
					
				$arrInsert = array("JobID"=>$JobID, "SalesCurrency"=>$SalesCurrency,"SalesPriceExchangeRate"=>$SalesPriceExchangeRate,"SalesPrice"=>$SalesPrice,
				"SalesTerms"=>$SalesTerms,"CustomerPOReceivedDate"=>$CustomerPOReceivedDate,"SalesExpDate"=>$SalesExpDate,"SalesReadyDate"=>$SalesReadyDate,
				"CreatedOn"=>new Zend_Db_Expr('now()'), "CreatedBy"=>$this->userInfo->ID);

				if (!$Request->getParam('SalesCurrencyID') && $Request->getParam('SalesCurrency'))
				{
					$existCurrency = $db->fetchRow("SELECT * FROM Currency WHERE Code='".$SalesCurrency."' order by ID DESC");
					if (!$existCurrency)
					{
						$arrInsertCurrency = array("Name"=>$SalesCurrency, "Code"=>$SalesCurrency, "Rate"=>$SalesPriceExchangeRate);
						$db->insert("Currency", $arrInsertCurrency);
						$SalesCurrencyID = $db->lastInsertId();
						$arrUpdate['SalesCurrencyID'] = $SalesCurrencyID;
					}else
					{
						$arrUpdate['SalesCurrencyID'] = $existCurrency['ID'];
					}
				}else 
				{
					$arrUpdate['SalesCurrencyID'] = $SalesCurrencyID;
					
				}	
				
				if ($Request->getParam('SalesCurrencyID') && $Request->getParam('SalesCurrency') && $Request->getParam('SalesPriceExchangeRate'))
				{
					$db->update("Currency", array("Rate"=>$SalesPriceExchangeRate), "ID=".$Request->getParam('SalesCurrencyID'));
					
				}
				
				if (!$Request->getParam('SalesTermsID') && $Request->getParam('SalesTerms'))
				{
					$arrInsertTerms = array("Name"=>$SalesTerms,"Code"=>$SalesTerms);
					$db->insert("Terms", $arrInsertTerms);
					$SalesTermsID = $db->lastInsertId();
					$arrUpdate['SalesTermsID'] = $SalesTermsID;
				}else
				{
					$arrUpdate['SalesTermsID'] = $SalesTermsID;
					
				}

				
				$db->insert("JobSales", $arrInsert);
				$JobSalesID = $db->lastInsertId();
				
				$this->appMessage->setNotice(1, "New purchase has been created, you can now proceed in enter more details about the purchases.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID."/SID/".$JobSalesID); 
			
			}
			
			$SaveSales = $Request->getParam('SaveSales');	
			$JobSalesID = $Request->getParam('JobSalesID');	
			if ($SaveSales && $JobID && $JobSalesID && $isAdmin)
			{
				$SalesCurrency = $Request->getParam('SalesCurrency') ? $Request->getParam('SalesCurrency') : new Zend_Db_Expr('NULL');	
				$SalesCurrencyID = $Request->getParam('SalesCurrencyID') ? $Request->getParam('SalesCurrencyID') : new Zend_Db_Expr('NULL');	
				$SalesPriceExchangeRate = $Request->getParam('SalesPriceExchangeRate') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('SalesPriceExchangeRate')) : "1";	
				$SalesPrice = $Request->getParam('SalesPrice') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('SalesPrice')) : new Zend_Db_Expr('NULL');	
				$SalesTerms = $Request->getParam('SalesTerms') ? $Request->getParam('SalesTerms') : new Zend_Db_Expr('NULL');	
				$SalesTermsID = $Request->getParam('SalesTermsID') ? $Request->getParam('SalesTermsID') : new Zend_Db_Expr('NULL');	
				$SalesPersonID = $Request->getParam('SalesPersonID') ? $Request->getParam('SalesPersonID') : new Zend_Db_Expr('NULL');	
				$Remarks = $Request->getParam('Remarks') ? $Request->getParam('Remarks') : new Zend_Db_Expr('NULL');	
				
				$DrawingApprovedDate = $Request->getParam('DrawingApprovedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('DrawingApprovedDate')) : new Zend_Db_Expr('NULL');	
				$CustomerPOReceivedDate = $Request->getParam('CustomerPOReceivedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('CustomerPOReceivedDate')) : new Zend_Db_Expr('NULL');	
				$SalesExpDate = $Request->getParam('SalesExpDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('SalesExpDate')) : new Zend_Db_Expr('NULL');	
				$SalesReadyDate = $Request->getParam('SalesReadyDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('SalesReadyDate')) : new Zend_Db_Expr('NULL');	
						
				$arrUpdate = array("SalesCurrency"=>$SalesCurrency, "SalesPriceExchangeRate"=>$SalesPriceExchangeRate, "SalesPrice"=>$SalesPrice, 
				"SalesTerms"=>$SalesTerms, "SalesPersonID"=>$SalesPersonID, "DrawingApprovedDate"=>$DrawingApprovedDate, "CustomerPOReceivedDate"=>$CustomerPOReceivedDate, 
				"SalesExpDate"=>$SalesExpDate, "SalesReadyDate"=>$SalesReadyDate, "Remarks"=>$Remarks);


				if (!$Request->getParam('SalesCurrencyID') && $Request->getParam('SalesCurrency'))
				{
					
					$existCurrency = $db->fetchRow("SELECT * FROM Currency WHERE Code='".$SalesCurrency."' order by ID DESC");
					if (!$existCurrency)
					{
						$arrInsertCurrency = array("Name"=>$SalesCurrency, "Code"=>$SalesCurrency, "Rate"=>$SalesPriceExchangeRate);
						$db->insert("Currency", $arrInsertCurrency);
						$SalesCurrencyID = $db->lastInsertId();
						$arrUpdate['SalesCurrencyID'] = $SalesCurrencyID;
					}else
					{
						$arrUpdate['SalesCurrencyID'] = $existCurrency['ID'];
					}
				}else 
				{
					$arrUpdate['SalesCurrencyID'] = $SalesCurrencyID;
					
				}	
				
				if ($Request->getParam('SalesCurrencyID') && $Request->getParam('SalesCurrency') && $Request->getParam('SalesPriceExchangeRate'))
				{
					$db->update("Currency", array("Rate"=>$SalesPriceExchangeRate), "ID=".$Request->getParam('SalesCurrencyID'));
					
				}
				
				if (!$Request->getParam('SalesTermsID') && $Request->getParam('SalesTerms'))
				{
					$arrInsertTerms = array("Name"=>$SalesTerms,"Code"=>$SalesTerms);
					$db->insert("Terms", $arrInsertTerms);
					$SalesTermsID = $db->lastInsertId();
					$arrUpdate['SalesTermsID'] = $SalesTermsID;
				}else
				{
					$arrUpdate['SalesTermsID'] = $SalesTermsID;
					
				}
				
				

				$db->update("JobSales", $arrUpdate, "ID=".$JobSalesID);
				
				$this->appMessage->setNotice(1, "Sales details has been updated.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID."/SID/".$JobSalesID); 
			
			}
			
			$DeleteSales = $Request->getParam('DeleteSales');	
			$JobSalesID = $Request->getParam('JobSalesID');	
			if ($DeleteSales && $JobID && $JobSalesID && $isAdmin)
			{
				$db->delete("JobSales", "ID=".$JobSalesID);
				
				$this->appMessage->setNotice(1, "Sales has been removed.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			
			}
			
			
			$DeleteDelivery = $Request->getParam('DeleteDelivery');
			$JobPurchaseDeliveryID = $Request->getParam('JobPurchaseDeliveryID') ? $Request->getParam('JobPurchaseDeliveryID') : new Zend_Db_Expr('NULL');	
			if ($DeleteDelivery && $isAdmin)
			{
				$JobPurchaseID = $Request->getParam('JobPurchaseID') ? $Request->getParam('JobPurchaseID') : new Zend_Db_Expr('NULL');	
				$db->delete("JobPurchaseDelivery", "ID=".$JobPurchaseDeliveryID);
				
				$this->appMessage->setNotice(1, "Delivery has been removed.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID."/PID/".$JobPurchaseID); 
			}
			
			$CreateDelivery = $Request->getParam('CreateDelivery');	
			$SaveDelivery = $Request->getParam('SaveDelivery');	
			if (($CreateDelivery || $SaveDelivery) && $JobID && $isAdmin)
			{
				$JobPurchaseID = $Request->getParam('JobPurchaseID') ? $Request->getParam('JobPurchaseID') : new Zend_Db_Expr('NULL');	
				$DeliveryAWB = $Request->getParam('DeliveryAWB') ? $Request->getParam('DeliveryAWB') : new Zend_Db_Expr('NULL');	
				$DeliveryReceivedDate = $Request->getParam('DeliveryReceivedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('DeliveryReceivedDate')) : new Zend_Db_Expr('NULL');	
				$DutyTax = $Request->getParam('DutyTax') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('DutyTax')) : new Zend_Db_Expr('NULL');	
				$FreightCost = $Request->getParam('FreightCost') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('FreightCost')) : new Zend_Db_Expr('NULL');	
				$Remarks = $Request->getParam('Remarks') ? $Request->getParam('Remarks') : new Zend_Db_Expr('NULL');	
				
				if ($CreateDelivery)
				{
					$arrInsert = array("JobID"=>$JobID,"JobPurchaseID"=>$JobPurchaseID,"DeliveryAWB"=>$DeliveryAWB,"DeliveryReceivedDate"=>$DeliveryReceivedDate,
						"DutyTax"=>$DutyTax,"FreightCost"=>$FreightCost,"Remarks"=>$Remarks, "CreatedOn"=>new Zend_Db_Expr('now()'), "CreatedBy"=>$this->userInfo->ID);
					$db->insert("JobPurchaseDelivery", $arrInsert);
					
				}else
				{
					$JobPurchaseDeliveryID = $Request->getParam('JobPurchaseDeliveryID') ? $Request->getParam('JobPurchaseDeliveryID') : new Zend_Db_Expr('NULL');	
					$arrUpdate = array("JobID"=>$JobID,"JobPurchaseID"=>$JobPurchaseID,"DeliveryAWB"=>$DeliveryAWB,"DeliveryReceivedDate"=>$DeliveryReceivedDate,
					"DutyTax"=>$DutyTax,"FreightCost"=>$FreightCost,"Remarks"=>$Remarks, "CreatedOn"=>new Zend_Db_Expr('now()'), "CreatedBy"=>$this->userInfo->ID);
					$db->update("JobPurchaseDelivery", $arrUpdate, "ID=".$JobPurchaseDeliveryID);

				}
				$this->appMessage->setNotice(1, "Delivery details saved.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID."/PID/".$JobPurchaseID); 
			}
			
			
			$CreatePurchase = $Request->getParam('CreatePurchase');	
			if ($CreatePurchase && $JobID && $isAdmin)
			{
				$PONo = $Request->getParam('PONo') ? $Request->getParam('PONo') : new Zend_Db_Expr('NULL');	
				$SupplierName = $Request->getParam('SupplierName') ? $Request->getParam('SupplierName') : new Zend_Db_Expr('NULL');	
				$SupplierID = $Request->getParam('SupplierID') ? $Request->getParam('SupplierID') : new Zend_Db_Expr('NULL');	
				$SupplierCode = $Request->getParam('SupplierCode') ? $Request->getParam('SupplierCode') : new Zend_Db_Expr('NULL');	
				$PurchaseCurrency = $Request->getParam('PurchaseCurrency') ? $Request->getParam('PurchaseCurrency') : new Zend_Db_Expr('NULL');	
				$PurchaseCurrencyID = $Request->getParam('PurchaseCurrencyID') ? $Request->getParam('PurchaseCurrencyID') : new Zend_Db_Expr('NULL');	
				$PurchasePrice = $Request->getParam('PurchasePrice') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('PurchasePrice')) : new Zend_Db_Expr('NULL');	
				$PurchasePriceExchangeRate = $Request->getParam('PurchasePriceExchangeRate') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('PurchasePriceExchangeRate')) : "1";	
				$PurchaseTerms = $Request->getParam('PurchaseTerms') ? $Request->getParam('PurchaseTerms') : new Zend_Db_Expr('NULL');	
				$PurchaseTermsID = $Request->getParam('PurchaseTermsID') ? $Request->getParam('PurchaseTermsID') : new Zend_Db_Expr('NULL');	
				
				$arrInsert = array("JobID"=>$JobID,"PONo"=>$PONo,"SupplierName"=>$SupplierName,"SupplierID"=>$SupplierID,"PurchaseCurrency"=>$PurchaseCurrency,"PurchaseTerms"=>$PurchaseTerms,
					"PurchasePrice"=>$PurchasePrice,"PurchasePriceExchangeRate"=>$PurchasePriceExchangeRate,"SupplierCode"=>$SupplierCode, "CreatedOn"=>new Zend_Db_Expr('now()'), "CreatedBy"=>$this->userInfo->ID);
				
				if ($Request->getParam('SupplierID') && $Request->getParam('SupplierName') )
				{
					$arrInsert['SupplierID'] = $SupplierID;
					$db->update("Supplier", array("Name"=>$SupplierName, "Code"=>$SupplierCode), "ID=".$Request->getParam('SupplierID'));
				}else if ($Request->getParam('SupplierName'))
				{
					$arrInsertSupplier = array("Name"=>$SupplierName, "Code"=>$SupplierCode);
					$db->insert("Supplier", $arrInsertSupplier);
					$SupplierID = $db->lastInsertId();
					$arrInsert['SupplierID'] = $SupplierID;
				}
				
				if (!$Request->getParam('PurchaseCurrencyID') && $Request->getParam('PurchaseCurrency'))
				{
					
					$existCurrency = $db->fetchRow("SELECT * FROM Currency WHERE Code='".$PurchaseCurrency."' order by ID DESC");
					if (!$existCurrency)
					{
						$arrInsertCurrency = array("Name"=>$PurchaseCurrency, "Code"=>$PurchaseCurrency, "Rate"=>$PurchasePriceExchangeRate);
						$db->insert("Currency", $arrInsertCurrency);
						$PurchaseCurrencyID = $db->lastInsertId();
						$arrUpdate['PurchaseCurrencyID'] = $PurchaseCurrencyID;
					}else
					{
						$arrUpdate['PurchaseCurrencyID'] = $existCurrency['ID'];
					}

				}else 
				{
					$arrInsert['PurchaseCurrencyID'] = $PurchaseCurrencyID;
					
				}	
				
				if (!$Request->getParam('PurchaseTermsID') && $Request->getParam('PurchaseTerms'))
				{
					$arrInsertTerms = array("Name"=>$PurchaseTerms,"Code"=>$PurchaseTerms);
					$db->insert("Terms", $arrInsertTerms);
					$PurchaseTermsID = $db->lastInsertId();
					$arrInsert['PurchaseTermsID'] = $PurchaseTermsID;
				}else
				{
					$arrInsert['PurchaseTermsID'] = $PurchaseTermsID;
					
				}
				
				
				$db->insert("JobPurchase", $arrInsert);
				$JobPurchaseID = $db->lastInsertId();
				
				$this->appMessage->setNotice(1, "New purchase has been created, you can now proceed in enter more details about the purchases.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID."/PID/".$JobPurchaseID); 
			
			}
			
			$SavePurchase = $Request->getParam('SavePurchase');	
			$JobPurchaseID = $Request->getParam('JobPurchaseID');	
			if ($SavePurchase && $JobID && $JobPurchaseID && $isAdmin)
			{
				//				print_r($arrUpdate); exit();
				
				//$PONo = $Request->getParam('PONo') ? $Request->getParam('PONo') : new Zend_Db_Expr('NULL');	
				$PODate = $Request->getParam('PODate') ? $dispFormat->format_date_simple_to_db($Request->getParam('PODate')) : new Zend_Db_Expr('NULL');	
				$POFaxedDate = $Request->getParam('POFaxedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('POFaxedDate')) : new Zend_Db_Expr('NULL');	
				$SupplierName = $Request->getParam('SupplierName') ? $Request->getParam('SupplierName') : new Zend_Db_Expr('NULL');	
				$SupplierID = $Request->getParam('SupplierID') ? $Request->getParam('SupplierID') : new Zend_Db_Expr('NULL');	
				$SupplierCode = $Request->getParam('SupplierCode') ? $Request->getParam('SupplierCode') : new Zend_Db_Expr('NULL');	
				$PurchaseCurrency = $Request->getParam('PurchaseCurrency') ? $Request->getParam('PurchaseCurrency') : new Zend_Db_Expr('NULL');	
				$PurchaseCurrencyID = $Request->getParam('PurchaseCurrencyID') ? $Request->getParam('PurchaseCurrencyID') : new Zend_Db_Expr('NULL');	
				$PurchasePrice = $Request->getParam('PurchasePrice') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('PurchasePrice')) : new Zend_Db_Expr('NULL');	
				$PurchasePriceExchangeRate = $Request->getParam('PurchasePriceExchangeRate') ? preg_replace("/[^0-9\.]/", "",$Request->getParam('PurchasePriceExchangeRate')) : "1";	
				$PurchaseTerms = $Request->getParam('PurchaseTerms') ? $Request->getParam('PurchaseTerms') : new Zend_Db_Expr('NULL');	
				$PurchaseTermsID = $Request->getParam('PurchaseTermsID') ? $Request->getParam('PurchaseTermsID') : new Zend_Db_Expr('NULL');	
				//$PurchaseAckNO = $Request->getParam('PurchaseAckNO') ? $Request->getParam('PurchaseAckNO') : new Zend_Db_Expr('NULL');	
				//$PurchaseInvoiceNo = $Request->getParam('PurchaseInvoiceNo') ? $Request->getParam('PurchaseInvoiceNo') : new Zend_Db_Expr('NULL');	
				$PurchaseShippingDate = $Request->getParam('PurchaseShippingDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('PurchaseShippingDate')) : new Zend_Db_Expr('NULL');	
				$PurchaseShippingActualDate = $Request->getParam('PurchaseShippingActualDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('PurchaseShippingActualDate')) : new Zend_Db_Expr('NULL');	
				$PurchasePaymentDate = $Request->getParam('PurchasePaymentDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('PurchasePaymentDate')) : new Zend_Db_Expr('NULL');	
		/*		
				$DeliveryAWB = $Request->getParam('DeliveryAWB') ? $Request->getParam('DeliveryAWB') : new Zend_Db_Expr('NULL');	
				$DeliveryReceivedDate = $Request->getParam('DeliveryReceivedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('DeliveryReceivedDate')) : new Zend_Db_Expr('NULL');	
				$DutyTax = $Request->getParam('DutyTax') ? $Request->getParam('DutyTax') : new Zend_Db_Expr('NULL');	
				$FreightCost = $Request->getParam('FreightCost') ? $Request->getParam('FreightCost') : new Zend_Db_Expr('NULL');	
				$Remarks = $Request->getParam('Remarks') ? $Request->getParam('Remarks') : new Zend_Db_Expr('NULL');	
		*/		
			//	$arrUpdate = array("PONo"=>$PONo,"PODate"=>$PODate,"POFaxedDate"=>$POFaxedDate,"SupplierName"=>$SupplierName,"SupplierID"=>$SupplierID,"SupplierCode"=>$SupplierCode,
			//	"PurchaseCurrency"=>$PurchaseCurrency,"PurchasePrice"=>$PurchasePrice,"PurchasePriceExchangeRate"=>$PurchasePriceExchangeRate,"PurchaseTerms"=>$PurchaseTerms,
			//	"PurchaseAckNO"=>$PurchaseAckNO,"PurchaseInvoiceNo"=>$PurchaseInvoiceNo,"PurchaseShippingDate"=>$PurchaseShippingDate,
			//	"PurchaseShippingActualDate"=>$PurchaseShippingActualDate,"PurchasePaymentDate"=>$PurchasePaymentDate,"DeliveryAWB"=>$DeliveryAWB,
			//	"DeliveryReceivedDate"=>$DeliveryReceivedDate,"DutyTax"=>$DutyTax,"FreightCost"=>$FreightCost,"Remarks"=>$Remarks);
				$arrUpdate = array("PODate"=>$PODate,"POFaxedDate"=>$POFaxedDate,"SupplierName"=>$SupplierName,"SupplierID"=>$SupplierID,"SupplierCode"=>$SupplierCode,
				"PurchaseCurrency"=>$PurchaseCurrency,"PurchasePrice"=>$PurchasePrice,"PurchasePriceExchangeRate"=>$PurchasePriceExchangeRate,"PurchaseTerms"=>$PurchaseTerms,
				"PurchaseShippingDate"=>$PurchaseShippingDate,
				"PurchaseShippingActualDate"=>$PurchaseShippingActualDate,"PurchasePaymentDate"=>$PurchasePaymentDate);
			

				
				if ($Request->getParam('SupplierID') && $Request->getParam('SupplierName') )
				{
					$arrUpdate['SupplierID'] = $SupplierID;
					$db->update("Supplier", array("Name"=>$SupplierName, "Code"=>$SupplierCode), "ID=".$Request->getParam('SupplierID'));
				}else if ($Request->getParam('SupplierName'))
				{
					
					$arrInsertSupplier = array("Name"=>$SupplierName, "Code"=>$SupplierCode);
					$db->insert("Supplier", $arrInsertSupplier);
					$SupplierID = $db->lastInsertId();
					$arrUpdate['SupplierID'] = $SupplierID;
				}

				if (!$Request->getParam('PurchaseCurrencyID') && $Request->getParam('PurchaseCurrency'))
				{
					$existCurrency = $db->fetchRow("SELECT * FROM Currency WHERE Code='".$PurchaseCurrency."' order by ID DESC");
					if (!$existCurrency)
					{
						$arrInsertCurrency = array("Name"=>$PurchaseCurrency, "Code"=>$PurchaseCurrency, "Rate"=>$PurchasePriceExchangeRate);
						$db->insert("Currency", $arrInsertCurrency);
						$PurchaseCurrencyID = $db->lastInsertId();
						$arrUpdate['PurchaseCurrencyID'] = $PurchaseCurrencyID;
					}else
					{
						$arrUpdate['PurchaseCurrencyID'] = $existCurrency['ID'];
					}
					
				}else 
				{
					$arrUpdate['PurchaseCurrencyID'] = $PurchaseCurrencyID;
					
				}	
				
				if ($Request->getParam('PurchaseCurrencyID') && $Request->getParam('PurchaseCurrency') && $Request->getParam('PurchasePriceExchangeRate'))
				{
					$db->update("Currency", array("Rate"=>$PurchasePriceExchangeRate), "ID=".$Request->getParam('PurchaseCurrencyID'));
					
				}
				
				if (!$Request->getParam('PurchaseTermsID') && $Request->getParam('PurchaseTerms'))
				{
					$arrInsertTerms = array("Name"=>$PurchaseTerms,"Code"=>$PurchaseTerms);
					$db->insert("Terms", $arrInsertTerms);
					$PurchaseTermsID = $db->lastInsertId();
					$arrUpdate['PurchaseTermsID'] = $PurchaseTermsID;
				}else
				{
					$arrUpdate['PurchaseTermsID'] = $PurchaseTermsID;
					
				}
				
				

				$db->update("JobPurchase", $arrUpdate, "ID=".$JobPurchaseID);
				
				$this->appMessage->setNotice(1, "Job purchasing details has been updated.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID."/PID/".$JobPurchaseID); 
			
			}
			
			$DeletePurchase = $Request->getParam('DeletePurchase');	
			$JobPurchaseID = $Request->getParam('JobPurchaseID');	
			if ($DeletePurchase && $JobID && $JobPurchaseID && $isAdmin)
			{
				$db->delete("JobPurchase", "ID=".$JobPurchaseID);
				
				$this->appMessage->setNotice(1, "Purchase has been removed.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			
			}
			
			
			$Create = $Request->getParam('Create');	
			if ($Create && $isAdmin)
			{
				$CustomerPOReceivedDate = $Request->getParam('CustomerPOReceivedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('CustomerPOReceivedDate')) : new Zend_Db_Expr('NULL');	
				$JobType = $Request->getParam('JobType') ? $Request->getParam('JobType') : new Zend_Db_Expr('NULL');	
				if ($Request->getParam('JobNo'))
					$JobNo = $Request->getParam('JobNo');
				else{
					$arrTable = $db->fetchRow("SHOW TABLE STATUS LIKE 'Job'");
					$JobNo = $JobType.str_pad($arrTable['Auto_increment'], 4, '0', STR_PAD_LEFT) . "/".Date("Y", strtotime($CustomerPOReceivedDate));
				}
				
				$PrincipleName = $Request->getParam('PrincipleName') ? $Request->getParam('PrincipleName') : new Zend_Db_Expr('NULL');	
				$CustomerName = $Request->getParam('CustomerName') ? $Request->getParam('CustomerName') : new Zend_Db_Expr('NULL');	
			//	$CustomerPaymentTerms = $Request->getParam('CustomerPaymentTerms') ? $Request->getParam('CustomerPaymentTerms') : new Zend_Db_Expr('NULL');	
				
				$CustomerID = $Request->getParam('CustomerID') ? $Request->getParam('CustomerID') : new Zend_Db_Expr('NULL');	
				$Items = $Request->getParam('Items') ? $Request->getParam('Items') : new Zend_Db_Expr('NULL');
                $InitialGrossMargin = $Request->getParam('InitialGrossMargin') ? $Request->getParam('InitialGrossMargin') : new Zend_Db_Expr('NULL');

                $arrInsert = array("JobNo"=>$JobNo,
				"CustomerName"=>$CustomerName, "PrincipleName"=>$PrincipleName, "JobType"=>$JobType, "Items"=>$Items, "InitialGrossMargin"=>$InitialGrossMargin, "CustomerPOReceivedDate"=>$CustomerPOReceivedDate, "CreatedOn"=>new Zend_Db_Expr('now()'), "CreatedBy"=>$this->userInfo->ID);

				if ($Request->getParam('CustomerID'))
				{
					$arrInsert['CustomerID'] = $CustomerID;
					$db->update("Customers", array("Name"=>$CustomerName), "ID=".$Request->getParam('CustomerID'));

				}else
				{
					$arrInsertCustomer = array("Name"=>$CustomerName);
					$db->insert("Customers", $arrInsertCustomer);
					$CustomerID = $db->lastInsertId();
					$arrInsert['CustomerID'] = $CustomerID;
				}

				$db->insert("Job", $arrInsert);
				$JobID = $db->lastInsertId();
				
				$this->appMessage->setNotice(1, "New job has been created, you can now proceed in enter more details about the job.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			
			}
			
			$Save = $Request->getParam('Save');	
			$JobID = $Request->getParam('JobID');	
			if ($Save && $JobID && $isAdmin)
			{
				$JobNo = $Request->getParam('JobNo') ? $Request->getParam('JobNo') : new Zend_Db_Expr('NULL');	
				$JobType = $Request->getParam('JobType') ? $Request->getParam('JobType') : new Zend_Db_Expr('NULL');	
				$PrincipleName = $Request->getParam('PrincipleName') ? $Request->getParam('PrincipleName') : new Zend_Db_Expr('NULL');	
				$CustomerName = $Request->getParam('CustomerName') ? $Request->getParam('CustomerName') : new Zend_Db_Expr('NULL');	
			//	$CustomerPaymentTerms = $Request->getParam('CustomerPaymentTerms') ? $Request->getParam('CustomerPaymentTerms') : new Zend_Db_Expr('NULL');	
				$CustomerID = $Request->getParam('CustomerID') ? $Request->getParam('CustomerID') : new Zend_Db_Expr('NULL');	
				$CustomerPOReceivedDate = $Request->getParam('CustomerPOReceivedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('CustomerPOReceivedDate')) : new Zend_Db_Expr('NULL');	
				$Items = $Request->getParam('Items') ? $Request->getParam('Items') : new Zend_Db_Expr('NULL');
                $InitialGrossMargin = $Request->getParam('InitialGrossMargin') ? $Request->getParam('InitialGrossMargin') : new Zend_Db_Expr('NULL');

                $Cancelled = $Request->getParam('Cancelled') ? $Request->getParam('Cancelled') : new Zend_Db_Expr('NULL');
				$Completed = $Request->getParam('Completed') ? $Request->getParam('Completed') : new Zend_Db_Expr('NULL');	
				$CompletedDate = $Request->getParam('CompletedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('CompletedDate')) : new Zend_Db_Expr('NULL');	
				$Closed = $Request->getParam('Closed') ? $Request->getParam('Closed') : new Zend_Db_Expr('NULL');	
				$ClosedDate = $Request->getParam('ClosedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('ClosedDate')) : new Zend_Db_Expr('NULL');	

				//update jobNo jobtype
                $tempJobNo = substr($JobNo, 1);
                $JobNo = $JobType.$tempJobNo;

				
				$arrUpdate = array("JobNo"=>$JobNo, "JobType"=>$JobType, "Items"=>$Items, "InitialGrossMargin"=>$InitialGrossMargin, "CustomerPOReceivedDate"=>$CustomerPOReceivedDate, "PrincipleName"=>$PrincipleName, "Completed"=>$Completed, "Closed"=>$Closed,
					"Cancelled"=>$Cancelled, "CompletedDate"=>$CompletedDate, "ClosedDate"=>$ClosedDate);
				
				if ($Request->getParam('CustomerID'))
				{
					$arrUpdate['CustomerID'] = $CustomerID;
					$db->update("Customers", array("Name"=>$CustomerName), "ID=".$Request->getParam('CustomerID'));
					
				}else
				{
					$arrInsertCustomer = array("Name"=>$CustomerName);
					$db->insert("Customers", $arrInsertCustomer);
					$CustomerID = $db->lastInsertId();
					$arrUpdate['CustomerID'] = $CustomerID;
				}
				
				$db->update("Job", $arrUpdate, "ID=".$JobID);
				
				$this->appMessage->setNotice(1, "Job details has been updated.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			
			}
			
			if ($Save && $JobID && $isSales)
			{
				$Closed = $Request->getParam('Closed') ? $Request->getParam('Closed') : new Zend_Db_Expr('NULL');	
				$ClosedDate = $Request->getParam('ClosedDate') ? $dispFormat->format_date_simple_to_db($Request->getParam('ClosedDate')) : new Zend_Db_Expr('NULL');	
				$arrUpdate = array("Closed"=>$Closed, "ClosedDate"=>$ClosedDate);
				$db->update("Job", $arrUpdate, "ID=".$JobID);
				
				$this->appMessage->setNotice(1, "Job closure has been updated.");
				$this->_redirect('/default/index/index/edit_job/'.$JobID); 
			}	
			
			$this->view->edit_job = "";
			$edit_job = $Request->getParam('edit_job');	
			if ($edit_job)
			{
				
				$JobID = $edit_job;
				$this->view->edit_job = $edit_job;
				$arrJob = $db->fetchRow("SELECT *, Customers.PaymentTerms FROM Job LEFT JOIN Customers ON (Job.CustomerID=Customers.ID) where Job.ID=".$JobID);
				
				$this->view->JobNo = $arrJob['JobNo'];	
				$this->view->JobType = $arrJob['JobType'];	
				$this->view->PrincipleName = $arrJob['PrincipleName'];	
				$this->view->CustomerName = $arrJob['CustomerName'];	
			//	$this->view->CustomerPaymentTerms = $arrJob['CustomerPaymentTerms'] ? $arrJob['CustomerPaymentTerms']  : ($arrJob['PaymentTerms'] ? $arrJob['PaymentTerms'] : "");	
				$this->view->CustomerID = $arrJob['CustomerID'];	
				$this->view->Items = $arrJob['Items'];
                $this->view->InitialGrossMargin = $arrJob['InitialGrossMargin'];

                $this->view->CustomerPOReceivedDate = $dispFormat->format_date_db_to_simple($arrJob['CustomerPOReceivedDate']);
				$this->view->EOGSTSBPO = $arrJob['EOGSTSBPO'];	
				$this->view->CustomerPO = $arrJob['CustomerPO'];	
				$this->view->SalesCurrency = $arrJob['SalesCurrency'];	
				$this->view->SalesCurrencyID = $arrJob['SalesCurrencyID'];	
				$this->view->SalesPriceExchangeRate = $arrJob['SalesPriceExchangeRate'];	
				$this->view->SalesPrice = $arrJob['SalesPrice'];	
				$this->view->SalesTerms = $arrJob['SalesTerms'];	
				$this->view->SalesTermsID = $arrJob['SalesTermsID'];	
				$this->view->SalesInspReportNo = $arrJob['SalesInspReportNo'];	
				$this->view->SalesOrderAckNo = $arrJob['SalesOrderAckNo'];	
				$this->view->SalesExpDate = $dispFormat->format_date_db_to_simple($arrJob['SalesExpDate']);	
				$this->view->SalesReadyDate = $dispFormat->format_date_db_to_simple($arrJob['SalesReadyDate']);	
				$this->view->SalesInvoiceDate = $dispFormat->format_date_db_to_simple($arrJob['SalesInvoiceDate']);	
				$this->view->DrawingApprovedDate = $dispFormat->format_date_db_to_simple($arrJob['DrawingApprovedDate']);	
				$this->view->SalesInvoiceNo = $arrJob['SalesInvoiceNo'];	
				$this->view->SalesDO = $arrJob['SalesDO'];	
				$this->view->EOGSTSBDO = $arrJob['EOGSTSBDO'];	
				$this->view->ServiceReportNo = $arrJob['ServiceReportNo'];	
				
				$this->view->Cancelled = $arrJob['Cancelled'];
				$this->view->Completed = $arrJob['Completed'];
				if ($arrJob['CompletedDate'])
					$this->view->CompletedDate = $dispFormat->format_date_db_to_simple($arrJob['CompletedDate']);	
				else
					$this->view->CompletedDate = Date("d-m-Y", time());	
				$this->view->Closed = $arrJob['Closed'];
				if ($arrJob['ClosedDate'])
					$this->view->ClosedDate = $dispFormat->format_date_db_to_simple($arrJob['ClosedDate']);	
				else
					$this->view->ClosedDate = Date("d-m-Y", time());	
					
				
				$this->view->SalesPersonID = $arrJob['SalesPersonID'];	
				
				$this->view->displayCompletedDate = "visibility:hidden;";
				if ($arrJob['Completed'])
					$this->view->displayCompletedDate = "";

				$this->view->displayClosedDate = "visibility:hidden;";
				if ($arrJob['Closed'])
					$this->view->displayClosedDate = "";

				
				/////////////////////////////////////////////////////////////////////////////////////
				//////////////////// get payment details /////////////////////////////////////////////
				$arrPaymentAll = $db->fetchAll("SELECT JobPayments.*, ACLUsers.Name as Username FROM JobPayments, ACLUsers WHERE JobPayments.EntryBy=ACLUsers.ID AND ".
					"JobID=".$JobID." ORDER BY PaymentDate DESC");
				$this->view->listClaims = ""; 
				foreach ($arrPaymentAll as $arrPayments)
				{
					$PaymentDate = $dispFormat->format_date_db_to_simple($arrPayments['PaymentDate']);
					
					$PaymentInvoiceName = "PaymentInvoicePurchase";
					if ($arrPayments[PaymentReceive])
						$PaymentInvoiceName = "PaymentInvoice";
					
					$strAdminButton = "";
					if ($this->userInfo->ACLRole == "AdminSystem" || $this->userInfo->ACLRole == "Admin" || $this->userInfo->ACLRole == "Sales" || $this->userInfo->ACLRole == "Account")
					{
						$strAdminButton = <<<END
						<div class="uk-width-medium-1-6">
						<input type=submit name='SavePayment' id='SavePayment' value='Save'>
						<input type=submit name='DeletePayment' id='DeletePayment'  class='clsDeletePayment' value='Delete'>
						<input type="hidden" id="JobID" name="JobID" value="$JobID">
						<input type="hidden" id="JobPaymentsID" name="JobPaymentsID" value="$arrPayments[ID]">
				</div>
END;
						
						
					}
					
					
					
					$display = <<<END
		<form enctype="multipart/form-data" action='/default/index/index/#tabs1' method=POST  data-ajax="false">
		<input type=hidden name='PaymentReceive' value="$arrPayments[PaymentReceive]">
		<div class="uk-form-row">
			<div class="uk-grid">
				<div class="uk-width-medium-1-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Date</label>
						<input type="text"  name="PaymentDate" class="md-input PaymentDate" value="$PaymentDate"><span class="md-input-bar"></span></div>
				</div>
				<div class="uk-width-medium-3-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Description</label>
						<input type="text" name="PaymentDescription" id="PaymentDescription" class="md-input" value="$arrPayments[PaymentDescription]"><span class="md-input-bar"></span>
					</div>
					
				</div>
				<div class="uk-width-medium-2-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Invoice</label>
						<input type="text" name="$PaymentInvoiceName" id="$PaymentInvoiceName" class="md-input $PaymentInvoiceName" value="$arrPayments[PaymentInvoice]"><span class="md-input-bar"></span>
						<input type=hidden name='JobDocumentID' ID='JobDocumentID' value="$arrPayments[JobDocumentID]">
					</div>
					
				</div>
			</div>
		</div>	
				
			<div class="uk-form-row">
				<div class="uk-grid">
				<div class="uk-width-medium-1-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Currency</label>
					
						<input type="text" id="PaymentCurrency" name="PaymentCurrency" class="md-input PaymentCurrency" value="$arrPayments[PaymentCurrency]"><span class="md-input-bar"></span>
						<input type="hidden" id="PaymentCurrencyID" name="PaymentCurrencyID" value="$arrPayments[PaymentCurrencyID]">
						<span class="md-input-bar"></span></div>
						
				</div>
				<div class="uk-width-medium-1-6 clsPaymentCurrencyRate">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Rate</label>
						<input type="text" id="PaymentCurrencyExchangeRate" name="PaymentCurrencyExchangeRate" class="md-input" value="$arrPayments[PaymentCurrencyExchangeRate]">
						<span class="md-input-bar"></span></div>
				</div>
				<div class="uk-width-medium-3-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Amount</label>
						<input type="text" name="PaymentAmount" id="PaymentAmount" class="md-input" value="$arrPayments[PaymentAmount]"><span class="md-input-bar"></span></div>
				</div>
				$strAdminButton
				
			   
			</div>
		</div>
		</form>			
		<HR>			
END;

					if ($arrPayments[PaymentReceive])
						$this->view->listPaymentReceived .= $display;
					else
						$this->view->listPaymentMade .= $display;
					
				}
				
				
				/////////////////////////////////////////////////////////////////////////////////////
				//////////////////// get claims details /////////////////////////////////////////////
				$arrClaimsAll = $db->fetchAll("SELECT JobClaims.*, ACLUsers.Name as Username FROM JobClaims, ACLUsers WHERE JobClaims.SubmittedBy=ACLUsers.ID AND JobID=".$JobID." ORDER BY ClaimDate DESC");
				$this->view->listClaims = ""; 
				foreach ($arrClaimsAll as $arrClaims)
				{
					$ClaimDate = $dispFormat->format_date_db_to_simple($arrClaims['ClaimDate']);
					$optionClaimCurrency = $libDb->getSystemOptions("arrCurrency", $arrClaims['ClaimCurrency']);
					$optionClaimUserID = $libDb->getTableOptions("ACLUsers", "Name", "ID", $arrClaims['ClaimUserID']); 
			
					$display = <<<END
		<form enctype="multipart/form-data"  action='/default/index/index/#tabs1' method=POST  data-ajax="false">
		<div class="uk-form-row">
			<div class="uk-grid">
				<div class="uk-width-medium-2-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label><i class="uk-input-group-icon uk-icon-calendar"></i> Claim Date.</label>
						<input type="text"  name="ClaimDate" class="md-input ClaimDate" value="$ClaimDate"><span class="md-input-bar"></span></div>
				</div>
				<div class="uk-width-medium-4-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Description</label>
						<input type="text" name="ClaimDescription" id="ClaimDescription" class="md-input" value="$arrClaims[ClaimDescription]"><span class="md-input-bar"></span>
					</div>
					
				</div>
			</div>	
		</div>	
				
			<div class="uk-form-row">
				<div class="uk-grid">
				<div class="uk-width-medium-1-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Currency</label>
					
						<input type="text" id="ClaimCurrency" name="ClaimCurrency" class="md-input ClaimCurrency" value="$arrClaims[ClaimCurrency]"><span class="md-input-bar"></span>
						<input type="hidden" id="ClaimCurrencyID" name="ClaimCurrencyID" value="$arrClaims[ClaimCurrencyID]">
						<span class="md-input-bar"></span></div>
						
				</div>
				<div class="uk-width-medium-1-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Rate</label>
						<input type="text" id="ClaimCurrencyExchangeRate" name="ClaimCurrencyExchangeRate" class="md-input" value="$arrClaims[ClaimCurrencyExchangeRate]">
						<span class="md-input-bar"></span></div>
				</div>
				<div class="uk-width-medium-2-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Amount</label>
						<input type="text" name="ClaimAmount" id="ClaimAmount" class="md-input" value="$arrClaims[ClaimAmount]"><span class="md-input-bar"></span></div>
				</div>
				<div class="uk-width-medium-1-6">
					<div class="md-input-wrapper md-input-wrapper-success"><label>Claim By</label>
							<select class="md-input" name='ClaimUserID' id='ClaimUserID'>
								<option value=''>
								$optionClaimUserID
							</select>
							<span class="md-input-bar"></span>
						</div>
				</div>
				<div class="uk-width-medium-1-6">

						<input type=submit name='SaveClaims' id='SaveClaims' value='Save'>
						<input type=submit name='DeleteClaims' id='DeleteClaims'  class='clsDeleteClaims' value='Delete'>
						<input type="hidden" id="JobID" name="JobID" value="$JobID">
						<input type="hidden" id="JobClaimsID" name="JobClaimsID" value="$arrClaims[ID]">
				</div>
			   
			</div>
		</div>
		</form>			
					
END;


					$this->view->listClaims .= $display;
					
				}
			
				$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase where JobID=".$JobID." ORDER BY PONO");
				if ($arrPurchaseAll)
				{
					$this->view->purchaseForm = "";
					foreach ($arrPurchaseAll as $arrPurchase)
					{
						$this->view->purchaseForm .= $this->_purchaseForm($arrPurchase);
					}
				}
				
				$arrSalesAll = $db->fetchAll("SELECT * FROM JobSales where JobID=".$JobID);
				
				if ($arrSalesAll)
				{
					$this->view->salesForm = "";
					foreach ($arrSalesAll as $arrSales)
					{
						$this->view->salesForm .= $this->_salesForm($arrSales);
					}
				}
				
			}
			
			$this->view->canCreate = true;
			if ($this->userInfo->ACLRole != "admin" && $this->userInfo->ACLRole != "admin_branch" && $this->userInfo->ACLRole != "data_entry") {
				$this->view->canCreate = false;
			}
			
			
			$this->view->TotalUnit = $totalUnits;
			$this->view->TotalAmount = number_format($totalAmounts, 2, ".", ",");
			
			$this->view->optionJobType = $libDb->getSystemOptions("arrJobType", $this->view->JobType);
			$this->view->optionClaimCurrency = $libDb->getSystemOptions("arrCurrency");
			$this->view->optionClaimUserID = $libDb->getTableOptions("ACLUsers", "Name", "ID"); 
			
			$sqlSearchJob = "";$sqlSearchJob = "";
			$SearchSales = $Request->getParam('SearchSales');	
			$SearchPurchase = $Request->getParam('SearchPurchase');	
			$SearchJob = $Request->getParam('SearchJob');	
			
		//	$this->view->SearchJob = false;
			$strHiddenSearchJob = "";
//			if ($SearchJob || $SearchPurchase || $SearchSales)
//			{
		//		$this->view->SearchJob = true;
				$SearchJobNo = $Request->getParam('SearchJobNo');	
				$SearchCustomerName = $Request->getParam('SearchCustomerName');	
				$SearchPrincipleName = $Request->getParam('SearchPrincipleName');	
				$SearchJobType = $Request->getParam('SearchJobType');	
				$SearchCustomerPOReceivedDateFrom = $Request->getParam('SearchCustomerPOReceivedDateFrom');	
				$SearchCustomerPOReceivedDateTo = $Request->getParam('SearchCustomerPOReceivedDateTo');	
				$SearchItems = $Request->getParam('SearchItems');	
				$SearchCompleted = $Request->getParam('SearchCompleted');	
				$SearchClosed = $Request->getParam('SearchClosed');	
				$SearchCancelled = $Request->getParam('SearchCancelled');	
			
				
				$sqlSearchJob .= $SearchJobNo ? " and Job.JobNo LIKE '%".$SearchJobNo."%'" : "";
				$sqlSearchJob .= $SearchCustomerName ? " and Job.CustomerName LIKE \"%".trim($SearchCustomerName)."%\"" : "";
				$sqlSearchJob .= $SearchPrincipleName ? " and Job.PrincipleName LIKE \"%".trim($SearchPrincipleName)."%\"" : "";
				$sqlSearchJob .= $SearchJobType ? " and Job.JobType LIKE '%".$SearchJobType."%'" : "";
				$sqlSearchJob .= $SearchCustomerPOReceivedDateFrom ? " and Job.CustomerPOReceivedDate >= '".$dispFormat->format_date_simple_to_db($SearchCustomerPOReceivedDateFrom)."'" : "";
				$sqlSearchJob .= $SearchCustomerPOReceivedDateTo ? " and Job.CustomerPOReceivedDate <= '".$dispFormat->format_date_simple_to_db($SearchCustomerPOReceivedDateTo)."'" : "";
				$sqlSearchJob .= $SearchItems ? " and Job.Items LIKE '%".$SearchItems."%'" : "";
				$sqlSearchJob .= $SearchCompleted ? " and Job.Completed = ".$SearchCompleted : "";
				$sqlSearchJob .= $SearchClosed ? " and Job.Closed = ".$SearchClosed : "";
				$sqlSearchJob .= $SearchCancelled ? " and Job.Cancelled = ".$SearchCancelled : "";
				
				
				$this->view->SearchJobNo = $SearchJobNo ? $SearchJobNo : "";
				$this->view->SearchCustomerName = $SearchCustomerName ? $SearchCustomerName : "";
				$this->view->SearchPrincipleName = $SearchPrincipleName ? $SearchPrincipleName : "";
				$this->view->SearchJobType = $SearchJobType ? $SearchJobType : "";
				$this->view->SearchCustomerPOReceivedDateFrom = $SearchCustomerPOReceivedDateFrom ? $SearchCustomerPOReceivedDateFrom : "";
				$this->view->SearchCustomerPOReceivedDateTo = $SearchCustomerPOReceivedDateTo ? $SearchCustomerPOReceivedDateTo : "";
				$this->view->SearchItems = $SearchItems ? $SearchItems : "";
				$this->view->SearchCompleted = $SearchCompleted ? $SearchCompleted : "";
				$this->view->SearchClosed = $SearchClosed ? $SearchClosed : "";
				$this->view->SearchCancelled = $SearchCancelled ? $SearchCancelled : "";
				
				$strHiddenSearchJob = "<input type=hidden name='SearchJob' value='true'>";
				$strHiddenSearchJob .= "<input type=hidden name='SearchJobNo' value='".$SearchJobNo."'>";
				$strHiddenSearchJob .= "<input type=hidden name='SearchCustomerName' value=\"".$SearchCustomerName."\">";
				$strHiddenSearchJob .= "<input type=hidden name='SearchPrincipleName' value=\"".$SearchPrincipleName."\">";
				$strHiddenSearchJob .= "<input type=hidden name='SearchJobType' value='".$SearchJobType."'>";
				$strHiddenSearchJob .= "<input type=hidden name='SearchCustomerPOReceivedDateFrom' value='".$SearchCustomerPOReceivedDateFrom."'>";
				$strHiddenSearchJob .= "<input type=hidden name='SearchCustomerPOReceivedDateTo' value='".$SearchCustomerPOReceivedDateTo."'>";
				$strHiddenSearchJob .= "<input type=hidden name='SearchItems' value='".$SearchItems."'>";
				$strHiddenSearchJob .= "<input type=hidden name='SearchCompleted' value='".$SearchCompleted."'>";
				$strHiddenSearchJob .= "<input type=hidden name='SearchClosed' value='".$SearchClosed."'>";
				$strHiddenSearchJob .= "<input type=hidden name='SearchCancelled' value='".$SearchCancelled."'>";
				
//			}
			
			$this->view->optionSearchJobType = $libDb->getSystemOptions("arrJobType", $this->view->SearchJobType);
			
			
			/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
			$sortbyJob = $Request->getParam('sortbyJob');			
			if (strlen($sortbyJob) == 0) $sortbyJob = 'Job.ID';
				
			$ascdescJob = $Request->getParam('ascdescJob');			
			if (strlen($ascdescJob) == 0) $ascdescJob = 'desc'; 
			
			$showPageJob = $Request->getParam('PagerJobpagenum');			
			if (!$showPageJob) $showPageJob = 1; 
				
			$pagerNextJob = $Request->getParam('PagerJob_next_page');			
			if (strlen($pagerNextJob) > 0) $showPageJob++; 	

			$pagerPrevJob = $Request->getParam('PagerJob_prev_page');			
			if (strlen($pagerPrevJob) > 0) $showPageJob--; 	
			
			$recordsPerPageJob = 30 ;

			
			$sysHelper->setFetchMode(Zend_Db::FETCH_NUM);
			$arrJobs = $sysHelper->getJobs($sortbyJob, $ascdescJob, $recordsPerPageJob, $showPageJob, $sqlSearchJob);
			$dataJobs = $arrJobs[1];
			$exportSql = $arrJobs[2];

			$sessionJobs = new Zend_Session_Namespace('sessionJobs');
			$sessionJobs->numCounter = $recordsPerPageJob * ($showPageJob-1);
			function format_counterJob($colnum, $rowdata)
			{
				$sessionJobs = new Zend_Session_Namespace('sessionJobs');
				$sessionJobs->numCounter++;
				return $sessionJobs->numCounter;
			}
			
				
			function format_jobno($colnum, $rowdata, $export)
			{
				$completedDate = "";
				if ($rowdata[28])
					$completedDate = " on " .Date("d-m-Y", strtotime($rowdata[28]));
				
				if (!$export)
					$status = ($rowdata[22] ? "<img title='Job Completed ".$completedDate."' style='width: 15px' src='/images/icons/IconColorGreen.png'>" : "<img title='Job Pending'  style='width: 15px' src='/images/icons/IconColorOrange.png'>");
				else
					$status = ($rowdata[22] ? "(Completed)<BR>" : "(Pending)<BR>");
				return $status.$rowdata[1] . "<BR>".(($rowdata[20] == "P") ? "Project" : "Trade");
			}
			
			
			function format_jobstatus($colnum, $rowdata, $export)
			{
				if (!$export){
					$status = "";
					if ($rowdata[5])
					{
						$status .= "<img style='width: 15px' src='/images/icons/IconColorGreen.png'> Completed<BR>";
						if ($rowdata[6]){
							$status .= Date("d-m-Y", strtotime($rowdata[6]))."<BR>";
						}
					}
					if ($rowdata[19]){
						$status .= "<img style='width: 15px' src='/images/icons/IconColorDarkGreen.png'> Closed<BR>";
						if ($rowdata[20]){
							$status .= Date("d-m-Y", strtotime($rowdata[20]))."<BR>";
						}
					}
					if ($rowdata[14])
						$status = "<img style='width: 15px' src='/images/icons/IconColorGray.png'> Cancelled<BR>";
					
					if (!$rowdata[19] && !$rowdata[5] && !$rowdata[14])
					{
						$status .= "<img title='Job Pending'  style='width: 15px' src='/images/icons/IconColorOrange.png'> On Going<BR>";
					}
					
				}
				else
				{
					$status = "";
					if ($rowdata[5])
					{
						$status .= "Completed";
						if ($rowdata[6]){
							$status .= " (" . Date("d-m-Y", strtotime($rowdata[6])).")<BR>";
						}else
							$status .= "<BR>";
							
					}
					if ($rowdata[19]){
						$status .= "Closed";
						if ($rowdata[20]){
							$status .= " (" . Date("d-m-Y", strtotime($rowdata[20])).")<BR>";
						}else
							$status .= "<BR>";
							
					}
					if ($rowdata[14])
						$status = "Cancelled<BR>";
					
					if (!$rowdata[19] && !$rowdata[5] && !$rowdata[14])
					{
						$status .= "On Going<BR>";
					}
					
				}

				return $status;
			}
			
			function format_action($colnum, $rowdata, $export)
			{
				if ($export)
					return "";
				$systemSetting = new Zend_Session_Namespace('systemSetting');	
				if ($systemSetting->userInfo->ACLRole == "AdminSystem" || $systemSetting->userInfo->ACLRole == "Admin")
					$strReturn = "<a href='/default/index/index/edit_job/".$rowdata[0]."#tabs1'><img border=0 style='max-width: 20px;' src='/images/icons/IconEdit.gif'></a>";
				else
					$strReturn = "<a href='/default/index/index/edit_job/".$rowdata[0]."#tabs1'><img border=0 style='max-width: 20px;' src='/images/icons/IconView3.png'></a>";
			
				return $strReturn;
				
			}
			
			function format_poreceived($colnum, $rowdata, $export)
			{
				if ($export)
					return $rowdata[9];
				
				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[9]);
			}

			function format_customer($colnum, $rowdata)
			{
				$strPrinciple = "";
				if ($rowdata[15])
					$strPrinciple = "<BR>(".$rowdata[15].")";
				
				return $rowdata[2].$strPrinciple.":<BR>".$rowdata[6];
				
			}
			
			function format_jobtype($colnum, $rowdata)
			{
				$systemSetting = new Zend_Session_Namespace('systemSetting');
				return $systemSetting->arrJobType[$rowdata[4]];
			}
			
			function format_balance($colnum, $rowdata, $export)
			{
				if ($rowdata[7]){
					$Margin = $rowdata[7];
					if (!$export)
						$linkChart = "<a target='_blank' href='/admin/report/margin/JobID/".$rowdata[0]."'><img src='/images/icons/IconChart.png'></a>";
					if ($Margin < 0)
						return "<div style='color: red; text-align: right'>RM ". number_format($Margin, 2, ".", ",").$linkChart."</div>";
					else
						return "<div style='color: blue; text-align: right'>RM ". number_format($Margin, 2, ".", ",").$linkChart."</div>";
				}
				else
					return "";
			}
			
			function format_balanceproject($colnum, $rowdata, $export)
			{
				if ($rowdata[8]){
					$Margin = $rowdata[8];
					if (!$export)
						$linkChart = "<a target='_blank' href='/admin/report/margin/JobID/".$rowdata[0]."'><img src='/images/icons/IconChart.png'></a>";
					if ($Margin < 0)
						return "<div style='color: red; text-align: right'>RM ". number_format($Margin, 2, ".", ",").$linkChart."</div>";
					else
						return "<div style='color: blue; text-align: right'>RM ". number_format($Margin, 2, ".", ",").$linkChart."</div>";
				}
				else
					return "";
			}



			function format_sellingprice_margin($colnum, $rowdata, $export)
            {
                return $rowdata[16];
            }

			function format_sellingpricerm_margin($colnum, $rowdata, $export)
            {
				if ($export)
					return number_format($rowdata[10],2);
				
				if ($rowdata[10])
					return number_format($rowdata[10],2,".", ",");
				else
					return "";
            }

			
            function format_salesperson($colnum, $rowdata, $export)
            {
                return $rowdata[18];
            }

            function format_initial_margin($colnum, $rowdata, $export)
            {
                if ($rowdata[25]){
                    return number_format($rowdata[25], 2);
                }else{
                    return "";
                }
            }



            $arrHeader = array ('', '', 'Job No',  'Job Status',  'PO Received<BR>Date', 'Customer', 'Items', 'Job Type','Selling Price', 'Selling Price<BR>RM', 'Initial Cost<BR>in Value', 'Sales Person', '');
			$arrFormat = array('{format_counterJob}', '{format_action}','%1%', '{format_jobstatus}', '{format_poreceived}', '{format_customer}', '%3%','{format_jobtype}', '{format_sellingprice_margin}',  '{format_sellingpricerm_margin}', '{format_initial_margin}',  '{format_salesperson}', '{format_action}');
			$arrSort = array('','','Job.ID', 'JobSort.Completed', 'Job.CustomerPOReceivedDate', 'Job.CustomerName', 'Job.Items', 'Job.JobType', '','','Job.InitialGrossMargin','','');
			$arrColParam = array('width=20px','width=20px','width=50px', 'width=100px', 'width=100px', 'width=250px', '','width=100px','width=120px nowrap','nowrap','nowrap','nowrap','width=30px');
			$aligndata = 'CCCCCLLCRRRRC'; $tablewidth = '1550px';

            $arrHeaderMargin = array('', '', 'Job No',  'PO Received<BR>Date', 'Customer', 'Payment Term',  'Credit Limit',  'Job Type','Selling Price', 'Selling Price<BR>RM', 'Job/Project<BR>Margin', 'Current<BR>Margin', '');
            $arrFormatMargin = array('{format_counterJob}', '{format_action}','%1%', '{format_poreceived}', '{format_customer}', '%21%', '%22%', '{format_jobtype}', '{format_sellingprice_margin}',  '{format_sellingpricerm_margin}',  '{format_balanceproject}',  '{format_balance}', '{format_action}');
            $arrSortMargin = array('','','Job.ID', 'Job.CustomerPOReceivedDate', 'Job.CustomerName', 'PaymentTerms', 'CreditLimit', 'Job.JobType', '', 'JobSales.TotalSalesPriceRM','ProjectMarginRM','MarginRM','');
            $arrColParamMargin = array('width=20px','width=20px','width=50px', 'width=100px', '', 'width=100px', 'width=100px','nowrap width=120px','nowrap width=120px','nowrap width=120px','width=120px','width=120px','width=30px');
            $aligndataMargin = 'CCCCLCCCRRRC'; $tablewidthMargin = '1350px';



            $exportReportJob = new Venz_App_Report_Excel(array('exportsql'=> $exportSql,  'export_name'=>'export_excel_job',  'hiddenparam'=>'<input type=hidden name="Search" value="Search">'));
            $exportReportJobMargin = new Venz_App_Report_Excel(array('exportsql'=> $exportSql,  'export_name'=>'export_excel_jobmargin',  'hiddenparam'=>'<input type=hidden name="Search" value="Search">'));


            $displayTable = new Venz_App_Display_Table(
				array (
					 'data' => $dataJobs,
					 'headings' => $arrHeader,
					 'format' 		=> $arrFormat,					 
					 'sort_column' 	=> $arrSort,
					 'alllen' 		=> $arrJobs[0],
					 'title'		=> 'Job List: '.$arrJobs[0],					 
					 'aligndata' 	=> $aligndata,
					 'pagelen' 		=> $recordsPerPageJob,
					 'numcols' 		=> sizeof($arrHeader),
					 'colparam' 	=> $arrColParam,
					 'tablewidth' 	=> $tablewidth,
					 'formname'   	=> 'cform',
					 'sortby_name'   => 'sortbyJob',
                     'ascdesc_name'  => 'ascdescJob',
					 'prefix'        => 'PagerJob',
					 'page'          => 'Page ',
					 'sortby' 		=> $sortbyJob,
					 'ascdesc' 		=> $ascdescJob,
					 'hiddenparam' 	=> $strHiddenSearchJob,
					 'export_excel' => $exportReportJob->display_icon()
				)
			);


            $displayTableMargin= new Venz_App_Display_Table(
                array (
                    'data' => $dataJobs,
                    'headings' => $arrHeaderMargin,
                    'format' 		=> $arrFormatMargin,
                    'sort_column' 	=> $arrSortMargin,
                    'alllen' 		=> $arrJobs[0],
                    'title'		=> 'Job List: '.$arrJobs[0],
                    'aligndata' 	=> $aligndataMargin,
                    'pagelen' 		=> $recordsPerPageJob,
                    'numcols' 		=> sizeof($arrHeaderMargin),
                    'colparam' 	=> $arrColParamMargin,
                    'tablewidth' 	=> $tablewidthMargin,
                    'formname'   	=> 'dform',
                    'sortby_name'   => 'sortbyJob',
                    'ascdesc_name'  => 'ascdescJob',
                    'prefix'        => 'PagerJob',
                    'page'          => 'Page ',
                    'sortby' 		=> $sortbyJob,
                    'ascdesc' 		=> $ascdescJob,
                    'hiddenparam' 	=> $strHiddenSearch,
                    'export_excel' => $exportReportJobMargin->display_icon()
                )
            );
			
			$this->view->content_jobs = $displayTable->render();
            $sessionJobs->numCounter = $recordsPerPage * ($showPage-1);

            $this->view->content_jobsmargin = $displayTableMargin->render();
            $sessionJobs->numCounter = $recordsPerPage * ($showPage-1);


            $export_excel_jobmargin_x = $Request->getParam('export_excel_jobmargin_x');
			if ($export_excel_jobmargin_x)
			{

				$db = Zend_Db_Table::getDefaultAdapter(); 
				$exportsql = $Request->getParam('exportsql');	
				$exportReportJob = new Venz_App_Report_Excel(array('exportsql'=> base64_decode($exportsql), 'db'=>$db, 'headings'=>$arrHeaderMargin, 'format'=>$arrFormatMargin));
				$exportReportJob->render();
				
			}

            $export_excel_job_x = $Request->getParam('export_excel_job_x');
            if ($export_excel_job_x)
            {

                $db = Zend_Db_Table::getDefaultAdapter();
                $exportsql = $Request->getParam('exportsql');
                $exportReportJob = new Venz_App_Report_Excel(array('exportsql'=> base64_decode($exportsql), 'db'=>$db, 'headings'=>$arrHeader, 'format'=>$arrFormat));
                $exportReportJob->render();

            }



            ////////////////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////
			/////////////////////////// Sales Records //////////////////////////////////////////////
			$this->view->disbabledTab = '';
			
			
			$sqlSearch = "";
			$SearchSales = $Request->getParam('SearchSales');	
			
			$this->view->SearchSales = false;
			$strHiddenSearch = "";
			if ($SearchSales)
			{
				$this->view->SearchSales = true;
				$this->view->disbabledTab = '0,2,3';
				$SearchEOGSTSBPO = $Request->getParam('SearchEOGSTSBPO');	
				$SearchCustomerPO = $Request->getParam('SearchCustomerPO');	
				$SearchSalesCurrency = $Request->getParam('SearchSalesCurrency');	
				$SearchSalesPriceFrom = $Request->getParam('SearchSalesPriceFrom');	
				$SearchSalesPriceTo = $Request->getParam('SearchSalesPriceTo');	
				$SearchSalesTerms = $Request->getParam('SearchSalesTerms');	
				$SearchSalesInspReportNo = $Request->getParam('SearchSalesInspReportNo');	
				$SearchSalesOrderAckNo = $Request->getParam('SearchSalesOrderAckNo');	
				$SearchSalesExpDateFrom = $Request->getParam('SearchSalesExpDateFrom');	
				$SearchSalesExpDateTo = $Request->getParam('SearchSalesExpDateTo');	
				$SearchSalesReadyDateFrom = $Request->getParam('SearchSalesReadyDateFrom');	
				$SearchSalesReadyDateTo = $Request->getParam('SearchSalesReadyDateTo');	
				$SearchSalesInvoiceDateFrom = $Request->getParam('SearchSalesInvoiceDateFrom');	
				$SearchSalesInvoiceDateTo = $Request->getParam('SearchSalesInvoiceDateTo');	
				$SearchSalesInvoiceNo = $Request->getParam('SearchSalesInvoiceNo');	
				$SearchSalesDO = $Request->getParam('SearchSalesDO');	
				$SearchEOGSTSBDO = $Request->getParam('SearchEOGSTSBDO');	
				$SearchServiceReportNo = $Request->getParam('SearchServiceReportNo');	
				$SearchDrawingApprovedDateFrom = $Request->getParam('SearchDrawingApprovedDateFrom');	
				$SearchDrawingApprovedDateTo = $Request->getParam('SearchDrawingApprovedDateTo');	
				$SearchSalesPersonID = $Request->getParam('SearchSalesPersonID');	
				
			
				$sqlSearch .= $SearchEOGSTSBPO ? " and JobSales.EOGSTSBPO LIKE '%".$SearchEOGSTSBPO."%'" : "";
				$sqlSearch .= $SearchCustomerPO ? " and JobSales.CustomerPO LIKE '%".$SearchCustomerPO."%'" : "";
				$sqlSearch .= $SearchSalesCurrency ? " and JobSales.SalesCurrency LIKE '%".$SearchSalesCurrency."%'" : "";
				$sqlSearch .= $SearchSalesPriceFrom ? " and JobSales.SalesPrice >= ".$SearchSalesPriceFrom : "";
				$sqlSearch .= $SearchSalesPriceTo ? " and JobSales.SalesPrice <= ".$SearchSalesPriceTo : "";
				$sqlSearch .= $SearchSalesTerms ? " and JobSales.SalesTerms LIKE '%".$SearchSalesTerms."%'" : "";
				$sqlSearch .= $SearchSalesInspReportNo ? " and JobSales.SalesInspReportNo LIKE '%".$SearchSalesInspReportNo."%'" : "";
				$sqlSearch .= $SearchSalesOrderAckNo ? " and JobSales.SalesOrderAckNo LIKE '%".$SearchSalesOrderAckNo."%'" : "";
				$sqlSearch .= $SearchSalesExpDateFrom ? " and JobSales.SalesExpDate >= '".$dispFormat->format_date_simple_to_db($SearchSalesExpDateFrom)."'" : "";
				$sqlSearch .= $SearchSalesExpDateTo ? " and JobSales.SalesExpDate <= '".$dispFormat->format_date_simple_to_db($SearchSalesExpDateTo)."'" : "";
				$sqlSearch .= $SearchSalesReadyDateFrom ? " and JobSales.SalesReadyDate >= '".$dispFormat->format_date_simple_to_db($SearchSalesReadyDateFrom)."'" : "";
				$sqlSearch .= $SearchSalesReadyDateTo ? " and JobSales.SalesReadyDate <= '".$dispFormat->format_date_simple_to_db($SearchSalesReadyDateTo)."'" : "";
				$sqlSearch .= $SearchSalesInvoiceDateFrom ? " and JobSales.SalesInvoiceDate >= '".$dispFormat->format_date_simple_to_db($SearchSalesInvoiceDateFrom)."'" : "";
				$sqlSearch .= $SearchSalesInvoiceDateTo ? " and JobSales.SalesInvoiceDate <= '".$dispFormat->format_date_simple_to_db($SearchSalesInvoiceDateTo)."'" : "";
				$sqlSearch .= $SearchSalesInvoiceNo ? " and JobSales.SalesInvoiceNo LIKE '%".$SearchSalesInvoiceNo."%'" : "";
				$sqlSearch .= $SearchSalesDO ? " and JobSales.SalesDO LIKE '%".$SearchSalesDO."%'" : "";
				$sqlSearch .= $SearchEOGSTSBDO ? " and JobSales.EOGSTSBDO LIKE '%".$SearchEOGSTSBDO."%'" : "";
				$sqlSearch .= $SearchServiceReportNo ? " and JobSales.ServiceReportNo LIKE '%".$SearchServiceReportNo."%'" : "";
				$sqlSearch .= $SearchDrawingApprovedDateFrom ? " and JobSales.DrawingApprovedDate >= '".$dispFormat->format_date_simple_to_db($SearchDrawingApprovedDateFrom)."'" : "";
				$sqlSearch .= $SearchDrawingApprovedDateTo ? " and JobSales.DrawingApprovedDate <= '".$dispFormat->format_date_simple_to_db($SearchDrawingApprovedDateTo)."'" : "";
				$sqlSearch .= $SearchSalesPersonID ? " and JobSales.SalesPersonID = ".$SearchSalesPersonID : "";
				
				
				$this->view->SearchEOGSTSBPO = $SearchEOGSTSBPO ? $SearchEOGSTSBPO : "";
				$this->view->SearchCustomerPO = $SearchCustomerPO ? $SearchCustomerPO : "";
				$this->view->SearchSalesCurrency = $SearchSalesCurrency ? $SearchSalesCurrency : "";
				$this->view->SearchSalesPriceFrom = $SearchSalesPriceFrom ? $SearchSalesPriceFrom : "";
				$this->view->SearchSalesPriceTo = $SearchSalesPriceTo ? $SearchSalesPriceTo : "";
				$this->view->SearchSalesTerms = $SearchSalesTerms ? $SearchSalesTerms : "";
				$this->view->SearchSalesInspReportNo = $SearchSalesInspReportNo ? $SearchSalesInspReportNo : "";
				$this->view->SearchSalesOrderAckNo = $SearchSalesOrderAckNo ? $SearchSalesOrderAckNo : "";
				$this->view->SearchSalesExpDateFrom = $SearchSalesExpDateFrom ? $SearchSalesExpDateFrom : "";
				$this->view->SearchSalesExpDateTo = $SearchSalesExpDateTo ? $SearchSalesExpDateTo : "";
				$this->view->SearchSalesReadyDateFrom = $SearchSalesReadyDateFrom ? $SearchSalesReadyDateFrom : "";
				$this->view->SearchSalesReadyDateTo = $SearchSalesReadyDateTo ? $SearchSalesReadyDateTo : "";
				$this->view->SearchSalesInvoiceDateFrom = $SearchSalesInvoiceDateFrom ? $SearchSalesInvoiceDateFrom : "";
				$this->view->SearchSalesInvoiceDateTo = $SearchSalesInvoiceDateTo ? $SearchSalesInvoiceDateTo : "";
				$this->view->SearchSalesInvoiceNo = $SearchSalesInvoiceNo ? $SearchSalesInvoiceNo : "";
				$this->view->SearchSalesDO = $SearchSalesDO ? $SearchSalesDO : "";
				$this->view->SearchEOGSTSBDO = $SearchEOGSTSBDO ? $SearchEOGSTSBDO : "";
				$this->view->SearchServiceReportNo = $SearchServiceReportNo ? $SearchServiceReportNo : "";
				$this->view->SearchDrawingApprovedDateFrom = $SearchDrawingApprovedDateFrom ? $SearchDrawingApprovedDateFrom : "";
				$this->view->SearchDrawingApprovedDateTo = $SearchDrawingApprovedDateTo ? $SearchDrawingApprovedDateTo : "";
				$this->view->SearchSalesPersonID = $SearchSalesPersonID ? $SearchSalesPersonID : "";
				
				$strHiddenSearch = "<input type=hidden name='SearchSales' value='true'>";
				$strHiddenSearch .= "<input type=hidden name='SearchEOGSTSBPO' value='".$SearchEOGSTSBPO."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchCustomerPO' value='".$SearchCustomerPO."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesCurrency' value='".$SearchSalesCurrency."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesPriceFrom' value='".$SearchSalesPriceFrom."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesPriceTo' value='".$SearchSalesPriceTo."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesTerms' value='".$SearchSalesTerms."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesInspReportNo' value='".$SearchSalesInspReportNo."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesOrderAckNo' value='".$SearchSalesOrderAckNo."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesExpDateFrom' value='".$SearchSalesExpDateFrom."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesExpDateTo' value='".$SearchSalesExpDateTo."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesReadyDateFrom' value='".$SearchSalesReadyDateFrom."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesReadyDateTo' value='".$SearchSalesReadyDateTo."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesInvoiceDateFrom' value='".$SearchSalesInvoiceDateFrom."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesInvoiceDateTo' value='".$SearchSalesInvoiceDateTo."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesInvoiceNo' value='".$SearchSalesInvoiceNo."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesDO' value='".$SearchSalesDO."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchEOGSTSBDO' value='".$SearchEOGSTSBDO."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchServiceReportNo' value='".$SearchServiceReportNo."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchDrawingApprovedDateFrom' value='".$SearchDrawingApprovedDateFrom."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchDrawingApprovedDateTo' value='".$SearchDrawingApprovedDateTo."'>";
				$strHiddenSearch .= "<input type=hidden name='SearchSalesPersonID' value='".$SearchSalesPersonID."'>";
				
			}
			$strHiddenSearch .= $strHiddenSearchJob;
			
			$sqlSearch .= $sqlSearchJob;
			$libDb->setFetchMode(Zend_Db::FETCH_ASSOC);
			$this->view->optionSearchSalesPersonID = $libDb->getTableOptions("ACLUsers", "Name", "ID", $this->view->SearchSalesPersonID); 
			
			$sortby = $Request->getParam('sortby');	
			if (strlen($sortby) == 0) $sortby = 'Job.ID';
				
			$ascdesc = $Request->getParam('ascdesc');			
			if (strlen($ascdesc) == 0) $ascdesc = 'desc'; 
			
			$showPage = $Request->getParam('Pagerpagenum');			
			if (!$showPage) $showPage = 1; 
				
			$pagerNext = $Request->getParam('Pager_next_page');			
			if (strlen($pagerNext) > 0) $showPage++; 	

			$pagerPrev = $Request->getParam('Pager_prev_page');			
			if (strlen($pagerPrev) > 0) $showPage--; 	
			
			$recordsPerPage = 30 ;
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_NUM);
			$arrJobs = $sysHelper->getJobsSales($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$dataJobs = $arrJobs[1];
			$exportSql = $arrJobs[2];

			$sessionSales = new Zend_Session_Namespace('sessionSales');
			$sessionSales->numCounter = $recordsPerPage * ($showPage-1);
			function format_counterSales($colnum, $rowdata)
			{
				$sessionSales = new Zend_Session_Namespace('sessionSales');
				$sessionSales->numCounter++;
				return $sessionSales->numCounter;
			}
			
			function format_CustPOReceiveddate($colnum, $rowdata, $export)
			{
				if ($export)
					return $rowdata[3];
					
				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[3]);
			}
			
			function format_PO($colnum, $rowdata, $export)
			{
				if ($export)
				{
					$listDocESPO = "";$listDocCustPO = "";
					if ($rowdata[4])
						$listDocESPO = '<label>EOG & STSB:<BR>'.str_replace("|", "<BR>", substr($rowdata[4], 1)).'</label><BR>';
					if ($rowdata[5])
						$listDocCustPO = '<label>Customer PO:<BR>'.str_replace("|", "<BR>", substr($rowdata[5], 1)).'</label><BR>';
					
					return $listDocESPO.$listDocCustPO;
				}
				
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$db->setFetchMode(Zend_Db::FETCH_ASSOC);
				$arrDocESPOAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='ESPODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobSalesID=".$rowdata[31]." ORDER BY DateSubmitted DESC");
				$listDocESPO = "";
				foreach ($arrDocESPOAll as $arrUploads)
				{
					$display = "<a target='_blank' href='http://exactjob.localhost/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' style='height: 15px' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					$listDocESPO .= $display;
				}
				if ($listDocESPO || $rowdata[4])
					$listDocESPO = '<div class="clsDocList"><label>EOG & STSB:</label><BR>'.$listDocESPO.'</div><BR><BR>';
				//$listDocESPO = '<div style="display: inline-block; white-space: nowrap; "><label>EOG & STSB:<BR>'.$rowdata[4].'</label><BR>'.$listDocESPO.'</div>';
				
				$arrDocCustPOAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='CustPODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobSalesID=".$rowdata[31]." ORDER BY DateSubmitted DESC");
				$listDocCustPO = "";
				foreach ($arrDocCustPOAll as $arrUploads)
				{
					$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					$listDocCustPO .= $display;
				}
				if ($listDocCustPO || $rowdata[5])
					$listDocCustPO = '<div class="clsDocList"><label>Customer PO:</label><BR>'.$listDocCustPO.'</div>';
					
				//$listDocCustPO = '<div style="display: inline-block; white-space: nowrap; "><label>Customer PO:<BR>'.$rowdata[5].'</label><BR>'.$listDocCustPO.'</div>';
				
				return $listDocESPO.$listDocCustPO;
			}
			
			function format_inspreport($colnum, $rowdata, $export)
			{
				if ($export)
				{
					return  str_replace("|", "<BR>", substr($rowdata[10], 1));
				}
				
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$db->setFetchMode(Zend_Db::FETCH_ASSOC);
				$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='InspReportDoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobSalesID=".$rowdata[31]." ORDER BY DateSubmitted DESC");
				$listDoc = "";
				foreach ($arrDocAll as $arrUploads)
				{
					$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					$listDoc .= $display;
				}
				if ($listDoc || $rowdata[10])
					$listDoc = '<div  class="clsDocList">'.$listDoc.'</div>';
				
				
				return $listDoc;
			}
			
			function format_soa($colnum, $rowdata, $export)
			{
				if ($export)
				{
					return str_replace("|", "<BR>", substr($rowdata[11], 1));
				}
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$db->setFetchMode(Zend_Db::FETCH_ASSOC);
				$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='SOADoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobSalesID=".$rowdata[31]." ORDER BY DateSubmitted DESC");
				$listDoc = "";
				foreach ($arrDocAll as $arrUploads)
				{
					$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					$listDoc .= $display;
				}
				if ($listDoc || $rowdata[11])
					$listDoc = '<div  class="clsDocList">'.$listDoc.'</div>';
				
				
				return $listDoc;
			}
			
			
			function format_docExactInv($colnum, $rowdata, $export)
			{
				if ($export)
				{
					if (strpos($rowdata[15], "|") !== false)
						return str_replace("|", "<BR>", substr($rowdata[15], 1));
					else
						return "";
				}
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$db->setFetchMode(Zend_Db::FETCH_ASSOC);
				$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='ExactInvoiceDoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobSalesID=".$rowdata[31]." ORDER BY DateSubmitted DESC");
				$listDoc = "";
				foreach ($arrDocAll as $arrUploads)
				{
					$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					$listDoc .= $display;
				}
				if ($listDoc || $rowdata[15])
					$listDoc = '<div  class="clsDocList">'.$listDoc.'</div>';
				
				
				return $listDoc;
			}
			
			function format_docDO($colnum, $rowdata, $export)
			{
				if ($export)
				{
					$listDocESDO = "";$listDocCustDO = "";
					if ($rowdata[16])
						$listDocESDO = '<label>EOG & STSB DO:<BR>'. str_replace("|", "<BR>", substr($rowdata[16], 1)).'</label><BR>';
					if ($rowdata[17])
						$listDocCustDO = '<label>Customer DO:<BR>'. str_replace("|", "<BR>", substr($rowdata[17], 1)).'</label><BR>';
					
					return $listDocESDO.$listDocCustDO;
				
				}
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$db->setFetchMode(Zend_Db::FETCH_ASSOC);
				$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='ExactDODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobSalesID=".$rowdata[31]." ORDER BY DateSubmitted DESC");
				$listDocExact = "";
				foreach ($arrDocAll as $arrUploads)
				{
					$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					$listDocExact .= $display;
				}
				if ($listDocExact || $rowdata[16])
					$listDocExact = '<div  class="clsDocList"><label>Exact DO:</label><BR>'.$listDocExact.'</div><BR><BR>';
				
				$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='ESDODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobSalesID=".$rowdata[31]." ORDER BY DateSubmitted DESC");
				$listDocES = "";
				foreach ($arrDocAll as $arrUploads)
				{
					$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					$listDocES .= $display;
				}
				if ($listDocES || $rowdata[17])
					$listDocES = '<div  class="clsDocList"><label>EOG & STSB DO:</label><BR>'.$listDocES.'</div>';
				
				
				
				return $listDocExact.$listDocES;
			}
			
			
			function format_docServiceReport($colnum, $rowdata, $export)
			{
				if ($export)
				{
					return  str_replace("|", "<BR>", substr($rowdata[18], 1));
				}
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$db->setFetchMode(Zend_Db::FETCH_ASSOC);
				$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='ServiceReportDoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobSalesID=".$rowdata[31]." ORDER BY DateSubmitted DESC");
				$listDoc = "";
				foreach ($arrDocAll as $arrUploads)
				{
					$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					$listDoc .= $display;
				}
				if ($listDoc || $rowdata[18])
					$listDoc = '<div class="clsDocList">'.$listDoc.'</div>';
				
				
				return $listDoc;
			}


			
			function format_sellingprice($colnum, $rowdata)
			{
				$dispFormat = new Venz_App_Display_Format();
				if ($rowdata[8])
				{
					if ($rowdata[7] == "RM")
						return $rowdata[7] . " ". number_format($rowdata[8],2)."<BR>".$rowdata[9];
					else
						return $rowdata[7] . " ". number_format($rowdata[8],2)."<BR>@".$rowdata[27]."<BR>"."(RM " . number_format($rowdata[26], 2) . ")<BR>".$rowdata[9];
					
				}else
					return "";
			}
			
			function format_sellingprice_ex($colnum, $rowdata)
			{
				$dispFormat = new Venz_App_Display_Format();
				if ($rowdata[8])
				{
					if ($rowdata[7] == "RM")
						return "";
					else
						return $rowdata[7] . " ". number_format($rowdata[8],2);
					
				}else
					return "";
			}
			
			function format_sellingprice_ex_rm($colnum, $rowdata)
			{
				$dispFormat = new Venz_App_Display_Format();
				if ($rowdata[8])
				{
					if ($rowdata[7] == "RM")
						return number_format($rowdata[8],2);
					else
						return number_format($rowdata[26], 2);
					
				}else
					return "";
			}
			
						
			function format_expecteddate($colnum, $rowdata, $export)
			{
				if ($export)
					return $rowdata[12];
					
				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[12]);
			}
			
			function format_goodsreadydate($colnum, $rowdata, $export)
			{
				if ($export)
					return $rowdata[13];

				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[13]);
			}
			
			function format_invoicedate($colnum, $rowdata, $export)
			{
				if ($export)
					return $rowdata[14];

				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[14]);
			}
			
			function format_drawingapproveddate($colnum, $rowdata, $export)
			{
				if ($export)
					return $rowdata[21];

				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[21]);
			}

			function format_completed($colnum, $rowdata)
			{
				return ($rowdata[22] ? "<BR><img title='Job Completed' style='width: 15px' src='/images/icons/IconColorGreen.png'>" : "<img title='Job Pending'  style='width: 15px' src='/images/icons/IconColorOrange.png'>");
			}
			
			
			function format_jobnosales($colnum, $rowdata)
			{
				return $rowdata[1];
			}
			

			
			$arrHeader = array ('', '', 'Job No', 'Date<BR>Received<BR>Cust. PO.', 'Customer /<BR>Items', 'Purchase<BR>Order', 'Selling Price', 'Inspection<BR>Report', 
				'Sales Order<BR>Ack', 'Customer<BR>Expected<BR>Date', 'Goods<BR>Ready<BR>Date', 'Exact<BR>Invoice', 'Delivery<BR>Order', 
				'Service<BR>Report', 'Drawing<BR>Approved', 'Sales<BR>Person', '');
			$arrFormat = array('{format_counterSales}',  '{format_action}', '{format_jobnosales}', '{format_CustPOReceiveddate}', '{format_customer}', '{format_PO}', '{format_sellingprice}',   
				'{format_inspreport}', '{format_soa}', '{format_expecteddate}', '{format_goodsreadydate}', '{format_docExactInv}', 
				'{format_docDO}','{format_docServiceReport}', '{format_drawingapproveddate}','%19%',  '{format_action}');
		
			$arrHeaderEx = array ('', '', 'Job No', 'Date<BR>Received<BR>Cust. PO.', 'Customer /<BR>Items', 'Purchase<BR>Order', 'Selling Price', 'Selling Price<BR>RM', 'Inspection<BR>Report', 
				'Sales Order<BR>Ack', 'Customer<BR>Expected<BR>Date', 'Goods<BR>Ready<BR>Date', 'Exact<BR>Invoice', 'Delivery<BR>Order', 
				'Service<BR>Report', 'Drawing<BR>Approved', 'Sales<BR>Person', '');
			$arrFormatEx = array('{format_counterSales}',  '{format_action}', '{format_jobnosales}', '{format_CustPOReceiveddate}', '{format_customer}', '{format_PO}', '{format_sellingprice_ex}','{format_sellingprice_ex_rm}',   
				'{format_inspreport}', '{format_soa}', '{format_expecteddate}', '{format_goodsreadydate}', '{format_docExactInv}', 
				'{format_docDO}','{format_docServiceReport}', '{format_drawingapproveddate}','%19%',  '{format_action}');
		

			$arrSort = array('','','Job.JobNo', 'Job.CustomerPOReceivedDate', 'Job.CustomerName', '', '', 'ProjectMarginRM', 'MarginRM', '',
				'', 'Job.SalesExpDate', 'Job.SalesReadyDate', '', '', '', 'Job.DrawingApprovedDate', 'SalesPerson');
			$arrColParam = array('','width=30px','width=75px', 'width=75px', '', '', '','','nowrap','nowrap', '','width=75px','width=75px','','','','width=75px','width=100px','width=30px');
			$aligndata = 'CCCCLLRRRLLCCLLLCCC'; $tablewidth = '1650px';

			$exportReport = new Venz_App_Report_Excel(array('exportsql'=> $exportSql, 'hiddenparam'=>'<input type=hidden name="Search" value="Search">'));	
				
				
			$displayTable = new Venz_App_Display_Table(
				array (
					 'data' => $dataJobs,
					 'headings' => $arrHeader,
					 'format' 		=> $arrFormat,					 
					 'sort_column' 	=> $arrSort,
					 'alllen' 		=> $arrJobs[0],
					 'title'		=> 'Job List: '.$arrJobs[0],					 
					 'aligndata' 	=> $aligndata,
					 'pagelen' 		=> $recordsPerPage,
					 'numcols' 		=> sizeof($arrHeader),
					 'colparam' 	=> $arrColParam,
					 'tablewidth' 	=> $tablewidth,
					 'formname'   	=> 'aform',
					 'sortby' 		=> $sortby,
					 'ascdesc' 		=> $ascdesc,
					 'hiddenparam' 	=> $strHiddenSearch,
					 'export_excel' => $exportReport->display_icon()
				)
			);
			
			$this->view->content_jobssales = $displayTable->render();
			$sessionSales->numCounter = $recordsPerPage * ($showPage-1);
			
			
			
			$export_excel_x = $Request->getParam('export_excel_x');						
			if ($export_excel_x)
			{

				$db = Zend_Db_Table::getDefaultAdapter(); 
				$exportsql = $Request->getParam('exportsql');	
				$exportReport = new Venz_App_Report_Excel(array('exportsql'=> base64_decode($exportsql), 'db'=>$db, 'headings'=>$arrHeaderEx, 'format'=>$arrFormatEx));	
				$exportReport->render();
				
			}
			
			////////////////////////////////////////////////////////////////////////
			///////////////////// Purchasing List //////////////////////////////////
			/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
			
			$sqlSearchPurchase = "";
			$sqlSearchPurchaseDelivery = "";
			$SearchPurchase = $Request->getParam('SearchPurchase');	
			
			$this->view->SearchPurchase = false;
			
			$strHiddenSearchPurchase = "";
			if ($SearchPurchase)
			{
				$this->view->SearchPurchase = true;
				$this->view->disbabledTab = '0,1,3';
				$SearchPONo = $Request->getParam('SearchPONo');	
				$SearchSupplierName = $Request->getParam('SearchSupplierName');	
				$SearchSupplierCode = $Request->getParam('SearchSupplierCode');	
				$SearchPODateFrom = $Request->getParam('SearchPODateFrom');	
				$SearchPODateTo = $Request->getParam('SearchPODateTo');	
				$SearchPOFaxedDateFrom = $Request->getParam('SearchPOFaxedDateFrom');	
				$SearchPOFaxedDateTo = $Request->getParam('SearchPOFaxedDateTo');	
				$SearchPurchaseCurrency = $Request->getParam('SearchPurchaseCurrency');	
				$SearchPurchasePriceFrom = $Request->getParam('SearchPurchasePriceFrom');	
				$SearchPurchasePriceTo = $Request->getParam('SearchPurchasePriceTo');	
				$SearchPurchaseTerms = $Request->getParam('SearchPurchaseTerms');	
				$SearchPurchaseAckNO = $Request->getParam('SearchPurchaseAckNO');	
				$SearchPurchaseInvoiceNo = $Request->getParam('SearchPurchaseInvoiceNo');	
				$SearchPurchaseShippingDateFrom = $Request->getParam('SearchPurchaseShippingDateFrom');	
				$SearchPurchaseShippingDateTo = $Request->getParam('SearchPurchaseShippingDateTo');	
				$SearchPurchaseShippingActualDateFrom = $Request->getParam('SearchPurchaseShippingActualDateFrom');	
				$SearchPurchaseShippingActualDateTo = $Request->getParam('SearchPurchaseShippingActualDateTo');	
				$SearchPurchasePaymentDateFrom = $Request->getParam('SearchPurchasePaymentDateFrom');	
				$SearchPurchasePaymentDateTo = $Request->getParam('SearchPurchasePaymentDateTo');	
				$SearchDeliveryAWB = $Request->getParam('SearchDeliveryAWB');	
				$SearchDeliveryReceivedDateFrom = $Request->getParam('SearchDeliveryReceivedDateFrom');	
				$SearchDeliveryReceivedDateTo = $Request->getParam('SearchDeliveryReceivedDateTo');	
				$SearchDutyTaxFrom = $Request->getParam('SearchDutyTaxFrom');	
				$SearchDutyTaxTo = $Request->getParam('SearchDutyTaxTo');	
				$SearchFreightCostFrom = $Request->getParam('SearchFreightCostFrom');	
				$SearchFreightCostTo = $Request->getParam('SearchFreightCostTo');	
				$SearchRemarks = $Request->getParam('SearchRemarks');	
				
				
				$sqlSearchPurchase .= $SearchPONo ? " and JobPurchase.PONo LIKE '%".$SearchPONo."%'" : "";
				$sqlSearchPurchase .= $SearchSupplierName ? " and JobPurchase.SupplierName LIKE \"%".trim($SearchSupplierName)."%\"" : "";
				$sqlSearchPurchase .= $SearchSupplierCode ? " and JobPurchase.SupplierCode LIKE \"%".trim($SearchSupplierCode)."%\"" : "";
				$sqlSearchPurchase .= $SearchPODateFrom ? " and JobPurchase.PODate >= '".$dispFormat->format_date_simple_to_db($SearchPODateFrom)."'" : "";
				$sqlSearchPurchase .= $SearchPODateTo ? " and JobPurchase.PODate <= '".$dispFormat->format_date_simple_to_db($SearchPODateTo)."'" : "";
				$sqlSearchPurchase .= $SearchPOFaxedDateFrom ? " and JobPurchase.POFaxedDate >= '".$dispFormat->format_date_simple_to_db($SearchPOFaxedDateFrom)."'" : "";
				$sqlSearchPurchase .= $SearchPOFaxedDateTo ? " and JobPurchase.POFaxedDate <= '".$dispFormat->format_date_simple_to_db($SearchPOFaxedDateTo)."'" : "";
				$sqlSearchPurchase .= $SearchPurchaseCurrency ? " and JobPurchase.PurchaseCurrency LIKE '%".$SearchPurchaseCurrency."%'" : "";
				$sqlSearchPurchase .= $SearchPurchasePriceFrom ? " and JobPurchase.PurchasePrice >= ".$SearchPurchasePriceFrom : "";
				$sqlSearchPurchase .= $SearchPurchasePriceTo ? " and JobPurchase.PurchasePrice <= ".$SearchPurchasePriceTo : "";
				$sqlSearchPurchase .= $SearchPurchaseTerms ? " and JobPurchase.PurchaseTerms LIKE '%".$SearchPurchaseTerms."%'" : "";
				$sqlSearchPurchase .= $SearchPurchaseAckNO ? " and JobPurchase.PurchaseAckNO LIKE '%".$SearchPurchaseAckNO."%'" : "";
				$sqlSearchPurchase .= $SearchPurchaseInvoiceNo ? " and JobPurchase.PurchaseInvoiceNo LIKE '%".$SearchPurchaseInvoiceNo."%'" : "";
				$sqlSearchPurchase .= $SearchPurchaseShippingDateFrom ? " and JobPurchase.PurchaseShippingDate >= '".$dispFormat->format_date_simple_to_db($SearchPurchaseShippingDateFrom)."'" : "";
				$sqlSearchPurchase .= $SearchPurchaseShippingDateTo ? " and JobPurchase.PurchaseShippingDate <= '".$dispFormat->format_date_simple_to_db($SearchPurchaseShippingDateTo)."'" : "";
				$sqlSearchPurchase .= $SearchPurchaseShippingActualDateFrom ? " and JobPurchase.PurchaseShippingActualDate >= '".$dispFormat->format_date_simple_to_db($SearchPurchaseShippingActualDateFrom)."'" : "";
				$sqlSearchPurchase .= $SearchPurchaseShippingActualDateTo ? " and JobPurchase.PurchaseShippingActualDate <= '".$dispFormat->format_date_simple_to_db($SearchPurchaseShippingActualDateTo)."'" : "";
				$sqlSearchPurchase .= $SearchPurchasePaymentDateFrom ? " and JobPurchase.PurchasePaymentDate >= '".$dispFormat->format_date_simple_to_db($SearchPurchasePaymentDateFrom)."'" : "";
				$sqlSearchPurchase .= $SearchPurchasePaymentDateTo ? " and JobPurchase.PurchasePaymentDate <= '".$dispFormat->format_date_simple_to_db($SearchPurchasePaymentDateTo)."'" : "";
				
				$sqlSearchPurchaseDelivery .= $SearchDeliveryAWB ? " and JobPurchaseDelivery.DeliveryAWB LIKE '%".$SearchDeliveryAWB."%'" : "";
				$sqlSearchPurchaseDelivery .= $SearchDeliveryReceivedDateFrom ? " and JobPurchaseDelivery.DeliveryReceivedDate >= '".$dispFormat->format_date_simple_to_db($SearchDeliveryReceivedDateFrom)."'" : "";
				$sqlSearchPurchaseDelivery .= $SearchDeliveryReceivedDateTo ? " and JobPurchaseDelivery.DeliveryReceivedDate <= '".$dispFormat->format_date_simple_to_db($SearchDeliveryReceivedDateTo)."'" : "";
				$sqlSearchPurchaseDelivery .= $SearchDutyTaxFrom ? " and JobPurchaseDelivery.DutyTax >= ".$SearchDutyTaxFrom : "";
				$sqlSearchPurchaseDelivery .= $SearchDutyTaxTo ? " and JobPurchaseDelivery.DutyTax <= ".$SearchDutyTaxTo : "";
				$sqlSearchPurchaseDelivery .= $SearchFreightCostFrom ? " and JobPurchaseDelivery.FreightCost >= ".$SearchFreightCostFrom : "";
				$sqlSearchPurchaseDelivery .= $SearchFreightCostTo ? " and JobPurchaseDelivery.FreightCost <= ".$SearchFreightCostTo : "";
				$sqlSearchPurchaseDelivery .= $SearchRemarks ? " and JobPurchaseDelivery.Remarks LIKE '%".$SearchRemarks."%'" : "";
				
				
				
				$this->view->SearchPONo = $SearchPONo ? $SearchPONo : "";
				$this->view->SearchSupplierName = $SearchSupplierName ? $SearchSupplierName : "";
				$this->view->SearchSupplierCode = $SearchSupplierCode ? $SearchSupplierCode : "";
				$this->view->SearchPODateFrom = $SearchPODateFrom ? $SearchPODateFrom : "";
				$this->view->SearchPODateTo = $SearchPODateTo ? $SearchPODateTo : "";
				$this->view->SearchPOFaxedDateFrom = $SearchPOFaxedDateFrom ? $SearchPOFaxedDateFrom : "";
				$this->view->SearchPOFaxedDateTo = $SearchPOFaxedDateTo ? $SearchPOFaxedDateTo : "";
				$this->view->SearchPurchaseCurrency = $SearchPurchaseCurrency ? $SearchPurchaseCurrency : "";
				$this->view->SearchPurchasePriceFrom = $SearchPurchasePriceFrom ? $SearchPurchasePriceFrom : "";
				$this->view->SearchPurchasePriceTo = $SearchPurchasePriceTo ? $SearchPurchasePriceTo : "";
				$this->view->SearchPurchaseTerms = $SearchPurchaseTerms ? $SearchPurchaseTerms : "";
				$this->view->SearchPurchaseAckNO = $SearchPurchaseAckNO ? $SearchPurchaseAckNO : "";
				$this->view->SearchPurchaseInvoiceNo = $SearchPurchaseInvoiceNo ? $SearchPurchaseInvoiceNo : "";
				$this->view->SearchPurchaseShippingDateFrom = $SearchPurchaseShippingDateFrom ? $SearchPurchaseShippingDateFrom : "";
				$this->view->SearchPurchaseShippingDateTo = $SearchPurchaseShippingDateTo ? $SearchPurchaseShippingDateTo : "";
				$this->view->SearchPurchaseShippingActualDateFrom = $SearchPurchaseShippingActualDateFrom ? $SearchPurchaseShippingActualDateFrom : "";
				$this->view->SearchPurchaseShippingActualDateTo = $SearchPurchaseShippingActualDateTo ? $SearchPurchaseShippingActualDateTo : "";
				$this->view->SearchPurchasePaymentDateFrom = $SearchPurchasePaymentDateFrom ? $SearchPurchasePaymentDateFrom : "";
				$this->view->SearchPurchasePaymentDateTo = $SearchPurchasePaymentDateTo ? $SearchPurchasePaymentDateTo : "";
				$this->view->SearchDeliveryAWB = $SearchDeliveryAWB ? $SearchDeliveryAWB : "";
				$this->view->SearchDeliveryReceivedDateFrom = $SearchDeliveryReceivedDateFrom ? $SearchDeliveryReceivedDateFrom : "";
				$this->view->SearchDeliveryReceivedDateTo = $SearchDeliveryReceivedDateTo ? $SearchDeliveryReceivedDateTo : "";
				$this->view->SearchDutyTaxFrom = $SearchDutyTaxFrom ? $SearchDutyTaxFrom : "";
				$this->view->SearchDutyTaxTo = $SearchDutyTaxTo ? $SearchDutyTaxTo : "";
				$this->view->SearchFreightCostFrom = $SearchFreightCostFrom ? $SearchFreightCostFrom : "";
				$this->view->SearchFreightCostTo = $SearchFreightCostTo ? $SearchFreightCostTo : "";
				$this->view->SearchRemarks = $SearchRemarks ? $SearchRemarks : "";
				

				$strHiddenSearchPurchase = "<input type=hidden name='SearchPurchase' value='true'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPONo' value='".$SearchPONo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchSupplierName' value=\"".$SearchSupplierName."\">";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchSupplierCode' value=\"".$SearchSupplierCode."\">";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPODateFrom' value='".$SearchPODateFrom."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPODateTo' value='".$SearchPODateTo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPOFaxedDateFrom' value='".$SearchPOFaxedDateFrom."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPOFaxedDateTo' value='".$SearchPOFaxedDateTo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchaseCurrency' value='".$SearchPurchaseCurrency."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchasePriceFrom' value='".$SearchPurchasePriceFrom."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchasePriceTo' value='".$SearchPurchasePriceTo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchaseTerms' value='".$SearchPurchaseTerms."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchaseAckNO' value='".$SearchPurchaseAckNO."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchaseInvoiceNo' value='".$SearchPurchaseInvoiceNo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchaseShippingDateFrom' value='".$SearchPurchaseShippingDateFrom."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchaseShippingDateTo' value='".$SearchPurchaseShippingDateTo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchaseShippingActualDateFrom' value='".$SearchPurchaseShippingActualDateFrom."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchaseShippingActualDateTo' value='".$SearchPurchaseShippingActualDateTo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchasePaymentDateFrom' value='".$SearchPurchasePaymentDateFrom."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchPurchasePaymentDateTo' value='".$SearchPurchasePaymentDateTo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchDeliveryAWB' value='".$SearchDeliveryAWB."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchDeliveryReceivedDateFrom' value='".$SearchDeliveryReceivedDateFrom."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchDeliveryReceivedDateTo' value='".$SearchDeliveryReceivedDateTo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchDutyTaxFrom' value='".$SearchDutyTaxFrom."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchDutyTaxTo' value='".$SearchDutyTaxTo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchFreightCostFrom' value='".$SearchFreightCostFrom."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchFreightCostTo' value='".$SearchFreightCostTo."'>";
				$strHiddenSearchPurchase .= "<input type=hidden name='SearchRemarks' value='".$SearchRemarks."'>";
				
				
			}
			
			$strHiddenSearchPurchase .= $strHiddenSearchJob;
			$sqlSearchPurchase .= $sqlSearchJob;
			
			
			
			$sortbyPurchase = $Request->getParam('sortbyPurchase');			
			if (strlen($sortbyPurchase) == 0) $sortbyPurchase = 'Job.ID';
				
			$ascdescPurchase = $Request->getParam('ascdescPurchase');			
			if (strlen($ascdescPurchase) == 0) $ascdescPurchase = 'desc'; 
			
			$showPagePurchase = $Request->getParam('PagerPurchasepagenum');			
			if (!$showPagePurchase) $showPagePurchase = 1; 
				
			$pagerNextPurchase = $Request->getParam('PagerPurchase_next_page');			
			if (strlen($pagerNextPurchase) > 0) $showPagePurchase++; 	

			$pagerPrevPurchase = $Request->getParam('PagerPurchase_prev_page');			
			if (strlen($pagerPrevPurchase) > 0) $showPagePurchase--; 	
			
			$recordsPerPagePurchase = 30 ;
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_NUM);
			$arrJobsPurchase = $sysHelper->getJobsPurchase($sortbyPurchase, $ascdescPurchase, $recordsPerPagePurchase, $showPagePurchase, $sqlSearchPurchase, $sqlSearchPurchaseDelivery);
			$dataJobsPurchase = $arrJobsPurchase[1];
			$exportSqlPurchase = $arrJobsPurchase[2];

			$sessionJobsPurchase = new Zend_Session_Namespace('sessionJobsPurchase');
			$sessionJobsPurchase->numCounter = $recordsPerPagePurchase * ($showPagePurchase-1);
			function format_counterPurchase($colnum, $rowdata)
			{
				$sessionJobsPurchase = new Zend_Session_Namespace('sessionJobsPurchase');
				$sessionJobsPurchase->numCounter++;
				return $sessionJobsPurchase->numCounter;
			}
			
			function format_docPO($colnum, $rowdata, $export)
			{
			//	if ($export)
			//	{
			//		return str_replace("|", "<BR>", substr($rowdata[7]));
			//	}
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$db->setFetchMode(Zend_Db::FETCH_ASSOC);
				$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='PurchasePODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobPurchaseID=".$rowdata[26]." ORDER BY DateSubmitted DESC");
				
				$listDoc = "";
				foreach ($arrDocAll as $arrUploads)
				{
					if ($export) {
						$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
							"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					}else
					{
						$display = $arrUploads['Name']."<BR>";
						
					}
					$listDoc .= $display;
				}
				if ($listDoc || $rowdata[7])
					$listDoc = '<div class="clsDocList">'.$listDoc.'</div>';
				
				
				return $listDoc;
			}
			
			function format_podate($colnum, $rowdata, $export)
			{
				if ($export)
					return $rowdata[8];
					
				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[8]);
			}
			
			function format_pofaxeddate($colnum, $rowdata, $export)
			{
				if ($export)
					return $rowdata[9];

				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[9]);
			}
			
			function format_buyingprice($colnum, $rowdata)
			{
				$dispFormat = new Venz_App_Display_Format();
				if ($rowdata[13])
				{
					if ($rowdata[12] == "RM")
						return $rowdata[12] . " ". number_format($rowdata[13],2)."<BR>".$rowdata[15];
					else
						return $rowdata[12] . " ". number_format($rowdata[13],2)."<BR>@".$rowdata[14]."<BR>"."(RM " . number_format($rowdata[27], 2) . ")<BR>".$rowdata[15];

				}
				else
					return "";
			}
			
			function format_buyingprice_ex($colnum, $rowdata)
			{
				$dispFormat = new Venz_App_Display_Format();
				if ($rowdata[13])
				{
					if ($rowdata[12] == "RM")
						return "";
					else
						return $rowdata[12] . " ". number_format($rowdata[13],2);

				}
				else
					return "";
			}
			
			function format_buyingprice_ex_rm($colnum, $rowdata)
			{
				$dispFormat = new Venz_App_Display_Format();
				if ($rowdata[13])
				{
					if ($rowdata[12] == "RM")
						return number_format($rowdata[13],2);
					else
						return number_format($rowdata[27],2);;

				}
				else
					return "";
			}
			
			
			
			
			function format_docOA($colnum, $rowdata, $export)
			{
				if ($export)
				{
					return str_replace("|", "<BR>", substr($rowdata[16], 1));
				}
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$db->setFetchMode(Zend_Db::FETCH_ASSOC);
				$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='PurchaseAckNODoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobPurchaseID=".$rowdata[26]." ORDER BY DateSubmitted DESC");
				
				$listDoc = "";
				foreach ($arrDocAll as $arrUploads)
				{
					$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					$listDoc .= $display;
				}
				if ($listDoc || $rowdata[7])
					$listDoc = '<div class="clsDocList">'.$listDoc.'</div>';
				
				
				return $listDoc;
			}
			
			function format_docPIV($colnum, $rowdata, $export)
			{
				if ($export)
				{
					return str_replace("|", "<BR>", substr($rowdata[19], 1));
				}
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$db->setFetchMode(Zend_Db::FETCH_ASSOC);
				$arrDocAll = $db->fetchAll("SELECT JobDocuments.*, ACLUsers.Name as Username FROM JobDocuments, ACLUsers WHERE DocType='PurchaseInvoiceNoDoc' AND JobDocuments.SubmittedBy=ACLUsers.ID AND JobID=".$rowdata[0]." AND JobPurchaseID=".$rowdata[26]." ORDER BY DateSubmitted DESC");
				
				$listDoc = "";
				foreach ($arrDocAll as $arrUploads)
				{
					$display = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrUploads['ID']."'>".
						"<img title='".$arrUploads['Name']." by ".$arrUploads['Username']."' src='/images/icons/IconViewSmall.png'></a> ".$arrUploads['Name']."<BR>";
					$listDoc .= $display;
				}
				if ($listDoc || $rowdata[7])
					$listDoc = '<div  class="clsDocList">'.$listDoc.'</div>';
				
				
				return $listDoc;
			}
			
			function format_scheduledshipping($colnum, $rowdata, $export)
			{
				if ($export)
					return $rowdata[17];
				
				$dispFormat = new Venz_App_Display_Format();	
				if ($rowdata[17])
					return  $dispFormat->format_date_db_to_simple($rowdata[17]);
				else
					return  "";
			}
			
			function format_actualshipping($colnum, $rowdata)
			{
				if ($export)
					return $rowdata[18];

				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[18]);
			}
			
			function format_paymentdate($colnum, $rowdata)
			{
				if ($export)
					return $rowdata[20];
				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[20]);
			}
			
			function format_goodreceiveddate($colnum, $rowdata)
			{
				return $rowdata[22];
				$dispFormat = new Venz_App_Display_Format();	
				return  $dispFormat->format_date_db_to_simple($rowdata[22]);
			}
			
			function format_dutytax($colnum, $rowdata)
			{
				return $rowdata[23];
				$dispFormat = new Venz_App_Display_Format();
				if ($rowdata[23])
					return  "RM ". number_format($rowdata[23],2);
				else
					return "";
			}
			
			function format_freight($colnum, $rowdata)
			{
				return $rowdata[24];
				$dispFormat = new Venz_App_Display_Format();
				if ($rowdata[24])
					return  "RM ". number_format($rowdata[24],2);
				else
					return "";
			}
			
			function format_remarks($colnum, $rowdata, $export)
			{
				return $rowdata[25];
				if ($export)
					return $rowdata[25];
				
				if ($rowdata[25])
					return "<img class='clsRemarks' title='".$rowdata[25]."' src='/images/icons/IconInfo.png'>";
				else
					return $rowdata[25];
			}
			
			function format_supplier($colnum, $rowdata)
			{
				if ($rowdata[10])
					return $rowdata[10] . ($rowdata[11] ? "<BR>(".$rowdata[11].")" : "");
				else
					return "";
			}
			
			
			
			function format_jobnopurchase($colnum, $rowdata, $export)
			{
				if ($export)
					return $rowdata[1];
				else
					return ($rowdata[28] ? "<BR><img title='Job Completed' style='width: 15px' src='/images/icons/IconColorGreen.png'>" : "<img title='Job Pending'  style='width: 15px' src='/images/icons/IconColorOrange.png'>").$rowdata[1];
			}
			
			

			
			
			$arrHeaderPurchase = array ('', '', 'Job No', 'Principal /<BR>Supplier','Purchase<BR>Order','Exact<BR>PO','PO Faxed<BR>Out',
				'Buying Price','Order Ack.','Invoice No.','Scheduled<BR>Shipping','Actual<BR>Shipping','Payment<BR>Date','AWB','Good<BR>Received','Duty<BR>/ Tax',
				'Freight<BR>Cost','Remarks', '');
			$arrFormatPurchase = array('{format_counterPurchase}', '{format_action}','{format_jobnopurchase}', '{format_supplier}', '{format_docPO}', '{format_podate}',
				'{format_pofaxeddate}','{format_buyingprice}', '{format_docOA}', '{format_docPIV}', '{format_scheduledshipping}','{format_actualshipping}',
				'{format_paymentdate}','%21%','{format_goodreceiveddate}','{format_dutytax}','{format_freight}','{format_remarks}','{format_action}');
		
			$arrHeaderPurchaseEx = array ('', '', 'Job No', 'Principal /<BR>Supplier','Purchase<BR>Order','Exact<BR>PO','PO Faxed<BR>Out',
				'Buying Price','Buying Price<BR>RM','Order Ack.','Invoice No.','Scheduled<BR>Shipping','Actual<BR>Shipping','Payment<BR>Date','AWB','Good<BR>Received','Duty<BR>/ Tax',
				'Freight<BR>Cost','Remarks', '');
			$arrFormatPurchaseEx = array('{format_counterPurchase}', '{format_action}','{format_jobnopurchase}', '{format_supplier}', '{format_docPO}', '{format_podate}',
				'{format_pofaxeddate}','{format_buyingprice_ex}', '{format_buyingprice_ex_rm}', '{format_docOA}', '{format_docPIV}', '{format_scheduledshipping}','{format_actualshipping}',
				'{format_paymentdate}','%21%','{format_goodreceiveddate}','{format_dutytax}','{format_freight}','{format_remarks}','{format_action}');

			$arrSortPurchase = array('','','Job.JobNo', 'JobPurchase.SupplierName','', 
				'JobPurchase.PODate','JobPurchase.POFaxedDate','','','',
				'JobPurchase.PurchaseShippingDate','JobPurchase.PurchaseShippingActualDate','JobPurchase.PurchasePaymentDate',
				'', '', '', '', '');
			$arrColParamPurchase = array('','width=30px', 'width=75px', '', '','width=75px','width=75px','','','','width=75px','width=75px','width=75px','nowrap','nowrap','nowrap','nowrap', 'nowrap', 'width=30px');
			$aligndataPurchase = 'CCCCLCCCLLCCCLCRRLC'; $tablewidthPurchase = '1700px';

			$exportReportPurchase = new Venz_App_Report_Excel(array('exportsql'=> $exportSqlPurchase, 'export_name'=>'export_excel_purchase', 'hiddenparam'=>'<input type=hidden name="SearchPurchase" value="SearchPurchase">'));	
				
				
			$displayTablePurchase = new Venz_App_Display_Table(
				array (
					 'data' => $dataJobsPurchase,
					 'headings' => $arrHeaderPurchase,
					 'format' 		=> $arrFormatPurchase,					 
					 'sort_column' 	=> $arrSortPurchase,
					 'alllen' 		=> $arrJobsPurchase[0],
					 'title'		=> $this->translate->_('Job Purchases List: ').$arrJobsPurchase[0],					 
					 'aligndata' 	=> $aligndataPurchase,
					 'pagelen' 		=> $recordsPerPage,
					 'numcols' 		=> sizeof($arrHeaderPurchase),
					 'colparam' 	=> $arrColParamPurchase,
					 'tablewidth' 	=> $tablewidthPurchase,
					 'formname'   	=> 'bform',
					 'sortby_name'   => 'sortbyPurchase',
                     'ascdesc_name'  => 'ascdescPurchase',
					 'prefix'        => 'PagerPurchase',
					 'page'          => 'PagePurchase ',
					 'sortby' 		=> $sortbyPurchase,
					 'ascdesc' 		=> $ascdescPurchase,
					 'hiddenparam' 	=> $strHiddenSearchPurchase,
					 'export_excel' => $exportReportPurchase->display_icon()
				)
			);
			
			$this->view->content_jobspurchase = $displayTablePurchase->render();
			$sessionJobsPurchase->numCounter = $recordsPerPagePurchase * ($showPagePurchase-1);
			
			$export_excel_purchase_x = $Request->getParam('export_excel_purchase_x');						
			if ($export_excel_purchase_x)
			{
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$exportSqlPurchase = $Request->getParam('exportsql');	
				$exportReportPurchase = new Venz_App_Report_Excel(array('exportsql'=> base64_decode($exportSqlPurchase), 'db'=>$db, 'headings'=>$arrHeaderPurchaseEx, 'format'=>$arrFormatPurchaseEx));	
				$exportReportPurchase->render();
				
			}
			
			
			
		}catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	
		
}

?>