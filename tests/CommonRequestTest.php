<?php

namespace Antistatique\TrustedShops\Tests;

use Antistatique\TrustedShops\TrustedShops;
use PHPUnit\Framework\TestCase;
use Exception;

/**
 * @coversDefaultClass Antistatique\TrustedShops\TrustedShops
 */
class CommonRequestTest extends RequestTestBase
{

  /**
   * @covers ::determineSuccess
   * @covers ::findHTTPStatus
   */
  public function testInvalidTsid()
  {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('ERROR 400: TS_ID_INVALID');
    $this->ts_public->get('shops/abc');
  }

  /**
   * @covers ::construct
   */
  public function testConstructor() {
    $ts = new TrustedShops();
    $this->assertSame('https://api.trustedshops.com/rest/public/v2', $ts->getApiEndpoint());

    $ts = new TrustedShops('restricted');
    $this->assertSame('https://api.trustedshops.com/rest/restricted/v2', $ts->getApiEndpoint());

    $ts = new TrustedShops('restricted', 'v3');
    $this->assertSame('https://api.trustedshops.com/rest/restricted/v3', $ts->getApiEndpoint());

    $ts = new TrustedShops('restricted', 'v3', 'api-qa');
    $this->assertSame('https://api-qa.trustedshops.com/rest/restricted/v3', $ts->getApiEndpoint());

    $ts = new TrustedShops(NULL, NULL, 'api-qa');
    $this->assertSame('https://api-qa.trustedshops.com/rest/public/v2', $ts->getApiEndpoint());

    $ts = new TrustedShops('restricted', NULL, 'api-qa');
    $this->assertSame('https://api-qa.trustedshops.com/rest/restricted/v2', $ts->getApiEndpoint());
  }

  /**
   * @covers ::success
   * @covers ::getLastError
   * @covers ::getLastResponse
   * @covers ::getLastRequest
   */
  public function testInstantiation()
  {
    $this->assertFalse($this->ts_public->success());
    $this->assertFalse($this->ts_public->getLastError());
    $this->assertSame(array('headers' => null, 'body' => null), $this->ts_public->getLastResponse());
    $this->assertSame(array(), $this->ts_public->getLastRequest());
  }

  /**
   * @covers ::determineSuccess
   * @covers ::findHTTPStatus
   * @covers ::getLastError
   * @covers ::getLastResponse
   * @covers ::getLastRequest
   */
  public function testGetShops()
  {
    $TRUSTEDSHOPS_TSID = getenv('TRUSTEDSHOPS_TSID');

    /** @var array $response */
    $response = $this->ts_public->get('shops/'.$TRUSTEDSHOPS_TSID);

    $this->assertInternalType('array', $response);
    $this->assertTrue($this->ts_public->success());
    $this->assertFalse($this->ts_public->getLastError());

    $this->assertSame(
      ['headers', 'httpHeaders', 'body'],
      array_keys($this->ts_public->getLastResponse())
    );

    $this->assertSame(
      ['method', 'path', 'url', 'body', 'timeout', 'headers'],
      array_keys($this->ts_public->getLastRequest())
    );
  }
}
