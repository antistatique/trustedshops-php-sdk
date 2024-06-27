<?php
/**
 * Example to read comment given review Id and comment Id from a TrustedShopsID.
 */
include_once '../base.php';

/********************************
Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

/********************************
Read comment given review Id and comment Id
 ********************************/
$reviews_comments = $ts_restricted->get('shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviews/3093400568a676f378d191487a4ed7f5/comments/12345');
?>

<?php echo renderResponse('Read comment given review Id and comment Id', $reviews_comments); ?>

