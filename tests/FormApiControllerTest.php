<?php

namespace App\Tests;

use App\Enum\ThematiqueSlugEnum;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class FormApiControllerTest extends WebTestCase
{

    /**
     * @dataProvider questionByGreenSpaceProvider
     */
    public function testSomething(bool $greenSpace, string $thematiqueSlug, int $choices): void
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/api/form?green_space=%s', $greenSpace ? 'true' : 'false'));

        $this->assertResponseIsSuccessful();

        $json = $client->getResponse()->getContent();
        $this->assertJson($json);

        $data = json_decode($json, true);

        $question = array_values(array_filter($data['questions'], fn (array $question) =>$question['thematique']['slug'] === $thematiqueSlug));

        self::assertCount($choices, $question[0]['choices']);
    }

    /**
     * @return iterable{greenSpace: bool, thematiqueSlug: string, choices: int}
     */
    public function questionByGreenSpaceProvider(): iterable
    {
        yield [true, ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value, 5];
        yield [false, ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value, 13];
        yield [true, ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value, 5];
        yield [false, ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value, 10];
    }
}
