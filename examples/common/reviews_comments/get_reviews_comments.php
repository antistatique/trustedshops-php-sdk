<?php
/**
 * Example to read comment given review Id and comment Id from a TrustedShopsID
 */
include_once "../base.php";

/********************************
Create the Trustedshops object
 ********************************/
$ts_public = getTrustedShops();

$envs = getEnvVariables();

/********************************
Read comment given review Id and comment Id
 ********************************/
$reviews_comments = $ts_public->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviews/3093400568a676f378d191487a4ed7f5/comments/12345');
?>

<?= renderResponse('Read comment given review Id and comment Id', $reviews_comments); ?>

