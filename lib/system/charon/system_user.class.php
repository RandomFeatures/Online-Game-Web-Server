<?php
/*
 Description:    Class for storing and retrieving
 				 the user data in a session.
 
 ****************History************************************
 Date:         	{DATE}
 Author:       	Allen Halsted
 Mod:          	Creation
 ***********************************************************
 */


class sys_user {
    
	var $failed = false; // failed login attempt
    var $date; // current date GMT
    var $id = 0; // the current user's id
   
    var $gameName = ''; //name of the current game
    var $gameID = ''; //Game ID
    var $sourceID = ''; //Source ID
    var $sourceName = ''; //Name fo the current source
    var $userID = '';//Current users ID
    var $sessionID = '';//Current SessionID
    
    
    
	function User(&$db) {
        $this->date = $GLOBALS['date'];
        
		$this->gameName = $GameName;
        $this->sourceName = $SourceName;
        $this->gameID = (isset($_SESSION['pythia_gameid'])) ? $_SESSION['pythia_gameid'] : '';
        $this->sourceID = (isset($_SESSION['pythia_sourceid'])) ? $_SESSION['pythia_sourceid'] : '';
        $this->userID = (isset($_SESSION['pythia_userid'])) ? $_SESSION['pythia_userid'] : '';
        $this->sessionID = session_id();
		
		if ($_SESSION['logged']) {
            $this->_checkSession();
        } elseif (isset($_COOKIE['mtwebLogin'])) {
            $this->_checkRemembered($_COOKIE['mtwebLogin']);
        }
    }

	
    function _checkLogin($username, $password, $remember) {
        $username = $this->db->quote($username);
        $password = $this->db->quote(md5($password));
        $sql = "SELECT * FROM member WHERE "."username = $username AND "."password = $password";
        $result = $this->db->getRow($sql);
        if (is_object($result)) {
            $this->_setSession($result, $remember);
            return true;
        } else {
            $this->failed = true;
            $this->_logout();
            return false;
        }
    }
    
    function _setSession(&$values, $remember, $init = true) {
        $this->id = $values->id;
        $_SESSION['uid'] = $this->id;
        $_SESSION['username'] = htmlspecialchars($values->username);
        $_SESSION['cookie'] = $values->cookie;
        $_SESSION['logged'] = true;
        if ($remember) {
            $this->updateCookie($values->cookie, true);
        }
        if ($init) {
            $session = $this->db->quote(session_id());
            $ip = $this->db->quote($_SERVER['REMOTE_ADDR']);
            
            $sql = "UPDATE member SET session = $session, ip = $ip WHERE "."id = $this->id";
            $this->db->query($sql);
        }
    }
    
    function _checkRemembered($cookie) {
        list($username, $cookie) = @unserialize($cookie);
        if (!$username or !$cookie)
            return;
        $username = $this->db->quote($username);
        $cookie = $this->db->quote($cookie);
        $sql = "SELECT * FROM member WHERE "."(username = $username) AND (cookie = $cookie)";
        $result = $this->db->getRow($sql);
        if (is_object($result)) {
            $this->_setSession($result, true);
        }
    }

    
    function _checkSession() {
        $username = $this->db->quote($_SESSION['username']);
        $cookie = $this->db->quote($_SESSION['cookie']);
        $session = $this->db->quote(session_id());
        $ip = $this->db->quote($_SERVER['REMOTE_ADDR']);
        $sql = "SELECT * FROM member WHERE "."(username = $username) AND (cookie = $cookie) AND "."(session = $session) AND (ip = $ip)";
        $result = $this->db->getRow($sql);
        if (is_object($result)) {
            $this->_setSession($result, false, false);
        } else {
            $this->_logout();
        }
    }

}
?>
