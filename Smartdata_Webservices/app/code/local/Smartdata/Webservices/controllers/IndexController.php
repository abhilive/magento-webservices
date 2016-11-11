<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Smartdata
 * @package     Smartdata_Webservices
 * @copyright   Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @category    Smartdata
 * @package     Smartdata_Webserivces
 * @Author      Abhishek Srivastava <abhishek.srivastava@smartdatainc.net>
 * 
 * Index controller for webservices
 */
class Smartdata_Webservices_IndexController extends Mage_Core_Controller_Front_Action
{
    protected $api_user;
    protected $api_key;
    
    protected $soap_v1_url_suffix = '/api/soap?wsdl=1';
    protected $soap_v2_url_suffix = '/api/v2_soap/?wsdl=1';
    
    protected $soapClient;
    
    public function _construct() {
	ini_set("soap.wsdl_cache_enabled", "0");
	parent::_construct();
    }
    
    public function getSoapV1Session($params) {
	$this->api_user = $params['api_user']; 
	$this->api_key = $params['api_key'];
		    
	$magento_url = Mage::getBaseUrl();
	$soap_url = $magento_url.'/'.$this->soap_v1_url_suffix;
	try {
	    $soap = new SoapClient( $soap_url ); 
	    $sessionId = $soap->login( $this->api_user, $this->api_key );
	    $this->soapClient = $soap;
	} catch(Exception $ex) {
	    echo $ex->getMessage();die;
	}
	
	return $sessionId;
    }
    
    public function getSoapV2Session($params) {
	$this->api_user = $params['api_user']; 
	$this->api_key = $params['api_key'];
		    
	$magento_url = Mage::getBaseUrl();
	$soap_url = $magento_url.'/'.$this->soap_v2_url_suffix;
	try {
	    $soap = new SoapClient( $soap_url ); 
	    $sessionId = $soap->login( $this->api_user, $this->api_key );
	    $this->soapClient = $soap;
	} catch(Exception $ex) {
	    echo $ex->getMessage();die;
	}
	
	return $sessionId;
    }
    
    public function indexAction() {
	ob_start();
	$html = '<html>';
	$html .= '<head>';
	$html .= '<title>Magento Webservices</title>';
	$html .= '<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>';
	$html .= '</head>';
	$html .= '<body><div class="container" style="margin-top: 20px;"><div class="row"><div class="col-md-12"><div class="panel panel-primary"><div class="panel-body">';
	$html .= '<form class="form-inline" role="form" action="'.Mage::getBaseUrl().'webservices/index/sendPost">
		    <div class="form-group">
			<label for="username">API UserName:</label>
			<input type="text" name="username" class="form-control" id="username">
		    </div>
		    <div class="form-group">
			<label for="api_key">API Key:</label>
			<input type="text" name="api_key" class="form-control" id="api_key">
		    </div>
		    <div class="form-group">
			<label for="wst">Web Service Type:</label>
			<select name="wst" class="form-control" id="wst">
			  <option value="soap_v1">SOAP V1</option>
			  <option value="soap_v2">SOAP V2</option>
			</select>
		     </div>
		    <button type="submit" class="btn btn-primary">Submit</button>
		  </form>';
	$html .= '</div></div></div>
		<div class="col-md-12">
		<p class="bg-danger">SOAP-V2 request takes more time to response as compared to SOAP-V1.</p>
		<p class="bg-info">Generate API UserName & Key from Magento Admin Panel. For more information <a target="_blank" href="http://inchoo.net/magento/magento-v2-soap-demystified/" class="btn btn-success" role="button">Click here</a></p>
		</div>'
		. '<div class="col-md-12"><p>
		    <a class="btn btn-primary" target="_blank" href="'.Mage::getUrl('adminhtml').'" role="button">Visit Admin Panel »</a>
		  </p>
	</div></div></div></body>';
	$html .= '</html>';
	ob_end_clean();
	$this->getResponse()->setBody($html);
    }
    
    public function sendPostAction($params) {
	$params = $this->getRequest()->getParams();
	if(($params['username']=='')||($params['api_key']=='')){
	    $this->_redirect('*/');
            return;
	}
	
	if($params['wst']=='soap_v2'){
	    $this->_redirect('*/*/soapv2',array(
                'api_user'   => $params['username'],
                'api_key'    => $params['api_key']
            ));
	} else {
	    $this->_redirect('*/*/soapv1',array(
                'api_user'   => $params['username'],
                'api_key'    => $params['api_key']
            ));
	}
    }
    
    public function soapv1Action() {
	try {
	    $params = $this->getRequest()->getParams();
	    $sessionId = $this->getSoapV1Session($params);
	    
	    echo '<h5 style="background-color: rgb(255, 255, 0);"><i>SOAP-V1 : Pre defined method : Get customers list : Response :</i></h5>';
	    $result = $this->soapClient->call($sessionId, 'customer.list');
	    
	    print_r($result);
	    
	    echo "<h5 style='background-color: rgb(255, 255, 0);'><i>SOAP-V1 : Custom API Method : Get customer's group : Response :</i></h5>";
	    $result = $this->soapClient->call( $sessionId, 'webservice_customer.getpref', array('1') );

	    print_r($result);
	    
	} catch (Exception $ex) {
		echo $ex->getMessage();die;
	}
    }
    
    public function soapv2Action() {
	try {
	    $params = $this->getRequest()->getParams();
	    $sessionId = $this->getSoapV2Session($params);
	    /*
	     * __getFunctions() : This lists all methods defined for soap v2 .
	     * Check the existence of you custom api method there.
	     **/
	    //$functions = $this->soapClient->__getFunctions();
	    //var_dump ($functions);

	    echo '<h5 style="background-color: rgb(255, 255, 0);"><i>SOAP-V2 : Pre defined method : Get customers list : Response :</i></h5>';
	    $result = $this->soapClient->customerCustomerList($sessionId);
	    
	    print_r($result);
	    
	    echo "<h5 style='background-color: rgb(255, 255, 0);'><i>SOAP-V2 : Custom API Method : Get customer's group : Response :</i></h5>";
	    $result = $this->soapClient->webservicesCustomersGetpref($sessionId, $customerId);

	    print_r($result);
	    
	} catch (Exception $ex) {
		echo $ex->getMessage();die;
	}
    }
    
}