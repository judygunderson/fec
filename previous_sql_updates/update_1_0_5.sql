SET @configuration_group_id=0;
SELECT @configuration_group_id:=configuration_group_id
FROM configuration_group
WHERE configuration_group_title= 'Fast and Easy Checkout Configuration'
LIMIT 1;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'Hide Checkout Confirmation', 'FEC_CHECKOUT_CONFIRMATION_TEXT_SWITCH', 'true', 'Display alternate text for Checkout Confirmation (if one-page checkout activated)?', @configuration_group_id, 1, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Checkout Confirmation Alternate Text', 'FEC_CHECKOUT_CONFIRMATION_TEXT', 'Your order is being processed, please wait...', 'Alternate text to be displayed on Checkout Confirmation page:', @configuration_group_id, 2, NOW(), NULL, NULL);