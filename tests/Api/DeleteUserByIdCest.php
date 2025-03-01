<?php

declare(strict_types=1);

namespace Tests\Api;

namespace Helper;

use Tests\Support\ApiTester;
use Helper\CreateNewUser;

header('Content-Type: application/json');

final class DeleteUserByIdCest
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

    public function tryToDeleteUserById(ApiTester $I): void
    {
        $userId = $this->data['id'];

        $I->sendDelete("/api/users/$userId");
        $I->seeResponseCodeIs(204);
        $response = $I->grabResponse();
        $I->assertEmpty($response);
    }
}
