<?php

namespace Helper;

use Codeception\Module;
use Tests\Support\ApiTester;

class CreateNewUser extends Module
{
    public function __construct() {}
    public function tryToCreateNewUser(ApiTester $I): array
    {
        $faker = \Faker\Factory::create();
        $userID = bin2hex(random_bytes(8));
        $countryOfIssue = [
            'AF', 'AL', 'DZ', 'AS', 'AD', 'AO', 'AI', 'AQ', 'AG', 'AR', 'AM', 'AW', 'AU', 'AT', 'AZ', 'BS', 'BH', 'BD', 
            'BB', 'BY', 'BE', 'BZ', 'BJ', 'BM', 'BT', 'BO', 'BA', 'BW', 'BR', 'IO', 'BN', 'BG', 'BF', 'BI', 'KH', 'CM', 
            'CA', 'CV', 'KY', 'CF', 'TD', 'CL', 'CN', 'CX', 'CC', 'CO', 'KM', 'CG', 'CD', 'CK', 'CR', 'HR', 'CU', 'CW', 
            'CY', 'CZ', 'CI', 'DK', 'DJ', 'DM', 'DO', 'EC', 'EG', 'SV', 'GQ', 'ER', 'EE', 'ET', 'FK', 'FO', 'FJ', 'FI', 
            'FR', 'GA', 'GM', 'GE', 'DE', 'GH', 'GI', 'GR', 'GL', 'GD', 'GP', 'GU', 'GT', 'GG', 'GN', 'GW', 'GY', 'HT', 
            'HM', 'HN', 'HK', 'HU', 'IS', 'IN', 'ID', 'IR', 'IQ', 'IE', 'IL', 'IT', 'JM', 'JP', 'JE', 'JO', 'KZ', 'KE', 
            'KI', 'KW', 'KG', 'LA', 'LV', 'LB', 'LS', 'LR', 'LY', 'LI', 'LT', 'LU', 'MO', 'MK', 'MW', 'MY', 'MV', 'ML', 
            'MT', 'MH', 'MQ', 'MR', 'MU', 'YT', 'MX', 'FM', 'MD', 'MC', 'MN', 'ME', 'MS', 'MA', 'MZ', 'MM', 'NA', 'NR', 
            'NP', 'NL', 'NC', 'NZ', 'NI', 'NE', 'NG', 'NU', 'NF', 'KP', 'NO', 'OM', 'PK', 'PW', 'PA', 'PG', 'PY', 'PE', 
            'PH', 'PN', 'PL', 'PT', 'PR', 'QA', 'RE', 'RO', 'RU', 'RW', 'BL', 'SH', 'KN', 'LC', 'MF', 'PM', 'VC', 'WS', 
            'SM', 'ST', 'SA', 'SN', 'RS', 'SC', 'SL', 'SG', 'SK', 'SI', 'SB', 'SO', 'ZA', 'GS', 'KR', 'ES', 'LK', 'SD', 
            'SR', 'SS', 'SJ', 'SE', 'CH', 'SY', 'TW', 'TJ', 'TZ', 'TH', 'TL', 'TG', 'TK', 'TO', 'TT', 'TN', 'TR', 'TM', 
            'TC', 'TV', 'UG', 'UA', 'AE', 'GB', 'US', 'UY', 'UZ', 'VU', 'VA', 'VE', 'VN', 'WF', 'EH', 'YE', 'ZM', 'ZW'
        ];
        $data = [
            'id' => "$userID",
            'firstName' => $faker->firstName,
            'lastName' => $faker-> lastname,
            'email' => $faker->email,
            'dateOfBirth' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'personalIdDocument' => [
                'documentId' => strtoupper($faker->lexify('???')) . $faker->randomNumber(4),
                'countryOfIssue' => $faker->randomElement($countryOfIssue),
                'validUntil' => $faker->dateTimeBetween('now', '+30 year')->format('Y-m-d')
            ]
        ];
        
        $I->sendPOST('/api/users', json_encode($data));
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson(['message' => 'User created successfully']);

        return $data;
    }
}
