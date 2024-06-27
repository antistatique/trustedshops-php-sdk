<?php
/**
 * Example to get reviews from a TrustedShopsID.
 */
include_once '../base.php';

/********************************
 Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

/********************************
 Make the call to get all the reviews
 ********************************/
$reviews = $ts_restricted->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviews');

?>

<?php echo renderResponse('Get Reviews with the TrustedShops ID', $reviews); ?>