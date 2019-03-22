<?php

class Admin_ReportController extends Venz_Zend_Controller_Action
{

    public function init()
    {
		$db = Zend_Db_Table::getDefaultAdapter();
		$fromPeriod = Date("Y-01-01 00:00:00", time());
		$toPeriod = Date("Y-12-31 23:59:59", time());
		$arrPHDaysAll = $db->fetchAll("SELECT * FROM PublicHoliday WHERE PHDate >= '$fromPeriod' AND PHDate <= '$toPeriod'");
		$pubHolidays = 0;
		$this->view->jsArrPH = "";
		foreach ($arrPHDaysAll as $arrPHDays)
		{
			if (Date("N", strtotime($arrPHDays['PHDate'])) < 6)
				$this->view->jsArrPH .= ",'".$arrPHDays['PHDate']."'";
		}
		$this->view->jsArrPH = substr($this->view->jsArrPH, 1);
		
		$actionName = $this->getRequest()->getActionName();
		switch ($actionName){
			default: parent::init("private");
		}		
		
    }
	
	public function blankAction()
    {
		print ""; exit();
    }

    public function salesAction(){

        $Request = $this->getRequest();
        $db = Zend_Db_Table::getDefaultAdapter();
        $sysHelper = new Venz_App_System_Helper();
        $dispFormat = new Venz_App_Display_Format();
        $sysNotification = new Venz_App_System_Notification();
        $libDb = new Venz_App_Db_Table();



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

        $sqlSearch = "";
        $SearchJobNo = $Request->getParam('SearchJobNo');
        $SearchJobType = $Request->getParam('SearchJobType');
        $SearchCustomerName = $Request->getParam('SearchCustomerName');
        $SearchCustomerPOReceivedDateFrom = $Request->getParam('SearchCustomerPOReceivedDateFrom');
        $SearchCustomerPOReceivedDateTo = $Request->getParam('SearchCustomerPOReceivedDateTo');
        $SearchItems = $Request->getParam('SearchItems');
        $SearchSalesPersonID = $Request->getParam('SearchSalesPersonID');
        $SearchCompleted = $Request->getParam('SearchCompleted');
        $SearchClosed = $Request->getParam('SearchClosed');
        $SearchCancelled = $Request->getParam('SearchCancelled');

        $sqlSearch .= $SearchJobNo ? " and Job.JobNo LIKE '%".$SearchJobNo."%'" : "";
        $sqlSearch .= $SearchJobType ? " and Job.JobType LIKE '%".$SearchJobType."%'" : "";
        $sqlSearch .= $SearchCustomerName ? " and Job.CustomerName LIKE \"%".trim($SearchCustomerName)."%\"" : "";
        $sqlSearch .= $SearchCustomerPOReceivedDateFrom ? " and Job.CustomerPOReceivedDate >= '".$dispFormat->format_date_simple_to_db($SearchCustomerPOReceivedDateFrom)."'" : "";
        $sqlSearch .= $SearchCustomerPOReceivedDateTo ? " and Job.CustomerPOReceivedDate <= '".$dispFormat->format_date_simple_to_db($SearchCustomerPOReceivedDateTo)."'" : "";
        $sqlSearch .= $SearchItems ? " and Job.Items LIKE '%".$SearchItems."%'" : "";
        $sqlSearch .= $SearchSalesPersonID ? " and JobSalesData.SalesPersonGroupID IN (".$SearchSalesPersonID.")" : "";
        $sqlSearch .= $SearchCompleted ? " and Job.Completed = ".$SearchCompleted : "";
        $sqlSearch .= $SearchClosed ? " and Job.Closed = ".$SearchClosed : "";
        $sqlSearch .= $SearchCancelled ? " and Job.Cancelled = ".$SearchCancelled : "";



        $this->view->SearchJobNo = $SearchJobNo ? $SearchJobNo : "";
        $this->view->SearchJobType = $SearchJobType ? $SearchJobType : "";
        $this->view->SearchCustomerName = $SearchCustomerName ? $SearchCustomerName : "";
        $this->view->SearchCustomerPOReceivedDateFrom = $SearchCustomerPOReceivedDateFrom ? $SearchCustomerPOReceivedDateFrom : "";
        $this->view->SearchCustomerPOReceivedDateTo = $SearchCustomerPOReceivedDateTo ? $SearchCustomerPOReceivedDateTo : "";
        $this->view->SearchItems = $SearchItems ? $SearchItems : "";
        $this->view->SearchSalesPersonID = $SearchSalesPersonID ? $SearchSalesPersonID : "";
        $this->view->SearchCompleted = $SearchCompleted ? $SearchCompleted : "";
        $this->view->SearchClosed = $SearchClosed ? $SearchClosed : "";
        $this->view->SearchCancelled = $SearchCancelled ? $SearchCancelled : "";


        $strHiddenSearch = "<input type=hidden name='Search' value='true'>";
        $strHiddenSearch .= "<input type=hidden name='SearchJobNo' value='".$SearchJobNo."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchJobType' value='".$SearchJobType."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchCustomerName' value='".$SearchCustomerName."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchCustomerPOReceivedDateFrom' value='".$SearchCustomerPOReceivedDateFrom."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchCustomerPOReceivedDateTo' value='".$SearchCustomerPOReceivedDateTo."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchItems' value='".$SearchItems."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchSalesPersonID' value='".$SearchSalesPersonID."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchCompleted' value='".$SearchCompleted."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchClosed' value='".$SearchClosed."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchCancelled' value='".$SearchCancelled."'>";

        $this->view->optionJobType = $libDb->getSystemOptions("arrJobType", $this->view->SearchJobType);
        $this->view->optionSearchSalesPersonID = $libDb->getTableOptions("ACLUsers", "Name", "ID", $this->view->SearchSalesPersonID, "Name");

        $sysHelper->setFetchMode(Zend_Db::FETCH_NUM);
        $arrJobs = $sysHelper->getJobs($sortbyJob, $ascdescJob, $recordsPerPageJob, $showPageJob,  $sqlSearch);
        $dataJobs = $arrJobs[1];
        $exportSql = $arrJobs[2];

        $arrJobsTotalAmount = $sysHelper->getJobsTotalAmount($sqlSearch);
        $this->view->totalSellingPrice = number_format($arrJobsTotalAmount[0], 2, ".", ",");
        $this->view->totalProjectMargin = number_format($arrJobsTotalAmount[1], 2, ".", ",");
        $this->view->totalCurrentMargin = number_format($arrJobsTotalAmount[2], 2, ".", ",");
        $this->view->totalCost = number_format($arrJobsTotalAmount[3], 2, ".", ",");
        $totalSellingPrice = $arrJobsTotalAmount[0];
        $totalProjectMargin = $arrJobsTotalAmount[1];
        $totalCurrentMargin = $arrJobsTotalAmount[2];
        $totalCost = $arrJobsTotalAmount[3];

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

            return $rowdata[2].$strPrinciple;

        }


        function format_item($colnum, $rowdata){
            return $rowdata[3];

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
                    return "<div style='color: red; text-align: right'>".(!$export ? "RM " : "" ). number_format($Margin, 2, ".", ",").$linkChart."</div>";
                else
                    return "<div style='color: blue; text-align: right'>".(!$export ? "RM " : "" ). number_format($Margin, 2, ".", ",").$linkChart."</div>";
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
                    return "<div style='color: red; text-align: right'>".(!$export ? "RM " : "" ). number_format($Margin, 2, ".", ",").$linkChart."</div>";
                else
                    return "<div style='color: blue; text-align: right'>".(!$export ? "RM " : "" ). number_format($Margin, 2, ".", ",").$linkChart."</div>";
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

        function format_totalcost($colnum, $rowdata, $export)
        {
            return number_format($rowdata[24],2);
        }


        $arrHeaderMargin = array('', '', 'Job No',  'PO Received<BR>Date', 'Customer', 'Item', 'Job Type','Selling Price<BR>RM', 'Total Cost<BR>RM', 'Job/Project<BR>Margin', 'Current<BR>Margin', 'Sales Person');
        $arrFormatMargin = array('{format_counterJob}', '{format_action}','%1%', '{format_poreceived}', '{format_customer}', '{format_item}', '{format_jobtype}', '{format_sellingpricerm_margin}', '{format_totalcost}',  '{format_balanceproject}',  '{format_balance}', '{format_salesperson}');
        $arrSortMargin = array('','','Job.ID', 'Job.CustomerPOReceivedDate', 'Job.CustomerName', 'Job.Items', 'Job.JobType', 'JobSales.TotalSalesPriceRM','TotalCostRM','ProjectMarginRM','MarginRM', '');
        $arrColParamMargin = array('width=20px','width=20px','width=50px', '', '', 'width=100px', 'width=100px', 'nowrap width=120px','nowrap width=120px','width=120px','width=120px', 'width=120px');
        $aligndataMargin = 'CCCCLLCCCRRR'; $tablewidthMargin = '1650px';

        $exportReportJobMargin = new Venz_App_Report_Excel(array('exportsql'=> $exportSql,  'export_name'=>'export_excel_jobmargin',  'hiddenparam'=>$strHiddenSearch));

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

        $this->view->content_jobsmargin = $displayTableMargin->render();
        $sessionJobs->numCounter = $recordsPerPage * ($showPage-1);



        $export_excel_jobmargin_x = $Request->getParam('export_excel_jobmargin_x');
        if ($export_excel_jobmargin_x)
        {

            $db = Zend_Db_Table::getDefaultAdapter();
            $exportsql = $Request->getParam('exportsql');
            $exportReportJob = new Venz_App_Report_Excel(array('exportsql'=> base64_decode($exportsql), 'db'=>$db, 'exit'=>'No', 'hiddenparam' 	=> $strHiddenSearch, 'headings'=>$arrHeaderMargin, 'format'=>$arrFormatMargin));
            $exportReportJob->render();
            echo ",,,,,,,".$totalSellingPrice.",".$totalCost.",".$totalProjectMargin.",".$totalCurrentMargin.",\n";
            exit();

        }
    }

	public function deliveryObjectiveDetailsAction()
    {
		$Request = $this->getRequest();			
		$sysHelper = new Venz_App_System_Helper();
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
			
		$MonthPeriod = $Request->getParam('MonthPeriod');	
		$YearPeriod = $Request->getParam('YearPeriod');	
		
		if ($MonthPeriod && $YearPeriod)
		{
			$MonthYear = Date("M-Y", strtotime($YearPeriod . "-".$MonthPeriod."-01"));
			$fromPeriod = $YearPeriod."-".$MonthPeriod."-01 00:00:00";
			$toPeriod = Date("Y-m-t 23:59:59", strtotime($YearPeriod."-".$MonthPeriod."-01"));
			$sqlSearch = "AND JobSales.CustomerPOReceivedDate >= '".$fromPeriod."' AND JobSales.CustomerPOReceivedDate <= '".$toPeriod."' ";
			
			$sortby = 'Job.CustomerPOReceivedDate';
			$ascdesc = 'asc'; 
			$showPage = 1; 
			
			$recordsPerPage = 1000 ;
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_ASSOC);
			$arrJobs = $sysHelper->getJobsSales($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$dataJobsAll = $arrJobs[1];
			$data = "<h3>Report: $MonthYear</h3>".
				"<table class='report_list' border=1 cellspacing=4 cellpadding=4 WIDTH=100%>".
				"<TR><TD>Job No</TD><TD>Date Received Cust. PO</TD><TD>Customer</TD><TD>PO</TD><TD>Items</TD><TD>Customer Expected Date</TD>".
				"<TD>Goods Ready Date</TD><TD>No. Workdays<BR>Exceeded</TD></TR>";
			foreach ($dataJobsAll as $dataJobs)
			{
				if (strtotime($dataJobs['SalesExpDate']) < strtotime($dataJobs['SalesReadyDate']))
				{
					$WorkDays = $dispFormat->networkdays($dataJobs['SalesExpDate'], $dataJobs['SalesReadyDate'])-1;
					$CustomerPOReceivedDate = "";
					if ($dataJobs['CustomerPOReceivedDate'])
						$CustomerPOReceivedDate = $dispFormat->format_date_db_to_simple($dataJobs['CustomerPOReceivedDate']);
					
					$SalesExpDate = "";
					if ($dataJobs['SalesExpDate'])
						$SalesExpDate = $dispFormat->format_date_db_to_simple($dataJobs['SalesExpDate'])."<BR>".Date("D", strtotime($dataJobs['SalesExpDate']));
					
					$SalesReadyDate = "";
					if ($dataJobs['SalesReadyDate'])
						$SalesReadyDate = $dispFormat->format_date_db_to_simple($dataJobs['SalesReadyDate'])."<BR>".Date("D", strtotime($dataJobs['SalesReadyDate']));
					
					
					$listDocESPO = "";$listDocCustPO = "";
					if ($dataJobs[EOGSTSBPO])
						$listDocESPO = '<label>EOG & STSB:<BR>'.str_replace("|", ", ", substr($dataJobs[EOGSTSBPO],1)).'</label><BR>';
					if ($dataJobs[CustomerPO])
						$listDocCustPO = '<label>Customer PO:<BR>'.str_replace("|", ", ", substr($dataJobs[CustomerPO],1)).'</label><BR>';
					
					$strPO = $listDocESPO.$listDocCustPO;			

				
					$data .=<<<END
				<TR><TD>$dataJobs[JobNo]</TD><TD>$CustomerPOReceivedDate</TD><TD>$dataJobs[CustomerName]</TD><TD style='text-align: left;'>$strPO</TD><TD style='text-align: left;'>$dataJobs[Items]</TD>
					<TD style='color: red;'>$SalesExpDate</TD><TD style='color: red;'>$SalesReadyDate</TD><TD style='color: red;'>$WorkDays  
					<input type=hidden class='clsDateRange$dataJobs[ID]'><img ID='$dataJobs[ID]' StartDate='$dataJobs[SalesExpDate]' EndDate='$dataJobs[SalesReadyDate]' class='clsDateImg' style='cursor: pointer' src='/images/icons/calendar.jpg'></TD></TR>	
END;
				}
			}
			echo $data."</table>"; 
			
		}
		
		exit();
	}
	
	public function deliveryObjectiveAction()
    {
		$db = Zend_Db_Table::getDefaultAdapter();
		$systemSetting = new Zend_Session_Namespace('systemSetting');	
		$this->view->percent = $systemSetting->arrReportDeliveryObjective[Date("Y", time())];
		$fromPeriod = Date("Y-01-01 00:00:00", time());
		$toPeriod = Date("Y-12-31 23:59:59", time());
		
	//	$arrJobAll = $db->fetchAll("SELECT Job.CustomerPOReceivedDate, JobSales.SalesExpDate, JobSales.SalesReadyDate FROM Job, JobSales where JobSales.JobID=Job.ID AND Job.CustomerPOReceivedDate > '".$fromPeriod."' AND Job.CustomerPOReceivedDate < '".$toPeriod."'  AND (Job.Cancelled=0 OR Job.Cancelled IS NULL)");
		$arrJobAll = $db->fetchAll("SELECT * FROM Job where CustomerPOReceivedDate > '".$fromPeriod."' AND CustomerPOReceivedDate < '".$toPeriod."' AND  (Cancelled=0 OR Cancelled IS NULL) ");
		
		//print "---".sizeof($arrJobAll);
		$this->view->content_report = "";
		$arrDataAll = array();$arrDataQuarterAll = array();
		for ($date = strtotime($fromPeriod); $date < strtotime($toPeriod); $date += (31 * 24 * 60 * 60))
		{
			$arrDataAll[Date("M-Y", $date)][0] = "";
			$arrDataAll[Date("M-Y", $date)][1] = "";
			$arrDataAll[Date("M-Y", $date)][2] = "";
		}
		foreach ($arrJobAll as $arrJob)
		{
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][1]++;
			
			
			$arrSalesAll = $db->fetchAll("SELECT * FROM JobSales WHERE JobID=".$arrJob['ID']);
			foreach ($arrSalesAll as $arrSales)
			{
				
				if (strtotime($arrSales['SalesExpDate']) < strtotime($arrSales['SalesReadyDate']))
				{
					$arrDataAll[Date("M-Y", strtotime($arrSales['CustomerPOReceivedDate']))][0]++;
					
				}
			}
			
			
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] = $arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][0] / $arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][1];
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] = number_format($arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] * 100, 2);
			
			
		}
		
		$iCount = 1; $totalPercentage = 0; $quarter = 1;
		foreach ($arrDataAll as $month => $data)
		{
			$totalPercentage += $data[2];
			if (($iCount % 3) == 0){
				$arrDataQuarterAll[$quarter] = number_format(($totalPercentage/3),2);
				$quarter++;
				$totalPercentage = 0;
			}
			$iCount++;
		}

				
		$iCount = 0; $quarter = 1;
		foreach ($arrDataAll as $month => $data)
		{
			$arrDate = explode("-", $month);
			$strMonth = Date("m", strtotime($arrDate[0]." 01 2000"));
			$this->view->content_report .= "<TR>";
			$this->view->content_report .= "<TD>".$month."</TD>";
			$this->view->content_report .= "<TD><B><a class='clsLoadDetail' MonthPeriod='".$strMonth."' YearPeriod='".$arrDate[1]."' href='#'>".$data[0]."</a></B></TD>";
			$this->view->content_report .= "<TD>".$data[1]."</TD>";
			if ($data[2] > $this->view->percent)
				$this->view->content_report .= "<TD style='color:red;'>".$data[2]."</TD>";
			else
				$this->view->content_report .= "<TD>".$data[2]."</TD>";
			
			if (($iCount % 3) == 0){
				if ($arrDataQuarterAll[$quarter] > $this->view->percent)
					$this->view->content_report .= "<TD rowspan=3  style='color:red;'>".$arrDataQuarterAll[$quarter]."</TD>";
				else
					$this->view->content_report .= "<TD rowspan=3>".$arrDataQuarterAll[$quarter]."</TD>";
				$quarter++;
			}
			
			$iCount++;
			$this->view->content_report .= "</TR>";


		}
	
		
		
    }
	
	public function purchaseObjectiveDetailsAction()
    {
		$Request = $this->getRequest();			
		$sysHelper = new Venz_App_System_Helper();
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		$systemSetting = new Zend_Session_Namespace('systemSetting');	
		$this->view->days = $systemSetting->arrReportPurchaseObjectiveDays[Date("Y", time())];
			
		$MonthPeriod = $Request->getParam('MonthPeriod');	
		$YearPeriod = $Request->getParam('YearPeriod');	
		
		if ($MonthPeriod && $YearPeriod)
		{
			$MonthYear = Date("M-Y", strtotime($YearPeriod . "-".$MonthPeriod."-01"));
			$fromPeriod = $YearPeriod."-".$MonthPeriod."-01 00:00:00";
			$toPeriod = Date("Y-m-t 23:59:59", strtotime($YearPeriod."-".$MonthPeriod."-01"));
			$sqlSearch = "AND Job.CustomerPOReceivedDate >= '".$fromPeriod."' AND Job.CustomerPOReceivedDate <= '".$toPeriod."'  AND Job.JobType != 'P' AND (Job.Cancelled=0 OR Job.Cancelled IS NULL)";
			
			$sortby = 'Job.CustomerPOReceivedDate';
			$ascdesc = 'asc'; 
			$showPage = 1; 
			
			$recordsPerPage = 1000 ;
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_ASSOC);
//			$arrJobs = $sysHelper->getJobs($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$arrJobsPurchase = $sysHelper->getJobsPurchase($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			
			$dataJobsAll = $arrJobsPurchase[1];
			$data = "<h3>Report: $MonthYear</h3>".
				"<table class='report_list' border=1 cellspacing=4 cellpadding=4 WIDTH=100%>".
				"<TR><TD>Job No</TD><TD>Date Received<BR>Cust. PO</TD><TD>Customer</TD><TD>PO</TD><TD>Items</TD><TD>Customer<BR>Expected Date</TD>".
				"<TD>PO No.</TD><TD>Principal / Suppliers</TD><TD>PO Faxed Out Date</TD><TD>Days</TD></TR>";
			foreach ($dataJobsAll as $dataJobs)
			{
				
//				$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase WHERE JobID=".$dataJobs['ID']);
//				foreach ($arrPurchaseAll as $arrPurchase)
//				{
					$days = $dispFormat->networkdays($dataJobs['CustomerPOReceivedDate'], $dataJobs['POFaxedDate'])-1;
				//	if ($days > $this->view->days || $arrPurchase['POFaxedDate'] == "")
					if ($days > $this->view->days)
					{
						$days = $days > 0 ? $days : "";
						$img = $days > 0 ? "<img ID='$dataJobs[ID]' StartDate='$dataJobs[CustomerPOReceivedDate]' EndDate='$dataJobs[POFaxedDate]' class='clsDateImg' style='cursor: pointer' src='/images/icons/calendar.jpg'>" : "";
						$CustomerPOReceivedDate = "";
						if ($dataJobs['CustomerPOReceivedDate'])
							$CustomerPOReceivedDate = $dispFormat->format_date_db_to_simple($dataJobs['CustomerPOReceivedDate']);
						
						$SalesExpDate = "";
						if ($dataJobs['SalesExpDate'])
							$SalesExpDate = $dispFormat->format_date_db_to_simple($dataJobs['SalesExpDate']);
						
						$POFaxedDate = "";
						if ($dataJobs['POFaxedDate'])
							$POFaxedDate = $dispFormat->format_date_db_to_simple($dataJobs['POFaxedDate']);
						
						
						$listDocESPO = "";$listDocCustPO = "";
						if ($dataJobs[EOGSTSBPO])
							$listDocESPO = '<label>EOG & STSB:<BR>'.str_replace("|", ", ", substr($dataJobs[EOGSTSBPO],1)).'</label><BR>';
						if ($dataJobs[CustomerPO])
							$listDocCustPO = '<label>Customer PO:<BR>'.str_replace("|", ", ", substr($dataJobs[CustomerPO],1)).'</label><BR>';
						
						$strPO = $listDocESPO.$listDocCustPO;		

						$PONo = str_replace("|", ", ", substr($arrPurchase['PONo'],1));
				
						$data .=<<<END
				<TR><TD>$dataJobs[JobNo]</TD><TD style='color: red;'>$CustomerPOReceivedDate</TD><TD>$dataJobs[CustomerName]</TD><TD style='text-align: left'>$strPO</TD><TD style='text-align: left'>$dataJobs[Items]</TD>
					<TD>$SalesExpDate</TD><TD style='text-align: left'>$strPO</TD><TD>$arrPurchase[SupplierName]</TD><TD style='color: red;'>$POFaxedDate</TD>
					<TD style='color: red;'>$days 
					<input type=hidden class='clsDateRange$dataJobs[ID]'>
					$img
					</TD></TR>	
END;
					}				
//				}

			}
			echo $data."</table>"; 
			
		}
		
		exit();
	}
	
	public function purchaseObjectiveAction()
    {
		////////////////////////////////////////////////////////////////////////////////
		////////////// NEED TO INCORPORATE MALAYSIA PUBLIC HOLIDAY /////////////////////
		////////////////////////////////////////////////////////////////////////////////
		
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		$systemSetting = new Zend_Session_Namespace('systemSetting');	
		$this->view->percent = $systemSetting->arrReportPurchaseObjective[Date("Y", time())];
		$this->view->days = $systemSetting->arrReportPurchaseObjectiveDays[Date("Y", time())];
		$fromPeriod = Date("Y-01-01 00:00:00", time());
		$toPeriod = Date("Y-12-31 23:59:59", time());
		
//		$arrJobAll = $db->fetchAll("SELECT * FROM Job where CustomerPOReceivedDate > '".$fromPeriod."' AND CustomerPOReceivedDate < '".$toPeriod."' AND (Cancelled=0 OR Cancelled IS NULL)");
		
//		$arrJobAll = $db->fetchAll("SELECT Job.*, JobPurchase.POFaxedDate FROM Job, JobPurchase where Job.JobType != 'P' AND JobPurchase.JobID=Job.ID AND Job.CustomerPOReceivedDate > '".$fromPeriod."' AND Job.CustomerPOReceivedDate < '".$toPeriod."' AND (Job.Cancelled=0 OR Job.Cancelled IS NULL)");
		$arrJobAll = $db->fetchAll("SELECT * FROM Job where Job.JobType != 'P' AND CustomerPOReceivedDate > '".$fromPeriod."' AND CustomerPOReceivedDate < '".$toPeriod."' AND (Cancelled=0 OR Cancelled IS NULL)");
		
		$this->view->content_report = "";
		$arrDataAll = array();
		for ($date = strtotime($fromPeriod); $date < strtotime($toPeriod); $date += (31 * 24 * 60 * 60))
		{
			$arrDataAll[Date("M-Y", $date)][0] = "";
			$arrDataAll[Date("M-Y", $date)][1] = "";
			$arrDataAll[Date("M-Y", $date)][2] = "";
		}
		
		foreach ($arrJobAll as $arrJob)
		{
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][1]++;
			
			$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase WHERE JobID=".$arrJob['ID']." AND POFaxedDate IS NOT NULL order by POFaxedDate ASC");
//			$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase WHERE JobID=".$arrJob['ID']);
			foreach ($arrPurchaseAll as $arrPurchase)
			{
				$days = $dispFormat->networkdays($arrJob['CustomerPOReceivedDate'], $arrPurchase['POFaxedDate'])-1;
			//	if ($days > $this->view->days || $arrPurchase['POFaxedDate'] == "")
				if ($days > $this->view->days)
				{
					$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][0]++;
				}				
			}
			

			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] = $arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][0] / $arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][1];
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] = number_format($arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] * 100, 2);
		}
		
		$iCount = 1; $totalPercentage = 0; $quarter = 1;
		foreach ($arrDataAll as $month => $data)
		{
			$totalPercentage += $data[2];
			if (($iCount % 3) == 0){
				$arrDataQuarterAll[$quarter] = number_format(($totalPercentage/3),2);
				$quarter++;
				$totalPercentage = 0;
			}
			$iCount++;
		}
		
		$iCount = 0; $quarter = 1;
		foreach ($arrDataAll as $month => $data)
		{
			$arrDate = explode("-", $month);
			$strMonth = Date("m", strtotime($arrDate[0]." 01 2000"));
			
			$this->view->content_report .= "<TR>";
			$this->view->content_report .= "<TD>".$month."</TD>";
			$this->view->content_report .= "<TD><B><a class='clsLoadDetail' MonthPeriod='".$strMonth."' YearPeriod='".$arrDate[1]."' href='#'>".$data[0]."</a></TD>";
			$this->view->content_report .= "<TD>".$data[1]."</TD>";
			if ($data[2] > $this->view->percent)
				$this->view->content_report .= "<TD style='color:red;'>".$data[2]."</TD>";
			else
				$this->view->content_report .= "<TD>".$data[2]."</TD>";
						
			if (($iCount % 3) == 0){
				if ($arrDataQuarterAll[$quarter] > $this->view->percent)
					$this->view->content_report .= "<TD rowspan=3  style='color:red;'>".$arrDataQuarterAll[$quarter]."</TD>";
				else
					$this->view->content_report .= "<TD rowspan=3>".$arrDataQuarterAll[$quarter]."</TD>";
				$quarter++;
			}
			
			$iCount++;
			$this->view->content_report .= "</TR>";


		}
	
		
		
    }


	public function purchaseObjectiveDetails2Action()
    {
		$Request = $this->getRequest();			
		$sysHelper = new Venz_App_System_Helper();
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		$systemSetting = new Zend_Session_Namespace('systemSetting');	
		$this->view->days = $systemSetting->arrReportPurchaseObjective2Days[Date("Y", time())];
			
		$MonthPeriod = $Request->getParam('MonthPeriod');	
		$YearPeriod = $Request->getParam('YearPeriod');	
		
		if ($MonthPeriod && $YearPeriod)
		{
			$MonthYear = Date("M-Y", strtotime($YearPeriod . "-".$MonthPeriod."-01"));
			$fromPeriod = $YearPeriod."-".$MonthPeriod."-01 00:00:00";
			$toPeriod = Date("Y-m-t 23:59:59", strtotime($YearPeriod."-".$MonthPeriod."-01"));
			$sqlSearch = "AND Job.CustomerPOReceivedDate >= '".$fromPeriod."' AND Job.CustomerPOReceivedDate <= '".$toPeriod."'  AND Job.JobType = 'P' AND (Job.Cancelled=0 OR Job.Cancelled IS NULL)";
			
			$sortby = 'Job.CustomerPOReceivedDate';
			$ascdesc = 'asc'; 
			$showPage = 1; 
			
			$recordsPerPage = 1000 ;
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_ASSOC);
//			$arrJobs = $sysHelper->getJobs($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$arrJobsPurchase = $sysHelper->getJobsPurchase($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			
			$dataJobsAll = $arrJobsPurchase[1];
			$data = "<h3>Report: $MonthYear</h3>".
				"<table class='report_list' border=1 cellspacing=4 cellpadding=4 WIDTH=100%>".
				"<TR><TD>Job No</TD><TD>Date Received<BR>Cust. PO</TD><TD>Customer</TD><TD>PO</TD><TD>Items</TD><TD>Customer<BR>Expected Date</TD>".
				"<TD>PO No.</TD><TD>Principal / Suppliers</TD><TD>PO Faxed Out Date</TD><TD>Days</TD></TR>";
			foreach ($dataJobsAll as $dataJobs)
			{
				
//				$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase WHERE JobID=".$dataJobs['ID']);
//				foreach ($arrPurchaseAll as $arrPurchase)
//				{
					$days = $dispFormat->networkdays($dataJobs['CustomerPOReceivedDate'], $dataJobs['POFaxedDate'])-1;
				//	if ($days > $this->view->days || $arrPurchase['POFaxedDate'] == "")
					if ($days > $this->view->days)
					{
						$days = $days > 0 ? $days : "";
						$img = $days > 0 ? "<img ID='$dataJobs[ID]' StartDate='$dataJobs[CustomerPOReceivedDate]' EndDate='$dataJobs[POFaxedDate]' class='clsDateImg' style='cursor: pointer' src='/images/icons/calendar.jpg'>" : "";
						$CustomerPOReceivedDate = "";
						if ($dataJobs['CustomerPOReceivedDate'])
							$CustomerPOReceivedDate = $dispFormat->format_date_db_to_simple($dataJobs['CustomerPOReceivedDate']);
						
						$SalesExpDate = "";
						if ($dataJobs['SalesExpDate'])
							$SalesExpDate = $dispFormat->format_date_db_to_simple($dataJobs['SalesExpDate']);
						
						$POFaxedDate = "";
						if ($dataJobs['POFaxedDate'])
							$POFaxedDate = $dispFormat->format_date_db_to_simple($dataJobs['POFaxedDate']);
						
						
						$listDocESPO = "";$listDocCustPO = "";
						if ($dataJobs[EOGSTSBPO])
							$listDocESPO = '<label>EOG & STSB:<BR>'.str_replace("|", ", ", substr($dataJobs[EOGSTSBPO],1)).'</label><BR>';
						if ($dataJobs[CustomerPO])
							$listDocCustPO = '<label>Customer PO:<BR>'.str_replace("|", ", ", substr($dataJobs[CustomerPO],1)).'</label><BR>';
						
						$strPO = $listDocESPO.$listDocCustPO;		

						$PONo = str_replace("|", ", ", substr($arrPurchase['PONo'],1));
				
						$data .=<<<END
				<TR><TD>$dataJobs[JobNo]</TD><TD style='color: red;'>$CustomerPOReceivedDate</TD><TD>$dataJobs[CustomerName]</TD><TD style='text-align: left'>$strPO</TD><TD style='text-align: left'>$dataJobs[Items]</TD>
					<TD>$SalesExpDate</TD><TD style='text-align: left'>$strPO</TD><TD>$arrPurchase[SupplierName]</TD><TD style='color: red;'>$POFaxedDate</TD>
					<TD style='color: red;'>$days 
					<input type=hidden class='clsDateRange$dataJobs[ID]'>
					$img
					</TD></TR>	
END;
					}				
//				}

			}
			echo $data."</table>"; 
			
		}
		
		exit();
	}
		
	public function purchaseObjective2Action()
    {
		////////////////////////////////////////////////////////////////////////////////
		////////////// NEED TO INCORPORATE MALAYSIA PUBLIC HOLIDAY /////////////////////
		////////////////////////////////////////////////////////////////////////////////
		
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		$systemSetting = new Zend_Session_Namespace('systemSetting');	
		$this->view->percent = $systemSetting->arrReportPurchaseObjective2[Date("Y", time())];
		$this->view->days = $systemSetting->arrReportPurchaseObjective2Days[Date("Y", time())];
		$fromPeriod = Date("Y-01-01 00:00:00", time());
		$toPeriod = Date("Y-12-31 23:59:59", time());
		
//		$arrJobAll = $db->fetchAll("SELECT * FROM Job where CustomerPOReceivedDate > '".$fromPeriod."' AND CustomerPOReceivedDate < '".$toPeriod."' AND (Cancelled=0 OR Cancelled IS NULL)");
		
//		$arrJobAll = $db->fetchAll("SELECT Job.*, JobPurchase.POFaxedDate FROM Job, JobPurchase where Job.JobType = 'P' AND JobPurchase.JobID=Job.ID AND Job.CustomerPOReceivedDate > '".$fromPeriod."' AND Job.CustomerPOReceivedDate < '".$toPeriod."' AND (Job.Cancelled=0 OR Job.Cancelled IS NULL)");
		$arrJobAll = $db->fetchAll("SELECT * FROM Job where Job.JobType = 'P' AND CustomerPOReceivedDate > '".$fromPeriod."' AND CustomerPOReceivedDate < '".$toPeriod."' AND (Cancelled=0 OR Cancelled IS NULL)");
		
		$this->view->content_report = "";
		$arrDataAll = array();
		for ($date = strtotime($fromPeriod); $date < strtotime($toPeriod); $date += (31 * 24 * 60 * 60))
		{
			$arrDataAll[Date("M-Y", $date)][0] = "";
			$arrDataAll[Date("M-Y", $date)][1] = "";
			$arrDataAll[Date("M-Y", $date)][2] = "";
		}
		
		foreach ($arrJobAll as $arrJob)
		{
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][1]++;
			
		//	$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase WHERE JobID=".$arrJob['ID']." AND POFaxedDate IS NOT NULL order by POFaxedDate ASC");
			$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase WHERE JobID=".$arrJob['ID']);
//			print $arrJob['ID'] . "--<BR>";
			foreach ($arrPurchaseAll as $arrPurchase)
			{
			//	$days = $dispFormat->networkdays($arrJob['CustomerPOReceivedDate'], $arrPurchase['POFaxedDate'])-1;
			//	if ($days > $this->view->days || $arrPurchase['POFaxedDate'] == "")
				$days = $dispFormat->networkdays($arrJob['CustomerPOReceivedDate'], $arrPurchase['POFaxedDate'])-1;
//				print $days."--".$arrJob['CustomerPOReceivedDate']."--".$arrJob['POFaxedDate']."<BR>";
				if ($days > $this->view->days)
				{
					$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][0]++;
				}				
			}
//			print "<HR>";

			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] = $arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][0] / $arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][1];
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] = number_format($arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] * 100, 2);
		}
		
		$iCount = 1; $totalPercentage = 0; $quarter = 1;
		foreach ($arrDataAll as $month => $data)
		{
			$totalPercentage += $data[2];
			if (($iCount % 3) == 0){
				$arrDataQuarterAll[$quarter] = number_format(($totalPercentage/3),2);
				$quarter++;
				$totalPercentage = 0;
			}
			$iCount++;
		}
		
		$iCount = 0; $quarter = 1;
		foreach ($arrDataAll as $month => $data)
		{
			$arrDate = explode("-", $month);
			$strMonth = Date("m", strtotime($arrDate[0]." 01 2000"));
			
			$this->view->content_report .= "<TR>";
			$this->view->content_report .= "<TD>".$month."</TD>";
			$this->view->content_report .= "<TD><B><a class='clsLoadDetail' MonthPeriod='".$strMonth."' YearPeriod='".$arrDate[1]."' href='#'>".$data[0]."</a></TD>";
			$this->view->content_report .= "<TD>".$data[1]."</TD>";
			if ($data[2] > $this->view->percent)
				$this->view->content_report .= "<TD style='color:red;'>".$data[2]."</TD>";
			else
				$this->view->content_report .= "<TD>".$data[2]."</TD>";
						
			if (($iCount % 3) == 0){
				if ($arrDataQuarterAll[$quarter] > $this->view->percent)
					$this->view->content_report .= "<TD rowspan=3  style='color:red;'>".$arrDataQuarterAll[$quarter]."</TD>";
				else
					$this->view->content_report .= "<TD rowspan=3>".$arrDataQuarterAll[$quarter]."</TD>";
				$quarter++;
			}
			
			$iCount++;
			$this->view->content_report .= "</TR>";


		}
	
		
		
    }
	
	public function lateDeliveryDetailsAction()
    {
		$Request = $this->getRequest();			
		$sysHelper = new Venz_App_System_Helper();
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
			
		$MonthPeriod = $Request->getParam('MonthPeriod');	
		$YearPeriod = $Request->getParam('YearPeriod');	
		
		if ($MonthPeriod && $YearPeriod)
		{
			$MonthYear = Date("M-Y", strtotime($YearPeriod . "-".$MonthPeriod."-01"));
			$fromPeriod = $YearPeriod."-".$MonthPeriod."-01 00:00:00";
			$toPeriod = Date("Y-m-t 23:59:59", strtotime($YearPeriod."-".$MonthPeriod."-01"));
			$sqlSearch = "AND CustomerPOReceivedDate >= '".$fromPeriod."' AND CustomerPOReceivedDate <= '".$toPeriod."' AND (Cancelled=0 OR Cancelled IS NULL)";
			
			$sortby = 'Job.CustomerPOReceivedDate';
			$ascdesc = 'asc'; 
			$showPage = 1; 
			
			$recordsPerPage = 1000 ;
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_ASSOC);
			$arrJobs = $sysHelper->getJobs($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$dataJobsAll = $arrJobs[1];
			$data = "<h3>Report: $MonthYear</h3>".
				"<table class='report_list' border=1 cellspacing=4 cellpadding=4 WIDTH=100%>".
				"<TR><TD>Job No</TD><TD>Date Received<BR>Cust. PO</TD><TD>Customer</TD><TD>PO</TD><TD>Items</TD><TD>Customer<BR>Expected Date</TD>".
				"<TD>PO No.</TD><TD>Principal / Suppliers</TD><TD>Scheduled<BR>Shipping Date</TD><TD>Actual<BR>Shipping Date</TD><TD>Workdays</TD></TR>";
			foreach ($dataJobsAll as $dataJobs)
			{
				
				$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase WHERE JobID=".$dataJobs['ID']);
				foreach ($arrPurchaseAll as $arrPurchase)
				{
					if (strtotime($arrPurchase['PurchaseShippingDate']) < strtotime($arrPurchase['PurchaseShippingActualDate']))
					{
						$days = $dispFormat->networkdays($arrPurchase['PurchaseShippingDate'], $arrPurchase['PurchaseShippingActualDate'])-1;
						$days = $days > 0 ? $days : "";
						$img = $days > 0 ? "<img ID='$dataJobs[ID]' StartDate='$arrPurchase[PurchaseShippingDate]' EndDate='$arrPurchase[PurchaseShippingActualDate]' class='clsDateImg' style='cursor: pointer' src='/images/icons/calendar.jpg'>" : "";
						$CustomerPOReceivedDate = "";
						if ($dataJobs['CustomerPOReceivedDate'])
							$CustomerPOReceivedDate = $dispFormat->format_date_db_to_simple($dataJobs['CustomerPOReceivedDate']);
						
						$SalesExpDate = "";
						if ($dataJobs['SalesExpDate'])
							$SalesExpDate = $dispFormat->format_date_db_to_simple($dataJobs['SalesExpDate']);
						
						$PurchaseShippingDate = "";
						if ($arrPurchase['PurchaseShippingDate'])
							$PurchaseShippingDate = $dispFormat->format_date_db_to_simple($arrPurchase['PurchaseShippingDate']);
						
						$PurchaseShippingActualDate = "";
						if ($arrPurchase['PurchaseShippingActualDate'])
							$PurchaseShippingActualDate = $dispFormat->format_date_db_to_simple($arrPurchase['PurchaseShippingActualDate']);
						
						
						
						$listDocESPO = "";$listDocCustPO = "";
						if ($dataJobs[EOGSTSBPO])
							$listDocESPO = '<label>EOG & STSB:<BR>'.str_replace("|", ", ", substr($dataJobs[EOGSTSBPO],1)).'</label><BR>';
						if ($dataJobs[CustomerPO])
							$listDocCustPO = '<label>Customer PO:<BR>'.str_replace("|", ", ", substr($dataJobs[CustomerPO],1)).'</label><BR>';
						
						$strPO = $listDocESPO.$listDocCustPO;			
						$PONo = str_replace("|", ", ", substr($arrPurchase[PONo],1));
				
						$data .=<<<END
				<TR><TD>$dataJobs[JobNo]</TD><TD>$CustomerPOReceivedDate</TD><TD>$dataJobs[CustomerName]</TD><TD style='text-align: left;'>$strPO</TD><TD style='text-align: left;'>$dataJobs[Items]</TD>
					<TD>$SalesExpDate</TD><TD style='text-align: left;'>$PONo</TD><TD>$arrPurchase[SupplierName]</TD>
					<TD style='color: red;'>$PurchaseShippingDate</TD><TD style='color: red;'>$PurchaseShippingActualDate</TD>
					<TD style='color: red;'>$days 
					<input type=hidden class='clsDateRange$dataJobs[ID]'>
					$img
					</TD></TR>	
END;
					}				
				}

			}
			echo $data."</table>"; 
			
		}
		
		exit();
	}
	
	public function lateDeliveryAction()
    {
		$db = Zend_Db_Table::getDefaultAdapter();
		$systemSetting = new Zend_Session_Namespace('systemSetting');	
		$this->view->percent = $systemSetting->arrReportLateDelivery[Date("Y", time())];
		$fromPeriod = Date("Y-01-01 00:00:00", time());
		$toPeriod = Date("Y-12-31 23:59:59", time());
		
		$arrJobAll = $db->fetchAll("SELECT * FROM Job where CustomerPOReceivedDate > '".$fromPeriod."' AND CustomerPOReceivedDate < '".$toPeriod."' AND  (Job.Cancelled=0 OR Job.Cancelled IS NULL) ");
		//print "---".sizeof($arrJobAll);
		$this->view->content_report = "";
		$arrDataAll = array();
		for ($date = strtotime($fromPeriod); $date < strtotime($toPeriod); $date += (31 * 24 * 60 * 60))
		{
			$arrDataAll[Date("M-Y", $date)][0] = "";
			$arrDataAll[Date("M-Y", $date)][1] = "";
			$arrDataAll[Date("M-Y", $date)][2] = "";
		}
		
		foreach ($arrJobAll as $arrJob)
		{
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][1]++;
			
			$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase WHERE JobID=".$arrJob['ID']);
			foreach ($arrPurchaseAll as $arrPurchase)
			{
				
				if (strtotime($arrPurchase['PurchaseShippingDate']) < strtotime($arrPurchase['PurchaseShippingActualDate']))
				{
					$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][0]++;
				}
			}
				
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] = $arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][0] / 
				$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][1];
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] = number_format($arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] * 100, 2);
		}
		
		$iCount = 1; $totalPercentage = 0; $quarter = 1;
		foreach ($arrDataAll as $month => $data)
		{
			$totalPercentage += $data[2];
			if (($iCount % 3) == 0){
				$arrDataQuarterAll[$quarter] = number_format(($totalPercentage/3),2);
				$quarter++;
				$totalPercentage = 0;
			}
			$iCount++;
		}
		
		
		$iCount = 0; $quarter = 1;
		foreach ($arrDataAll as $month => $data)
		{
			$arrDate = explode("-", $month);
			$strMonth = Date("m", strtotime($arrDate[0]." 01 2000"));
			$this->view->content_report .= "<TR>";
			$this->view->content_report .= "<TD>".$month."</TD>";
			$this->view->content_report .= "<TD><a class='clsLoadDetail' MonthPeriod='".$strMonth."' YearPeriod='".$arrDate[1]."' href='#'>".$data[0]."</a></TD>";
			$this->view->content_report .= "<TD>".$data[1]."</TD>";
			if ($data[2] > $this->view->percent)
				$this->view->content_report .= "<TD style='color:red;'>".$data[2]."</TD>";
			else
				$this->view->content_report .= "<TD>".$data[2]."</TD>";
			if (($iCount % 3) == 0){
				if ($arrDataQuarterAll[$quarter] > $this->view->percent)
					$this->view->content_report .= "<TD rowspan=3  style='color:red;'>".$arrDataQuarterAll[$quarter]."</TD>";
				else
					$this->view->content_report .= "<TD rowspan=3>".$arrDataQuarterAll[$quarter]."</TD>";
				$quarter++;
			}
			
			$iCount++;
			$this->view->content_report .= "</TR>";


		}
	
		
		
    }
	
	public function drawingApprovalObjectiveDetailsAction()
    {
		$Request = $this->getRequest();			
		$sysHelper = new Venz_App_System_Helper();
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		
		$systemSetting = new Zend_Session_Namespace('systemSetting');	
		$this->view->days = $systemSetting->arrDrawingApproval[Date("Y", time())];
		$MonthPeriod = $Request->getParam('MonthPeriod');	
		$YearPeriod = $Request->getParam('YearPeriod');	

		if ($MonthPeriod && $YearPeriod)
		{
			$MonthYear = Date("M-Y", strtotime($YearPeriod . "-".$MonthPeriod."-01"));
			$fromPeriod = $YearPeriod."-".$MonthPeriod."-01 00:00:00";
			$toPeriod = Date("Y-m-t 23:59:59", strtotime($YearPeriod."-".$MonthPeriod."-01"));
			$sqlSearch = "AND CustomerPOReceivedDate >= '".$fromPeriod."' AND CustomerPOReceivedDate <= '".$toPeriod."'  AND JobType='P' AND  (Cancelled=0 OR Cancelled IS NULL) ";
			
			$sortby = 'Job.CustomerPOReceivedDate';
			$ascdesc = 'asc'; 
			$showPage = 1; 
			
			$recordsPerPage = 1000 ;
			
			$sysHelper->setFetchMode(Zend_Db::FETCH_ASSOC);
			$arrJobs = $sysHelper->getJobs($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$dataJobsAll = $arrJobs[1];
			$data = "<h3>Report: $MonthYear</h3>".
				"<table class='report_list' border=1 cellspacing=4 cellpadding=4 WIDTH=100%>".
				"<TR><TD>Job No</TD><TD>Date Received<BR>Cust. PO</TD><TD>Customer</TD><TD>PO</TD><TD>Items</TD><TD>Customer<BR>Expected Date</TD>".
				"<TD>PO No.</TD><TD>Principal / Suppliers</TD><TD>Drawing<BR>Approved Date</TD><TD>Scheduled<BR>PO Faxed Out Date</TD><TD>Workdays</TD></TR>";
			foreach ($dataJobsAll as $dataJobs)
			{
				
				$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase WHERE JobID=".$dataJobs['ID']);
				foreach ($arrPurchaseAll as $arrPurchase)
				{
					$days = $dispFormat->networkdays($dataJobs['CustomerPOReceivedDate'], $arrPurchase['POFaxedDate'])-1;
					//print $days."--".$this->view->days."--".$dataJobs['CustomerPOReceivedDate']."--".$arrPurchase['POFaxedDate']."<BR>"; 
					if ($days > $this->view->days)
					{
						$days = $days > 0 ? $days : "";
						$img = $days > 0 ? "<img ID='$dataJobs[ID]' StartDate='$dataJobs[CustomerPOReceivedDate]' EndDate='$arrPurchase[POFaxedDate]' class='clsDateImg' style='cursor: pointer' src='/images/icons/calendar.jpg'>" : "";
						$CustomerPOReceivedDate = "";
						if ($dataJobs['CustomerPOReceivedDate'])
							$CustomerPOReceivedDate = $dispFormat->format_date_db_to_simple($dataJobs['CustomerPOReceivedDate']);
						
						$SalesExpDate = "";
						if ($dataJobs['SalesExpDate'])
							$SalesExpDate = $dispFormat->format_date_db_to_simple($dataJobs['SalesExpDate']);
						
						$POFaxedDate = "";
						if ($arrPurchase['POFaxedDate'])
							$POFaxedDate = $dispFormat->format_date_db_to_simple($arrPurchase['POFaxedDate']);
						
						$DrawingApprovedDate = "";
						if ($dataJobs['DrawingApprovedDate']){
							$daysDrawing = $dispFormat->networkdays($dataJobs['CustomerPOReceivedDate'], $dataJobs['DrawingApprovedDate'])-1;
							$DrawingApprovedDate = $dispFormat->format_date_db_to_simple($dataJobs['DrawingApprovedDate']) . "<BR>(".$daysDrawing." days)";

						}
						

						
						$listDocESPO = "";$listDocCustPO = "";
						if ($dataJobs[EOGSTSBPO])
							$listDocESPO = '<label>EOG & STSB:<BR>'.str_replace("|", ", ", substr($dataJobs[EOGSTSBPO],1)).'</label><BR>';
						if ($dataJobs[CustomerPO])
							$listDocCustPO = '<label>Customer PO:<BR>'.str_replace("|", ", ", substr($dataJobs[CustomerPO],1)).'</label><BR>';
						
						$strPO = $listDocESPO.$listDocCustPO;			
						$PONo = str_replace("|", ", ", substr($arrPurchase[PONo],1));
				
						$data .=<<<END
				<TR><TD>$dataJobs[JobNo]</TD><TD style='color: red;'>$CustomerPOReceivedDate</TD><TD>$dataJobs[CustomerName]</TD><TD style='text-align: left;'>$strPO</TD><TD style='text-align: left;'>$dataJobs[Items]</TD>
					<TD>$SalesExpDate</TD><TD style='text-align: left;'>$PONo</TD><TD>$arrPurchase[SupplierName]</TD>
					<TD >$DrawingApprovedDate</TD>
					<TD style='color: red;'>$POFaxedDate</TD>
					<TD style='color: red;'>$days 
					<input type=hidden class='clsDateRange$dataJobs[ID]'>
					$img
					</TD></TR>	
END;
					}				
				}

			}
			echo $data."</table>"; 
			
		}
		
		exit();
	}
	
	
	public function drawingApprovalObjectiveAction()
    {
		////////////////////////////////////////////////////////////////////////////////
		////////////// NEED TO INCORPORATE MALAYSIA PUBLIC HOLIDAY /////////////////////
		////////////////////////////////////////////////////////////////////////////////
		
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		$systemSetting = new Zend_Session_Namespace('systemSetting');	
		$this->view->cases = $systemSetting->arrDrawingApprovalCases[Date("Y", time())];
		$this->view->days = $systemSetting->arrDrawingApproval[Date("Y", time())];
		$fromPeriod = Date("Y-01-01 00:00:00", time());
		$toPeriod = Date("Y-12-31 23:59:59", time());
		
		$arrJobAll = $db->fetchAll("SELECT * FROM Job where CustomerPOReceivedDate > '".$fromPeriod."' AND CustomerPOReceivedDate < '".$toPeriod."' AND JobType='P' AND  (Cancelled=0 OR Cancelled IS NULL)");
		$this->view->content_report = "";
		$arrDataAll = array();
		for ($date = strtotime($fromPeriod); $date < strtotime($toPeriod); $date += (31 * 24 * 60 * 60))
		{
			$arrDataAll[Date("M-Y", $date)][0] = "";
			$arrDataAll[Date("M-Y", $date)][1] = "";
			$arrDataAll[Date("M-Y", $date)][2] = "";
		}
		
		foreach ($arrJobAll as $arrJob)
		{
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][1]++;
			$arrPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase WHERE JobID=".$arrJob['ID']);
			foreach ($arrPurchaseAll as $arrPurchase)
			{
				$days = $dispFormat->networkdays($arrJob['CustomerPOReceivedDate'], $arrPurchase['POFaxedDate'])-1;
				if ($days > $this->view->days || empty($arrPurchase))
				{
					$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][0]++;
					
				}
			}

			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] = $arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][0] / $arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][1];
			$arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] = number_format($arrDataAll[Date("M-Y", strtotime($arrJob['CustomerPOReceivedDate']))][2] * 100, 2);
		}
		
		$iCount = 0; $totalPercentage = 0;
		foreach ($arrDataAll as $month => $data)
		{
			$arrDate = explode("-", $month);
			$strMonth = Date("m", strtotime($arrDate[0]." 01 2000"));
			$this->view->content_report .= "<TR>";
			$this->view->content_report .= "<TD>".$month."</TD>";
			$this->view->content_report .= "<TD><a class='clsLoadDetail' MonthPeriod='".$strMonth."' YearPeriod='".$arrDate[1]."' href='#'>".$data[0]."</a></TD>";
			$this->view->content_report .= "<TD>".$data[1]."</TD>";
			$iCount++;
			$this->view->content_report .= "</TR>";


		}
	
		
		
    }
	
	public function marginAction()
    {
		$db = Zend_Db_Table::getDefaultAdapter();
		$dispFormat = new Venz_App_Display_Format();
		$Request = $this->getRequest();			
		$JobID = $Request->getParam('JobID');	

		$arrDataList = array();$arrPaymentPaid = array();$arrPaymentReceived = array(); 
		$arrJobData = $db->fetchRow("SELECT * FROM Job where Job.ID=".$JobID);
			
		$startDate = strtotime($arrJobData['CustomerPOReceivedDate']);
		
		if ($arrJobData['CompletedDate'])
			$endDate = strtotime($arrJobData['CompletedDate']); // + (2*24*60*60); //strtotime('2016-4-21');
		else
			$endDate = time(); // + (2*24*60*60); //strtotime('2016-4-21');
		
		
		
		
		
		
		
		
	//	$salesAmount = $arrJobData['SalesPriceExchangeRate'] * $arrJobData['SalesPrice'];
		/////////////////////////////////////////////////////////////////////////////////
		//////////////////////////// Sales //////////////////////////////////////////
		$this->view->listSales = "<table border=0 class=report_table cellspacing=0 celppadding=3 width=600px>";
		
		$this->view->listSales .=  "<TR><TD class=report_header> </TD>";
		$this->view->listSales .=  "<TD class=report_header> Description </TD>"; 
		$this->view->listSales .=  "<TD class=report_header style=\"text-align:right;\"> Amount </TD></TR>";
		
		
		$salesAmount = 0;
		$arrJobSalesAll = $db->fetchAll("SELECT * FROM JobSales where JobSales.JobID=".$JobID);
		foreach ($arrJobSalesAll as $arrJobSales)
		{
			$salesAmount += $arrJobSales['SalesPriceExchangeRate'] * $arrJobSales['SalesPrice'];
			//$this->view->listPayments .= $arrJobPurchase['PONo'] . " - " . $arrJobPurchase['PurchasePriceExchangeRate'] * $arrJobPurchase['PurchasePrice'] . "<BR>";
			$this->view->listSales .=  "<TR class=report_even><TD class=report_cell> ".$arrJobSales['CustomerPO'] ."</TD>";
			$this->view->listSales .=  "<TD class=report_cell>  </TD>"; 
			$this->view->listSales .=  "<TD class=report_cell style=\"text-align:right;\"> RM ". number_format($arrJobSales['SalesPriceExchangeRate'] * $arrJobSales['SalesPrice'], 2)."</TD></TR>";
		
		}
		$this->view->listSales .=  "<TR class=report_even><TD class=report_cell colspan=2  style=\"text-align:right;\"> Total: </TD>";
		$this->view->listSales .=  "<TD class=report_cell style=\"text-align:right;\"> RM ".number_format($salesAmount, 2)."</TD></TR>";
		$this->view->listSales .=  "</table>";
		
		/////////////////////////////////////////////////////////////////////////////////
		//////////////////////////// PURCHASES //////////////////////////////////////////
		$this->view->listPayments = "<table border=0 class=report_table cellspacing=0 celppadding=3 width=600px>";
		
		$this->view->listPayments .=  "<TR><TD class=report_header> </TD>";
		$this->view->listPayments .=  "<TD class=report_header> Description </TD>"; 
		$this->view->listPayments .=  "<TD class=report_header style=\"text-align:right;\"> Amount </TD></TR>";
		
		
		$purchaseAmount = 0;
		$arrJobPurchaseAll = $db->fetchAll("SELECT * FROM JobPurchase where JobPurchase.JobID=".$JobID);
		foreach ($arrJobPurchaseAll as $arrJobPurchase)
		{
			$purchaseAmount += $arrJobPurchase['PurchasePriceExchangeRate'] * $arrJobPurchase['PurchasePrice'];
			//$this->view->listPayments .= $arrJobPurchase['PONo'] . " - " . $arrJobPurchase['PurchasePriceExchangeRate'] * $arrJobPurchase['PurchasePrice'] . "<BR>";
			$this->view->listPayments .=  "<TR class=report_even><TD class=report_cell> ".$arrJobPurchase['PONo'] ."</TD>";
			$this->view->listPayments .=  "<TD class=report_cell>  </TD>"; 
			$this->view->listPayments .=  "<TD class=report_cell style=\"text-align:right;\"> RM ". number_format($arrJobPurchase['PurchasePriceExchangeRate'] * $arrJobPurchase['PurchasePrice'], 2)."</TD></TR>";
			
			
			$arrJobPurchaseDeliveryAll = $db->fetchAll("SELECT * FROM JobPurchaseDelivery where JobPurchaseDelivery.JobPurchaseID=".$arrJobPurchase['ID']);
			foreach ($arrJobPurchaseDeliveryAll as $arrJobPurchaseDelivery)
			{
			
				if ($arrJobPurchaseDelivery['DutyTax'])
				{
					$purchaseAmount += $arrJobPurchaseDelivery['DutyTax'];
					$arrPaymentPaid[Date("d-M-y", strtotime($arrJobPurchaseDelivery['DeliveryReceivedDate']))] += $arrJobPurchaseDelivery['DutyTax'];
					$arrDataList[strtotime($arrJobPurchaseDelivery['DeliveryReceivedDate'])][] = array("Description" => "Delivery Duty/Tax: ".$arrJobPurchaseDelivery['DeliveryAWB'], "Currency" => "RM",
						"ExchangeRate" => 1,"Amount" => $arrJobPurchaseDelivery['DutyTax'],"AmountRM" => $arrJobPurchaseDelivery['DutyTax'] * -1);
				
					//$this->view->listPayments .= $arrJobPurchase['PONo'] . " -  <B>Delivery Duty/Tax</B>  -" . $arrJobPurchase['DutyTax'] . "<BR>";
					$this->view->listPayments .=  "<TR class=report_even><TD class=report_cell> ".$arrJobPurchaseDelivery['DeliveryAWB'] ."</TD>";
					$this->view->listPayments .=  "<TD class=report_cell> Delivery Duty/Tax </TD>"; 
					$this->view->listPayments .=  "<TD class=report_cell style=\"text-align:right;\"> RM ".  number_format($arrJobPurchaseDelivery['DutyTax'], 2) ."</TD></TR>";
					
				}
				if ($arrJobPurchaseDelivery['FreightCost']){
					$purchaseAmount += $arrJobPurchaseDelivery['FreightCost'];
					$arrPaymentPaid[Date("d-M-y", strtotime($arrJobPurchaseDelivery['DeliveryReceivedDate']))] += $arrJobPurchaseDelivery['FreightCost'];
					$arrDataList[strtotime($arrJobPurchaseDelivery['DeliveryReceivedDate'])][] = array("Description" => "Delivery Charges: ".$arrJobPurchaseDelivery['DeliveryAWB'], "Currency" => "RM",
						"ExchangeRate" => 1,"Amount" => $arrJobPurchaseDelivery['FreightCost'],"AmountRM" => $arrJobPurchaseDelivery['FreightCost'] * -1);
				
				
				//	$this->view->listPayments .= $arrJobPurchase['PONo'] . " -  Delivery Charges  -" . $arrJobPurchase['FreightCost'] . "<BR>";
					$this->view->listPayments .=  "<TR class=report_even><TD class=report_cell> ".$arrJobPurchaseDelivery['DeliveryAWB'] ."</TD>";
					$this->view->listPayments .=  "<TD class=report_cell> Delivery Charges </TD>"; 
					$this->view->listPayments .=  "<TD class=report_cell style=\"text-align:right;\"> RM ".  number_format($arrJobPurchaseDelivery['FreightCost'], 2) ."</TD></TR>";

				}
				
			}
			
		
		}
		
		$maxAmount = $salesAmount;
		if ($purchaseAmount > $maxAmount)
			$maxAmount = $purchaseAmount;
			
		
		$maxAmount = $maxAmount + ($maxAmount * 0.1);
		$this->view->maxAmount = $maxAmount;
		
		
		/////////////////////////////////////////////////////////////////////////////////
		//////////////////////////// CLAIMS //////////////////////////////////////////
		$claimAmount = 0;$totalPaymentPaid = 0;
		$arrJobClaimsAll = $db->fetchAll("SELECT JobClaims.*, ACLUsers.Username FROM JobClaims LEFT JOIN ACLUsers ON (JobClaims.SubmittedBy=ACLUsers.ID) where JobClaims.JobID=".$JobID);
		foreach ($arrJobClaimsAll as $arrJobClaims)
		{
			$claimRM = $arrJobClaims['ClaimCurrencyExchangeRate'] * $arrJobClaims['ClaimAmount'];
			$claimAmount += $claimRM;
			//$totalPaymentPaid += $claimRM;
			$arrPaymentPaid[Date("d-M-y", strtotime($arrJobClaims['ClaimDate']))] += $claimRM;
			$arrDataList[strtotime($arrJobClaims['ClaimDate'])][] = array("Description" => $arrJobClaims['ClaimDescription'], "Currency" => $arrJobClaims['ClaimCurrency'],
				"ExchangeRate" => $arrJobClaims['ClaimCurrencyExchangeRate'],"Amount" => $arrJobClaims['ClaimAmount'],"AmountRM" => $claimRM * -1,
				"By" => $arrJobClaims['Username']);
				
			$this->view->listPayments .=  "<TR class=report_even><TD class=report_cell> Claims By ".$arrJobClaims['Username']."</TD>";
			$this->view->listPayments .=  "<TD class=report_cell> ".$arrJobClaims['ClaimDescription']."</TD>"; 
			$this->view->listPayments .=  "<TD class=report_cell style=\"text-align:right;\"> RM ".number_format($claimRM, 2)."</TD></TR>";
	


		}
		
		$totalCost = $purchaseAmount + $claimAmount;
		
		$this->view->listPayments .=  "<TR class=report_even><TD class=report_cell colspan=2  style=\"text-align:right;\"> Total: </TD>";
		$this->view->listPayments .=  "<TD class=report_cell style=\"text-align:right;\"> RM ".number_format($totalCost, 2)."</TD></TR>";
		
		$this->view->listPayments .=  "</table>";
		
		//print $purchaseAmount."-". $claimAmount;
		
		/////////////////////////////////////////////////////////////////////////////////
		//////////////////////////// Payments //////////////////////////////////////////
		$totalPaymentReceived = 0; 
		
		$arrJobPaymentsAll = $db->fetchAll("SELECT JobPayments.*, JobDocuments.Name as InvoiceNo, JobDocuments.ID as JobDocumentsID FROM JobPayments LEFT JOIN JobDocuments ON (JobDocuments.ID=JobPayments.JobDocumentID) where JobPayments.JobID=".$JobID);
		foreach ($arrJobPaymentsAll as $arrJobPayments)
		{
			$paymentRM = $arrJobPayments['PaymentCurrencyExchangeRate'] * $arrJobPayments['PaymentAmount'];
			$finalAmount = 0;
			if ($arrJobPayments['PaymentReceive']){
				$totalPaymentReceived += $paymentRM;
				$arrPaymentReceived[Date("d-M-y", strtotime($arrJobPayments['PaymentDate']))] += $paymentRM;
				$finalAmount = $paymentRM;
			}else
			{
				$totalPaymentPaid += $paymentRM;
				$arrPaymentPaid[Date("d-M-y", strtotime($arrJobPayments['PaymentDate']))] += $paymentRM;
				$finalAmount = $paymentRM * -1;
			}
			$strInvoice = "";
			if ($arrJobPayments['InvoiceNo'])
			{
				$strInvoice = "<a target='_blank' href='/default/index/doc-view/JobDocumentsID/".$arrJobPayments['JobDocumentsID']."'>".
						"<img title='".$arrJobPayments['InvoiceNo']."' src='/images/icons/IconViewSmall.png'></a> ".$arrJobPayments['InvoiceNo']."<BR>";
			}
			$arrDataList[strtotime($arrJobPayments['PaymentDate'])][] = array("Description" => $arrJobPayments['PaymentDescription'], "Currency" => $arrJobPayments['PaymentCurrency'],
				"ExchangeRate" => $arrJobPayments['PaymentCurrencyExchangeRate'],"Amount" => $arrJobPayments['PaymentAmount'],"AmountRM" => $finalAmount,
				"Invoice" => $strInvoice);


			
		}

		$totalPaymentReceivedFinal = 0; $totalPaymentPaidFinal = 0;
		for ($date = $startDate; $date < $endDate; $date += (24 * 60 * 60))
		{
			$arrDataAll[Date("d-M-y", $date)][0] = $salesAmount;
			$arrDataAll[Date("d-M-y", $date)][1] = $purchaseAmount;
			if ($arrPaymentReceived[Date("d-M-y", $date)])
				$totalPaymentReceivedFinal += $arrPaymentReceived[Date("d-M-y", $date)];
			
			if ($arrPaymentPaid[Date("d-M-y", $date)])
				$totalPaymentPaidFinal += $arrPaymentPaid[Date("d-M-y", $date)];
			
			$arrDataAll[Date("d-M-y", $date)][2] = $totalPaymentReceivedFinal;
			$arrDataAll[Date("d-M-y", $date)][3] = $totalPaymentPaidFinal;
		}
	
		$this->view->dataSalesAmount = "";
		$this->view->dataCostAmount = "";
		$this->view->dataReceivedAmount = "";
		$this->view->datapaidAmount = "";
		$this->view->ticks = ""; $counter = 1; $maxTicks = 15; $skip = intval(sizeof($arrDataAll) / $maxTicks);
		
		foreach ($arrDataAll as $date => $arrData)
		{
			/*
			if ($counter % $skip == 0)
				$this->view->ticks .= "[$counter, '$date'],";
			else
				$this->view->ticks .= "[$counter, ''],";
		
			$this->view->dataSalesAmount .= "$salesAmount,";
			$this->view->dataCostAmount .= "$totalCost, ";
			$this->view->dataReceivedAmount .= $arrData[2].",";
			$this->view->datapaidAmount .= $arrData[3].", ";
			*/
			
			if ($counter % $skip == 0)
				$this->view->ticks .= "['$date', '$date'],";
			else
				$this->view->ticks .= "['$date', ''],";
		
			$this->view->dataSalesAmount .= "['$date', $salesAmount],";
			$this->view->dataCostAmount .= "['$date', $totalCost], ";
			$this->view->dataReceivedAmount .= "['$date', $arrData[2]],";
			$this->view->datapaidAmount .= "['$date', $arrData[3]], ";
			
			$counter++;
			
		}
	//	$this->view->ticks .= "[".($counter+1).", ''],[".($counter+2).", ''],";
	//	$this->view->dataSalesAmount .= ",,";
	//	$this->view->dataCostAmount .= ",,";
	//	$this->view->dataReceivedAmount .= ",,";
	//	$this->view->datapaidAmount .= ",,";
		
		$this->view->receiveAmount = "RM ".number_format($salesAmount,2, ".", ",");
		$this->view->payAmount = "RM ".number_format($totalCost,2, ".", ",");
		$netAmount = $salesAmount - $totalCost;
		$this->view->netClass = "color: blue;";
		if ($netAmount < 0)
			$this->view->netClass = "color: red;";
		$this->view->netAmount = "RM ".number_format($netAmount,2, ".", ",");
		
		
		$this->view->currentReceived = "RM ".number_format($totalPaymentReceivedFinal,2, ".", ",");
		$this->view->currentPaid = "RM ".number_format($totalPaymentPaidFinal,2, ".", ",");
		$currentNet = $totalPaymentReceivedFinal - $totalPaymentPaidFinal;
		$this->view->currentClass = "color: blue;";
		if ($currentNet < 0)
			$this->view->currentClass = "color: red;";
		$this->view->currentNet = "RM ".number_format($currentNet,2, ".", ",");
		
		if ($totalPaymentReceivedFinal <= $salesAmount)
		{
			$leftReceivable = $salesAmount - $totalPaymentReceivedFinal;
			$this->view->leftReceivable = "RM ".number_format(($leftReceivable),2, ".", ",");
		}else
		{
			$leftReceivable = $totalPaymentReceivedFinal - $salesAmount;
			$this->view->leftReceivable = "<div style='font-size: 12px; text-align: right'>over received</div>RM +".number_format(($leftReceivable),2, ".", ",");
			
		}
		
		
		$leftPayment = $totalCost - $totalPaymentPaidFinal;
		
		if ($leftPayment < 0)
		{
			$leftNet = $leftReceivable + $leftPayment;
			$this->view->leftPayment = "<div style='font-size: 12px; text-align: right'>over paid</div>RM ".number_format(($leftPayment),2, ".", ",");
			
		}
		else{
			$leftNet = $leftReceivable - $leftPayment;
			$this->view->leftPayment = "RM ".number_format(($leftPayment),2, ".", ",");
			
		}
		
		$this->view->leftClass = "color: blue;";
		if (($leftNet) < 0)
			$this->view->leftClass = "color: red;";
		$this->view->leftNet = "RM ".number_format(($leftNet),2, ".", ",");
		
		//$this->view->projectedNet = "RM ".number_format(($netAmount - $currentNet),2, ".", ",");
		$this->view->tableList = "<table border=0 class=report_table cellspacing=0 celppadding=3 width=100%>".
		"<TR><TD class=report_header style='text-align: center'>Date</TD><TD class=report_header style='text-align: center'>Description</TD>".
		"<TD class=report_header style='text-align: center'>Invoice</TD>".
		"<TD class=report_header  style='text-align: center'>Amount</TD></TR>";		
		krsort($arrDataList); $counter = 0; $finalAmount = 0;
		foreach ($arrDataList as $date => $dataAll)
		{
			foreach ($dataAll as $data)
			{
				$rowClass = "report_even";
				if ($counter % 2 == 0)
					$rowClass = "report_odd";
				$counter++;
				$description = $data['Description'];
				if ($data['Currency'] != "RM")
					$description .= "<BR>".$data['Currency']." ".number_format($data['Amount'], 2, ".", ",")."<BR>@".$data['ExchangeRate'];
				if ($data['By'])
					$description .= "<BR>claimed by ".$data['By'];
				
				$class = "color: blue;";
				if (($data['AmountRM']) < 0)
					$class = "color: red;";
				
				$this->view->tableList .= "<TR class=".$rowClass.">".
					"<TD class='report_cell'  style='text-align: center'>".Date("d/m/Y", $date)."</TD>".
					"<TD class='report_cell' style='text-align: left'>".$description."</TD>".
					"<TD class='report_cell' style='text-align: left'>".$data['Invoice']."</TD>".
					"<TD class='report_cell'  style='text-align: right; $class'>RM ".number_format($data['AmountRM'], 2, ".", ",")."</TD></TR>";
					
				$finalAmount += $data['AmountRM'];
			}
		}
		
		$class = "color: blue;";
		if (($finalAmount) < 0)
			$class = "color: red;";
		
		$this->view->tableList .= "<TR class=".$rowClass.">".
				"<TD class='report_cell2'  style='text-align: center' colspan=3>".
				"<TD class='report_cell2'  style='text-align: right; $class'><B>RM ".number_format($finalAmount, 2, ".", ",")."</B></TD></TR>";
		$this->view->tableList .= "</table>";
		
		
    }


    public function temporaryCostAction(){

        $Request = $this->getRequest();
        $db = Zend_Db_Table::getDefaultAdapter();
        $sysHelper = new Venz_App_System_Helper();
        $dispFormat = new Venz_App_Display_Format();
        $sysNotification = new Venz_App_System_Notification();
        $libDb = new Venz_App_Db_Table();



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

        $sqlSearch = "";
        $SearchJobNo = $Request->getParam('SearchJobNo');
        $SearchJobType = $Request->getParam('SearchJobType');
        $SearchCustomerName = $Request->getParam('SearchCustomerName');
        $SearchCustomerPOReceivedDateFrom = $Request->getParam('SearchCustomerPOReceivedDateFrom');
        $SearchCustomerPOReceivedDateTo = $Request->getParam('SearchCustomerPOReceivedDateTo');
        $SearchItems = $Request->getParam('SearchItems');
        $SearchSalesPersonID = $Request->getParam('SearchSalesPersonID');
        $SearchCompleted = $Request->getParam('SearchCompleted');
        $SearchClosed = $Request->getParam('SearchClosed');
        $SearchCancelled = $Request->getParam('SearchCancelled');





        $sqlSearch .= $SearchJobNo ? " and Job.JobNo LIKE '%".$SearchJobNo."%'" : "";
        $sqlSearch .= $SearchJobType ? " and Job.JobType LIKE '%".$SearchJobType."%'" : "";
        $sqlSearch .= $SearchCustomerName ? " and Job.CustomerName LIKE \"%".trim($SearchCustomerName)."%\"" : "";
        $sqlSearch .= $SearchCustomerPOReceivedDateFrom ? " and Job.CustomerPOReceivedDate >= '".$dispFormat->format_date_simple_to_db($SearchCustomerPOReceivedDateFrom)."'" : "";
        $sqlSearch .= $SearchCustomerPOReceivedDateTo ? " and Job.CustomerPOReceivedDate <= '".$dispFormat->format_date_simple_to_db($SearchCustomerPOReceivedDateTo)."'" : "";
        $sqlSearch .= $SearchItems ? " and Job.Items LIKE '%".$SearchItems."%'" : "";
        $sqlSearch .= $SearchSalesPersonID ? " and JobSalesData.SalesPersonGroupID IN (".$SearchSalesPersonID.")" : "";
        $sqlSearch .= $SearchCompleted ? " and Job.Completed = ".$SearchCompleted : "";
        $sqlSearch .= $SearchClosed ? " and Job.Closed = ".$SearchClosed : "";
        $sqlSearch .= $SearchCancelled ? " and Job.Cancelled = ".$SearchCancelled : "";



        $this->view->SearchJobNo = $SearchJobNo ? $SearchJobNo : "";
        $this->view->SearchJobType = $SearchJobType ? $SearchJobType : "";
        $this->view->SearchCustomerName = $SearchCustomerName ? $SearchCustomerName : "";
        $this->view->SearchCustomerPOReceivedDateFrom = $SearchCustomerPOReceivedDateFrom ? $SearchCustomerPOReceivedDateFrom : "";
        $this->view->SearchCustomerPOReceivedDateTo = $SearchCustomerPOReceivedDateTo ? $SearchCustomerPOReceivedDateTo : "";
        $this->view->SearchItems = $SearchItems ? $SearchItems : "";
        $this->view->SearchSalesPersonID = $SearchSalesPersonID ? $SearchSalesPersonID : "";
        $this->view->SearchCompleted = $SearchCompleted ? $SearchCompleted : "";
        $this->view->SearchClosed = $SearchClosed ? $SearchClosed : "";
        $this->view->SearchCancelled = $SearchCancelled ? $SearchCancelled : "";


        $strHiddenSearch = "<input type=hidden name='Search' value='true'>";
        $strHiddenSearch .= "<input type=hidden name='SearchJobNo' value='".$SearchJobNo."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchJobType' value='".$SearchJobType."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchCustomerName' value='".$SearchCustomerName."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchCustomerPOReceivedDateFrom' value='".$SearchCustomerPOReceivedDateFrom."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchCustomerPOReceivedDateTo' value='".$SearchCustomerPOReceivedDateTo."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchItems' value='".$SearchItems."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchSalesPersonID' value='".$SearchSalesPersonID."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchCompleted' value='".$SearchCompleted."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchClosed' value='".$SearchClosed."'>";
        $strHiddenSearch .= "<input type=hidden name='SearchCancelled' value='".$SearchCancelled."'>";

        $this->view->optionJobType = $libDb->getSystemOptions("arrJobType", $this->view->SearchJobType);
        $this->view->optionSearchSalesPersonID = $libDb->getTableOptions("ACLUsers", "Name", "ID", $this->view->SearchSalesPersonID, "Name");

        $sysHelper->setFetchMode(Zend_Db::FETCH_NUM);
        $arrJobs = $sysHelper->getTempCost($sortbyJob, $ascdescJob, $recordsPerPageJob, $showPageJob,  $sqlSearch);
        $dataJobs = $arrJobs[1];
        $exportSql = $arrJobs[2];

//        $arrJobsTotalAmount = $sysHelper->getTempCostAmount($sqlSearch);
//
//        $this->view->totalDutyTax = number_format($arrJobsTotalAmount[0], 2, ".", ",");
//        $this->view->totalFreightCost = number_format($arrJobsTotalAmount[1], 2, ".", ",");
//        $this->view->totalPurchasePriceRM = number_format($arrJobsTotalAmount[2], 2, ".", ",");
//        $this->view->totalPurchase = number_format($arrJobsTotalAmount[3], 2, ".", ",");
//        $this->view->totalSales = number_format($arrJobsTotalAmount[4], 2, ".", ",");
//        $this->view->totalSalesPartial = number_format($arrJobsTotalAmount[5], 2, ".", ",");
//        $this->view->totalSalesDelivered = number_format($arrJobsTotalAmount[6], 2, ".", ",");
//        $this->view->totalTemporaryCost = number_format($arrJobsTotalAmount[7], 2, ".", ",");
//        $totalDutyTax = $arrJobsTotalAmount[0];
//        $totalFreightCost = $arrJobsTotalAmount[1];
//        $totalPurchasePriceRM = $arrJobsTotalAmount[2];
//        $totalPurchase = $arrJobsTotalAmount[3];
//        $totalSales = $arrJobsTotalAmount[4];
//        $totalSalesPartial = $arrJobsTotalAmount[5];
//        $totalSalesDelivered = $arrJobsTotalAmount[6];
//        $totalTemporaryCost = $arrJobsTotalAmount[7];


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

        function format_customer($colnum, $rowdata)
        {
            $strPrinciple = "";
            if ($rowdata[15])
                $strPrinciple = "<BR>(".$rowdata[15].")";

            return $rowdata[2].$strPrinciple;

        }


        function format_item($colnum, $rowdata){
            return $rowdata[3];

        }

        function format_jobtype($colnum, $rowdata)
        {
            $systemSetting = new Zend_Session_Namespace('systemSetting');
            return $systemSetting->arrJobType[$rowdata[4]];
        }


        function format_total_duty($colnum, $rowdata, $export)
        {
            $dispFormat = new Venz_App_Display_Format();
            if ($export)
                return number_format($rowdata[8],2);

            if ($rowdata[8])
                return "<span style='color: red'>".$dispFormat->format_currency($rowdata[8])."<span>";
            else
                return "";
        }

        function format_received_date($colnum, $rowdata, $export)
        {
            $dispFormat = new Venz_App_Display_Format();
            return $dispFormat->format_date_simple($rowdata[7]);
        }

        function format_total_freight($colnum, $rowdata, $export)
        {
            $dispFormat = new Venz_App_Display_Format();
            if ($export)
                return number_format($rowdata[9],2);

            if ($rowdata[9])
                return "<span style='color: red'>".$dispFormat->format_currency($rowdata[9])."<span>";
            else
                return "";
        }


        function format_total_purchase($colnum, $rowdata, $export)
        {
            $strPartial = $rowdata[13] ? "*" : "";

            $dispFormat = new Venz_App_Display_Format();
            if ($export)
                return number_format($rowdata[10],2);

            if ($rowdata[10])
                return "<span style='color: red'>".$dispFormat->format_currency($rowdata[10]).$strPartial."<span>";
            else
                return "";
        }

        function format_total_cost($colnum, $rowdata, $export)
        {
            $strPartial = $rowdata[13] ? "*" : "";
            $dispFormat = new Venz_App_Display_Format();
            if ($export)
                return number_format($rowdata[11],2);

            if ($rowdata[11])
                return "<span style='color: red'>".$dispFormat->format_currency($rowdata[11]).$strPartial."<span>";
            else
                return "";
        }

        function format_total_sales($colnum, $rowdata, $export)
        {
            $dispFormat = new Venz_App_Display_Format();
            if ($export)
                return number_format($rowdata[12],2);

            if ($rowdata[12])
                return $dispFormat->format_currency($rowdata[12]);
            else
                return "";
        }




        function format_sales_delivered($colnum, $rowdata, $export)
        {
            $dispFormat = new Venz_App_Display_Format();
            $rowdata[14] = $rowdata[14] == '0' ? "" : $rowdata[14];

            if ($export){
                if ($rowdata[14]){
                    return number_format($rowdata[15] - $rowdata[14],2) . " / " . number_format($rowdata[15],2);
                }else{
                    return "";
                }
            }
            if ($rowdata[14]){
                return $dispFormat->format_currency($rowdata[15] - $rowdata[14]) . "<BR><span style='line-height: 5px;'>------</span><BR>" . number_format($rowdata[15],2);
            }
            else
                return "";
        }

        function format_temporary_cost($colnum, $rowdata, $export)
        {
            $dispFormat = new Venz_App_Display_Format();
            if ($export)
                return number_format($rowdata[16],2);

            if ($rowdata[16])
                return "<span style='color: red'>".$dispFormat->format_currency($rowdata[16])."<span>";
            else
                return "";
        }


        $arrHeaderMargin = array('','','Job No', 'Customer', 'Item', 'Job Type','Latest<BR>Received Date','Total<BR>Duty Tax', 'Total<BR>Freight Cost', 'Total<BR>Purchase Price', 'Total Cost','Remaining Sales Not Delivered<BR>------<BR>Total Sales Amount','Temporary Cost');
        $arrFormatMargin = array('{format_counterJob}', '{format_action}', '%1%', '{format_customer}', '{format_item}', '{format_jobtype}', '{format_received_date}', '{format_total_duty}', '{format_total_freight}',  '{format_total_purchase}',
            '{format_total_cost}','{format_sales_delivered}','{format_temporary_cost}');
        $arrSortMargin = array('','','Job.ID', 'Job.CustomerName', 'Job.Items', 'Job.JobType', 'JobPurchase.LatestReceivedDate', 'JobSales.TotalSalesPriceRM','TotalCostRM','ProjectMarginRM','MarginRM', 'TotalSalesDelivered', 'TemporaryCost');
        $arrColParamMargin = array('width=20px','width=50px','width=50px','', '', 'width=100px','width=100px','width=100px', 'nowrap width=120px','nowrap width=120px','width=120px','width=120px', 'width=120px', 'width=120px', 'width=120px');
        $aligndataMargin = 'CCCLLCCRRRRRR'; $tablewidthMargin = '1750px';

        $exportReportJobMargin = new Venz_App_Report_Excel(array('exportsql'=> $exportSql,  'export_name'=>'export_excel_jobmargin',  'hiddenparam'=>$strHiddenSearch));

        $displayTableMargin= new Venz_App_Display_Table(
            array (
                'data' => $dataJobs,
                'headings' => $arrHeaderMargin,
                'format' 		=> $arrFormatMargin,
                'sort_column' 	=> $arrSortMargin,
                'alllen' 		=> $arrJobs[0],
                'title'		=> 'Job with item delivered which havent shipped out: '.$arrJobs[0],
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

        $this->view->content_tempcost = $displayTableMargin->render();
        $sessionJobs->numCounter = $recordsPerPage * ($showPage-1);



        $export_excel_jobmargin_x = $Request->getParam('export_excel_jobmargin_x');
        if ($export_excel_jobmargin_x)
        {

            $db = Zend_Db_Table::getDefaultAdapter();
            $exportsql = $Request->getParam('exportsql');
            $exportReportJob = new Venz_App_Report_Excel(array('exportsql'=> base64_decode($exportsql), 'db'=>$db, 'exit'=>'No', 'hiddenparam' 	=> $strHiddenSearch, 'headings'=>$arrHeaderMargin, 'format'=>$arrFormatMargin));
            $exportReportJob->render();
           // echo ",,,,,,,".$totalDutyTax.",".$totalFreightCost.",".$totalPurchasePriceRM.",".$totalPurchase.",0,".$totalTemporaryCost."\n";
            exit();

        }
    }


    public function indexAction()
    {
		exit();
    }	

}

