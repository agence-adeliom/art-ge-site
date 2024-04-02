<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class InseeApiControllerTest extends WebTestCase
{
    /**
     * @dataProvider zipProvider
     */
    public function testSomething(int $zip, array $cities): void
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/api/insee/%s', $zip), [], [], ['PHP_AUTH_USER' => $_ENV['PHP_AUTH_USER'], 'PHP_AUTH_PW' => $_ENV['PHP_AUTH_PW']]);

        $this->assertResponseIsSuccessful();

        $json = $client->getResponse()->getContent();
        $this->assertJson($json, 'Json non correct');

        $data = json_decode($json, true);
        foreach ($data as $d) {
            self::assertCount(3, array_keys($d));
            self::assertArrayHasKey('name', $d);
            self::assertArrayHasKey('zip', $d);
            self::assertArrayHasKey('insee', $d);
            self::assertTrue(in_array($d['name'], $cities), 'Ville non présente');
        }
    }
    public function zipProvider(): iterable
    {
        yield [67500, ["BATZENDORF", "HAGUENAU", "NIEDERSCHAEFFOLSHEIM", "WEITBRUCH"]];
        yield [51700, ["ANTHENAY","BASLIEUX SOUS CHATILLON","CHAMPVOISY","CHATILLON SUR MARNE","COEUR DE LA VALLEE","COURTHIEZY","CUISLES","DORMANS","FESTIGNY","IGNY COMBLIZY","JONQUERY","LEUVRIGNY","MAREUIL LE PORT","NESLE LE REPONS","OLIZY","PASSY GRIGNY","STE GEMME","TROISSY","VANDIERES","VERNEUIL","VINCELLES",]];
    }
}
