<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*
 * File : API.php (SOAP V1 API File For Custom Webservices)
 * Decription : Defines all API functions
 * Author : Abhishek Srivastva <abhishek.srivastava@smartdatainc.net>
 */
class Smartdata_Webservices_Model_Customer_Api extends Mage_Api_Model_Resource_Abstract {    
    /*
     * Get Customer's Group //Raw Method : Not In Use : But We can use such type of logic later
     */
    public function showItems() {
	$collection = Mage::getModel('customer/group')->getCollection();
	
	$customer_table = Mage::getResourceModel('customer/customer')->getEntityTable();
	
	$collection->getSelect()
		->joinLeft(
			$customer_table,
			$customer_table.'.group_id = main_table.customer_group_id',
			array('customers_count'=>'count(DISTINCT '.$customer_table.'.entity_id)')
			)
		->group('main_table.customer_group_id');
		
	$result = array();
	
	foreach ($collection as $group) {
	    $result[] = $group->toArray(array('customer_group_id','customer_group_code','customer_count'));
	}
	return $result; 
    }
    /*
     * Get Customer Prefrences
     */
    public function getpref( $sessionId, $customerId ){
	$collection = Mage::getModel('customer/group')->getCollection();
	
	$customer_table = Mage::getResourceModel('customer/customer')->getEntityTable();
	
	$collection->getSelect()
		->joinLeft(
			$customer_table,
			$customer_table.'.group_id = main_table.customer_group_id',
			array('customers_count'=>'count(DISTINCT '.$customer_table.'.entity_id)')
			)
		->group('main_table.customer_group_id');
		
	$result = array();
	
	foreach ($collection as $group) {
	    $result[] = $group->toArray(array('customer_group_id','customer_group_code','customer_count'));
	}
	return $result;
    }
}
