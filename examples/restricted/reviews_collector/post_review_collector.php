<?php
/**
 * Example to post a reviewcollector from a TrustedShopsID
 */
include_once "../base.php";

/********************************
 Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

$now = \DateTime::createFromFormat('U', time());
$now->setTimezone(new \DateTimeZone('UTC'));


$review_collector = $ts_restricted->post('shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviewcollector', [
  "reviewCollectorRequest" => [
    "reviewCollectorReviewRequests" => [
      [
        "reminderDate" => "",
        "template" => [
          "variant" => "ReviewCollectorReviewRquestVariantEnum",
          "includeWidget" => false
        ],
        "order" => [
          "orderDate" => $now->format('Y-m-d'),
          "orderReference" => "order-1",
          "products" => [
            [
              "sku" => "test",
              "name" => "test",
              "gtin" => "test",
              "mpn" => "test",
              "brand" => "test",
              "imageUrl" => "test",
              "uuid" => "test",
              "url" => "test"
            ]
          ],
          "currency" => "CHF",
          "estimatedDeliveryDate" => $now->format('Y-m-d')
        ],
        "consumer" => [
          "firstname" => "John",
          "lastname" => "Doe",
          "contact" => [
            "email" => "john.doe@email.com"
          ]
        ]
      ]
    ]
  ]
]);

?>

<?= renderResponse('Post Review Collector from a TrustedShops ID', $review_collector); ?>
