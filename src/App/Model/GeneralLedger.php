<?php
namespace App\Model;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="general_ledger")
 */
class GeneralLedger
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="transaction_type", nullable=false)
     */
    protected $transactionType;

    /**
     * @ORM\Column(type="string", length=36)
     */
    protected $uuid;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=2, nullable=false)
     * @var float
     */
    protected $amount;

    /**
     * @ORM\Column(type="string", name="from_account_number", nullable=true)
     */
    protected $fromAccountNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\AccountOwner")
     * @ORM\JoinColumn(name="from_owner_id", referencedColumnName="id")
     */
    protected $fromOwner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\AccountOwner")
     * @ORM\JoinColumn(name="to_owner_id", referencedColumnName="id")
     */
    protected $toOwner;

    /**
     * @ORM\Column(type="string", name="to_account_number", nullable=true)
     */
    protected $toAccountNumber;

    /**
     * @ORM\Column(type="decimal", name="service_charge_amount", precision=16, scale=2, nullable=false, options={"default":0})
     * @var float
     */
    protected $serviceChargeAmount;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $created;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();;
        $this->created = new \DateTime();
        $this->serviceChargeAmount = 0;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return GeneralLedger
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set serviceChargeAmount
     *
     * @param string $serviceChargeAmount
     *
     * @return GeneralLedger
     */
    public function setServiceChargeAmount($serviceChargeAmount)
    {
        $this->serviceChargeAmount = $serviceChargeAmount;

        return $this;
    }

    /**
     * Get serviceChargeAmount
     *
     * @return string
     */
    public function getServiceChargeAmount()
    {
        return $this->serviceChargeAmount;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return GeneralLedger
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set fromOwner
     *
     * @param \App\Model\AccountOwner $fromOwner
     *
     * @return GeneralLedger
     */
    public function setFromOwner(\App\Model\AccountOwner $fromOwner = null)
    {
        $this->fromOwner = $fromOwner;

        return $this;
    }

    /**
     * Get fromOwner
     *
     * @return \App\Model\AccountOwner
     */
    public function getFromOwner()
    {
        return $this->fromOwner;
    }

    /**
     * Set toOwner
     *
     * @param \App\Model\AccountOwner $toOwner
     *
     * @return GeneralLedger
     */
    public function setToOwner(\App\Model\AccountOwner $toOwner = null)
    {
        $this->toOwner = $toOwner;

        return $this;
    }

    /**
     * Get toOwner
     *
     * @return \App\Model\AccountOwner
     */
    public function getToOwner()
    {
        return $this->toOwner;
    }

    /**
     * Set fromAccountNumber
     *
     * @param string $fromAccountNumber
     *
     * @return GeneralLedger
     */
    public function setFromAccountNumber($fromAccountNumber = null)
    {
        $this->fromAccountNumber = $fromAccountNumber;

        return $this;
    }

    /**
     * Get fromAccountNumber
     *
     * @return string
     */
    public function getFromAccountNumber()
    {
        return $this->fromAccountNumber;
    }

    /**
     * Set toAccountNumber
     *
     * @param string $toAccount
     *
     * @return GeneralLedger
     */
    public function setToAccountNumber( $toAccountNumber = null)
    {
        $this->toAccountNumber = $toAccountNumber;

        return $this;
    }

    /**
     * Get toAccountNumber
     *
     * @return string
     */
    public function getToAccountNumber()
    {
        return $this->toAccountNumber;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     *
     * @return GeneralLedger
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Set transactionType
     *
     * @param string $transactionType
     *
     * @return GeneralLedger
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * Get transactionType
     *
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }
}
