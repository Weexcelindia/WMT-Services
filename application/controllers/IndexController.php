<?php
ini_set('soap.wsdl_cache_enable', 0);

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

    }

    /* public function indexAction()
     {
         // action body
     }
 */
    public function soapAction()
    {
        // disable layouts and renderers
        $this->getHelper('viewRenderer')->setNoRender(true);

        // initialize server and set URI
        $server = new Zend_Soap_Server(null,
            array('uri' => 'http://localhost/zend_webservice/public/index.php/index/soap/wsdl'));

        // set SOAP service class
        $server->setClass('Example_Manager');

        // handle request
        $server->handle();
    }

    /**
     * function to generated WSDL.
     */
    public function wsdlAction()
    {

        //You can add Zend_Auth code here if you do not want
        //everybody can access the WSDL file.

        // disable layouts and renderers
        $this->getHelper('viewRenderer')->setNoRender(true);

        // initilizing zend autodiscover object.
        $wsdl = new Zend_Soap_AutoDiscover ();

        // register SOAP service class
        $wsdl->setClass('Example_Manager');

        // set a SOAP action URI. here, SOAP action is 'soap' as defined above.
        $wsdl->setUri('http://localhost/zend_webservice/index.php/index/soap');


        // handle request
        $wsdl->handle();
    }

    public function testAction()
    {


        $request = $this->getRequest();
        if ($request->isPost()) {
           $data =$request->getParams();
            $firstName = $data['firstName'];
            $lastName = $data['lastName'];
            $userName = $data['userName'];
            $email = $data['emailAddress'];
            $password = $data['password'];
            $insertData = array('firstName'=>$firstName,'lastName'=>$lastName,'username'=>$userName,
            'email'=>$email,'password'=>$password);

           $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/index/soap',
                'uri'      => 'http://localhost/zend_webservice/public/index.php/index/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->addUser($insertData);
                print_r($result);
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }

        }
        #  Zend_Debug::dump($this->getRequest());

        #$client = new Zend_Soap_Client('http://localhost/zend_webservice/public/index.php/index/wsdl');
        # $result=$client->getProduct(1);
        # Zend_Debug::dump($result);
        #$this->getHelper ( 'viewRenderer' )->setNoRender ( true );
        /* $options = array(
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
 */

    }

    public function editAction(){
        $this->getHelper('viewRenderer')->setNoRender(true);
             $id = $_GET['id'];
        $options = array(
            'location' => 'http://localhost/zend_webservice/public/index.php/index/soap',
            'uri'      => 'http://localhost/zend_webservice/public/index.php/index/wsdl'
        );
        try {
            $client = new Zend_Soap_Client(null, $options);
            $result = $client->getProduct($id);
           # print_r($result);
          echo  $json = Zend_Json::encode($result);
        } catch (SoapFault $s) {
            die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }

    }

}

