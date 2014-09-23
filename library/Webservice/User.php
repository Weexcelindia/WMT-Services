<?php

class Webservice_User
{

    /*
     * Return list of all user from database
     *
     *  @return array
     */
    public function getUsers()
    {
        $db = Zend_Registry::get('Zend_Db');
        $sql = "SELECT * FROM userinfo";
        return $db->fetchAll($sql);
    }

    /*
     * Return list of store user from database
     *
     * @return array
     */
    public function getStoreUsers()
    {
        $db = Zend_Registery::get('Zend_Db');
        $sql = "SELECT * FROM store";
        $result = $db->fetchAll($sql);
        return $result;

    }

    /*
     * Insert user phone number in store table in database
     * @param array $data array of data values with keys -> table fields
     * @return integer id numeric
     */

    public function addStoreUser($data, $phoneNumber)
    {
        $db = Zend_Registry::get('Zend_Db');
        $sql = "SELECT PhoneNumber FROM store WHERE PhoneNumber='$phoneNumber'";
        $result = $db->fetchAll($sql);
        $count = count($result);
        if ($count > 0) {
            return 0;
        } else {
            $db->insert('store', $data);
            $store_id = $db->lastInsertId();
            return $store_id;
        }


    }

    /*
     * Insert user phone number in usesrinfo table in database
     * @param return integer number
     */
    public function addUser($store_id, $data)
    {
        $db = Zend_Registry::get('Zend_Db');
        $db->insert('userinfo', $data);
        $user_id = $db->lastInsertId();
        return $store_id;
    }

    /*
     *  Update password with respect to store ID
     * @param integer $store_id
     * Created date 6/6/2014
     *  @param array $data array of data values with keys -> table fields
     * @return integer number
     */

    public function updatePassword($store_id, $data)
    {
        $db = Zend_Registry::get('Zend_Db');
        $count = $db->update('userinfo', $data, 'StoreID=' . $db->quote($store_id));
        return $count;
    }

    /*
     * Update address into Store Table
     * @param integer $store_id
     * @param array $data array of data values with keys -> table fields
     * @return integer number
     */

    public function updateAddressStoreData($store_id, $data)
    {
        $db = Zend_Registry::get('Zend_Db');
        $count = $db->update('store', $data, 'StoreID=' . $db->quote($store_id));
        return $count;
    }

    /*
     * Insert store industries table fields
     * @param array $data array of data values with keys -> table field
     * @return integer number
     */

    public function insertStoreIndustry($store_id, $data)
    {
        $db = Zend_Registry::get('Zend_Db');
        foreach ($data as $value) {
            $insertData = array("StoreID" => $store_id, "IndustryLevel" => $value);
            $db->insert('storeindustry', $insertData);
        }
    }

    /*
     * Update user publish password in database
     * @param integer $store_id
     * @param array $data array of data values with keys -> table field
     * @param return integer number
     *
     */

    public function updatePublishPassword($store_id, $data)
    {
        $db = Zend_Registry::get('Zend_Db');
        $count = $db->update('userinfo', $data, 'StoreID=' . $db->quote($store_id));
        return $count;
    }

    /*
     * Login user by phone number and password
     * @param $phoneNumber
     * @param $password
     * @param return StoreID and UserID
     */

    public function loginUser($phoneNumber, $password)
    {
        $db = Zend_Registry::get('Zend_Db');
        $sql = "SELECT StoreID,userID FROM userinfo WHERE UserName='$phoneNumber' AND Password ='$password'";
        $result = $db->fetchAll($sql);
        $count = count($result);
        if ($count > 0) {
            return $result;
        } else {
            return 0;
        }
    }

    /*
     * Add owner in store table
     * @param $store_id
     * @param array $data array of data values with keys -> table field
     * @param return integer number
     */

    public function addOwnerData($store_id, $ownerData)
    {
        $db = Zend_Registry::get('Zend_Db');
        $count = $db->update('store', $ownerData, 'StoreID=' . $db->quote($store_id));
        return $count;
    }

    /*
     * Edit store info by id
     * @params $store_id
     * return @params array()
     */
    public function getStoreDataByID($store_id)
    {

        $db = Zend_Registry::get('Zend_Db');
        $sql = "SELECT s.StoreName,s.OwnerName,s.PhoneNumber, s.Address, s.Address2, GROUP_CONCAT(si.IndustryLevel) as industres,GROUP_CONCAT(si.storeIndustryID) as industryIDs  FROM store s JOIN storeindustry si ON s.StoreID =si.StoreID WHERE s.StoreID = '$store_id'";
        # $sql =  "SELECT StoreName,OwnerName,PhoneNumber, Address,Address2 FROM store WHERE StoreID = '$store_id'";
        #$sql = "SELECT s.StoreName,s.OwnerName,s.PhoneNumber, s.Address, s.Address2, (SELECT GROUP_CONCAT(si.IndustryLevel)  FROM storeindustry si  WHERE si.StoreID = s.StoreID ) FROM store s WHERE s.StoreID ='$store_id'";
        return $result = $db->fetchAll($sql);

        # $query = "SELECT IndustryLevel FROM storeindustry WHERE storeid ='$store_id'";
        # $industries = $db->fetchAll($query);
        /*print_r($result);
        $sql_industries = "SELECT IndustryLevel FROM storeindustry WHERE StoreID='$store_id'";
       $ndustries_level = $db->fetchAll($sql_industries);*/

        /*if (count($result) != 1) {
            throw new Exception('Invalid product ID: ' . $store_id);
        }*/


    }


    /*
     * update single field in database
     * @param $store_id;
     * @param array $updateData array of data values with keys -> table field
     * return integer number
     */

    public function updateField($store_id, $updateData)
    {
        $db = Zend_Registry::get('Zend_Db');
        $db->update('store', $updateData, 'StoreID=' . $db->quote($store_id));
        $count = mysql_affected_rows();

        return $count;
    }

    /*
     * update industries level filed in database
     * @param $store_id;
     * @param array $updateData array of data values with keys -> table field
     * return integer number
     */

    public function updateIndustries($store_id, $industrylevels)
    {
        $db = Zend_Registry::get('Zend_Db');
        foreach ($industrylevels as $level) {
            $industry = array("IndustryLevel" => $level);
            $count = $db->update('storeindustry', $industry, 'StoreID=' . $db->quote($store_id));
            return $count;
        }

    }


    /*
     * validate published pin
     * @param $store_id, $publish_pin
     * @return integer number
     */
    public function validatePublish($store_id, $publish_pin)
    {
        $db = Zend_Registry::get('Zend_Db');
        $sql = "SELECT 1 FROM userinfo WHERE StoreID='$store_id' AND PublishPassword ='$publish_pin' ";
        $result = $db->fetchAll($sql);
        $count = count($result);
        if ($count > 0) {
            return 1;
        } else {
            return 0;
        }

    }

    /*
     * Update new password
     * @params $store_id, $password, $email
     * @return integer number
     */
    public function updateNewPassword($store_id, $password, $email_id)
    {
        $db = Zend_Registry::get('Zend_Db');
        $data = array("Password" => $password);
        $where = array("StoreID=?" => (int)$store_id, "EmailAddress=?" => "$email_id");
        $result = $db->update('userinfo', $data, $where);
    }

    /*
     * Add discount Product
     * @param array  $discountData
     * return parameter last inserted id
     */

    public function addDiscountProduct($discountData)
    {
        $db = Zend_Registry::get('Zend_Db');
        $discount_id = $db->insert('discountproduct', $discountData);
        return $discount_id->lastInsertId();
    }

    /*
     * Update discount product data
     * @param $store_id
     * @param $updateDiscountData array
     * return effect row
     */
    public function updateDiscountProductData($store_id, $updateDiscountData)
    {
        $db = Zend_Registry::get('Zend_Db');
        $updated_row = $db->update('discountproduct', $updateDiscountData, 'StoreID=' . $db->quote($store_id));
        return $updated_row;
    }

    /*
     * Edit discount product data by StoreID
     * @param $store_id
     * return @param array
     */
    public function editDiscountProductDataById($store_id)
    {
        $db = Zend_Registry::get('Zend_Db');
        $selectDiscountProduct = "SELECT * FROM discountproduct WHERE storeid='$store_id'";
        $result = $db->fetchAll($selectDiscountProduct);
        if (count($result) != 1) {
            throw new Exception('Invalid discount product ID: ' . $store_id);
        }
        return $result;
    }

    /*
     * Add last minute product
     * @param $lastminuteproductData
     * return last inserted id
     */

    public function addLastMinuteProduct($lastminuteproductData)
    {
        $db = Zend_Registry::get('Zend_Db');
        $insert_id = $db->insert('lastminuteproduct', $lastminuteproductData);
        return $insert_id->lastInsertId();
    }

    /*
     * Member ship discount
     * @params $store_id
     * @params $discountData array
     * return effect or insert row id integer number
     */
    public function memberShipDiscount($store_id, $discountData)
    {
        $db = Zend_Registry::get('Zend_Db');
        $discount = "SELECT membership_discount FROM membershipdiscount WHERE StoreID='$store_id'";
        $result = $db->fetchAll($discount);
        $count = count($result);
        if ($count > 0) {
            return $db->update('membershipdiscount', $discountData, 'StoreID=' . $db->quote($store_id));
        } else {
            $db->insert('membershipdiscount', $discountData);
            return $db->lastInsertId();
        }
    }

}

?>
