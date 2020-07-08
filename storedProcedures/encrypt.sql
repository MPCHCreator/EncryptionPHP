DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `encrypt`(IN `_table_name` VARCHAR(32), IN `_columns` TEXT, IN `_values` TEXT, IN `_key` TEXT)
    NO SQL
BEGIN 

DECLARE _next TEXT DEFAULT NULL;
DECLARE _nextlen INT DEFAULT NULL;
DECLARE _value TEXT DEFAULT NULL;
DECLARE _values_e TEXT DEFAULT NULL;
DECLARE _counter INT DEFAULT NULL;
SET _values_e = "";
SET _counter = 0;

iterator:
LOOP
     IF LENGTH(TRIM(_values)) = 0 OR _values IS NULL THEN
       LEAVE iterator;
     END IF;

     SET _next = SUBSTRING_INDEX(_values,',',1);
     SET _nextlen = LENGTH(_next);
     SET _value = TRIM(_next);
     
     IF _counter = 0 THEN
        SET _values_e = concat(_values_e ,"hex(aes_encrypt('",_next,"','",_key,"'))");
     ELSE
     	SET _values_e = concat(_values_e ,",hex(aes_encrypt('",_next,"','",_key,"'))");
     END IF;

     SET _values = INSERT(_values,1,_nextlen + 1,'');
     SET _counter = _counter + 1;
END LOOP;

set @sql = concat("INSERT INTO " , _table_name , " (" , _columns , ") VALUES (" , _values_e , ")");

PREPARE stmt FROM @sql;
EXECUTE stmt;

END$$
DELIMITER ;