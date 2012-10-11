SET @configuration_group_id=0;
SELECT @configuration_group_id:=configuration_group_id
FROM configuration_group
WHERE configuration_group_title= 'Fast and Easy Checkout Configuration'
LIMIT 1;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES
(NULL, 'Shipping Address', 'FEC_SHIPPING_ADDRESS', 'false', 'Display the shipping address form on the login and COWOA pages?', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Copy Billing', 'FEC_COPYBILLING', 'false', 'If the shipping address form is enabled, should the copy billing address checkbox be checked by default?', @configuration_group_id, 11, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Master Password', 'FEC_MASTER_PASSWORD', 'false', 'Allow login to customer account using master password??', @configuration_group_id, 12, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),');