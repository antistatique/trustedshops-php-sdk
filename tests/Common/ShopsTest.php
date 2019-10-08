<?php

namespace Antistatique\TrustedShops\Tests\Common;

use Antistatique\TrustedShops\Tests\RequestTestBase;
use Exception;

/**
 * @coversDefaultClass Antistatique\TrustedShops\TrustedShops
 */
class ShopsTest extends RequestTestBase
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
