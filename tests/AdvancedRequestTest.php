<?php

namespace Antistatique\TrustedShops\Tests;

use Antistatique\TrustedShops\TrustedShops;
use PHPUnit\Framework\TestCase;
use Exception;
use RuntimeException;

/**
 * @coversDefaultClass Antistatique\TrustedShops\TrustedShops
 */
class AdvancedRequestTest extends TestCase
{

  /**
   * {@inheritDoc}
   */
  public function setup() {
    $TRUSTEDSHOPS_TSID = getenv('TRUSTEDSHOPS_TSID');
    $TRUSTEDSHOPS_LOGIN = getenv('TRUSTEDSHOPS_LOGIN');
    $TRUSTEDSHOPS_PASS = getenv('TRUSTEDSHOPS_PASS');

    $this->assertNotEmpty($TRUSTEDSHOPS_TSID, 'No environment variables! Copy .env.example -> .env and fill out your TrustedShops account details.');
    $this->assertNotEmpty($TRUSTEDSHOPS_LOGIN, 'No environment variables! Copy .env.example -> .env and fill out your TrustedShops account details.');
    $this->assertNotEmpty($TRUSTEDSHOPS_PASS, 'No environment variables! Copy .env.example -> .env and fill out your TrustedShops account details.');
  }

  /**
   * @covers ::__construct
   */
  public function testUnsupportedScoop()
  {
    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Unsupported TrustedShops scoop "foo".');
    new TrustedShops('foo');
  }

  /**
   * @covers ::determineSuccess
   * @covers ::findHTTPStatus
   */
  public function testUnconfiguredCredentials()
  {
    $TRUSTEDSHOPS_TSID = getenv('TRUSTEDSHOPS_TSID');

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('ERROR 403: FORBIDDEN');
    $ts = new TrustedShops('restricted');
    $ts->get('shops/'.$TRUSTEDSHOPS_TSID.'/quality/complaints');
  }

  /**
   * @covers ::determineSuccess
   * @covers ::findHTTPStatus
   */
  public function testBadCredentials()
  {
    $TRUSTEDSHOPS_TSID = getenv('TRUSTEDSHOPS_TSID');
    $ts = new TrustedShops('restricted');
    $ts->setApiCredentials( 'foo', 'bar');

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('ERROR 403: FORBIDDEN');
    $ts->get('shops/'.$TRUSTEDSHOPS_TSID.'/quality/complaints');
  }

  /**
   * @covers ::determineSuccess
   * @covers ::findHTTPStatus
   * @covers ::getLastError
   * @covers ::getLastResponse
   * @covers ::getLastRequest
   */
  public function testShopsQualityComplaints()
  {
    $TRUSTEDSHOPS_TSID = getenv('TRUSTEDSHOPS_TSID');
    $TRUSTEDSHOPS_LOGIN = getenv('TRUSTEDSHOPS_LOGIN');
    $TRUSTEDSHOPS_PASS = getenv('TRUSTEDSHOPS_PASS');

    $ts = new TrustedShops('restricted');
    $ts->setApiCredentials( $TRUSTEDSHOPS_LOGIN, $TRUSTEDSHOPS_PASS);

    $this->markTestSkipped('We need valid credentials from TrustedShops.');

    /** @var SimpleXMLElement $response */
    $response = $ts->get('shops/'.$TRUSTEDSHOPS_TSID.'/quality/complaints');

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
