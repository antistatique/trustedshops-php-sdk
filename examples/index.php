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

<h2>Common API</h2>

<ul>
  <li><a href="common/shops/get_shops_by_id.php">Get Shops from a TrustedShopsID Example</a></li>
  <li><a href="common/shops/get_shops_by_url.php">Get Shops from an URL Example</a></li>
  <li><a href="common/reviews/get_reviews.php">Get Reviews from a TrustedShops ID</a></li>
  <li><a href="common/reviews_comments/get_reviews_comments.php">Read comment of a given review Id by comment Id</a></li>
  <li><a href="common/quality_indicators/get_quality_indicators.php">Get Quality Indicators from a TrustedShops ID</a></li>
</ul>

<h2>Restricted API</h2>

<ul>
  <li><a href="restricted/shops/get_shops.php">Get Shops from a TrustedShopsID Example</a></li>
  <li><a href="restricted/reviews/get_reviews.php">Get Reviews from a TrustedShopsID Example</a></li>
  <li><a href="restricted/reviews_comments/get_reviews_comments.php">Read comment given review Id and comment Id</a></li>
  <li><a href="restricted/reviews_comments/post_reviews_comments.php">Create shop retailer comment given review Id</a></li>
  <li><a href="restricted/reviews_comments/put_reviews_comments.php">Update shop retailer comment given review Id and commend Id</a></li>
  <li><a href="restricted/retailer_shops/get_retailers.php">Get Retailers from a TrustedShopsID Example</a></li>
  <li><a href="restricted/quality_indicators/get_quality.php">Get Quality Indicators from a TrustedShopsID Example</a></li>
  <li><a href="restricted/quality_indicators/get_quality_complaints.php">Get Quality Indicators Complaints from a TrustedShopsID Example</a></li>
  <li><a href="restricted/quality_indicators/get_quality_reviews.php">Get Quality Indicators Reviews from a TrustedShopsID Example</a></li>
  <li><a href="restricted/benchmarks/get_benchmarks.php">Get Benchmarks from a TrustedShopsID Example</a></li>
  <li><a href="restricted/retailer_shops/get_retailers.php">Get Retailers from a TrustedShopsID Example</a></li>
  <li><a href="restricted/reviews_collector/post_review_collector.php">Post Review Collector from a TrustedShops ID Example</a></li>
  <li><a href="restricted/trigger/post_reviews_trigger.php">Trigger a Review from a TrustedShopsID Example</a></li>
</ul>

<?= pageFooter(); ?>
