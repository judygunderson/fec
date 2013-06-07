<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2007 That Software Guy                                 |
// | http://www.thatsoftwareguy.com/                                      |
// |                                                                      |
// | Gift Wrap Contribution Version 2.4                                   |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// | Portions Copyright (c) 2006 The Zen Cart Developers                  |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
//

  class ot_giftwrap_checkout {
    var $title, $output;

    function ot_giftwrap_checkout() {
      $this->code = 'ot_giftwrap_checkout';
      $this->title = MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_TITLE;
      $this->description = MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_DESCRIPTION;
      $this->calculate_tax = MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_CALC_TAX;
      $this->sort_order = MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_SORT_ORDER;
      $this->include_tax = MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_INC_TAX;
      $this->fee = MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_FEE;
      $this->itemfee = MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_ITEMFEE;
      $this->freeover= MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_FREEOVER;

      $this->output = array();
    }

    function get_category_id($id) {
        // how aggravating that the order doesn't have the mcat.
        global $db;
        $product_to_categories = $db->Execute("select master_categories_id from " . TABLE_PRODUCTS . " where products_id = '" . $id . "'");
        $category = $product_to_categories->fields['master_categories_id'];
        return $category;
    }

    // Add categories you wish to not offer wrapping on to this list.
    // Go to Admin->Catalog->Categories/Products and look 
    // at the left hand side of the list to determine 
    // category id.   Note that 99999 and 99998 are just given
    // as examples.  By returning true, we exclude these categories.
    function exclude_category($prid) {
        $id = (int)$prid;
        $category = $this->get_category_id($id); 
        switch($category) {
           case 99999:
           case 99998:
                return true;
        }
        return false;
    }

    // Add products you wish to not offer wrapping for to this list.
    // Go to Admin->Catalog->Categories/Products->[Your category]
    // and look at the left hand side of the list to determine 
    // product id.   Note that 99999 and 99998 are just given
    // as examples.  By returning true, we exclude these products.
    function exclude_product($prid) {
        $id = (int)$prid;
        switch($id) {
           case 99999:
           case 99998:
                return true;
        }
        return false;
    }

    // Add categories you wish to surcharge for wrapping to this list.
    // Go to Admin->Catalog->Categories/Products and look 
    // at the left hand side of the list to determine 
    // category id.   Note that 99999 and 99998 are just given
    // as examples.  By returning 1.00, we surcharge these categories.
    function apply_category_wrap_surcharge($prid) {
        $id = (int)$prid;
        $category = $this->get_category_id($id); 
        switch($category) {
           case 99999:
           case 99998:
        }
        return 0;
    }

    // Add products you wish to surcharge for wrapping to this list.
    // Go to Admin->Catalog->Categories/Products->[Your category]
    // and look at the left hand side of the list to determine 
    // product id.   Note that 99999 and 99998 are just given
    // as examples.  By returning 1.00, we surcharge these products.
    function apply_product_wrap_surcharge($prid) {
        $id = (int)$prid;
        switch($id) {
           case 99999:
           case 99998:
                return 1.00;
        }
        return 0;
    }

    function process() {
       global $order, $currencies;

       $od_amount = $this->calculate_wrap_fee();
       // if ($od_amount['total'] > 0) {
       if ($od_amount['items_wrapped'] > 0) {
          reset($order->info['tax_groups']);
          while (list($key, $value) = each($order->info['tax_groups'])) {
             $tax_rate = zen_get_tax_rate_from_desc($key);
             if ($od_amount[$key]) {
               $order->info['tax_groups'][$key] += $od_amount[$key];
               if ($this->include_tax != 'true') {
                   $order->info['total'] +=  $od_amount[$key];
               }
             }
          }
          $order->info['total'] = $order->info['total'] + $od_amount['total'];
          $this->output[] = array('title' => $this->title . ':',
                                 'text' =>  $currencies->format($od_amount['total'], true, $order->info['currency'], $order->info['currency_value']),
                                 'value' => $od_amount['total']);
   
       }
    }
    
    /**
     * Get wrap fee for products
     * @param $products_id
     * @return wrap_fee array
     */
    function get_products_wrap_fee() {
      global $order;

       $products_wrap_fee = array();

       // Compute wrap fee by seeing which ones are wrapped
       $wrapfee = 0;
       $items_wrapped = 0; 
       for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
          $prid = $order->products[$i]['id'];
          for ($q = 1; $q <= $order->products[$i]['qty']; $q++) {
            //$wrapfee += ($this->itemfee + $this->apply_category_wrap_surcharge($prid) + $this->apply_product_wrap_surcharge($prid) );
            $products_wrap_fee[zen_get_prid($prid)] = ($this->itemfee + $this->apply_category_wrap_surcharge($prid) + $this->apply_product_wrap_surcharge($prid) );
            $items_wrapped++; 
          }
       }
       return $products_wrap_fee;
    }

    function calculate_wrap_fee() {
       global $order;

       $od_amount = array();

       // Compute wrap fee by seeing which ones are wrapped
       $wrapfee = 0;
       $items_wrapped = 0; 
       $wrapsettings = $_SESSION['wrapsettings'];
       for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
          $prid = $order->products[$i]['id'];
          for ($q = 1; $q <= $order->products[$i]['qty']; $q++) {
              if ( isset($wrapsettings[$prid][$q]) &&  
                   ($wrapsettings[$prid][$q] != 0) ) { 
                  $wrapfee += ($this->itemfee + $this->apply_category_wrap_surcharge($prid) + $this->apply_product_wrap_surcharge($prid) );
                  //echo $prid . ' - ' . $wrapfee;
                  //echo "<br />\n";
                  $items_wrapped++; 
              }
          }
       }
       if ($items_wrapped != 0) { 
          $wrapfee += $this->fee; 
       }
      
       if ($this->freeover != 0) {
          if ($order->info['total'] > $this->freeover) { 
              $wrapfee = 0; 
          }
       }

       // Gross up wrap fee if required.
       if ($this->include_tax == 'true') {
                $wrapfee = $this->gross_up($wrapfee);
       }

       $od_amount['total'] = round($wrapfee, 2); 
       $od_amount['items_wrapped'] = $items_wrapped;

       if ($wrapfee != 0 && $this->include_tax == 'true') {
          $ratio = $od_amount['total']/$this->gross_up($wrapfee);  //bug fix rounding: sky50
       } else {
          $ratio = 1;
       }

       switch ($this->calculate_tax) {
       case 'Standard':
          reset($order->info['tax_groups']);
          while (list($key, $value) = each($order->info['tax_groups']))
          {
             $tax_rate = zen_get_tax_rate_from_desc($key);
             if ($tax_rate > 0) {
                $od_amount[$key] = round((($od_amount['total'] * $tax_rate)) /100 * $ratio, 2);  //bug fix rounding: sky50
             }
          }
          break;
       }
       return $od_amount; 
    }

   function gross_up($net) {
      global $order;
      $gross_up_amt = 0; 
      reset($order->info['tax_groups']);
      while (list($key, $value) = each($order->info['tax_groups']))
      {
          $tax_rate = zen_get_tax_rate_from_desc($key);
          if ($tax_rate > 0) {
             $gross_up_amt += round((($net * $tax_rate)) /100, 2) ;
          }
      }
      return $gross_up_amt + $net; 
   }

    function check() {
      global $db;
      if (!isset($this->_check)) {
        $check_query = "select configuration_value
                        from " . TABLE_CONFIGURATION . "
                        where configuration_key = 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_STATUS'";

        $check_query = $db->Execute($check_query);
        $this->_check = $check_query->RecordCount();
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_STATUS', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_SORT_ORDER', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_INC_TAX', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_FEE', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_ITEMFEE', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_FREEOVER', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_CALC_TAX');
    }

    function install() {
      global $db;
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('&copy; That Software Guy<br /><div><a href=\"http://www.thatsoftwareguy.com/donate.html\" target=\"_blank\">Donate</a> - Support this Module</div><div><a href=\"http://www.thatsoftwareguy.com/zencart_giftwrap_checkout.html\" target=\"_blank\">Help</a> - View the Documentation</div><br />This module is installed', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_STATUS', 'true', 'Would you like to offer gift wrapping?', '6', '1','zen_cfg_select_option(array(\'true\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_SORT_ORDER', '220', 'Sort order of display.', '6', '2', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Include Tax', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_INC_TAX', 'false', 'Include Tax in calculation.', '6', '6','zen_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Gift Wrapping Flat Fee', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_FEE', '0', 'Example: 5 (for $5.00 flat rate)', '6', '3', '', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Gift Wrapping Per Item Fee', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_ITEMFEE', '0', 'Example: 2 (for $2/item)', '6', '4', '', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('Free Gift Wrapping For Orders Over', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_FREEOVER', '0', 'Provide free gift wrapping for orders over the set amount', '6', '5', '', now())");
      $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Re-calculate Tax', 'MODULE_ORDER_TOTAL_GIFTWRAP_CHECKOUT_CALC_TAX', 'Standard', 'Re-Calculate Tax', '6', '6','zen_cfg_select_option(array(\'None\', \'Standard\'), ', now())");
    }

    function remove() {
      global $db;
      $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
