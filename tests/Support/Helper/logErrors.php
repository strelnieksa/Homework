<?php

namespace Helper;

use PDO;
use PDOException;

function logError(PDO $db, string $type, string $title, string $status, string $detail, string $instance): void
{
    try {
        $stmt = $db->prepare('INSERT INTO ProblemDetails (type, title, status, detail, instance) VALUES (:type, :title, :status, :detail, :instance)');
        $stmt->execute([
            'type' => $type,
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
            'instance' => $instance
        ]);
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
    }
}
