<?php

//
// Twig to PHP compiler.
//
// Usage: php bin/parse-twig.php
//
// Start Poedit and open the file: resources/locale/*.po
// Open menu: Catalog > Properties > Source Path
// Add source path: tmp/twig-cache
//
// Open tab: Sources keywords
// Add keyword: __
// Click 'Ok' to store the settings
//
// Click button 'Update form source' to extract the template strings.
// Translate the text and save the file.
//

use App\Application;
use App\Utility\Configuration;
use Odan\Twig\TwigCompiler;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Slim\Views\TwigRuntimeLoader;

require_once __DIR__ . '/../vendor/autoload.php';

define('APP_ENV', 'integration');

$app = Application::boostrap();

$settings = $app->getContainer()->get(Configuration::class)->get('twig');
$templatePath = (string)$settings['path'];
$cachePath = (string)$settings['cache_path'];

$twig = $app->getContainer()->get(Twig::class)->getEnvironment();

$routeParser = $app->getRouteCollector()->getRouteParser();
$basePath = $app->getBasePath();
$factory = new ServerRequestFactory();
$request = $factory->createServerRequest('GET', '/');
$runtimeLoader = new TwigRuntimeLoader($routeParser, $request->getUri(), $basePath);
$twig->addRuntimeLoader($runtimeLoader);
$twig->addExtension(new TwigExtension());

$compiler = new TwigCompiler($twig, $cachePath, true);
$compiler->compile();

echo "Done\n";

return 0;