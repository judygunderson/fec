<?php

//Added ability to remove left/right columns and header/footer
$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function, configuration_tab) VALUES
('Show Left Column in Checkout', 'FEC_LEFT_COLUMN_STATUS', 'true', 'Show Left column in checkout?', " . $configuration_group_id . ", 42, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', 'Layout'),
('Show Right Column in Checkout', 'FEC_RIGHT_COLUMN_STATUS', 'true', 'Show Right column in checkout?', " . $configuration_group_id . ", 43, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', 'Layout'),
('Show Header in Checkout', 'FEC_HEADER_STATUS', 'true', 'Show Header in checkout?', " . $configuration_group_id . ", 44, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', 'Layout'),
('Show Footer in Checkout', 'FEC_FOOTER_STATUS', 'true', 'Show Footer in checkout?', " . $configuration_group_id . ", 45, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', 'Layout');" );