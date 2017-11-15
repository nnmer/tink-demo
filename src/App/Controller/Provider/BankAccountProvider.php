<?php

namespace App\Controller\Provider;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;

class BankAccountProvider implements ControllerProviderInterface
{

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', "App\\Controller\\API\\BankAccountController::indexAction");

        $controllers->post('/open', "App\\Controller\\API\\BankAccountController::openAction");

        $controllers->post('/transfer', "App\\Controller\\API\\BankAccountController::transferAction");

        $controllers->post('/{bankAccount}/close', "App\\Controller\\API\\BankAccountController::closeAction")
            ->convert('bankAccount', 'converter.bankAccount:convert')
        ;
        $controllers->get('/{bankAccount}/balance', "App\\Controller\\API\\BankAccountController::balanceAction")
            ->convert('bankAccount', 'converter.bankAccount:convert')
        ;

        $controllers->get('/{bankAccount}/withdraw/{amount}', "App\\Controller\\API\\BankAccountController::withdrawAction")
            ->assert('amount', '\d+')
            ->convert('bankAccount', 'converter.bankAccount:convert')
        ;

        $controllers->put('/{bankAccount}/deposit/{amount}', "App\\Controller\\API\\BankAccountController::depositAction")
            ->assert('amount', '\d+')
            ->convert('bankAccount', 'converter.bankAccount:convert')
        ;

        return $controllers;
    }
}