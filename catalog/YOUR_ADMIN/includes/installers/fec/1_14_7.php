<?php
  $db->Execute("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '1.14.7' WHERE configuration_key = 'FAST_AND_EASY_CHECKOUT_VERSION' LIMIT 1;");
  $messageStack->add('Updated Fast and Easy Checkout to v1.14.7', 'success');
  $db->Execute("UPDATE " . TABLE_CONFIGURATION . " SET configuration_group_description = 'Allow login to customer account using master password? (Must be using ZenCart v1.5.0 or higher)' WHERE configuration_key = 'FEC_MASTER_PASSWORD'");