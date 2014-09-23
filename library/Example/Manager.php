<?php


class Example_Manager {

    /**
     * Returns list of all products in database
     *
     * @return array
     */
    public function getProducts()
    {
        $db = Zend_Registry::get('Zend_Db');
        $sql = "SELECT * FROM products";
        return $db->fetchAll($sql);
    }

    /**
     * Returns specified product in database
     *
     * @param integer $id
     * @return array|Exception
     */
    public function getProduct($id)
    {
        if (!Zend_Validate::is($id, 'Int')) {
            throw new Example_Exception('Invalid input');
        }
        $db = Zend_Registry::get('Zend_Db');
        $sql = "SELECT * FROM products WHERE id = '$id'";
        $result = $db->fetchAll($sql);
        if (count($result) != 1) {
            throw new Exception('Invalid product ID: ' . $id);
        }
        return $result;
    }
    /**
     * Adds new product to database
     *
     * @param array $data array of data values with keys -> table fields
     * @return integer id of inserted product
     */
    public function addProduct($data)
    {
        $db = Zend_Registry::get('Zend_Db');
        $db->insert('products', $data);
        return $db->lastInsertId();
    }
    /**
     * Deletes product from database
     *
     * @param integer $id
     * @return integer number of products deleted
     */
    public function deleteProduct($id)
    {
        $db = Zend_Registry::get('Zend_Db');
        $count = $db->delete('products', 'id=' . $db->quote($id));
        return $count;
    }

    /**
     * Updates product in database
     *
     * @param integer $id
     * @param array $data
     * @return integer number of products updated
     */
    public function updateProduct($id, $data)
    {
        $db = Zend_Registry::get('Zend_Db');
        $count = $db->update('products', $data, 'id=' . $db->quote($id));
        return $count;
    }

    /**
     * Adds new user to database
     *
     * @param array $data array of data values with keys -> table fields
     * @return integer id of inserted product
     */
    public function addUser($data)
    {
        $db = Zend_Registry::get('Zend_Db');
        $db->insert('users', $data);
        return $db->lastInsertId();
    }

}