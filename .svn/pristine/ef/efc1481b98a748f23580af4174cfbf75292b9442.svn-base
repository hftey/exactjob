<?php

class Venz_Zend_Controller_Action extends Zend_Controller_Action
{
	private $_resourceName = 'public';
	private $_roleName = 'guest';
	private $_priviledgeName = 'view'; // general priviledge to view the page
	private $_pageAllow = false;
	public $userInfo = NULL;
	public $appMessage = NULL;
	public $Acl = NULL;
	
	
	function postDispatch()
	{

		
		$appMsg = new Venz_App_Msg();
		if ($appMsg->gotMsg() || $this->appMessage->gotMsg())
		{
			
			$strMessage = $appMsg->getMsg();
			$this->view->appMsg = $strMessage;
		}

		if ($appMsg->gotNotice() || $this->appMessage->gotNotice())
		{
			
			$strMessage = $appMsg->getNotice();
			$this->view->appNotice = $strMessage;
		}		
		
		
	}	
	
	private function _checkPath()
	{
	
		
	}
	
	
	function init($resourceName = NULL, $recordHistory = true)
    {

		try {
		
			$db = Zend_Db_Table::getDefaultAdapter();
			$dispFormat = new Venz_App_Display_Format();
		
			
			$mobileDetect = new Venz_App_MobileDetect();
			$this->view->isMobile = $this->isMobile = $mobileDetect->isMobile();	
			$this->view->isTablet = $this->isTablet = $mobileDetect->isTablet();
				

			$this->view->moduleName = $this->getRequest()->getModuleName();
			$this->view->controllerName = $this->getRequest()->getControllerName();
			$this->view->actionName = $this->getRequest()->getActionName();
			
		//	if ($this->view->isMobile || $this->view->isTablet)
		//	{
		//		if (!($this->view->moduleName == "default" && $this->view->controllerName == "index" && $this->view->actionName == "nomobile"))
		//		{
		//			$this->_redirect("/default/index/nomobile");
		//			exit();
		//		}
		//	}

			$config_env = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application_env.ini'); 
			Zend_Registry::set('config_env', $config_env); 

			$this->appMessage = new Venz_App_Msg();	
			
			$this->_resourceName = $resourceName;
			if (empty($this->_resourceName)){
				// treat every page without resource name as public page
				$this->_resourceName = 'public';
			}
			$sessionUsers = new Zend_Session_Namespace('sessionUsers');	
			$sessionUsers->arrEntity = $arrEntity;
			//print_r($sessionUsers->arrEntity); //exit();
			if(Zend_Auth::getInstance()->hasIdentity())   
			{   
				
				$this->view->login = true;
				$userInfo = Zend_Auth::getInstance()->getStorage()->read();
				
				$this->view->userInfo = $userInfo;
				$this->userInfo = $userInfo;
				$sessionUsers->userInfo = $userInfo;
				$this->_roleName = $userInfo->ACLRole;	
				if ($sessionUsers->Acl){
					$Acl = $sessionUsers->Acl;
				}else{
				
					$Acl = new Venz_Zend_Acl($this->_roleName);
				
				}
			}else{
					$this->_roleName = "User";
					$Acl = new Venz_Zend_Acl($this->_roleName);
				//	if (!($this->view->isMobile || $this->view->isTablet))
				//	{	
						if (!($this->view->moduleName == "auth" && $this->view->controllerName == "index"))
						{
							$this->_redirect("/auth");
						}
				//	}
				
			}
		
				
				
			$this->Acl = $Acl;
			
			$this->_pageAllow = $Acl->isAllowed($this->_roleName, $this->_resourceName, $this->_priviledgeName);
			if (!$this->_pageAllow)
			{
				$this->appMessage->setMsg(0, "You have no access to view the page. The system has directed you to the main page");
				$this->_redirect("/");
			}
			

			
			$systemSetting = new Zend_Session_Namespace('systemSetting');	
			$systemSetting->language = 'en';
			$systemSetting->userInfo = $userInfo;
			$systemSetting->currentPage = $_SERVER['REQUEST_URI'];
			$sessionUsers->Acl = $Acl;
			
			$config_env = Zend_Registry::get('config_env'); 
			$layout = $this->_helper->layout();
			if ($systemSetting->template)
				$layout->setLayout($systemSetting->template);
			else
				$layout->setLayout("default");

			$systemSetting->arrEnvironments = $config_env->environments->toArray();
			$systemSetting->arrLanguages = $config_env->language->toArray();
			$systemSetting->arrEnv = $config_env->environments->toArray();
			$systemSetting->arrCurrency = $config_env->currency->toArray();
			$systemSetting->arrTerms = $config_env->terms->toArray();
			$systemSetting->arrJobType = $config_env->jobtype->toArray();
			
			$arrObjectivesAll = $db->fetchAll("SELECT * FROM ReportObjectives order by ObjectiveYear DESC");
			foreach ($arrObjectivesAll as $arrObjectives)
			{
				if ($arrObjectives['ObjectiveType'] == "deliveryobjective")
				{
					
					$systemSetting->arrReportDeliveryObjective[$arrObjectives['ObjectiveYear']] = $arrObjectives['ObjectiveValue'];
				}
				if ($arrObjectives['ObjectiveType'] == "purchaseobjective")
				{
					$systemSetting->arrReportPurchaseObjective[$arrObjectives['ObjectiveYear']] = $arrObjectives['ObjectiveValue'];
				}
				if ($arrObjectives['ObjectiveType'] == "purchaseobjectivedays")
				{
					$systemSetting->arrReportPurchaseObjectiveDays[$arrObjectives['ObjectiveYear']] = $arrObjectives['ObjectiveValue'];
				}
				
				if ($arrObjectives['ObjectiveType'] == "purchaseobjective2")
				{
					$systemSetting->arrReportPurchaseObjective2[$arrObjectives['ObjectiveYear']] = $arrObjectives['ObjectiveValue'];
				}
				if ($arrObjectives['ObjectiveType'] == "purchaseobjectivedays2")
				{
					$systemSetting->arrReportPurchaseObjective2Days[$arrObjectives['ObjectiveYear']] = $arrObjectives['ObjectiveValue'];
				}
				
				if ($arrObjectives['ObjectiveType'] == "latedelivery")
				{
					$systemSetting->arrReportLateDelivery[$arrObjectives['ObjectiveYear']] = $arrObjectives['ObjectiveValue'];
				}
				if ($arrObjectives['ObjectiveType'] == "drawingapproval")
				{
					$systemSetting->arrDrawingApproval[$arrObjectives['ObjectiveYear']] = $arrObjectives['ObjectiveValue'];
				}
				if ($arrObjectives['ObjectiveType'] == "drawingapprovalcases")
				{
					$systemSetting->arrDrawingApprovalCases[$arrObjectives['ObjectiveYear']] = $arrObjectives['ObjectiveValue'];
				}
			}
			
			
			
			//print_r($systemSetting->arrReportDeliveryObjective);
			
			/*
			$systemSetting->arrReportDeliveryObjective = $config_env->deliveryobjective->toArray();
			$systemSetting->arrReportPurchaseObjective = $config_env->purchaseobjective->toArray();
			$systemSetting->arrReportPurchaseObjectiveDays = $config_env->purchaseobjectivedays->toArray();
			$systemSetting->arrReportLateDelivery = $config_env->latedelivery->toArray();
			$systemSetting->arrDrawingApproval = $config_env->drawingapproval->toArray();
			$systemSetting->arrDrawingApprovalCases = $config_env->drawingapprovalcases->toArray();
			*/
			//print_r($systemSetting->arrDrawingApprovalCases);

			$langPath = NULL;
			$scriptPath = $this->view->getScriptPaths();
			$languageSet = $systemSetting->language;
			// must exist
			$langSysPath = APPLICATION_PATH."/language/".$systemSetting->language."/system.".$systemSetting->arrLanguages[$systemSetting->language][1];	
			
			$translate = new Zend_Translate($systemSetting->arrLanguages[$systemSetting->language][1], $langSysPath, $systemSetting->language, array('delimiter' => '|'));			
			//print $langSysPath; exit();
			$translate->addTranslation($langSysPath, $systemSetting->language);
			$langPath = $scriptPath[0].$this->getRequest()->getControllerName()."/".$languageSet."/".$this->getRequest()->getActionName().".".
				$systemSetting->arrLanguages[$languageSet][1];				

				
			
		
			
			if (is_file($langPath))
			{
				$translate->addTranslation($langPath, $languageSet);
			}
			$systemSetting->translate = $translate;		
			$this->translate = $translate;
			$this->view->translate = $translate;				
		
			$sessionUsers->helpContent = "";
			$sessionUsers->helpPath = "";
			$helpPath = $scriptPath[0].$this->getRequest()->getControllerName()."/".$this->getRequest()->getActionName()."-help.phtml";				
			if (file_exists($helpPath))
			{
				$sessionUsers->helpContent = file_get_contents($helpPath);
				$sessionUsers->helpPath =  $scriptPath[0].$this->getRequest()->getControllerName()."/".$this->getRequest()->getActionName()."-help.phtml";
			}
			

		

		if ($recordHistory){
			$systemSetting->currentPage = $_SERVER['REQUEST_URI'];
			$systemSetting->moduleName =  $this->getRequest()->getModuleName();
			$systemSetting->controllerName =  $this->getRequest()->getControllerName();
			$systemSetting->actionName =  $this->getRequest()->getActionName();
			
			$config_env = Zend_Registry::get('config_env'); 
			$layout = $this->_helper->layout();
			if ($systemSetting->template)
				$layout->setLayout($systemSetting->template);
			else
				$layout->setLayout("default");		

			$systemSetting->arrTemplates = $config_env->templates->toArray();		
			$strPOST = print_r($this->getRequest()->getParams(), true);
			$strGET = $_SERVER['QUERY_STRING'];
			
			if ($this->getRequest()->getActionName() != 'ajax-new-notification-count')
			{
				$arrData = array("username"=>$userInfo->Username, "role"=>$userInfo->ACLRole, "logtime"=>new Zend_Db_Expr('now()'), "zendmodule"=>$this->getRequest()->getModuleName(),
					"zendcontroller"=>$this->getRequest()->getControllerName(),"zendaction"=>$this->getRequest()->getActionName(),"postdata"=>$strPOST,"IP"=>$_SERVER["REMOTE_ADDR"],
					"getdata"=>$strGET);
				$db = Zend_Db_Table::getDefaultAdapter(); 
							

				$db->insert("SYSLog", $arrData);
				
			}

		}
			
		if ($userInfo->ID)
		{
			$arrNewNotification = $db->fetchRow("SELECT count(*) as TotalNotRead FROM Notification WHERE MessageRead=0 and ACLUserID=".$userInfo->ID);
			$this->view->newNotification = $arrNewNotification['TotalNotRead'];
			
		}	
		
		$mobileDetect = new Venz_App_MobileDetect();
		$this->view->isMobile = $this->isMobile = $mobileDetect->isMobile();	
		$this->view->isTablet = $this->isTablet = $mobileDetect->isTablet();
		

		}catch (Exception $e)
		{
			print $e->getMessage();
		}
		
	}

}
