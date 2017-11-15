<?php

namespace App\Controller\API;

use App\Application;
use App\Exception\NotFoundHttpException;
use App\Exception\ValidationException;
use App\Model\BankAccount;
use App\Model\Request\TransferHTTPRequest;
use Symfony\Component\HttpFoundation\Request;

class BankAccountController
{
    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Application $app)
    {
        $result = $app['bank_account.service']->findAllAccounts();
        return $app->json($app['dto_transformer.bankAccount']->reverseCollection($result));
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function openAction(Application $app, Request $request)
    {
        $result = $app['bank_account.service']->open($request->get('name'), $request->get('identityId'));
        return $app->json($app['dto_transformer.bankAccount']->reverse($result));
    }

    /**
     * @param Application $app
     * @param BankAccount $bankAccount
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function closeAction(Application $app, BankAccount $bankAccount)
    {
        $result = $app['bank_account.service']->close($bankAccount);
        return $app->json($app['dto_transformer.bankAccount']->reverse($result));
    }

    /**
     * @param Application $app
     * @param BankAccount $bankAccount
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function balanceAction(Application $app, BankAccount $bankAccount)
    {
        $result = $app['bank_account.service']->getCurrentBalance($bankAccount);
        return $app->json($result);
    }

    /**
     * @param Application $app
     * @param BankAccount $bankAccount
     * @param $amount
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function withdrawAction(Application $app, BankAccount $bankAccount, $amount)
    {
        $result = $app['bank_account.service']->withdraw($bankAccount, $amount);
        return $app->json($result);
    }

    /**
     * @param Application $app
     * @param BankAccount $bankAccount
     * @param $amount
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function depositAction(Application $app, BankAccount $bankAccount, $amount)
    {
        $result = $app['bank_account.service']->deposit($bankAccount, $amount);
        return $app->json($result);
    }

    /**
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function transferAction(Application $app, Request $request)
    {
        $transferRequest = $app['serializer']->deserialize(json_encode($request->request->all()), TransferHTTPRequest::class, 'json');
        $errors = $app['validator']->validate($transferRequest);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        if (!$fromAccount = $app['orm.em']->getRepository('App\\Model\\BankAccount')->findOneBy(['number'=>$transferRequest->fromAccount])) {
            throw new NotFoundHttpException(sprintf('Account %s not found', $transferRequest->fromAccount));
        }

        if (!$toAccount = $app['orm.em']->getRepository('App\\Model\\BankAccount')->findOneBy(['number'=>$transferRequest->toAccount])) {
            throw new NotFoundHttpException(sprintf('Account %s not found', $transferRequest->toAccount));
        }

        $result = $app['bank_account.service']->transfer($fromAccount, $toAccount, $transferRequest->amount);
        return $app->json([]);
    }

}