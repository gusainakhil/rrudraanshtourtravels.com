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

$treks = $pdo->query(
  'SELECT id, title, category, region, trek_type, duration, altitude, difficulty, price, image, description, slug, overview_pdf_name
   FROM treks
   ORDER BY updated_at DESC'
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View and Edit Treks | Rudraansh Tours & Travel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body data-page="trek-management-db">
    <div class="bg-noise"></div>
    <main class="admin-layout">
     <?php include 'sidebar.php'; ?>

      <section class="content-area">
        <header class="top-banner">
          <p>View and Edit Trek Packages</p>
          <span>Open existing records, update trek details, and manage listing visibility.</span>
        </header>

        <section class="panel-card">
          <div class="section-head">
            <h3>Saved Treks</h3>
            <a class="edit-btn" href="treks.php">Open Add or Edit Form</a>
          </div>
          <div id="trek-management-list" class="item-grid">
            <?php if (!$treks): ?>
              <p class="empty">No trek packages added yet.</p>
            <?php else: ?>
              <?php foreach ($treks as $trek): ?>
                <article class="item-card">
                  <?php if (!empty($trek['image'])): ?>
                    <img src="<?php echo h((string)$trek['image']); ?>" alt="<?php echo h((string)$trek['title']); ?>" />
                  <?php endif; ?>
                  <div class="item-card-content">
                    <h4><?php echo h((string)$trek['title']); ?></h4>
                    <p class="meta"><?php echo h((string)($trek['category'] ?: 'Domestic')); ?> | State: <?php echo h((string)($trek['region'] ?: 'N/A')); ?></p>
                    <p class="meta"><?php echo h((string)($trek['duration'] ?: 'N/A')); ?> | <?php echo h((string)($trek['difficulty'] ?: 'N/A')); ?></p>
                    <p class="meta">Altitude <?php echo h((string)($trek['altitude'] ?: '0')); ?>m | INR <?php echo h(number_format((float)($trek['price'] ?? 0), 0, '.', ',')); ?></p>
                    <p><?php echo h((string)($trek['description'] ?: 'No description available.')); ?></p>
                    <p class="meta">SEO Slug: <?php echo h((string)($trek['slug'] ?: 'N/A')); ?></p>
                    <div class="card-actions">
                      <a class="edit-btn small-action-btn" href="treks.php?edit=<?php echo urlencode((string)$trek['id']); ?>" target="_blank" rel="noopener">Edit</a>
                      <?php if (!empty($trek['overview_pdf_name']) && str_starts_with((string)$trek['overview_pdf_name'], 'uploads/itinerary/')): ?>
                        <a class="edit-btn small-action-btn" href="<?php echo h((string)$trek['overview_pdf_name']); ?>" target="_blank" rel="noopener">Itinerary PDF</a>
                      <?php endif; ?>
                      <a class="edit-btn small-action-btn" href="download-trek-pdf.php?id=<?php echo urlencode((string)$trek['id']); ?>" target="_blank" rel="noopener">Trek PDF</a>
                    </div>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </section>
      </section>
    </main>

    <script src="app.js"></script>
  </body>
</html>