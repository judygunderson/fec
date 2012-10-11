ALTER TABLE address_book ADD entry_telephone varchar(50) NULL default NULL;
ALTER TABLE orders ADD shipping_telephone varchar(50) NULL default NULL;
ALTER TABLE orders ADD dropdown varchar(50) NULL default NULL; 
ALTER TABLE orders ADD gift_message varchar(100) NULL default NULL after dropdown; 
ALTER TABLE orders ADD checkbox integer(1) NULL default NULL after gift_message;
ALTER TABLE customers ADD COWOA_account tinyint(1) NOT NULL default 0;
ALTER TABLE orders ADD COWOA_order tinyint(1) NOT NULL default 0;

SET @configuration_group_id=0;
SELECT @configuration_group_id:=configuration_group_id
FROM configuration_group
WHERE configuration_group_title= 'Fast and Easy Checkout Configuration'
LIMIT 1;
DELETE FROM configuration WHERE configuration_group_id = @configuration_group_id;
DELETE FROM configuration_group WHERE configuration_group_id = @configuration_group_id;

INSERT INTO configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (NULL, 'Fast and Easy Checkout Configuration', 'Set Fast and Easy Checkout Options', '1', '1');
SET @configuration_group_id=last_insert_id();
UPDATE configuration_group SET sort_order = @configuration_group_id WHERE configuration_group_id = @configuration_group_id;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES 
(NULL, 'Version', 'FAST_AND_EASY_CHECKOUT_VERSION', '1.11.0', 'Version Installed:', @configuration_group_id, 0, NOW(), NULL, NULL),
(NULL, 'Fast and Easy Checkout', 'FEC_STATUS', 'false', 'Activate Fast and Easy Checkout? (note: Easy Sign-up and Login must be disabled separately)', @configuration_group_id, 10, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'One Page Checkout', 'FEC_ONE_PAGE', 'true', 'Activate One Page Checkout?<br />Default = false (includes checkout_confirmation page)', @configuration_group_id, 11, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Checkout Confirmation Alternate Text', 'FEC_CHECKOUT_CONFIRMATION_TEXT', 'Your order is being processed, please wait...', 'Alternate text to be displayed on Checkout Confirmation page:', @configuration_group_id, 12, NOW(), NULL, NULL),
(NULL, 'Display Checkout in Split Column', 'FEC_SPLIT_CHECKOUT', 'true', 'Display the checkout page in a split column format?', @configuration_group_id, 13, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Activate Drop Down List', 'FEC_DROP_DOWN', 'false', 'Activate drop down list to appear on checkout page?', @configuration_group_id, 14, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Gift Wrapping Module Switch', 'FEC_GIFT_WRAPPING_SWITCH', 'false', 'If the gift wrapping module is installed, set to true to activate', @configuration_group_id, 15, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Activate Gift Message Field', 'FEC_GIFT_MESSAGE', 'false', 'Activate gift message field to appear on checkout page?', @configuration_group_id, 16, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Drop Down List Options', 'FEC_DROP_DOWN_LIST', 'Option 1,Option 2,Option 3,Option 4,Option 5', 'Enter each option separated by commas:', @configuration_group_id, 17, NOW(), NULL, NULL),
(NULL, 'Activate Checkbox Field', 'FEC_CHECKBOX', 'false', 'Activate checkbox field to appear on checkout page?', @configuration_group_id, 18, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

(NULL, 'Easy Sign-Up and Login', 'FEC_EASY_SIGNUP_STATUS', 'false', 'Activate Easy Sign-Up and Login?', @configuration_group_id, 20, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Display Order Total', 'FEC_ORDER_TOTAL', 'false', 'Display the Order Total sidebox on login?', @configuration_group_id, 21, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Display Confidence Box', 'FEC_CONFIDENCE', 'false', 'Display the "Shop With Confidence" sidebox on login?', @configuration_group_id, 22, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'COWOA Position', 'FEC_NOACCOUNT_POSITION', 'side', 'Display the COWOA fieldset above the registration form (top) or beneath the login (side)?', @configuration_group_id, 23, NOW(), NULL, 'zen_cfg_select_option(array(\'top\', \'side\'),'),
(NULL, 'Confirm Email', 'FEC_CONFIRM_EMAIL', 'false', 'Require user to enter email twice for confirmation?', @configuration_group_id, 24, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Shipping Address', 'FEC_SHIPPING_ADDRESS', 'true', 'Display the shipping address form on the login and COWOA pages?', @configuration_group_id, 25, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Copy Billing', 'FEC_COPYBILLING', 'true', 'If the shipping address form is enabled, should the copy billing address checkbox be checked by default?', @configuration_group_id, 26, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Master Password', 'FEC_MASTER_PASSWORD', 'false', 'Allow login to customer account using master password??', @configuration_group_id, 27, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),

(NULL, 'Checkout Without Account', 'FEC_NOACCOUNT_SWITCH', 'true', 'Activate Checkout Without an Account?', @configuration_group_id, 30, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Checkout Without Account Only', 'FEC_NOACCOUNT_ONLY_SWITCH', 'false', 'Disable regular login/registration and force Checkout Without an Account?', @configuration_group_id, 31, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Combine COWOA Accounts', 'FEC_NOACCOUNT_COMBINE', 'false', 'Combine COWOA accounts so that COWOA customers can access their orders and other account features (note this will only work on future registrations)?', @configuration_group_id, 31, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Hide Email Options For No Account', 'FEC_NOACCOUNT_HIDEEMAIL', 'true', 'Hide "HTML/TEXT-Only" for checkout without account?', @configuration_group_id, 32, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Automatic LogOff for No Account', 'FEC_NOACCOUNT_LOGOFF', 'true', 'Automatically logoff customers without accounts?', @configuration_group_id, 33, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
(NULL, 'Free/Virtual Checkout', 'FEC_FREE_VIRTUAL_CHECKOUT', 'false', 'Only require name and email address for products that are both free and virtual?', @configuration_group_id, 34, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),');

# this next line can be skipped if it already exists in the database
INSERT IGNORE INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Telephone Number', 'ACCOUNT_TELEPHONE', 'true', 'Display telephone number field during account creation and with account information', '5', '8', 'zen_cfg_select_option(array(\'true\', \'false\'), ', now());

# Register the configuration page for Admin Access Control
INSERT IGNORE INTO admin_pages (page_key,language_key,main_page,page_params,menu_key,display_on_menu,sort_order) VALUES ('configFastandEasyCheckout','BOX_CONFIGURATION_FEC','FILENAME_CONFIGURATION',CONCAT('gID=',@configuration_group_id),'configuration','Y',@configuration_group_id);

# this next line can be skipped if it already exists in the database
INSERT IGNORE INTO query_builder ( query_id , query_category , query_name , query_description , query_string ) VALUES ( '', 'email,newsletters', 'Permanent Account Holders Only', 'Send email only to permanent account holders ', 'select customers_email_address, customers_firstname, customers_lastname from TABLE_CUSTOMERS where COWOA_account != 1 order by customers_lastname, customers_firstname, customers_email_address');
