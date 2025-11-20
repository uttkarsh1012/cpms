<?php
include __DIR__ . '/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO bookings (property_id, room_id, guest_name, check_in, check_out, status, source) VALUES (?,?,?,?,?,?,?)');
    $stmt->execute([
        $_POST['property_id'],
        $_POST['room_id'],
        $_POST['guest_name'],
        $_POST['check_in'],
        $_POST['check_out'],
        $_POST['status'],
        $_POST['source'] ?? 'direct',
    ]);
    echo '<div class="notification is-success">Booking added!</div>';
}

$properties = $pdo->query('SELECT * FROM properties')->fetchAll();
$rooms = $pdo->query('SELECT * FROM rooms')->fetchAll();
$bookings = $pdo->query('SELECT b.*, p.name as property_name, r.name as room_name FROM bookings b JOIN properties p ON b.property_id = p.id JOIN rooms r ON b.room_id = r.id ORDER BY b.created_at DESC')->fetchAll();
?>
<h1 class="title">Bookings</h1>
<div class="box">
    <form method="POST">
        <div class="columns">
            <div class="column">
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
                    <label class="label">Room</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="room_id" required>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?php echo e($room['id']); ?>"><?php echo e($room['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Guest Name</label>
                    <div class="control">
                        <input class="input" type="text" name="guest_name" required>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Check-in</label>
                    <div class="control">
                        <input class="input" type="date" name="check_in" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Check-out</label>
                    <div class="control">
                        <input class="input" type="date" name="check_out" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Status</label>
                    <div class="control">
                        <div class="select is-fullwidth">
                            <select name="status">
                                <option value="confirmed">Confirmed</option>
                                <option value="tentative">Tentative</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Source</label>
                    <div class="control">
                        <input class="input" type="text" name="source" placeholder="direct, OTA name, phone">
                    </div>
                </div>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <button class="button is-primary">Save Booking</button>
            </div>
        </div>
    </form>
</div>

<table class="table is-fullwidth is-striped">
    <thead>
        <tr>
            <th>Guest</th>
            <th>Property</th>
            <th>Room</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
            <th>Source</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo e($booking['guest_name']); ?></td>
                <td><?php echo e($booking['property_name']); ?></td>
                <td><?php echo e($booking['room_name']); ?></td>
                <td><?php echo e($booking['check_in']); ?></td>
                <td><?php echo e($booking['check_out']); ?></td>
                <td><?php echo e($booking['status']); ?></td>
                <td><?php echo e($booking['source']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/footer.php';
