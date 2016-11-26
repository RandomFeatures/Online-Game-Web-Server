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



class game_play_webservice {
    
	public function &Login($GameName, $SourceName, $Session, $Login, $Password, $expiration) {
		
        $client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('gamename'=>$GameName,'source'=>$SourceName,'login'=>$Login,'password'=>$Password,'session'=> $Session,'expire'=>date('d-m-Y', $expiration),'format'=>'dd-MM-yyyy');
        $response = $client->login($param);
        $returnxml = $response->return;
		$result = new sys_recordset;
		$result->ParseXML($returnxml);
		
		unset($returnxml);
		unset($response);
		unset($client);
		
        return $result;
    }
	
	public function &xRefLogin($gamename, $sourcename, $session, $xref, $expiration) {
		
        $client = new SoapClient(GAME_PLAY_WSDL);
			
        $param = array('gamename'=>$gamename,'source'=>$sourcename,'xref'=>$xref,'session'=>$session,'expire'=>date('d-m-Y', $expiration),'format'=>'dd-MM-yyyy');
        $response = $client->xRefLogin($param);
        $returnxml = $response->return;
		$result = new sys_recordset;
		$result->ParseXML($returnxml);
		
		unset($returnxml);
		unset($response);
		unset($client);
        
        return $result;
    }
	
	public function &Register($gamename, $sourcename, $session, $username, $password, $firstname, $lastname, $email, $expiration) {
		
        $client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('gamename'=>$gamename,'source'=>$sourcename,'firstname'=>$firstname,'lastname'=>$lastname,'login'=>$Login,'password'=>$Password,'email'=>$email,'session'=>$session,'expire'=>date('d-m-Y', $expiration),'format'=>'dd-MM-yyyy');
        $response = $client->register($param);
        $returnxml = $response->return;
		$result = new sys_recordset;
		$result->ParseXML($returnxml);
		unset($returnxml);
		unset($response);
		unset($client);
		
        return $result;
	}
	
	public function &xRefRegister($gamename, $sourcename, $session, $username, $password, $firstname, $lastname, $email, $xref, $expiration) {
		
        $client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('gamename'=>$gamename,'source'=>$sourcename,'firstname'=>$firstname,'lastname'=>$lastname,'login'=>$Login,'password'=>$Password,'email'=>$email,'xref'=>$xref,'session'=>$session,'expire'=>date('d-m-Y', $expiration),'format'=>'dd-MM-yyyy');
        $response = $client->register($param);
        $returnxml = $response->return;
		$result = new sys_recordset;
		$result->ParseXML($returnxml);
		unset($returnxml);
		unset($response);
		unset($client);
		
        return $result;
	}
	
    public function &doLogin(&$xml) {
        
        $client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('loginxml'=>$xml);
        $response = $client->userXmlLogin($param);
        $returnxml = $response->return;
        
		$result = new sys_recordset;
		$result->ParseXML($returnxml);
		
		unset($returnxml);
		unset($response);
		unset($client);
        
      	return $result;
    }
    
    public function &doRegister(&$xml) {

        $client = new SoapClient(GAME_PLAY_WSDL);
        $param = array('registerxml'=>$xml);
        $response = $client->userXmlRegister($param);
        $returnxml = $response->return;
        
		$result = new sys_recordset;
		$result->ParseXML($returnxml);
		unset($returnxml);
		unset($response);
		unset($client);
        
      	return $result;
    }
    
	
}
?>
