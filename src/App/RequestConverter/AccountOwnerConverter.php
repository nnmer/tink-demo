<?php

namespace App\RequestConverter;

use App\Exception\NotFoundHttpException;
use Doctrine\Common\Persistence\ObjectManager;

class AccountOwnerConverter
{
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function convert($id)
    {
        if (null === $accountOwner = $this->om->find('App\\Model\\AccountOwner', (int) $id)) {
            throw new NotFoundHttpException(sprintf('AccountOwner %d does not exist', $id));
        }

        return $accountOwner;
    }
}