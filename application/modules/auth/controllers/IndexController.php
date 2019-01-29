<?php

class Auth_IndexController extends Venz_Zend_Controller_Action
{

	private $_userInfo;
    public function init()
    {
        parent::init(NULL, NULL, false);
		if(Zend_Auth::getInstance()->hasIdentity())   
		{   
			$this->_userInfo = Zend_Auth::getInstance()->getStorage()->read(); 
		}
		$this->view->userInfo = $this->_userInfo;
		
    }
	
		
	public function forgotpasswordrequestAction()
    {
		if ($this->userInfo) {
			$this->_redirect('/default');
		}
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$appMessage = new Venz_App_Msg();
		
		
		$captcha=new Zend_Captcha_Image();
		$captcha->setWordLen('6')
		->setHeight('60')
		->setFont('font/arial.ttf')
		->setImgDir('captcha')
		->setDotNoiseLevel('2') 
		->setLineNoiseLevel('2');
	
		$SubmitEmail = $Request->getParam('SubmitEmail');
		if ($SubmitEmail)
		{		
		
			$CaptchaStr = $Request->getParam('Captcha');	
			$CaptchaID = $Request->getParam('CaptchaID');	
			$Email = $Request->getParam('Email');	
			$captchaSession = new Zend_Session_Namespace('Zend_Form_Captcha_' . $CaptchaID);
			$captchaIterator = $captchaSession->getIterator();
			$captchaWord = $captchaIterator['word'];
			if ($CaptchaStr != $captchaWord)
			{		
				$this->appMessage->setMsg(0, $this->translate->_('The verification string you entered is not correct.'));
				$this->_redirect('/auth/index/forgotpasswordrequest');
				exit();
			}
			$arrData = $db->fetchRow("SELECT * FROM ACLUsers where Email='".$Email."'");
			if (!$arrData)
			{
				$this->appMessage->setMsg(0, $this->translate->_('The email that you had entered is not registered in the system.'));
				$this->_redirect('/auth/index/forgotpasswordrequest');
				exit();

			}else
			{
			
				$rowAccount = $db->fetchRow("SELECT * FROM ACLUsers WHERE Email='".$Email."'");
				if (!$rowAccount)
				{
					$this->appMessage->setNotice(0, "The account does not exist.");
					$this->_redirect('/auth/index/forgotpasswordrequest'); 
					exit();
				}
				
			
				
				$requestString = md5($Email.$Username.$Password.time());
				$requestLink = "http://".$_SERVER['HTTP_HOST']."/auth/index/resetpassword/k/".$requestString;
			
				$arrInsert = array("ACLUserID"=>$rowAccount['ID'], "RequestBy"=>$rowAccount['ID'], "Email"=>$Email,"RequestDate"=>new Zend_Db_Expr("now()"),  "RequestString"=>$requestString); 
				$db->Insert("PasswordRequest", $arrInsert);
			
				
				$headers = "From: AFC CLAS Administrator <noreply@clas.afc-link.com>\r\n";
				$headers .= "Reply-To: noreply@clas.afc-link.com\r\n";
			//	$headers .= "CC: susan@example.com\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				
				$Name = $rowAccount['Name'];
				$Username = $rowAccount['Username'];
				
				$emailContent = "Dear ".$Name." <BR><BR>
				We received a request to reset the password associated with this e-mail address. Please follow the instructions below to proceed.<BR><BR>
				Click the link below to reset your password. You need to enter your username to reset your password:<BR><BR>
				Username: ".$Username."<BR>
				<a href='".$requestLink."'>".$requestLink."</a><BR><BR>
				If you did not request to have your password reset you can safely ignore this email.<BR><BR>
				Thank you.
				";
									
				
				mail($Email, "AFC CLAS - Reset Password",$emailContent, $headers);
				
			//	print $emailContent; exit();
				
				$this->appMessage->setNotice(1, "Password reset request has been sent to <B>".$Email."</B>.<BR>If you do not receive the email within a few minutes, please check your Spam/Junk folder");
				$this->_redirect('/');  
			
			}
		}
		$this->view->captchaId = $captcha->generate();

	}
	
	public function resetpasswordAction()
    {
		if ($this->userInfo) {
			$this->_redirect('/default');
		}
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$appMessage = new Venz_App_Msg();
		$key = $Request->getParam('k');
		$this->view->codeok = false;
		if ($key)
		{
		
			$rowReset = $db->fetchRow("SELECT * FROM PasswordRequest WHERE RequestString='".$key."'");
			if ($rowReset)
			{
			
			//	if ($rowReset['Accessed'])
			//	{
			//		$appMessage->setNotice(0, "Your password reset code is no longer valid. Please request for password reset again.");
			//		$this->_redirect("/");
			//		
			//	}
			
				$this->view->codeok = true;
				$this->view->key = $key;
				$rowAccount = $db->fetchRow("SELECT * FROM ACLUsers WHERE ID='".$rowReset['ACLUserID']."'");
			//	$db->query("UPDATE PasswordRequest SET Accessed=1, AccessedDate=now() WHERE ACLUserID=".$rowAccount['ID']);
				
				$SubmitConfirm = $Request->getParam('SubmitConfirm');
				if ($SubmitConfirm)	
				{
					$userID = $Request->getParam('userID');
					$userPassword = $Request->getParam('userPassword');
					$userPassword2 = $Request->getParam('userPassword2');
					
					if ($userID == $rowAccount['Username'])
					{
						if ($userPassword == $userPassword2)
						{
							$db->query("UPDATE ACLUsers SET Password=MD5('".$userPassword."'), EmailValidated=1 WHERE ID=".$rowAccount['ID']);
							$db->query("UPDATE PasswordRequest SET UpdatedDate=now() WHERE id=".$rowReset['ID']);
							$appMessage->setNotice(1, "Your password has been successfully updated. Please login using the password you entered.");
							$this->_redirect("/");

						}else
						{
							$appMessage->setNotice(0, "The passwords you entered do not match.");
						}

					}else
					{
						$appMessage->setNotice(0, "The username you entered is not correct.");
					}
				}					
				
		//		print_r($rowAccount);
		//		$db->query("UPDATE SYSEntity SET Active=1 WHERE ID=".$rowEntity['ID']);
		//		$appMessage->setNotice(1, "Your account has been activated. You can now login to AFC Photo Gallery with your username and password.");
			}
			else{
				$appMessage->setNotice(0, "The password request code is invalid.");
			
			}

		}
	//	$this->_redirect("/");
	//	exit();
	}
	

	public function verificationAction()
    {
		if ($this->userInfo) {
			$this->_redirect('/default');
		}
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$appMessage = new Venz_App_Msg();
		$key = $Request->getParam('k');
		$this->view->codeok = false;
		if ($key)
		{
		
			$rowAccount = $db->fetchRow("SELECT * FROM ACLUsers WHERE EmailValidatedString='".$key."'");
			if ($rowAccount)
			{
			
				if ($rowAccount['EmailValidated'])
					$appMessage->setNotice(0, "The account has been verified previously. Please proceed to the <a href='http://".$_SERVER['HTTP_HOST']."/auth'>login</a> page to login to the system.");
				else
				{
					$this->view->codeok = true;
					$this->view->key = $key;
					
					$SubmitConfirm = $Request->getParam('SubmitConfirm');
					if ($SubmitConfirm)	
					{
						$userID = $Request->getParam('userID');
						$userPassword = $Request->getParam('userPassword');
						$userPassword2 = $Request->getParam('userPassword2');
						if ($userID == $rowAccount['Username'])
						{
							if ($userPassword == $userPassword2)
							{
								$db->query("UPDATE ACLUsers SET EmailValidated=1, EmailValidatedDate=now(), Password=MD5('".$userPassword."') WHERE ID=".$rowAccount['ID']);
								$appMessage->setNotice(1, "Your account has been successfully verified. Please login using the username and the password you entered.");
								$this->_redirect("/");

							}else
							{
								$appMessage->setNotice(0, "The passwords you entered do not match.");
							}

						}else
						{
							$appMessage->setNotice(0, "The username you entered is not correct.");
						}
					}					
				}
		//		print_r($rowAccount);
		//		$db->query("UPDATE SYSEntity SET Active=1 WHERE ID=".$rowEntity['ID']);
		//		$appMessage->setNotice(1, "Your account has been activated. You can now login to AFC Photo Gallery with your username and password.");
			}
			else{
				$appMessage->setNotice(0, "The activating code is invalid. Please request the administrator to resend the activation code.");
			
			}

		}
	//	$this->_redirect("/");
	//	exit();
	}
	
    public function resendactivationAction()
    {
		if ($this->userInfo) {
			$this->_redirect('/default');
		}

		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$appMessage = new Venz_App_Msg();
//		print_r($this->view->arrEntity);
		
		if ($this->view->arrEntity){
			//$arrEntity = $db->fetchRow("SELECT * FROM SYSEntity where Subdomain='".$this->view->arrEntity['Subdomain']."'");
			if ($this->view->arrEntity['ACLUserID'])
			{
			
			
				$arrUser = $db->fetchRow("SELECT * FROM ACLUsers where ID='".$this->view->arrEntity['ACLUserID']."'");
				$ActiveString = base64_encode($arrUser['Password'].time());
				$db->query("UPDATE SYSEntity SET ActiveString='".$ActiveString."' WHERE ID=".$this->view->arrEntity['ID']);
				$Username = $arrUser['Username'];
				$Email = $arrUser['Email'];
				$Subdomain = $this->view->arrEntity['Subdomain'];
				//$ActiveString = $this->view->arrEntity['ActiveString'];
				$trialEnd = date("m/d/Y H:i:s", strtotime($this->view->arrEntity['DateExpire']));
$msgUser=<<<END
Dear $Name\n\n
Welcome to InZentory. Below are your account details\n\n
Username: $Username\n
Access Domain: http://$Subdomain.inzentory-sys.com\n\n
Please click on the activation link below to activate your account.\n
http://$Subdomain.inzentory-sys.com/auth/index/activate/k/$ActiveString\n\n
You can create unlimited users with your access. All the users you created can access InZentory with the same Access Domain.\n
There are self help slide out notes on each of the sections in InZentory. Do feel free to refer to the slide out notes for assistant and tips.\n\n
Your 14 days free trials will end on $trialEnd\n\n

From\n
InZentory team\n

END;
	mail($Email, "Welcome to InZentory", $msgUser, "from:sales@venzon-solution.com, reply-to:sales@venzon-solution.com");
				$appMessage->setNotice(1, "An activation email has been sent to ".$Email.".<BR>Please click on the link in the email to activate your InZentory account.");
				$this->_redirect("/");
			}
		
		}
		
		
		exit();
    }
	


    public function indexAction()
    {
		if ($this->userInfo) {
			$this->_redirect('/default');
		}
		$Request = $this->getRequest();			
		$db = Zend_Db_Table::getDefaultAdapter(); 

    }
	
	public function authloginsysAction()   
	{
	
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$front = Zend_Controller_Front::getInstance();
		$front->throwExceptions(true);
		try {
			
			$Request = $this->getRequest();
			$Username = $Request->getParam('userID');
			$Password = $Request->getParam('userPassword');
			$appMessage = new Venz_App_Msg();
			$sessionUsers = new Zend_Session_Namespace('sessionUsers');	
			
			$rowAccount = $db->fetchRow("SELECT * FROM ACLUsers WHERE Username='".$Username."' AND Active=1");
			if (!$rowAccount)
			{
				$appMessage->setMsg(0, "The username and password entered do not match or<BR>your account has been deactivated.");
				$this->_redirect('/auth');
				exit();
			}
			
		   if($Username && $Password) {

				$refURL = $Request->getParam('referer_url');	
				// Connect to the database and receive the adapter
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$auth = new Venz_App_Auth_Authentication($db);	

		
				if (true === $message = $auth->loginsys($Username, $Password)) {
					$appMessage->setNotice(1, "Login Successful.");
					$userInfo = Zend_Auth::getInstance()->getStorage()->read(); 
					$sessionUsers = new Zend_Session_Namespace('sessionUsers');	
					$sessionUsers->Acl = new Venz_Zend_Acl($userInfo->ACLRole);
				} else {
					$appMessage->setMsg(0, "The username and password entered do not match or<BR>your account has been deactivated.");
					$this->_redirect('/auth');
				}

			}
			else{
				$appMessage->setMsg(0, "Username and password must be entered.");
				$this->_redirect('/auth');

			}
			
			$userInfo = Zend_Auth::getInstance()->getStorage()->read();	
			if ($userInfo->ACLRole == "accounts") 
				$this->_redirect('/admin/system/invoices');
			else if ($userInfo->ACLRole == "transporter" || $userInfo->ACLRole == "loading_warehouse" ) 
				$this->_redirect('/#tabs2');
			else
				$this->_redirect('/#tabs1');
		}catch (Exception $e) {
		
			echo $e->getMessage();
		}

		
		exit();

	}	
	
	public function authloginAction()   
	{
	
		$db = Zend_Db_Table::getDefaultAdapter(); 
		$front = Zend_Controller_Front::getInstance();
		$front->throwExceptions(true);
		try {
			
			$Request = $this->getRequest();
			$Username = $Request->getParam('userID');
			$Password = $Request->getParam('userPassword');
			$appMessage = new Venz_App_Msg();
			$sessionUsers = new Zend_Session_Namespace('sessionUsers');	
			
			$rowAccount = $db->fetchRow("SELECT * FROM ACLUsers WHERE Username='".$Username."' AND Active=1");
			if (!$rowAccount)
			{
				$appMessage->setMsg(0, "The username and password entered do not match or<BR>your account has been deactivated.");
				$this->_redirect('/auth');
				exit();
			}
			
		   if($Username && $Password) {

				$refURL = $Request->getParam('referer_url');	
				// Connect to the database and receive the adapter
				$db = Zend_Db_Table::getDefaultAdapter(); 
				$auth = new Venz_App_Auth_Authentication($db);	

		
				if (true === $message = $auth->login($Username, $Password)) {
					$appMessage->setNotice(1, "Login Successful.");
					$userInfo = Zend_Auth::getInstance()->getStorage()->read(); 
					$sessionUsers = new Zend_Session_Namespace('sessionUsers');	
					$sessionUsers->Acl = new Venz_Zend_Acl($userInfo->ACLRole);
				} else {
					$appMessage->setMsg(0, "The username and password entered do not match or<BR>your account has been deactivated.");
					$this->_redirect('/auth');
				}

			}
			else{
				$appMessage->setMsg(0, "Username and password must be entered.");
				$this->_redirect('/auth');

			}
			
			$userInfo = Zend_Auth::getInstance()->getStorage()->read();	
			if ($userInfo->ACLRole == "accounts") 
				$this->_redirect('/admin/system/invoices');
			else if ($userInfo->ACLRole == "transporter" || $userInfo->ACLRole == "loading_warehouse" ) 
				$this->_redirect('/#tabs2');
			else
				$this->_redirect('/#tabs1');
		}catch (Exception $e) {
		
			echo $e->getMessage();
		}

		
		exit();

	}	


	public function authlogoutAction()   
	{
		Zend_Auth::getInstance()->clearIdentity();
		$systemSetting = new Zend_Session_Namespace('systemSetting');		
		$this->_redirect($systemSetting->arrEnvironments['main_url']);	
		
		
	}	

}

