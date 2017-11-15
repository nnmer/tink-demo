<?php

use Behat\Behat\Context\Context;

class FeatureContext implements Context

{
    const HOST_NAME = 'http://tink.lo';

    /**
     * Guzzle client
     * @var GuzzleHttp\Client
     */
    protected $client;

    /**
     * Request data
     */
    protected $requestData;

    /** @var string */
    protected $requestMethod = 'get';

    /**
     * Guzzle response
     * @var \GuzzleHttp\Psr7\Response
     */
    protected $response;

    /**
     * Guzzle response body
     * @var \GuzzleHttp\Psr7\Stream | \Psr\Http\Message\StreamInterface
     */
    protected $responseBody;

    public function __construct()
    {
        // Initialize your context here
        $this->client      = new \GuzzleHttp\Client([
            'base_uri'  => self::HOST_NAME,
            'headers'   => [
                'Content-Type' => 'application/json; charset=utf-8'
            ]
        ]);
    }

    /**
     * @Given /^I call "([^"]*)"$/
     */
    public function iCall($uri)
    {
        switch ($this->requestMethod) {
            case 'post':
                $response = $this->client->post($uri, ['body' => json_encode( (array) $this->requestData)]);
                break;
            case 'put':
                $response = $this->client->put($uri, ['body' => json_encode( (array) $this->requestData)]);
                break;
            default:
                $response = $this->client->get($uri);
                break;
        }
        $this->response = $response;
        $this->responseBody = $this->response->getBody();
    }

    /**
     * @Then /^the response is JSON$/
     */
    public function theResponseIsJson()
    {
        $data = json_decode($this->response->getBody(),true);
        if (empty($data)) {
            throw new Exception("Response was not JSON\n" . $this->responseBody);
        }
    }

    /**
     * @Then /^the response status code should be (\d+)$/
     */
    public function theResponseStatusCodeShouldBe($httpStatus)
    {
        if ((string)$this->response->getStatusCode() !== $httpStatus) {
            throw new \Exception('HTTP code does not match '.$httpStatus.
                ' (actual: '.$this->response->getStatusCode().')');
        }
    }

    /**
     * @Given /^that I send "({.*})"$/
     */
    public function thatISend($data)
    {
        $this->requestData = json_decode($data);
        $this->requestMethod = 'post';
    }

    /**
     * @Given /^that I put ("[^"]*")$/
     */
    public function thatIPut($data)
    {
        $this->requestData = json_decode($data);
        $this->requestMethod = 'put';
    }

    /**
         * @Given /^the response has a "([^"]*)" property$/
         */
    public function theResponseHasAProperty($propertyName)
     {
         $data = json_decode($this->responseBody);
         if (!empty($data)) {
             if (!isset($data->$propertyName)) {
                 throw new Exception("Property '".$propertyName."' is not set!\n");
             }
        } else {
             throw new Exception("Response was not JSON\n" . $this->responseBody);
         }
     }
      /**
      * @Then /^the "([^"]*)" property equals "([^"]*)"$/
      */
     public function thePropertyEquals($propertyName, $propertyValue)
     {
         if ($propertyValue=='false'){
             $propertyValue = false;
         }
         if ($propertyValue=='true'){
             $propertyValue = true;
         }

         if (is_numeric($propertyValue)){
             $propertyValue = $propertyValue."";
         }

         $data = json_decode($this->responseBody, true);

         if (!empty($data)) {
             if (!isset($data[$propertyName])) {
                 throw new Exception("Property '".$propertyName."' is not set!\n");
             }
             if (is_numeric($data[$propertyName])){
                 $data[$propertyName] .= '';
             }

             if ($data[$propertyName] !== $propertyValue) {
                 throw new \Exception('Property value mismatch! (given: '.$propertyValue.', match: '.$data[$propertyName].')');
             }
         } else {
             throw new Exception("Response was not JSON\n" . $this->responseBody);
         }
     }
}