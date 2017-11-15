<?php

namespace App\Service;

use App\Exception\ValidationException;
use App\Model\AccountOwner;
use App\Model\BankAccount;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BankAccountService
{
    const AccountNumberPrefix = 40093;

    /** @var EntityManager */
    protected $em;

    /** @var ValidatorInterface */
    protected $validator;

    /** @var GeneralLedgerService */
    protected $generalLedgerService;

    /** @var AccountOwnerService */
    protected $accountOwnerService;

    /** @var array */
    protected $config;

    public function __construct(
        EntityManager $em,
        AccountOwnerService $accountOwnerService,
        ValidatorInterface $validator,
        GeneralLedgerService $generalLedgerService,
        $config)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->generalLedgerService = $generalLedgerService;
        $this->config = $config;
        $this->accountOwnerService = $accountOwnerService;
    }

    /**
     * @param $name
     * @param $identityId
     * @return BankAccount
     */
    public function open($name, $identityId)
    {
        $constraint = new Assert\Collection([
            'name' => new Assert\NotBlank(),
            'identityId' => new Assert\NotBlank(),
        ]);
        $errors = $this->validator->validate([
            'name' => $name,
            'identityId' => $identityId
        ], $constraint);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $owner = $this->findOwnerOrCreate($name, $identityId);
        $account = $this->createNewAccount($owner);
        return $account;
    }

    /**
     * @param BankAccount $bankAccount
     * @return BankAccount
     */
    public function close(BankAccount $bankAccount)
    {
        $bankAccount->setClosedAt(new \DateTime());
        $this->em->persist($bankAccount);
        $this->em->flush();
        return $bankAccount;
    }

    /**
     * @param BankAccount $bankAccount
     * @param $amount
     * @return array
     */
    public function withdraw(BankAccount $bankAccount, $amount)
    {
        if ($amount <= 0) {
            throw new ValidationException('The amount to withdraw should be more that 0');
        }

        if ($bankAccount->isClosed()) {
            throw new ValidationException(sprintf('Account %s is closed, cannot withdraw money',$bankAccount->getNumber()));
        }

        if ($bankAccount->getBalance() - $amount < 0) {
            throw new ValidationException(sprintf('Account %s has insufficient balance',$bankAccount->getNumber()));
        }

        if (true !== $e = $this->generalLedgerService->recordWithdrawalTransaction($bankAccount, $amount)) {
            throw new \LogicException('Something happens, cannot process the transaction, please try again later', 500, $e);
        }

        return [
            'number'  => $bankAccount->getNumber(),
            'balance' => $bankAccount->getBalance()
        ];
    }

    /**
     * @param BankAccount $bankAccount
     * @param $amount
     * @return array
     */
    public function deposit(BankAccount $bankAccount, $amount)
    {
        if ($amount <= 0) {
            throw new ValidationException('The amount to deposit should be more than 0');
        }

        if ($bankAccount->isClosed()) {
            throw new ValidationException(sprintf('Account %s is closed, cannot deposit money',$bankAccount->getNumber()));
        }

        if (true !== $e = $this->generalLedgerService->recordDepositTransaction($bankAccount, $amount)) {
            throw new \LogicException('Something happens, cannot process the transaction, please try again later', 500, $e);
        }

        return [
            'number'  => $bankAccount->getNumber(),
            'balance' => $bankAccount->getBalance()
        ];
    }

    /**
     * @param BankAccount $bankAccount
     * @return array
     */
    public function getCurrentBalance(BankAccount $bankAccount)
    {
        if ($bankAccount->isClosed()) {
            throw new ValidationException(sprintf('Account %s is closed, cannot know it\'s balance',$bankAccount->getNumber()));
        }

        return [
            'number'  => $bankAccount->getNumber(),
            'balance' => $bankAccount->getBalance()
        ];
    }

    /**
     * @param BankAccount $fromAccount
     * @param BankAccount $toAccount
     * @param $amount
     * @return bool
     */
    public function transfer(BankAccount $fromAccount, BankAccount $toAccount, $amount)
    {
        if ($amount <= 0) {
            throw new ValidationException('The amount to transfer should be more than 0');
        }

        if ($fromAccount->isClosed()) {
            throw new ValidationException(sprintf('Account %s is closed, cannot transfer from closed account', $fromAccount->getNumber()));
        }
        if ($toAccount->isClosed()) {
            throw new ValidationException(sprintf('Account %s is closed, cannot transfer to closed account', $toAccount->getNumber()));
        }
        if ($fromAccount->getBalance() - $amount < 0) {
            throw new ValidationException(sprintf('Account %s has insufficient balance', $fromAccount->getNumber()));
        }

        if (!$this->accountOwnerService->isTransferApproved($fromAccount->getOwner())) {
            throw new ValidationException(sprintf('The transfer from %s is not allowed', $fromAccount->getOwner()));
        }


        if (true !== $e = $this->generalLedgerService->recordTransferTransaction($fromAccount, $toAccount,  $amount)) {
            throw new \LogicException('Something happens, cannot process the transaction, please try again later', 500, $e);
        }

        return true || false;
    }

    /**
     * @return array
     */
    public function findAllAccounts()
    {
        return $this->em->getRepository('App\\Model\\BankAccount')
            ->findAll();
    }

    /**
     * @param AccountOwner $accountOwner
     * @return array
     */
    public function findAllAccountsBy(AccountOwner $accountOwner)
    {
        return $this->em->getRepository('App\\Model\\BankAccount')
            ->findBy([
                'accountOwner' => $accountOwner
            ]);
    }

    /**
     * @param $name
     * @param $identityId
     * @return AccountOwner
     */
    private function findOwnerOrCreate($name, $identityId)
    {
        $owner = $this->em->getRepository('App\\Model\\AccountOwner')
            ->findOneBy([
                'identityId' => $identityId
            ]);

        if (!$owner){
            $owner = new AccountOwner();
            $owner
                ->setName($name)
                ->setIdentityId($identityId)
            ;
            $this->em->persist($owner);
            $this->em->flush();
        }

        return $owner;
    }

    /**
     * @param AccountOwner $owner
     * @return BankAccount
     */
    private function createNewAccount(AccountOwner $owner)
    {
        $account = new BankAccount();
        $account
            ->setOwner($owner)
            ->setNumber(self::AccountNumberPrefix . time())
        ;
        $owner->addBankAccount($account);

        $this->em->persist($account);
        $this->em->flush();

        return $account;
    }
}