<?php

namespace App\Model\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class TransferHTTPRequest
{
    public $fromAccount;

    public $toAccount;

    public $amount;

    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('fromAccount', new Assert\Length(['min' => 8]));
        $metadata->addPropertyConstraint('fromAccount', new Assert\NotBlank());
        $metadata->addPropertyConstraint('toAccount', new Assert\Length(['min' => 8]));
        $metadata->addPropertyConstraint('toAccount', new Assert\NotBlank());
        $metadata->addPropertyConstraint('amount', new Assert\GreaterThan(0));
        $metadata->addPropertyConstraint('amount', new Assert\NotBlank());
    }

    public function setFromAccount($value)
    {
        $this->fromAccount = $value;
    }

    public function getFromAccount()
    {
        return $this->fromAccount;
    }

    public function setToAccount($value)
    {
        $this->toAccount = $value;
    }

    public function getToAccount()
    {
        return $this->toAccount;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}