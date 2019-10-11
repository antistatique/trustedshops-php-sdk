<?php
/**
 * Index file for the examples of the SDK
 */

\error_reporting(E_ALL);

include_once "templates/base.php";
?>

<?php if (!isWebRequest()): ?>
  To view this example, run the following command from the root directory of this repository:

    php -S localhost:8000 -t examples/

  And then browse to "localhost:8000" in your web browser
<?php return ?>
<?php endif ?>

<?= pageHeader("TrustedShops API SDK Examples"); ?>

<ul>
  <li><a href="common/shops/get_shops_by_id.php">Get Shops from a TrustedShopsID Example</a></li>
  <li><a href="common/shops/get_shops_by_url.php">Get Shops from an URL Example</a></li>
  <li><a href="common/reviews/get_reviews.php">Get Reviews for a shop from a TrustedShops ID</a></li>
  <li><a href="common/quality_indicators/get_quality_indicators.php">Get Quality Indicators for a shop from a TrustedShops ID</a></li>
</ul>

<?= pageFooter(); ?>