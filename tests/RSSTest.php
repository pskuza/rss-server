<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class RSSTest extends TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = new Client(['base_uri' => 'http://127.0.0.1/', 'http_errors' => false]);
    }

    public function testConnection()
    {
        $r = $this->client->request('GET', '/');
        $this->assertEquals(200, $r->getStatusCode());
    }

    public function testAddPost()
    {
        $body = json_encode(['title' => bin2hex(random_bytes(2)), 'link' => 'https://example.com/', 'description' => bin2hex(random_bytes(20))]);
        $r = $this->client->request('POST', '/add', ['body' => $body]);

        $this->assertEquals(201, $r->getStatusCode());
    }

    public function testWrongPost()
    {
        //empty title
        $body = json_encode(['title' => '', 'link' => 'https://example.com/', 'description' => bin2hex(random_bytes(20))]);
        $r = $this->client->request('POST', '/add', ['body' => $body]);

        $this->assertEquals(400, $r->getStatusCode());

        //too long title
        $body = json_encode(['title' => bin2hex(random_bytes(80)), 'link' => 'https://example.com/', 'description' => bin2hex(random_bytes(20))]);
        $r = $this->client->request('POST', '/add', ['body' => $body]);

        $this->assertEquals(400, $r->getStatusCode());

        //too long description
        $body = json_encode(['title' => bin2hex(random_bytes(2)), 'link' => 'https://example.com/', 'description' => bin2hex(random_bytes(1400))]);
        $r = $this->client->request('POST', '/add', ['body' => $body]);

        $this->assertEquals(400, $r->getStatusCode());
    }

    public function testGetPost()
    {
        $r = $this->client->request('GET', '/');

        $this->assertEquals(200, $r->getStatusCode());
    }
}