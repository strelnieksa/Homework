<?php

declare(strict_types=1);

namespace Tests\Api;

header('Content-Type: application/json');

use Tests\Support\ApiTester;

final class GetAllUsersCest
{
    public function tryToGetAllUsers(ApiTester $I): void
    {
        $auth = base64_encode('admin:P@ssw0rd');
        $I->haveHttpHeader('Authorization', 'Basic ' . $auth);

        $I->sendGet('/api/users');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson(['message' => 'A list of users']);
        $I->seeResponseIsJson();
    }
}
