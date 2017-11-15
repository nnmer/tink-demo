<?php

namespace App\Service;

use App\Model\BankAccount;

class BankAccountDTOTransformer implements DTOTransformerInterface
{

    public function transform($data, $object)
    {
        // TODO: Implement transform() method.
    }

    /**
     * @param BankAccount $object
     * @return array
     */
    public function reverse($object)
    {
        return [
            'number'    => $object->getNumber(),
            'balance'   => $object->getBalance(),
            'currency'  => $object->getCurrency(),
            'active'    => $object->isActive(),
            'owner'     => [
                'id'    => $object->getOwner()->getId(),
                'name'  => $object->getOwner()->getName()
            ]
        ];
    }

    /**
     * @param $collection
     * @return array
     */
    public function reverseCollection($collection)
    {
        $data = [];
        foreach ($collection as $item) {
            $data[] = $this->reverse($item);
        }

        return $data;
    }
}