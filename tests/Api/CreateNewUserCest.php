<?php

declare(strict_types=1);

namespace Tests\Api;

header('Content-Type: application/json');

use Tests\Support\ApiTester;

final class CreateNewUserCest
{
    public function tryToCreateNewUser(ApiTester $I): void
    {
        $faker = \Faker\Factory::create();
        $userID = bin2hex(random_bytes(8));
        $data = [
            'id' => "$userID",
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastname,
            'email' => $faker->email,
            'dateOfBirth' => $faker->date($format = 'Y-m-d', $max = 'now'),
            'personalIdDocument' => [
                'documentId' => strtoupper($faker->lexify('???')) . $faker->randomNumber(4),
                'countryOfIssue' => 'IT',
                'validUntil' => $faker->dateTimeBetween('now', '+30 year')->format('Y-m-d')
            ]
        ];

        $auth = base64_encode('admin:P@ssw0rd');
        $I->haveHttpHeader('Authorization', 'Basic ' . $auth);

        $I->sendPOST('/api/users', json_encode($data));
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson(['message' => 'User created successfully']);
        codecept_debug($I->grabResponse());
    }
}
