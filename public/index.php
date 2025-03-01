<?php

require_once __DIR__ . '/../tests/Support/Helper/logErrors.php';
use function Helper\logError;

header('Content-Type: application/json');

$validUsername = 'admin';
$validPassword = 'P@ssw0rd';

if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] !== $validUsername || $_SERVER['PHP_AUTH_PW'] !== $validPassword) {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized', 'message' => 'Invalid credentials']);
    exit;
}

$uri = $_SERVER['REQUEST_URI'];
$base_url = 'http://localhost:8080';
$method = $_SERVER['REQUEST_METHOD'];
$uriSegments = explode('/', trim($uri, '/'));
$db = new \PDO('sqlite:../database/database.sqlite');


if ($uri == '/api/users') {

    if ($method == 'POST') {
        $inputData = json_decode(file_get_contents('php://input'), true);

        if (isset($inputData['id'], $inputData['firstName'], $inputData['lastName'], $inputData['email'], $inputData['dateOfBirth'], $inputData['personalIdDocument'])) {
            try {
                $stmt = $db->prepare('INSERT INTO User (id, firstName, lastName, email, dateOfBirth, documentId, countryOfIssue, validUntil) 
                VALUES (:id, :firstName, :lastName, :email, :dateOfBirth, :documentId, :countryOfIssue, :validUntil)');
                $stmt->execute([
                    'id' => $inputData['id'],
                    'firstName' => $inputData['firstName'],
                    'lastName' => $inputData['lastName'],
                    'email' => $inputData['email'],
                    'dateOfBirth' => $inputData['dateOfBirth'],
                    'documentId' => $inputData['personalIdDocument']['documentId'],
                    'countryOfIssue' => $inputData['personalIdDocument']['countryOfIssue'],
                    'validUntil' => $inputData['personalIdDocument']['validUntil']
                ]);
                http_response_code(201);
                echo json_encode(['message' => 'User created successfully']);
                exit;
            } catch (\PDOException $e) {
                header('Content-Type: application/problem+json');
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input', 'message' => $e->getMessage()]);
                logError($db, $base_url.$uri, 'Invalid input', '400', 'One or more input fields are invalid. Please check your input and try again', '/users');
                exit;
            }
        } else {
            header('Content-Type: application/problem+json');
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input', 'message' => 'Required fields are missing']);
            logError($db, $base_url.$uri, 'Invalid input', '400', 'Required fields are missing', '/users');
        }
    }
    if ($method === 'GET') {
        $stmt = $db->prepare('SELECT * FROM user');
        $stmt->execute();
        $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response = [
            'message' => 'A list of users',
            'users' => $allUsers
        ];
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }
}
//API requests for a single user /api/users/{id}
if (isset($uriSegments[2]) && $uri == "/api/users/$uriSegments[2]") {

    $stmt = $db->prepare('SELECT * FROM user WHERE id = ?');
    $stmt->execute([$uriSegments[2]]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        header('Content-Type: application/problem+json');
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        logError($db, $base_url . $uri, 'User not found', '404', 'The specified user record does not exist.', '/users');
        exit;
    }
    // GET request - Retrieve single user
    if ($method === 'GET') {
        http_response_code(200);
        echo json_encode(['message' => 'A single user', 'user' => $user]);
        exit;
    }
    // PUT request - Update user
    if ($method === 'PUT') {
        $inputData = json_decode(file_get_contents('php://input'), true);

        if (isset($inputData['id'], $inputData['lastName'], $inputData['email'], $inputData['personalIdDocument'])) {
            try {
                $stmt = $db->prepare('UPDATE User SET lastName = ?, email = ?, documentId = ?, countryOfIssue = ?, validUntil = ? where id = ?');
                $stmt->execute([
                    $inputData['lastName'],
                    $inputData['email'],
                    $inputData['personalIdDocument']['documentId'],
                    $inputData['personalIdDocument']['countryOfIssue'],
                    $inputData['personalIdDocument']['validUntil'],
                    $inputData['id']
                ]);
                http_response_code(200);
                echo json_encode(['message' => 'User updated successfully']);
                exit;
            } catch (\PDOException $e) {
                header('Content-Type: application/problem+json');
                http_response_code(400);
                echo json_encode(['error' => 'Invalid input', 'message' => $e->getMessage()]);
                logError($db, $base_url.$uri, 'Invalid input', '400', 'One or more input fields are invalid. Please check your input and try again', '/users');
                exit;
            }
        }
    }
    // DELETE request - Delete user
    if ($method == 'DELETE') {
        $stmt = $db->prepare('DELETE FROM user WHERE id = ?');
        $stmt->execute([$uriSegments[2]]);
        http_response_code(204);
        echo json_encode(['message' => 'User deleted successfully']);
        exit;
    }
}
