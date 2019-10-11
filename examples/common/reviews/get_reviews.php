<?php
/**
 * Example to get all reviews from a TrustedShopsID
 */
include_once "../base.php";

/********************************
 Create the Trustedshops object
 ********************************/
$ts_public = getTrustedShops();

$envs = getEnvVariables();

/********************************
 Make the API call to get all reviews
 ********************************/
$reviews = $ts_public->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviews');

?>

<?= renderResponse('Get Reviews from a TrustedShops ID', $reviews); ?>
