<?php
include __DIR__ . '/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_ota'])) {
        $stmt = $pdo->prepare('INSERT INTO otas (name, api_url, api_key) VALUES (?,?,?)');
        $stmt->execute([
            $_POST['name'],
            $_POST['api_url'],
            $_POST['api_key'],
        ]);
        echo '<div class="notification is-success">OTA added.</div>';
    }

    if (isset($_POST['create_mapping'])) {
        $stmt = $pdo->prepare('INSERT INTO ota_channel_mappings (property_id, ota_id, external_hotel_id, external_room_map) VALUES (?,?,?,?)');
        $stmt->execute([
            $_POST['property_id'],
            $_POST['ota_id'],
            $_POST['external_hotel_id'],
            json_encode(['room_map' => $_POST['external_room_map'] ?? ''])
        ]);
        echo '<div class="notification is-success">Channel mapping saved.</div>';
    }
}

$otas = $pdo->query('SELECT * FROM otas')->fetchAll();
$properties = $pdo->query('SELECT * FROM properties')->fetchAll();
$mappings = $pdo->query('SELECT m.*, o.name as ota_name, p.name as property_name FROM ota_channel_mappings m JOIN otas o ON m.ota_id = o.id JOIN properties p ON m.property_id = p.id ORDER BY m.id DESC')->fetchAll();
?>
<h1 class="title">OTAs & Channel Manager Links</h1>
<div class="columns">
    <div class="column">
        <div class="box">
            <h2 class="subtitle">Add OTA</h2>
            <form method="POST">
                <input type="hidden" name="create_ota" value="1">
                <div class="field">
                    <label class="label">Name</label>
                    <div class="control"><input class="input" name="name" required></div>
                </div>
                <div class="field">
                    <label class="label">API URL</label>
                    <div class="control"><input class="input" name="api_url" required></div>
                </div>
                <div class="field">
                    <label class="label">API Key</label>
                    <div class="control"><input class="input" name="api_key" required></div>
                </div>
                <div class="field">
                    <div class="control"><button class="button is-primary">Save OTA</button></div>
                </div>
            </form>
        </div>
    </div>
    <div class="column">
        <div class="box">
            <h2 class="subtitle">Link Property to Channel</h2>
            <form method="POST">
                <input type="hidden" name="create_mapping" value="1">
                <div class="field">
                    <label class="label">Property</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="property_id" required>
                                <?php foreach ($properties as $property): ?>
                                    <option value="<?php echo e($property['id']); ?>"><?php echo e($property['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label">OTA</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="ota_id" required>
                                <?php foreach ($otas as $ota): ?>
                                    <option value="<?php echo e($ota['id']); ?>"><?php echo e($ota['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label">External Hotel ID</label>
                    <div class="control"><input class="input" name="external_hotel_id" required></div>
                </div>
                <div class="field">
                    <label class="label">External Room Map (JSON or notes)</label>
                    <div class="control"><input class="input" name="external_room_map" placeholder='{"Standard Room": "OTA-1"}'></div>
                </div>
                <div class="field">
                    <div class="control"><button class="button is-link">Save Mapping</button></div>
                </div>
            </form>
        </div>
    </div>
</div>

<h2 class="subtitle">Existing OTAs</h2>
<table class="table is-fullwidth is-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>API URL</th>
            <th>API Key</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($otas as $ota): ?>
            <tr>
                <td><?php echo e($ota['name']); ?></td>
                <td><?php echo e($ota['api_url']); ?></td>
                <td><?php echo e($ota['api_key']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2 class="subtitle">Channel Mappings</h2>
<table class="table is-fullwidth is-striped">
    <thead>
        <tr>
            <th>Property</th>
            <th>OTA</th>
            <th>External Hotel ID</th>
            <th>External Room Map</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mappings as $mapping): ?>
            <tr>
                <td><?php echo e($mapping['property_name']); ?></td>
                <td><?php echo e($mapping['ota_name']); ?></td>
                <td><?php echo e($mapping['external_hotel_id']); ?></td>
                <td><code><?php echo e($mapping['external_room_map']); ?></code></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/footer.php';
