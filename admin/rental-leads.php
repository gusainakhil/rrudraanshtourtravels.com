<?php
session_start();
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
  header('Location: login.php');
  exit;
}

require_once __DIR__ . '/../backend/db.php';

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Get total count
$stmt = $pdo->prepare('SELECT COUNT(*) as count FROM contact_enquiries WHERE trip_type = "rental"');
$stmt->execute();
$totalCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'] ?? 0;
$totalPages = ceil($totalCount / $limit);

// Get rental leads
$stmt = $pdo->prepare(
  'SELECT id, full_name, phone, email, travellers, travel_date, trip_duration, message, created_at, source_page
   FROM contact_enquiries
   WHERE trip_type = "rental"
   ORDER BY created_at DESC
   LIMIT :limit OFFSET :offset'
);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$leads = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Car Rental Leads | Rudraansh Tours & Travel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="styles.css" />
    <style>
      .lead-card {
        background: white;
        border: 1px solid var(--line);
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
      }

      .lead-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        background: var(--mist);
      }

      .lead-info {
        flex: 1;
        display: grid;
        grid-template-columns: 2fr 1.5fr 1.5fr 0.8fr;
        gap: 12px;
        align-items: center;
      }

      .lead-item {
        display: flex;
        flex-direction: column;
      }

      .lead-label {
        font-size: 10px;
        font-weight: 600;
        color: var(--forest);
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }

      .lead-value {
        font-size: 13px;
        color: var(--ink);
        font-weight: 500;
        margin-top: 2px;
      }

      .lead-value a {
        color: var(--gold);
        text-decoration: none;
      }

      .lead-value a:hover {
        text-decoration: underline;
      }

      .lead-actions {
        display: flex;
        gap: 8px;
        margin-left: 12px;
      }

      .btn-small {
        padding: 6px 10px;
        font-size: 12px;
        border: 1px solid var(--gold);
        background: white;
        color: var(--gold);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
        white-space: nowrap;
      }

      .btn-small:hover {
        background: var(--gold);
        color: white;
      }

      .btn-delete {
        border-color: #e74c3c;
        color: #e74c3c;
        background: white;
      }

      .btn-delete:hover {
        background: #e74c3c;
        color: white;
      }

      .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
      }

      .modal-overlay.active {
        display: flex;
      }

      .modal-content {
        background: white;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
      }

      .modal-header {
        padding: 16px 20px;
        border-bottom: 2px solid var(--line);
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .modal-header h3 {
        margin: 0;
        color: var(--forest);
        font-size: 18px;
      }

      .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #999;
      }

      .modal-body {
        padding: 20px;
      }

      .details-group {
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--line);
      }

      .details-group:last-child {
        border-bottom: none;
      }

      .detail-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--forest);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
      }

      .detail-value {
        font-size: 14px;
        color: var(--ink);
        line-height: 1.5;
        white-space: pre-wrap;
        word-wrap: break-word;
      }

      .pagination {
        display: flex;
        gap: 8px;
        justify-content: center;
        margin-top: 20px;
        flex-wrap: wrap;
      }

      .pag-btn {
        padding: 8px 12px;
        background: white;
        border: 1px solid var(--line);
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
      }

      .pag-btn:hover {
        background: var(--mist);
        border-color: var(--gold);
      }

      .pag-btn.active {
        background: var(--gold);
        color: white;
        border-color: var(--gold);
      }

      .empty-state {
        text-align: center;
        padding: 40px;
        background: var(--mist);
        border-radius: 8px;
        color: var(--ink);
      }

      .leads-count {
        font-size: 13px;
        color: #999;
        margin-bottom: 12px;
      }

      .leads-list-header {
        background: var(--mist);
        padding: 10px 12px;
        border-radius: 6px;
        margin-bottom: 10px;
        display: grid;
        grid-template-columns: 2fr 1.5fr 1.5fr 0.8fr;
        gap: 12px;
        font-size: 11px;
        font-weight: 700;
        color: var(--forest);
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }

      @media (max-width: 1200px) {
        .lead-info {
          grid-template-columns: 1fr 1fr;
        }
        .leads-list-header {
          grid-template-columns: 1fr 1fr;
        }
      }
    </style>
  </head>
  <body data-page="rental-leads">
    <div class="bg-noise"></div>
    <main class="admin-layout">
     <?php include 'sidebar.php'; ?>

      <section class="content-area">
        <header class="top-banner">
          <p>Car Rental Leads</p>
          <span>Total leads: <?php echo $totalCount; ?></span>
        </header>

        <section class="panel-card">
          <div class="section-head">
            <h3>All Rental Enquiries</h3>
          </div>

          <div class="leads-count">
            Showing <?php echo count($leads) > 0 ? ($offset + 1) : 0; ?> to <?php echo min($offset + count($leads), $totalCount); ?> of <?php echo $totalCount; ?> leads
          </div>

          <?php if ($totalCount > 0 && count($leads) > 0): ?>
            <div class="leads-list">
              <div class="leads-list-header">
                <div>Name</div>
                <div>Phone</div>
                <div>Email</div>
                <div>Actions</div>
              </div>

              <?php foreach ($leads as $lead): 
                $createdDate = new DateTime($lead['created_at']);
                $formattedDate = $createdDate->format('d M Y, H:i');
                $travelDate = $lead['travel_date'] ? date('d M Y', strtotime($lead['travel_date'])) : 'Not specified';
              ?>
                <div class="lead-card">
                  <div class="lead-info">
                    <div class="lead-item">
                      <span class="lead-label">Name</span>
                      <span class="lead-value"><?php echo htmlspecialchars($lead['full_name']); ?></span>
                    </div>
                    <div class="lead-item">
                      <span class="lead-label">Phone</span>
                      <span class="lead-value">
                        <a href="tel:<?php echo htmlspecialchars($lead['phone']); ?>"><?php echo htmlspecialchars($lead['phone']); ?></a>
                      </span>
                    </div>
                    <div class="lead-item">
                      <span class="lead-label">Email</span>
                      <span class="lead-value">
                        <a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>"><?php echo htmlspecialchars($lead['email']); ?></a>
                      </span>
                    </div>
                    <div class="lead-item">
                      <span class="lead-label">Added</span>
                      <span class="lead-value"><?php echo $formattedDate; ?></span>
                    </div>
                  </div>
                  <div class="lead-actions">
                    <button class="btn-small" onclick="showDetails(<?php echo $lead['id']; ?>, '<?php echo htmlspecialchars($lead['full_name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($lead['phone'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($lead['email'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($lead['travellers'] ?? 'N/A', ENT_QUOTES); ?>', '<?php echo $travelDate; ?>', '<?php echo htmlspecialchars($lead['trip_duration'] ?? 'N/A', ENT_QUOTES); ?>', '<?php echo htmlspecialchars($lead['source_page'] ?? 'N/A', ENT_QUOTES); ?>', '<?php echo htmlspecialchars($lead['message'] ?? '', ENT_QUOTES); ?>')">View Details</button>
                    <button class="btn-small btn-delete" onclick="deleteLead(<?php echo $lead['id']; ?>, '<?php echo htmlspecialchars($lead['full_name'], ENT_QUOTES); ?>')">Delete</button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
              <div class="pagination">
                <?php if ($page > 1): ?>
                  <a href="?page=1" class="pag-btn">« First</a>
                  <a href="?page=<?php echo $page - 1; ?>" class="pag-btn">← Previous</a>
                <?php endif; ?>

                <?php 
                  $start = max(1, $page - 2);
                  $end = min($totalPages, $page + 2);
                  
                  for ($i = $start; $i <= $end; $i++):
                ?>
                  <?php if ($i === $page): ?>
                    <button class="pag-btn active"><?php echo $i; ?></button>
                  <?php else: ?>
                    <a href="?page=<?php echo $i; ?>" class="pag-btn"><?php echo $i; ?></a>
                  <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                  <a href="?page=<?php echo $page + 1; ?>" class="pag-btn">Next →</a>
                  <a href="?page=<?php echo $totalPages; ?>" class="pag-btn">Last »</a>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <div class="empty-state">
              <p>No rental leads found in database.</p>
              <p style="font-size: 12px; color: #999; margin-top: 10px;">Total records: <?php echo $totalCount; ?></p>
            </div>
          <?php endif; ?>
        </section>
      </section>
    </main>

    <!-- Details Modal -->
    <div class="modal-overlay" id="detailsModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3>Lead Details</h3>
          <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
          <!-- Details will be inserted here -->
        </div>
      </div>
    </div>

    <script>
      function showDetails(id, name, phone, email, travellers, travelDate, duration, source, message) {
        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `
          <div class="details-group">
            <div class="detail-label">📝 Full Name</div>
            <div class="detail-value">${name}</div>
          </div>
          <div class="details-group">
            <div class="detail-label">📞 Phone</div>
            <div class="detail-value"><a href="tel:${phone}" style="color: var(--gold);">${phone}</a></div>
          </div>
          <div class="details-group">
            <div class="detail-label">📧 Email</div>
            <div class="detail-value"><a href="mailto:${email}" style="color: var(--gold);">${email}</a></div>
          </div>
          <div class="details-group">
            <div class="detail-label">👥 Number of Travellers</div>
            <div class="detail-value">${travellers}</div>
          </div>
          <div class="details-group">
            <div class="detail-label">📅 Travel Date</div>
            <div class="detail-value">${travelDate}</div>
          </div>
          <div class="details-group">
            <div class="detail-label">⏱️ Trip Duration</div>
            <div class="detail-value">${duration}</div>
          </div>
          <div class="details-group">
            <div class="detail-label">📄 Source Page</div>
            <div class="detail-value">${source}</div>
          </div>
          ${message ? `
            <div class="details-group">
              <div class="detail-label">💬 Message / Notes</div>
              <div class="detail-value">${message}</div>
            </div>
          ` : ''}
        `;
        document.getElementById('detailsModal').classList.add('active');
      }

      function closeModal() {
        document.getElementById('detailsModal').classList.remove('active');
      }

      function deleteLead(id, name) {
        if (confirm(`Are you sure you want to delete the lead for ${name}? This action cannot be undone.`)) {
          fetch('../backend/delete-rental-lead.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Lead deleted successfully');
              location.reload();
            } else {
              alert('Error: ' + (data.error || 'Failed to delete lead'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Error deleting lead');
          });
        }
      }

      // Close modal when clicking outside
      document.getElementById('detailsModal').addEventListener('click', function(e) {
        if (e.target === this) {
          closeModal();
        }
      });
    </script>
  </body>
</html>
