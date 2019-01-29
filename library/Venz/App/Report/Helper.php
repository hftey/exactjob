<?php
 
class Venz_App_Report_Helper extends Zend_Db_Table_Abstract
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
	
	public function getActivityDetail($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
	{
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT ACLUsers.ID, ACLUsers.Name, UsersActivityDetail.ActCity, SYSCountry.Name, UsersActivityDetail.ActFromDate, ".
 		"UsersActivityDetail.ActToDate, SYSActivityCat.Name as ActivityCategory, UsersActivityDetail.ActTitle ". 
		"FROM ACLUsers, UsersActivityDetail LEFT JOIN SYSActivityCat ON (UsersActivityDetail.SYSActivityID=SYSActivityCat.ID), ".
		"SYSCountry  WHERE ACLUsers.ID=UsersActivityDetail.ACLUsersID and SYSCountry.ID=UsersActivityDetail.SYSCountryID ";	
		
		if ($searchString)
			$sqlAll .= $searchString;
		$sql .= $sqlAll." order by $sql_orderby $sql_limit";

		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql));	

	}
	
	public function getTotalExperience($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
	{
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT ACLUsers.ID, ACLUsers.Name, '', SYSDesignationLevel.Name as DesignationName, SYSOffice.Name as OfficeName, ".
		"UsersJPSEmploymentDetail.JPSEmpStartDate, UsersJPSEmploymentDetail.JPSEmpEndDate, DATE_FORMAT(FROM_DAYS(DATEDIFF(UsersJPSEmploymentDetail.JPSEmpEndDate,UsersJPSEmploymentDetail.JPSEmpStartDate)), '%Y')+0 AS Years, ".
		"UsersJPSEmploymentDetail.ID, DATE_FORMAT(FROM_DAYS(DATEDIFF(UsersJPSEmploymentDetail.JPSEmpEndDate,UsersJPSEmploymentDetail.JPSEmpStartDate)), '%c')+0 as Month, SYSState.Name,  CurOffice.Name as CurOfficeName ".
 		"FROM ACLUsers, UsersProfessionalDetail LEFT JOIN SYSOffice as CurOffice on (CurOffice.ID=UsersProfessionalDetail.SYSOfficeID), UsersJPSEmploymentDetail LEFT JOIN SYSOffice ON (UsersJPSEmploymentDetail.JPSSYSOfficeID=SYSOffice.ID) LEFT JOIN SYSState on (SYSState.ID=SYSOffice.OfficeState) LEFT JOIN ".
		"SYSDesignationLevel ON (UsersJPSEmploymentDetail.JPSSYSDesignationLevelID=SYSDesignationLevel.ID) WHERE ACLUsers.ID=UsersJPSEmploymentDetail.ACLUsersID AND ACLUsers.ID=UsersProfessionalDetail.ACLUsersID";	

		if ($searchString)
			$sqlAll .= $searchString;
		$sqlOrder .= $sqlAll." order by ACLUsers.ID, UsersJPSEmploymentDetail.JPSEmpStartDate, $sql_orderby";
		$sql .= $sqlOrder." $sql_limit";
	//	print $sql; exit();
		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql), $sqlOrder);	

	}		
	
	
	public function getHistoricalExperience($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
	{
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT ACLUsers.ID, ACLUsers.Name, '', SYSDesignationLevel.Name as DesignationName, SYSOffice.Name as OfficeName, ".
		"UsersJPSEmploymentDetail.JPSEmpStartDate, UsersJPSEmploymentDetail.JPSEmpEndDate, 
		DATE_FORMAT(FROM_DAYS(DATEDIFF(IF(UsersJPSEmploymentDetail.JPSEmpEndDate IS NULL,now(),UsersJPSEmploymentDetail.JPSEmpEndDate),UsersJPSEmploymentDetail.JPSEmpStartDate)), '%Y')+0 AS Years, UsersJPSEmploymentDetail.ID, DATE_FORMAT(FROM_DAYS(DATEDIFF(UsersJPSEmploymentDetail.JPSEmpEndDate,UsersJPSEmploymentDetail.JPSEmpStartDate)), '%c')+0 as Month ".
 		"FROM ACLUsers, UsersJPSEmploymentDetail LEFT JOIN SYSOffice ON (UsersJPSEmploymentDetail.JPSSYSOfficeID=SYSOffice.ID) LEFT JOIN ".
		"SYSDesignationLevel ON (UsersJPSEmploymentDetail.JPSSYSDesignationLevelID=SYSDesignationLevel.ID) WHERE ACLUsers.ID=UsersJPSEmploymentDetail.ACLUsersID ";	
		
		if ($searchString)
			$sqlAll .= $searchString;
		$sqlOrder .= $sqlAll." order by $sql_orderby";
		$sql .= $sqlOrder." $sql_limit";

		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql), $sqlOrder);	

	}	
	
	
	public function getCurrentExperience($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $searchString = null)
	{
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ID" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";
		$sqlAll = "SELECT ACLUsers.ID, ACLUsers.Name, UsersProfessionalDetail.ProSeniority, SYSDesignationLevel.Name as DesignationName, SYSOffice.Name as OfficeName, ".
		"UsersProfessionalDetail.ProCurDesignationDate, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),UsersProfessionalDetail.ProCurDesignationDate)), '%Y')+0 AS Years, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(),UsersProfessionalDetail.ProCurDesignationDate)), '%m')+0 AS Month ".
 		"FROM ACLUsers, UsersProfessionalDetail LEFT JOIN SYSOffice ON (UsersProfessionalDetail.SYSOfficeID=SYSOffice.ID) LEFT JOIN ".
		"SYSDesignationLevel ON (UsersProfessionalDetail.SYSDesignationLevelID=SYSDesignationLevel.ID) WHERE ACLUsers.ID=UsersProfessionalDetail.ACLUsersID ";	
		
		if ($searchString)
			$sqlAll .= $searchString;
		$sqlOrder .= $sqlAll." order by $sql_orderby";
		$sql .= $sqlOrder." $sql_limit";
		//print $sql; exit();
		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql), $sqlOrder);	

	}
		
	
}




?>