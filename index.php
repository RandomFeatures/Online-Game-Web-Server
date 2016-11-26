<?php
/*
Description:    Main lands index page
                
****************History************************************
Date:         	10.11.2009
Author:       	Allen Halsted
Mod:          	Creation
***********************************************************
*/
	include_once ($_SERVER['DOCUMENT_ROOT'] . '/lib/system/common/system_start_session.php');

	//default settings
	$myserver = $_SERVER['SERVER_NAME'];
  
	include ($_SERVER['DOCUMENT_ROOT'] . '/lib/configs/tbs.inc.php');
	$tpl = new clsTinyButStrong;
	  	
	if (isset($_GET['login']))
	{//Load Login Page
	
		include($_SERVER['DOCUMENT_ROOT'] . '/apps/lands/lib/system/lands_show_login.php');
	}elseif (isset($_GET['register']))
	{//Realm Builder
		include($_SERVER['DOCUMENT_ROOT'] . '/apps/lands/lib/system/lands_show_register.php');
	}elseif (isset($_POST['paysystem']))
	{//PaySystem
		if ($_POST['paysystem'] == 1)
			include($_SERVER['DOCUMENT_ROOT'] . '/lib/system/purchase/purchase_paypal.php');
		
	}else //Load Login Page
		include($_SERVER['DOCUMENT_ROOT'] . '/apps/lands/lib/system/lands_show_login.php');
	 	
	//Load and display the template
	$tpl->LoadTemplate($_SERVER['DOCUMENT_ROOT'] . '/apps/lands/lib/templates/' . $tmplatename);
	$tpl->Show();
 
?>
