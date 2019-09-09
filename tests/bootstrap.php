<?php

\error_reporting(E_ALL);

include_once \dirname(__DIR__) . '/vendor/autoload.php';

if (!\class_exists('Symfony\Component\Dotenv\Dotenv')) {
  throw new \RuntimeException('You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
}

$env =  __DIR__ . '/../.env';

if (file_exists($env)) {
  $dotenv = new Symfony\Component\Dotenv\Dotenv($env);
  $dotenv->load( __DIR__ . '/../.env');
}

