-- ---------------------------------------
-- Currency Conversion
-- ---------------------------------------
DELIMITER \\ 
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
    WHERE currency_code = from_currency_code AND converted_code = to_currency_code;
    SET newAmount = amount * rate;
END;
\\ DELIMITER ;



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
-- [DOUBLE CHECK] Add item to cart
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

 