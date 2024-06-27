<?php
/**
 * Example to get Benchmarks from a TrustedShopsID.
 */
include_once '../base.php';

/********************************
 Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

/********************************
 Make the call to get all the benchmarks
 ********************************/
$benchmarks = $ts_restricted->get('shops/benchmarks', ['tsId' => $envs['TRUSTEDSHOPS_ID']]);

?>

<?php echo renderResponse('Get Shops Benchmarks from a TrustedShops ID', $benchmarks); ?>