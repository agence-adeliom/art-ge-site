<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ZipApiControllerTest extends WebTestCase
{
    /**
     * @dataProvider zipProvider
     */
    public function testSomething(string $zip, int $count): void
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/api/zip/%s', $zip), [], [], ['PHP_AUTH_USER' => $_ENV['PHP_AUTH_USER'], 'PHP_AUTH_PW' => $_ENV['PHP_AUTH_PW']]);

        $this->assertResponseIsSuccessful();

        $json = $client->getResponse()->getContent();
        $this->assertJson($json, 'Json non correct');

        $data = json_decode($json, true);
        self::assertCount($count, $data);
    }

    /**
     * @return iterable{string: zip, int: count}
     */
    public function zipProvider(): iterable
    {
        yield ["08", 38];
        yield ["10", 42];
        yield ["51", 44];
        yield ["52", 32];
        yield ["54", 107];
        yield ["57", 114];
        yield ["67", 96];
        yield ["88", 53];
        yield ["675", 10];
    }
}
