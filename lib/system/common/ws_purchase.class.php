<?php
/*
Description:    Wrapper for the Purchase Webservice
 
 ****************History************************************
 Date:         	8.17.2010
 Author:       	Allen Halsted
 Mod:          	Creation
 ***********************************************************
 */
include ($_SERVER['DOCUMENT_ROOT'].'/lib/configs/base.inc.php');
include ($_SERVER['DOCUMENT_ROOT'].'/lib/configs/config.inc.php');
include ($_SERVER['DOCUMENT_ROOT'].'/lib/system/common/system_start_session.php');

class purchase_webservice {
    
	
	public function &GetProductList() {
		
		$client = new SoapClient(PURCHASE_WSDL);
        $param = array('sessionxml'=>$_SESSION['pythia_xml']);
        $response = $client->getProductList($param);
        $returnxml = $response->return;
		//var_dump($returnxml);//Debug: throw the raw data onto the screen
		$result = new sys_recordset;
		if ($result->ParseXML($returnxml))
		{
			$rtn = $result;
		}	
        
		unset($returnxml);
		unset($response);
		unset($client);
		
        return $rtn;
    }
	
	public function &BeginTransaction($productid) {
		
		$client = new SoapClient(PURCHASE_WSDL);
        $param = array('sessionxml'=>$_SESSION['pythia_xml'], 'productid'=>$productid);
        $response = $client->beginTransaction($param);
        $return = $response->return;
        
		unset($response);
		unset($client);
		
        return $return;
    }
    
	public function &CompletePurchase($itemcode,$externalID,$externalData) {
		
		$client = new SoapClient(PURCHASE_WSDL);
        $param = array('itemcode'=>$itemcode, 'externalData'=>$externalData, 'externalID'=>$externalID);
        $client->completePurchase($param);

        unset($client);
        
    }
    
	public function &LogPurchaseErrors($ErrorMsg,$PayLoad) {
		
		$client = new SoapClient(PURCHASE_WSDL);
        $param = array('errmsg'=>$ErrorMsg, 'payload'=>$PayLoad);
        $client->logPurchaseErrors($param);

        unset($client);
        
    }
    
}
?>