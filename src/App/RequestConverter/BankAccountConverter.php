<?php

namespace App\RequestConverter;

use App\Exception\NotFoundHttpException;
use Doctrine\Common\Persistence\ObjectManager;

class BankAccountConverter
{
    private $om;

    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function convert($number)
    {
        if (null === $bankAccount = $this->om->getRepository('App\\Model\\BankAccount')->findOneBy(['number' => (string) $number])) {
            throw new NotFoundHttpException(sprintf('BankAccount %s does not exist', $number));
        }

        return $bankAccount;
    }
}