<?php

namespace Antistatique\TrustedShops\Tests;

use Antistatique\TrustedShops\Tests\Traits\TestPrivateTrait;
use Antistatique\TrustedShops\TrustedShops;
use Exception;
use phpmock\phpunit\PHPMock;
use RuntimeException;

/**
 * @coversDefaultClass \Antistatique\TrustedShops\TrustedShops
 */
class TrustedShopsTest extends RequestTestBase
{
    use PHPMock;
    use TestPrivateTrait;

    /**
     * @covers ::__construct
     * @covers ::setEndpoint
     * @covers ::getApiEndpoint
     *
     * @dataProvider providerSetEndpoint
     */
    public function testConstructor($dc, $scoop, $version, $expected)
    {
        $ts = new TrustedShops($scoop, $version, $dc);
        $this->assertSame($expected, $ts->getApiEndpoint());
    }

    /**
     * @covers ::setEndpoint
     * @covers ::getApiEndpoint
     *
     * @dataProvider providerSetEndpoint
     */
    public function testSetEndpoint($dc, $scoop, $version, $expected)
    {
        $ts = new TrustedShops();
        $ts->setEndpoint($dc, $scoop, $version);
        $this->assertSame($expected, $ts->getApiEndpoint());
    }

    /**
     * Dataprovider of :testSetEndpoint.
     *
     * @return array
     *   Variation of endpoint
     */
    public static function providerSetEndpoint(): iterable
    {
        return [
          [null, null, null, 'https://api.trustedshops.com/rest/public/v2'],
          [
            null,
            'restricted',
            null,
            'https://api.trustedshops.com/rest/restricted/v2',
          ],
          [
            null,
            'restricted',
            'v3',
            'https://api.trustedshops.com/rest/restricted/v3',
          ],
          [
            'api-qa',
            'restricted',
            'v3',
            'https://api-qa.trustedshops.com/rest/restricted/v3',
          ],
          ['api-qa', null, null, 'https://api-qa.trustedshops.com/rest/public/v2'],
          [
            'api-qa',
            'restricted',
            null,
            'https://api-qa.trustedshops.com/rest/restricted/v2',
          ],
      ];
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
        $this->assertSame([
      'headers' => null,
      'body' => null,
    ], $this->ts_public->getLastResponse());
        $this->assertSame([], $this->ts_public->getLastRequest());
    }

    /**
     * @covers ::setApiCredentials
     */
    public function testSetApiCredentials()
    {
        $reflection = new \ReflectionClass($this->ts_restricted);

        $property = $reflection->getProperty('api_credentials_user');
        $property->setAccessible(true);
        $this->assertEmpty($property->getValue($this->ts_restricted));

        $property = $reflection->getProperty('api_credentials_pass');
        $property->setAccessible(true);
        $this->assertEmpty($property->getValue($this->ts_restricted));

        $this->ts_restricted->setApiCredentials('foo', 'bar');

        $property = $reflection->getProperty('api_credentials_user');
        $property->setAccessible(true);
        $this->assertEquals('foo', $property->getValue($this->ts_restricted));

        $property = $reflection->getProperty('api_credentials_pass');
        $property->setAccessible(true);
        $this->assertEquals('bar', $property->getValue($this->ts_restricted));
    }

    /**
     * @covers ::delete
     *
     * @dataProvider providerVerbMock
     */
    public function testVerbDelete($method, $args, $timeout)
    {
        $ts_mock = $this->getMockBuilder(TrustedShops::class)
          ->setMethods(['makeRequest'])
          ->getMock();

        $ts_mock->expects($this->once())
          ->method('makeRequest')
          ->with('delete', $method, $args, $timeout);

        $ts_mock->delete($method, $args, $timeout);
    }

    /**
     * @covers ::get
     *
     * @dataProvider providerVerbMock
     */
    public function testVerbGet($method, $args, $timeout)
    {
        $ts_mock = $this->getMockBuilder(TrustedShops::class)
          ->setMethods(['makeRequest'])
          ->getMock();

        $ts_mock->expects($this->once())
          ->method('makeRequest')
          ->with('get', $method, $args, $timeout);

        $ts_mock->get($method, $args, $timeout);
    }

    /**
     * @covers ::patch
     *
     * @dataProvider providerVerbMock
     */
    public function testVerbPatch($method, $args, $timeout)
    {
        $ts_mock = $this->getMockBuilder(TrustedShops::class)
          ->setMethods(['makeRequest'])
          ->getMock();

        $ts_mock->expects($this->once())
          ->method('makeRequest')
          ->with('patch', $method, $args, $timeout);

        $ts_mock->patch($method, $args, $timeout);
    }

    /**
     * @covers ::post
     *
     * @dataProvider providerVerbMock
     */
    public function testVerbPost($method, $args, $timeout)
    {
        $ts_mock = $this->getMockBuilder(TrustedShops::class)
          ->setMethods(['makeRequest'])
          ->getMock();

        $ts_mock->expects($this->once())
          ->method('makeRequest')
          ->with('post', $method, $args, $timeout);

        $ts_mock->post($method, $args, $timeout);
    }

    /**
     * @covers ::put
     *
     * @dataProvider providerVerbMock
     */
    public function testVerbPut($method, $args, $timeout)
    {
        $ts_mock = $this->getMockBuilder(TrustedShops::class)
          ->setMethods(['makeRequest'])
          ->getMock();

        $ts_mock->expects($this->once())
          ->method('makeRequest')
          ->with('put', $method, $args, $timeout);

        $ts_mock->put($method, $args, $timeout);
    }

    /**
     * Dataprovider of :testVerbDelete.
     *
     * @return array
     *   Variation of verb parameters
     */
    public function providerVerbMock(): iterable
    {
        return [
          ['foo', [], 10],
          ['foo/bar', [], 20],
          ['foo/bar', ['foo' => 'bar'], 20],
          ['foo?bar=boo', [], 20],
        ];
    }

    /**
     * @covers ::formatResponse
     * @covers ::getLastResponse
     */
    public function testFormatResponseJson()
    {
        $response['body'] = file_get_contents(__DIR__.'/assets/responses/partials/body.json');
        $result = $this->callPrivateMethod($this->ts_public, 'formatResponse', [$response]);

        $this->assertIsArray($result);
        $this->assertIsArray($this->ts_public->getLastResponse());
        $this->assertSame([
          'response' => [
            'code' => 200,
            'data' => [
              'shop' => [
                'tsId' => 'UNSMOKGONMYBWZOXNLVRKUQMUHJAYFGCA',
                'url' => 'demoshop.trustedshops.com/fr/home',
                'name' => 'Trusted Shops DemoShop FR',
                'languageISO2' => 'fr',
                'targetMarketISO3' => 'FRA',
              ],
            ],
            'message' => 'SUCCESS',
            'responseInfo' => [
              'apiVersion' => '2.4.18',
            ],
            'status' => 'SUCCESS',
          ],
        ], $result);
    }

    /**
     * @covers ::formatResponse
     */
    public function testFormatResponseEmptyBody()
    {
        $result = $this->callPrivateMethod($this->ts_public, 'formatResponse', [[]]);
        $this->assertFalse($result);
        $this->assertEmpty($this->ts_public->getLastResponse());
    }

    /**
     * @covers ::setResponseState
     */
    public function testSetResponseState()
    {
        $response = json_decode(file_get_contents(__DIR__.'/assets/responses/shops.json'), true);
        $response_content = file_get_contents(__DIR__.'/assets/responses/partials/body.txt');

        $this->assertArrayHasKey('httpHeaders', $response);
        $this->assertArrayHasKey('body', $response);
        $this->assertEmpty($response['httpHeaders']);
        $this->assertEmpty($response['body']);

        $response = $this->callPrivateMethod($this->ts_public, 'setResponseState', [
      $response, $response_content, null,
    ]);

        $this->assertArrayHasKey('httpHeaders', $response);
        $this->assertArrayHasKey('body', $response);
        $this->assertSame([
          'Date' => 'Mon, 30 Sep 2019 14:09:23 GMT',
          'Server' => 'Apache',
          'Access-Control-Allow-Origin' => '*',
          'Content-Encoding' => 'gzip',
          'Connection' => 'close',
          'Transfer-Encoding' => 'chunked',
          'Content-Type' => 'application/json',
        ], $response['httpHeaders']);
        $this->assertSame('
{"response":{"code":200,"data":{"shop":{"tsId":"UNSMOKGONMYBWZOXNLVRKUQMUHJAYFGCA","url":"demoshop.trustedshops.com/fr/home","name":"Trusted Shops DemoShop FR","languageISO2":"fr","targetMarketISO3":"FRA"}},"message":"SUCCESS","responseInfo":{"apiVersion":"2.4.18"},"status":"SUCCESS"}}
', $response['body']);
    }

    /**
     * @covers ::setResponseState
     */
    public function testSetResponseStateError()
    {
        $curl_error_mock = $this->getFunctionMock('Antistatique\TrustedShops', 'curl_error');
        $curl_error_mock->expects($this->once())
          ->willReturn('Something went wrong.');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Something went wrong.');
        $this->callPrivateMethod($this->ts_public, 'setResponseState', [
          [], false, null,
        ]);
    }

    /**
     * @covers ::getHeadersAsArray
     */
    public function testGetHeadersAsArray()
    {
        $headers_string = file_get_contents(__DIR__.'/assets/responses/partials/headers.txt');
        $headers = $this->callPrivateMethod($this->ts_public, 'getHeadersAsArray', [$headers_string]);

        $this->assertSame([
          'Date' => 'Mon, 30 Sep 2019 14:09:23 GMT',
          'Server' => 'Apache',
          'Access-Control-Allow-Origin' => '*',
          'Content-Encoding' => 'gzip',
          'Connection' => 'close',
          'Transfer-Encoding' => 'chunked',
          'Content-Type' => 'application/json',
        ], $headers);
    }

    /**
     * @covers ::makeRequest
     */
    public function testMakeRequestGet()
    {
        $headers_json = json_decode((file_get_contents(__DIR__.'/assets/responses/partials/headers.json')), true);
        $body_json = file_get_contents(__DIR__.'/assets/responses/partials/body.json');
        $body_txt = file_get_contents(__DIR__.'/assets/responses/partials/body.txt');

        $ts_mock = $this->getMockBuilder(TrustedShops::class)
          ->setMethods(['prepareStateForRequest', 'setResponseState', 'formatResponse', 'determineSuccess'])
          ->getMock();

        $ts_mock->expects($this->once())
          ->method('prepareStateForRequest')
          ->with('get', 'foo', 'https://api.trustedshops.com/rest/public/v2/foo', 10)
          ->willReturn([
            'headers' => null,
            'httpHeaders' => null,
            'body' => null,
          ]);

        $response = [
          'body' => $body_json,
          'headers' => $headers_json,
          'httpHeaders' => [
            'Date' => 'Mon, 30 Sep 2019 14:09:23 GMT',
            'Server' => 'Apache',
            'Access-Control-Allow-Origin' => '*',
            'Content-Encoding' => 'gzip',
            'Connection' => 'close',
            'Transfer-Encoding' => 'chunked',
            'Content-Type' => 'application/json',
          ],
    ];
        $ts_mock->expects($this->once())
          ->method('setResponseState')
          ->with($this->isType('array'), $this->isType('string'), $this->anything())
          ->willReturn($response);

        $ts_mock->expects($this->once())
          ->method('formatResponse')
          ->with($this->isType('array'))
          ->willReturn(json_decode($response['body'], true));

        $ts_mock->expects($this->once())
          ->method('determineSuccess')
          ->with($this->isType('array'), $this->isType('array'), $this->isType('integer'))
          ->willReturn(true);

        $curl_exec_mock = $this->getFunctionMock('Antistatique\TrustedShops', 'curl_exec');
        $curl_exec_mock->expects($this->any())->willReturn($body_txt);

        $curl_getinfo_mock = $this->getFunctionMock('Antistatique\TrustedShops', 'curl_getinfo');
        $curl_getinfo_mock->expects($this->any())->willReturn($response['headers']);

        $result = $this->callPrivateMethod($ts_mock, 'makeRequest', [
          'get',
          'foo',
          ['foo' => 'bar'],
          10,
        ]);
        $this->assertSame([
          'code' => 200,
          'data' => [
            'shop' => [
              'tsId' => 'UNSMOKGONMYBWZOXNLVRKUQMUHJAYFGCA',
              'url' => 'demoshop.trustedshops.com/fr/home',
              'name' => 'Trusted Shops DemoShop FR',
              'languageISO2' => 'fr',
              'targetMarketISO3' => 'FRA',
            ],
          ],
          'message' => 'SUCCESS',
          'responseInfo' => [
            'apiVersion' => '2.4.18',
          ],
          'status' => 'SUCCESS',
        ], $result);
    }

    /**
     * @covers ::findHTTPStatus
     *
     * @dataProvider providerHTTPStatus
     */
    public function testFindHTTPStatus($response, $formatted_response, $expected_code)
    {
        $code = $this->callPrivateMethod($this->ts_public, 'findHTTPStatus', [
          $response,
          $formatted_response,
        ]);
        $this->assertEquals($expected_code, $code);
    }

    /**
     * Dataprovider of :testFindHTTPStatus.
     *
     * @return array
     *   Variation of HTTP Status response
     */
    public static function providerHTTPStatus(): iterable
    {
        return [
          [
            ['headers' => ['http_code' => 400]],
            null,
            400,
          ],
          [
            ['headers' => null],
            ['code' => 300],
            418,
          ],
          [
            ['headers' => null, 'body' => ''],
            ['code' => 300],
            418,
          ],
          [
            ['headers' => ['http_code' => 400]],
            ['code' => 300],
            400,
          ],
          [
            ['body' => 'lorem'],
            ['code' => 318],
            318,
          ],
          [
            ['body' => ''],
            ['code' => 318],
            418,
          ],
          [
            [],
            null,
            418,
          ],
          [
            ['headers' => []],
            [],
            418,
          ],
      ];
    }

    /**
     * @covers ::determineSuccess
     *
     * @dataProvider providerStatus200
     */
    public function testDetermineSuccessStatus200($code)
    {
        $ts_mock = $this->getMockBuilder(TrustedShops::class)
          ->setMethods(['findHTTPStatus'])
          ->getMock();

        $ts_mock->expects($this->once())
          ->method('findHTTPStatus')
          ->willReturn($code);

        $this->assertFalse($ts_mock->success());
        $result = $this->callPrivateMethod($ts_mock, 'determineSuccess', [
          [], null, 0,
        ]);
        $this->assertTrue($result);
        $this->assertTrue($ts_mock->success());
    }

    /**
     * Dataprovider of :testDetermineSuccessStatus200.
     *
     * @return array
     *   Variation of HTTP Status response
     */
    public static function providerStatus200(): iterable
    {
        return [
          [
            200,
          ],
          [
            250,
          ],
          [
            299,
          ],
        ];
    }

    /**
     * @covers ::determineSuccess
     */
    public function testDetermineSuccessErrorMessage()
    {
        $ts_mock = $this->getMockBuilder(TrustedShops::class)
          ->setMethods(['findHTTPStatus'])
          ->getMock();

        $ts_mock->expects($this->once())
          ->method('findHTTPStatus')
          ->willReturn(100);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('ERROR 300: Something went wrong.');
        $this->callPrivateMethod($ts_mock, 'determineSuccess', [
          [], ['status' => 'ERROR', 'message' => 'Something went wrong.', 'code' => 300], 0,
        ]);

        $this->assertEquals('ERROR 300: Something went wrong.', $ts_mock->getLastError());
    }

    /**
     * @covers ::determineSuccess
     */
    public function testDetermineSuccessTimeout()
    {
        $ts_mock = $this->getMockBuilder(TrustedShops::class)
          ->setMethods(['findHTTPStatus'])
          ->getMock();

        $ts_mock->expects($this->once())
          ->method('findHTTPStatus')
          ->willReturn(100);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Request timed out after 20.000000 seconds.');
        $this->callPrivateMethod($ts_mock, 'determineSuccess', [
          ['headers' => ['total_time' => 20]], null, 5,
        ]);
    }

    /**
     * @covers ::determineSuccess
     */
    public function testDetermineSuccessUnknown()
    {
        $ts_mock = $this->getMockBuilder(TrustedShops::class)
          ->setMethods(['findHTTPStatus'])
          ->getMock();

        $ts_mock->expects($this->once())
          ->method('findHTTPStatus')
          ->willReturn(100);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unknown error, call getLastResponse() to find out what happened.');
        $this->callPrivateMethod($ts_mock, 'determineSuccess', [
          ['headers' => ['total_time' => 20]], null, 35,
        ]);

        $this->assertEquals('Unknown error, call getLastResponse() to find out what happened.', $ts_mock->getLastError());
    }

    /**
     * @covers ::prepareStateForRequest
     */
    public function testPrepareStateForRequest()
    {
        $this->callPrivateMethod($this->ts_public, 'prepareStateForRequest', [
          'get', 'shops', 'https://api-qa.trustedshops.com/rest/public/v2/shops/UNSMOKGONMYBWZOXNLVRKUQMUHJAYFGCA', 23,
        ]);

        $this->assertFalse($this->ts_public->success());
        $this->assertFalse($this->ts_public->getLastError());
        $this->assertSame([
          'headers' => null,
          'httpHeaders' => null,
          'body' => null,
        ], $this->ts_public->getLastResponse());
        $this->assertSame([
          'method' => 'get',
          'path' => 'shops',
          'url' => 'https://api-qa.trustedshops.com/rest/public/v2/shops/UNSMOKGONMYBWZOXNLVRKUQMUHJAYFGCA',
          'body' => '',
          'timeout' => 23,
          ], $this->ts_public->getLastRequest());
    }

    /**
     * @covers ::attachRequestPayload
     */
    public function testAttachRequestPayload()
    {
        $this->assertSame([], $this->ts_public->getLastRequest());

        $curl = curl_init();
        $curl_setopt_mock = $this->getFunctionMock('Antistatique\TrustedShops', 'curl_setopt');
        $curl_setopt_mock->expects($this->once())->with($curl, CURLOPT_POSTFIELDS, '{"name":"john","age":30,"car":null}');

        $this->callPrivateMethod($this->ts_public, 'attachRequestPayload', [
          &$curl, ['name' => 'john', 'age' => 30, 'car' => null],
        ]);
        $this->assertSame(['body' => '{"name":"john","age":30,"car":null}'], $this->ts_public->getLastRequest());
    }
}
