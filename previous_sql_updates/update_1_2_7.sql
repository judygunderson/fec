ALTER TABLE orders ADD checkbox integer(1) NULL default NULL after gift_message;  
                                                                                      
SET @configuration_group_id=0;
SELECT @configuration_group_id:=configuration_group_id
FROM configuration_group
WHERE configuration_group_title= 'Fast and Easy Checkout Configuration'
LIMIT 1;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES
(NULL, 'Activate Checkbox Field', 'FEC_CHECKBOX', 'false', 'Activate checkbox field to appear on checkout page?', @configuration_group_id, 99, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),');