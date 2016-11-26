<?php
/*
Description:    Basic purchase fuctions for paypal
                
****************History************************************
Date:         	8.17.2010
Author:       	Allen Halsted
Mod:          	Creation
***********************************************************
*/    
include ($_SERVER['DOCUMENT_ROOT'].'/lib/system/common/system_start_session.php');
include ($_SERVER['DOCUMENT_ROOT'].'/lib/system/common/ws_purchase.class.php');
include ($_SERVER['DOCUMENT_ROOT'] . '/lib/configs/tbs.inc.php');


if (isset($_SESSION['pythia_userid']) && isset($_POST['product'])) //Must be logged in or we are done here
{
	
	$tpl = new clsTinyButStrong;
	
	$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
	$paypal_merchantid = 'WJEBAMYMGR6ML';
	
	$product = explode('|',$_POST['product']);
	
	$productID = $product[0];
	$item_descript =$product[1];
	$item_cost =$product[2];
	
	$objPurchase=new purchase_webservice();
	$payload = $objPurchase->BeginTransaction($productID);
	
	$return_url = 'http://apps.facebook.com/MyGame';
	$cancel_url = 'http://apps.facebook.com/MyGame';
	$iplistner = 'http://'.$_SERVER['SERVER_NAME'].'/lib/system/paypal/ipnListener.php';
	
	
	//Load and display the template
	$tpl->LoadTemplate($_SERVER['DOCUMENT_ROOT'] . '/lib/system/purchase/purchase_paypal.tpl.php');
	$tpl->Show();
	
}

?>