<?php

declare(strict_types=1);

namespace App\Tests\Helpers;

use App\Entity\Repondant;
use App\Entity\Reponse;
use App\Repository\DepartmentRepository;
use App\Repository\TypologieRepository;
use Symfony\Component\Uid\Ulid;

class AnswersHelper {
    public static function generateFullAnswers(): array
    {
        $rawForm = [];
        $rawForm[1]['answers'][1] = 'on';
        $rawForm[1]['answers'][2] = 'on';
        $rawForm[1]['answers'][3] = 'on';
        $rawForm[1]['answers'][4] = 'on';
        $rawForm[1]['answers'][5] = 'on';
        $rawForm[1]['answers'][6] = 'on';
        $rawForm[1]['answers'][7] = 'on';
        $rawForm[1]['answers'][8] = 'on';
        $rawForm[1]['answers'][9] = 'on';
        $rawForm[1]['answers'][10] = 'on';
        $rawForm[1]['answers'][11] = 'on';
        $rawForm[1]['answers'][12] = 'on';
        $rawForm[1]['answers'][13] = 'on';
        $rawForm[2]['answers'][14] = 'on';
        $rawForm[2]['answers'][15] = 'on';
        $rawForm[2]['answers'][16] = 'on';
        $rawForm[2]['answers'][17] = 'on';
        $rawForm[2]['answers'][18] = 'on';
        $rawForm[2]['answers'][19] = 'on';
        $rawForm[2]['answers'][20] = 'on';
        $rawForm[2]['answers'][21] = 'on';
        $rawForm[2]['answers'][22] = 'on';
        $rawForm[2]['answers'][23] = 'on';
        $rawForm[2]['answers'][24] = 'on';
        $rawForm[2]['answers'][25] = 'on';
        $rawForm[2]['answers'][26] = 'on';
        $rawForm[2]['answers'][27] = 'on';
        $rawForm[3]['answers'][28] = 'on';
        $rawForm[3]['answers'][29] = 'on';
        $rawForm[3]['answers'][30] = 'on';
        $rawForm[3]['answers'][31] = 'on';
        $rawForm[3]['answers'][32] = 'on';
        $rawForm[3]['answers'][33] = 'on';
        $rawForm[3]['answers'][34] = 'on';
        $rawForm[3]['answers'][35] = 'on';
        $rawForm[3]['answers'][36] = 'on';
        $rawForm[3]['answers'][37] = 'on';
        $rawForm[4]['answers'][38] = 'on';
        $rawForm[4]['answers'][39] = 'on';
        $rawForm[4]['answers'][40] = 'on';
        $rawForm[4]['answers'][41] = 'on';
        $rawForm[4]['answers'][42] = 'on';
        $rawForm[4]['answers'][43] = 'on';
        $rawForm[4]['answers'][44] = 'on';
        $rawForm[4]['answers'][45] = 'on';
        $rawForm[5]['answers'][46] = 'on';
        $rawForm[5]['answers'][47] = 'on';
        $rawForm[5]['answers'][48] = 'on';
        $rawForm[5]['answers'][49] = 'on';
        $rawForm[5]['answers'][50] = 'on';
        $rawForm[5]['answers'][51] = 'on';
        $rawForm[5]['answers'][52] = 'on';
        $rawForm[5]['answers'][53] = 'on';
        $rawForm[5]['answers'][54] = 'on';
        $rawForm[5]['answers'][55] = 'on';
        $rawForm[5]['answers'][56] = 'on';
        $rawForm[5]['answers'][57] = 'on';
        $rawForm[5]['answers'][58] = 'on';
        $rawForm[5]['answers'][59] = 'on';
        $rawForm[6]['answers'][60] = 'on';
        $rawForm[6]['answers'][61] = 'on';
        $rawForm[6]['answers'][62] = 'on';
        $rawForm[6]['answers'][63] = 'on';
        $rawForm[6]['answers'][64] = 'on';
        $rawForm[6]['answers'][65] = 'on';
        $rawForm[7]['answers'][66] = 'on';
        $rawForm[7]['answers'][67] = 'on';
        $rawForm[7]['answers'][68] = 'on';
        $rawForm[7]['answers'][69] = 'on';
        $rawForm[7]['answers'][70] = 'on';
        $rawForm[7]['answers'][71] = 'on';
        $rawForm[7]['answers'][72] = 'on';
        $rawForm[7]['answers'][73] = 'on';
        $rawForm[8]['answers'][74] = 'on';
        $rawForm[8]['answers'][75] = 'on';
        $rawForm[8]['answers'][76] = 'on';
        $rawForm[8]['answers'][77] = 'on';
        $rawForm[8]['answers'][78] = 'on';
        $rawForm[8]['answers'][79] = 'on';
        $rawForm[8]['answers'][80] = 'on';
        $rawForm[8]['answers'][81] = 'on';
        $rawForm[8]['answers'][82] = 'on';
        $rawForm[9]['answers'][83] = 'on';
        $rawForm[9]['answers'][84] = 'on';
        $rawForm[9]['answers'][85] = 'on';
        $rawForm[9]['answers'][86] = 'on';
        $rawForm[10]['answers'][87] = 'on';
        $rawForm[10]['answers'][88] = 'on';
        $rawForm[10]['answers'][89] = 'on';
        $rawForm[10]['answers'][90] = 'on';
        $rawForm[10]['answers'][91] = 'on';
        $rawForm[10]['answers'][92] = 'on';
        $rawForm[10]['answers'][93] = 'on';
        $rawForm[10]['answers'][94] = 'on';
        $rawForm[11]['answers'][95] = 'on';
        $rawForm[11]['answers'][96] = 'on';
        $rawForm[11]['answers'][97] = 'on';
        $rawForm[11]['answers'][98] = 'on';
        $rawForm[11]['answers'][99] = 'on';
        $rawForm[12]['answers'][100] = 'on';
        $rawForm[12]['answers'][101] = 'on';
        $rawForm[12]['answers'][102] = 'on';
        $rawForm[12]['answers'][103] = 'on';
        $rawForm[12]['answers'][104] = 'on';
        $rawForm[13]['answers'][105] = 'on';
        $rawForm[13]['answers'][106] = 'on';
        $rawForm[13]['answers'][107] = 'on';
        $rawForm[13]['answers'][108] = 'on';
        $rawForm[13]['answers'][109] = 'on';
        $rawForm[14]['answers'][110] = 'on';
        $rawForm[14]['answers'][111] = 'on';
        $rawForm[14]['answers'][112] = 'on';
        $rawForm[14]['answers'][113] = 'on';
        $rawForm[14]['answers'][114] = 'on';
        return $rawForm;
    }
}
