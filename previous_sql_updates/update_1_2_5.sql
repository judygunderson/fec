ALTER TABLE orders ADD dropdown varchar(50) NULL default NULL; 
ALTER TABLE orders ADD gift_message varchar(100) NULL default NULL after dropdown;   

SET @configuration_group_id=0;
SELECT @configuration_group_id:=configuration_group_id
FROM configuration_group
WHERE configuration_group_title= 'Fast and Easy Checkout Configuration'
LIMIT 1;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES
(NULL, 'Activate Drop Down List', 'FEC_DROP_DOWN', 'false', 'Activate drop down list to appear on checkout page?', @configuration_group_id, 99, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Activate Gift Message Field', 'FEC_GIFT_MESSAGE', 'false', 'Activate gift message field to appear on checkout page?', @configuration_group_id, 99, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Drop Down List Options', 'FEC_DROP_DOWN_LIST', 'Option 1,Option 2,Option 3,Option 4,Option 5', 'Enter each option separated by commas:', @configuration_group_id, 99, NOW(), NULL, NULL);