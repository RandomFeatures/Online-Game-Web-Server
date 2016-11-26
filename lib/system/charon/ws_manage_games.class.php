<?php
 /*
Description:    PHP Class wrapper for the Manage Games
				Web Service
                
****************History************************************
Date:         	11.12.2009
Author:       	Allen Halsted
Mod:          	Creation
***********************************************************
*/    

class manage_games_webservice {
    private $userID = '';

	
    function __construct() {
        $this->userID = (isset($_SESSION['pythia_userid'])) ? $_SESSION['pythia_userid'] : '';
    }

	public function &GetActiveGameList()
	{
		$rtn = null;
		$result = null; 
		if (isset($_SESSION['pythia_userid']))
		{//make sure the user is logged in
		    //construct the webservice reference
			$client = new SoapClient(MANAGE_GAMES_WSDL);
		    $param = array('active'=>1);
		    //call the function for the webservice
		    $response = $client->findActiveGames($param);
			$returnxml = $response->return;
			//var_dump($returnxml); //Debug: throw the raw data onto the screen
			$result = new sys_recordset;
			if ($result->ParseXML($returnxml))
			{
				$rtn = $result;
			}	
		}
		return $rtn;
	}

	public function &GetGameDetails($game)
	{
		$rtn = null;
		$result = null; 
		if (isset($_SESSION['pythia_userid']))
		{//make sure the user is logged in
			
			//construct the webservice reference
		    $client = new SoapClient(MANAGE_GAMES_WSDL);
		    $param = array('game'=>$game);
		     //call the function for the webservice
		    $response = $client->findGameDetails($param);
			$returnxml = $response->return;
			//var_dump($returnxml);//Debug: throw the raw data onto the screen
			$result = new sys_recordset;
			if ($result->ParseXML($returnxml))
			{
				$rtn = $result;
			}	
		}
		return $rtn;
	}

}


?>