<?php

declare(strict_types=1);

namespace Tests\Api;

namespace Helper;

use Tests\Support\ApiTester;
use Helper\CreateNewUser;

header('Content-Type: application/json');

final class GetUserByIdCest
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

    public function tryToGetUserById(ApiTester $I): void
    {
        $userId = $this->data['id'];

        $I->sendGet("/api/users/$userId");
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['message' => 'A single user']);
        $response = $I->grabResponse();

        codecept_debug("User Info Response: " . json_encode(json_decode($response), JSON_PRETTY_PRINT));
    }
}
