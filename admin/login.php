<?php
session_start();

if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_username'])) {
  header('Location: index.php');
  exit;
}

$loginError = '';
$oldUsername = '';

function complete_login(int $adminId, string $username): void {
  session_regenerate_id(true);
  $_SESSION['admin_id'] = $adminId;
  $_SESSION['admin_username'] = $username;
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $oldUsername = trim((string)($_POST['username'] ?? ''));
  $passwordRaw = (string)($_POST['password'] ?? '');
  $passwordTrimmed = trim($passwordRaw);
  $passwordCleaned = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}\x{00A0}]/u', '', $passwordTrimmed);
  if (!is_string($passwordCleaned)) {
    $passwordCleaned = $passwordTrimmed;
  }

  if ($oldUsername === '' || $passwordTrimmed === '') {
    $loginError = 'Username and password are required.';
  } else {
    require_once __DIR__ . '/connection.php';

    try {
      $normalizedUsername = strtolower($oldUsername);
      $stmt = $pdo->prepare(
        'SELECT admin_id, username, password_hash
         FROM admin_login
         WHERE is_active = 1 AND LOWER(username) = :username
         LIMIT 1'
      );
      $stmt->execute([':username' => $normalizedUsername]);
      $admin = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($admin) {
        $storedHash = trim((string)$admin['password_hash']);
        $isSha256Hash = preg_match('/^[a-fA-F0-9]{64}$/', $storedHash) === 1;

        if ($isSha256Hash) {
          $storedSha256 = strtolower($storedHash);
          $isPasswordValid = (
            hash_equals($storedSha256, hash('sha256', $passwordRaw))
            || hash_equals($storedSha256, hash('sha256', $passwordTrimmed))
            || hash_equals($storedSha256, hash('sha256', $passwordCleaned))
          );
        } else {
          $isPasswordValid = (
            password_verify($passwordRaw, $storedHash)
            || password_verify($passwordTrimmed, $storedHash)
            || password_verify($passwordCleaned, $storedHash)
          );
        }

        if ($isPasswordValid) {
          $update = $pdo->prepare('UPDATE admin_login SET last_login_at = NOW() WHERE admin_id = :admin_id');
          $update->execute([':admin_id' => (int)$admin['admin_id']]);
          complete_login((int)$admin['admin_id'], (string)$admin['username']);
        }
      }

      $loginError = 'Invalid username or password.';
    } catch (Throwable $e) {
      $loginError = 'Login service unavailable. Please try again.';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login | Rudraansh Tours & Travel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="styles.css" />
  </head>
  <body data-page="login">
    <div class="bg-noise"></div>
    <main class="login-layout">
      <section class="login-card">
        <p class="eyebrow">Secure Access</p>
        <h1>Rudraansh Admin Login</h1>
        <p class="login-sub">Rudraansh Tours & Travel content and enquiry management panel.</p>

        <form  class="form-grid single-col" method="post" action="">
          <label>
            Username
            <input
              type="text"
              name="username"
              placeholder="Enter username"
              value="<?php echo htmlspecialchars($oldUsername, ENT_QUOTES, 'UTF-8'); ?>"
              required
            />
          </label>
          <label>
            Password
            <input type="password" name="password" placeholder="Enter password" required />
          </label>
          <button type="submit" class="btn-primary">Login</button>
        </form>

        <p id="login-error" class="login-error" aria-live="polite"><?php echo htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="login-note">Login using credentials configured in the admin_login table.</p>
      </section>
    </main>
  </body>
</html>
