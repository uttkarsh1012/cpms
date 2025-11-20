# Channel PMS (MiniCal-inspired)

This repository provides a lightweight PHP property management system inspired by MiniCal. It includes:

- Quick web-based installer that asks for MySQL credentials and seeds demo data.
- Simple dashboard with bookings overview.
- Booking creation page that ties properties, rooms, and sources together.
- OTA and channel mapping screens to link properties with external IDs from your channel manager.

## Requirements
- PHP 8.1+ with PDO MySQL extension
- MySQL 5.7+ or MariaDB
- A web server (Apache, nginx) configured to serve the `public/` directory

## Installation
1. Upload the repository files to your server.
2. Ensure the web server points to the `public/` folder.
3. Visit `/install.php` in your browser.
4. Enter your database host, name, username, and password. The installer will create tables, seed demo data, and save `public/config.php`.
5. Log in to `/index.php` to view the dashboard. Demo credentials are `demo@example.com` with password `demo1234` (use your DB to manage users).

## Customization
- Update styles via Bulma CSS or your own stylesheet.
- Expand OTA integration by storing tokens or endpoints in the `otas` table and mapping room IDs in `ota_channel_mappings`.
- Seed more demo data by adjusting `src/Installer.php`.

## Security Note
This starter keeps logic minimal for easy setup. Before production use, add authentication, input validation, and HTTPS.
