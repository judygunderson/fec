SET @configuration_group_id=0;
SELECT @configuration_group_id:=configuration_group_id
FROM configuration_group
WHERE configuration_group_title= 'Fast and Easy Checkout Configuration'
LIMIT 1;
INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'Combine COWOA Accounts', 'FEC_NOACCOUNT_COMBINE', 'false', 'Combine COWOA accounts so that COWOA customers can access their orders and other account features (note this will only work on future registrations)?', @configuration_group_id, 31, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),');