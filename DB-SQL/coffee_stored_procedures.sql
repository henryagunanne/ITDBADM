-- ---------------------------------------
-- Currency Conversion
-- ---------------------------------------
DELIMITER // 
CREATE PROCEDURE convertCurrency (
				INOUT amount DECIMAL(8,2),
                IN currency_code VARCHAR(3),
                IN converted_code VARCHAR(3),
                OUT newAmount DECIMAL(8,2)
)
BEGIN
	DECLARE rate DECIMAL(18,6);
    SELECT er.exchange_rate INTO rate
    FROM exchange_rate er
    WHERE er.from_currency_code = currency_code  AND er.to_currency_code = converted_code;
    SET newAmount = amount * rate;
END;
// DELIMITER ;



-- ---------------------------------------
-- Update exchange rate
-- version 1: using ID
-- ---------------------------------------
DELIMITER //
CREATE PROCEDURE updateExchangeRate (
	IN id INT,
    IN new_rate DECIMAL(18,6)
)
BEGIN
	UPDATE exchange_rate er
    SET er.exchange_rate = new_rate, er.effective_date = CURDATE()
    WHERE er.rate_id = id;
END;
// DELIMITER ;



-- ---------------------------------------
-- Update exchange rate
-- version 2: using codes
-- ---------------------------------------
DELIMITER //
CREATE PROCEDURE updateExchangeRate2 (
	IN old_from_code VARCHAR(5),
    IN old_to_code VARCHAR(5),
    IN new_rate DECIMAL(18,6)
)
BEGIN
	UPDATE exchange_rate er
    SET er.exchange_rate = new_rate, er.effective_date = CURDATE()
    WHERE TRIM(er.from_currency_code) = TRIM(old_from_code) 
		AND TRIM(er.to_currency_code) = TRIM(old_to_code);
END;
// DELIMITER ;



-- ---------------------------------------
-- Get all coffee beans from a province
-- ---------------------------------------
DELIMITER \\ 
CREATE PROCEDURE getCoffeeBeans (
				IN region VARCHAR(45)
)
BEGIN
	SELECT p.province_name, cb.bean_name
    FROM coffee_bean cb
		JOIN province p ON p.province_id=cb.origin_province_id
	WHERE region = p.province_name;
END;
\\ DELIMITER ;



-- ---------------------------------------
-- Add item to cart
-- ---------------------------------------
DELIMITER //
CREATE PROCEDURE addToCart (
    IN in_store_id INT,
    IN in_customer_id INT,
    IN in_bean_id INT,
    IN in_quantity INT,
    IN in_unit_price DECIMAL(18,2),
    IN in_currency_code VARCHAR(5)
)
BEGIN
    DECLARE v_sale_id INT;
    DECLARE v_subtotal DECIMAL(18,2);

    -- try to find an existing sale "header" for this customer at this store
    SELECT sale_id INTO v_sale_id 
    FROM sale 
    WHERE customer_id = in_customer_id AND store_id = in_store_id
    ORDER BY sale_date DESC 
    LIMIT 1;

    -- if no sale exists, create a new one
    IF v_sale_id IS NULL THEN
        INSERT INTO sale (store_id, customer_id, sale_date, total_amount, currency_code)
        VALUES (in_store_id, in_customer_id, CURDATE(), 0.00, in_currency_code);
        
        SET v_sale_id = LAST_INSERT_ID();
    END IF;

    -- calculate subtotal for the new item
    SET v_subtotal = in_quantity * in_unit_price;

    -- add the item to sale_items
    INSERT INTO sale_items (sale_id, bean_bean_id, quantity, unit_price, subtotal)
    VALUES (v_sale_id, in_bean_id, in_quantity, in_unit_price, v_subtotal);

    -- update the total amount in the main sale table
    UPDATE sale 
    SET total_amount = total_amount + v_subtotal 
    WHERE sale_id = v_sale_id;
END; 
// DELIMITER ;



-- ---------------------------------------
-- Checkout procedure
-- ---------------------------------------
DELIMITER //
CREATE PROCEDURE checkoutSale (
    IN in_sale_id INT,
    IN in_payment_method VARCHAR(20)
)
BEGIN
    INSERT INTO sale_payment (
        sale_id, payment_date, amount_paid, currency_code, payment_method, payment_status
    )
    SELECT 
        sale_id, CURDATE(), total_amount, currency_code, in_payment_method, 'PAID'
    FROM sale
    WHERE sale_id = in_sale_id;
END;
// DELIMITER ;


-- ---------------------------------------
-- Get total sales per store
-- ---------------------------------------
DELIMITER //
CREATE PROCEDURE getStoreSales (
    IN in_store_id INT
)
BEGIN
    SELECT store_id, SUM(total_amount) AS total_sales
    FROM sale
    WHERE store_id = in_store_id
    GROUP BY store_id;
END;
// DELIMITER ;



-- ---------------------------------------
-- Restock procedure (header + items)
-- ---------------------------------------
DELIMITER //
CREATE PROCEDURE createRestock (
    IN in_store_id INT,
    IN in_supplier_id INT,
    IN in_currency VARCHAR(5)
)
BEGIN
    INSERT INTO restock (store_id, supplier_id, restock_date, currency_code, total_amount)
    VALUES (in_store_id, in_supplier_id, CURDATE(), in_currency, 0.00);
END;
// DELIMITER ;



-- ---------------------------------------
-- Get inventory per store
-- ---------------------------------------
DELIMITER //
CREATE PROCEDURE getStoreInventory (
    IN in_store_id INT
)
BEGIN
    SELECT si.store_id, cb.bean_name, si.quantity_kg
    FROM store_inventory si
    JOIN coffee_bean cb ON si.bean_bean_id = cb.bean_id
    WHERE si.store_id = in_store_id;
END;
// DELIMITER ;



-- ---------------------------------------
-- Register a new customer + user account
-- ---------------------------------------
DELIMITER //
CREATE PROCEDURE registerCustomer (
    IN in_first_name      VARCHAR(100),
    IN in_last_name       VARCHAR(100),
    IN in_email           VARCHAR(100),
    IN in_contact_number  VARCHAR(45),
    IN in_address         VARCHAR(150),
    IN in_city_id         INT,
    IN in_username        VARCHAR(50),
    IN in_password        VARCHAR(255)
)
BEGIN
    DECLARE v_customer_id INT;

    INSERT INTO customer (first_name, last_name, email, contact_number, address, city_id)
    VALUES (in_first_name, in_last_name, in_email, in_contact_number, in_address, in_city_id);

    SET v_customer_id = LAST_INSERT_ID();

    INSERT INTO users (username, password, role, linked_customer_id, is_active, created_at)
    VALUES (in_username, in_password, 'customer', v_customer_id, 1, NOW());
END;
// DELIMITER ;



-- ---------------------------------------
-- Add a new coffee bean
-- ---------------------------------------
DELIMITER //
CREATE PROCEDURE addCoffeeBean (
    IN in_bean_name          VARCHAR(100),
    IN in_variety            VARCHAR(100),
    IN in_origin_province_id INT,
    IN in_roast_level        ENUM('Light','Medium','Dark'),
    IN in_price_per_kg       DECIMAL(10,2),
    IN in_supplier_id        INT,
    IN in_description        TEXT
)
BEGIN
    INSERT INTO coffee_bean (
        bean_name, variety, origin_province_id,
        roast_level, price_per_kg, supplier_id, description
    )
    VALUES (
        in_bean_name, in_variety, in_origin_province_id,
        in_roast_level, in_price_per_kg, in_supplier_id, in_description
    );
END;
// DELIMITER ;


-- ---------------------------------------
-- Update coffee bean price
-- ---------------------------------------
DELIMITER //
CREATE PROCEDURE updateBeanPrice (
    IN in_bean_id      INT,
    IN in_new_price    DECIMAL(10,2)
)
BEGIN
    UPDATE coffee_bean
    SET price_per_kg = in_new_price
    WHERE bean_id = in_bean_id;
END;
// DELIMITER ;