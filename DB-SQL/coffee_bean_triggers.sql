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
END$$

DELIMITER ;