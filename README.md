# Hostel Mate

Hostel Mate is a dynamic PHP and MySQL web application designed specifically for students at Mbarara University of Science and Technology (MUST). It simplifies the process of finding, filtering, and booking off-campus student accommodation.

## Features

* **Advanced Search & Filtering:** Filter hostels by price range, room type (Single, Shared, Self-Contained), and distance from the campus.
* **Detailed Hostel Profiles:** View hostel amenities, beautiful image galleries, pricing, and exact locations.
* **Booking System:** Students can securely log in, book a room, and check their booking statuses.
* **Admin Dashboard:** A complete backend for administrators to manage hostel listings, upload images, and track active bookings.
* **Responsive Modern UI:** A premium, custom-designed interface built from scratch without external frameworks, featuring dynamic elements and smooth transitions.

## Technologies Used

* **Frontend:** HTML5, CSS3 (Custom Design System with CSS Variables)
* **Backend:** PHP 8+
* **Database:** MySQL
* **Icons:** Custom SVG Iconography

## Local Setup Instructions

1. Install **XAMPP** (or any LAMP/WAMP stack).
2. Clone or extract this repository into your XAMPP `htdocs` directory (e.g., `C:\xampp\htdocs\hostels`).
3. Open **phpMyAdmin** (`http://localhost/phpmyadmin`) and create a new database named `hostels_db` (or check `config.php` for your specific database name).
4. Import the provided `db.sql` file into the new database. This will set up all tables and populate them with demo data and an admin account.
5. Open your browser and navigate to `http://localhost/hostels`.

## Temporary Internet Hosting (Via Port Forwarding)

If you want to showcase this project to someone over the internet directly from your local XAMPP server, you can use **Port Forwarding**. This exposes your local Apache server to the public internet using your public IP address.

### Step 1: Open Port 80 on Windows Firewall
1. Search for **Windows Defender Firewall with Advanced Security** in the Start menu.
2. Click **Inbound Rules** > **New Rule...** (on the right panel).
3. Select **Port** > Next.
4. Choose **TCP** and specific local port: `80` > Next.
5. Select **Allow the connection** > Next.
6. Check all boxes (Domain, Private, Public) > Next.
7. Name it "XAMPP Web Server" and click Finish.

### Step 2: Configure Your Wi-Fi Router
1. Find your laptop's Local IPv4 Address (open Command Prompt and type `ipconfig`). It usually looks like `192.168.1.x` or `10.x.x.x`.
2. Find your "Default Gateway" from the same command (usually `192.168.1.1`) and type it into your browser.
3. Log in with your router's admin credentials (often found on a sticker on the back of the router).
4. Navigate to the **Port Forwarding**, **Virtual Server**, or **NAT** settings.
5. Create a new rule:
   * **Internal IP:** Your laptop's Local IPv4 Address
   * **Internal Port:** `80`
   * **External Port:** `80`
   * **Protocol:** `TCP` (or Both)
6. Save and apply the settings.

### Step 3: Share Your Public IP
1. Go to Google and search "What is my IP" to find your Public Internet IP.
2. Share this address with your client or friend.
3. They can view the site by visiting: `http://YOUR-PUBLIC-IP/hostels`

*(Note: Port forwarding requires administrative access to your network's main router. If you are connected to a campus Wi-Fi or mobile hotspot using CGNAT, port forwarding will fail. In such cases, use a tunneling tool like **Ngrok** instead).*
