<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private const API_URL = 'http://symfony.localhost';

    /**
     * @dataProvider getAllUrls
     * @param string $httpMethod
     * @param string $url
     * @param array $parameters
     * @param array $files
     * @param array $server
     * @param string $content
     */
    public function testUrlsIsAccessible(
        string $httpMethod,
        string $url,
        array $parameters,
        array $files,
        array $server,
        string $content
    )
    {
        $client = static::createClient();
        $client->request($httpMethod, $url, $parameters, $files, $server, $content);
        $this->assertResponseIsSuccessful();
    }

    public function getAllUrls()
    {
        yield ['GET', self::API_URL . '/users', ['offset' => 0, 'limit' => 10], [], [], ''];
        yield ['POST', self::API_URL . '/users', [], [], [], '{ "user_id": "4" }'];
        yield ['PUT', self::API_URL . '/users/2', [], [], [], '{ "user_name": "Woland" }'];
        yield ['POST', self::API_URL . '/users/3/recharge', [], [], [], '{ "balance": 100 }'];
    }
}