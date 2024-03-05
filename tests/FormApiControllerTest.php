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
    public function testSomething(bool $greenSpace, bool $restauration, string $thematiqueSlug, int $choices): void
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/api/form?green_space=%s&restauration=%s', $greenSpace ? 'true' : 'false', $restauration ? 'true' : 'false'), [], [], ['PHP_AUTH_USER' => $_ENV['PHP_AUTH_USER'], 'PHP_AUTH_PW' => $_ENV['PHP_AUTH_PW']]);

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
        yield [true, true, ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value, 13];
        yield [true, false, ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value, 13];
        yield [false, true, ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value, 5];
        yield [false, false, ThematiqueSlugEnum::BIODIVERSITE_ET_CONSERVATION_DE_LA_NATURE_SUR_SITE->value, 5];

        yield [true, true, ThematiqueSlugEnum::GESTION_DES_DECHETS->value, 14];
        yield [true, false, ThematiqueSlugEnum::GESTION_DES_DECHETS->value, 12];
        yield [false, true, ThematiqueSlugEnum::GESTION_DES_DECHETS->value, 13];
        yield [false, false, ThematiqueSlugEnum::GESTION_DES_DECHETS->value, 11];

        yield [true, true, ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value, 10];
        yield [true, false, ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value, 10];
        yield [false, true, ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value, 7];
        yield [false, false, ThematiqueSlugEnum::GESTION_DE_L_EAU_ET_DE_L_EROSION->value, 7];

        yield [true, true, ThematiqueSlugEnum::ECO_CONSTRUCTION->value, 8];

        yield [true, true, ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value, 14];
        yield [true, false, ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value, 12];
        yield [false, true, ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value, 14];
        yield [false, false, ThematiqueSlugEnum::GESTION_DE_L_ENERGIE->value, 12];

        yield [true, true, ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value, 8];
        yield [true, false, ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value, 7];
        yield [false, true, ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value, 8];
        yield [false, false, ThematiqueSlugEnum::SENSIBILISATION_DES_ACTEURS->value, 7];

//        yield [true, false, ThematiqueSlugEnum::GESTION_DES_DECHETS->value, 12];
    }
}
