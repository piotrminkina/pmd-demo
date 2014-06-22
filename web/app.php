<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../var/bootstrap.php.cache';

// Use APC for autoloading to improve performance, but if extension loaded.
if (extension_loaded('apc')) {
    $apcPrefix = str_replace(array(' ', '.'), '_', strtolower(basename(dirname(__DIR__))));
    $apcLoader = new ApcClassLoader($apcPrefix, $loader);
    $loader->unregister();
    $apcLoader->register(true);
}

require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
$kernel = new AppCache($kernel);

Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
