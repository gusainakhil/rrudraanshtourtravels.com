<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

try {
    require_once __DIR__ . '/db.php';

    $slug = trim((string)($_GET['slug'] ?? ''));

    if ($slug !== '') {
        $trekStmt = $pdo->prepare(
            'SELECT t.id, t.title, t.category, t.region, t.state, t.trek_type, t.duration, t.altitude, t.difficulty,
                    t.price, t.group_size, t.best_season, t.image, t.gallery_images_json, t.description, t.overview,
                    t.inclusions, t.exclusions, t.slug,
                    s.meta_title, s.meta_description, s.keywords, s.og_title, s.og_description, s.og_image
             FROM treks t
             LEFT JOIN trek_seo s ON s.trek_id = t.id
             WHERE t.slug = :slug
             LIMIT 1'
        );
        $trekStmt->execute([':slug' => $slug]);
    } else {
        $trekStmt = $pdo->query(
            'SELECT t.id, t.title, t.category, t.region, t.state, t.trek_type, t.duration, t.altitude, t.difficulty,
                    t.price, t.group_size, t.best_season, t.image, t.gallery_images_json, t.description, t.overview,
                    t.inclusions, t.exclusions, t.slug,
                    s.meta_title, s.meta_description, s.keywords, s.og_title, s.og_description, s.og_image
             FROM treks t
             LEFT JOIN trek_seo s ON s.trek_id = t.id
             ORDER BY t.updated_at DESC
             LIMIT 1'
        );
    }

    $trek = $trekStmt->fetch(PDO::FETCH_ASSOC);
    if (!$trek) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Trek not found.',
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    $gallery = [];
    $galleryRaw = trim((string)($trek['gallery_images_json'] ?? ''));
    if ($galleryRaw !== '') {
        $decoded = json_decode($galleryRaw, true);
        if (is_array($decoded)) {
            foreach ($decoded as $image) {
                $imagePath = trim((string)$image);
                if ($imagePath !== '') {
                    $gallery[] = $imagePath;
                }
            }
        }
    }

    $itineraryStmt = $pdo->prepare(
        'SELECT day_label, title, details
         FROM trek_itinerary
         WHERE trek_id = :trek_id
         ORDER BY sort_order ASC, itinerary_id ASC'
    );
    $itineraryStmt->execute([':trek_id' => $trek['id']]);
    $itineraryRows = $itineraryStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    $faqStmt = $pdo->prepare(
        'SELECT question, answer
         FROM trek_faq
         WHERE trek_id = :trek_id
         ORDER BY sort_order ASC, faq_id ASC'
    );
    $faqStmt->execute([':trek_id' => $trek['id']]);
    $faqRows = $faqStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    unset($trek['gallery_images_json']);

    echo json_encode([
        'success' => true,
        'data' => [
            'trek' => $trek,
            'gallery' => $gallery,
            'itinerary' => $itineraryRows,
            'faq' => $faqRows,
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to load trek details.',
        'error' => $e->getMessage(),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
