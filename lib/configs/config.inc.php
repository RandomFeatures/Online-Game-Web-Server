<?php

/*
Description:    Database config 
                
****************History************************************
Date:         	10.13.2009
Author:       	Allen Halsted
Mod:          	Creation
***********************************************************
*/

//$proxy = Array('host' => '10.192.100.99', 'port' => '3128');
define('GAME_PLAY_WSDL', 'http://192.168.2.202:8080/charon.web.myaccount/gameplay?wsdl');
define('MANAGE_USER_WSDL', 'http://192.168.2.202:8080/charon.web.myaccount/manageusers?wsdl');
define('MANAGE_GAMES_WSDL', 'http://192.168.2.202:8080/charon.web.myaccount/managegames?wsdl');
define('PURCHASE_WSDL', 'http://192.168.2.202:8080/charon.web.myaccount/purchase?wsdl');

define('INACTIVE', 0);
define('ACTIVE', 1);
define('SOURCE_FACEBOOK', 0);
define('SOURCE_WEB', 1);
define('STATUS_INACTIVE',0);
define('STATUS_ACTIVE',1);
define('STATUS_BANNED',2);
define('STATUS_SUSPENDED',3);
define('STATUS_DEAD',4);
define('STATUS_PROTECTED',5);


?>