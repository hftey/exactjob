<?php
 
class Venz_App_Applications_Helper extends Zend_Db_Table_Abstract
{
	protected $_db  = NULL;
	protected $_status  = array(0=>"Pending", 1=>"Viewed", 2=>"Approved", 3=>"Not Approved");
	protected $_statusIcon  = array(0=>"<img border=0 src='/images/icons/IconPending.gif'>", 1=>"<img border=0 src='/images/icons/IconViewed.gif'>", 
	2=>"<img border=0 src='/images/icons/IconApproved.gif'>", 3=>"<img border=0 src='/images/icons/IconNotApproved.gif'>");

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

	
	public function getStatusIcon($statusID)
	{
		return $this->_statusIcon[$statusID];
	}	
	public function getStatusName($statusID)
	{
		return $this->_status[$statusID];
	}

	public function getStatusOptions($objView, $default = NULL)
	{
		$options_status = "";
		foreach ($this->_status as $index => $status)
		{
			if (strlen($default) == 0)
				$options_status .= "<OPTION value='".$index."'>".$objView->view->translate->_($status);
			else if ($default == $index)
				$options_status .= "<OPTION value='".$index."' selected>".$objView->view->translate->_($status);
			else
				$options_status .= "<OPTION value='".$index."'>".$objView->view->translate->_($status);
		}
		return $options_status;
	}

    public function getStudiesList($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $sqlString = null)
    {
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ApplicationDate" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";	
		$sqlAll = "SELECT AppStudies.ID as AppStudiesID, ACLUsers.Name as UserName, AppStudies.StudiesDate, AppStudies.StudiesDetail, AppStudies.Location, AppStudies.Period, AppStudies.StudiesReason, ".
			"AppStudies.ApplicationDate, AppStudies.ApplicationStatus, ACLUsers.ID, SYSDesignationLevel.Name as DesignationLevel ".
			" FROM ACLUsers LEFT JOIN UsersProfessionalDetail ON (ACLUsers.ID=UsersProfessionalDetail.ACLUsersID)  LEFT JOIN SYSDesignationLevel on (SYSDesignationLevel.ID=UsersProfessionalDetail.SYSDesignationLevelID), 
			AppStudies WHERE 1=1 AND ".
				"ACLUsers.ID=AppStudies.ACLUsersID";
					
		if ($sqlString)
			$sqlAll .= $sqlString;
		$sql .= $sqlAll." order by $sql_orderby $sql_limit";
		$sqlExport = $sqlAll." order by $sql_orderby ";
		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql), $sqlExport);	
	}
	
    public function getStudiesDetail($ID = NULL, $searchString = null)
    {
		$sql = "SELECT AppStudies.*, ACLUsers.Name as UserName FROM AppStudies, ACLUsers WHERE  ACLUsers.ID=AppStudies.ACLUsersID";
		if ($ID)
			$sql .= " and AppStudies.ID=".$ID;	

		if ($searchString)
			$sql .= " ".$searchString;	
		if ($ID)
			return $this->_db->fetchRow($sql);
		else
			return $this->_db->fetchAll($sql);
	}	 
	

    public function getTransferList($orderby = null, $ascdesc = null, $recordsPerPage = null, $showPage = null, $sqlString = null)
    {
		if ($showPage	< 0 || $showPage == "") $showPage = 1;
				
		$sql_orderby =  is_null($orderby) ? "ApplicationDate" : $orderby;
		$sql_orderby .= strlen($sql_orderby) == 0 ? "" : " " . $ascdesc ;
		$count = $showPage -1;
		$sql_limit = isset($recordsPerPage) ? " limit " . ($count * $recordsPerPage) . ", " . $recordsPerPage : "";	
		$sqlAll = "SELECT AppTransfer.ID as AppTransferID, ACLUsers.Name as UserName, SYSOffice.Name as OfficeName, AppTransfer.TransferDate, ".
			"AppTransfer.ApplicationDate, AppTransfer.TransferReason, AppTransfer.ApplicationStatus, ACLUsers.ID, SYSDesignationLevel.Name as DesignationLevel, ".
			" IF (SYSOfficeFrom.Name IS NULL, SYSOfficeCurrent.Name, SYSOfficeFrom.Name) as OfficeNameFrom ".
			" FROM ACLUsers LEFT JOIN UsersProfessionalDetail ON (ACLUsers.ID=UsersProfessionalDetail.ACLUsersID) LEFT JOIN SYSDesignationLevel on (SYSDesignationLevel.ID=UsersProfessionalDetail.SYSDesignationLevelID) ". 
			" LEFT JOIN SYSOffice as SYSOfficeCurrent ON (SYSOfficeCurrent.ID=UsersProfessionalDetail.SYSOfficeID), ".
			" AppTransfer LEFT JOIN SYSOffice as SYSOfficeFrom ON (SYSOfficeFrom.ID=AppTransfer.SYSOfficeFromID), SYSOffice WHERE 1=1 AND ".
				"ACLUsers.ID=AppTransfer.ACLUsersID and SYSOffice.ID=AppTransfer.SYSOfficeID ";
		
		if ($sqlString)
			$sqlAll .= $sqlString;
		$sql = $sqlAll." order by $sql_orderby $sql_limit";
		$sqlExport = $sqlAll." order by $sql_orderby ";
		//print $sqlExport; exit();
		return array(sizeof($this->_db->fetchAll($sqlAll)), $this->_db->fetchAll($sql), $sqlExport);	
	}
	
    public function getTransferDetail($ID = NULL, $searchString = null)
    {
		$sql = "SELECT AppTransfer.*, ACLUsers.Name as UserName, SYSOffice.Name as OfficeName FROM AppTransfer, ACLUsers, SYSOffice WHERE ACLUsers.ID=AppTransfer.ACLUsersID and SYSOffice.ID=AppTransfer.SYSOfficeID ";
		if ($ID)
			$sql .= " and AppTransfer.ID=".$ID;	


		if ($searchString)
			$sql .= " ".$searchString;	
		if ($ID)
			return $this->_db->fetchRow($sql);
		else
			return $this->_db->fetchAll($sql);
	}	 


	
}




?>