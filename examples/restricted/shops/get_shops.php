<?php
/**
 * Example to get shops from a TrustedShopsID.
 */
include_once '../base.php';

/********************************
 Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

/********************************
 Make the call to get all the shops
 ********************************/
$shops = $ts_restricted->get('shops/'.$envs['TRUSTEDSHOPS_ID']);

?>

<?php echo renderResponse('Get Shops with the TrustedShops ID', $shops); ?>