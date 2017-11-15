<?php

namespace App\Service;

use App\Model\AccountOwner;
use Doctrine\ORM\EntityManager;

class AccountOwnerService
{
    /** @var EntityManager */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param AccountOwner $accountOwner
     * @return mixed
     */
    public function isTransferApproved(AccountOwner $accountOwner)
    {
        if (! $accountOwner->isActive()) {
            return false;
        }

        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://handy.travel/test/success.json');
        if (200 != $res->getStatusCode() ||
            null === $result = json_decode($res->getBody()->getContents(),true)) {

            throw new \LogicException('Somethings bad happen, we are investigating, please try again later', 500);
        }

        return $result['status'] == 'success';
    }
}