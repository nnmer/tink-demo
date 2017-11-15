<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bank_account")
 */
class BankAccount
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="bigint")
     * @ORM\GeneratedValue()
     *
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="number", nullable=false, unique=true)
     *
     * @var string
     */
    protected $number;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=2, options={"default":0})
     *
     * @var float
     */
    protected $balance;

    /**
     * @ORM\Column(type="string", length=3, nullable=false, options={"default":"HKD"})
     * @var string
     */
    protected $currency;

    /**
     * @ORM\ManyToOne(targetEntity="App\Model\AccountOwner", inversedBy="bankAccounts")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $owner;

    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @ORM\Column(name="closed_at", type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    protected $closedAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->balance = 0;
        $this->currency= 'HKD';
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
     * Set number
     *
     * @param string $number
     *
     * @return BankAccount
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set balance
     *
     * @param string $balance
     *
     * @return BankAccount
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return BankAccount
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return BankAccount
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set closedAt
     *
     * @param \DateTime $closedAt
     *
     * @return BankAccount
     */
    public function setClosedAt($closedAt)
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    /**
     * Get closedAt
     *
     * @return \DateTime
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return null === $this->closedAt;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return null !== $this->closedAt;
    }

    /**
     * Set owner
     *
     * @param \App\Model\AccountOwner $owner
     *
     * @return BankAccount
     */
    public function setOwner(\App\Model\AccountOwner $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \App\Model\AccountOwner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return BankAccount
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function withdraw($amount)
    {
        $this->balance -= $amount;

        return $this;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function deposit($amount)
    {
        $this->balance += $amount;

        return $this;
    }
}
