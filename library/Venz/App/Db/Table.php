<?php
 
class Venz_App_Db_Table extends Zend_Db_Table_Abstract
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
	
   public function getMAOptions($displayField = "Name", $IDField = "ID", $defaultValue = null, $orderBy = null, $where = null)
   {
		$systemSetting = new Zend_Session_Namespace('systemSetting');
	   	if ($orderBy)
   			$orderBy = " ORDER BY $orderBy ";
		
		
   		$sql = "select MA.*, Country.iso_3_code as CountryName from Country, MA where MA.CountryID=Country.ID $where GROUP BY $IDField $orderBy ";

		$record = $this->_db->fetchAll($sql);
		$option_string = "";
		foreach ($record as $index => $TypeData)
		{	
			if (!is_null($TypeData[$displayField])){
				if (!is_null($defaultValue))
				{
					if ($defaultValue == $TypeData[$IDField])
						$option_string .= "<option value='".$TypeData[$IDField]."' selected>".$TypeData['CountryName'] . "&nbsp;&nbsp;-&nbsp;&nbsp;" .$systemSetting->translate->_($TypeData[$displayField])."</option>";
					else
						$option_string .= "<option value='".$TypeData[$IDField]."'>".$TypeData['CountryName'] . "&nbsp;&nbsp;-&nbsp;&nbsp;" .$systemSetting->translate->_($TypeData[$displayField])."</option>";
				}else
					$option_string .= "<option value='".$TypeData[$IDField]."'>".$TypeData['CountryName'] . "&nbsp;&nbsp;-&nbsp;&nbsp;" .$systemSetting->translate->_($TypeData[$displayField])."</option>";
			}
		}

		return $option_string;
   }

   public function getClubOptions($displayField = "Name", $IDField = "ID", $defaultValue = null, $orderBy = null, $where = null)
   {
		$systemSetting = new Zend_Session_Namespace('systemSetting');
	   	if ($orderBy)
   			$orderBy = " ORDER BY $orderBy ";
		
		
   		$sql = "select Club.*, Country.iso_3_code as CountryName from Country, MA, Club where Club.MAID=MA.ID AND MA.CountryID=Country.ID $where GROUP BY $IDField $orderBy ";

		$record = $this->_db->fetchAll($sql);
		$option_string = "";
		foreach ($record as $index => $TypeData)
		{	
			if (!is_null($TypeData[$displayField])){
				if (!is_null($defaultValue))
				{
					if ($defaultValue == $TypeData[$IDField])
						$option_string .= "<option value='".$TypeData[$IDField]."' selected>".$TypeData['CountryName'] . "&nbsp;&nbsp;-&nbsp;&nbsp;" .$systemSetting->translate->_($TypeData[$displayField])."</option>";
					else
						$option_string .= "<option value='".$TypeData[$IDField]."'>".$TypeData['CountryName'] . "&nbsp;&nbsp;-&nbsp;&nbsp;" .$systemSetting->translate->_($TypeData[$displayField])."</option>";
				}else
					$option_string .= "<option value='".$TypeData[$IDField]."'>".$TypeData['CountryName'] . "&nbsp;&nbsp;-&nbsp;&nbsp;" .$systemSetting->translate->_($TypeData[$displayField])."</option>";
			}
		}

		return $option_string;
   }

	
   public function getCountryOptions($displayField = "Name", $IDField = "ID", $defaultValue = null, $orderBy = null, $where = null)
   {
		$systemSetting = new Zend_Session_Namespace('systemSetting');
	   	if ($orderBy)
   			$orderBy = " ORDER BY $orderBy ";
		
		
   		$sql = "select Country.* from Country, MA where MA.CountryID=Country.ID $where GROUP BY $IDField $orderBy ";

		$record = $this->_db->fetchAll($sql);
		$option_string = "";
		foreach ($record as $index => $TypeData)
		{	
			if (!is_null($TypeData[$displayField])){
				if (!is_null($defaultValue))
				{
					if ($defaultValue == $TypeData[$IDField])
						$option_string .= "<option value='".$TypeData[$IDField]."' selected>".$systemSetting->translate->_($TypeData[$displayField])."</option>";
					else
						$option_string .= "<option value='".$TypeData[$IDField]."'>".$systemSetting->translate->_($TypeData[$displayField])."</option>";
				}else
					$option_string .= "<option value='".$TypeData[$IDField]."'>".$systemSetting->translate->_($TypeData[$displayField])."</option>";
			}
		}

		return $option_string;
   }
	
	public function getTableOptions($table, $displayField = "Name", $IDField = "ID", $defaultValue = null, $orderBy = null, $where = null)
   {
		$systemSetting = new Zend_Session_Namespace('systemSetting');
	   	if ($orderBy)
   			$orderBy = " ORDER BY $orderBy ";
		
		
   		$sql = "select * from `$table` where 1=1 $where GROUP BY $IDField $orderBy ";

		$record = $this->_db->fetchAll($sql);
		$option_string = "";
		foreach ($record as $index => $TypeData)
		{	
			if (!is_null($TypeData[$displayField])){
				if (!is_null($defaultValue))
				{
					if ($defaultValue == $TypeData[$IDField])
						$option_string .= "<option value='".$TypeData[$IDField]."' selected>".$systemSetting->translate->_($TypeData[$displayField])."</option>";
					else
						$option_string .= "<option value='".$TypeData[$IDField]."'>".$systemSetting->translate->_($TypeData[$displayField])."</option>";
				}else
					$option_string .= "<option value='".$TypeData[$IDField]."'>".$systemSetting->translate->_($TypeData[$displayField])."</option>";
			}
		}

		return $option_string;
   }
   
	public function getSystemOptions($table, $defaultValue = null)
   {
   
		$systemSetting = new Zend_Session_Namespace('systemSetting');
		$systemSetting->$table;
		
		
		foreach ($systemSetting->$table as $index => $TypeData)
		{	
		
			if (is_array($TypeData))
			{
				if (!is_null($defaultValue))
				{
					if ($defaultValue ==$index)
						$option_string .= "<option value='".$index."' selected>".$systemSetting->translate->_($TypeData[1])."</option>";
					else
						$option_string .= "<option value='".$index."'>".$systemSetting->translate->_($TypeData[1])."</option>";
				}else
					$option_string .= "<option value='".$index."'>".$systemSetting->translate->_($TypeData[1])."</option>";
				
			}else
			{
				if (!is_null($defaultValue))
				{
					if ($defaultValue ==$index)
						$option_string .= "<option value='".$index."' selected>".$systemSetting->translate->_($TypeData)."</option>";
					else
						$option_string .= "<option value='".$index."'>".$systemSetting->translate->_($TypeData)."</option>";
				}else
					$option_string .= "<option value='".$index."'>".$systemSetting->translate->_($TypeData)."</option>";
			}
		}
		return $option_string;
		

   }
       
   
    
}




?>