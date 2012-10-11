SET @configuration_group_id=0;
SELECT @configuration_group_id:=configuration_group_id
FROM configuration_group
WHERE configuration_group_title= 'Fast and Easy Checkout Configuration'
LIMIT 1;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES
(NULL, 'Checkout Without Account', 'FEC_NOACCOUNT_SWITCH', 'false', 'Activate Checkout Without an Account?', @configuration_group_id, 0, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Hide Email Options For No Account', 'FEC_NOACCOUNT_HIDEEMAIL', 'true', 'Hide "HTML/TEXT-Only" for checkout without account?', @configuration_group_id, 1, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Automatic LogOff for No Account', 'FEC_NOACCOUNT_LOGOFF', 'true', 'Automatically logoff customers without accounts?', @configuration_group_id, 1, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'COWOA Position', 'FEC_NOACCOUNT_POSITION', 'top', 'Display the COWOA fieldset above the registration form (top) or beneath the login (side)?', @configuration_group_id, 1, NOW(), NULL, 'zen_cfg_select_option(array(\'top\', \'side\'),'),
(NULL, 'Display Confidence Box', 'FEC_CONFIDENCE', 'false', 'Display the "Shop With Confidence" sidebox on login?', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Display Order Total', 'FEC_ORDER_TOTAL', 'false', 'Display the Order Total sidebox on login?', @configuration_group_id, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Confirm Email', 'FEC_CONFIRM_EMAIL', 'false', 'Require user to enter email twice for confirmation?', @configuration_group_id, 3, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),');