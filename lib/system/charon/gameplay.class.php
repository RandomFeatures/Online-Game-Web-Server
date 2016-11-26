<?php
 /*
Description:    wrapper class for dealing with the Gameplay Webservice
                
****************History************************************
Date:         	{DATE}
Author:       	Allen Halsted
Mod:          	Creation
***********************************************************
*/    

include ($_SERVER['DOCUMENT_ROOT'].'/lib/configs/base.inc.php');
include ($_SERVER['DOCUMENT_ROOT'].'/lib/system/charon/system_start_session.php');
include ($_SERVER['DOCUMENT_ROOT'].'/lib/system/charon/ws_gameplay.class.php');

class gameplay {
	
	private $objGame = NULL;
	private $objGamePlayWS = NULL;
	
	function __construct($GameName,$SourceName)
	{
		//create game object
		$this->objGame = new sys_game($GameName,$SourceName);
		//create web service object
		$this->objGamePlayWS = new game_play_webservice();
	}
	
	private function SaveTheSession(&$result)
	{
		$rtn = FALSE;
		while ($row=$result->getRow())
		{
    		if ($row)
			{				
				$this->objGame->SetGameID($row['GameID']);
				$this->objGame->SetSourceID($row['SourceID']);
				$this->objGame->SetUserID($row['UserID']);
		        $rtn = TRUE;
			}
		}
	 return $rtn;	
	}
    
	public function Login($Login, $Password, $expiration) {
		
        $rtn = FALSE;

		$result = $this->objGamePlayWS->Login($this->objGame->GetGame(), $this->objGame->GetSource(), $this->objGame->GetSessionID(), $Login, $Password, $expiration);	
		
		if ($result)	
		{
			$rtn = SaveTheSession($result);
			unset($result);
        }
		
        return $rtn;
    }
	
	public function xRefLogin($xref, $expiration) {
		
        $rtn = FALSE;

		$result = &$this->objGamePlayWS->xRefLogin($this->objGame->GetGame(), $this->objGame->GetSource(), $this->objGame->GetSessionID(), $xref, $expiration);
		
		if ($result)	
		{
			$rtn = SaveTheSession($result);
			unset($result);
		}

        return $rtn;
    }
	
	public function Register($username, $password, $firstname, $lastname, $email, $expiration) {
		
		$rtn = FALSE;
		$result = &$this->objGamePlayWS->Register($this->objGame->GetGame(), $this->objGame->GetSource(), $this->objGame->GetSessionID(), $username, $password, $firstname, $lastname, $email, $expiration);
		
		$rtn = SaveTheSession($result);
		unset($result);
		
        return $rtn;
		
	}
	
	public function xRefRegister($username, $password, $firstname, $lastname, $email, $xref, $expiration) {
		
		$rtn = FALSE;
		$result = &$this->objGamePlayWS->xRefRegister($this->objGame->GetGame(), $this->objGame->GetSource(), $this->objGame->GetSessionID(), $username, $password, $firstname, $lastname, $email, $xref, $expiration);
		$rtn = SaveTheSession($result);
		unset($result);
		
        return $rtn;
		
	}
	
    
    //Login with just the xRef from like facebook
    public function xRefXmlLogin($xref, $expiration) {
        $rtn = FALSE;
        $date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        //build xRef Login  XML
		$XML = '<?xml version="1.0"?>'.
		'<Charon-XML>'.
			'<header gameid="'.$this->objGame->GetGameID().'" gamename="'.$this->objGame->GetGame().'">'.
				'<source id="'.$this->objGame->GetSourceID().'" name="'.$this->objGame->GetSource().'" />'.
				'<date>'.date('d-m-Y', $date).'</date>'.
				'<user id=""/>'.
			'</header>'.
			'<transaction event="login">'.
				'<userdata type="reference">'.
					'<xref>'.$xref.'</xref>'.
					'<session expire="'.date('d-m-Y', $expiration).'" format="dd-mm-yyyy">'.$this->objGame->GetSessionID().'</session>'.
				'</userdata>'.
			'</transaction{DESC}>'.
		'</Charon-XML>';
		
		$result = &$this->objGamePlayWS->doLogin($XML);
		$rtn = SaveTheSession($result);
		unset($result);
        
		return $rtn;
    }
	
    //Login wth the user name and password from the website
    public function userXmlLogin($username, $password, $expiration) {
        $rtn = FALSE;
        $date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        //build Login XML
		$XML = '<?xml version="1.0"?>'.
		'<Charon-XML>'.
			'<header gameid="'.$this->objGame->GetGameID().'" gamename="'.$this->objGame->GetGame().'">'.
				'<source id="'.$this->objGame->GetSourceID().'" name="'.$this->objGame->GetSource().'" />'.
				'<date>'.date('d-m-Y', $date).'</date>'.
				'<user id=""/>'.
			'</header>'.
			'<transaction event="login">'.
				'<userdata type="login">'.
					'<login>'.$username.'</login>'.
					'<password>'.$password.'</password>'.
					'<session expire="'.date('d-m-Y', $expiration).'" format="dd-mm-yyyy">'.$this->objGame->GetSessionID().'</session>'.
				'</userdata>'.
			'</transaction>'.
		'</Charon-XML>';
		
		$result = &$this->objGamePlayWS->doLogin($XML);
		$rtn = SaveTheSession($result);
		unset($result);
		
        return $rtn;
    }
    
    public function userXmlRegister($username, $password, $firstname, $lastname, $email, $xref, $expiration) {
        $rtn = FALSE;
        $date = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        //build Register XML
		$XML = '<?xml version="1.0"?>'.
		'<Charon-XML>'.
			'<header gameid="'.$this->objGame->GetGameID().'" gamename="'.$this->objGame->GetGame().'">'.
				'<source id="'.$this->objGame->GetSourceID().'" name="'.$this->objGame->GetSource().'" />'.
				'<date>'.date('d-m-Y', $date).'</date>'.
			'</header>'.
			'<transaction event="register">'.
				'<userdata type="new">'.
					'<login>'.$username.'</login>'.
					'<password>'.$password.'</password>'.
					'<firstname>'.$firstname.'</firstname>'.
					'<lastname>'.$lastname.'</lastname>'.
					'<email>'.$email.'</email>'.
					'<xref>'.$xref.'</xref>'.
					'<session expire="'.date('d-m-Y', $expiration).'" format="dd-mm-yyyy">'.$this->objGame->GetSessionID().'</session>'.
				'</userdata>'.
			'</transaction>'.
		'</Charon-XML>';

		$result = &$this->objGamePlayWS->doRegister($XML);
		$rtn = SaveTheSession($result);
		unset($result);
		
        return $rtn;
    }
	
	
	
}

?>