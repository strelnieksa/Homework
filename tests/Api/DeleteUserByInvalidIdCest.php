<?php

declare(strict_types=1);

namespace Tests\Api;

namespace Helper;

use Tests\Support\ApiTester;

header('Content-Type: application/problem+json');

header('Content-Type: application/json');

final class DeleteUserByInvalidIdCest
{
    public function tryToDeleteUserByInvalidId(ApiTester $I): void
    {
        $auth = base64_encode('admin:P@ssw0rd');
        $I->haveHttpHeader('Authorization', 'Basic ' . $auth);

        $userId = 'a'; // invalid user id
        $I->sendDelete("/api/users/$userId");
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'User not found']);
    }
}
