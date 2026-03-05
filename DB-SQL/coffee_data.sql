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
(1, "Intramuros Branch", "09182315982", "No. 56 Aragon St., SFDM", 19),		-- manila (NCR)
(2, "Burnham Branch", "09632936321", "8263 Constancia Street 1200", 3),		-- baguio (CAR)
(3, "Taal Branch", "09646113128", "1051 North Bay Blvd.", 14);				-- lucena (calabarzon)


-- ---------------------------------------
-- Fill supplier table
--
-- notes:
-- > randomly generated
-- ----------------------------------------
INSERT INTO supplier VALUES
(1, "Nathaniel Isidro", "09826587073", "nathanielisidro@gmail.com", "Phase 3 B F Homes 308 Aguirre Avenue 1700", 70),
(2, "South Farms", "026965544", "SouthFarms@yahoo.com", "Sitio Lugusangan, Barangay Mantalongon", 39),
(3, "Leon Arabejo", "09464376275", "Leon_Arabejo@gmail.com", "Cavite Economic Zone, Rosario", 18),
(4, "Sakahang SK", "020375202", "SakahangSK@yahoo.com", "1244 Sitio Kapehan, Barangay Kanipaan", 62),
(5, "Felippe Vargas", "09240068561", "f.vargas@gmail.com", "Sitio El Dulo, Barangay Edwards", 61);


-- ---------------------------------------
-- Fill coffee_bean table
-- ----------------------------------------
INSERT INTO coffee_bean VALUES 
(1, "Arabica", "", 70),
(2, "Sagada Coffee", "", 70),
(3, "Robusta", "", 39),
(4, "Liberica", "", 18),
(5, "Excelsa", "", 18),
(6, "Daguma Coffee", "", 62),
(7, "Kulaman Coffee", "", 62),
(8, "Civet Coffee", "", 61);


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