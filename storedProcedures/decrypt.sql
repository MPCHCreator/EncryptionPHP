DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `decrypt`(IN `_table_name` VARCHAR(32), IN `_columns` TEXT, IN `_key` TEXT)
    NO SQL
BEGIN

DECLARE _next TEXT DEFAULT NULL;
DECLARE _nextlen INT DEFAULT NULL;
DECLARE _value TEXT DEFAULT NULL;
DECLARE _columns_e TEXT DEFAULT NULL;
DECLARE _counter INT DEFAULT NULL;
DECLARE _indicator TEXT DEFAULT NULL;
SET _columns_e = "";
SET _counter = 0;

iterator:
LOOP
     IF LENGTH(TRIM(_columns)) = 0 OR _columns IS NULL THEN
       LEAVE iterator;
     END IF;

     SET _next = SUBSTRING_INDEX(_columns,',',1);
     SET _nextlen = LENGTH(_next);
     SET _value = TRIM(_next);
     SET _indicator = _next;
     
     IF _counter = 0 THEN
        SET _columns_e = concat(_columns_e ,"aes_decrypt(unhex(",_next,"),'",_key,"')");
     ELSE
     	SET _columns_e = concat(_columns_e ,",aes_decrypt(unhex(",_next,"),'",_key,"')");
     END IF;

     SET _columns = INSERT(_columns,1,_nextlen + 1,'');
     SET _counter = _counter + 1;
END LOOP;

    set @sql = concat("SELECT " , _columns_e , " FROM ",  _table_name ," WHERE aes_decrypt(unhex(", _indicator ,"),'",_key,"') IS NOT NULL ");
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
END$$
DELIMITER ;