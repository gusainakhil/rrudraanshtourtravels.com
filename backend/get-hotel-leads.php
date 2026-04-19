<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

require_once __DIR__ . '/db.php';

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;
$offset = ($page - 1) * $limit;

try {
  // Get total count
  $countStmt = $pdo->prepare('SELECT COUNT(*) as total FROM contact_enquiries WHERE trip_type = ?');
  $countStmt->execute(['hotel']);
  $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

  // Get paginated hotel leads
  $stmt = $pdo->prepare(
    'SELECT id, full_name, phone, email, travellers, travel_date, trip_duration, message, created_at, source_page
     FROM contact_enquiries
     WHERE trip_type = ?
     ORDER BY created_at DESC
     LIMIT :limit OFFSET :offset'
  );
  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute(['hotel']);
  $leads = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

  $totalPages = ceil($totalCount / $limit);

  echo json_encode([
    'leads' => $leads,
    'pagination' => [
      'page' => $page,
      'limit' => $limit,
      'total' => $totalCount,
      'pages' => $totalPages
    ]
  ]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
