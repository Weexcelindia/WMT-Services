<?php
// load Zend libraries
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Soap_Client');

// initialize SOAP client
$options = array(
    'location' => 'http://localhost/zend_webservice/public/index.php/index/soap',
    'uri'      => 'http://localhost/zend_webservice/public/index.php/index/wsdl'
);

try {
    $client = new Zend_Soap_Client(null, $options);
    $result = $client->getProducts();
    print_r(json_encode($result));
} catch (SoapFault $s) {
    die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
} catch (Exception $e) {
    die('ERROR: ' . $e->getMessage());
}