<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Only POST method is allowed.',
    ]);
    exit;
}

try {
    require __DIR__ . '/db.php';

    $rawBody = file_get_contents('php://input');
    $payload = json_decode($rawBody ?: '', true);

    if (!is_array($payload)) {
        throw new InvalidArgumentException('Invalid request payload.');
    }

    $tripType = strtolower(trim((string)($payload['trip_type'] ?? '')));
    $allowedTripTypes = ['trekking', 'hotel', 'rental'];
    if (!in_array($tripType, $allowedTripTypes, true)) {
        throw new InvalidArgumentException('Please select a valid trip type.');
    }

    $fullName = trim((string)($payload['full_name'] ?? ''));
    $phone = trim((string)($payload['phone'] ?? ''));
    $email = trim((string)($payload['email'] ?? ''));
    $travellers = trim((string)($payload['travellers'] ?? ''));
    $travelDate = trim((string)($payload['travel_date'] ?? ''));
    $tripDuration = trim((string)($payload['trip_duration'] ?? ''));
    $message = trim((string)($payload['message'] ?? ''));
    $consent = (bool)($payload['consent'] ?? false);

    if ($fullName === '' || $phone === '' || $email === '') {
        throw new InvalidArgumentException('Name, phone, and email are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Please enter a valid email address.');
    }

    if (!$consent) {
        throw new InvalidArgumentException('Consent is required to submit the form.');
    }

    $travelDateSql = null;
    if ($travelDate !== '') {
        $date = DateTime::createFromFormat('Y-m-d', $travelDate);
        $isValidDate = $date && $date->format('Y-m-d') === $travelDate;
        if (!$isValidDate) {
            throw new InvalidArgumentException('Travel date format is invalid.');
        }
        $travelDateSql = $travelDate;
    }

    $sql = 'INSERT INTO contact_enquiries (
                trip_type,
                full_name,
                phone,
                email,
                travellers,
                travel_date,
                trip_duration,
                message,
                consent,
                source_page,
                user_agent,
                ip_address
            ) VALUES (
                :trip_type,
                :full_name,
                :phone,
                :email,
                :travellers,
                :travel_date,
                :trip_duration,
                :message,
                :consent,
                :source_page,
                :user_agent,
                :ip_address
            )';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':trip_type' => $tripType,
        ':full_name' => mb_substr($fullName, 0, 120),
        ':phone' => mb_substr($phone, 0, 25),
        ':email' => mb_substr($email, 0, 190),
        ':travellers' => $travellers !== '' ? mb_substr($travellers, 0, 80) : null,
        ':travel_date' => $travelDateSql,
        ':trip_duration' => $tripDuration !== '' ? mb_substr($tripDuration, 0, 60) : null,
        ':message' => $message !== '' ? mb_substr($message, 0, 2000) : null,
        ':consent' => $consent ? 1 : 0,
        ':source_page' => 'contact.html',
        ':user_agent' => mb_substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
        ':ip_address' => mb_substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Form submitted successfully.',
        'id' => (int)$pdo->lastInsertId(),
    ]);
} catch (InvalidArgumentException $e) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error. Please try again in a moment.',
    ]);
}
