<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

try {
    require_once __DIR__ . '/db.php';

    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    if ($limit <= 0) {
        $limit = 50;
    }
    if ($limit > 200) {
        $limit = 200;
    }

    $stmt = $pdo->prepare(
        'SELECT id, title, category, region, state, trek_type, duration, altitude, difficulty, price, group_size, best_season, image, description, slug
         FROM treks
         ORDER BY updated_at DESC
         LIMIT :limit'
    );
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $treks = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    echo json_encode([
        'success' => true,
        'data' => $treks,
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to load treks.',
        'error' => $e->getMessage(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
