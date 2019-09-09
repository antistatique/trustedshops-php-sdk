<?php

namespace Antistatique\TrustedShops\Tests;

use Antistatique\TrustedShops\TrustedShops;
use PHPUnit\Framework\TestCase;
use Exception;

/**
 * @coversDefaultClass Antistatique\TrustedShops\TrustedShops
 */
class CommonRequestTest extends TestCase
{

  /**
   * {@inheritDoc}
   */
  public function setup() {
    $TRUSTEDSHOPS_TSID = getenv('TRUSTEDSHOPS_TSID');
    $this->assertNotEmpty($TRUSTEDSHOPS_TSID, 'No environment variables! Copy .env.example -> .env and fill out your TrustedShops account details.');
  }

  /**
   * @covers ::determineSuccess
   * @covers ::findHTTPStatus
   */
  public function testInvalidTsid()
  {
    $this->expectException(Exception::class);
    $this->expectExceptionMessage('ERROR 400: TS_ID_INVALID');
    $ts = new TrustedShops();
    $ts->get('shops/abc');
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
  }

  /**
   * @covers ::success
   * @covers ::getLastError
   * @covers ::getLastResponse
   * @covers ::getLastRequest
   */
  public function testInstantiation()
  {
    $ts = new TrustedShops();
    $this->assertFalse($ts->success());
    $this->assertFalse($ts->getLastError());
    $this->assertSame(array('headers' => null, 'body' => null), $ts->getLastResponse());
    $this->assertSame(array(), $ts->getLastRequest());
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
    $ts = new TrustedShops();

    /** @var SimpleXMLElement $response */
    $response = $ts->get('shops/'.$TRUSTEDSHOPS_TSID);

    $this->assertInstanceOf(\SimpleXMLElement::class, $response);
    $this->assertTrue($ts->success());
    $this->assertFalse($ts->getLastError());

    $this->assertSame(
      ['headers', 'httpHeaders', 'body'],
      array_keys($ts->getLastResponse())
    );

    $this->assertSame(
      ['method', 'path', 'url', 'body', 'timeout', 'headers'],
      array_keys($ts->getLastRequest())
    );
  }
}
