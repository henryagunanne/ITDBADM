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
