<?php


class AddressRepository
{
    public function getCustomerCount($id)
    {
        $dbHelper = new DbHelper();
        $dbHelper->bind("id", $id);
        $result = $dbHelper->runSQL('selct * from customer left join customer_address on customer.id=customer_address.customer_id where customer_id=:id');
        return $result;
    }

}


