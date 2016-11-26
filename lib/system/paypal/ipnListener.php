<?php 

include ($_SERVER['DOCUMENT_ROOT'].'/lib/system/common/ws_purchase.class.php');

error_reporting(E_ALL ^ E_NOTICE); 
$header = ""; 
// Read the post from PayPal and add 'cmd' 
$req = 'cmd=_notify-validate'; 
if(function_exists('get_magic_quotes_gpc')) 
{  
	$get_magic_quotes_exits = true; 
} 
foreach ($_POST as $key => $value) 
// Handle escape characters, which depends on setting of magic quotes 
{  
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1){  
		$value = urlencode(stripslashes($value)); 
	} else { 
		$value = urlencode($value); 
	} 
	$req .= "&$key=$value"; 
} 
// Post back to PayPal to validate 
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n"; 
$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n"; 
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30); 

//new instance of the purchase webservice
$objPurchase=new purchase_webservice();
 
// Process validation from PayPal 
// TODO: This sample does not test the HTTP response code. All 
// HTTP response codes must be handles or you should use an HTTP 
// library, such as cUrl 


if (!$fp) { // HTTP ERROR 
	$ErrorMsg = "HTTP ERROR Processing IPN";
	$PayLoad = print_r($_POST, true);
    //Log Errors      
    $objPurchase->LogPurchaseErrors($ErrorMsg,$PayLoad);
    unset($objPurchase);

} else { // NO HTTP ERROR 
fputs ($fp, $header . $req); 
while (!feof($fp)) { 
	$res = fgets ($fp, 1024); 
	if (strcmp ($res, "VERIFIED") == 0) { 
		// TODO: 
		// Check the payment_status is Completed 
		// Check that txn_id has not been previously processed 
		//    $externalID will be checked within CompletePurchase
		// Check that receiver_email is your Primary PayPal email 
		// Check that payment_amount/payment_currency are correct 
		// Process payment 
		// If 'VERIFIED', send an email of IPN variables and values to the 
		// specified email address 
		
		
    	//data from the paypal post
		$status = $_POST['payment_status'];
    	$itemcode = $_POST['item_number']; //user defind value that we send. Should come back in the payload
    	$externalID = $_POST['txn_id']; //put paypals transaciton id in here
    	$externalData = $req; //this will hold a lot of data. should be able to put the entire paypal post in 		here for logging

    	//complete the purchase      
    	$objPurchase->CompletePurchase($itemcode,$externalID,$externalData, $status);
    	unset($objPurchase);

	} else if (strcmp ($res, "INVALID") == 0) { 

		$ErrorMsg = "INVALID received from PayPal";
		$PayLoad = print_r($_POST, true);
    	//Log Errors      
    	$objPurchase->LogPurchaseErrors($ErrorMsg,$PayLoad);
    	unset($objPurchase);
		
	} // end if	 
} // end while
} // end if(!$fp)
fclose ($fp); 
?>
