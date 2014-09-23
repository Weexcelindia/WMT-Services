<?php
ini_set('soap.wsdl_cache_enable', 0);
// include auto-loader class
require_once 'Zend/Loader/Autoloader.php';
require 'tcpdf/tcpdf.php';
#require 'libchart/classes/libchart.php';

class UserController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */

    }

    public function soapAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $server = new Zend_Soap_Server(null,
            array('uri' => 'http://localhost/zend_webservice/public/index.php/user/soap/wsdl'));

        $server->setClass('Webservice_User');

        $server->handle();
    }

    public function wsdlAction()
    {

        $this->getHelper('viewRenderer')->setNoRender(true);

        $wsdl = new Zend_Soap_AutoDiscover ();

        $wsdl->setClass('Webservice_User');

        // set a SOAP action URI. here, SOAP action is 'soap' as defined above.
        $wsdl->setUri('http://localhost/zend_webservice/index.php/user/soap');

        $wsdl->handle();
    }

    public function adduserAction()
    {
        #echo $uri = $this->view->serverUrl();
        $request = $this->getRequest();
        $IndustriesIds = "151,152,153";
        #$industriesIds = $data['IndustriesIds'];
        $arrayIds = explode(',', $IndustriesIds);
        $key1 = $arrayIds[0];
        $key2 = $arrayIds[1];
        $key3 = $arrayIds[2];
        $htmlContent='<title>
    Add User
</title>
<body>
<form action="#" method="post" id="sendphone">
    <label>Phone Number</label><input type="text" name="phoneNumber"><br>
    <label>Email Address</label><input type="text" name="emailAddress"><br>
    <input type="submit" name="submit" value="Add User"/>
    </form>';
        $path = 'genrate.pdf';
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->AddPage();
        // output the HTML content
       # $pdf->writeHTML($htmlContent, true, false, true, false,'');
        #$pdf->Output($path, 'F');
        $industrylevels = array($key1 => "industries1", $key2 => "industries2", $key3 => "industries3");
        print_r($industrylevels);
        foreach ($industrylevels as $key => $value) {
            echo $key;

        }
        /*
         * End of pdf code
         */


        if ($request->isPost()) {
            $data = $request->getParams();
            $phone_number = $data['phoneNumber'];
            $email_address = $data['emailAddress'];
            $createdData = date('Y-m-d H:i:s');
            $createdTime = date('H:i:s');
            $data = array('PhoneNumber' => $phone_number, "CreatedDate" => $createdData, "CreateTime" => $createdTime);

            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->addStoreUser($data, $phone_number);
                if ($result) {
                    $userData = array('StoreID' => $result, 'UserName' => $phone_number, "EmailAddress" => $email_address);
                    $store_id = $client->addUser($result, $userData);
                    $success = array("store_id" => $result);
                    echo json_encode($success);
                } else {
                    echo json_encode(array('success' => $result));
                }
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }
        }
    }

    public function updatepasswordAction()
    {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getParams();
            $store_id = $data['store_id'];
            $password = $data['password'];
            $data = array("Password" => $password);

            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->updatePassword($store_id, $data);
                $success = array("success" => $result);
                echo json_encode($success);
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }

        }

    }

    public function updatestoredataAction()
    {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getParams();
            $store_id = $data['store_id'];
            $name = $data['storeName'];
            $address1 = $data['address1'];
            # $addressDetail = $data['addressDetails'];
            $address2 = $data['address2'];
            $phoneNumber = $data['phoneNumber'];
            $createdData = date('Y-m-d H:i:s');
            $createdTime = date('H:i:s');
            $type = "Public";

            #$addressData = json_encode($data['StoreAddress']);

            $industryLevel1 = $data['Industrylevel1'];
            $industryLevel2 = $data['Industrylevel2'];
            $industryLevel3 = $data['Industrylevel3'];

            $industrylevels = array("IndustryLevel" => $industryLevel1, "industryLevel2" => $industryLevel2, "industryLevel3" => $industryLevel3);


            $details = array('StoreID' => $store_id, 'StoreName' => $name, 'PhoneNumber' => $phoneNumber,
                'Address' => $address1, "AddressDetail" => $address2, "CreatedDate" => $createdData, "type" => $type, "CreateTime" => $createdTime);

            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->updateAddressStoreData($store_id, $details);
                $storeResult = $client->insertStoreIndustry($store_id, $industrylevels);
                $success = array("success" => $result);
                echo json_encode($success);
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }

        }

    }

    public function userloginAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getParams();
            $phoneNumber = $data['phoneNumber'];
            $password = $data['password'];
            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );

            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->loginUser($phoneNumber, $password);
                if ($result) {
                    $store_id = $result[0]['StoreID'];
                    $user_id = $result[0]['userID'];
                    $userData = array('store_id' => $store_id, "user_id" => $user_id);
                    echo json_encode($userData);
                } else {
                    $success = array("success" => $result);
                    echo json_encode($success);
                }
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }
        }

    }

    public function publishpasswordAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getParams();
            $store_id = $data['store_id'];
            $publishPassword = $data['publishPassword'];
            $updatePassword = array('PublishPassword' => $publishPassword);
            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->updatePublishPassword($store_id, $updatePassword);
                $success = array("success" => $result);
                echo json_encode($success);
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }
        }
    }

    public function ownerprofileAction()
    {

        $request = $this->getRequest();
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/zend_webservice/public/pic/";
        if ($request->isPost()) {
            $data = $request->getParams();
            $store_id = $data['store_id'];
            $owner_name = $data['ownerName'];
            $owner_id = $data['owner_id'];
            #$profilePicPath =$data['ProfilePicPath'];
            $profilePicPath = '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';
            $decode = base64_decode($profilePicPath);
            $profilePath = $dir . $store_id . ".png";
            $file = fopen($profilePath, 'wb');
            if (fwrite($file, $decode) === false) {
                echo "Error";
            }
            $ownerData = array('OwnerName' => $owner_name, 'ProfilePicPath' => $profilePath, 'OwnerId' => $owner_id);
            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->addOwnerData($store_id, $ownerData);
                $success = array("success" => $result);
                echo json_encode($success);
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }

        }

    }

    public function editstoreinfoAction()
    {
        $store_id = $_GET['store_id'];

        # getStoreDataByID
        $options = array(
            'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
            'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
        );
        try {
            $client = new Zend_Soap_Client(null, $options);
            $result = $client->getStoreDataByID($store_id);
            #$success = array("success" => $result);
            echo json_encode($result);
        } catch (SoapFault $s) {
            die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }


    public function updatefieldAction()
    {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getParams();

            switch ($data) {
                case $data['storename']:
                    echo "hi";
                    break;
                case $data['storeaddresss']:
                    echo "no data";
                    break;
                default:
                    break;
            }

        }

    }


    public function validatePublishPinAction()
    {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getParams();
            $store_id = $data['store_id'];
            $published_pin = $data['published_pin'];
            # $user_id = $data['user_id'];

            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->validatePublish($store_id, $published_pin);
                $success = array("success" => $result);
                echo json_encode($result);
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }

        }


    }

    public function forgetpasswordAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getParams();
            $store_id = $data['store_id'];
            $user_id = $data['user_id'];
            $password = uniqid();
            $newpassword = md5($password);
            $email_id = $data['EmailAddress'];
            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->updateNewPassword($store_id, $password, $email_id);
                $success = array("success" => $result);
                echo json_encode($success);
                if ($result) {
                    $this->sendPasswordEmail($password, $email_id);
                }
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }

        }
    }

    function sendPasswordEmail($password, $email_id)
    {
        $subject = "Forget Store Password";
        $message = "<table><tr><td>Password: </td><td>$password</td></tr><tr><td>Email Address: </td><td>$email_id</td></tr></table>";
        $success = mail("weexecelindia@example.com", $subject, $message, "From: $email_id\n");
        return $success;
    }

    /*
     * add discount Product
     * created date 23/6/2014
     * Insert data for discount product
     */
    public function discountproductAction()
    {
        $request = $this->getRequest();
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/zend_webservice/public/pic/";
        if ($request->isPost()) {
            $data = $request->getParams();
            $store_id = $data['store_id'];
            $profilePicPath = $data['profilePicPath'];
            $introduction = $data['introduction'];
            $orignalPrice = $data['orignalPrice'];
            $discount = $data['discount'];
            $decode = base64_decode($profilePicPath);
            $profilePath = $dir . $store_id . ".png";
            $file = fopen($profilePath, 'wb');
            if (fwrite($file, $decode) === false) {
                echo "Error";
            }
            $discountData = array("storeid" => $store_id, "productImagePath" => $profilePath, "Introduction" => $introduction,
                "OrignalPrice" => $orignalPrice, "Discount" => $discount);
            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->addDiscountProduct($discountData);
                $success = array("success" => $result);
                echo json_encode($success);
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }
        }
    }

    /*
     * update discount product
     * Created 23/6/2014
     *
     */
    public function updatediscountproductAction()
    {
        $request = $this->getRequest();
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/zend_webservice/public/pic/";
        if ($request->isPost()) {
            $data = $request->getParams();
            $store_id = $data['store_id'];
            $profilePicPath = $data['profilePicPath'];
            $introduction = $data['introduction'];
            $orignalPrice = $data['orignalPrice'];
            $discount = $data['discount'];
            $decode = base64_decode($profilePicPath);
            $profilePath = $dir . $store_id . ".png";
            $file = fopen($profilePath, 'wb');
            if (fwrite($file, $decode) === false) {
                echo "Error";
            }
            $discountData = array("storeid" => $store_id, "productImagePath" => $profilePath, "Introduction" => $introduction,
                "OrignalPrice" => $orignalPrice, "Discount" => $discount);
            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->updateDiscountProductData($store_id, $discountData);
                $success = array("success" => $result);
                echo json_encode($success);
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }
        }
    }

    /*
     * Edit discount product by Store_id
     * @param $store_id
     * Created date 23/6/2014
     */
    public function editdiscountproductAction()
    {
        $store_id = $_GET['store_id'];
        $options = array(
            'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
            'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
        );
        try {
            $client = new Zend_Soap_Client(null, $options);
            $result = $client->editDiscountProductDataById($store_id);
            $success = array("success" => $result);
            echo json_encode($success);
        } catch (SoapFault $s) {
            die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
        } catch (Exception $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }

    /*
     * add Last minute Product
     * created 23/6/2014
     */

    public function lastminuteproductAction()
    {
        $request = $this->getRequest();
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/zend_webservice/public/pic/";
        if ($request->isPost()) {
            $data = $request->getParams();
            $store_id = $data['store_id'];
            $profilePicPath = $data['profilePicPath'];
            $introduction = $data['introduction'];
            $orignalPrice = $data['orignalPrice'];
            $discount = $data['discount'];
            $expiryDate = $data['expiryDate'];
            $decode = base64_decode($profilePicPath);
            $profilePath = $dir . $store_id . ".png";
            $file = fopen($profilePath, 'wb');
            if (fwrite($file, $decode) === false) {
                echo "Error";
            }

            $lastminuteproductData = array("StoreID" => $store_id, "productImagePath" => $profilePath, "introduction" => $introduction,
                "orignalPrice" => $orignalPrice, "discount" => $discount, "expiryDate" => $expiryDate);
            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->addLastMinuteProduct($lastminuteproductData);
                $success = array("success" => $result);
                echo json_encode($success);
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }

        }
    }

    /*
     * Member Ship Discount
     * Created 24/6/2014
     */
    public function membershipdiscountAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getParams();
            $store_id = $data['store_id'];
            $discount = $data['discount'];
            $created_date = date('Y-m-d H:i:s');
            $discount_data = array("StoreID" => $store_id, "Membership_discount" => $discount, "CreatedDate" => $created_date);

            $options = array(
                'location' => 'http://localhost/zend_webservice/public/index.php/user/soap',
                'uri' => 'http://localhost/zend_webservice/public/index.php/user/wsdl'
            );
            try {
                $client = new Zend_Soap_Client(null, $options);
                $result = $client->memberShipDiscount($store_id, $discount_data);
                $success = array("success" => $result);
                echo json_encode($success);
            } catch (SoapFault $s) {
                die('ERROR: [' . $s->faultcode . '] ' . $s->faultstring);
            } catch (Exception $e) {
                die('ERROR: ' . $e->getMessage());
            }

        }
    }

   public function getresponseAction(){
       $this->getHelper('viewRenderer')->setNoRender(true);
       header("Access-Control-Allow-Origin: *");
       $dir = $_SERVER['DOCUMENT_ROOT'] . "/zend_webservice/public/storeinfopic/";
       $request = $this->getRequest();
      if($request->isPost()){
          $store_id = 45;
          $profilePicPath = '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';
          $decode = base64_decode($profilePicPath);
          $profilePath = $dir . $store_id . ".png";
          $file = fopen($profilePath, 'wb');
          if (fwrite($file, $decode) === false) {
              echo "Error";
          }else{
              echo "Success fully save";
          }
      }
   }

    /**
     *
     */
    public function libchartAction(){
    }

}


?>