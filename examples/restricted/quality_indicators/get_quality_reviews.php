<?php
/**
 * Example to get all quality indicators reviews from a TrustedShopsID
 */
include_once "../base.php";

/********************************
 Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

/********************************
 Make the call to get all the quality -
 indicators reviews
 ********************************/
$quality_indicators = $ts_restricted->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/quality/reviews');

?>

<?= renderResponse('Get Quality Reviews from a TrustedShops ID', $quality_indicators); ?>