<?php


class AddressHelper
{
    const PACKSTATION = "packstation";
    const OFFICE = "office";
    const HOME = "home";
    const UNKNOWN_ADDRESS = 'unknown address_type or no customer found';
    private $repository;

    /**
     * AddressHelper constructor.
     */
    public function __construct(AddressRepository $repository)
    {
        $this->repository;
    }


    /**
     * This function identifies the type of the address of the given customer
     *
     * @param int $customerId
     * @return mixed
     */
    public function getAddress(Request $request, ?int $customerId = null)
    {
        $error = null;
        $customerId = $request->query->get('id');
        $customerCountIsMoreThanZero = ($this->repository->getCustomerCount($customerId) > 0);
        $requestedType = strtolower($request->query->get('type'));
        $addressType = "";
        if($customerCountIsMoreThanZero)
            $addressType = $this->getAddressType($requestedType);
        else if (!$customerCountIsMoreThanZero || $addressType == null){
            $error = self::UNKNOWN_ADDRESS;
        }
        
        $is_packagestation_enabled = ($requestedType == self::PACKSTATION);

        return $this->prepareTheResult($error, $is_packagestation_enabled, $addressType, $customerId);
    }


    public function getAddressType($requestedType){
        if ( $requestedType === self::PACKSTATION) {
            return 'type_packstation';
        } else if ($requestedType === self::OFFICE) {
            return 'type_office';
        } else if ($requestedType === self::HOME) {
            return 'type_home';
        }
        return null;
    }

    
    public function prepareTheResult($error, $is_packagestation_enabled, $addressType, $customerId){
        $result = [];
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
}
