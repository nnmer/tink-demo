<?php

require_once __DIR__.'/../vendor/autoload.php';

use App\Controller\Provider\BankAccountProvider;
use App\Service\BankAccountService;
use App\Service\GeneralLedgerService;
use App\Exception\ValidationException;
use App\Service\AccountOwnerService;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use App\RequestConverter\BankAccountConverter;
use App\Service\BankAccountDTOTransformer;

$env = getenv('APP_ENV') ?: 'dev';

ErrorHandler::register();
ExceptionHandler::register($env == 'dev');

$app = new App\Application();
$app['debug'] = $env == 'dev';


$app->register(new Silex\Provider\SerializerServiceProvider());

// Mount Error handling
$app->error(function (\Exception $e, Request $request, $code) use ($app) {

    if ($e instanceof ValidationException) {
        $response = $e->toArray();
        $code = $e->getCode();
    }else{
        $response = [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
        ];
    }

    if ($app['debug']){
        $response['line'] = $e->getFile() .":". $e->getLine();

        $previous = $e->getPrevious();
        if ($previous) {
            $response['error'] = $previous->getMessage(). ' at '. $previous->getFile().':'.$previous->getLine();
        }
        $response['trace'] = $e->getTrace();
    }

    return $app->json($response,$code);
});
$app->register(new Silex\Provider\ValidatorServiceProvider());


$app->register(new HelloMotto\Silex\Config\ConfigServiceProvider(), [
    'config.files' => [
        __DIR__."/config/config_".$env.".yml",
    ]
]);

// Mount DB
$app->register(new Silex\Provider\DoctrineServiceProvider(), [
    'db.options' => $app['parameters']['db']
]);

$app->register(new DoctrineOrmServiceProvider, [
    'orm.default_cache' => 'array',
    'orm.proxies_dir' => __DIR__.'/../var/cache/'.$env.'/doctrine/proxy',
    'orm.em.options' => [
        'mappings' => [
            [
                'type' => 'annotation',
                'use_simple_annotation_reader' => false,
                'namespace' => 'App\Model',
                'path' => __DIR__.'/../src/App/Model/',
            ]
        ],
    ],
]);

// Accepting JSON requests
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});


// Mount services
$app['converter.bankAccount'] = function () use ($app) {
    return new BankAccountConverter($app['orm.em']);
};

$app['dto_transformer.bankAccount'] = function () use ($app) {
    return new BankAccountDTOTransformer();
};

$app['account_owner.service'] = function () use ($app) {
    return new AccountOwnerService($app['orm.em']);
};

$app['general_ledger.service'] = function () use ($app) {
    return new GeneralLedgerService($app['orm.em'], $app['config']);
};

$app['bank_account.service'] = function () use ($app) {
    return new BankAccountService($app['orm.em'], $app['account_owner.service'], $app['validator'], $app['general_ledger.service'], $app['config']);
};

// Mount routes
$app->mount('/api/accounts', new BankAccountProvider());

$app->get('/',function () use ($app) {
    return $app->json('OK');
});