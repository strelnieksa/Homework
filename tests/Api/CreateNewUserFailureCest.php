<?php

declare(strict_types=1);

namespace Tests\Api;

header('Content-Type: application/problem+json');

use Tests\Support\ApiTester;

final class CreateNewUserFailCest
{
    public function tryToCreateNewUserInvalidInput(ApiTester $I): void
    {
        $userID = bin2hex(random_bytes(8));
        $data = [
            'id' => "$userID",
            'firstName' => 'Sara',
            'lastName' => 'Landscape',
            'email' => 'sara.lgmail.com', // incorrect email
            'dateOfBirth' => '1989-10-03',
            'personalIdDocument' => [
                'documentId' => 'AB75444',
                'countryOfIssue' => 'IT',
                'validUntil' => '2075-09-23'
            ]
        ];

        $auth = base64_encode('admin:P@ssw0rd');
        $I->haveHttpHeader('Authorization', 'Basic ' . $auth);

        $I->sendPOST('/api/users', json_encode($data));
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson(['error' => 'Invalid input']);
        codecept_debug($I->grabResponse());
    }
    public function tryToCreateNewUserMissingRequiredFields(ApiTester $I): void
    {
        $userID = bin2hex(random_bytes(8));
        $data = [
            'id' => "$userID",
            'firstName' => 'Sara',
            'lastName' => 'Landscape',
            'email' => 'sara.@lgmail.com',
            // 'dateOfBirth' => '1989-10-03', // Field is not passed to force error
            'personalIdDocument' => [
                'documentId' => 'AB75444',
                'countryOfIssue' => 'IT',
                'validUntil' => '2075-09-23'
            ]
        ];

        $auth = base64_encode('admin:P@ssw0rd');
        $I->haveHttpHeader('Authorization', 'Basic ' . $auth);

        $I->sendPOST('/api/users', json_encode($data));
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson(['error' => 'Invalid input', 'message' => 'Required fields are missing']);
        codecept_debug($I->grabResponse());
    }
}
