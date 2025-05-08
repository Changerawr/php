<?php

namespace Changerawr\Tests\Config;

use Changerawr\Config\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    private $apiKey = 'test_api_key';
    private $baseUrl = 'https://test.changerawr.com';

    public function testCanCreateConfiguration()
    {
        $config = new Configuration($this->apiKey, $this->baseUrl);

        $this->assertEquals($this->apiKey, $config->getApiKey());
        $this->assertEquals($this->baseUrl, $config->getBaseUrl());
    }

    public function testCanSetTimeout()
    {
        $config = new Configuration($this->apiKey, $this->baseUrl);
        $config->setTimeout(60);

        $this->assertEquals(60, $config->getTimeout());
    }

    public function testCanSetUserAgent()
    {
        $config = new Configuration($this->apiKey, $this->baseUrl);
        $config->setUserAgent('Test-Agent/1.0');

        $this->assertEquals('Test-Agent/1.0', $config->getUserAgent());
    }

    public function testCanSetDebugMode()
    {
        $config = new Configuration($this->apiKey, $this->baseUrl);
        $config->setDebug(true);

        $this->assertTrue($config->isDebug());
    }

    public function testCanGetAuthorizationHeader()
    {
        $config = new Configuration($this->apiKey, $this->baseUrl);
        $headers = $config->getAuthorizationHeader();

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertEquals('Bearer ' . $this->apiKey, $headers['Authorization']);
    }

    public function testCanGetDefaultHeaders()
    {
        $config = new Configuration($this->apiKey, $this->baseUrl);
        $headers = $config->getDefaultHeaders();

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertArrayHasKey('Accept', $headers);
        $this->assertArrayHasKey('User-Agent', $headers);

        $this->assertEquals('Bearer ' . $this->apiKey, $headers['Authorization']);
        $this->assertEquals('application/json', $headers['Content-Type']);
        $this->assertEquals('application/json', $headers['Accept']);
    }

    public function testTrimsTrailingSlashFromBaseUrl()
    {
        $config = new Configuration($this->apiKey, 'https://test.changerawr.com/');

        $this->assertEquals('https://test.changerawr.com', $config->getBaseUrl());
    }
}