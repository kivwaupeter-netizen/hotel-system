DROP DATABASE IF EXISTS phantom_ridge_resort;
CREATE DATABASE phantom_ridge_resort
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE phantom_ridge_resort;

CREATE TABLE users (
    id         INT          NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    phone      VARCHAR(20)  DEFAULT NULL,
    role       ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rooms (
    id              INT           NOT NULL AUTO_INCREMENT,
    name            VARCHAR(150)  NOT NULL,
    type            ENUM('affordable', 'standard', 'luxury') NOT NULL,
    description     TEXT          DEFAULT NULL,
    services        TEXT          DEFAULT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    max_guests      INT           NOT NULL,
    main_image      VARCHAR(255)  DEFAULT NULL,
    is_available    TINYINT(1)    NOT NULL DEFAULT 1,
    created_at      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE bookings (
    id          INT           NOT NULL AUTO_INCREMENT,
    user_id     INT           NOT NULL,
    room_id     INT           NOT NULL,
    check_in    DATE          NOT NULL,
    check_out   DATE          NOT NULL,
    num_guests  INT           NOT NULL,
    num_nights  INT           NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    status      ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending',
    created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_bookings_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_bookings_room
        FOREIGN KEY (room_id) REFERENCES rooms(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_room_id (room_id),
    INDEX idx_status  (status),
    INDEX idx_dates   (check_in, check_out)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (name, email, password, phone, role) VALUES
(
    'Admin',
    'admin@phantomridge.co.ke',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    NULL,
    'admin'
),
(
    'John Kamau',
    'john@gmail.com',
    '$2y$12$eUDcKeXcFLSi5oKDa0P3EOkGNNwUGVMCOxuUQhLBQYxPcm5x1pLHa',
    '0712345678',
    'user'
);

INSERT INTO rooms (name, type, description, services, price_per_night, max_guests, main_image, is_available) VALUES
(
    'Savannah Room',
    'affordable',
    'A cozy and comfortable room perfect for solo travelers or couples looking for an affordable retreat in the heart of Kenya.',
    'Free Wi-Fi, Daily Breakfast, Free Parking, Clean Linen, 24-Hour Reception',
    4500.00,
    2,
    NULL,
    1
),
(
    'Garden Suite',
    'standard',
    'A spacious garden-facing suite offering modern amenities and a relaxing environment for families and small groups.',
    'Free Wi-Fi, Daily Breakfast, Free Parking, Clean Linen, 24-Hour Reception, Air Conditioning, Flat-Screen TV, Daily Housekeeping, Swimming Pool Access',
    9000.00,
    4,
    NULL,
    1
),
(
    'Highland Villa',
    'luxury',
    'Our most exclusive offering — a fully private villa with panoramic views, butler service, and all premium amenities for the ultimate luxury experience.',
    'Free Wi-Fi, Daily Breakfast, Free Parking, Clean Linen, 24-Hour Reception, Air Conditioning, Flat-Screen TV, Daily Housekeeping, Swimming Pool Access, Private Butler, Airport Pickup, Rooftop Terrace, Spa Access, Private Dining',
    22000.00,
    8,
    NULL,
    1
);

INSERT INTO bookings (user_id, room_id, check_in, check_out, num_guests, num_nights, total_price, status) VALUES
(
    2,
    1,
    '2025-08-01',
    '2025-08-04',
    2,
    3,
    13500.00,
    'confirmed'
),
(
    2,
    2,
    '2025-09-10',
    '2025-09-12',
    3,
    2,
    18000.00,
    'pending'
);