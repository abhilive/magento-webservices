<?php
/*
 * File : API.php (SOAP V1 API File For Cutom Webservices)
 * Decription : Defines all API functions
 * Author : Abhishek Srivastva <abhishek.srivastava@smartdatainc.net>
 */
class Smartdata_Webservices_Model_Category_Api extends Mage_Api_Model_Resource_Abstract {
    /*
     * Get Customer Prefrences
     */
    public function getpref( $customerId ){
	$Multiarray = array();
	$customerid = $customerId;
	$collection = Mage::getModel('customer/customer')->getCollection()
	    ->addAttributeToSelect('*')
	    ->addAttributeToFilter('entity_id',$customerid);
	foreach ($collection as $item) {
	    $hangout = $item->getHangout();
	    $celebration = $item->getCelebration();
	    $interest = $item->getInterest();
	}
	$Hangout = (explode(",",$hangout));
	$Celebration = (explode(",",$celebration));
	$Interest = (explode(",",$interest));
	
	foreach($Hangout as $hang){
	    $_category = Mage::getModel('catalog/category')->getCollection()-> addAttributeToSelect('*')
		      ->addAttributeToFilter('entity_id',$hang); 
	    foreach ($_category as $value) {
		$HArray = array();
		$HArray['entity_id'] = $value->getId();
		$HArray['name'] = $value->getName();
		$HArray['image'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."catalog/category/".$value->getImage();
		$finalhangoutArray[] = $HArray;
	    }//End Foreach - Category
	}//End Foreach - Hangout
	
	foreach($Celebration as $celebration){
	    $_category = Mage::getModel('catalog/category')->getCollection()-> addAttributeToSelect('*')
			      ->addAttributeToFilter('entity_id',$celebration); 
	    foreach ($_category as $value) {
		$CArray = array();
		$CArray['entity_id'] = $value->getId();
		$CArray['name'] = $value->getName();
		$CArray['image'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."catalog/category/".$value->getImage();
		$finalcelebrationArray[] = $CArray;
	    }//End Foreach - Category
	}//End Foreach - Celebration

	foreach($Interest as $interest){
	    $_category = Mage::getModel('catalog/category')->getCollection()-> addAttributeToSelect('*')
			      ->addAttributeToFilter('entity_id',$interest); 
	    foreach ($_category as $value) {
		$IArray = array();
		$IArray['entity_id'] = $value->getId();
		$IArray['name'] = $value->getName();
		$IArray['image'] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."catalog/category/".$value->getImage();
		$finalinterestArray[] = $IArray;
	    }//End Foreach - Interest
	}//End Foreach - Category

	$Multiarray['hangout'] = $finalhangoutArray;
	$Multiarray['celebration'] = $finalcelebrationArray;
	$Multiarray['interest'] = $finalinterestArray;

	return $Multiarray;
    }
}
