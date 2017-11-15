<?php

// retrieve EntityManager
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__.'/../app/bootstrap.php';

$isDevMode = $app['debug'];

$paths = $app['orm.em.options']['mappings'][0]['path'];
$config = Setup::createAnnotationMetadataConfiguration([$paths], $isDevMode, null, null, false);
$entityManager = EntityManager::create($app['db.options'], $config);

return ConsoleRunner::createHelperSet($entityManager);