<?php

class Admin_AclController extends Venz_Zend_Controller_Action
{

    public function init()
    {
        $actionName = $this->getRequest()->getActionName();
		switch ($actionName){
			default: parent::init("private");
		}	
    }
    public function changepasswordAction()
    {
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$Password = $Request->getParam('Password') ? new Zend_Db_Expr("MD5('".$Request->getParam('Password')."')") : false;
		$arrUpdate = array("Password"=>$Password);
		$db->update("ACLUsers", $arrUpdate, "ID=".$this->userInfo->ID);			
		exit();
	}
	
    public function indexAction()
    {
	
		try {
			if (!$this->userInfo){
				$this->appMessage->setMsg(0, "Please login first before accessing this page.");
				$this->_redirect('/auth');
			}
			$sessionUsers = new Zend_Session_Namespace('sessionUsers');
			$UserResourceName = "UserProfile";
			
			if(!$this->Acl->has($UserResourceName)) 
				$this->Acl->add(new Zend_Acl_Resource($UserResourceName));	
			else
			{
				$view = $this->Acl->isAllowed($this->userInfo->ACLRole, $UserResourceName, "view"); $this->view->chkView = $view ? "checked" : "";
				$edit = $this->Acl->isAllowed($this->userInfo->ACLRole, $UserResourceName, "edit"); $this->view->chkEdit = $edit ? "checked" : "";
				$delete = $this->Acl->isAllowed($this->userInfo->ACLRole, $UserResourceName, "delete"); $this->view->chkDelete = $delete ? "checked" : "";
			
			}
			$Request = $this->getRequest();
			$UpdatePriviledges = $Request->getParam('UpdatePriviledges');

			if ($UpdatePriviledges){
				$view = $Request->getParam('view'); $this->view->chkView = $view ? "checked" : "";
				$edit = $Request->getParam('edit'); $this->view->chkEdit = $edit ? "checked" : "";
				$delete = $Request->getParam('delete'); $this->view->chkDelete = $delete ? "checked" : "";
			}
			
			if ($UpdatePriviledges){
				
				if ($view)
					$this->Acl->allow($this->userInfo->ACLRole, $UserResourceName, "view");
				else
					$this->Acl->deny($this->userInfo->ACLRole, $UserResourceName, "view");
					
				if ($edit)
					$this->Acl->allow($this->userInfo->ACLRole, $UserResourceName, "edit");
				else
					$this->Acl->deny($this->userInfo->ACLRole, $UserResourceName, "edit");

				if ($delete)
					$this->Acl->allow($this->userInfo->ACLRole, $UserResourceName, "delete");
				else
					$this->Acl->deny($this->userInfo->ACLRole, $UserResourceName, "delete");
			
			}
			$this->view->allowView = $this->Acl->isAllowed($this->userInfo->ACLRole, $UserResourceName, "view");
			$this->view->allowEdit = $this->Acl->isAllowed($this->userInfo->ACLRole, $UserResourceName, "edit");
			$this->view->allowDelete = $this->Acl->isAllowed($this->userInfo->ACLRole, $UserResourceName, "delete");

		}catch (Exception $e) {
			echo $e->getMessage();
		}
    }

	

	public function usersAction()   
	{
	
	try {
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$sysHelper = new Venz_App_System_Helper();
		$sysAcl = new Venz_App_System_Acl();
		$libDb = new Venz_App_Db_Table();
		$sessionUsers = new Zend_Session_Namespace('sessionUsers');	
		
		/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
		if (!$this->userInfo){
			$this->appMessage->setMsg(0, "Please login first before accessing this page.");
			$this->_redirect('/auth');
		}
	
		$sortby = $Request->getParam('sortby');			
		if (strlen($sortby) == 0) $sortby = 'ID';
			
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
		$search_users = $Request->getParam('search_users');	
		$this->view->searchUsers = false;
		$strHiddenSearch = "";
		$this->view->radActive = "checked";
		if ($search_users)
		{
			$this->view->searchUsers = true;
			$SearchName = $Request->getParam('SearchName');	
			$SearchUsername = $Request->getParam('SearchUsername');	
			$SearchEmail = $Request->getParam('SearchEmail');	
			$SearchACLRole = $Request->getParam('SearchACLRole');	
			$SearchRadioActive = $Request->getParam('SearchRadioActive');	
			
			$sqlSearch .= $SearchName ? " and ACLUsers.Name LIKE '%".$SearchName."%'" : "";
			$sqlSearch .= $SearchUsername ? " and ACLUsers.Username LIKE '%".$SearchUsername."%'" : "";
			$sqlSearch .= $SearchEmail ? " and ACLUsers.Email LIKE '%".$SearchEmail."%'" : "";
			$sqlSearch .= $SearchACLRole ? " and ACLUsers.ACLRole = '".$SearchACLRole."'" : "";
			$sqlSearch .= strlen($SearchRadioActive) > 0 ? " and ACLUsers.Active = '".$SearchRadioActive."'" : " and ACLUsers.Active IS NULL";

			//print $sqlSearch; exit();
			$this->view->SearchName = $SearchName ? $SearchName : "";				
			$this->view->SearchUsername = $SearchUsername ? $SearchUsername : "";				
			$this->view->SearchEmail = $SearchEmail ? $SearchEmail : "";				
			$this->view->SearchACLRole = $SearchACLRole ? $SearchACLRole : "";				
			$this->view->SearchRadioActive = $SearchRadioActive ? $SearchRadioActive : "";	
			$this->view->radActive = $this->view->SearchRadioActive ? "checked" : "";
			$this->view->radNotActive = $this->view->SearchRadioActive ? "" : "checked";

			$strHiddenSearch = "<input type=hidden name='search_users' value='true'>";
			$strHiddenSearch .= "<input type=hidden name='Name' value='".$SearchName."'>";
			$strHiddenSearch .= "<input type=hidden name='Username' value='".$SearchUsername."'>";
			$strHiddenSearch .= "<input type=hidden name='Email' value='".$SearchEmail."'>";
			$strHiddenSearch .= "<input type=hidden name='ACLRole' value='".$SearchACLRole."'>";
			$strHiddenSearch .= "<input type=hidden name='radioActive' value='".$SearchRadioActive."'>";

		}
		
		if ($this->userInfo->ACLRole == "admin_branch")
			$sqlSearch .= " and ACLUsers.OfficeID=".$this->userInfo->OfficeID;
		
		
		$reset_password = $Request->getParam('reset_password');	
		if ($reset_password)
		{
			$userID = $reset_password;
			$rowAccount = $db->fetchRow("SELECT * FROM ACLUsers WHERE ID='".$userID."'");
			if (!$rowAccount)
			{
				$this->appMessage->setNotice(0, "The account does not exist.");
				$this->_redirect('/admin/acl/users/'); 
				exit();
			}
			
			$Email = $rowAccount['Email'];
			$Username = $rowAccount['Username'];
			$Password = $rowAccount['Password'];
			$Name = $rowAccount['Name'];
			
			$requestString = md5($Email.$Username.$Password.time());
			$requestLink = "http://".$_SERVER['HTTP_HOST']."/auth/index/resetpassword/k/".$requestString;
		
			$arrInsert = array("ACLUserID"=>$rowAccount['ID'], "RequestBy"=>$this->userInfo->ID, "Email"=>$Email,"RequestDate"=>new Zend_Db_Expr("now()"),  "RequestString"=>$requestString); 
			$db->Insert("PasswordRequest", $arrInsert);
			
			
			$headers = "From: AFC CLAS Administrator <noreply@clas.afc-link.com>\r\n";
			$headers .= "Reply-To: noreply@clas.afc-link.com\r\n";
		//	$headers .= "CC: susan@example.com\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			
			
			$emailContent = "Dear ".$Name." <BR><BR>
			We received a request to reset the password associated with this e-mail address. Please follow the instructions below to proceed.<BR><BR>
			Click the link below to reset your password. You need to enter your username to reset your password:<BR><BR>
			Username: ".$Username."<BR>
			<a href='".$requestLink."'>".$requestLink."</a><BR><BR>
			If you did not request to have your password reset you can safely ignore this email.<BR><BR>
			Thank you.
			";
								
			
			mail($Email, "AFC CLAS - Reset Password",$emailContent, $headers);
			
	//		print $emailContent; exit();
			
			$this->appMessage->setNotice(1, "Password reset request has been sent for ".$Username.".");
			$this->_redirect('/admin/acl/users/');  
		
		}
		
		$remove_users = $Request->getParam('remove_users');	
		if ($remove_users)
		{
			///// need to do further checking //////////////
/*				$arrUserDetail = $sysAcl->getUsersDetail($remove_users);
			if ($this->userInfo->ACLRole == "club_admin")
			{
				if ($this->userInfo->ClubID != $arrUserDetail['ClubID'])
				{
					$this->appMessage->setMsg(0, "Sorry, you do not have permission to perform this action.");
					$this->_redirect('/');	
					exit();						
				}
				
			}
			
			
			if ($this->userInfo->ACLRole == "ma_admin")
			{
				if ($this->userInfo->MAID != $arrUserDetail['MAID'])
				{
					$this->appMessage->setMsg(0, "Sorry, you do not have permission to perform this action.");
					$this->_redirect('/');					
					exit();						
				}
				
			}
*/				
			if ($this->userInfo->ACLRole == "admin")
			{
				$db->delete("ACLUsers", "ID=".$remove_users);
				$this->appMessage->setNotice(1, "The user has been deleted.");
				$this->_redirect('/admin/acl/users/'); 
			}else
			{
				$this->appMessage->setMsg(0, "Sorry, you do not have permission to perform this action.");
				$this->_redirect('/');					
				exit();						
			}
			exit();						
			
		}
		
		
		$add_users = $Request->getParam('add_users');	
		if ($add_users)
		{
			$sessionAddUsers = new Zend_Session_Namespace('sessionAddUsers');	
			$sessionAddUsers->Username = $Request->getParam('Username');
			$sessionAddUsers->Name = $Request->getParam('Name');
			$sessionAddUsers->Email = $Request->getParam('Email');
			$sessionAddUsers->ACLRole = $Request->getParam('ACLRole');
			$sessionAddUsers->AddError = false;

			$Username = $Request->getParam('Username') ? trim($Request->getParam('Username')) : new Zend_Db_Expr("NULL");
			$Email = $Request->getParam('Email') ? trim($Request->getParam('Email')) : new Zend_Db_Expr("NULL");
			$rowAccount = $db->fetchRow("SELECT * FROM ACLUsers WHERE Username='".$Username."'");
			if ($rowAccount)
			{
	
				$sessionAddUsers->AddError = true;
				$this->appMessage->setNotice(0, "The username '<B>".$Username."</B>' is no longer available.");
				$this->_redirect('/admin/acl/users/'); 
				exit();
			}
			
			$rowAccountEmail = $db->fetchRow("SELECT * FROM ACLUsers WHERE Email='".$Email."'");
			if ($rowAccountEmail)
			{
	
				$sessionAddUsers->AddError = true;
				$this->appMessage->setNotice(0, "The email address '<B>".$Email."</B>' is being used by <B>".$rowAccountEmail['Username']."</B> and is not available to be used again.");
				$this->_redirect('/admin/acl/users/'); 
				exit();
			}
			
			$ACLPassword = new Zend_Db_Expr("MD5('".$Request->getParam('ACLPassword')."')");
			// set a dummy password
			//$ACLPassword = md5($ACLRole.time());
			$Name = $Request->getParam('Name') ? $Request->getParam('Name') : new Zend_Db_Expr("NULL");
			$ACLRole = $Request->getParam('ACLRole') ? $Request->getParam('ACLRole') : new Zend_Db_Expr("NULL");
			$CriteriaCategoryID = $Request->getParam('CriteriaCategoryID') ? $Request->getParam('CriteriaCategoryID') : new Zend_Db_Expr("NULL");
			$radioActive = $Request->getParam('radioActive') ? $Request->getParam('radioActive') : false;
			
			$arrInsert = array("ACLRole"=>$ACLRole,"UserCreated"=>$this->userInfo->Username, "Name"=>$Name,"Username"=>$Username,"Email"=>$Email,
				"Active"=>$radioActive, "Password"=>$ACLPassword, "DateCreated"=>new Zend_Db_Expr("now()"));
			
			$db->insert("ACLUsers", $arrInsert);
			$ACLUserID = $db->lastInsertId();
			
			$this->appMessage->setNotice(1, "A new login account with the username ".$Username." has been created.");
			$this->_redirect('/admin/acl/users/');  
//				exit();
			
		}
		
		
		$sessionAddUsers = new Zend_Session_Namespace('sessionAddUsers');	
		if ($sessionAddUsers->AddError)
		{
			$sessionAddUsers->AddError = false;
			$this->view->Username = $sessionAddUsers->Username;
			$this->view->Name = $sessionAddUsers->Name;
			$this->view->Email = $sessionAddUsers->Email;
			$this->view->ACLRole = $sessionAddUsers->ACLRole;
		}
		
		
		$this->view->edit_users = '';
		$edit_users = $Request->getParam('edit_users');	
		if ($edit_users)
		{
			$arrUserDetail = $sysAcl->getUsersDetail($edit_users);
			
		//	$this->appMessage->setNotice(3, $this->translate->_('Please leave the password empty if you do not wish to change the password').".");
			$this->view->edit_users = $edit_users;
			$arrUserDetail = $sysAcl->getUsersDetail($edit_users);
			if (!$arrUserDetail)
			{
				$this->_redirect('/admin/acl/users/'); exit();
			}
			$this->view->Name = $arrUserDetail['Name'];			
			$this->view->Username = $arrUserDetail['Username'];		
			$this->view->Email = $arrUserDetail['Email'];		
			$this->view->ACLRole = $arrUserDetail['ACLRole'];	
			$this->view->radioActive = $arrUserDetail['Active'];
			$this->view->radActive = $this->view->radioActive ? "checked" : "";
			$this->view->radNotActive = $this->view->radioActive ? "" : "checked";
			
			
		}					
	
	
		$save_users = $Request->getParam('save_users');	
		if ($save_users)
		{
			$ID = $Request->getParam('save_users_id') ? $Request->getParam('save_users_id') : new Zend_Db_Expr("NULL");
			$arrUserDetail = $sysAcl->getUsersDetail($ID);
			
			if (!$arrUserDetail)
			{
				$this->_redirect('/admin/acl/users/'); exit();
			}
			
			$Name = $Request->getParam('Name') ? $Request->getParam('Name') : new Zend_Db_Expr("NULL");
			$Username = $Request->getParam('Username') ? $Request->getParam('Username') : new Zend_Db_Expr("NULL");
			$ACLPassword = $Request->getParam('ACLPassword') ? new Zend_Db_Expr("MD5('".$Request->getParam('ACLPassword')."')") : false;
			$Email = $Request->getParam('Email') ? $Request->getParam('Email') : new Zend_Db_Expr("NULL");
			$ACLRole = $Request->getParam('ACLRole') ? $Request->getParam('ACLRole') : new Zend_Db_Expr("NULL");
			$radioActive = $Request->getParam('radioActive') ? $Request->getParam('radioActive') : new Zend_Db_Expr("NULL");

			$arrUpdate = array("ACLRole"=>$ACLRole,"Name"=>$Name,"Username"=>$Username,"Email"=>$Email,"Active"=>$radioActive);
			if ($ACLPassword)
				$arrUpdate['Password'] = $ACLPassword;

			$db->update("ACLUsers", $arrUpdate, "ID=".$ID);
			$this->appMessage->setNotice(1, $this->translate->_('Record for')." <B>".$Username."</B> ".$this->translate->_('has been updated').".");
			$this->_redirect('/admin/acl/users/'); 
							
		}


	//	$remove_users = $Request->getParam('remove_users');	
	//	if ($remove_users)
	//	{
	//		$arrUserDetail = $sysAcl->getUsersDetail($remove_users);
	//		if (!$arrUserDetail)
	//		{
	//			$this->_redirect('/admin/acl/users/'); 
	//		}
	//	
	//		$db->delete("ACLUsers", "ID=".$remove_users);
	//		$this->_redirect('/admin/acl/users/');   				
	//	}			
		
	
		
		
		
		if ($this->userInfo->ACLRole == "AdminSystem")
			$this->view->optionsRole = $libDb->getTableOptions('ACLRole', "Description", "Name", $this->view->ACLRole, "ID");
		

		$sysAcl->setFetchMode(Zend_Db::FETCH_NUM);

		$arrUsers = $sysAcl->getUsers($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
		$dataUsers = $arrUsers[1];
		
		function format_date($colnum, $rowdata)
		{

			$dispFormat = new Venz_App_Display_Format();
			return $dispFormat->format_datetime_simple($rowdata[6], "<BR>");
			
		}
		
		function format_date_created($colnum, $rowdata)
		{

			$dispFormat = new Venz_App_Display_Format();
			return $dispFormat->format_datetime_simple($rowdata[7], "<BR>");
			
		}
		
		function format_action($colnum, $rowdata)
		{
			$systemSetting = new Zend_Session_Namespace('systemSetting');
			$sessionUsers = new Zend_Session_Namespace('sessionUsers');
			
			if ($sessionUsers->userInfo->ACLRole == "AdminSystem")
				return "<a href='/admin/acl/users/edit_users/".$rowdata[0]."#tabs1'><img border=0 src='/images/icons/IconEdit.gif'></a> | <a href='javascript:void(0);' onclick='OnDeleteUsers(".$rowdata[0].")'><img border=0 src='/images/icons/IconDelete.gif'></a>";
			
			return "-";
		}		

		$sessionUsers = new Zend_Session_Namespace('sessionUsers');
		$sessionUsers->numCounter = $recordsPerPage * ($showPage-1);
		function format_counter($colnum, $rowdata)
		{
			$sessionUsers = new Zend_Session_Namespace('sessionUsers');
			$sessionUsers->numCounter++;
			return $sessionUsers->numCounter;
		}
		
		function format_active($colnum, $rowdata)
		{
			$systemSetting = new Zend_Session_Namespace('systemSetting');
			return $rowdata[5] ? $systemSetting->translate->_('Yes') : $systemSetting->translate->_('No');
		}
		
		function format_name($colnum, $rowdata)
		{
			$strExtra = "";
			if ($rowdata[3] == "transporter")
				$strExtra = "<BR>(".$rowdata[9].")";
			if ($rowdata[3] == "loading_warehouse")
				$strExtra = "<BR>(".$rowdata[10].")";
			return $rowdata[1].$strExtra;
		}
		
		$strSearch = "";
		if ($this->view->searchUsers)
			$strSearch = "<input type=hidden name=''>";
		
		$arrHeader = array ('', $this->translate->_('Name'), $this->translate->_('Username'), $this->translate->_('Email'), $this->translate->_('Role'), $this->translate->_('Active'), $this->translate->_('Last Login'), $this->translate->_('Date Created'), $this->translate->_('Action'));
		$displayTable = new Venz_App_Display_Table(
			array (
				 'data' => $dataUsers,
				 'hiddenparamtop'=> $strSearch,
				 'headings' => $arrHeader,
				 'format' 		=> array('{format_counter}','{format_name}','%2%','%4%', '%3%', '{format_active}', '{format_date}','{format_date_created}','{format_action}'),					 
				 'sort_column' 	=> array('','Name', 'Username', 'Email', 'OfficeName','ACLRole', 'Active', 'LastLogin', 'DateCreated', ''),
				 'alllen' 		=> $arrUsers[0],
				 'title'		=> $this->translate->_('Users'),					 
				 'aligndata' 	=> 'CLLLLLLCCC',
				 'pagelen' 		=> $recordsPerPage,
				 'numcols' 		=> sizeof($arrHeader),
				 'tablewidth' => "1350px",
				 'sortby' => $sortby,
				 'ascdesc' => $ascdesc,
				 'hiddenparam' => $strHiddenSearch,
			)
		);
		$this->view->content_users = $displayTable->render();
		
		
		
		
	}catch (Exception $e) {
	
		echo $e->getMessage();
	}		
	
	}
	
	public function jsonrolesaccessexAction()
	{
	try {
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$sysAcl = new Venz_App_System_Acl();
		$libDb = new Venz_App_Db_Table();
		$roles = $Request->getParam('roles');	
		$allow = $Request->getParam('allow');	
		$deny = $Request->getParam('deny');	
		
		$arrACLRoleMap = array();
		if ($allow)
			$arrACLRoleAll = $db->fetchAll("SELECT * FROM ACLMap where Role = '".$roles."' and Allow=1 ");
		else if ($deny)	
			$arrACLRoleAll = $db->fetchAll("SELECT * FROM ACLMap where Role = '".$roles."' and Allow=0 ");
			
			
		foreach ($arrACLRoleAll as $arrACLRole)
		{
			$arrACLRoleMap[$arrACLRole['Resources']][$arrACLRole['Priviledges']] = 1;
		}
		
		
		$arrACLResourcesAll = $db->fetchAll("SELECT * FROM ACLResources WHERE 1=1 order by Name");
		$arrACLPriviledgesAll = $db->fetchAll("SELECT * FROM ACLPriviledges WHERE 1=1 ");
	
		$matrix = array();
		foreach ($arrACLPriviledgesAll as $i => $arrACLPriviledges)
		{
			$matrix['priviledges'][$i] = $arrACLPriviledges['Name'];
	
		}
		
		
		
		foreach ($arrACLResourcesAll as $arrACLResources)
		{
			foreach ($arrACLPriviledgesAll as $arrACLPriviledges)
			{
				$matrix['data'][$arrACLResources['Name']][$arrACLPriviledges['Name']] = $arrACLRoleMap[$arrACLResources['Name']][$arrACLPriviledges['Name']] ? true : false;
		
			}
			
		}

		echo Zend_Json::encode($matrix);
	}catch (Exception $e) {
	
		echo $e->getMessage();
	}			
		
		
		
		
		exit();
	}

	
	public function rolesaccessexAction()   
	{	
		try {
			$Request = $this->getRequest();			
			$db = Zend_Db_Table::getDefaultAdapter(); 
			$sysAcl = new Venz_App_System_Acl();
			$libDb = new Venz_App_Db_Table();
			/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
			$accessMapAllow = $Request->getParam('accessMapAllow');
			
			$accessMapDeny = $Request->getParam('accessMapDeny');
			
			$ACLRole = $Request->getParam('ACLRole');	
			$Update = $Request->getParam('Update');	
			$this->view->optionsRole = $libDb->getTableOptions('ACLRole', "Name", "Name", $ACLRole, NULL);
			
			if ($Update)
			{
				
				$db->query("DELETE FROM ACLMap where Role = '".$ACLRole."'");
				
				foreach ($accessMapAllow as $indexResourceAllow => $value)
				{
					$arrIndexResourceAllow = explode("|", $indexResourceAllow);
					$arrResourceAllow = array("Role"=>$ACLRole, "Resources" => $arrIndexResourceAllow[0], "Priviledges" => $arrIndexResourceAllow[1], "Allow" => 1);
					$db->insert("ACLMap", $arrResourceAllow);
				}
				
				foreach ($accessMapDeny as $indexResourceDeny => $value)
				{
					$arrIndexResourceDeny = explode("|", $indexResourceDeny);
					$arrResourceDeny = array("Role"=>$ACLRole, "Resources" => $arrIndexResourceDeny[0], "Priviledges" => $arrIndexResourceDeny[1], "Allow" => 0);
					$db->insert("ACLMap", $arrResourceDeny);
				}					
				
			}
			
		}catch (Exception $e)
		{
			echo $e->getMessage();
		}
					
	}

	
	public function rolesaccessAction()   
	{
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$sysAcl = new Venz_App_System_Acl();
		$libDb = new Venz_App_Db_Table();
		/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
		
		$sortby = $Request->getParam('sortby');			
		if (strlen($sortby) == 0) $sortby = 'ID';
			
		$ascdesc = $Request->getParam('ascdesc');			
		if (strlen($ascdesc) == 0) $ascdesc = 'asc'; 
		
		$showPage = $Request->getParam('Pagerpagenum');			
		if (!$showPage) $showPage = 1; 
			
		$pagerNext = $Request->getParam('Pager_next_page');			
		if (strlen($pagerNext) > 0) $showPage++; 	

		$pagerPrev = $Request->getParam('Pager_prev_page');			
		if (strlen($pagerPrev) > 0) $showPage--; 	
		
		$recordsPerPage = 10 ;
		////////////////////////////////////////////////////////////////////////////////////////
		$add_rolesaccess = $Request->getParam('add_rolesaccess');	
		if ($add_rolesaccess)
		{
			$Role = $Request->getParam('Role');	
			$Resources = $Request->getParam('Resources');
			$Priviledges = $Request->getParam('Priviledges');
			$Access = $Request->getParam('Access');
			if (!$sysAcl->isRolesaccess($Role, $Resources, $Priviledges))
			{
				$arrInsert = array("Role"=>$Role,"Resources"=>$Resources,"Priviledges"=>$Priviledges,"Allow"=>$Access);
				$db->insert("ACLMap", $arrInsert);
			}
			$this->_redirect('/admin/acl/rolesaccess/');   				
		}
		
		$save_rolesaccess = $Request->getParam('save_rolesaccess');	
		if ($save_rolesaccess)
		{
			$Role = $Request->getParam('Role');	
			$Resources = $Request->getParam('Resources');
			$Priviledges = $Request->getParam('Priviledges');
			$Access = $Request->getParam('Access');		
			$ID = $Request->getParam('save_rolesaccess_id');
			$arrRolesaccessDetail = $sysAcl->getRolesaccessDetail($ID);
			if (!$arrRolesaccessDetail)
			{
				$this->_redirect('/admin/acl/rolesaccess/'); exit(); 
			}
			
			if (!$sysAcl->isRolesaccess($Role, $Resources, $Priviledges))
			{
				$arrUpdate = array("Role"=>$Role,"Resources"=>$Resources,"Priviledges"=>$Priviledges,"Allow"=>$Access);
				$db->update("ACLMap", $arrUpdate, "ID=".$ID);
			}
			$this->_redirect('/admin/acl/rolesaccess/');   				
		}


		$remove_rolesaccess = $Request->getParam('remove_rolesaccess');	
		if ($remove_rolesaccess)
		{
			$arrRolesaccessDetail = $sysAcl->getRolesaccessDetail($remove_rolesaccess);
			if (!$arrRolesaccessDetail)
			{
				$this->_redirect('/admin/acl/rolesaccess/'); exit(); 
			}
			
			$db->delete("ACLMap", "ID=".$remove_rolesaccess);
			$this->_redirect('/admin/acl/rolesaccess/');   				
		}			
		
		$this->view->edit_rolesaccess = '';
		$edit_rolesaccess = $Request->getParam('edit_rolesaccess');	
		if ($edit_rolesaccess)
		{
			$this->view->edit_rolesaccess = $edit_rolesaccess;
			$arrRolesaccessDetail = $sysAcl->getRolesaccessDetail($edit_rolesaccess);
			if (!$arrRolesaccessDetail)
			{
				$this->_redirect('/admin/acl/rolesaccess/'); exit(); 
			}				
			
			
			$this->view->Role = $arrRolesaccessDetail['Role'];			
			$this->view->Resources = $arrRolesaccessDetail['Resources'];		
			$this->view->Priviledges = $arrRolesaccessDetail['Priviledges'];		
			$this->view->Access = $arrRolesaccessDetail['Access'];		
		}			
		
		$sqlSearch = "";
		$search_rolesaccess = $Request->getParam('search_rolesaccess');	
		$strHiddenSearch = "";
		if ($search_rolesaccess)
		{
			$Role = $Request->getParam('Role');	
			$sqlSearch .= $Role ? " and Role LIKE '%".$Role."%'" : "";
			
			$Resources = $Request->getParam('Resources');	
			$sqlSearch .= $Resources ? " and Resources LIKE '%".$Resources."%'" : "";

			$Priviledges = $Request->getParam('Priviledges');	
			$sqlSearch .= $Priviledges ? " and Priviledges LIKE '%".$Priviledges."%'" : "";

			$Access = $Request->getParam('Access');	
			$sqlSearch .= $Access ? " and Allow = ".$Access."" : "";
			
			$this->view->Role = $Role ? $Role : "";				
			$this->view->Resources = $Resources ? $Resources : "";				
			$this->view->Priviledges = $Priviledges ? $Priviledges : "";				
			$this->view->Access = $Access ? $Access : "";				
			$strHiddenSearch = "<input type=hidden name='search_rolesaccess' value='true'>";
			$strHiddenSearch .= "<input type=hidden name='Role' value='".$Role."'>";
			$strHiddenSearch .= "<input type=hidden name='Resources' value='".$Resources."'>";
			$strHiddenSearch .= "<input type=hidden name='Priviledges' value='".$Priviledges."'>";
			$strHiddenSearch .= "<input type=hidden name='Access' value='".$Access."'>";
			
		
		}
		
		
		$this->view->optionsRole = $libDb->getTableOptions('ACLRole', "Name", "Name", $this->view->Role);
		$this->view->optionsResources = $libDb->getTableOptions('ACLResources', "Name", "Name", $this->view->Resources);
		$this->view->optionsPriviledges = $libDb->getTableOptions('ACLPriviledges', "Name", "Name", $this->view->Priviledges);
		
		$this->view->optionsAccess = "";
		foreach (array(1=>'Allow', 0=>'Deny') as $AccessID => $Access)
		{
			if ($this->view->Access == $AccessID && strlen($AccessID) == 0)
				$this->view->optionsAccess .= "<OPTION value='".$AccessID."' SELECTED>".$Access;
			else
				$this->view->optionsAccess .= "<OPTION value='".$AccessID."'>".$Access;
		}
		
		$sysAcl->setFetchMode(Zend_Db::FETCH_NUM);
		$arrRolesaccess = $sysAcl->getRolesaccess($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
		$dataRolesaccess = $arrRolesaccess[1];
		
		function format_access($colnum, $rowdata)
		{
			return $rowdata[4] ? "Allow" : "Deny";
		}			
		function format_action($colnum, $rowdata)
		{
			$systemSetting = new Zend_Session_Namespace('systemSetting');			
			return "<a href='/admin/acl/rolesaccess/edit_rolesaccess/".$rowdata[0]."'><img border=0 src='/images/icons/IconEdit.gif'></a> | <a href='javascript:void(0);' onclick='OnDeleteRolesaccess(".$rowdata[0].")'><img border=0 src='/images/icons/IconDelete.gif'></a>";
		}
		$sessionRolesaccess = new Zend_Session_Namespace('sessionRolesaccess');
		$sessionRolesaccess->numCounter = $recordsPerPage * ($showPage-1);
		function format_counter($colnum, $rowdata)
		{
			$sessionRolesaccess = new Zend_Session_Namespace('sessionRolesaccess');
			$sessionRolesaccess->numCounter++;
			return $sessionRolesaccess->numCounter;
		}
		
		$arrHeader = array ('', 'ID', $this->translate->_('Role'), $this->translate->_('Resources'), $this->translate->_('Priviledges'), $this->translate->_('Access'), '');
		$displayTable = new Venz_App_Display_Table(
			array (
				 'data' => $dataRolesaccess,
				 'headings' => $arrHeader,
				 'format' 		=> array('{format_counter}','%0%','%1%','%2%','%3%','{format_access}', '{format_action}'),					 
				 'sort_column' 	=> array('','ID','Role', 'Resources', 'Priviledges', 'Allow', ''),
				 'alllen' 		=> $arrRolesaccess[0],
				 'title'		=> 'Roles',					 
				 'aligndata' 	=> 'LLLLL',
				 'pagelen' 		=> $recordsPerPage,
				 'numcols' 		=> sizeof($arrHeader),
				 'tablewidth' => "700px",
				 'sortby' => $sortby,
				 'ascdesc' => $ascdesc,
				 'hiddenparam' => $strHiddenSearch,
			)
		);
		$this->view->content_rolesaccess = $displayTable->render();
				
	}	

	public function rolesAction()   
	{
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$sysAcl = new Venz_App_System_Acl();
		$libDb = new Venz_App_Db_Table();
		/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
		
		$sortby = $Request->getParam('sortby');			
		if (strlen($sortby) == 0) $sortby = 'ID';
			
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
		$add_roles = $Request->getParam('add_roles');	
		if ($add_roles)
		{
			$Name = $Request->getParam('Name');	
			$Description = $Request->getParam('Description');
			$ParentName = $Request->getParam('ParentName');
			$arrInsert = array("Name"=>$Name,"Description"=>$Description,"ParentName"=>$ParentName);
			$db->insert("ACLRole", $arrInsert);
			$this->_redirect('/admin/acl/roles/');   				
		}
		
		$save_roles = $Request->getParam('save_roles');	
		if ($save_roles)
		{
			$Name = $Request->getParam('Name');	
			$Description = $Request->getParam('Description');
			$ParentName = $Request->getParam('ParentName');				
			$ID = $Request->getParam('save_roles_id');	
			$arrRolesDetail = $sysAcl->getRolesDetail($ID);
			if (!$arrRolesDetail)
			{
				$this->_redirect('/admin/acl/roles/'); exit(); 
			}				
			
			
			$arrUpdate = array("Name"=>$Name,"Description"=>$Description,"ParentName"=>$ParentName);
			$db->update("ACLRole", $arrUpdate, "ID=".$ID);
			$this->_redirect('/admin/acl/roles/');   				
		}


		$remove_roles = $Request->getParam('remove_roles');	
		if ($remove_roles)
		{
			$arrRolesDetail = $sysAcl->getRolesDetail($remove_roles);
			if (!$arrRolesDetail)
			{
				$this->_redirect('/admin/acl/roles/'); exit(); 
			}
			$db->delete("ACLRole", "ID=".$remove_roles);
			$this->_redirect('/admin/acl/roles/');   				
		}			
		
		$this->view->edit_roles = '';
		$edit_roles = $Request->getParam('edit_roles');	
		if ($edit_roles)
		{
			$this->view->edit_roles = $edit_roles;
			$arrRolesDetail = $sysAcl->getRolesDetail($edit_roles);
			if (!$arrRolesDetail)
			{
				$this->_redirect('/admin/acl/roles/'); exit(); 
			}
			
			$this->view->Name = $arrRolesDetail['Name'];			
			$this->view->Description = $arrRolesDetail['Description'];		
			$this->view->ParentName = $arrRolesDetail['ParentName'];		
		}			
		
		$sqlSearch = "";
		$search_roles = $Request->getParam('search_roles');	
		$strHiddenSearch = "";
		if ($search_roles)
		{
			$Name = $Request->getParam('Name');	
			$sqlSearch .= $Name ? " and Name LIKE '%".$Name."%'" : "";
			
			$Description = $Request->getParam('Description');	
			$sqlSearch .= $Description ? " and Description LIKE '%".$Description."%'" : "";

			$ParentName = $Request->getParam('ParentName');	
			$sqlSearch .= $ParentName ? " and ParentName LIKE '%".$ParentName."%'" : "";

			
			$this->view->Name = $Name ? $Name : "";				
			$this->view->Description = $Description ? $Description : "";				
			$this->view->ParentName = $ParentName ? $ParentName : "";				
			$strHiddenSearch = "<input type=hidden name='search_roles' value='true'>";
			$strHiddenSearch .= "<input type=hidden name='Name' value='".$Name."'>";
			$strHiddenSearch .= "<input type=hidden name='ParentName' value='".$ParentName."'>";
			$strHiddenSearch .= "<input type=hidden name='Description' value='".$Description."'>";

		}
		

		$sysAcl->setFetchMode(Zend_Db::FETCH_NUM);
		$arrRoles = $sysAcl->getRoles($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
		$dataRoles = $arrRoles[1];
		
		function format_action($colnum, $rowdata)
		{
			$systemSetting = new Zend_Session_Namespace('systemSetting');

			$db = Zend_Db_Table::getDefaultAdapter(); 
			$arrMapExist = $db->fetchRow("SELECT * FROM ACLMap where Role ='".$rowdata[1]."'");
			
			if ($arrMapExist)
				return " ** ";
			else
				return "<a href='/admin/acl/roles/edit_roles/".$rowdata[0]."'><img border=0 src='/images/icons/IconEdit.gif'></a> | <a href='javascript:void(0);' onclick='OnDeleteRoles(".$rowdata[0].")'><img border=0 src='/images/icons/IconDelete.gif'></a>";
		}
		$sessionRoles = new Zend_Session_Namespace('sessionRoles');
		$sessionRoles->numCounter = $recordsPerPage * ($showPage-1);
		function format_counter($colnum, $rowdata)
		{
			$sessionRoles = new Zend_Session_Namespace('sessionRoles');
			$sessionRoles->numCounter++;
			return $sessionRoles->numCounter;
		}
		
		function format_count($colnum, $rowdata)
		{
		
			$db = Zend_Db_Table::getDefaultAdapter(); 
			return count($db->fetchAll("SELECT * FROM ACLUsers where ACLRole='".$rowdata[1]."'"));
		}
		
		$arrHeader = array ('', 'ID', $this->translate->_('Name'), $this->translate->_('Description'), $this->translate->_('Parent Name'), $this->translate->_('Number of<BR>Users'), 'Action');
		$displayTable = new Venz_App_Display_Table(
			array (
				 'data' => $dataRoles,
				 'headings' => $arrHeader,
				 'format' 		=> array('{format_counter}','%0%','%1%','%2%','%3%','{format_count}', '{format_action}'),					 
				 'sort_column' 	=> array('','ID','Name', 'Description', 'ParentName', '', ''),
				 'alllen' 		=> $arrRoles[0],
				 'title'		=> $this->translate->_('Roles'),					 
				 'aligndata' 	=> 'LLLLLCL',
				 'pagelen' 		=> $recordsPerPage,
				 'numcols' 		=> sizeof($arrHeader),
				 'tablewidth' => "700px",
				 'sortby' => $sortby,
				 'ascdesc' => $ascdesc,
				 'hiddenparam' => $strHiddenSearch,
			)
		);
		$this->view->content_roles = $displayTable->render();
				
	}




	public function priviledgesAction()   
	{
	
		try {
	
			$Request = $this->getRequest();			
			$db = Zend_Db_Table::getDefaultAdapter(); 
			$sysAcl = new Venz_App_System_Acl();
			$libDb = new Venz_App_Db_Table();
			/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
			
			$sortby = $Request->getParam('sortby');			
			if (strlen($sortby) == 0) $sortby = 'ID';
				
			$ascdesc = $Request->getParam('ascdesc');			
			if (strlen($ascdesc) == 0) $ascdesc = 'asc'; 
			
			$showPage = $Request->getParam('Pagerpagenum');			
			if (!$showPage) $showPage = 1; 
				
			$pagerNext = $Request->getParam('Pager_next_page');			
			if (strlen($pagerNext) > 0) $showPage++; 	

			$pagerPrev = $Request->getParam('Pager_prev_page');			
			if (strlen($pagerPrev) > 0) $showPage--; 	
			
			$recordsPerPage = 10 ;
			////////////////////////////////////////////////////////////////////////////////////////
			$add_priviledges = $Request->getParam('add_priviledges');	
			if ($add_priviledges)
			{
				$Name = $Request->getParam('Name');	
				$Description = $Request->getParam('Description');
				$arrInsert = array("Name"=>$Name,"Description"=>$Description);
				$db->insert("ACLPriviledges", $arrInsert);
				$this->_redirect('/admin/acl/priviledges/');   				
			}
			
			$save_priviledges = $Request->getParam('save_priviledges');	
			if ($save_priviledges)
			{
				$Name = $Request->getParam('Name');	
				$Description = $Request->getParam('Description');					
				$ID = $Request->getParam('save_priviledges_id');
				$arrPriviledgesDetail = $sysAcl->getPriviledgesDetail($ID);
				if (!$arrPriviledgesDetail)
				{
					$this->_redirect('/admin/acl/priviledges/'); exit(); 
				}						
				$arrUpdate = array("Name"=>$Name,"Description"=>$Description);
				$db->update("ACLPriviledges", $arrUpdate, "ID=".$ID);
				$this->_redirect('/admin/acl/priviledges/');   				
			}


			$remove_priviledges = $Request->getParam('remove_priviledges');	
			if ($remove_priviledges)
			{
				$arrPriviledgesDetail = $sysAcl->getPriviledgesDetail($remove_priviledges);
				if (!$arrPriviledgesDetail)
				{
					$this->_redirect('/admin/acl/priviledges/'); exit(); 
				}	
				$db->delete("ACLPriviledges", "ID=".$remove_priviledges);
				$this->_redirect('/admin/acl/priviledges/');   				
			}			
			
			$this->view->edit_priviledges = '';
			$edit_priviledges = $Request->getParam('edit_priviledges');	
			if ($edit_priviledges)
			{
				$this->view->edit_priviledges = $edit_priviledges;
				$arrPriviledgesDetail = $sysAcl->getPriviledgesDetail($edit_priviledges);
				if (!$arrPriviledgesDetail)
				{
					$this->_redirect('/admin/acl/priviledges/'); exit(); 
				}	
				$this->view->Name = $arrPriviledgesDetail['Name'];			
				$this->view->Description = $arrPriviledgesDetail['Description'];		
			}			
			
			$sqlSearch = "";
			$search_priviledges = $Request->getParam('search_priviledges');	
			$strHiddenSearch = "";
			if ($search_priviledges)
			{
				$Name = $Request->getParam('Name');	
				$sqlSearch .= $Name ? " and Name LIKE '%".$Name."%'" : "";
				
				$Description = $Request->getParam('Description');	
				$sqlSearch .= $Description ? " and Description LIKE '%".$Description."%'" : "";
				
				$this->view->Name = $Name ? $Name : "";				
				$this->view->Description = $Description ? $Description : "";				
				$strHiddenSearch = "<input type=hidden name='search_priviledges' value='true'>";
				$strHiddenSearch .= "<input type=hidden name='Name' value='".$Name."'>";
				$strHiddenSearch .= "<input type=hidden name='Description' value='".$Description."'>";
						
			
			
			}
			

			$sysAcl->setFetchMode(Zend_Db::FETCH_NUM);
			$arrPriviledges = $sysAcl->getPriviledges($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
			$dataPriviledges = $arrPriviledges[1];
			
			function format_action($colnum, $rowdata)
			{
				$systemSetting = new Zend_Session_Namespace('systemSetting');

				$db = Zend_Db_Table::getDefaultAdapter(); 
				$arrMapExist = $db->fetchRow("SELECT * FROM ACLMap where Priviledges ='".$rowdata[1]."'");
				
				if ($arrMapExist)
					return " ** ";
				else
					return "<a href='/admin/acl/priviledges/edit_priviledges/".$rowdata[0]."'><img border=0 src='/images/icons/IconEdit.gif'></a> | <a href='javascript:void(0);' onclick='OnDeletePriviledges(".$rowdata[0].")'><img border=0 src='/images/icons/IconDelete.gif'></a>";
			}
			$sessionPriviledges = new Zend_Session_Namespace('sessionPriviledges');
			$sessionPriviledges->numCounter = $recordsPerPage * ($showPage-1);
			function format_counter($colnum, $rowdata)
			{
				$sessionPriviledges = new Zend_Session_Namespace('sessionPriviledges');
				$sessionPriviledges->numCounter++;
				return $sessionPriviledges->numCounter;
			}
			
			$arrHeader = array ('', 'ID', $this->translate->_('Name'), $this->translate->_('Description'), $this->translate->_('Action'));
			$displayTable = new Venz_App_Display_Table(
				array (
					 'data' => $dataPriviledges,
					 'headings' => $arrHeader,
					 'format' 		=> array('{format_counter}','%0%','%1%','%2%', '{format_action}'),					 
					 'sort_column' 	=> array('','ID','Name', 'Description', ''),
					 'alllen' 		=> $arrPriviledges[0],
					 'title'		=> $this->translate->_('Priviledges'),					 
					 'aligndata' 	=> 'LLLLL',
					 'pagelen' 		=> $recordsPerPage,
					 'numcols' 		=> sizeof($arrHeader),
					 'tablewidth' => "700px",
					 'sortby' => $sortby,
					 'ascdesc' => $ascdesc,
					 'hiddenparam' => $strHiddenSearch,
				)
			);
			$this->view->content_priviledges = $displayTable->render();
		}catch (Exception $e) {
	
			echo $e->getMessage();
		}		
	}

	
	public function resourcesAction()   
	{
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$sysAcl = new Venz_App_System_Acl();
		$libDb = new Venz_App_Db_Table();
		/////////////////////////// DEALING WITH PAGINGS AND SORTING ///////////////////////////
		
		$sortby = $Request->getParam('sortby');			
		if (strlen($sortby) == 0) $sortby = 'ID';
			
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
		$add_resources = $Request->getParam('add_resources');	
		if ($add_resources)
		{
			$Name = $Request->getParam('Name');	
			$Description = $Request->getParam('Description');	
			$Category = $Request->getParam('Category');	
			$ParentName = $Request->getParam('ParentName');	
			$arrInsert = array("Name"=>$Name,"Description"=>$Description,"Category"=>$Category,"ParentName"=>$ParentName);
			$db->insert("ACLResources", $arrInsert);
			$this->_redirect('/admin/acl/resources/');   				
		}
		
		$save_resources = $Request->getParam('save_resources');	
		if ($save_resources)
		{
			$Name = $Request->getParam('Name');	
			$Description = $Request->getParam('Description');	
			$Category = $Request->getParam('Category');	
			$ParentName = $Request->getParam('ParentName');					
			$ID = $Request->getParam('save_resources_id');
			$arrResourcesDetail = $sysAcl->getResourcesDetail($ID);
			if (!$arrResourcesDetail)
			{
				$this->_redirect('/admin/acl/resources/'); exit(); 
			}
			
			$arrUpdate = array("Name"=>$Name,"Description"=>$Description, "Category"=>$Category, "ParentName"=>$ParentName);
			$db->update("ACLResources", $arrUpdate, "ID=".$ID);
			$this->_redirect('/admin/acl/resources/');   				
		}


		$remove_resources = $Request->getParam('remove_resources');	
		if ($remove_resources)
		{
			$arrResourcesDetail = $sysAcl->getResourcesDetail($remove_resources);
			if (!$arrResourcesDetail)
			{
				$this->_redirect('/admin/acl/resources/'); exit(); 
			}
		
			$db->delete("ACLResources", "ID=".$remove_resources);
			$this->_redirect('/admin/acl/resources/');   				
		}			
		
		$this->view->edit_resources = '';
		$edit_resources = $Request->getParam('edit_resources');	
		if ($edit_resources)
		{
			$this->view->edit_resources = $edit_resources;
			$arrResourcesDetail = $sysAcl->getResourcesDetail($edit_resources);
			if (!$arrResourcesDetail)
			{
				$this->_redirect('/admin/acl/resources/'); exit(); 
			}
			$this->view->Name = $arrResourcesDetail['Name'];			
			$this->view->Description = $arrResourcesDetail['Description'];			
			$this->view->Category = $arrResourcesDetail['Category'];			
			$this->view->ParentName = $arrResourcesDetail['ParentName'];			
		}			
		
		$sqlSearch = "";
		$search_resources = $Request->getParam('search_resources');	
		$strHiddenSearch = "";
		if ($search_resources)
		{
			$Name = $Request->getParam('Name');	
			$sqlSearch .= $Name ? " and Name LIKE '%".$Name."%'" : "";
			
			$Description = $Request->getParam('Description');	
			$sqlSearch .= $Description ? " and Description LIKE '%".$Description."%'" : "";
			
			$Category = $Request->getParam('Category');	
			$sqlSearch .= $Category ? " and Category LIKE '%".$Category."%'" : "";
			
			$ParentName = $Request->getParam('ParentName');	
			$sqlSearch .= $ParentName ? " and ParentName LIKE '%".$ParentName."%'" : "";
			
			$this->view->Name = $Name ? $Name : "";				
			$this->view->Description = $Description ? $Description : "";				
			$this->view->Category = $Category ? $Category : "";				
			$this->view->ParentName = $ParentName ? $ParentName : "";				
			$strHiddenSearch = "<input type=hidden name='search_resources' value='true'>";
			$strHiddenSearch .= "<input type=hidden name='Name' value='".$Name."'>";
			$strHiddenSearch .= "<input type=hidden name='Description' value='".$Description."'>";
			$strHiddenSearch .= "<input type=hidden name='Category' value='".$Category."'>";
			$strHiddenSearch .= "<input type=hidden name='ParentName' value='".$ParentName."'>";
				
		
		
		}
		


		$sysAcl->setFetchMode(Zend_Db::FETCH_NUM);
		$arrResources = $sysAcl->getResources($sortby, $ascdesc, $recordsPerPage, $showPage, $sqlSearch);
		$dataResources = $arrResources[1];
		
		function format_action($colnum, $rowdata)
		{
			
			$db = Zend_Db_Table::getDefaultAdapter(); 
			$arrMapExist = $db->fetchRow("SELECT * FROM ACLMap where Resources ='".$rowdata[1]."'");
			$systemSetting = new Zend_Session_Namespace('systemSetting');	
			if ($arrMapExist)
				return " ** ";
			else
				return "<a href='/admin/acl/resources/edit_resources/".$rowdata[0]."'><img border=0 src='/images/icons/IconEdit.gif'></a> | <a href='javascript:void(0);' onclick='OnDeleteResources(".$rowdata[0].")'><img border=0 src='/images/icons/IconDelete.gif'></a>";
		}
		$sessionResources = new Zend_Session_Namespace('sessionResources');
		$sessionResources->numCounter = $recordsPerPage * ($showPage-1);
		function format_counter($colnum, $rowdata)
		{
			$sessionResources = new Zend_Session_Namespace('sessionResources');
			$sessionResources->numCounter++;
			return $sessionResources->numCounter;
		}
		
		$arrHeader = array ('', 'ID', $this->translate->_('Name'), $this->translate->_('Description'), $this->translate->_('Category'), $this->translate->_('Parent Name'), '');
		$displayTable = new Venz_App_Display_Table(
			array (
				 'data' => $dataResources,
				 'headings' => $arrHeader,
				 'format' 		=> array('{format_counter}','%0%','%1%','%2%','%3%','%4%', '{format_action}'),					 
				 'sort_column' 	=> array('','ID','Name', 'Description', 'Category', 'ParentName', ''),
				 'alllen' 		=> $arrResources[0],
				 'title'		=> $this->translate->_('Resources'),					 
				 'aligndata' 	=> 'LLLL',
				 'pagelen' 		=> $recordsPerPage,
				 'numcols' 		=> sizeof($arrHeader),
				 'tablewidth' => "700px",
				 'sortby' => $sortby,
				 'ascdesc' => $ascdesc,
				 'hiddenparam' => $strHiddenSearch,
			)
		);
		$this->view->content_resources = $displayTable->render();
				
	}


}

