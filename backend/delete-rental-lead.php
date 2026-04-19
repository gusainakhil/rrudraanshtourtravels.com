<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}


require_once __DIR__ . '/db.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if (!$id) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid lead ID']);
  exit;
}

try {
  $stmt = $pdo->prepare('DELETE FROM contact_enquiries WHERE id = ? AND trip_type = "rental"');
  $stmt->execute([$id]);
  
  if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true, 'message' => 'Lead deleted successfully']);
  } else {
    http_response_code(404);
    echo json_encode(['error' => 'Lead not found']);
  }
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database error']);
}
