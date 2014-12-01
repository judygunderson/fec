<?php
global $sniffer;
if (!$sniffer->field_exists(TABLE_ORDERS, 'dropdown')) $db->Execute("ALTER TABLE " . TABLE_ORDERS . " ADD dropdown VARCHAR(72);");
if (!$sniffer->field_exists(TABLE_ORDERS, 'gift_message')) $db->Execute("ALTER TABLE " . TABLE_ORDERS . " ADD gift_message VARCHAR(126);");
