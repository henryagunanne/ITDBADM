USE cool_beans;
-- -------------------------------------
--  Coffee bean Delete log
-- -------------------------------------
DELIMITER $$

CREATE TRIGGER before_coffee_bean_delete
BEFORE DELETE ON coffee_bean
FOR EACH ROW
BEGIN
    INSERT INTO coffee_bean_delete_log (
        bean_id,
        bean_name,
        variety,
        origin_province_id,
        roast_level,
        price_per_kg,
        deleted_at
    )
    VALUES (
        OLD.bean_id,
        OLD.bean_name,
        OLD.variety,
        OLD.origin_province_id,
        OLD.roast_level,
        OLD.price_per_kg,
        NOW()
    );
END

$$ DELIMITER ;


-- -------------------------------------
--  Coffee bean Update audit log
-- -------------------------------------
DELIMITER $$

CREATE TRIGGER before_coffee_bean_update
AFTER UPDATE ON coffee_bean
FOR EACH ROW
BEGIN
    INSERT INTO coffee_bean_update_log (bean_id)
    VALUES (OLD.bean_id);
END

$$ DELIMITER ;


-- ---------------------------------------
-- Prevent negative inventory on UPDATE
-- ---------------------------------------
DELIMITER $$

CREATE TRIGGER before_inventory_update
BEFORE UPDATE ON store_inventory
FOR EACH ROW
BEGIN
    IF NEW.quantity_kg < 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Inventory cannot be negative.';
    END IF;
END 

$$ DELIMITER ;


-- ---------------------------------------
-- Prevent negative inventory on INSERT
-- ---------------------------------------
DELIMITER $$

CREATE TRIGGER before_inventory_insert
BEFORE INSERT ON store_inventory
FOR EACH ROW
BEGIN
    IF NEW.quantity_kg < 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Inventory cannot be negative.';
    END IF;
END 

$$ DELIMITER ;

-- -------------------------------------
--  Order History Log
-- -------------------------------------
DELIMITER //
CREATE TRIGGER after_payment_completed
AFTER UPDATE ON sale_payment
FOR EACH ROW
BEGIN
    IF NEW.payment_status = 'PAID' AND OLD.payment_status != 'PAID' THEN
        INSERT INTO order_history_log (
            sale_id,
            customer_id,
            store_name,
            sale_date,
            total_amount,
            currency_code,
            payment_method
        )
        SELECT
            s.sale_id,
            s.customer_id,
            st.store_name,
            s.sale_date,
            s.total_amount,
            NEW.currency_code,
            NEW.payment_method
        FROM sale s
        JOIN store st ON s.store_id = st.store_id
        WHERE s.sale_id = NEW.sale_id;
    END IF;
END
// DELIMITER ;

-- -------------------------------------
--  Prevent duplicate coffee beans (same name + origin)
-- -------------------------------------
DELIMITER //
CREATE TRIGGER before_coffee_bean_insert
BEFORE INSERT ON coffee_bean
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1
        FROM coffee_bean
        WHERE bean_name = NEW.bean_name
          AND origin_province_id = NEW.origin_province_id
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Duplicate coffee bean from same province is not allowed.';
    END IF;
END;
// DELIMITER ;

-- -------------------------------------
--  Ensures that every supplier inserted has a properly formatted email.
-- -------------------------------------
DELIMITER //
CREATE TRIGGER before_supplier_insert
BEFORE INSERT ON supplier
FOR EACH ROW
BEGIN
    IF NEW.email NOT LIKE '%@%.%' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid email format for supplier.';
    END IF;
END;
// DELIMITER ;


-- -------------------------------------
--  Auto increase inventory after restock
-- -------------------------------------
DELIMITER //
CREATE TRIGGER after_restock_items_insert
AFTER INSERT ON restock_items
FOR EACH ROW
BEGIN
    UPDATE store_inventory
    SET quantity_kg = quantity_kg + NEW.quantity
    WHERE store_id = (SELECT store_id FROM restock WHERE restock_id = NEW.restock_id)
    AND bean_id = NEW.bean_id;
END;
// DELIMITER ;



-- -------------------------------------
--  Prevent overpayment (sale)
-- -------------------------------------
DELIMITER //
CREATE TRIGGER before_sale_payment_insert
BEFORE INSERT ON sale_payment
FOR EACH ROW
BEGIN
    DECLARE total DECIMAL(18,2);

    SELECT COALESCE(total_amount, 0) INTO total
    FROM sale
    WHERE sale_id = NEW.sale_id;

    IF NEW.amount_paid > total THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Payment exceeds total sale amount.';
    END IF;
END;
// DELIMITER ;



-- -------------------------------------
--  Auto update payment status
-- -------------------------------------
DELIMITER //
CREATE TRIGGER after_sale_payment_insert
AFTER INSERT ON sale_payment
FOR EACH ROW
BEGIN
    IF NEW.amount_paid >= (
        SELECT total_amount FROM sale WHERE sale_id = NEW.sale_id
    ) THEN
        UPDATE sale_payment
        SET payment_status = 'PAID'
        WHERE payment_id = NEW.payment_id;
    END IF;
END;
// DELIMITER ;


SHOW TRIGGERS;