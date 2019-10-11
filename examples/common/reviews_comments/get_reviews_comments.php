<?php
/**
 * Example to get all shops from a TrustedShopsID
 */

\error_reporting(E_ALL);

include_once \dirname(__DIR__) . '/../../vendor/autoload.php';
include_once "../../templates/base.php";

$envs = getEnvVariables();

/********************************
 Create the Trustedshops object
 that is public so credentials
 are not required
 ********************************/
$ts_public = new Antistatique\TrustedShops\TrustedShops('public', 'v2');
echo 'shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviews/1/comments/1';

try {
  $reviews_comments = $ts_public->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviews/1/comments/1');
} catch (\Exception $error) {
  print_r($ts_public->getLastResponse());
}

?>

<?= pageHeader("Get Reviews comments for a shop from a TrustedShops ID"); ?>

<? //print_r($reviews_comments); ?>

<?= pageFooter(); ?>