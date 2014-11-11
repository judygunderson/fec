<?php
if (!nmx_check_field(TABLE_ORDERS, 'dropdown')) $db->Execute("ALTER TABLE " . TABLE_ORDERS . " ADD dropdown VARCHAR(72);");
if (!nmx_check_field(TABLE_ORDERS, 'gift_message')) $db->Execute("ALTER TABLE " . TABLE_ORDERS . " ADD gift_message VARCHAR(126);");