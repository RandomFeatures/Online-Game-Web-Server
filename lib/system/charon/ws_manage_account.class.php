<?php
 /*
Description:    PHP Class wrapper for the Manage Account
				Web Service
                
****************History************************************
Date:         	10.18.2009
Author:       	Allen Halsted
Mod:          	Creation
***********************************************************
*/  

include ($_SERVER['DOCUMENT_ROOT'].'/lib/configs/base.inc.php');
include ($_SERVER['DOCUMENT_ROOT'].'/lib/configs/config.inc.php');
include ($_SERVER['DOCUMENT_ROOT'].'/lib/system/charon/system_start_session.php');


class manage_account_webservice {
    private $userID = '';

	
    function __construct() {
        $this->userID = (isset($_SESSION['pythia_userid'])) ? $_SESSION['pythia_userid'] : '';
    }

	public function &AccountLogin($Login, $Password) {
		
        $rtn = FALSE;
        //construct the webservice reference
        $client = new SoapClient(MANAGE_USER_WSDL);
        $param = array('login'=>$Login,'password'=>$Password);
        $response = $client->findUserDetailsFromLogin($param);
        $returnxml = $response->return;
        //var_dump($returnxml);//Debug: throw the raw data onto the screen
		$result = new sys_recordset;
		if ($result->ParseXML($returnxml))
		{
			$row =$result->getRow();
			if ($row)
			{
				$_SESSION['pythia_userid'] =  $row['UserID'];
				$this->userID = $row['UserID'];
	        	$rtn = TRUE;
			} 
        }
		unset($returnxml);
		unset($result);
		unset($response);
		unset($client);
        
		return $rtn;
    }

	public function &GetUserDetails()
	{
		$rtn = null;
		$result = null; 
		if (isset($_SESSION['pythia_userid']))
		{
		    //construct the webservice reference
		    $client = new SoapClient(MANAGE_USER_WSDL);
		    $param = array('userid'=>$_SESSION['pythia_userid']);
		    $response = $client->findUserByUserID($param);
			$returnxml = $response->return;
			//var_dump($returnxml);//Debug: throw the raw data onto the screen
			$result = new sys_recordset;
			if ($result->ParseXML($returnxml))
			{
				$rtn = $result;
			}
			unset($returnxml);
			unset($response);
			unset($client);	
		}
		return $rtn;
	}

	public function &GetUserPurchases($gameID)
	{
		$rtn = null;
		$result = null;
		if (isset($_SESSION['pythia_userid']))
		{

		    //construct the webservice reference
		    $client = new SoapClient(MANAGE_USER_WSDL);
		    $param = array('gameid'=>$gameID,'userid'=>$_SESSION['pythia_userid']);
		    $response = $client->findUserProductHistory($param);
			$returnxml = $response->return;
		
			$result = new sys_recordset;
			if ($result->ParseXML($returnxml))
			{
				$rtn = $result;
			}
			unset($returnxml);
			unset($response);
			unset($client);	
		}
		
		return $rtn;
	}

	public function &GetUserGames()
	{
		$rtn = null;
		$result = null;
		if (isset($_SESSION['pythia_userid']))
		{

		    //construct the webservice reference
		    $client = new SoapClient(MANAGE_USER_WSDL);
		    $param = array('userid'=>$_SESSION['pythia_userid']);
		    $response = $client->findUserGameList($param);
			$returnxml = $response->return;
			//var_dump($returnxml); //Debug: throw the raw data onto the screen
			$result = new sys_recordset;
			if ($result->ParseXML($returnxml))
			{
				$rtn = $result;
			}
			unset($returnxml);
			unset($response);
			unset($client);	
		}
		
		return $rtn;
	}
	
	public function &GetHistoryList($GameID)
	{
		$rtn = null;
		$result = null;
		if (isset($_SESSION['pythia_userid']))
		{

		    //construct the webservice reference
		    $client = new SoapClient(MANAGE_USER_WSDL);
		    $param = array('gameid'=>$GameID,'userid'=>$_SESSION['pythia_userid']);
		    $response = $client->findPurchaseByGameIDUserID($param);
			$returnxml = $response->return;
			//var_dump($returnxml); //Debug: throw the raw data onto the screen
			$result = new sys_recordset;
			if ($result->ParseXML($returnxml))
			{
				$rtn = $result;
			}	
			unset($returnxml);
			unset($response);
			unset($client);
		}
		
		return $rtn;	
	}
	
	
	public function &AccountUpdate($login, $firstname, $lastname, $email, $status) {
        $rtn = FALSE;
		if (isset($_SESSION['pythia_userid']))
		{
	       //construct the webservice reference
	        $client = new SoapClient(MANAGE_USER_WSDL);
			$param = array('userid'=>$_SESSION['pythia_userid'],'firstname'=>$firstname,'lastname'=>$lastname,'login'=>$login,'password'=>'','email'=>$email,'status'=>$status);
	        $client->updateUser($param);
			$rtn = TRUE;
			unset($client);
		}
        return $rtn;
    }
	     
	public function AccountUpdatePassword($oldpassword, $newpassword) {
        $rtn = FALSE;
		if (isset($_SESSION['pythia_userid']))
		{
	        //construct the webservice reference
	        $client = new SoapClient(MANAGE_USER_WSDL);
			$param = array('userid'=>$_SESSION['pythia_userid'],'password'=>$oldpassword,'newpassword'=>$newpassword);
	        $response = $client->updatePassword($param);
			if ($response->return == 1)
				$rtn = TRUE;
			unset($response);
			unset($client);				
				
		}
        return $rtn;
    }


	public function AccountRegister($username, $password, $firstname, $lastname, $email) {
        $rtn = FALSE;
        
        //construct the webservice reference
        $client = new SoapClient(MANAGE_USER_WSDL);
		$param = array('firstname'=>$firstname,'lastname'=>$lastname,'login'=>$username,'password'=>$password,'email'=>$email,'status'=>ACTIVE);
        $response = $client->insertNewUser($param);
        $userID = $response->return;
		unset($response);
		unset($client);

		if ($userID > 0)
		{ 
			$_SESSION['pythia_userid'] =  $userID;
			$this->UserID =  $userID;
			$rtn = TRUE;
		}
        return $rtn;
    }

	public function &PurchaseProduct($gameid, $productid)
	{
		$today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		$expire = mktime(0, 0, 0, date("m"), date("d"), date("Y")+10);
		$PurchaseID = 0;
		if (isset($_SESSION['pythia_userid']))
		{

	        //construct the webservice reference
	        $client = new SoapClient(MANAGE_USER_WSDL);
			$param = array('userid'=>$_SESSION['pythia_userid'], 'gameid'=>$gameid, 'productid'=>$productid, 'purchasecode'=>0, 'purchasedate'=>$today, 'expirationdate'=>$expire, 'productstatus'=>1);
	        $response = $client->insertNewUserPurchase($param);
	        $PurchaseID = $response->return;
			unset($response);
			unset($client);
		}
        return $PurchaseID;
	}

	public function &JoinGame($gameid)
	{
		$JoinID = 0;
		$today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		if (isset($_SESSION['pythia_userid']))
		{

	        //construct the webservice reference
	        $client = new SoapClient(MANAGE_USER_WSDL);
			$param = array('userid'=>$_SESSION['pythia_userid'], 'gameid'=>$gameid, 'accountstatus'=>STATUS_ACTIVE, 'source'=>SOURCE_WEB, 'startdate'=>$today);
	        $response = $client->insertUserGames($param);
	        $JoinID = $response->return;
			unset($client);
		}	
		return $JoinID;
	}
	
	
	public function &EmailAccountDetails($email)
	{
	    //construct the webservice reference
	    $client = new SoapClient(MANAGE_USER_WSDL);
		$param = array('email'=>$email);
		$client->EmailAccountDetails($param);

		unset($client);
	}


//TODO Cancel game subscrption
	
}



?>