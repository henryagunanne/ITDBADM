-- Converts the Currency
DELIMITER \\ 
CREATE PROCEDURE convertCurrency(
				IN amount DECIMAL(8,2),
                IN currency_code VARCHAR(3),
                IN converted_code VARCHAR(3),
                OUT newAmount DECIMAL(8,2)
)
BEGIN
	DECLARE rate DECIMAL(8,2);
    SELECT conversion_rate INTO rate 
    FROM conversion_table ct
    WHERE currency_code = ct.currency_code AND converted_code = ct.converted_code;
    SET newAmount = amount * rate;
END
\\ DELIMITER ;

-- gets all the coffee beans
DELIMITER \\ 
CREATE PROCEDURE getCoffeeBeans(
				IN region VARCHAR(255)
                
)
BEGIN
	SELECT r.region_name, cb.cb_name
    FROM coffee_bean cb
		JOIN region r ON r.region_id=cb.region_id
	WHERE region = r.region_name
	GROUP BY r.region_name;
END
\\ DELIMITER ;


DELIMITER //

-- add to cart / place order
CREATE PROCEDURE PlaceCoffeeOrder(
    IN p_customer_id INT,
    IN p_product_id INT,
    IN p_quantity INT
)
BEGIN
    DECLARE v_price DECIMAL(10,2);
    DECLARE v_stock INT;
    DECLARE v_total DECIMAL(10,2);
    DECLARE v_order_id INT;

    SELECT price, stock
    INTO v_price, v_stock
    FROM products
    WHERE product_id = p_product_id
    FOR UPDATE;

    SET v_total = v_price * p_quantity;

    INSERT INTO orders (customer_id, order_date, total_amount)
    VALUES (p_customer_id, NOW(), v_total);

    SET v_order_id = LAST_INSERT_ID();

    INSERT INTO order_items (order_id, product_id, quantity, subtotal)
    VALUES (v_order_id, p_product_id, p_quantity, v_total);

    UPDATE products
    SET stock = stock - p_quantity
    WHERE product_id = p_product_id;

    COMMIT;
END //

DELIMITER ;

 