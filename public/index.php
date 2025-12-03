<?php
include __DIR__ . '/header.php';

$bookings = $pdo->query('SELECT b.*, p.name as property_name, r.name as room_name FROM bookings b JOIN properties p ON b.property_id = p.id JOIN rooms r ON b.room_id = r.id ORDER BY b.created_at DESC LIMIT 5')->fetchAll();
$totalBookings = $pdo->query('SELECT COUNT(*) as total FROM bookings')->fetch()['total'];
$totalProperties = $pdo->query('SELECT COUNT(*) as total FROM properties')->fetch()['total'];
$totalOtas = $pdo->query('SELECT COUNT(*) as total FROM otas')->fetch()['total'];
?>
<h1 class="title">Dashboard</h1>
<div class="columns">
    <div class="column">
        <div class="box has-text-centered">
            <p class="heading">Bookings</p>
            <p class="title"><?php echo e($totalBookings); ?></p>
        </div>
    </div>
    <div class="column">
        <div class="box has-text-centered">
            <p class="heading">Properties</p>
            <p class="title"><?php echo e($totalProperties); ?></p>
        </div>
    </div>
    <div class="column">
        <div class="box has-text-centered">
            <p class="heading">OTAs</p>
            <p class="title"><?php echo e($totalOtas); ?></p>
        </div>
    </div>
</div>

<h2 class="subtitle">Recent Bookings</h2>
<table class="table is-fullwidth is-striped">
    <thead>
        <tr>
            <th>Guest</th>
            <th>Property</th>
            <th>Room</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Status</th>
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
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include __DIR__ . '/footer.php';
