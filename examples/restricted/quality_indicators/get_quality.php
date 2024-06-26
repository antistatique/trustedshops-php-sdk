<?php
/**
 * Example to get all quality indicators from a TrustedShopsID.
 */
include_once '../base.php';

/********************************
 Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

/********************************
 Make the call to get all the quality -
 indicators
 ********************************/
$quality_indicators = $ts_restricted->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/quality');

?>

<?php echo renderResponse('Get Quality from a TrustedShops ID', $quality_indicators); ?>