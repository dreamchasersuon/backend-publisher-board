<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

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

    public function testGetUsersInvalidUrl()
    {
        $client = static::createClient();
        $client->request('GET', self::API_URL . '/users');
        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testGetUsersSuccessful()
    {
        $client = static::createClient();
        $client->request('GET', self::API_URL . '/users?offset=0&limit=10');

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testCreateUserInvalidBody()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::API_URL . '/users',
            [],
            [],
            [],
            '{ "user_name": "Alan" }'
        );

        $this->assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testCreateUserAlreadyExist()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::API_URL . '/users',
            [],
            [],
            [],
            '{ "user_id": "1", "user_name": "Paul" }'
        );

        $this->assertEquals(
            Response::HTTP_CONFLICT,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testCreateUserSuccessful()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::API_URL . '/users',
            [],
            [],
            [],
            '{ "user_id": "10", "user_name": "Paul" }'
        );

        $this->assertEquals(
            Response::HTTP_CREATED,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testUpdateUserNotFound()
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            self::API_URL . '/users/100',
            [],
            [],
            [],
            '{ "user_name": "Luck" }'
        );

        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testUpdateUserSuccessful()
    {
        $client = static::createClient();
        $client->request(
            'PUT',
            self::API_URL . '/users/1',
            [],
            [],
            [],
            '{ "user_name": "Luck" }'
        );

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testUpdateUserBalanceNotFound()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::API_URL . '/users/100/recharge',
            [],
            [],
            [],
            '{ "balance": 2500 }'
        );

        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testUpdateUserBalanceSuccessful()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            self::API_URL . '/users/2/recharge',
            [],
            [],
            [],
            '{ "balance": 2500 }'
        );

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $client->getResponse()->getStatusCode()
        );
    }
}