<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Kreait\Firebase\Factory;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

$firebase = (new Factory)
    ->withServiceAccount($_ENV['FIREBASE_CREDENTIALS_PATH'])
    ->create();

$auth = $firebase->getAuth();
