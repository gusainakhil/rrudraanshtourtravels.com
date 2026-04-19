<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit;
}

require_once __DIR__ . '/db.php';

try {
  // Get today's date
  $today = date('Y-m-d');
  $nextDay = date('Y-m-d', strtotime('+1 day'));

  // Get count of leads by trip_type for today
  $stmt = $pdo->prepare(
    'SELECT trip_type, COUNT(*) as count 
     FROM contact_enquiries 
     WHERE DATE(created_at) = ?
     GROUP BY trip_type
     ORDER BY count DESC'
  );
  $stmt->execute([$today]);
  $todaysLeads = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

  // Get latest lead from each type (today and overall)
  $latestTrekking = null;
  $latestHotel = null;
  $latestRental = null;

  $stmt = $pdo->prepare(
    'SELECT full_name, phone FROM contact_enquiries 
     WHERE trip_type = ? AND DATE(created_at) = ?
     ORDER BY created_at DESC LIMIT 1'
  );
  
  $stmt->execute(['trekking', $today]);
  $latestTrekking = $stmt->fetch(PDO::FETCH_ASSOC);

  $stmt->execute(['hotel', $today]);
  $latestHotel = $stmt->fetch(PDO::FETCH_ASSOC);

  $stmt->execute(['rental', $today]);
  $latestRental = $stmt->fetch(PDO::FETCH_ASSOC);

  echo json_encode([
    'success' => true,
    'todaysLeads' => $todaysLeads,
    'latestTrekking' => $latestTrekking,
    'latestHotel' => $latestHotel,
    'latestRental' => $latestRental,
    'date' => $today
  ]);

} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
