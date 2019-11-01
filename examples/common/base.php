<?php
/**
 * Example to get all shops from a TrustedShopsID
 */

\error_reporting(E_ALL);

include_once \dirname(__DIR__) . '/../vendor/autoload.php';
include_once __DIR__ . "/../templates/base.php";

$envs = getEnvVariables();

/********************************
 Create the Trustedshops object
 that is public so credentials
 are not required
 ********************************/
function getTrustedShops() {
  return new Antistatique\TrustedShops\TrustedShops('public', 'v2');
}
