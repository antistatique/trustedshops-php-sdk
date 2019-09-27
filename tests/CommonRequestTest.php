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
   * @covers ::__construct
   * @covers ::setEndpoint
   * @covers ::getApiEndpoint
   *
   * @dataProvider providerSetEndpoint
   */
  public function testConstructor($dc, $scoop, $version, $expected) {
    $ts = new TrustedShops($scoop, $version, $dc);
    $this->assertSame($expected, $ts->getApiEndpoint());
  }

  /**
   * @covers ::setEndpoint
   * @covers ::getApiEndpoint
   *
   * @dataProvider providerSetEndpoint
   */
  public function testSetEndpoint($dc, $scoop, $version, $expected) {
    $ts = new TrustedShops();
    $ts->setEndpoint($dc, $scoop, $version);
    $this->assertSame($expected, $ts->getApiEndpoint());
  }

  /**
   * Dataprovider of :testSetEndpoint
   *
   * @return array
   *   Variation of endpoint.
   */
  public function providerSetEndpoint() {
    return [
      [NULL, NULL, NULL, 'https://api.trustedshops.com/rest/public/v2'],
      [NULL, 'restricted', NULL, 'https://api.trustedshops.com/rest/restricted/v2'],
      [NULL, 'restricted', 'v3', 'https://api.trustedshops.com/rest/restricted/v3'],
      ['api-qa', 'restricted', 'v3', 'https://api-qa.trustedshops.com/rest/restricted/v3'],
      ['api-qa', NULL, NULL, 'https://api-qa.trustedshops.com/rest/public/v2'],
      ['api-qa', 'restricted', NULL, 'https://api-qa.trustedshops.com/rest/restricted/v2'],
    ];
  }

  /**
   * @covers ::success
   * @covers ::getLastError
   * @covers ::getLastResponse
   * @covers ::getLastRequest
   * @covers ::setResponseState
   * @covers ::getHeadersAsArray
   * @covers ::prepareStateForRequest
   */
  public function testInstantiation()
  {
    $this->assertFalse($this->ts_public->success());
    $this->assertFalse($this->ts_public->getLastError());
    $this->assertSame(array('headers' => null, 'body' => null), $this->ts_public->getLastResponse());
    $this->assertSame(array(), $this->ts_public->getLastRequest());
  }

  /**
   * @covers ::get
   * @covers ::makeRequest
   * @covers ::determineSuccess
   * @covers ::findHTTPStatus
   * @covers ::getLastError
   * @covers ::getLastResponse
   * @covers ::getLastRequest
   * @covers ::setResponseState
   * @covers ::getHeadersAsArray
   * @covers ::attachRequestPayload
   * @covers ::prepareStateForRequest
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
