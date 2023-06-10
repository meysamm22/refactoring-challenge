<?php

class AdressHelper
{

    /**
     * This function identifies the type of the address of the given customer
     *
     * @param int $customerId
     * @return mixed
     */
    public function getAddress(Request $request, ?int $customerId = null)
    {
        // $error variable initialization.
        $error = null;
        $is_packagestation_enabled = true;
        $customerId = $request->query->get('id');

        if (($this->getCustomerCount($customerId) > 0) && (strtolower($request->query->get('type')) === 'packstation')) {
            $addressType = 'type_packstation';
            $is_packagestation_enabled = true;
        } else if (($this->getCustomerCount($customerId) > 0) && (strtolower($request->query->get('type')) === 'office')) {
            $addressType = 'type_office';
            $is_packagestation_enabled = false;
        } else if (($this->getCustomerCount($customerId) > 0) && (strtolower($request->query->get('type')) === 'home')) {
            $addressType = 'type_home';
            $is_packagestation_enabled = false;
        } else {
            $error = 'unknown address_type or no customer found';
            $is_packagestation_enabled = false;
        }




        if (!$error) {
            $result = array('address_type' => $addressType, 'id' => $customerId, 'success' => true);

            if ($is_packagestation_enabled = true) {
                $result['packstation'] = 'yes';
            }
        } else {
            $result = array('success' => false, 'error' => $error);
        }

        return $result;
    }



    public function getCustomerCount($id)
    {
        $dbHelper = new DbHelper();
        $result  = $dbHelper->runSQL('selct * from customer left join customer_address on customer.id=customer_address.customer_id where customer_id=' . $id);
        return $result;
    }
}
