<?php
/**
 * Example to get all quality indicators complaints from a TrustedShopsID.
 */
include_once '../base.php';

/********************************
 Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

/********************************
 Make the call to get all the quality -
 indicators complaints
 ********************************/
$quality_indicators = $ts_restricted->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/quality/complaints');

?>

<?php echo renderResponse('Get Quality Complaints from a TrustedShops ID', $quality_indicators); ?>