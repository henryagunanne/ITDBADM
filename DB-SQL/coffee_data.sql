-- ---------------------------------------
-- Fill region table
-- ---------------------------------------
INSERT INTO region VALUES
(1, "Ilocos Region"),
(2, "Cagayan Valley"),
(3, "Central Luzon"),
(4, "CALABARZON"),
(5, "Bicol Region"),
(6, "Western Visayas"),
(7, "Central Visayas"),
(8, "Eastern Visayas"),
(9, "Zamboanga Peninsula"),
(10, "Northern Mindanao"),
(11, "Davao Region"),
(12, "SOCCSKSARGEN"),
(13, "Caraga"),
(14, "MIMAROPA"),
(15, "NCR"),
(16, "CAR"),
(17, "BARMM"),
(18, "NIR");


-- ---------------------------------------
-- Fill province table
-- ---------------------------------------
INSERT INTO province VALUES
(1, "Ilocos Norte", 1),
(2, "Ilocos Sur", 1),
(3, "La Union", 1),
(4, "Pangasinan", 1),
(5, "Batanes", 2),
(6, "Cagayan", 2),
(7, "Isabela", 2),
(8, "Nueva Vizcaya", 2),
(9, "Quirino", 2),
(10, "Aurora", 3),
(11, "Bataan", 3),
(12, "Bulacan", 3),
(13, "Nueva Ecija", 3),
(14, "Pampanga", 3),
(15, "Tarlac", 3),
(16, "Zambales", 3),
(17, "Batangas", 4),
(18, "Cavite", 4),
(19, "Laguna", 4),
(20, "Quezon", 4),
(21, "Rizal", 4),
(22, "Marinduque", 14),
(23, "Occidental Mindoro", 14),
(24, "Oriental Mindoro", 14),
(25, "Palawan", 14),
(26, "Romblon", 14),
(27, "Albay", 5),
(28, "Camarines Norte", 5),
(29, "Camarines Sur", 5),
(30, "Catanduanes", 5),
(31, "Masbate", 5),
(32, "Sorsogon", 5),
(33, "Aklan", 6),
(34, "Antique", 6),
(35, "Capiz", 6),
(36, "Guimaras", 6),
(37, "Iloilo", 6),
(38, "Bohol", 7),
(39, "Cebu", 7),
(40, "Biliran", 8),
(41, "Eastern Samar", 8),
(42, "Leyte", 8),
(43, "Northern Samar", 8),
(44, "Samar", 8),
(45, "Southern Leyte", 8),
(46, "Zamboanga del Norte", 9),
(47, "Zamboanga del Sur", 9),
(48, "Zamboanga Sibugay", 9),
(49, "Bukidnon", 10),
(50, "Camiguin", 10),
(51, "Lanao del Norte", 10),
(52, "Misamis Occidental", 10),
(53, "Misamis Oriental", 10),
(54, "Davao de Oro", 11),
(55, "Davao del Norte", 11),
(56, "Davao del Sur", 11),
(57, "Davao Occidental", 11),
(58, "Davao Oriental", 11),
(59, "Cotabato", 12),
(60, "Sarangani", 12),
(61, "South Cotabato", 12),
(62, "Sultan Kudarat", 12),
(63, "Agusan del Norte", 13),
(64, "Agusan del Sur", 13),
(65, "Dinagat Islands", 13),
(66, "Surigao del Norte", 13),
(67, "Surigao del Sur", 13),
(68, "Abra", 16),
(69, "Apayao", 16),
(70, "Benguet", 16),
(71, "Ifugao", 16),
(72, "Kalinga", 16),
(73, "Mountain Province", 16),
(74, "Basilan", 17),
(75, "Lanao del Sur", 17),
(76, "Maguindanao", 17),
(77, "Sulu", 17),
(78, "Tawi-Tawi", 17);


-- ---------------------------------------
-- Fill city table
-- ---------------------------------------
INSERT INTO city VALUES
(1, "Angeles", 3),
(2, "Bacolod", 18),
(3, "Baguio", 16),
(4, "Butuan", 13),
(5, "Cagayan de Oro", 10),
(6, "Caloocan", 15),
(7, "Cebu City", 7),
(8, "Davao City", 11),
(9, "General Santos", 12),
(10, "Iligan", 10),
(11, "Iloilo City", 6),
(12, "Lapu-Lapu", 7),
(13, "Las Piñas", 15),
(14, "Lucena", 4),
(15, "Makati", 15),
(16, "Malabon", 15),
(17, "Mandaluyong", 15),
(18, "Mandaue", 7),
(19, "Manila", 15),
(20, "Marikina", 15),
(21, "Muntinlupa", 15),
(22, "Navotas", 15),
(23, "Olongapo", 3),
(24, "Parañaque", 15),
(25, "Pasay", 15),
(26, "Pasig", 15),
(27, "Puerto Princesa", 14),
(28, "Quezon City", 15),
(29, "San Juan", 15),
(30, "Tacloban", 8),
(31, "Taguig", 15),
(32, "Valenzuela", 15),
(33, "Zamboanga", 9);


-- ---------------------------------------
-- Fill store table
--
-- notes:
-- > randomly generated
-- > names are based on a famous landmarks
-- ----------------------------------------
INSERT INTO store VALUES
(1, "Cool Beans - Intramuros Branch", "09182315982", "No. 56 Aragon St., SFDM", 19),		-- manila (NCR)
(2, "Cool Beans - Burnham Branch", "09632936321", "8263 Constancia Street 1200", 3),		-- baguio (CAR)
(3, "Cool Beans - Taal Branch", "09646113128", "1051 North Bay Blvd.", 14);				-- lucena (calabarzon)


-- ---------------------------------------
-- Fill supplier table
--
-- notes:
-- > randomly generated
-- ----------------------------------------
INSERT INTO supplier VALUES
(1, "Nathaniel Isidro", "09826587073", "nathanielisidro@gmail.com", "Phase 3 B F Homes 308 Aguirre Avenue 1700", 19),
(2, "South Farms", "026965544", "SouthFarms@yahoo.com", "Sitio Lugusangan, Barangay Mantalongon", 15),
(3, "Leon Arabejo", "09464376275", "Leon_Arabejo@gmail.com", "Cavite Economic Zone, Rosario", 18),
(4, "Sakahang SK", "020375202", "SakahangSK@yahoo.com", "1244 Sitio Kapehan, Barangay Kanipaan", 13),
(5, "Felippe Vargas", "09240068561", "f.vargas@gmail.com", "Sitio El Dulo, Barangay Edwards", 3);


-- ---------------------------------------
-- Fill coffee_bean table
-- ----------------------------------------
INSERT INTO coffee_bean VALUES
(1, 'Benguet Arabica Premium', 'Arabica', 70, 'MEDIUM', 850.00, 1, 'High-altitude Arabica from the mountains of Benguet. Bright acidity with citrus notes.'),
(2, 'Benguet Dark Reserve', 'Arabica', 18, 'DARK', 920.00, 1, 'Full-bodied dark roast with chocolate and smoky undertones.'),
(3, 'Sagada Arabica', 'Arabica', 70, 'LIGHT', 780.00, 2, 'Light roast preserving delicate floral and fruity notes from Sagada highlands.'),
(4, 'Sagada Dark Roast', 'Arabica', 61, 'MEDIUM_DARK', 880.00, 2, 'Bold Sagada Arabica with caramel sweetness and deep body.'),
(5, 'Davao Robusta Classic', 'Robusta', 39, 'DARK', 520.00, 3, 'Strong, earthy Robusta from Davao lowlands. High caffeine content.'),
(6, 'Davao Liberica Rare', 'Liberica', 18, 'MEDIUM', 1200.00, 3, 'Rare Liberica variety with unique woody and floral profile.'),
(7, 'Oro Valley Blend', 'Robusta', 62, 'MEDIUM', 580.00, 4, 'Smooth Robusta blend from the valleys of Davao de Oro.'),
(8, 'Cavite Barako Bold', 'Liberica', 62, 'DARK', 680.00, 5, 'Traditional Filipino Barako. Bold, strong, and aromatic.');


-- ---------------------------------------
-- Fill customer table
--
-- notes:
-- > randomly generated
-- ----------------------------------------
INSERT INTO customer VALUES
(1, "Julia", "Castro", "juliacastro@gmail.com", "09467259175", "No. 56 Aragon St., SFDM", 28),
(2, "Sklar", "Arancel", "sky.arancel@gmail.com", "09348592572", "Riverbanks Center, A. Bonifacio Avenue", 20),
(3, "Ansley", "Chua", "ansleychu@yahoo.com", "09127342953", "Basement, Shangri-La Plaza, EDSA Corner Shaw Boulevard", 17);


-- ---------------------------------------
-- Fill currency table
-- ----------------------------------------
INSERT INTO currency VALUES
("PHP", "Philippine Peso", "₱"),
("USD", "US Dollar", "$"),
("JPY", "Japanese Yen", "¥");


-- ---------------------------------------
-- Fill exchange_rate table
--
-- notes:
-- > as of March 5, 2026
-- ----------------------------------------
INSERT INTO exchange_rate VALUES
(1, "PHP", "USD", 0.017, "2026-03-05"),
(2, "PHP", "JPY", 2.68, "2026-03-05"),
(3, "USD", "PHP", 58.62, "2026-03-05"),
(4, "USD", "JPY", 157.05, "2026-03-05"),
(5, "JPY", "PHP", 0.37, "2026-03-05"),
(6, "JPY", "USD", 0.0064, "2026-03-05"); 


-- Store Inventory
INSERT INTO store_inventory (store_id, bean_id, quantity_kg) VALUES
(1, 1, 45), (1, 2, 30), (1, 3, 22), (1, 5, 60), (1, 8, 15),
(2, 1, 35), (2, 4, 4), (2, 6, 12), (2, 7, 50),
(3, 2, 3), (3, 3, 18), (3, 5, 40), (3, 6, 8),
(1, 1, 25), (2, 5, 55), (2, 7, 3), (3, 8, 35);

-- Sample Sales
INSERT INTO sale (store_id, customer_id, sale_date, total_amount) VALUES
(1, 1, '2026-02-10', 2220.00),
(2, 2, '2026-02-11', 1200.00),
(3, 3, '2026-03-12', 920.00);


INSERT INTO sale_items (sale_id, bean_id, quantity, unit_price, subtotal) VALUES
(1, 1, 2, 850.00, 1700.00),
(1, 5, 1, 520.00, 520.00),
(2, 6, 1, 1200.00, 1200.00),
(3, 2, 1, 920.00, 920.00);

INSERT INTO sale_payment (sale_id, payment_date, amount_paid, currency_code, payment_method) VALUES
(1, '2026-02-10', 2220.00, 'PHP', 'BANK TRANSFER'),
(2, '2026-02-11', 1200.00, 'PHP','CARD'),
(3, '2026-03-12', 920.00, 'USD', 'CASH');

-- Sample Restocks
INSERT INTO restock (store_id, supplier_id, restock_date, total_amount) VALUES
(2, 2, '2026-01-08', 17500.00),
(3, 1, '2026-01-09', 15000.00);

INSERT INTO restock_items (restock_id, bean_id, quantity, unit_cost) VALUES
(1, 4, 25.00, 700.00),
(2, 2, 20.00, 750.00);

INSERT INTO restock_payment (restock_id, payment_date, amount_paid, currency_code, payment_method) VALUES
(1, '2026-01-08', 17500.00, 'PHP', 'CASH'),
(2, '2026-01-09', 15000.00, 'PHP', 'CASH');

-- Users (passwords are bcrypt hashes of: admin123, staff123, customer123)
INSERT INTO users (username, password, role, linked_customer_id, linked_store_id) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', NULL, NULL),
('staff_intramuros', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'STAFF', NULL, 1),
('staff_burnham', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'STAFF', NULL, 2),
('carlo_r', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CUSTOMER', 1, NULL),
('sofia_a', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CUSTOMER', 2, NULL);


