-- ============================================================
-- Cool Beans - Seed Data
-- ============================================================

USE `cool_beans`;

-- Regions
INSERT INTO `region` (`region_name`) VALUES
('Cordillera Administrative Region'),
('Davao Region'),
('CALABARZON'),
('National Capital Region'),
('Central Visayas');

-- Provinces
INSERT INTO `province` (`province_name`, `region_id`) VALUES
('Benguet', 1),
('Mountain Province', 1),
('Davao del Sur', 2),
('Davao de Oro', 2),
('Cavite', 3),
('Laguna', 3),
('Metro Manila', 4),
('Cebu', 5);

-- Cities
INSERT INTO `city` (`city_name`, `province_id`) VALUES
('La Trinidad', 1),       -- 1
('Baguio City', 1),       -- 2
('Sagada', 2),             -- 3
('Digos City', 3),         -- 4
('Nabunturan', 4),         -- 5
('Tagaytay City', 5),      -- 6
('San Pablo City', 6),     -- 7
('Quezon City', 7),        -- 8
('Makati City', 7),        -- 9
('Taguig City', 7),        -- 10
('Cebu City', 8);          -- 11

-- Suppliers
INSERT INTO `supplier` (`supplier_name`, `contact_person`, `phone`, `email`, `city_id`) VALUES
('Northern Highlands Farm', 'Maria Santos', '+63-917-123-4567', 'maria@nhfarm.ph', 1),
('Sagada Coffee Collective', 'Juan Dela Cruz', '+63-918-234-5678', 'juan@sagadacoffee.ph', 3),
('Davao Bean Co.', 'Pedro Reyes', '+63-919-345-6789', 'pedro@davaobeans.ph', 4),
('Mindanao Coffee Traders', 'Ana Garcia', '+63-920-456-7890', 'ana@mctph.com', 5),
('Cavite Roasters Inc.', 'Luis Mendoza', '+63-921-567-8901', 'luis@caviteroasters.ph', 6);

-- Coffee Beans
INSERT INTO `coffee_bean` (`bean_name`, `variety`, `roast_level`, `price_per_kg`, `province_id`, `supplier_id`, `description`) VALUES
('Benguet Arabica Premium', 'Arabica', 'MEDIUM', 850.00, 1, 1, 'High-altitude Arabica from the mountains of Benguet. Bright acidity with citrus notes.'),
('Benguet Dark Reserve', 'Arabica', 'DARK', 920.00, 1, 1, 'Full-bodied dark roast with chocolate and smoky undertones.'),
('Sagada Arabica', 'Arabica', 'LIGHT', 780.00, 2, 2, 'Light roast preserving delicate floral and fruity notes from Sagada highlands.'),
('Sagada Dark Roast', 'Arabica', 'MEDIUM_DARK', 880.00, 2, 2, 'Bold Sagada Arabica with caramel sweetness and deep body.'),
('Davao Robusta Classic', 'Robusta', 'DARK', 520.00, 3, 3, 'Strong, earthy Robusta from Davao lowlands. High caffeine content.'),
('Davao Liberica Rare', 'Liberica', 'MEDIUM', 1200.00, 3, 3, 'Rare Liberica variety with unique woody and floral profile.'),
('Oro Valley Blend', 'Robusta', 'MEDIUM', 580.00, 4, 4, 'Smooth Robusta blend from the valleys of Davao de Oro.'),
('Cavite Barako Bold', 'Liberica', 'DARK', 680.00, 5, 5, 'Traditional Filipino Barako. Bold, strong, and aromatic.');

-- Stores
INSERT INTO `store` (`store_name`, `address`, `city_id`, `phone`) VALUES
('Cool Beans - Quezon City', '123 Tomas Morato Ave, Quezon City', 8, '+63-2-8123-4567'),
('Cool Beans - Makati', '456 Ayala Avenue, Makati City', 9, '+63-2-8234-5678'),
('Cool Beans - BGC', '789 Bonifacio High Street, Taguig City', 10, '+63-2-8345-6789'),
('Cool Beans - Cebu', '321 Osmena Blvd, Cebu City', 11, '+63-32-234-5678');

-- Customers
INSERT INTO `customer` (`first_name`, `last_name`, `email`, `phone`, `city_id`) VALUES
('Carlo', 'Rivera', 'carlo.rivera@email.com', '+63-917-111-2222', 8),
('Sofia', 'Aquino', 'sofia.aquino@email.com', '+63-918-222-3333', 9),
('Miguel', 'Torres', 'miguel.torres@email.com', '+63-919-333-4444', 10),
('Isabella', 'Cruz', 'isabella.cruz@email.com', '+63-920-444-5555', 11),
('Rafael', 'Bautista', 'rafael.b@email.com', '+63-921-555-6666', 8);

-- Store Inventory
INSERT INTO `store_inventory` (`store_id`, `bean_id`, `quantity_kg`) VALUES
(1, 1, 45.00), (1, 2, 30.00), (1, 3, 22.50), (1, 5, 60.00), (1, 8, 15.00),
(2, 1, 35.00), (2, 4, 4.50), (2, 6, 12.00), (2, 7, 50.00),
(3, 2, 3.20), (3, 3, 18.00), (3, 5, 40.00), (3, 6, 8.50),
(4, 1, 25.00), (4, 5, 55.00), (4, 7, 2.80), (4, 8, 35.00);

-- Currency
INSERT INTO `currency` (`currency_code`, `currency_name`, `symbol`) VALUES
('PHP', 'Philippine Peso', '₱'),
('USD', 'US Dollar', '$'),
('KRW', 'South Korean Won', '₩'),
('JPY', 'Japanese Yen', '¥'),
('EUR', 'Euro', '€');

-- Exchange Rates (base: PHP)
INSERT INTO `exchange_rate` (`from_currency`, `to_currency`, `rate`, `effective_date`) VALUES
('PHP', 'USD', 0.0178, '2025-01-01'),
('USD', 'PHP', 56.20, '2025-01-01'),
('PHP', 'KRW', 24.50, '2025-01-01'),
('KRW', 'PHP', 0.0408, '2025-01-01'),
('PHP', 'JPY', 2.68, '2025-01-01'),
('JPY', 'PHP', 0.373, '2025-01-01'),
('PHP', 'EUR', 0.0164, '2025-01-01'),
('EUR', 'PHP', 60.98, '2025-01-01'),
('USD', 'KRW', 1376.00, '2025-01-01'),
('KRW', 'USD', 0.000727, '2025-01-01');

-- Sample Sales
INSERT INTO `sale` (`customer_id`, `store_id`, `sale_date`) VALUES
(1, 1, '2025-06-10 14:30:00'),
(2, 2, '2025-06-11 10:15:00'),
(3, 3, '2025-06-12 16:45:00');

INSERT INTO `sale_items` (`sale_id`, `bean_id`, `quantity_kg`, `unit_price`) VALUES
(1, 1, 2.00, 850.00),
(1, 5, 1.50, 520.00),
(2, 6, 0.50, 1200.00),
(3, 2, 1.00, 920.00);

INSERT INTO `sale_payment` (`sale_id`, `currency_code`, `amount`) VALUES
(1, 'PHP', 2480.00),
(2, 'PHP', 600.00),
(3, 'USD', 16.37);

-- Sample Restocks
INSERT INTO `restock` (`store_id`, `supplier_id`, `restock_date`) VALUES
(2, 2, '2025-06-08 09:00:00'),
(3, 1, '2025-06-09 11:00:00');

INSERT INTO `restock_items` (`restock_id`, `bean_id`, `quantity_kg`, `unit_cost`) VALUES
(1, 4, 25.00, 700.00),
(2, 2, 20.00, 750.00);

INSERT INTO `restock_payment` (`restock_id`, `currency_code`, `amount`) VALUES
(1, 'PHP', 17500.00),
(2, 'PHP', 15000.00);

-- Users (passwords are bcrypt hashes of: admin123, staff123, customer123)
INSERT INTO `users` (`username`, `password_hash`, `role`, `linked_customer_id`, `linked_store_id`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', NULL, NULL),
('staff_qc', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'STAFF', NULL, 1),
('staff_makati', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'STAFF', NULL, 2),
('carlo_r', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CUSTOMER', 1, NULL),
('sofia_a', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CUSTOMER', 2, NULL);
