<?php
/**
 * Example to get shops from a TrustedShopsID
 */
include_once "../base.php";

/********************************
 Create the Trustedshops object
 ********************************/
$ts_restricted = getTrustedShopsRestricted();

$envs = getEnvVariables();

$now = \DateTime::createFromFormat('U', time());
$now->setTimezone(new \DateTimeZone('UTC'));

$review = $ts_restricted->post('shops/'.$envs['TRUSTEDSHOPS_ID'].'/reviews/trigger.json', [
  'reviewCollectorRequest' => [
    'reviewCollectorReviewRequests' => [
      [
        'reminderDate' => $now->format('Y-m-d'),
        'template' => [
          'variant' => 'test',
          'includeWidget' => 'true',
        ],
        'order' => [
          'orderDate' => $now->format('Y-m-d'),
          // TrustedShops has a limitation of at least 2 chars for
          // orderReference. To bypass this limitation pass, we prefix
          // every reference with "order-".
          'orderReference' => 'order-1',
          'currency' => 'CHF',
          'estimatedDeliveryDate' => $now->format('Y-m-d'),
          'products' => [],
        ],
        'consumer' => [
          'firstname' => 'John',
          'lastname' => 'Doe',
          'contact' => [
            'email' => 'john.doe@email.com',
          ],
        ],
      ],
    ],
  ],
]);

?>

<?= renderResponse('Trigger a Review from a TrustedShopsID ', $review); ?>
