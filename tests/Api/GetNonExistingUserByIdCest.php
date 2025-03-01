<?php

declare(strict_types=1);

namespace Tests\Api;

namespace Helper;

use Tests\Support\ApiTester;
use Helper\CreateNewUser;

header('Content-Type: application/problem+json');

final class GetNonExistingUserByIdCest
{


    public function tryToGetNonExistingUserById(ApiTester $I): void
    {
        $auth = base64_encode('admin:P@ssw0rd');
        $I->haveHttpHeader('Authorization', 'Basic ' . $auth);

        $I->sendGet('/api/users/a');
        $I->seeResponseCodeIs(404);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'User not found']);
        $response = $I->grabResponse();

        codecept_debug("User Info Response: " . json_encode(json_decode($response), JSON_PRETTY_PRINT));
    }
}
