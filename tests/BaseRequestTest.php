<?php

namespace Antistatique\TrustedShops\Tests;

use Antistatique\TrustedShops\TrustedShops;
use PHPUnit\Framework\TestCase;
use Exception;

/**
 * @coversDefaultClass Antistatique\TrustedShops\TrustedShops
 */
class BaseRequestTest extends TestCase
{
  /**
   * The Restricted TrustedShops class to tests public access API endpoints.
   *
   * @var \Antistatique\TrustedShops\TrustedShops
   */
  protected $ts_public;

  /**
   * The Restricted TrustedShops class to tests restricted access API endpoints.
   *
   * @var \Antistatique\TrustedShops\TrustedShops
   */
  protected $ts_restricted;

  /**
   * {@inheritDoc}
   */
  public function setup() {
    $TRUSTEDSHOPS_TSID = getenv('TRUSTEDSHOPS_TSID');
    $this->assertNotEmpty($TRUSTEDSHOPS_TSID, 'No environment variables! Copy .env.example -> .env and fill out your TrustedShops account details.');

    $this->ts_public = new TrustedShops('public', 'v2', 'api-qa');
    $this->ts_restricted = new TrustedShops('restricted', 'v2', 'api-qa');
  }

}
