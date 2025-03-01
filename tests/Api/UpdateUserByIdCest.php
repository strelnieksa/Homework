<?php

declare(strict_types=1);

namespace Tests\Api;

namespace Helper;

use Tests\Support\ApiTester;
use Helper\CreateNewUser;

header('Content-Type: application/json');

final class UpdateUserByIdCest
{
    private array $data;

    public function _before(ApiTester $I): void
    {
        $auth = base64_encode('admin:P@ssw0rd');
        $I->haveHttpHeader('Authorization', 'Basic ' . $auth);

        $createNewUser = new CreateNewUser();
        $this->data = $createNewUser->tryToCreateNewUser($I);
        codecept_debug("User created: " . json_encode(($this->data), JSON_PRETTY_PRINT));
    }

    public function tryToUpdateUserById(ApiTester $I): void
    {
        $userId = $this->data['id'];

        $data = [

            'lastName' => 'Riverstone',
            'email' => 'married.sara@yahoo.com',
            'personalIdDocument' => [
                'documentId' => 'LV111222',
                'countryOfIssue' => 'LV',
                'validUntil' => '2030-01-01',
            ],
            'id' => "$userId"
        ];

        $I->sendPut("/api/users/$userId", json_encode($data));
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['message' => 'User updated successfully']);

        $I->sendGet("/api/users/$userId");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $response = $I->grabResponse();
        codecept_debug("Updated User Info Response: " . json_encode(json_decode($response), JSON_PRETTY_PRINT));

        $I->seeResponseContainsJson([
            'id' => $userId,
            'firstName' => $this->data['firstName'],
            'lastName' => 'Riverstone',
            'dateOfBirth' => $this->data['dateOfBirth'],
            'email' => 'married.sara@yahoo.com',
            'documentId' => 'LV111222',
            'countryOfIssue' => 'LV',
            'validUntil' => '2030-01-01'
        ]);
    }
}
