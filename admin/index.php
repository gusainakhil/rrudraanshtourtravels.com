<?php
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
  header('Location: login.php');
  exit;
}

require_once __DIR__ . '/../backend/db.php';

// Get today's date
$today = date('Y-m-d');

// Get count of leads by trip_type for today
$stmt = $pdo->prepare(
  'SELECT trip_type, COUNT(*) as count 
   FROM contact_enquiries 
   WHERE DATE(created_at) = ?
   GROUP BY trip_type'
);
$stmt->execute([$today]);
$todaysLeadsData = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

// Initialize counts
$trekkingCount = 0;
$hotelCount = 0;
$rentalCount = 0;

// Process today's leads
foreach ($todaysLeadsData as $lead) {
  if ($lead['trip_type'] === 'trekking') $trekkingCount = $lead['count'];
  else if ($lead['trip_type'] === 'hotel') $hotelCount = $lead['count'];
  else if ($lead['trip_type'] === 'rental') $rentalCount = $lead['count'];
}

// Get latest lead from each type
$stmt = $pdo->prepare('SELECT full_name FROM contact_enquiries WHERE trip_type = ? AND DATE(created_at) = ? ORDER BY created_at DESC LIMIT 1');

$stmt->execute(['trekking', $today]);
$latestTrekking = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt->execute(['hotel', $today]);
$latestHotel = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt->execute(['rental', $today]);
$latestRental = $stmt->fetch(PDO::FETCH_ASSOC);

$totalLeads = $trekkingCount + $hotelCount + $rentalCount;
$todayFormatted = date('M j');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rudraansh Tours & Travel Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
    <style>
      .todays-leads {
        background: linear-gradient(135deg, var(--mist) 0%, rgba(255, 255, 255, 0.5) 100%);
        border-left: 4px solid var(--gold);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
      }

      .todays-leads h3 {
        color: var(--forest);
        margin-top: 0;
        font-size: 18px;
      }

      .lead-badge {
        display: inline-block;
        background: var(--gold);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-right: 12px;
        margin-bottom: 8px;
      }

      .lead-badge small {
        display: block;
        font-size: 11px;
        font-weight: 400;
        margin-top: 4px;
        opacity: 0.9;
      }
    </style>
  <body data-page="dashboard">
    <div class="bg-noise"></div>
    <main class="admin-layout">
      <?php include 'sidebar.php'; ?>

      <section class="content-area">
        <header class="top-banner">
          <p>Travel Smarter, Travel Better</p>
          <span>Overview for content, SEO, and customer enquiry operations.</span>
        </header>

        <section class="panel-card">
          <h3>Today's Leads Summary</h3>
          <div class="overview-grid kpi-overview" id="todays-leads-grid">
            <article>
              <h4>🏔️ Trekking</h4>
              <p><?php echo $trekkingCount; ?></p>
              <small><?php echo $latestTrekking ? htmlspecialchars($latestTrekking['full_name']) : 'No leads yet'; ?></small>
            </article>
            <article>
              <h4>🏨 Hotels</h4>
              <p><?php echo $hotelCount; ?></p>
              <small><?php echo $latestHotel ? htmlspecialchars($latestHotel['full_name']) : 'No leads yet'; ?></small>
            </article>
            <article>
              <h4>🚗 Car Rental</h4>
              <p><?php echo $rentalCount; ?></p>
              <small><?php echo $latestRental ? htmlspecialchars($latestRental['full_name']) : 'No leads yet'; ?></small>
            </article>
            <article>
              <h4>📊 Total Today</h4>
              <p><?php echo $totalLeads; ?></p>
              <small><?php echo $todayFormatted; ?></small>
            </article>
          </div>
        </section>

        <!-- <section class="panel-card">
          <div class="overview-grid kpi-overview">
            <article>
              <h4>Total Treks</h4>
              <p id="dashboard-trek-count">0</p>
            </article>
           
            <article>
              <h4>Total Quotations</h4>
              <p id="dashboard-quotation-count">0</p>
            </article>
        

 
          </div>
        </section> -->

        <section class="panel-card">
          <h3>Latest Records</h3>
          <div class="overview-grid">
            <article>
              <h4>Latest Trek</h4>
              <p id="dashboard-latest-trek">No trek uploaded yet.</p>
            </article>
            <article>
              <h4>Latest Blog</h4>
              <p id="dashboard-latest-blog">No blog uploaded yet.</p>
            </article>
          </div>
        </section>
      </section>
    </main>

    <script src="app.js"></script>
  </body>
</html>
