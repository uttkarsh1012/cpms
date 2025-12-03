<?php

require_once __DIR__ . '/Database.php';

class Installer
{
    public static function install(array $config): void
    {
        $pdo = Database::connect($config);
        self::createTables($pdo);
        self::seedDemoData($pdo);
    }

    private static function createTables(PDO $pdo): void
    {
        $pdo->exec('CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $pdo->exec('CREATE TABLE IF NOT EXISTS properties (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            address VARCHAR(255) NOT NULL,
            timezone VARCHAR(64) NOT NULL DEFAULT "UTC",
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $pdo->exec('CREATE TABLE IF NOT EXISTS rooms (
            id INT AUTO_INCREMENT PRIMARY KEY,
            property_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            capacity INT NOT NULL DEFAULT 2,
            rate DECIMAL(10,2) NOT NULL DEFAULT 0,
            FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $pdo->exec('CREATE TABLE IF NOT EXISTS bookings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            property_id INT NOT NULL,
            room_id INT NOT NULL,
            guest_name VARCHAR(255) NOT NULL,
            check_in DATE NOT NULL,
            check_out DATE NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT "confirmed",
            source VARCHAR(50) NOT NULL DEFAULT "direct",
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE CASCADE,
            FOREIGN KEY(room_id) REFERENCES rooms(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $pdo->exec('CREATE TABLE IF NOT EXISTS otas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            api_url VARCHAR(255) NOT NULL,
            api_key VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $pdo->exec('CREATE TABLE IF NOT EXISTS ota_channel_mappings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            property_id INT NOT NULL,
            ota_id INT NOT NULL,
            external_hotel_id VARCHAR(255) NOT NULL,
            external_room_map JSON NULL,
            FOREIGN KEY(property_id) REFERENCES properties(id) ON DELETE CASCADE,
            FOREIGN KEY(ota_id) REFERENCES otas(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    }

    private static function seedDemoData(PDO $pdo): void
    {
        $count = $pdo->query('SELECT COUNT(*) as total FROM users')->fetch()['total'] ?? 0;
        if ($count > 0) {
            return;
        }

        $password = password_hash('demo1234', PASSWORD_BCRYPT);
        $pdo->prepare('INSERT INTO users (email, password, name) VALUES (?,?,?)')
            ->execute(['demo@example.com', $password, 'Demo Manager']);

        $pdo->prepare('INSERT INTO properties (name, address, timezone) VALUES (?,?,?)')
            ->execute(['Seaside Hotel', '123 Beach Road', 'UTC']);
        $propertyId = (int) $pdo->lastInsertId();

        $pdo->prepare('INSERT INTO rooms (property_id, name, capacity, rate) VALUES (?,?,?,?)')
            ->execute([$propertyId, 'Standard Room', 2, 120]);
        $roomId = (int) $pdo->lastInsertId();

        $pdo->prepare('INSERT INTO bookings (property_id, room_id, guest_name, check_in, check_out, status, source) VALUES (?,?,?,?,?,?,?)')
            ->execute([$propertyId, $roomId, 'John Doe', date('Y-m-d'), date('Y-m-d', strtotime('+2 days')), 'confirmed', 'demo']);

        $pdo->prepare('INSERT INTO otas (name, api_url, api_key) VALUES (?,?,?)')
            ->execute(['Demo OTA', 'https://ota.example.com/api', 'demo-api-key']);

        $pdo->prepare('INSERT INTO ota_channel_mappings (property_id, ota_id, external_hotel_id, external_room_map) VALUES (?,?,?,?)')
            ->execute([$propertyId, 1, 'OTA-123', json_encode(['Standard Room' => 'OTA-ROOM-1'])]);
    }
}
