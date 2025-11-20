<?php
require_once __DIR__ . '/../src/Installer.php';

$configPath = __DIR__ . '/config.php';

if (file_exists($configPath)) {
    header('Location: /index.php');
    exit;
}

$error = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config = [
        'db_host' => $_POST['db_host'] ?? 'localhost',
        'db_name' => $_POST['db_name'] ?? 'cpms',
        'db_user' => $_POST['db_user'] ?? 'root',
        'db_pass' => $_POST['db_pass'] ?? '',
    ];

    try {
        Installer::install($config);
        file_put_contents($configPath, "<?php\nreturn " . var_export($config, true) . ';');
        $success = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MiniCal Clone Setup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.0/css/bulma.min.css">
</head>
<body class="section">
    <div class="container">
        <h1 class="title">Channel PMS Setup</h1>
        <p class="subtitle">Provide your database credentials to finish installation.</p>

        <?php if ($error): ?>
            <div class="notification is-danger">Installation failed: <?php echo htmlspecialchars($error); ?></div>
        <?php elseif ($success): ?>
            <div class="notification is-success">
                Installation completed. <a href="/index.php">Go to dashboard</a>.
            </div>
        <?php endif; ?>

        <form method="POST" class="box">
            <div class="field">
                <label class="label">Database Host</label>
                <div class="control">
                    <input class="input" type="text" name="db_host" value="<?php echo htmlspecialchars($_POST['db_host'] ?? 'localhost'); ?>" required>
                </div>
            </div>
            <div class="field">
                <label class="label">Database Name</label>
                <div class="control">
                    <input class="input" type="text" name="db_name" value="<?php echo htmlspecialchars($_POST['db_name'] ?? 'cpms'); ?>" required>
                </div>
            </div>
            <div class="field">
                <label class="label">Database User</label>
                <div class="control">
                    <input class="input" type="text" name="db_user" value="<?php echo htmlspecialchars($_POST['db_user'] ?? 'root'); ?>" required>
                </div>
            </div>
            <div class="field">
                <label class="label">Database Password</label>
                <div class="control">
                    <input class="input" type="password" name="db_pass" value="<?php echo htmlspecialchars($_POST['db_pass'] ?? ''); ?>">
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-primary">Install</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
