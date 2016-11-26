<?php
 /*
Description:    Class for storing and retrieving the game data
				form the session
                
****************History************************************
Date:         	{DATE}
Author:       	Allen Halsted
Mod:          	Creation
***********************************************************
*/    
include ($_SERVER['DOCUMENT_ROOT'].'/lib/system/charon/system_start_session.php');

class sys_game {
    
    private $gameName = ''; //name of the current game
    private $gameID = 0; //Game ID
    private $sourceID = 0; //Source ID
    private $sourceName = ''; //Name of the current source
    private $userID = 0;
    private $sessionID = '';
	
	function __construct($GameName,$SourceName) {
        $this->date = $GLOBALS['date'];
		$this->gameName = $GameName;
        $this->sourceName = $SourceName;
        $this->gameID = (isset($_SESSION['pythia_gameid'])) ? $_SESSION['pythia_gameid'] : 0;
        $this->sourceID = (isset($_SESSION['pythia_sourceid'])) ? $_SESSION['pythia_sourceid'] : 0;
        $this->userID = (isset($_SESSION['pythia_userid'])) ? $_SESSION['pythia_userid'] : 0;
        $this->sessionID = session_id();
    }
	

	public function GetGame()
	{
		return $this->gameName;
	}
	
	public function GetGameID()
	{
		return $this->gameID;
	}
	
	public function SetGameID($gameid)
	{
		$this->gameID = $gameid;
		$_SESSION['pythia_gameid'] = $gameid;
	}
	
	public function GetSource()
	{
		return $this->sourceName;
	}
	
	public function GetSourceID()
	{
		return  $this->sourceID;	
	}
	
	public function SetSourceID($sourceid)
	{
		 $this->sourceID = $sourceid;
		 $_SESSION['pythia_sourceid'] = $sourceid;
	}
	
	public function GetUserID()
	{
		return  $this->userID;	
	}
	
	public function SetUserID($userid)
	{
		 $this->userID = $userid;
		 $_SESSION['pythia_userid'] = $userid;
	}
	
	
	public function GetSessionID()
	{
		return  $this->sessionID;	
	}
	
	
}

?>