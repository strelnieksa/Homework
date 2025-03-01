<?php

declare(strict_types=1);

namespace Tests\Api;

namespace Helper;

use Tests\Support\ApiTester;
use Helper\CreateNewUser;

header('Content-Type: application/problem+json');

final class UpdateUserByIdFailureCest
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

    public function tryToUpdateUserByIdInvalidInput(ApiTester $I): void
    {
        $userId = $this->data['id'];

        $data = [
            'lastName' => 'Riverstone',
            'email' => 'married.sara@yahoo.com',
            'personalIdDocument' => [
                'documentId' => 'LV111222',
                'countryOfIssue' => 'AAQQ', // incorrect country of issue
                'validUntil' => '2030-01-01',
            ],
            'id' => "$userId"
        ];

        $I->sendPut("/api/users/$userId", json_encode($data));
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Invalid input']);
    }
    public function tryToUpdateUserByInvalidId(ApiTester $I): void
    {
        $data = [
            'lastName' => 'Riverstone',
            'email' => 'married.sara@yahoo.com',
            'personalIdDocument' => [
                'documentId' => 'LV111222',
                'countryOfIssue' => 'IT',
                'validUntil' => '2030-01-01',
            ],
            'id' => "a"
        ];

        $I->sendPut("/api/users/{$data['id']}", json_encode($data));
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'User not found']);
    }
}
