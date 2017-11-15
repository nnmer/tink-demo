<?php

namespace App\Service;

use App\Model\BankAccount;
use App\Model\GeneralLedger;
use Doctrine\ORM\EntityManager;

class GeneralLedgerService
{
    const BANK_OWNER_NUMBER = '0000000000';

    const TRANSACTION_TYPE_DEPOSIT = 'deposit';
    const TRANSACTION_TYPE_WITHDRAW = 'withdraw';
    const TRANSACTION_TYPE_SERVICE_FEE = 'service fee';
    const TRANSACTION_TYPE_TRANSFER = 'transfer';

    /** @var EntityManager */
    protected $em;

    /** @var array */
    protected $config;

    public function __construct(EntityManager $em, $config)
    {
        $this->em = $em;
        $this->config = $config;
    }

    /**
     * @param BankAccount $bankAccount
     * @param $amount
     * @return bool | \Exception
     */
    public function recordWithdrawalTransaction(BankAccount $bankAccount, $amount)
    {
        try {
            $this->em->beginTransaction();
            $transaction = new GeneralLedger();
            $transaction
                ->setTransactionType(self::TRANSACTION_TYPE_WITHDRAW)
                ->setAmount(-1 * abs($amount))
                ->setFromOwner($bankAccount->getOwner())
                ->setFromAccountNumber($bankAccount->getNumber());

            $this->em->persist($transaction);

            $bankAccount
                ->withdraw($amount);

            $this->em->flush();
            $this->em->commit();

            return true;

        } catch (\Exception $e) {

            $this->em->rollback();

            return $e;
        }
    }

    /**
     * @param BankAccount $bankAccount
     * @param $amount
     * @return bool | \Exception
     */
    public function recordDepositTransaction(BankAccount $bankAccount, $amount)
    {
        try {
            $this->em->beginTransaction();
            $transaction = new GeneralLedger();
            $transaction
                ->setTransactionType(self::TRANSACTION_TYPE_DEPOSIT)
                ->setAmount(abs($amount))
                ->setToOwner($bankAccount->getOwner())
                ->setToAccountNumber($bankAccount->getNumber());

            $this->em->persist($transaction);

            $bankAccount
                ->deposit($amount);

            $this->em->flush();
            $this->em->commit();

            return true;

        } catch (\Exception $e) {

            $this->em->rollback();

            return $e;
        }
    }

    /**
     * The logic of the transfer process is simplified.
     * It is more complicated process of recording the transactions between customers in real life in a bank.
     * For the test purposes the process left very simple
     *
     * @param BankAccount $fromAccount
     * @param BankAccount $toAccount
     * @param $amount
     */
    public function recordTransferTransaction(BankAccount $fromAccount, BankAccount $toAccount, $amount)
    {
        try {
            $this->em->beginTransaction();
            $transaction = new GeneralLedger();
            $transaction
                ->setTransactionType(self::TRANSACTION_TYPE_TRANSFER)
                ->setAmount(abs($amount))
                ->setFromOwner($fromAccount->getOwner())
                ->setFromAccountNumber($fromAccount->getNumber())
                ->setToOwner($toAccount->getOwner())
                ->setToAccountNumber($toAccount->getNumber());

            $this->em->persist($transaction);

            $fromAccount
                ->withdraw($amount);
            $toAccount
                ->deposit($amount);

            if ($fromAccount->getOwner()->getId() != $toAccount->getOwner()->getId()) {
                $transactionCharges = new GeneralLedger();
                $transactionCharges
                    ->setTransactionType(self::TRANSACTION_TYPE_SERVICE_FEE)
                    ->setAmount(-1 * $this->config['account']['transfer_other_owner_service_charge'])
                    ->setFromOwner($fromAccount->getOwner())
                    ->setFromAccountNumber($fromAccount->getNumber())
                    ->setToAccountNumber(self::BANK_OWNER_NUMBER);

                $this->em->persist($transactionCharges);

                $fromAccount
                    ->withdraw($this->config['account']['transfer_other_owner_service_charge']);
            }

            $this->em->flush();
            $this->em->commit();

            return true;

        } catch (\Exception $e) {

            $this->em->rollback();

            return $e;
        }
    }
}