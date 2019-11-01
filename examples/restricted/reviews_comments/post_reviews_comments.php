<?php
/**
 * Example to Create shop retailer comment given review Id from a TrustedShopsID
 */
include_once "../base.php";

/********************************
Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

/********************************
Create shop retailer comment given review Id
 ********************************/
$reviews_comments = $ts_restricted->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviews/3093400568a676f378d191487a4ed7f5/comments', [
    'informBuyerForShopComment' => FALSE,
    'comment' => 'Nunc iaculis venenatis duis at sodales dis vivamus sit aptent, euismod hac pharetra lorem magnis vestibulum urna quis, class volutpat ultricies proin netus nam dui convallis.'
]);
?>

<?= renderResponse('Create shop retailer comment given review Id', $reviews_comments); ?>

