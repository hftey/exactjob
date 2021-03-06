<?php
 
class Venz_App_System_Notification extends Zend_Db_Table_Abstract
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

    public function setNotificationEmail($Subject, $Message, $Where = "1=1")
	{
	//	$headers = "From: DPS Administrator <noreply@wahkong.myvnc.com:8070>\r\n";
	//	$headers .= "Reply-To: noreply@wahkong.myvnc.com:8070\r\n";
	//	$headers .= "MIME-Version: 1.0\r\n";
	//	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		

		$mail = new Venz_App_Phpmailer_Main();
		$mail->IsSMTP();
		$mail->Host = 'mail.wahkong.net';
		$mail->SMTPDebug  = 2; 
		$mail->Port = 26;  
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		$mail->Username = 'yfm-dps@wahkong.net';  // a valid email here
		$mail->Password = '3edc#EDC#';
		
		$mail->From = 'yfm-dps@wahkong.net';
		$mail->AddReplyTo('yfm-dps@wahkong.net', 'DPS Admin');
		$mail->FromName = 'yfm-dps@wahkong.net';

		$mail->Subject = $Subject;
		$mail->ClearAllRecipients();
		
		$emailFooter = "<BR><BR><font style='font-size: 10px'>This is a automated notification email sent through DPS (<a href='http://wahkong.myvnc.com:8060'>http://wahkong.myvnc.com:8060</a>)</font>";
			
		$arrAdminAll = $this->_db->fetchAll("SELECT * FROM ACLUsers WHERE ".$Where);
		foreach ($arrAdminAll as $arrAdmin)
		{
			$emailHeader = "Dear ".$arrAdmin['Name']." <BR><BR>";
			$emailContent = $emailHeader.$Message.$emailFooter;
			if ($arrAdmin['Email']){
				$mail->ClearAllRecipients();
				$mail->AddAddress($arrAdmin['Email']);
				$mail->Body = $emailContent; 
				$mail->IsHTML(true); 				
				$mail->Send();
			}
			//	mail($arrAdmin['Email'], $Subject, $emailContent, $headers);
		}		
							
		
		
	}
	
	public function setNotification($Message, $Triggerby, $Link = NULL, $Where = "1=1")
    {
		$Link = strlen($Link) > 0 ? $Link : new Zend_Db_Expr('NULL');
		$sqlMA = "SELECT * FROM ACLUsers WHERE ".$Where;
		$arrMAAll = $this->_db->fetchAll($sqlMA);
		foreach ($arrMAAll as $arrMA)
		{
			if ($Triggerby != $arrMA['ID'])
			{
				$arrInsert = array("ACLUserID" => $arrMA['ID'], "Message"=>$Message, "Triggerby"=>$Triggerby, "Link"=>$Link, "MessageDate"=>new Zend_Db_Expr('now()'));
				$this->_db->insert("Notification", $arrInsert);
			}
		}
	
	}
	
	
	
	
	
	public function setNotificationEmailClub($ClubID, $Subject, $Message)
	{
		$headers = "From: AFC CLAS Administrator <noreply@clas.afc-link.com>\r\n";
		$headers .= "Reply-To: noreply@clas.afc-link.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		$emailFooter = "<BR><BR><font style='font-size: 10px'>This is a automated notification email sent through AFC CLAS (<a href='http://clas.afc-link.com'>http://clas.afc-link.com</a>)</font>";
		
		$sqlClub = "SELECT * FROM ACLUsers WHERE ClubID=".$ClubID." AND (ACLRole='club_user' OR ACLRole='club_admin')";
		$arrClubAll = $this->_db->fetchAll($sqlClub);
		foreach ($arrClubAll as $arrClub)
		{
			$emailHeader = "Dear ".$arrClub['Name']." <BR><BR>";
			$emailContent = $emailHeader.$Message.$emailFooter;
			if ($arrClub['Email'])
				mail($arrClub['Email'], $Subject, $emailContent, $headers);
		}		
		
	}

	
    public function setNotificationAdmin($Message, $Triggerby, $Link = NULL)
    {
		$Link = strlen($Link) > 0 ? $Link : new Zend_Db_Expr('NULL');
		$arrAdminAll = $this->_db->fetchAll("SELECT * FROM ACLUsers WHERE ACLRole='admin' OR ACLRole='system_admin'");
		foreach ($arrAdminAll as $arrAdmin)
		{
			if ($Triggerby != $arrAdmin['ID'])
			{
				$arrInsert = array("ACLUserID" => $arrAdmin['ID'], "Message"=>$Message, "Triggerby"=>$Triggerby, "Link"=>$Link, "MessageDate"=>new Zend_Db_Expr('now()'));
				$this->_db->insert("Notification", $arrInsert);
			
			}
		}
	
	}
	
	 
    public function setNotificationMA($MAID, $Message, $Triggerby, $Link = NULL)
    {
		$Link = strlen($Link) > 0 ? $Link : new Zend_Db_Expr('NULL');
		$sqlMA = "SELECT * FROM ACLUsers WHERE MAID=".$MAID." AND (ACLRole='ma_user' OR ACLRole='ma_admin')";
		$arrMAAll = $this->_db->fetchAll($sqlMA);
		foreach ($arrMAAll as $arrMA)
		{
			if ($Triggerby != $arrMA['ID'])
			{
				$arrInsert = array("ACLUserID" => $arrMA['ID'], "Message"=>$Message, "Triggerby"=>$Triggerby, "Link"=>$Link, "MessageDate"=>new Zend_Db_Expr('now()'));
				$this->_db->insert("Notification", $arrInsert);
			}
		}
	
	}
	
	
	public function setNotificationClub($ClubID, $Message, $Triggerby, $Link = NULL)
    {
		$Link = strlen($Link) > 0 ? $Link : new Zend_Db_Expr('NULL');
		$sqlClub = "SELECT * FROM ACLUsers WHERE ClubID=".$ClubID." AND (ACLRole='club_user' OR ACLRole='club_admin')";
		$arrClubAll = $this->_db->fetchAll($sqlClub);
		foreach ($arrClubAll as $arrClub)
		{
			if ($Triggerby != $arrClub['ID'])
			{
				$arrInsert = array("ACLUserID" => $arrClub['ID'], "Message"=>$Message, "Triggerby"=>$Triggerby, "Link"=>$Link, "MessageDate"=>new Zend_Db_Expr('now()'));
				$this->_db->insert("Notification", $arrInsert);
			}
		}
	
	}

	
	
	public function setNotificationClubUser($ClubID, $Message, $Triggerby, $Link = NULL)
    {
		$Link = strlen($Link) > 0 ? $Link : new Zend_Db_Expr('NULL');
		$sqlClub = "SELECT * FROM ACLUsers WHERE ClubID=".$ClubID." AND ACLRole='club_user'";
		$arrClubAll = $this->_db->fetchAll($sqlClub);
		foreach ($arrClubAll as $arrClub)
		{
			if ($Triggerby != $arrClub['ID'])
			{
				$arrInsert = array("ACLUserID" => $arrClub['ID'], "Message"=>$Message, "Triggerby"=>$Triggerby, "Link"=>$Link, "MessageDate"=>new Zend_Db_Expr('now()'));
				$this->_db->insert("Notification", $arrInsert);
			}
		}
	
	}

	public function setNotificationClubAdmin($ClubID, $Message, $Triggerby, $Link = NULL)
    {
		$Link = strlen($Link) > 0 ? $Link : new Zend_Db_Expr('NULL');
		$sqlClub = "SELECT * FROM ACLUsers WHERE ClubID=".$ClubID." AND ACLRole='club_admin'";
		$arrClubAll = $this->_db->fetchAll($sqlClub);
		foreach ($arrClubAll as $arrClub)
		{
			if ($Triggerby != $arrClub['ID'])
			{
				$arrInsert = array("ACLUserID" => $arrClub['ID'], "Message"=>$Message, "Triggerby"=>$Triggerby, "Link"=>$Link, "MessageDate"=>new Zend_Db_Expr('now()'));
				$this->_db->insert("Notification", $arrInsert);
			}
		}
	
	}
	
  
}




?>