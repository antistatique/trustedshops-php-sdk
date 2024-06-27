<?php
/**
 * Example to get all shops from a TrustedShopsID.
 */
include_once '../base.php';

/********************************
 Create the Trustedshops object
 ********************************/
$ts_public = getTrustedShops();

$envs = getEnvVariables();

/********************************
 Make the API call to get shops
 from the TS ID
 ********************************/
$shops = $ts_public->get('shops/'.$envs['TRUSTEDSHOPS_ID']);

?>

<?php echo renderResponse('Get Shops with the TrustedShops ID', $shops); ?>
