<?php
/**
 * Example to get all reviews for a shop from a TrustedShopsID
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
$reviews = $ts_public->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviews');

?>

<?= pageHeader("Get Reviews for a shop from a TrustedShops ID"); ?>

<pre>
<?= var_dump($reviews); ?>
</pre>
<?= pageFooter(); ?>