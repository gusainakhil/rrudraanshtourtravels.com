<?php
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
  header('Location: login.php');
  exit;
}

require_once __DIR__ . '/connection.php';

function h(string $value): string
{
  return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function clean_text(?string $value): string
{
  return trim((string)$value);
}

function clean_nullable_text(?string $value): ?string
{
  $value = trim((string)$value);
  return $value === '' ? null : $value;
}

function generate_trek_id(): string
{
  return 'trek_' . bin2hex(random_bytes(8));
}

function ensure_upload_directory(string $subPath = ''): string
{
  $uploadDir = __DIR__ . '/uploads/treks';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
  }

  $subPath = trim($subPath, '/');
  if ($subPath === '') {
    return $uploadDir;
  }

  $targetDir = $uploadDir . '/' . $subPath;
  if (!is_dir($targetDir)) {
    mkdir($targetDir, 0775, true);
  }

  return $targetDir;
}

function store_uploaded_image(array $file, string $prefix): ?string
{
  $error = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
  $tmpName = (string)($file['tmp_name'] ?? '');
  $originalName = clean_text((string)($file['name'] ?? ''));

  if ($error !== UPLOAD_ERR_OK || $tmpName === '' || !is_uploaded_file($tmpName) || $originalName === '') {
    return null;
  }

  $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
  if ($extension === '') {
    $extension = 'jpg';
  }

  $relativePath = 'uploads/treks/' . $prefix . '_' . bin2hex(random_bytes(8)) . '.' . preg_replace('/[^a-z0-9]/i', '', $extension);
  $destination = __DIR__ . '/' . $relativePath;
  ensure_upload_directory();

  if (!move_uploaded_file($tmpName, $destination)) {
    return null;
  }

  return $relativePath;
}

function store_multiple_uploaded_images(array $files, string $prefix): array
{
  $paths = [];
  $fileCount = is_array($files['name'] ?? null) ? count($files['name']) : 0;

  for ($index = 0; $index < $fileCount; $index++) {
    $file = [
      'name' => $files['name'][$index] ?? '',
      'type' => $files['type'][$index] ?? '',
      'tmp_name' => $files['tmp_name'][$index] ?? '',
      'error' => $files['error'][$index] ?? UPLOAD_ERR_NO_FILE,
      'size' => $files['size'][$index] ?? 0,
    ];
    $path = store_uploaded_image($file, $prefix . '_' . ($index + 1));
    if ($path !== null) {
      $paths[] = $path;
    }
  }

  return $paths;
}

function store_uploaded_pdf(array $file, string $prefix): ?string
{
  $error = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
  $tmpName = (string)($file['tmp_name'] ?? '');
  $originalName = clean_text((string)($file['name'] ?? ''));

  if ($error !== UPLOAD_ERR_OK || $tmpName === '' || !is_uploaded_file($tmpName) || $originalName === '') {
    return null;
  }

  $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
  if ($extension !== 'pdf') {
    return null;
  }

  $pdfDir = __DIR__ . '/uploads/itinerary';
  if (!is_dir($pdfDir)) {
    mkdir($pdfDir, 0775, true);
  }

  $baseName = slugify((string)pathinfo($originalName, PATHINFO_FILENAME));
  if ($baseName === '') {
    $baseName = slugify($prefix);
  }

  $fileName = $baseName . '-' . bin2hex(random_bytes(4)) . '.pdf';
  $relativePath = 'uploads/itinerary/' . $fileName;
  $destination = __DIR__ . '/' . $relativePath;

  if (!move_uploaded_file($tmpName, $destination)) {
    return null;
  }

  return $relativePath;
}

function decode_gallery_images(?string $json): array
{
  $json = trim((string)$json);
  if ($json === '') {
    return [];
  }

  $decoded = json_decode($json, true);
  if (!is_array($decoded)) {
    return [];
  }

  return array_values(array_filter(array_map('clean_text', $decoded), static fn ($value) => $value !== ''));
}

function encode_gallery_images(array $images): string
{
  return json_encode(array_values(array_filter(array_map('clean_text', $images), static fn ($value) => $value !== '')), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

function slugify(string $text): string
{
  $text = strtolower(trim($text));
  $text = preg_replace('/[^a-z0-9\s-]/', '', $text) ?? '';
  $text = preg_replace('/[\s-]+/', '-', $text) ?? '';
  $text = trim($text, '-');
  return $text !== '' ? $text : 'trek';
}

function generate_unique_slug(PDO $pdo, string $title, ?string $excludeId = null): string
{
  $base = slugify($title);
  $slug = $base;
  $counter = 2;

  while (true) {
    if ($excludeId !== null && $excludeId !== '') {
      $stmt = $pdo->prepare('SELECT COUNT(*) FROM treks WHERE slug = :slug AND id <> :id');
      $stmt->execute([':slug' => $slug, ':id' => $excludeId]);
    } else {
      $stmt = $pdo->prepare('SELECT COUNT(*) FROM treks WHERE slug = :slug');
      $stmt->execute([':slug' => $slug]);
    }

    if ((int)$stmt->fetchColumn() === 0) {
      return $slug;
    }

    $slug = $base . '-' . $counter;
    $counter++;
  }
}

function create_blank_form(): array
{
  return [
    'id' => '',
    'title' => '',
    'category' => 'Domestic',
    'region' => '',
    'state' => '',
    'trekType' => '',
    'duration' => '',
    'altitude' => '',
    'difficulty' => 'Easy',
    'price' => '',
    'groupSize' => '',
    'bestSeason' => '',
    'image' => '',
    'galleryImages' => [],
    'description' => '',
    'overview' => '',
    'overviewPdfName' => '',
    'overviewPdfData' => '',
    'itinerary_day' => [''],
    'itinerary_title' => [''],
    'itinerary_details' => [''],
    'faq_question' => [''],
    'faq_answer' => [''],
    'inclusions' => '',
    'exclusions' => '',
    'slug' => '',
    'canonicalUrl' => '',
    'metaTitle' => '',
    'metaDescription' => '',
    'keywords' => '',
    'ogTitle' => '',
    'ogDescription' => '',
    'ogImage' => '',
  ];
}

function normalize_post_array(mixed $value, int $minSize = 1): array
{
  $items = is_array($value) ? array_values($value) : [];
  $normalized = [];
  foreach ($items as $item) {
    $normalized[] = clean_text(is_string($item) ? $item : (string)$item);
  }
  while (count($normalized) < $minSize) {
    $normalized[] = '';
  }
  return $normalized;
}

function fetch_trek_bundle(PDO $pdo, string $trekId): ?array
{
  $stmt = $pdo->prepare(
    'SELECT t.*, s.canonical_url, s.meta_title, s.meta_description, s.keywords, s.og_title, s.og_description, s.og_image
     FROM treks t
     LEFT JOIN trek_seo s ON s.trek_id = t.id
     WHERE t.id = :id
     LIMIT 1'
  );
  $stmt->execute([':id' => $trekId]);
  $trek = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$trek) {
    return null;
  }

  $itineraryStmt = $pdo->prepare(
    'SELECT day_label, title, details
     FROM trek_itinerary
     WHERE trek_id = :id
     ORDER BY sort_order ASC, itinerary_id ASC'
  );
  $itineraryStmt->execute([':id' => $trekId]);
  $trek['itinerary_rows'] = $itineraryStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

  $faqStmt = $pdo->prepare(
    'SELECT question, answer
     FROM trek_faq
     WHERE trek_id = :id
     ORDER BY sort_order ASC, faq_id ASC'
  );
  $faqStmt->execute([':id' => $trekId]);
  $trek['faq_rows'] = $faqStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
  $trek['gallery_images'] = decode_gallery_images($trek['gallery_images_json'] ?? null);

  return $trek;
}

function hydrate_form_from_record(array $record): array
{
  $form = create_blank_form();
  $form['id'] = (string)$record['id'];
  $form['title'] = (string)$record['title'];
  $form['category'] = (string)$record['category'];
  $form['region'] = (string)$record['region'];
  $form['state'] = (string)($record['state'] ?? '');
  $form['trekType'] = (string)$record['trek_type'];
  $form['duration'] = (string)$record['duration'];
  $form['altitude'] = (string)$record['altitude'];
  $form['difficulty'] = (string)$record['difficulty'];
  $form['price'] = (string)$record['price'];
  $form['groupSize'] = (string)($record['group_size'] ?? '');
  $form['bestSeason'] = (string)($record['best_season'] ?? '');
  $form['image'] = (string)$record['image'];
  $form['galleryImages'] = decode_gallery_images($record['gallery_images_json'] ?? null);
  $form['description'] = (string)$record['description'];
  $form['overview'] = (string)($record['overview'] ?? '');
  $form['overviewPdfName'] = (string)($record['overview_pdf_name'] ?? '');
  $form['overviewPdfData'] = (string)($record['overview_pdf_data'] ?? '');
  $form['inclusions'] = (string)($record['inclusions'] ?? '');
  $form['exclusions'] = (string)($record['exclusions'] ?? '');
  $form['slug'] = (string)($record['slug'] ?? '');
  $form['canonicalUrl'] = (string)($record['canonical_url'] ?? '');
  $form['metaTitle'] = (string)($record['meta_title'] ?? '');
  $form['metaDescription'] = (string)($record['meta_description'] ?? '');
  $form['keywords'] = (string)($record['keywords'] ?? '');
  $form['ogTitle'] = (string)($record['og_title'] ?? '');
  $form['ogDescription'] = (string)($record['og_description'] ?? '');
  $form['ogImage'] = (string)($record['og_image'] ?? '');

  $form['itinerary_day'] = [];
  $form['itinerary_title'] = [];
  $form['itinerary_details'] = [];
  foreach ($record['itinerary_rows'] ?? [] as $row) {
    $form['itinerary_day'][] = (string)($row['day_label'] ?? '');
    $form['itinerary_title'][] = (string)($row['title'] ?? '');
    $form['itinerary_details'][] = (string)($row['details'] ?? '');
  }
  if (!$form['itinerary_day']) {
    $form['itinerary_day'] = [''];
    $form['itinerary_title'] = [''];
    $form['itinerary_details'] = [''];
  }

  $form['faq_question'] = [];
  $form['faq_answer'] = [];
  foreach ($record['faq_rows'] ?? [] as $row) {
    $form['faq_question'][] = (string)($row['question'] ?? '');
    $form['faq_answer'][] = (string)($row['answer'] ?? '');
  }
  if (!$form['faq_question']) {
    $form['faq_question'] = [''];
    $form['faq_answer'] = [''];
  }

  return $form;
}

function infer_next_step(array $record): int
{
  $hasStep2Data = false;
  if (trim((string)($record['inclusions'] ?? '')) !== '' || trim((string)($record['exclusions'] ?? '')) !== '') {
    $hasStep2Data = true;
  }
  if (!$hasStep2Data) {
    foreach ($record['itinerary_rows'] ?? [] as $row) {
      if (trim((string)($row['day_label'] ?? '')) !== '' || trim((string)($row['title'] ?? '')) !== '' || trim((string)($row['details'] ?? '')) !== '') {
        $hasStep2Data = true;
        break;
      }
    }
  }
  if (!$hasStep2Data) {
    foreach ($record['faq_rows'] ?? [] as $row) {
      if (trim((string)($row['question'] ?? '')) !== '' || trim((string)($row['answer'] ?? '')) !== '') {
        $hasStep2Data = true;
        break;
      }
    }
  }

  if (!$hasStep2Data) {
    return 2;
  }

  $hasSeo = trim((string)($record['meta_title'] ?? '')) !== ''
    && trim((string)($record['meta_description'] ?? '')) !== ''
    && trim((string)($record['keywords'] ?? '')) !== '';

  return $hasSeo ? 3 : 3;
}

function validate_step_1(array $form): array
{
  $errors = [];
  if ($form['title'] === '') { $errors[] = 'Trek title is required.'; }
  if ($form['region'] === '') { $errors[] = 'County name is required.'; }
  if ($form['trekType'] === '') { $errors[] = 'Trek type is required.'; }
  if ($form['duration'] === '') { $errors[] = 'Duration is required.'; }
  if ($form['altitude'] === '' || !is_numeric($form['altitude']) || (int)$form['altitude'] < 0) { $errors[] = 'Altitude must be valid.'; }
  if ($form['price'] === '' || !is_numeric($form['price']) || (float)$form['price'] < 0) { $errors[] = 'Price must be valid.'; }
  if ($form['image'] === '') { $errors[] = 'Hero image URL is required.'; }
  if ($form['description'] === '') { $errors[] = 'Description is required.'; }
  if ($form['overview'] === '') { $errors[] = 'Overview is required.'; }
  return $errors;
}

function validate_step_3(array $form): array
{
  $errors = [];
  if ($form['metaTitle'] === '') { $errors[] = 'Meta title is required.'; }
  if ($form['metaDescription'] === '') { $errors[] = 'Meta description is required.'; }
  if ($form['keywords'] === '') { $errors[] = 'Keywords are required.'; }
  return $errors;
}

function save_step_1(PDO $pdo, array $form): string
{
  $trekId = $form['id'] !== '' ? $form['id'] : generate_trek_id();
  $slug = generate_unique_slug($pdo, $form['title'], $trekId);

  $existing = fetch_trek_bundle($pdo, $trekId);
  $pdfName = $form['overviewPdfName'] !== '' ? $form['overviewPdfName'] : ($existing['overview_pdf_name'] ?? null);
  $pdfData = $form['overviewPdfData'] !== '' ? $form['overviewPdfData'] : ($existing['overview_pdf_data'] ?? null);

  $featuredImagePath = $form['image'];
  if (isset($_FILES['featuredImage']) && is_array($_FILES['featuredImage'])) {
    $uploadedFeatured = store_uploaded_image($_FILES['featuredImage'], 'featured');
    if ($uploadedFeatured !== null) {
      $featuredImagePath = $uploadedFeatured;
    }
  }

  $galleryImages = decode_gallery_images($existing['gallery_images_json'] ?? null);
  if (isset($_FILES['galleryImages']) && is_array($_FILES['galleryImages'])) {
    $uploadedGallery = store_multiple_uploaded_images($_FILES['galleryImages'], 'gallery');
    if ($uploadedGallery) {
      $galleryImages = array_values(array_unique(array_merge($galleryImages, $uploadedGallery)));
    }
  }

  $stmt = $pdo->prepare(
    'INSERT INTO treks
      (id, title, category, region, state, trek_type, duration, altitude, difficulty, price, group_size, best_season, image, description, overview, overview_pdf_name, overview_pdf_data, gallery_images_json, inclusions, exclusions, slug)
     VALUES
      (:id, :title, :category, :region, :state, :trek_type, :duration, :altitude, :difficulty, :price, :group_size, :best_season, :image, :description, :overview, :overview_pdf_name, :overview_pdf_data, :gallery_images_json, :inclusions, :exclusions, :slug)
     ON DUPLICATE KEY UPDATE
      title = VALUES(title),
      category = VALUES(category),
      region = VALUES(region),
      state = VALUES(state),
      trek_type = VALUES(trek_type),
      duration = VALUES(duration),
      altitude = VALUES(altitude),
      difficulty = VALUES(difficulty),
      price = VALUES(price),
      group_size = VALUES(group_size),
      best_season = VALUES(best_season),
      image = VALUES(image),
      description = VALUES(description),
      overview = VALUES(overview),
      overview_pdf_name = VALUES(overview_pdf_name),
      overview_pdf_data = VALUES(overview_pdf_data),
        gallery_images_json = VALUES(gallery_images_json),
      slug = VALUES(slug)'
  );
  $stmt->execute([
    ':id' => $trekId,
    ':title' => $form['title'],
    ':category' => $form['category'],
    ':region' => $form['region'],
    ':state' => clean_nullable_text($form['state']),
    ':trek_type' => $form['trekType'],
    ':duration' => $form['duration'],
    ':altitude' => (int)$form['altitude'],
    ':difficulty' => $form['difficulty'],
    ':price' => (float)$form['price'],
    ':group_size' => $form['groupSize'] !== '' ? (int)$form['groupSize'] : null,
    ':best_season' => clean_nullable_text($form['bestSeason']),
    ':image' => $featuredImagePath,
    ':description' => $form['description'],
    ':overview' => $form['overview'],
    ':overview_pdf_name' => clean_nullable_text($pdfName),
    ':overview_pdf_data' => clean_nullable_text($pdfData),
    ':gallery_images_json' => encode_gallery_images($galleryImages),
    ':inclusions' => clean_nullable_text($existing['inclusions'] ?? ''),
    ':exclusions' => clean_nullable_text($existing['exclusions'] ?? ''),
    ':slug' => $slug,
  ]);

  return $trekId;
}

function save_step_2(PDO $pdo, string $trekId, array $form): void
{
  $update = $pdo->prepare('UPDATE treks SET inclusions = :inclusions, exclusions = :exclusions WHERE id = :id');
  $update->execute([
    ':inclusions' => clean_nullable_text($form['inclusions']),
    ':exclusions' => clean_nullable_text($form['exclusions']),
    ':id' => $trekId,
  ]);

  $pdo->prepare('DELETE FROM trek_itinerary WHERE trek_id = :id')->execute([':id' => $trekId]);
  $itineraryStmt = $pdo->prepare(
    'INSERT INTO trek_itinerary (trek_id, day_label, title, details, sort_order)
     VALUES (:trek_id, :day_label, :title, :details, :sort_order)'
  );
  $itineraryCount = max(
    count($form['itinerary_day'] ?? []),
    count($form['itinerary_title'] ?? []),
    count($form['itinerary_details'] ?? [])
  );
  for ($i = 0; $i < $itineraryCount; $i++) {
    $day = trim((string)($form['itinerary_day'][$i] ?? ''));
    $title = trim((string)($form['itinerary_title'][$i] ?? ''));
    $details = trim((string)($form['itinerary_details'][$i] ?? ''));
    if ($day === '' && $title === '' && $details === '') {
      continue;
    }
    $itineraryStmt->execute([
      ':trek_id' => $trekId,
      ':day_label' => $day !== '' ? $day : null,
      ':title' => $title !== '' ? $title : null,
      ':details' => $details !== '' ? $details : null,
      ':sort_order' => $i + 1,
    ]);
  }

  $pdo->prepare('DELETE FROM trek_faq WHERE trek_id = :id')->execute([':id' => $trekId]);
  $faqStmt = $pdo->prepare(
    'INSERT INTO trek_faq (trek_id, question, answer, sort_order)
     VALUES (:trek_id, :question, :answer, :sort_order)'
  );
  $faqCount = max(count($form['faq_question'] ?? []), count($form['faq_answer'] ?? []));
  for ($i = 0; $i < $faqCount; $i++) {
    $q = trim((string)($form['faq_question'][$i] ?? ''));
    $a = trim((string)($form['faq_answer'][$i] ?? ''));
    if ($q === '' && $a === '') {
      continue;
    }
    $faqStmt->execute([
      ':trek_id' => $trekId,
      ':question' => $q !== '' ? $q : 'Question',
      ':answer' => $a !== '' ? $a : null,
      ':sort_order' => $i + 1,
    ]);
  }
}

function save_step_3(PDO $pdo, string $trekId, array $form): void
{
  $seoDelete = $pdo->prepare('DELETE FROM trek_seo WHERE trek_id = :id');
  $seoDelete->execute([':id' => $trekId]);

  $seoStmt = $pdo->prepare(
    'INSERT INTO trek_seo
      (trek_id, canonical_url, meta_title, meta_description, keywords, og_title, og_description, og_image)
     VALUES
      (:trek_id, :canonical_url, :meta_title, :meta_description, :keywords, :og_title, :og_description, :og_image)'
  );
  $seoStmt->execute([
    ':trek_id' => $trekId,
    ':canonical_url' => clean_nullable_text($form['canonicalUrl']),
    ':meta_title' => $form['metaTitle'],
    ':meta_description' => $form['metaDescription'],
    ':keywords' => $form['keywords'],
    ':og_title' => clean_nullable_text($form['ogTitle']),
    ':og_description' => clean_nullable_text($form['ogDescription']),
    ':og_image' => clean_nullable_text($form['ogImage']),
  ]);
}

$flashMessage = '';
$flashType = 'success';
$errors = [];
$currentStep = max(1, min(3, (int)($_GET['step'] ?? $_POST['step'] ?? 1)));
$form = create_blank_form();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = clean_text((string)($_POST['action'] ?? 'next'));
  $currentStep = max(1, min(3, (int)($_POST['step'] ?? $currentStep)));

  $form['id'] = clean_text((string)($_POST['id'] ?? ''));
  $form['title'] = clean_text((string)($_POST['title'] ?? ''));
  $form['category'] = clean_text((string)($_POST['category'] ?? 'Domestic'));
  $form['region'] = clean_text((string)($_POST['region'] ?? ''));
  $form['state'] = clean_text((string)($_POST['state'] ?? ''));
  $form['trekType'] = clean_text((string)($_POST['trekType'] ?? ''));
  $form['duration'] = clean_text((string)($_POST['duration'] ?? ''));
  $form['altitude'] = clean_text((string)($_POST['altitude'] ?? ''));
  $form['difficulty'] = clean_text((string)($_POST['difficulty'] ?? 'Easy'));
  $form['price'] = clean_text((string)($_POST['price'] ?? ''));
  $form['groupSize'] = clean_text((string)($_POST['groupSize'] ?? ''));
  $form['bestSeason'] = clean_text((string)($_POST['bestSeason'] ?? ''));
  $form['image'] = clean_text((string)($_POST['image'] ?? ''));
  $form['description'] = clean_text((string)($_POST['description'] ?? ''));
  $form['overview'] = clean_text((string)($_POST['overview'] ?? ''));
  $form['inclusions'] = clean_text((string)($_POST['inclusions'] ?? ''));
  $form['exclusions'] = clean_text((string)($_POST['exclusions'] ?? ''));
  $form['canonicalUrl'] = clean_text((string)($_POST['canonicalUrl'] ?? ''));
  $form['metaTitle'] = clean_text((string)($_POST['metaTitle'] ?? ''));
  $form['metaDescription'] = clean_text((string)($_POST['metaDescription'] ?? ''));
  $form['keywords'] = clean_text((string)($_POST['keywords'] ?? ''));
  $form['ogTitle'] = clean_text((string)($_POST['ogTitle'] ?? ''));
  $form['ogDescription'] = clean_text((string)($_POST['ogDescription'] ?? ''));
  $form['ogImage'] = clean_text((string)($_POST['ogImage'] ?? ''));
  $form['itinerary_day'] = normalize_post_array($_POST['itinerary_day'] ?? [], 1);
  $form['itinerary_title'] = normalize_post_array($_POST['itinerary_title'] ?? [], 1);
  $form['itinerary_details'] = normalize_post_array($_POST['itinerary_details'] ?? [], 1);
  $form['faq_question'] = normalize_post_array($_POST['faq_question'] ?? [], 1);
  $form['faq_answer'] = normalize_post_array($_POST['faq_answer'] ?? [], 1);

  $existingRecord = null;
  if ($form['id'] !== '') {
    $existingRecord = fetch_trek_bundle($pdo, $form['id']);
  }

  if (isset($_FILES['featuredImage']) && is_array($_FILES['featuredImage'])) {
    $uploadedFeatured = store_uploaded_image($_FILES['featuredImage'], 'featured');
    if ($uploadedFeatured !== null) {
      $form['image'] = $uploadedFeatured;
    }
  }

  if ($form['image'] === '' && $existingRecord && !empty($existingRecord['image'])) {
    $form['image'] = (string)$existingRecord['image'];
  }

  if (isset($_FILES['overview_pdf']) && is_array($_FILES['overview_pdf']) && (int)($_FILES['overview_pdf']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
    $tmpName = (string)($_FILES['overview_pdf']['tmp_name'] ?? '');
    $fileName = clean_text((string)($_FILES['overview_pdf']['name'] ?? ''));
    if ($tmpName !== '' && is_uploaded_file($tmpName) && $fileName !== '') {
      $storedPdfPath = store_uploaded_pdf($_FILES['overview_pdf'], 'overview_pdf');
      if ($storedPdfPath !== null) {
        $form['overviewPdfName'] = $storedPdfPath;
      } else {
        $form['overviewPdfName'] = $fileName;
      }
      $form['overviewPdfData'] = base64_encode((string)file_get_contents($tmpName));
    }
  }

  if ($action === 'delete') {
    $deleteId = clean_text((string)($_POST['delete_id'] ?? ''));
    if ($deleteId !== '') {
      $pdo->prepare('DELETE FROM treks WHERE id = :id')->execute([':id' => $deleteId]);
      header('Location: treks.php?deleted=1');
      exit;
    }
    $flashType = 'error';
    $flashMessage = 'Trek id missing for delete.';
  } elseif ($currentStep === 1) {
    $errors = validate_step_1($form);
    if (!$errors) {
      $trekId = save_step_1($pdo, $form);
      header('Location: treks.php?edit=' . urlencode($trekId) . '&step=2');
      exit;
    }
  } elseif ($currentStep === 2) {
    if ($form['id'] === '') {
      $flashType = 'error';
      $flashMessage = 'Step 1 save required before step 2.';
      $currentStep = 1;
    } elseif ($action === 'back') {
      header('Location: treks.php?edit=' . urlencode($form['id']) . '&step=1');
      exit;
    } else {
      save_step_2($pdo, $form['id'], $form);
      header('Location: treks.php?edit=' . urlencode($form['id']) . '&step=3');
      exit;
    }
  } elseif ($currentStep === 3) {
    if ($form['id'] === '') {
      $flashType = 'error';
      $flashMessage = 'Step 1 save required before step 3.';
      $currentStep = 1;
    } elseif ($action === 'back') {
      header('Location: treks.php?edit=' . urlencode($form['id']) . '&step=2');
      exit;
    } else {
      $errors = validate_step_3($form);
      if (!$errors) {
        save_step_3($pdo, $form['id'], $form);
        header('Location: treks.php?saved=1&edit=' . urlencode($form['id']) . '&step=3');
        exit;
      }
    }
  }
}

$editingId = clean_text((string)($_GET['edit'] ?? ''));
if ($editingId !== '') {
  $record = fetch_trek_bundle($pdo, $editingId);
  if ($record) {
    $form = hydrate_form_from_record($record);
    if (!isset($_GET['step'])) {
      $currentStep = infer_next_step($record);
    }
  } else {
    $flashType = 'error';
    $flashMessage = 'Trek record not found.';
  }
}

if (isset($_GET['saved'])) {
  $flashType = 'success';
  $flashMessage = 'Trek saved successfully.';
}
if (isset($_GET['deleted'])) {
  $flashType = 'success';
  $flashMessage = 'Trek deleted successfully.';
}

$treks = $pdo->query(
  'SELECT t.id, t.title, t.category, t.region, t.trek_type, t.price, t.slug, t.updated_at
   FROM treks t
   ORDER BY t.updated_at DESC'
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trek Management | Rudraansh Tours & Travel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body data-page="treks">
    <div class="bg-noise"></div>
    <main class="admin-layout">
      <?php include 'sidebar.php'; ?>

      <section class="content-area">
        <header class="top-banner">
          <p>View and Edit Trek Packages</p>
          <span>Step 1 creates trek in DB directly. Slug is auto-generated from title.</span>
        </header>

        <?php if ($flashMessage !== ''): ?>
          <section class="panel-card" style="margin-bottom:18px; border-color: <?php echo $flashType === 'error' ? '#b21f1f' : '#2f6f3e'; ?>;">
            <p><?php echo h($flashMessage); ?></p>
          </section>
        <?php endif; ?>

        <?php if ($errors): ?>
          <section class="panel-card" style="margin-bottom:18px; border-color:#b21f1f;">
            <p><?php echo h(implode(' ', $errors)); ?></p>
          </section>
        <?php endif; ?>

        <section class="panel-card form-card">
          <h3><?php echo $currentStep === 1 ? 'Update Trek Package' : ($currentStep === 2 ? 'Itinerary & FAQ' : 'SEO Details'); ?></h3>
          <form class="form-grid" method="post" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="id" value="<?php echo h((string)$form['id']); ?>" />
            <input type="hidden" name="step" value="<?php echo (int)$currentStep; ?>" />

            <div class="full form-stepper" role="tablist" aria-label="Trek form steps">
              <span class="step-chip <?php echo $currentStep === 1 ? 'active' : ''; ?>">1. Update Trek Package</span>
              <span class="step-chip <?php echo $currentStep === 2 ? 'active' : ''; ?>">2. Itinerary & FAQ</span>
              <span class="step-chip <?php echo $currentStep === 3 ? 'active' : ''; ?>">3. SEO Details</span>
            </div>

            <div class="full form-step-panel <?php echo $currentStep === 1 ? 'active' : ''; ?>" <?php echo $currentStep === 1 ? '' : 'hidden'; ?>>
              <div class="step-panel-grid">
                <label>
                  Trek Title
                  <input type="text" name="title" value="<?php echo h((string)$form['title']); ?>" placeholder="Kedarnath Trek" required />
                </label>
                <label>
                  Category
                  <select name="category" required>
                    <option value="Domestic" <?php echo $form['category'] === 'Domestic' ? 'selected' : ''; ?>>Domestic</option>
                    <option value="International" <?php echo $form['category'] === 'International' ? 'selected' : ''; ?>>International</option>
                  </select>
                </label>
                <label>
                  County Name
                  <input type="text" name="region" value="<?php echo h((string)$form['region']); ?>" placeholder="Uttarakhand" required />
                </label>
                <label>
                  State Name
                  <input type="text" name="state" value="<?php echo h((string)$form['state']); ?>" placeholder="Uttarakhand" />
                </label>
                <label>
                  Trek Type
                  <select name="trekType" required>
                    <?php
                    $trekTypes = ['Most Popular','Popular','Adventure','Hillstation','Winter','Wildlife','Family','Pilgrimage','Honeymoon Hit','Spiritual','Heritage','Best Value','Romantic'];
                    foreach ($trekTypes as $type) {
                      $selected = $form['trekType'] === $type ? 'selected' : '';
                      echo '<option value="' . h($type) . '" ' . $selected . '>' . h($type) . '</option>';
                    }
                    ?>
                  </select>
                </label>
                <label>
                  Duration (Days)
                  <input type="text" name="duration" value="<?php echo h((string)$form['duration']); ?>" placeholder="6 Days / 5 Nights" required />
                </label>
                <label>
                  Altitude (m)
                  <input type="number" name="altitude" min="0" value="<?php echo h((string)$form['altitude']); ?>" placeholder="3584" required />
                </label>
                <label>
                  Difficulty
                  <select name="difficulty" required>
                    <option value="Easy" <?php echo $form['difficulty'] === 'Easy' ? 'selected' : ''; ?>>Easy</option>
                    <option value="Moderate" <?php echo $form['difficulty'] === 'Moderate' ? 'selected' : ''; ?>>Moderate</option>
                    <option value="Hard" <?php echo $form['difficulty'] === 'Hard' ? 'selected' : ''; ?>>Hard</option>
                  </select>
                </label>
                <label>
                  Price per Person (INR)
                  <input type="number" name="price" min="0" step="0.01" value="<?php echo h((string)$form['price']); ?>" placeholder="8500" required />
                </label>
                <label>
                  Group Size
                  <input type="number" name="groupSize" min="1" value="<?php echo h((string)$form['groupSize']); ?>" placeholder="10" />
                </label>
                <label>
                  Best Season
                  <input type="text" name="bestSeason" value="<?php echo h((string)$form['bestSeason']); ?>" placeholder="May to June, September to October" />
                </label>
                <label class="full">
                  Featured Image
                  <input type="hidden" name="image" value="<?php echo h((string)$form['image']); ?>" />
                  <input type="file" name="featuredImage" accept="image/*" />
                  <?php if ($form['image'] !== ''): ?>
                    <small>Current featured image</small>
                    <div class="featured-preview-wrap" style="margin-top:10px;">
                      <img src="<?php echo h((string)$form['image']); ?>" alt="Featured image" class="featured-thumb" />
                    </div>
                  <?php endif; ?>
                </label>
                <label class="full">
                  Gallery Images (Add at least 4 images)
                  <input type="file" name="galleryImages[]" accept="image/*" multiple />
                  <?php if (!empty($form['galleryImages'])): ?>
                    <small>Current gallery images</small>
                    <div class="image-preview-grid" style="margin-top:10px;">
                      <?php foreach ($form['galleryImages'] as $galleryImage): ?>
                        <img src="<?php echo h((string)$galleryImage); ?>" alt="Gallery image" class="gallery-thumb" />
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </label>
                <label class="full">
                  Short Description
                  <textarea name="description" rows="4" placeholder="A sacred pilgrimage through lush meadows..." required><?php echo h((string)$form['description']); ?></textarea>
                </label>
                <label class="full">
                  Overview
                  <input type="hidden" name="overview" id="overview-input" value="<?php echo h((string)$form['overview']); ?>" />
                  <div id="overview-toolbar" class="overview-toolbar">
                    <span class="ql-formats">
                      <button type="button" class="ql-bold"></button>
                      <button type="button" class="ql-italic"></button>
                      <button type="button" class="ql-underline"></button>
                      <button type="button" class="ql-strike"></button>
                    </span>
                    <span class="ql-formats">
                      <button type="button" class="ql-list" value="ordered"></button>
                      <button type="button" class="ql-list" value="bullet"></button>
                    </span>
                    <span class="ql-formats">
                      <button type="button" class="ql-link"></button>
                    </span>
                  </div>
                  <div id="overview-editor" class="overview-editor"></div>
                </label>
                <label class="full">
                  Upload Itinerary Overview PDF
                  <input type="file" name="overview_pdf" accept="application/pdf,.pdf" />
                  <?php if ($form['overviewPdfName'] !== ''): ?>
                    <small>
                      Current file: <?php echo h((string)$form['overviewPdfName']); ?>
                      <?php if (str_starts_with((string)$form['overviewPdfName'], 'uploads/')): ?>
                        | <a href="<?php echo h((string)$form['overviewPdfName']); ?>" target="_blank" rel="noopener">View PDF</a>
                      <?php endif; ?>
                    </small>
                  <?php endif; ?>
                </label>
              </div>
              <div class="btn-row full">
                <button type="submit" class="btn-primary">Save & Next</button>
              </div>
            </div>

            <div class="full form-step-panel <?php echo $currentStep === 2 ? 'active' : ''; ?>" <?php echo $currentStep === 2 ? '' : 'hidden'; ?>>
              <div class="step-panel-grid">
                <div class="full editor-label-row" style="justify-content:space-between; align-items:center;">
                  <h4 class="subsection-title" style="margin:0;">Trek Itinerary</h4>
                  <button type="button" class="btn-secondary" id="add-itinerary-row">+ Add Day</button>
                </div>
                <div class="full" id="itinerary-container">
                  <?php
                  $itineraryCount = max(count($form['itinerary_day'] ?? []), count($form['itinerary_title'] ?? []), count($form['itinerary_details'] ?? []));
                  if ($itineraryCount < 1) {
                    $itineraryCount = 1;
                  }
                  for ($i = 0; $i < $itineraryCount; $i++):
                  ?>
                    <div class="full step-panel-grid itinerary-row" style="margin-bottom:12px; border:1px solid rgba(255,255,255,0.08); padding:12px; border-radius:12px;">
                      <label>
                        Day Label
                        <input type="text" name="itinerary_day[]" value="<?php echo h((string)($form['itinerary_day'][$i] ?? '')); ?>" placeholder="Day 1" />
                      </label>
                      <label>
                        Title
                        <input type="text" name="itinerary_title[]" value="<?php echo h((string)($form['itinerary_title'][$i] ?? '')); ?>" placeholder="Arrival" />
                      </label>
                      <label class="full">
                        Details
                        <textarea name="itinerary_details[]" rows="3" placeholder="Describe this day..."><?php echo h((string)($form['itinerary_details'][$i] ?? '')); ?></textarea>
                      </label>
                      <div class="full" style="text-align:right;">
                        <button type="button" class="btn-secondary remove-itinerary-row">Remove</button>
                      </div>
                    </div>
                  <?php endfor; ?>
                </div>

                <div class="full editor-label-row" style="justify-content:space-between; align-items:center;">
                  <h4 class="subsection-title" style="margin:0;">FAQ</h4>
                  <button type="button" class="btn-secondary" id="add-faq-row">+ Add FAQ</button>
                </div>
                <div class="full" id="faq-container">
                  <?php
                  $faqCount = max(count($form['faq_question'] ?? []), count($form['faq_answer'] ?? []));
                  if ($faqCount < 1) {
                    $faqCount = 1;
                  }
                  for ($i = 0; $i < $faqCount; $i++):
                  ?>
                    <div class="full step-panel-grid faq-row" style="margin-bottom:12px; border:1px solid rgba(255,255,255,0.08); padding:12px; border-radius:12px;">
                      <label class="full">
                        Question
                        <input type="text" name="faq_question[]" value="<?php echo h((string)($form['faq_question'][$i] ?? '')); ?>" placeholder="Is prior trekking experience required?" />
                      </label>
                      <label class="full">
                        Answer
                        <textarea name="faq_answer[]" rows="3" placeholder="Write the answer..."><?php echo h((string)($form['faq_answer'][$i] ?? '')); ?></textarea>
                      </label>
                      <div class="full" style="text-align:right;">
                        <button type="button" class="btn-secondary remove-faq-row">Remove</button>
                      </div>
                    </div>
                  <?php endfor; ?>
                </div>

                <h4 class="subsection-title full">Inclusions and Exclusions</h4>
                <label class="full">
                  Inclusions
                  <textarea name="inclusions" rows="4" placeholder="Meals, stay, permits, guide support..."><?php echo h((string)$form['inclusions']); ?></textarea>
                </label>
                <label class="full">
                  Exclusions
                  <textarea name="exclusions" rows="4" placeholder="Personal expenses, insurance, porter charges..."><?php echo h((string)$form['exclusions']); ?></textarea>
                </label>
              </div>
              <div class="btn-row full">
                <button type="submit" name="action" value="back" class="btn-secondary">Back</button>
                <button type="submit" name="action" value="next" class="btn-primary">Save & Next</button>
              </div>
            </div>

            <div class="full form-step-panel <?php echo $currentStep === 3 ? 'active' : ''; ?>" <?php echo $currentStep === 3 ? '' : 'hidden'; ?>>
              <div class="step-panel-grid">
                <h4 class="subsection-title full">SEO Details</h4>
                <label>
                  Auto Slug
                  <input type="text" value="<?php echo h((string)$form['slug']); ?>" readonly />
                </label>
                <label>
                  Canonical URL
                  <input type="url" name="canonicalUrl" value="<?php echo h((string)$form['canonicalUrl']); ?>" placeholder="https://rudraanshtours.com/treks/kedarnath-trek" />
                </label>
                <label class="full">
                  Meta Title
                  <input type="text" name="metaTitle" value="<?php echo h((string)$form['metaTitle']); ?>" placeholder="Kedarnath Trek 2026" required />
                </label>
                <label class="full">
                  Meta Description
                  <textarea name="metaDescription" rows="3" placeholder="Plan your Kedarnath trek with certified guides..." required><?php echo h((string)$form['metaDescription']); ?></textarea>
                </label>
                <label class="full">
                  Keywords (comma separated)
                  <input type="text" name="keywords" value="<?php echo h((string)$form['keywords']); ?>" placeholder="kedarnath trek, uttarakhand trek, spiritual trek" required />
                </label>
                <label class="full">
                  OG Title
                  <input type="text" name="ogTitle" value="<?php echo h((string)$form['ogTitle']); ?>" placeholder="Kedarnath Trek - Book Now" />
                </label>
                <label class="full">
                  OG Description
                  <textarea name="ogDescription" rows="3" placeholder="Plan your Kedarnath trek with expert guides, meals, and stay included."><?php echo h((string)$form['ogDescription']); ?></textarea>
                </label>
                <label class="full">
                  OG Image URL
                  <input type="url" name="ogImage" value="<?php echo h((string)$form['ogImage']); ?>" placeholder="https://example.com/og-image.jpg" />
                </label>
              </div>
              <div class="btn-row full">
                <button type="submit" name="action" value="back" class="btn-secondary">Back</button>
                <button type="submit" name="action" value="save" class="btn-primary">Save Trek</button>
              </div>
            </div>
          </form>
        </section>
<!-- 
        <section class="panel-card" style="margin-top:22px;">
          <h3>Saved Treks</h3>
          <div class="table-wrap">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Category</th>
                  <th>Region</th>
                  <th>Type</th>
                  <th>Price</th>
                  <th>Slug</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!$treks): ?>
                  <tr>
                    <td colspan="7">No trek records found.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($treks as $trek): ?>
                    <tr>
                      <td><?php echo h((string)$trek['title']); ?></td>
                      <td><?php echo h((string)$trek['category']); ?></td>
                      <td><?php echo h((string)$trek['region']); ?></td>
                      <td><?php echo h((string)$trek['trek_type']); ?></td>
                      <td><?php echo h((string)$trek['price']); ?></td>
                      <td><?php echo h((string)$trek['slug']); ?></td>
                      <td>
                        <a class="pill-link" href="treks.php?edit=<?php echo urlencode((string)$trek['id']); ?>">Edit</a>
                        <form method="post" style="display:inline-block; margin-left:8px;">
                          <input type="hidden" name="action" value="delete" />
                          <input type="hidden" name="delete_id" value="<?php echo h((string)$trek['id']); ?>" />
                          <button type="submit" class="btn-secondary">Delete</button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section> -->
      </section>
    </main>

    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script>
      (function () {
        const overviewEditorElement = document.getElementById('overview-editor');
        const overviewInput = document.getElementById('overview-input');

        if (overviewEditorElement && overviewInput && typeof Quill !== 'undefined') {
          const quill = new Quill(overviewEditorElement, {
            theme: 'snow',
            modules: {
              toolbar: '#overview-toolbar'
            },
            placeholder: 'Write trek overview here...'
          });

          const initialOverview = overviewInput.value || '';
          if (initialOverview.trim() !== '') {
            quill.root.innerHTML = initialOverview;
          }

          const syncOverview = function () {
            overviewInput.value = quill.root.innerHTML;
          };

          quill.on('text-change', syncOverview);
          syncOverview();

          const form = overviewInput.closest('form');
          if (form) {
            form.addEventListener('submit', syncOverview);
          }
        }
      })();

      (function () {
        const itineraryContainer = document.getElementById('itinerary-container');
        const faqContainer = document.getElementById('faq-container');
        const addItineraryBtn = document.getElementById('add-itinerary-row');
        const addFaqBtn = document.getElementById('add-faq-row');

        if (!itineraryContainer || !faqContainer || !addItineraryBtn || !addFaqBtn) {
          return;
        }

        function itineraryRowHtml() {
          return '' +
            '<div class="full step-panel-grid itinerary-row" style="margin-bottom:12px; border:1px solid rgba(255,255,255,0.08); padding:12px; border-radius:12px;">' +
              '<label>Day Label<input type="text" name="itinerary_day[]" placeholder="Day 1" /></label>' +
              '<label>Title<input type="text" name="itinerary_title[]" placeholder="Arrival" /></label>' +
              '<label class="full">Details<textarea name="itinerary_details[]" rows="3" placeholder="Describe this day..."></textarea></label>' +
              '<div class="full" style="text-align:right;"><button type="button" class="btn-secondary remove-itinerary-row">Remove</button></div>' +
            '</div>';
        }

        function faqRowHtml() {
          return '' +
            '<div class="full step-panel-grid faq-row" style="margin-bottom:12px; border:1px solid rgba(255,255,255,0.08); padding:12px; border-radius:12px;">' +
              '<label class="full">Question<input type="text" name="faq_question[]" placeholder="Is prior trekking experience required?" /></label>' +
              '<label class="full">Answer<textarea name="faq_answer[]" rows="3" placeholder="Write the answer..."></textarea></label>' +
              '<div class="full" style="text-align:right;"><button type="button" class="btn-secondary remove-faq-row">Remove</button></div>' +
            '</div>';
        }

        addItineraryBtn.addEventListener('click', function () {
          itineraryContainer.insertAdjacentHTML('beforeend', itineraryRowHtml());
        });

        addFaqBtn.addEventListener('click', function () {
          faqContainer.insertAdjacentHTML('beforeend', faqRowHtml());
        });

        itineraryContainer.addEventListener('click', function (event) {
          const target = event.target;
          if (!(target instanceof HTMLElement) || !target.classList.contains('remove-itinerary-row')) {
            return;
          }
          const rows = itineraryContainer.querySelectorAll('.itinerary-row');
          if (rows.length <= 1) {
            return;
          }
          const row = target.closest('.itinerary-row');
          if (row) {
            row.remove();
          }
        });

        faqContainer.addEventListener('click', function (event) {
          const target = event.target;
          if (!(target instanceof HTMLElement) || !target.classList.contains('remove-faq-row')) {
            return;
          }
          const rows = faqContainer.querySelectorAll('.faq-row');
          if (rows.length <= 1) {
            return;
          }
          const row = target.closest('.faq-row');
          if (row) {
            row.remove();
          }
        });
      })();
    </script>
  </body>
</html>
