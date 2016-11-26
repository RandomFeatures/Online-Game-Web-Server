<?php
/*
 Description:    Wrapper for the GamePlay Webservice
 
 ****************History************************************
 Date:         	10.11.2009
 Author:       	Allen Halsted
 Mod:          	Creation
 ***********************************************************
 */
include ($_SERVER['DOCUMENT_ROOT'].'/lib/configs/base.inc.php');
include ($_SERVER['DOCUMENT_ROOT'].'/lib/configs/config.inc.php');
include ($_SERVER['DOCUMENT_ROOT'].'/lib/system/common/system_start_session.php');

class game_play_webservice {
    
	
	private $expiration;
	private $gamename;
	private $gamesource;
	
	function __construct($Exp, $GameName, $GameSource) {
		$this->expiration = mktime(0, 0, 0, date("m"), date("d") + $Exp, date("Y"));
		$this->gamename = $GameName;
		$this->gamesource = $GameSource;
	}
	
	
	public function &Login($Login, $Password) {
		$client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('gamename'=>$this->gamename,'source'=>$this->gamesource,'login'=>$Login,'password'=>$Password,'session'=>session_id(),'expire'=>date('m-d-Y', $this->expiration),'format'=>'MM-dd-yyyy');
        $response = $client->login($param);
        $returnxml = $response->return;
		//var_dump($returnxml);//Debug: throw the raw data onto the screen
		unset($response);
		unset($client);
		
        return $this->SessionLogin($returnxml);
    }
	
	public function &LoginCheck($Login, $Password) {
		$client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('gamename'=>$this->gamename,'source'=>$this->gamesource,'login'=>$Login,'password'=>$Password);
        $response = $client->login($param);
        $returnxml = $response->return;
		//var_dump($returnxml);//Debug: throw the raw data onto the screen
		unset($response);
		unset($client);
        //create teh parser
    	$xmlParser = new gc_xmlparser($returnxml);
    	$objxml = $xmlParser->GetData();
		$rtn = FALSE;
		
		$success = $objxml['Charon-XML']['header']['status']['VALUE'];
		
        if (strcasecmp($success, 'Success') == 0) 
		$rtn = TRUE;
		
        return $rtn;
    }
    
	public function &xRefLogin($xref) {
        $client = new SoapClient(GAME_PLAY_WSDL);
			
        $param = array('gamename'=>$this->gamename,'source'=>$this->gamesource,'xref'=>$xref,'session'=>session_id(),'expire'=>date('d-m-Y', $this->expiration),'format'=>'dd-MM-yyyy');
        $response = $client->xRefLogin($param);
        $returnxml = $response->return;
		
		unset($response);
		unset($client);
        
        return $this->SessionLogin($returnxml);
    }
	
	public function &xRefLoginCheck($xref) {
        $client = new SoapClient(GAME_PLAY_WSDL);
			
        $param = array('gamename'=>$this->gamename,'source'=>$this->gamesource,'xref'=>$xref);
        $response = $client->xRefLoginCheck($param);
        $returnxml = $response->return;
		
		unset($response);
		unset($client);
        
		//create teh parser
    	$xmlParser = new gc_xmlparser($returnxml);
    	$objxml = $xmlParser->GetData();
		$rtn = FALSE;
		
		$success = $objxml['Charon-XML']['header']['status']['VALUE'];
		
        if (strcasecmp($success, 'Success') == 0) 
		$rtn = TRUE;
		
        return $rtn;
    }
    
	public function &Register($username, $password, $firstname, $lastname, $email) {
        $client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('gamename'=>$this->gamename,'source'=>$this->gamesource,'firstname'=>$firstname,'lastname'=>$lastname,'login'=>$username,'password'=>$password,'email'=>$email,'session'=>session_id(),'expire'=>date('d-m-Y', $this->expiration),'format'=>'dd-MM-yyyy');
        $response = $client->register($param);
        $returnxml = $response->return;

        unset($response);
		unset($client);
		
        return $this->SessionLogin($returnxml);;
	}
	
	public function &xRefRegister($username, $password, $firstname, $lastname, $email, $xref) {
        $client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('gamename'=>$this->gamename,'source'=>$this->gamesource,'firstname'=>$firstname,'lastname'=>$lastname,'login'=>$username,'password'=>$password,'email'=>$email,'xref'=>$xref,'session'=>session_id(),'expire'=>date('d-m-Y', $this->expiration),'format'=>'dd-MM-yyyy');
        $response = $client->xRefRegister($param);
        $returnxml = $response->return;

        unset($response);
		unset($client);
		
        return $this->SessionLogin($returnxml);;
	}
	
    public function &doLogin(&$xml) {
    
        $client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('loginxml'=>$xml);
        $response = $client->userXmlLogin($param);
        $returnxml = $response->return;
        
		unset($response);
		unset($client);
        
      	return $this->SessionLogin($returnxml);;
    }
    
    public function &doRegister(&$xml) {

        $client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('registerxml'=>$xml);
        $response = $client->userXmlRegister($param);
        $returnxml = $response->return;
        
		unset($response);
		unset($client);
        
      	return $this->SessionLogin($returnxml);;
    }
    
    private function SessionLogin($xml)
    {
    	$result = new sys_recordset;
    	$rtn = FALSE;
    	//var_dump($xml);
		if ($result->ParseXML($xml))
		{

			$row = $result->getRow();
			if ($row)
			{
				//Track Session Data
				$_SESSION['pythia_userid'] = $row['UserID'];
				$_SESSION['pythia_gameid'] = $row['GameID'];
				$_SESSION['pythia_sourceid'] = $row['SourceID'];
				$_SESSION['pythia_xml'] = $row['SessionXML'];
				$rtn = TRUE;
			} 
        }
        unset($result);
		unset($xml);
		
        return $rtn;
    }
	
}
?>