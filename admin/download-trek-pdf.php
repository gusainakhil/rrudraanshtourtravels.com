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

function clean_rich_html(string $html): string
{
  return strip_tags($html, '<p><br><strong><b><em><i><u><ul><ol><li><h1><h2><h3><h4><blockquote><a>');
}

$trekId = trim((string)($_GET['id'] ?? ''));
if ($trekId === '') {
  http_response_code(400);
  echo 'Missing trek id.';
  exit;
}

$stmt = $pdo->prepare(
  'SELECT t.*, s.canonical_url, s.meta_title, s.meta_description, s.keywords, s.og_title, s.og_description
   FROM treks t
   LEFT JOIN trek_seo s ON s.trek_id = t.id
   WHERE t.id = :id
   LIMIT 1'
);
$stmt->execute([':id' => $trekId]);
$trek = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$trek) {
  http_response_code(404);
  echo 'Trek not found.';
  exit;
}

$itineraryStmt = $pdo->prepare(
  'SELECT day_label, title, details
   FROM trek_itinerary
   WHERE trek_id = :id
   ORDER BY sort_order ASC, itinerary_id ASC'
);
$itineraryStmt->execute([':id' => $trekId]);
$itineraryRows = $itineraryStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

$faqStmt = $pdo->prepare(
  'SELECT question, answer
   FROM trek_faq
   WHERE trek_id = :id
   ORDER BY sort_order ASC, faq_id ASC'
);
$faqStmt->execute([':id' => $trekId]);
$faqRows = $faqStmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

$slug = trim((string)($trek['slug'] ?? ''));
if ($slug === '') {
  $slug = strtolower(trim((string)($trek['title'] ?? 'trek-pdf')));
  $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug) ?? 'trek-pdf';
  $slug = preg_replace('/[\s-]+/', '-', $slug) ?? 'trek-pdf';
  $slug = trim($slug, '-');
  if ($slug === '') {
    $slug = 'trek-pdf';
  }
}

$fileName = $slug . '-trek.pdf';
$overviewHtml = clean_rich_html((string)($trek['overview'] ?? ''));
$generatedOn = date('d M Y, h:i A');

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo h($fileName); ?></title>
    <style>
      :root { color-scheme: light; }
      * { box-sizing: border-box; }
      body {
        margin: 0;
        font-family: "Segoe UI", Tahoma, sans-serif;
        color: #1f2a24;
        background: #f6f7f8;
      }
      .toolbar {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #ffffff;
        border-bottom: 1px solid #d7dde2;
        padding: 10px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .print-btn {
        border: 1px solid #2b6f50;
        background: #e9f5ee;
        color: #1d5139;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
      }
      .sheet {
        width: 210mm;
        min-height: 297mm;
        margin: 14px auto 26px;
        background: #fff;
        border: 1px solid #d5dde2;
        border-radius: 10px;
        padding: 20mm 16mm;
      }
      h1 { margin: 0 0 8px; font-size: 28px; color: #1f3f31; }
      h2 {
        margin: 22px 0 10px;
        font-size: 18px;
        border-bottom: 1px solid #d9e3dd;
        padding-bottom: 6px;
        color: #275440;
      }
      p { margin: 0 0 10px; line-height: 1.55; font-size: 14px; }
      .meta-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px 16px;
        margin-bottom: 12px;
      }
      .meta-item { font-size: 13px; color: #32433a; }
      .meta-label { font-weight: 700; color: #1c352a; }
      .block {
        border: 1px solid #dfe6e2;
        background: #fbfdfc;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 10px;
      }
      .tag {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 999px;
        border: 1px solid #bdd2c7;
        background: #f0f7f3;
        font-size: 11px;
        font-weight: 700;
        color: #24523f;
      }
      .footer-note {
        margin-top: 18px;
        font-size: 11px;
        color: #617369;
      }
      ul { margin: 8px 0 0 20px; }
      .ql-content p:last-child { margin-bottom: 0; }
      @media print {
        body { background: #fff; }
        .toolbar { display: none; }
        .sheet {
          width: auto;
          min-height: auto;
          margin: 0;
          border: 0;
          border-radius: 0;
          padding: 0;
        }
      }
    </style>
  </head>
  <body>
    <div class="toolbar">
      <div><strong>Trek PDF View</strong> (DB-generated)</div>
      <button class="print-btn" type="button" onclick="window.print()">Download / Print PDF</button>
    </div>

    <main class="sheet">
      <h1><?php echo h((string)($trek['title'] ?? 'Trek Details')); ?></h1>
      <p><span class="tag"><?php echo h((string)($trek['category'] ?: 'Domestic')); ?></span> <span class="tag"><?php echo h((string)($trek['trek_type'] ?: 'Trek')); ?></span></p>

      <section>
        <h2>Overview</h2>
        <div class="meta-grid">
          <div class="meta-item"><span class="meta-label">Region:</span> <?php echo h((string)($trek['region'] ?: 'N/A')); ?></div>
          <div class="meta-item"><span class="meta-label">State:</span> <?php echo h((string)($trek['state'] ?: 'N/A')); ?></div>
          <div class="meta-item"><span class="meta-label">Duration:</span> <?php echo h((string)($trek['duration'] ?: 'N/A')); ?></div>
          <div class="meta-item"><span class="meta-label">Difficulty:</span> <?php echo h((string)($trek['difficulty'] ?: 'N/A')); ?></div>
          <div class="meta-item"><span class="meta-label">Altitude:</span> <?php echo h((string)($trek['altitude'] ?: '0')); ?> m</div>
          <div class="meta-item"><span class="meta-label">Price:</span> INR <?php echo h(number_format((float)($trek['price'] ?? 0), 0, '.', ',')); ?></div>
        </div>
        <div class="block">
          <p><?php echo h((string)($trek['description'] ?: 'No description available.')); ?></p>
        </div>
        <?php if (trim($overviewHtml) !== ''): ?>
          <div class="block ql-content"><?php echo $overviewHtml; ?></div>
        <?php endif; ?>
      </section>

      <section>
        <h2>Itinerary</h2>
        <?php if (!$itineraryRows): ?>
          <div class="block"><p>No itinerary added yet.</p></div>
        <?php else: ?>
          <?php foreach ($itineraryRows as $row): ?>
            <div class="block">
              <p><strong><?php echo h((string)($row['day_label'] ?: 'Day')); ?></strong> - <?php echo h((string)($row['title'] ?: 'Untitled')); ?></p>
              <p><?php echo nl2br(h((string)($row['details'] ?? ''))); ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>

      <section>
        <h2>FAQ</h2>
        <?php if (!$faqRows): ?>
          <div class="block"><p>No FAQ added yet.</p></div>
        <?php else: ?>
          <?php foreach ($faqRows as $row): ?>
            <div class="block">
              <p><strong>Q:</strong> <?php echo h((string)($row['question'] ?: 'Question')); ?></p>
              <p><strong>A:</strong> <?php echo h((string)($row['answer'] ?: '')); ?></p>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>

      <section>
        <h2>Inclusions / Exclusions</h2>
        <div class="block">
          <p><strong>Inclusions:</strong> <?php echo h((string)($trek['inclusions'] ?: 'N/A')); ?></p>
          <p><strong>Exclusions:</strong> <?php echo h((string)($trek['exclusions'] ?: 'N/A')); ?></p>
        </div>
      </section>

      <section>
        <h2>SEO</h2>
        <div class="block">
          <p><strong>Slug:</strong> <?php echo h((string)($trek['slug'] ?: 'N/A')); ?></p>
          <p><strong>Canonical URL:</strong> <?php echo h((string)($trek['canonical_url'] ?: 'N/A')); ?></p>
          <p><strong>Meta Title:</strong> <?php echo h((string)($trek['meta_title'] ?: 'N/A')); ?></p>
          <p><strong>Meta Description:</strong> <?php echo h((string)($trek['meta_description'] ?: 'N/A')); ?></p>
          <p><strong>Keywords:</strong> <?php echo h((string)($trek['keywords'] ?: 'N/A')); ?></p>
        </div>
      </section>

      <p class="footer-note">Generated on <?php echo h($generatedOn); ?> from database data for trek ID: <?php echo h($trekId); ?></p>
    </main>
  </body>
</html>
