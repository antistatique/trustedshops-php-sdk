<?php

namespace Antistatique\TrustedShops\Tests\Advanced;

use Antistatique\TrustedShops\Tests\RequestTestBase;
use Exception;

/**
 * @coversDefaultClass \Antistatique\TrustedShops\TrustedShops
 */
class QualityComplaintsTest extends RequestTestBase
{
    /**
     * {@inheritDoc}
     */
    public function setup()
    {
        parent::setup();

        $TRUSTEDSHOPS_LOGIN = getenv('TRUSTEDSHOPS_LOGIN');
        $TRUSTEDSHOPS_PASS = getenv('TRUSTEDSHOPS_PASS');

        $this->assertNotEmpty($TRUSTEDSHOPS_LOGIN, 'No environment variables! Copy .env.example -> .env and fill out your TrustedShops account details.');
        $this->assertNotEmpty($TRUSTEDSHOPS_PASS, 'No environment variables! Copy .env.example -> .env and fill out your TrustedShops account details.');
    }

    /**
     * @covers ::setApiCredentials
     * @covers ::determineSuccess
     * @covers ::findHTTPStatus
     */
    public function testUnconfiguredCredentials()
    {
        $TRUSTEDSHOPS_TSID = getenv('TRUSTEDSHOPS_TSID');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('ERROR 403: FORBIDDEN');
        $this->ts_restricted->get('shops/'.$TRUSTEDSHOPS_TSID.'/quality/complaints');
    }

    /**
     * @covers ::setApiCredentials
     * @covers ::determineSuccess
     * @covers ::findHTTPStatus
     */
    public function testBadCredentials()
    {
        $TRUSTEDSHOPS_TSID = getenv('TRUSTEDSHOPS_TSID');
        $this->ts_restricted->setApiCredentials('foo', 'bar');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('ERROR 403: FORBIDDEN');
        $this->ts_restricted->get('shops/'.$TRUSTEDSHOPS_TSID.'/quality/complaints');
    }

    /**
     * @covers ::get
     * @covers ::makeRequest
     * @covers ::setApiCredentials
     * @covers ::determineSuccess
     * @covers ::findHTTPStatus
     * @covers ::getLastError
     * @covers ::getLastResponse
     * @covers ::getLastRequest
     * @covers ::setResponseState
     * @covers ::getHeadersAsArray
     * @covers ::formatResponse
     * @covers ::attachRequestPayload
     * @covers ::prepareStateForRequest
     */
    public function testShopsQualityComplaints()
    {
        $TRUSTEDSHOPS_TSID = getenv('TRUSTEDSHOPS_TSID');
        $TRUSTEDSHOPS_LOGIN = getenv('TRUSTEDSHOPS_LOGIN');
        $TRUSTEDSHOPS_PASS = getenv('TRUSTEDSHOPS_PASS');

        $this->ts_restricted->setApiCredentials($TRUSTEDSHOPS_LOGIN, $TRUSTEDSHOPS_PASS);

        /** @var array $response */
        $response = $this->ts_restricted->get('shops/'.$TRUSTEDSHOPS_TSID.'/quality/complaints');

        $this->assertInternalType('array', $response);
        $this->assertTrue($this->ts_restricted->success());
        $this->assertFalse($this->ts_restricted->getLastError());

        $this->assertSame(
      ['headers', 'httpHeaders', 'body'],
      array_keys($this->ts_restricted->getLastResponse())
    );

        $this->assertSame(
      ['method', 'path', 'url', 'body', 'timeout', 'headers'],
      array_keys($this->ts_restricted->getLastRequest())
    );

        $this->assertInternalType('string', $response['data']['shop']['url']);
        $this->assertEquals('demoshop.trustedshops.com/fr/home', $response['data']['shop']['url']);

        $this->assertSame(
      ['totalComplaintCount', 'activeComplaintCount'],
      array_keys((array) $response['data']['shop']['qualityIndicators']['complaintIndicator'])
    );
    }
}
