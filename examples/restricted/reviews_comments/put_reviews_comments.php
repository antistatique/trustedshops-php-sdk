<?php
/**
 * Example to Update shop retailer comment given review Id and commend Id from a TrustedShopsID
 */
include_once "../base.php";

/********************************
Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

/********************************
Update shop retailer comment given review Id and commend Id
 ********************************/
$reviews_comments = $ts_restricted->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviews/3093400568a676f378d191487a4ed7f5/comments/12345', [
    'informBuyerForShopComment' => FALSE,
    'comment' => 'Nunc iaculis venenatis duis at sodales dis vivamus sit aptent, euismod hac pharetra lorem magnis vestibulum urna quis, class volutpat ultricies proin netus nam dui convallis.'
]);
?>

<?= renderResponse('Update shop retailer comment given review Id and commend Id', $reviews_comments); ?>

