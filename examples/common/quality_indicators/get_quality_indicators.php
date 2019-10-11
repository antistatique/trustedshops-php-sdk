<?php
/**
 * Example to get all quality indicators for a shop from a TrustedShopsID
 */
include_once "../base.php";

/********************************
 Create the Trustedshops object
 ********************************/
$ts_public = getTrustedShops();

$envs = getEnvVariables();

/********************************
 Make the API call to get quality
 indicators
 ********************************/
$quality_indicators = $ts_public->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/quality');

?>

<?= renderResponse('Get Quality Indicators from a TrustedShops ID', $quality_indicators); ?>
