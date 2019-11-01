<?php
/**
 * Example to get retailers of the shops from a TrustedShopsID
 */
include_once "../base.php";

/********************************
 Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

/********************************
 Make the call to get all the retailers
 ********************************/
$retailers = $ts_restricted->get('retailers/shops');

?>

<?= renderResponse('Get Retailers from a TrustedShops ID', $retailers); ?>