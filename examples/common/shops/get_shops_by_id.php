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

/********************************
 Make the API call
 For this call you need your TrustedShopsID
 ********************************/
$shops = $ts_public->get('shops/'.$envs['TRUSTEDSHOPS_ID']);

?>

<?= pageHeader("Get Shops from a TrustedShops ID"); ?>

<pre>
<?= var_dump($shops); ?>
</pre>

<?= pageFooter(); ?>