<?php
/**
 * Example to get all quality indicators for a shop from a TrustedShopsID
 */

\error_reporting(E_ALL);

include_once \dirname(__DIR__) . '/../vendor/autoload.php';
include_once __DIR__ . "/../templates/base.php";

function getTrustedShopsRestricted() {
  $envs = getEnvVariables();
  $ts_restricted = new Antistatique\TrustedShops\TrustedShops('restricted', 'v2', 'api-qa');
  $ts_restricted->setApiCredentials($envs['TRUSTEDSHOPS_LOGIN'], $envs['TRUSTEDSHOPS_PASS']);

  return $ts_restricted;
}