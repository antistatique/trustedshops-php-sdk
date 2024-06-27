<?php

namespace Antistatique\TrustedShops;

use CurlHandle;
use Exception;
use RuntimeException;

/**
 * Super-simple, minimum abstraction TrustedShops API v2.x wrapper, in PHP.

 * TrustedShops API: https://api.trustedshops.com/
 * TrustedShops API QA: https://api-qa.trustedshops.com/
 * This wrapper: https://github.com/antistatique/trustedshops-php-sdk.
 */
class TrustedShops
{
    /**
     * Default timeout limit for request in seconds.
     *
     * @var int
     */
    public const TIMEOUT = 10;

    /**
     * TrustedShops allowed API scoop.
     *
     * @var string[]
     */
    public const ALLOWED_SCOOP = [
    'public',
    'restricted',
  ];

    /**
     * The original URL for public & restricted API call.
     *
     * @var string
     */
    public const BASE_URL = 'https://<dc>.trustedshops.com/rest/<scoop>/<version>';

    /**
     * The API dc used.
     *
     * @var string
     */
    private $api_dc = 'api';

    /**
     * The API version used.
     *
     * @var string
     */
    private $api_version = 'v2';

    /**
     * The API scoop. Choose from [public|restricted].
     *
     * Then scoop will affect the available API calls.
     *
     * @var string
     */
    private $api_scoop = 'public';

    /**
     * The base URL for public & restricted API call.
     *
     * @var string
     */
    private $api_endpoint = '';

    /**
     * SSL Verification.
     *
     * Read before disabling:
     * http://snippets.webaware.com.au/howto/stop-turning-off-curlopt_ssl_verifypeer-and-fix-your-php-config/
     */
    public bool $verify_ssl = true;

    private bool $request_successful = false;
    private string $last_error = '';
    private array $last_response = [];
    private array $last_request = [];

    /**
     * The API username credentials.
     */
    private ?string $api_credentials_user = null;

    /**
     * The API password credentials.
     */
    private ?string $api_credentials_pass = null;

    /**
     * Create a new instance.
     *
     * @param ?string $api_scoop
     *   The API range of call to be used
     * @param ?string $api_version
     *   The API version you use
     * @param ?string $api_dc
     *   The API dc of call to be used
     *
     * @throws \Exception
     */
    public function __construct(?string $api_scoop = null, ?string $api_version = null, ?string $api_dc = null)
    {
        if (!$this->isCurlAvailable()) {
            throw new RuntimeException("cURL support is required, but can't be found.");
        }

        if (null !== $api_scoop) {
            $this->api_scoop = $api_scoop;
        }

        if (null !== $api_version) {
            $this->api_version = $api_version;
        }

        if (null !== $api_dc) {
            $this->api_dc = $api_dc;
        }

        // Ensure the scoop is supported by TrustedShops.
        if (!in_array($this->api_scoop, self::ALLOWED_SCOOP)) {
            throw new RuntimeException(sprintf('Unsupported TrustedShops scoop "%s".', $this->api_scoop));
        }

        // Build the concrete api endpoint.
        $this->setEndpoint($this->api_dc, $this->api_scoop, $this->api_version);
        $this->last_response = ['headers' => null, 'body' => null];
    }

    /**
     * Check if cURL is available.
     */
    public function isCurlAvailable(): bool
    {
        return function_exists('curl_init') || function_exists('curl_setopt');
    }

    /**
     * Set the concrete API endpoint.
     *
     * @param ?string $api_dc
     *   The API dc of call to be used
     * @param ?string $api_scoop
     *   The API range of call to be used
     * @param ?string $api_version
     *   The API version you use
     *
     * @return string
     *   The endpoint url
     */
    public function setEndpoint(?string $api_dc = null, ?string $api_scoop = null, ?string $api_version = null): string
    {
        if (null !== $api_dc) {
            $this->api_dc = $api_dc;
        }

        if (null !== $api_scoop) {
            $this->api_scoop = $api_scoop;
        }

        if (null !== $api_version) {
            $this->api_version = $api_version;
        }

        // Reset the API endpoint to the original value.
        $this->api_endpoint = self::BASE_URL;

        $this->api_endpoint = str_replace('<dc>', $this->api_dc, $this->api_endpoint);
        $this->api_endpoint = str_replace('<scoop>', $this->api_scoop, $this->api_endpoint);
        $this->api_endpoint = str_replace('<version>', $this->api_version, $this->api_endpoint);

        return $this->api_endpoint;
    }

    /**
     * Get the concrete endpoint API.
     *
     * @return string
     *   The endpoint url
     */
    public function getApiEndpoint(): string
    {
        return $this->api_endpoint;
    }

    /**
     * Set the API username and password for restricted API calls.

     *
     * @param string $username
     *   The TrustedShops username authorized for restricted API calls
     * @param string $password
     *   The TrustedShops password authorized for restricted API calls
     */
    public function setApiCredentials(string $username, string $password): void
    {
        $this->api_credentials_user = $username;
        $this->api_credentials_pass = $password;
    }

    /**
     * Was the last request successful?
     *
     * @return bool
     *    True for success, FALSE for failure
     */
    public function success(): bool
    {
        return $this->request_successful;
    }

    /**
     * Get the last error returned by either the network transport, or by the API.
     *
     * If something didn't work, this contain the string describing the problem.
     *
     * @return  string|bool
     *    Describing the error
     */
    public function getLastError()
    {
        return $this->last_error ?: false;
    }

    /**
     * Get an array containing the HTTP headers and the body of the API response.
     *
     * @return array
     *    Assoc array with keys 'headers' and 'body'
     */
    public function getLastResponse(): array
    {
        return $this->last_response;
    }

    /**
     * Get an array containing the HTTP headers and the body of the API request.
     *
     * @return array
     *    Assoc array
     */
    public function getLastRequest(): array
    {
        return $this->last_request;
    }

    /**
     * Make an HTTP DELETE request - for deleting data.
     *
     * @param string $method
     *    URL of the API request method
     * @param array $args
     *    Assoc array of arguments (if any)
     * @param int $timeout
     *    Timeout limit for request in seconds
     *
     * @return array|bool
     *    A decoded array of result or an boolean on unattended response
     *
     * @throws \Exception
     */
    public function delete(string $method, array $args = [], int $timeout = self::TIMEOUT)
    {
        return $this->makeRequest('delete', $method, $args, $timeout);
    }

    /**
     * Make an HTTP GET request - for retrieving data.
     *
     * @param string $method
     *    URL of the API request method
     * @param array $args
     *    Assoc array of arguments (usually your data)
     * @param int $timeout
     *    Timeout limit for request in seconds
     *
     * @return array|bool
     *    A decoded array of result or an boolean on unattended response
     *
     * @throws \Exception
     */
    public function get(string $method, array $args = [], int $timeout = self::TIMEOUT)
    {
        return $this->makeRequest('get', $method, $args, $timeout);
    }

    /**
     * Make an HTTP PATCH request - for performing partial updates.
     *
     * @param string $method
     *    URL of the API request method
     * @param array $args
     *    Assoc array of arguments (usually your data)
     * @param int $timeout
     *    Timeout limit for request in seconds
     *
     * @return array|bool
     *    A decoded array of result or an boolean on unattended response
     *
     * @throws \Exception
     */
    public function patch(string $method, array $args = [], int $timeout = self::TIMEOUT)
    {
        return $this->makeRequest('patch', $method, $args, $timeout);
    }

    /**
     * Make an HTTP POST request - for creating and updating items.
     *
     * @param string $method
     *    URL of the API request method
     * @param array $args
     *    Assoc array of arguments (usually your data)
     * @param int $timeout
     *   Timeout limit for request in seconds
     *
     * @return array|bool
     *    A decoded array of result or an boolean on unattended response
     *
     * @throws \Exception
     */
    public function post(string $method, array $args = [], int $timeout = self::TIMEOUT)
    {
        return $this->makeRequest('post', $method, $args, $timeout);
    }

    /**
     * Make an HTTP PUT request - for creating new items.
     *
     * @param string $method
     *    URL of the API request method
     * @param array $args
     *    Assoc array of arguments (usually your data)
     * @param int $timeout
     *    Timeout limit for request in seconds
     *
     * @return array|bool
     *    A decoded array of result or an boolean on unattended response
     *
     * @throws \Exception
     */
    public function put(string $method, array $args = [], int $timeout = self::TIMEOUT)
    {
        return $this->makeRequest('put', $method, $args, $timeout);
    }

    /**
     * Performs the underlying HTTP request. Not very exciting.
     *
     * @param string $http_verb
     *    The HTTP verb to use: get, post, put, patch, delete
     * @param string $method
     *    The API method to be called
     * @param array $args
     *    Assoc array of parameters to be passed
     * @param int $timeout
     *    Timeout limit for request in seconds
     *
     * @return array|bool
     *    A decoded array of result or an boolean on unattended response
     *
     * @throws \Exception
     */
    protected function makeRequest(string $http_verb, string $method, array $args = [], int $timeout = self::TIMEOUT)
    {
        $url = $this->api_endpoint.'/'.$method;

        $response = $this->prepareStateForRequest($http_verb, $method, $url, $timeout);

        $httpHeader = [
          'Accept: application/json',
          'Content-Type: application/json',
          // TrustedShops needs the X-Requested-With header to works properly.
          'X-Requested-With: XMLHttpRequest',
        ];

        if (isset($args['language'])) {
            $httpHeader[] = 'Accept-Language: '.(string) $args['language'];
        }

        if ('put' === $http_verb) {
            $httpHeader[] = 'Allow: PUT, PATCH, POST';
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Antistatique/TrustedShops-PHP-SDK (github.com/antistatique/trustedshops-php-sdk)');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);

        // Set credentials when given.
        if ($this->api_credentials_user && $this->api_credentials_pass) {
            curl_setopt($curl, CURLOPT_USERPWD, $this->api_credentials_user.':'.$this->api_credentials_pass);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }

        switch ($http_verb) {
      case 'post':
        curl_setopt($curl, CURLOPT_POST, true);
        $this->attachRequestPayload($curl, $args);
        break;

      case 'get':
        $query = http_build_query($args, '', '&');
        curl_setopt($curl, CURLOPT_URL, $url.'?'.$query);
        break;

      case 'delete':
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        break;

      case 'patch':
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->attachRequestPayload($curl, $args);
        break;

      case 'put':
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->attachRequestPayload($curl, $args);
        break;
    }

        $response_content = curl_exec($curl);
        /** @var array|false $curl_info */
        $curl_info = curl_getinfo($curl);
        $response['headers'] = $curl_info;
        $response = $this->setResponseState($response, $response_content, $curl);
        $formattedResponse = $this->formatResponse($response);

        curl_close($curl);

        if (isset($formattedResponse['response']) && is_array($formattedResponse['response'])) {
            $this->determineSuccess($response, $formattedResponse['response'], $timeout);

            return $formattedResponse['response'];
        }

        return $this->determineSuccess($response, false, $timeout);
    }

    /**
     * @param string $http_verb
     *    The HTTP verb to use: get, post, put, patch, delete
     * @param string $method
     *    The API method to be called
     * @param string $url
     *    The URL
     * @param int $timeout
     *    Timeout limit for request in seconds
     */
    protected function prepareStateForRequest(string $http_verb, string $method, string $url, int $timeout): array
    {
        $this->last_error = '';

        $this->request_successful = false;

        $this->last_response = [
          'headers' => null, // array of details from curl_getinfo().
          'httpHeaders' => null, // array of HTTP headers.
          'body' => null, // content of the response.
        ];

        $this->last_request = [
          'method' => $http_verb,
          'path' => $method,
          'url' => $url,
          'body' => '',
          'timeout' => $timeout,
        ];

        return $this->last_response;
    }

    /**
     * Get the HTTP headers as an array of header-name => header-value pairs.
     *
     * @param string $headersAsString
     *   A string of headers to parse
     *
     * @return array
     *   The parsed headers
     */
    protected function getHeadersAsArray(string $headersAsString): array
    {
        $headers = [];

        foreach (explode(PHP_EOL, $headersAsString) as $line) {
            if (1 === preg_match('/HTTP\/[1-2]/', substr($line, 0, 7))) { // http code
                continue;
            }

            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            list($key, $value) = explode(': ', $line);
            $headers[$key] = $value;
        }

        return $headers;
    }

    /**
     * Encode the data and attach it to the request.
     *
     * @param CurlHandle $curl
     *    cURL session handle, used by reference
     * @param array $data
     *    Assoc array of data to attach
     */
    protected function attachRequestPayload(CurlHandle &$curl, array $data): void
    {
        $encoded = json_encode($data);
        $this->last_request['body'] = $encoded;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded);
    }

    /**
     * Decode the response and format any error messages for debugging.
     *
     * @param array $response
     *    The response from the curl request
     *
     * @return array|false
     *    A decoded array from JSON response
     */
    protected function formatResponse(array $response)
    {
        $this->last_response = $response;

        if (empty($response['body']) || !is_string($response['body'])) {
            return false;
        }

        // Return the decoded response from JSON when response is a valid json.
        // Will return FALSE otherwise.
        /** @var array|null $result */
        $result = json_decode($response['body'], true);

        return $result ?? false;
    }

    /**
     * Do post-request formatting and setting state from the response.
     *
     * @param array $response
     *    The response from the curl request
     * @param string|bool $response_content
     *    The body of the response from the curl request
     * @param CurlHandle $curl
     *    The curl resource
     *
     * @return array
     *    The modified response
     *
     * @throws \Exception
     */
    protected function setResponseState(array $response, string|bool $response_content, CurlHandle $curl): array
    {
        if (false === $response_content) {
            $this->last_error = curl_error($curl);
            throw new Exception($this->last_error);
        } elseif (is_string($response_content)) {
            $headerSize = isset($response['headers']['header_size']) ? (int) $response['headers']['header_size'] : 0;

            $response['httpHeaders'] = $this->getHeadersAsArray(substr($response_content, 0, $headerSize));
            $response['body'] = substr($response_content, $headerSize);

            if (isset($response['headers']['request_header'])) {
                $this->last_request['headers'] = $response['headers']['request_header'];
            }
        }

        return $response;
    }

    /**
     * Check if the response was successful or a failure.
     *
     * @param array $response
     *    The response from the curl request
     * @param array|false $formattedResponse
     *    The response body payload from the curl request
     * @param int $timeout
     *    The timeout supplied to the curl request
     *
     * @return bool
     *    If the request was successful
     *
     * @throws \Exception
     */
    protected function determineSuccess(array $response, $formattedResponse, int $timeout): bool
    {
        $status = $this->findHTTPStatus($response, $formattedResponse);

        if ($status >= 200 && $status <= 299) {
            $this->request_successful = true;

            return true;
        }

        if (isset($formattedResponse['message'])) {
            $this->last_error = sprintf('%s %d: %s', (string) $formattedResponse['status'], (int) $formattedResponse['code'], (string) $formattedResponse['message']);
            throw new Exception($this->last_error);
        }

        if (isset($response['headers']['total_time']) && $timeout > 0 && $response['headers']['total_time'] >= $timeout) {
            $this->last_error = sprintf('Request timed out after %f seconds.', (float) $response['headers']['total_time']);
            throw new Exception($this->last_error);
        }

        $this->last_error = 'Unknown error, call getLastResponse() to find out what happened.';
        throw new Exception($this->last_error);
    }

    /**
     * Find the HTTP status code from the headers or API response body.
     *
     * @param array $response
     *    The response from the curl request
     * @param array|false $formattedResponse
     *    The decoded response body payload from the curl request
     *
     * @return int
     *    HTTP status code
     */
    protected function findHTTPStatus(array $response, $formattedResponse): int
    {
        if (!empty($response['headers']) && isset($response['headers']['http_code'])) {
            return (int) $response['headers']['http_code'];
        }

        if (!empty($response['body']) && isset($formattedResponse['code'])) {
            return (int) $formattedResponse['code'];
        }

        return 418;
    }
}
