<?php

namespace Antistatique\TrustedShops\Tests;

use Antistatique\TrustedShops\TrustedShops;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Antistatique\TrustedShops\TrustedShops
 *
 * @group trustedshop
 * @group trustedshop_unit
 *
 * @internal
 */
final class CurlAvailabilitiesTest extends TestCase
{
    use PHPMock;

    /**
     * @covers ::isCurlAvailable
     */
    public function testIsCurlAvailable(): void
    {
        $trustedShops = new TrustedShops();
        $this->assertTrue($trustedShops->isCurlAvailable());
    }

    /**
     * @covers ::__construct
     * @covers ::isCurlAvailable
     */
    public function testcurlNotAvailable(): void
    {
        $trustedShopsMock = $this->createMock(TrustedShops::class);
        $trustedShopsMock->method('isCurlAvailable')->willReturn(false);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('cURL support is required, but can\'t be found.');
        $trustedShopsMock->__construct();

        $trustedShopsMock->method('isCurlAvailable')->willReturn(true);
    }

    /**
     * @covers ::__construct
     * @covers ::isCurlAvailable
     *
     * @doesNotPerformAssertions
     */
    public function testCurlAvailable(): void
    {
        $trustedShopsMock = $this->createMock(TrustedShops::class);
        $trustedShopsMock->method('isCurlAvailable')->willReturn(true);
        $trustedShopsMock->__construct();
    }
}
