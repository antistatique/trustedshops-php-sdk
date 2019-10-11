<?php
/**
 * Example to get all shops from an url
 */
include_once "../base.php";

/********************************
 Create the Trustedshops object
 ********************************/
$ts_public = getTrustedShops();

/********************************
 Make the API call to get all shops
 by URL
 ********************************/
$shops = $ts_public->get('shops', ['url' => 'demoshop.trustedshops.com']);

?>

<?= renderResponse('Get Shops from an URL', $shops); ?>
