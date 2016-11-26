<?php
/*
Description:    Complete PayPal Purchase
                
****************History************************************
Date:         	8.18.2010
Author:       	Allen Halsted
Mod:          	Creation
***********************************************************
*/    
include ($_SERVER['DOCUMENT_ROOT'].'/lib/system/common/ws_purchase.class.php');

	
	$itemcode = 0;//user defind value that we send. Should come back in the payload
	$externalID = 88; //put paypals transaciton id in here
	$externalData = "Extern";//this will hold a lot of data. should be able to put the entire paypal post in here for loging
	
	//new instance of the purchase webservice
	$objPurchase=new purchase_webservice();
	//complete the purchase	
	$objPurchase->CompletePurchase($itemcode,$externalID,$externalData);
	unset($objPurchase);

?>