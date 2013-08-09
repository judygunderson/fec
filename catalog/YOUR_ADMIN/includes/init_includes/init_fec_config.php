<?php
  if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
  }
  // add upgrade script
  $fec_version = (defined('FAST_AND_EASY_CHECKOUT_VERSION') ? FAST_AND_EASY_CHECKOUT_VERSION : 'new');
  $current_version = '1.13.0';
  while ($fec_version != $current_version) {
    switch($fec_version) {
      case 'new':
        // perform upgrade
        if (file_exists(DIR_WS_INCLUDES . 'installers/fec/new.php')) {
          include_once(DIR_WS_INCLUDES . 'installers/fec/new.php');
          $fec_version = '1.12.0';          
        }
        break;
      case '1.11.2':
        // perform upgrade
        if (file_exists(DIR_WS_INCLUDES . 'installers/fec/1_12_0.php')) {
          include_once(DIR_WS_INCLUDES . 'installers/fec/1_12_0.php');
          $fec_version = '1.12.0';          
        }
        break;
      case '1.12.0':
        // perform upgrade
        if (file_exists(DIR_WS_INCLUDES . 'installers/fec/1_12_1.php')) {
          include_once(DIR_WS_INCLUDES . 'installers/fec/1_12_1.php');
          $fec_version = '1.12.1';          
        }
        break;
      case '1.12.1':
        // perform upgrade
        if (file_exists(DIR_WS_INCLUDES . 'installers/fec/1_12_2.php')) {
          include_once(DIR_WS_INCLUDES . 'installers/fec/1_12_2.php');
          $fec_version = '1.12.2';          
        }
        break;
      case '1.12.2':
        // perform upgrade
        if (file_exists(DIR_WS_INCLUDES . 'installers/fec/1_12_3.php')) {
          include_once(DIR_WS_INCLUDES . 'installers/fec/1_12_3.php');
          $fec_version = '1.12.3';          
        }
        break;
      case '1.12.3':
        // perform upgrade
        if (file_exists(DIR_WS_INCLUDES . 'installers/fec/1_13_0.php')) {
          include_once(DIR_WS_INCLUDES . 'installers/fec/1_13_0.php');
          $fec_version = '1.13.0';          
        }
        break;                                        
      default:
        $fec_version = $current_version;
        // break all the loops
        break 2;      
    }
  }