<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="account_owner")
 */
class AccountOwner
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
     * @ORM\Column(type="string", name="name", nullable=false)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", name="identity_id", unique=true, nullable=false)
     * @var string
     */
    protected $identityId;

    /**
     * @ORM\Column(type="datetime", name="created_at", nullable=false)
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Model\BankAccount", mappedBy="owner")
     */
    protected $bankAccounts;

    // could me much more other different required fields

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->bankAccounts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->name} [{$this->identityId}]";
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
     * Set name
     *
     * @param string $name
     *
     * @return AccountOwner
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Set identityId
     *
     * @param string $identityId
     *
     * @return AccountOwner
     */
    public function setIdentityId($identityId)
    {
        $this->identityId = $identityId;

        return $this;
    }

    /**
     * Get identityId
     *
     * @return string
     */
    public function getIdentityId()
    {
        return $this->identityId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AccountOwner
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
     * Add bankAccount
     *
     * @param \App\Model\BankAccount $bankAccount
     *
     * @return AccountOwner
     */
    public function addBankAccount(\App\Model\BankAccount $bankAccount)
    {
        $this->bankAccounts[] = $bankAccount;

        return $this;
    }

    /**
     * Remove bankAccount
     *
     * @param \App\Model\BankAccount $bankAccount
     */
    public function removeBankAccount(\App\Model\BankAccount $bankAccount)
    {
        $this->bankAccounts->removeElement($bankAccount);
    }

    /**
     * Get bankAccounts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBankAccounts()
    {
        return $this->bankAccounts;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return true;
    }
}
